<?php
session_start();
include '../db.php';

$message = '';


if (!$conn) {
    die("DB CONNECTION FAILED: check db.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

  
    $image = '';

    if (!empty($_FILES['image']['name'])) {

        $image = time() . '_' . basename($_FILES['image']['name']);
        $target = '../images/' . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("IMAGE UPLOAD FAILED");
        }
    }


    $sql = "INSERT INTO Product (Name, Price, Stock, Color, Description, Image, Size)
            VALUES ('$name', $price, $stock, '$color', '$description', '$image', '$size')";

    if (mysqli_query($conn, $sql)) {
        $message = 'Product added successfully.';
    } else {
        die("SQL ERROR: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Abaya Atelier | Add Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/style.css">
</head>

<body class="register-page">

<header>
<div class="container header-container">
<img src="../images/logo.png" class="logo" alt="Abaya Atelier Logo">

<nav>
<ul class="nav-links">
<li><a href="AdminHome.php">Home</a></li>
<li><a href="manage_product.php">Manage Products</a></li>
<li><a href="add-product.php">Add Product</a></li>
<li><a href="Orders.php">Orders</a></li>
<li><a href="../index.php">Logout</a></li>
</ul>
</nav>
</div>
</header>

<section class="form-container">

<h2 class="form-title">Add New Product</h2>

<?php if ($message): ?>
<p class="form-subtitle"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Product Name</label>
<input type="text" name="name" required>

<label>Price</label>
<input type="number" name="price" required>

<label>Stock</label>
<input type="number" name="stock" required>

<label>Size</label>
<input type="text" name="size" required>

<label>Color</label>
<input type="text" name="color" required>

<label>Description</label>
<textarea name="description" required></textarea>

<label>Product Image</label>
<input type="file" name="image" accept="image/*" required>

<button type="submit">Add Product</button>

</form>

</section>

</body>
</html>