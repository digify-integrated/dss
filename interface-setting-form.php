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
                $transaction_log_id = $interface_setting_details[0]['TRANSACTION_LOG_ID'];
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
        <link rel="stylesheet" type="text/css" href="assets/libs/toastr/build/toastr.min.css">
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
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Interface Setting Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($interface_setting_id)){
                                                                    $dropdown_action = '<div class="btn-group form-details">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_interface_setting > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="interface-setting-form.php">Add Interface Setting</a>';
                                                                        }
        
                                                                        if($delete_interface_setting > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="delete-interface-setting">Delete Interface Setting</button>';
                                                                        }
        
                                                                        if(($activate_interface_setting > 0 && $interface_setting_status == 2) || ($deactivate_interface_setting > 0 && $interface_setting_status == 1)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($activate_interface_setting > 0 && $interface_setting_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="activate-interface-setting">Activate Interface Setting</button>';
                                                                            }
            
                                                                            if($deactivate_interface_setting > 0 && $interface_setting_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-interface-setting-id="'. $interface_setting_id .'" id="deactivate-interface-setting">Deactivate Interface Setting</button>';
                                                                            }
                                                                        }    

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($interface_setting_id) && $add_interface_setting > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($interface_setting_id) && $update_interface_setting > 0){
                                                                    echo '<button type="button" id="form-edit" class="btn btn-primary waves-effect waves-light form-details">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-edit"></i></span>
                                                                            <span class="d-none d-sm-block">Edit</span>
                                                                        </button>
                                                                        <button type="button" id="view-transaction-log" class="btn btn-info waves-effect waves-light form-details" data-bs-toggle="offcanvas" data-bs-target="#transaction-log-filter-off-canvas" aria-controls="transaction-log-filter-off-canvas">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-notepad"></i></span>
                                                                            <span class="d-none d-sm-block">Transaction Log</span>
                                                                        </button>
                                                                        <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light d-none form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard" class="btn btn-outline-danger waves-effect waves-light d-none form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($interface_setting_id) && $update_interface_setting <= 0){
                                                                    echo '<button type="button" id="view-transaction-log" class="btn btn-info waves-effect waves-light form-details" data-bs-toggle="offcanvas" data-bs-target="#transaction-log-filter-off-canvas" aria-controls="transaction-log-filter-off-canvas">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-notepad"></i></span>
                                                                            <span class="d-none d-sm-block">Transaction Log</span>
                                                                        </button>';
                                                                }
                                                            ?>
                                                        </div>
                                                        <?php require('views/_transaction_log_canvas.php'); ?>
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
                                                <input type="hidden" id="interface_setting_id" name="interface_setting_id" value="<?php echo $interface_setting_id; ?>">
                                                <?php
                                                    if(empty($interface_setting_id) && $add_interface_setting > 0){
                                                        echo '<div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <input type="hidden" id="transaction_log_id">
                                                                        <label for="interface_setting_name" class="col-md-3 col-form-label">Interface Setting <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="interface_setting_name" name="interface_setting_name" maxlength="100" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="login_background" class="col-md-3 col-form-label">Login Background</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control" type="file" name="login_background" id="login_background" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="login_logo" class="col-md-3 col-form-label">Login Logo</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control" type="file" name="login_logo" id="login_logo" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="description" class="col-md-3 col-form-label">Description <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="description" name="description" maxlength="200" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="menu_logo" class="col-md-3 col-form-label">Menu Logo</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control" type="file" name="menu_logo" id="menu_logo" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="favicon" class="col-md-3 col-form-label">Favicon</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control" type="file" name="favicon" id="favicon" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                    }
                                                    else if(!empty($interface_setting_id) && $update_interface_setting > 0){
                                                            echo '<div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                        <label for="interface_setting_name" class="col-md-3 col-form-label">Interface Setting <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <label class="col-form-label form-details" id="interface_setting_name_label"></label>
                                                                            <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="interface_setting_name" name="interface_setting_name" maxlength="100" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12 avatar-xl" id="login_background_image"></div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="login_background" class="col-md-3 col-form-label">Login Background</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control d-none form-edit" type="file" name="login_background" id="login_background" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12 avatar-xl" id="login_logo_image"></div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="login_logo" class="col-md-3 col-form-label">Login Logo</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control d-none form-edit" type="file" name="login_logo" id="login_logo" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="description" class="col-md-3 col-form-label">Description <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <label class="col-form-label form-details" id="description_label"></label>
                                                                            <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="description" name="description" maxlength="200" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12 avatar-xl" id="menu_logo_image"></div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="menu_logo" class="col-md-3 col-form-label">Menu Logo</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control d-none form-edit" type="file" name="menu_logo" id="menu_logo" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-12 avatar-xl" id="favicon_image"></div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="favicon" class="col-md-3 col-form-label">Favicon</label>
                                                                        <div class="col-md-9">
                                                                            <input class="form-control d-none form-edit" type="file" name="favicon" id="favicon" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                    }
                                                ?>
                                            </div>
                                        </form>
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
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/interface-setting-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>