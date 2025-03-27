<?php
session_start();
include 'db.php'; // Include database connection

// Enable error reporting temporarily for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Ensure $conn is defined and valid
if (!isset($conn)) {
    die("Database connection is not initialized. Please check your database configuration.");
}

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch movies
$sql = "SELECT MovieID, Title FROM Movie";
$movies = $conn->query($sql);
if (!$movies) {
    error_log("Error fetching movies: " . $conn->error);
    die("An error occurred while fetching movies. Please try again later.");
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
                echo "<form action='booking.php' method='GET'>";
                echo "<input type='hidden' name='movieID' value='" . htmlspecialchars($movie['MovieID']) . "'>";
                echo "<label for='ticketCount'>Number of Tickets:</label>";
                echo "<input type='number' id='ticketCount' name='ticketCount' min='1' max='10' required>";
                echo "<button type='submit' class='btn'>Book Now</button>";
                echo "</form>";
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