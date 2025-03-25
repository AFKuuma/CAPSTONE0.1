<?php
session_start();
if (!isset($_SESSION['userID'])) {
    // If not logged in, redirect to login page
    header('Location: index.php');
    exit();
}

include 'db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Ticket Booking - Home</title>
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
            background: #4CAF50; /* Green background for sidebar */
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
        .movie {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px; /* Add spacing below */
            background-color: #f0f8ff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .movie:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        .movie h3 {
            margin: 0;
            color: #4CAF50; /* Green color for titles */
            font-size: 26px; /* Larger font size */
            margin-bottom: 10px; /* Add spacing below */
        }
        .movie p {
            margin: 10px 0;
            color: #555;
            font-size: 18px; /* Larger font size */
        }
        h2 {
            color: #4CAF50;
            font-size: 32px; /* Larger font size */
            margin-bottom: 20px; /* Add spacing below */
        }
        p {
            font-size: 20px; /* Larger font size */
            margin-bottom: 20px; /* Add spacing below */
        }
    </style>
</head>
<body>
    <!-- Left-side navigation -->
    <div class="sidebar">
        <ul style="list-style:none; padding:0;">
            <li><a href="booked.php">Booked Seats</a></li>
            <li><a href="booking.php">Book Seats</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <!-- Main content -->
    <div class="content">
        <h2>Welcome to the Movie Ticket Booking System!</h2>
        <p>Below are some of our current movie listings:</p>
        
        <div class="movies">
            <?php
            // Retrieve at least 10 movies from the Movie table
            $sql    = "SELECT MovieID, Title, Genre, ReleaseDate, Language, Rating, Description FROM Movie LIMIT 10";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='movie'>";
                    echo "<h3><a href='booking.php?movieID=" . htmlspecialchars($row['MovieID']) . "' style='text-decoration:none; color:#4CAF50;'>" . htmlspecialchars($row['Title']) . "</a></h3>";
                    echo "<p><strong>Genre:</strong> " . htmlspecialchars($row['Genre']) . "</p>";
                    echo "<p><strong>Release Date:</strong> " . htmlspecialchars($row['ReleaseDate']) . "</p>";
                    echo "<p><strong>Language:</strong> " . htmlspecialchars($row['Language']) . "</p>";
                    echo "<p><strong>Rating:</strong> " . htmlspecialchars($row['Rating']) . "</p>";
                    echo "<p><strong>Description:</strong> " . htmlspecialchars($row['Description']) . "</p>";
                    echo "<p><a href='booking.php?movieID=" . htmlspecialchars($row['MovieID']) . "' style='color:white; background-color:#4CAF50; padding:10px 20px; border-radius:5px; text-decoration:none;'>Book Now</a></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No movies available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
