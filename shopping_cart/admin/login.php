<?php
session_start();
include '../includes/db.php'; // Include your MySQLi connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
        } else {
            echo "<p class='text-danger'>Invalid password.</p>";
        }
    } else {
        echo "<p class='text-danger'>No user found with that username.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }
        h2 {
            color: #6c757d;
        }
        .form-control {
            border-radius: 20px;
        }
        button {
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="text-center">Admin Login</h2>
    <form method="POST">
        <div class="form-group">
            <input type="text" name="username" class="form-control" required placeholder="Username">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" required placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
</div>

</body>
</html>
