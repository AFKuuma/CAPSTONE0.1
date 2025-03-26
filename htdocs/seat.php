<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

// Fetch all seats
$sql = "SELECT SeatID, SeatNumber, SeatType, IsBooked FROM Seat";
$seats = $conn->query($sql);
if (!$seats) {
    error_log("Error fetching seats: " . $conn->error);
    die("An error occurred while fetching seats. Please try again later.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Seats</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="booking.php">Book Seats</a></li>
            <li><a href="seat.php">Manage Seats</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Manage Seats</h2>
        <table>
            <thead>
                <tr>
                    <th>Seat Number</th>
                    <th>Seat Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($seats->num_rows > 0) {
                    while ($seat = $seats->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($seat['SeatNumber']) . "</td>";
                        echo "<td>" . htmlspecialchars($seat['SeatType']) . "</td>";
                        echo "<td>" . ($seat['IsBooked'] ? "Booked" : "Available") . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No seats available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
