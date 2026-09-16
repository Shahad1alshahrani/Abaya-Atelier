
<?php
session_start();
include '../db.php';
$product_count = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM Product"))[0] ?? 0;
$orders_today = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM Orders WHERE DATE(OrderDate)=CURDATE()"))[0] ?? 0;
$pending_orders = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM Orders WHERE Status='Processing'"))[0] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Admin Dashboard</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="../css/style.css"></head>
<body class="admin-dashboard">
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
<section class="container dashboard">
<h1>Admin Dashboard</h1>
<p>Welcome Admin! Manage your store from here.</p>
<div class="dashboard-stats">
<div class="stat-card"><h3><?php echo $product_count; ?></h3><p>Total Products</p></div>
<div class="stat-card"><h3><?php echo $orders_today; ?></h3><p>Orders Today</p></div>
<div class="stat-card"><h3><?php echo $pending_orders; ?></h3><p>Pending Orders</p></div>
</div>
<div class="dashboard-cards">
<a href="mange.proudct.php" class="admin-card"><h3>Manage Products</h3><p>Edit or remove products from the store.</p></a>
<a href="add-product.php" class="admin-card"><h3>Add New Product</h3><p>Add a new abaya to the collection.</p></a>
<a href="Orders.php" class="admin-card"><h3>Customer Orders</h3><p>View and manage customer orders.</p></a>
</div>
</section>
<footer><div class="container footer-container"><p>&copy; 2026 Abaya Atelier. All rights reserved.</p><p>Email: info@abayastore.com | Phone: +966 533458857</p></div></footer>
</body></html>
