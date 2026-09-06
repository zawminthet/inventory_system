<?php

session_start();

require_once "config/database.php";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $error = "Please enter your email and password.";
    } else {

        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();


        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"]   = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: index.php");
            exit;
        } else {

            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Login</title>
</head>

<body>

    <h1>Inventory Login</h1>

    <?php if ($error !== "") { ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php } ?>

    <form method="POST">
        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        </p>
        <p>
            <label>Password</label><br>
            <input type="password" name="password">
        </p>
        <button type="submit">Login</button>
    </form>

</body>

</html>