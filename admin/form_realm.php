<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false; $id_edit = ""; $nama_edit = ""; $deskripsi_edit = ""; $top_edit = ""; $left_edit = ""; $img_edit = "";

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realms WHERE id='$_GET[edit]'"));
    $id_edit = $d['id']; $nama_edit = $d['nama_realm']; $deskripsi_edit = $d['deskripsi']; $top_edit = $d['posisi_top']; $left_edit = $d['posisi_left']; $img_edit = $d['gambar'];
}

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
        mysqli_query($conn, "INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES ('$nama', '$deskripsi', '$gambar', '$top', '$left')");
    }
    header("Location: manage_realms.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Form Realm</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link active">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>
    <div class="content">
        <div class="page-header">
            <h1 class="page-title"><?= $edit_mode ? 'EDIT REALM' : 'ADD NEW REALM'; ?></h1>
        </div>
        <div class="form-box">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                <div class="form-group"><label class="form-label">NAME</label><input type="text" name="nama_realm" class="form-input" value="<?= $nama_edit; ?>" required></div>
                <div class="form-group"><label class="form-label">DESCRIPTION</label><textarea name="deskripsi" class="form-textarea" rows="4" required><?= $deskripsi_edit; ?></textarea></div>
                <div style="display:flex; gap:20px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">POS TOP (%)</label><input type="text" name="posisi_top" class="form-input" value="<?= $top_edit; ?>" required></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">POS LEFT (%)</label><input type="text" name="posisi_left" class="form-input" value="<?= $left_edit; ?>" required></div>
                </div>
                <div class="form-group"><label class="form-label">IMAGE</label><?php if($img_edit) echo "<img src='../asset/$img_edit' width='80'><br>"; ?><input type="file" name="gambar" class="form-input" <?= $edit_mode ? '' : 'required'; ?>></div>
                <div class="form-actions"><a href="manage_realms.php" class="btn-cancel">CANCEL</a><button type="submit" name="simpan" class="btn-save">SAVE DATA</button></div>
            </form>
        </div>
    </div>
</body>
</html>