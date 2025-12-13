<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God of War II - Defy Your Fate</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & VARIABLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --gold: #cfa35e;
            --blue: #2c5d87;
            --red: #b30000;
            --dark-bg: #0b0b0b;
            
            /* Stat Colors */
            --stat-dmg: #d32f2f; 
            --stat-speed: #fbc02d;  
            --stat-range: #0288d1;  
            --stat-cc: #7b1fa2;     
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
            background: linear-gradient(to bottom, rgba(0,0,0,0.9), rgba(0,0,0,0));
        }
        .nav-logo img { height: 40px; filter: drop-shadow(0 2px 4px #000); }
        .nav-links a {
            text-decoration: none; color: #ddd; margin-left: 25px; font-size: 13px; letter-spacing: 2px;
            text-transform: uppercase; transition: .3s; text-shadow: 0 2px 4px #000;
        }
        .nav-links a:hover { color: var(--gold); }
        .nav-logout { color: #ff5555 !important; }

        /* --- 3. HERO SECTION --- */
        .hero {
            height: 100vh; width: 100%;
            background: url('asset/GOW2.jpg') no-repeat center center/cover;
            display: flex; flex-direction: column; justify-content: center; align-items: flex-start;
            padding-left: 10%; position: relative;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0) 100%);
        }

        .hero-content { position: relative; z-index: 2; margin-top: 50px; }
        .hero-logo-img { width: 300px; margin-bottom: 20px; display: block; filter: drop-shadow(0 0 10px #000); }
        
        .hero-saga { color: var(--red); font-size: 0.9rem; letter-spacing: 3px; font-family: 'Lato'; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; display: block;}
        .hero-saga::before { content: '― '; color: var(--red); }

        .hero-title {
            font-size: 4rem; color: #fff; line-height: 1.1; margin-bottom: 30px;
            text-shadow: 0 5px 15px rgba(0,0,0,0.8); letter-spacing: 2px;
        }
        
        .btn-start {
            display: inline-block;
            background-color: #a31616;
            color: white; padding: 15px 40px; text-decoration: none;
            font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;
            clip-path: polygon(
                0% 0%, 5% 5%, 10% 0%, 15% 5%, 20% 0%, 25% 5%, 30% 0%, 35% 5%, 40% 0%, 45% 5%, 50% 0%, 55% 5%, 60% 0%, 65% 5%, 70% 0%, 75% 5%, 80% 0%, 85% 5%, 90% 0%, 95% 5%, 100% 0%, 
                100% 100%, 95% 95%, 90% 100%, 85% 95%, 80% 100%, 75% 95%, 70% 100%, 65% 95%, 60% 100%, 55% 95%, 50% 100%, 45% 95%, 40% 100%, 35% 95%, 30% 100%, 25% 95%, 20% 100%, 15% 95%, 10% 100%, 5% 95%, 0% 100%
            );
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); transition: .3s;
        }
        .btn-start:hover { background-color: #c41e1e; transform: scale(1.05); }

        /* --- 4. THE BETRAYAL --- */
        .section-black { background-color: #000; padding: 80px 10%; border-bottom: 2px solid var(--gold); }
        
        .title-wrapper { text-align: center; margin-bottom: 60px; }
        .section-title {
            display: inline-block; font-size: 2.2rem; color: #fff; text-transform: uppercase; 
            letter-spacing: 4px; padding-bottom: 10px; border-bottom: 3px solid var(--red);
        }

        .betrayal-box {
            border: 2px solid var(--gold); padding: 40px; background: #080808;
            display: flex; gap: 40px; align-items: center; box-shadow: 0 0 50px rgba(0,0,0,0.8);
        }

        .betrayal-img-box { flex: 0 0 45%; }
        .betrayal-img { width: 100%; border: 1px solid #333; display: block; }

        .betrayal-content h3 { color: var(--gold); font-size: 1.6rem; margin-bottom: 20px; text-transform: uppercase; }
        .betrayal-text {
            font-family: 'Lato', sans-serif; font-size: 1rem; line-height: 1.6; color: #ccc;
        }
        .highlight-red { color: #ff4444; font-weight: bold; }
        .highlight-white { color: #fff; font-weight: bold; }

        /* --- 5. TIMELINE --- */
        .timeline-section {
            background: #fff; padding: 80px 0; position: relative; overflow: hidden;
        }
        .timeline-section::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: url('https://static.wikia.nocookie.net/godofwar/images/f/f0/Kratos_GoW2.png') no-repeat center center;
            background-size: contain; opacity: 0.03; pointer-events: none;
        }

        .tl-header { text-align: center; margin-bottom: 60px; position: relative; z-index: 2; }
        .tl-title { font-size: 2.5rem; color: #8b6c42; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px; }
        .tl-sub { font-family: 'Lato'; font-size: 0.8rem; color: #000; letter-spacing: 3px; font-weight: bold; text-transform: uppercase; }

        .timeline-wrapper { position: relative; max-width: 900px; margin: 0 auto; z-index: 2; }
        
        .tl-line {
            position: absolute; left: 50%; top: 0; bottom: 0; width: 2px;
            background: #b30000; transform: translateX(-50%);
        }
        .tl-line::after { content: 'v'; color: #b30000; position: absolute; bottom: -15px; left: -5px; font-weight: bold; }

        .tl-row { display: flex; justify-content: space-between; margin-bottom: 60px; width: 100%; position: relative; }
        
        .tl-omega {
            position: absolute; left: 50%; top: 20px; transform: translateX(-50%);
            color: #b30000; font-size: 1.5rem; background: #fff; padding: 5px; font-weight: bold; line-height: 1;
        }

        .tl-card {
            width: 42%; background: #fff; padding: 10px; border: 2px solid #daa520;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); text-align: center;
            transition: .3s;
        }
        .tl-card:hover { transform: scale(1.02); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }

        .tl-img { width: 100%; height: 160px; object-fit: cover; border: 1px solid #ccc; margin-bottom: 10px; }
        .tl-name { font-size: 0.9rem; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .tl-desc { font-family: 'Lato'; font-size: 0.75rem; color: #333; line-height: 1.4; }

        .tl-row.left .tl-card { margin-right: auto; }
        .tl-row.right .tl-card { margin-left: auto; margin-top: 50px; }

        /* --- 6. CHARACTER --- */
        .char-section {
            background: url('https://w0.peakpx.com/wallpaper/397/23/HD-wallpaper-god-of-war-cloud-dark-game-video-game.jpg') center/cover;
            padding: 60px 0; border-top: 4px solid var(--gold); border-bottom: 4px solid var(--gold);
            background-blend-mode: multiply; background-color: #333;
        }
        .char-header { text-align: center; color: #fff; font-size: 2rem; margin-bottom: 40px; letter-spacing: 3px; }

        .char-container {
            display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; max-width: 1200px; margin: 0 auto;
        }

        .char-card {
            width: 220px; background: rgba(30, 35, 45, 0.9); border: 1px solid #5a6a85;
            text-align: center; transition: .3s; box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }
        .char-card:hover { border-color: var(--gold); transform: translateY(-10px); }

        .char-img-wrap { padding: 15px 15px 0 15px; }
        .char-img { width: 100%; height: 220px; object-fit: cover; border: 1px solid #000; }
        .char-info { padding: 15px; }
        .char-name { font-size: 0.9rem; color: #fff; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .char-role { font-family: 'Lato'; font-size: 0.7rem; color: var(--gold); text-transform: uppercase; }

        /* --- 7. REALMS MAP --- */
        .map-section { background: #1a1b1e; padding: 60px 0; text-align: center; }
        .map-title { color: #fff; font-size: 2rem; letter-spacing: 3px; margin-bottom: 30px; }
        .map-container { position: relative; width: 90%; max-width: 900px; margin: 0 auto; }
        .map-img { width: 100%; display: block; border-radius: 5px; box-shadow: 0 0 30px rgba(0,0,0,0.5); }

        .map-pointer {
            position: absolute; width: 45px; height: 45px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" stroke="black" stroke-width="1.5"><path d="M9 11.24V7.5C9 6.12 10.12 5 11.5 5S14 6.12 14 7.5v3.74c1.21-.81 2-2.18 2-3.74C16 5.01 13.99 3 11.5 3S7 5.01 7 7.5c0 1.56.79 2.93 2 3.74zm9.84 4.63l-2.54-.78c-.61-.19-1.28.15-1.48.76l-.6 1.94c-.16.52-.73.8-1.25.64-.52-.16-.8-.74-.64-1.25l.48-1.58c-1.35.66-2.91.86-4.29.35l-2.07-.76c-.52-.19-.79-.77-.6-1.29.19-.52.77-.79 1.29-.6l2.07.76c.72.27 1.52.19 2.19-.19l-1.09-3.52c-.19-.61.15-1.28.76-1.48.61-.19 1.28.15 1.48.76l1.24 4.02c.81-.36 1.63-.49 2.44-.31l2.54.78c.52.16.82.72.66 1.24-.16.52-.72.82-1.24.66z"/></svg>');
            background-repeat: no-repeat; cursor: pointer; filter: drop-shadow(3px 3px 2px rgba(0,0,0,0.8));
            animation: bounceHand 1.5s infinite alternate;
        }
        @keyframes bounceHand { from { transform: translateY(0); } to { transform: translateY(-10px); } }

        .hp-1 { top: 20%; left: 30%; transform: rotate(-20deg); }
        .hp-2 { top: 35%; left: 48%; transform: rotate(-10deg); }
        .hp-3 { top: 60%; left: 25%; transform: rotate(-20deg); }
        .hp-4 { top: 70%; left: 50%; transform: rotate(-10deg); }
        .hp-5 { top: 30%; left: 70%; transform: rotate(10deg); }
        .hp-6 { top: 70%; left: 80%; transform: rotate(10deg); }

        /* --- 8. ARSENAL (PERBAIKAN GRID 2x2) --- */
        .arsenal-section { background: #000; padding: 80px 0; text-align: center; border-top: 3px solid var(--gold); }
        .arsenal-sub { color: #b30000; font-family: 'Cinzel'; font-size: 1rem; letter-spacing: 2px; }
        .arsenal-title { font-size: 2.5rem; color: #fff; margin-bottom: 50px; }
        .arsenal-title span { color: var(--gold); }

        /* CSS GRID YANG BENAR UNTUK 4 KARTU */
        .arsenal-grid {
            display: grid; 
            grid-template-columns: repeat(2, 1fr); /* 2 Kolom */
            gap: 30px; /* Jarak antar kartu */
            max-width: 900px; margin: 0 auto;
        }

        /* Weapon cards: gunakan ukuran fleksibel, jangan tinggi tetap */
        .weapon-card {
            background: #15181e; border: 1px solid #333; padding: 20px;
            position: relative; cursor: pointer; transition: .3s; min-height: 380px;
            overflow: hidden; display: flex; flex-direction: column; align-items: center;
        }
        .weapon-card:hover { border-color: var(--gold); background: #1f232b; }

        /* Kotak gambar: center dan beri ruang yang cukup */
        .wp-img-box {
            height: 200px; display: flex; align-items: center; justify-content: center;
            width: 100%; padding: 10px 0; box-sizing: border-box;
        }

        /* Gambar: ukuran konsisten, tidak di-rotate, gunakan object-fit untuk menjaga proporsi */
        .wp-img {
            width: 160px; height: 160px; object-fit: contain; display: block;
            transition: transform .4s, filter .4s; transform: none; margin: 0 auto;
            filter: drop-shadow(0 5px 5px #000);
        }
        
        .wp-name { color: var(--gold); font-size: 1.1rem; margin: 15px 0; text-transform: uppercase; letter-spacing: 1px; text-align:center; }
        .wp-desc { font-family: 'Lato'; font-size: 0.8rem; color: #ccc; line-height: 1.5; opacity: 1; transition: .3s; text-align:center; padding: 0 8px; }

        .wp-stats {
            position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px;
            background: rgba(20,20,20,0.95); transform: translateY(100%); opacity: 0;
            transition: .4s; display: flex; flex-direction: column; gap: 8px;
        }
        
        /* Saat aktif: sembunyikan deskripsi dan tampilkan stats, gambar sedikit diangkat */
        .weapon-card.active .wp-desc { opacity: 0; }
        .weapon-card.active .wp-stats { opacity: 1; transform: translateY(0); bottom: 10px; }
        .weapon-card.active .wp-img { transform: scale(0.9) translateY(-18px); }

        .stat-bar { display: flex; flex-direction: column; align-items: flex-start; gap: 3px; }
        .sb-label { font-size: 0.6rem; color: #fff; width: 100%; display: flex; justify-content: space-between; }
        .sb-bg { width: 100%; height: 6px; background: #333; border-radius: 3px; }
        .sb-fill { height: 100%; border-radius: 3px; }

        /* --- 9. FOOTER --- */
        .footer { background: #000; padding: 60px 0; text-align: center; border-top: 1px solid var(--gold); }
        .footer-quote { font-family: 'Lato'; font-style: italic; color: #fff; margin-bottom: 30px; letter-spacing: 1px; }
        .btn-back {
            background: var(--red); color: white; padding: 10px 30px; text-decoration: none;
            font-size: 0.9rem; border: 1px solid #ff5555; transition: .3s;
        }
        .btn-back:hover { background: #800000; }
        .copyright { margin-top: 40px; font-size: 10px; color: #666; font-family: sans-serif; }

        @media (max-width: 768px) {
            .hero { align-items: center; padding-left: 0; text-align: center; }
            .betrayal-box { flex-direction: column; }
            .betrayal-img-box { flex: 0 0 auto; width: 100%; }
            .timeline-row { flex-direction: column; }
            .tl-line { left: 20px; }
            .tl-omega { left: 20px; }
            .tl-card { width: 100%; margin-left: 50px !important; margin-top: 20px !important; text-align: left; }
            .arsenal-grid { grid-template-columns: 1fr; } /* Stack jadi 1 kolom di HP */
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

    <section class="hero">
        <div class="hero-content">
            <div style="border-top: 2px solid var(--red); width: 30px; margin-bottom: 10px;"></div>
            <span class="hero-saga">THE SAGA CONTINUES</span>
            <h1 class="hero-title">DEFY<br>YOUR FATE</h1>
            <a href="#betrayal" class="btn-start">CONTINUE JOURNEY</a>
        </div>
    </section>

    <section id="betrayal" class="section-black">
        <div class="title-wrapper"><h2 class="section-title">THE BETRAYAL</h2></div>
        
        <div class="betrayal-box">
            <div class="betrayal-img-box">
                <img src="https://static.wikia.nocookie.net/godofwar/images/6/63/Zeus_Betrayal.jpg" class="betrayal-img">
            </div>
            <div class="betrayal-content">
                <h3>THE BETRAYAL OF ZEUS</h3>
                <div class="betrayal-text">
                    <p>Setelah menjadi Dewa Perang yang baru, Kratos dijebak. Zeus, yang takut akan kekuatan Kratos, menipunya untuk menyalurkan kekuatan dewanya ke dalam <span class="highlight-white">Blade of Olympus</span>.</p>
                    <br>
                    <p>Zeus membunuh Kratos, mengirimnya kembali ke dunia bawah (Underworld). Namun, Gaia dan para Titan menyelamatkannya dengan satu tujuan: Menemukan <span class="highlight-red">Sisters of Fate</span> untuk mengubah masa lalu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="timeline-section">
        <div class="tl-header">
            <h2 class="tl-title">JOURNEY TIMELINE</h2>
            <p class="tl-sub">THE PATH OF VENGEANCE</p>
        </div>
        
        <div class="timeline-wrapper">
            <div class="tl-line"></div>

            <div class="tl-row left">
                <div class="tl-omega">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/c/c5/Colossus_of_Rhodes.jpg" class="tl-img">
                    <div class="tl-name">COLOSSUS OF RHODES</div>
                    <div class="tl-desc">Kratos turun ke bumi untuk menghancurkan Rhodes, namun dihadapkan pada Colossus yang dihidupkan oleh Zeus. Di sinilah kekuatan dewa-nya hilang.</div>
                </div>
            </div>

            <div class="tl-row right">
                <div class="tl-omega">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/3/3e/Typhon%27s_Cavern.jpg" class="tl-img">
                    <div class="tl-name">TYPHON'S CAVERN</div>
                    <div class="tl-desc">Untuk mencapai Island of Creation, Kratos merebut busur Typhon's Bane dan membebaskan Kuda Waktu (Steeds of Time).</div>
                </div>
            </div>

            <div class="tl-row left">
                <div class="tl-omega">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/e/e0/Island_of_Creation.jpg" class="tl-img">
                    <div class="tl-name">ISLAND OF CREATION</div>
                    <div class="tl-desc">Sebuah pulau berbahaya penuh monster mitologi. Kratos harus mengalahkan Theseus dan Raja Barbarian demi Golden Fleece.</div>
                </div>
            </div>

            <div class="tl-row right">
                <div class="tl-omega">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/a/a3/Loom_Chamber.jpg" class="tl-img">
                    <div class="tl-name">LOOM CHAMBER</div>
                    <div class="tl-desc">Klimaks cerita. Kratos melawan Sisters of Fate, menguasai Alat Tenun Takdir, dan kembali ke masa lalu untuk menghadapi Zeus.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="char-section">
        <h2 class="char-header">CHARACTER</h2>
        <div class="char-container">
            <div class="char-card">
                <div class="char-img-wrap"><img src="https://static.wikia.nocookie.net/godofwar/images/f/f0/Kratos_GoW2.png" class="char-img"></div>
                <div class="char-info"><div class="char-name">KRATOS</div><div class="char-role">THE GHOST OF SPARTA</div></div>
            </div>
            <div class="char-card">
                <div class="char-img-wrap"><img src="https://static.wikia.nocookie.net/godofwar/images/6/67/Zeus_GoW2.png" class="char-img"></div>
                <div class="char-info"><div class="char-name">ZEUS</div><div class="char-role">THE PARANOID SOVEREIGN</div></div>
            </div>
            <div class="char-card">
                <div class="char-img-wrap"><img src="https://static.wikia.nocookie.net/godofwar/images/7/73/Gaia_GoW2.png" class="char-img"></div>
                <div class="char-info"><div class="char-name">GAIA</div><div class="char-role">MOTHER OF TITANS</div></div>
            </div>
            <div class="char-card">
                <div class="char-img-wrap"><img src="https://static.wikia.nocookie.net/godofwar/images/d/d8/Athena_GoW2.png" class="char-img"></div>
                <div class="char-info"><div class="char-name">ATHENA</div><div class="char-role">GODDESS OF WISDOM</div></div>
            </div>
            <div class="char-card">
                <div class="char-img-wrap"><img src="https://static.wikia.nocookie.net/godofwar/images/6/6e/Lahkesis.jpg" class="char-img"></div>
                <div class="char-info"><div class="char-name">LAHKESIS</div><div class="char-role">SISTER OF FATE</div></div>
            </div>
        </div>
    </section>

    <section class="map-section">
        <h2 class="map-title">REALMS MAP</h2>
        <div class="map-container">
            <img src="https://static.wikia.nocookie.net/godofwar/images/e/e0/Island_of_Creation.jpg" class="map-img">
            <div class="map-pointer hp-1"></div>
            <div class="map-pointer hp-2"></div>
            <div class="map-pointer hp-3"></div>
            <div class="map-pointer hp-4"></div>
            <div class="map-pointer hp-5"></div>
            <div class="map-pointer hp-6"></div>
        </div>
    </section>

    <section class="arsenal-section">
        <p class="arsenal-sub">CHOOSE YOUR TOOL</p>
        <h2 class="arsenal-title">ARSENAL OF <span>SPARTA</span></h2>
        
        <div class="arsenal-grid">
            <div class="weapon-card" onclick="toggleStats(this)">
                <div class="wp-img-box"><img src="asset/GoWII_Blades_of_Athena.jpg" class="wp-img"></div>
                <h3 class="wp-name">BLADES OF ATHENA</h3>
                <p class="wp-desc">GIVEN BY THE GODDESS ATHENA. FAST, DEADLY, AND CHAINED TO THE FLESH OF THE SPARTAN.</p>
                <div class="wp-stats">
                    <div class="stat-bar"><div class="sb-label"><span>DAMAGE</span><span>8/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:80%; background:var(--stat-dmg);"></div></div></div>
                    <div class="stat-bar"><div class="sb-label"><span>SPEED</span><span>10/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:100%; background:var(--stat-speed);"></div></div></div>
                </div>
            </div>

            <div class="weapon-card" onclick="toggleStats(this)">
                <div class="wp-img-box"><img src="asset/Barbarian_Hammer.jpg" class="wp-img"></div>
                <h3 class="wp-name">BARBARIAN HAMMER</h3>
                <p class="wp-desc">TAKEN FROM THE BARBARIAN KING. SLOW BUT DELIVERS CRUSHING BLOWS THAT SHAKE THE EARTH.</p>
                <div class="wp-stats">
                    <div class="stat-bar"><div class="sb-label"><span>DAMAGE</span><span>10/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:100%; background:var(--stat-dmg);"></div></div></div>
                    <div class="stat-bar"><div class="sb-label"><span>SPEED</span><span>3/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:30%; background:var(--stat-speed);"></div></div></div>
                </div>
            </div>

            <div class="weapon-card" onclick="toggleStats(this)">
                <div class="wp-img-box"><img src="asset/GOW2_Spear_Of_Destiny.jpg" class="wp-img"></div>
                <h3 class="wp-name">SPEAR OF DESTINY</h3>
                <p class="wp-desc">A WEAPON CAPABLE OF EXTENDING ITS REACH TO PIERCE ENEMIES FROM APAR WITH MAGICAL ENERGY.</p>
                <div class="wp-stats">
                    <div class="stat-bar"><div class="sb-label"><span>RANGE</span><span>9/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:90%; background:var(--stat-range);"></div></div></div>
                    <div class="stat-bar"><div class="sb-label"><span>CC</span><span>7/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:70%; background:var(--stat-cc);"></div></div></div>
                </div>
            </div>

            <div class="weapon-card" onclick="toggleStats(this)">
                <div class="wp-img-box"><img src="asset/Blade_of_Olympus.jpg" class="wp-img"></div>
                <h3 class="wp-name">BLADE OF OLYMPUS</h3>
                <p class="wp-desc">THE SWORD THAT ENDED THE GREAT WAR. IT HOLDS THE POWER OF GOD KRATOS HIMSELF.</p>
                <div class="wp-stats">
                    <div class="stat-bar"><div class="sb-label"><span>POWER</span><span>∞/10</span></div><div class="sb-bg"><div class="sb-fill" style="width:100%; background:var(--stat-dmg);"></div></div></div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p class="footer-quote">"THE JOURNEY DOES NOT END HERE..."</p>
        <a href="series.php" class="btn-back">BACK TO SERIES</a>
        <p class="copyright">God of War II © Sony Interactive Entertainment</p>
    </footer>

    <script>
        function toggleStats(card) {
            card.classList.toggle('active');
        }
    </script>
</body>
</html>