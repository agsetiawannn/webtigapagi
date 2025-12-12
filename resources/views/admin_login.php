<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // PENTING: Cek dulu koneksi
    if ($conn->connect_error) {
        die("Koneksi ke db.php GAGAL: " . $conn->connect_error);
    }

    // Kode ini sudah benar
    $result = $conn->query("SELECT * FROM admin WHERE username='$username' AND password=MD5('$password')");
    
    if ($result && $result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        // Tampilkan juga error SQL jika query-nya yang salah
        if(!$result) {
            $error = "Error Query: " . $conn->error;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Studio Tigapagi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('../img/Cover 1.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            background: rgba(30, 30, 30, 0.85);
            backdrop-filter: blur(10px);
            padding: 50px 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-container h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 40px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 12px;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transition: all 0.3s ease;
            outline: none;
        }
        
        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #00ff88;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }
        
        button {
            width: 100%;
            padding: 16px;
            background: #00ff88;
            color: #000;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }
        
        button:hover {
            background: #00dd77;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 255, 136, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .error {
            background: rgba(255, 77, 77, 0.15);
            color: #ff6b6b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid rgba(255, 77, 77, 0.3);
            font-size: 14px;
        }
        
        .client-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .client-link a {
            color: #00ff88;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .client-link a:hover {
            color: #00dd77;
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 40px 30px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <p class="subtitle">Studio Tigapagi Tracking System</p>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Type Something..." required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Type Something..." required>
            </div>
            <button type="submit">Log in</button>
        </form>
        
        <div class="client-link">
            <a href="login.php">← Back to Client Login</a>
        </div>
    </div>
</body>
</html>