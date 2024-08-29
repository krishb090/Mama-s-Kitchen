<?php
    ob_start();
    session_start();

    $pageTitle = 'Contact Submissions';

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
            vertical_menu.getElementsByClassName('contact_submissions_link')[0].className += " active_link";
        </script>

        <style type="text/css">
            .contact-submissions-table {
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

        if($do == "Manage")
        {
            $stmt = $con->prepare("SELECT * FROM contact_submissions");
            $stmt->execute();
            $contact_submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            ?>
            <div class="card">
                <div class="card-header">
                    <?php echo $pageTitle; ?>
                </div>
                <div class="card-body">

                    <!-- ADD NEW CONTACT SUBMISSION BUTTON -->
             

                    <!-- CONTACT SUBMISSIONS TABLE -->
                    <table class="table table-bordered contact-submissions-table">
                        <thead>
                            <tr>
                            <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Message</th>A
                                <th scope="col">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($contact_submissions as $submission)
                                {
                                    echo "<tr>";
                                    echo "<td>";
                                    echo htmlspecialchars($submission['id']); 
                                echo "</td>";
                                        echo "<td>";
                                            echo htmlspecialchars($submission['contact_name']);
                                        echo "</td>";
                                        echo "<td>";
                                            echo htmlspecialchars($submission['contact_email']);
                                        echo "</td>";
                                        echo "<td>";
                                        echo htmlspecialchars($submission['phone']);
                                    echo "</td>";
                                        echo "<td>";
                                            echo htmlspecialchars($submission['contact_message']);
                                        echo "</td>";
                                        echo "<td>";
                                            $delete_data = "delete_".$submission["id"];
                                            $view_data = "view_".$submission["id"];
                                            ?>
                                                <ul class="list-inline m-0">

                                                    <!-- VIEW BUTTON -->
                                                    <li class="list-inline-item" data-toggle="tooltip" title="View">
                                                        <button class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $view_data; ?>" data-placement="top">
                                                            <i class="fa fa-eye"></i>
                                                        </button>

                                                        <!-- VIEW Modal -->
                                                        <div class="modal fade" id="<?php echo $view_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $view_data; ?>" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                    <p><strong>ID:</strong> <?php echo htmlspecialchars($submission['id']); ?></p>
                                                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($submission['contact_name']); ?></p>
                                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['contact_email']); ?></p>
                                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($submission['phone']); ?></p>

                                                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($submission['contact_subject']); ?></p>
                                                                        <p><strong>Message:</strong> <?php echo htmlspecialchars($submission['contact_message']); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <!-- EDIT BUTTON -->
                                                    <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                        <button class="btn btn-success btn-sm rounded-0">
                                                            <a href="contact_submissions.php?do=Edit&id=<?php echo htmlspecialchars($submission['id']); ?>" style="color: white;">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </button>
                                                    </li>

                                                    <!-- DELETE BUTTON -->
                                                    <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                        <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i>
                                                        </button>

                                                        <!-- DELETE Modal -->
                                                        <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Delete Contact Submission</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete this contact submission?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="button" data-id="<?php echo htmlspecialchars($submission['id']); ?>" class="btn btn-danger delete_submission">Delete</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            <?php
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.delete_submission').forEach(button => {
                        button.addEventListener('click', function () {
                            let submissionId = this.getAttribute('data-id');
                            swal({
                                title: "Are you sure?",
                                text: "Once deleted, you will not be able to recover this contact submission!",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) => {
                                if (willDelete) {
                                    window.location.href = 'contact_submissions.php?do=Delete&id=' + submissionId;
                                }
                            });
                        });
                    });
                });
            </script>

        <?php

        }
        elseif($do == "Add")
        {
            // Add contact submission functionality here
        }
        elseif($do == "Edit")
        {
            // Edit contact submission functionality here
        }
        elseif($do == "Delete")
        {
            if(isset($_GET['id']))
            {
                $submission_id = intval($_GET['id']);
                $stmt = $con->prepare("DELETE FROM contact_submissions WHERE id = ?");
                $stmt->execute([$submission_id]);

                header('Location: contact_submissions.php');
            }
        }

        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }
?>
