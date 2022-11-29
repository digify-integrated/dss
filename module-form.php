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
                                        <form id="module-form" action="module.php">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Module Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                                if(($delete_module > 0 && !empty($module_id)) || ($add_module_access_right > 0 && ((!empty($module_id) && $update_module > 0)))){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
                                                                        <ul class="dropdown-menu dropdown-menu-end">';

                                                                    if($delete_module > 0 && !empty($module_id)){
                                                                        $dropdown_action .= '<li><button class="dropdown-item" type="button" id="delete-module">Delete Module</button></li>';
                                                                    }

                                                                    if($add_module_access_right > 0 && ((!empty($module_id) && $update_module > 0))){
                                                                        $dropdown_action .= '<li><button class="dropdown-item" type="button" id="add-module-access">Add Module Access</button></li>';
                                                                    }

                                                                    $dropdown_action .= '</ul></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if((!empty($module_id) && $update_module > 0) || (empty($module_id) && $add_module > 0)){
                                                                    echo '<button type="submit" for="module-form" id="submit-data" class="btn btn-primary w-sm">Save</button>';
                                                                }
                                                            ?>
                                                            <button type="button" id="discard" class="btn btn-outline-danger w-sm">Discard</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="row mb-4">
                                                                <input type="hidden" id="module_id" name="module_id">
                                                                <label for="module_name" class="col-sm-3 col-form-label">Module Name <span class="text-danger">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_name" name="module_name" maxlength="200">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label for="module_description" class="col-sm-3 col-form-label">Module Description <span class="text-danger">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_description" name="module_description" maxlength="500">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label for="module_category" class="col-sm-3 col-form-label">Module Category <span class="text-danger">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control select2" id="module_category" name="module_category">
                                                                        <option value=""></option>
                                                                        <?php echo $api->generate_system_code_options('MODULECAT'); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="row mb-4">
                                                                <label for="module_version" class="col-sm-3 col-form-label">Module Version <span class="text-danger">*</span></label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="module_version" name="module_version" maxlength="20">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <label for="module_icon" class="col-sm-3 col-form-label">Module Icon</label>
                                                                <div class="col-sm-9">
                                                                    <input class="form-control" type="file" name="module_icon" id="module_icon">
                                                                </div>
                                                            </div>
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
                                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                                    <span class="d-none d-sm-block">Module Access</span>    
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