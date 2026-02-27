<?php
/**
 * Admin Dashboard
 * Main dashboard untuk admin mengelola klien dan progress
 */

session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

// Require admin authentication
requireAdminAuth();

$errorMessage = '';
$successMessage = '';

// Handle: Add New Client
if (isset($_POST['add_client'])) {
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    
    $result = createClient($conn, $name, $email);
    
    if ($result['success']) {
        $successMessage = $result['message'];
    } else {
        $errorMessage = $result['message'];
    }
}

// Handle: Delete Client
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $clientId = intval($_GET['id']);
    $result = deleteClient($conn, $clientId);
    
    if ($result['success']) {
        $successMessage = $result['message'];
        // Redirect to clean URL
        redirectTo('admin_dashboard.php');
    } else {
        $errorMessage = $result['message'];
    }
}

// Fetch all clients
$clients = getAllClients($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/tracking/admin_dashboard.css">
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

    <?= renderErrorMessage($errorMessage) ?>
    <?= renderSuccessMessage($successMessage) ?>

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