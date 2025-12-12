
DROP DATABASE IF EXISTS db_god_of_war;
CREATE DATABASE db_god_of_war;
USE db_god_of_war;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);


INSERT INTO users (nama, email, password, role) VALUES 
('Kratos Admin', 'admin@gow.com', 'admin123', 'admin');


CREATE TABLE series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    tahun VARCHAR(10),
    platform VARCHAR(50),
    deskripsi TEXT,
    gambar VARCHAR(255),
    link_teleport VARCHAR(100)
);

INSERT INTO series (judul, tahun, platform, deskripsi, gambar, link_teleport) VALUES 
('God of War', '2005', 'PS 2', 'Awal mula perjalanan Kratos membalas dendam pada Ares.', 'GOW.jpg', 'detail.php?id=1'),
('God of War II', '2007', 'PS 2', 'Kratos dikhianati Zeus dan mulai memburu para Dewa Olympus.', 'GOW2.jpg', 'detail.php?id=2'),
('God of War III', '2010', 'PS 3', 'Akhir dari era Yunani, Kratos menghancurkan Olympus.', 'GOW3.jpg', 'detail.php?id=3'),
('God of War (2018)', '2018', 'PS 4', 'Hidup baru di tanah Nordik bersama putranya, Atreus.', 'GOW 2018.jpg', 'detail.php?id=4'),
('God of War Ragnarök', '2022', 'PS 5', 'Fimbulwinter telah tiba. Perang akhir zaman dimulai.', 'GOW RAGNAROK.jpg', 'detail.php?id=5');


CREATE TABLE realms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_realm VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    posisi_top VARCHAR(10),  
    posisi_left VARCHAR(10)  
);


INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES 
('Midgard', 'Realm manusia, tempat Kratos tinggal. Hutan lebat dan danau beku.', 'GOW 2018.jpg', '50%', '50%'),
('Alfheim', 'Rumah para Elves. Realm yang penuh cahaya magis.', 'GOW4.jpg', '20%', '75%'),
('Muspelheim', 'Alam api abadi, tempat ujian kekuatan.', 'GOW6.jpg', '70%', '80%');


USE db_god_of_war;


ALTER TABLE series ADD COLUMN header_img VARCHAR(255) DEFAULT 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg';


CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama VARCHAR(100),
    peran VARCHAR(100),
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);


CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama_senjata VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);


UPDATE series SET header_img = 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg' WHERE id = 4;


INSERT INTO characters (series_id, nama, peran, gambar) VALUES 
(4, 'Kratos', 'God of War', 'kratos_face.jpg'),
(4, 'Atreus', 'Son of Kratos', 'atreus_face.jpg'),
(4, 'Freya', 'Witch of the Woods', 'freya_face.jpg'),
(4, 'Baldur', 'The Stranger', 'baldur_face.jpg');


INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar) VALUES 
(4, 'Leviathan Axe', 'Kapak es buatan Brok & Sindri.', 'axe.png'),
(4, 'Blades of Chaos', 'Pedang ikonik masa lalu Kratos.', 'blades.png'),
(4, 'Guardian Shield', 'Perisai lipat untuk pertahanan.', 'shield.png');

USE db_god_of_war;



UPDATE users SET role = 'admin' WHERE email = 'admin@gow.com';


CREATE TABLE IF NOT EXISTS timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    judul_chapter VARCHAR(100),
    deskripsi TEXT,
    urutan INT,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

DROP DATABASE IF EXISTS db_god_of_war;
CREATE DATABASE db_god_of_war;
USE db_god_of_war;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

INSERT INTO users (nama, email, password, role) VALUES 
('Kratos Admin', 'admin@gow.com', 'admin123', 'admin');

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

INSERT INTO series (judul, tahun, platform, deskripsi, gambar, header_img, link_teleport) VALUES 
('God of War', '2005', 'PS 2', 'Awal mula perjalanan Kratos membalas dendam pada Ares.', 'GOW.jpg', 'GOW.jpg', 'detail.php?id=1'),
('God of War II', '2007', 'PS 2', 'Kratos dikhianati Zeus dan mulai memburu para Dewa Olympus.', 'GOW2.jpg', 'GOW2.jpg', 'detail.php?id=2'),
('God of War III', '2010', 'PS 3', 'Akhir dari era Yunani, Kratos menghancurkan Olympus.', 'GOW3.jpg', 'GOW3.jpg', 'detail.php?id=3'),
('God of War (2018)', '2018', 'PS 4', 'Hidup baru di tanah Nordik bersama putranya, Atreus.', 'GOW 2018.jpg', 'GOWRG_Wallpaper_Desktop_Boat_4k.jpg', 'detail.php?id=4'),
('God of War Ragnarök', '2022', 'PS 5', 'Fimbulwinter telah tiba. Perang akhir zaman dimulai.', 'GOW RAGNAROK.jpg', 'GOW RAGNAROK.jpg', 'detail.php?id=5');

CREATE TABLE realms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_realm VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    posisi_top VARCHAR(10),  
    posisi_left VARCHAR(10)  
);

INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) VALUES 
('Midgard', 'Realm manusia, tempat Kratos tinggal. Hutan lebat dan danau beku.', 'GOW 2018.jpg', '50%', '50%'),
('Alfheim', 'Rumah para Elves. Realm yang penuh cahaya magis.', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg', '20%', '75%'),
('Muspelheim', 'Alam api abadi, tempat ujian kekuatan.', 'GOW ascension.jpg', '70%', '80%');

CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama VARCHAR(100),
    peran VARCHAR(100),
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

-- Menggunakan gambar aset yang tersedia sebagai placeholder
INSERT INTO characters (series_id, nama, peran, gambar) VALUES 
(4, 'Kratos', 'God of War', 'GOW 2018.jpg'),
(4, 'Atreus', 'Son of Kratos', 'GOW RAGNAROK.jpg'),
(4, 'Freya', 'Witch of the Woods', 'GOWRG_Wallpaper_Desktop_Vista_4k.jpg'),
(4, 'Baldur', 'The Stranger', 'GOW ascension.jpg');

CREATE TABLE weapons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    nama_senjata VARCHAR(100),
    deskripsi TEXT,
    gambar VARCHAR(255),
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);

INSERT INTO weapons (series_id, nama_senjata, deskripsi, gambar) VALUES 
(4, 'Leviathan Axe', 'Kapak es buatan Brok & Sindri.', 'logo.png'),
(4, 'Blades of Chaos', 'Pedang ikonik masa lalu Kratos.', 'GOW.jpg'),
(4, 'Guardian Shield', 'Perisai lipat untuk pertahanan.', 'logo.png');

CREATE TABLE IF NOT EXISTS timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    series_id INT,
    judul_chapter VARCHAR(100),
    deskripsi TEXT,
    urutan INT,
    FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
);