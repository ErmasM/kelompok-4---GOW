<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // Redirect ke index.php (mundur 1 folder)
    exit;
}


$edit_mode = false;
$id_edit = ""; $nama_edit = ""; $peran_edit = ""; $series_id_edit = ""; $img_edit = "";

// Cek jika mode Edit
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

// Proses Simpan
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $peran = htmlspecialchars($_POST['peran']);
    $series_id = $_POST['series_id'];
    
    if (!empty($_POST['id_edit'])) {
        // UPDATE
        $id = $_POST['id_edit'];
        $query = "UPDATE characters SET nama='$nama', peran='$peran', series_id='$series_id' WHERE id='$id'";
        mysqli_query($conn, $query);
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE characters SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        // INSERT
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query = "INSERT INTO characters (series_id, nama, peran, gambar) VALUES ('$series_id', '$nama', '$peran', '$gambar')";
        mysqli_query($conn, $query);
    }
    header("Location: manage_characters.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Form Character</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_characters.php" class="menu-link active">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title"><?= $edit_mode ? 'EDIT CHARACTER' : 'ADD NEW CHARACTER'; ?></h1>
        </div>

        <div class="form-box">
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
                    <?php if($edit_mode && $img_edit): ?>
                        <img src="../asset/<?= $img_edit; ?>" width="100" style="display:block; margin-bottom:10px; border:1px solid #ccc;">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-input" <?= $edit_mode ? '' : 'required'; ?>>
                </div>

                <div class="form-actions">
                    <a href="manage_characters.php" class="btn-cancel">CANCEL</a>
                    <button type="submit" name="simpan" class="btn-save">SAVE DATA</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>