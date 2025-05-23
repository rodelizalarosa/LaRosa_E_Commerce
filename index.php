<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php
use Rodeliza\MiniFrameworkStore\Models\Product;

$products = new Product();

$amounLocale = 'en_PH';
$pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);
?>

<style>
    .product-image-container {
        width: 100%;
        height: 250px;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 8px;
        transition: box-shadow 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
    }

    .product-image-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .card {
        cursor: pointer;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        position: relative;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
    }

    .card:hover .product-image-container {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transform: scale(1.05);
        background-color: #e9f0f7;
    }

    .card:hover .product-image-container img {
        transform: scale(1.1);
    }

    .quantity-input {
        width: 60px;
        display: inline-block;
        margin-right: 8px;
    }

    /* Prevent links and inputs inside the card from triggering the card click */
    .card-body a,
    .card-body input {
        cursor: auto;
        position: relative;
        z-index: 2;
    }
</style>

<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-12 text-center">
            <h1 class="brand-text">Welcome to The Matcha Nook</h1>
            <p class="text-matcha">Your one-stop shop for all your matcha cravings!</p>
        </div>
    </div>
    <div class="row">
        <h2 class="text-matcha">Products</h2>
        <?php foreach ($products->getAll() as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card rounded-box shadow-sm"
                     onclick="window.location.href='product.php?id=<?= $product['id'] ?>'">
                    <div class="product-image-container">
                        <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="Product Image">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-matcha"><?= htmlspecialchars($product['name']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $pesoFormatter->formatCurrency($product['price'], 'PHP') ?></h6>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>

                        <input type="number" min="1" value="1" class="form-control quantity-input" id="qty-<?= $product['id'] ?>">
                        <a href="#" class="btn btn-success add-to-cart" data-productid="<?= $product['id'] ?>" data-quantity="1">Add to Cart</a>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary">View Product</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Update quantity before sending to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-productid');
            const quantity = document.getElementById('qty-' + productId).value || 1;
            this.setAttribute('data-quantity', quantity);

            fetch('cart-process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `productId=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Added!', 'Product added to cart.', 'success');
                    if (data.cartCount !== undefined) {
                        document.getElementById('cart-count').innerText = data.cartCount;
                    }
                }
            });
        });
    });

    // Prevent card click when clicking buttons or inputs inside card-body
    document.querySelectorAll('.card .card-body a, .card .card-body input').forEach(el => {
        el.addEventListener('click', e => {
            e.stopPropagation();
        });
    });
</script>

<?php template('footer.php'); ?>
