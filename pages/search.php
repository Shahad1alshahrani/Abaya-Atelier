<?php
session_start();
include '../db.php';

$q_raw = isset($_GET['q']) ? trim($_GET['q']) : '';
$q = mysqli_real_escape_string($conn, $q_raw);

$error = "";
$result = false;

if (isset($_GET['q'])) {
    if ($q_raw === '') {
        $error = "Please enter a search keyword.";
    } else {
        if (is_numeric($q_raw)) {
            $product_id = (int)$q_raw;
            $sql = "SELECT * FROM Product 
                    WHERE ProductID = $product_id 
                    OR Name LIKE '%$q%'";
        } else {
            $sql = "SELECT * FROM Product 
                    WHERE Name LIKE '%$q%'";
        }

        $result = mysqli_query($conn, $sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Abaya Atelier | Search</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body class="admin-page">

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

<section class="container admin-section">

  <h1>Search Product</h1>
  <p class="admin-subtitle">Search for a product to purchase.</p>

  <form class="search-form" action="search.php" method="get">
    <label for="q">Search</label>
    <input type="search" id="q" name="q"
           placeholder="Enter product name or ID"
           value="<?php echo htmlspecialchars($q); ?>" required>
    <button type="submit" class="btn">Search</button>
  </form>
<?php if ($error): ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
  <h2 class="results-title">Results</h2>

  <table class="results-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
      </tr>
    </thead>

   <tbody>

<?php if ($result && mysqli_num_rows($result) > 0): ?>

  <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td>
        <div class="result-product">
          <img class="result-thumb"
               src="../images/<?php echo htmlspecialchars($row['Image']); ?>"
               alt="<?php echo htmlspecialchars($row['Name']); ?>">
          <span><?php echo htmlspecialchars($row['Name']); ?></span>
        </div>
      </td>

      <td><?php echo number_format($row['Price'], 2); ?> SAR</td>
      <td><?php echo (int)$row['Stock']; ?></td>

      <td>
        <form action="add_to_cart.php" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $row['ProductID']; ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="btn">Add to cart</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>

<?php elseif (isset($_GET['q']) && $q_raw !== ''): ?>

  <tr>
    <td colspan="4" style="color: red;">No products found matching your search.</td>
  </tr>

<?php else: ?>

  <tr>
    <td colspan="4">Please enter a product name or ID to search.</td>
  </tr>

<?php endif; ?>

</tbody>
  </table>

</section>

</main>

<footer>
  <div class="container footer-footer">
    <p>&copy; 2026 Abaya Atelier. All rights reserved.</p>
    <p>Email: info@abayastore.com | Phone: +966 533458857</p>
  </div>
</footer>
<script>
document.querySelector(".search-form").addEventListener("submit", function(e) {
    const searchInput = document.querySelector("#q");

    if (searchInput.value.trim() === "") {
        e.preventDefault();
        alert("Please enter a product name or ID to search.");
    }
});
</script>
</body>
</html>
