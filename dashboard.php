<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>

    <p>You are logged in.</p>
    <p><a href="auth/logout.php" class="order-button">Logout</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>