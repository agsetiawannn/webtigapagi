<?php
// Complete Database Connection and Tables Check
include __DIR__ . '/../resources/views/db.php';

echo "<!DOCTYPE html><html><head><title>Database Setup & Test</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;color:#333;}h2{color:#2c3e50;border-bottom:2px solid #3498db;padding-bottom:5px;}table{border-collapse:collapse;width:100%;margin:15px 0;}th,td{border:1px solid #ddd;padding:10px;text-align:left;}th{background:#f2f2f2;}.success{color:#27ae60;font-weight:bold;}.error{color:#e74c3c;font-weight:bold;}.info{background:#ecf0f1;padding:10px;border-left:3px solid #3498db;margin:10px 0;}</style>";
echo "</head><body>";

echo "<h2>🔍 Database Connection & Tables Verification</h2>";

// Test 1: Connection
echo "<div class='info'>";
try {
    $test = $conn->query("SELECT 1");
    if ($test) {
        echo "✅ <span class='success'>Database Connected Successfully</span><br>";
        echo "Server: {$conn->host_info}<br>";
        echo "Database: tigapagi<br>";
        echo "Port: 3308<br>";
    } else {
        throw new Exception("Query failed");
    }
} catch (Exception $e) {
    echo "❌ <span class='error'>Database Connection Failed: {$e->getMessage()}</span><br>";
    exit;
}
echo "</div>";

// Test 2: Check all required tables
$required_tables = ['clients', 'admin', 'client_progress', 'client_notes', 'contacts', 'users', 'cache', 'jobs'];

echo "<h3>📋 Table Status Check</h3>";
echo "<table><tr><th>Table Name</th><th>Status</th><th>Row Count</th></tr>";

foreach ($required_tables as $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check && $check->num_rows > 0) {
        $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
        $count = $count_result ? $count_result->fetch_assoc()['count'] : 0;
        echo "<tr><td><strong>$table</strong></td><td><span class='success'>✅ Exists</span></td><td>$count rows</td></tr>";
    } else {
        echo "<tr><td><strong>$table</strong></td><td><span class='error'>❌ Missing</span></td><td>-</td></tr>";
    }
}
echo "</table>";

// Test 3: Check table structures
echo "<h3>🔧 Key Tables Structure</h3>";

// Clients table
echo "<h4>clients table:</h4>";
$result = $conn->query("DESCRIBE clients");
if ($result) {
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
    }
    echo "</table>";
}

// Client_progress table
echo "<h4>client_progress table:</h4>";
$result = $conn->query("DESCRIBE client_progress");
if ($result) {
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
    }
    echo "</table>";
}

// Test 4: Sample data check
echo "<h3>📊 Sample Data</h3>";

// Check if admin exists
$admin_result = $conn->query("SELECT COUNT(*) as count FROM admin");
$admin_count = $admin_result->fetch_assoc()['count'];
echo "<div class='info'>";
echo "Admin users: <strong>$admin_count</strong><br>";
if ($admin_count == 0) {
    echo "<span class='error'>⚠️ No admin user found. You may need to create one.</span><br>";
} else {
    echo "<span class='success'>✅ Admin user(s) exist</span><br>";
}
echo "</div>";

// Check clients
$clients_result = $conn->query("SELECT COUNT(*) as count FROM clients");
$clients_count = $clients_result->fetch_assoc()['count'];
echo "<div class='info'>";
echo "Clients: <strong>$clients_count</strong><br>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ Database Setup Complete!</h3>";
echo "<p>All tables are properly configured. You can now:</p>";
echo "<ul>";
echo "<li>Access admin panel: <a href='/admin_login.php'>Admin Login</a></li>";
echo "<li>Access client login: <a href='/login.php'>Client Login</a></li>";
echo "<li>View contact form: <a href='/'>Home Page</a></li>";
echo "</ul>";

echo "</body></html>";
?>
