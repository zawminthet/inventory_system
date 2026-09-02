<?php

require_once "auth.php";
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = (int) ($_POST["id"] ?? 0);

if ($id <= 0) {
    die("Product ID is missing.");
}

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;