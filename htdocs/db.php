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

try {
    $conn->query($createBookedTableQuery);
} catch (mysqli_sql_exception $e) {
    exit("Error creating Booked table: " . $e->getMessage());
}

// Create the AdminNotifications table if it doesn't exist
$createAdminNotificationsTableQuery = "
    CREATE TABLE IF NOT EXISTS AdminNotifications (
        NotificationID INT AUTO_INCREMENT PRIMARY KEY,
        BookingID VARCHAR(40) NOT NULL,
        Message TEXT NOT NULL,
        IsRead TINYINT(1) NOT NULL DEFAULT 0,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (BookingID) REFERENCES Booking(BookingID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

try {
    $conn->query($createAdminNotificationsTableQuery);
} catch (mysqli_sql_exception $e) {
    exit("Error creating AdminNotifications table: " . $e->getMessage());
}

// Insert 10 movies into the Movie table (run this only once)
$insertMoviesQuery = "
    INSERT IGNORE INTO Movie (MovieID, Title, Genre, Duration, ReleaseDate, Language, Rating, Description) VALUES
    ('1', 'Inception', 'Sci-Fi', 148, '2010-07-16', 'English', 9, 'A thief who steals corporate secrets through dream-sharing technology.'),
    ('2', 'The Dark Knight', 'Action', 152, '2008-07-18', 'English', 9, 'Batman faces the Joker in Gotham City.'),
    ('3', 'Interstellar', 'Sci-Fi', 169, '2014-11-07', 'English', 8, 'A team of explorers travel through a wormhole in space.'),
    ('4', 'The Matrix', 'Sci-Fi', 136, '1999-03-31', 'English', 9, 'A hacker discovers the truth about his reality.'),
    ('5', 'Avengers: Endgame', 'Action', 181, '2019-04-26', 'English', 8, 'The Avengers assemble to undo Thanos\' actions.'),
    ('6', 'Titanic', 'Romance', 195, '1997-12-19', 'English', 8, 'A love story unfolds on the ill-fated RMS Titanic.'),
    ('7', 'The Shawshank Redemption', 'Drama', 142, '1994-09-22', 'English', 9, 'Two imprisoned men bond over years.'),
    ('8', 'Forrest Gump', 'Drama', 142, '1994-07-06', 'English', 8, 'The life story of a slow-witted but kind-hearted man.'),
    ('9', 'The Lion King', 'Animation', 88, '1994-06-24', 'English', 8, 'A lion cub flees his kingdom only to return as an adult.'),
    ('10', 'Jurassic Park', 'Adventure', 127, '1993-06-11', 'English', 8, 'A theme park suffers a major power breakdown.');
";

try {
    $conn->query($insertMoviesQuery);
} catch (mysqli_sql_exception $e) {
    exit("Error inserting movies: " . $e->getMessage());
}

// Insert default admin account if it doesn't exist
$insertAdminQuery = "
    INSERT IGNORE INTO User (UserID, Name, Email, Password, Phone, Role) 
    VALUES ('admin', 'Administrator', 'admin@example.com', '" . password_hash('admin', PASSWORD_BCRYPT) . "', '1234567890', 'Admin');
";

try {
    $conn->query($insertAdminQuery);
} catch (mysqli_sql_exception $e) {
    exit("Error inserting default admin account: " . $e->getMessage());
}

// Insert default admin account for userID 'janjan'
$insertAdminQuery = "
    INSERT IGNORE INTO User (UserID, Name, Email, Password, Phone, Role) 
    VALUES ('janjan', 'Jan Jan', 'janjan@example.com', '" . password_hash('jan', PASSWORD_BCRYPT) . "', '1234567890', 'Admin');
";

try {
    $conn->query($insertAdminQuery);
} catch (mysqli_sql_exception $e) {
    exit("Error inserting default admin account: " . $e->getMessage());
}
?>
