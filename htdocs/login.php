<?php
session_start();
include 'db.php'; // Updated to include the correct database connection file

// Check if the database connection is established
if (!$conn) {
    die("Database connection error. Please try again later.");
}

// Redirect to home if already logged in
if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
    header("Location: home.php");
    exit();
}

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $userID   = htmlspecialchars(trim($_POST['userID']));
    $password = htmlspecialchars(trim($_POST['password']));
    $csrf_token = $_POST['csrf_token'] ?? '';

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error = "Invalid CSRF token.";
    } elseif (empty($userID) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        // Validate user credentials
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
                    $error = "Invalid credentials. Please try again.";
                }
            } else {
                $error = "No account found with the provided User ID.";
            }
            $stmt->close();
        } else {
            $error = "Database query failed: " . htmlspecialchars($conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; } ?>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <input type="text" name="userID" placeholder="User ID" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="create_account.php">Create one</a></p>
    </div>
</body>
</html>
