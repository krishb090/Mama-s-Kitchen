<?php
    ob_start();
    session_start();

    $pageTitle = 'Sales Report';

    if (isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['role_restaurant_qRewacvAqzA'])) {
        include 'connect.php';
        include 'Includes/functions/functions.php';
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        ?>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script type="text/javascript">
            var vertical_menu = document.getElementById("vertical-menu");
            var current = vertical_menu.getElementsByClassName("active_link");
            if(current.length > 0) {
                current[0].classList.remove("active_link");
            }
            vertical_menu.getElementsByClassName('revenue_report_link')[0].className += " active_link";
        </script>

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
            .chart-container {
                width: 100%;
                height: 400px;
            }
        </style>

        <?php

        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $item_wise = isset($_POST['item_wise']) ? $_POST['item_wise'] : false;

        $query = "SELECT * FROM placed_orders WHERE 1=1";

        if ($start_date != '' && $end_date != '') {
            $query .= " AND order_time BETWEEN '$start_date' AND '$end_date'";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_revenue = 0;
        $item_data = [];

        foreach ($orders as $order) {
            if (isset($order['order_id'])) {
                $order_total = floatval($order['total_price']); // Ensure it's treated as a number
                $total_revenue += $order_total;

                if ($item_wise) {
                    $items_stmt = $con->prepare("SELECT menu_names, total_price FROM placed_orders WHERE order_id = ?");
                    $items_stmt->execute([$order['order_id']]);
                    $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($items as $item) {
                        $item_name = htmlspecialchars($item['menu_names']);
                        $item_price = floatval($item['total_price']);
                        if (!isset($item_data[$item_name])) {
                            $item_data[$item_name] = 0;
                        }
                        $item_data[$item_name] += $item_price;
                    }
                }
            }
        }

        ?>

        <div class="card">
            <div class="card-header">
                <?php echo $pageTitle; ?>
            </div>
            <div class="card-body">

                <!-- FILTER FORM -->
                <form method="POST" action="report.php">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="item_wise" name="item_wise" <?php echo $item_wise ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="item_wise">View Revenue by Item</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </form>

                <!-- Chart Container -->
                <?php if ($item_wise && !empty($item_data)): ?>
                    <div class="chart-container mt-4">
                        <canvas id="itemChart"></canvas>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var ctx = document.getElementById('itemChart').getContext('2d');
                            var itemData = <?php echo json_encode($item_data); ?>;

                            var labels = Object.keys(itemData);
                            var data = Object.values(itemData);

                            var chart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Revenue by Item',
                                        data: data,
                                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                                        borderColor: '#fff',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    var label = context.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    if (context.parsed !== null) {
                                                        label += '$' + context.parsed.toFixed(2);
                                                    }
                                                    return label;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php endif; ?>

                <!-- REPORT TABLE -->
                <table class="table table-bordered report-table mt-4">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Total Price</th>
                            <?php if ($item_wise): ?>
                                <th scope="col">Items</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($orders as $order) {
                                if (isset($order['order_id'])) {
                                    $order_total = floatval($order['total_price']); // Ensure it's treated as a number
                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($order['order_id'])."</td>";
                                    echo "<td>".htmlspecialchars($order['order_time'])."</td>";
                                    echo "<td>$".number_format($order_total, 2)."</td>"; // Format the price

                                    if ($item_wise) {
                                        $items_stmt = $con->prepare("SELECT menu_names, total_price FROM placed_orders WHERE order_id = ?");
                                        $items_stmt->execute([$order['order_id']]);
                                        $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

                                        echo "<td>";
                                        foreach ($items as $item) {
                                            echo htmlspecialchars($item['menu_names'])." - $".number_format(floatval($item['total_price']), 2)."<br>";
                                        }
                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                } else {
                                    echo "<tr><td colspan='4'>Invalid Order Data</td></tr>";
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="<?php echo $item_wise ? '3' : '2'; ?>">Total Revenue:</th>
                            <th>$<?php echo number_format($total_revenue, 2); ?></th>
                        </tr>
                    </tfoot>
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
