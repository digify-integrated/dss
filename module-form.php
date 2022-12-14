<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(2);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, '2', 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $module_id = $api->decrypt_data($id);
            }
            else{
                $module_id = null;
            }

            $add_module = $api->check_role_access_rights($username, '1', 'action');
            $update_module = $api->check_role_access_rights($username, '2', 'action');
            $delete_module = $api->check_role_access_rights($username, '3', 'action');
            $add_module_access_right = $api->check_role_access_rights($username, '4', 'action');

            if($update_module > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Module Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Technical</a></li>
                                            <li class="breadcrumb-item"><a href="modules.php">Modules</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($module_id)){
                                                    echo '<li class="breadcrumb-item" id="module-id"><a href="javascript: void(0);">'. $module_id .'</a></li>';
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
                                        <form id="module-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Module Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($module_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_module > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="module-form.php">Add Module</a>';
                                                                        }
        
                                                                        if($delete_module > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-module-id="'. $module_id .'" id="delete-module">Delete Module</button>';
                                                                        }
        
                                                                        if($add_module_access_right > 0 && $update_module > 0){   
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" id="add-module-access">Add Module Access</button>';
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(($add_module > 0 || ($update_module > 0 && !empty($module_id)))){
                                                                    echo '<button type="submit" for="module-form" id="submit-data" class="btn btn-primary">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>';
                                                                }
                                                            ?>
                                                             <button type="button" id="discard" class="btn btn-outline-danger">
                                                                <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                <span class="d-none d-sm-block">Discard</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if(!empty($module_id)){
                                                    echo '<div class="row mt-2">
                                                            <div class="col-md-12" id="module_icon_image"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="module_id" name="module_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="module_name" class="col-md-3 col-form-label">Module Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_name" name="module_name" maxlength="200" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="module_description" class="col-md-3 col-form-label">Module Description <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_description" name="module_description" maxlength="500" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="module_category" class="col-md-3 col-form-label">Module Category <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="module_category" name="module_category" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_system_code_options('MODULECAT'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="order_sequence" class="col-md-3 col-form-label">Order Sequence</label>
                                                        <div class="col-md-9">
                                                            <input id="order_sequence" name="order_sequence" class="form-control" type="number" min="0" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="module_version" class="col-md-3 col-form-label">Module Version <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_version" name="module_version" maxlength="20" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="module_icon" class="col-md-3 col-form-label">Module Icon</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="module_icon" id="module_icon" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="default_page" class="col-md-3 col-form-label">Default Page <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="default_page" name="default_page" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($module_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#module-access" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-cubes"></i></span>
                                                                    <span class="d-none d-sm-block">Module Access</span>    
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
                                                            <div class="tab-pane active" id="module-access" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="module-access-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
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
        <script src="assets/js/pages/module-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>