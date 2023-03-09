<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(6);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 6, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: 404.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $action_id = $api->decrypt_data($id);

                $action_details = $api->get_action_details($action_id);
                $transaction_log_id = $action_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                $action_id = null;
            }

            $add_action = $api->check_role_access_rights($username, '11', 'action');
            $update_action = $api->check_role_access_rights($username, '12', 'action');
            $delete_action = $api->check_role_access_rights($username, '13', 'action');
            $add_action_access_right = $api->check_role_access_rights($username, '14', 'action');

            if($update_action > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Action Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Technical</a></li>
                                            <li class="breadcrumb-item"><a href="actions.php">Actions</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($action_id)){
                                                    echo '<li class="breadcrumb-item" id="action-id"><a href="javascript: void(0);">'. $action_id .'</a></li>';
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
                                        <form id="action-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Action Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($action_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_action > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="action-form.php">Add Action</a>';
                                                                        }
    
                                                                        if($delete_action > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-action-id="'. $action_id .'" id="delete-action">Delete Action</button>';
                                                                        }
    
                                                                        if($add_action_access_right > 0 && $update_action > 0){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" id="add-action-access">Add Action Access</button>';
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($action_id) && $add_action > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($action_id) && $update_action > 0){
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
                                                                else if(!empty($action_id) && $update_action <= 0){
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
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="action_id" name="action_id" value="<?php echo $action_id; ?>">
                                                        <?php
                                                            if(empty($action_id) && $add_action > 0){
                                                                echo ' <label for="action_name" class="col-md-2 col-form-label">Action Name <span class="text-danger">*</span></label>
                                                                <div class="col-md-10">
                                                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="action_name" name="action_name" maxlength="200" '. $disabled .'>
                                                                </div>';
                                                            }
                                                            else if(!empty($action_id) && $update_action > 0){
                                                                echo '<label for="action_name" class="col-md-2 col-form-label">Action Name <span class="text-danger">*</span></label>
                                                                <div class="col-md-10">
                                                                    <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                    <label class="col-form-label form-details" id="action_name_label"></label>
                                                                    <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="action_name" name="action_name" maxlength="200" '. $disabled .'>
                                                                </div>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($action_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#action-access" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                                    <span class="d-none d-sm-block">Action Access</span>    
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="action-access" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="action-access-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
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
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/action-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>