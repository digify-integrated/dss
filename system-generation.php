<?php
require('./config/config.php');
require('./classes/api.php');

if(isset($_POST['type']) && !empty($_POST['type']) && isset($_POST['username']) && !empty($_POST['username'])){
    $api = new Api;
    $type = $_POST['type'];
    $username = $_POST['username'];
    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $response = array();

    # -------------------------------------------------------------
    #   Generate elements functions
    # -------------------------------------------------------------

    # System modal
    if($type == 'system modal'){
        if(isset($_POST['title']) && isset($_POST['size']) && isset($_POST['scrollable']) && isset($_POST['submit_button']) && isset($_POST['form_id'])){
            $title = $_POST['title'];
            $size = $api->check_modal_size($_POST['size']);
            $scrollable = $api->check_modal_scrollable($_POST['scrollable']);
            $form_id = $_POST['form_id'];
            $submit_button = $_POST['submit_button'];

            if($submit_button == 1){
                $button = '<button type="submit" class="btn btn-primary" id="submit-form" form="'. $form_id .'">Submit</button>';
            }
            else{
                $button = '';
            }

            $modal = '<div class="modal fade" id="System-Modal" role="dialog" aria-labelledby="modal-'. $form_id .'" aria-hidden="true">
                            <div class="modal-dialog '. $scrollable .' '. $size .'">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-'. $form_id .'">'. $title .'</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="modal-body"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        '. $button .'
                                    </div>
                                </div>
                            </div>
                        </div>';

            $response[] = array(
                'MODAL' => $modal
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # System form
    else if($type == 'system form'){
        if(isset($_POST['form_type']) && isset($_POST['form_id'])){
            $form_type = $_POST['form_type'];
            $form_id = $_POST['form_id'];

            $form = '<form class="cmxform" id="'. $form_id .'" method="post" action="#">';

            if($form_type == 'module access form' || $form_type == 'page access form' || $form_type == 'action access form' || $form_type == 'user account role form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="role-assignment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Role</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'role module access form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="module-access-assignment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Module Access</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'role page access form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="page-access-assignment" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Page Access</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'role action access form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="action-access-assignment" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Action Access</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'role user account form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="user-account-assignment" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">User Account</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'upload setting file type form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="file-type-assignment" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">File Type</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'notification role recipient form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="notification-role-recipient-assignment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Role</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'notification user account recipient form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="notification-user-account-recipient-assignment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">User Account</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'notification channel form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                    <table id="notification-role-recipient-assignment-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="all">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="form-datatable-checkbox" type="checkbox">
                                                    </div>
                                                </th>
                                                <th class="all">Channel</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                        </div>';
            }
            else if($form_type == 'state form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="state_id" name="state_id">
                                    <label for="state_name" class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="state_name" name="state_name" maxlength="200">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'job position responsibility form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="responsibility_id" name="responsibility_id">
                                    <label for="responsibility" class="form-label">Responsiblity <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="responsibility" name="responsibility" maxlength="500">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'job position requirement form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="requirement_id" name="requirement_id">
                                    <label for="requirement" class="form-label">Requirement <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="requirement" name="requirement" maxlength="500">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'job position qualification form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="qualification_id" name="qualification_id">
                                    <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="qualification" name="qualification" maxlength="500">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'job position attachment form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="attachment_id" name="attachment_id">
                                    <input type="hidden" id="update" value="0">
                                    <label for="attachment_name" class="form-label">Attachment Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="attachment_name" name="attachment_name" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="attachment" class="form-label">Attachment</label>
                                <input class="form-control" type="file" name="attachment" id="attachment">
                            </div>
                        </div>';
            }
            else if($form_type == 'fixed working hours form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <input type="hidden" id="working_hours_id" name="working_hours_id">
                                    <label for="working_hours" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="working_hours" name="working_hours" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="day_of_week" class="col-form-label">Day of Week <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="day_of_week" name="day_of_week">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('DAYOFWEEK');
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="day_period" class="col-form-label">Day Period <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="day_period" name="day_period">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('DAYPERIOD');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="work_from" class="col-form-label">Work From <span class="text-danger">*</span></label>
                                    <input type="time" id="work_from" name="work_from" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="work_to" class="col-form-label">Work To <span class="text-danger">*</span></label>
                                    <input type="time" id="work_to" name="work_to" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'flexible working hours form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <input type="hidden" id="working_hours_id" name="working_hours_id">
                                    <label for="working_hours" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="working_hours" name="working_hours" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="working_date" class="col-form-label">Working Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="working-date-container">
                                        <input type="text" class="form-control" id="working_date" name="working_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#working-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="day_period" class="col-form-label">Day Period <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="day_period" name="day_period">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('DAYPERIOD');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="work_from" class="col-form-label">Work From <span class="text-danger">*</span></label>
                                    <input type="time" id="work_from" name="work_from" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="work_to" class="col-form-label">Work To <span class="text-danger">*</span></label>
                                    <input type="time" id="work_to" name="work_to" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>';
            }

            $form .= '</form>';

            $response[] = array(
                'FORM' => $form
            );

            echo json_encode($response);
        }
    }
    
    # System element
    else if($type == 'system element'){
        if(isset($_POST['element_type']) && !empty($_POST['element_type']) && isset($_POST['value'])){
            $element_type = $_POST['element_type'];
            $value = $_POST['value'];
            $element = '';

            if($element_type == 'user account details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name :</th>
                                        <td id="file_as"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Username :</th>
                                        <td id="user_code"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">User Acount Status :</th>
                                        <td id="active"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Password Expiry Date :</th>
                                        <td id="password_expiry_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Failed Login :</th>
                                        <td id="failed_login"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Last Failed Login :</th>
                                        <td id="last_failed_login"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Roles :</th>
                                        <td id="roles"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'transaction log'){
                $element = '<table id="transaction-log-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="all">Log Type</th>
                                        <th class="all">Log</th>
                                        <th class="all">Log Date</th>
                                        <th class="all">Log By</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                            </table>';
            }
            else if($element_type == 'system parameter details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Parameter :</th>
                                        <td id="parameter"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Parameter Description :</th>
                                        <td id="parameter_description"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Extension :</th>
                                        <td id="extension"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Number :</th>
                                        <td id="parameter_number"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'company details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Company Logo :</th>
                                        <td id="company_logo"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Company Name :</th>
                                        <td id="company_name"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tax ID :</th>
                                        <td id="tax_id"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Street 1 :</th>
                                        <td id="street_1"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Street 2 :</th>
                                        <td id="street_2"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City :</th>
                                        <td id="city"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">State :</th>
                                        <td id="state"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zip Code :</th>
                                        <td id="zip_code"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email :</th>
                                        <td id="email"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Telephone :</th>
                                        <td id="telephone"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Mobile :</th>
                                        <td id="mobile"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Website :</th>
                                        <td id="website"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'job position details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Job Position :</th>
                                        <td id="job_position"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Job Description :</th>
                                        <td id="job_description"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'work location details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Work Location :</th>
                                        <td id="work_location"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Street 1 :</th>
                                        <td id="street_1"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Street 2 :</th>
                                        <td id="street_2"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City :</th>
                                        <td id="city"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">State :</th>
                                        <td id="state"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zip Code :</th>
                                        <td id="zip_code"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email :</th>
                                        <td id="email"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Telephone :</th>
                                        <td id="telephone"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Mobile :</th>
                                        <td id="mobile"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'working hours details'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Working Hours :</th>
                                                    <td id="working_hours"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Schedule Type :</th>
                                                    <td id="schedule_type"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Start Date :</th>
                                                    <td id="start_date"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">End Date :</th>
                                                    <td id="end_date"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#working_hours_schedule" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-time"></i></span>
                                                <span class="d-none d-sm-block">Working Hours Schedule</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#employee" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                                <span class="d-none d-sm-block">Employee</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="working_hours_schedule" role="tabpanel">
                                            <table class="table table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Day of Week</th>
                                                        <th>Period</th>
                                                        <th>Work From</th>
                                                        <th>Work To</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Monday</th>
                                                        <td>Morning</td>
                                                        <td id="monday_morning_work_from"></td>
                                                        <td id="monday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Monday</th>
                                                        <td>Afternoon</td>
                                                        <td id="monday_afternoon_work_from"></td>
                                                        <td id="monday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Tueday</th>
                                                        <td>Morning</td>
                                                        <td id="tuesday_morning_work_from"></td>
                                                        <td id="tuesday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Tueday</th>
                                                        <td>Afternoon</td>
                                                        <td id="tuesday_afternoon_work_from"></td>
                                                        <td id="tuesday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Wednesday</th>
                                                        <td>Morning</td>
                                                        <td id="wednesday_morning_work_from"></td>
                                                        <td id="wednesday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Wednesday</th>
                                                        <td>Afternoon</td>
                                                        <td id="wednesday_afternoon_work_from"></td>
                                                        <td id="wednesday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Thursday</th>
                                                        <td>Morning</td>
                                                        <td id="thursday_morning_work_from"></td>
                                                        <td id="thursday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Thursday</th>
                                                        <td>Afternoon</td>
                                                        <td id="thursday_afternoon_work_from"></td>
                                                        <td id="thursday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Friday</th>
                                                        <td>Morning</td>
                                                        <td id="friday_morning_work_from"></td>
                                                        <td id="friday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Friday</th>
                                                        <td>Afternoon</td>
                                                        <td id="friday_afternoon_work_from"></td>
                                                        <td id="friday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Saturday</th>
                                                        <td>Morning</td>
                                                        <td id="saturday_morning_work_from"></td>
                                                        <td id="saturday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Saturday</th>
                                                        <td>Afternoon</td>
                                                        <td id="saturday_afternoon_work_from"></td>
                                                        <td id="saturday_afternoon_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sunday</th>
                                                        <td>Morning</td>
                                                        <td id="sunday_morning_work_from"></td>
                                                        <td id="sunday_morning_work_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Sunday</th>
                                                        <td>Afternoon</td>
                                                        <td id="sunday_afternoon_work_from"></td>
                                                        <td id="sunday_afternoon_work_to"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="employee" role="tabpanel">
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
            else if($element_type == 'attendance details'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Employee :</th>
                                                    <td id="employee" colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Late :</th>
                                                    <td id="late"></td>
                                                    <th scope="row">Early Leave :</th>
                                                    <td id="early_leave"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Overtime :</th>
                                                    <td id="overtime"></td>
                                                    <th scope="row">Total Working Hours :</th>
                                                    <td id="total_working_hours"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Remarks :</th>
                                                    <td id="remarks" colspan="3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#attendance_record" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-time"></i></span>
                                                <span class="d-none d-sm-block">Attendance Record</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#attendance_adjustment" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-time-five"></i></span>
                                                <span class="d-none d-sm-block">Attendance Adjustment</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="attendance_record" role="tabpanel">
                                            <table class="table table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Time In / Time Out</th>
                                                        <th>Attendance Record</th>
                                                        <th>Behavior</th>
                                                        <th>Location</th>
                                                        <th>IP Address</th>
                                                        <th>Attendance By</th>
                                                        <th>Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Time In</th>
                                                        <td id="time_in"></td>
                                                        <td id="time_in_behavior"></td>
                                                        <td id="time_in_location"></td>
                                                        <td id="time_in_ip_address"></td>
                                                        <td id="time_in_by"></td>
                                                        <td id="time_in_note"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Time Out</th>
                                                        <td id="time_out"></td>
                                                        <td id="time_out_behavior"></td>
                                                        <td id="time_out_location"></td>
                                                        <td id="time_out_ip_address"></td>
                                                        <td id="time_out_by"></td>
                                                        <td id="time_out_note"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="attendance_adjustment" role="tabpanel"></div>
                                    </div>
                                </div>
                            </div>';
            }
            else if($element_type == 'attendance adjustment details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Employee :</th>
                                        <td id="employee"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Time In :</th>
                                        <td id="time_in"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Time Out :</th>
                                        <td id="time_out"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Reason :</th>
                                        <td id="reason"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status :</th>
                                        <td id="adjustment_status"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sanction :</th>
                                        <td id="sanction"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Attachment :</th>
                                        <td id="attachment"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created Date :</th>
                                        <td id="created_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">For Recommendation Date :</th>
                                        <td id="for_recommendation_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation Date :</th>
                                        <td id="recommendation_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation By :</th>
                                        <td id="recommendation_by"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation Remarks :</th>
                                        <td id="recommendation_remarks"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision Date :</th>
                                        <td id="decision_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision By :</th>
                                        <td id="decision_by"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision Remarks :</th>
                                        <td id="decision_remarks"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'attendance creation details'){
                $element = '<table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Employee :</th>
                                        <td id="employee"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Time In :</th>
                                        <td id="time_in"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Time Out :</th>
                                        <td id="time_out"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Reason :</th>
                                        <td id="reason"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status :</th>
                                        <td id="creation_status"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sanction :</th>
                                        <td id="sanction"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Attachment :</th>
                                        <td id="attachment"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created Date :</th>
                                        <td id="created_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">For Recommendation Date :</th>
                                        <td id="for_recommendation_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation Date :</th>
                                        <td id="recommendation_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation By :</th>
                                        <td id="recommendation_by"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Recommendation Remarks :</th>
                                        <td id="recommendation_remarks"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision Date :</th>
                                        <td id="decision_date"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision By :</th>
                                        <td id="decision_by"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Decision Remarks :</th>
                                        <td id="decision_remarks"></td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            else if($element_type == 'approval type details'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Approval Type :</th>
                                                    <td id="approval_type"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Approval Type Description :</th>
                                                    <td id="approval_type_description"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#approvers" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-user-check"></i></span>
                                                <span class="d-none d-sm-block">Approvers</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#exceptions" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-user-x"></i></span>
                                                <span class="d-none d-sm-block">Approval Exception</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="approvers" role="tabpanel"></div>
                                        <div class="tab-pane" id="exceptions" role="tabpanel"></div>
                                    </div>
                                </div>
                            </div>';
            }
            else if($element_type == 'scan badge form'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div id="badge-reader"></div>
                                </div>
                            </div>';
            }
            else if($element_type == 'public holiday details'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Public Holiday :</th>
                                                    <td id="public_holiday"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Holiday Date :</th>
                                                    <td id="holiday_date"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Holiday Type :</th>
                                                    <td id="holiday_type"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#work_location" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-map"></i></span>
                                                <span class="d-none d-sm-block">Work Location</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="work_location" role="tabpanel"></div>
                                    </div>
                                </div>
                            </div>';
            }
            else if($element_type == 'leave details'){
                $element = '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Employee :</th>
                                                    <td id="employee" colspan="4"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Leave Type :</th>
                                                    <td id="leave_type"></td>
                                                    <th scope="row">Leave Date :</th>
                                                    <td id="leave_date"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Start Time :</th>
                                                    <td id="start_time"></td>
                                                    <th scope="row">End Time :</th>
                                                    <td id="end_time"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Total Hours :</th>
                                                    <td id="total_hours"></td>
                                                    <th scope="row">Status :</th>
                                                    <td id="leave_status"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Reason :</th>
                                                    <td id="reason" colspan="4"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Created Date :</th>
                                                    <td id="created_date"></td>
                                                    <th scope="row">For Approval Date :</th>
                                                    <td id="for_approval_date"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Decision Date :</th>
                                                    <td id="decision_date"></td>
                                                    <th scope="row">Decision By :</th>
                                                    <td id="decision_by"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Decision Remarks :</th>
                                                    <td id="decision_remarks" colspan="4"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#supporting_documents" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-time"></i></span>
                                                <span class="d-none d-sm-block">Supporting Documents</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="supporting_documents" role="tabpanel"></div>
                                    </div>
                                </div>
                            </div>';
            }

            $response[] = array(
                'ELEMENT' => $element
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate table functions
    # -------------------------------------------------------------

    # Transaction log table
    else if($type == 'transaction log table'){
        if(isset($_POST['transaction_log_id']) && !empty($_POST['transaction_log_id'])){
            if ($api->databaseConnection()) {
                $transaction_log_id = $_POST['transaction_log_id'];
    
                $sql = $api->db_connection->prepare('SELECT USERNAME, LOG_TYPE, LOG_DATE, LOG FROM global_transaction_log WHERE TRANSACTION_LOG_ID = :transaction_log_id');
                $sql->bindValue(':transaction_log_id', $transaction_log_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
                        $log_type = $row['LOG_TYPE'];
                        $log = $row['LOG'];
                        $log_date = $api->check_date('empty', $row['LOG_DATE'], '', 'm/d/Y h:i:s a', '', '', '');
    
                        $response[] = array(
                            'LOG_TYPE' => $log_type,
                            'LOG' => $log,
                            'LOG_DATE' => $log_date,
                            'LOG_BY' => $username
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Modules table
    else if($type == 'modules table'){
        if(isset($_POST['filter_module_category'])){
            if ($api->databaseConnection()) {
                $filter_module_category = $_POST['filter_module_category'];

                $query = 'SELECT MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY FROM technical_module';

                if(!empty($filter_module_category)){
                    $query .= ' WHERE MODULE_CATEGORY = :filter_module_category';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_module_category)){
                    $sql->bindValue(':filter_module_category', $filter_module_category);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $module_id = $row['MODULE_ID'];
                        $module_name = $row['MODULE_NAME'];
                        $module_version = $row['MODULE_VERSION'];
                        $module_description = $row['MODULE_DESCRIPTION'];
                        $module_category = $row['MODULE_CATEGORY'];
    
                        $system_code_details = $api->get_system_code_details(null, 'MODULECAT', $module_category);
                        $module_category_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;
    
                        $module_id_encrypted = $api->encrypt_data($module_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $module_id .'">',
                            'MODULE_ID' => $module_id,
                            'MODULE_NAME' => $module_name . '<p class="text-muted mb-0">'. $module_description .'</p>' . '<p class="text-muted mb-0"> Version: '. $module_version .'</p>',
                            'MODULE_CATEGORY' => $module_category_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="module-form.php?id='. $module_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Module">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Module access table
    else if($type == 'module access table'){
        if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
            if ($api->databaseConnection()) {
                $module_id = $_POST['module_id'];

                $update_module = $api->check_role_access_rights($username, '2', 'action');
                $delete_module_access_right = $api->check_role_access_rights($username, '5', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID FROM technical_module_access_rights WHERE MODULE_ID = :module_id');
                $sql->bindValue(':module_id', $module_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];

                        $role_details = $api->get_role_details($role_id);
                        $role = $role_details[0]['ROLE'] ?? null;

                        if($delete_module_access_right > 0 && $update_module > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-module-access" data-module-id="'. $module_id .'" data-role-id="'. $role_id .'" title="Delete Module Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ROLE' => $role,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Pages table
    else if($type == 'pages table'){
        if(isset($_POST['filter_module'])){
            if ($api->databaseConnection()) {
                $filter_module = $_POST['filter_module'];

                $query = 'SELECT PAGE_ID, PAGE_NAME, MODULE_ID FROM technical_page';

                if(!empty($filter_module)){
                    $query .= ' WHERE MODULE_ID = :filter_module';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_module)){
                    $sql->bindValue(':filter_module', $filter_module);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $page_id = $row['PAGE_ID'];
                        $page_name = $row['PAGE_NAME'];
                        $module_id = $row['MODULE_ID'];
    
                        $module_details = $api->get_module_details($module_id);
                        $module_name = $module_details[0]['MODULE_NAME'] ?? null;
    
                        $page_id_encrypted = $api->encrypt_data($page_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $page_id .'">',
                            'PAGE_ID' => $page_id,
                            'PAGE_NAME' => $page_name,
                            'MODULE' => $module_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="page-form.php?id='. $page_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Page">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Page access table
    else if($type == 'page access table'){
        if(isset($_POST['page_id']) && !empty($_POST['page_id'])){
            if ($api->databaseConnection()) {
                $page_id = $_POST['page_id'];

                $update_page = $api->check_role_access_rights($username, '7', 'action');
                $delete_page_access_right = $api->check_role_access_rights($username, '10', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID FROM technical_page_access_rights WHERE PAGE_ID = :page_id');
                $sql->bindValue(':page_id', $page_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];

                        $role_details = $api->get_role_details($role_id);
                        $role = $role_details[0]['ROLE'] ?? null;

                        if($delete_page_access_right > 0 && $update_page > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-page-access" data-page-id="'. $page_id .'" data-role-id="'. $role_id .'" title="Delete Page Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ROLE' => $role,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Actions table
    else if($type == 'actions table'){
        if ($api->databaseConnection()) {

            $sql = $api->db_connection->prepare('SELECT ACTION_ID, ACTION_NAME FROM technical_action');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $action_id = $row['ACTION_ID'];
                    $action_name = $row['ACTION_NAME'];

                    $action_id_encrypted = $api->encrypt_data($action_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $action_id .'">',
                        'ACTION_ID' => $action_id,
                        'ACTION_NAME' => $action_name,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="action-form.php?id='. $action_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Action">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Action access table
    else if($type == 'action access table'){
        if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
            if ($api->databaseConnection()) {
                $action_id = $_POST['action_id'];

                $update_action = $api->check_role_access_rights($username, '12', 'action');
                $delete_action_access_right = $api->check_role_access_rights($username, '15', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID FROM technical_action_access_rights WHERE ACTION_ID = :action_id');
                $sql->bindValue(':action_id', $action_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];

                        $role_details = $api->get_role_details($role_id);
                        $role = $role_details[0]['ROLE'] ?? null;

                        if($delete_action_access_right > 0 && $update_action > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-action-access" data-action-id="'. $action_id .'" data-role-id="'. $role_id .'" title="Delete Action Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ROLE' => $role,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # System parameter table
    else if($type == 'system parameters table'){
        if ($api->databaseConnection()) {

            $sql = $api->db_connection->prepare('SELECT PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER FROM global_system_parameters');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $parameter_id = $row['PARAMETER_ID'];
                    $parameter = $row['PARAMETER'];
                    $parameter_description = $row['PARAMETER_DESCRIPTION'];
                    $parameter_extension = $row['PARAMETER_EXTENSION'];
                    $parameter_number = $row['PARAMETER_NUMBER'];

                    $parameter_id_encrypted = $api->encrypt_data($parameter_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $parameter_id .'">',
                        'PARAMETER_ID' => $parameter_id,
                        'PARAMETER' => $parameter . '<p class="text-muted mb-0">'. $parameter_description .'</p>',
                        'PARAMETER_EXTENSION' => $parameter_extension,
                        'PARAMETER_NUMBER' => $parameter_number,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="system-parameter-form.php?id='. $parameter_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View System Parameter">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Role table
    else if($type == 'roles table'){
        if(isset($_POST['filter_assignable'])){
            if ($api->databaseConnection()) {
                $filter_assignable = $_POST['filter_assignable'];

                $query = 'SELECT ROLE_ID, ROLE, ROLE_DESCRIPTION, ASSIGNABLE FROM global_role';

                if(!empty($filter_assignable)){
                    $query .= ' WHERE ASSIGNABLE = :filter_assignable';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_assignable)){
                    $sql->bindValue(':filter_assignable', $filter_assignable);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
                        $role_description = $row['ROLE_DESCRIPTION'];
                        $assignable = $row['ASSIGNABLE'];
    
                        $assignable_status = $api->get_roles_assignable_status($assignable)[0]['BADGE'] ?? null;
    
                        $role_id_encrypted = $api->encrypt_data($role_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE_ID' => $role_id,
                            'ROLE' => $role . '<p class="text-muted mb-0">'. $role_description .'</p>',
                            'ASSIGNABLE' => $assignable_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="role-form.php?id='. $role_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Role">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role module access table
    else if($type == 'role module access table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];

                $update_role = $api->check_role_access_rights($username, '21', 'action');
                $delete_role_module_access = $api->check_role_access_rights($username, '24', 'action');
    
                $sql = $api->db_connection->prepare('SELECT MODULE_ID FROM technical_module_access_rights WHERE ROLE_ID = :role_id');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $module_id = $row['MODULE_ID'];

                        $module_details = $api->get_module_details($module_id);
                        $module_name = $module_details[0]['MODULE_NAME'] ?? null;

                        if($delete_role_module_access > 0 && $update_role > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-module-access" data-module-id="'. $module_id .'" data-role-id="'. $role_id .'" title="Delete Module Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'MODULE_NAME' => $module_name,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role page access table
    else if($type == 'role page access table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];

                $update_role = $api->check_role_access_rights($username, '21', 'action');
                $delete_role_page_access = $api->check_role_access_rights($username, '26', 'action');
    
                $sql = $api->db_connection->prepare('SELECT PAGE_ID FROM technical_page_access_rights WHERE ROLE_ID = :role_id');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $page_id = $row['PAGE_ID'];

                        $page_details = $api->get_page_details($page_id);
                        $page_name = $page_details[0]['PAGE_NAME'] ?? null;

                        if($delete_role_page_access > 0 && $update_role > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-page-access" data-page-id="'. $page_id .'" data-role-id="'. $role_id .'" title="Delete Page Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'PAGE_NAME' => $page_name,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role action access table
    else if($type == 'role action access table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];

                $update_role = $api->check_role_access_rights($username, '21', 'action');
                $delete_role_action_access = $api->check_role_access_rights($username, '26', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ACTION_ID FROM technical_action_access_rights WHERE ROLE_ID = :role_id');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $action_id = $row['ACTION_ID'];

                        $action_details = $api->get_action_details($action_id);
                        $action_name = $action_details[0]['ACTION_NAME'] ?? null;

                        if($delete_role_action_access > 0 && $update_role > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-action-access" data-action-id="'. $action_id .'" data-role-id="'. $role_id .'" title="Delete Action Access">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ACTION_NAME' => $action_name,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role user account table
    else if($type == 'role user account table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];

                $update_role = $api->check_role_access_rights($username, '21', 'action');
                $delete_role_user_account = $api->check_role_access_rights($username, '30', 'action');
    
                $sql = $api->db_connection->prepare('SELECT USERNAME FROM global_role_user_account WHERE ROLE_ID = :role_id');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];

                        $user_account_details = $api->get_user_account_details($username);
                        $file_as = $user_account_details[0]['FILE_AS'];

                        if($delete_role_user_account > 0 && $update_role > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-user-account" data-user-id="'. $username .'" data-role-id="'. $role_id .'" title="Delete User Account">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'USERNAME' => $file_as . '<p class="text-muted mb-0">'. $username .'</p>',
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Module role assignment table
    else if($type == 'module role assignment table'){
        if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
            if ($api->databaseConnection()) {
                $module_id = $_POST['module_id'];
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE FROM global_role WHERE ROLE_ID NOT IN (SELECT ROLE_ID FROM technical_module_access_rights WHERE MODULE_ID = :module_id)');
                $sql->bindValue(':module_id', $module_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE' => $role
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Page role assignment table
    else if($type == 'page role assignment table'){
        if(isset($_POST['page_id']) && !empty($_POST['page_id'])){
            if ($api->databaseConnection()) {
                $page_id = $_POST['page_id'];
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE FROM global_role WHERE ROLE_ID NOT IN (SELECT ROLE_ID FROM technical_page_access_rights WHERE PAGE_ID = :page_id)');
                $sql->bindValue(':page_id', $page_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE' => $role
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Action role assignment table
    else if($type == 'action role assignment table'){
        if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
            if ($api->databaseConnection()) {
                $action_id = $_POST['action_id'];
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE FROM global_role WHERE ROLE_ID NOT IN (SELECT ROLE_ID FROM technical_action_access_rights WHERE ACTION_ID = :action_id)');
                $sql->bindValue(':action_id', $action_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE' => $role
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role module access assignment table
    else if($type == 'role module access assignment table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];
    
                $sql = $api->db_connection->prepare('SELECT MODULE_ID, MODULE_NAME FROM technical_module WHERE MODULE_ID NOT IN (SELECT MODULE_ID FROM technical_module_access_rights WHERE ROLE_ID = :role_id)');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $module_id = $row['MODULE_ID'];
                        $module_name = $row['MODULE_NAME'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $module_id .'">',
                            'MODULE_NAME' => $module_name
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role page access assignment table
    else if($type == 'role page access assignment table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];
    
                $sql = $api->db_connection->prepare('SELECT PAGE_ID, PAGE_NAME FROM technical_page WHERE PAGE_ID NOT IN (SELECT PAGE_ID FROM technical_page_access_rights WHERE ROLE_ID = :role_id)');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $page_id = $row['PAGE_ID'];
                        $page_name = $row['PAGE_NAME'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $page_id .'">',
                            'PAGE_NAME' => $page_name
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role action access assignment table
    else if($type == 'role action access assignment table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];
    
                $sql = $api->db_connection->prepare('SELECT ACTION_ID, ACTION_NAME FROM technical_action WHERE ACTION_ID NOT IN (SELECT ACTION_ID FROM technical_action_access_rights WHERE ROLE_ID = :role_id)');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $action_id = $row['ACTION_ID'];
                        $action_name = $row['ACTION_NAME'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $action_id .'">',
                            'ACTION_NAME' => $action_name
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Role user account assignment table
    else if($type == 'role user account assignment table'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            if ($api->databaseConnection()) {
                $role_id = $_POST['role_id'];
    
                $sql = $api->db_connection->prepare('SELECT USERNAME, FILE_AS FROM global_user_account WHERE USERNAME NOT IN (SELECT USERNAME FROM global_role_user_account WHERE ROLE_ID = :role_id)');
                $sql->bindValue(':role_id', $role_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
                        $file_as = $row['FILE_AS'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $username .'">',
                            'FILE_AS' => $file_as . '<p class="text-muted mb-0">'. $username .'</p>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # System code table
    else if($type == 'system codes table'){
        if ($api->databaseConnection()) {

            $sql = $api->db_connection->prepare('SELECT SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $system_code_id = $row['SYSTEM_CODE_ID'];
                    $system_type = $row['SYSTEM_TYPE'];
                    $system_code = $row['SYSTEM_CODE'];
                    $system_decription = $row['SYSTEM_DESCRIPTION'];

                    $system_code_id_encrypted = $api->encrypt_data($system_code_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $system_code_id .'">',
                        'SYSTEM_CODE_ID' => $system_code_id,
                        'SYSTEM_TYPE' => $system_type,
                        'SYSTEM_CODE' => $system_code,
                        'SYSTEM_DESCRIPTION' => $system_decription,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="system-code-form.php?id='. $system_code_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View System Code">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Upload settings table
    else if($type == 'upload settings table'){
        if ($api->databaseConnection()) {

            $sql = $api->db_connection->prepare('SELECT UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE FROM global_upload_setting');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $file_type = '';
                    $upload_setting_id = $row['UPLOAD_SETTING_ID'];
                    $upload_setting = $row['UPLOAD_SETTING'];
                    $description = $row['DESCRIPTION'];
                    $max_file_size = $row['MAX_FILE_SIZE'];

                    $upload_setting_id_encrypted = $api->encrypt_data($upload_setting_id);

                    $upload_file_type_details = $api->get_upload_file_type_details($upload_setting_id);

                    for($i = 0; $i < count($upload_file_type_details); $i++) {
                        $system_code_details = $api->get_system_code_details(null, 'FILETYPE', $upload_file_type_details[$i]['FILE_TYPE']);

                        $file_type .= '<span class="badge bg-info font-size-11">'. $system_code_details[0]['SYSTEM_DESCRIPTION'] .'</span> ';

                        if(($i + 1) % 4 == 0){
                            $file_type .= '<br/>';
                        }
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $upload_setting_id .'">',
                        'UPLOAD_SETTING_ID' => $upload_setting_id,
                        'UPLOAD_SETTING' => $upload_setting . '<p class="text-muted mb-0">'. $description .'</p>',
                        'MAX_FILE_SIZE' => $max_file_size . ' mb',
                        'FILE_TYPE' => $file_type,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="upload-setting-form.php?id='. $upload_setting_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Upload Setting">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Upload setting file type table
    else if($type == 'upload setting file type table'){
        if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            if ($api->databaseConnection()) {
                $upload_setting_id = $_POST['upload_setting_id'];

                $update_upload_setting = $api->check_role_access_rights($username, '32', 'action');
                $delete_upload_setting_file_type = $api->check_role_access_rights($username, '38', 'action');
    
                $sql = $api->db_connection->prepare('SELECT FILE_TYPE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = :upload_setting_id');
                $sql->bindValue(':upload_setting_id', $upload_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $file_type = $row['FILE_TYPE'];

                        $system_code_details = $api->get_system_code_details(null, 'FILETYPE', $file_type);
                        $file_type_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;

                        if($delete_upload_setting_file_type > 0 && $update_upload_setting > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-upload-setting-file-type" data-upload-setting-id="'. $upload_setting_id .'" data-file-type="'. $file_type
                            .'" title="Delete Upload Setting File Type">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'FILE_TYPE' => $file_type_name,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # File type assignment table
    else if($type == 'file type assignment table'){
        if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            if ($api->databaseConnection()) {
                $upload_setting_id = $_POST['upload_setting_id'];
    
                $sql = $api->db_connection->prepare('SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = :system_type AND SYSTEM_CODE NOT IN (SELECT FILE_TYPE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = :upload_setting_id)');
                $sql->bindValue(':system_type', 'FILETYPE');
                $sql->bindValue(':upload_setting_id', $upload_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $system_code = $row['SYSTEM_CODE'];
                        $system_description = $row['SYSTEM_DESCRIPTION'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $system_code .'">',
                            'SYSTEM_DESCRIPTION' => $system_description
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Company table
    else if($type == 'company table'){
        if ($api->databaseConnection()) {

            $sql = $api->db_connection->prepare('SELECT COMPANY_ID, COMPANY_NAME FROM global_company');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $company_id = $row['COMPANY_ID'];
                    $company_name = $row['COMPANY_NAME'];
                    $company_id_encrypted = $api->encrypt_data($company_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $company_id .'">',
                        'COMPANY_ID' => $company_id,
                        'COMPANY_NAME' => $company_name,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="company-form.php?id='. $company_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Company">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Interface settings table
    else if($type == 'interface settings table'){
        if(isset($_POST['filter_status'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];

                $query = 'SELECT INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS FROM global_interface_setting';

                if(!empty($filter_status)){
                    $query .= ' WHERE STATUS = :filter_status';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status)){
                    $sql->bindValue(':filter_status', $filter_status);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $interface_setting_id = $row['INTERFACE_SETTING_ID'];
                        $interface_setting_name = $row['INTERFACE_SETTING_NAME'];
                        $description = $row['DESCRIPTION'];
                        $status = $row['STATUS'];
    
                        $interface_setting_status = $api->get_interface_setting_status($status)[0]['BADGE'];
    
                        $interface_setting_id_encrypted = $api->encrypt_data($interface_setting_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $interface_setting_id .'">',
                            'INTERFACE_SETTING_ID' => $interface_setting_id,
                            'INTERFACE_SETTING_NAME' => $interface_setting_name . '<p class="text-muted mb-0">'. $description .'</p>',
                            'STATUS' => $interface_setting_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="interface-setting-form.php?id='. $interface_setting_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Interface Setting">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Email settings table
    else if($type == 'email settings table'){
        if(isset($_POST['filter_status'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];

                $query = 'SELECT EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS FROM global_email_setting';

                if(!empty($filter_status)){
                    $query .= ' WHERE STATUS = :filter_status';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status)){
                    $sql->bindValue(':filter_status', $filter_status);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $email_setting_id = $row['EMAIL_SETTING_ID'];
                        $email_setting_name = $row['EMAIL_SETTING_NAME'];
                        $description = $row['DESCRIPTION'];
                        $status = $row['STATUS'];
    
                        $email_setting_status = $api->get_email_setting_status($status)[0]['BADGE'];
    
                        $email_setting_id_encrypted = $api->encrypt_data($email_setting_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $email_setting_id .'">',
                            'EMAIL_SETTING_ID' => $email_setting_id,
                            'EMAIL_SETTING_NAME' => $email_setting_name . '<p class="text-muted mb-0">'. $description .'</p>',
                            'STATUS' => $email_setting_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="email-setting-form.php?id='. $email_setting_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Email Setting">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification settings table
    else if($type == 'notification settings table'){
        if(isset($_POST['filter_notification_channel'])){
            if ($api->databaseConnection()) {
                $filter_notification_channel = $_POST['filter_notification_channel'];

                $query = 'SELECT NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, DESCRIPTION FROM global_notification_setting';

                if(!empty($filter_notification_channel)){
                    $query .= ' WHERE NOTIFICATION_SETTING_ID IN (SELECT NOTIFICATION_SETTING_ID FROM global_notification_channel WHERE CHANNEL = :filter_notification_channel)';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_notification_channel)){
                    $sql->bindValue(':filter_notification_channel', $filter_notification_channel);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $notification_setting_id = $row['NOTIFICATION_SETTING_ID'];
                        $notification_setting = $row['NOTIFICATION_SETTING'];
                        $description = $row['DESCRIPTION'];
        
                        $notification_setting_id_encrypted = $api->encrypt_data($notification_setting_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $notification_setting_id .'">',
                            'NOTIFICATION_SETTING_ID' => $notification_setting_id,
                            'NOTIFICATION_SETTING' => $notification_setting . '<p class="text-muted mb-0">'. $description .'</p>',
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="notification-setting-form.php?id='. $notification_setting_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Notification Setting">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification role recipient table
    else if($type == 'notification role recipient table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];

                $update_notification_setting = $api->check_role_access_rights($username, '53', 'action');
                $delete_notification_role_recipient = $api->check_role_access_rights($username, '56', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = :notification_setting_id');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];

                        $role_details = $api->get_role_details($role_id);
                        $role = $role_details[0]['ROLE'] ?? null;

                        if($delete_notification_role_recipient > 0 && $update_notification_setting > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-notification-role-recipient" data-notification-setting-id="'. $notification_setting_id .'" data-role-id="'. $role_id .'" title="Delete Role Recipient">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ROLE' => $role,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification user account recipient table
    else if($type == 'notification user account recipient table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];

                $update_notification_setting = $api->check_role_access_rights($username, '53', 'action');
                $delete_notification_user_account_recipient = $api->check_role_access_rights($username, '58', 'action');
    
                $sql = $api->db_connection->prepare('SELECT USERNAME FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = :notification_setting_id');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];

                        $user_account_details = $api->get_user_account_details($username);
                        $file_as = $user_account_details[0]['FILE_AS'];

                        if($delete_notification_user_account_recipient > 0 && $update_notification_setting > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-notification-user-account-recipient" data-notification-setting-id="'. $notification_setting_id .'" data-user-id="'. $username .'" title="Delete User Account Recipient">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'USERNAME' => $file_as . '<p class="text-muted mb-0">'. $username .'</p>',
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification channel table
    else if($type == 'notification channel table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];

                $update_notification_setting = $api->check_role_access_rights($username, '53', 'action');
                $delete_notification_channel = $api->check_role_access_rights($username, '60', 'action');
    
                $sql = $api->db_connection->prepare('SELECT CHANNEL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = :notification_setting_id');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $channel = $row['CHANNEL'];

                        $system_code_details = $api->get_system_code_details(null, 'NOTIFICATIONCHANNEL', $channel);
                        $channel_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;

                        if($delete_notification_channel > 0 && $update_notification_setting > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-notification-channel" data-notification-setting-id="'. $notification_setting_id .'" data-channel="'. $channel .'" title="Delete Notification Channel">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'CHANNEL' => $channel_name,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification role recipient assignment table
    else if($type == 'notification role recipient assignment table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE FROM global_role WHERE ROLE_ID NOT IN (SELECT ROLE_ID FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = :notification_setting_id)');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE' => $role
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification user account recipient assignment table
    else if($type == 'notification user account recipient assignment table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];
    
                $sql = $api->db_connection->prepare('SELECT USERNAME, FILE_AS FROM global_user_account WHERE USERNAME NOT IN (SELECT USERNAME FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = :notification_setting_id)');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
                        $file_as = $row['FILE_AS'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $username .'">',
                            'USERNAME' => $file_as . '<p class="text-muted mb-0">'. $username .'</p>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Notification channel assignment table
    else if($type == 'notification channel assignment table'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            if ($api->databaseConnection()) {
                $notification_setting_id = $_POST['notification_setting_id'];
    
                $sql = $api->db_connection->prepare('SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = :system_type AND SYSTEM_CODE NOT IN (SELECT CHANNEL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = :notification_setting_id)');
                $sql->bindValue(':system_type', 'NOTIFICATIONCHANNEL');
                $sql->bindValue(':notification_setting_id', $notification_setting_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $system_code = $row['SYSTEM_CODE'];
                        $system_description = $row['SYSTEM_DESCRIPTION'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $system_code .'">',
                            'CHANNEL' => $system_description
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Country table
    else if($type == 'country table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $country_id = $row['COUNTRY_ID'];
                    $country_name = $row['COUNTRY_NAME'];

                    $country_id_encrypted = $api->encrypt_data($country_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $country_id.'">',
                        'COUNTRY_ID' => $country_id,
                        'COUNTRY_NAME' => $country_name,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="country-form.php?id='. $country_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Country">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Country state table
    else if($type == 'country state table'){
        if(isset($_POST['country_id']) && !empty($_POST['country_id'])){
            if ($api->databaseConnection()) {
                $country_id = $_POST['country_id'];

                $update_country = $api->check_role_access_rights($username, '62', 'action');
                $update_state = $api->check_role_access_rights($username, '65', 'action');
                $delete_state = $api->check_role_access_rights($username, '66', 'action');
    
                $sql = $api->db_connection->prepare('SELECT STATE_ID, STATE_NAME FROM global_state WHERE COUNTRY_ID = :country_id');
                $sql->bindValue(':country_id', $country_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $state_id = $row['STATE_ID'];
                        $state_name = $row['STATE_NAME'];

                        if($update_state > 0 && $update_country > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-state" data-country-id="'. $country_id .'" data-state-id="'. $state_id .'" title="Edit State">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_state > 0 && $update_country > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-state" data-country-id="'. $country_id .'" data-state-id="'. $state_id .'" title="Delete State">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'STATE_NAME' => $state_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # State table
    else if($type == 'state table'){
        if(isset($_POST['filter_country'])){
            if ($api->databaseConnection()) {
                $filter_country = $_POST['filter_country'];

                $query = 'SELECT STATE_ID, STATE_NAME, COUNTRY_ID FROM global_state';

                if(!empty($filter_country)){
                    $query .= ' WHERE COUNTRY_ID = :filter_country';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_country)){
                    $sql->bindValue(':filter_country', $filter_country);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $state_id = $row['STATE_ID'];
                        $state_name = $row['STATE_NAME'];
                        $country_id = $row['COUNTRY_ID'];

                        $country_details = $api->get_country_details($country_id);
                        $country_name = $country_details[0]['COUNTRY_NAME'] ?? null;
    
                        $state_id_encrypted = $api->encrypt_data($state_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $state_id.'">',
                            'STATE_ID' => $state_id,
                            'STATE_NAME' => $state_name,
                            'COUNTRY' => $country_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="state-form.php?id='. $state_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View State">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Zoom API table
    else if($type == 'zoom api table'){
        if(isset($_POST['filter_status'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];

                $query = 'SELECT ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, STATUS FROM global_zoom_api';

                if(!empty($filter_status)){
                    $query .= ' WHERE STATUS = :filter_status';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status)){
                    $sql->bindValue(':filter_status', $filter_status);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $zoom_api_id = $row['ZOOM_API_ID'];
                        $email_setting_name = $row['ZOOM_API_NAME'];
                        $description = $row['DESCRIPTION'];
                        $status = $row['STATUS'];
    
                        $zoom_api_status = $api->get_zoom_api_status($status)[0]['BADGE'];
    
                        $zoom_api_id_encrypted = $api->encrypt_data($zoom_api_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $zoom_api_id .'">',
                            'ZOOM_API_ID' => $zoom_api_id,
                            'ZOOM_API_NAME' => $email_setting_name . '<p class="text-muted mb-0">'. $description .'</p>',
                            'STATUS' => $zoom_api_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="zoom-api-form.php?id='. $zoom_api_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Zoom API">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------
    
    # User accounts table
    else if($type == 'user accounts table'){
        if(isset($_POST['filter_user_account_lock_status']) && isset($_POST['filter_user_account_status']) && isset($_POST['filter_start_date']) && isset($_POST['filter_end_date']) && isset($_POST['filter_last_connection_start_date']) && isset($_POST['filter_last_connection_end_date'])){
            if ($api->databaseConnection()) {
                $filter_user_account_lock_status = $_POST['filter_user_account_lock_status'];
                $filter_user_account_status = $_POST['filter_user_account_status'];
                $filter_last_connection_start_date = $api->check_date('empty', $_POST['filter_last_connection_start_date'], '', 'Y-m-d', '', '', '');
                $filter_last_connection_end_date = $api->check_date('empty', $_POST['filter_last_connection_end_date'], '', 'Y-m-d', '', '', '');
                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');

                $query = 'SELECT USERNAME, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_CONNECTION_DATE, TRANSACTION_LOG_ID FROM global_user_account';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || (!empty($filter_last_connection_start_date) && !empty($filter_last_connection_end_date)) || $filter_user_account_status != '' || !empty($filter_user_account_lock_status)){
                    $query .= ' WHERE ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'PASSWORD_EXPIRY_DATE BETWEEN :filter_start_date AND :filter_end_date';
                    }

                    if(!empty($filter_last_connection_start_date) && !empty($filter_last_connection_end_date)){
                        $filter[] = 'LAST_CONNECTION_DATE BETWEEN :filter_last_connection_start_date AND :filter_last_connection_end_date';
                    }

                    if($filter_user_account_lock_status == 'locked'){
                        $filter[] = 'FAILED_LOGIN >= 5';
                    }
                    else {
                        $filter[] = 'FAILED_LOGIN < 5';
                    }

                    if($filter_user_account_status != ''){
                        $filter[] = 'USER_STATUS = :filter_user_account_status';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if((!empty($filter_start_date) && !empty($filter_end_date)) || (!empty($filter_last_connection_start_date) && !empty($filter_last_connection_end_date)) || $filter_user_account_status != ''){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
                    }

                    if(!empty($filter_last_connection_start_date) && !empty($filter_last_connection_end_date)){
                        $sql->bindValue(':filter_last_connection_start_date', $filter_last_connection_start_date);
                        $sql->bindValue(':filter_last_connection_end_date', $filter_last_connection_end_date);
                    }

                    if($filter_user_account_status != ''){
                        $sql->bindValue(':filter_user_account_status', $filter_user_account_status);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
                        $file_as = $row['FILE_AS'];
                        $user_status = $row['USER_STATUS'];
                        $password_expiry_date = $api->check_date('empty', $row['PASSWORD_EXPIRY_DATE'], '', 'm/d/Y', '', '', '');
                        $last_connection_date = $api->check_date('empty', $row['LAST_CONNECTION_DATE'], '', 'm/d/Y h:i:s a', '', '', '');
                        $failed_login = $row['FAILED_LOGIN'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                        $lock_status = $api->get_user_account_lock_status($failed_login)[0]['BADGE'];
                        $account_status = $api->get_user_account_status($user_status)[0]['BADGE'];
                        $password_expiry_date_difference = $api->get_date_difference($system_date, $password_expiry_date);
                        $expiry_difference = 'Expiring in ' . $password_expiry_date_difference[0]['MONTHS'] . ' ' . $password_expiry_date_difference[0]['DAYS'];

                        $user_id_encrypted = $api->encrypt_data($username);

                        if($failed_login >= 5){
                            $data_lock = '1';
                        }
                        else{
                            $data_lock = '0';
                        }
    
                        if($user_status == 'Active'){
                            $data_active = '1';
                        }
                        else{
                            $data_active = '0';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-lock="'. $data_lock .'" data-active="'. $data_active .'" value="'. $username .'">',
                            'USERNAME' =>  $file_as . '<p class="text-muted mb-0">'. $username .'</p>',
                            'ACCOUNT_STATUS' => $account_status,
                            'LOCK_STATUS' => $lock_status,
                            'PASSWORD_EXPIRY_DATE' => $password_expiry_date . '<p class="text-muted mb-0">'. $expiry_difference .'</p>',
                            'LAST_CONNECTION_DATE' => $last_connection_date,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="user-account-form.php?id='. $user_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View User Account">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # User account role table
    else if($type == 'user account role table'){
        if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
            if ($api->databaseConnection()) {
                $user_id = $_POST['user_id'];

                $update_user_account = $api->check_role_access_rights($username, '73', 'action');
                $delete_user_account_role = $api->check_role_access_rights($username, '80', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = :user_id');
                $sql->bindValue(':user_id', $user_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];

                        $role_details = $api->get_role_details($role_id);
                        $role = $role_details[0]['ROLE'] ?? null;

                        if($delete_user_account_role > 0 && $update_user_account > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-user-account-role" data-user-id="'. $user_id .'" data-role-id="'. $role_id .'" title="Delete User Account Role">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ROLE' => $role,
                            'ACTION' => $delete
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # User account role assignment table
    else if($type == 'user account role assignment table'){
        if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
            if ($api->databaseConnection()) {
                $user_id = $_POST['user_id'];
    
                $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE FROM global_role WHERE ROLE_ID NOT IN (SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = :user_id)');
                $sql->bindValue(':user_id', $user_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">',
                            'ROLE' => $role
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Departments table
    else if($type == 'departments table'){
        if(isset($_POST['filter_status'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];

                $query = 'SELECT DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS FROM employee_department';

                if(!empty($filter_status)){
                    $query .= ' WHERE STATUS = :filter_status';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status)){
                    $sql->bindValue(':filter_status', $filter_status);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $department_id = $row['DEPARTMENT_ID'];
                        $department = $row['DEPARTMENT'];
                        $parent_department = $row['PARENT_DEPARTMENT'];
                        $manager = $row['MANAGER'];
                        $status = $row['STATUS'];

                        if($status == 1){
                            $data_archive = '1';
                        }
                        else{
                            $data_archive = '0';
                        }
    
                        $department_status = $api->get_department_status($status)[0]['BADGE'];

                        $department_details = $api->get_department_details($parent_department);
                        $parent_department_name = $department_details[0]['DEPARTMENT'] ?? null;
    
                        $department_id_encrypted = $api->encrypt_data($department_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-archive="'. $data_archive .'" type="checkbox" value="'. $department_id .'">',
                            'DEPARTMENT_ID' => $department_id,
                            'DEPARTMENT' => $department,
                            'STATUS' => $department_status,
                            'MANAGER' => $manager,
                            'EMPLOYEES' => 0,
                            'PARENT_DEPARTMENT' => $parent_department_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="department-form.php?id='. $department_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Department">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Job positions table
    else if($type == 'job positions table'){
        if(isset($_POST['filter_status']) || isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT JOB_POSITION_ID, JOB_POSITION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES FROM employee_job_position';

                if(!empty($filter_status) || !empty($filter_department)){
                    $query .= ' WHERE ';

                    if(!empty($filter_status)){
                        $filter[] = 'RECRUITMENT_STATUS = :filter_status';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'DEPARTMENT = :filter_department';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status) || !empty($filter_department)){
                    if(!empty($filter_status)){
                        $sql->bindValue(':filter_status', $filter_status);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $job_position_id = $row['JOB_POSITION_ID'];
                        $job_position = $row['JOB_POSITION'];
                        $recruitment_status = $row['RECRUITMENT_STATUS'];
                        $department = $row['DEPARTMENT'];
                        $expected_new_employees = $row['EXPECTED_NEW_EMPLOYEES'];

                        if($recruitment_status == '1'){
                            $data_start = '0';
                        }
                        else{
                            $data_start = '1';
                        }
    
                        $job_position_recruitment_status = $api->get_job_position_recruitment_status($recruitment_status)[0]['BADGE'];

                        $department_details = $api->get_department_details($department);
                        $department_name = $department_details[0]['DEPARTMENT'] ?? null;
    
                        $job_position_id_encrypted = $api->encrypt_data($job_position_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-start="'. $data_start .'" type="checkbox" value="'. $job_position_id .'">',
                            'JOB_POSITION_ID' => $job_position_id,
                            'JOB_POSITION' => $job_position,
                            'DEPARTMENT' => $department_name,
                            'NUMBER_OF_EMPLOYEE' => 0,
                            'EXPECTED_NEW_EMPLOYEES' => $expected_new_employees,
                            'FORECASTED_EMPLOYEE' => 0,
                            'RECRUITMENT_STATUS' => $job_position_recruitment_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="job-position-form.php?id='. $job_position_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Job Position">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Job position responsibility table
    else if($type == 'job position responsibility table'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            if ($api->databaseConnection()) {
                $job_position_id = $_POST['job_position_id'];

                $update_job_position = $api->check_role_access_rights($username, '87', 'action');
                $update_job_position_responsibility = $api->check_role_access_rights($username, '92', 'action');
                $delete_job_position_responsibility = $api->check_role_access_rights($username, '93', 'action');
    
                $sql = $api->db_connection->prepare('SELECT RESPONSIBILITY_ID, RESPONSIBILITY FROM employee_job_position_responsibility WHERE JOB_POSITION_ID = :job_position_id');
                $sql->bindValue(':job_position_id', $job_position_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $responsibility_id = $row['RESPONSIBILITY_ID'];
                        $responsibility = $row['RESPONSIBILITY'];

                        if($update_job_position_responsibility > 0 && $update_job_position > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-responsibility" data-responsibility-id="'. $responsibility_id .'" data-job-position-id="'. $job_position_id .'" title="Edit Job Position Responsibility">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_job_position_responsibility > 0 && $update_job_position > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-responsibility" data-responsibility-id="'. $responsibility_id .'" data-job-position-id="'. $job_position_id .'" title="Delete Job Position Responsibility">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'RESPONSIBILITY' => $responsibility,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Job position requirement table
    else if($type == 'job position requirement table'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            if ($api->databaseConnection()) {
                $job_position_id = $_POST['job_position_id'];

                $update_job_position = $api->check_role_access_rights($username, '87', 'action');
                $update_job_position_requirement = $api->check_role_access_rights($username, '92', 'action');
                $delete_job_position_requirement = $api->check_role_access_rights($username, '93', 'action');
    
                $sql = $api->db_connection->prepare('SELECT REQUIREMENT_ID, REQUIREMENT FROM employee_job_position_requirement WHERE JOB_POSITION_ID = :job_position_id');
                $sql->bindValue(':job_position_id', $job_position_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $requirement_id = $row['REQUIREMENT_ID'];
                        $requirement = $row['REQUIREMENT'];

                        if($update_job_position_requirement > 0 && $update_job_position > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-requirement" data-requirement-id="'. $requirement_id .'" data-job-position-id="'. $job_position_id .'" title="Edit Job Position Requirement">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_job_position_requirement > 0 && $update_job_position > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-requirement" data-requirement-id="'. $requirement_id .'" data-job-position-id="'. $job_position_id .'" title="Delete Job Position Requirement">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'REQUIREMENT' => $requirement,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Job position qualification table
    else if($type == 'job position qualification table'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            if ($api->databaseConnection()) {
                $job_position_id = $_POST['job_position_id'];

                $update_job_position = $api->check_role_access_rights($username, '87', 'action');
                $update_job_position_qualification = $api->check_role_access_rights($username, '92', 'action');
                $delete_job_position_qualification = $api->check_role_access_rights($username, '93', 'action');
    
                $sql = $api->db_connection->prepare('SELECT QUALIFICATION_ID, QUALIFICATION FROM employee_job_position_qualification WHERE JOB_POSITION_ID = :job_position_id');
                $sql->bindValue(':job_position_id', $job_position_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $qualification_id = $row['QUALIFICATION_ID'];
                        $qualification = $row['QUALIFICATION'];

                        if($update_job_position_qualification > 0 && $update_job_position > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-qualification" data-qualification-id="'. $qualification_id .'" data-job-position-id="'. $job_position_id .'" title="Edit Job Position Qualification">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_job_position_qualification > 0 && $update_job_position > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-qualification" data-qualification-id="'. $qualification_id .'" data-job-position-id="'. $job_position_id .'" title="Delete Job Position Qualification">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'QUALIFICATION' => $qualification,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Job position attachment table
    else if($type == 'job position attachment table'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            if ($api->databaseConnection()) {
                $job_position_id = $_POST['job_position_id'];

                $update_job_position = $api->check_role_access_rights($username, '87', 'action');
                $update_job_position_attachment = $api->check_role_access_rights($username, '101', 'action');
                $delete_job_position_attachment = $api->check_role_access_rights($username, '102', 'action');
    
                $sql = $api->db_connection->prepare('SELECT ATTACHMENT_ID, ATTACHMENT_NAME, ATTACHMENT FROM employee_job_position_attachment WHERE JOB_POSITION_ID = :job_position_id');
                $sql->bindValue(':job_position_id', $job_position_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $attachment_id = $row['ATTACHMENT_ID'];
                        $attachment_name = $row['ATTACHMENT_NAME'];
                        $attachment = $row['ATTACHMENT'];

                        if($update_job_position_attachment > 0 && $update_job_position > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-attachment" data-attachment-id="'. $attachment_id .'" data-job-position-id="'. $job_position_id .'" title="Edit Job Position Attachment">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_job_position_attachment > 0 && $update_job_position > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-attachment" data-attachment-id="'. $attachment_id .'" data-job-position-id="'. $job_position_id .'" title="Delete Job Position Attachment">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'ATTACHMENT' => '<a href="'. $attachment .'" target="_blank">' . $attachment_name . '</a>',
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Work locations table
    else if($type == 'work locations table'){
        if(isset($_POST['filter_status'])){
            if ($api->databaseConnection()) {
                $filter_status = $_POST['filter_status'];

                $query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION, WORK_LOCATION_ADDRESS, STATUS FROM employee_work_location';

                if(!empty($filter_status)){
                    $query .= ' WHERE STATUS = :filter_status';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_status)){
                    $sql->bindValue(':filter_status', $filter_status);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $work_location_id = $row['WORK_LOCATION_ID'];
                        $work_location = $row['WORK_LOCATION'];
                        $work_location_address = $row['WORK_LOCATION_ADDRESS'];
                        $status = $row['STATUS'];

                        if($status == '1'){
                            $data_archive = '1';
                        }
                        else{
                            $data_archive = '0';
                        }
    
                        $work_location_status = $api->get_work_location_status($status)[0]['BADGE'];
    
                        $work_location_id_encrypted = $api->encrypt_data($work_location_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-archive="'. $data_archive .'" type="checkbox" value="'. $work_location_id .'">',
                            'WORK_LOCATION_ID' => $work_location_id,
                            'WORK_LOCATION' => $work_location . '<p class="text-muted mb-0">'. $work_location_address .'</p>',
                            'STATUS' => $work_location_status,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="work-location-form.php?id='. $work_location_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Work Location">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Departure reasons table
    else if($type == 'departure reasons table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON FROM employee_departure_reason');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $departure_reason_id = $row['DEPARTURE_REASON_ID'];
                    $departure_reason = $row['DEPARTURE_REASON'];

                    $departure_reason_id_encrypted = $api->encrypt_data($departure_reason_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $departure_reason_id .'">',
                        'DEPARTURE_REASON_ID' => $departure_reason_id,
                        'DEPARTURE_REASON' => $departure_reason,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="departure-reason-form.php?id='. $departure_reason_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Departure Reason">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Employee types table
    else if($type == 'employee types table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE FROM employee_employee_type');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $employee_type_id = $row['EMPLOYEE_TYPE_ID'];
                    $employee_type = $row['EMPLOYEE_TYPE'];

                    $employee_type_id_encrypted = $api->encrypt_data($employee_type_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employee_type_id .'">',
                        'EMPLOYEE_TYPE_ID' => $employee_type_id,
                        'EMPLOYEE_TYPE' => $employee_type,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="employee-type-form.php?id='. $employee_type_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Employee Type">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Wage types table
    else if($type == 'wage types table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT WAGE_TYPE_ID, WAGE_TYPE FROM employee_wage_type');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $wage_type_id = $row['WAGE_TYPE_ID'];
                    $wage_type = $row['WAGE_TYPE'];

                    $wage_type_id_encrypted = $api->encrypt_data($wage_type_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $wage_type_id .'">',
                        'WAGE_TYPE_ID' => $wage_type_id,
                        'WAGE_TYPE' => $wage_type,
                        'VIEW' => '<div class="d-flex gap-2">
                                        <a href="wage-type-form.php?id='. $wage_type_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Wage Type">
                                            <i class="bx bx-show font-size-16 align-middle"></i>
                                        </a>
                                    </div>'
                    );
                }

                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # Working schedules table
    else if($type == 'working schedules table'){
        if(isset($_POST['filter_working_schedule_type'])){
            if ($api->databaseConnection()) {
                $filter_working_schedule_type = $_POST['filter_working_schedule_type'];

                $query = 'SELECT WORKING_SCHEDULE_ID, WORKING_SCHEDULE, WORKING_SCHEDULE_TYPE FROM employee_working_schedule';

                if(!empty($filter_working_schedule_type)){
                    $query .= ' WHERE WORKING_SCHEDULE_TYPE = :filter_working_schedule_type';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_working_schedule_type)){
                    $sql->bindValue(':filter_working_schedule_type', $filter_working_schedule_type);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $working_schedule_id = $row['WORKING_SCHEDULE_ID'];
                        $working_schedule = $row['WORKING_SCHEDULE'];
                        $working_schedule_type = $row['WORKING_SCHEDULE_TYPE'];

                        $working_schedule_type_details = $api->get_working_schedule_type_details($working_schedule_type);
                        $working_schedule_type_name = $working_schedule_type_details[0]['WORKING_SCHEDULE_TYPE'] ?? null;
    
                        $working_schedule_id_encrypted = $api->encrypt_data($working_schedule_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $working_schedule_id .'">',
                            'WORKING_SCHEDULE_ID' => $working_schedule_id,
                            'WORKING_SCHEDULE' => $working_schedule,
                            'WORKING_SCHEDULE_TYPE' => $working_schedule_type_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="working-schedule-form.php?id='. $working_schedule_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Working Schedule">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Working hours table
    else if($type == 'working hours table'){
        if(isset($_POST['working_schedule_id']) && !empty($_POST['working_schedule_id'])){
            if ($api->databaseConnection()) {
                $working_schedule_id = $_POST['working_schedule_id'];
                $working_schedule_type_category = $api->get_working_schedule_type_category($working_schedule_id);

                $update_working_schedule = $api->check_role_access_rights($username, '118', 'action');
                $update_working_hours = $api->check_role_access_rights($username, '121', 'action');
                $delete_working_hours = $api->check_role_access_rights($username, '122', 'action');
    
                $sql = $api->db_connection->prepare('SELECT WORKING_HOURS_ID, WORKING_HOURS, WORKING_DATE, DAY_OF_WEEK, DAY_PERIOD, WORK_FROM, WORK_TO FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = :working_schedule_id');
                $sql->bindValue(':working_schedule_id', $working_schedule_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $working_hours_id = $row['WORKING_HOURS_ID'];
                        $working_hours = $row['WORKING_HOURS'];
                        $day_of_week = $row['DAY_OF_WEEK'];
                        $day_period = $row['DAY_PERIOD'];
                        $working_date = $api->check_date('empty', $row['WORKING_DATE'], '', 'm/d/Y', '', '', '');
                        $work_from = $api->check_date('empty', $row['WORK_FROM'], '', 'h:i:s a', '', '', '');
                        $work_to = $api->check_date('empty', $row['WORK_TO'], '', 'h:i:s a', '', '', '');

                        $system_code_details = $api->get_system_code_details(null, 'DAYOFWEEK', $day_of_week);
                        $day_of_week_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;

                        $system_code_details = $api->get_system_code_details(null, 'DAYPERIOD', $day_period);
                        $day_period_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;

                        if($update_working_hours > 0 && $update_working_schedule > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-working-hours" data-working-hours-id="'. $working_hours_id .'" data-category="'. $working_schedule_type_category .'" title="Edit Working Hours">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($delete_working_hours > 0 && $update_working_schedule > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-working-hours" data-working-hours-id="'. $working_hours_id .'" title="Delete Working Hours">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'WORKING_HOURS' => $working_hours,
                            'WORKING_DATE' => $working_date,
                            'DAY_OF_WEEK' => $day_of_week_name,
                            'DAY_PERIOD' => $day_period_name,
                            'WORK_FROM' => $work_from,
                            'WORK_TO' => $work_to,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $delete .'
                            </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Working schedule types table
    else if($type == 'working schedule types table'){
        if(isset($_POST['filter_category'])){
            if ($api->databaseConnection()) {
                $filter_category = $_POST['filter_category'];

                $query = 'SELECT WORKING_SCHEDULE_TYPE_ID, WORKING_SCHEDULE_TYPE, WORKING_SCHEDULE_TYPE_CATEGORY FROM employee_working_schedule_type';

                if(!empty($filter_category)){
                    $query .= ' WHERE WORKING_SCHEDULE_TYPE_CATEGORY = :filter_category';
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_category)){
                    $sql->bindValue(':filter_category', $filter_category);
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $working_schedule_type_id = $row['WORKING_SCHEDULE_TYPE_ID'];
                        $working_schedule_type = $row['WORKING_SCHEDULE_TYPE'];
                        $working_schedule_type_category = $row['WORKING_SCHEDULE_TYPE_CATEGORY'];

                        $system_code_details = $api->get_system_code_details(null, 'WORKINGSCHEDTYPECAT', $working_schedule_type_category);
                        $working_schedule_type_category_name = $system_code_details[0]['SYSTEM_DESCRIPTION'] ?? null;
    
                        $working_schedule_type_id_encrypted = $api->encrypt_data($working_schedule_type_id);
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $working_schedule_type_id .'">',
                            'WORKING_SCHEDULE_TYPE_ID' => $working_schedule_type_id,
                            'WORKING_SCHEDULE_TYPE' => $working_schedule_type,
                            'WORKING_SCHEDULE_TYPE_CATEGORY' => $working_schedule_type_category_name,
                            'VIEW' => '<div class="d-flex gap-2">
                                            <a href="working-schedule-type-form.php?id='. $working_schedule_type_id_encrypted .'" class="btn btn-primary waves-effect waves-light" title="View Working Schedule Type">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </a>
                                        </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        }
    }
    # -------------------------------------------------------------

}

?>