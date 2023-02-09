<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(50);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 50, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $employee_id = $api->decrypt_data($id);
                $employee_details = $api->get_employee_details($employee_id);
                $employee_status = $employee_details[0]['EMPLOYEE_STATUS'] ?? null;
            }
            else{
                $employee_id = null;
            }

            $add_employee = $api->check_role_access_rights($username, '131', 'action');
            $update_employee = $api->check_role_access_rights($username, '132', 'action');
            $delete_employee = $api->check_role_access_rights($username, '133', 'action');
            $archive_employee = $api->check_role_access_rights($username, '134', 'action');
            $unarchive_employee = $api->check_role_access_rights($username, '135', 'action');

            if($update_employee > 0){
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
                require('views/menu/_menu_employee.php');
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Employee Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($employee_id)){
                                                    echo '<li class="breadcrumb-item" id="employee-id"><a href="javascript: void(0);">'. $employee_id .'</a></li>';
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
                                        <form id="employee-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Employee Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($employee_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_employee > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="employee-form.php">Add Employee</a>';
                                                                        }
        
                                                                        if($delete_employee > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-employee-id="'. $employee_id .'" id="delete-employee">Delete Employee</button>';
                                                                        }
        
                                                                        if(($archive_employee > 0 && $employee_status == 1) || ($unarchive_employee > 0 && $employee_status == 2)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($archive_employee > 0 && $employee_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-employee-id="'. $employee_id .'" id="archive-employee">Archive Employee</button>';
                                                                            }
            
                                                                            if($unarchive_employee > 0 && $employee_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-employee-id="'. $employee_id .'" id="unarchive-employee">Unarchive Employee</button>';
                                                                            }
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                               if(($add_employee > 0 || ($update_employee > 0 && !empty($employee_id)))){
                                                                    echo '<button type="submit" for="employee-form" id="submit-data" class="btn btn-primary">
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
                                                if(!empty($employee_id)){
                                                    echo '<div class="row mt-2">
                                                            <div class="col-md-12" id="employee_status"></div>
                                                        </div>';
                                                }

                                                if(!empty($employee_id)){
                                                    echo '<div class="row mt-2">
                                                            <div class="col-md-6" id="employee_image"></div>
                                                            <div class="col-md-6" id="employee_digital_signature"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="employee_id" name="employee_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="first_name" class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="first_name" name="first_name" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="middle_name" class="col-md-3 col-form-label">Middle Name</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="middle_name" name="middle_name" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="last_name" class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="last_name" name="last_name" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="last_name" class="col-md-3 col-form-label">Suffix</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="suffix" name="suffix" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_system_code_options('SUFFIX'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="department" class="col-md-3 col-form-label">Department <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="department" name="department" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_department_options('active'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="job_position" class="col-md-3 col-form-label">Job Position <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="job_position" name="job_position" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_job_position_options('active'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="manager" class="col-md-3 col-form-label">Manager</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="manager" name="manager" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="coach" class="col-md-3 col-form-label">Coach</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="coach" name="coach" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#work-information" role="tab">
                                                            <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                            <span class="d-none d-sm-block">Work Information</span>    
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#personal-information" role="tab">
                                                            <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                            <span class="d-none d-sm-block">Personal Information</span>    
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#HR" role="tab">
                                                            <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                            <span class="d-none d-sm-block">Personal Information</span>    
                                                        </a>
                                                    </li>
                                                    <?php
                                                        if(!empty($employee_id)){
                                                            echo '<li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab" href="#transaction-log" role="tab">
                                                                        <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                                        <span class="d-none d-sm-block">Transaction Log</span>    
                                                                    </a>
                                                                </li>';
                                                        }
                                                    ?>
                                                </ul>
                                                <div class="tab-content p-3 text-muted">
                                                    <div class="tab-pane active" id="work-information" role="tabpanel">
                                                        <div class="row mt-4">
                                                            <div class="col-md-12">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane active" id="personal-information" role="tabpanel">
                                                        <div class="row mt-4">
                                                            <div class="col-md-12">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if(!empty($employee_id)){
                                                            echo '<div class="tab-pane" id="transaction-log" role="tabpanel">
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
                                                            </div>';
                                                        }
                                                    ?>
                                                </div>
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
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/employee-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>