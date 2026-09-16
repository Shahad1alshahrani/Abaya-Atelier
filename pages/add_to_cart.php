
<?php
session_start();
include '../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);
    $result = mysqli_query($conn, "SELECT * FROM Product WHERE ProductID=$product_id");
    $product = mysqli_fetch_assoc($result);
    if (!$product) {
        die('Product not found.');
    }
    if ($quantity > (int)$product['Stock']) {
        die('Required quantity is not available. <a href="product-details.php?id=' . $product_id . '">Back</a>');
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $newQty = $item['quantity'] + $quantity;
            if ($newQty > (int)$product['Stock']) {
                die('Required quantity is not available. <a href="cart.php">View Cart</a>');
            }
            $item['quantity'] = $newQty;
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product['ProductID'],
            'name' => $product['Name'],
            'image' => $product['Image'],
            'price' => $product['Price'],
            'quantity' => $quantity
        ];
    }
    header('Location: cart.php');
    exit;
}
header('Location: products.php');
