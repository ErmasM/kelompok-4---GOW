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
        <a href="logout.php" class="btn-primary" style="padding: 8px 20px; font-size:12px; text-decoration:none;">LOGOUT</a>
    </nav>

    <div class="map-wrapper">
        <h1 style="position: absolute; top: 20px; color: var(--gold); z-index: 5;">CHOOSE A REALM</h1>
        
        <div style="position: relative; display: inline-block;">
            <img src="asset/MacBook Pro 14_ - 4.jpg" alt="Map" class="map-image">

            <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <div class="realm-point" 
                     style="top: <?= $row['posisi_top']; ?>; left: <?= $row['posisi_left']; ?>;"
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