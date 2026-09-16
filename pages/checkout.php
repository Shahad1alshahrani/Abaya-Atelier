
<?php

session_start();
include '../db.php';

$items = $_SESSION['cart'] ?? [];
$message = '';
$error = '';

if (empty($items)) {
    header('Location: cart.php');
    exit;
}

$subtotal = 0;

foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$vat = $subtotal * 0.15;
$final = $subtotal + $vat;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $building = trim($_POST['building'] ?? '');
    $apartment = trim($_POST['apartment'] ?? '');
    $payment = trim($_POST['payment'] ?? '');

    if ($full_name === '' || $phone === '' || $city === '' || $street === '' || $building === '' || $payment === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!preg_match('/^05[0-9]{8}$/', $phone)) {
        $error = 'Please enter a valid Saudi phone number starting with 05.';
    } else {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $phone = mysqli_real_escape_string($conn, $phone);
        $city = mysqli_real_escape_string($conn, $city);
        $street = mysqli_real_escape_string($conn, $street);
        $building = mysqli_real_escape_string($conn, $building);
        $apartment = mysqli_real_escape_string($conn, $apartment);
        $payment = mysqli_real_escape_string($conn, $payment);

        $address = trim($street . ', Building ' . $building . ($apartment ? ', Apt ' . $apartment : ''));


        $customer_id = isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 1;

        $total = $final;

        $orderSql = "INSERT INTO Orders 
            (CustomerID, TotalPrice, Status, Address, City, PaymentMethod) 
            VALUES 
            ($customer_id, $total, 'Processing', '$address', '$city', '$payment')";

        if (mysqli_query($conn, $orderSql)) {
            $order_id = mysqli_insert_id($conn);

            $all_items_saved = true;

            foreach ($items as $item) {
                $pid = (int)$item['product_id'];
                $qty = (int)$item['quantity'];
                $price = (float)$item['price'];

                $stockCheck = mysqli_query($conn, "SELECT Stock FROM Product WHERE ProductID = $pid");
                $product = mysqli_fetch_assoc($stockCheck);

                if (!$product || $product['Stock'] < $qty) {
                    $all_items_saved = false;
                    $error = 'One of the products is out of stock.';
                    break;
                }

                $insertItem = mysqli_query($conn, "INSERT INTO OrderItem 
                    (OrderID, ProductID, Quantity, UnitPrice) 
                    VALUES 
                    ($order_id, $pid, $qty, $price)");

                $updateStock = mysqli_query($conn, "UPDATE Product 
                    SET Stock = Stock - $qty 
                    WHERE ProductID = $pid");

                if (!$insertItem || !$updateStock) {
                    $all_items_saved = false;
                    $error = 'Could not save order items.';
                    break;
                }
            }

            if ($all_items_saved) {
                $_SESSION['cart'] = [];
                $message = 'Order placed successfully for ' . htmlspecialchars($full_name) . '.';
            }
        } else {
            $error = 'Order could not be placed: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abaya Atelier | Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="register-page">
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
<section class="checkout-page-section">
    <div class="container">
        <div class="checkout-box">
            <h1 class="checkout-title">Checkout</h1>
            <?php if ($message): ?>
    <p class="form-subtitle" style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="form-subtitle" style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
            <h2>Shipping Address</h2>
            <?php if (!$message): ?>
            <form action="checkout.php" method="POST">
                <label>Full Name:</label>
                <input type="text" name="full_name" required placeholder="Enter your full name">
                <label>Phone Number:</label>
                <input type="tel" name="phone" required placeholder="05xxxxxxxx">
                <label>City:</label>
                <select name="city" required>
                    <option value="">Select City</option>
                    <option value="Riyadh">Riyadh</option>
                    <option value="Jeddah">Jeddah</option>
                    <option value="Dammam">Dammam</option>
                    <option value="Mecca">Mecca</option>
                    <option value="Medina">Medina</option>
                    <option value="Al Khobar">Al Khobar</option>
                    <option value="Tabuk">Tabuk</option>
                    <option value="Taif">Taif</option>
                    <option value="Abha">Abha</option>
                    <option value="Buraydah">Buraydah</option>
                    <option value="Al Hasa">Al Hasa</option>
                </select>
                <label>Full Address:</label>
                <input type="text" name="street" required placeholder="Street">
                <input type="text" name="building" required placeholder="Building number">
                <input type="text" name="apartment" placeholder="Apartment number">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    <label class="payment-option"><input type="radio" name="payment" value="Apple Pay" required><img src="../images/friendPay.jpg" alt="Apple Pay"></label>
                    <label class="payment-option"><input type="radio" name="payment" value="Tabby"><img src="../images/friendPay.jpg" alt="Tabby"></label>
                    <label class="payment-option"><input type="radio" name="payment" value="Tamara"><img src="../images/friendPay.jpg" alt="Tamara"></label>
                    <label class="payment-option"><input type="radio" name="payment" value="Cash"><img src="../images/friendPay.jpg" alt="Cash"></label>
                </div>
                <label>Card Number:</label>
               <input type="text" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX">
                <label>Expiry Date:</label>
                <input type="text" name="expiry" placeholder="MM / YY">
                <label>CVV:</label>
                <input type="text" name="cvv" placeholder="XXX">
                <h2>Order Summary</h2>
<p>Subtotal: <?php echo number_format($subtotal, 2); ?> SAR</p>
<p>VAT 15%: <?php echo number_format($vat, 2); ?> SAR</p>
<p><strong>Total: <?php echo number_format($final, 2); ?> SAR</strong></p>
                <button type="submit" class="btn">Place Order</button>
            </form>
            <?php else: ?>
    <div style="text-align: center; margin-top: 20px;">
        <h2>Thank you for your order!</h2>
        <p>Your order has been placed successfully.</p>
        <a href="products.php" class="btn">Continue Shopping</a>
    </div>
<?php endif; ?>
        </div>
    </div>
</section>
<footer><div class="container footer-container"><p>&copy; 2026 Abaya Atelier. All rights reserved.</p><p>Email: info@abayastore.com | Phone: +966 533458857</p></div></footer>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const phone = document.querySelector("input[name='phone']").value.trim();
    const payment = document.querySelector("input[name='payment']:checked");

    if (!/^05[0-9]{8}$/.test(phone)) {
        e.preventDefault();
        alert("Please enter a valid Saudi phone number starting with 05.");
        return;
    }

    if (!payment) {
        e.preventDefault();
        alert("Please select a payment method.");
        return;
    }
});
</script>
</body>
</html>
