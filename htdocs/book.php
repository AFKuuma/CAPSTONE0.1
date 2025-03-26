<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Tickets</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    <div class="container">
        <h2>Book Tickets</h2>
        <p>Booking functionality coming soon...</p>
    </div>
</body>
</html>
