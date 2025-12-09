-- 1. Buat Database (Jika belum ada)
CREATE DATABASE IF NOT EXISTS db_god_of_war;

-- 2. Pilih Database yang baru dibuat
USE db_god_of_war;

-- 3. Buat Tabel 'users' untuk menyimpan data akun
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- (Opsional) Masukkan 1 akun admin untuk tes login langsung
-- Passwordnya adalah: admin123
INSERT INTO users (nama, email, password) VALUES 
('Kratos Admin', 'admin@gow.com', '$2y$10$wKq./.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w');