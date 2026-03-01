<?php
// simple placeholder vendor admin page
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../auth/login.php");
    exit();
}
$pageTitle = 'Vendor Panel';
include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h2>Vendor Dashboard</h2>
    <p>Welcome to your vendor panel! To get started, add items you'd like to sell so students can place orders.</p>
    <p><a href="add_item.php" class="order-button">Add menu item</a> (this page will be developed soon)</p>
    <p>Your current offerings and incoming orders will appear here once they're set up.</p>
</div>
<?php include __DIR__ . '/../includes/footer.php';
