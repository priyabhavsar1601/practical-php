<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f4f8;
        }
        header {
            background-color: #6c757d;
        }
        footer {
            background-color: #343a40;
        }
        .navbar {
            background-color: #e9ecef;
        }
        .navbar-nav .nav-link {
            color: #495057;
        }
        .navbar-nav .nav-link:hover {
            color: #007bff;
        }
        .container h2 {
            color: #343a40;
        }
        .container p {
            color: #6c757d;
        }
    </style>
</head>
<body>

<header class="text-white text-center p-4">
    <h1>Admin Dashboard</h1>
</header>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">Shopping Cart Admin</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="manage_categories.php">Manage Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_products.php">Manage Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Welcome to the Admin Dashboard</h2>
    <p>Select an option from the navigation above to manage your shopping cart.</p>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
