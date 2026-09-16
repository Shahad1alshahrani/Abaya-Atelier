
<?php
session_start();
include '../db.php';
$sql = "SELECT o.OrderID, c.Name AS CustomerName, oi.Quantity, p.Name AS ProductName, (oi.Quantity * oi.UnitPrice) AS TotalPrice, o.Status FROM Orders o JOIN Customer c ON o.CustomerID = c.CustomerID JOIN OrderItem oi ON o.OrderID = oi.OrderID JOIN Product p ON oi.ProductID = p.ProductID ORDER BY o.OrderID DESC";
$orders = mysqli_query($conn, $sql);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Abaya Atelier | Orders</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="../css/style.css"></head>
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
<main class="container"><div class="form-box"><h1 class="page-title">Customer Orders</h1><p class="page-description">Below is a list of all customer orders placed on the Abaya Atelier website.</p><section class="orders-table"><table><thead><tr><th>Order ID</th><th>Customer Name</th><th>Product</th><th>Quantity</th><th>Total Price</th><th>Status</th></tr></thead><tbody><?php while($row = mysqli_fetch_assoc($orders)): ?><tr><td>#<?php echo $row['OrderID']; ?></td><td><?php echo htmlspecialchars($row['CustomerName']); ?></td><td><?php echo htmlspecialchars($row['ProductName']); ?></td><td><?php echo (int)$row['Quantity']; ?></td><td><?php echo number_format($row['TotalPrice'], 2); ?> SAR</td><td class="status processing"><?php echo htmlspecialchars($row['Status']); ?></td></tr><?php endwhile; ?></tbody></table></section></div></main>
<footer><div class="container footer-container"><p>&copy; 2026 Abaya Atelier. All rights reserved.</p><p>Email: info@abayastore.com | Phone: +966 533458857</p></div></footer>
</body></html>
