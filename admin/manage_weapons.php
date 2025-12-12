<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false;
$id_edit = "";
$nama_edit = "";
$deskripsi_edit = "";
$series_id_edit = "";
$img_edit = "";

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama_senjata']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $series_id = $_POST['series_id'];

    if (!empty($_POST['id_edit'])) {
        $id = $_POST['id_edit'];
        $query = "UPDATE weapons SET nama_senjata='$nama', deskripsi='$deskripsi', series_id='$series_id' WHERE id='$id'";
        mysqli_query($conn, $query);

        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE weapons SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query = "INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar) VALUES ('$series_id', '$nama', '$deskripsi', '$gambar')";
        mysqli_query($conn, $query);
    }
    header("Location: manage_weapons.php");
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM weapons WHERE id='$_GET[hapus]'");
    header("Location: manage_weapons.php");
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM weapons WHERE id='$_GET[edit]'"));
    $id_edit = $d['id'];
    $nama_edit = $d['nama_senjata'];
    $deskripsi_edit = $d['deskripsi'];
    $series_id_edit = $d['series_id'];
    $img_edit = $d['gambar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Weapons</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="sidebar">
        <h2>GOD <span>OF</span> WAR<br><small style="font-size:0.8rem; color:#888;">ADMIN PANEL</small></h2>
        <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
        <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
        <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
        <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
        <a href="manage_weapons.php" class="menu-link active">MANAGE WEAPONS</a>
        <a href="../logout.php" class="menu-link" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE WEAPONS</h1>
            <a href="#formInput" class="btn btn-add" onclick="document.getElementById('formInput').reset()">+ ADD NEW</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IMAGE</th>
                    <th>WEAPON NAME</th>
                    <th>DESCRIPTION (SHORT)</th>
                    <th>SERIES</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT weapons.*, series.judul FROM weapons JOIN series ON weapons.series_id = series.id ORDER BY weapons.id DESC");
                $no = 1;
                while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" style="width:50px; height:50px; object-fit:contain; border:1px solid #ccc; background:#333;"></td>
                    <td><?= $row['nama_senjata']; ?></td>
                    <td><?= substr($row['deskripsi'], 0, 50) . '...'; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td>
                        <a href="manage_weapons.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus?')">DELETE</a>
                        <a href="manage_weapons.php?edit=<?= $row['id']; ?>#formInput" class="btn btn-edit">EDIT</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br><br>

        <div class="form-container" id="formInput">
            <h2 style="color:var(--red); margin-bottom:20px;"><?= $edit_mode ? 'EDIT WEAPON' : 'ADD NEW WEAPON'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                
                <div class="form-group">
                    <label class="form-label">WEAPON NAME</label>
                    <input type="text" name="nama_senjata" class="form-input" value="<?= $nama_edit; ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">DESCRIPTION</label>
                    <textarea name="deskripsi" class="form-textarea" rows="4" required><?= $deskripsi_edit; ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">GAME SERIES</label>
                    <select name="series_id" class="form-select" required>
                        <option value="">-- Choose Series --</option>
                        <?php 
                        $s_query = mysqli_query($conn, "SELECT * FROM series");
                        while($s = mysqli_fetch_assoc($s_query)):
                        ?>
                        <option value="<?= $s['id']; ?>" <?= ($series_id_edit == $s['id']) ? 'selected' : ''; ?>><?= $s['judul']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">IMAGE</label>
                    <?php if($edit_mode): ?>
                        <img src="../asset/<?= $img_edit; ?>" width="80" style="margin-bottom:10px; display:block;">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-file" <?= $edit_mode ? '' : 'required'; ?>>
                </div>

                <div style="text-align:right;">
                    <a href="manage_weapons.php" class="btn btn-cancel">CANCEL</a>
                    <button type="submit" name="simpan" class="btn btn-save">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>