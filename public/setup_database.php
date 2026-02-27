<?php
/**
 * ⚠️ SECURITY WARNING ⚠️
 * This file should ONLY be run during initial setup!
 * DELETE this file after successful setup or set SETUP_ENABLED=false in .env
 */

// Load environment
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envVars = parse_ini_file($envPath);
    foreach ($envVars as $key => $value) {
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
    }
}

// Security: Disable setup in production
$setupEnabled = $_ENV['SETUP_ENABLED'] ?? 'false';
$setupKey = $_GET['key'] ?? '';
$expectedKey = $_ENV['SETUP_KEY'] ?? '';

if ($setupEnabled !== 'true') {
    die('❌ Setup is disabled. Set SETUP_ENABLED=true in .env to enable.');
}

if (empty($expectedKey)) {
    die('❌ SETUP_KEY not set in .env file. Please add a random string as SETUP_KEY.');
}

if ($setupKey !== $expectedKey) {
    die('❌ Invalid setup key. Access denied.');
}

// Database Setup Script - Run this ONCE to create all tables
include __DIR__ . '/../resources/views/db.php';

echo "<h2>Database Setup for Tigapagi Web</h2>";
echo "<pre>";

// Array of SQL statements
$sql_statements = [
    // 1. Clients table
    "CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    // 2. Admin table
    "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    // 3. Client_progress table
    "CREATE TABLE IF NOT EXISTS client_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        onboard JSON DEFAULT NULL,
        presprint JSON DEFAULT NULL,
        sprint JSON DEFAULT NULL,
        client_view ENUM('none', 'onboard', 'presprint', 'sprint') DEFAULT 'none',
        sprint_week_focus INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
        UNIQUE KEY unique_client (client_id)
    )",
    
    // 4. Client_notes table
    "CREATE TABLE IF NOT EXISTS client_notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        note_text TEXT,
        created_by VARCHAR(50) DEFAULT 'client',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
    )"
];

$success_count = 0;
$error_count = 0;

foreach ($sql_statements as $index => $sql) {
    $table_num = $index + 1;
    if ($conn->query($sql)) {
        echo "✅ Step $table_num: Table created or already exists\n";
        $success_count++;
    } else {
        echo "❌ Step $table_num: Error - " . $conn->error . "\n";
        $error_count++;
    }
}

// Insert default admin if not exists
$admin_check = $conn->query("SELECT COUNT(*) as count FROM admin");
if ($admin_check) {
    $count = $admin_check->fetch_assoc()['count'];
    if ($count == 0) {
        // Generate secure random password
        $random_password = bin2hex(random_bytes(8)); // 16 character random password
        $hashed_password = password_hash($random_password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $username = 'admin';
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            echo "✅ Default admin user created\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "⚠️  SAVE THESE CREDENTIALS NOW! ⚠️\n";
            echo "Username: admin\n";
            echo "Password: $random_password\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "\n⚠️  This password is shown ONLY ONCE!\n";
            echo "Store it in a password manager immediately.\n\n";
        } else {
            echo "❌ Failed to create admin user: " . $conn->error . "\n";
        }
        $stmt->close();
    } else {
        echo "ℹ️  Admin user already exists\n";
    }
}

echo "\n";
echo "==========================================\n";
echo "Setup Summary:\n";
echo "✅ Success: $success_count tables\n";
echo "❌ Errors: $error_count\n";
echo "==========================================\n\n";

if ($error_count == 0) {
    echo "🎉 All tables created successfully!\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "IMPORTANT SECURITY STEPS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "1. ⚠️  SAVE your admin password above!\n";
    echo "2. ⚠️  Set SETUP_ENABLED=false in .env\n";
    echo "3. ⚠️  Or DELETE this setup file entirely\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "You can now:\n";
    echo "- Admin login: /tracking/admin_login.php\n";
    echo "- Client login: /tracking/login.php\n";
} else {
    echo "⚠️  Some errors occurred. Please check the errors above.\n";
}

echo "</pre>";
?>
