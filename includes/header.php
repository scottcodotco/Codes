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
                <a href="/CampusEats/auth/register.php" id="navRegister">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>

    <!-- global modal used when clicking register link -->
    <div id="globalRoleModal">
        <div class="modal-content">
            <h3>Choose account type</h3>
            <p>Register as a student or a vendor?</p>
            <button type="button" id="globalStudent">I'm a student</button>
            <button type="button" id="globalVendor">I'm a vendor</button>
        </div>
    </div>

    <script>
    // global script added here so it is available on every page
    (function(){
        document.addEventListener('DOMContentLoaded', function() {
            var registerLink = document.getElementById('navRegister');
            var globalModal = document.getElementById('globalRoleModal');
            var studentBtn = document.getElementById('globalStudent');
            var vendorBtn = document.getElementById('globalVendor');

            if (registerLink) {
                registerLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (globalModal) {
                        globalModal.classList.add('show');
                    }
                });
            }

            if (studentBtn) {
                studentBtn.addEventListener('click', function() {
                    window.location.href = '/CampusEats/auth/register.php?role=student';
                });
            }
            if (vendorBtn) {
                vendorBtn.addEventListener('click', function() {
                    window.location.href = '/CampusEats/auth/register.php?role=vendor';
                });
            }
        });
    })();
    </script>
