<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(18);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 18, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $interface_setting_id = $api->decrypt_data($id);
                $interface_setting_details = $api->get_interface_setting_details($interface_setting_id);
                $interface_setting_status = $interface_setting_details[0]['STATUS'];
            }
            else{
                $interface_setting_id = null;
            }

            $add_interface_setting = $api->check_role_access_rights($username, '42', 'action');
            $update_interface_setting = $api->check_role_access_rights($username, '43', 'action');
            $delete_interface_setting = $api->check_role_access_rights($username, '44', 'action');
            $activate_interface_setting = $api->check_role_access_rights($username, '45', 'action');
            $deactivate_interface_setting = $api->check_role_access_rights($username, '46', 'action');

            if($update_interface_setting > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Interface Setting Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Technical</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="interface-settings.php">Interface Settings</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($interface_setting_id)){
                                                    echo '<li class="breadcrumb-item" id="interface-setting-id"><a href="javascript: void(0);">'. $interface_setting_id .'</a></li>';
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
                                        <form id="interface-setting-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="card-title flex-grow-1">Interface Setting Form</h4>
                                                        <div class="flex-shrink-0">
                                                            <?php
                                                                if(($add_interface_setting > 0 || ($update_interface_setting > 0 && !empty($interface_setting_id)))){
                                                                    echo '<button type="submit" for="page-form" id="submit-data" class="btn btn-primary w-sm">Save</button>';
                                                                }
                                                            ?>
                                                            <button type="button" id="discard" class="btn btn-outline-danger"><i class="bx bx-trash font-size-16 align-middle"></i></button>
                                                            <?php
                                                                if(!empty($interface_setting_id)){
                                                                    $dropdown_action = '<div class="dropdown d-inline-block">
                                                                    <button type="menu" class="btn btn-success" id="action_menu" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                                                                    <ul class="dropdown-menu" aria-labelledby="action_menu">';

                                                                    if($add_interface_setting > 0){
                                                                        $dropdown_action .= '<li><a class="dropdown-item" href="interface-setting-form.php">Add Interface Setting</a></li>';
                                                                    }
    
                                                                    if($delete_interface_setting > 0){
                                                                        $dropdown_action .= '<li><button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="delete-interface-setting">Delete Interface Setting</button></li>';
                                                                    }
    
                                                                    if(($activate_interface_setting > 0 && $interface_setting_status == 2) || ($deactivate_interface_setting > 0 && $interface_setting_status == 1)){
                                                                        $dropdown_action .= '<li><div class="dropdown-divider"></div></li>';
    
                                                                        if($activate_interface_setting > 0 && $interface_setting_status == 2){
                                                                            $dropdown_action .= '<li><button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="activate-interface-setting">Activate Interface Setting</button></li>';
                                                                        }
        
                                                                        if($deactivate_interface_setting > 0 && $interface_setting_status == 1){
                                                                            $dropdown_action .= '<li><button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="deactivate-interface-setting">Deactivate Interface Setting</button></li>';
                                                                        }
                                                                    }    

                                                                    $dropdown_action .= ' </ul>
                                                                    </div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if(!empty($interface_setting_id)){
                                                    echo '<div class="row">
                                                            <div class="col-md-12" id="interface_setting_status"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="interface_setting_id" name="interface_setting_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="interface_setting_name" class="col-md-3 col-form-label">Interface Setting <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="interface_setting_name" name="interface_setting_name" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if(!empty($interface_setting_id)){
                                                            echo '<div class="row mt-4">
                                                                    <div class="col-md-12" id="login_background_image"></div>
                                                                </div>';
                                                        }
                                                    ?>
                                                    <div class="row mb-4">
                                                        <label for="login_background" class="col-md-3 col-form-label">Login Background</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="login_background" id="login_background" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if(!empty($interface_setting_id)){
                                                            echo '<div class="row mt-4">
                                                                    <div class="col-md-12" id="login_logo_image"></div>
                                                                </div>';
                                                        }
                                                    ?>
                                                    <div class="row mb-4">
                                                        <label for="login_logo" class="col-md-3 col-form-label">Login Logo</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="login_logo" id="login_logo" <?php echo $disabled; ?>>
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
                                                    <?php
                                                        if(!empty($interface_setting_id)){
                                                            echo '<div class="row mt-4">
                                                                    <div class="col-md-12" id="menu_logo_image"></div>
                                                                </div>';
                                                        }
                                                    ?>
                                                    <div class="row mb-4">
                                                        <label for="menu_logo" class="col-md-3 col-form-label">Menu Logo</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="menu_logo" id="menu_logo" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if(!empty($interface_setting_id)){
                                                            echo '<div class="row mt-4">
                                                                    <div class="col-md-12" id="favicon_image"></div>
                                                                </div>';
                                                        }
                                                    ?>
                                                    <div class="row mb-4">
                                                        <label for="favicon" class="col-md-3 col-form-label">Favicon</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="favicon" id="favicon" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($interface_setting_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#transaction-log" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                                    <span class="d-none d-sm-block">Transaction Log</span>    
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="transaction-log" role="tabpanel">
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
        <script src="assets/js/pages/interface-setting-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>