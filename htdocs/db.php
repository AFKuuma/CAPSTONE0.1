<?php
$servername = "localhost"; // Replace with your database server
$username = "mariadb";        // Replace with your database username
$password = "mariadb";            // Replace with your database password
$dbname = "mariadb";      // Replace with your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Create the Booked table if it doesn't exist
$createBookedTableQuery = "
    CREATE TABLE IF NOT EXISTS Booked (
        BookingID INT AUTO_INCREMENT PRIMARY KEY,
        UserID INT NOT NULL,
        MovieID VARCHAR(40) NOT NULL,
        BookingDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (MovieID) REFERENCES Movie(MovieID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if (!$conn->query($createBookedTableQuery)) {
    error_log("Error creating Booked table: " . $conn->error);
    die("Error creating Booked table.");
}

// Insert default admin account if it doesn't exist
$insertAdminQuery = "
    INSERT IGNORE INTO User (UserID, Name, Email, Password, Phone, Role) 
    VALUES ('admin', 'Administrator', 'admin@example.com', '" . password_hash('admin', PASSWORD_BCRYPT) . "', '1234567890', 'Admin');
";

if (!$conn->query($insertAdminQuery)) {
    error_log("Error inserting default admin account: " . $conn->error);
    die("Error inserting default admin account.");
}
?>
