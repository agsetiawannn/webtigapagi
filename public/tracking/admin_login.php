<?php
/**
 * Admin Login Page  
 * Login page untuk admin tracking system
 */

session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
    redirectTo('admin_dashboard.php');
}

$errorMessage = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (!isNotEmpty($username) || !isNotEmpty($password)) {
        $errorMessage = 'Username dan password harus diisi.';
    } else {
        // Check database connection
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Authenticate admin
        // NOTE: MD5 digunakan untuk backward compatibility
        // Idealnya gunakan password_hash() dan password_verify()
        $stmt = $conn->prepare("SELECT username FROM admin WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows === 1) {
            // Login successful
            $_SESSION['admin'] = $username;
            $stmt->close();
            redirectTo('admin_dashboard.php');
        } else {
            $errorMessage = 'Username atau password salah.';
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
    <title>Admin Login - Studio Tigapagi</title>
    <link rel="stylesheet" href="../css/tracking/admin_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <p class="subtitle">Studio Tigapagi Tracking System</p>
        
        <?= renderErrorMessage($errorMessage) ?>
        
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