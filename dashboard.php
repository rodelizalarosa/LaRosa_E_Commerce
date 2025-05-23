<?php 
include 'helpers/functions.php'; 
template('header.php'); 

use Rodeliza\MiniFrameworkStore\Models\Checkout;
$checkout = new Checkout();

$statusClass = [
    'Pending' => 'bg-warning text-dark',
    'Delivered' => 'bg-success',
    'Cancelled' => 'bg-danger',
    'Processing' => 'bg-info text-dark'
];

$orders = $checkout->getAllOrders(); // make sure this fetches order_status now
?>

<div class="container my-5">
    <h2>Order History</h2>
    <p>Here are your past orders made on the site:</p>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($orders as $order): ?>
            <?php
            $status = $order['order_status'] ?? 'Pending'; // default fallback
            $class = $statusClass[$status] ?? '';
            ?>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Order #<?= htmlspecialchars($order['id']) ?></h5>
                        <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                        <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></p>
                        <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                        <p><strong>Order Date:</strong> <?= date('F j, Y', strtotime($order['order_date'])) ?></p>
                        <p><strong>Status:</strong> <span class="badge <?= $class ?>"><?= htmlspecialchars($status) ?></span></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php template('footer.php'); ?>
