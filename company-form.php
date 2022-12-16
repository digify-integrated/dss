<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    
    $check_user_account_status = $api->check_user_account_status($username);

    if($check_user_account_status){
        $page_details = $api->get_page_details(16);
        $module_id = $page_details[0]['MODULE_ID'];
        $page_title = $page_details[0]['PAGE_NAME'];
    
        $page_access_right = $api->check_role_access_rights($username, 16, 'page');
        $module_access_right = $api->check_role_access_rights($username, $module_id, 'module');

        if($module_access_right == 0 || $page_access_right == 0){
            header('location: apps.php');
        }
        else{
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $company_id = $api->decrypt_data($id);
            }
            else{
                $company_id = null;
            }

            $add_company = $api->check_role_access_rights($username, '39', 'action');
            $update_company = $api->check_role_access_rights($username, '40', 'action');
            $delete_company = $api->check_role_access_rights($username, '41', 'action');

            if($update_company > 0){
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
                                    <h4 class="mb-sm-0 font-size-18">Company Form</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item"><a href="company.php">Company</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            <?php
                                                if(!empty($company_id)){
                                                    echo '<li class="breadcrumb-item" id="company-id"><a href="javascript: void(0);">'. $company_id .'</a></li>';
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
                                        <form id="company-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1 align-self-center">
                                                            <h4 class="card-title">Company Form</h4>
                                                        </div>
                                                        <div class="flex-grow-1 align-self-center">
                                                        <?php
                                                            if(!empty($company_id)){
                                                                $dropdown_action = '<div class="btn-group">
                                                                        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
                                                                        <div class="dropdown-menu dropdown-menu-end">';

                                                                if($add_company > 0){
                                                                    $dropdown_action .= '<a class="dropdown-item" href="company-form.php">Add Company</a>';
                                                                }

                                                                if($delete_company > 0){
                                                                    $dropdown_action .= '<button class="dropdown-item" type="button" data-company-id="'. $company_id .'" id="delete-company">Delete Company</button>';
                                                                }

                                                                $dropdown_action .= '</div>
                                                                </div>';

                                                                echo $dropdown_action;
                                                            }
                                                        ?>
                                                        </div>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <?php
                                                                if(($add_company > 0 || ($update_company > 0 && !empty($company_id)))){
                                                                    echo '<button type="submit" for="page-form" id="submit-data" class="btn btn-primary w-sm">Save</button>';
                                                                }
                                                            ?>
                                                            <button type="button" id="discard" class="btn btn-outline-danger w-sm">Discard</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                if(!empty($company_id)){
                                                    echo '<div class="row mt-4">
                                                            <div class="col-md-12" id="company_logo_image"></div>
                                                        </div>';
                                                }
                                            ?>
                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <input type="hidden" id="company_id" name="company_id">
                                                        <input type="hidden" id="transaction_log_id">
                                                        <label for="company_name" class="col-md-3 col-form-label">Company <span class="text-danger">*</span></label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="company_name" name="company_name" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="company_logo" class="col-md-3 col-form-label">Company Logo</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" type="file" name="company_logo" id="company_logo">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="company_address" class="col-md-3 col-form-label">Company Address</label>
                                                        <div class="col-md-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="company_address" name="company_address" maxlength="500" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="tax_id" class="col-sm-3 col-form-label">Tax ID</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="tax_id" name="tax_id" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-4">
                                                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                                                        <div class="col-sm-9">
                                                            <input type="email" id="email" name="email" class="form-control form-maxlength" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="email" class="col-sm-3 col-form-label">Mobile Number</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="mobile" name="mobile" maxlength="30" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="telephone" class="col-sm-3 col-form-label">Telephone</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-maxlength" autocomplete="off" id="telephone" name="telephone" maxlength="30" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="website" class="col-sm-3 col-form-label">Website</label>
                                                        <div class="col-sm-9">
                                                            <input type="url" class="form-control form-maxlength" autocomplete="off" id="website" name="website" maxlength="100" <?php echo $disabled; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                            if(!empty($company_id)){
                                                echo ' <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#transaction-log" role="tab">
                                                                    <span class="d-block d-sm-none"><i class="fas fa-list"></i></span>
                                                                    <span class="d-none d-sm-block">Transaction Log</span>    
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content p-3 text-muted">
                                                            <div class="tab-pane active" id="transaction-log" role="tabpanel">
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
        <script src="assets/js/pages/company-form.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>