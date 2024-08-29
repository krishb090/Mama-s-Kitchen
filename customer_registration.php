<?php
session_start();
$pageTitle = 'Customer Registration';
include "Includes/templates/navbar.php";
include 'connect.php'; 
include 'Includes/functions/functions.php'; 
include 'Includes/templates/header.php'; 

if(isset($_POST['register_customer'])) {
    $name = test_input($_POST['customer_name']);
    $email = test_input($_POST['customer_email']);
    $phone = test_input($_POST['customer_phone']);
    $address = test_input($_POST['customer_address']);
    $password = test_input($_POST['customer_password']);
    $passwordConfirm = test_input($_POST['customer_password_confirm']);
    
    // Initialize error message
    $errorMessage = '';

    // Validate name
    if (empty($name)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Name is required!
                        </div>";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Invalid email address!
                        </div>";
    }

    // Validate phone number
    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Phone number must be 10 digits!
                        </div>";
    }

    // Validate address
    if (empty($address)) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Address is required!
                        </div>";
    }

    // Validate password
    if (empty($password) || strlen($password) < 6) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Password must be at least 6 characters long!
                        </div>";
    }

    // Validate password confirmation
    if ($password !== $passwordConfirm) {
        $errorMessage .= "<div class='alert alert-danger' role='alert'>
                            <strong>Error:</strong> Passwords do not match!
                        </div>";
    }

    if (!empty($errorMessage)) {
        echo $errorMessage;
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Check if email already exists
            $stmt = $con->prepare("SELECT email FROM customers WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                echo "<div class='alert alert-danger' role='alert'>
                        <strong>Error:</strong> Email already exists!
                    </div>";
            } else {
                // Insert new customer
                $stmt = $con->prepare("INSERT INTO customers (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $address, $hashedPassword]);
                
                echo "<div class='alert alert-success' role='alert'>
                        Registration successful!
                    </div>";
            }
        } catch (PDOException $e) {
            // Check for duplicate email error
            if ($e->getCode() == '23000') {
                $message = '<div class="alert alert-danger">Error: This email is already registered.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
    }
}}
?>


<!-- HTML form for customer registration -->
<div class="containerr">
    <h2 class="text-center">Customer Registration</h2>
    <?php if (isset($message)) { echo $message; } ?>
    <form method="POST" action="customer_registration.php">
        <div class="form-group">
            <label for="customer_name">Name:</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="form-group">
            <label for="customer_email">Email:</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
        </div>
        <div class="form-group">
            <label for="customer_phone">Phone Number:</label>
            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
        </div>
        <div class="form-group">
            <label for="customer_address">Address:</label>
            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="customer_password">Password:</label>
            <input type="password" class="form-control" id="customer_password" name="customer_password" required>
        </div>
        <div class="form-group">
            <label for="customer_password_confirm">Confirm Password:</label>
            <input type="password" class="form-control" id="customer_password_confirm" name="customer_password_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary" name="register_customer">Register</button>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<?php include 'Includes/templates/footer.php'; ?>

<style>
    .containerr {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

    .login-link {
        margin-top: 20px;
        text-align: center;
    }

    .login-link a {
        color: #007bff;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>
