<?php
/**
 * Cleanup Admin Duplicates
 * Hapus admin duplicate, sisakan hanya 1
 */

// Security check
if (!isset($_GET['key']) || $_GET['key'] !== 'nocturnal0300') {
    die("❌ Invalid key. Use ?key=nocturnal0300");
}

if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Cleanup Admin</title>
        <link rel="stylesheet" href="css/tracking/admin_login.css">
    </head>
    <body>
    <div class="login-container">
        <h2>⚠️ Cleanup Admin Duplicates</h2>
        <p class="subtitle">Hapus semua admin duplicate, sisakan hanya 1</p>
        
        <div style="background: rgba(255, 77, 77, 0.15); color: #ff6b6b; padding: 15px; border-radius: 10px; margin: 20px 0; border: 1px solid rgba(255, 77, 77, 0.3);">
            <strong>⚠️ PERINGATAN!</strong><br>
            Operasi ini akan:
            <ul style="margin: 10px 0 0 20px; text-align: left;">
                <li>Hapus SEMUA admin yang ada</li>
                <li>Buat 1 admin baru dengan credentials yang Anda tentukan</li>
            </ul>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label>Username Admin Baru</label>
                <input type="text" name="username" placeholder="Minimal 3 karakter" required minlength="3">
            </div>
            
            <div class="form-group">
                <label>Password Admin Baru</label>
                <input type="text" name="password" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            
            <div class="form-group">
                <label>Role</label>
                <select name="role" style="width: 100%; padding: 16px 20px; border: 2px solid rgba(255, 255, 255, 0.2); border-radius: 12px; font-size: 15px; background: rgba(255, 255, 255, 0.1); color: #ffffff;">
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <button type="submit" style="background: #ff6b6b;">🗑️ Reset & Buat Admin Baru</button>
        </form>
        
        <a href="add_admin.php?key=nocturnal0300" style="display: block; text-align: center; color: #00ff88; margin-top: 20px; text-decoration: none;">
            ← Kembali ke Add Admin
        </a>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// Database connection
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'tigapagi';
$port = 3308;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("❌ Database connection failed");
}

// Process cleanup
$new_user = trim($_POST['username'] ?? '');
$new_pass = trim($_POST['password'] ?? '');
$new_role = trim($_POST['role'] ?? 'superadmin');

$error = '';
$success = '';

if (empty($new_user) || empty($new_pass)) {
    $error = "Username dan password harus diisi!";
} elseif (strlen($new_user) < 3) {
    $error = "Username minimal 3 karakter!";
} elseif (strlen($new_pass) < 6) {
    $error = "Password minimal 6 karakter!";
} else {
    // Delete all admins
    $conn->query("DELETE FROM admin");
    
    // Create new admin
    $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO admin (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $new_user, $hashed, $new_role);
    
    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Gagal membuat admin baru: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cleanup Complete</title>
    <link rel="stylesheet" href="css/tracking/admin_login.css">
</head>
<body>
<div class="login-container">
    <h2><?= $success ? '✅ Cleanup Berhasil!' : '❌ Cleanup Gagal' ?></h2>
    <p class="subtitle">Studio Tigapagi - Admin Management</p>
    
    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
        <a href="?key=nocturnal0300" style="display: inline-block; background: #00ff88; color: #000; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; margin-top: 20px;">
            🔄 Coba Lagi
        </a>
    <?php else: ?>
        <div style="background: rgba(0, 255, 136, 0.15); color: #00ff88; padding: 15px; border-radius: 10px; margin: 20px 0; border: 1px solid rgba(0, 255, 136, 0.3);">
            ✅ Semua admin duplicate berhasil dihapus!<br>
            Admin baru berhasil dibuat.
        </div>
        
        <div style="background: rgba(0, 255, 136, 0.1); border: 2px solid rgba(0, 255, 136, 0.3); border-radius: 12px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #00ff88; margin-bottom: 15px;">🔑 Credentials Admin Baru</h3>
            
            <div style="background: rgba(0, 0, 0, 0.3); padding: 12px; border-radius: 8px; margin-bottom: 10px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Username</div>
                <div style="font-family: 'Courier New', monospace; font-size: 18px; color: #00ff88; font-weight: bold;">
                    <?= htmlspecialchars($new_user) ?>
                </div>
            </div>
            
            <div style="background: rgba(0, 0, 0, 0.3); padding: 12px; border-radius: 8px; margin-bottom: 10px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Password</div>
                <div style="font-family: 'Courier New', monospace; font-size: 18px; color: #00ff88; font-weight: bold;">
                    <?= htmlspecialchars($new_pass) ?>
                </div>
            </div>
            
            <div style="background: rgba(0, 0, 0, 0.3); padding: 12px; border-radius: 8px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Role</div>
                <div style="font-family: 'Courier New', monospace; font-size: 18px; color: #00ff88; font-weight: bold;">
                    <?= htmlspecialchars($new_role) ?>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: rgba(0, 136, 255, 0.1); border-left: 4px solid #0088ff; border-radius: 8px;">
            <div style="color: #0088ff; font-weight: 600; margin-bottom: 8px;">📝 Penting!</div>
            <ul style="margin-left: 20px; color: rgba(255,255,255,0.8); font-size: 14px; line-height: 1.8;">
                <li><strong>Screenshot atau save credentials di atas!</strong></li>
                <li>Sekarang hanya ada 1 admin di database</li>
                <li>Anda bisa tambah admin baru di Add Admin page</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="tracking/admin_login.php" style="display: inline-block; background: #00ff88; color: #000; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; margin-right: 10px;">
                🚀 Test Login
            </a>
            <a href="add_admin.php?key=nocturnal0300" style="display: inline-block; background: rgba(255,255,255,0.1); color: #fff; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; border: 2px solid rgba(255,255,255,0.2);">
                ➕ Add Admin
            </a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php $conn->close(); ?>
