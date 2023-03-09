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
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
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

                                                                        $dropdown_action .= '<div class="dropdown-divider"></div>
                                                                        <button class="dropdown-item" type="button" data-employee-id="'. $employee_id .'" id="update-employee">Delete Employee</button>';
        
                                                                        if(($archive_employee > 0 && $employee_status == 1) || ($unarchive_employee > 0 && $employee_status == 2)){
                                                                            
        
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
                                                                <?php echo $api->generate_job_position_options('all'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="manager" class="col-md-3 col-form-label">Manager</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="manager" name="manager" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_employee_options('active'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="coach" class="col-md-3 col-form-label">Coach</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control select2" id="coach" name="coach" <?php echo $disabled; ?>>
                                                                <option value="">--</option>
                                                                <?php echo $api->generate_employee_options('active'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#work-information" role="tab">
                                                                <span class="d-block d-sm-none"><i class="fas fa-building"></i></span>
                                                                <span class="d-none d-sm-block">Work Information</span>    
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#personal-information" role="tab">
                                                                <span class="d-block d-sm-none"><i class="fas fa-id-card"></i></span>
                                                                <span class="d-none d-sm-block">Personal Information</span>    
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#hr-settings" role="tab">
                                                                <span class="d-block d-sm-none"><i class="fas fa-users-cog"></i></span>
                                                                <span class="d-none d-sm-block">HR Settings</span>    
                                                            </a>
                                                        </li>
                                                        <?php
                                                            if(!empty($employee_id)){
                                                                echo '<li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-contact-infromation" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-phone"></i></span>
                                                                            <span class="d-none d-sm-block">Contact Information</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-address" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-map-marker-alt"></i></span>
                                                                            <span class="d-none d-sm-block">Employee Address</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-identification" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-id-badge"></i></span>
                                                                            <span class="d-none d-sm-block">Employee Identification</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-family-details" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-user-friends"></i></span>
                                                                            <span class="d-none d-sm-block">Family Details</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-emergency-contact" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-exclamation-triangle"></i></span>
                                                                            <span class="d-none d-sm-block">Emergency Contact</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-educational-background" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-user-graduate"></i></span>
                                                                            <span class="d-none d-sm-block">Educational Background</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-trainings-seminar" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-certificate"></i></span>
                                                                            <span class="d-none d-sm-block">Trainings & Seminars</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-bank-information" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-money-check"></i></span>
                                                                            <span class="d-none d-sm-block">Bank Information</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-bs-toggle="tab" href="#employee-employment-history" role="tab">
                                                                            <span class="d-block d-sm-none"><i class="fas fa-city"></i></span>
                                                                            <span class="d-none d-sm-block">Employment History</span>    
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
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
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="company" class="col-md-3 col-form-label">Company <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="company" name="company" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_company_options(); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="badge_id" class="col-md-3 col-form-label">Badge ID <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="badge_id" name="badge_id" maxlength="100" <?php echo $disabled; ?>>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="work_location" class="col-md-3 col-form-label">Work Location <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="work_location" name="work_location" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_work_location_options('active'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="work_schedule" class="col-md-3 col-form-label">Working Schedule <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="work_schedule" name="work_schedule" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_working_schedule_options(); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="personal-information" role="tabpanel">
                                                            <div class="row mt-4">
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="nickname" class="col-md-3 col-form-label">Nickname</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="nickname" name="nickname" maxlength="20" <?php echo $disabled; ?>>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="nationality" class="col-md-3 col-form-label">Nationality</label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="nationality" name="nationality" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_system_code_options('NATIONALITY'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="birthday" class="col-md-3 col-form-label">Birthday <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group" id="birthday-container">
                                                                                <input type="text" class="form-control birthday-date-picker" id="birthday" name="birthday" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#birthday-container" data-provide="datepicker" data-date-autoclose="true">
                                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="blood_type" class="col-md-3 col-form-label">Blood Type</label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="blood_type" name="blood_type" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_system_code_options('BLOODTYPE'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="height" class="col-md-3 col-form-label">Height</label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group">
                                                                                <input id="height" name="height" class="form-control" type="number" min="0" step=".01" <?php echo $disabled; ?>>
                                                                                <div class="input-group-text">cm</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="civil_status" class="col-md-3 col-form-label">Civil Status</label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="civil_status" name="civil_status" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_system_code_options('CIVIL STATUS'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="gender" class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="gender" name="gender" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_system_code_options('GENDER'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="birth_place" class="col-md-3 col-form-label">Birth Place</label>
                                                                        <div class="col-md-9">
                                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="birth_place" name="birth_place" maxlength="500" <?php echo $disabled; ?>>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="religion" class="col-md-3 col-form-label">Religion</label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="religion" name="religion" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_system_code_options('RELIGION'); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="weight" class="col-md-3 col-form-label">Weight</label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group">
                                                                                <input id="weight" name="weight" class="form-control" type="number" min="0" step=".01" <?php echo $disabled; ?>>
                                                                                <div class="input-group-text">kg</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="hr-settings" role="tabpanel">
                                                            <div class="row mt-4">
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="employee_type" class="col-md-3 col-form-label">Employee Type <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <select class="form-control select2" id="employee_type" name="employee_type" <?php echo $disabled; ?>>
                                                                                <option value="">--</option>
                                                                                <?php echo $api->generate_employee_type_options(); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-4">
                                                                        <label for="permanency_date" class="col-md-3 col-form-label">Pemanency Date</label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group" id="permanency-date-container">
                                                                                <input type="text" class="form-control form-date-picker" id="permanency_date" name="permanency_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#permanency-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row mb-4">
                                                                        <label for="onboard_date" class="col-md-3 col-form-label">Onboard Date <span class="text-danger">*</span></label>
                                                                        <div class="col-md-9">
                                                                            <div class="input-group" id="onboard-date-container">
                                                                                <input type="text" class="form-control form-date-picker" id="onboard_date" name="onboard_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#onboard-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/employee-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>