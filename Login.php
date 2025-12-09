<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        if (password_verify($password, $data['password'])) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['status'] = "login";
            header("Location: home.php");
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
    <title>God of War Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS ASLI DARI FILE LOGIN.HTML */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Cinzel', serif; overflow: hidden; }
        .container {
            position: relative; width: 100%; height: 100vh;
            background-image: url('https://wallpapers.com/images/featured/god-of-war-4-4k-l50s10237596005z.jpg');
            background-size: cover; background-position: center;
            display: flex; justify-content: flex-end; 
        }
        .glass-panel {
            width: 450px; height: 100%;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.3); box-shadow: -10px 0 30px rgba(0,0,0,0.5);
            display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px;
        }
        .logo { width: 280px; margin-bottom: 60px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.6)); }
        .form-group { width: 100%; margin-bottom: 25px; }
        .input-field {
            width: 100%; padding: 15px; font-family: 'Cinzel', serif; font-size: 16px; color: #000;
            background: rgba(255, 255, 255, 0.95); border: 1px solid transparent; outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;
        }
        .input-field::placeholder { color: #555; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; }
        .input-field:focus { transform: scale(1.02); background: #fff; border-left: 5px solid #c00; box-shadow: 0 0 20px rgba(255, 255, 255, 0.4); }
        .btn-login {
            width: 100%; padding: 15px; margin-top: 30px;
            background-color: #ff3b3b; background-image: linear-gradient(45deg, #cc0000, #ff5f5f);
            color: white; font-family: 'Cinzel', serif; font-size: 20px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 2px; border: none; cursor: pointer;
            position: relative; transition: transform 0.2s; text-shadow: 0 2px 4px rgba(0,0,0,0.4);
            clip-path: polygon(0% 2%, 98% 0%, 100% 15%, 98% 30%, 100% 45%, 99% 60%, 100% 75%, 98% 90%, 100% 100%, 2% 98%, 0% 85%, 1% 70%, 0% 55%, 2% 40%, 0% 25%, 2% 10%);
        }
        .btn-login:hover { transform: scale(1.02); filter: brightness(1.1); }
        .footer-text { margin-top: 40px; color: white; font-family: 'Lato', sans-serif; font-size: 14px; text-shadow: 0 2px 4px rgba(0,0,0,0.8); letter-spacing: 0.5px; }
        .footer-text a { color: #fff; text-decoration: none; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.5); transition: 0.3s; }
        .footer-text a:hover { color: #ff3b3b; border-color: #ff3b3b; }
        @media (max-width: 768px) { .glass-panel { width: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="glass-panel">
            <img src="asset/logo.png" alt="God of War" class="logo">
            <form style="width: 100%;" action="" method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="input-field" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="input-field" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn-login">Login</button>
            </form>
            <p class="footer-text">
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>
    </div>
</body>
</html>