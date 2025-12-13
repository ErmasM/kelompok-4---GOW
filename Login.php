<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        if ($password === $data['password']) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role']; 
            $_SESSION['status'] = "login";

            if ($data['role'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: home.php"); 
            }
            exit;
        } else {
            echo "<script>alert('Password Salah!');</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - God of War</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="background: url('asset/GOWRG_Wallpaper_Desktop_Vista_4k.jpg') no-repeat center center/cover; height: 100vh; display: flex; justify-content: flex-end; align-items: center;">
        <div class="glass-panel" style="width: 450px; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px); border-left: 1px solid rgba(255, 255, 255, 0.3); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px;">
            <img src="asset/logo.png" alt="Logo" class="logo" style="width: 250px; margin-bottom: 50px;">

            <form action="" method="POST" style="width: 100%;">
                <div style="margin-bottom: 25px;">
                    <input type="email" name="email" class="input-field" placeholder="EMAIL" required>
                </div>
                <div style="margin-bottom: 25px;">
                    <input type="password" name="password" class="input-field" placeholder="PASSWORD" required>
                </div>
                <button type="submit" name="login" class="btn-primary" style="width: 100%; font-size: 18px;">LOGIN</button>
            </form>

            <p class="footer-text" style="margin-top: 30px; color: white; font-family: 'Lato', sans-serif;">
                Don't have an account? <a href="register.php" style="color: #ff3b3b; font-weight: bold; text-decoration: none;">Register</a>
            </p>
        </div>
    </div>
</body>
</html>