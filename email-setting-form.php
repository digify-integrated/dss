<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(20);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 20, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $email_setting_id = $api->decrypt_data($id);
                $email_setting_details = $api->get_email_setting_details($email_setting_id);
                $email_setting_status = $email_setting_details[0]['STATUS'];
                $transaction_log_id = $email_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                $email_setting_id = null;
            }

            $add_email_setting = $api->check_role_access_rights($username, '47', 'action');
            $update_email_setting = $api->check_role_access_rights($username, '48', 'action');
            $delete_email_setting = $api->check_role_access_rights($username, '49', 'action');
            $activate_email_setting = $api->check_role_access_rights($username, '50', 'action');
            $deactivate_email_setting = $api->check_role_access_rights($username, '51', 'action');

            if($update_email_setting > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Email Setting Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="email-settings.php">Email Settings</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($email_setting_id)){
                                                    echo '<li class="breadcrumb-item" id="email-setting-id"><a href="javascript: void(0);">'. $email_setting_id .'</a></li>';
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
                                        <form id="email-setting-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Email Setting Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($email_setting_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_email_setting > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="email-setting-form.php">Add Email Setting</a>';
                                                                        }
        
                                                                        if($delete_email_setting > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-email-setting-id="'. $email_setting_id .'" id="delete-email-setting">Delete Email Setting</button>';
                                                                        }
        
                                                                        if(($activate_email_setting > 0 && $email_setting_status == 2) || ($deactivate_email_setting > 0 && $email_setting_status == 1)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
                                                                            
                                                                            if($activate_email_setting > 0 && $email_setting_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-email-setting-id="'. $email_setting_id .'" id="activate-email-setting">Activate Email Setting</button>';
                                                                            }
            
                                                                            if($deactivate_email_setting > 0 && $email_setting_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-email-setting-id="'. $email_setting_id .'" id="deactivate-email-setting">Deactivate Email Setting</button>';
                                                                            }
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($email_setting_id) && $add_email_setting > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($email_setting_id) && $update_email_setting > 0){
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
                                                                else if(!empty($email_setting_id) && $update_email_setting <= 0){
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
                                                if(!empty($email_setting_id)){
                                                    echo '<div class="row mt-2">
                                                            <div class="col-md-12" id="email_setting_status"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <input type="hidden" id="email_setting_id" name="email_setting_id" value="<?php echo $email_setting_id; ?>">
                                                <?php
                                                    if(empty($email_setting_id) && $add_email_setting > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="email_setting_name" class="col-md-3 col-form-label">Email Setting <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="email_setting_name" name="email_setting_name" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_host" class="col-md-3 col-form-label">Mail Host <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="mail_host" name="mail_host" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_username" class="col-md-3 col-form-label">Mail Username <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="mail_username" name="mail_username" maxlength="200" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_encryption" class="col-md-3 col-form-label">Mail Encryption <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <select class="form-control select2" id="mail_encryption" name="mail_encryption" '. $disabled .'>
                                                                            <option value="">--</option>
                                                                            '. $api->generate_system_code_options('MAILENCRYPTION') .'
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="smtp_auth" class="col-md-3 col-form-label">SMTP Authentication</label>
                                                                    <div class="col-md-9">
                                                                        <select class="form-control select2" id="smtp_auth" name="smtp_auth" '. $disabled .'>
                                                                            <option value="0">False</option>
                                                                            <option value="1">True</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_from_name" class="col-md-3 col-form-label">Mail From Name <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="mail_from_name" name="mail_from_name" maxlength="200" '. $disabled .'>
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
                                                                    <label for="port" class="col-md-3 col-form-label">Port</label>
                                                                    <div class="col-md-9">
                                                                        <input id="port" name="port" class="form-control" type="number" min="0" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_password" class="col-md-3 col-form-label">Mail Password <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <div class="input-group auth-pass-inputgroup">
                                                                            <input type="password" id="mail_password" name="mail_password" class="form-control" aria-label="Password" aria-describedby="password-addon" '. $disabled .'>
                                                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="smtp_auto_tls" class="col-md-3 col-form-label">SMTP Auto TLS</label>
                                                                    <div class="col-md-9">
                                                                        <select class="form-control select2" id="smtp_auto_tls" name="smtp_auto_tls" '. $disabled .'>
                                                                            <option value="0">False</option>
                                                                            <option value="1">True</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_from_email" class="col-md-3 col-form-label">Mail From Email <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="mail_from_email" name="mail_from_email" maxlength="200" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                    else if(!empty($email_setting_id) && $update_email_setting > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                    <label for="email_setting_name" class="col-md-3 col-form-label">Email Setting <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="email_setting_name_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="email_setting_name" name="email_setting_name" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_host" class="col-md-3 col-form-label">Mail Host <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="mail_host_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="mail_host" name="mail_host" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_username" class="col-md-3 col-form-label">Mail Username <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="mail_username_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="mail_username" name="mail_username" maxlength="200" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_encryption" class="col-md-3 col-form-label">Mail Encryption <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="mail_encryption_label"></label>
                                                                        <div class="d-none form-edit">
                                                                            <select class="form-control select2" id="mail_encryption" name="mail_encryption" '. $disabled .'>
                                                                                <option value="">--</option>
                                                                                '. $api->generate_system_code_options('MAILENCRYPTION') .'
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="smtp_auth" class="col-md-3 col-form-label">SMTP Authentication</label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="smtp_auth_label"></label>
                                                                        <div class="d-none form-edit">
                                                                            <select class="form-control select2" id="smtp_auth" name="smtp_auth" '. $disabled .'>
                                                                                <option value="0">False</option>
                                                                                <option value="1">True</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_from_name" class="col-md-3 col-form-label">Mail From Name <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="mail_from_name_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="mail_from_name" name="mail_from_name" maxlength="200" '. $disabled .'>
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
                                                                    <label for="port" class="col-md-3 col-form-label">Port</label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="port_label"></label>
                                                                        <input id="port" name="port" class="form-control d-none form-edit" type="number" min="0" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_password" class="col-md-3 col-form-label">Mail Password <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="mail_password_label"></label>
                                                                        <div class="input-group auth-pass-inputgroup d-none form-edit">
                                                                            <input type="password" id="mail_password" name="mail_password" class="form-control" aria-label="Password" aria-describedby="password-addon" '. $disabled .'>
                                                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="smtp_auto_tls" class="col-md-3 col-form-label">SMTP Auto TLS</label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="smtp_auto_tls_label"></label>
                                                                        <div class="d-none form-edit">
                                                                            <select class="form-control select2" id="smtp_auto_tls" name="smtp_auto_tls" '. $disabled .'>
                                                                                <option value="0">False</option>
                                                                                <option value="1">True</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="mail_from_email" class="col-md-3 col-form-label">Mail From Email <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                    <label class="col-form-label form-details" id="mail_from_email_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="mail_from_email" name="mail_from_email" maxlength="200" '. $disabled .'>
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
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/email-setting-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>