<?php
require 'vendor/autoload.php';
use Rodeliza\MiniFrameworkStore\Models\Product;

session_start();

$product_id = intval($_POST['productId']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product = new Product();
$productDetails = $product->getById($product_id);

// Add or update cart
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
} else {
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'name' => $productDetails['name'],
        'price' => $productDetails['price'],
        'image_path' => $productDetails['image_path'],
        'total' => $productDetails['price'] * $quantity
    ];
}

$cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

echo json_encode([
    'status' => 'success',
    'message' => 'Product added to cart',
    'cartCount' => $cartCount
]);
