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
    <style>
        .movies-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .movie-item {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9; /* Default background color */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .movie-item:nth-child(odd) {
            background-color: #e3f2fd; /* Light blue for odd items */
        }
        .movie-item:nth-child(even) {
            background-color: #fce4ec; /* Light pink for even items */
        }
        .movie-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .movie-item h3 {
            margin: 0 0 10px;
        }
        .movie-item p {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
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
            echo "<div class='movies-container'>";
            while ($movie = $movies->fetch_assoc()) {
                echo "<div class='movie-item'>";
                echo "<h3>" . htmlspecialchars($movie['Title']) . "</h3>";
                echo "<p>" . htmlspecialchars($movie['Description']) . "</p>";
                echo "<p><strong>Genre:</strong> " . htmlspecialchars($movie['Genre']) . "</p>";
                echo "<p><strong>Duration:</strong> " . htmlspecialchars($movie['Duration']) . " mins</p>";
                echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie['ReleaseDate']) . "</p>";
                echo "<p><strong>Language:</strong> " . htmlspecialchars($movie['Language']) . "</p>";
                echo "<p><strong>Rating:</strong> " . htmlspecialchars($movie['Rating']) . "/10</p>";
                echo "<a href='booking.php?movieID=" . htmlspecialchars($movie['MovieID']) . "' class='btn'>Book Now</a>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No movies available.</p>";
        }
        ?>
    </div>
</body>
</html>
