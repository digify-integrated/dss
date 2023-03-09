<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(4);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, '4', 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $page_id = $api->decrypt_data($id);

                $page_details = $api->get_page_details($page_id);
                $transaction_log_id = $page_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                $page_id = null;
            }

            $add_page = $api->check_role_access_rights($username, '6', 'action');
            $update_page = $api->check_role_access_rights($username, '7', 'action');
            $delete_page = $api->check_role_access_rights($username, '8', 'action');
            $add_page_access_right = $api->check_role_access_rights($username, '9', 'action');

            if($update_page > 0){
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
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
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
                                    <h4 class="mb-sm-0 font-size-18">Page Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Technical</a></li>
                                            <li class="breadcrumb-item"><a href="pages.php">Pages</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($page_id)){
                                                    echo '<li class="breadcrumb-item" id="page-id"><a href="javascript: void(0);">'. $page_id .'</a></li>';
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
                                        <form id="page-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Page Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($page_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_page > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="page-form.php">Add Page</a>';
                                                                        }
        
                                                                        if($delete_page > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-page-id="'. $page_id .'" id="delete-page">Delete Page</button>';
                                                                        }
        
                                                                        if($add_page_access_right > 0 && $update_page > 0){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" id="add-page-access">Add Page Access</button>';
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($page_id) && $add_page > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($page_id) && $update_page > 0){
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
                                                                else if(!empty($page_id) && $update_page <= 0){
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
                                                <input type="hidden" id="page_id" name="page_id" value="<?php echo $page_id; ?>">
                                                <?php
                                                    if(empty($page_id) && $add_page > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="page_name" class="col-md-3 col-form-label">Page Name <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="page_name" name="page_name" maxlength="200" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="module_id" class="col-md-3 col-form-label">Module <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <select class="form-control select2" id="module_id" name="module_id" '. $disabled .'>
                                                                            <option value="">--</option>
                                                                            '. $api->generate_module_options() .'
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                    else if(!empty($page_id) && $update_page > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                    <label for="page_name" class="col-md-3 col-form-label">Page Name <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="page_name_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="page_name" name="page_name" maxlength="200" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="module_id" class="col-md-3 col-form-label">Module <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="module_id_label"></label>
                                                                        <div class="d-none form-edit">
                                                                            <select class="form-control select2" id="module_id" name="module_id" '. $disabled .'>
                                                                                <option value="">--</option>
                                                                                '. $api->generate_module_options() .'
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                ?>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($page_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#page-access" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-window-maximize"></i></span>
                                                                    <span class="d-none d-sm-block">Page Access</span>    
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="page-access" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="page-access-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
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
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/page-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>