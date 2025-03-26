<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) { // Redirect to login.php if not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include styles for consistency -->
</head>
<body>
    <div class="container">
        <h1>Welcome to the Homepage</h1>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['userID']); ?>! You are logged in.</p>
        <a href="logout.php" class="button">Logout</a> <!-- Logout button -->

        <!-- Add navigation links to key pages -->
        <div class="navigation">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="movie.php">Movies</a></li>
                <li><a href="booking.php">Book Seats</a></li>
                <li><a href="booked.php">Your Bookings</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
