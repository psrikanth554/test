<?php
$servername = "localhost";
$username   = "root";
$password   = "Srikanth@123";
$dbname     = "cars_showroom";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
