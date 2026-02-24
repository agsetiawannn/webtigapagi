<?php
// Test Database Connection & Data Check
include __DIR__ . '/db.php';

echo "<h2>Database Connection Test</h2>";

// Test 1: Connection
if ($conn->ping()) {
    echo "✅ <strong>Database Connected Successfully</strong><br>";
    echo "Server: {$conn->host_info}<br><br>";
} else {
    echo "❌ Database Connection Failed<br>";
    exit;
}

// Test 2: Check client_progress table
echo "<h3>Client Progress Data:</h3>";
$result = $conn->query("SELECT * FROM client_progress ORDER BY updated_at DESC LIMIT 5");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Client ID</th><th>Client View</th><th>Sprint Week</th><th>Updated At</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['client_id']}</td>";
        echo "<td><strong>{$row['client_view']}</strong></td>";
        echo "<td>{$row['sprint_week_focus']}</td>";
        echo "<td>{$row['updated_at']}</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ No data found in client_progress table<br>";
}

// Test 3: Check specific client data
if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);
    echo "<h3>Data for Client ID: {$client_id}</h3>";
    
    $stmt = $conn->prepare("SELECT * FROM client_progress WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
        echo "Client View: " . $data['client_view'] . "\n";
        echo "Sprint Week Focus: " . $data['sprint_week_focus'] . "\n";
        echo "Updated At: " . $data['updated_at'] . "\n\n";
        echo "Onboard Data:\n" . json_encode(json_decode($data['onboard'], true), JSON_PRETTY_PRINT) . "\n\n";
        echo "Pre-Sprint Data:\n" . json_encode(json_decode($data['presprint'], true), JSON_PRETTY_PRINT) . "\n\n";
        echo "Sprint Data:\n" . json_encode(json_decode($data['sprint'], true), JSON_PRETTY_PRINT) . "\n";
        echo "</pre>";
    } else {
        echo "❌ No data found for Client ID: {$client_id}<br>";
    }
}

echo "<hr>";
echo "<p>Usage: <code>test_db.php?client_id=1</code> to check specific client data</p>";
?>
