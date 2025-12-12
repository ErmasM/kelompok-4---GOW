<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false;
$id_edit = "";
$nama_edit = "";
$deskripsi_edit = "";
$top_edit = "";
$left_edit = "";
$img_edit = "";

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama_realm']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $top = htmlspecialchars($_POST['posisi_top']);
    $left = htmlspecialchars($_POST['posisi_left']);

    if (!empty($_POST['id_edit'])) {
        $id = $_POST['id_edit'];
        $query = "UPDATE realms SET nama_realm='$nama', deskripsi='$deskripsi', posisi_top='$top', posisi_left='$left' WHERE id='$id'";
        mysqli_query($conn, $query);

        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE realms SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query = "INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES ('$nama', '$deskripsi', '$gambar', '$top', '$left')";
        mysqli_query($conn, $query);
    }
    header("Location: manage_realms.php");
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM realms WHERE id='$_GET[hapus]'");
    header("Location: manage_realms.php");
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realms WHERE id='$_GET[edit]'"));
    $id_edit = $d['id'];
    $nama_edit = $d['nama_realm'];
    $deskripsi_edit = $d['deskripsi'];
    $top_edit = $d['posisi_top'];
    $left_edit = $d['posisi_left'];
    $img_edit = $d['gambar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Realms</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="sidebar">
        <h2>GOD <span>OF</span> WAR<br><small style="font-size:0.8rem; color:#888;">ADMIN PANEL</small></h2>
        <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
        <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
        <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
        <a href="manage_realms.php" class="menu-link active">MANAGE REALMS</a>
        <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
        <a href="../logout.php" class="menu-link" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE REALMS</h1>
            <a href="#formInput" class="btn btn-add" onclick="document.getElementById('formInput').reset()">+ ADD NEW</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IMAGE</th>
                    <th>NAME</th>
                    <th>POSITION (TOP/LEFT)</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT * FROM realms ORDER BY id DESC");
                $no = 1;
                while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" style="width:50px; height:50px; object-fit:cover; border:1px solid #ccc;"></td>
                    <td><?= $row['nama_realm']; ?></td>
                    <td><?= $row['posisi_top']; ?> / <?= $row['posisi_left']; ?></td>
                    <td>
                        <a href="manage_realms.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus?')">DELETE</a>
                        <a href="manage_realms.php?edit=<?= $row['id']; ?>#formInput" class="btn btn-edit">EDIT</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br><br>

        <div class="form-container" id="formInput">
            <h2 style="color:var(--red); margin-bottom:20px;"><?= $edit_mode ? 'EDIT REALM' : 'ADD NEW REALM'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                
                <div class="form-group">
                    <label class="form-label">REALM NAME</label>
                    <input type="text" name="nama_realm" class="form-input" value="<?= $nama_edit; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">DESCRIPTION</label>
                    <textarea name="deskripsi" class="form-textarea" rows="4" required><?= $deskripsi_edit; ?></textarea>
                </div>

                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">POSITION TOP (%)</label>
                        <input type="text" name="posisi_top" class="form-input" value="<?= $top_edit; ?>" placeholder="e.g. 50%" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">POSITION LEFT (%)</label>
                        <input type="text" name="posisi_left" class="form-input" value="<?= $left_edit; ?>" placeholder="e.g. 50%" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">IMAGE (Background/Preview)</label>
                    <?php if($edit_mode): ?>
                        <img src="../asset/<?= $img_edit; ?>" width="80" style="margin-bottom:10px; display:block;">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-file" <?= $edit_mode ? '' : 'required'; ?>>
                </div>

                <div style="text-align:right;">
                    <a href="manage_realms.php" class="btn btn-cancel">CANCEL</a>
                    <button type="submit" name="simpan" class="btn btn-save">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>