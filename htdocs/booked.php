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

// Fetch booked seats
$sql = "SELECT b.BookingID, m.Title, b.SeatNumber, b.BookingDate 
        FROM Booking b
        JOIN Movie m ON b.MovieID = m.MovieID
        WHERE b.UserID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $_SESSION['userID']);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booked Seats</title>
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
        .booking {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px; /* Add spacing below */
            background-color: #f0f8ff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .booking:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        .booking h3 {
            margin: 0;
            color: #4CAF50; /* Green color for titles */
            font-size: 26px; /* Larger font size */
            margin-bottom: 10px; /* Add spacing below */
        }
        .booking p {
            margin: 10px 0;
            color: #555;
            font-size: 18px; /* Larger font size */
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
        <h2>Your Booked Seats</h2>
        <div class="booked-list">
            <?php
            if ($bookings->num_rows > 0) {
                while ($booking = $bookings->fetch_assoc()) {
                    echo "<div class='booking'>";
                    echo "<h3>" . htmlspecialchars($booking['Title']) . "</h3>";
                    echo "<p><strong>Seat Number:</strong> " . htmlspecialchars($booking['SeatNumber']) . "</p>";
                    echo "<p><strong>Booking Date:</strong> " . htmlspecialchars($booking['BookingDate']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No bookings found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
