<?php 
require 'vendor/autoload.php';
include 'helpers/functions.php';

use Rodeliza\MiniFrameworkStore\Models\Product;
use Rodeliza\MiniFrameworkStore\Models\Category;

    $categoryId = isset($_GET['id']) ? $_GET['id'] : null;

    // Redirect early if no id provided
    if (!$categoryId) {
        header('Location: index.php');
        exit;
    }

    $categoryModel = new Category();
    $categoryDetails = $categoryModel->getById($categoryId);

    // Redirect if category not found
    if (!$categoryDetails) {
        header('Location: index.php');
        exit;
    }

    template('header.php');

    $products = new Product();

    $amounLocale = 'en_PH';
    $pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $categoryDetails['category_name']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="text-center"><?php echo $categoryDetails['category_name']; ?></h2>
        </div>

        <?php foreach ($products->getByCategory($categoryId) as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100 shadow-hover">
                    <div class="position-relative">
                        <img src="<?php echo $product['image_path']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">
                                <?php echo $pesoFormatter->formatCurrency($product['price'], 'PHP'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text flex-grow-1 text-muted"><?php echo substr($product['description'], 0, 100) . '...'; ?></p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary flex-fill">
                                <i class="bi bi-eye me-2"></i>View Product
                            </a>
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-success add-to-cart flex-fill" 
                                        data-productid="<?php echo $product['id']; ?>" 
                                        data-quantity="1">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-success flex-fill">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php template('footer.php'); ?>
