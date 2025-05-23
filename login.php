<?php include 'helpers/functions.php'; ?>
<?php
require 'vendor/autoload.php';
use Rodeliza\MiniFrameworkStore\Models\User;


$user = new User();

if (isset($_POST['submit'])) {
    $user_info = $user->login([
        'email' => $_POST['email'],
    ]);

    if ($user_info && password_verify($_POST['password'], $user_info['password'])) {
        // 🔒 Clear old session data and regenerate
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);

        $_SESSION['user'] = $user_info;

        // Redirect based on role
        if ($user_info['role'] === 'Admin') {
            header('Location: admin-dashboard.php');
        } else {
            header('Location: my-account.php');
        }
        exit;
    } else {
        $message = 'Invalid email or password.';
    }
}

// Redirect already-logged-in users
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'Admin') {
        header('Location: admin-dashboard.php');
    } else {
        header('Location: my-account.php');
    }
    exit;
}
?>

<?php template('header.php'); ?>

<div class="container">
    <div class="row align-items-center">
        <div class="col mt-5 mb-5">
            <h1 class="text-center">Login</h1>
            <h6 class="text-center text-danger">
                <small><?php echo isset($message) ? $message : ''; ?></small>
            </h6>
            <form style="width: 400px; margin: auto;" action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
