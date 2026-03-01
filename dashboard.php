<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// if role wasn't stored in session (or is unexpected), try to retrieve from database
if (empty($_SESSION['role']) || !in_array($_SESSION['role'], ['student','vendor'])) {
    include __DIR__ . '/config/database.php';
    $uid = intval($_SESSION['user_id']);
    $res = mysqli_query($conn, "SELECT role FROM users WHERE id=$uid");
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if (in_array($row['role'], ['student','vendor'])) {
            $_SESSION['role'] = $row['role'];
        }
    }
}

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>

    <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

    <?php if ($_SESSION['role'] === 'student'): ?>
        <p>Ready to order? <a href="/CampusEats/menu.php" class="order-button">View menu</a></p>
        <p>Your recent orders will appear here once you start ordering.</p>
    <?php elseif ($_SESSION['role'] === 'vendor'): ?>
        <p>Manage your offerings by adding or editing menu items.</p>
        <p><a href="/CampusEats/vendor/dashboard.php" class="order-button">Vendor panel</a></p>
    <?php else: ?>
        <p>Explore the site using the navigation above.</p>
        <?php if (empty($_SESSION['role'])): ?>
            <p class="alert error">No role is assigned to your account; please re‑register or contact support.</p>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="auth/logout.php" class="order-button">Logout</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>