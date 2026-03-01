<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../config/database.php");

// role column is managed by registration; no need to inspect on login anymore
$colcheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if ($colcheck && mysqli_num_rows($colcheck) == 0) {
    // column will be created automatically during registration if missing
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    // role selection removed; login simply uses stored role

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../dashboard.php");
                exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}

// set page title and include header
$pageTitle = 'Login';
include __DIR__ . '/../includes/header.php';

// check for registration success flash
$regMessage = '';
if (isset($_SESSION['reg_success'])) {
    $roleMsg = isset($_SESSION['registered_role']) ? $_SESSION['registered_role'] : 'student';
    $regMessage = "Account registered successfully as " . htmlspecialchars($roleMsg) . "!";
    unset($_SESSION['reg_success'], $_SESSION['registered_role']);
}
?>

<div class="container">
    <h2>Login</h2>

    <?php if(!empty($regMessage)): ?>
        <div id="infoModal">
            <div class="modal-content">
                <p><?php echo $regMessage; ?></p>
                <button type="button" id="closeInfo">Close</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($error)) echo "<p class='alert error'>" . htmlspecialchars($error) . "</p>"; ?>

    <form method="POST">

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var info = document.getElementById('infoModal');
        var closeBtn = document.getElementById('closeInfo');
        if (info) {
            info.classList.add('show');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    info.classList.remove('show');
                });
            }
            // auto-hide after 4 seconds
            setTimeout(function() { info.classList.remove('show'); }, 4000);
        }
    });
    </script>
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>


        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>