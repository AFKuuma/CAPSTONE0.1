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
    <style>
        /* Inline styles for quick design improvements */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            width: 100%;
            max-width: 600px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem 0;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        .welcome-message {
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }
        .logout-button {
            display: block; /* Change to block for proper alignment */
            margin: 1rem auto 0; /* Center and add spacing */
            padding: 0.5rem 1rem;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-button:hover {
            background-color: #d32f2f;
        }
        .main-content {
            background: white;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 2rem 1rem;
        }
        .quick-links h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #4CAF50;
        }
        .links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .links-list li {
            margin: 0.5rem 0;
        }
        .link-item {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            display: block;
            text-align: center;
            padding: 0.5rem;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .link-item:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Welcome to the Homepage</h1>
            <p class="welcome-message">Hello, <?php echo htmlspecialchars($_SESSION['userID']); ?>! You are logged in.</p>
        </header>
        <main class="main-content">
            <section class="quick-links">
                <h2>Quick Links</h2>
                <ul class="links-list">
                    <li><a href="movie.php" class="link-item">Movies</a></li>
                    <li><a href="booking.php" class="link-item">Book Seats</a></li>
                    <li><a href="booked.php" class="link-item">Your Bookings</a></li>
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <li><a href="admin.php" class="link-item">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
                <a href="logout.php" class="button logout-button">Logout</a> <!-- Moved here -->
            </section>
        </main>
    </div>
</body>
</html>
