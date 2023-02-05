<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(47);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 47, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            $add_employee = $api->check_role_access_rights($username, '126', 'action');
            $delete_employee = $api->check_role_access_rights($username, '128', 'action');
            $archive_employee = $api->check_role_access_rights($username, '129', 'action');
            $unarchive_employee = $api->check_role_access_rights($username, '130', 'action');

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
                                    <h4 class="mb-sm-0 font-size-18">Employees</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employees</a></li>
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
                                                        <h4 class="card-title">Employees List</h4>
                                                    </div>
                                                    <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if($delete_employee > 0 || $archive_employee > 0 || $unarchive_employee > 0){
                                                                $dropdown_action = '<div class="btn-group">
                                                                    <button type="button" class="btn btn-outline-dark dropdown-toggle d-none multiple-action" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                        <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-end">';
                                                                    
                                                                    if($delete_employee > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple" type="button" id="delete-employee">Delete Employee</button>';
                                                                    }
    
                                                                    if($archive_employee > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-archive" type="button" id="archive-employee">Archive Employee</button>';
                                                                    }
    
                                                                    if($unarchive_employee > 0){
                                                                        $dropdown_action .= '<button class="dropdown-item d-none multiple-unarchive" type="button" id="unarchive-employee">Unarchive Employee</button>';
                                                                    }

                                                                $dropdown_action .= '</div></div>';

                                                                echo $dropdown_action;
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <?php
                                                            if($add_employee > 0){
                                                                echo '<a href="employee-form.php" class="btn btn-primary">
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
                                                            <div class="mb-3">
                                                                <p class="text-muted">Department</p>

                                                                <select class="form-control filter-select2" id="filter_department">
                                                                    <option value="">All</option>
                                                                    <?php echo $api->generate_department_options('active');?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <p class="text-muted">Job Position</p>

                                                                <select class="form-control filter-select2" id="filter_job_position">
                                                                    <option value="">All</option>
                                                                    <?php echo $api->generate_job_position_options('all');?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <p class="text-muted">Employee Type</p>

                                                                <select class="form-control filter-select2" id="filter_employee_type">
                                                                    <option value="">All</option>
                                                                    <?php echo $api->generate_employee_type_options();?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <p class="text-muted">Work Location</p>

                                                                <select class="form-control filter-select2" id="filter_work_location">
                                                                    <option value="">All</option>
                                                                    <?php echo $api->generate_work_location_options('active');?>
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
                                                <table id="employees-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                                                </div>
                                                            </th>
                                                            <th class="all">Employee ID</th>
                                                            <th class="all">Employee</th>
                                                            <th class="all">Department</th>
                                                            <th class="all">Job Positon</th>
                                                            <th class="all">Employee Type</th>
                                                            <th class="all">Work Location</th>
                                                            <th class="all">Status</th>
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
        <script src="assets/js/pages/employees.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>