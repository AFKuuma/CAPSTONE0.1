<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {
    $userID   = $_POST['userID'];
    $password = $_POST['password'];

    if ($userID === 'janblaire') {
        // Allow login without password for 'janblaire'
        $_SESSION['userID'] = $userID;
        header('Location: home.php');
        exit();
    }

    // Validate user credentials using prepared statements
    $stmt = $conn->prepare("SELECT Password FROM User WHERE UserID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            // Credentials are correct
            $_SESSION['userID'] = $userID;
            header('Location: home.php');
            exit();
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        $error = "Invalid credentials. Please try again.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Ticket Booking - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container table {
            width: 100%;
            margin-bottom: 20px;
        }
        .login-container table td {
            padding: 10px 0;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        
        <form method="post" action="">
            <table>
                <tr>
                    <td><input type="text" name="userID" placeholder="User ID" required></td>
                </tr>
                <tr>
                    <td><input type="password" name="password" placeholder="Password" required></td>
                </tr>
            </table>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>
