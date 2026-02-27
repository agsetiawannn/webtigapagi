<?php
/**
 * Add New Admin - Tracking Style
 * Menambah admin baru dengan tampilan tracking system
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

// Get existing admins
$existing_admins = [];
$result = $conn->query("SELECT id, username, role, created_at FROM admin ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $existing_admins[] = $row;
    }
}

// Process form
$success = '';
$error = '';
$new_user = '';
$new_pass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = trim($_POST['username'] ?? '');
    $new_pass = trim($_POST['password'] ?? '');
    $new_role = trim($_POST['role'] ?? 'admin');
    
    if (empty($new_user)) {
        $error = 'Username tidak boleh kosong!';
    } elseif (empty($new_pass)) {
        $error = 'Password tidak boleh kosong!';
    } elseif (strlen($new_pass) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif (strlen($new_user) < 3) {
        $error = 'Username minimal 3 karakter!';
    } else {
        $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO admin (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $new_user, $hashed, $new_role);
        
        if ($stmt->execute()) {
            $success = "Admin baru '{$new_user}' berhasil ditambahkan!";
            // Refresh list
            $existing_admins = [];
            $result = $conn->query("SELECT id, username, role, created_at FROM admin ORDER BY id");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $existing_admins[] = $row;
                }
            }
        } else {
            if ($conn->errno == 1062) {
                $error = "Username '{$new_user}' sudah digunakan!";
            } else {
                $error = "Gagal menambah admin";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Admin - Studio Tigapagi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
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
            max-width: 900px;
            margin: 0 auto;
        }
        
        .card {
            background: rgba(30, 30, 30, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #00ff88, #00dd77);
            padding: 30px;
            text-align: center;
        }
        
        .card-header h1 {
            color: #000;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .card-header p {
            color: rgba(0, 0, 0, 0.7);
            font-size: 14px;
        }
        
        .card-body {
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
        
        input, select {
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
        
        input:focus, select:focus {
            border-color: #00ff88;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }
        
        select {
            cursor: pointer;
        }
        
        select option {
            background: #1e1e1e;
            color: #fff;
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
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .admin-table th {
            background: rgba(0, 255, 136, 0.1);
            padding: 15px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            color: #00ff88;
            border-bottom: 2px solid rgba(0, 255, 136, 0.3);
            letter-spacing: 0.5px;
        }
        
        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        
        .admin-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-superadmin {
            background: #ff6b6b;
            color: white;
        }
        
        .badge-admin {
            background: #00ff88;
            color: #000;
        }
        
        .count-badge {
            background: #00ff88;
            color: #000;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            margin-left: 10px;
        }
        
        h3 {
            color: #ffffff;
            margin-bottom: 25px;
            font-size: 22px;
            display: flex;
            align-items: center;
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
        
        .info-box li {
            margin-bottom: 5px;
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
    
    <!-- Existing Admins List -->
    <div class="card">
        <div class="card-header">
            <h1>👥 Admin List</h1>
            <p>Total: <?= count($existing_admins) ?> Admin</p>
        </div>
        <div class="card-body">
            <?php if (empty($existing_admins)): ?>
                <div class="alert alert-error">
                    Belum ada admin. Jalankan setup_database.php terlebih dahulu.
                </div>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($existing_admins as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['id']) ?></td>
                                <td><strong><?= htmlspecialchars($admin['username']) ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= strtolower($admin['role'] ?? 'admin') ?>">
                                        <?= htmlspecialchars($admin['role'] ?? 'admin') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($admin['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Add New Admin Form -->
    <div class="card">
        <div class="card-header">
            <h1>➕ Tambah Admin Baru</h1>
            <p>Menambah admin tanpa menghapus yang lama</p>
        </div>
        <div class="card-body">
            
            <?php if ($error): ?>
                <div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
                
                <div class="credentials-box">
                    <h3>🔑 Credentials Admin Baru</h3>
                    
                    <div class="cred-item">
                        <span class="cred-label">Username</span>
                        <div class="cred-value"><?= htmlspecialchars($new_user) ?></div>
                    </div>
                    
                    <div class="cred-item">
                        <span class="cred-label">Password</span>
                        <div class="cred-value"><?= htmlspecialchars($new_pass) ?></div>
                    </div>
                </div>
                
                <div class="info-box">
                    <h4>📝 Penting:</h4>
                    <ul>
                        <li><strong>Screenshot atau save credentials di atas!</strong></li>
                        <li>Password hanya ditampilkan sekali</li>
                        <li>Total admin sekarang: <strong><?= count($existing_admins) ?> admin</strong></li>
                    </ul>
                </div>
                
                <center>
                    <a href="tracking/admin_login.php" class="test-link" target="_blank">🚀 Test Login Sekarang</a>
                </center>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username Admin Baru</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Contoh: admin_bali, superadmin, etc" 
                        required
                        autocomplete="off"
                        minlength="3"
                    >
                    <div class="form-hint">Username minimal 3 karakter, harus unik</div>
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
                        minlength="6"
                    >
                    <div class="form-hint">Password minimal 6 karakter (kombinasi huruf, angka, simbol)</div>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                    <div class="form-hint">Pilih role untuk privileges admin</div>
                </div>
                
                <button type="submit">➕ Tambah Admin Baru</button>
            </form>
            
            <div class="info-box" style="margin-top: 30px;">
                <h4>💡 Catatan:</h4>
                <ul>
                    <li>Tidak ada batasan jumlah admin</li>
                    <li>Setiap admin harus punya username unik</li>
                    <li>Password di-hash dengan bcrypt (aman!)</li>
                    <li>Admin lama tidak akan terhapus</li>
                </ul>
            </div>
            
        </div>
    </div>
    
</div>
</body>
</html>
<?php $conn->close(); ?>
