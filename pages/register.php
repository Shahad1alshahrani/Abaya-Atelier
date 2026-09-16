<?php
session_start();
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    // Check if email already exists
    $checkEmail = "SELECT Email FROM Customer WHERE Email = '$email'";
    $result = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($result) > 0) {
        $message = 'Email is already registered!';
    } else {
        $sql = "INSERT INTO Customer (Name, Email, Password, PhoneNumber, DateOfBirth)
                VALUES ('$name', '$email', '$password', '$phone', '$dob')";

        if (mysqli_query($conn, $sql)) {
            $message = 'Registered successfully. You can now log in.';
        } else {
            $message = 'Error: ' . mysqli_error($conn);
        }
    }
}

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Abaya Atelier</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="register-page">

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

<section class="form-container">
    <h2 class="register-title">Create New Account</h2>

    <?php if ($message): ?>
        <p class="form-subtitle"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name" required autocomplete="name">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required autocomplete="email">

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>Phone Number</label>
        <input type="tel" name="phone" placeholder="05xxxxxxxx" pattern="05[0-9]{8}" required>

        <label>Date of Birth</label>
        <input type="date" name="dob" required>

        <button type="submit">Register</button>
    </form>
</section>

<footer>
  <div class="container footer-container">
    <p>&copy; 2026 Abaya Atelier. All rights reserved.</p>
    <p>Email: info@abayastore.com | Phone: +966 533458857</p>
  </div>
</footer>

</body>
</html>