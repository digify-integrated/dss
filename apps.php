<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    $page_title = 'Apps';

    #$page_access = $api->check_role_permissions($username, 1);
    $check_user_account_status = $api->check_user_account_status($username);

    if(!$check_user_account_status){
        header('location: logout.php?logout');
    }

    require('views/_interface_settings.php');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <?php require('views/_preloader.php'); ?>

        <div id="layout-wrapper">

            <?php 
                require('views/_top_bar.php');
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h3 class="mb-sm-0">Apps</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-20">Human Resource</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="employee.php">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-user-circle"></i>
                                            </div>
                                            <h6>Employees</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="time-in-time-out.php">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-time"></i>
                                            </div>
                                            <h6>Attendance</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="my-leave.php">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                            <h6>Leave Management</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="#">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-money"></i>
                                            </div>
                                            <h6>Payroll</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="approval-type.php">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-user-check"></i>
                                            </div>
                                            <h6>Approvals</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--<div class="col-sm-2">
                                <a class="dropdown-icon-item" href="#">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-user-plus"></i>
                                            </div>
                                            <h6>Recruitment</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="#">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-receipt"></i>
                                            </div>
                                            <h6>Expense</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="#">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-car"></i>
                                            </div>
                                            <h6>Fleet</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>-->
                            <div class="col-sm-2">
                                <a class="dropdown-icon-item" href="general-setting.php">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="font-size-24 text-primary mb-2">
                                                <i class="bx bx-wrench"></i>
                                            </div>
                                            <h6>Settings</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require('views/_footer.php'); ?>
            </div>

        </div>

        <?php require('views/_script.php'); ?>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/dashboard.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>
