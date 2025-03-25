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

// Initialize variables
$movie = null;
$reviews = null;
$movies = null;

// Check if a specific movie ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $movieID = intval($_GET['id']);

    // Fetch movie details
    $sql = "SELECT * FROM Movie WHERE MovieID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $movieID);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die("Error executing query: " . $stmt->error);
    }
    $movie = $result->fetch_assoc();
    $stmt->close();

    if (!$movie) {
        echo "<div class='error-message'>Movie not found.</div>";
        exit();
    }

    // Fetch reviews for the movie
    $sql = "SELECT r.Rating, r.Comment, r.ReviewDate, u.Name 
            FROM Review r
            JOIN User u ON r.UserID = u.UserID
            WHERE r.MovieID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $movieID);
    $stmt->execute();
    $reviews = $stmt->get_result();
    if (!$reviews) {
        die("Error executing query: " . $stmt->error);
    }
    $stmt->close();
} else {
    // Fetch all movies if no specific movie ID is provided
    $sql = "SELECT MovieID, Title, Genre, ReleaseDate, Language, Rating FROM Movie";
    $movies = $conn->query($sql);
    if (!$movies) {
        die("Error fetching movies: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movies</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .sidebar {
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #333;
            padding-top: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            font-size: 18px;
        }
        .sidebar ul li a:hover {
            background-color: #575757;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .movie-item, .movie-details, .reviews {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .movie-item h3, .movie-details h2 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .movie-item p, .movie-details p {
            margin: 5px 0;
            color: #555;
        }
        .book-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
        }
        .book-button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
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
        <div class="container">
            <?php if ($movie) { ?>
                <div class="movie-details">
                    <h2><?php echo htmlspecialchars($movie['Title']); ?></h2>
                    <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['Genre']); ?></p>
                    <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['ReleaseDate']); ?></p>
                    <p><strong>Language:</strong> <?php echo htmlspecialchars($movie['Language']); ?></p>
                    <p><strong>Rating:</strong> <?php echo htmlspecialchars($movie['Rating']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['Description']); ?></p>
                    <a href="booking.php?movieID=<?php echo htmlspecialchars($movieID); ?>" class="book-button">Book Now</a>
                </div>
                <div class="reviews">
                    <h3>Reviews</h3>
                    <?php
                    if ($reviews->num_rows > 0) {
                        while ($review = $reviews->fetch_assoc()) {
                            echo "<div class='review'>";
                            echo "<h4>" . htmlspecialchars($review['Name']) . "</h4>";
                            echo "<p><strong>Rating:</strong> " . htmlspecialchars($review['Rating']) . "</p>";
                            echo "<p><strong>Comment:</strong> " . htmlspecialchars($review['Comment']) . "</p>";
                            echo "<p><strong>Date:</strong> " . htmlspecialchars($review['ReviewDate']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No reviews yet.</p>";
                    }
                    ?>
                </div>
            <?php } elseif ($movies) { ?>
                <h2>Available Movies</h2>
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
                            echo "<a href='movie.php?id=" . htmlspecialchars($movie['MovieID']) . "' class='book-button'>View Details</a>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No movies available at the moment.</p>";
                    }
                    ?>
                </div>
            <?php } else { ?>
                <p class="error-message">An unexpected error occurred. Please try again later.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>
