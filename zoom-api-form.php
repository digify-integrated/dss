<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(28);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 28, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $zoom_api_id = $api->decrypt_data($id);

                $zoom_api_details = $api->get_zoom_api_details($zoom_api_id);
                $zoom_api_status = $zoom_api_details[0]['STATUS'];
                $transaction_log_id = $zoom_api_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                $zoom_api_id = null;
            }

            $add_zoom_api = $api->check_role_access_rights($username, '67', 'action');
            $update_zoom_api = $api->check_role_access_rights($username, '68', 'action');
            $delete_zoom_api = $api->check_role_access_rights($username, '69', 'action');
            $activate_zoom_api = $api->check_role_access_rights($username, '70', 'action');
            $deactivate_zoom_api = $api->check_role_access_rights($username, '71', 'action');

            if($update_zoom_api > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Zoom API Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="zoom-api.php">Zoom API</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($zoom_api_id)){
                                                    echo '<li class="breadcrumb-item" id="zoom-api-id"><a href="javascript: void(0);">'. $zoom_api_id .'</a></li>';
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
                                        <form id="zoom-api-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Zoom API Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($zoom_api_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_zoom_api > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="zoom-api-form.php">Add Zoom API</a>';
                                                                        }
        
                                                                        if($delete_zoom_api > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-zoom-api-id="'. $zoom_api_id .'" id="delete-zoom-api">Delete Zoom API</button>';
                                                                        }
        
                                                                        if(($activate_zoom_api > 0 && $zoom_api_status == 2) || ($deactivate_zoom_api > 0 && $zoom_api_status == 1)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($activate_zoom_api > 0 && $zoom_api_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-zoom-api-id="'. $zoom_api_id .'" id="activate-zoom-api">Activate Zoom API</button>';
                                                                            }
            
                                                                            if($deactivate_zoom_api > 0 && $zoom_api_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-zoom-api-id="'. $zoom_api_id .'" id="deactivate-zoom-api">Deactivate Zoom API</button>';
                                                                            }
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($zoom_api_id) && $add_zoom_api > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($zoom_api_id) && $update_zoom_api > 0){
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
                                                                else if(!empty($zoom_api_id) && $update_zoom_api <= 0){
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
                                            <?php
                                                if(!empty($zoom_api_id)){
                                                    echo '<div class="row">
                                                            <div class="col-md-12" id="zoom_api_status"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <input type="hidden" id="zoom_api_id" name="zoom_api_id" value="<?php echo $zoom_api_id; ?>">
                                                <?php
                                                    if(empty($zoom_api_id) && $add_zoom_api > 0){
                                                        echo '<div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="zoom_api_name" class="col-md-3 col-form-label">Zoom API <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="zoom_api_name" name="zoom_api_name" maxlength="100" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="api_key" class="col-md-3 col-form-label">API Key <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="api_key" name="api_key" maxlength="1000" '. $disabled .'>
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
                                                                        <label for="api_secret" class="col-md-3 col-form-label">API Secret <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="api_secret" name="api_secret" maxlength="1000" '. $disabled .'>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                    }
                                                    else if(!empty($zoom_api_id) && $update_zoom_api > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                    <label for="zoom_api_name" class="col-md-3 col-form-label">Zoom API <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="zoom_api_name_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="zoom_api_name" name="zoom_api_name" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="api_key" class="col-md-3 col-form-label">API Key <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="api_key_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="api_key" name="api_key" maxlength="1000" '. $disabled .'>
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
                                                                <div class="row mb-4">
                                                                    <label for="api_secret" class="col-md-3 col-form-label">API Secret <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="api_secret_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="api_secret" name="api_secret" maxlength="1000" '. $disabled .'>
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
        <script src="assets/js/pages/zoom-api-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>