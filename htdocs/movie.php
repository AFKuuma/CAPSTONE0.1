<?php
session_start();
include 'db.php'; // Include database connection

// Disable error display in production
error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Check if $conn is defined and valid
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed. Please try again later.");
}

// Fetch movies
$movies = $conn->query("SELECT * FROM Movie");
if (!$movies) {
    die("Error fetching movies. Please try again later.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="booked.php">Booked Seats</a></li>
            <li><a href="booking.php">Book Seats</a></li>
            <li><a href="movie.php">Movies</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Available Movies</h2>
        <?php
        if ($movies->num_rows > 0) {
            while ($movie = $movies->fetch_assoc()) {
                echo "<div class='movie-item'>";
                echo "<h3>" . htmlspecialchars($movie['Title']) . "</h3>";
                echo "<p>" . htmlspecialchars($movie['Description']) . "</p>";
                echo "<p>Genre: " . htmlspecialchars($movie['Genre']) . ", Duration: " . htmlspecialchars($movie['Duration']) . " mins</p>";
                echo "<a href='booking.php?movie_id=" . urlencode($movie['MovieID']) . "'>Book Now</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No movies available.</p>";
        }
        ?>
    </div>
</body>
</html>
