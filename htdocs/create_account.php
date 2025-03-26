<?php
session_start();
include 'db.php'; // Include the database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_account'])) {
    $userID   = htmlspecialchars(trim($_POST['userID']));
    $name     = htmlspecialchars(trim($_POST['name']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $phone    = htmlspecialchars(trim($_POST['phone']));
    $role     = 'Customer'; // Default role is Customer

    // Validate input
    if (empty($userID) || empty($name) || empty($email) || empty($password) || empty($phone)) {
        $error = "All fields are required. Please provide complete information.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO User (UserID, Name, Email, Password, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssss", $userID, $name, $email, $hashedPassword, $phone, $role);
            try {
                $stmt->execute();
                $_SESSION['userID'] = $userID; // Set session user ID
                $_SESSION['role'] = $role; // Set session role
                header("Location: home.php"); // Redirect to home page
                exit();
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1062) { // Duplicate entry error
                    $error = "User ID or email already exists. Please choose a different one.";
                } else {
                    $error = "Failed to create account. Please try again.";
                }
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
    <title>Create Account</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="form_styles.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="userID" placeholder="User ID" required>
            </div>
            <div class="form-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>
            <button type="submit" name="create_account">Create Account</button>
        </form>
        <p>Already have an account? <a href="login.php">Go to login</a>.</p>
    </div>
</body>
</html>
