<?php
// simple placeholder menu page
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
$pageTitle = 'Menu';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h2>Menu</h2>
    <p>This is where the student menu would be displayed. Coming soon!</p>
</div>
<?php include __DIR__ . '/includes/footer.php';
