<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    
    // Logic Backend Ezra
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        // Cek password (mendukung hash atau plain text sesuai data lama)
        if ($password === $data['password'] || password_verify($password, $data['password'])) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role']; // Penting untuk Admin
            $_SESSION['status'] = "login";

            if ($data['role'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: home.php"); 
            }
            exit;
        } else {
            $error = "Password Salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - God of War</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Style Frontend Ermas */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Cinzel', serif; overflow: hidden; }
        .container {
            position: relative; width: 100%; height: 100vh;
            background-image: url('asset/GOWRG_Wallpaper_Desktop_Vista_4k.jpg'); /* Asset Ezra */
            background-size: cover; background-position: center;
            display: flex; justify-content: flex-end; 
        }
        .glass-panel {
            width: 450px; height: 100%;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px;
        }
        .logo { width: 280px; margin-bottom: 60px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.8)); }
        .form-group { width: 100%; margin-bottom: 25px; }
        .input-field {
            width: 100%; padding: 15px; font-family: 'Cinzel', serif; font-size: 16px; color: #000;
            background: rgba(255, 255, 255, 0.9); border: none; outline: none; transition: 0.3s;
        }
        .input-field:focus { background: #fff; box-shadow: 0 0 15px var(--gold); }
        .btn-login {
            width: 100%; padding: 15px; margin-top: 30px;
            background-color: #b30000; color: white; font-family: 'Cinzel', serif; font-size: 18px; font-weight: bold;
            border: none; cursor: pointer; transition: 0.3s; letter-spacing: 2px;
        }
        .btn-login:hover { background-color: #ff0000; box-shadow: 0 0 20px #ff0000; }
        .footer-text { margin-top: 40px; color: white; font-family: 'Lato', sans-serif; font-size: 14px; }
        .footer-text a { color: #ff3b3b; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="glass-panel">
            <img src="asset/logo.png" alt="God of War" class="logo">
            <?php if(isset($error)) echo "<p style='color:red; margin-bottom:10px;'>$error</p>"; ?>
            <form style="width: 100%;" action="" method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="input-field" placeholder="EMAIL" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="input-field" placeholder="PASSWORD" required>
                </div>
                <button type="submit" name="login" class="btn-login">LOGIN</button>
            </form>
            <p class="footer-text">
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>
    </div>
</body>
</html>