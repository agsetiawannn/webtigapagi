<?php
// Comprehensive Connection Test Script
// This file tests all database connections and file includes

echo "<!DOCTYPE html>";
echo "<html lang='id'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Comprehensive Connection Test</title>";
echo "<style>
    body { font-family: 'Segoe UI', Tahoma, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f7fa; }
    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; margin-bottom: 20px; }
    .section { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { color: #27ae60; font-weight: bold; }
    .error { color: #e74c3c; font-weight: bold; }
    .warning { color: #f39c12; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
    th { background: #f8f9fa; font-weight: 600; }
    .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-error { background: #f8d7da; color: #721c24; }
    .badge-info { background: #d1ecf1; color: #0c5460; }
    h2 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
    h3 { color: #34495e; margin-top: 20px; }
    code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; }
</style>";
echo "</head>";
echo "<body>";

echo "<div class='header'>";
echo "<h1 style='margin:0;'>🔍 Comprehensive Connection Test</h1>";
echo "<p style='margin:5px 0 0 0;'>Testing all database connections and file includes</p>";
echo "</div>";

$total_tests = 0;
$passed_tests = 0;
$failed_tests = 0;

// TEST 1: Database Connection via db.php
echo "<div class='section'>";
echo "<h2>1. Database Connection Test (Native PHP)</h2>";

$db_path = __DIR__ . '/../resources/views/db.php';
if (file_exists($db_path)) {
    echo "<span class='success'>✅ db.php file exists</span><br>";
    include $db_path;
    
    if (isset($conn) && $conn instanceof mysqli) {
        echo "<span class='success'>✅ Connection object created</span><br>";
        $total_tests++;
        
        if ($conn->connect_error) {
            echo "<span class='error'>❌ Connection failed: " . $conn->connect_error . "</span><br>";
            $failed_tests++;
        } else {
            echo "<span class='success'>✅ Connected to MySQL successfully</span><br>";
            echo "<table>";
            echo "<tr><th>Property</th><th>Value</th></tr>";
            echo "<tr><td>Host</td><td>127.0.0.1:3308</td></tr>";
            echo "<tr><td>Database</td><td>tigapagi</td></tr>";
            echo "<tr><td>Server Info</td><td>{$conn->server_info}</td></tr>";
            echo "<tr><td>Character Set</td><td>{$conn->character_set_name()}</td></tr>";
            echo "</table>";
            $passed_tests++;
        }
    } else {
        echo "<span class='error'>❌ Connection object not created properly</span><br>";
        $total_tests++;
        $failed_tests++;
    }
} else {
    echo "<span class='error'>❌ db.php file not found at: $db_path</span><br>";
    $total_tests++;
    $failed_tests++;
}
echo "</div>";

// TEST 2: All Required Tables
echo "<div class='section'>";
echo "<h2>2. Database Tables Verification</h2>";

$required_tables = [
    'clients' => 'Client management data',
    'admin' => 'Admin users',
    'client_progress' => 'Client progress tracking',
    'client_notes' => 'Client and admin notes',
    'contacts' => 'Contact form submissions (Laravel)',
    'users' => 'Laravel users table',
    'cache' => 'Laravel cache table',
    'jobs' => 'Laravel jobs queue'
];

echo "<table>";
echo "<tr><th>Table Name</th><th>Description</th><th>Status</th><th>Rows</th></tr>";

foreach ($required_tables as $table => $description) {
    $total_tests++;
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    
    if ($check && $check->num_rows > 0) {
        $count_result = $conn->query("SELECT COUNT(*) as count FROM `$table`");
        $count = $count_result ? $count_result->fetch_assoc()['count'] : 0;
        echo "<tr>";
        echo "<td><strong>$table</strong></td>";
        echo "<td>$description</td>";
        echo "<td><span class='badge badge-success'>✅ EXISTS</span></td>";
        echo "<td>$count rows</td>";
        echo "</tr>";
        $passed_tests++;
    } else {
        echo "<tr>";
        echo "<td><strong>$table</strong></td>";
        echo "<td>$description</td>";
        echo "<td><span class='badge badge-error'>❌ MISSING</span></td>";
        echo "<td>-</td>";
        echo "</tr>";
        $failed_tests++;
    }
}
echo "</table>";
echo "</div>";

// TEST 3: PHP Files with db.php includes
echo "<div class='section'>";
echo "<h2>3. PHP Files Database Include Test</h2>";

$php_files_to_test = [
    'login.php' => 'Client login page',
    'dashboard.php' => 'Client dashboard',
    'admin_login.php' => 'Admin login page',
    'admin_dashboard.php' => 'Admin dashboard',
    'edit_client.php' => 'Edit client page',
    'save_progress.php' => 'Save progress page',
    'test_db.php' => 'Database test page'
];

echo "<table>";
echo "<tr><th>File</th><th>Description</th><th>Exists</th><th>Has db.php Include</th></tr>";

foreach ($php_files_to_test as $file => $desc) {
    $total_tests++;
    $file_path = __DIR__ . "/../resources/views/$file";
    $exists = file_exists($file_path);
    
    $has_include = false;
    if ($exists) {
        $content = file_get_contents($file_path);
        $has_include = (strpos($content, "include __DIR__ . '/db.php'") !== false || 
                       strpos($content, 'include __DIR__ . "/db.php"') !== false);
    }
    
    echo "<tr>";
    echo "<td><strong>$file</strong></td>";
    echo "<td>$desc</td>";
    echo "<td>" . ($exists ? "<span class='badge badge-success'>✅</span>" : "<span class='badge badge-error'>❌</span>") . "</td>";
    echo "<td>" . ($has_include ? "<span class='badge badge-success'>✅</span>" : "<span class='badge badge-error'>❌</span>") . "</td>";
    echo "</tr>";
    
    if ($exists && $has_include) {
        $passed_tests++;
    } else {
        $failed_tests++;
    }
}
echo "</table>";
echo "</div>";

// TEST 4: Laravel Configuration
echo "<div class='section'>";
echo "<h2>4. Laravel Database Configuration</h2>";

$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    echo "<span class='success'>✅ .env file exists</span><br><br>";
    
    $env_content = file_get_contents($env_file);
    preg_match('/DB_CONNECTION=(.*)/', $env_content, $db_connection);
    preg_match('/DB_HOST=(.*)/', $env_content, $db_host);
    preg_match('/DB_PORT=(.*)/', $env_content, $db_port);
    preg_match('/DB_DATABASE=(.*)/', $env_content, $db_database);
    preg_match('/DB_USERNAME=(.*)/', $env_content, $db_username);
    
    echo "<table>";
    echo "<tr><th>Configuration</th><th>Value</th><th>Status</th></tr>";
    
    $configs = [
        'DB_CONNECTION' => trim($db_connection[1] ?? ''),
        'DB_HOST' => trim($db_host[1] ?? ''),
        'DB_PORT' => trim($db_port[1] ?? ''),
        'DB_DATABASE' => trim($db_database[1] ?? ''),
        'DB_USERNAME' => trim($db_username[1] ?? '')
    ];
    
    foreach ($configs as $key => $value) {
        $total_tests++;
        $is_valid = !empty($value);
        echo "<tr>";
        echo "<td><strong>$key</strong></td>";
        echo "<td><code>$value</code></td>";
        echo "<td>" . ($is_valid ? "<span class='badge badge-success'>✅</span>" : "<span class='badge badge-error'>❌</span>") . "</td>";
        echo "</tr>";
        
        if ($is_valid) {
            $passed_tests++;
        } else {
            $failed_tests++;
        }
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ .env file not found</span><br>";
    $total_tests++;
    $failed_tests++;
}
echo "</div>";

// TEST 5: Sample Queries
echo "<div class='section'>";
echo "<h2>5. Sample Database Queries Test</h2>";

$queries = [
    'Clients Count' => "SELECT COUNT(*) as count FROM clients",
    'Admin Count' => "SELECT COUNT(*) as count FROM admin",
    'Progress Records' => "SELECT COUNT(*) as count FROM client_progress",
    'Notes Count' => "SELECT COUNT(*) as count FROM client_notes",
    'Contact Form Submissions' => "SELECT COUNT(*) as count FROM contacts"
];

echo "<table>";
echo "<tr><th>Query</th><th>Result</th><th>Status</th></tr>";

foreach ($queries as $label => $sql) {
    $total_tests++;
    $result = $conn->query($sql);
    
    if ($result) {
        $data = $result->fetch_assoc();
        $count = $data['count'];
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td><strong>$count</strong> record(s)</td>";
        echo "<td><span class='badge badge-success'>✅</span></td>";
        echo "</tr>";
        $passed_tests++;
    } else {
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td><span class='error'>Query failed: " . $conn->error . "</span></td>";
        echo "<td><span class='badge badge-error'>❌</span></td>";
        echo "</tr>";
        $failed_tests++;
    }
}
echo "</table>";
echo "</div>";

// TEST 6: Routes Test
echo "<div class='section'>";
echo "<h2>6. Route Definitions (Laravel)</h2>";

$routes_file = __DIR__ . '/../routes/web.php';
if (file_exists($routes_file)) {
    echo "<span class='success'>✅ routes/web.php exists</span><br><br>";
    
    $routes_content = file_get_contents($routes_file);
    
    $route_checks = [
        'ContactController' => [
            'pattern' => '/use App\\\\Http\\\\Controllers\\\\ContactController/',
            'description' => 'Contact controller imported'
        ],
        'Contact Route' => [
            'pattern' => "/Route::post\\('\/contact'/",
            'description' => 'Contact form route defined'
        ],
        'Home Route' => [
            'pattern' => "/Route::get\\('\/'/",
            'description' => 'Home page route'
        ],
        'Login Routes' => [
            'pattern' => "/Route::any\\('\/login\\.php'/",
            'description' => 'Login route defined'
        ],
        'Admin Routes' => [
            'pattern' => "/Route::any\\('\/admin_dashboard\\.php'/",
            'description' => 'Admin dashboard route'
        ]
    ];
    
    echo "<table>";
    echo "<tr><th>Route Check</th><th>Description</th><th>Status</th></tr>";
    
    foreach ($route_checks as $name => $check) {
        $total_tests++;
        $found = preg_match($check['pattern'], $routes_content);
        
        echo "<tr>";
        echo "<td><strong>$name</strong></td>";
        echo "<td>{$check['description']}</td>";
        echo "<td>" . ($found ? "<span class='badge badge-success'>✅</span>" : "<span class='badge badge-error'>❌</span>") . "</td>";
        echo "</tr>";
        
        if ($found) {
            $passed_tests++;
        } else {
            $failed_tests++;
        }
    }
    echo "</table>";
} else {
    echo "<span class='error'>❌ routes/web.php not found</span><br>";
}
echo "</div>";

// SUMMARY
echo "<div class='section' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'>";
echo "<h2 style='color: white; border-bottom: 3px solid rgba(255,255,255,0.3);'>📊 Test Summary</h2>";
echo "<table style='color: white;'>";
echo "<tr><th style='color: white;'>Metric</th><th style='color: white;'>Value</th></tr>";
echo "<tr><td><strong>Total Tests</strong></td><td><strong>$total_tests</strong></td></tr>";
echo "<tr><td><strong>✅ Passed</strong></td><td><strong style='color: #2ecc71;'>$passed_tests</strong></td></tr>";
echo "<tr><td><strong>❌ Failed</strong></td><td><strong style='color: #e74c3c;'>$failed_tests</strong></td></tr>";

$success_rate = $total_tests > 0 ? round(($passed_tests / $total_tests) * 100, 2) : 0;
echo "<tr><td><strong>Success Rate</strong></td><td><strong style='font-size: 24px;'>{$success_rate}%</strong></td></tr>";
echo "</table>";

if ($failed_tests == 0) {
    echo "<div style='background: rgba(255,255,255,0.2); padding: 15px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3 style='color: white; margin: 0;'>🎉 All Tests Passed!</h3>";
    echo "<p style='margin: 10px 0 0 0;'>All database connections and file includes are working correctly!</p>";
    echo "</div>";
} else {
    echo "<div style='background: rgba(255,255,255,0.2); padding: 15px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3 style='color: white; margin: 0;'>⚠️ Some Tests Failed</h3>";
    echo "<p style='margin: 10px 0 0 0;'>Please review the failed tests above and address the issues.</p>";
    echo "</div>";
}
echo "</div>";

// Quick Access Links
echo "<div class='section'>";
echo "<h2>🔗 Quick Access Links</h2>";
echo "<ul style='list-style: none; padding: 0;'>";
echo "<li style='margin: 10px 0;'>🏠 <a href='/' style='color: #3498db; text-decoration: none; font-weight: 600;'>Home Page</a></li>";
echo "<li style='margin: 10px 0;'>👤 <a href='/login.php' style='color: #3498db; text-decoration: none; font-weight: 600;'>Client Login</a></li>";
echo "<li style='margin: 10px 0;'>🔐 <a href='/admin_login.php' style='color: #3498db; text-decoration: none; font-weight: 600;'>Admin Login</a></li>";
echo "<li style='margin: 10px 0;'>🗄️ <a href='/test_db.php' style='color: #3498db; text-decoration: none; font-weight: 600;'>Database Test</a></li>";
echo "</ul>";
echo "</div>";

echo "</body>";
echo "</html>";
?>
