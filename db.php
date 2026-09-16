<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "abaya_atelier";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>