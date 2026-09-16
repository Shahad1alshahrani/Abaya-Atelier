<?php
session_start();
include 'db.php';

$featured = mysqli_query($conn, "SELECT * FROM Product ORDER BY ProductID ASC LIMIT 6");
$newCollection = mysqli_query($conn, "SELECT * FROM Product ORDER BY ProductID DESC LIMIT 6");

if (!$featured || !$newCollection) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Abaya Atelier | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>

<header>
    <div class="container header-container">

        <!-- FIXED LOGO PATH -->
        <img class="logo" src="images/logo.png" alt="Abaya Atelier Logo">

        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="pages/products.php">Products</a></li>
                <li><a href="pages/about.php">About Us</a></li>
                <li><a href="pages/contact.php">Contact</a></li>
                <li><a href="pages/login.php">Login</a></li>
                <li><a href="pages/register.php">Register</a></li>
                <li><a href="pages/cart.php">Cart (<?php echo $cart_count; ?>)</a></li>
                <li><a href="pages/help.php">Help</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>

<!-- BANNER -->
<section class="banner">
    <img src="images/banner4.png" alt="Elegant Abaya Collection Banner">
    <div class="banner-text">
        <h2>Elegance in Every Detail</h2>
        <p>Discover our premium abaya collection</p>
        <a href="pages/products.php" class="btn">Shop Now</a>
        <a href="pages/search.php" class="btn">Search Abayas</a>
    </div>
</section>

<!-- FEATURED -->
<section class="featured">
    <h2>Our best-selling abayas</h2>

    <div class="products-slider">

    <?php while($row = mysqli_fetch_assoc($featured)): ?>

        <article class="product-card">
            <figure>

                <!-- FIXED IMAGE PATH -->
                <img src="images/<?php echo htmlspecialchars($row['Image']); ?>"
                     alt="<?php echo htmlspecialchars($row['Name']); ?>">

                <figcaption>
                    <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
                    <p><?php echo number_format($row['Price'], 2); ?> SAR</p>
                    <a href="pages/product-details.php?id=<?php echo $row['ProductID']; ?>" class="btn">
                        View Details
                    </a>
                </figcaption>

            </figure>
        </article>

    <?php endwhile; ?>

    </div>
</section>

<!-- NEW COLLECTION -->
<section class="featured">

<section class="collection-banner">
    <img src="images/new-collection-banner.jpeg" alt="New Collection Banner">
    <div class="collection-banner-text">
        <h2>Our New Collection</h2>
        <p>Discover our latest abaya designs</p>
    </div>
</section>

<div class="products-slider">

<?php while($row = mysqli_fetch_assoc($newCollection)): ?>

    <article class="product-card">
        <figure>

            <!-- FIXED IMAGE PATH -->
            <img src="images/<?php echo htmlspecialchars($row['Image']); ?>"
                 alt="<?php echo htmlspecialchars($row['Name']); ?>">

            <figcaption>
                <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
                <p><?php echo number_format($row['Price'], 2); ?> SAR</p>
                <a href="pages/product-details.php?id=<?php echo $row['ProductID']; ?>" class="btn">
                    View Details
                </a>
            </figcaption>

        </figure>
    </article>

<?php endwhile; ?>

</div>

</section>

</main>

<footer>
    <div class="container footer-container">
        <p>&copy; 2026 Abaya Atelier. All rights reserved.</p>
        <p>Email: info@abayastore.com | Phone: +966 533458857</p>
    </div>
</footer>

</body>
</html>