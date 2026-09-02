<?php

require_once "auth.php";
require_once "config/database.php";

$sql = "
    SELECT
        products.id,
        products.product_name,
        categories.category_name,
        products.price,
        products.stock
    FROM products
    LEFT JOIN categories
        ON products.category_id = categories.id
    ORDER BY products.id DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> 
    <title>Product List</title>
</head>
<body>
<p class="topbar"><span>Hello, <strong><?= htmlspecialchars($currentUserName) ?></strong> | <a href="logout.php">Logout</a></span></p>
<h1>Product List</h1>
<p><a href="create.php" class="btn">+ Add Product</a></p>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Action</th>
    </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row["id"]) ?></td>
            <td><?= htmlspecialchars($row["product_name"]) ?></td>
            <td><?= htmlspecialchars($row["category_name"] ?? "-") ?></td>
            <td><?= number_format($row["price"]) ?></td>
            <td><?= htmlspecialchars($row["stock"]) ?></td>
            <td>
                <a href="edit.php?id=<?= htmlspecialchars($row["id"]) ?>">Edit</a>

                <form method="POST" action="delete.php" style="display:inline"
                      onsubmit="return confirm('Delete this product?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row["id"]) ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>

</table>

</body>
</html>