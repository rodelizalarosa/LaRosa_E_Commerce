<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

require 'vendor/autoload.php';
use Rodeliza\MiniFrameworkStore\Models\Category;

$categories = new Category();
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>The Matcha Nook</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="assets/css/styles.css" />

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #4C8C4A !important;
    }
    .nav-link {
      font-weight: 500;
    }
    .badge {
      font-size: 0.7rem;
      padding: 4px 7px;
      min-width: 18px;
    }
  </style>
</head>

<body class="store-body">
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">The Matcha Nook</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- Left side -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

        <!-- Categories -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
            <?php foreach ($categories->getAll() as $category): ?>
              <li><a class="dropdown-item" href="category.php?id=<?= htmlspecialchars($category['id']) ?>">
                <?= htmlspecialchars($category['category_name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>

        <!-- Admin-only links -->
        <?php if ($user && $user['role'] === 'Admin'): ?>
          <li class="nav-item"><a class="nav-link" href="add-product.php">Add Product</a></li>
          <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <?php endif; ?>
      </ul>

      <!-- Right side -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if ($user): ?>
          <!-- Show cart only when logged in -->
          <li class="nav-item">
            <a class="nav-link position-relative" href="cart.php">
              <i class="fas fa-shopping-cart me-1"></i> Cart
              <?php if ($cartCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                  <?= $cartCount ?>
                  <span class="visually-hidden">cart items</span>
                </span>
              <?php endif; ?>
            </a>
          </li>

          <!-- Customer-specific link -->
          <?php if ($user['role'] === 'Customer'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Order Summary</a></li>
          <?php endif; ?>

          <!-- Account dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Hello, <?= htmlspecialchars($user['name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="my-account.php">My Account</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Show only if not logged in -->
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
