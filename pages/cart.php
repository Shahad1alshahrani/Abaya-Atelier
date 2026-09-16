
<?php
session_start();
include '../db.php';

$error = "";
$message = "";

if (isset($_POST['remove_item'])) {
    $remove = (int)$_POST['remove_item'];

    if (isset($_SESSION['cart'][$remove])) {
        unset($_SESSION['cart'][$remove]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        $message = "Item removed from cart successfully.";
    }
}

if (isset($_POST['update_qty'])) {
    foreach ($_SESSION['cart'] as $i => $item) {
        $qty = (int)$_POST['qty'][$i];

        if ($qty < 1) {
            $error = "Quantity must be at least 1.";
            $qty = 1;
        }

        $product_id = $item['product_id'] ?? $item['ProductID'] ?? $item['id'] ?? null;

        if ($product_id) {
            $product_id = (int)$product_id;

            $stockQuery = mysqli_query($conn, "SELECT Stock FROM Product WHERE ProductID = $product_id");
            $product = mysqli_fetch_assoc($stockQuery);

            if ($product && $qty > $product['Stock']) {
                $error = "Only " . $product['Stock'] . " item(s) are available in stock.";
                $qty = (int)$product['Stock'];
            }
        }

        $_SESSION['cart'][$i]['quantity'] = $qty;
    }

    if ($error === "") {
        $message = "Cart updated successfully.";
    }
}
$items = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$vat = $subtotal * 0.15;
$final = $subtotal + $vat;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abaya Atelier | Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="cart-page">
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
<main>
  <section class="container cart-page-section">
    <h1>Shopping Cart</h1>
    <p class="cart-subtitle">Here are the items you selected.</p>
    <?php if ($message): ?>
  <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($error): ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
    <div class="cart-wrap">
      <div class="cart-left">
        <form method="POST" action="cart.php">
        <table class="cart-table">
          <thead>
            <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
          </thead>
          <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $index => $item): $item_total = $item['price'] * $item['quantity']; ?>
            <tr>
              <td>
  <div class="cart-product">
    <img class="cart-thumb" src="../images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
    <span><?php echo htmlspecialchars($item['name']); ?></span>
    <br>
    <button 
      type="submit" 
      name="remove_item" 
      value="<?php echo $index; ?>" 
      style="background:none; border:none; color:#6b4f4f; text-decoration:underline; cursor:pointer;">
      Remove
    </button>
  </div>
</td>
              <td><input class="qty" type="number" name="qty[<?php echo $index; ?>]" value="<?php echo (int)$item['quantity']; ?>" min="1"></td>
              <td><?php echo number_format($item['price'], 2); ?> SAR</td>
              <td><?php echo number_format($item_total, 2); ?> SAR</td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4">Your cart is empty.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
        <?php if (!empty($items)): ?><button class="btn" type="submit" name="update_qty">Update Cart</button><?php endif; ?>
        </form>
      </div>
      <aside class="cart-right">
        <div class="summary-box">
          <h2>Order Summary</h2>
          <div class="summary-row"><span>Items total</span><span><?php echo number_format($subtotal, 2); ?> SAR</span></div>
          <div class="summary-row"><span>VAT (15%)</span><span><?php echo number_format($vat, 2); ?> SAR</span></div>
          <hr>
          <div class="summary-row total"><span>Final total</span><span><?php echo number_format($final, 2); ?> SAR</span></div>
          <?php if (!empty($items)): ?>
  <a href="checkout.php" class="btn summary-btn">Proceed to Checkout</a>
<?php else: ?>
  <p style="color: red;">Your cart is empty. Add products before checkout.</p>
<?php endif; ?>
          <p class="note">* VAT Inclusive</p>
        </div>
      </aside>
    </div>
  </section>
</main>
<footer><div class="container footer-container"><p>&copy; 2026 Abaya Atelier. All rights reserved.</p><p>Email: info@abayastore.com | Phone: +966 533458857</p></div></footer>
<script>
document.querySelectorAll(".qty").forEach(function(input) {
    input.addEventListener("input", function() {
        if (parseInt(this.value) < 1 || this.value === "") {
            this.value = 1;
            alert("Quantity must be at least 1.");
        }
    });
});
</script>
</body>
</html>
