<?php
session_start();

$pageTitle = 'Customer Login';
include "Includes/templates/navbar.php";

// Include database connection and functions
include 'connect.php';
include 'Includes/functions/functions.php';

// Check if user is already logged in
if (isset($_SESSION['customer_email'])) {
    header('Location: table-reservation.php');
    exit();
}

// Handle form submission
if (isset($_POST['login'])) {
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);

    // Initialize error message
    $errorMessage = '';

    // Validate inputs
    if (empty($email)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Email is required!
                        </div>";
    }

    if (empty($password)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Password is required!
                        </div>";
    }

    if (empty($errorMessage)) {
        try {
            // Check if customer exists in the database
            $stmt = $con->prepare("SELECT customer_id, name, email, phone, password FROM customers WHERE email = ?");
            $stmt->execute([$email]);
            $customer = $stmt->fetch();

            if ($customer && password_verify($password, $customer['password'])) {
                // Start session and store user details
                $_SESSION['customer_id'] = $customer['customer_id'];
                $_SESSION['name'] = $customer['name'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['customer_phone'] = $customer['phone'];

                // Redirect to the stored URL or default page
                $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'table-reservation.php';
                unset($_SESSION['redirect_url']); // Clear the redirect URL
                header('Location: ' . $redirect_url);
                exit();
            } else {
                $errorMessage .= "<div class='alert alert-danger' role='alert'>
                                    <strong>Error:</strong> Invalid email or password!
                                </div>";
            }
        } catch (PDOException $e) {
            $errorMessage .= "<div class='alert alert-danger' role='alert'>
                                <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "
                            </div>";
        }
    }

    if (!empty($errorMessage)) {
        echo $errorMessage;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <!-- Include your CSS here -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Include header if needed -->
    <?php include 'Includes/templates/header.php'; ?>
    <div class="containerr">
        <div class="login-container">
            <form method="POST" action="login.php" class="login-form">
                <h2>Customer Login</h2>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary">Log In</button>
                
                <p class="register-link">Don't have an account? <a href="customer_registration.php">Register here</a></p>
            </form>
        </div>   
    </div>

    <!-- Include footer if needed -->
    <?php include 'Includes/templates/footer.php'; ?>
</body>
</html>

<style>
    .containerr {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-container {
        padding: 20px;
    }

    h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: .25rem;
        position: relative;
        width: 80%;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .register-link {
        margin-top: 20px;
        text-align: center;
    }

    .register-link a {
        color: #007bff;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }
</style>
