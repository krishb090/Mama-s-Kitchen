<?php 
session_start();
$pageTitle = 'Admin Login';

// Check if the user is already logged in and redirect accordingly
if (isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['role_restaurant_qRewacvAqzA'])) {
    if ($_SESSION['role_restaurant_qRewacvAqzA'] === 'admin') {
        header('Location: dashboard.php');
        exit(); // Make sure to exit after header redirection
    } elseif ($_SESSION['role_restaurant_qRewacvAqzA'] === 'staff') {
        header('Location: staff_dashboard.php');
        exit(); // Make sure to exit after header redirection
    }
}

// PHP INCLUDES
include 'connect.php';
include 'Includes/functions/functions.php';
include 'Includes/templates/header.php';
?>

<!-- LOGIN FORM -->
<div class="login">
    <form class="login-container validate-form" name="login-form" action="index.php" method="POST" onsubmit="return validateLoginForm()">
        <span class="login100-form-title p-b-32">
            Admin Login
        </span>
        <?php
        // Check if the user clicked on the submit button
        if (isset($_POST['admin_login'])) {
            $username = test_input($_POST['username']);
            $password = test_input($_POST['password']);
            $hashedPass = sha1($password); // Consider using password_hash() and password_verify()

            // Check if User exists in the database
            $stmt = $con->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? AND password = ?");
            $stmt->execute(array($username, $hashedPass));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // Check if count > 0, which means that the database contains a record for this username
            if ($count > 0) {
                $_SESSION['username_restaurant_qRewacvAqzA'] = $username;
                $_SESSION['userid_restaurant_qRewacvAqzA'] = $row['user_id'];
                $_SESSION['role_restaurant_qRewacvAqzA'] = $row['role'];

                // Redirect based on the user role
                if ($row['role'] === 'admin') {
                    header('Location: dashboard.php');
                    exit();
                } elseif ($row['role'] === 'staff') {
                    header('Location: staff_dashboard.php');
                    exit();
                }
            } else {
                ?>
                <div class="alert alert-danger">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="messages">
                        <div>Username and/or password are incorrect!</div>
                    </div>
                </div>
                <?php 
            }
        }
        ?>

        <!-- USERNAME INPUT -->
        <div class="form-input">
            <span class="txt1">Username</span>
            <input type="text" name="username" class="form-control username" oninput="document.getElementById('username_required').style.display = 'none'" id="user" autocomplete="off">
            <div class="invalid-feedback" id="username_required">Username is required!</div>
        </div>

        <!-- PASSWORD INPUT -->
        <div class="form-input">
            <span class="txt1">Password</span>
            <input type="password" name="password" class="form-control" oninput="document.getElementById('password_required').style.display = 'none'" id="password" autocomplete="new-password">
            <div class="invalid-feedback" id="password_required">Password is required!</div>
        </div>

        <!-- SIGNIN BUTTON -->
        <p>
            <button type="submit" name="admin_login">Sign In</button>
        </p>

        <!-- FORGOT PASSWORD PART -->
        <span class="forgotPW">Forgot your password? <a href="#">Reset it here.</a></span>

    </form>
</div>

<?php include 'Includes/templates/footer.php'; ?>
