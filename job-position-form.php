<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(34);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 34, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $job_position_id = $api->decrypt_data($id);
                $job_position_details = $api->get_job_position_details($job_position_id);
                $job_position_recruitment_status = $job_position_details[0]['RECRUITMENT_STATUS'];
            }
            else{
                $job_position_id = null;
            }

            $add_job_position = $api->check_role_access_rights($username, '86', 'action');
            $update_job_position = $api->check_role_access_rights($username, '87', 'action');
            $delete_job_position = $api->check_role_access_rights($username, '88', 'action');
            $start_job_position_recruitment = $api->check_role_access_rights($username, '89', 'action');
            $stop_job_position_recruitment = $api->check_role_access_rights($username, '90', 'action');
            $add_job_position_responsibility = $api->check_role_access_rights($username, '91', 'action');
            $add_job_position_requirement = $api->check_role_access_rights($username, '94', 'action');
            $add_job_position_qualification = $api->check_role_access_rights($username, '97', 'action');
            $add_job_position_attachment = $api->check_role_access_rights($username, '100', 'action');

            if($update_job_position > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Job Position Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="job-positions.php">Job Positions</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($job_position_id)){
                                                    echo '<li class="breadcrumb-item" id="job-position-id"><a href="javascript: void(0);">'. $job_position_id .'</a></li>';
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
                                        <form id="job-position-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Job Position Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($job_position_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_job_position > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="job-position-form.php">Add Job Position</a>';
                                                                        }
        
                                                                        if($delete_job_position > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-job-position-id="'. $job_position_id .'" id="delete-job-position">Delete Job Position</button>';
                                                                        }
        
                                                                        if(($start_job_position_recruitment > 0 && $job_position_recruitment_status == 2) || ($stop_job_position_recruitment > 0 && $job_position_recruitment_status == 1)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($start_job_position_recruitment > 0 && $job_position_recruitment_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-job-position-id="'. $job_position_id .'" id="start-job-position-recruitment">Start Recruitment</button>';
                                                                            }
            
                                                                            if($stop_job_position_recruitment > 0 && $job_position_recruitment_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-job-position-id="'. $job_position_id .'" id="stop-job-position-recruitment">Stop Recruitment</button>';
                                                                            }
                                                                        }
        
                                                                        if(($add_job_position_attachment > 0 || $add_job_position_responsibility > 0 || $add_job_position_requirement > 0 || $add_job_position_qualification > 0) && $update_job_position > 0){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($add_job_position_attachment > 0){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" id="add-attachment">Add Attachment</button>';
                                                                            }
        
                                                                            if($add_job_position_responsibility > 0){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" id="add-responsibility">Add Responsibility</button>';
                                                                            }
        
                                                                            if($add_job_position_requirement > 0){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" id="add-requirement">Add Requirement</button>';
                                                                            }
        
                                                                            if($add_job_position_qualification > 0){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" id="add-qualification">Add Qualification</button>';
                                                                            }
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                  if(($add_job_position > 0 || ($update_job_position > 0 && !empty($job_position_id)))){
                                                                    echo '<button type="submit" for="job-position-form" id="submit-data" class="btn btn-primary">
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
                                                if(!empty($job_position_id)){
                                                    echo '<div class="row mt-2">
                                                            <div class="col-md-12" id="job_position_recruitment_status"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="job_position_id" name="job_position_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="job_position" class="col-md-3 col-form-label">Job Position <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="job_position" name="job_position" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="description" class="col-md-3 col-form-label">Description <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="description" name="description" maxlength="500" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="department" class="col-md-3 col-form-label">Department <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="department" name="department" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_department_options('all'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="expected_new_employees" class="col-md-3 col-form-label">Expected New Employees</label>
                                                        <div class="col-md-9">
                                                            <input id="expected_new_employees" name="expected_new_employees" class="form-control" type="number" min="0" value="0" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($job_position_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#job-position-employee" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                                    <span class="d-none d-sm-block">Employee</span>    
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#job-position-attachment" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-file-upload"></i></span>
                                                                    <span class="d-none d-sm-block">Attachment</span>    
                                                                </a>    
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#job-position-responsibility" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-handshake"></i></span>
                                                                    <span class="d-none d-sm-block">Responsibilities</span>    
                                                                </a>    
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#job-position-requiremennt" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-check-double"></i></span>
                                                                    <span class="d-none d-sm-block">Requirements</span>    
                                                                </a>    
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#job-position-qualification" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-clipboard-check"></i></span>
                                                                    <span class="d-none d-sm-block">Qualifications</span>    
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
                                                            <div class="tab-pane active" id="job-position-employee" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="job-position-employee-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Employee</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="tab-pane" id="job-position-attachment" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="job-position-attachment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Attachment</th>
                                                                                    <th class="all">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="job-position-responsibility" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="job-position-responsibility-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Responsibility</th>
                                                                                    <th class="all">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="job-position-requiremennt" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="job-position-requirement-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Requirement</th>
                                                                                    <th class="all">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="job-position-qualification" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="job-position-qualification-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="all">Qualification</th>
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
        <script src="assets/js/pages/job-position-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>