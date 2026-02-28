<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../config/database.php");

// make sure connection is alive
if (!$conn || mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ensure users table has a role column (student/vendor)
$colcheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if ($colcheck && mysqli_num_rows($colcheck) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'student'");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // role should be either 'student' or 'vendor'; default to student if anything else
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : 'student';
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

        header("Location: login.php");
        exit();
    }
}

// header include
$pageTitle = 'Register';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <h2>Register (student or vendor)</h2>

    <?php if(isset($error)) echo "<p class='alert error'>" . htmlspecialchars($error) . "</p>"; ?>

    <form method="POST">
        Full Name: <br>
        <input type="text" name="full_name" required><br><br>

        Email: <br>
        <input type="email" name="email" required><br><br>

        Password: <br>
        <input type="password" name="password" required><br><br>

        Role: <br>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="vendor">Vendor</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>