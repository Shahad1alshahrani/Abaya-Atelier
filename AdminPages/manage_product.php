<?php
session_start();
include '../db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query($conn, "DELETE FROM product WHERE ProductID=$id");

    header('Location: manage_product.php');
    exit;
}

$products = mysqli_query($conn, "SELECT * FROM product ORDER BY ProductID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Abaya Atelier | Manage Products</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/style.css">
</head>

<body>

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

<main>

<section class="about-hero-simple">
<div class="container">
<h1>Manage Products</h1>
<p>Add, Modify or Delete Products</p>
<a href="add-product.php" class="btn">+ Add New Product</a>
</div>
</section>

<section class="about">
<div class="container">

<div class="about-grid-simple">

<?php while($row = mysqli_fetch_assoc($products)): ?>

<div class="about-card-simple">

<img src="../images/<?php echo htmlspecialchars($row['Image']); ?>" 
style="width:100%; border-radius:12px;" 
alt="<?php echo htmlspecialchars($row['Name']); ?>">

<h3><?php echo htmlspecialchars($row['Name']); ?></h3>

<p><?php echo number_format($row['Price'], 2); ?> SAR</p>

<p>Stock: <?php echo (int)$row['Stock']; ?></p>

<a class="btn" href="edit_product.php?id=<?php echo $row['ProductID']; ?>">
    Modify
</a>

<a class="btn" href="manage_product.php?delete=<?php echo $row['ProductID']; ?>" 
onclick="return confirm('Delete this product?');">
Delete
</a>

</div>

<?php endwhile; ?>

</div>
</div>
</section>

</main>

<footer>
<div class="container footer-container">
<p>&copy; 2026 Abaya Atelier. All rights reserved.</p>
<p>Email: info@abayastore.com | Phone: +966 533458857</p>
</div>
</footer>

</body>
</html>