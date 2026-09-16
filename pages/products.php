<?php
session_start();
include '../db.php';

$result = mysqli_query($conn, "SELECT * FROM Product ORDER BY ProductID ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Abaya Atelier | Products</title>
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

<h1>Our Abayas</h1>
<div class="container" style="margin-bottom: 20px;">
 <input type="text" id="searchInput" placeholder="Search for abayas..." style="width:100%; padding:10px;">
</div>
<section class="products-grid">

<?php while($row = mysqli_fetch_assoc($result)): ?>

<article class="product-card">

    <!-- FIXED IMAGE PATH -->
    <img src="../images/<?php echo htmlspecialchars($row['Image']); ?>"
         alt="<?php echo htmlspecialchars($row['Name']); ?>">

    <h3><?php echo htmlspecialchars($row['Name']); ?></h3>

    <p><?php echo number_format($row['Price'], 2); ?> SAR</p>

    <a class="btn" href="product-details.php?id=<?php echo $row['ProductID']; ?>">
        View Details
    </a>

</article>

<?php endwhile; ?>

</section>

</div>

</main>

<footer>
  <div class="container footer-footer">
    <p>&copy; 2026 Abaya Atelier. All rights reserved.</p>
    <p>Email: info@abayastore.com | Phone: +966 533458857</p>
  </div>
</footer>
<script>

document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchValue = this.value.toLowerCase();
    
    let cards = document.querySelectorAll('.product-card, section, .item'); 

    cards.forEach(card => {
        let cardContent = card.innerText.toLowerCase();
        
        if (cardContent.includes(searchValue)) {
            card.style.display = ""; 
        } else {
            card.style.display = "none"; 
        }
    });
});
</script>
</body>
</html>
