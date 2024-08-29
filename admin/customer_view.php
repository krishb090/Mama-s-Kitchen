<?php
    ob_start();
    session_start();

    $pageTitle = 'Customer View';

    if (isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['role_restaurant_qRewacvAqzA'])) {
        include 'connect.php';
        include 'Includes/functions/functions.php';
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

        // Query to fetch customers within the selected date range
        $query = "SELECT * FROM customers WHERE 1=1";

        if ($start_date != '' && $end_date != '') {
            $query .= " AND registration_date BETWEEN '$start_date' AND '$end_date'";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get total count of customers
        $total_customers = count($customers);
        ?>

        <style type="text/css">
            .report-table {
                -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
            }
            .panel-X {
                border: 0;
                -webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                border-radius: .25rem;
                position: relative;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-color: #fff;
                background-clip: border-box;
                margin: auto;
                width: 600px;
            }
            .panel-header-X {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                padding-left: 1.25rem;
                padding-right: 1.25rem;
                border-bottom: 1px solid rgb(226, 226, 226);
            }
            .panel-header-X>.main-title {
                font-size: 18px;
                font-weight: 600;
                color: #313e54;
                padding: 15px 0;
            }
            .panel-body-X {
                padding: 1rem 1.25rem;
            }
        </style>

        <div class="card">
            <div class="card-header">
                <?php echo $pageTitle; ?>
            </div>
            <div class="card-body">

                <!-- FILTER FORM -->
                <form method="POST" action="customer_view.php">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </form>

                <!-- Total Customers -->
                <h5 class="mt-4">Total Customers: <?php echo $total_customers; ?></h5>

                <!-- REPORT TABLE -->
                <table class="table table-bordered report-table mt-4">
                    <thead>
                        <tr>
                            <th scope="col">Customer ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($customers as $customer) {
                                echo "<tr>";
                                echo "<td>".htmlspecialchars($customer['customer_id'])."</td>";
                                echo "<td>".htmlspecialchars($customer['name'])."</td>";
                                echo "<td>".htmlspecialchars($customer['email'])."</td>";
                                echo "<td>".htmlspecialchars($customer['phone'])."</td>";
                                echo "<td>".htmlspecialchars($customer['address'])."</td>";
                                echo "<td>".htmlspecialchars($customer['registration_date'])."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>

        <?php

        include 'Includes/templates/footer.php';
    } else {
        header('Location: login.php');
        exit();
    }
?>
