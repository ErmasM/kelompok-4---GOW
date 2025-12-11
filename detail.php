<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}


if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: series.php");
    exit;
}


$query_series = mysqli_query($conn, "SELECT * FROM series WHERE id = '$id'");
$series = mysqli_fetch_assoc($query_series);


$query_chars = mysqli_query($conn, "SELECT * FROM characters WHERE series_id = '$id'");


$query_weapons = mysqli_query($conn, "SELECT * FROM weapons WHERE series_id = '$id'");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $series['judul']; ?> - God of War Saga</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png" alt="Logo"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php" class="active">SERIES</a>
            <a href="realms.php">REALMS</a>
            <a href="about.php">ABOUT</a>
        </div>
        <a href="logout.php" class="btn-primary" style="padding: 5px 15px; font-size:12px; text-decoration:none;">LOGOUT</a>
    </nav>

    <header class="hero-landing" style="background-image: url('asset/<?= $series['header_img']; ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><?= $series['judul']; ?></h1>
            <p class="hero-subtitle"><?= $series['tahun']; ?> | <?= $series['platform']; ?></p>
            <p style="font-family:'Lato'; color:#ddd; margin-bottom:30px; font-size:1.1rem; line-height:1.6;">
                <?= $series['deskripsi']; ?>
            </p>
            <a href="#story" class="btn-primary" style="text-decoration:none;">START THE JOURNEY</a>
        </div>
    </header>

    <section id="story" class="section-landing">
        <h2 class="section-title">The Beginning</h2>
        <div class="story-container">
            <div class="story-img-box">
                <img src="asset/<?= $series['gambar']; ?>" alt="Story Art" class="story-img">
            </div>
            <div class="story-text">
                <h3 style="color:var(--primary-red); margin-bottom:20px; font-size:1.5rem;">SINOPSIS</h3>
                <p>
                    <?= $series['deskripsi']; ?>
                    <br><br>
                    Di seri ini, Kratos menghadapi tantangan baru yang menguji tidak hanya kekuatan fisiknya, tetapi juga mentalitasnya. 
                    Dari kedalaman Hades hingga puncak Gunung Olympus (atau alam Nordik), perjalanan ini dipenuhi darah, pengkhianatan, dan penebusan.
                </p>
            </div>
        </div>
    </section>

    <section class="section-landing" style="background: #0a0a0a;">
        <h2 class="section-title">Journey Timeline</h2>
        <div class="timeline-box">
            <div class="timeline-item">
                <span class="timeline-date">CHAPTER 1</span>
                <h3 style="color:white;">The Call to Adventure</h3>
                <p style="color:#aaa; font-family:'Lato';">Perjalanan dimulai ketika ancaman baru muncul, memaksa Kratos untuk mengangkat senjatanya kembali.</p>
            </div>
            <div class="timeline-item">
                <span class="timeline-date">CHAPTER 2</span>
                <h3 style="color:white;">The First Battle</h3>
                <p style="color:#aaa; font-family:'Lato';">Pertarungan bos pertama yang epik melawan musuh utama di seri ini.</p>
            </div>
            <div class="timeline-item">
                <span class="timeline-date">CHAPTER 3</span>
                <h3 style="color:white;">The Climax</h3>
                <p style="color:#aaa; font-family:'Lato';">Puncak konflik di mana nasib dunia ditentukan.</p>
            </div>
        </div>
    </section>

    <section class="section-landing">
        <h2 class="section-title">Characters</h2>
        <div class="char-grid">
            <?php if(mysqli_num_rows($query_chars) > 0): ?>
                <?php while($char = mysqli_fetch_assoc($query_chars)) : ?>
                <div class="char-card">
                    <img src="asset/<?= $char['gambar']; ?>" alt="<?= $char['nama']; ?>" class="char-img">
                    <div class="char-name"><?= $char['nama']; ?></div>
                    <div class="char-role"><?= $char['peran']; ?></div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#666;">Data karakter belum ditambahkan untuk seri ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section-landing" style="background: #080808; text-align:center;">
        <h2 class="section-title">Realms Map</h2>
        <p style="color:#aaa; margin-bottom:30px; font-family:'Lato';">Peta dunia tempat petualangan ini berlangsung.</p>
        <a href="realms.php">
            <img src="asset/MacBook Pro 14_ - 4.jpg" alt="Map Preview" class="map-preview" title="Click to Explore Interactive Map">
        </a>
        <br><br>
        <a href="realms.php" class="btn-primary" style="text-decoration:none;">EXPLORE FULL MAP</a>
    </section>

    <section class="section-landing">
        <h2 class="section-title">Arsenal of Sparta</h2>
        <div class="weapon-container">
            <?php if(mysqli_num_rows($query_weapons) > 0): ?>
                <?php while($weapon = mysqli_fetch_assoc($query_weapons)) : ?>
                <div class="weapon-card">
                    <img src="asset/<?= $weapon['gambar']; ?>" alt="<?= $weapon['nama_senjata']; ?>" class="weapon-img">
                    <h3 style="color:var(--gold); margin-bottom:10px;"><?= $weapon['nama_senjata']; ?></h3>
                    <p style="font-family:'Lato'; color:#ccc; font-size:0.9rem;"><?= $weapon['deskripsi']; ?></p>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#666;">Data senjata belum ditambahkan.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer style="text-align:center; padding:30px; background:#000; border-top:1px solid #333; color:#666; font-family:'Lato';">
        &copy; 2025 God of War Project. Crafted for Web Programming.
    </footer>

</body>
</html>