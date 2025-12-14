<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    // ... (kode bagian atas tetap sama) ...
    if ($password === $confirm_password) {
        // ...
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar!');</script>";
        } else {
            $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
            if (mysqli_query($conn, $query)) {
                // UPDATE DI SINI: window.location='index.php'
                echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat mendaftar.');</script>";
            }
        }
    } else {
        echo "<script>alert('Password tidak cocok!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<body>
    <div class="container" style="background:url('asset/GOWRG_Wallpaper_Desktop_Boat_4k.jpg') center/cover; height:100vh; display:flex; justify-content:center; align-items:center;">
        <div class="glass-panel" style="width:450px; padding:40px; background:rgba(0,0,0,0.7); backdrop-filter:blur(10px); border: 1px solid #444;">
            <form method="POST">
                <input type="text" name="nama" placeholder="NAMA LENGKAP" class="input-field" required style="margin-bottom:15px;">
                <input type="email" name="email" placeholder="EMAIL" class="input-field" required style="margin-bottom:15px;">
                <input type="password" name="password" placeholder="PASSWORD" class="input-field" required style="margin-bottom:15px;">
                <input type="password" name="confirm_password" placeholder="CONFIRM PASSWORD" class="input-field" required style="margin-bottom:15px;">
                <button type="submit" name="register" class="btn-primary" style="width:100%;">REGISTER</button>
            </form>
            <p style="text-align:center; margin-top:20px; color:white;">Sudah punya akun? <a href="index.php" style="color:red;">Login</a></p>
        </div>
    </div>
</body>
</html>