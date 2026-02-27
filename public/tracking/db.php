<?php
// Load Laravel environment variables securely
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    $envVars = parse_ini_file($envPath);
    foreach ($envVars as $key => $value) {
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
    }
}

// Get credentials from environment (NOT hardcoded)
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$db   = $_ENV['DB_DATABASE'] ?? 'tigapagi';
$port = $_ENV['DB_PORT'] ?? 3308;

// Create connection with error suppression for security
$conn = @new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    // Don't expose database details in production
    error_log("Database connection failed: " . $conn->connect_error);
    die("Unable to connect to database. Please contact administrator.");
}

// Set charset and autocommit
$conn->set_charset("utf8mb4");
$conn->autocommit(TRUE);
?>
