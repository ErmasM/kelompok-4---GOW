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
INSERT INTO users (nama, email, password, role) VALUES 
('Kratos Admin', 'admin@gow.com', 'admin123', 'admin');

-- 3. TABLE SERIES
CREATE TABLE series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    tahun VARCHAR(10),
    platform VARCHAR(50),
    deskripsi TEXT,
    gambar VARCHAR(255),
    header_img VARCHAR(255),
    link_teleport VARCHAR(100)
);

-- Insert Data Series (Gambar header disesuaikan dengan aset yang ada)
INSERT INTO series (judul, tahun, platform, deskripsi, gambar, header_img, link_teleport) VALUES 
('God of War', '2005', 'PS 2', 'Awal mula perjalanan Kratos membalas dendam pada Ares.', 'GOW.jpg', 'GOW.jpg', 'detail.php?id=1'),
('God of War II', '2007', 'PS 2', 'Kratos dikhianati Zeus dan mulai memburu para Dewa Olympus.', 'GOW2.jpg', 'GOW2.jpg', 'detail.php?id=2'),
('God of War III', '2010', 'PS 3', 'Akhir dari era Yunani, Kratos menghancurkan Olympus.', 'GOW3.jpg', 'GOW3.jpg', 'detail.php?id=3'),
('God of War (2018)', '2018', 'PS 4', 'Hidup baru di tanah Nordik bersama putranya, Atreus.', 'GOW 2018.jpg', 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg', 'detail.php?id=4'),
('God of War Ragnarök', '2022', 'PS 5', 'Fimbulwinter telah tiba. Perang akhir zaman dimulai.', 'GOW RAGNAROK.jpg', 'GOW RAGNAROK.jpg', 'detail.php?id=5');

-- 4. TABLE REALMS
CREATE TABLE realms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_realm VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    posisi_top VARCHAR(10),  
    posisi_left VARCHAR(10)  
);

-- Insert Data Realms (Menggunakan aset wallpaper untuk background modal)
INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES 
('Midgard', 'Realm manusia, tempat Kratos tinggal. Hutan lebat dan danau beku.', 'GOW 2018.jpg', '50%', '50%'),
('Alfheim', 'Rumah para Elves. Realm yang penuh cahaya magis.', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg', '20%', '75%'),
('Muspelheim', 'Alam api abadi, tempat ujian kekuatan.', 'GOW ascension.jpg', '70%', '80%');

-- 5. TABLE CHARACTERS
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama VARCHAR(100),
    peran VARCHAR(100),
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Insert Characters (Menggunakan aset yang ada sebagai placeholder agar tidak kosong)
INSERT INTO characters (series_id, nama, peran, gambar) VALUES 
(4, 'Kratos', 'God of War', 'GOW 2018.jpg'),
(4, 'Atreus', 'Son of Kratos', 'GOW RAGNAROK.jpg'),
(4, 'Freya', 'Witch of the Woods', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg'),
(4, 'Baldur', 'The Stranger', 'GOW ascension.jpg');

-- 6. TABLE WEAPONS
CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama_senjata VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Insert Weapons
INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar) VALUES 
(4, 'Leviathan Axe', 'Kapak es buatan Brok & Sindri.', 'logo.png'),
(4, 'Blades of Chaos', 'Pedang ikonik masa lalu Kratos.', 'GOW.jpg'),
(4, 'Guardian Shield', 'Perisai lipat untuk pertahanan.', 'logo.png');

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

-- Insert Timeline Sample (Agar fitur timeline zigzag tidak kosong)
INSERT INTO timeline (series_id, judul_chapter, deskripsi, urutan, gambar) VALUES 
(4, 'The Journey Begins', 'Kratos menebang pohon bertanda tangan istrinya, memulai perjalanan.', 1, 'GOW 2018.jpg'),
(4, 'The Stranger', 'Pertarungan pertama melawan Baldur yang mengguncang tanah.', 2, 'GOW ascension.jpg'),
(4, 'Lake of Nine', 'Menemukan kuil Tyr dan Jormungandr.', 3, 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg');

ALTER TABLE weapons 
ADD COLUMN stat_damage INT DEFAULT 50,
ADD COLUMN stat_speed INT DEFAULT 50,
ADD COLUMN stat_range INT DEFAULT 50,
ADD COLUMN stat_cc INT DEFAULT 50;

ALTER TABLE series 
ADD COLUMN boss_name VARCHAR(100) DEFAULT 'Unknown God',
ADD COLUMN boss_hp INT DEFAULT 1000,
ADD COLUMN boss_img VARCHAR(255) DEFAULT 'logo.png';