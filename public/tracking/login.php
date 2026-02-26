<?php
/**
 * Client Login Page
 * Login page untuk client tracking system
 */

session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

// Redirect if already logged in
if (isset($_SESSION['client_id'])) {
    redirectTo('dashboard.php');
}

$errorMessage = '';

// Handle login form submission
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = sanitizeInput($_POST['email'] ?? '');

    // Validate email
    if (!isValidEmail($email)) {
        $errorMessage = 'Format email tidak valid.';
    } else {
        // Get active client by email
        $stmt = $conn->prepare("SELECT id, name FROM clients WHERE email = ? AND status = 'active'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $client = $result->fetch_assoc();

            // Set session
            $_SESSION['client_id'] = $client['id'];
            $_SESSION['client_name'] = $client['name'];

            $stmt->close();
            redirectTo('dashboard.php');
        } else {
            $errorMessage = 'Email tidak terdaftar atau tidak aktif.';
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
    <title>Login Client - Studio Tigapagi</title>
    <link rel="stylesheet" href="../css/tracking/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Client Login</h2>
        <p class="subtitle">Studio Tigapagi Tracking System</p>
        
        <?= renderErrorMessage($errorMessage) ?>
        
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
