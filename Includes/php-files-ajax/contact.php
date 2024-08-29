<?php
	
    include '../functions/functions.php';
     include '../../connect.php';
    
     if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {

        $contact_name = test_input($_POST['name']);
        $contact_email = test_input($_POST['email']);
        $contact_subject = test_input($_POST['subject']);
        $contact_message = test_input($_POST['message']);
        $contact_phone = test_input($_POST['phone']);
        try {
            // Prepare an SQL statement
            $stmt = $con->prepare("INSERT INTO contact_submissions (contact_name, contact_email, contact_subject, contact_message, phone) VALUES (:contact_name, :contact_email, :contact_subject, :contact_message, :phone)");
        
        // Bind parameters
        $stmt->bindParam(':contact_name', $contact_name);
        $stmt->bindParam(':contact_email', $contact_email);
        $stmt->bindParam(':contact_subject', $contact_subject);
        $stmt->bindParam(':contact_message', $contact_message);
        $stmt->bindParam(':phone', $contact_phone);
            // Execute the statement
            $stmt->execute();
    
            // Display success message
            echo json_encode([
                'status' => 'success',
                'message' => 'The message has been sent successfully'
            ]);
    
        } catch (PDOException $e) {
            // Display error message
            echo json_encode([
                'status' => 'error',
                'message' => 'A problem occurred while trying to send the message. Please try again!'
            ]);
        }
    } else {
        // Handle case where the form data is not set
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all required fields.'
        ]);
    }
    
    ?>
    