<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false;
$id_edit = ""; $judul_edit = ""; $deskripsi_edit = ""; $urutan_edit = ""; $series_id_edit = ""; $img_edit = "";

if (isset($_POST['simpan'])) {
    $judul = htmlspecialchars($_POST['judul_chapter']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $urutan = $_POST['urutan'];
    $series_id = $_POST['series_id'];

    if (!empty($_POST['id_edit'])) {
        $id = $_POST['id_edit'];
        $query = "UPDATE timeline SET judul_chapter='$judul', deskripsi='$deskripsi', urutan='$urutan', series_id='$series_id' WHERE id='$id'";
        mysqli_query($conn, $query);
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE timeline SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query = "INSERT INTO timeline (series_id, judul_chapter, deskripsi, urutan, gambar) VALUES ('$series_id', '$judul', '$deskripsi', '$urutan', '$gambar')";
        mysqli_query($conn, $query);
    }
    header("Location: manage_story.php");
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM timeline WHERE id='$_GET[hapus]'");
    header("Location: manage_story.php");
}

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM timeline WHERE id='$_GET[edit]'"));
    $id_edit = $d['id']; $judul_edit = $d['judul_chapter']; $deskripsi_edit = $d['deskripsi']; $urutan_edit = $d['urutan']; $series_id_edit = $d['series_id']; $img_edit = $d['gambar'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head><title>Manage Story</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="logo-area"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <a href="admin_dashboard.php" class="menu-item">DASHBOARD</a>
        <a href="manage_characters.php" class="menu-item">CHARACTERS</a>
        <a href="manage_story.php" class="menu-item active">STORY TIMELINE</a>
        <a href="manage_realms.php" class="menu-item">REALMS</a>
        <a href="manage_weapons.php" class="menu-item">WEAPONS</a>
        <a href="../logout.php" class="menu-item" style="background:#333; margin-top:50px; color:white;">LOGOUT</a>
    </div>

    <div class="content">
        <div class="page-title">
            <span>MANAGE STORY TIMELINE</span>
            <a href="#formInput" class="btn-add" onclick="document.getElementById('formInput').reset()">+ ADD NEW</a>
        </div>

        <table>
            <thead><tr><th>IMG</th><th>TITLE</th><th>ORDER</th><th>SERIES</th><th>ACTION</th></tr></thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT timeline.*, series.judul FROM timeline JOIN series ON timeline.series_id = series.id ORDER BY series.id ASC, timeline.urutan ASC");
                while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><img src="../asset/<?= $row['gambar']; ?>" width="60"></td>
                    <td><?= $row['judul_chapter']; ?></td>
                    <td><?= $row['urutan']; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td>
                        <a href="manage_story.php?edit=<?= $row['id']; ?>#formInput" class="btn-action btn-edit">EDIT</a>
                        <a href="manage_story.php?hapus=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Hapus?')">DEL</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="form-box" id="formInput">
            <h3 style="margin-bottom:20px; font-family:'Cinzel';"><?= $edit_mode ? 'EDIT CHAPTER' : 'ADD NEW CHAPTER'; ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                <div class="form-group"><label class="form-label">CHAPTER TITLE</label><input type="text" name="judul_chapter" class="form-input" value="<?= $judul_edit; ?>" required></div>
                <div class="form-group"><label class="form-label">DESCRIPTION</label><textarea name="deskripsi" class="form-input" rows="4" required><?= $deskripsi_edit; ?></textarea></div>
                <div class="form-group"><label class="form-label">TIMELINE ORDER (NUMBER)</label><input type="number" name="urutan" class="form-input" value="<?= $urutan_edit; ?>" required></div>
                <div class="form-group"><label class="form-label">GAME SERIES</label>
                    <select name="series_id" class="form-input" required>
                        <option value="">-- Select Series --</option>
                        <?php $s_q = mysqli_query($conn, "SELECT * FROM series"); while($s = mysqli_fetch_assoc($s_q)) { ?>
                        <option value="<?= $s['id']; ?>" <?= ($series_id_edit == $s['id']) ? 'selected' : ''; ?>><?= $s['judul']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">IMAGE</label>
                    <?php if($img_edit) echo "<img src='../asset/$img_edit' width='100'><br>"; ?>
                    <input type="file" name="gambar" class="form-file" <?= $edit_mode ? '' : 'required'; ?>>
                </div>
                <button type="submit" name="simpan" class="btn-add" style="width:100%;">SAVE DATA</button>
            </form>
        </div>
    </div>
</body>
</html>