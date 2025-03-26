<?php
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_account'])) {
    $userID   = htmlspecialchars(trim($_POST['userID']));
    $name     = htmlspecialchars(trim($_POST['name']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $phone    = htmlspecialchars(trim($_POST['phone']));
    $role     = 0; // Default role as Customer

    // Validate input
    if (empty($userID) || empty($name) || empty($email) || empty($password) || empty($phone)) {
        $error = "All fields are required. Please provide complete information.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO User (UserID, Name, Email, Password, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssi", $userID, $name, $email, $hashedPassword, $phone, $role);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id; // Set session user ID
                header("Location: home.php"); // Redirect to home page
                exit();
            } else {
                $error = "Failed to create account. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Database query failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="form_styles.css"> <!-- New CSS file for styling -->
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
        <div class="form-group">
            <p>Already have an account? <a href="index.php">Go to login</a>.</p>
        </div>
    </div>
</body>
</html>
