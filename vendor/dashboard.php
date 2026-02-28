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
    <p>Here you will manage your menu items and orders.</p>
    <p>Functionality coming soon.</p>
</div>
<?php include __DIR__ . '/../includes/footer.php';
