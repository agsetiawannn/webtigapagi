<?php
session_start();
include __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // ambil data client aktif berdasarkan email
    $sql = "SELECT * FROM clients WHERE email='$email' AND status='active'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $client = $result->fetch_assoc();

        // simpan session
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email tidak terdaftar.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Client - Studio Tigapagi</title>
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
            margin-bottom: 30px;
        }
        
        label {
            display: block;
            margin-bottom: 12px;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        input[type="email"] {
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
        
        input[type="email"]::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        input[type="email"]:focus {
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
        
        .admin-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-link a {
            color: #00ff88;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .admin-link a:hover {
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
        <h2>Client Login</h2>
        <p class="subtitle">Studio Tigapagi Tracking System</p>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Type Something..." required>
            </div>
            <button type="submit">Log in</button>
        </form>
        
        <div class="admin-link">
            <a href="admin_login.php">Admin Login →</a>
        </div>
    </div>
</body>
</html>
