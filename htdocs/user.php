<?php
session_start();
include('db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Welcome to Robinson Movieworld Iligan</h2>
        
        <?php if (!isset($_SESSION['user_id'])) { ?>
        <h3 class="text-center">User Login</h3>
        <form method="POST" action="user_login.php" class="mt-4">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php } else { ?>
        <p>Welcome, User! <a href="logout.php" class="btn btn-danger">Logout</a></p>
        <?php } ?>
    </div>
</body>
</html>