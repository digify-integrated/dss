<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    $page_title = 'Menu Items';

    require('views/_interface_settings.php');
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
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $page_title; ?></h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="apps.php">Apps</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
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
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="row mb-2">
                                                    <label for="menu_title" class="col-sm-2 col-form-label">Menu Title</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" autocomplete="off" id="menu_title" name="menu_title" maxlength="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row mb-2">
                                                    <label for="menu_view" class="col-sm-2 col-form-label">View</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control form-select2" id="menu_view" name="menu_view">
                                                            <option value="">--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="row mb-2">
                                                    <label for="parent_menu" class="col-sm-2 col-form-label">Parent Menu</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control form-select2" id="parent_menu" name="parent_menu">
                                                            <option value="">--</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row mb-2">
                                                    <label for="menu_title" class="col-sm-2 col-form-label">Sequence</label>
                                                    <div class="col-sm-10">
                                                        <div class="input-group" id="duration-container">
                                                            <span class="input-group-text">#</span>
                                                            <input id="duration" name="duration" class="form-control" type="number" min="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#access-rights-tab" role="tab">
                                                            <span class="d-block d-sm-none"><i class="bx bx-building"></i></span>
                                                            <span class="d-none d-sm-block">Access Rights</span>    
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#submenus-tab" role="tab">
                                                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                                            <span class="d-none d-sm-block">Submenus</span>    
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content p-3">
                                                    <div class="tab-pane active" id="access-rights-tab" role="tabpanel">
                                                        <div class="row">
                                                        <table id="company-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                                                </div>
                                                            </th>
                                                            <th class="all">Menu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="submenus-tab" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="row mb-3">
                                                                    <label for="street_1" class="col-sm-3 col-form-label">Employee Address</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="street_1" name="street_1" placeholder="Street" maxlength="200">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-sm-3"></div>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="street_2" name="street_2" placeholder="Street 2" maxlength="200">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-sm-3"></div>
                                                                    <div class="col-sm-3 mb-3">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="city" name="city" placeholder="City" maxlength="100">
                                                                    </div>
                                                                    <div class="col-sm-3 mb-3">
                                                                        <select class="form-control form-select2" id="state" name="state">
                                                                        <option value="">State</option></select>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="zip_code" name="zip_code" placeholder="Zip Code" maxlength="10">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="personal_email" class="col-sm-3 col-form-label">Personal Email</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="email" id="personal_email" name="personal_email" class="form-control form-maxlength" maxlength="100" autocomplete="off">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="personal_mobile" class="col-sm-3 col-form-label">Personal Mobile Number</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="personal_mobile" name="personal_mobile" maxlength="30">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="personal_telephone" class="col-sm-3 col-form-label">Personal Telephone</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="personal_telephone" name="personal_telephone" maxlength="30">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="bank_account_number" class="col-sm-3 col-form-label">Bank Account Number</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="bank_account_number" name="bank_account_number" maxlength="100">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="home_work_distance" class="col-sm-3 col-form-label">Home-Work Distance</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group">
                                                                            <input id="home_work_distance" name="home_work_distance" class="form-control" type="number" min="0">
                                                                            <div class="input-group-text">km</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="marital_status" class="col-sm-3 col-form-label">Marital Status</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control form-select2" id="marital_status" name="marital_status">
                                                                        <option value="">--</option></select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="spouse_name" class="col-sm-3 col-form-label">Spouse Name</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="spouse_name" name="spouse_name" maxlength="500">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="spouse_birthday" class="col-sm-3 col-form-label">Spouse Birthday</label>
                                                                    <div class="col-sm-9">
                                                                        <div class="input-group" id="spouse-birthday-container">
                                                                            <input type="text" class="form-control" id="spouse_birthday" name="spouse_birthday" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#spouse-birthday-container" data-provide="datepicker" data-date-autoclose="true">
                                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="emergency_contact" class="col-sm-3 col-form-label">Emergency Contact</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="emergency_contact" name="emergency_contact" maxlength="500">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="emergency_phone" class="col-sm-3 col-form-label">Emergency Phone</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="emergency_phone" name="emergency_phone" maxlength="20">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="certificate_level" class="col-sm-3 col-form-label">Certificate Level</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control form-select2" id="certificate_level" name="certificate_level">
                                                                        <option value="">--</option></select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                <label for="field_of_study" class="col-sm-3 col-form-label">Field of Study</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="field_of_study" name="field_of_study" maxlength="200">
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="school" class="col-sm-3 col-form-label">School</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="school" name="school" maxlength="200">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
        <script src="assets/libs/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/general-setting.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>
