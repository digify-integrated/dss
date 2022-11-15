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

            if($form_type == 'change password form' || $form_type == 'change profile password form'){
                $form .= '<div class="mb-3">
                                <label class="form-label" for="change_username">Password <span class="text-danger">*</span></label>
                                <input type="hidden" id="change_username" name="change_username" value="'. $username .'">
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" id="change_password" name="change_password" class="form-control" aria-label="Password" aria-describedby="form-password-addon">
                                    <button class="btn btn-light" type="button" id="form-password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>';
            }
            else if($form_type == 'policy form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="policy_id" name="policy_id">
                                    <label for="policy" class="form-label">Policy <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="policy" name="policy" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="policy_description" class="form-label">Policy Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="policy_description" name="policy_description" maxlength="200" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'permission form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="permission_id" name="permission_id">
                                    <input type="hidden" id="policy_id" name="policy_id">
                                    <label for="permission" class="form-label">Permission <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="permission" name="permission" maxlength="100">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'role form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="hidden" id="role_id" name="role_id">
                                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="role" name="role" maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="role_description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="role_description" name="role_description" maxlength="200" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'role permission form'){
                $form .= '<div class="row">
                                <input type="hidden" id="role_id" name="role_id">
                                '. $api->generate_role_permission_form() .'
                            </div>';
            }
            else if($form_type == 'user account form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="update" value="0">
                                    <label for="file_as" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="file_as" name="file_as" maxlength="300">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="related_employee" class="form-label">Related Employee</label>
                                    <select class="form-control form-select2" id="related_employee" name="related_employee">
                                    <option value="">--</option>';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_code" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="user_code" name="user_code" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" id="password" name="password" class="form-control" aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-control form-select2" multiple="multiple" id="role" name="role">
                                            '. $api->generate_role_options() .'
                                        </select>
                                    </div>
                                </div>
                        </div>';
            }
            else if($form_type == 'system parameter form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="parameter_id" name="parameter_id">
                                    <label for="parameter" class="form-label">Parameter <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="parameter" name="parameter" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="extension" class="form-label">Extension</label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="extension" name="extension" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parameter_number" class="form-label">Number</label>
                                    <input id="parameter_number" name="parameter_number" class="form-control" type="number" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="parameter_description" class="form-label">Parameter Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="parameter_description" name="parameter_description" maxlength="100" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
                
            }
            else if($form_type == 'system code form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">System Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="system_type" name="system_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('SYSTYPE');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="system_code" class="form-label">System Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="system_code" name="system_code" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="system_description" class="form-label">System Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="system_description" name="system_description" maxlength="100">
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'upload setting form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="upload_setting" class="form-label">Upload Setting <span class="text-danger">*</span></label>
                                        <input type="hidden" id="upload_setting_id" name="upload_setting_id">
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="upload_setting" name="upload_setting" maxlength="200">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_file_size" class="form-label">Max File Size (Megabytes) <span class="text-danger">*</span></label>
                                        <input id="max_file_size" name="max_file_size" class="form-control" type="number" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="file_type" class="form-label">Allowed File Type <span class="text-danger">*</span></label>
                                        <select class="form-control form-select2" multiple="multiple" id="file_type" name="file_type">
                                            '. $api->generate_system_code_options('FILETYPE') .'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="description" name="description" maxlength="200" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'company form'){
                $form .= '<div class="row mb-3">
                                <input type="hidden" id="company_id" name="company_id">
                                <label for="company_name" class="col-sm-3 col-form-label">Company Name <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="company_name" name="company_name" maxlength="100">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="company_logo" class="col-sm-3 col-form-label">Company Logo</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="company_logo" id="company_logo">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="street_1" class="col-sm-3 col-form-label">Address</label>
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
                                    <option value="">State</option>';
                                    $form .= $api->generate_state_options();
                                    $form .='</select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="zip_code" name="zip_code" placeholder="Zip Code" maxlength="10">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="tax_id" class="col-sm-3 col-form-label">Tax ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="tax_id" name="tax_id" maxlength="100">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" id="email" name="email" class="form-control form-maxlength" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Mobile Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="mobile" name="mobile" maxlength="30">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="telephone" class="col-sm-3 col-form-label">Telephone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="telephone" name="telephone" maxlength="30">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="website" class="col-sm-3 col-form-label">Website</label>
                                <div class="col-sm-9">
                                    <input type="url" class="form-control form-maxlength" autocomplete="off" id="website" name="website" maxlength="100">
                                </div>
                            </div>';
            }
            else if($form_type == 'country form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="country_id" name="country_id">
                                    <label for="country_name" class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="country_name" name="country_name" maxlength="200">
                                </div>
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
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="country" name="country">
                                    <option value="">--</option>';
                                    $form .= $api->generate_country_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'notification setting form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="notification_setting_id" name="notification_setting_id">
                                    <label for="notification_setting" class="form-label">Notification Setting <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="notification_setting" name="notification_setting" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="notification_channel" class="form-label">Notification Channel</label>
                                        <select class="form-control form-select2" multiple="multiple" id="notification_channel" name="notification_channel">
                                            '. $api->generate_system_code_options('NOTIFICATIONCHANNEL') .'
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notification_setting_description" class="form-label">Notification Setting Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="notification_setting_description" name="notification_setting_description" maxlength="200" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'notification template form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="notification_setting_id" name="notification_setting_id">
                                    <label for="notification_title" class="form-label">Notification Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="notification_title" name="notification_title" maxlength="500">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notification_message" class="form-label">Notification Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="notification_message" name="notification_message" maxlength="500" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="system_link" class="form-label">System Link <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="system_link" name="system_link" maxlength="200">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_link" class="form-label">Email Link <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="email_link" name="email_link" maxlength="200">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="role_recipient" class="form-label">Role Recipient</label>
                                        <select class="form-control form-select2" multiple="multiple" id="role_recipient" name="role_recipient">
                                            '. $api->generate_role_options() .'
                                        </select>
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="user_account_recipient" class="form-label">User Account Recipient</label>
                                        <select class="form-control form-select2" multiple="multiple" id="user_account_recipient" name="user_account_recipient">
                                            '. $api->generate_user_account_options() .'
                                        </select>
                                    </div>
                                </div>
                        </div>';
            }
            else if($form_type == 'department form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="department_id" name="department_id">
                                    <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="department" name="department" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Parent Department</label>
                                    <select class="form-control form-select2" id="parent_department" name="parent_department">
                                    <option value="">--</option>';
                                    $form .= $api->generate_department_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Manager</label>
                                    <select class="form-control form-select2" id="manager" name="manager">
                                    <option value="">--</option>';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'job position form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="job_position_id" name="job_position_id">
                                    <label for="job_position" class="form-label">Job Position <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="job_position" name="job_position" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="job_description" class="form-label">Job Description</label><br/>
                                    <input class="form-control" type="file" name="job_description" id="job_description">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'work location form'){
                $form .= '<div class="row mb-3">
                                <input type="hidden" id="work_location_id" name="work_location_id">
                                <label for="work_location" class="col-sm-3 col-form-label">Work Location <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_location" name="work_location" maxlength="100">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="street_1" class="col-sm-3 col-form-label">Address</label>
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
                                    <option value="">State</option>';
                                    $form .= $api->generate_state_options();
                                    $form .='</select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="zip_code" name="zip_code" placeholder="Zip Code" maxlength="10">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" id="email" name="email" class="form-control form-maxlength" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">Mobile Number</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="mobile" name="mobile" maxlength="30">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="telephone" class="col-sm-3 col-form-label">Telephone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="telephone" name="telephone" maxlength="30">
                                </div>
                            </div>';
            }
            else if($form_type == 'departure reason form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="departure_reason_id" name="departure_reason_id">
                                    <label for="departure_reason" class="form-label">Departure Reason <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="departure_reason" name="departure_reason" maxlength="100">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'employee type form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="employee_type_id" name="employee_type_id">
                                    <label for="employee_type" class="form-label">Employee Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="employee_type" name="employee_type" maxlength="100">
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'employee form'){
                $form .= '<div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="hidden" id="employee_id" name="employee_id">
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="first_name" name="first_name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="middle_name" name="middle_name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="last_name" name="last_name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Suffix</label>
                                    <select class="form-control form-select2" id="suffix" name="suffix">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('SUFFIX');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_position" class="form-label">Job Position</label>
                                    <select class="form-control form-select2" id="job_position" name="job_position">
                                    <option value="">--</option>';
                                    $form .= $api->generate_job_position_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee_image" class="form-label">Employee Image</label><br/>
                                    <input class="form-control" type="file" name="employee_image" id="employee_image">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="work_email" class="col-sm-4 col-form-label">Work Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" id="work_email" name="work_email" class="form-control form-maxlength" maxlength="100" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="work_mobile" class="col-sm-4 col-form-label">Work Mobile Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_mobile" name="work_mobile" maxlength="30">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="work_telephone" class="col-sm-4 col-form-label">Work Telephone</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_telephone" name="work_telephone" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="department" class="col-sm-4 col-form-label">Department</label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select2" id="department" name="department">
                                        <option value="">--</option>';
                                        $form .= $api->generate_department_options();
                                        $form .='</select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="manager" class="col-sm-4 col-form-label">Manager</label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select2" id="manager" name="manager">
                                        <option value="">--</option>';
                                        $form .= $api->generate_employee_options();
                                        $form .='</select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="coach" class="col-sm-4 col-form-label">Coach</label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-select2" id="coach" name="coach">
                                        <option value="">--</option>';
                                        $form .= $api->generate_employee_options();
                                        $form .='</select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#work-information-tab" role="tab">
                                            <span class="d-block d-sm-none"><i class="bx bx-building"></i></span>
                                            <span class="d-none d-sm-block">Work Information</span>    
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#private-information-tab" role="tab">
                                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                            <span class="d-none d-sm-block">Private Information</span>    
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#citizenship-tab" role="tab">
                                            <span class="d-block d-sm-none"><i class="bx bx-id-card"></i></span>
                                            <span class="d-none d-sm-block">Citizenship</span>    
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#work-permit-tab" role="tab">
                                            <span class="d-block d-sm-none"><i class="bx bx-folder"></i></span>
                                            <span class="d-none d-sm-block">Work Permit</span>    
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content p-3">
                                    <div class="tab-pane active" id="work-information-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row mb-3">
                                                    <label for="company" class="col-sm-3 col-form-label">Company</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="company" name="company">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_company_options();
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="permanency_date" class="col-sm-3 col-form-label">Permanent Date</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group" id="permanency-date-container">
                                                            <input type="text" class="form-control" id="permanency_date" name="permanency_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#permanency-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="onboard_date" class="col-sm-3 col-form-label">Onboard Date</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group" id="onboard-date-container">
                                                            <input type="text" class="form-control" id="onboard_date" name="onboard_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#onboard-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="work_location" class="col-sm-3 col-form-label">Work Location</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="work_location" name="work_location">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_work_location_options();
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="employee_type" class="col-sm-3 col-form-label">Employee Type</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="employee_type" name="employee_type">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_employee_type_options();
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="working_hours" class="col-sm-3 col-form-label">Working Hours</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="working_hours" name="working_hours">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_working_hours_options();
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row mb-3">
                                                    <label for="badge_id" class="col-sm-3 col-form-label">Badge ID</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="badge_id" name="badge_id" maxlength="100">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="sss" class="col-sm-3 col-form-label">SSS</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="sss" name="sss" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="tin" class="col-sm-3 col-form-label">TIN</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="tin" name="tin" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="philhealth" class="col-sm-3 col-form-label">Philhealth</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="philhealth" name="philhealth" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="pagibig" class="col-sm-3 col-form-label">Pagibig</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="pagibig" name="pagibig" maxlength="20">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="private-information-tab" role="tabpanel">
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
                                                        <option value="">State</option>';
                                                        $form .= $api->generate_state_options();
                                                        $form .='</select>
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
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_system_code_options('MARITALSTATUS');
                                                        $form .='</select>
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
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_system_code_options('CERTIFICATELEVEL');
                                                        $form .='</select>
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
                                    <div class="tab-pane" id="citizenship-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row mb-3">
                                                    <label for="nationality" class="col-sm-3 col-form-label">Nationality (Country)</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="nationality" name="nationality">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_country_options();
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="identification_number" class="col-sm-3 col-form-label">Identification Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="identification_number" name="identification_number" maxlength="100">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="passport_number" class="col-sm-3 col-form-label">Passport Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="passport_number" name="passport_number" maxlength="100">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="birthday" class="col-sm-3 col-form-label">Date of Birth</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group" id="birthday-container">
                                                            <input type="text" class="form-control" id="birthday" name="birthday" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#birthday-container" data-provide="datepicker" data-date-autoclose="true">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="place_of_birth" class="col-sm-3 col-form-label">Place of Birth</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="place_of_birth" name="place_of_birth" maxlength="500">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="gender" class="col-sm-3 col-form-label">Gender</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-select2" id="gender" name="gender">
                                                        <option value="">--</option>';
                                                        $form .= $api->generate_system_code_options('GENDER');
                                                        $form .='</select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="number_of_children" class="col-sm-3 col-form-label">Number of Children</label>
                                                    <div class="col-sm-9">
                                                        <input id="number_of_children" name="number_of_children" class="form-control" type="number" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="work-permit-tab" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row mb-3">
                                                    <label for="visa_number" class="col-sm-3 col-form-label">Visa Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="visa_number" name="visa_number" maxlength="100">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="visa_expiry_date" class="col-sm-3 col-form-label">Visa Expiry Date</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group" id="visa-expiry-date-container">
                                                            <input type="text" class="form-control" id="visa_expiry_date" name="visa_expiry_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#visa-expiry-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="work_permit_number" class="col-sm-3 col-form-label">Work Permit Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="work_permit_number" name="work_permit_number" maxlength="100">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="work_permit_expiry_date" class="col-sm-3 col-form-label">Work Permit Expiry Date</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group" id="work-permit-expiry-date-container">
                                                            <input type="text" class="form-control" id="work_permit_expiry_date" name="work_permit_expiry_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#work-permit-expiry-date-container" data-provide="datepicker" data-date-autoclose="true">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="work_permit" class="col-sm-3 col-form-label">Work Permit</label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="file" name="work_permit" id="work_permit">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'working hours form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="working_hours_id" name="working_hours_id">
                                    <label for="working_hours" class="form-label">Working Hours <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="working_hours" name="working_hours" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="schedule_type" class="form-label">Schedule Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="schedule_type" name="schedule_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('SCHEDULETYPE');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'regular working hours form'){
                $form .= ' <div class="row mb-3">
                                <input type="hidden" id="working_hours_id" name="working_hours_id">
                                <label for="employee" class="col-sm-3 col-form-label">Employee</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-select2" multiple="multiple" id="employee" name="employee">';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-borderless mb-0">
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
                                                    <td><input type="time" id="monday_morning_work_from" name="monday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="monday_morning_work_to" name="monday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Monday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="monday_afternoon_work_from" name="monday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="monday_afternoon_work_to" name="monday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Tuesday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="tuesday_morning_work_from" name="tuesday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="tuesday_morning_work_to" name="tuesday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Tuesday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="tuesday_afternoon_work_from" name="tuesday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="tuesday_afternoon_work_to" name="tuesday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Wednesday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="wednesday_morning_work_from" name="wednesday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="wednesday_morning_work_to" name="wednesday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Wednesday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="wednesday_afternoon_work_from" name="wednesday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="wednesday_afternoon_work_to" name="wednesday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Thursday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="thursday_morning_work_from" name="thursday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="thursday_morning_work_to" name="thursday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Thursday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="thursday_afternoon_work_from" name="thursday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="thursday_afternoon_work_to" name="thursday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Friday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="friday_morning_work_from" name="friday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="friday_morning_work_to" name="friday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Friday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="friday_afternoon_work_from" name="friday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="friday_afternoon_work_to" name="friday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Saturday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="saturday_morning_work_from" name="saturday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="saturday_morning_work_to" name="saturday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Saturday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="saturday_afternoon_work_from" name="saturday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="saturday_afternoon_work_to" name="saturday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sunday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="sunday_morning_work_from" name="sunday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="sunday_morning_work_to" name="sunday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sunday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="sunday_afternoon_work_from" name="sunday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="sunday_afternoon_work_to" name="sunday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'scheduled working hours form'){
                $form .= ' <div class="row mb-3">
                                <input type="hidden" id="working_hours_id" name="working_hours_id">
                                <label for="start_date" class="col-sm-3 col-form-label">Start Date <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group" id="start-date-container">
                                        <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#start-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="end_date" class="col-sm-3 col-form-label">End Date <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group" id="end-date-container">
                                        <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#end-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="employee" class="col-sm-3 col-form-label">Employee</label>
                                <div class="col-sm-9">
                                    <select class="form-control form-select2" multiple="multiple" id="employee" name="employee">';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <table class="table table-borderless mb-0">
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
                                                    <td><input type="time" id="monday_morning_work_from" name="monday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="monday_morning_work_to" name="monday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Monday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="monday_afternoon_work_from" name="monday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="monday_afternoon_work_to" name="monday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Tuesday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="tuesday_morning_work_from" name="tuesday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="tuesday_morning_work_to" name="tuesday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Tuesday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="tuesday_afternoon_work_from" name="tuesday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="tuesday_afternoon_work_to" name="tuesday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Wednesday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="wednesday_morning_work_from" name="wednesday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="wednesday_morning_work_to" name="wednesday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Wednesday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="wednesday_afternoon_work_from" name="wednesday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="wednesday_afternoon_work_to" name="wednesday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Thursday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="thursday_morning_work_from" name="thursday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="thursday_morning_work_to" name="thursday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Thursday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="thursday_afternoon_work_from" name="thursday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="thursday_afternoon_work_to" name="thursday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Friday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="friday_morning_work_from" name="friday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="friday_morning_work_to" name="friday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Friday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="friday_afternoon_work_from" name="friday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="friday_afternoon_work_to" name="friday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Saturday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="saturday_morning_work_from" name="saturday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="saturday_morning_work_to" name="saturday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Saturday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="saturday_afternoon_work_from" name="saturday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="saturday_afternoon_work_to" name="saturday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sunday</th>
                                                    <td>Morning</td>
                                                    <td><input type="time" id="sunday_morning_work_from" name="sunday_morning_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="sunday_morning_work_to" name="sunday_morning_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Sunday</th>
                                                    <td>Afternoon</td>
                                                    <td><input type="time" id="sunday_afternoon_work_from" name="sunday_afternoon_work_from" class="form-control" autocomplete="off"></td>
                                                    <td><input type="time" id="sunday_afternoon_work_to" name="sunday_afternoon_work_to" class="form-control" autocomplete="off"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'archive employee form' || $form_type == 'archive multiple employee form'){
                $form .= '<div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="employee_id" name="employee_id">
                                    <label class="form-label">Departure Reason <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="departure_reason" name="departure_reason">
                                    <option value="">--</option>';
                                    $form .= $api->generate_departure_reason_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Departure Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="departure-date-container">
                                        <input type="text" class="form-control" id="departure_date" name="departure_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#departure-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="detailed_reason" class="form-label">Detailed Reason</label>
                                    <textarea class="form-control form-maxlength" id="detailed_reason" name="detailed_reason" maxlength="500" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'time in form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="time_in_note" class="form-label">Time In Note</label>
                                    <input type="hidden" id="attendance_position" name="attendance_position">
                                    <textarea class="form-control form-maxlength" id="time_in_note" name="time_in_note" maxlength="200" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'time out form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="attendance_id" name="attendance_id">
                                    <label for="time_out_note" class="form-label">Time Out Note</label>
                                    <input type="hidden" id="attendance_position" name="attendance_position">
                                    <textarea class="form-control form-maxlength" id="time_out_note" name="time_out_note" maxlength="200" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'attendance form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                                        <input type="hidden" id="attendance_id" name="attendance_id">
                                        <select class="form-control form-select2" id="employee_id" name="employee_id">
                                            <option value="">--</option>';
                                        $form .= $api->generate_employee_options();
                                        $form .='</select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date</label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out</label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea class="form-control form-maxlength" id="remarks" name="remarks" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'request full attendance adustment form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="employee_id" name="employee_id">
                                        <input type="hidden" id="attendance_id" name="attendance_id">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true" disabled>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out <span class="text-danger">*</span></label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'request partial attendance adustment form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="employee_id" name="employee_id">
                                        <input type="hidden" id="attendance_id" name="attendance_id">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true" disabled>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'request attendance form' || $form_type == 'request attendance creation form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date</label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out</label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'request attendance adjustment form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="employee_id" name="employee_id">
                                    <input type="hidden" id="request_type" name="request_type">
                                    <label class="form-label">Attendance <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="attendance_id" name="attendance_id">
                                        <option value="">--</option>';
                                    $form .= $api->generate_employee_attendance_options($username);
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true" disabled>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none" id="time-out-section">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out <span class="text-danger">*</span></label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'update full attendance adustment form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true" disabled>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out <span class="text-danger">*</span></label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment</label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'update partial attendance adustment form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true" disabled>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment</label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'cancel attendance adjustment form' || $form_type == 'cancel multiple attendance adjustment form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Cancellation Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'reject attendance adjustment form' || $form_type == 'reject multiple attendance adjustment form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Rejection Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'recommend attendance adjustment form' || $form_type == 'recommend multiple attendance adjustment form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Recommendation Remarks</label>
                                        <input type="hidden" id="adjustment_id" name="adjustment_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'approve attendance adjustment form' || $form_type == 'approve multiple attendance adjustment form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Sanction <span class="text-danger">*</span></label>
                                    <input type="hidden" id="adjustment_id" name="adjustment_id">
                                    <select class="form-control form-select2" id="sanction" name="sanction">
                                        <option value="">--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'update attendance creation form'){
                $form .= '<div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" id="creation_id" name="creation_id">
                                        <label for="time_in_date" class="form-label">Time In Date <span class="text-danger">*</span></label>
                                        <div class="input-group" id="time-in-date-container">
                                            <input type="text" class="form-control" id="time_in_date" name="time_in_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-in-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_in_time" class="form-label">Time In <span class="text-danger">*</span></label>
                                        <input type="time" id="time_in_time" name="time_in_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_date" class="form-label">Time Out Date</label>
                                        <div class="input-group" id="time-out-date-container">
                                            <input type="text" class="form-control" id="time_out_date" name="time_out_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#time-out-date-container" data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time_out_time" class="form-label">Time Out</label>
                                        <input type="time" id="time_out_time" name="time_out_time" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label">Attachment</label>
                                        <input class="form-control" type="file" name="attachment" id="attachment">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'cancel attendance creation form' || $form_type == 'cancel multiple attendance creation form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Cancellation Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="creation_id" name="creation_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'reject attendance creation form' || $form_type == 'reject multiple attendance creation form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Rejection Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="creation_id" name="creation_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'recommend attendance creation form' || $form_type == 'recommend multiple attendance creation form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Recommendation Remarks</label>
                                        <input type="hidden" id="creation_id" name="creation_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'approve attendance creation form' || $form_type == 'approve multiple attendance creation form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Sanction <span class="text-danger">*</span></label>
                                    <input type="hidden" id="creation_id" name="creation_id">
                                    <select class="form-control form-select2" id="sanction" name="sanction">
                                        <option value="">--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'approval type form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="approval_type_id" name="approval_type_id">
                                    <label for="approval_type" class="form-label">Approval Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="approval_type" name="approval_type" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="approval_type_description" class="form-label">Approval Type Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="approval_type_description" name="approval_type_description" maxlength="100" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'approver form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="hidden" id="approval_type_id" name="approval_type_id">
                                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                                        <select class="form-control form-select2" id="employee" name="employee">
                                        <option value="">--</option>';
                                        $form .= $api->generate_employee_options();
                                        $form .='</select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Department <span class="text-danger">*</span></label>
                                        <select class="form-control form-select2" multiple="multiple" id="department" name="department">';
                                        $form .= $api->generate_department_options();
                                        $form .='</select>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'approval exception form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="approval_type_id" name="approval_type_id">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" multiple="multiple" id="employee" name="employee">';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'public holiday form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="public_holiday_id" name="public_holiday_id">
                                    <label for="public_holiday" class="form-label">Public Holiday <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="public_holiday" name="public_holiday" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="holiday_type" class="form-label">Holiday Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="holiday_type" name="holiday_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_system_code_options('HOLIDAYTYPE');
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="holiday_date" class="form-label">Holiday Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="holiday-date-container">
                                        <input type="text" class="form-control" id="holiday_date" name="holiday_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#holiday-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="work_location" class="form-label">Work Location <span class="text-danger">*</span></label>
                                        <select class="form-control form-select2" multiple="multiple" id="work_location" name="work_location">
                                            '. $api->generate_work_location_options() .'
                                        </select>
                                    </div>
                                </div>
                        </div>';
            }
            else if($form_type == 'leave type form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="leave_type_id" name="leave_type_id">
                                    <label for="leave_type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-maxlength" autocomplete="off" id="leave_type" name="leave_type" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="paid_type" class="form-label">Paid Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="paid_type" name="paid_type">
                                        <option value="">--</option>
                                        <option value="PAID">Paid</option>
                                        <option value="UNPAID">Unpaid</option>                                   
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_allocation_type" class="form-label">Allocation Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="leave_allocation_type" name="leave_allocation_type">
                                        <option value="">--</option>
                                        <option value="LIMITED">Limited</option>
                                        <option value="NOLIMIT">No Limit</option>                                   
                                    </select>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'leave allocation form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <input type="hidden" id="leave_allocation_id" name="leave_allocation_id">
                                    <select class="form-control form-select2" id="employee_id" name="employee_id">
                                    <option value="">--</option>';
                                    $form .= $api->generate_employee_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-control form-select2" id="leave_type" name="leave_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_leave_type_variation_options('LIMITED');
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
                                    <div class="input-group" id="duration-container">
                                        <input id="duration" name="duration" class="form-control" type="number" min="1">
                                        <span class="input-group-text">Hours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validity_start_date" class="form-label">Validity Start Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="validity-start-date-container">
                                        <input type="text" class="form-control" id="validity_start_date" name="validity_start_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#validity-start-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validity_end_date" class="form-label">Validity End Date</label>
                                    <div class="input-group" id="validity-end-date-container">
                                        <input type="text" class="form-control" id="validity_end_date" name="validity_end_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#validity-end-date-container" data-provide="datepicker" data-date-autoclose="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'add leave form'){
                $form .= '<div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <input type="hidden" id="leave_id" name="leave_id">
                                    <select class="form-control form-select2" id="leave_type" name="leave_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_leave_type_options();
                                    $form .='</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_date" class="form-label">Leave Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="leave-date-container">
                                        <input type="text" class="form-control" id="leave_date" name="leave_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#leave-date-container" data-provide="datepicker" data-date-autoclose="false" data-date-multidate="true">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" id="start_time" name="start_time" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="time" id="end_time" name="end_time" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'update leave form'){
                $form .= '<div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <input type="hidden" id="leave_id" name="leave_id">
                                    <select class="form-control form-select2" id="leave_type" name="leave_type">
                                    <option value="">--</option>';
                                    $form .= $api->generate_leave_type_options();
                                    $form .='</select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="leave_date" class="form-label">Leave Date <span class="text-danger">*</span></label>
                                    <div class="input-group" id="leave-date-container">
                                        <input type="text" class="form-control" id="leave_date" name="leave_date" autocomplete="off" data-date-format="m/dd/yyyy" data-date-container="#leave-date-container" data-provide="datepicker" data-date-autoclose="false">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" id="start_time" name="start_time" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="time" id="end_time" name="end_time" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Reason</label>
                                    <textarea class="form-control form-maxlength" id="reason" name="reason" maxlength="500" rows="5"></textarea>
                                </div>
                            </div>
                        </div>';
            }
            else if($form_type == 'cancel leave form' || $form_type == 'cancel multiple leave form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Cancellation Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="leave_id" name="leave_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'reject leave form' || $form_type == 'reject multiple leave form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="decision_remarks" class="form-label">Rejection Remarks <span class="text-danger">*</span></label>
                                        <input type="hidden" id="leave_id" name="leave_id">
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'approve leave form' || $form_type == 'approve multiple leave form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="hidden" id="leave_id" name="leave_id">
                                        <label for="decision_remarks" class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                                        <textarea class="form-control form-maxlength" id="decision_remarks" name="decision_remarks" maxlength="500" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>';
            }
            else if($form_type == 'add leave supporting document form'){
                $form .= '<div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="hidden" id="leave_id" name="leave_id">
                                        <label for="document_name" class="form-label">Document Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-maxlength" autocomplete="off" id="document_name" name="document_name" maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="supporting_document" class="form-label">Supporting Document <span class="text-danger">*</span></label><br/>
                                        <input class="form-control" type="file" name="supporting_document" id="supporting_document">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#supporting_document_tab" role="tab">
                                                <span class="d-block d-sm-none"><i class="bx bx-time"></i></span>
                                                <span class="d-none d-sm-block">Supporting Documents</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="supporting_document_tab" role="tabpanel">
                                            <table id="leave-supporting-document-table" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Supporting Document</th>
                                                        <th>Upload Date</th>
                                                        <th>Uploaded By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
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
    
    # Card header
    else if($type == 'card header'){
        if(isset($_POST['filter']) && isset($_POST['menu_id']) && !empty($_POST['menu_id'])){
            $filter = $_POST['filter'];
            $menu_id = $_POST['menu_id'];

            $card_header = '<div class="col-md-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1 align-self-center">
                                        <h4 class="card-title">{card_title}</h4>
                                    </div>
                                    <div class="d-flex gap-2">
                                        {actions}
                                        {filter_actions}
                                    </div>
                                </div>
                                {filter}
                            </div>';

            $filter_container = '<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-off-canvas" data-bs-backdrop="true" aria-labelledby="filter-off-canvas-label">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="filter-off-canvas-label">Filter</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        {filter_category}
                        <div>
                            <button type="button" class="btn btn-primary waves-effect waves-light" id="apply-filter" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas">Apply Filter</button>
                        </div>
                    </div>
                </div>';

            if($menu_id == 4){
                $card_header = str_replace('{card_title}', 'Menu Item List', $card_header);
                $filter_category = '<div class="mb-3">
                                    <p class="text-muted">Module</p>
                                    <select class="form-control filter-select2" id="filter_module">
                                        <option value="">All Modules</option>
                                        '. $api->generate_module_options() .'
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <p class="text-muted">Parent Menu</p>

                                    <select class="form-control filter-select2" id="filter_parent_menu">
                                        <option value="">All Parent Menus</option>
                                        '. $api->generate_menu_options() .'
                                    </select>
                                </div>';
            }
            else{
                $card_header = '';
                $filter_category = '';
            }

            if($filter){
                $filter_container = str_replace('{filter_category}', $filter_category, $filter_container);

                $card_header = str_replace('{filter}', $filter_container, $card_header);
                $card_header = str_replace('{filter_actions}', '<button type="button" class="btn btn-info waves-effect btn-label waves-light" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas"><i class="bx bx-filter-alt label-icon"></i> Filter</button>', $card_header);
            }
            else{
                $card_header = str_replace('{filter}', '', $card_header);
                $card_header = str_replace('{filter_actions}', '', $card_header);
            }
            
            $response[] = array(
                'CARD_HEADER' => $card_header
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate table functions
    # -------------------------------------------------------------

    # Menu item table
    else if($type == 'menu item table'){
        if ($api->databaseConnection()) {
            $sql = $api->db_connection->prepare('SELECT MENU_ID, MENU, PARENT_MENU, MODULE_ID, ORDER_SEQUENCE FROM technical_menu');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $menu_id = $row['MENU_ID'];
                    $menu = $row['MENU'];
                    $parent_menu = $row['PARENT_MENU'];
                    $module_id = $row['MODULE_ID'];
                    $order_sequence = $row['ORDER_SEQUENCE'];
                    $menu_id_encrypted = $api->encrypt_data($menu_id);

                    $get_menu_details = $api->get_menu_details($parent_menu);
                    $parent_menu_name = $get_menu_details[0]['MENU'] ?? null;

                    $get_module_details = $api->get_module_details($module_id);
                    $module_name = $get_module_details[0]['MODULE_NAME'] ?? null;

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