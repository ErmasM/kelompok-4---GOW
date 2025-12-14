<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: index.php"); // Diubah dari login.php
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: series.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: series.php");
    exit;
}

$series = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM series WHERE id = '$id'"));
$timeline = mysqli_query($conn, "SELECT * FROM timeline WHERE series_id = '$id' ORDER BY urutan ASC");
$characters = mysqli_query($conn, "SELECT * FROM characters WHERE series_id = '$id'");
$weapons = mysqli_query($conn, "SELECT * FROM weapons WHERE series_id = '$id'");
$realms = mysqli_query($conn, "SELECT * FROM realms");

$boss_name = !empty($series['boss_name']) ? $series['boss_name'] : "UNKNOWN GOD";
$boss_hp   = !empty($series['boss_hp'])   ? $series['boss_hp']   : 1500;
$boss_img  = !empty($series['boss_img'])  ? $series['boss_img']  : "boss_default.png";
$boss_quote = !empty($series['boss_quote']) ? $series['boss_quote'] : "You cannot defeat me, mortal!";

$theme_color = ($id >= 4) ? '#00bfff' : '#b30000';
$theme_secondary = ($id >= 4) ? '#003366' : '#660000';

$timeline_data = array();
$timeline_count = 0;

while($tl = mysqli_fetch_assoc($timeline)) {
    $timeline_data[] = $tl;
    $timeline_count++;
}

if ($timeline_count < 5) {
    $default_timelines = getDefaultTimeline($id, $series['judul']);
    $needed = 5 - $timeline_count;
    
    for ($i = 0; $i < $needed && $i < count($default_timelines); $i++) {
        $timeline_data[] = $default_timelines[$i];
        $timeline_count++;
    }
}

$detailed_story = getDetailedStory($id, $series['judul'], $series['deskripsi']);

function getDefaultTimeline($series_id, $series_title) {
    $timelines = array();
    
    if ($series_id >= 4) { 
        $timelines = array(
            array(
                'judul_chapter' => 'THE DEATH OF BALDUR',
                'deskripsi' => 'The tragic death of Baldur at the hands of Kratos sets Ragnarok in motion, forcing Kratos and Atreus to face the consequences of their actions.',
                'gambar' => 'timeline_norse1.jpg'
            ),
            array(
                'judul_chapter' => 'JOURNEY TO JOTUNHEIM',
                'deskripsi' => 'Kratos and Atreus travel through Midgard, Alfheim, and Helheim to reach Jotunheim and scatter Faye\'s ashes, discovering their true destiny.',
                'gambar' => 'timeline_norse2.jpg'
            ),
            array(
                'judul_chapter' => 'CONFRONTING THOR',
                'deskripsi' => 'The epic battle against Thor, God of Thunder, as he seeks vengeance for the death of his sons Magni and Modi.',
                'gambar' => 'timeline_norse3.jpg'
            ),
            array(
                'judul_chapter' => 'RAGNAROK BEGINS',
                'deskripsi' => 'The world serpent emerges, giants awaken, and the final battle for the fate of the Nine Realms commences.',
                'gambar' => 'timeline_norse4.jpg'
            ),
            array(
                'judul_chapter' => 'A NEW BEGINNING',
                'deskripsi' => 'After defeating Odin, Kratos and Atreus rebuild the realms and establish a new pantheon, breaking the cycle of vengeance.',
                'gambar' => 'timeline_norse5.jpg'
            )
        );
    } else { 
        $timelines = array(
            array(
                'judul_chapter' => 'THE OATH TO ARES',
                'deskripsi' => 'Kratos, a Spartan general on the verge of defeat, swears allegiance to Ares, God of War, in exchange for power to destroy his enemies.',
                'gambar' => 'timeline_greek1.jpg'
            ),
            array(
                'judul_chapter' => 'THE GREAT WAR',
                'deskripsi' => 'Kratos becomes the Ghost of Sparta, serving Ares for years until the god tricks him into killing his own family, creating his eternal guilt.',
                'gambar' => 'timeline_greek2.jpg'
            ),
            array(
                'judul_chapter' => 'PANDORA\'S TEMPLE',
                'deskripsi' => 'To break free from Ares, Kratos journeys through the Desert of Lost Souls to find Pandora\'s Box and gain the power to kill a god.',
                'gambar' => 'timeline_greek3.jpg'
            ),
            array(
                'judul_chapter' => 'BATTLE WITH ARES',
                'deskripsi' => 'The epic confrontation in Athens where Kratos uses Pandora\'s Box to defeat Ares and become the new God of War.',
                'gambar' => 'timeline_greek4.jpg'
            ),
            array(
                'judul_chapter' => 'WRATH OF THE GODS',
                'deskripsi' => 'The other Olympian gods betray Kratos, leading to his quest for vengeance against the entire Greek pantheon.',
                'gambar' => 'timeline_greek5.jpg'
            )
        );
    }
    
    switch(strtoupper($series_title)) {
        case 'GOD OF WAR (2005)':
            $timelines[0]['judul_chapter'] = 'BATTLE OF ATTICA';
            $timelines[0]['deskripsi'] = 'Kratos leads the Spartan army against barbarian hordes, facing defeat until he calls upon Ares for salvation.';
            break;
        case 'GOD OF WAR II':
            $timelines[0]['judul_chapter'] = 'BETRAYAL BY ZEUS';
            $timelines[0]['deskripsi'] = 'Zeus strips Kratos of his godhood and kills him, but the Titans save him to seek revenge against Olympus.';
            break;
        case 'GOD OF WAR III':
            $timelines[0]['judul_chapter'] = 'ASCENT TO OLYMPUS';
            $timelines[0]['deskripsi'] = 'Kratos, riding Gaia, leads the Titans in an assault on Mount Olympus, beginning the final war against the gods.';
            break;
        case 'GOD OF WAR (2018)':
            $timelines[0]['judul_chapter'] = 'FAYE\'S DEATH';
            $timelines[0]['deskripsi'] = 'After his wife Faye dies, Kratos must fulfill her final wish: to spread her ashes from the highest peak in the Nine Realms.';
            break;
        case 'GOD OF WAR RAGNAROK':
            $timelines[0]['judul_chapter'] = 'FIMBULWINTER';
            $timelines[0]['deskripsi'] = 'Three years of endless winter have passed since Baldur\'s death, and Ragnarok approaches as Kratos and Atreus prepare for war.';
            break;
    }
    
    return $timelines;
}

function getDetailedStory($series_id, $series_title, $base_description) {
    $detailed_stories = array();
    
    if ($series_id >= 4) { 
        $detailed_stories = array(
            'GOD OF WAR (2018)' => "In the frozen wilds of Midgard, a changed Kratos lives in isolation with his son Atreus. Having left his bloody past in Greece behind, Kratos now seeks to live as a man outside the shadow of the gods. But when his wife Faye dies, Kratos must fulfill her final wish: to spread her ashes from the highest peak in the Nine Realms.

            Reluctantly, Kratos takes Atreus on a perilous journey through a dangerous world filled with gods, monsters, and prophecies. Armed with the Leviathan Axe—a weapon forged by the dwarves Brok and Sindri—and haunted by the memories of his past, Kratos must not only protect his son but also teach him to survive in a harsh world where every decision has consequences.

            As they travel through stunning landscapes, they encounter the mysterious Witch of the Woods, face the wrath of the Æsir gods, and uncover the truth about Atreus' divine heritage. The journey becomes a test of their bond as father and son, forcing Kratos to confront the ghosts of Sparta while Atreus discovers what it means to be both god and giant.",
            
            'GOD OF WAR RAGNAROK' => "Three years have passed since Kratos and Atreus scattered Faye's ashes. Fimbulwinter—the great winter that precedes Ragnarok—rages across the Nine Realms. Kratos and Atreus find themselves hunted by Odin's forces, particularly Thor, who seeks vengeance for the death of his sons.

            As prophecies unfold and alliances shift, Kratos must decide whether to run from destiny or meet it head-on. Atreus, now a teenager grappling with his identity as Loki, seeks answers about his role in the coming apocalypse. Together, they embark on a desperate journey across all Nine Realms: from the frozen lakes of Midgard to the golden halls of Asgard.

            They will face gods like Odin, Thor, and Freya, forge uneasy alliances with Tyr and the dwarves, and confront their own conflicting desires—Kratos' wish for peace versus Atreus' need to understand his destiny. The choices they make will determine not only their fate but the fate of all Nine Realms as Ragnarok begins."
        );
    } else { 
        $detailed_stories = array(
            'GOD OF WAR (2005)' => "Kratos, a Spartan general on the brink of defeat against a barbarian horde, calls upon Ares, the God of War. In exchange for victory, Kratos pledges eternal servitude. Ares grants him the Blades of Chaos, weapons that become extensions of Kratos' arms.

            For years, Kratos serves as Ares' champion, spreading chaos across Greece. But the god of war, seeking to sever Kratos' last mortal ties, tricks him into killing his own wife and daughter. Covered in their ashes, which permanently bleach his skin white, Kratos becomes the Ghost of Sparta—haunted by nightmares of his deed.

            The Oracle of Athens tells Kratos he can be forgiven if he serves the other gods. After ten years of service, Athena offers him a final task: kill Ares, who has laid siege to Athens. To defeat a god, Kratos must find Pandora's Box, hidden in the Temple of Pandora within the Desert of Lost Souls. After overcoming deadly traps and monstrous guardians, Kratos claims the box's power and faces Ares in an epic battle that will determine the fate of Athens—and his own soul.",
            
            'GOD OF WAR II' => "Having become the new God of War, Kratos rules from Olympus but finds no peace. The other gods resent him, and his nightmares continue. When Kratos destroys a city in his rage, Athena warns him that Zeus will not tolerate his recklessness.

            Zeus betrays Kratos, stripping him of his godhood and killing him. But the Titan Gaia intervenes, saving Kratos and telling him he can change his fate by finding the Sisters of Fate. Kratos journeys through the Island of Creation, battling mythical beasts and solving ancient puzzles.

            Along the way, he encounters old enemies like the barbarian king, new allies like the Titan Thera, and learns shocking truths about his past. Kratos discovers that Zeus is his father and that he was meant to be Olympus' savior, not its destroyer. With this knowledge, Kratos must decide whether to change his past or embrace his destiny as the destroyer of Olympus.",
            
            'GOD OF WAR III' => "Kratos, riding Gaia and the other Titans, storms Mount Olympus in the final assault against the gods. But Zeus betrays the Titans, and Kratos is cast into the River Styx, losing most of his power.

            Clawing his way back from the underworld, Kratos is guided by Athena's spirit to find the Flame of Olympus, the only thing that can kill Zeus. His quest takes him through the depths of Hades, the labyrinth of Daedalus, and the gardens of Olympus.

            One by one, Kratos kills the Olympian gods: Poseidon, Hades, Hermes, Helios, and Hercules. Each death unleashes catastrophes upon the world—floods, plagues, and eternal darkness. Kratos learns that Pandora holds the key to defeating Zeus, but using her requires another sacrifice. In the end, Kratos must choose between revenge and redemption, with the fate of the world hanging in the balance."
        );
    }
    
    if (isset($detailed_stories[$series_title])) {
        return $detailed_stories[$series_title];
    } else {
        return $base_description;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($series['judul']); ?> - WAR OF GODS</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=MedievalSharp&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        /* === RESET & VARIABLES === */
        * { 
            margin: 0; padding: 0; box-sizing: border-box; 
            -webkit-tap-highlight-color: transparent;
        }
        
        :root {
            --gold: #cfa35e;
            --gold-dark: #8a6d3b;
            --blood-red: <?= $theme_color; ?>;
            --blood-dark: <?= $theme_secondary; ?>;
            --dark-bg: #0a0a0a;
            --metal: #7d7d7d;
            --bronze: #cd7f32;
        }

        html { 
            scroll-behavior: smooth;
            background-color: var(--dark-bg);
        }

        body {
            font-family: 'Cinzel', serif;
            background-color: var(--dark-bg);
            color: #e0e0e0;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Texture */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(179, 0, 0, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(0, 0, 0, 0.3) 0%, transparent 50%);
            opacity: 0.5;
            z-index: -1;
            pointer-events: none;
        }

        /* === NAVBAR === */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 50px; position: fixed; width: 100%; top: 0; z-index: 1000;
            background: rgba(5, 5, 5, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--gold);
            box-shadow: 0 5px 30px rgba(0,0,0,0.7);
        }
        
        .nav-logo { 
            display: flex; align-items: center; gap: 15px;
        }
        
        .nav-logo img { 
            height: 45px;
            filter: drop-shadow(0 0 5px rgba(207, 163, 94, 0.5));
        }
        
        .logo-text {
            font-family: 'MedievalSharp', cursive;
            font-size: 1.2rem;
            color: var(--gold);
            letter-spacing: 3px;
        }
        
        .nav-links {
            display: flex; gap: 25px;
        }
        
        .nav-links a {
            text-decoration: none; color: #ccc; font-size: 0.9rem; letter-spacing: 2px;
            text-transform: uppercase; padding: 8px 15px; border: 1px solid transparent;
            transition: all 0.3s ease; position: relative;
        }
        
        .nav-links a:hover { 
            color: var(--gold); 
            border-color: rgba(207, 163, 94, 0.3);
            background: rgba(207, 163, 94, 0.05);
        }
        
        .nav-logout { 
            color: #ff5555 !important; font-weight: bold;
            border: 1px solid rgba(255, 85, 85, 0.3) !important;
        }

        /* === SEPARATOR === */
        .section-separator {
            height: 80px; position: relative;
            background: linear-gradient(to bottom, transparent 0%, rgba(207, 163, 94, 0.1) 50%, transparent 100%);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            margin: 30px 0;
        }
        
        .separator-icon {
            font-size: 2rem; color: var(--gold); text-shadow: 0 0 20px rgba(207, 163, 94, 0.5);
            position: relative; z-index: 2; background: var(--dark-bg); padding: 0 20px;
        }
        
        .separator-line {
            position: absolute; height: 1px; width: 40%;
            background: linear-gradient(90deg, transparent, var(--gold), transparent); top: 50%;
        }
        
        .separator-line.left { left: 5%; }
        .separator-line.right { right: 5%; }

        /* === HERO === */
        .hero {
            height: 100vh; width: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%),
                linear-gradient(to right, rgba(0,0,0,0.9), rgba(0,0,0,0.4)),
                url('asset/<?= $series['gambar']; ?>');
            background-size: cover; background-position: center; background-attachment: fixed;
            display: flex; flex-direction: column; justify-content: center; align-items: flex-start;
            padding-left: 8%; position: relative; overflow: hidden;
        }
        
        .hero::after {
            content: ""; position: absolute; bottom: 0; left: 0;
            width: 100%; height: 150px;
            background: linear-gradient(to top, var(--dark-bg), transparent);
        }
        
        .hero-content { 
            position: relative; z-index: 2; max-width: 700px;
            animation: fadeInUp 1s ease-out;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hero-logo { 
            width: 300px; margin-bottom: 20px; 
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.8));
        }
        
        .hero-title { 
            font-size: 4rem; color: white; line-height: 1; margin-bottom: 10px; 
            text-shadow: 0 5px 15px rgba(0,0,0,0.8);
            background: linear-gradient(to right, #fff, var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }
        
        .hero-subtitle { 
            font-family: 'Lato', sans-serif; font-size: 1.2rem; color: #ddd; 
            text-shadow: 0 2px 4px rgba(0,0,0,0.8); margin-bottom: 40px; 
            letter-spacing: 3px; font-weight: 300;
        }
        
        .btn-start {
            background: linear-gradient(45deg, var(--blood-dark), var(--blood-red));
            color: white; padding: 15px 40px; text-decoration: none;
            font-size: 1.1rem; letter-spacing: 2px; border: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.2);
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 10px;
        }
        
        .btn-start:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 40px rgba(179, 0, 0, 0.4);
        }

        /* === SECTION CONTAINERS === */
        .section-container { 
            padding: 80px 10%; position: relative;
        }
        
        .section-title {
            text-align: center; font-size: 2.5rem; color: #fff; letter-spacing: 4px;
            margin: 0 auto 60px auto; text-transform: uppercase;
            display: table; padding-bottom: 10px; position: relative;
        }
        
        .section-title::after {
            content: ''; position: absolute; bottom: 0; left: 50%;
            transform: translateX(-50%); width: 80px; height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        /* === STORY SECTION === */
        .begin-content { 
            display: flex; gap: 50px; align-items: flex-start;
            position: relative;
        }
        
        .throne-img-box { 
            flex: 0 0 400px; position: relative; overflow: hidden;
            border: 2px solid var(--gold-dark);
            box-shadow: 0 15px 40px rgba(0,0,0,0.7);
        }
        
        .throne-img { 
            width: 100%; display: block; 
            filter: sepia(0.2) contrast(1.1); transition: 0.5s;
        }
        
        .throne-img-box:hover .throne-img { 
            transform: scale(1.03);
            filter: sepia(0) contrast(1.2);
        }
        
        .story-text {
            font-family: 'Lato', sans-serif; font-size: 1.1rem; 
            line-height: 1.8; text-align: justify; color: #ddd;
            flex: 1;
        }

        /* === TIMELINE === */
        .timeline-section {
            background: linear-gradient(to bottom, #111, #000);
            position: relative; overflow: hidden;
        }
        
        .timeline-wrapper { 
            position: relative; max-width: 1000px; margin: 0 auto;
            padding: 40px 0;
        }
        
        .timeline-line {
            position: absolute; width: 3px; 
            background: linear-gradient(to bottom, transparent, var(--blood-red), var(--gold), transparent);
            top: 0; bottom: 0; left: 50%; transform: translateX(-50%);
        }
        
        .timeline-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 60px; position: relative; width: 100%;
            opacity: 0; transform: translateY(50px); transition: 1s ease;
        }
        
        .timeline-row.visible { opacity: 1; transform: translateY(0); }
        
        .omega-node {
            position: absolute; left: 50%; transform: translateX(-50%);
            font-size: 2rem; color: var(--gold); background: #111;
            padding: 8px 15px; font-weight: bold; z-index: 2;
            text-shadow: 0 0 15px var(--gold); transition: 0.5s;
            border: 2px solid var(--gold-dark); border-radius: 50%;
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
        }
        
        .timeline-row:hover .omega-node { 
            transform: translateX(-50%) scale(1.1); 
            background: var(--blood-dark);
        }
        
        .tl-card {
            width: 45%; background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
            padding: 20px; border: 2px solid var(--gold);
            box-shadow: 0 10px 25px rgba(0,0,0,0.6); 
            position: relative; transition: 0.3s; overflow: hidden;
        }
        
        .tl-card:hover { 
            transform: translateY(-5px); border-color: var(--blood-red);
            box-shadow: 0 15px 30px rgba(179, 0, 0, 0.3);
        }
        
        .tl-img-container {
            position: relative; overflow: hidden; margin-bottom: 15px;
            border: 1px solid #333; height: 180px;
        }
        
        .tl-img { 
            width: 100%; height: 100%; object-fit: cover; 
            transition: 0.5s;
        }
        
        .tl-card:hover .tl-img {
            transform: scale(1.05); filter: brightness(1.1);
        }
        
        .tl-title { 
            font-size: 1.1rem; font-weight: bold; color: var(--gold); 
            text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;
        }
        
        .tl-desc { 
            font-family: 'Lato'; font-size: 0.9rem; color: #aaa; line-height: 1.5; 
        }
        
        .tl-number {
            position: absolute; top: 10px; right: 10px;
            background: var(--blood-red); color: white;
            width: 25px; height: 25px;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; border-radius: 50%; font-size: 0.7rem;
            z-index: 9999;
        }

        .timeline-row.left .tl-card { margin-right: auto; }
        .timeline-row.right .tl-card { margin-left: auto; }

        /* === CHARACTERS === */
        .char-section {
            background: 
                radial-gradient(circle at 30% 50%, rgba(0,0,0,0.8) 0%, transparent 70%),
                linear-gradient(to bottom, #000, #111 30%, #222);
            position: relative; overflow: hidden;
        }
        
        .char-scroll-container {
            display: flex; overflow-x: auto; gap: 30px; padding: 30px;
            scroll-behavior: smooth; scrollbar-width: none; 
            -ms-overflow-style: none; cursor: grab;
        }
        
        .char-scroll-container:active { cursor: grabbing; }
        .char-scroll-container::-webkit-scrollbar { display: none; }
        
        .char-card {
            flex: 0 0 250px; 
            background: linear-gradient(145deg, rgba(20, 20, 30, 0.9), rgba(10, 10, 15, 0.9));
            border: 1px solid #333; display: flex; flex-direction: column;
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }
        
        .char-card:hover { 
            transform: translateY(-5px); border-color: var(--gold);
            box-shadow: 0 15px 30px rgba(207, 163, 94, 0.2);
        }
        
        .char-img-container { height: 280px; overflow: hidden; position: relative; }
        
        .char-img { 
            width: 100%; height: 100%; object-fit: cover;
            transition: 0.5s;
        }
        
        .char-card:hover .char-img { transform: scale(1.05); }
        
        .char-info { 
            padding: 15px; text-align: center; width: 100%; 
            border-top: 1px solid #333;
            background: rgba(10, 10, 15, 0.8);
            flex-grow: 1; display: flex;
            flex-direction: column; justify-content: center;
        }
        
        .char-name { 
            font-size: 1rem; color: #fff; text-transform: uppercase; 
            margin-bottom: 5px; letter-spacing: 1px;
        }
        
        .char-role { 
            font-family: 'Lato'; font-size: 0.8rem; color: var(--gold); 
            text-transform: uppercase; letter-spacing: 1px;
        }

        /* === ARSENAL === */
        .arsenal-section { 
            background: linear-gradient(to bottom, #000, #111 30%);
            text-align: center; position: relative; overflow: hidden;
        }
        
        .arsenal-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px; margin-top: 50px;
        }
        
        .weapon-card {
            background: linear-gradient(145deg, rgba(30, 35, 45, 0.8), rgba(15, 20, 30, 0.9));
            border: 1px solid #333; padding: 20px; 
            position: relative; cursor: pointer; 
            transition: all 0.3s ease; overflow: hidden; height: 450px;
            display: flex; flex-direction: column;
        }
        
        .weapon-card:hover { 
            transform: translateY(-10px);
            border-color: var(--gold);
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }
        
        .wp-img-box { 
            height: 180px; display: flex; align-items: center; 
            justify-content: center; transition: .3s; position: relative;
        }
        
        .wp-img { 
            max-width: 70%; max-height: 100%; object-fit: contain;
            filter: drop-shadow(0 10px 10px #000); transition: .5s;
        }
        
        .weapon-card:hover .wp-img {
            transform: rotate(-5deg) scale(1.05);
            filter: drop-shadow(0 15px 15px rgba(179, 0, 0, 0.3));
        }
        
        .wp-title {
            font-size: 1.2rem; color: var(--gold); margin: 15px 0 10px;
            text-transform: uppercase;
        }
        
        .wp-desc {
            font-family: 'Lato'; font-size: 0.85rem; color: #bbb;
            line-height: 1.6; flex-grow: 1;
        }
        
        .wp-stats {
            position: absolute; bottom: 0; left: 0; width: 100%;
            padding: 20px; background: rgba(10, 10, 15, 0.95);
            transform: translateY(100%); transition: .4s ease;
            display: flex; flex-direction: column; gap: 10px;
            border-top: 2px solid var(--blood-red);
        }
        
        .weapon-card:hover .wp-stats { transform: translateY(0); }

        /* === STAT BARS === */
        .stat-row { display: flex; flex-direction: column; gap: 6px; }
        .stat-label { display:flex; justify-content:space-between; font-family:'Lato'; color:#ddd; font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; }
        .bar-bg { width:100%; height:10px; background:#1a1a1a; border-radius:6px; overflow:hidden; border:1px solid #222; }
        .bar-fill { height:100%; width:0; transition: width .6s ease; border-radius:6px; }
        .bar-damage  { background: linear-gradient(90deg,#ff3b3b,#ff6b6b); box-shadow: 0 6px 18px rgba(255,59,59,0.15); }
        .bar-speed   { background: linear-gradient(90deg,#ffd700,#ffe47a); box-shadow: 0 6px 18px rgba(255,215,0,0.12); }
        .bar-range   { background: linear-gradient(90deg,#00a3ff,#66ccff); box-shadow: 0 6px 18px rgba(0,163,255,0.12); }
        .bar-crown   { background: linear-gradient(90deg,#8a2be2,#b28cff); box-shadow: 0 6px 18px rgba(138,43,226,0.12); }

        /* === BOSS BATTLE === */
        .boss-section { 
            background: radial-gradient(circle at 50% 0%, rgba(179, 0, 0, 0.15) 0%, transparent 60%), linear-gradient(to bottom, #111, #000); 
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 100px 20px; min-height: 60vh;
        }

        .boss-quote { 
            max-width: 800px; width: 90%; margin: 0 auto 40px; 
            font-size: 1.5rem; color: var(--gold); font-style: italic; 
            padding: 30px; border-left: 4px solid var(--blood-red); 
            background: rgba(0,0,0,0.4); text-align: center;
            display: flex; flex-direction: column; align-items: center;
        }

        /* === BATTLE MODAL & CONTAINER === */
        .battle-modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95); z-index: 2000;
            justify-content: center; align-items: center;
            backdrop-filter: blur(5px);
        }

        .battle-container {
            width: 900px; 
            min-height: 600px; 
            position: relative;
            background: radial-gradient(circle, rgba(20,0,0,0.9), #000), 
                        url('asset/arena.jpg') no-repeat center/cover;
            border: 3px solid var(--blood-red);
            box-shadow: 0 0 50px rgba(139, 0, 0, 0.6);
            display: flex; flex-direction: column;
            overflow: hidden;
        }

        .btn-game-exit {
            position: absolute; top: 0; right: 0;
            background: #500; color: #fff; border: none;
            border-bottom: 2px solid var(--blood-red);
            border-left: 2px solid var(--blood-red);
            padding: 8px 20px; cursor: pointer;
            font-family: 'Cinzel', serif; font-weight: bold;
            z-index: 101; transition: all 0.3s ease;
        }

        .btn-game-exit:hover {
            background: red; box-shadow: 0 0 15px red;
        }

        .battle-header {
            padding: 40px 30px 20px; 
            background: linear-gradient(to bottom, rgba(0,0,0,0.9), rgba(0,0,0,0.6));
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid var(--blood-red);
            box-shadow: 0 5px 15px rgba(0,0,0,0.8);
        }
        
        .hp-box { width: 42%; color: white; font-family: 'Cinzel', serif; position: relative; }
        .hp-name { margin-bottom: 5px; font-size: 1.1rem; text-shadow: 2px 2px 0 #000; }
        
        .hp-bar-bg { 
            width: 100%; height: 25px; background: #111; 
            border: 2px solid #444; transform: skewX(-10deg); overflow: hidden;
        }
        .hp-bar-fill { height: 100%; transition: width 0.3s ease-out; box-shadow: inset 0 0 10px rgba(0,0,0,0.5); }
        
        .battle-field {
            flex: 1; display: flex; justify-content: space-between; align-items: flex-end;
            padding: 20px 80px 40px; position: relative;
        }
        
        .fighter { width: 200px; height: 300px; position: relative; transition: transform 0.2s; }
        .fighter img { width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 10px 20px black); }
        .fighter::after {
            content: ''; position: absolute; bottom: -10px; left: 10%; width: 80%; height: 20px;
            background: black; filter: blur(10px); opacity: 0.7; z-index: -1;
        }
        #kratosSprite { transform: scaleX(-1); }
        
        /* Animations */
        .shake { animation: shake 0.5s; }
        @keyframes shake { 
            0%, 100% { transform: translateX(0); } 
            25% { transform: translateX(-10px); } 
            75% { transform: translateX(10px); } 
        }
        
        .attack-anim { animation: lunge 0.3s; }
        @keyframes lunge { 
            0%, 100% { transform: translateX(0) scaleX(-1); } 
            50% { transform: translateX(40px) scaleX(-1); } 
        }
        
        .battle-controls {
            height: auto; min-height: 140px;
            background: rgba(10, 0, 0, 0.95); 
            border-top: 3px solid var(--blood-red);
            box-shadow: 0 -5px 20px rgba(139, 0, 0, 0.3);
            display: flex; padding: 20px; gap: 20px;
            align-items: stretch;
        }
        
        .log-box { 
            flex: 1.5; border: 1px solid #555; 
            background: rgba(0,0,0,0.6); padding: 10px;
            font-family: 'Lato'; color: #ccc; font-size: 0.9rem; 
            overflow-y: auto; max-height: 120px;
            display: flex; flex-direction: column-reverse;
            border-left: 3px solid var(--gold);
        }
        
        .actions-box { 
            flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; 
        }
        
        .btn-action {
            background: linear-gradient(to bottom, #222, #111);
            border: 1px solid #555; color: #ddd;
            font-family: 'Cinzel'; cursor: pointer; transition: 0.2s;
            text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;
            padding: 10px 5px; position: relative; overflow: hidden;
        }
        .btn-action:hover:not(:disabled) { 
            background: var(--blood-red); color: white; border-color: red;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.6);
        }
        .btn-action:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .btn-action[onclick="playerRage()"] {
            grid-column: span 2; background: #500; border: 1px solid red; font-weight: bold;
        }
        
        .result-screen {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.95); z-index: 100; display: none;
            flex-direction: column; justify-content: center; align-items: center;
        }
        
        .result-title { font-size: 3rem; color: #fff; text-shadow: 0 0 20px var(--gold); margin-bottom: 20px; }

        /* === FOOTER === */
        .footer {
            padding: 60px 0 30px; text-align: center;
            background: linear-gradient(to top, #000, #111);
            border-top: 2px solid var(--gold);
        }
        
        .footer-text {
            font-family: 'MedievalSharp', cursive; font-size: 1.5rem; color: var(--gold);
            margin-bottom: 20px; letter-spacing: 2px;
        }
        
        .btn-back {
            background: linear-gradient(45deg, var(--blood-dark), var(--blood-red));
            color: white; padding: 12px 30px; text-decoration: none;
            border: 1px solid rgba(255,255,255,0.2); transition: .3s;
            display: inline-block; font-size: 0.9rem;
        }
        .btn-back:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(179,0,0,0.3); }

        /* === RESPONSIVE === */
        @media (max-width: 992px) {
            .navbar { padding: 12px 20px; }
            .nav-links { gap: 15px; }
            .nav-links a { font-size: 0.8rem; padding: 6px 10px; }
            .hero { padding-left: 5%; }
            .hero-title { font-size: 3rem; }
            .hero-logo { width: 250px; }
            .begin-content { flex-direction: column; gap: 30px; }
            .throne-img-box { width: 100%; flex: auto; }
            .timeline-row { flex-direction: column; gap: 20px; margin-bottom: 40px; }
            .tl-card { width: 90%; }
            .timeline-line, .omega-node { display: none; }
            .battle-container { width: 95%; min-height: auto; }
            .section-container { padding: 60px 5%; }
        }
        
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1rem; }
            .btn-start { padding: 12px 30px; font-size: 1rem; }
            .section-title { font-size: 2rem; margin-bottom: 40px; }
            .battle-field { padding: 0 20px 30px; }
            .fighter { width: 150px; height: 200px; }
            .actions-box { grid-template-columns: 1fr; }
            .arsenal-grid { grid-template-columns: 1fr; }
            .char-card { flex: 0 0 220px; }
            .wp-stats { padding: 15px; } .bar-bg { height: 8px; }
        }
        
        @media (max-width: 576px) {
            .navbar { flex-direction: column; gap: 10px; padding: 10px; }
            .nav-links { width: 100%; justify-content: center; flex-wrap: wrap; }
            .hero-title { font-size: 2rem; }
            .hero-logo { width: 200px; }
            .section-title { font-size: 1.8rem; }
            .footer-text { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo">
            <img src="asset/logo.png" alt="GOD OF WAR">
            <div class="logo-text">WAR OF GODS</div>
        </div>
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
            <h2 class="hero-subtitle">A JOURNEY OF VENGEANCE AND REDEMPTION</h2>
                        
            <a href="#boss-fight" class="btn-start">⚔ CHALLENGE THE GODS ⚔</a>
        </div>
    </section>

    <div class="section-separator">
        <div class="separator-line left"></div>
        <div class="separator-icon">⚔</div>
        <div class="separator-line right"></div>
    </div>

    <section class="section-container">
        <h2 class="section-title">THE BEGINNING</h2>
        <div class="begin-content">
            <div class="throne-img-box">
                <img src="asset/<?= $series['gambar']; ?>" class="throne-img" alt="<?= $series['judul']; ?>">
            </div>
            <div class="story-text">
                <?= nl2br($detailed_story); ?>
            </div>
        </div>
    </section>

    <div class="section-separator">
        <div class="separator-line left"></div>
        <div class="separator-icon">🛡</div>
        <div class="separator-line right"></div>
    </div>

    <section class="timeline-section section-container">
        <h2 class="section-title">JOURNEY TIMELINE</h2>
        <p style="text-align: center; color: #aaa; margin-bottom: 40px; max-width: 700px; margin-left: auto; margin-right: auto; font-family: 'Lato'; font-size: 0.95rem;">
            Follow the bloody path of Kratos through five pivotal moments in this chapter of his saga.
        </p>
        
        <div class="timeline-wrapper">
            <div class="timeline-line"></div>
            <?php 
            $i = 1;
            foreach($timeline_data as $tl): 
                $pos = ($i % 2 == 0) ? 'right' : 'left';
            ?>
            <div class="timeline-row <?= $pos; ?>">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <div class="tl-number"><?= $i; ?></div>
                    <div class="tl-img-container">
                        <img src="asset/<?= $tl['gambar']; ?>" class="tl-img" alt="<?= $tl['judul_chapter']; ?>">
                    </div>
                    <div class="tl-title"><?= $tl['judul_chapter']; ?></div>
                    <div class="tl-desc"><?= $tl['deskripsi']; ?></div>
                </div>
            </div>
            <?php $i++; endforeach; ?>
        </div>
    </section>

    <div class="section-separator">
        <div class="separator-line left"></div>
        <div class="separator-icon">👑</div>
        <div class="separator-line right"></div>
    </div>

    <section class="char-section section-container">
        <h2 class="section-title">CHARACTERS</h2>
        <div class="char-scroll-container">
            <?php while($char = mysqli_fetch_assoc($characters)): ?>
            <div class="char-card">
                <div class="char-img-container">
                    <img src="asset/<?= $char['gambar']; ?>" class="char-img" alt="<?= $char['nama']; ?>">
                </div>
                <div class="char-info">
                    <div class="char-name"><?= $char['nama']; ?></div>
                    <div class="char-role"><?= $char['peran']; ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <div class="section-separator">
        <div class="separator-line left"></div>
        <div class="separator-icon">🗡</div>
        <div class="separator-line right"></div>
    </div>

    <section class="arsenal-section section-container">
        <h2 class="section-title">GODLY ARSENAL</h2>
        <div class="arsenal-grid">
            <?php while($wp = mysqli_fetch_assoc($weapons)): ?>
            <div class="weapon-card">
                <div class="wp-img-box">
                    <img src="asset/<?= $wp['gambar']; ?>" class="wp-img" alt="<?= $wp['nama_senjata']; ?>">
                </div>
                <h3 class="wp-title"><?= $wp['nama_senjata']; ?></h3>
                <p class="wp-desc"><?= $wp['deskripsi']; ?></p>
                <?php
                
                    $damage = isset($wp['stat_damage']) ? (int)$wp['stat_damage'] : 50;
                    $speed  = isset($wp['stat_speed'])  ? (int)$wp['stat_speed']  : 50;
                    $range  = isset($wp['stat_range'])  ? (int)$wp['stat_range']  : 50;
                    $crown  = isset($wp['stat_crown'])  ? (int)$wp['stat_crown']  : 50;
                ?>
                <div class="wp-stats">
                    <div class="stat-row">
                        <div class="stat-label"><span>Damage</span><span><?= $damage; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill bar-damage" style="width:<?= $damage; ?>%;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>Speed</span><span><?= $speed; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill bar-speed" style="width:<?= $speed; ?>%;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>Range</span><span><?= $range; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill bar-range" style="width:<?= $range; ?>%;"></div></div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label"><span>Crown Control</span><span><?= $crown; ?>/100</span></div>
                        <div class="bar-bg"><div class="bar-fill bar-crown" style="width:<?= $crown; ?>%;"></div></div>
                    </div>
                </div>
            </div>
             <?php endwhile; ?>
        </div>
    </section>

    <div class="section-separator">
        <div class="separator-line left"></div>
        <div class="separator-icon">⚡</div>
        <div class="separator-line right"></div>
    </div>

    <section id="boss-fight" class="boss-section">
        <h2 class="section-title" style="color:var(--blood-red);">FINAL CONFRONTATION</h2>
        
        <div class="boss-quote">
            "<?= $boss_quote; ?>"
            <div style="text-align: right; margin-top: 10px; font-size: 0.9rem; color: var(--gold);">
                - <?= $boss_name; ?>
            </div>
        </div>
        
        <p style="color:#ccc; margin-bottom:30px; max-width: 700px; margin-left: auto; margin-right: auto; font-family: 'Lato'; text-align: center; font-size: 0.95rem;">
            Face the ultimate challenge. Defeat <?= $boss_name; ?> to prove your worth as a god slayer.
        </p>
        
        <button onclick="openBattle()" class="btn-start" style="cursor:pointer; font-size: 1.2rem; padding: 15px 40px;">
            ⚔ CHALLENGE <?= strtoupper($boss_name); ?> ⚔
        </button>
    </section>

    <div class="battle-modal" id="battleModal">
        <div class="battle-container">
            <button class="btn-game-exit" onclick="closeBattle()">✕ EXIT BATTLE</button>

            <div class="battle-header">
                <div class="hp-box">
                    <div class="hp-name">KRATOS <span id="hpTxtPlayer" style="color:#4cd137">1000</span></div>
                    <div class="hp-bar-bg">
                        <div class="hp-bar-fill" id="hpBarPlayer" style="width: 100%; background: linear-gradient(90deg, #4cd137, #2ecc71);"></div>
                    </div>
                </div>
                
                <div style="font-family:'Cinzel'; font-size:2rem; color:var(--blood-red); font-weight:bold;">VS</div>

                <div class="hp-box" style="text-align: right;">
                    <div class="hp-name"><?= $boss_name; ?> <span id="hpTxtEnemy" style="color:red"><?= $boss_hp; ?></span></div>
                    <div class="hp-bar-bg">
                        <div class="hp-bar-fill" id="hpBarEnemy" style="width: 100%; background: linear-gradient(90deg, #c0392b, #e74c3c); float:right;"></div>
                    </div>
                </div>
            </div>

            <div class="battle-field">
                <div class="fighter" id="kratosSprite">
                    <img src="asset/kratosss.jpeg" alt="Kratos">
                </div>
                <div class="fighter" id="enemySprite">
                    <img src="asset/<?= $boss_img; ?>" alt="<?= $boss_name; ?>">
                </div>
            </div>

            <div class="battle-controls">
                <div class="log-box" id="battleLog">
                    <div style="color: var(--gold);">> Battle Start! Kratos faces <?= $boss_name; ?>!</div>
                </div>
                <div class="actions-box">
                    <button class="btn-action" onclick="playerAttack('light')">Light Attack</button>
                    <button class="btn-action" onclick="playerAttack('heavy')">Heavy Attack</button>
                    <button class="btn-action" onclick="playerHeal()">Heal</button>
                    <button class="btn-action" onclick="playerBlock()">Block</button>
                    <button class="btn-action" onclick="playerRage()">SPARTAN RAGE</button> 
                </div>
            </div>

            <div class="result-screen" id="resultScreen">
                <h2 class="result-title" id="resultTitle">VICTORY</h2>
                <p id="resultDesc" style="color: #ccc; margin-bottom: 20px; font-family:'Lato';">Result description here.</p>
                <button class="btn-back" onclick="closeBattle()" style="padding:10px 30px; font-family:'Cinzel'; cursor:pointer;">RETURN</button>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p class="footer-text">"THE CYCLE ENDS HERE."</p>
        <a href="series.php" class="btn-back">← BACK TO SERIES</a>
        <div style="margin-top: 20px; color: #666; font-size: 0.7rem;">
            GOD OF WAR FAN PROJECT © <?= date('Y'); ?>
        </div>
    </footer>

    <script>
        // === PARALLAX EFFECT ===
        const heroSection = document.getElementById('hero-section');
        const heroContent = document.querySelector('.hero-content');
        
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 100;
            const y = (window.innerHeight - e.pageY * 2) / 100;
            heroContent.style.transform = `translate(${x}px, ${y}px)`;
        });
        
        heroSection.addEventListener('mouseleave', () => {
            heroContent.style.transform = 'translate(0, 0)';
        });

        // === TIMELINE SCROLL ANIMATION ===
        const timelineRows = document.querySelectorAll('.timeline-row');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });
        
        timelineRows.forEach(row => observer.observe(row));

        // === CHARACTER SCROLL DRAGGABLE ===
        const charScroll = document.querySelector('.char-scroll-container');
        let isDown = false;
        let startX;
        let scrollLeft;
        
        charScroll.addEventListener('mousedown', (e) => {
            isDown = true;
            charScroll.style.cursor = 'grabbing';
            startX = e.pageX - charScroll.offsetLeft;
            scrollLeft = charScroll.scrollLeft;
        });
        
        charScroll.addEventListener('mouseleave', () => {
            isDown = false;
            charScroll.style.cursor = 'grab';
        });
        
        charScroll.addEventListener('mouseup', () => {
            isDown = false;
            charScroll.style.cursor = 'grab';
        });
        
        charScroll.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - charScroll.offsetLeft;
            const walk = (x - startX) * 2;
            charScroll.scrollLeft = scrollLeft - walk;
        });

        // === BOSS BATTLE GAME ===
        const maxHpPlayer = 1000;
        const maxHpEnemy = <?= $boss_hp; ?>;
        let hpPlayer = maxHpPlayer;
        let hpEnemy = maxHpEnemy;
        let isPlayerTurn = true;

        const modal = document.getElementById('battleModal');
        const logBox = document.getElementById('battleLog');
        const enemySprite = document.getElementById('enemySprite');
        const kratosSprite = document.getElementById('kratosSprite');

        function openBattle() { 
            modal.style.display = 'flex'; 
            resetBattle();
        }
        
        function closeBattle() { 
            modal.style.display = 'none'; 
        }
        
        function log(msg, color='#ddd') { 
            const p = document.createElement('div'); 
            p.innerHTML = `> ${msg}`; 
            p.style.color = color; 
            logBox.prepend(p);
            
            if(logBox.children.length > 10) {
                logBox.removeChild(logBox.lastChild);
            }
        }

        function updateUI() {
            document.getElementById('hpBarPlayer').style.width = (hpPlayer / maxHpPlayer * 100) + "%";
            document.getElementById('hpTxtPlayer').innerText = hpPlayer;
            document.getElementById('hpBarEnemy').style.width = (hpEnemy / maxHpEnemy * 100) + "%";
            document.getElementById('hpTxtEnemy').innerText = hpEnemy;
        }

        function playerAttack(type) {
            if(!isPlayerTurn || hpEnemy <= 0) return;
            
            let dmg = type === 'light' ? 50 : 80;
            if(type === 'heavy' && Math.random() < 0.3) dmg = 0; // Miss chance

            kratosSprite.classList.add('attack-anim'); 
            setTimeout(() => kratosSprite.classList.remove('attack-anim'), 300);
            
            if(dmg > 0) {
                hpEnemy = Math.max(0, hpEnemy - dmg);
                enemySprite.classList.add('shake'); 
                setTimeout(() => enemySprite.classList.remove('shake'), 500);
                log(`Kratos dealt ${dmg} damage!`, '#4cd137');
            } else {
                log("Kratos missed!", 'gray');
            }
            
            updateUI(); 
            checkWin();
            if(hpEnemy > 0) { 
                isPlayerTurn = false; 
                setTimeout(enemyTurn, 1000); 
            }
        }

        function playerHeal() {
            if(!isPlayerTurn) return;
            let heal = 100;
            hpPlayer = Math.min(maxHpPlayer, hpPlayer + heal);
            log("Kratos healed 100 HP", 'cyan');
            updateUI();
            isPlayerTurn = false; 
            setTimeout(enemyTurn, 1000);
        }

        function playerBlock() {
            if(!isPlayerTurn) return;
            log("Kratos prepares to block the next attack", '#00bfff');
            isPlayerTurn = false; 
            setTimeout(enemyTurn, 1000);
        }

        function playerRage() {
            if(!isPlayerTurn) return;
            let dmg = 150;
            hpEnemy = Math.max(0, hpEnemy - dmg);
            enemySprite.classList.add('shake');
            log("SPARTAN RAGE! 150 DMG!", 'red');
            updateUI(); 
            checkWin();
            if(hpEnemy > 0) { 
                isPlayerTurn = false; 
                setTimeout(enemyTurn, 1000); 
            }
        }

        function enemyTurn() {
            if(hpEnemy <= 0) return;
            let dmg = Math.floor(Math.random() * 60) + 20;
            hpPlayer = Math.max(0, hpPlayer - dmg);
            kratosSprite.classList.add('shake'); 
            setTimeout(() => kratosSprite.classList.remove('shake'), 500);
            log(`Boss attacked! ${dmg} damage!`, '#ff5555');
            updateUI(); 
            checkWin();
            if(hpPlayer > 0) isPlayerTurn = true;
        }

        function checkWin() {
            const screen = document.getElementById('resultScreen');
            const title = document.getElementById('resultTitle');
            const desc = document.getElementById('resultDesc');
            
            if(hpEnemy <= 0) {
                screen.style.display = 'flex'; 
                title.innerText = "VICTORY"; 
                title.style.color = "#4cd137";
                desc.innerText = `You have defeated ${'<?= $boss_name; ?>'} and proven yourself worthy of the gods.`;
            } else if(hpPlayer <= 0) {
                screen.style.display = 'flex'; 
                title.innerText = "DEFEATED"; 
                title.style.color = "red";
                desc.innerText = `You have fallen to ${'<?= $boss_name; ?>'}. Your journey ends here.`;
            }
        }

        function resetBattle() {
            hpPlayer = maxHpPlayer; 
            hpEnemy = maxHpEnemy; 
            isPlayerTurn = true;
            
            document.getElementById('resultScreen').style.display = 'none';
            logBox.innerHTML = ''; 
            log("Battle Start!", '#4cd137');
            log(`${'<?= $boss_name; ?>'}: "${'<?= $boss_quote; ?>"'}`, '#ff5555');
            
            updateUI();
        }
    </script>
</body>
</html>
