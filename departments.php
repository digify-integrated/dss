<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(31);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 31, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            $add_department = $api->check_role_access_rights($username, '81', 'action');
            $delete_department = $api->check_role_access_rights($username, '82', 'action');
            $archive_department = $api->check_role_access_rights($username, '84', 'action');
            $unarchive_department = $api->check_role_access_rights($username, '85', 'action');

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
                require('views/menu/_menu_employee.php');
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Departments</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1 align-self-center">
                                                        <h4 class="card-title">Departments List</h4>
                                                    </div>
                                                    <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if($delete_department > 0 || $archive_department > 0 || $unarchive_department > 0){
                                                                $dropdown_action = '<div class="btn-group">
                                                                    <button type="button" class="btn btn-outline-dark dropdown-toggle d-none multiple-action" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                        <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">';
                                                                    
                                                                    if($delete_department > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple" type="button" id="delete-department">Delete Department</button>';
                                                                    }
    
                                                                    if($archive_department > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-archive" type="button" id="archive-department">Archive Department</button>';
                                                                    }
    
                                                                    if($unarchive_department > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-unarchive" type="button" id="unarchive-department">Unarchive Department</button>';
                                                                    }

                                                                $dropdown_action .= '</div></div>';

                                                                echo $dropdown_action;
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <?php
                                                            if($add_department > 0){
                                                                echo '<a href="department-form.php" class="btn btn-primary">
                                                                    <span class="d-block d-sm-none"><i class="bx bx-plus"></i></span>
                                                                    <span class="d-none d-sm-block">Create</span>
                                                                </a>';
                                                            }
                                                        ?>
                                                       <button type="button" class="btn btn-info waves-effect waves-light" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas"><i class="bx bx-filter-alt"></i></span></button>
                                                    </div>
                                                </div>

                                                <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-off-canvas" data-bs-backdrop="true" aria-labelledby="filter-off-canvas-label">
                                                        <div class="offcanvas-header">
                                                            <h5 class="offcanvas-title" id="filter-off-canvas-label">Filter</h5>
                                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                        </div>
                                                        <div class="offcanvas-body">
                                                            <div class="mb-3">
                                                                <p class="text-muted">Status</p>

                                                                <select class="form-control filter-select2" id="filter_status">
                                                                    <option value="">All</option>
                                                                    <option value="1">Unarchived</option>
                                                                    <option value="2">Archived</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <button type="button" class="btn btn-primary waves-effect waves-light" id="apply-filter" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas">Apply Filter</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="departments-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                                                </div>
                                                            </th>
                                                            <th class="all">Department ID</th>
                                                            <th class="all">Department</th>
                                                            <th class="all">Status</th>
                                                            <th class="all">Manager</th>
                                                            <th class="all">Employees</th>
                                                            <th class="all">Parent Department</th>
                                                            <th class="all">View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
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
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/departments.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>