<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// clear session
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// show a friendly message before redirect
$pageTitle = 'Logging out';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h2>Goodbye!</h2>
    <p>You have been logged out. Redirecting to login page...</p>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
// send meta refresh in case headers already sent
echo '<meta http-equiv="refresh" content="2;url=login.php">';
exit();
?>