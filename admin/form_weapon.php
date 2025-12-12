<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false; $id_edit = ""; $nama_edit = ""; $deskripsi_edit = ""; $series_id_edit = ""; $img_edit = "";

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM weapons WHERE id='$_GET[edit]'"));
    $id_edit = $d['id']; $nama_edit = $d['nama_senjata']; $deskripsi_edit = $d['deskripsi']; $series_id_edit = $d['series_id']; $img_edit = $d['gambar'];
}

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
        mysqli_query($conn, "INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar) VALUES ('$series_id', '$nama', '$deskripsi', '$gambar')");
    }
    header("Location: manage_weapons.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Form Weapon</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link active">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>
    <div class="content">
        <div class="page-header">
            <h1 class="page-title"><?= $edit_mode ? 'EDIT WEAPON' : 'ADD NEW WEAPON'; ?></h1>
        </div>
        <div class="form-box">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                <div class="form-group"><label class="form-label">NAME</label><input type="text" name="nama_senjata" class="form-input" value="<?= $nama_edit; ?>" required></div>
                <div class="form-group"><label class="form-label">DESCRIPTION</label><textarea name="deskripsi" class="form-textarea" rows="4" required><?= $deskripsi_edit; ?></textarea></div>
                <div class="form-group">
                    <label class="form-label">GAME SERIES</label>
                    <select name="series_id" class="form-select" required>
                        <option value="">-- Choose Series --</option>
                        <?php $s_q = mysqli_query($conn, "SELECT * FROM series"); while($s = mysqli_fetch_assoc($s_q)) { ?>
                            <option value="<?= $s['id']; ?>" <?= ($series_id_edit == $s['id']) ? 'selected' : ''; ?>><?= $s['judul']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">IMAGE</label><?php if($img_edit) echo "<img src='../asset/$img_edit' width='80'><br>"; ?><input type="file" name="gambar" class="form-input" <?= $edit_mode ? '' : 'required'; ?>></div>
                <div class="form-actions"><a href="manage_weapons.php" class="btn-cancel">CANCEL</a><button type="submit" name="simpan" class="btn-save">SAVE DATA</button></div>
            </form>
        </div>
    </div>
</body>
</html>