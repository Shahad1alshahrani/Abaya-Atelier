<?php
session_start();
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($admin_id) || empty($password)) {

        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all fields.'
        ]);
        exit;
    }

    $sql = "SELECT * FROM Admin 
            WHERE AdminID = '$admin_id' 
            AND Password = '$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_fetch_assoc($result)) {

        $_SESSION['admin_id'] = $admin_id;

        echo json_encode([
            'success' => true
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid admin ID or password.'
        ]);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abaya Atelier | Admin Login</title>
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

<main>

<section class="form-container">

  <h1 class="form-title">Admin Login</h1>

  <p id="messageBox" class="form-subtitle" style="color:#d4a373;"></p>

  <form id="adminLoginForm">

    <label>Admin ID</label>
    <input 
      type="text" 
      name="admin_id" 
      id="admin_id"
      placeholder="Enter Admin ID" 
      required
    >

    <label>Password</label>
    <input 
      type="password" 
      name="password" 
      id="password"
      placeholder="Enter Password" 
      required
    >

    <button type="submit" class="btn">Login</button>

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

document.getElementById('adminLoginForm').addEventListener('submit', function(e) {

    e.preventDefault();

    const admin_id = document.getElementById('admin_id').value;
    const password = document.getElementById('password').value;

    fetch('admin-login.php', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

        body:
            'admin_id=' + encodeURIComponent(admin_id) +
            '&password=' + encodeURIComponent(password)

    })

    .then(response => response.json())

    .then(data => {

        const messageBox = document.getElementById('messageBox');

        if (data.success) {

            window.location.href = '../AdminPages/AdminHome.php';

        } else {

            messageBox.textContent = data.message;
        }

    })

    .catch(error => {

        document.getElementById('messageBox').textContent =
            'Something went wrong.';
    });

});

</script>

</body>
</html>