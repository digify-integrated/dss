<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);
    
    if (!$check_user_account_status) {
        header('Location: logout.php?logout');
        exit();
    }
    
    $page_details = $api->get_page_details(29);
    $module_id = $page_details[0]['MODULE_ID'];
    $page_title = $page_details[0]['PAGE_NAME'];
    
    $page_access_right = $api->check_role_access_rights($username, 29, 'page');
    $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');
    
    if ($module_access_right == 0 || $page_access_right == 0) {
        header('Location: apps.php');
        exit();
    }
    
    $add_user_account = $api->check_role_access_rights($username, '72', 'action');
    $delete_user_account = $api->check_role_access_rights($username, '74', 'action');
    $lock_user_account = $api->check_role_access_rights($username, '75', 'action');
    $unlock_user_account = $api->check_role_access_rights($username, '76', 'action');
    $activate_user_account = $api->check_role_access_rights($username, '77', 'action');
    $deactivate_user_account = $api->check_role_access_rights($username, '78', 'action');
    
    require('views/_interface_settings.php');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link rel="stylesheet" type="text/css" href="assets/libs/toastr/build/toastr.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body data-topbar="dark" data-layout="horizontal">
        <div id="layout-wrapper">

            <?php 
                require('views/_top_bar.php');
                require('views/menu/_menu_technical.php');
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">User Accounts</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Administration</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1 align-self-center">
                                                        <h4 class="card-title">User Accounts List</h4>
                                                    </div>
                                                    <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if($delete_user_account > 0 || $lock_user_account > 0 || $unlock_user_account > 0 || $activate_user_account > 0 || $deactivate_user_account > 0){
                                                                $dropdown_action = '<div class="btn-group">
                                                                    <button type="button" class="btn btn-outline-dark dropdown-toggle d-none multiple-action" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                        <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">';
                                                                    
                                                                    if($delete_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple" type="button" id="delete-user-account">Delete User Account</button>';
                                                                    }
    
                                                                    if($lock_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-lock" type="button" id="lock-user-account">Lock User Account</button>';
                                                                    }
    
                                                                    if($unlock_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-unlock" type="button" id="unlock-user-account">Unlock User Account</button>';
                                                                    }
    
                                                                    if($activate_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-activate" type="button" id="activate-user-account">Activate User Account</button>';
                                                                    }
    
                                                                    if($deactivate_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-deactivate" type="button" id="deactivate-user-account">Deactivate User Account</button>';
                                                                    }

                                                                $dropdown_action .= '</div></div>';

                                                                echo $dropdown_action;
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <?php
                                                            if($add_user_account > 0){
                                                                echo '<a href="user-account-form.php" class="btn btn-primary">
                                                                    <span class="d-block d-sm-none"><i class="bx bx-plus"></i></span>
                                                                    <span class="d-none d-sm-block">Create</span>
                                                                </a>';
                                                            }
                                                        ?>
                                                       <button type="button" class="btn btn-info waves-effect waves-light" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas"><i class="bx bx-filter-alt"></i></span></button>
                                                    </div>
                                                </div>
                                                
                                                <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-off-canvas" data-bs-backdrop="true" aria-labelledby="filter-off-canvas-label">
                                                    <div class="offcanvas-header">
                                                        <h5 class="offcanvas-title" id="filter-off-canvas-label">Filter</h5>
                                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                    </div>
                                                    <div class="offcanvas-body">
                                                        <div class="mb-3">
                                                            <p class="text-muted">Password Expiry Date</p>

                                                            <div class="input-group mb-3" id="filter-start-date-container">
                                                                <input type="text" class="form-control" id="filter_start_date" name="filter_start_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#filter-start-date-container" data-provide="datepicker" data-date-autoclose="true" data-date-orientation="right" placeholder="Start Date">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>

                                                            <div class="input-group" id="filter-end-date-container">
                                                                <input type="text" class="form-control" id="filter_end_date" name="filter_end_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#filter-end-date-container" data-provide="datepicker" data-date-autoclose="true" data-date-orientation="right" placeholder="End Date">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <p class="text-muted">Last Connection Date</p>

                                                            <div class="input-group mb-3" id="filter-last-connection-start-date-container">
                                                                <input type="text" class="form-control" id="filter_last_connection_start_date" name="filter_last_connection_start_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#filter-last-connection-start-date-container" data-provide="datepicker" data-date-autoclose="true" data-date-orientation="right" placeholder="Start Date">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>

                                                            <div class="input-group" id="filter-last-connection-end-date-container">
                                                                <input type="text" class="form-control" id="filter_last_connection_end_date" name="filter_last_connection_end_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#filter-last-connection-end-date-container" data-provide="datepicker" data-date-autoclose="true" data-date-orientation="right" placeholder="End Date">
                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <p class="text-muted">User Account Status</p>

                                                            <select class="form-control filter-select2" id="filter_user_account_status">
                                                                <option value="">All</option>
                                                                <option value="INACTIVE">Inactive</option>
                                                                <option value="ACTIVE">Active</option>
                                                             </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <p class="text-muted">User Account Lock Status</p>

                                                            <select class="form-control filter-select2" id="filter_user_account_lock_status">
                                                                <option value="">All</option>
                                                                <option value="locked">Locked</option>
                                                                <option value="Unlocked">Unlocked</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-primary waves-effect waves-light" id="apply-filter" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas">Apply Filter</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="user-accounts-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                                                </div>
                                                            </th>
                                                            <th class="all">User Account</th>
                                                            <th class="all">Account Status</th>
                                                            <th class="all">Lock Status</th>
                                                            <th class="all">Password Expiry Date</th>
                                                            <th class="all">Last Connection Date</th>
                                                            <th class="all">View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require('views/_footer.php'); ?>
            </div>

        </div>

        <?php require('views/_script.php'); ?>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/user-accounts.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>