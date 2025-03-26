<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Fetch available movies for booking
$sql = "SELECT MovieID, Title, Genre, ReleaseDate, Language, Rating FROM Movie";
$movies = $conn->query($sql);
if (!$movies) {
    die("Error fetching movies: " . $conn->error);
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieID = isset($_POST['movieID']) ? $conn->real_escape_string($_POST['movieID']) : null;
    $showtimeID = isset($_POST['showtimeID']) ? $conn->real_escape_string($_POST['showtimeID']) : null;
    $totalTickets = isset($_POST['totalTickets']) ? intval($_POST['totalTickets']) : 0;
    $totalPrice = isset($_POST['totalPrice']) ? floatval($_POST['totalPrice']) : 0.0;
    $userID = $_SESSION['userID'];
    $bookingDate = date('Y-m-d');
    $status = 1; // Confirmed

    // Validate inputs
    if ($movieID && $showtimeID && $totalTickets > 0 && $totalPrice > 0) {
        $bookingID = uniqid(); // Generate a unique booking ID

        $sql = "INSERT INTO Booking (BookingID, UserID, ShowtimeID, TotalTickets, TotalPrice, BookingDate, Status) 
                VALUES ('$bookingID', '$userID', '$showtimeID', '$totalTickets', '$totalPrice', '$bookingDate', '$status')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Booking successful!";
        } else {
            $errorMessage = "Error: " . htmlspecialchars($conn->error); // Escape error
        }
    } else {
        $errorMessage = "Invalid input. Please ensure all fields are filled correctly.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Seats</title>
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
        <h2>Book Your Seats</h2>
        <?php
        if (isset($successMessage)) {
            echo "<p class='success-message'>" . htmlspecialchars($successMessage) . "</p>";
        }
        if (isset($errorMessage)) {
            echo "<p class='error-message'>" . htmlspecialchars($errorMessage) . "</p>";
        }
        ?>
        <?php
        if ($movies->num_rows > 0) {
            while ($movie = $movies->fetch_assoc()) {
                echo "<div class='movie-item'>";
                echo "<h3>" . htmlspecialchars($movie['Title']) . "</h3>";
                echo "<p><strong>Genre:</strong> " . htmlspecialchars($movie['Genre']) . "</p>";
                echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie['ReleaseDate']) . "</p>";
                echo "<p><strong>Language:</strong> " . htmlspecialchars($movie['Language']) . "</p>";
                echo "<p><strong>Rating:</strong> " . htmlspecialchars($movie['Rating']) . "</p>";
                echo "<form method='POST' action='booking.php'>";
                echo "<input type='hidden' name='movieID' value='" . htmlspecialchars($movie['MovieID']) . "'>";
                echo "<label for='showtimeID'>Showtime ID:</label>";
                echo "<input type='text' name='showtimeID' required>";
                echo "<label for='totalTickets'>Total Tickets:</label>";
                echo "<input type='number' name='totalTickets' min='1' required>";
                echo "<label for='totalPrice'>Total Price:</label>";
                echo "<input type='number' name='totalPrice' step='0.01' min='0.01' required>";
                echo "<button type='submit'>Book Now</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No movies available for booking.</p>";
        }
        ?>
    </div>
</body>
</html>
