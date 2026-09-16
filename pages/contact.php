<?php
$messageStatus = "";

$host = "localhost";
$user = "root";
$pass = "";
$db   = "abaya_atelier.fi";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajax"])) {

    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    $sql = "INSERT INTO Contact (name, email, phone, message)
            VALUES ('$name', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {

        echo json_encode([
            "success" => true,
            "message" => "Message sent successfully"
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Error: " . $conn->error
        ]);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../css/style.css">
</head>

<body class="register-page">

<header>
    <div class="container header-container">

        <img class="logo" src="../images/logo.png" alt="Elegant Abaya Collection Banner">

        <nav>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../pages/products.php">Products</a></li>
                <li><a href="../pages/about.php">About Us</a></li>
                <li><a href="../pages/contact.php">Contact</a></li>
                <li><a href="../pages/login.php">Login</a></li>
                <li><a href="../pages/register.php">Register</a></li>
                <li><a href="../pages/cart.php">Cart</a></li>
            </ul>
        </nav>

    </div>
</header>

<main>

<section class="about-hero-simple">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We would love to hear from you</p>
    </div>
</section>

<section class="about">
<div class="container">

<div class="about-grid-simple">

<div class="about-card-simple">

<h2>Contact Information</h2>

<p>📍 Dammam, Saudi Arabia</p>
<p>📞 +966 533458857</p>
<p>✉️ info@abayastore.com</p>

</div>

<div class="about-card-simple">

<h2>Send Message</h2>

<p id="msgBox" style="color:green;"></p>

<form id="contactForm">

<input type="text" name="name" placeholder="Your Name" required><br><br>

<input type="email" name="email" placeholder="Your Email" required><br><br>

<input type="tel" name="phone" pattern="05[0-9]{8}" placeholder="05xxxxxxxx" required><br><br>

<textarea name="message" rows="5" placeholder="Write your message" required></textarea><br><br>

<button class="btn" type="submit">Send</button>

</form>

</div>

</div>
</div>
</section>

<section class="container">

<h2>Our Location</h2>

<iframe
src="https://www.google.com/maps/embed?..."
width="100%"
height="300"
style="border:0">
</iframe>

</section>

</main>

<footer>
    <div class="container footer-container">
        <p>©️ 2026 Abaya Store</p>
    </div>
</footer>

<script>
document.getElementById("contactForm").addEventListener("submit", function(e) {

    e.preventDefault();

    const formData = new FormData(this);
    formData.append("ajax", "1");

    fetch("contact.php", {
        method: "POST",
        body: formData
    })

    .then(res => res.json())
    .then(data => {

        const msgBox = document.getElementById("msgBox");

        if (data.success) {
            msgBox.style.color = "green";
            msgBox.textContent = data.message;
            document.getElementById("contactForm").reset();
        } else {
            msgBox.style.color = "red";
            msgBox.textContent = data.message;
        }

    })

    .catch(() => {
        document.getElementById("msgBox").textContent = "Something went wrong.";
    });

});
</script>

</body>
</html>