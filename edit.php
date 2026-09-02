<?php

require_once "auth.php";
require_once "config/database.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    die("Product ID is missing.");
}

$stmt = $conn->prepare("SELECT id, product_name, category_id, price, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$categories = $conn->query("SELECT id, category_name FROM categories ORDER BY category_name")
                   ->fetch_all(MYSQLI_ASSOC);

$errors      = [];
$productName = $product["product_name"];
$categoryId  = (string) $product["category_id"];
$price       = (int) $product["price"];
$stock       = $product["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productName = trim($_POST["product_name"] ?? "");
    $categoryId  = trim($_POST["category_id"] ?? "");
    $price       = trim($_POST["price"] ?? "");
    $stock       = trim($_POST["stock"] ?? "");

    if ($productName === "") {
        $errors[] = "Product name is required.";
    } elseif (mb_strlen($productName) > 150) {
        $errors[] = "Product name must be 150 characters or less.";
    }

    if ($categoryId === "") {
        $errors[] = "Please select a category.";
    } else {
        $found = false;
        foreach ($categories as $category) {
            if ((string) $category["id"] === $categoryId) {
                $found = true;
            }
        }
        if (!$found) {
            $errors[] = "Selected category does not exist.";
        }
    }

    if ($price === "") {
        $errors[] = "Price is required.";
    } elseif (!ctype_digit($price)) {
        $errors[] = "Price must be a whole number.";
    }

    if ($stock === "") {
        $errors[] = "Stock is required.";
    } elseif (!ctype_digit($stock)) {
        $errors[] = "Stock must be a whole number.";
    }

    if (count($errors) === 0) {

        $sql = "
            UPDATE products
            SET product_name = ?, category_id = ?, price = ?, stock = ?
            WHERE id = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidii", $productName, $categoryId, $price, $stock, $id);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Edit Product</title>
</head>
<body>

<h1>Edit Product</h1>

<?php if (count($errors) > 0) { ?>
    <ul class="errors">        
        <?php foreach ($errors as $error) { ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php } ?>
    </ul>
<?php } ?>

<form method="POST" action="edit.php?id=<?= htmlspecialchars($id) ?>">

    <p>
        <label>Product Name</label><br>
        <input type="text" name="product_name"
               value="<?= htmlspecialchars($productName) ?>">
    </p>

    <p>
        <label>Category</label><br>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?= htmlspecialchars($category["id"]) ?>"
                    <?= ($categoryId === (string) $category["id"]) ? "selected" : "" ?>>
                    <?= htmlspecialchars($category["category_name"]) ?>
                </option>
            <?php } ?>
        </select>
    </p>

    <p>
        <label>Price</label><br>
        <input type="number" name="price" step="1" min="0"
               value="<?= htmlspecialchars($price) ?>">
    </p>

    <p>
        <label>Stock</label><br>
        <input type="number" name="stock" min="0"
               value="<?= htmlspecialchars($stock) ?>">
    </p>

    <button type="submit">Update Product</button>

</form>

<p><a href="index.php">Back to Product List</a></p>

</body>
</html>