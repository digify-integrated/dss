<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(36);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 36, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $work_location_id = $api->decrypt_data($id);

                $work_location_details = $api->get_work_location_details($work_location_id);
                $work_location_status = $work_location_details[0]['STATUS'];
                $transaction_log_id = $work_location_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                $work_location_id = null;
            }

            $add_work_location = $api->check_role_access_rights($username, '103', 'action');
            $update_work_location = $api->check_role_access_rights($username, '104', 'action');
            $delete_work_location = $api->check_role_access_rights($username, '105', 'action');
            $archive_work_location = $api->check_role_access_rights($username, '106', 'action');
            $unarchive_work_location = $api->check_role_access_rights($username, '107', 'action');

            if($update_work_location > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Work Location Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configurations</a></li>
                                            <li class="breadcrumb-item"><a href="work-locations.php">Work Locations</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($work_location_id)){
                                                    echo '<li class="breadcrumb-item" id="work-location-id"><a href="javascript: void(0);">'. $work_location_id .'</a></li>';
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
                                        <form id="work-location-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Work Location Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                            <?php
                                                                if(!empty($work_location_id)){
                                                                    $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-wrench"></i> <i class="mdi mdi-chevron-down"></i></span>
                                                                            <span class="d-none d-sm-block">Action <i class="mdi mdi-chevron-down"></i></span>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';
                                                                        
                                                                        if($add_work_location > 0){
                                                                            $dropdown_action .= '<a class="dropdown-item" href="work-location-form.php">Add Work Location</a>';
                                                                        }
        
                                                                        if($delete_work_location > 0){
                                                                            $dropdown_action .= '<button class="dropdown-item" type="button" data-work-location-id="'. $work_location_id .'" id="delete-work-location">Delete Work Location</button>';
                                                                        }
        
                                                                        if(($archive_work_location > 0 && $work_location_status == 1) || ($unarchive_work_location > 0 && $work_location_status == 2)){
                                                                            $dropdown_action .= '<div class="dropdown-divider"></div>';
        
                                                                            if($archive_work_location > 0 && $work_location_status == 1){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-work-location-id="'. $work_location_id .'" id="archive-work-location">Archive Work Location</button>';
                                                                            }
            
                                                                            if($unarchive_work_location > 0 && $work_location_status == 2){
                                                                                $dropdown_action .= '<button class="dropdown-item" type="button" data-work-location-id="'. $work_location_id .'" id="unarchive-work-location">Unarchive Work Location</button>';
                                                                            }
                                                                        }

                                                                    $dropdown_action .= '</div></div>';

                                                                    echo $dropdown_action;
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(empty($work_location_id) && $add_work_location > 0){
                                                                    echo ' <button type="submit" for="action-form" id="submit-data" class="btn btn-primary waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-save"></i></span>
                                                                            <span class="d-none d-sm-block">Save</span>
                                                                        </button>
                                                                        <button type="button" id="discard-create" class="btn btn-outline-danger waves-effect waves-light form-edit">
                                                                            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
                                                                            <span class="d-none d-sm-block">Discard</span>
                                                                        </button>';
                                                                }
                                                                else if(!empty($work_location_id) && $update_work_location > 0){
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
                                                                else if(!empty($work_location_id) && $update_work_location <= 0){
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
                                                if(!empty($work_location_id)){
                                                    echo '<div class="row">
                                                            <div class="col-md-12" id="work_location_status"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <input type="hidden" id="work_location_id" name="work_location_id" value="<?php echo $work_location_id; ?>">
                                                <?php
                                                    if(empty($work_location_id) && $add_work_location > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="work_location" class="col-md-3 col-form-label">Work Location <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_location" name="work_location" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="work_location_address" class="col-md-3 col-form-label">Work Location Address <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_location_address" name="work_location_address" maxlength="500" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="location_number" class="col-md-3 col-form-label">Location Number <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <input id="location_number" name="location_number" class="form-control" type="number" min="1" value="1" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="email" id="email" name="email" class="form-control form-maxlength" maxlength="100" autocomplete="off" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="email" class="col-sm-3 col-form-label">Mobile Number</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="mobile" name="mobile" maxlength="30" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="telephone" class="col-sm-3 col-form-label">Telephone</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="telephone" name="telephone" maxlength="30" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                    else if(!empty($work_location_id) && $update_work_location > 0){
                                                        echo '<div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <input type="hidden" id="transaction_log_id" value="'. $transaction_log_id .'">
                                                                    <label for="work_location" class="col-md-3 col-form-label">Work Location <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="work_location_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="work_location" name="work_location" maxlength="100" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="work_location_address" class="col-md-3 col-form-label">Work Location Address <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="work_location_address_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="work_location_address" name="work_location_address" maxlength="500" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="location_number" class="col-md-3 col-form-label">Location Number <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9">
                                                                        <label class="col-form-label form-details" id="location_number_label"></label>
                                                                        <input id="location_number" name="location_number" class="form-control d-none form-edit" type="number" min="1" value="1" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row mb-4">
                                                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                                    <div class="col-sm-9">
                                                                        <label class="col-form-label form-details" id="email_label"></label>
                                                                        <input type="email" id="email" name="email" class="form-control form-maxlength d-none form-edit" maxlength="100" autocomplete="off" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="email" class="col-sm-3 col-form-label">Mobile Number</label>
                                                                    <div class="col-sm-9">
                                                                        <label class="col-form-label form-details" id="mobile_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="mobile" name="mobile" maxlength="30" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <label for="telephone" class="col-sm-3 col-form-label">Telephone</label>
                                                                    <div class="col-sm-9">
                                                                        <label class="col-form-label form-details" id="telephone_label"></label>
                                                                        <input type="text" class="form-control form-maxlength d-none form-edit" autocomplete="off" id="telephone" name="telephone" maxlength="30" '. $disabled .'>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }
                                                ?>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($work_location_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#work-location-employee" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-hand-point-up"></i></span>
                                                                    <span class="d-none d-sm-block">Employee</span>    
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="work-location-employee" role="tabpanel">
                                                                <div class="row mt-4">
                                                                    <div class="col-md-12">
                                                                        <table id="work-location-employee-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
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
        <script src="assets/js/pages/work-location-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>