<?php
error_reporting(0);
ini_set('display_errors', '0');

// Security check
if (!isset($_GET['key']) || $_GET['key'] !== 'nocturnal0300') {
    die("❌ Invalid key. Use ?key=nocturnal0300");
}

// Use same DB connection as tracking system
require_once __DIR__ . '/tracking/db.php';

// Get existing admins
$existing_admins = [];
$result = $conn->query("SELECT id, username, role FROM admin ORDER BY id");
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
            $result = $conn->query("SELECT id, username, role FROM admin ORDER BY id");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $existing_admins[] = $row;
                }
            }
        } else {
            if ($conn->errno == 1062) {
                $error = "Username '{$new_user}' sudah digunakan!";
            } else {
                $error = "Gagal menambah admin: " . $conn->error;
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
    <title>Add Admin - Studio Tigapagi</title>
    <link rel="stylesheet" href="css/tracking/admin_login.css">
</head>
<body>
<div class="login-container" style="max-width: 800px;">
    <h2>➕ Tambah Admin Baru</h2>
    <p class="subtitle">Studio Tigapagi - Admin Management</p>
    
    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="background: rgba(0, 255, 136, 0.15); color: #00ff88; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(0, 255, 136, 0.3);">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
        
        <div style="background: rgba(0, 255, 136, 0.1); border: 2px solid rgba(0, 255, 136, 0.3); border-radius: 12px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #00ff88; margin-bottom: 15px;">🔑 Credentials Admin Baru</h3>
            
            <div style="background: rgba(0, 0, 0, 0.3); padding: 12px; border-radius: 8px; margin-bottom: 10px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Username</div>
                <div style="font-family: 'Courier New', monospace; font-size: 18px; color: #00ff88; font-weight: bold;">
                    <?= htmlspecialchars($new_user) ?>
                </div>
            </div>
            
            <div style="background: rgba(0, 0, 0, 0.3); padding: 12px; border-radius: 8px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Password</div>
                <div style="font-family: 'Courier New', monospace; font-size: 18px; color: #00ff88; font-weight: bold;">
                    <?= htmlspecialchars($new_pass) ?>
                </div>
            </div>
        </div>
        
        <a href="tracking/admin_login.php" style="display: inline-block; background: #00ff88; color: #000; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; margin-top: 15px;">
            🚀 Test Login Sekarang
        </a>
    <?php endif; ?>
    
    <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; margin: 20px 0;">
        <h3 style="color: #fff; margin-bottom: 15px;">👥 Existing Admins (<?= count($existing_admins) ?>)</h3>
        <table style="width: 100%; color: #fff;">
            <tr style="background: rgba(0, 255, 136, 0.1);">
                <th style="padding: 10px; text-align: left;">ID</th>
                <th style="padding: 10px; text-align: left;">Username</th>
                <th style="padding: 10px; text-align: left;">Role</th>
            </tr>
            <?php foreach ($existing_admins as $admin): ?>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <td style="padding: 10px;"><?= htmlspecialchars($admin['id']) ?></td>
                <td style="padding: 10px;"><strong><?= htmlspecialchars($admin['username']) ?></strong></td>
                <td style="padding: 10px;">
                    <span style="background: #00ff88; color: #000; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        <?= htmlspecialchars($admin['role'] ?? 'admin') ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
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
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
        </div>
        
        <button type="submit">➕ Tambah Admin</button>
    </form>
    
    <div style="margin-top: 30px; padding: 15px; background: rgba(0, 136, 255, 0.1); border-left: 4px solid #0088ff; border-radius: 8px;">
        <div style="color: #0088ff; font-weight: 600; margin-bottom: 8px;">💡 Info</div>
        <ul style="margin-left: 20px; color: rgba(255,255,255,0.8); font-size: 14px; line-height: 1.8;">
            <li>Username harus unik (tidak boleh sama)</li>
            <li>Password di-hash dengan bcrypt (aman!)</li>
            <li>Tidak ada limit jumlah admin</li>
        </ul>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
