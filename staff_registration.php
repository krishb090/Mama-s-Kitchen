<?php
$pageTitle = 'Staff Registration';
include "Includes/templates/navbar.php";
include 'connect.php'; 
include 'Includes/functions/functions.php'; 
include 'Includes/templates/header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username  = $_POST['username'];
    $full_name= $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $stmt = $con->prepare("INSERT INTO users (username, full_name, email, password, role) VALUES (:username,:full_name,:email, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            echo '<div class="message success">User registered successfully!</div>';
        } else {
            echo '<div class="message error">Error registering user.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="message error">Error: ' . $e->getMessage() . '</div>';
    }
}
?>
 <div class="containerr">
<form action="staff_registration.php" method="post">
    <label for="username">User Name:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name " name="full_name" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
    </select><br>

    <input type="submit" value="Register">
</form>
 </div>
 <?php include 'Includes/templates/footer.php'; ?>
 <style>
   .containerr {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f7f7f7;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.containerr label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.containerr input[type="text"],
.containerr input[type="email"],
.containerr input[type="password"],
.containerr select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.containerr input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #5cb85c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.containerr input[type="submit"]:hover {
    background-color: #4cae4c;
}
/* Container for messages */
.message {
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
    font-family: Arial, sans-serif;
    font-size: 16px;
    text-align: center;
}

/* Success message styling */
.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Error message styling */
.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}


</style>
