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
    <title>Showtimes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    <div class="container">
        <h2>Showtimes</h2>
        <p>Showtime functionality coming soon...</p>
    </div>
</body>
</html>
