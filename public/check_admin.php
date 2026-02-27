<?php
// Check admin table
$conn = new mysqli('127.0.0.1', 'root', '', 'tigapagi', 3308);

echo "<h2>Admin Table Contents</h2>";
echo "<pre>";

$result = $conn->query("SELECT * FROM admin");
$count = 0;
while ($row = $result->fetch_assoc()) {
    $count++;
    echo "Row $count:\n";
    print_r($row);
    echo "\n";
}

echo "\nTotal admins: $count\n";
echo "</pre>";

echo "<h2>Table Structure</h2>";
echo "<pre>";
$result = $conn->query("DESCRIBE admin");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

$conn->close();
?>
