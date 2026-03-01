<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../config/database.php");

// make sure connection is alive
if (!$conn || mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ensure users table has a role column (student/vendor or vendor initially)
$colcheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if ($colcheck && mysqli_num_rows($colcheck) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'student'");
} elseif ($colcheck && mysqli_num_rows($colcheck) == 1) {
    $col = mysqli_fetch_assoc($colcheck);
    // if column exists but is an enum that doesn't include vendor, convert to varchar
    if (stripos($col['Type'], 'enum') !== false) {
        mysqli_query($conn, "ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'student'");
    }
}

// determine default role from query string (used when coming from header popup)
$defaultRole = 'student';
if (isset($_GET['role']) && in_array($_GET['role'], ['student','vendor'])) {
    $defaultRole = $_GET['role'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // determine role: prefer POST value but fall back to GET/default
    if (!empty($_POST['role'])) {
        $role = mysqli_real_escape_string($conn, $_POST['role']);
    } elseif (isset($_GET['role']) && in_array($_GET['role'], ['student','vendor'])) {
        $role = $_GET['role'];
    } else {
        $role = 'student';
    }
    if ($role !== 'student' && $role !== 'vendor') {
        $role = 'student';
    }

    // ensure connection didn't drop (ping reconnect)
    if (!mysqli_ping($conn)) {
        mysqli_close($conn);
        // re-open using same credentials from config
        $conn = mysqli_connect($host, $user, $pass, $db);
        if (!$conn) {
            die("Lost connection and reconnection failed: " . mysqli_connect_error());
        }
    }

    // check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (!$check) {
        die("Query error: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($check) > 0) {
        $error = "Email is already registered.";
    } else {
        $query = "INSERT INTO users (full_name, email, password, role)
                  VALUES ('$full_name', '$email', '$password', '$role')";

        if (!mysqli_query($conn, $query)) {
            die("Insertion error: " . mysqli_error($conn));
        }

        // flash message for login page
        $_SESSION['reg_success'] = true;
        $_SESSION['registered_role'] = $role;

        header("Location: login.php");
        exit();
    }
}

// header include
$pageTitle = 'Register';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h2>Register</h2>

    <?php if(isset($error)) echo "<p class='alert error'>" . htmlspecialchars($error) . "</p>"; ?>

    <form method="POST">
        Full Name: <br>
        <input type="text" name="full_name" required><br><br>

        Email: <br>
        <input type="email" name="email" required><br><br>

        Password: <br>
        <input type="password" name="password" required><br><br>

        <!-- role will be chosen via JS modal popup; default may come from ?role -->
        <input type="hidden" name="role" id="roleInput" value="<?php echo htmlspecialchars($defaultRole); ?>">

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>

    <!-- modal for picking student/vendor -->
    <div id="roleModal">
        <div class="modal-content">
            <h3>Choose account type</h3>
            <p>Are you registering as a student or a vendor?</p>
            <button type="button" id="chooseStudent">I'm a student</button>
            <button type="button" id="chooseVendor">I'm a vendor</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    var roleInput = document.getElementById('roleInput');
    var modal = document.getElementById('roleModal');
    var studentBtn = document.getElementById('chooseStudent');
    var vendorBtn = document.getElementById('chooseVendor');

    var suppressPopup = false;
    form.addEventListener('submit', function(e) {
        if (suppressPopup) {
            return; // allow programmatic submission
        }
        // if role already supplied via query string, skip the popup and allow submit
        if (window.location.search.indexOf('role=') !== -1) {
            return; // let the form submit normally
        }
        e.preventDefault();
        modal.classList.add('show');
    });

    studentBtn.addEventListener('click', function() {
        roleInput.value = 'student';
        suppressPopup = true;
        form.submit();
    });
    vendorBtn.addEventListener('click', function() {
        roleInput.value = 'vendor';
        suppressPopup = true;
        form.submit();
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>