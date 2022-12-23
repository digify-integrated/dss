<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(22);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 22, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $notification_setting_id = $api->decrypt_data($id);
            }
            else{
                $notification_setting_id = null;
            }

            $add_notification_setting = $api->check_role_access_rights($username, '52', 'action');
            $update_notification_setting = $api->check_role_access_rights($username, '53', 'action');
            $delete_notification_setting = $api->check_role_access_rights($username, '54', 'action');
            $add_notification_role_recipient = $api->check_role_access_rights($username, '55', 'action');
            $add_notification_user_account_recipient = $api->check_role_access_rights($username, '57', 'action');
            $add_notification_channel = $api->check_role_access_rights($username, '59', 'action');

            if($update_notification_setting > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Notification Setting Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="notification-settings.php">Notification Settings</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($notification_setting_id)){
                                                    echo '<li class="breadcrumb-item" id="notification-setting-id"><a href="javascript: void(0);">'. $notification_setting_id .'</a></li>';
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
                                        <form id="notification-setting-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Notification Setting Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if(!empty($notification_setting_id)){
                                                                $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';

                                                                if($add_notification_setting > 0){
                                                                    $dropdown_action .= '<a class="dropdown-item" href="notification-setting-form.php">Add Notification Setting</a>';
                                                                }

                                                                if($delete_notification_setting > 0){
                                                                    $dropdown_action .= '<button class="dropdown-item" type="button" data-notification-setting-id="'. $notification_setting_id .'" id="delete-notification-setting">Delete Notification Setting</button>';
                                                                }

                                                                if(($add_notification_role_recipient > 0 || $add_notification_user_account_recipient > 0 || $add_notification_channel > 0) && $update_notification_setting > 0){
                                                                    $dropdown_action .= '<div class="dropdown-divider"></div>';

                                                                    if($add_notification_role_recipient > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-notification-role-recipient">Add Role Recipient</button>';
                                                                    }

                                                                    if($add_notification_user_account_recipient > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-notification-user-account-recipient">Add User Account Recipient</button>';
                                                                    }

                                                                    if($add_notification_channel > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item" type="button" id="add-notification-channel">Add Notification Channel</button>';
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
                                                                if(($add_notification_setting > 0 || ($update_notification_setting > 0 && !empty($notification_setting_id)))){
                                                                    echo '<button type="submit" for="page-form" id="submit-data" class="btn btn-primary w-sm">Save</button>';
                                                                }
                                                            ?>
                                                            <button type="button" id="discard" class="btn btn-outline-danger w-sm">Discard</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="notification_setting_id" name="notification_setting_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="notification_setting" class="col-md-3 col-form-label">Notification Setting <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="notification_setting" name="notification_setting" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="notification_title" class="col-md-3 col-form-label">Notification Title <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="notification_title" name="notification_title" maxlength="500" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="system_link" class="col-md-3 col-form-label">System Link</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="system_link" name="system_link" maxlength="200" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="description" class="col-md-3 col-form-label">Description <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="description" name="description" maxlength="200" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="notification_message" class="col-md-3 col-form-label">Notification Message <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="notification_message" name="notification_message" maxlength="500" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="email_link" class="col-md-3 col-form-label">Email Link</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="email_link" name="email_link" maxlength="200" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($notification_setting_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#role-recipients" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-users-cog"></i></span>
                                                                    <span class="d-none d-sm-block">Role Recipients</span>    
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#user-account-recipients" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-user"></i></span>
                                                                    <span class="d-none d-sm-block">User Account Recipients</span>    
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#notification-channel" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-bell"></i></span>
                                                                    <span class="d-none d-sm-block">Notification Channel</span>    
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
                                                            <div class="tab-pane active" id="role-recipients" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="notification-role-recipients-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Role</th>
                                                                                    <th class="all">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="user-account-recipients" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="notification-user-account-recipients-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">User Account</th>
                                                                                    <th class="all">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="notification-channel" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="notification-channel-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Notification Channel</th>
                                                                                    <th class="all">Action</th>
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
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/notification-setting-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>