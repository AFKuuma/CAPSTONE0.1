<?php
session_start();
include 'db.php'; // Include database connection

// Enable error logging for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to login if user is not authenticated
if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Check if database connection is valid
if (!isset($conn) || $conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later.");
}

$userID = $_SESSION['userID'];

// Check if the Booking table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'Booking'");
if ($tableCheck->num_rows == 0) {
    die("The Booking table does not exist. Please contact the administrator.");
}

// Fetch booked seats for the logged-in user
$seatsSql = "
    SELECT Seat.SeatNumber, Seat.IsBooked
    FROM Seat
    LEFT JOIN Seat_Booking ON Seat.SeatID = Seat_Booking.SeatID
    LEFT JOIN Booking ON Seat_Booking.BookingID = Booking.BookingID
    WHERE Booking.UserID = ? OR Seat.IsBooked = 1
";
$stmt = $conn->prepare($seatsSql);
if (!$stmt) {
    error_log("Failed to prepare statement: " . $conn->error);
    die("An error occurred while fetching your booked seats. Please try again later.");
}
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Organize seats into a grid
$seatGrid = [];
while ($seat = $result->fetch_assoc()) {
    $seatGrid[$seat['SeatNumber']] = $seat['IsBooked'];
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteBookingID'])) {
    $deleteBookingID = $_POST['deleteBookingID'];

    // Ensure the booking belongs to the logged-in user
    $deleteStmt = $conn->prepare("DELETE FROM Booking WHERE BookingID = ? AND UserID = ?");
    $deleteStmt->bind_param("ss", $deleteBookingID, $userID);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        header("Location: booked.php");
        exit();
    } else {
        $errorMessage = "Failed to delete booking. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booked Seats</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 5px;
            margin: 20px 0;
        }
        .seat {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .seat.available {
            background-color: #4CAF50;
        }
        .seat.booked {
            background-color: rgba(0, 0, 0, 0.5);
            cursor: not-allowed;
        }
        .seat:hover:not(.booked) {
            background-color: #45a049;
            transform: scale(1.1);
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
        <h2>Your Booked Seats</h2>
        <?php if (isset($errorMessage)) echo "<p class='error-message'>" . htmlspecialchars($errorMessage) . "</p>"; ?>
        <div class="seat-grid">
            <?php for ($i = 1; $i <= 100; $i++): ?>
                <div class="seat <?= isset($seatGrid[$i]) && $seatGrid[$i] ? 'booked' : 'available' ?>"></div>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
