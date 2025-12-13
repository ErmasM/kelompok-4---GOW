<?php
session_start();
include 'koneksi.php'; // Koneksi Database Ezra

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Ambil data series dari database Ezra untuk ditampilkan di card Ermas
$query = mysqli_query($conn, "SELECT * FROM series ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God of War - Series Selection</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Frontend Ermas (Fixed Scroll) */
        * { margin: 0; padding: 0; box-sizing: border-box; user-select: none; }
        
        body { 
            font-family: 'Cinzel', serif; 
            color: white; 
            background-color: #000; 
            
            /* --- PERBAIKAN SCROLL --- */
            /* Sebelumnya overflow: hidden; membuat tidak bisa scroll */
            overflow-x: hidden; 
            overflow-y: auto; 
            
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        .bg-container { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -10; }
        .bg-image { width: 100%; height: 100%; object-fit: cover; background-image: url('asset/GOWRG_Wallpapaper_KeyArt_Background_Desktop_4k.jpg'); animation: breatheEffect 25s infinite alternate ease-in-out; filter: brightness(0.4); }
        @keyframes breatheEffect { 0% { transform: scale(1); } 100% { transform: scale(1.15); } }
        
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; position: relative; z-index: 50; background: linear-gradient(to bottom, rgba(0,0,0,0.9), transparent); }
        .nav-logo img { height: 50px; }
        .nav-links a { text-decoration: none; color: #ccc; margin: 0 8px; letter-spacing: 2px; font-size: 14px; padding: 8px 18px; background: rgba(0, 0, 0, 0.5); border-radius: 25px; transition: .3s; }
        .nav-links a:hover, .nav-links a.active { color: white; border: 1px solid #cfa35e; box-shadow: 0 0 15px rgba(207,163,94,.4); }
        
        .main-content { 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            position: relative; 
            padding: 20px 0; /* Tambah padding agar tidak mepet atas bawah saat di scroll */
        }
        
        .page-title { text-align: center; font-size: 2.5rem; margin-bottom: 10px; color: #cfa35e; letter-spacing: 8px; text-transform: uppercase; text-shadow: 0 0 20px rgba(207,163,94,0.4); animation: slideDown 1s ease-out; }
        
        /* Ubah tinggi slider agar menyesuaikan konten, bukan fixed */
        .slider-wrapper { 
            position: relative; 
            width: 100%; 
            min-height: 550px; /* Gunakan min-height */
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        
        .scroll-container { display: flex; gap: 40px; padding: 0 80px; overflow-x: auto; scroll-behavior: smooth; width: 100%; height: 100%; align-items: center; scrollbar-width: none; }
        .scroll-container::-webkit-scrollbar { display: none; }
        
        .card { flex: 0 0 auto; width: 280px; height: 460px; background: rgba(30, 30, 30, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 15px; display: flex; flex-direction: column; align-items: center; transition: all 0.4s; position: relative; overflow: hidden; opacity: 0; animation: fadeInUp 0.8s forwards; }
        .card:hover { transform: translateY(-20px) scale(1.02); background: rgba(40, 40, 40, 0.85); border-color: #cfa35e; box-shadow: 0 0 30px rgba(207, 163, 94, 0.3); z-index: 10; }
        .card-image-container { width: 100%; height: 280px; overflow: hidden; border-radius: 6px; margin-bottom: 15px; }
        .card-img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
        .card:hover .card-img { transform: scale(1.15); }
        .card-title { font-size: 1rem; text-transform: uppercase; margin-bottom: 5px; color: #fff; text-align: center; font-weight: 700; min-height: 40px; display: flex; align-items: center; justify-content: center; }
        .card-info { font-family: 'Lato', sans-serif; font-size: 0.8rem; color: #aaa; margin-bottom: 15px; }
        
        /* Tombol Teleport */
        .btn-teleport { background: linear-gradient(45deg, #8b0000, #b30000); color: white; width: 100%; border: none; padding: 12px 0; font-family: 'Cinzel', serif; font-weight: bold; cursor: pointer; margin-top: auto; clip-path: polygon(10px 0, 100% 0, 100% 100%, 0% 100%, 0% 10px); transition: 0.3s; }
        .btn-teleport:hover { background: #ff0000; box-shadow: 0 0 20px rgba(255, 0, 0, 0.6); }
        
        .nav-arrow { position: absolute; width: 50px; height: 50px; background: rgba(0,0,0,0.6); border: 1px solid #cfa35e; color: #cfa35e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; cursor: pointer; z-index: 30; transition: 0.3s; }
        .nav-arrow:hover { background: #cfa35e; color: black; }
        #prevBtn { left: 30px; } #nextBtn { right: 30px; }
        #portal-overlay { position: fixed; inset: 0; background: white; z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.8s ease-in; }
        #portal-overlay.active { opacity: 1; pointer-events: all; }
        @keyframes slideDown { from { transform: translateY(-40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>
    <div class="bg-container"><div class="bg-image"></div></div>
    <div id="portal-overlay"></div>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png" alt="Logo"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php" class="active">SERIES</a>
            <a href="about.php">ABOUT</a>
        </div>
        <a href="logout.php" style="color:#ff3b3b; font-size:12px; font-weight:bold;">LOGOUT</a>
    </nav>

    <div class="main-content">
        <h1 class="page-title">CHOOSE YOUR SAGA</h1>
        <div class="slider-wrapper">
            <button id="prevBtn" class="nav-arrow">❮</button>
            <div class="scroll-container" id="slider">
                
                <?php $delay = 0.1; while($row = mysqli_fetch_assoc($query)) : ?>
                <div class="card" style="animation-delay: <?= $delay; ?>s;">
                    <div class="card-image-container">
                        <img src="asset/<?= $row['gambar']; ?>" class="card-img" alt="<?= $row['judul']; ?>">
                    </div>
                    <h2 class="card-title"><?= $row['judul']; ?></h2>
                    <p class="card-info"><?= $row['tahun']; ?> - <?= $row['platform']; ?></p>
                    <button class="btn-teleport" onclick="openPortal('detail.php?id=<?= $row['id']; ?>')">TELEPORT</button>
                </div>
                <?php $delay += 0.1; endwhile; ?>

            </div>
            <button id="nextBtn" class="nav-arrow">❯</button>
        </div>
    </div>

    <script>
        const slider = document.getElementById("slider");
        document.getElementById("nextBtn").onclick = () => slider.scrollBy({ left: 320, behavior: "smooth" });
        document.getElementById("prevBtn").onclick = () => slider.scrollBy({ left: -320, behavior: "smooth" });

        function openPortal(url) {
            const overlay = document.getElementById('portal-overlay');
            const btn = event.target;
            btn.innerHTML = "WARPING...";
            btn.style.background = "white"; btn.style.color = "black";
            overlay.classList.add('active');
            setTimeout(() => { window.location.href = url; }, 800);
        }
    </script>
</body>
</html>