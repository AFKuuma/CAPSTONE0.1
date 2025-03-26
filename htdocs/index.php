<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to home if already logged in
if (isset($_SESSION['userID'])) {
    header("Location: home.php");
    exit();
}

include 'db.php'; // Include the database connection file

// Check if the database connection is established
if (!$conn) {
    die("Database connection error. Please try again later.");
}

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $userID = htmlspecialchars(trim($_POST['userID']));
    $password = htmlspecialchars(trim($_POST['password']));
    $csrf_token = $_POST['csrf_token'] ?? '';

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error = "Invalid CSRF token.";
    } elseif (empty($userID) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT Password FROM User WHERE UserID = ?");
        if ($stmt) {
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['Password'])) {
                    $_SESSION['userID'] = $userID;
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Invalid credentials.";
                }
            } else {
                $error = "No account found.";
            }
            $stmt->close();
        } else {
            $error = "Database query failed.";
        }
    }
}

// Display error message if set in session
if (isset($_SESSION['error'])) {
    $error = htmlspecialchars($_SESSION['error']);
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Ticket Booking - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="form_styles.css"> <!-- New CSS file for styling -->
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($_GET['success'])) { echo "<p class='success'>Account created successfully. Please log in.</p>"; } ?>
        <?php if (isset($_GET['logout'])) { echo "<p class='success'>You have successfully logged out.</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; } ?>
        
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <input type="text" name="userID" placeholder="User ID" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <p>Don't have an account? <a href="create_account.php">Create one here</a>.</p>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
