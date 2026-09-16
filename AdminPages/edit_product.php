<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../db.php';

if (!isset($_GET['id'])) {
    die("No product ID provided");
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM Product WHERE ProductID = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image = $product['image'];

    if (!empty($_FILES['image']['name'])) {

        $original = basename($_FILES['image']['name']);
        $safeName = preg_replace("/[^a-zA-Z0-9\.\-]/", "_", $original);

        $image = time() . '_' . $safeName;

        $target = __DIR__ . '/../images/' . $image;

        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "UPDATE Product SET 
            name='$name',
            price=$price,
            stock=$stock,
            size='$size',
            color='$color',
            description='$description',
            image='$image'
            WHERE ProductID = $id";

    if (mysqli_query($conn, $sql)) {
        $message = "Product updated successfully.";
        $result = mysqli_query($conn, "SELECT * FROM Product WHERE ProductID = $id");
        $product = mysqli_fetch_assoc($result);
    } else {
        die("SQL ERROR: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/style.css">

<style>
.edit-box {
    max-width: 650px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.edit-box h2 {
    text-align: center;
    margin-bottom: 20px;
}

.edit-box label {
    display: block;
    margin-top: 12px;
    font-weight: bold;
}

.edit-box input,
.edit-box textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.edit-box button {
    width: 100%;
    margin-top: 20px;
    padding: 12px;
    background: black;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.edit-box button:hover {
    background: #333;
}

.edit-box img {
    width: 120px;
    margin-top: 10px;
    border-radius: 8px;
}
</style>

</head>

<body>

<header>
<div class="container header-container">
<img src="../images/logo.png" class="logo" alt="Logo">

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

<section class="edit-box">

<h2>Edit Product</h2>

<?php if ($message): ?>
<p style="text-align:center; color:green;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Name</label>
<input type="text" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>

<label>Price</label>
<input type="number" name="price" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required>

<label>Stock</label>
<input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock'] ?? ''); ?>" required>

<label>Size</label>
<input type="text" name="size" value="<?php echo htmlspecialchars($product['size'] ?? ''); ?>">

<label>Color</label>
<input type="text" name="color" value="<?php echo htmlspecialchars($product['color'] ?? ''); ?>">

<label>Description</label>
<textarea name="description"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>

<label>Current Image</label><br>

<?php if (!empty($product['image'])): ?>
<img src="../images/<?php echo $product['image']; ?>">
<?php endif; ?>

<label>Change Image</label>
<input type="file" name="image">

<button type="submit">Update Product</button>

</form>

</section>

</body>
</html>