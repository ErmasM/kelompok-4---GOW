<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

$edit_mode = false; 
$id_edit = ""; $nama_edit = ""; $deskripsi_edit = ""; $series_id_edit = ""; $img_edit = "";
// Default stats
$dmg_edit = 50; $spd_edit = 50; $rng_edit = 50; $cc_edit = 50;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM weapons WHERE id='$id'"));
    $id_edit = $d['id']; $nama_edit = $d['nama_senjata']; $deskripsi_edit = $d['deskripsi']; 
    $series_id_edit = $d['series_id']; $img_edit = $d['gambar'];
    // Ambil stats dari DB
    $dmg_edit = $d['stat_damage']; $spd_edit = $d['stat_speed']; 
    $rng_edit = $d['stat_range']; $cc_edit = $d['stat_cc'];
}

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama_senjata']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $series_id = $_POST['series_id'];
    
    // Ambil data stats
    $dmg = $_POST['stat_damage']; $spd = $_POST['stat_speed']; 
    $rng = $_POST['stat_range']; $cc = $_POST['stat_cc'];

    if (!empty($_POST['id_edit'])) {
        $id = $_POST['id_edit'];
        $query = "UPDATE weapons SET 
                  nama_senjata='$nama', deskripsi='$deskripsi', series_id='$series_id',
                  stat_damage='$dmg', stat_speed='$spd', stat_range='$rng', stat_cc='$cc' 
                  WHERE id='$id'";
        mysqli_query($conn, $query);
        
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE weapons SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        $query = "INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar, stat_damage, stat_speed, stat_range, stat_cc) 
                  VALUES ('$series_id', '$nama', '$deskripsi', '$gambar', '$dmg', '$spd', '$rng', '$cc')";
        mysqli_query($conn, $query);
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
                <div class="form-group"><label class="form-label">DESCRIPTION</label><textarea name="deskripsi" class="form-textarea" rows="3" required><?= $deskripsi_edit; ?></textarea></div>
                <div class="form-group"><label class="form-label">GAME SERIES</label>
                    <select name="series_id" class="form-select" required>
                        <option value="">-- Choose Series --</option>
                        <?php $s_q = mysqli_query($conn, "SELECT * FROM series"); while($s = mysqli_fetch_assoc($s_q)) { ?>
                            <option value="<?= $s['id']; ?>" <?= ($series_id_edit == $s['id']) ? 'selected' : ''; ?>><?= $s['judul']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; background:#f9f9f9; padding:15px; border:1px solid #ddd; margin-bottom:20px;">
                    <div class="form-group">
                        <label class="form-label">DAMAGE (0-100)</label>
                        <input type="range" name="stat_damage" min="0" max="100" value="<?= $dmg_edit; ?>" oninput="this.nextElementSibling.value = this.value" style="width:80%;">
                        <output><?= $dmg_edit; ?></output>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SPEED (0-100)</label>
                        <input type="range" name="stat_speed" min="0" max="100" value="<?= $spd_edit; ?>" oninput="this.nextElementSibling.value = this.value" style="width:80%;">
                        <output><?= $spd_edit; ?></output>
                    </div>
                    <div class="form-group">
                        <label class="form-label">RANGE (0-100)</label>
                        <input type="range" name="stat_range" min="0" max="100" value="<?= $rng_edit; ?>" oninput="this.nextElementSibling.value = this.value" style="width:80%;">
                        <output><?= $rng_edit; ?></output>
                    </div>
                    <div class="form-group">
                        <label class="form-label">CROWD CONTROL (0-100)</label>
                        <input type="range" name="stat_cc" min="0" max="100" value="<?= $cc_edit; ?>" oninput="this.nextElementSibling.value = this.value" style="width:80%;">
                        <output><?= $cc_edit; ?></output>
                    </div>
                </div>

                <div class="form-group"><label class="form-label">IMAGE</label><?php if($img_edit) echo "<img src='../asset/$img_edit' width='80'><br>"; ?><input type="file" name="gambar" class="form-input" <?= $edit_mode ? '' : 'required'; ?>></div>
                <div class="form-actions"><a href="manage_weapons.php" class="btn-cancel">CANCEL</a><button type="submit" name="simpan" class="btn-save">SAVE DATA</button></div>
            </form>
        </div>
    </div>
</body>
</html>