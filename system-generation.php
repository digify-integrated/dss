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

            if($form_type == 'module access form' || $form_type == 'page access form' || $form_type == 'action access form'){
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
    
                        $assignable_status = $api->get_roles_assignable_status($assignable)[0]['BADGE'];
    
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

                        if($delete_role_user_account > 0 && $update_role > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-user-account" data-user-id="'. $username .'" data-role-id="'. $role_id .'" title="Delete User Account">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'USERNAME' => $username,
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

}

?>