<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
       
        
        $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar!');</script>";
        } else {
           
            $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
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
<head>
    <meta charset="UTF-8">
    <title>God of War Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="background:url('asset/GOWRG_Wallpaper_Desktop_Boat_4k.jpg') center/cover; height:100vh; display:flex;">
        <div class="glass-panel" style="width:450px; padding:40px; background:rgba(255,255,255,0.1); backdrop-filter:blur(15px); height:100%;">
            <img src="asset/logo.png" style="width:200px; margin-bottom:30px; display:block; margin-left:auto; margin-right:auto;">
            <form method="POST">
                <input type="text" name="nama" placeholder="NAMA LENGKAP" class="input-field" required style="width:100%; padding:10px; margin-bottom:15px;">
                <input type="email" name="email" placeholder="EMAIL" class="input-field" required style="width:100%; padding:10px; margin-bottom:15px;">
                <input type="password" name="password" placeholder="PASSWORD" class="input-field" required style="width:100%; padding:10px; margin-bottom:15px;">
                <input type="password" name="confirm_password" placeholder="CONFIRM PASSWORD" class="input-field" required style="width:100%; padding:10px; margin-bottom:15px;">
                <button type="submit" name="register" class="btn-primary" style="width:100%;">REGISTER</button>
            </form>
            <p style="text-align:center; margin-top:20px; color:white;">Sudah punya akun? <a href="login.php" style="color:red;">Login</a></p>
        </div>
    </div>
</body>
</html>
