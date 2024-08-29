
    
    

<?php
session_start();

// Set page title
$pageTitle = 'Table Reservation';

// Include database connection and functions
include 'connect.php';
include 'Includes/functions/functions.php';
include 'Includes/templates/header.php';
include 'Includes/templates/navbar.php';
if (!isset($_SESSION['customer_id'])) {
    // Store the current page URL to redirect back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}


// Function to check table availability
function isTableAvailable($con, $table_id, $desired_date_time) {
    $stmt = $con->prepare("SELECT COUNT(*) FROM reservations WHERE table_id = ? AND selected_time = ?");
    $stmt->execute([$table_id, $desired_date_time]);
    $count = $stmt->fetchColumn();
    return $count == 0;
}
?>
    
    <style type="text/css">
        .table_reservation_section
        {
            max-width: 850px;
            margin: 50px auto;
            min-height: 500px;
        }

        .check_availability_submit
        {
            background: #ffc851;
            color: white;
            border-color: #ffc851;
            font-family: work sans,sans-serif;
        }
        .client_details_tab  .form-control
        {
            background-color: #fff;
            border-radius: 0;
            padding: 25px 10px;
            box-shadow: none;
            border: 2px solid #eee;
        }

        .client_details_tab  .form-control:focus 
        {
            border-color: #ffc851;
            box-shadow: none;
            outline: none;
        }
        .text_header
        {
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.5;
            margin-top: 22px;
            text-transform: capitalize;
        }
        .layer
        {
            height: 100%;
        background: -moz-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
    background: -webkit-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
    background: linear-gradient(to bottom, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
        }
/* Error Message Container */
.error_div {
    background-color: #f8d7da; /* Light red background */
    color: #721c24;            /* Dark red text color */
    border: 1px solid #f5c6cb; /* Light red border */
    border-radius: 5px;       /* Rounded corners */
    padding: 20px;            /* Padding inside the container */
    margin: 15px 0;           /* Margin above and below the container */
    font-family: Arial, sans-serif; /* Font style */
    font-size: 16px;          /* Font size */
    text-align: center;       /* Center align the text */
}



/* Error Message Text */
.error_message {
    display: inline-block;    /* Make the text inline-block to align with the icon */
    vertical-align: middle;   /* Vertically align the text with the icon */
    line-height: 1.5;         /* Line height for better readability */
}
    </style>

<!-- START ORDER FOOD SECTION -->
<section style="
background: url(Design/images/food_pic.jpg);
background-position: center bottom;
background-repeat: no-repeat;
background-size: cover;">
    <div class="layer">
        <div style="text-align: center; padding: 15px;">
            <h1 style="font-size: 120px; color: white; font-family: 'Roboto'; font-weight: 100;">
                Book a Table
            </h1>
        </div>
    </div>
</section>

<section class="table_reservation_section">
    <div class="container">
        <?php
        if (isset($_POST['submit_table_reservation_form']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected_date = $_POST['selected_date'];
            $selected_time = $_POST['selected_time'];
            $desired_date_time = $selected_date . " " . $selected_time;
            $number_of_guests = $_POST['number_of_guests'];
            $table_id = $_POST['table_id'];

            $client_full_name = test_input($_POST['client_full_name']);
            $client_phone_number = test_input($_POST['client_phone_number']);
            $client_email = test_input($_POST['client_email']);

            // Check if the table is available
            if (!isTableAvailable($con, $table_id, $desired_date_time)) {
                echo "<div class='alert alert-danger'>Sorry, the selected table is already reserved for the chosen time slot.</div>";
            } else {
                $con->beginTransaction();
                try {
                    // Insert client details
                    $stmtClient = $con->prepare("INSERT INTO clients (client_name, client_phone, client_email) VALUES (?, ?, ?)");
                    $stmtClient->execute([$client_full_name, $client_phone_number, $client_email]);
                    $client_id = $con->lastInsertId();  // Fetch last inserted client ID

                    // Insert reservation
                    $stmt_reservation = $con->prepare("INSERT INTO reservations (date_created, client_id, selected_time, nbr_guests, table_id) VALUES (?, ?, ?, ?, ?)");
                    $stmt_reservation->execute([date("Y-m-d H:i"), $client_id, $desired_date_time, $number_of_guests, $table_id]);

                    $con->commit();

                    echo "<div class='alert alert-success'>Great! Your Reservation has been created successfully.</div>";
                } catch (Exception $e) {
                    $con->rollBack();
                    echo "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
                }
            }
        }
        ?>

        <div class="text_header">
            <span>1. Select Date & Time</span>
        </div>
        <form method="POST" action="table-reservation.php">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="reservation_date">Date</label>
                        <input type="date" min="<?php echo date('Y-m-d', strtotime("+1 day")); ?>" 
                               value="<?php echo isset($_POST['reservation_date']) ? htmlspecialchars($_POST['reservation_date']) : date('Y-m-d', strtotime("+1 day")); ?>"
                               class="form-control" name="reservation_date">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="reservation_time">Time</label>
                        <input type="time" value="<?php echo isset($_POST['reservation_time']) ? htmlspecialchars($_POST['reservation_time']) : date('H:i'); ?>" class="form-control" name="reservation_time">
                    </div>
                </div> 
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="number_of_guests">How many people?</label>
                        <select class="form-control" name="number_of_guests">
                            <option value="1" <?php echo (isset($_POST['number_of_guests']) && $_POST['number_of_guests'] == 1) ? "selected" : ""; ?>>One person</option>
                            <option value="2" <?php echo (isset($_POST['number_of_guests']) && $_POST['number_of_guests'] == 2) ? "selected" : ""; ?>>Two people</option>
                            <option value="3" <?php echo (isset($_POST['number_of_guests']) && $_POST['number_of_guests'] == 3) ? "selected" : ""; ?>>Three people</option>
                            <option value="4" <?php echo (isset($_POST['number_of_guests']) && $_POST['number_of_guests'] == 4) ? "selected" : ""; ?>>Four people</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="check_availability" style="visibility: hidden;">Check Availability</label>
                        <input type="submit" class="form-control check_availability_submit" name="check_availability_submit" value="Check Availability">
                    </div>
                </div>
            </div>
        </form>

        <!-- CHECKING AVAILABILITY OF TABLES -->
        <?php
        if (isset($_POST['check_availability_submit'])) {
            $selected_date = $_POST['reservation_date'];
            $selected_time = $_POST['reservation_time'];
            $desired_date_time = $selected_date . " " . $selected_time;
            $number_of_guests = $_POST['number_of_guests'];

            // Query to check available tables at the specific time slot
            $stmt = $con->prepare("SELECT table_id FROM tables WHERE table_id NOT IN (
                                    SELECT t.table_id
                                    FROM tables t
                                    JOIN reservations r ON t.table_id = r.table_id
                                    WHERE DATE(r.selected_time) = ? AND r.selected_time = ?
                                    AND r.liberated = 0 AND r.canceled = 0
                                  )");

            $stmt->execute([$selected_date, $desired_date_time]);
            $rows = $stmt->fetchAll();

            if (empty($rows)) {
                ?>
                <div class="error_div">
                    <span class="error_message" style="font-size: 16px">ALL TABLES ARE RESERVED FOR THIS TIME SLOT</span>
                </div>     
                <?php
            } else {
                $table_id = $rows[0]['table_id'];  // Select the first available table
                ?>
                <div class="text_header">
                    <span>2. Reserve Your Table</span>
                </div>
                <form method="POST" action="table-reservation.php">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="client_full_name">Full Name</label>
                                <input type="text" name="client_full_name" class="form-control" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="client_phone_number">Phone Number</label>
                                <input type="text" name="client_phone_number" class="form-control" value="<?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="client_email">Email</label>
                                <input type="email" name="client_email" class="form-control" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="hidden" name="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                            <input type="hidden" name="selected_time" value="<?php echo htmlspecialchars($selected_time); ?>">
                            <input type="hidden" name="number_of_guests" value="<?php echo htmlspecialchars($number_of_guests); ?>">
                            <input type="hidden" name="table_id" value="<?php echo htmlspecialchars($table_id); ?>">
                            <input type="submit" class="form-control check_availability_submit" name="submit_table_reservation_form" value="Reserve Your Table">
                        </div>
                    </div>
                </form>
                <?php
            }
        }
        ?>
    </div>
</section>

<?php
include 'Includes/templates/footer.php';
?>
