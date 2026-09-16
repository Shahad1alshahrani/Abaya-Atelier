<?php
session_start();
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM Customer WHERE Email='$email' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($user = mysqli_fetch_assoc($result)) {

        if(isset($_POST['remember'])) {

            setcookie("user_email", $email, time() + (86400 * 30), "/");

        }

        $_SESSION['customer_id'] = $user['CustomerID'];
        $_SESSION['customer_name'] = $user['Name'];

        header('Location: products.php');
        exit;

    } else {

        $message = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Login</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

.password-container {
    position: relative;
}

.password-container input {
    width: 100%;
    padding-right: 40px;
}

.password-container i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: gray;
}

</style>

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

<main>

<section class="form-container">

  <h1 class="form-title">User Login</h1>

  <p class="form-subtitle">
    Sign in to your Abaya Atelier account to continue shopping.
  </p>

  <?php if ($message): ?>

    <p class="form-subtitle">
      <?php echo htmlspecialchars($message); ?>
    </p>

  <?php endif; ?>

<form action="login.php" method="POST" onsubmit="return validateForm()">

<label for="email">Email</label>

<input
type="email"
id="email"
name="email"
placeholder="Enter your email"
value="<?php echo isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : ''; ?>"
required>

<label for="password">Password</label>

<div class="password-container">

<input
type="password"
id="password"
name="password"
placeholder="Enter your password"
required>

<i class="fa-solid fa-eye"
onclick="togglePassword()"
style="cursor:pointer;"></i>

</div>

<label class="remember-me">

<input type="checkbox" name="remember">

Remember Me

</label>

<div class="login-buttons">

<button type="submit" class="btn">
Login
</button>

<a href="admin-login.php" class="btn btn-alt">
As Admin
</a>

</div>

<p class="form-footer">

Don't have an account?

<a href="register.php">Register here</a>

</p>

</form>

</section>

</main>

<footer>

<div class="container footer-container">

<p>&copy; 2026 Abaya Atelier. All rights reserved.</p>

<p>Email: info@abayastore.com | Phone: +966 533458857</p>

</div>

</footer>

<script>

function validateForm() {

   let email = document.getElementById("email").value;

   let password = document.getElementById("password").value;

   if(email === "" || password === "") {

      alert("Please fill all fields");

      return false;
   }

   let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

   if(!emailPattern.test(email)) {

      alert("Please enter a valid email address");

      return false;
   }

   if(password.length < 8) {

      alert("Password must be at least 8 characters");

      return false;
   }

   if(!/[A-Z]/.test(password)) {

      alert("Password must contain at least one capital letter");

      return false;
   }

   if(!/[0-9]/.test(password)) {

      alert("Password must contain at least one number");

      return false;
   }

   return true;
}

function togglePassword() {

   let passwordField = document.getElementById("password");

   if(passwordField.type === "password") {

      passwordField.type = "text";

   } else {

      passwordField.type = "password";
   }
}

</script>

</body>
</html>
