<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // Redirect ke index.php (mundur 1 folder)
    exit;
}


// Inisialisasi Variabel
$id_edit = ""; $judul_edit = ""; $tahun_edit = ""; $platform_edit = ""; 
$deskripsi_edit = ""; $img_edit = "";
// Variabel Boss Default
$boss_name_edit = ""; $boss_hp_edit = 1000; $boss_img_edit = "";

// Ambil data jika tombol EDIT diklik
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM series WHERE id='$id'");
    $d = mysqli_fetch_assoc($q);
    
    $id_edit = $d['id'];
    $judul_edit = $d['judul'];
    $tahun_edit = $d['tahun'];
    $platform_edit = $d['platform'];
    $deskripsi_edit = $d['deskripsi'];
    $img_edit = $d['gambar'];
    
    // Data Boss
    $boss_name_edit = $d['boss_name'];
    $boss_hp_edit = $d['boss_hp'];
    $boss_img_edit = $d['boss_img'];
}

// Proses Simpan Perubahan
if (isset($_POST['simpan'])) {
    $id = $_POST['id_edit'];
    $judul = htmlspecialchars($_POST['judul']);
    $tahun = htmlspecialchars($_POST['tahun']);
    $platform = htmlspecialchars($_POST['platform']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    
    // Data Boss Baru
    $boss_name = htmlspecialchars($_POST['boss_name']);
    $boss_hp = $_POST['boss_hp'];

    // Update Data Teks
    $query = "UPDATE series SET 
              judul='$judul', tahun='$tahun', platform='$platform', deskripsi='$deskripsi',
              boss_name='$boss_name', boss_hp='$boss_hp'
              WHERE id='$id'";
    
    mysqli_query($conn, $query);

    // Update Gambar Cover
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        mysqli_query($conn, "UPDATE series SET gambar='$gambar' WHERE id='$id'");
    }

    // Update Gambar Boss
    if (!empty($_FILES['boss_img']['name'])) {
        $boss_img = $_FILES['boss_img']['name'];
        move_uploaded_file($_FILES['boss_img']['tmp_name'], "../asset/" . $boss_img);
        mysqli_query($conn, "UPDATE series SET boss_img='$boss_img' WHERE id='$id'");
    }

    echo "<script>alert('Data Series & Boss Berhasil Diupdate!'); window.location='manage_series.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Series & Boss</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_series.php" class="menu-link active">MANAGE SERIES</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">EDIT SERIES & BOSS</h1>
        </div>

        <div class="form-box">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">
                
                <h3 style="color: #b30000; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">GAME INFO</h3>
                <div class="form-group"><label class="form-label">JUDUL GAME</label><input type="text" name="judul" class="form-input" value="<?= $judul_edit; ?>" required></div>
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">TAHUN</label><input type="text" name="tahun" class="form-input" value="<?= $tahun_edit; ?>" required></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">PLATFORM</label><input type="text" name="platform" class="form-input" value="<?= $platform_edit; ?>" required></div>
                </div>
                <div class="form-group"><label class="form-label">THE BEGINNING (DESKRIPSI)</label><textarea name="deskripsi" class="form-textarea" rows="5" required><?= $deskripsi_edit; ?></textarea></div>
                <div class="form-group"><label class="form-label">COVER IMAGE</label><?php if($img_edit) echo "<img src='../asset/$img_edit' width='80' style='margin-bottom:10px;'>"; ?><input type="file" name="gambar" class="form-input"></div>

                <h3 style="color: #b30000; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin: 30px 0 20px;">BOSS BATTLE CONFIG</h3>
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex:2;"><label class="form-label">NAMA BOSS</label><input type="text" name="boss_name" class="form-input" value="<?= $boss_name_edit; ?>" placeholder="Contoh: ZEUS"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">HP BOSS</label><input type="number" name="boss_hp" class="form-input" value="<?= $boss_hp_edit; ?>"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">FOTO BOSS (TRANSPARAN LEBIH BAGUS)</label>
                    <?php if($boss_img_edit): ?>
                        <div style="margin-bottom:10px;"><img src="../asset/<?= $boss_img_edit; ?>" width="80" style="border:1px solid #ccc; background:#000;"></div>
                    <?php endif; ?>
                    <input type="file" name="boss_img" class="form-input">
                </div>

                <div class="form-actions">
                    <a href="manage_series.php" class="btn-cancel">BATAL</a>
                    <button type="submit" name="simpan" class="btn-save">SIMPAN SEMUA</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>