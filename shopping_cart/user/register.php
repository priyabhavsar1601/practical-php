<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['captcha'] != $_SESSION['captcha']) {
        echo "<b><p class='text-danger text-center'>CAPTCHA is incorrect. Please try again.</p></b>";
    } else {
        $name = $_POST['name'];
        $password = $_POST['password'];

        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss",$name, $password);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .registration-container {
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

<div class="registration-container">
    <h2 class="text-center">User Registration</h2>
    <form method="POST">
        <div class="form-group">
            <input type="text" name="name" class="form-control" required placeholder="Username">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" required placeholder="Password">
        </div>
        
        <!-- CAPTCHA Image -->
        <div class="form-group text-center">
            <img src="captcha.php?rand=<?php echo rand(); ?>" alt="CAPTCHA Image" style="margin-bottom: 10px;">
        </div>
        <div class="form-group">
            <input type="text" name="captcha" class="form-control" required placeholder="Enter CAPTCHA">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
</div>

</body>
</html>
