<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM realms");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Realms Map - God of War</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="realms.php" class="active">REALMS</a>
            <a href="about.php">ABOUT</a>
        </div>
        <a href="logout.php" class="btn-primary" style="padding: 8px 20px; font-size:12px;">LOGOUT</a>
    </nav>

    <div style="position: relative; text-align: center; padding: 50px; background: #000;">
        <h1 style="color: var(--gold); margin-bottom: 20px;">CHOOSE A REALM</h1>
        
        <div style="position: relative; display: inline-block;">
            <img src="asset/GOWRG_Wallpaper_Desktop_Vista_4k.jpg" alt="Map" style="max-width: 90%; border: 2px solid var(--gold); opacity: 0.8;">

            <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <div class="realm-point" 
                     style="position: absolute; width: 20px; height: 20px; background: red; border-radius: 50%; border: 2px solid white; cursor: pointer; top: <?= $row['posisi_top']; ?>; left: <?= $row['posisi_left']; ?>;"
                     onclick="openModal('<?= $row['nama_realm']; ?>', '<?= addslashes($row['deskripsi']); ?>', 'asset/<?= $row['gambar']; ?>')">
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="modal-overlay" id="myModal">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="mTitle" style="color: var(--gold); text-transform:uppercase; margin-bottom:10px;"></h2>
            <img id="mImg" src="" class="modal-img">
            <p id="mDesc" style="font-family:'Lato'; color:#ccc; line-height:1.6;"></p>
        </div>
    </div>

    <script>
        const modal = document.getElementById('myModal');
        const mTitle = document.getElementById('mTitle');
        const mDesc = document.getElementById('mDesc');
        const mImg = document.getElementById('mImg');

        function openModal(title, desc, img) {
            mTitle.innerText = title;
            mDesc.innerText = desc;
            mImg.src = img;
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(e) {
            if (e.target == modal) closeModal();
        }
    </script>
</body>
</html>