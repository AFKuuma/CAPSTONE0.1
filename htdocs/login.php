<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the database connection is established
if (!$conn) {
    die("Database connection error. Please try again later.");
}

// Redirect to the appropriate page if already logged in
if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin.php");
    } else {
        header("Location: home.php");
    }
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
        $stmt = $conn->prepare("SELECT Password, Role FROM User WHERE UserID = ?");
        if ($stmt) {
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['Password'])) {
                    $_SESSION['userID'] = $userID;
                    $_SESSION['role'] = $row['Role']; // Store the user's role in the session

                    // Redirect based on role
                    if ($row['Role'] === 'Admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: home.php");
                    }
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
        }
        .login-container input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .login-container button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .login-container a {
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; } ?>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="userID" placeholder="User ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p><a href="create_account.php">Create an account</a></p>
    </div>
</body>
</html>
