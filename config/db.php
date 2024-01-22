<?php
global $conn;

$database = "bdc_test";
$host = "localhost";
$username = "root";
$password = "";

try {
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // echo "Connected";

} catch (\Throwable $error) {
    die("Catch Error: " . $error);
}