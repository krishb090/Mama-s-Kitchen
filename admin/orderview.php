<?php
    ob_start();
    session_start();

    $pageTitle = 'Orders Overview';

    if (isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['role_restaurant_qRewacvAqzA']))    {
        include 'connect.php';
        include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        ?>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

        <script type="text/javascript">
            var vertical_menu = document.getElementById("vertical-menu");
            var current = vertical_menu.getElementsByClassName("active_link");
            if(current.length > 0) {
                current[0].classList.remove("active_link");   
            }
            vertical_menu.getElementsByClassName('orders_link')[0].className += " active_link";
        </script>

        <style type="text/css">
            .orders-table {
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

        <?php

        $do = '';

        if(isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add','Edit')))
            $do = $_GET['do'];
        else
            $do = 'Manage';

         
       
            if ($do == "Manage") {
                // Query to get order details
                $stmt = $con->prepare("
                    SELECT o.*, c.client_name, i.quantity, m.menu_name, m.menu_price
                    FROM placed_orders o
                    JOIN clients c ON o.client_id = c.client_id
                    JOIN in_order i ON o.order_id = i.order_id
                    JOIN menus m ON i.menu_id = m.menu_id
                    ORDER BY o.order_id, m.menu_name
                ");
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                // Initialize variables for grouping
                $groupedOrders = [];
                foreach ($orders as $order) {
                    $orderId = $order['order_id'];
                    if (!isset($groupedOrders[$orderId])) {
                        $groupedOrders[$orderId] = [
                            'order_id' => $orderId,
                            'client_name' => $order['client_name'],
                            'delivery_address' => $order['delivery_address'],
                            'order_time' => $order['order_time'],
                            'delivered' => $order['delivered'],
                            'canceled' => $order['canceled'],
                            'cancellation_reason' => $order['cancellation_reason'],
                            'items' => [],
                            'total_price' => 0,
                        ];
                    }
                    // Aggregate items and prices
                    $groupedOrders[$orderId]['items'][] = $order['menu_name'];
                    $groupedOrders[$orderId]['total_price'] += $order['menu_price'];
                }
                ?>
            
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">
                        <!-- TOTAL ORDERS DISPLAY -->
                        <div class="total-orders mb-4">
                            <h5>Total Orders Placed: <span class="text-primary"><?php echo htmlspecialchars(count($groupedOrders)); ?></span></h5>
                        </div>
            
                        <!-- ORDERS TABLE -->
                        <table class="table table-bordered orders-table">
                            <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Order Items</th>
                                    <th scope="col">Item Price</th>
                                    <th scope="col">Order Time</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Delivery Address</th>
                                    <th scope="col">Delivered</th>
                                    <th scope="col">Canceled</th>
                                    <th scope="col">Cancellation Reason</th>
                                    <th scope="col">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($groupedOrders as $order) {
                                    $itemsStr = implode(', ', $order['items']);
                                    ?>
            
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                        <td><?php echo htmlspecialchars($itemsStr); ?></td>
                                        <td><?php echo htmlspecialchars('$ ' . number_format($order['total_price'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_time']); ?></td>
                                        <td><?php echo htmlspecialchars($order['client_name']); ?></td>
                                        <td><?php echo htmlspecialchars($order['delivery_address']); ?></td>
                                        <td><?php echo $order['delivered'] ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo $order['canceled'] ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo htmlspecialchars($order['cancellation_reason']); ?></td>
                                        <td>
                                            <?php
                                            $delete_data = "delete_" . $order['order_id'];
                                            $view_data = "view_" . $order['order_id'];
                                            ?>
                                            <ul class="list-inline m-0">
                                                <!-- VIEW BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="View">
                                                    <button class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $view_data; ?>">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
            
                                                    <!-- VIEW Modal -->
                                                    <div class="modal fade" id="<?php echo $view_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $view_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                                                                    <p><strong>Order Items:</strong> <?php echo htmlspecialchars($itemsStr); ?></p>
                                                                    <p><strong>Item Prices:</strong> <?php echo htmlspecialchars('$ ' . number_format($order['total_price'], 2)); ?></p>
                                                                    <p><strong>Order Time:</strong> <?php echo htmlspecialchars($order['order_time']); ?></p>
                                                                    <p><strong>Client Name:</strong> <?php echo htmlspecialchars($order['client_name']); ?></p>
                                                                    <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                                                    <p><strong>Delivered:</strong> <?php echo $order['delivered'] ? 'Yes' : 'No'; ?></p>
                                                                    <p><strong>Canceled:</strong> <?php echo $order['canceled'] ? 'Yes' : 'No'; ?></p>
                                                                    <p><strong>Cancellation Reason:</strong> <?php echo htmlspecialchars($order['cancellation_reason']); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
            
                                                <!-- EDIT BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                    <button class="btn btn-success btn-sm rounded-0">
                                                        <a href="placed_orders.php?do=Edit&order_id=<?php echo htmlspecialchars($order['order_id']); ?>" style="color: white;">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </button>
                                                </li>
            
                                                <!-- DELETE BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>"><i class="fa fa-trash"></i></button>
            
                                                    <!-- DELETE Modal -->
                                                    <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Delete Order</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this order?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="button" data-id="<?php echo htmlspecialchars($order['order_id']); ?>" class="btn btn-danger delete_order">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            
<?php

        }
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }
?>
