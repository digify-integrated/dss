<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(10);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 10, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $user_id = $api->decrypt_data($id);
            }
            else{
                $user_id = null;
            }

            $add_user_account = $api->check_role_access_rights($username, '20', 'action');
            $update_user_account = $api->check_role_access_rights($username, '21', 'action');
            $delete_user_account = $api->check_role_access_rights($username, '22', 'action');
            $lock_user_account = $api->check_role_access_rights($username, '75', 'action');
            $unlock_user_account = $api->check_role_access_rights($username, '76', 'action');
            $activate_user_account = $api->check_role_access_rights($username, '77', 'action');
            $deactivate_user_account = $api->check_role_access_rights($username, '78', 'action');

            if($update_user_account > 0){
                $disabled = null;
            }
            else{
                $disabled = 'disabled';
            }

            require('views/_interface_settings.php');
        }
    }
    else{
        header('location: logout.php?logout');
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <?php require('views/_preloader.php'); ?>

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
                                    <h4 class="mb-sm-0 font-size-18">User Account Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Administration</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($user_id)){
                                                    echo '<li class="breadcrumb-item" id="user-id"><a href="javascript: void(0);">'. $user_id .'</a></li>';
                                                }
                                            ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="role-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">User Account Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if(!empty($user_id)){
                                                                $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';

                                                                if($add_user_account > 0){
                                                                    $dropdown_action .= '<a class="dropdown-item" href="role-form.php">Add User Account</a>';
                                                                }

                                                                if($delete_user_account > 0){
                                                                    $dropdown_action .= '<button class="dropdown-item" type="button" data-user-id="'. $user_id .'" id="delete-role">Delete User Account</button>';
                                                                }

                                                                if(($add_user_account_module_access > 0 || $add_user_account_page_access > 0 || $add_user_account_action_access > 0 || $add_user_account_user_account > 0) && $update_user_account > 0){
                                                                    $dropdown_action .= '<div class="dropdown-divider"></div>';

                                                                    if($add_user_account_module_access > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-module-access">Add Module Access</button>';
                                                                    }                                                                    

                                                                    if($add_user_account_page_access > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-page-access">Add Page Access</button>';
                                                                    }                                                                    

                                                                    if($add_user_account_action_access > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-action-access">Add Action Access</button>';
                                                                    }                                                                    

                                                                    if($add_user_account_user_account > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-user-account">Add User Account</button>';
                                                                    }                                                                    
                                                                }

                                                                $dropdown_action .= '</div>
                                                                </div>';

                                                                echo $dropdown_action;
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(($add_user_account > 0 || ($update_user_account > 0 && !empty($user_id)))){
                                                                    echo '<button type="submit" for="page-form" id="submit-data" class="btn btn-primary w-sm">Save</button>';
                                                                }
                                                            ?>
                                                            <button type="button" id="discard" class="btn btn-outline-danger w-sm">Discard</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="role" class="col-md-3 col-form-label">Full Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="file_as" name="file_as" maxlength="300" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="user_id" class="col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="user_id" name="user_id" maxlength="5" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="password" class="col-md-3 col-form-label">Password</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group auth-pass-inputgroup">
                                                                <input type="password" id="password" name="password" class="form-control" aria-label="Password" aria-describedby="password-addon" <?php echo $disabled; ?>>
                                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($user_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#user-account-details" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-cubes"></i></span>
                                                                    <span class="d-none d-sm-block">Details</span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#user-account-role" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                                    <span class="d-none d-sm-block">Role</span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#transaction-log" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                                    <span class="d-none d-sm-block">Transaction Log</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="user-account-details" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="user-account-role" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="user-account-role-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Log Type</th>
                                                                                    <th class="all">Log</th>
                                                                                    <th class="all">Log Date</th>
                                                                                    <th class="all">Log By</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="transaction-log" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="transaction-log-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Log Type</th>
                                                                                    <th class="all">Log</th>
                                                                                    <th class="all">Log Date</th>
                                                                                    <th class="all">Log By</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        ?>
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
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/role-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>