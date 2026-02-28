<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../config/database.php");

// if role column doesn't exist yet, add it so login logic works
$colcheck = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if ($colcheck && mysqli_num_rows($colcheck) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'student'");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $selected_role = isset($_POST['role']) ? $_POST['role'] : '';

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // make sure the user is choosing the correct role when logging in
            if ($selected_role && $selected_role !== $user['role']) {
                $error = "Role mismatch. Please choose the correct role.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../dashboard.php");
                exit();
            }
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
?>

<div class="container">
    <h2>Login (student or vendor)</h2>

    <?php if(isset($error)) echo "<p class='alert error'>" . htmlspecialchars($error) . "</p>"; ?>

    <form method="POST">
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role</label><br>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="vendor">Vendor</option>
        </select><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>