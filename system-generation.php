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

    # Modules table
    else if($type == 'modules table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPION, MODULE_CATEGORY FROM technical_module');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $module_id = $row['MODULE_ID'];
                    $module_name = $row['MODULE_NAME'];
                    $module_version = $row['MODULE_VERSION'];
                    $module_description = $row['MODULE_DESCRIPION'];
                    $module_category = $row['MODULE_CATEGORY'];

                    $module_id_encrypted = $api->encrypt_data($module_id);

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $menu_id .'">',
                        'MENU' => $menu,
                        'PARENT_MENU' => $parent_menu_name,
                        'MODULE' => $module_name,
                        'ORDER_SEQUENCE' => $order_sequence,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="" class="btn btn-primary waves-effect waves-light view-user-account" data-user-code="'. $username .'" title="View User Account">
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

}

?>