<?php include 'helpers/functions.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}
?>

<?php template('header.php'); ?>

<?php
use Rodeliza\MiniFrameworkStore\Models\Product;

$productId = $_GET['id'];
$products = new Product();
$product = $products->getById($productId);

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);
?>

<style>
    .product-image-container {
        width: 100%;
        height: 400px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        cursor: pointer;
    }

    .product-image-container:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transform: scale(1.05);
        background-color: #e9f0f7; /* subtle background change */
    }

    .product-image-container img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-image-container:hover img {
        transform: scale(1.1);
    }

    .quantity-input {
        width: 80px;
        margin-right: 10px;
    }
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="product-image-container">
                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Product Image" class="img-fluid">
            </div>
        </div>
        <div class="col-md-6">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <h4 class="text-body-secondary"><?= $pesoFormatter->formatCurrency($product['price'], 'PHP'); ?></h4>
            <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>

            <div class="d-flex align-items-center">
                <input type="number" min="1" value="1" class="form-control quantity-input" id="quantity">
                <a href="#" class="btn btn-success add-to-cart" 
                   data-productid="<?= $product['id'] ?>" 
                   data-quantity="1">Add to Cart</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.querySelector('.add-to-cart');

    if (quantityInput && addToCartBtn) {
        quantityInput.addEventListener('input', () => {
            addToCartBtn.setAttribute('data-quantity', quantityInput.value || 1);
        });
    }

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const productId = this.getAttribute('data-productid');
            const quantity = this.getAttribute('data-quantity') || 1;

            fetch('cart-process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `productId=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Added!', 'Product added to cart.', 'success');
                    // Optionally update cart UI here
                } else {
                    Swal.fire('Error', 'Failed to add product to cart.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            });
        });
    }
</script>

<?php template('footer.php'); ?>
