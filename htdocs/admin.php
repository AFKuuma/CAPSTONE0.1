<?php
session_start();
if ($_SESSION['role'] !== 'Admin') {
    header("Location: home.php");
    exit();
}

include 'db.php';

// Fetch all bookings
$bookings = $conn->query("
    SELECT 
        Booking.BookingID, 
        Booking.BookingDate, 
        Booking.TotalTickets, 
        Booking.TotalPrice, 
        Booking.Status, 
        User.Name AS UserName, 
        Movie.Title AS MovieTitle, 
        Showtime.ShowDate, 
        Showtime.StartTime, 
        Showtime.EndTime
    FROM Booking
    INNER JOIN User ON Booking.UserID = User.UserID
    INNER JOIN Showtime ON Booking.ShowtimeID = Showtime.ShowtimeID
    INNER JOIN Movie ON Showtime.MovieID = Movie.MovieID
");

// Fetch all users
$users = $conn->query("SELECT UserID, Name, Email, Phone, Role FROM User");

// Fetch all notifications
$notifications = $conn->query("SELECT * FROM AdminNotifications WHERE IsRead = 0");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="admin.php">Admin Panel</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Admin Panel</h2>

        <!-- Notifications Section -->
        <div class="section">
            <h3>New Notifications</h3>
            <?php if ($notifications->num_rows > 0): ?>
                <ul>
                    <?php while ($notification = $notifications->fetch_assoc()): ?>
                        <li><?= htmlspecialchars($notification['Message']) ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No new notifications.</p>
            <?php endif; ?>
        </div>

        <!-- Bookings Section -->
        <div class="section">
            <h3>All Bookings</h3>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Show Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Tickets</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings->num_rows > 0): ?>
                        <?php while ($booking = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['BookingID']) ?></td>
                                <td><?= htmlspecialchars($booking['UserName']) ?></td>
                                <td><?= htmlspecialchars($booking['MovieTitle']) ?></td>
                                <td><?= htmlspecialchars($booking['ShowDate']) ?></td>
                                <td><?= htmlspecialchars($booking['StartTime']) ?></td>
                                <td><?= htmlspecialchars($booking['EndTime']) ?></td>
                                <td><?= htmlspecialchars($booking['TotalTickets']) ?></td>
                                <td>$<?= htmlspecialchars($booking['TotalPrice']) ?></td>
                                <td><?= htmlspecialchars($booking['Status'] == 1 ? 'Confirmed' : ($booking['Status'] == 0 ? 'Pending' : 'Cancelled')) ?></td>
                                <td><?= htmlspecialchars($booking['BookingDate']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="10">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Users Section -->
        <div class="section">
            <h3>All Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows > 0): ?>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['UserID']) ?></td>
                                <td><?= htmlspecialchars($user['Name']) ?></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= htmlspecialchars($user['Phone']) ?></td>
                                <td><?= htmlspecialchars($user['Role']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
