<?php
session_start();
include __DIR__ . '/db.php'; // Mengasumsikan $conn sudah terkoneksi dengan benar

// Validasi admin login
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// === Tambah Klien Baru (Ditingkatkan Keamanan) ===
if (isset($_POST['add_client'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    // Validasi input dasar
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Nama atau format Email tidak valid.";
    } else {
        
        // 1. Cek duplikasi menggunakan Prepared Statement
        $stmt_check = $conn->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $exists = $stmt_check->get_result();
        
        if ($exists->num_rows > 0) {
            $error_msg = "Email sudah digunakan klien lain.";
        } else {
            // 2. Insert Klien baru menggunakan Prepared Statement (Sangat Direkomendasikan)
            $stmt_insert = $conn->prepare("INSERT INTO clients (name, email, status) VALUES (?, ?, 'active')");
            $stmt_insert->bind_param("ss", $name, $email);
            
            if ($stmt_insert->execute()) {
                $success_msg = "Klien berhasil ditambahkan.";
            } else {
                $error_msg = "Gagal menambahkan klien: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}


// === HAPUS KLIEN BARU (Menggunakan Prepared Statement) ===
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    
    if ($delete_id > 0) {
        // Hapus data terkait di client_progress
        $conn->prepare("DELETE FROM client_progress WHERE client_id = ?")->execute([$delete_id]);
        
        // Hapus client notes
        $conn->prepare("DELETE FROM client_notes WHERE client_id = ?")->execute([$delete_id]);
        
        // Hapus klien utama
        $stmt_delete = $conn->prepare("DELETE FROM clients WHERE id = ?");
        $stmt_delete->bind_param("i", $delete_id);
        $stmt_delete->execute();
        
        $success_msg = "Klien (ID: $delete_id) dan semua data proyek terkait berhasil dihapus.";
        $stmt_delete->close();
        
        // Redirect untuk membersihkan parameter GET dari URL
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_msg = "ID Klien tidak valid untuk dihapus.";
    }
}


// === Ambil Data ===
$clients = $conn->query("SELECT id, name, email, status FROM clients ORDER BY name ASC");

$active_clients = $conn->query("SELECT id, name FROM clients WHERE status='active' ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background-image: url('../img/Cover 1.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
            padding: 40px 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }
        
        .container { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; }
        
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-left h1 { 
            font-size: 48px; 
            font-weight: 700; 
            margin: 0 0 8px 0; 
            color: #fff; 
            letter-spacing: -0.5px; 
        }
        
        .header-left p { 
            font-size: 17px; 
            color: rgba(255, 255, 255, 0.8); 
            margin: 0; 
        }
        
        .header-right { 
            display: flex; 
            align-items: center;
            gap: 15px;
        }
        
        .btn-logout { 
            background: #ff4444; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: #cc0000;
            transform: scale(1.01);
        }
        
        .nav-buttons { 
            margin-bottom: 30px; 
            display: flex; 
            gap: 15px; 
            flex-wrap: wrap; 
        }
        
        .btn-contact { 
            background: #198754; 
            color: white; 
            padding: 12px 24px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-contact:hover {
            background: #157347;
            transform: scale(1.01);
        }
        
        .msg { 
            padding: 15px 20px; 
            margin: 20px 0; 
            border-radius: 8px;
            font-weight: 500;
        }
        
        .error { 
            background: rgba(248, 215, 218, 0.95); 
            color: #842029; 
            border: 1px solid #f5c2c7;
        }
        
        .success { 
            background: rgba(209, 231, 221, 0.95); 
            color: #0f5132; 
            border: 1px solid #badbcc;
        }
        
        .form-container {
            background: rgba(15, 15, 15, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .form-container h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .form-container input {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 16px;
        }
        
        .form-container input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-container button {
            background: #00ff88;
            color: #000;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-container button:hover {
            background: #00cc6a;
            transform: scale(1.01);
        }
        
        .table-container {
            background: rgba(15, 15, 15, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .table-header {
            padding: 25px 30px;
            background: rgba(0, 0, 0, 0.3);
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.2);
        }
        
        .table-header h3 {
            font-size: 24px;
            color: #fff;
            margin: 0;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse;
        }
        
        th, td { 
            padding: 20px 30px; 
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        th { 
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
        }
        
        td {
            color: rgba(255, 255, 255, 0.95);
            font-size: 15px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        a.btn { 
            padding: 8px 16px; 
            border-radius: 6px; 
            text-decoration: none; 
            display: inline-block; 
            margin-right: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        a.btn-edit { 
            background: #198754;
            color: #fff;
        }
        
        a.btn-edit:hover {
            background: #157347;
            transform: scale(1.01);
        }
        
        a.btn-delete { 
            background: #dc3545;
            color: #fff;
        }
        
        a.btn-delete:hover { 
            background: #c82333;
            transform: scale(1.01);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }
            
            .header-left h1 {
                font-size: 32px;
            }
            
            .header-left p {
                font-size: 15px;
            }
            
            .form-container, .table-container {
                padding: 20px;
            }
            
            th, td {
                padding: 12px 15px;
                font-size: 14px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <h1>Admin Dashboard</h1>
            <p>Login sebagai <b><?= htmlspecialchars($_SESSION['admin']) ?></b></p>
        </div>
        <div class="header-right">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="nav-buttons">
        <a href="/tigapagiweb/public/admin/contacts" class="btn-contact">Kelola Kontak</a>
    </div>

    <?php if ($error_msg): ?><div class="msg error"><?= $error_msg ?></div><?php endif; ?>
    <?php if ($success_msg): ?><div class="msg success"><?= $success_msg ?></div><?php endif; ?>

    <div class="form-container">
        <h3>Tambah Klien Baru</h3>
        <form method="post">
            <input type="text" name="name" placeholder="Nama Klien" required>
            <input type="email" name="email" placeholder="Email Klien" required>
            <button type="submit" name="add_client">Tambah Klien</button>
        </form>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Klien</h3>
        </div>
        <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $clients->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <a class="btn btn-edit" href="save_progress.php?client_id=<?= $row['id'] ?>">Edit Progress</a>
                
                <a class="btn btn-delete" 
                href="?action=delete&id=<?= $row['id'] ?>"
                onclick="return confirm('Apakah Anda yakin ingin menghapus klien ID <?= $row['id'] ?> dan semua data terkait?')"
                >Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>