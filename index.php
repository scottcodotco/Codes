<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$pageTitle = 'Welcome';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h2>Welcome to CampusEats</h2>
    <p>Your on‑campus canteen ordering system. Please <a href="auth/login.php">login</a> or <a href="auth/register.php">register</a> to place an order.</p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>