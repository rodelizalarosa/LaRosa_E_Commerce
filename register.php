<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php

use Rodeliza\MiniFrameworkStore\Models\User;
use Carbon\Carbon;

$user = new User();

if (isset($_POST['submit'])) {
    $role = (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin' && isset($_POST['role']))
        ? $_POST['role']
        : 'Customer';

    $registered = $user->register([
        'name'       => $_POST['full-name'],
        'email'      => $_POST['email'],
        'password'   => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'role'       => $role,
        'created_at' => Carbon::now('Asia/Manila'),
        'updated_at' => Carbon::now('Asia/Manila')
    ]);
}

if (isset($_SESSION['user']) && !empty($_SESSION['user']) && !isset($_POST['submit'])) {
    header('Location: dashboard.php');
    exit;
}

?>

<div class="container">
    <div class="row align-items-center">
        <div class="col mt-5 mb-5">
            <h1 class="text-center">Register</h1>
            <h3 class="text-center">
                <?php if (!empty($registered)): ?>
                    <small class="confirmation-message">
                        You have successfully registered! You may now <a href="login.php">login</a>.
                    </small>
                <?php endif; ?>
            </h3>

            <form style="width: 400px; margin: auto;" action="register.php" method="POST">
                <div class="mb-3">
                    <label for="full-name" class="form-label">Name</label>
                    <input name="full-name" type="text" class="form-control" id="full-name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="password" required>
                </div>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select" id="role" required>
                            <option value="Customer" selected>Customer</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" name="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
