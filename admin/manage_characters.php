<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false;
$id_edit = "";
$nama_edit = "";
$peran_edit = "";
$series_id_edit = "";
$img_edit = "";

// LOGIKA CREATE & UPDATE
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $peran = htmlspecialchars($_POST['peran']);
    $series_id = $_POST['series_id'];
    
    // Cek apakah ini update atau insert baru
    if (!empty($_POST['id_edit'])) {
        // UPDATE
        $id = $_POST['id_edit'];
        $query_update = "UPDATE characters SET nama='$nama', peran='$peran', series_id='$series_id' WHERE id='$id'";
        mysqli_query($conn, $query_update);

        // Jika ada gambar baru
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE characters SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        // INSERT
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query_insert = "INSERT INTO characters (series_id, nama, peran, gambar) VALUES ('$series_id', '$nama', '$peran', '$gambar')";
        mysqli_query($conn, $query_insert);
    }
    header("Location: manage_characters.php");
}

// LOGIKA DELETE
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM characters WHERE id='$id'");
    header("Location: manage_characters.php");
}

// LOGIKA EDIT (AMBIL DATA)
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM characters WHERE id='$id_edit'");
    $d = mysqli_fetch_assoc($res);
    $nama_edit = $d['nama'];
    $peran_edit = $d['peran'];
    $series_id_edit = $d['series_id'];
    $img_edit = $d['gambar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Characters</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <h2>GOD <span>OF</span> WAR<br><small style="font-size:0.8rem; color:#888;">ADMIN PANEL</small></h2>
        <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
        <a href="manage_characters.php" class="menu-link active">MANAGE CHARACTERS</a>
        <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
        <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
        <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
        <a href="../logout.php" class="menu-link" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE CHARACTERS</h1>
            <a href="#formInput" class="btn btn-add" onclick="document.getElementById('formInput').reset()">+ ADD NEW</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IMAGE</th>
                    <th>NAME</th>
                    <th>ROLE</th>
                    <th>SERIES</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT characters.*, series.judul FROM characters JOIN series ON characters.series_id = series.id ORDER BY characters.id DESC");
                $no = 1;
                while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" style="width:50px; height:50px; object-fit:cover; border:1px solid #ccc;"></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['peran']; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td>
                        <a href="manage_characters.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus?')">DELETE</a>
                        <a href="manage_characters.php?edit=<?= $row['id']; ?>#formInput" class="btn btn-edit">EDIT</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br><br>

        <div class="form-container" id="formInput">
            <h2 style="color:var(--red); margin-bottom:20px;"><?= $edit_mode ? 'EDIT CHARACTER' : 'ADD NEW CHARACTER'; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                
                <div class="form-group">
                    <label class="form-label">NAME</label>
                    <input type="text" name="nama" class="form-input" value="<?= $nama_edit; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">ROLE</label>
                    <input type="text" name="peran" class="form-input" value="<?= $peran_edit; ?>" required>
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
                        <small>Biarkan kosong jika tidak ingin mengganti gambar.</small>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-file" <?= $edit_mode ? '' : 'required'; ?>>
                </div>

                <div style="text-align:right;">
                    <a href="manage_characters.php" class="btn btn-cancel">CANCEL</a>
                    <button type="submit" name="simpan" class="btn btn-save">SAVE</button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>