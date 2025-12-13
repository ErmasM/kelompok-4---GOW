<?php
session_start();
include 'koneksi.php';

// 1. CEK LOGIN
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// 2. CEK ID SERIES
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: series.php");
    exit;
}

// 3. FETCH DATA DARI DATABASE (BACKEND EZRA)
$series = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM series WHERE id = '$id'"));
$timeline = mysqli_query($conn, "SELECT * FROM timeline WHERE series_id = '$id' ORDER BY urutan ASC");
$characters = mysqli_query($conn, "SELECT * FROM characters WHERE series_id = '$id'");
$weapons = mysqli_query($conn, "SELECT * FROM weapons WHERE series_id = '$id'");
$realms = mysqli_query($conn, "SELECT * FROM realms"); 

// 4. DATA BOSS BATTLE (DARI DATABASE)
// Mengambil data boss yang sudah diinput lewat Admin Panel > Manage Series
$boss_name = !empty($series['boss_name']) ? $series['boss_name'] : "UNKNOWN GOD";
$boss_hp   = !empty($series['boss_hp'])   ? $series['boss_hp']   : 1000;
$boss_img  = !empty($series['boss_img'])  ? $series['boss_img']  : "logo.png";

// Warna Tema (Merah Yunani / Biru Nordik)
$theme_color = ($id >= 4) ? '#00bfff' : '#b30000';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $series['judul']; ?> - Detail</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & VARIABLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --gold: #cfa35e;
            --red: <?= $theme_color; ?>; /* Warna dinamis sesuai seri */
            --dark-bg: #050505;
            
            /* Stat Colors */
            --stat-dmg: #ff3b3b;
            --stat-spd: #ffd700;
            --stat-rng: #00bfff;
            --stat-cc: #9932cc;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cinzel', serif;
            background-color: var(--dark-bg);
            color: #ccc;
            overflow-x: hidden;
        }

        /* --- 2. NAVBAR --- */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px; position: fixed; width: 100%; top: 0; z-index: 1000;
            background: linear-gradient(to bottom, rgba(0,0,0,0.95), rgba(0,0,0,0.6));
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-logo img { height: 40px; }
        .nav-links a {
            text-decoration: none; color: #ccc; margin-left: 20px; font-size: 12px; letter-spacing: 2px;
            transition: .3s;
        }
        .nav-links a:hover { color: var(--gold); }
        .nav-logout { color: var(--red) !important; font-weight: bold; }

        /* --- 3. HERO SECTION (PARALLAX) --- */
        .hero {
            height: 100vh; width: 100%;
            /* Gambar Hero dari Database */
            background: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.2)), url('asset/<?= $series['gambar']; ?>'); 
            background-size: cover; background-position: center;
            display: flex; flex-direction: column; justify-content: center; align-items: flex-start;
            padding-left: 8%; position: relative; overflow: hidden;
        }
        
        .hero-content { position: relative; z-index: 2; transition: transform 0.1s ease-out; }
        .hero-logo { width: 300px; margin-bottom: 10px; filter: drop-shadow(0 5px 10px black); }
        .hero-title { font-size: 4rem; color: white; line-height: 1; margin-bottom: 10px; text-shadow: 0 5px 10px black; }
        .hero-subtitle { 
            font-family: 'Lato', sans-serif; font-size: 1.2rem; color: #ddd; 
            text-shadow: 0 2px 4px black; margin-bottom: 30px; letter-spacing: 4px;
        }
        .btn-start {
            background: var(--red); color: white; padding: 15px 40px; text-decoration: none;
            font-size: 1.2rem; letter-spacing: 2px; border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
            transition: .3s; display: inline-block;
        }
        .btn-start:hover { transform: scale(1.05); filter: brightness(1.2); }

        /* --- 4. THE BEGINNING --- */
        .section-container { padding: 80px 10%; border-bottom: 3px solid var(--gold); position: relative; }
        
        /* CSS JUDUL (DI TENGAH) */
        .section-title {
            text-align: center; font-size: 2.5rem; color: #fff; letter-spacing: 5px;
            margin: 0 auto 60px auto; text-transform: uppercase; border-bottom: 1px solid var(--red);
            display: table; padding-bottom: 5px;
        }

        .begin-content { display: flex; gap: 50px; align-items: flex-start; }
        .throne-img-box { flex: 0 0 400px; position: relative; border: 2px solid #222; overflow: hidden; }
        .throne-img { width: 100%; display: block; filter: contrast(1.1); transition: 0.5s; }
        .throne-img-box:hover .throne-img { transform: scale(1.05); }
        
        .story-text {
            font-family: 'Lato', sans-serif; font-size: 1.1rem; line-height: 1.8; 
            text-align: justify; color: #ddd;
        }

        /* --- 5. TIMELINE --- */
        .timeline-wrapper { position: relative; max-width: 1000px; margin: 0 auto; }
        .timeline-line {
            position: absolute; width: 3px; background: var(--red);
            top: 0; bottom: 0; left: 50%; transform: translateX(-50%);
            box-shadow: 0 0 5px var(--red);
        }
        .timeline-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 60px; position: relative; width: 100%;
        }
        .omega-node {
            position: absolute; left: 50%; transform: translateX(-50%);
            font-size: 2rem; color: var(--red); background: var(--dark-bg);
            padding: 5px; font-weight: bold; z-index: 2;
            text-shadow: 0 0 10px var(--red); transition: 0.3s;
        }
        .timeline-row:hover .omega-node { transform: translateX(-50%) scale(1.2); text-shadow: 0 0 20px var(--red); }
        
        .tl-card {
            width: 45%; background: #fff; padding: 8px; border: 2px solid var(--gold);
            box-shadow: 0 0 20px rgba(0,0,0,0.5); position: relative; transition: 0.3s;
        }
        .tl-card:hover { transform: translateY(-5px); border-color: var(--red); }
        .tl-img { width: 100%; height: 180px; object-fit: cover; border: 1px solid #aaa; margin-bottom: 10px; }
        .tl-title { font-size: 1rem; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .tl-desc { font-family: 'Lato'; font-size: 0.8rem; color: #333; line-height: 1.4; }
        
        .timeline-row.left .tl-card { margin-right: auto; }
        .timeline-row.right .tl-card { margin-left: auto; }

        /* --- 6. CHARACTER (SPOTLIGHT EFFECT) --- */
        .char-section {
            background: url('asset/GOWRG_Wallpapaper_KeyArt_Background_Desktop_4k.jpg');
            background-size: cover; background-blend-mode: multiply; background-color: #222;
        }
        .char-scroll-container {
            display: flex; overflow-x: auto; gap: 30px; padding: 20px 50px;
            scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;
        }
        .char-scroll-container::-webkit-scrollbar { display: none; }
        .char-card {
            flex: 0 0 250px; background: rgba(20, 25, 40, 0.8); border: 1px solid #445;
            display: flex; flex-direction: column; align-items: center;
            transition: all 0.4s ease; filter: brightness(1);
        }
        .char-scroll-container:hover .char-card { filter: brightness(0.4) blur(1px); transform: scale(0.95); }
        .char-scroll-container .char-card:hover {
            filter: brightness(1.2) blur(0); transform: scale(1.1) translateY(-10px);
            z-index: 10; border-color: var(--gold); box-shadow: 0 0 30px rgba(207, 163, 94, 0.4);
        }
        .char-img { width: 100%; height: 280px; object-fit: cover; }
        .char-info { padding: 15px; text-align: center; width: 100%; border-top: 1px solid #445; }
        .char-name { font-size: 1rem; color: #fff; text-transform: uppercase; margin-bottom: 5px; }
        .char-role { font-family: 'Lato'; font-size: 0.8rem; color: #aaa; text-transform: uppercase; letter-spacing: 1px; }

        /* --- 7. REALMS MAP --- */
        .map-section { background: #15100d; text-align: center; }
        .map-frame {
            position: relative; width: 90%; max-width: 1000px; margin: 0 auto;
            box-shadow: 0 0 50px rgba(0,0,0,0.8); transition: 0.5s;
        }
        .map-frame:hover { transform: perspective(1000px) rotateX(2deg); }
        .map-bg { width: 100%; display: block; border-radius: 5px; opacity: 0.8; }
        .map-pointer {
            position: absolute; width: 30px; height: 30px;
            background: red; border: 2px solid white; border-radius: 50%;
            box-shadow: 0 0 10px red; cursor: pointer; animation: pulse 2s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }

        /* --- 8. ARSENAL (HOVER & STATS EFFECT) --- */
        .arsenal-section { background: #000; text-align: center; }
        .arsenal-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 50px; }
        
        .weapon-card {
            flex: 0 0 350px; height: 500px;
            background: rgba(30, 35, 45, 0.6); border: 1px solid #445;
            padding: 20px; position: relative; cursor: pointer; transition: .3s; overflow: hidden;
        }
        
        .weapon-card:hover { border-color: var(--gold); background: rgba(30, 35, 45, 0.9); }
        
        .wp-img-box { height: 200px; display: flex; align-items: center; justify-content: center; transition: .4s; }
        .wp-img { width: 80%; filter: drop-shadow(0 10px 10px #000); transform: rotate(-45deg); transition: .4s; object-fit:contain; height: 100%;}
        
        .wp-title { color: var(--gold); font-size: 1.2rem; margin: 20px 0; text-transform: uppercase; }
        
        /* Deskripsi Default (Hilang saat Hover) */
        .wp-desc { 
            font-family: 'Lato'; font-size: 0.85rem; color: #ccc; line-height: 1.6; 
            transition: .4s; opacity: 1; transform: translateY(0); 
        }
        
        /* Statistik (Muncul saat Hover) */
        .wp-stats {
            position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px;
            background: rgba(20,20,20,0.95); transform: translateY(100%); transition: .4s; opacity: 0;
            display: flex; flex-direction: column; gap: 10px;
        }

        /* --- HOVER LOGIC --- */
        .weapon-card:hover .wp-desc { opacity: 0; transform: translateY(-20px); }
        .weapon-card:hover .wp-stats { opacity: 1; transform: translateY(0); bottom: 20px; }
        .weapon-card:hover .wp-img-box { transform: scale(0.8) translateY(-20px); }
        
        /* Style untuk Bar Statistik */
        .stat-row { display: flex; flex-direction: column; align-items: flex-start; gap: 5px; }
        .stat-label { font-size: 0.7rem; color: #aaa; font-family: 'Lato'; width: 100%; display: flex; justify-content: space-between; }
        .bar-bg { width: 100%; height: 8px; background: #333; border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; width: 0; transition: 1s ease-out; }

        /* --- 9. MINIGAME (BOSS BATTLE) --- */
        .battle-modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95); z-index: 2000; justify-content: center; align-items: center;
        }
        .battle-container {
            width: 900px; height: 600px; position: relative;
            background: url('asset/arena.jpg') no-repeat center/cover;
            border: 4px solid var(--red);
            box-shadow: 0 0 50px var(--red);
            display: flex; flex-direction: column; overflow: hidden;
        }
        .battle-header {
            padding: 20px; background: rgba(0,0,0,0.8);
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid var(--red);
        }
        .hp-box { width: 40%; color: white; font-family: 'Cinzel', serif; }
        .hp-bar-bg { width: 100%; height: 20px; background: #333; border: 1px solid #666; margin-top: 5px; position: relative; }
        .hp-bar-fill { height: 100%; transition: width 0.5s ease-out; }
        .battle-field { flex: 1; display: flex; justify-content: space-between; align-items: flex-end; padding: 0 50px 50px; }
        .fighter { width: 200px; height: 300px; position: relative; transition: transform 0.2s; }
        .fighter img { width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 0 20px black); }
        .shake { animation: shake 0.5s; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); filter: sepia(1) hue-rotate(-50deg) saturate(5); } 75% { transform: translateX(10px); } }
        .attack-anim { animation: lunge 0.3s; }
        @keyframes lunge { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(50px); } }
        .battle-controls { height: 120px; background: rgba(0,0,0,0.9); border-top: 2px solid var(--red); display: flex; padding: 20px; gap: 20px; }
        .log-box { flex: 2; border: 1px solid #444; background: #111; padding: 10px; font-family: 'Lato'; color: #ccc; font-size: 0.9rem; overflow-y: auto; display: flex; flex-direction: column-reverse; }
        .actions-box { flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .btn-action { background: #222; border: 1px solid var(--red); color: white; font-family: 'Cinzel'; cursor: pointer; text-transform: uppercase; transition: 0.2s; }
        .btn-action:hover:not(:disabled) { background: var(--red); color: black; }
        .result-screen { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 100; display: none; flex-direction: column; justify-content: center; align-items: center; color: white; }
        
        /* TOMBOL EXIT DI GAME */
        .btn-game-exit {
            position: absolute; top: 10px; right: 15px; 
            background: #8b0000; color: white; border: 1px solid white;
            padding: 5px 15px; cursor: pointer; font-family: 'Cinzel'; z-index: 100;
            transition: 0.3s;
        }
        .btn-game-exit:hover { background: red; }

        /* --- 10. FOOTER --- */
        .footer { padding: 80px 0; text-align: center; background: #000; border-top: 2px solid var(--gold); }
        .footer-text { font-family: 'Lato'; font-style: italic; color: #fff; margin-bottom: 30px; letter-spacing: 1px; }
        .btn-back { background: var(--red); color: white; padding: 10px 30px; text-decoration: none; border: 1px solid #ff5555; transition: .3s; }
        .btn-back:hover { background: #800000; }

        @media (max-width: 768px) {
            .begin-content { flex-direction: column; }
            .throne-img-box { width: 100%; flex: auto; }
            .timeline-row { flex-direction: column; }
            .tl-card { width: 100%; margin-bottom: 20px; }
            .timeline-line { left: 10px; } .omega-node { left: 10px; }
            .battle-container { width: 100%; height: 100vh; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo"><img src="asset/logo.png" alt="GOW"></div>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php">ABOUT</a>
            <a href="logout.php" class="nav-logout">LOGOUT</a>
        </div>
    </nav>

    <section class="hero" id="hero-section">
        <div class="hero-content">
            <img src="asset/logo.png" class="hero-logo">
            <h1 class="hero-title"><?= strtoupper($series['judul']); ?></h1>
            <h2 class="hero-subtitle"><?= $series['platform']; ?> | <?= $series['tahun']; ?></h2>
            <a href="#boss-fight" class="btn-start">CHALLENGE BOSS</a>
        </div>
    </section>

    <section id="beginning" class="section-container">
        <h2 class="section-title">THE BEGINNING</h2>
        <div class="begin-content">
            <div class="throne-img-box">
                <img src="asset/<?= $series['gambar']; ?>" class="throne-img">
            </div>
            <div class="story-text">
                <p><span style="font-size:3rem; float:left; line-height:0.8; margin-right:10px; color:var(--gold);"><?= substr($series['deskripsi'], 0, 1); ?></span><?= substr($series['deskripsi'], 1); ?></p>
            </div>
        </div>
    </section>

    <section class="section-container" style="background: #0a0a0a; border-bottom: 3px solid var(--gold);">
        <h2 class="section-title" style="color:var(--gold);">JOURNEY TIMELINE</h2>
        <div class="timeline-wrapper">
            <div class="timeline-line"></div>
            <?php 
            $i = 0;
            while($tl = mysqli_fetch_assoc($timeline)): 
                $pos = ($i % 2 == 0) ? 'left' : 'right';
            ?>
            <div class="timeline-row <?= $pos; ?>">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <img src="asset/<?= $tl['gambar']; ?>" class="tl-img">
                    <div class="tl-title"><?= $tl['judul_chapter']; ?></div>
                    <div class="tl-desc"><?= $tl['deskripsi']; ?></div>
                </div>
            </div>
            <?php $i++; endwhile; ?>
        </div>
    </section>

    <section class="char-section">
        <div style="text-align:center; padding-top:40px;">
            <h2 class="section-title">CHARACTERS</h2>
        </div>
        <div class="char-scroll-container">
            <?php while($char = mysqli_fetch_assoc($characters)): ?>
            <div class="char-card">
                <img src="asset/<?= $char['gambar']; ?>" class="char-img">
                <div class="char-info">
                    <div class="char-name"><?= $char['nama']; ?></div>
                    <div class="char-role"><?= $char['peran']; ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="map-section section-container">
        <h2 class="section-title" style="color:white; text-shadow:none; border:none;">REALMS MAP</h2>
        <div class="map-frame">
            <img src="asset/map.png" class="map-bg">
            <?php while($r = mysqli_fetch_assoc($realms)): ?>
                <div class="map-pointer" 
                     style="top: <?= $r['posisi_top']; ?>; left: <?= $r['posisi_left']; ?>;"
                     title="<?= $r['nama_realm']; ?>"
                     onclick="alert('REALM: <?= $r['nama_realm']; ?>\n<?= $r['deskripsi']; ?>')">
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="arsenal-section section-container">
        <p style="color:var(--red); letter-spacing:2px; font-family:'Cinzel';">CHOOSE YOUR TOOL</p>
        <h2 class="section-title" style="font-size:3rem; border:none; margin-bottom:20px;">ARSENAL</h2>
        
        <div class="arsenal-grid">
            <?php while($wp = mysqli_fetch_assoc($weapons)): ?>
            <div class="weapon-card">
                <div class="wp-img-box"><img src="asset/<?= $wp['gambar']; ?>" class="wp-img"></div>
                <h3 class="wp-title"><?= $wp['nama_senjata']; ?></h3>
                <p class="wp-desc"><?= $wp['deskripsi']; ?></p>
                <div class="wp-stats">
                    <div class="stat-row">
                        <div class="stat-label"><span>DAMAGE</span><span><?= $wp['stat_damage']; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill" style="width:<?= $wp['stat_damage']; ?>%; background: #ff3b3b;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>SPEED</span><span><?= $wp['stat_speed']; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill" style="width:<?= $wp['stat_speed']; ?>%; background: #ffd700;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>RANGE</span><span><?= $wp['stat_range']; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill" style="width:<?= $wp['stat_range']; ?>%; background: #00bfff;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>CROWD CONTROL</span><span><?= $wp['stat_cc']; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill" style="width:<?= $wp['stat_cc']; ?>%; background: #9932cc;"></div></div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="boss-fight" class="section-container" style="background:#111; text-align:center; padding:50px;">
        <h2 class="section-title" style="color:var(--red); border-color:var(--red);">BOSS BATTLE</h2>
        <p style="color:#ccc; margin-bottom:20px;">DEFEAT <?= $boss_name; ?> TO PROVE YOUR WORTH!</p>
        <button onclick="openBattle()" class="btn-start" style="cursor:pointer;">FIGHT BOSS</button>
    </section>

    <div class="battle-modal" id="battleModal">
        <div class="battle-container">
            <button class="btn-game-exit" onclick="closeBattle()">✕ EXIT GAME</button>

            <div class="battle-header">
                <div class="hp-box">
                    <div>KRATOS <span id="hpTxtPlayer">1000/1000</span></div>
                    <div class="hp-bar-bg"><div class="hp-bar-fill" id="hpBarPlayer" style="width: 100%; background: #4cd137;"></div></div>
                </div>
                <div style="font-size: 2rem; color: #666;">VS</div>
                <div class="hp-box" style="text-align: right;">
                    <div><?= $boss_name; ?> <span id="hpTxtEnemy"><?= $boss_hp; ?>/<?= $boss_hp; ?></span></div>
                    <div class="hp-bar-bg"><div class="hp-bar-fill" id="hpBarEnemy" style="width: 100%; background: var(--red); float: right;"></div></div>
                </div>
            </div>
            <div class="battle-field">
                <div class="fighter" id="kratosSprite"><img src="asset/GOW.jpg" style="transform: scaleX(-1);"></div>
                <div class="fighter" id="enemySprite"><img src="asset/<?= $boss_img; ?>"></div>
            </div>
            <div class="battle-controls">
                <div class="log-box" id="battleLog"><div>> BATTLE START!</div></div>
                <div class="actions-box" id="actionPanel">
                    <button class="btn-action" onclick="playerAttack('light')">Light Atk</button>
                    <button class="btn-action" onclick="playerAttack('heavy')">Heavy Atk</button>
                    <button class="btn-action" onclick="playerHeal()">Heal</button>
                    <button class="btn-action" style="background:#800; border-color:red;" onclick="playerRage()">RAGE</button>
                </div>
            </div>
            <div class="result-screen" id="resultScreen">
                <h2 id="resultTitle" style="font-size: 3rem; margin-bottom: 20px;">VICTORY</h2>
                <button class="btn-back" onclick="closeBattle()">CLOSE</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p class="footer-text">"THE CYCLE ENDS HERE..."</p>
        <a href="series.php" class="btn-back">BACK TO SERIES</a>
        <p style="margin-top:20px; font-size:10px; color:#555;">God of War Fan Project</p>
    </footer>

    <script>
        // --- PARALLAX HERO ---
        const heroSection = document.getElementById('hero-section');
        const heroContent = document.querySelector('.hero-content');
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 100;
            const y = (window.innerHeight - e.pageY * 2) / 100;
            heroContent.style.transform = `translate(${x}px, ${y}px)`;
        });

        // --- MINIGAME LOGIC ---
        const maxHpPlayer = 1000;
        const maxHpEnemy = <?= $boss_hp; ?>;
        let hpPlayer = maxHpPlayer;
        let hpEnemy = maxHpEnemy;
        let isPlayerTurn = true;

        const modal = document.getElementById('battleModal');
        const logBox = document.getElementById('battleLog');
        const enemySprite = document.getElementById('enemySprite');
        const kratosSprite = document.getElementById('kratosSprite');

        function openBattle() { modal.style.display = 'flex'; resetBattle(); }
        function closeBattle() { modal.style.display = 'none'; }
        function log(msg, color='white') { 
            const p = document.createElement('div'); p.innerHTML = `> ${msg}`; p.style.color = color; 
            logBox.prepend(p); 
        }

        function updateUI() {
            document.getElementById('hpBarPlayer').style.width = (hpPlayer / maxHpPlayer * 100) + "%";
            document.getElementById('hpTxtPlayer').innerText = `${hpPlayer}/${maxHpPlayer}`;
            document.getElementById('hpBarEnemy').style.width = (hpEnemy / maxHpEnemy * 100) + "%";
            document.getElementById('hpTxtEnemy').innerText = `${hpEnemy}/${maxHpEnemy}`;
        }

        function playerAttack(type) {
            if(!isPlayerTurn || hpEnemy <= 0) return;
            let dmg = type === 'light' ? 50 : 80;
            if(type === 'heavy' && Math.random() < 0.3) dmg = 0; // Miss chance

            kratosSprite.classList.add('attack-anim'); setTimeout(()=>kratosSprite.classList.remove('attack-anim'), 300);
            
            if(dmg > 0) {
                hpEnemy = Math.max(0, hpEnemy - dmg);
                enemySprite.classList.add('shake'); setTimeout(()=>enemySprite.classList.remove('shake'), 500);
                log(`Kratos dealt ${dmg} damage!`, '#4cd137');
            } else {
                log("Kratos missed!", 'gray');
            }
            
            updateUI(); checkWin();
            if(hpEnemy > 0) { isPlayerTurn = false; setTimeout(enemyTurn, 1000); }
        }

        function playerHeal() {
            if(!isPlayerTurn) return;
            let heal = 100;
            hpPlayer = Math.min(maxHpPlayer, hpPlayer + heal);
            log("Kratos healed 100 HP", 'cyan');
            updateUI();
            isPlayerTurn = false; setTimeout(enemyTurn, 1000);
        }

        function playerRage() {
            if(!isPlayerTurn) return;
            let dmg = 150;
            hpEnemy = Math.max(0, hpEnemy - dmg);
            enemySprite.classList.add('shake');
            log("SPARTAN RAGE! 150 DMG!", 'red');
            updateUI(); checkWin();
            if(hpEnemy > 0) { isPlayerTurn = false; setTimeout(enemyTurn, 1000); }
        }

        function enemyTurn() {
            if(hpEnemy <= 0) return;
            let dmg = Math.floor(Math.random() * 60) + 20;
            hpPlayer = Math.max(0, hpPlayer - dmg);
            kratosSprite.classList.add('shake'); setTimeout(()=>kratosSprite.classList.remove('shake'), 500);
            log(`Boss attacked! ${dmg} damage!`, '#ff5555');
            updateUI(); checkWin();
            if(hpPlayer > 0) isPlayerTurn = true;
        }

        function checkWin() {
            const screen = document.getElementById('resultScreen');
            const title = document.getElementById('resultTitle');
            if(hpEnemy <= 0) {
                screen.style.display = 'flex'; title.innerText = "VICTORY"; title.style.color = "#4cd137";
            } else if(hpPlayer <= 0) {
                screen.style.display = 'flex'; title.innerText = "DEFEATED"; title.style.color = "red";
            }
        }

        function resetBattle() {
            hpPlayer = maxHpPlayer; hpEnemy = maxHpEnemy; isPlayerTurn = true;
            document.getElementById('resultScreen').style.display = 'none';
            logBox.innerHTML = ''; log("Battle Start!"); updateUI();
        }
    </script>
</body>
</html>