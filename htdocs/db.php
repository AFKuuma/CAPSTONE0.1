<?php
// Enable error reporting for debugging during development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb"; // Updated to match the provided database name

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Set the charset to avoid charset issues
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Handle connection error
    exit("Connection failed: " . $e->getMessage());
}
?>
