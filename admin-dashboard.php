<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>
<?php

use Rodeliza\MiniFrameworkStore\Models\User;
use Rodeliza\MiniFrameworkStore\Models\Checkout;
use Rodeliza\MiniFrameworkStore\Models\Category;

$users = new User();
$orders = new Checkout();
$categories = new Category();

// Fetch all customers
$customers = $users->getAllCustomers();

// Fetch all orders with product and user info
$orderList = $orders->getAllOrders();

// Prepare revenue per category for pie chart
$revenueByCategory = $orders->getRevenueByCategory();

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

    <!-- Orders Table -->
    <div class="mb-5">
        <h4>List of Orders</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderList as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['name'] ?></td>
                    <td><?= $order['product_name'] ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td>₱<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= $order['order_date'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Revenue by Category Chart -->
    <div class="mb-5">
        <h4>Revenue by Category</h4>
        <canvas id="revenueChart" width="400" height="200"></canvas>
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
