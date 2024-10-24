<?php
$host = 'localhost';
$db = 'shopping_cart';
$user = 'root';
$pass = 'root@123';
$port = 4306;


$mysqli = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>