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

// --- KONFIGURASI BATTLE SYSTEM ---

// 1. Tentukan Tema Visual (Yunani vs Nordik)
$is_norse = ($id == 4 || $id == 5);
$theme_color = $is_norse ? '#00a8ff' : '#b30000'; // Biru (Es) atau Merah (Api)
$theme_glow = $is_norse ? 'rgba(0, 168, 255, 0.6)' : 'rgba(179, 0, 0, 0.6)';

// 2. Tentukan Data Boss Berdasarkan ID Series
// Kita set data manual (mockup) karena database belum tentu lengkap untuk semua boss
$boss_data = [
    'name' => 'Unknown Enemy',
    'hp' => 1000,
    'img' => 'logo.png' // Default
];

switch ($id) {
    case 1: 
        $boss_data = ['name' => 'ARES', 'hp' => 800, 'img' => 'GOW.jpg']; break;
    case 2: 
        $boss_data = ['name' => 'ZEUS', 'hp' => 1200, 'img' => 'GOW2.jpg']; break;
    case 3: 
        $boss_data = ['name' => 'HADES', 'hp' => 1000, 'img' => 'GOW3.jpg']; break;
    case 4: 
        $boss_data = ['name' => 'BALDUR', 'hp' => 1500, 'img' => 'GOW 2018.jpg']; break;
    case 5: 
        $boss_data = ['name' => 'THOR', 'hp' => 2000, 'img' => 'GOW RAGNAROK.jpg']; break;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $series['judul']; ?> - Battle Arena</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* --- CSS BATTLE ARENA --- */
        .battle-modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95); z-index: 2000; justify-content: center; align-items: center;
        }

        .battle-container {
            width: 900px; height: 600px; position: relative;
            background: url('asset/GOWRG_Wallpaper_Desktop_Vista_4k.jpg') no-repeat center/cover;
            border: 4px solid <?= $theme_color; ?>;
            box-shadow: 0 0 50px <?= $theme_glow; ?>;
            display: flex; flex-direction: column; overflow: hidden;
        }

        .battle-header {
            padding: 20px; background: rgba(0,0,0,0.8);
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid <?= $theme_color; ?>;
        }

        /* HP BARS */
        .hp-box { width: 40%; color: white; font-family: 'Cinzel', serif; }
        .hp-bar-bg { width: 100%; height: 20px; background: #333; border: 1px solid #666; margin-top: 5px; position: relative; }
        .hp-bar-fill { height: 100%; transition: width 0.5s ease-out; }
        .hp-text { font-size: 0.8rem; letter-spacing: 2px; }

        /* AREAS */
        .battle-field {
            flex: 1; position: relative; display: flex; justify-content: space-between; align-items: flex-end; padding: 0 50px 50px 50px;
        }

        .fighter {
            width: 200px; height: 300px; position: relative; transition: transform 0.2s;
        }
        .fighter img { 
            width: 100%; height: 100%; object-fit: contain; 
            filter: drop-shadow(0 0 20px black);
        }
        /* Efek Kena Hit */
        .shake { animation: shake 0.5s; }
        @keyframes shake {
            0% { transform: translateX(0); } 25% { transform: translateX(-10px); filter: brightness(2) sepia(1) hue-rotate(-50deg) saturate(5); }
            50% { transform: translateX(10px); } 75% { transform: translateX(-10px); } 100% { transform: translateX(0); }
        }
        /* Efek Serang */
        .attack-anim { animation: lunge 0.3s; }
        @keyframes lunge { 0% { transform: translateX(0); } 50% { transform: translateX(50px); } 100% { transform: translateX(0); } }

        /* CONTROLS */
        .battle-controls {
            height: 120px; background: rgba(0,0,0,0.9); border-top: 2px solid <?= $theme_color; ?>;
            display: flex; padding: 20px; gap: 20px;
        }
        .log-box {
            flex: 2; border: 1px solid #444; background: #111; padding: 10px;
            font-family: 'Lato'; color: #ccc; font-size: 0.9rem; overflow-y: auto;
            display: flex; flex-direction: column-reverse; /* Pesan baru di bawah */
        }
        .actions-box {
            flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
        }

        .btn-action {
            background: #222; border: 1px solid <?= $theme_color; ?>; color: white;
            font-family: 'Cinzel'; cursor: pointer; transition: 0.2s; text-transform: uppercase;
        }
        .btn-action:hover:not(:disabled) { background: <?= $theme_color; ?>; color: black; }
        .btn-action:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .btn-rage {
            grid-column: span 2; background: #550000; border-color: red; color: red;
        }
        .btn-rage:hover:not(:disabled) { background: red; color: white; box-shadow: 0 0 15px red; }

        /* OVERLAY SCREENS */
        .result-screen {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9); z-index: 100; display: none;
            flex-direction: column; justify-content: center; align-items: center; color: white;
        }
    </style>
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
        <a href="logout.php" class="btn-primary" style="padding: 5px 15px; font-size:12px;">LOGOUT</a>
    </nav>

    <header class="hero-landing" style="background-image: url('asset/<?= $series['header_img']; ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><?= $series['judul']; ?></h1>
            <p class="hero-subtitle"><?= $series['tahun']; ?> | <?= $series['platform']; ?></p>
            <p style="font-family:'Lato'; color:#ddd; margin-bottom:30px; font-size:1.1rem; line-height:1.6;">
                <?= $series['deskripsi']; ?>
            </p>
            <button onclick="openBattle()" class="btn-primary" 
                style="border: 2px solid white; font-size: 1.2rem; background: <?= $theme_color; ?>; box-shadow: 0 0 20px <?= $theme_glow; ?>;">
                ⚔️ FIGHT BOSS: <?= $boss_data['name']; ?>
            </button>
        </div>
    </header>

    <section class="section-landing">
        <h2 class="section-title">Characters</h2>
        <div class="char-grid">
            <?php if(mysqli_num_rows($query_chars) > 0): ?>
                <?php while($char = mysqli_fetch_assoc($query_chars)) : ?>
                <div class="char-card">
                    <img src="asset/<?= $char['gambar']; ?>" class="char-img">
                    <div class="char-name"><?= $char['nama']; ?></div>
                    <div class="char-role"><?= $char['peran']; ?></div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#666;">Data karakter belum tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <div class="battle-modal" id="battleModal">
        <div class="battle-container">
            <div class="battle-header">
                <div class="hp-box">
                    <div class="hp-text">KRATOS <span id="hpTxtPlayer">1000/1000</span></div>
                    <div class="hp-bar-bg">
                        <div class="hp-bar-fill" id="hpBarPlayer" style="width: 100%; background: #4cd137;"></div>
                    </div>
                </div>
                <div style="font-size: 2rem; color: #666;">VS</div>
                <div class="hp-box" style="text-align: right;">
                    <div class="hp-text"><?= $boss_data['name']; ?> <span id="hpTxtEnemy"><?= $boss_data['hp']; ?>/<?= $boss_data['hp']; ?></span></div>
                    <div class="hp-bar-bg">
                        <div class="hp-bar-fill" id="hpBarEnemy" style="width: 100%; background: <?= $theme_color; ?>; float: right;"></div>
                    </div>
                </div>
            </div>

            <div class="battle-field">
                <div class="fighter" id="kratosSprite">
                    <img src="asset/GOW 2018.jpg" style="transform: scaleX(-1);"> 
                </div>
                <div class="fighter" id="enemySprite">
                    <img src="asset/<?= $boss_data['img']; ?>">
                </div>
            </div>

            <div class="battle-controls">
                <div class="log-box" id="battleLog">
                    <div style="color: yellow;">> Battle Start! Defeat <?= $boss_data['name']; ?>!</div>
                </div>
                <div class="actions-box" id="actionPanel">
                    <button class="btn-action" onclick="playerAttack('light')">Light Atk</button>
                    <button class="btn-action" onclick="playerAttack('heavy')">Heavy Atk</button>
                    <button class="btn-action" onclick="playerHeal()">Heal</button>
                    <button class="btn-action btn-rage" onclick="playerRage()">SPARTAN RAGE</button>
                </div>
            </div>

            <div class="result-screen" id="resultScreen">
                <h2 id="resultTitle" style="font-size: 3rem; margin-bottom: 20px;">VICTORY</h2>
                <button class="btn-primary" onclick="closeBattle()">CLOSE BATTLE</button>
                <button class="btn-primary" style="margin-top: 10px; background: #333;" onclick="resetBattle()">RESTART</button>
            </div>
            
            <div style="position: absolute; top: 10px; right: 20px; color: white; cursor: pointer; font-size: 20px;" onclick="closeBattle()">✕</div>
        </div>
    </div>

    <script>
        // --- BATTLE LOGIC JAVASCRIPT ---
        
        // Initial Stats
        const maxHpPlayer = 1000;
        const maxHpEnemy = <?= $boss_data['hp']; ?>;
        let hpPlayer = maxHpPlayer;
        let hpEnemy = maxHpEnemy;
        let isPlayerTurn = true;
        let enemyName = "<?= $boss_data['name']; ?>";

        // Elements
        const modal = document.getElementById('battleModal');
        const logBox = document.getElementById('battleLog');
        const actionPanel = document.getElementById('actionPanel');
        const resultScreen = document.getElementById('resultScreen');
        const resultTitle = document.getElementById('resultTitle');

        // Sprites
        const kratosSprite = document.getElementById('kratosSprite');
        const enemySprite = document.getElementById('enemySprite');

        function openBattle() { modal.style.display = 'flex'; resetBattle(); }
        function closeBattle() { modal.style.display = 'none'; }

        function log(msg, color='white') {
            const p = document.createElement('div');
            p.innerHTML = `> ${msg}`;
            p.style.color = color;
            logBox.prepend(p);
        }

        function updateUI() {
            // Player UI
            const pctPlayer = (hpPlayer / maxHpPlayer) * 100;
            document.getElementById('hpBarPlayer').style.width = pctPlayer + "%";
            document.getElementById('hpTxtPlayer').innerText = `${hpPlayer}/${maxHpPlayer}`;
            
            // Enemy UI
            const pctEnemy = (hpEnemy / maxHpEnemy) * 100;
            document.getElementById('hpBarEnemy').style.width = pctEnemy + "%";
            document.getElementById('hpTxtEnemy').innerText = `${hpEnemy}/${maxHpEnemy}`;

            // Color Change Low HP
            document.getElementById('hpBarPlayer').style.background = pctPlayer < 30 ? 'red' : '#4cd137';
        }

        function toggleTurn(playerTurn) {
            isPlayerTurn = playerTurn;
            const btns = actionPanel.querySelectorAll('button');
            btns.forEach(btn => btn.disabled = !playerTurn);

            if (!playerTurn && hpEnemy > 0) {
                setTimeout(enemyTurn, 1500); // Enemy thinks for 1.5s
            }
        }

        // --- ACTIONS ---

        function playerAttack(type) {
            if (!isPlayerTurn) return;

            let dmg = 0;
            let msg = "";

            // Animasi Serang
            kratosSprite.classList.add('attack-anim');
            setTimeout(() => kratosSprite.classList.remove('attack-anim'), 300);

            if (type === 'light') {
                dmg = Math.floor(Math.random() * 30) + 50; // 50-80
                msg = `Kratos used Light Attack! Dealt ${dmg} dmg.`;
            } else if (type === 'heavy') {
                // Hit chance 80%
                if (Math.random() > 0.2) {
                    dmg = Math.floor(Math.random() * 50) + 80; // 80-130
                    msg = `Kratos used Heavy Attack! SMASH! Dealt ${dmg} dmg.`;
                } else {
                    msg = `Kratos missed the Heavy Attack!`;
                    dmg = 0;
                }
            }

            if (dmg > 0) {
                hpEnemy -= dmg;
                enemySprite.classList.add('shake'); // Enemy shake
                setTimeout(() => enemySprite.classList.remove('shake'), 500);
            }

            log(msg, '#4cd137');
            checkWin();
            toggleTurn(false);
            updateUI();
        }

        function playerHeal() {
            let heal = Math.floor(Math.random() * 40) + 60; // 60-100
            hpPlayer = Math.min(hpPlayer + heal, maxHpPlayer);
            log(`Kratos used Healing Stone. Recovered ${heal} HP.`, '#00a8ff');
            updateUI();
            toggleTurn(false);
        }

        function playerRage() {
            // Rage bisa gagal atau cooldown (disini kita buat simple: selalu kena tapi musuh turn next)
            let dmg = 200;
            hpEnemy -= dmg;
            log(`KRATOS USED SPARTAN RAGE!!! Dealt ${dmg} MASSIVE DAMAGE!`, 'red');
            enemySprite.classList.add('shake');
            setTimeout(() => enemySprite.classList.remove('shake'), 500);
            
            checkWin();
            updateUI();
            toggleTurn(false);
        }

        function enemyTurn() {
            if (hpEnemy <= 0) return;

            // Enemy Logic Simple
            let action = Math.random();
            let dmg = 0;

            // Animasi Serang Musuh
            enemySprite.classList.add('attack-anim');
            setTimeout(() => enemySprite.classList.remove('attack-anim'), 300);

            if (action < 0.7) {
                // Attack biasa
                dmg = Math.floor(Math.random() * 40) + 30; // 30-70
                log(`${enemyName} attacks! Dealt ${dmg} damage to Kratos.`, '#ff6b6b');
            } else {
                // Special Attack
                dmg = Math.floor(Math.random() * 50) + 60; // 60-110
                log(`${enemyName} uses SPECIAL MOVE! Dealt ${dmg} damage!`, 'red');
            }

            hpPlayer -= dmg;
            kratosSprite.classList.add('shake');
            setTimeout(() => kratosSprite.classList.remove('shake'), 500);

            checkWin();
            updateUI();
            if (hpPlayer > 0) toggleTurn(true);
        }

        function checkWin() {
            if (hpEnemy <= 0) {
                hpEnemy = 0;
                resultTitle.innerText = "VICTORY";
                resultTitle.style.color = "#4cd137";
                resultScreen.style.display = "flex";
            } else if (hpPlayer <= 0) {
                hpPlayer = 0;
                resultTitle.innerText = "DEFEATED";
                resultTitle.style.color = "red";
                resultScreen.style.display = "flex";
            }
        }

        function resetBattle() {
            hpPlayer = maxHpPlayer;
            hpEnemy = maxHpEnemy;
            isPlayerTurn = true;
            logBox.innerHTML = '';
            log("Battle Start! Choose your move.", "yellow");
            resultScreen.style.display = "none";
            toggleTurn(true);
            updateUI();
        }
    </script>
</body>
</html>