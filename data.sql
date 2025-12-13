-- 1. SETUP DATABASE
DROP DATABASE IF EXISTS db_god_of_war;
CREATE DATABASE db_god_of_war;
USE db_god_of_war;

-- 2. TABLE USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Insert Admin Default
-- Password: admin123
INSERT INTO users (nama, email, password, role) VALUES 
('Kratos Admin', 'admin@gow.com', 'admin123', 'admin'),
('Boy', 'user@gow.com', 'user123', 'user');

-- 3. TABLE SERIES (Updated for Minigame)
CREATE TABLE series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    tahun VARCHAR(10),
    platform VARCHAR(50),
    deskripsi TEXT,
    gambar VARCHAR(255),
    header_img VARCHAR(255),
    link_teleport VARCHAR(100),
    -- Kolom Tambahan untuk Minigame Boss Battle
    boss_name VARCHAR(100) DEFAULT 'Unknown God',
    boss_hp INT DEFAULT 1000,
    boss_img VARCHAR(255) DEFAULT 'logo.png'
);

-- Insert Data Series (Data Boss disesuaikan dengan aset yang ada)
INSERT INTO series (judul, tahun, platform, deskripsi, gambar, header_img, link_teleport, boss_name, boss_hp, boss_img) VALUES 
('God of War', '2005', 'PS 2', 'Awal mula perjalanan Kratos membalas dendam pada Ares, Sang Dewa Perang.', 'GOW.jpg', 'GOW.jpg', 'detail.php?id=1', 'ARES', 800, 'AresGod.webp'),
('God of War II', '2007', 'PS 2', 'Kratos dikhianati Zeus dan mulai memburu para Dewa Olympus dengan bantuan Titans.', 'GOW2.jpg', 'GOW2.jpg', 'detail.php?id=2', 'ZEUS', 1200, 'Youngzeus.webp'),
('God of War III', '2010', 'PS 3', 'Akhir dari era Yunani. Kratos mendaki Gunung Olympus untuk membunuh Zeus.', 'GOW3.jpg', 'GOW3.jpg', 'detail.php?id=3', 'HADES', 1000, 'GOW3.jpg'),
('God of War (2018)', '2018', 'PS 4', 'Hidup baru di tanah Nordik bersama putranya, Atreus. Perjalanan menyebar abu istri.', 'GOW 2018.jpg', 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg', 'detail.php?id=4', 'BALDUR', 1500, 'GOW 2018.jpg'),
('God of War Ragnarök', '2022', 'PS 5', 'Fimbulwinter telah tiba. Perang akhir zaman dimulai melawan Odin dan Thor.', 'GOW RAGNAROK.jpg', 'GOW RAGNAROK.jpg', 'detail.php?id=5', 'THOR', 2000, 'GOW RAGNAROK.jpg');

-- 4. TABLE REALMS
CREATE TABLE realms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_realm VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    posisi_top VARCHAR(10),  
    posisi_left VARCHAR(10)  
);

-- Insert Data Realms (Menggunakan aset background/map yang relevan)
INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES 
('Mount Olympus', 'Rumah para Dewa Yunani. Tempat Kratos membalas dendam.', 'Temple_5.webp', '20%', '60%'),
('Island of Creation', 'Tempat para Sisters of Fate bersemayam.', 'Island_of_creation.webp', '65%', '25%'),
('Midgard', 'Realm manusia di mitologi Nordik, tempat Kratos pensiun.', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg', '50%', '50%'),
('Sparta', 'Tanah kelahiran Kratos dan pasukan Spartan.', 'GOW ghost of sparta.jpg', '80%', '70%');

-- 5. TABLE CHARACTERS
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama VARCHAR(100),
    peran VARCHAR(100),
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Insert Characters (Menggunakan aset karakter yang ada)
INSERT INTO characters (series_id, nama, peran, gambar) VALUES 
(1, 'Athena', 'Goddess of Wisdom', 'Athena_29.webp'),
(1, 'Artemis', 'Goddess of Hunt', 'Artemis.webp'),
(4, 'Kratos', 'The Ghost of Sparta', 'GOW 2018.jpg'),
(4, 'Atreus', 'The Son (Loki)', 'GOW RAGNAROK.jpg'),
(2, 'Kratos (God)', 'God of War', 'Pcsx2-r4600_2011-07-17_19-56-02-28.webp');

-- 6. TABLE WEAPONS (Updated for Stats)
CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama_senjata VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    -- Kolom Tambahan untuk Statistik Senjata
    stat_damage INT DEFAULT 50,
    stat_speed INT DEFAULT 50,
    stat_range INT DEFAULT 50,
    stat_cc INT DEFAULT 50,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Insert Weapons (Disinkronkan dengan file aset yang ada di folder kamu)
INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar, stat_damage, stat_speed, stat_range, stat_cc) VALUES 
(1, 'Blades of Chaos', 'Senjata ikonik terikat rantai di lengan Kratos.', 'BladeOfChaos_29.webp', 80, 70, 90, 60),
(1, 'Blade of Artemis', 'Pedang besar pemberian Dewi Artemis.', 'Blade_of_Artemis_29.webp', 95, 40, 50, 80),
(2, 'Blade of Olympus', 'Pedang legendaris yang mengakhiri perang besar.', 'Blade_of_Olympus.jpg', 100, 50, 70, 90),
(2, 'Spear of Destiny', 'Tombak ungu dengan serangan jarak jauh.', 'GOW2_Spear_Of_Destiny.jpg', 60, 80, 95, 40),
(2, 'Barbarian Hammer', 'Palu raksasa milik Raja Barbarian.', 'Barbarian_Hammer.jpg', 98, 20, 40, 100),
(4, 'Leviathan Axe', 'Kapak es buatan Brok & Sindri untuk Faye.', 'GOW 2018.jpg', 85, 60, 50, 75);

-- 7. TABLE TIMELINE (STORY)
CREATE TABLE timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    judul_chapter VARCHAR(100),
    deskripsi TEXT,
    urutan INT,
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Insert Timeline Sample
INSERT INTO timeline (series_id, judul_chapter, deskripsi, urutan, gambar) VALUES 
(1, 'Hydra Battle', 'Kratos melawan Hydra di Laut Aegean atas perintah Poseidon.', 1, 'Hydra_Boss_Fight4_-_God_of_War2005.webp'),
(1, 'Pandora Temple', 'Mencari Kotak Pandora untuk mengalahkan Ares.', 2, 'Temple_5.webp'),
(4, 'The Journey Begins', 'Kratos menebang pohon bertanda tangan istrinya.', 1, 'GOW 2018.jpg'),
(4, 'The Stranger', 'Pertarungan pertama melawan Baldur.', 2, 'GOW ascension.jpg');