<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

include 'db_connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch available movies for booking
$sql = "SELECT MovieID, Title, Genre, ReleaseDate, Language, Rating FROM Movie";
$movies = $conn->query($sql);
if (!$movies) {
    die("Error fetching movies: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Seats</title>
    <style>
        /* Enhanced styling for a user-friendly appearance */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .sidebar {
            float: left;
            width: 25%;
            background: #4CAF50;
            color: white;
            height: 100vh;
            padding: 30px;
            font-size: 18px;
            text-align: center; /* Center-align text */
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 20px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 20px; /* Larger font size */
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #45a049;
            color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .content {
            float: left;
            width: 70%;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin: 20px;
            text-align: center; /* Center-align content */
        }
        h2 {
            color: #4CAF50;
            font-size: 32px; /* Larger font size */
            margin-bottom: 20px; /* Add spacing below */
        }
        form {
            background-color: #f0f8ff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center; /* Center-align form */
        }
        form:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        form label {
            font-weight: bold;
            color: #4CAF50; /* Green color for labels */
            font-size: 20px; /* Larger font size */
            margin-bottom: 10px; /* Add spacing below */
            display: block; /* Ensure labels are block elements */
        }
        form input[type="number"], form select {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid #ccc;
            border-radius: 10px;
            font-size: 18px; /* Larger font size */
            transition: border-color 0.3s;
        }
        form input[type="number"]:hover, form select:hover {
            border-color: #4CAF50;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px; /* Larger font size */
            margin-top: 20px; /* Add spacing above */
            transition: background-color 0.3s;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        p {
            color: #555;
            font-size: 18px; /* Larger font size */
            margin-bottom: 20px; /* Add spacing below */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul style="list-style:none; padding:0;">
            <li><a href="home.php">Home</a></li>
            <li><a href="booked.php">Booked Seats</a></li>
            <li><a href="booking.php">Book Seats</a></li>
            <li><a href="movie.php">Movies</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <div class="content">
        <div class="container">
            <h2>Book Your Seats</h2>
            <div class="movie-list">
                <?php
                if ($movies->num_rows > 0) {
                    while ($movie = $movies->fetch_assoc()) {
                        echo "<div class='movie-item'>";
                        echo "<h3>" . htmlspecialchars($movie['Title']) . "</h3>";
                        echo "<p><strong>Genre:</strong> " . htmlspecialchars($movie['Genre']) . "</p>";
                        echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie['ReleaseDate']) . "</p>";
                        echo "<p><strong>Language:</strong> " . htmlspecialchars($movie['Language']) . "</p>";
                        echo "<p><strong>Rating:</strong> " . htmlspecialchars($movie['Rating']) . "</p>";
                        echo "<a href='seat_selection.php?movieID=" . htmlspecialchars($movie['MovieID']) . "' class='book-button'>Book Now</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No movies available for booking.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
