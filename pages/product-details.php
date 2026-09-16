
<?php
session_start();
include '../db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM Product WHERE ProductID=$id");
    $product = mysqli_fetch_assoc($result);
}
if (!$product) {
    die('Product not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abaya Atelier | Product Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
<header>
  <div class="container header-container">
    <img class="logo" src="../images/logo.png" alt="Abaya Atelier Logo">
    <nav>
      <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="cart.php">Cart (<?php echo $cart_count; ?>)</a></li>
        <li><a href="help.php">Help</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="featured">
<div class="container">
<h1>Product Details</h1>
<section class="product-card">
<h2><?php echo htmlspecialchars($product['Name']); ?></h2>
<img src="../images/<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>" style="width:200px !important; height:auto;">
<p><?php echo number_format($product['Price'], 2); ?> SAR</p>
<div style="margin: 15px 0;">
    <label>View in:</label>
    <select id="currencySelector" onchange="convertCurrency()">
        <option value="SAR">Saudi Riyal (SAR)</option>
        <option value="BHD">Bahraini Dinar (BHD)</option>
    </select>
    <p id="convertedPrice" style="font-weight: bold; color: #2ecc71;"></p>
</div>
<p><?php echo htmlspecialchars($product['Description']); ?></p>
<p><strong>Color:</strong> <?php echo htmlspecialchars($product['Color']); ?></p>
<p><strong>Size:</strong> <?php echo htmlspecialchars($product['Size']); ?></p>
<p><strong>Stock:</strong> <?php echo (int)$product['Stock']; ?></p>
<form action="add_to_cart.php" method="POST">
<input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
<label>Quantity</label>
<input type="number" name="quantity" value="1" min="1" max="<?php echo max(1, (int)$product['Stock']); ?>" required>
<button class="btn" type="submit">Add to Cart</button>
</form>
<a class="btn" href="products.php">Back to Products</a>
</section>
</div>
</main>
<footer><div class="container footer-container"><p>&copy; 2026 Abaya Atelier. All rights reserved.</p><p>Email: info@abayastore.com | Phone: +966 533458857</p></div></footer>
<script>
async function convertCurrency() {
    const sarPrice = <?php echo $product['Price']; ?>; 
    const currency = document.getElementById('currencySelector').value;
    const display = document.getElementById('convertedPrice');
    if (currency === 'SAR') { display.innerText = ""; return; }
    try {
        const response = await fetch('https://api.exchangerate-api.com/v4/latest/SAR');
        const data = await response.json();
        const rate = data.rates[currency];
        display.innerText = `Approx: ${(sarPrice * rate).toFixed(3)} ${currency}`;
    } catch (e) { display.innerText = "Error."; }
}
</script>
</body>
</html>
