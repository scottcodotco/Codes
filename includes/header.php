<?php
// common header include - accepts $pageTitle variable
if (!isset($pageTitle)) {
    $pageTitle = 'CampusEats';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="/CampusEats/css/style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <h1>CampusEats</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/CampusEats/dashboard.php">Dashboard</a>
                <a href="/CampusEats/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="/CampusEats/auth/login.php">Login</a>
                <a href="/CampusEats/auth/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
