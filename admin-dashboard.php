<?php
require_once 'vendor/autoload.php';
include 'helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $checkout = new Rodeliza\MiniFrameworkStore\Models\Checkout();
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];
    $checkout->updateOrderStatus($orderId, $newStatus);
    // Redirect to avoid header already sent error and form resubmission
    header("Location: admin-dashboard.php");
    exit;
}

template('header.php');

use Rodeliza\MiniFrameworkStore\Models\User;
use Rodeliza\MiniFrameworkStore\Models\Category;

$users = new User();
$categories = new Category();

// Fetch all customers
$customers = $users->getAllCustomers();

// Prepare revenue per category for pie chart
$ordersModel = new Rodeliza\MiniFrameworkStore\Models\Checkout();
$revenueByCategory = $ordersModel->getRevenueByCategory();

// Fetch all orders with user info and order date
$allOrders = $ordersModel->getAllOrders();

$statusClass = [
    'Pending' => 'bg-warning text-dark',
    'Delivered' => 'bg-success',
    'Cancelled' => 'bg-danger',
    'Processing' => 'bg-info text-dark'
];
?>

<div class="container my-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Customers Table -->
    <div class="mb-5">
        <h4>List of Customers</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td><?= htmlspecialchars($customer['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Orders Section -->
    <div class="mb-5">
        <h4>All Orders</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($allOrders as $order): ?>
                <?php
                $status = $order['order_status'] ?? 'Pending';
                $class = $statusClass[$status] ?? '';
                ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Order #<?= htmlspecialchars($order['id']) ?></h5>
                            <p><strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
                            <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></p>
                            <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                            <p><strong>Order Date:</strong> <?= date('F j, Y', strtotime($order['order_date'] ?? '')) ?></p>
                            <p><strong>Status:</strong> <span class="badge <?= $class ?>"><?= htmlspecialchars($status) ?></span></p>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                                <button type="submit" name="new_status" value="Processing" class="btn btn-info btn-sm">Process</button>
                                <button type="submit" name="new_status" value="Delivered" class="btn btn-success btn-sm">Deliver</button>
                                <button type="submit" name="new_status" value="Cancelled" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Revenue by Category Chart -->
    <div class="mb-5">
        <h4>Revenue by Category</h4>
        <div class="chart-container">
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($revenueByCategory, 'category_name')) ?>,
        datasets: [{
            label: 'Revenue',
            data: <?= json_encode(array_column($revenueByCategory, 'revenue')) ?>,
            backgroundColor: [
                '#A7D7F9',
                '#009688',
                '#50C878',
                '#1E3A5F',
                '#F5F5F5'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>

<?php template('footer.php'); ?>
