<?php
session_start();
include 'db.php'; // Include database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['userID'])) {
    header('Location: index.php');
    exit();
}

// Fetch available movies for booking
$sql = "SELECT MovieID, Title FROM Movie";
$movies = $conn->query($sql);
if (!$movies) {
    error_log("Error fetching movies: " . $conn->error);
    die("An error occurred while fetching movies. Please try again later.");
}

// Get the pre-selected movieID, ticket count, and price from the query parameters
$selectedMovieID = $_GET['movieID'] ?? null;
$totalTickets = intval($_GET['totalTickets'] ?? 0);
$pricePerTicket = floatval($_GET['price'] ?? 0.0);

// Fetch all seats for the grid
$seatsSql = "SELECT SeatID, SeatNumber, IsBooked FROM Seat WHERE SeatNumber <= 100";
$seats = $conn->query($seatsSql);
if (!$seats) {
    error_log("Error fetching seats: " . $conn->error);
    die("An error occurred while fetching seats. Please try again later.");
}

// Organize seats into a grid
$seatGrid = [];
while ($seat = $seats->fetch_assoc()) {
    $seatGrid[$seat['SeatNumber']] = $seat['IsBooked'];
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieID = $_POST['movieID'] ?? null;
    $showtimeID = $_POST['showtimeID'] ?? null;
    $seatID = $_POST['seatID'] ?? null;
    $totalTickets = intval($_POST['totalTickets'] ?? 0);
    $totalPrice = floatval($_POST['totalPrice'] ?? 0.0);
    $userID = $_SESSION['userID'];
    $bookingDate = date('Y-m-d');
    $status = 1; // Confirmed

    // Validate inputs
    if ($movieID && $showtimeID && $seatID && $totalTickets > 0 && $totalPrice > 0) {
        // Check if the seat exists and is valid
        $seatCheckSql = "SELECT SeatID, IsBooked FROM Seat WHERE SeatID = ?";
        $seatCheckStmt = $conn->prepare($seatCheckSql);
        $seatCheckStmt->bind_param("s", $seatID);
        $seatCheckStmt->execute();
        $seatCheckResult = $seatCheckStmt->get_result();
        $seat = $seatCheckResult->fetch_assoc();

        if (!$seat) {
            $errorMessage = "Invalid seat selection. Please try again.";
        } elseif ($seat['IsBooked']) {
            $errorMessage = "This seat is already booked.";
        } else {
            $conn->begin_transaction(); // Start transaction
            try {
                // Insert booking
                $bookingID = uniqid();
                $sql = "INSERT INTO Booking (BookingID, UserID, ShowtimeID, TotalTickets, TotalPrice, BookingDate, Status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiidis", $bookingID, $userID, $showtimeID, $totalTickets, $totalPrice, $bookingDate, $status);
                $stmt->execute();

                // Insert seat booking
                $seatBookingSql = "INSERT INTO Seat_Booking (SeatID, BookingID, BookedBy) VALUES (?, ?, ?)";
                $seatStmt = $conn->prepare($seatBookingSql);
                $seatStmt->bind_param("sss", $seatID, $bookingID, $userID);
                $seatStmt->execute();

                // Mark seat as booked
                $updateSeatSql = "UPDATE Seat SET IsBooked = 1 WHERE SeatID = ?";
                $updateSeatStmt = $conn->prepare($updateSeatSql);
                $updateSeatStmt->bind_param("s", $seatID);
                $updateSeatStmt->execute();

                // Notify admin
                $notificationSql = "INSERT INTO AdminNotifications (BookingID, Message) VALUES (?, ?)";
                $notificationStmt = $conn->prepare($notificationSql);
                $notificationMessage = "New booking by user $userID for showtime $showtimeID.";
                $notificationStmt->bind_param("ss", $bookingID, $notificationMessage);
                $notificationStmt->execute();

                $conn->commit(); // Commit transaction
                header('Location: booked.php'); // Redirect to booked.php after booking
                exit();
            } catch (Exception $e) {
                $conn->rollback(); // Rollback transaction on error
                error_log("Error during booking: " . $e->getMessage());
                $errorMessage = "An error occurred while processing your booking. Please try again.";
            }
        }
    } else {
        $errorMessage = "Invalid input. Please ensure all fields are filled correctly.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Seats</title>
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
        .seat.selected {
            background-color: #007BFF;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const seats = document.querySelectorAll('.seat.available');
            const totalTicketsInput = document.querySelector('input[name="totalTickets"]');
            let selectedSeats = new Set();

            seats.forEach(seat => {
                seat.addEventListener('click', () => {
                    const totalTickets = parseInt(totalTicketsInput.value, 10) || 0;

                    if (seat.classList.contains('selected')) {
                        seat.classList.remove('selected');
                        selectedSeats.delete(seat.dataset.seatId);
                    } else if (selectedSeats.size < totalTickets) {
                        seat.classList.add('selected');
                        selectedSeats.add(seat.dataset.seatId);
                    } else {
                        alert(`You can only select up to ${totalTickets} seats.`);
                    }

                    // Update hidden input with selected seat IDs
                    document.getElementById('seatID').value = Array.from(selectedSeats).join(',');
                });
            });

            totalTicketsInput.addEventListener('input', () => {
                // Reset seat selection if ticket count changes
                selectedSeats.clear();
                document.querySelectorAll('.seat.selected').forEach(seat => seat.classList.remove('selected'));
                document.getElementById('seatID').value = '';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const totalTicketsInput = document.querySelector('input[name="totalTickets"]');
            const totalPriceInput = document.querySelector('input[name="totalPrice"]');
            const pricePerTicket = <?= json_encode($pricePerTicket) ?>;

            // Pre-fill total tickets and calculate total price
            totalTicketsInput.value = <?= json_encode($totalTickets) ?>;
            totalPriceInput.value = (<?= json_encode($totalTickets) ?> * pricePerTicket).toFixed(2);

            totalTicketsInput.addEventListener('input', () => {
                const totalTickets = parseInt(totalTicketsInput.value, 10) || 0;
                totalPriceInput.value = (totalTickets * pricePerTicket).toFixed(2);
            });
        });
    </script>
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
        <h2>Book Your Seats</h2>
        <?php if (isset($errorMessage)) echo "<p class='error-message'>" . htmlspecialchars($errorMessage) . "</p>"; ?>
        <form method="POST" action="booking.php">
            <label for="movieID">Select Movie:</label>
            <select name="movieID" required>
                <?php while ($movie = $movies->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($movie['MovieID']) ?>" <?= $selectedMovieID == $movie['MovieID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($movie['Title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="showtimeID">Showtime ID:</label>
            <input type="text" name="showtimeID" required>
            <label for="totalTickets">Total Tickets:</label>
            <input type="number" name="totalTickets" min="1" required>
            <label for="seatID">Select Seat:</label>
            <input type="hidden" id="seatID" name="seatID" required>
            <div class="seat-grid">
                <?php for ($i = 1; $i <= 100; $i++): ?>
                    <div class="seat <?= isset($seatGrid[$i]) && $seatGrid[$i] ? 'booked' : 'available' ?>" 
                         data-seat-id="<?= $i ?>"></div>
                <?php endfor; ?>
            </div>
            <label for="totalPrice">Total Price (₱):</label>
            <input type="number" name="totalPrice" step="0.01" min="0.01" readonly required>
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>
