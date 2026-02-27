<?php
/**
 * Update Admin - Tracking Style  
 * Update username dan password admin dengan tampilan tracking system
 */

// Security check
if (!isset($_GET['key']) || $_GET['key'] !== 'nocturnal0300') {
    die("❌ Invalid key");
}

// Database connection
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'tigapagi';
$port = 3308;

$conn = @new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("❌ Database connection failed");
}

// Process form
$success = '';
$error = '';
$updated_user = '';
$updated_pass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = trim($_POST['username'] ?? '');
    $new_pass = trim($_POST['password'] ?? '');
    
    if (empty($new_user)) {
        $error = 'Username tidak boleh kosong!';
    } elseif (empty($new_pass)) {
        $error = 'Password tidak boleh kosong!';
    } elseif (strlen($new_pass) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        
        // Check if admin table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'admin'");
        if ($table_check->num_rows == 0) {
            $error = "Table 'admin' tidak ditemukan. Jalankan setup_database.php terlebih dahulu.";
        } else {
            $stmt = $conn->prepare("UPDATE admin SET username = ?, password = ? ORDER BY id LIMIT 1");
            $stmt->bind_param("ss", $new_user, $hashed);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $success = "Credentials admin berhasil diupdate!";
                $updated_user = $new_user;
                $updated_pass = $new_pass;
            } elseif ($stmt->affected_rows == 0) {
                $error = "Tidak ada admin yang diupdate. Table admin mungkin kosong.";
            } else {
                $error = "Gagal update admin";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin - Studio Tigapagi</title>
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
            background-image: url('img/COver 1.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            padding: 40px 20px;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }
        
        .container {
            position: relative;
            z-index: 2;
            background: rgba(30, 30, 30, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #00ff88, #00dd77);
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            color: #000;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header p {
            color: rgba(0, 0, 0, 0.7);
            font-size: 14px;
        }
        
        .content {
            padding: 40px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background: rgba(0, 255, 136, 0.15);
            color: #00ff88;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .alert-error {
            background: rgba(255, 77, 77, 0.15);
            color: #ff6b6b;
            border: 1px solid rgba(255, 77, 77, 0.3);
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
        
        input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transition: all 0.3s ease;
            outline: none;
            font-family: inherit;
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        input:focus {
            border-color: #00ff88;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }
        
        .form-hint {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 8px;
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
        
        .credentials-box {
            background: rgba(0, 255, 136, 0.1);
            border: 2px solid rgba(0, 255, 136, 0.3);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .credentials-box h3 {
            color: #00ff88;
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .cred-item {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .cred-item:last-child {
            margin-bottom: 0;
        }
        
        .cred-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 8px;
        }
        
        .cred-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
            color: #00ff88;
            background: rgba(0, 0, 0, 0.4);
            padding: 12px 15px;
            border-radius: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            word-break: break-all;
        }
        
        .info-box {
            background: rgba(0, 136, 255, 0.1);
            border-left: 4px solid #0088ff;
            padding: 20px;
            border-radius: 12px;
            margin-top: 25px;
        }
        
        .info-box h4 {
            color: #0088ff;
            margin-bottom: 12px;
            font-size: 16px;
        }
        
        .info-box ul {
            margin-left: 20px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
        }
        
        .test-link {
            display: inline-block;
            background: #00ff88;
            color: #000;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s;
        }
        
        .test-link:hover {
            background: #00dd77;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔄 Update Admin</h1>
        <p>Ganti username & password admin</p>
    </div>
    <div class="content">
        
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
            
            <div class="credentials-box">
                <h3>🔑 Credentials Admin Baru</h3>
                
                <div class="cred-item">
                    <span class="cred-label">Username</span>
                    <div class="cred-value"><?= htmlspecialchars($updated_user) ?></div>
                </div>
                
                <div class="cred-item">
                    <span class="cred-label">Password</span>
                    <div class="cred-value"><?= htmlspecialchars($updated_pass) ?></div>
                </div>
            </div>
            
            <center>
                <a href="tracking/admin_login.php" class="test-link" target="_blank">🚀 Test Login Sekarang</a>
            </center>
            
            <div class="info-box">
                <h4>📝 Langkah Selanjutnya:</h4>
                <ul>
                    <li>Screenshot atau save credentials di atas</li>
                    <li>Test login dengan credentials baru</li>
                    <li>Setelah berhasil, hapus file ini</li>
                </ul>
            </div>
        <?php else: ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username Admin Baru</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Contoh: admin_tigapagi" 
                        required
                        autocomplete="off"
                    >
                    <div class="form-hint">Username untuk login admin</div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password Admin Baru</label>
                    <input 
                        type="text" 
                        id="password" 
                        name="password" 
                        placeholder="Minimal 6 karakter" 
                        required
                        autocomplete="off"
                    >
                    <div class="form-hint">Gunakan kombinasi huruf, angka, dan simbol</div>
                </div>
                
                <button type="submit">🔄 Update Credentials Admin</button>
            </form>
            
            <div class="info-box">
                <h4>💡 Tips Keamanan:</h4>
                <ul>
                    <li>Gunakan username yang unik</li>
                    <li>Password minimal 8-12 karakter</li>
                    <li>Kombinasikan huruf besar, kecil, angka, simbol</li>
                    <li>Jangan gunakan password yang mudah ditebak</li>
                </ul>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
