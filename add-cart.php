<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Rodeliza\MiniFrameworkStore\Models\Product;
use Rodeliza\MiniFrameworkStore\Models\Category;

if(isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    if(isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        echo "<script>alert('Product removed from cart');</script>";
    }
}

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);

$productModel = new Product();
$categoryModel = new Category();

?>

<style>
.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.cart-header h2 {
    font-weight: 600;
}
.cart-close-btn {
    font-size: 1.5rem;
    cursor: pointer;
    border: none;
    background: none;
}
.cart-product-card {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 1rem 0;
    position: relative;
}
.cart-product-image img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin-right: 1rem;
}
.cart-product-info {
    flex-grow: 1;
}
.cart-category-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.2rem;
}
.cart-product-name {
    font-weight: 600;
    margin: 0;
}
.cart-product-description {
    font-size: 0.9rem;
    color: #444;
    margin: 0.2rem 0;
}
.quantity-controls {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
}
.quantity-controls button {
    width: 28px;
    height: 28px;
    border: 1px solid #ccc;
    background: white;
    cursor: pointer;
    font-size: 1.2rem;
    line-height: 1;
    padding: 0;
    user-select: none;
}
.quantity-controls input {
    width: 40px;
    text-align: center;
    border: 1px solid #ccc;
    margin: 0 0.5rem;
    font-size: 1rem;
    height: 28px;
}
.cart-product-price {
    font-weight: 600;
    font-size: 1.1rem;
    margin-left: 1rem;
}
.cart-remove-btn {
    position: absolute;
    top: 1rem;
    right: 0;
    background: none;
    border: none;
    color: #d9534f;
    font-size: 1.3rem;
    cursor: pointer;
}
.cart-remove-btn svg {
    width: 20px;
    height: 20px;
    fill: #d9534f;
}
.cart-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #ddd;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
}
.btn-checkout {
    background-color:rgb(86, 142, 58); /* Matcha themed green */
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 1rem;
    display: inline-block;
}
</style>

<div class="container my-5">
    <div class="cart-header">
        <h2>My Cart (<?php echo count($_SESSION['cart']); ?>)</h2>
        <button class="cart-close-btn" onclick="window.location.href='index.php'">&times;</button>
    </div>

    <?php if(countCart() == 0): ?>
        <p>Your cart is empty.</p>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    <?php else: ?>
        <div class="cart-products">
            <?php foreach($_SESSION['cart'] as $item): 
                $productDetails = $productModel->getById($item['product_id']);
                $categoryDetails = $categoryModel->getById($productDetails['category_id']);
                $imagePath = $productDetails ? $productDetails['image_path'] : 'uploads/default.png';
                $categoryName = $categoryDetails ? $categoryDetails['category_name'] : 'Uncategorized';
            ?>
            <div class="cart-product-card">
                <div class="cart-product-image">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="cart-product-info">
                    <div class="cart-category-label"><?php echo htmlspecialchars($categoryName); ?></div>
                    <h3 class="cart-product-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <div class="cart-product-description"><?php echo htmlspecialchars($productDetails['description'] ?? ''); ?></div>
                    <div class="quantity-controls">
                        <button type="button" class="qty-decrease" data-product-id="<?php echo $item['product_id']; ?>">−</button>
                        <input type="text" class="qty-input" data-product-id="<?php echo $item['product_id']; ?>" value="<?php echo $item['quantity']; ?>" readonly>
                        <button type="button" class="qty-increase" data-product-id="<?php echo $item['product_id']; ?>">+</button>
                    </div>
                </div>
                <div class="cart-product-price">
                    <?php echo $pesoFormatter->formatCurrency($item['total'], 'PHP'); ?>
                </div>
                <button class="cart-remove-btn" onclick="window.location.href='add-cart.php?remove=<?php echo $item['product_id']; ?>'" title="Remove product">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M3 6h18M9 6v12a1 1 0 0 0 2 0V6m4 0v12a1 1 0 0 0 2 0V6M5 6l1-2h12l1 2"/>
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="btn-checkout" onclick="window.location.href='checkout.php'">Checkout</button>
    <?php endif; ?>
</div>

<div class="cart-footer"></div>

<script>
document.querySelectorAll('.qty-increase').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-product-id');
        const input = document.querySelector('.qty-input[data-product-id="' + productId + '"]');
        let currentQty = parseInt(input.value);
        input.value = currentQty + 1;
        // TODO: Add AJAX or form submission to update quantity in session/cart
    });
});

document.querySelectorAll('.qty-decrease').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-product-id');
        const input = document.querySelector('.qty-input[data-product-id="' + productId + '"]');
        let currentQty = parseInt(input.value);
        if(currentQty > 1) {
            input.value = currentQty - 1;
            // TODO: Add AJAX or form submission to update quantity in session/cart
        }
    });
});
</script>

<?php template('footer.php'); ?>
