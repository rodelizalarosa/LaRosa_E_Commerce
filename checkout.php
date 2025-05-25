<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Rodeliza\MiniFrameworkStore\Models\Checkout;
use Rodeliza\MiniFrameworkStore\Models\User;

$checkout = new Checkout();
$userModel = new User();

$superTotal = 0;
$orderId = null;

if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $superTotal += $item['total'] * $item['quantity'];
    }
}

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

$userInfo = null;
if(isset($_SESSION['user'])) {
    $userInfo = $userModel->getById($_SESSION['user']['id']);
}

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if(isset($_SESSION['user'])) {
        $orderId = $checkout->userCheckout([
            'user_id' => $_SESSION['user']['id'],
            'total' => $superTotal
        ]);
    } else {
        $orderId = $checkout->guestCheckout([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'total' => $superTotal
        ]);
    }

    foreach($_SESSION['cart'] as $item) {
        $checkout->saveOrderDetails([
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'subtotal' => $item['total'] * $item['quantity']
        ]);
    }

    unset($_SESSION['cart']);

    echo "<script>alert('Order placed successfully!'); window.location.href='order-success.php'</script>";
}

?>

<style>
.checkout-container {
    max-width: 900px;
    margin: 2rem auto;
    display: flex;
    gap: 2rem;
}
.checkout-form {
    flex: 2;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.checkout-summary {
    flex: 1;
    background: #f9f9f9;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
.checkout-summary h3 {
    margin-bottom: 1rem;
}
.checkout-summary .cart-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}
.checkout-summary .cart-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 1rem;
}
.checkout-summary .cart-item-details {
    flex-grow: 1;
}
.checkout-summary .cart-item-price {
    font-weight: 600;
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.3rem;
    font-weight: 600;
}
.form-group input, .form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.shipping-method, .payment-method {
    margin-top: 2rem;
}
.shipping-method label, .payment-method label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}
.checkout-button {
    margin-top: 2rem;
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}
</style>

<div class="checkout-container">
    <div class="checkout-form">
        <h2>Shipping Address</h2>
        <form action="checkout.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name*</label>
                <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($userInfo['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Contact Number*</label>
                <input type="text" id="phone" name="phone" required value="<?php echo htmlspecialchars($userInfo['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="address">Address*</label>
                <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($userInfo['address'] ?? ''); ?></textarea>
            </div>

            <div class="shipping-method">
                <label>Shipping Method</label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" id="free_shipping" name="shipping_method" value="free" checked disabled>
                    <label for="free_shipping" style="margin: 0;">Free Shipping</label>
                </div>
            </div>

            <div class="payment-method">
                <label>Payment Method</label>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" id="cod" name="payment_method" value="cod" checked disabled>
                    <label for="cod" style="margin: 0;">Cash on Delivery</label>
                </div>
            </div>

            <button type="submit" name="submit" class="checkout-button">Checkout</button>
        </form>
    </div>

    <div class="checkout-summary">
        <h3>Your Cart</h3>
        <?php if(countCart() > 0): ?>
            <?php foreach($_SESSION['cart'] as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image_path'] ?? 'uploads/default.png'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cart-item-details">
                        <div><?php echo htmlspecialchars($item['name']); ?></div>
                        <small><?php echo htmlspecialchars($item['description'] ?? ''); ?></small>
                    </div>
                    <div class="cart-item-price"><?php echo $pesoFormatter->formatCurrency($item['total'] * $item['quantity'], 'PHP'); ?></div>
                </div>
            <?php endforeach; ?>
            <hr>
            <div><strong>Total: <?php echo $pesoFormatter->formatCurrency($superTotal, 'PHP'); ?></strong></div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</div>

<?php template('footer.php'); ?>
