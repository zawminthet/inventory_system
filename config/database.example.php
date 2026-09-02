<?php

$host     = "localhost";
$dbname   = "inventory_system";
$username = "root";
$password = "";

try {

    $conn = new mysqli($host, $username, $password, $dbname);

    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    die("Database connection failed.");
}