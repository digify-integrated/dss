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

    # Policy table
    else if($type == 'policy table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_policy = $api->check_role_permissions($username, 3);
            $delete_policy = $api->check_role_permissions($username, 4);
            $view_transaction_log = $api->check_role_permissions($username, 5);
            $permission_page = $api->check_role_permissions($username, 6);

            $sql = $api->db_connection->prepare('SELECT POLICY_ID, POLICY, POLICY_DESCRIPTION, TRANSACTION_LOG_ID FROM global_policy');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $policy_id = $row['POLICY_ID'];
                    $policy = $row['POLICY'];
                    $policy_description = $row['POLICY_DESCRIPTION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                    $policy_id_encrypted = $api->encrypt_data($policy_id);

                    if($permission_page > 0){
                        $permission = '<a href="permission.php?id='. $policy_id_encrypted .'" class="btn btn-success waves-effect waves-light" title="View Permission">
                                    <i class="bx bx-list-check font-size-16 align-middle"></i>
                                </a>';
                    }
                    else{
                        $permission = '';
                    }

                    if($update_policy > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-policy" data-policy-id="'. $policy_id .'" title="Edit Policy">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_policy > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-policy" data-policy-id="'. $policy_id .'" title="Delete Policy">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $policy_id .'">',
                        'POLICY_ID' => $policy_id,
                        'POLICY' => $policy . '<p class="text-muted mb-0">'. $policy_description .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $permission .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Permission table
    else if($type == 'permission table'){
        if(isset($_POST['policy_id']) && !empty($_POST['policy_id'])){
            if ($api->databaseConnection()) {
                $policy_id = $_POST['policy_id'];
                $policy_details = $api->get_policy_details($policy_id);
                $policy = $policy_details[0]['POLICY'];

                # Get permission
                $update_permission = $api->check_role_permissions($username, 8);
                $delete_permission = $api->check_role_permissions($username, 9);
                $view_transaction_log = $api->check_role_permissions($username, 10);
    
                $sql = $api->db_connection->prepare('SELECT PERMISSION_ID, PERMISSION, TRANSACTION_LOG_ID FROM global_permission WHERE POLICY_ID = :policy_id ORDER BY PERMISSION_ID');
                $sql->bindValue(':policy_id', $policy_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $permission_id = $row['PERMISSION_ID'];
                        $permission = $row['PERMISSION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];
    
                        if($update_permission > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-permission" data-permission-id="'. $permission_id .'" title="Edit Permission">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }
    
                        if($delete_permission > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-permission" data-permission-id="'. $permission_id .'" title="Delete Permission">
                                            <i class="bx bx-trash font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $permission_id .'">',
                            'PERMISSION_ID' => $permission_id,
                            'PERMISSION' => $permission . '<p class="text-muted mb-0">'. $policy .'</p>',
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $transaction_log .'
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

    # Role table
    else if($type == 'role table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_role = $api->check_role_permissions($username, 13);
            $delete_role = $api->check_role_permissions($username, 14);
            $update_role_permission = $api->check_role_permissions($username, 15);
            $view_transaction_log = $api->check_role_permissions($username, 16);

            $sql = $api->db_connection->prepare('SELECT ROLE_ID, ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID FROM global_role');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $role_id = $row['ROLE_ID'];
                    $role = $row['ROLE'];
                    $role_description = $row['ROLE_DESCRIPTION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                    $role_id_encrypted = $api->encrypt_data($role_id);

                    if($update_role > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-role" data-role-id="'. $role_id .'" title="Edit Role">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_role > 0 && $role_id != 'RL-1'){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-role" data-role-id="'. $role_id .'" title="Delete Role">
                                    <i class="bx bx-trash font-size-16 align-middle"></i>
                                </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($update_role_permission > 0){
                        $permission = '<button type="button" class="btn btn-success waves-effect waves-light update-role-permission" data-role-id="'. $role_id .'" title="Edit Role permission">
                                        <i class="bx bx-list-check font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $permission = '';
                    }

                    if($role_id != 'RL-1'){
                        $check_box = '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $role_id .'">';
                    }
                    else{
                        $check_box = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => $check_box,
                        'ROLE' => $role . '<p class="text-muted mb-0">'. $role_description .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                            '. $update .'
                            '. $permission .'
                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # User account table
    else if($type == 'user account table'){
        if(isset($_POST['filter_user_account_lock_status']) && isset($_POST['filter_user_account_status']) && isset($_POST['filter_start_date']) && isset($_POST['filter_end_date'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_user_account = $api->check_role_permissions($username, 19);
                $lock_user_account = $api->check_role_permissions($username, 20);
                $unlock_user_account = $api->check_role_permissions($username, 21);
                $activate_user_account = $api->check_role_permissions($username, 22);
                $deactivate_user_account = $api->check_role_permissions($username, 23);
                $view_transaction_log = $api->check_role_permissions($username, 24);

                $filter_user_account_lock_status = $_POST['filter_user_account_lock_status'];
                $filter_user_account_status = $_POST['filter_user_account_status'];
                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');

                $query = 'SELECT USERNAME, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, TRANSACTION_LOG_ID FROM global_user_account';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || $filter_user_account_status != '' || !empty($filter_user_account_lock_status)){
                    $query .= ' WHERE ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'PASSWORD_EXPIRY_DATE BETWEEN :filter_start_date AND :filter_end_date';
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

                if((!empty($filter_start_date) && !empty($filter_end_date)) || $filter_user_account_status != ''){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
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
                        $failed_login = $row['FAILED_LOGIN'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                        $lock_status = $api->get_user_account_lock_status($failed_login)[0]['BADGE'];
                        $account_status = $api->get_user_account_status($user_status)[0]['BADGE'];
                        $password_expiry_date_difference = $api->get_date_difference($system_date, $password_expiry_date);
                        $expiry_difference = 'Expiring in ' . $password_expiry_date_difference[0]['MONTHS'] . ' ' . $password_expiry_date_difference[0]['DAYS'];
    
                        if($update_user_account > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-user-account" data-user-code="'. $username .'" title="Edit User Account">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }
    
                        if($failed_login >= 5){
                            if($unlock_user_account > 0){
                                $lock_unlock = '<button class="btn btn-info waves-effect waves-light unlock-user-account" title="Unlock User Account" data-user-code="'. $username .'">
                                <i class="bx bx-lock-open-alt font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $lock_unlock = '';
                            }
    
                            $data_lock = '1';
                        }
                        else{
                            if($lock_user_account > 0){
                                $lock_unlock = '<button class="btn btn-warning waves-effect waves-light lock-user-account" title="Lock User Account" data-user-code="'. $username .'">
                                <i class="bx bx-lock-alt font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $lock_unlock = '';
                            }
    
                            $data_lock = '0';
                        }
    
                        if($user_status == 'ACTIVE'){
                            if($deactivate_user_account > 0){
                                $active_inactive = '<button class="btn btn-danger waves-effect waves-light deactivate-user-account" title="Deactivate User Account" data-user-code="'. $username .'">
                                <i class="bx bx-x font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $active_inactive = '';
                            }
    
                            $data_active = '1';
                        }
                        else{
                            if($activate_user_account > 0){
                                $active_inactive = '<button class="btn btn-success waves-effect waves-light activate-user-account" title="Activate User Account" data-user-code="'. $username .'">
                                <i class="bx bx-user-check font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $active_inactive = '';
                            }
    
                            $data_active = '0';
                        }
    
                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-lock="'. $data_lock .'" data-active="'. $data_active .'" value="'. $username .'">',
                            'FILE_AS' => $file_as . '<p class="text-muted mb-0">'. $username .'</p>',
                            'ACCOUNT_STATUS' => $account_status,
                            'LOCK_STATUS' => $lock_status,
                            'PASSWORD_EXPIRY_DATE' => $password_expiry_date . '<p class="text-muted mb-0">'. $expiry_difference .'</p>',
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-user-account" data-user-code="'. $username .'" title="View User Account">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $active_inactive .'
                                '. $lock_unlock .'
                                '. $transaction_log .'
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

    # System parameter table
    else if($type == 'system parameter table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_system_parameter = $api->check_role_permissions($username, 21);
            $delete_system_parameter = $api->check_role_permissions($username, 22);
            $view_transaction_log = $api->check_role_permissions($username, 23);

            $sql = $api->db_connection->prepare('SELECT PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, TRANSACTION_LOG_ID FROM global_system_parameters ORDER BY PARAMETER_ID');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $parameter_id = $row['PARAMETER_ID'];
                    $parameter = $row['PARAMETER'];
                    $parameter_description = $row['PARAMETER_DESCRIPTION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_system_parameter > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-system-parameter" data-parameter-id="'. $parameter_id .'" title="Edit System Parameter">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_system_parameter > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-system-parameter" data-parameter-id="'. $parameter_id .'" title="Delete System Parameter">
                        <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $parameter_id .'">',
                        'PARAMETER_ID' => $parameter_id,
                        'PARAMETER' => $parameter . '<p class="text-muted mb-0">'. $parameter_description .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary waves-effect waves-light view-system-parameter" data-parameter-id="'. $parameter_id .'" title="View System Parameter">
                                <i class="bx bx-show font-size-16 align-middle"></i>
                            </button>
                            '. $update .'
                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # System code table
    else if($type == 'system code table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_system_code = $api->check_role_permissions($username, 32);
            $delete_system_code = $api->check_role_permissions($username, 33);
            $view_transaction_log = $api->check_role_permissions($username, 34);

            $sql = $api->db_connection->prepare('SELECT SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID FROM global_system_code ORDER BY SYSTEM_TYPE');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $system_type = $row['SYSTEM_TYPE'];
                    $system_code = $row['SYSTEM_CODE'];
                    $system_description = $row['SYSTEM_DESCRIPTION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_system_code > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-system-code" data-system-type="'. $system_type .'" data-system-code="'. $system_code .'" title="Edit System Code">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_system_code > 0 && ($system_type != 'SYSTYPE' || $system_code != 'SYSTYPE')){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-system-code" data-system-type="'. $system_type .'" data-system-code="'. $system_code .'" title="Delete System Code">
                        <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($system_type != 'SYSTYPE' || $system_code != 'SYSTYPE'){
                        $check_box = '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-system-type="'. $system_type .'" data-system-code="'. $system_code .'">';
                    }
                    else{
                        $check_box = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => $check_box,
                        'SYSTEM_TYPE' => $system_type,
                        'SYSTEM_CODE' => $system_code . '<p class="text-muted mb-0">'. $system_description .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                            '. $update .'
                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Upload setting table
    else if($type == 'upload setting table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_upload_setting = $api->check_role_permissions($username, 37);
            $delete_upload_setting = $api->check_role_permissions($username, 38);
            $view_transaction_log = $api->check_role_permissions($username, 39);

            $sql = $api->db_connection->prepare('SELECT UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID FROM global_upload_setting ORDER BY UPLOAD_SETTING');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $file_type = '';
                    $upload_setting_id = $row['UPLOAD_SETTING_ID'];
                    $upload_setting = $row['UPLOAD_SETTING'];
                    $description = $row['DESCRIPTION'];
                    $max_file_size = $row['MAX_FILE_SIZE'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                    $upload_file_type_details = $api->get_upload_file_type_details($upload_setting_id);

                    for($i = 0; $i < count($upload_file_type_details); $i++) {
                        $system_code_details = $api->get_system_code_details('FILETYPE', $upload_file_type_details[$i]['FILE_TYPE']);
                        $file_type .= '<span class="badge bg-info font-size-11">'. $system_code_details[0]['SYSTEM_DESCRIPTION'] .'</span> ';

                        if(($i + 1) % 3 == 0){
                            $file_type .= '<br/>';
                        }
                    }

                    if($delete_upload_setting > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-upload-setting" data-upload-setting-id="'. $upload_setting_id .'" title="Edit Upload Setting">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_upload_setting > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-upload-setting" data-upload-setting-id="'. $upload_setting_id .'" title="Delete Upload Setting">
                                    <i class="bx bx-trash font-size-16 align-middle"></i>
                                </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $upload_setting_id .'">',
                        'UPLOAD_SETTING_ID' => $upload_setting_id,
                        'UPLOAD_SETTING' => $upload_setting . '<p class="text-muted mb-0">'. $description .'</p>',
                        'MAX_FILE_SIZE' => $max_file_size . ' Mb',
                        'FILE_TYPE' => $file_type,
                        'ACTION' => '<div class="d-flex gap-2">
                            '. $update .'
                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Company table
    else if($type == 'company table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_company = $api->check_role_permissions($username, 42);
            $delete_company = $api->check_role_permissions($username, 43);
            $view_transaction_log = $api->check_role_permissions($username, 44);

            $sql = $api->db_connection->prepare('SELECT COMPANY_ID, COMPANY_NAME, TRANSACTION_LOG_ID FROM global_company');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $company_id = $row['COMPANY_ID'];
                    $company_name = $row['COMPANY_NAME'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_company > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-company" data-company-id="'. $company_id .'" title="Edit Company">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_company > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-company" data-company-id="'. $company_id .'" title="Delete Company">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $company_id .'">',
                        'COMPANY_ID' => $company_id,
                        'COMPANY_NAME' => $company_name,
                        'ACTION' => '<div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary waves-effect waves-light view-company" data-company-id="'. $company_id .'" title="View Company">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </button>
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Country table
    else if($type == 'country table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_country = $api->check_role_permissions($username, 47);
            $delete_country = $api->check_role_permissions($username, 48);
            $view_transaction_log = $api->check_role_permissions($username, 49);

            $sql = $api->db_connection->prepare('SELECT COUNTRY_ID, COUNTRY_NAME, TRANSACTION_LOG_ID FROM global_country');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $country_id = $row['COUNTRY_ID'];
                    $country_name = $row['COUNTRY_NAME'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_country > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-country" data-country-id="'. $country_id .'" title="Edit Country">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_country > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-country" data-country-id="'. $country_id .'" title="Delete Country">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $country_id .'">',
                        'COUNTRY_ID' => $country_id,
                        'COUNTRY_NAME' => $country_name,
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # State table
    else if($type == 'state table'){
        if(isset($_POST['filter_country'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_state = $api->check_role_permissions($username, 52);
                $delete_state = $api->check_role_permissions($username, 53);
                $view_transaction_log = $api->check_role_permissions($username, 54);

                $filter_country = $_POST['filter_country'];

                $query = 'SELECT STATE_ID, STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID FROM global_state';

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
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $country_details = $api->get_country_details($country_id);
                        $country_name = $country_details[0]['COUNTRY_NAME'];
    
                        if($update_state > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-state" data-state-id="'. $state_id .'" title="Edit State">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }
    
                        if($delete_state > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-state" data-state-id="'. $state_id .'" title="Delete State">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }
    
                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $state_id .'">',
                            'STATE_ID' => $state_id,
                            'STATE_NAME' => $state_name,
                            'COUNTRY' => $country_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                                '. $update .'
                                                '. $transaction_log .'
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

    # Notification setting table
    else if($type == 'notification setting table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_notification_setting = $api->check_role_permissions($username, 57);
            $update_notification_template = $api->check_role_permissions($username, 58);
            $delete_notification_setting = $api->check_role_permissions($username, 59);
            $view_transaction_log = $api->check_role_permissions($username, 60);

            $sql = $api->db_connection->prepare('SELECT NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, NOTIFICATION_SETTING_DESCRIPTION, TRANSACTION_LOG_ID FROM global_notification_setting');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $notification_setting_id = $row['NOTIFICATION_SETTING_ID'];
                    $notification_setting = $row['NOTIFICATION_SETTING'];
                    $notification_setting_description = $row['NOTIFICATION_SETTING_DESCRIPTION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_notification_setting > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-notification-setting" data-notification-setting-id="'. $notification_setting_id .'" title="Edit Notification Setting">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($update_notification_template > 0){
                        $template = '<button type="button" class="btn btn-success waves-effect waves-light update-notification-template" data-notification-setting-id="'. $notification_setting_id .'" title="Edit Notification Template">
                                        <i class="bx bx-file font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $template = '';
                    }

                    if($delete_notification_setting > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-notification-setting" data-notification-setting-id="'. $notification_setting_id .'" title="Delete Notification Setting">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $notification_setting_id .'">',
                        'NOTIFICATION_SETTING_ID' => $notification_setting_id,
                        'NOTIFICATION_SETTING' => $notification_setting . '<p class="text-muted mb-0">'. $notification_setting_description .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $template .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Department table
    else if($type == 'department table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_department = $api->check_role_permissions($username, 72);
            $delete_department = $api->check_role_permissions($username, 73);
            $view_transaction_log = $api->check_role_permissions($username, 74);

            $sql = $api->db_connection->prepare('SELECT DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, TRANSACTION_LOG_ID FROM employee_department');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $department_id = $row['DEPARTMENT_ID'];
                    $department = $row['DEPARTMENT'];
                    $parent_department = $row['PARENT_DEPARTMENT'];
                    $manager = $row['MANAGER'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    $parent_department_details = $api->get_department_details($parent_department);
                    $parent_department_name = $parent_department_details[0]['DEPARTMENT'] ?? null;

                    if($update_department > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-department" data-department-id="'. $department_id .'" title="Edit Department">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_department > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-department" data-department-id="'. $department_id .'" title="Delete Department">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $department_id .'">',
                        'DEPARTMENT' => $department,
                        'MANAGER' => '',
                        'EMPLOYEES' => 0,
                        'PARENT_DEPARTMENT' => $parent_department_name,
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Job position table
    else if($type == 'job position table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_job_position = $api->check_role_permissions($username, 77);
            $delete_job_position = $api->check_role_permissions($username, 78);
            $view_transaction_log = $api->check_role_permissions($username, 79);

            $sql = $api->db_connection->prepare('SELECT JOB_POSITION_ID, JOB_POSITION, TRANSACTION_LOG_ID FROM employee_job_position');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $job_position_id = $row['JOB_POSITION_ID'];
                    $job_position = $row['JOB_POSITION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_job_position > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-job-position" data-job-position-id="'. $job_position_id .'" title="Edit Job Position">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_job_position > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-job-position" data-job-position-id="'. $job_position_id .'" title="Delete Job Position">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $job_position_id .'">',
                        'JOB_POSITION' => $job_position,
                        'ACTION' => '<div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary waves-effect waves-light view-job-position" data-job-position-id="'. $job_position_id .'" title="View Job Position">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </button>
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Work location table
    else if($type == 'work location table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_work_location = $api->check_role_permissions($username, 82);
            $delete_work_location = $api->check_role_permissions($username, 83);
            $view_transaction_log = $api->check_role_permissions($username, 84);

            $sql = $api->db_connection->prepare('SELECT WORK_LOCATION_ID, WORK_LOCATION, TRANSACTION_LOG_ID FROM employee_work_location');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $work_location_id = $row['WORK_LOCATION_ID'];
                    $work_location = $row['WORK_LOCATION'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_work_location > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-work-location" data-work-location-id="'. $work_location_id .'" title="Edit Work Location">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_work_location > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-work-location" data-work-location-id="'. $work_location_id .'" title="Delete Work Location">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $work_location_id .'">',
                        'WORK_LOCATION' => $work_location,
                        'ACTION' => '<div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary waves-effect waves-light view-work-location" data-work-location-id="'. $work_location_id .'" title="View Work Location">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </button>
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Departure reason table
    else if($type == 'departure reason table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_departure_reason = $api->check_role_permissions($username, 87);
            $delete_departure_reason = $api->check_role_permissions($username, 88);
            $view_transaction_log = $api->check_role_permissions($username, 89);

            $sql = $api->db_connection->prepare('SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON, TRANSACTION_LOG_ID FROM employee_departure_reason');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $departure_reason_id = $row['DEPARTURE_REASON_ID'];
                    $departure_reason = $row['DEPARTURE_REASON'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_departure_reason > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-departure-reason" data-departure-reason-id="'. $departure_reason_id .'" title="Edit Departure Reason">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_departure_reason > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-departure-reason" data-departure-reason-id="'. $departure_reason_id .'" title="Delete Departure Reason">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $departure_reason_id .'">',
                        'DEPARTURE_REASON' => $departure_reason,
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Employee type table
    else if($type == 'employee type table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_employee_type = $api->check_role_permissions($username, 99);
            $delete_employee_type = $api->check_role_permissions($username, 100);
            $view_transaction_log = $api->check_role_permissions($username, 101);

            $sql = $api->db_connection->prepare('SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE, TRANSACTION_LOG_ID FROM employee_type');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $employee_type_id = $row['EMPLOYEE_TYPE_ID'];
                    $employee_type = $row['EMPLOYEE_TYPE'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    if($update_employee_type > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-employee-type" data-employee-type-id="'. $employee_type_id .'" title="Edit Employee Type">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($delete_employee_type > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-employee-type" data-employee-type-id="'. $employee_type_id .'" title="Delete Employee Type">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employee_type_id .'">',
                        'EMPLOYEE_TYPE' => $employee_type,
                        'ACTION' => '<div class="d-flex gap-2">
                                            '. $update .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Employee table
    else if($type == 'employee table'){
        if(isset($_POST['filter_employee_status']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department']) && isset($_POST['filter_job_position']) && isset($_POST['filter_employee_type'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_employee = $api->check_role_permissions($username, 92);
                $delete_employee = $api->check_role_permissions($username, 93);
                $archive_employee = $api->check_role_permissions($username, 94);
                $unarchive_employee = $api->check_role_permissions($username, 95);
                $view_transaction_log = $api->check_role_permissions($username, 96);

                $filter_employee_status = $_POST['filter_employee_status'];
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];
                $filter_job_position = $_POST['filter_job_position'];
                $filter_employee_type = $_POST['filter_employee_type'];

                $query = 'SELECT EMPLOYEE_ID, FILE_AS, EMPLOYEE_IMAGE, JOB_POSITION, EMPLOYEE_STATUS, TRANSACTION_LOG_ID FROM employee_details';

                if(!empty($filter_employee_status) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type)){
                    $query .= ' WHERE ';

                    if(!empty($filter_employee_status)){
                        $filter[] = 'EMPLOYEE_STATUS = :filter_employee_status';
                    }

                    if(!empty($filter_work_location)){
                        $filter[] = 'WORK_LOCATION = :filter_work_location';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'DEPARTMENT = :filter_department';
                    }

                    if(!empty($filter_job_position)){
                        $filter[] = 'JOB_POSITION = :filter_job_position';
                    }

                    if(!empty($filter_employee_type)){
                        $filter[] = 'EMPLOYEE_TYPE = :filter_employee_type';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_employee_status) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type)){

                    if(!empty($filter_employee_status)){
                        $sql->bindValue(':filter_employee_status', $filter_employee_status);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }

                    if(!empty($filter_job_position)){
                        $sql->bindValue(':filter_job_position', $filter_job_position);
                    }

                    if(!empty($filter_employee_type)){
                        $sql->bindValue(':filter_employee_type', $filter_employee_type);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $employee_id = $row['EMPLOYEE_ID'];
                        $file_as = $row['FILE_AS'];
                        $employee_image = $row['EMPLOYEE_IMAGE'];
                        $job_position = $row['JOB_POSITION'];
                        $employee_status = $row['EMPLOYEE_STATUS'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        if(empty($employee_image)){
                            $employee_image = $api->check_image($employee_image ?? null, 'profile');
                        }

                        $job_position_details = $api->get_job_position_details($job_position);
                        $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

                        if($update_employee > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-employee" data-employee-id="'. $employee_id .'" title="Edit Employee">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($delete_employee > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-employee" data-employee-id="'. $employee_id .'" title="Delete Employee">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }
    
                        if($employee_status == 'ACTIVE'){
                            if($archive_employee > 0){
                                $archive_unarchive = '<button class="btn btn-danger waves-effect waves-light archive-employee" title="Archive Employee" data-employee-id="'. $employee_id .'">
                                <i class="bx bx-archive-in font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $archive_unarchive = '';
                            }
    
                            $data_archive = '1';
                        }
                        else{
                            if($unarchive_employee > 0){
                                $archive_unarchive = '<button class="btn btn-success waves-effect waves-light unarchive-employee" title="Unarchive Employee" data-employee-id="'. $employee_id .'">
                                <i class="bx bx-archive-out font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $archive_unarchive = '';
                            }
    
                            $data_archive = '0';
                        }
    
                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-archive="'. $data_archive .'" value="'. $employee_id .'">',
                            'IMAGE' => '<img class="rounded-circle avatar-xs" src="'. $employee_image .'" alt="profile">',
                            'FILE_AS' => $file_as . '<p class="text-muted mb-0">'. $job_position_name .'</p>',
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $archive_unarchive .'
                                '. $transaction_log .'
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

    # Working hours table
    else if($type == 'working hours table'){
        if ($api->databaseConnection()) {
            # Get permission
            $update_working_hours = $api->check_role_permissions($username, 104);
            $update_working_hours_schedule = $api->check_role_permissions($username, 105);
            $delete_working_hours = $api->check_role_permissions($username, 106);
            $view_transaction_log = $api->check_role_permissions($username, 107);

            $sql = $api->db_connection->prepare('SELECT WORKING_HOURS_ID, WORKING_HOURS, SCHEDULE_TYPE, TRANSACTION_LOG_ID FROM employee_working_hours');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $working_hours_id = $row['WORKING_HOURS_ID'];
                    $working_hours = $row['WORKING_HOURS'];
                    $schedule_type = $row['SCHEDULE_TYPE'];
                    $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                    $system_code_details = $api->get_system_code_details('SCHEDULETYPE', $schedule_type);
                    $schedule_type_name = $system_code_details[0]['SYSTEM_DESCRIPTION'];

                    if($update_working_hours > 0){
                        $update = '<button type="button" class="btn btn-info waves-effect waves-light update-working-hours" data-working-hours-id="'. $working_hours_id .'" title="Edit Working Hours">
                                        <i class="bx bx-pencil font-size-16 align-middle"></i>
                                    </button>';
                    }
                    else{
                        $update = '';
                    }

                    if($update_working_hours_schedule > 0){
                        if($schedule_type == 'REGULAR'){
                            $schedule = '<button type="button" class="btn btn-success waves-effect waves-light update-regular-working-hours" data-working-hours-id="'. $working_hours_id .'" title="Edit Regular Working Hours">
                                        <i class="bx bx-time font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $schedule = '<button type="button" class="btn btn-success waves-effect waves-light update-scheduled-working-hours" data-working-hours-id="'. $working_hours_id .'" title="Edit Scheduled Working Hours">
                                    <i class="bx bx-time font-size-16 align-middle"></i>
                                </button>';
                        }
                    }
                    else{
                        $schedule = '';
                    }

                    if($delete_working_hours > 0){
                        $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-working-hours" data-working-hours-id="'. $working_hours_id .'" title="Delete Working Hours">
                            <i class="bx bx-trash font-size-16 align-middle"></i>
                        </button>';
                    }
                    else{
                        $delete = '';
                    }

                    if($view_transaction_log > 0 && !empty($transaction_log_id)){
                        $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                <i class="bx bx-detail font-size-16 align-middle"></i>
                                            </button>';
                    }
                    else{
                        $transaction_log = '';
                    }

                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $working_hours_id .'">',
                        'WORKING_HOURS' => $working_hours . '<p class="text-muted mb-0">'. $schedule_type_name .'</p>',
                        'ACTION' => '<div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary waves-effect waves-light view-working-hours" data-working-hours-id="'. $working_hours_id .'" title="View Working Hours">
                                                <i class="bx bx-show font-size-16 align-middle"></i>
                                            </button>
                                            '. $update .'
                                            '. $schedule .'
                                            '. $transaction_log .'
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
    # -------------------------------------------------------------

    # Attendance table
    else if($type == 'attendance table'){
        if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department']) && isset($_POST['filter_job_position']) && isset($_POST['filter_employee_type']) && isset($_POST['filter_time_in_behavior']) && isset($_POST['filter_time_out_behavior'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_attendance = $api->check_role_permissions($username, 115);
                $delete_attendance = $api->check_role_permissions($username, 116);
                $view_transaction_log = $api->check_role_permissions($username, 117);

                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];
                $filter_job_position = $_POST['filter_job_position'];
                $filter_employee_type = $_POST['filter_employee_type'];
                $filter_time_in_behavior = $_POST['filter_time_in_behavior'];
                $filter_time_out_behavior = $_POST['filter_time_out_behavior'];

                $query = 'SELECT ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_IN_BEHAVIOR, TIME_OUT, TIME_OUT_BEHAVIOR, LATE, EARLY_LEAVING, OVERTIME, TOTAL_WORKING_HOURS, TRANSACTION_LOG_ID FROM attendance_record';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type) || !empty($filter_time_in_behavior) || !empty($filter_time_out_behavior)){
                    $query .= ' WHERE ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'DATE(TIME_IN) BETWEEN :filter_start_date AND :filter_end_date';
                    }

                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter_job_position)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE JOB_POSITION = :filter_job_position)';
                    }

                    if(!empty($filter_employee_type)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE EMPLOYEE_TYPE = :filter_employee_type)';
                    }

                    if(!empty($filter_time_in_behavior)){
                        $filter[] = 'TIME_IN_BEHAVIOR = :filter_time_in_behavior';
                    }

                    if(!empty($filter_time_out_behavior)){
                        $filter[] = 'TIME_OUT_BEHAVIOR = :filter_time_out_behavior';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type) || !empty($filter_time_in_behavior) || !empty($filter_time_out_behavior)){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }

                    if(!empty($filter_job_position)){
                        $sql->bindValue(':filter_job_position', $filter_job_position);
                    }

                    if(!empty($filter_employee_type)){
                        $sql->bindValue(':filter_employee_type', $filter_employee_type);
                    }

                    if(!empty($filter_time_in_behavior)){
                        $sql->bindValue(':filter_time_in_behavior', $filter_time_in_behavior);
                    }

                    if(!empty($filter_time_out_behavior)){
                        $sql->bindValue(':filter_time_out_behavior', $filter_time_out_behavior);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $late = number_format($row['LATE'], 2);
                        $early_leaving = number_format($row['EARLY_LEAVING'], 2);
                        $overtime = number_format($row['OVERTIME'], 2);
                        $total_working_hours = number_format($row['TOTAL_WORKING_HOURS'], 2);
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $time_in_behavior = $api->get_time_in_behavior_status($row['TIME_IN_BEHAVIOR'])[0]['BADGE'];
                        $time_out_behavior = $api->get_time_out_behavior_status($row['TIME_OUT_BEHAVIOR'])[0]['BADGE'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;
                        $job_position = $employee_details[0]['JOB_POSITION'] ?? null;

                        $job_position_details = $api->get_job_position_details($job_position);
                        $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

                        if($update_attendance > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-attendance" data-attendance-id="'. $attendance_id .'" title="Edit Attendance">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($delete_attendance > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-attendance" data-attendance-id="'. $attendance_id .'" title="Delete Attendance">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $attendance_id .'">',
                            'FILE_AS' => $file_as . '<p class="text-muted mb-0">'. $job_position_name .'</p>',
                            'TIME_IN' => $time_in,
                            'TIME_IN_BEHAVIOR' => $time_in_behavior,
                            'TIME_OUT' => $time_out,
                            'TIME_OUT_BEHAVIOR' => $time_out_behavior,
                            'LATE' => $late,
                            'EARLY_LEAVING' => $early_leaving,
                            'OVERTIME' => $overtime,
                            'TOTAL_WORKING_HOURS' => $total_working_hours,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance" data-attendance-id="'. $attendance_id .'" title="View Attendance">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $transaction_log .'
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

    # My attendance table
    else if($type == 'my attendance table'){
        if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date']) && isset($_POST['filter_time_in_behavior']) && isset($_POST['filter_time_out_behavior'])){
            if ($api->databaseConnection()) {
                # Get permission
                $request_attendance_adjustment = $api->check_role_permissions($username, 119);
                $view_transaction_log = $api->check_role_permissions($username, 117);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'];

                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');
                $filter_time_in_behavior = $_POST['filter_time_in_behavior'];
                $filter_time_out_behavior = $_POST['filter_time_out_behavior'];

                $query = 'SELECT ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_IN_BEHAVIOR, TIME_OUT, TIME_OUT_BEHAVIOR, LATE, EARLY_LEAVING, OVERTIME, TOTAL_WORKING_HOURS, TRANSACTION_LOG_ID FROM attendance_record WHERE EMPLOYEE_ID = :employee_id';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_time_in_behavior) || !empty($filter_time_out_behavior)){
                    $query .= ' AND ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'DATE(TIME_IN) BETWEEN :filter_start_date AND :filter_end_date';
                    }

                    if(!empty($filter_time_in_behavior)){
                        $filter[] = 'TIME_IN_BEHAVIOR = :filter_time_in_behavior';
                    }

                    if(!empty($filter_time_out_behavior)){
                        $filter[] = 'TIME_OUT_BEHAVIOR = :filter_time_out_behavior';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_time_in_behavior) || !empty($filter_time_out_behavior)){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
                    }

                    if(!empty($filter_time_in_behavior)){
                        $sql->bindValue(':filter_time_in_behavior', $filter_time_in_behavior);
                    }

                    if(!empty($filter_time_out_behavior)){
                        $sql->bindValue(':filter_time_out_behavior', $filter_time_out_behavior);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $late = number_format($row['LATE'], 2);
                        $early_leaving = number_format($row['EARLY_LEAVING'], 2);
                        $overtime = number_format($row['OVERTIME'], 2);
                        $total_working_hours = number_format($row['TOTAL_WORKING_HOURS'], 2);
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $time_in_behavior = $api->get_time_in_behavior_status($row['TIME_IN_BEHAVIOR'])[0]['BADGE'];
                        $time_out_behavior = $api->get_time_out_behavior_status($row['TIME_OUT_BEHAVIOR'])[0]['BADGE'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'];
                        $job_position = $employee_details[0]['JOB_POSITION'];

                        $job_position_details = $api->get_job_position_details($job_position);
                        $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

                        if(!empty($time_out)){
                            $adjustment_type = 'full';
                        }
                        else{
                            $adjustment_type = 'partial';
                        }

                        if($request_attendance_adjustment > 0){
                            $attendance_adjustment = '<button type="button" class="btn btn-success waves-effect waves-light request-attendance-adjustment" data-adjustment-type="'. $adjustment_type .'" data-attendance-id="'. $attendance_id .'" title="Request Attendance Adjustment">
                                            <i class="bx bx-time-five font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $attendance_adjustment = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'TIME_IN' => $time_in,
                            'TIME_IN_BEHAVIOR' => $time_in_behavior,
                            'TIME_OUT' => $time_out,
                            'TIME_OUT_BEHAVIOR' => $time_out_behavior,
                            'LATE' => $late,
                            'EARLY_LEAVING' => $early_leaving,
                            'OVERTIME' => $overtime,
                            'TOTAL_WORKING_HOURS' => $total_working_hours,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance" data-attendance-id="'. $attendance_id .'" title="View Attendance">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $attendance_adjustment .'
                                '. $transaction_log .'
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

    # My attendance adjustment table
    else if($type == 'my attendance adjustment table'){
        if(isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_for_recommendation_start_date']) && isset($_POST['filter_for_recommendation_end_date']) && isset($_POST['filter_recommendation_start_date']) && isset($_POST['filter_recommendation_end_date']) && isset($_POST['filter_decision_start_date']) && isset($_POST['filter_decision_end_date']) && isset($_POST['filter_status']) && isset($_POST['filter_sanction'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_attendance_adjustment = $api->check_role_permissions($username, 124);
                $cancel_attendance_adjustment = $api->check_role_permissions($username, 125);
	            $tag_attendance_adjustment_for_recommendation = $api->check_role_permissions($username, 126);
	            $tag_attendance_adjustment_as_pending = $api->check_role_permissions($username, 127);
                $delete_attendance_adjustment = $api->check_role_permissions($username, 128);
                $view_transaction_log = $api->check_role_permissions($username, 129);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details(1);
                $approval_type_status = $approval_type_details[0]['STATUS'];
                $check_approval_exception_exist = $api->check_approval_exception_exist(1, $employee_id);

                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_creation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_start_date = $api->check_date('empty', $_POST['filter_for_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_end_date = $api->check_date('empty', $_POST['filter_for_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_start_date = $api->check_date('empty', $_POST['filter_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_end_date = $api->check_date('empty', $_POST['filter_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_start_date = $api->check_date('empty', $_POST['filter_decision_start_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_end_date = $api->check_date('empty', $_POST['filter_decision_end_date'], '', 'Y-m-d', '', '', '');
                $filter_status = $_POST['filter_status'];
                $filter_sanction = $_POST['filter_sanction'];

                $query = 'SELECT ADJUSTMENT_ID, ATTENDANCE_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_adjustment WHERE EMPLOYEE_ID = :employee_id';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || $filter_sanction != ''){
                    $query .= ' AND ';

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $filter[] = 'DATE(FOR_RECOMMENDATION_DATE) BETWEEN :filter_for_recommendation_start_date AND :filter_for_recommendation_end_date';
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $filter[] = 'DATE(RECOMMENDATION_DATE) BETWEEN :filter_recommendation_start_date AND :filter_recommendation_end_date';
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $filter[] = 'DATE(DECISION_DATE) BETWEEN :filter_decision_start_date AND :filter_decision_end_date';
                    }

                    if(!empty($filter_status)){
                        $filter[] = 'STATUS = :filter_status';
                    }

                    if($filter_sanction != ''){
                        $filter[] = 'SANCTION = :filter_sanction';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || $filter_sanction != ''){

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $sql->bindValue(':filter_for_recommendation_start_date', $filter_for_recommendation_start_date);
                        $sql->bindValue(':filter_for_recommendation_end_date', $filter_for_recommendation_end_date);
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $sql->bindValue(':filter_recommendation_start_date', $filter_recommendation_start_date);
                        $sql->bindValue(':filter_recommendation_end_date', $filter_recommendation_end_date);
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $sql->bindValue(':filter_decision_start_date', $filter_decision_start_date);
                        $sql->bindValue(':filter_decision_end_date', $filter_decision_end_date);
                    }

                    if(!empty($filter_status)){
                        $sql->bindValue(':filter_status', $filter_status);
                    }

                    if($filter_sanction != ''){
                        $sql->bindValue(':filter_sanction', $filter_sanction);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $adjustment_id = $row['ADJUSTMENT_ID'];
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $status_name = $api->get_attendance_adjustment_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_adjustment_sanction($sanction)[0]['BADGE'];

                        $attendance_details = $api->get_attendance_details($attendance_id);
                        $attendance_time_in = $api->check_date('empty', $attendance_details[0]['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $attendance_time_out = $api->check_date('empty', $attendance_details[0]['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');

                        if(strtotime($time_in) != strtotime($attendance_time_in)){
                            $time_in_details = $attendance_time_in . ' -> ' . $time_in;
                        }
                        else{
                            $time_in_details = $time_in;
                        }

                        if(!empty($time_out)){
                            $adjustment_type = 'full';

                            if(strtotime($time_out) != strtotime($attendance_time_out)){
                                $time_out_details = $attendance_time_out . ' -> ' . $time_out;
                            }
                            else{
                                $time_out_details = $time_out;
                            }
                        }
                        else{
                            $adjustment_type = 'partial';
                            $time_out_details = '--';
                        }

                        if($cancel_attendance_adjustment > 0 && ($status == 'PEN' || $status == 'REC' || $status == 'FORREC')){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Cancel Attendance Adjustment">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($tag_attendance_adjustment_as_pending > 0 && $status == 'FORREC'){
                            $data_pending = 1;
                            $pending = '<button type="button" class="btn btn-info waves-effect waves-light pending-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Tag Attendance Adjustment As Pending">
                                        <i class="bx bx-revision font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $data_pending = 0;
                            $pending = '';
                        }

                        if($delete_attendance_adjustment > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Delete Attendance Adjustment">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($status == 'PEN' && $check_approval_exception_exist == 0 && $approval_type_status == 'ACTIVE' && $tag_attendance_adjustment_for_recommendation > 0){
                            $data_for_recommendation = 1;
                            $for_recommendation = '<button type="button" class="btn btn-success waves-effect waves-light for-recommend-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Tag Attendance Adjustment For Recommendation">
                                <i class="bx bx-check font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $data_for_recommendation = 0;
                            $for_recommendation = '';
                        }

                        if($update_attendance_adjustment > 0 && $status == 'PEN'){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-attendance-adjustment" data-adjustment-type="'. $adjustment_type .'" data-adjustment-id="'. $adjustment_id .'" title="Update Attendance Adjustment">
                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if($status == 'PEN' || $status == 'REC' || $status == 'FORREC'){
                            $data_cancel = 1;
                        }
                        else{
                            $data_cancel = 0;
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="'. $data_cancel .'"  data-pending="'. $data_pending .'" data-for-recommendation="'. $data_for_recommendation .'" type="checkbox" value="'. $adjustment_id .'">',
                            'TIME_IN' => $time_in_details,
                            'TIME_OUT' => $time_out_details,
                            'STATUS' => $status_name,
                            'SANCTION' => $sanction_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="View Attendance Adjustment">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $for_recommendation .'
                                '. $pending .'
                                '. $cancel .'
                                '. $transaction_log .'
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

    # My attendance creation table
    else if($type == 'my attendance creation table'){
        if(isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_for_recommendation_start_date']) && isset($_POST['filter_for_recommendation_end_date']) && isset($_POST['filter_recommendation_start_date']) && isset($_POST['filter_recommendation_end_date']) && isset($_POST['filter_decision_start_date']) && isset($_POST['filter_decision_end_date']) && isset($_POST['filter_status']) && isset($_POST['filter_sanction'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_attendance_creation = $api->check_role_permissions($username, 132);
                $cancel_attendance_creation = $api->check_role_permissions($username, 133);
	            $tag_attendance_creation_for_recommendation = $api->check_role_permissions($username, 134);
	            $tag_attendance_creation_as_pending = $api->check_role_permissions($username, 135);
                $delete_attendance_creation = $api->check_role_permissions($username, 136);
                $view_transaction_log = $api->check_role_permissions($username, 137);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details(3);
                $approval_type_status = $approval_type_details[0]['STATUS'];
                $check_approval_exception_exist = $api->check_approval_exception_exist(3, $employee_id);

                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_for_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_start_date = $api->check_date('empty', $_POST['filter_for_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_end_date = $api->check_date('empty', $_POST['filter_for_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_start_date = $api->check_date('empty', $_POST['filter_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_end_date = $api->check_date('empty', $_POST['filter_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_start_date = $api->check_date('empty', $_POST['filter_decision_start_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_end_date = $api->check_date('empty', $_POST['filter_decision_end_date'], '', 'Y-m-d', '', '', '');
                $filter_status = $_POST['filter_status'];
                $filter_sanction = $_POST['filter_sanction'];

                $query = 'SELECT CREATION_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_creation WHERE EMPLOYEE_ID = :employee_id';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || $filter_sanction != ''){
                    $query .= ' AND ';

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $filter[] = 'DATE(FOR_RECOMMENDATION_DATE) BETWEEN :filter_for_recommendation_start_date AND :filter_for_recommendation_end_date';
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $filter[] = 'DATE(RECOMMENDATION_DATE) BETWEEN :filter_recommendation_start_date AND :filter_recommendation_end_date';
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $filter[] = 'DATE(DECISION_DATE) BETWEEN :filter_decision_start_date AND :filter_decision_end_date';
                    }

                    if(!empty($filter_status)){
                        $filter[] = 'STATUS = :filter_status';
                    }

                    if($filter_sanction != ''){
                        $filter[] = 'SANCTION = :filter_sanction';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || $filter_sanction != ''){

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $sql->bindValue(':filter_for_recommendation_start_date', $filter_for_recommendation_start_date);
                        $sql->bindValue(':filter_for_recommendation_end_date', $filter_for_recommendation_end_date);
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $sql->bindValue(':filter_recommendation_start_date', $filter_recommendation_start_date);
                        $sql->bindValue(':filter_recommendation_end_date', $filter_recommendation_end_date);
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $sql->bindValue(':filter_decision_start_date', $filter_decision_start_date);
                        $sql->bindValue(':filter_decision_end_date', $filter_decision_end_date);
                    }

                    if(!empty($filter_status)){
                        $sql->bindValue(':filter_status', $filter_status);
                    }

                    if($filter_sanction != ''){
                        $sql->bindValue(':filter_sanction', $filter_sanction);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $creation_id = $row['CREATION_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $status_name = $api->get_attendance_creation_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_creation_sanction($sanction)[0]['BADGE'];

                        if($cancel_attendance_creation > 0 && ($status == 'PEN' || $status == 'REC' || $status == 'FORREC')){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-creation" data-creation-id="'. $creation_id .'" title="Cancel Attendance Creation">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($tag_attendance_creation_as_pending > 0 && $status == 'FORREC'){
                            $data_pending = 1;
                            $pending = '<button type="button" class="btn btn-info waves-effect waves-light pending-attendance-creation" data-creation-id="'. $creation_id .'" title="Tag Attendance Creation As Pending">
                                        <i class="bx bx-revision font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $data_pending = 0;
                            $pending = '';
                        }

                        if($delete_attendance_creation > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-attendance-creation" data-creation-id="'. $creation_id .'" title="Delete Attendance Creation">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($status == 'PEN' && $check_approval_exception_exist == 0 && $approval_type_status == 'ACTIVE' && $tag_attendance_creation_for_recommendation > 0){
                            $data_for_recommendation = 1;
                            $for_recommendation = '<button type="button" class="btn btn-success waves-effect waves-light for-recommend-attendance-creation" data-creation-id="'. $creation_id .'" title="Tag Attendance Creation For Recommendation">
                                <i class="bx bx-check font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $data_for_recommendation = 0;
                            $for_recommendation = '';
                        }

                        if(($update_attendance_creation  > 0) && $status == 'PEN'){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-attendance-creation" data-creation-id="'. $creation_id .'" title="Update Attendance Creation">
                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if($status == 'PEN' || $status == 'REC' || $status == 'FORREC'){
                            $data_cancel = 1;
                        }
                        else{
                            $data_cancel = 0;
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="'. $data_cancel .'" data-pending="'. $data_pending .'" data-for-recommendation="'. $data_for_recommendation .'" type="checkbox" value="'. $creation_id .'">',
                            'TIME_IN' => $time_in,
                            'TIME_OUT' => $time_out,
                            'STATUS' => $status_name,
                            'SANCTION' => $sanction_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-creation" data-creation-id="'. $creation_id .'" title="View Attendance Creation">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $for_recommendation .'
                                '. $pending .'
                                '. $cancel .'
                                '. $transaction_log .'
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

    # Approval type table
    else if($type == 'approval type table'){
        if(isset($_POST['filter_approval_type_status'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_approval_type = $api->check_role_permissions($username, 140);
                $activate_approval_type = $api->check_role_permissions($username, 141);
                $deactivate_approval_type = $api->check_role_permissions($username, 142);
                $delete_approval_type = $api->check_role_permissions($username, 143);
                $view_transaction_log = $api->check_role_permissions($username, 144);
                $approver_page = $api->check_role_permissions($username, 145);
                $approval_exception_page = $api->check_role_permissions($username, 148);

                $filter_approval_type_status = $_POST['filter_approval_type_status'];

                $query = 'SELECT APPROVAL_TYPE_ID, APPROVAL_TYPE, APPROVAL_TYPE_DESCRIPTION, STATUS, TRANSACTION_LOG_ID FROM approval_type';

                if(!empty($filter_approval_type_status)){
                    $filter[] = ' WHERE STATUS = :filter_approval_type_status';

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_approval_type_status)){
                    $sql->bindValue(':filter_approval_type_status', $filter_approval_type_status);
                } 
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $approval_type_id = $row['APPROVAL_TYPE_ID'];
                        $approval_type = $row['APPROVAL_TYPE'];
                        $approval_type_description = $row['APPROVAL_TYPE_DESCRIPTION'];
                        $status = $row['STATUS'];
                        $approval_type_status = $api->get_approval_type_status($status)[0]['BADGE'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];
                        $approval_type_id_encrypted = $api->encrypt_data($approval_type_id);

                        if($approver_page > 0){
                            $approver = '<a href="approver.php?id='. $approval_type_id_encrypted .'" class="btn btn-success waves-effect waves-light" title="View Approver">
                                        <i class="bx bx-user-check font-size-16 align-middle"></i>
                                    </a>';
                        }
                        else{
                            $approver = '';
                        }

                        if($approval_exception_page > 0){
                            $exception = '<a href="approval-exception.php?id='. $approval_type_id_encrypted .'" class="btn btn-warning waves-effect waves-light" title="View Approval Exception">
                                        <i class="bx bx-user-x font-size-16 align-middle"></i>
                                    </a>';
                        }
                        else{
                            $exception = '';
                        }
    
                        if($update_approval_type > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-approval-type" data-approval-type-id="'. $approval_type_id .'" title="Edit Approval Type">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }
    
                        if($status == 'ACTIVE'){
                            if($deactivate_approval_type > 0){
                                $active_inactive = '<button class="btn btn-danger waves-effect waves-light deactivate-approval-type" title="Deactivate Approval Type" data-approval-type-id="'. $approval_type_id .'">
                                <i class="bx bx-x font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $active_inactive = '';
                            }
    
                            $data_active = '1';
                        }
                        else{
                            if($activate_approval_type > 0){
                                $active_inactive = '<button class="btn btn-success waves-effect waves-light activate-approval-type" title="Activate Approval Type" data-approval-type-id="'. $approval_type_id .'">
                                <i class="bx bx-check font-size-16 align-middle"></i>
                                </button>';
                            }
                            else{
                                $active_inactive = '';
                            }
    
                            $data_active = '0';
                        }

                        if($delete_approval_type > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-approval-type" data-approval-type-id="'. $approval_type_id .'" title="Delete Approval Type">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }
    
                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-active="'. $data_active .'" value="'. $approval_type_id .'">',
                            'APPROVAL_TYPE' => $approval_type . '<p class="text-muted mb-0">'. $approval_type_description .'</p>',
                            'STATUS' => $approval_type_status,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-approval-type" data-approval-type-id="'. $approval_type_id .'" title="View Approval Type">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $active_inactive .'
                                '. $approver .'
                                '. $exception .'
                                '. $transaction_log .'
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

    # Approver table
    else if($type == 'approver table'){
        if(isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            if ($api->databaseConnection()) {
                $approval_type_id = $_POST['approval_type_id'];

                # Get approver
                $delete_approver = $api->check_role_permissions($username, 147);
    
                $sql = $api->db_connection->prepare('SELECT EMPLOYEE_ID, DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id ORDER BY EMPLOYEE_ID');
                $sql->bindValue(':approval_type_id', $approval_type_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $employee_id = $row['EMPLOYEE_ID'];
                        $department = $row['DEPARTMENT'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'];

                        $department_details = $api->get_department_details($department);
                        $department_name = $department_details[0]['DEPARTMENT'];

                        if($delete_approver > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-approver" data-employee-id="'. $employee_id .'" data-department="'. $department .'" title="Delete Approver">
                                            <i class="bx bx-trash font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $delete = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" data-employee-id="'. $employee_id .'" data-department="'. $department .'">',
                            'FILE_AS' => $file_as,
                            'DEPARTMENT' => $department_name,
                            'ACTION' => '<div class="d-flex gap-2">
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

    # Approval exception table
    else if($type == 'approval exception table'){
        if(isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            if ($api->databaseConnection()) {
                $approval_type_id = $_POST['approval_type_id'];

                # Get approver
                $delete_approval_exception = $api->check_role_permissions($username, 150);
    
                $sql = $api->db_connection->prepare('SELECT EMPLOYEE_ID FROM approval_exception WHERE APPROVAL_TYPE_ID = :approval_type_id ORDER BY EMPLOYEE_ID');
                $sql->bindValue(':approval_type_id', $approval_type_id);
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $employee_id = $row['EMPLOYEE_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'];

                        if($delete_approval_exception > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-approval-exception" data-employee-id="'. $employee_id .'" title="Delete Approver">
                                            <i class="bx bx-trash font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $delete = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employee_id .'">',
                            'FILE_AS' => $file_as,
                            'ACTION' => '<div class="d-flex gap-2">
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

    # Attendance adjustment recommendation table
    else if($type == 'attendance adjustment recommendation table'){
        if(isset($_POST['filter_for_recommendation_start_date']) && isset($_POST['filter_for_recommendation_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                # Get permission
                $recommend_attendance_adjustment = $api->check_role_permissions($username, 152);
                $reject_attendance_adjustment = $api->check_role_permissions($username, 153);
                $cancel_attendance_adjustment = $api->check_role_permissions($username, 154);
                $view_transaction_log = $api->check_role_permissions($username, 155);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $filter_for_recommendation_start_date = $api->check_date('empty', $_POST['filter_for_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_end_date = $api->check_date('empty', $_POST['filter_for_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT ADJUSTMENT_ID, EMPLOYEE_ID, ATTENDANCE_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_adjustment WHERE STATUS = :status AND EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT IN (SELECT DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id AND EMPLOYEE_ID = :employee_id))';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    $query .= ' AND ';

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $filter[] = 'DATE(FOR_RECOMMENDATION_DATE) BETWEEN :filter_for_recommendation_start_date AND :filter_for_recommendation_end_date';
                    }
                    
                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':status', 'FORREC');
                $sql->bindValue(':approval_type_id', '1');
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $sql->bindValue(':filter_for_recommendation_start_date', $filter_for_recommendation_start_date);
                        $sql->bindValue(':filter_for_recommendation_end_date', $filter_for_recommendation_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $adjustment_id = $row['ADJUSTMENT_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $status_name = $api->get_attendance_adjustment_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_adjustment_sanction($sanction)[0]['BADGE'];

                        $attendance_details = $api->get_attendance_details($attendance_id);
                        $attendance_time_in = $api->check_date('empty', $attendance_details[0]['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $attendance_time_out = $api->check_date('empty', $attendance_details[0]['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');

                        if(strtotime($time_in) != strtotime($attendance_time_in)){
                            $time_in_details = $attendance_time_in . ' -> ' . $time_in;
                        }
                        else{
                            $time_in_details = $time_in;
                        }

                        if(!empty($time_out)){
                            if(strtotime($time_out) != strtotime($attendance_time_out)){
                                $time_out_details = $attendance_time_out . ' -> ' . $time_out;
                            }
                            else{
                                $time_out_details = $time_out;
                            }
                        }
                        else{
                            $time_out_details = '--';
                        }

                        if($recommend_attendance_adjustment > 0){
                            $recommend = '<button type="button" class="btn btn-success waves-effect waves-light recommend-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Recommend Attendance Adjustment">
                                        <i class="bx bx-check font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $recommend = '';
                        }

                        if($reject_attendance_adjustment > 0){
                            $reject = '<button type="button" class="btn btn-danger waves-effect waves-light reject-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Reject Attendance Adjustment">
                                        <i class="bx bx-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $reject = '';
                        }

                        if($cancel_attendance_adjustment > 0){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Cancel Attendance Adjustment">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="1" data-recommend="1" data-reject="1" type="checkbox" value="'. $adjustment_id .'">',
                            'FILE_AS' => $file_as,
                            'TIME_IN' => $time_in_details,
                            'TIME_OUT' => $time_out_details,
                            'STATUS' => $status_name,
                            'SANCTION' => $sanction_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="View Attendance Adjustment">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $recommend .'
                                '. $reject .'
                                '. $cancel .'
                                '. $transaction_log .'
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

    # Attendance creation recommendation table
    else if($type == 'attendance creation recommendation table'){
        if(isset($_POST['filter_for_recommendation_start_date']) && isset($_POST['filter_for_recommendation_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                # Get permission
                $recommend_attendance_creation = $api->check_role_permissions($username, 157);
                $reject_attendance_creation = $api->check_role_permissions($username, 158);
                $cancel_attendance_creation = $api->check_role_permissions($username, 159);
                $view_transaction_log = $api->check_role_permissions($username, 160);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $filter_for_recommendation_start_date = $api->check_date('empty', $_POST['filter_for_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_recommendation_end_date = $api->check_date('empty', $_POST['filter_for_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT CREATION_ID, EMPLOYEE_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_creation WHERE STATUS = :status AND EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT IN (SELECT DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id AND EMPLOYEE_ID = :employee_id))';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    $query .= ' AND ';

                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $filter[] = 'DATE(FOR_RECOMMENDATION_DATE) BETWEEN :filter_for_recommendation_start_date AND :filter_for_recommendation_end_date';
                    }
                    
                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':status', 'FORREC');
                $sql->bindValue(':employee_id', $employee_id);
                $sql->bindValue(':approval_type_id', '3');

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    if(!empty($filter_for_recommendation_start_date) && !empty($filter_for_recommendation_end_date)){
                        $sql->bindValue(':filter_for_recommendation_start_date', $filter_for_recommendation_start_date);
                        $sql->bindValue(':filter_for_recommendation_end_date', $filter_for_recommendation_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $creation_id = $row['CREATION_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $status_name = $api->get_attendance_creation_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_creation_sanction($sanction)[0]['BADGE'];

                        if($recommend_attendance_creation > 0){
                            $recommend = '<button type="button" class="btn btn-success waves-effect waves-light recommend-attendance-creation" data-creation-id="'. $creation_id .'" title="Recommend Attendance Creation">
                                        <i class="bx bx-check font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $recommend = '';
                        }

                        if($reject_attendance_creation > 0){
                            $reject = '<button type="button" class="btn btn-danger waves-effect waves-light reject-attendance-creation" data-creation-id="'. $creation_id .'" title="Reject Attendance Creation">
                                        <i class="bx bx-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $reject = '';
                        }

                        if($cancel_attendance_creation > 0){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-creation" data-creation-id="'. $creation_id .'" title="Cancel Attendance Creation">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="1" data-recommend="1" data-reject="1" type="checkbox" value="'. $creation_id .'">',
                            'FILE_AS' => $file_as,
                            'TIME_IN' => $time_in,
                            'TIME_OUT' => $time_out,
                            'STATUS' => $status_name,
                            'SANCTION' => $sanction_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-creation" data-creation-id="'. $creation_id .'" title="View Attendance Adjustment">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $recommend .'
                                '. $reject .'
                                '. $cancel .'
                                '. $transaction_log .'
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

    # Attendance adjustment approval table
    else if($type == 'attendance adjustment approval table'){
        if(isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_recommendation_start_date']) && isset($_POST['filter_recommendation_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                # Get permission
                $approve_attendance_adjustment = $api->check_role_permissions($username, 162);
                $reject_attendance_adjustment = $api->check_role_permissions($username, 163);
                $cancel_attendance_adjustment = $api->check_role_permissions($username, 164);
                $view_transaction_log = $api->check_role_permissions($username, 165);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details('2');
                $approval_type_status = $approval_type_details[0]['STATUS'];

                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_creation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_start_date = $api->check_date('empty', $_POST['filter_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_end_date = $api->check_date('empty', $_POST['filter_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT ADJUSTMENT_ID, EMPLOYEE_ID, ATTENDANCE_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_adjustment WHERE EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT IN (SELECT DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id AND EMPLOYEE_ID = :employee_id)) AND STATUS IN ("PEN", "FORREC", "REC")';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    $query .= ' AND ';

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $filter[] = 'DATE(RECOMMENDATION_DATE) BETWEEN :filter_recommendation_start_date AND :filter_recommendation_end_date';
                    }
                    
                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':approval_type_id', '2');
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $sql->bindValue(':filter_recommendation_start_date', $filter_recommendation_start_date);
                        $sql->bindValue(':filter_recommendation_end_date', $filter_recommendation_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $adjustment_id = $row['ADJUSTMENT_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $status_name = $api->get_attendance_adjustment_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_adjustment_sanction($sanction)[0]['BADGE'];

                        $attendance_details = $api->get_attendance_details($attendance_id);
                        $attendance_time_in = $api->check_date('empty', $attendance_details[0]['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $attendance_time_out = $api->check_date('empty', $attendance_details[0]['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');

                        if(strtotime($time_in) != strtotime($attendance_time_in)){
                            $time_in_details = $attendance_time_in . ' -> ' . $time_in;
                        }
                        else{
                            $time_in_details = $time_in;
                        }

                        if(!empty($time_out)){
                            if(strtotime($time_out) != strtotime($attendance_time_out)){
                                $time_out_details = $attendance_time_out . ' -> ' . $time_out;
                            }
                            else{
                                $time_out_details = $time_out;
                            }
                        }
                        else{
                            $time_out_details = '--';
                        }

                        if($approve_attendance_adjustment > 0){
                            $approve = '<button type="button" class="btn btn-success waves-effect waves-light approve-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Aprove Attendance Adjustment">
                                        <i class="bx bx-check font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $approve = '';
                        }

                        if($reject_attendance_adjustment > 0){
                            $reject = '<button type="button" class="btn btn-danger waves-effect waves-light reject-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Reject Attendance Adjustment">
                                        <i class="bx bx-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $reject = '';
                        }

                        if($cancel_attendance_adjustment > 0){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="Cancel Attendance Adjustment">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if(($approval_type_status == 'INACTIVE' && ($status == 'PEN' || $status == 'FORREC')) || $status == 'REC'){
                            $response[] = array(
                                'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="1" data-approve="1" data-reject="1" type="checkbox" value="'. $adjustment_id .'">',
                                'FILE_AS' => $file_as,
                                'TIME_IN' => $time_in_details,
                                'TIME_OUT' => $time_out_details,
                                'STATUS' => $status_name,
                                'SANCTION' => $sanction_name,
                                'ACTION' => '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-adjustment" data-adjustment-id="'. $adjustment_id .'" title="View Attendance Adjustment">
                                        <i class="bx bx-show font-size-16 align-middle"></i>
                                    </button>
                                    '. $approve .'
                                    '. $reject .'
                                    '. $cancel .'
                                    '. $transaction_log .'
                                </div>'
                            );
                        }
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

    # Attendance creation approval table
    else if($type == 'attendance creation approval table'){
        if(isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_recommendation_start_date']) && isset($_POST['filter_recommendation_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                # Get permission
                $approve_attendance_creation = $api->check_role_permissions($username, 167);
                $reject_attendance_creation = $api->check_role_permissions($username, 168);
                $cancel_attendance_creation = $api->check_role_permissions($username, 169);
                $view_transaction_log = $api->check_role_permissions($username, 170);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details('4');
                $approval_type_status = $approval_type_details[0]['STATUS'];

                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_creation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_start_date = $api->check_date('empty', $_POST['filter_recommendation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_recommendation_end_date = $api->check_date('empty', $_POST['filter_recommendation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT CREATION_ID, EMPLOYEE_ID, TIME_IN, TIME_OUT, STATUS, SANCTION, TRANSACTION_LOG_ID FROM attendance_creation WHERE EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT IN (SELECT DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id AND EMPLOYEE_ID = :employee_id)) AND STATUS IN ("PEN", "FORREC", "REC")';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    $query .= ' AND ';

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $filter[] = 'DATE(RECOMMENDATION_DATE) BETWEEN :filter_recommendation_start_date AND :filter_recommendation_end_date';
                    }
                    
                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':approval_type_id', '4');
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)) || !empty($filter_work_location) || !empty($filter_department)){
                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_recommendation_start_date) && !empty($filter_recommendation_end_date)){
                        $sql->bindValue(':filter_recommendation_start_date', $filter_recommendation_start_date);
                        $sql->bindValue(':filter_recommendation_end_date', $filter_recommendation_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $creation_id = $row['CREATION_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $time_in = $api->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $api->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $status_name = $api->get_attendance_creation_status($status)[0]['BADGE'];
                        $sanction_name = $api->get_attendance_creation_sanction($sanction)[0]['BADGE'];

                        if($approve_attendance_creation > 0){
                            $approve = '<button type="button" class="btn btn-success waves-effect waves-light approve-attendance-creation" data-creation-id="'. $creation_id .'" title="Approve Attendance Creation">
                                        <i class="bx bx-check font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $approve = '';
                        }

                        if($reject_attendance_creation > 0){
                            $reject = '<button type="button" class="btn btn-danger waves-effect waves-light reject-attendance-creation" data-creation-id="'. $creation_id .'" title="Reject Attendance Creation">
                                        <i class="bx bx-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $reject = '';
                        }

                        if($cancel_attendance_creation > 0){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-attendance-creation" data-creation-id="'. $creation_id .'" title="Cancel Attendance Creation">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if(($approval_type_status == 'INACTIVE' && ($status == 'PEN' || $status == 'FORREC')) || $status == 'REC'){
                            $response[] = array(
                                'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="1" data-approve="1" data-reject="1" type="checkbox" value="'. $creation_id .'">',
                                'FILE_AS' => $file_as,
                                'TIME_IN' => $time_in,
                                'TIME_OUT' => $time_out,
                                'STATUS' => $status_name,
                                'SANCTION' => $sanction_name,
                                'ACTION' => '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary waves-effect waves-light view-attendance-creation" data-creation-id="'. $creation_id .'" title="View Attendance Adjustment">
                                        <i class="bx bx-show font-size-16 align-middle"></i>
                                    </button>
                                    '. $approve .'
                                    '. $reject .'
                                    '. $cancel .'
                                    '. $transaction_log .'
                                </div>'
                            );
                        }
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

    # Public holiday table
    else if($type == 'public holiday table'){
        if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date']) && isset($_POST['filter_work_location']) && isset($_POST['filter_holiday_type'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_public_holiday = $api->check_role_permissions($username, 175);
                $delete_public_holiday = $api->check_role_permissions($username, 176);
                $view_transaction_log = $api->check_role_permissions($username, 177);

                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');
                $filter_work_location = $_POST['filter_work_location'];
                $filter_holiday_type = $_POST['filter_holiday_type'];

                $query = 'SELECT PUBLIC_HOLIDAY_ID, PUBLIC_HOLIDAY, HOLIDAY_DATE, HOLIDAY_TYPE, TRANSACTION_LOG_ID FROM public_holiday';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_holiday_type)){
                    $query .= ' WHERE ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'HOLIDAY_DATE BETWEEN :filter_start_date AND :filter_end_date';
                    }

                    if(!empty($filter_work_location)){
                        $filter[] = 'PUBLIC_HOLIDAY_ID IN (SELECT PUBLIC_HOLIDAY_ID FROM public_holiday_work_location WHERE WORK_LOCATION_ID = :filter_work_location)';
                    }

                    if(!empty($filter_holiday_type)){
                        $filter[] = 'HOLIDAY_TYPE = :filter_holiday_type';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_holiday_type)){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_holiday_type)){
                        $sql->bindValue(':filter_holiday_type', $filter_holiday_type);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $public_holiday_id = $row['PUBLIC_HOLIDAY_ID'];
                        $public_holiday = $row['PUBLIC_HOLIDAY'];
                        $holiday_type = $row['HOLIDAY_TYPE'];
                        $holiday_date = $api->check_date('empty', $row['HOLIDAY_DATE'], '', 'm/d/Y', '', '', '');
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $system_code_details = $api->get_system_code_details('HOLIDAYTYPE', $holiday_type);
                        $holiday_type_name = $system_code_details[0]['SYSTEM_DESCRIPTION'];

                        if($update_public_holiday > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-public-holiday" data-public-holiday-id="'. $public_holiday_id .'" title="Edit Public Holiday">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($delete_public_holiday > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-public-holiday" data-public-holiday-id="'. $public_holiday_id .'" title="Delete Public Holiday">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $public_holiday_id .'">',
                            'PUBLIC_HOLIDAY' => $public_holiday,
                            'HOLIDAY_DATE' => $holiday_date,
                            'HOLIDAY_TYPE' => $holiday_type_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-public-holiday" data-public-holiday-id="'. $public_holiday_id .'" title="View Public Holiday">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $transaction_log .'
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

    # Leave type table
    else if($type == 'leave type table'){
        if(isset($_POST['filter_paid_type']) && isset($_POST['allocation_type'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_leave_type = $api->check_role_permissions($username, 180);
                $delete_leave_type = $api->check_role_permissions($username, 181);
                $view_transaction_log = $api->check_role_permissions($username, 182);

                $filter_paid_type = $_POST['filter_paid_type'];
                $allocation_type = $_POST['allocation_type'];

                $query = 'SELECT LEAVE_TYPE_ID, LEAVE_TYPE, PAID_TYPE, ALLOCATION_TYPE, TRANSACTION_LOG_ID FROM leave_type';

                if(!empty($filter_paid_type) || !empty($allocation_type)){
                    $query .= ' WHERE ';

                    if(!empty($filter_paid_type)){
                        $filter[] = 'PAID_TYPE = :filter_paid_type';
                    }

                    if(!empty($allocation_type)){
                        $filter[] = 'ALLOCATION_TYPE = :allocation_type';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if(!empty($filter_paid_type) || !empty($allocation_type)){
                    if(!empty($filter_paid_type)){
                        $sql->bindValue(':filter_paid_type', $filter_paid_type);
                    }

                    if(!empty($allocation_type)){
                        $sql->bindValue(':allocation_type', $allocation_type);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $leave_type = $row['LEAVE_TYPE'];
                        $paid_type = $row['PAID_TYPE'];
                        $allocation_type = $row['ALLOCATION_TYPE'];
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $leave_type_paid_type = $api->get_leave_type_paid_type($paid_type);
                        $leave_type_allocation_type = $api->get_leave_type_allocation_type($allocation_type);

                        if($update_leave_type > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-leave-type" data-leave-type-id="'. $leave_type_id .'" title="Edit Leave Type">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($delete_leave_type > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-leave-type" data-leave-type-id="'. $leave_type_id .'" title="Delete Leave Type">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leave_type_id .'">',
                            'LEAVE_TYPE' => $leave_type,
                            'PAID_TYPE' => $leave_type_paid_type,
                            'ALLOCATION_TYPE' => $leave_type_allocation_type,
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $transaction_log .'
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

    # Leave allocation table
    else if($type == 'leave allocation table'){
        if(isset($_POST['filter_start_date']) && isset($_POST['filter_end_date']) && isset($_POST['filter_leave_type']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department']) && isset($_POST['filter_job_position']) && isset($_POST['filter_employee_type'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_leave_allocation = $api->check_role_permissions($username, 185);
                $delete_leave_allocation = $api->check_role_permissions($username, 186);
                $view_transaction_log = $api->check_role_permissions($username, 187);

                $filter_start_date = $api->check_date('empty', $_POST['filter_start_date'], '', 'Y-m-d', '', '', '');
                $filter_end_date = $api->check_date('empty', $_POST['filter_end_date'], '', 'Y-m-d', '', '', '');
                $filter_leave_type = $_POST['filter_leave_type'];
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];
                $filter_job_position = $_POST['filter_job_position'];
                $filter_employee_type = $_POST['filter_employee_type'];

                $query = 'SELECT LEAVE_ALLOCATION_ID, LEAVE_TYPE_ID, EMPLOYEE_ID, VALIDITY_START_DATE, VALIDITY_END_DATE, DURATION, AVAILED, TRANSACTION_LOG_ID FROM leave_allocation';

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type)){
                    $query .= ' WHERE ';

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $filter[] = 'VALIDITY_START_DATE BETWEEN :filter_start_date AND :filter_end_date';
                    }

                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter_job_position)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE JOB_POSITION = :filter_job_position)';
                    }

                    if(!empty($filter_employee_type)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE EMPLOYEE_TYPE = :filter_employee_type)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);

                if((!empty($filter_start_date) && !empty($filter_end_date)) || !empty($filter_work_location) || !empty($filter_department) || !empty($filter_job_position) || !empty($filter_employee_type)){

                    if(!empty($filter_start_date) && !empty($filter_end_date)){
                        $sql->bindValue(':filter_start_date', $filter_start_date);
                        $sql->bindValue(':filter_end_date', $filter_end_date);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }

                    if(!empty($filter_job_position)){
                        $sql->bindValue(':filter_job_position', $filter_job_position);
                    }

                    if(!empty($filter_employee_type)){
                        $sql->bindValue(':filter_employee_type', $filter_employee_type);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $leave_allocation_id = $row['LEAVE_ALLOCATION_ID'];
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $validity_start_date = $api->check_date('empty', $row['VALIDITY_START_DATE'], '', 'm/d/Y', '', '', '');
                        $validity_end_date = $api->check_date('empty', $row['VALIDITY_END_DATE'], '', 'm/d/Y', '', '', '');
                        $duration = $row['DURATION'];
                        $availed = $row['AVAILED'];
                        $allocation = $duration - $availed;
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $leave_type_details = $api->get_leave_type_details($leave_type_id);
                        $leave_type = $leave_type_details[0]['LEAVE_TYPE'];

                        if(empty($validity_end_date)){
                            $validity_end_date = 'No Limit';
                        }

                        if($update_leave_allocation > 0){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-leave-allocation" data-leave-allocation-id="'. $leave_allocation_id .'" title="Edit Leave Allocation">
                                            <i class="bx bx-pencil font-size-16 align-middle"></i>
                                        </button>';
                        }
                        else{
                            $update = '';
                        }

                        if($delete_leave_allocation > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-leave-allocation" data-leave-allocation-id="'. $leave_allocation_id .'" title="Delete Leave Allocation">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leave_allocation_id .'">',
                            'FILE_AS' => $file_as,
                            'LEAVE_TYPE' => $leave_type,
                            'VALIDITY' => $validity_start_date . ' - ' . $validity_end_date,
                            'DURATION' => $allocation . ' hour(s) remaining out of ' . $duration . ' hour(s)',
                            'ACTION' => '<div class="d-flex gap-2">
                                '. $update .'
                                '. $transaction_log .'
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

    # My leave table
    else if($type == 'my leave table'){
        if(isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_leave_start_date']) && isset($_POST['filter_leave_end_date']) && isset($_POST['filter_for_approval_start_date']) && isset($_POST['filter_for_approval_end_date']) && isset($_POST['filter_decision_start_date']) && isset($_POST['filter_decision_end_date']) && isset($_POST['filter_status']) && isset($_POST['filter_leave_type'])){
            if ($api->databaseConnection()) {
                # Get permission
                $update_leave = $api->check_role_permissions($username, 190);
                $cancel_leave = $api->check_role_permissions($username, 191);
	            $tag_leave_for_approval = $api->check_role_permissions($username, 192);
	            $tag_leave_as_pending = $api->check_role_permissions($username, 193);
                $delete_leave = $api->check_role_permissions($username, 194);
                $view_transaction_log = $api->check_role_permissions($username, 195);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details(5);
                $approval_type_status = $approval_type_details[0]['STATUS'];
                $check_approval_exception_exist = $api->check_approval_exception_exist(5, $employee_id);

                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_leave_end_date'], '', 'Y-m-d', '', '', '');
                $filter_leave_start_date = $api->check_date('empty', $_POST['filter_leave_start_date'], '', 'Y-m-d', '', '', '');
                $filter_leave_end_date = $api->check_date('empty', $_POST['filter_leave_end_date'], '', 'Y-m-d', '', '', '');
                $filter_for_approval_start_date = $api->check_date('empty', $_POST['filter_for_approval_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_approval_end_date = $api->check_date('empty', $_POST['filter_for_approval_end_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_start_date = $api->check_date('empty', $_POST['filter_decision_start_date'], '', 'Y-m-d', '', '', '');
                $filter_decision_end_date = $api->check_date('empty', $_POST['filter_decision_end_date'], '', 'Y-m-d', '', '', '');
                $filter_status = $_POST['filter_status'];
                $filter_leave_type = $_POST['filter_leave_type'];

                $query = 'SELECT LEAVE_ID, LEAVE_TYPE_ID, LEAVE_DATE, START_TIME, END_TIME, STATUS, TOTAL_HOURS, TRANSACTION_LOG_ID FROM leave_management WHERE EMPLOYEE_ID = :employee_id';

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_leave_start_date) && !empty($filter_leave_end_date)) || (!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || !empty($filter_leave_type) ){
                    $query .= ' AND ';

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_leave_start_date) && !empty($filter_leave_end_date)){
                        $filter[] = 'DATE(FOR_RECOMMENDATION_DATE) BETWEEN :filter_leave_start_date AND :filter_leave_end_date';
                    }

                    if(!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)){
                        $filter[] = 'DATE(RECOMMENDATION_DATE) BETWEEN :filter_for_approval_start_date AND :filter_for_approval_end_date';
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $filter[] = 'DATE(DECISION_DATE) BETWEEN :filter_decision_start_date AND :filter_decision_end_date';
                    }

                    if(!empty($filter_status)){
                        $filter[] = 'STATUS = :filter_status';
                    }

                    if(!empty($filter_leave_type)){
                        $filter[] = 'SANCTION = :filter_leave_type';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_leave_start_date) && !empty($filter_leave_end_date)) || (!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)) || (!empty($filter_decision_start_date) && !empty($filter_decision_end_date)) || !empty($filter_status) || !empty($filter_leave_type)){

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_leave_start_date) && !empty($filter_leave_end_date)){
                        $sql->bindValue(':filter_leave_start_date', $filter_leave_start_date);
                        $sql->bindValue(':filter_leave_end_date', $filter_leave_end_date);
                    }

                    if(!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)){
                        $sql->bindValue(':filter_for_approval_start_date', $filter_for_approval_start_date);
                        $sql->bindValue(':filter_for_approval_end_date', $filter_for_approval_end_date);
                    }

                    if(!empty($filter_decision_start_date) && !empty($filter_decision_end_date)){
                        $sql->bindValue(':filter_decision_start_date', $filter_decision_start_date);
                        $sql->bindValue(':filter_decision_end_date', $filter_decision_end_date);
                    }

                    if(!empty($filter_status)){
                        $sql->bindValue(':filter_status', $filter_status);
                    }

                    if(!empty($filter_leave_type)){
                        $sql->bindValue(':filter_leave_type', $filter_leave_type);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $leave_id = $row['LEAVE_ID'];
                        $leave_date = $api->check_date('empty', $row['LEAVE_DATE'], '', 'm/d/Y', '', '', '');
                        $start_time = $api->check_date('empty', $row['START_TIME'], '', 'h:i:s a', '', '', '');
                        $end_time = $api->check_date('empty', $row['END_TIME'], '', 'h:i:s a', '', '', '');
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $status = $row['STATUS'];
                        $total_hours = number_format($row['TOTAL_HOURS'], 2);
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_leave_allocation_details = $api->get_employee_leave_allocation_details($employee_id, $leave_type_id, $leave_date);
                        $duration = $employee_leave_allocation_details[0]['DURATION'] ?? 0;
                        $availed = $employee_leave_allocation_details[0]['AVAILED'] ?? 0;
                        $allocation = $duration - $availed;

                        $leave_type_details = $api->get_leave_type_details($leave_type_id);
                        $leave_type = $leave_type_details[0]['LEAVE_TYPE'] ?? null;
                        $allocation_type = $leave_type_details[0]['ALLOCATION_TYPE'] ?? null;

                        $status_name = $api->get_leave_status($status)[0]['BADGE'];

                        if($allocation_type == 'LIMITED'){
                            $duration = $allocation . ' hour(s) remaining out of ' . $duration . ' hour(s)';
                        }
                        else{
                            $duration = 'No Limit';
                        }

                        if($cancel_leave > 0 && ($status == 'PEN' || $status == 'FA' || ($status == 'APV' && strtotime($system_date) < strtotime($leave_date)))){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-leave" data-leave-id="'. $leave_id .'" title="Cancel Leave">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($tag_leave_as_pending > 0 && $status == 'FA'){
                            $data_pending = 1;
                            $pending = '<button type="button" class="btn btn-info waves-effect waves-light pending-leave" data-leave-id="'. $leave_id .'" title="Tag Leave As Pending">
                                        <i class="bx bx-revision font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $data_pending = 0;
                            $pending = '';
                        }

                        if($delete_leave > 0){
                            $delete = '<button type="button" class="btn btn-danger waves-effect waves-light delete-leave" data-leave-id="'. $leave_id .'" title="Delete Leave">
                                <i class="bx bx-trash font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $delete = '';
                        }

                        if($status == 'PEN' && $check_approval_exception_exist == 0 && $approval_type_status == 'ACTIVE' && $tag_leave_for_approval > 0){
                            $data_for_approval = 1;
                            $for_approval = '<button type="button" class="btn btn-success waves-effect waves-light for-approval-leave" data-leave-id="'. $leave_id .'" title="Tag Leave For Approval">
                                <i class="bx bx-check font-size-16 align-middle"></i>
                            </button>';
                        }
                        else{
                            $data_for_approval = 0;
                            $for_approval = '';
                        }

                        if(($update_leave > 0) && $status == 'PEN'){
                            $update = '<button type="button" class="btn btn-info waves-effect waves-light update-leave" data-leave-id="'. $leave_id .'" title="Update Leave">
                                    <i class="bx bx-pencil font-size-16 align-middle"></i>
                                </button>';

                            $upload = '<button type="button" class="btn btn-info waves-effect waves-light add-leave-supporting-document" data-leave-id="'. $leave_id .'" title="Add Leave Supporting Document">
                                    <i class="bx bx-paperclip font-size-16 align-middle"></i>
                                </button>';
                        }
                        else{
                            $update = '';
                            $upload = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if($status == 'PEN' || $status == 'FA'){
                            $data_cancel = 1;
                        }
                        else{
                            $data_cancel = 0;
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="'. $data_cancel .'" data-pending="'. $data_pending .'" data-for-approval="'. $data_for_approval .'" type="checkbox" value="'. $leave_id .'">',
                            'LEAVE_TYPE' => $leave_type,
                            'LEAVE_DATE' => $leave_date . ' ' . $start_time .' - '. $end_time . '<p class="text-muted mb-0"> Total Hours: '. $total_hours .'</p>',
                            'DURATION' => $duration,
                            'STATUS' => $status_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary waves-effect waves-light view-leave" data-leave-id="'. $leave_id .'" title="View Leave">
                                    <i class="bx bx-show font-size-16 align-middle"></i>
                                </button>
                                '. $update .'
                                '. $upload .'
                                '. $for_approval .'
                                '. $pending .'
                                '. $cancel .'
                                '. $transaction_log .'
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

    # Leave supporting document table
    else if($type == 'leave supporting document table'){
        if(isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            if ($api->databaseConnection()) {
                $leave_id = $_POST['leave_id'];
    
                $sql = $api->db_connection->prepare('SELECT LEAVE_SUPPORTING_DOCUMENT_ID, DOCUMENT_NAME, SUPPORTING_DOCUMENT, UPLOADED_BY, UPLOAD_DATE FROM leave_supporting_document WHERE LEAVE_ID = :leave_id');
                $sql->bindValue(':leave_id', $leave_id);

                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $leave_supporting_document_id = $row['LEAVE_SUPPORTING_DOCUMENT_ID'];
                        $document_name = $row['DOCUMENT_NAME'];
                        $supporting_document = $row['SUPPORTING_DOCUMENT'];
                        $uploaded_by = $row['UPLOADED_BY'];
                        $upload_date = $api->check_date('empty', $row['UPLOAD_DATE'], '', 'm/d/Y h:i:s a', '', '', '');

                        $response[] = array(
                            'DOCUMENT_NAME' => '<a href="'. $supporting_document .'" target="_blank">' . $document_name . '</a>',
                            'UPLOADED_BY' => $uploaded_by,
                            'UPLOAD_DATE' => $upload_date,
                            'ACTION' => '<div class="d-flex gap-2">
                                <button type="button" class="btn btn-danger waves-effect waves-light delete-leave-supporting-document" data-leave-supporting-document-id="'. $leave_supporting_document_id .'" title="Delete Leave Supporting Document">
                                    <i class="bx bx-trash font-size-16 align-middle"></i>
                                </button>
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

    # Leave approval table
    else if($type == 'leave approval table'){
        if(isset($_POST['filter_leave_start_date']) && isset($_POST['filter_leave_end_date']) && isset($_POST['filter_creation_start_date']) && isset($_POST['filter_creation_end_date']) && isset($_POST['filter_for_approval_start_date']) && isset($_POST['filter_for_approval_end_date']) && isset($_POST['filter_leave_type']) && isset($_POST['filter_work_location']) && isset($_POST['filter_department'])){
            if ($api->databaseConnection()) {
                # Get permission
                $approve_leave = $api->check_role_permissions($username, 197);
                $reject_leave = $api->check_role_permissions($username, 198);
                $cancel_leave = $api->check_role_permissions($username, 199);
                $view_transaction_log = $api->check_role_permissions($username, 200);

                $employee_details = $api->get_employee_details($username);
                $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                $approval_type_details = $api->get_approval_type_details('6');
                $approval_type_status = $approval_type_details[0]['STATUS'];

                $filter_leave_start_date = $api->check_date('empty', $_POST['filter_leave_start_date'], '', 'Y-m-d', '', '', '');
                $filter_leave_end_date = $api->check_date('empty', $_POST['filter_leave_end_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_start_date = $api->check_date('empty', $_POST['filter_creation_start_date'], '', 'Y-m-d', '', '', '');
                $filter_creation_end_date = $api->check_date('empty', $_POST['filter_creation_end_date'], '', 'Y-m-d', '', '', '');
                $filter_for_approval_start_date = $api->check_date('empty', $_POST['filter_for_approval_start_date'], '', 'Y-m-d', '', '', '');
                $filter_for_approval_end_date = $api->check_date('empty', $_POST['filter_for_approval_end_date'], '', 'Y-m-d', '', '', '');
                $filter_leave_type = $_POST['filter_leave_type'];
                $filter_work_location = $_POST['filter_work_location'];
                $filter_department = $_POST['filter_department'];

                $query = 'SELECT LEAVE_ID, EMPLOYEE_ID, LEAVE_TYPE_ID, LEAVE_DATE, START_TIME, END_TIME, STATUS, TOTAL_HOURS, TRANSACTION_LOG_ID FROM leave_management WHERE EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT IN (SELECT DEPARTMENT FROM approval_approver WHERE APPROVAL_TYPE_ID = :approval_type_id AND EMPLOYEE_ID = :employee_id)) AND STATUS IN ("PEN", "FA")';

                if((!empty($filter_leave_start_date) && !empty($filter_leave_end_date)) || (!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)) || !empty($filter_leave_type) || !empty($filter_work_location) || !empty($filter_department)){
                    $query .= ' AND ';

                    if(!empty($filter_leave_start_date) && !empty($filter_leave_start_date)){
                        $filter[] = 'LEAVE_DATE BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $filter[] = 'DATE(CREATED_DATE) BETWEEN :filter_creation_start_date AND :filter_creation_end_date';
                    }

                    if(!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)){
                        $filter[] = 'DATE(FOR_APPROVAL_DATE) BETWEEN :filter_for_approval_start_date AND :filter_for_approval_end_date';
                    }
                    
                    if(!empty($filter_leave_type)){
                        $filter[] = 'LEAVE_TYPE_ID = :filter_leave_type';
                    }
                    
                    if(!empty($filter_work_location)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE WORK_LOCATION = :filter_work_location)';
                    }

                    if(!empty($filter_department)){
                        $filter[] = 'EMPLOYEE_ID IN (SELECT EMPLOYEE_ID FROM employee_details WHERE DEPARTMENT = :filter_department)';
                    }

                    if(!empty($filter)){
                        $query .= implode(' AND ', $filter);
                    }
                }
    
                $sql = $api->db_connection->prepare($query);
                $sql->bindValue(':approval_type_id', '6');
                $sql->bindValue(':employee_id', $employee_id);

                if((!empty($filter_leave_start_date) && !empty($filter_leave_end_date)) || (!empty($filter_creation_start_date) && !empty($filter_creation_end_date)) || (!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)) || !empty($filter_leave_type) || !empty($filter_work_location) || !empty($filter_department)){
                    if(!empty($filter_leave_start_date) && !empty($filter_leave_start_date)){
                        $sql->bindValue(':filter_leave_start_date', $filter_leave_start_date);
                        $sql->bindValue(':filter_leave_start_date', $filter_leave_start_date);
                    }

                    if(!empty($filter_creation_start_date) && !empty($filter_creation_end_date)){
                        $sql->bindValue(':filter_creation_start_date', $filter_creation_start_date);
                        $sql->bindValue(':filter_creation_end_date', $filter_creation_end_date);
                    }

                    if(!empty($filter_for_approval_start_date) && !empty($filter_for_approval_end_date)){
                        $sql->bindValue(':filter_for_approval_start_date', $filter_for_approval_start_date);
                        $sql->bindValue(':filter_for_approval_end_date', $filter_for_approval_end_date);
                    }

                    if(!empty($filter_leave_type)){
                        $sql->bindValue(':filter_leave_type', $filter_leave_type);
                    }

                    if(!empty($filter_work_location)){
                        $sql->bindValue(':filter_work_location', $filter_work_location);
                    }

                    if(!empty($filter_department)){
                        $sql->bindValue(':filter_department', $filter_department);
                    }
                }
    
                if($sql->execute()){
                    while($row = $sql->fetch()){
                        $leave_id = $row['LEAVE_ID'];
                        $employee_id = $row['EMPLOYEE_ID'];
                        $leave_date = $api->check_date('empty', $row['LEAVE_DATE'], '', 'm/d/Y', '', '', '');
                        $start_time = $api->check_date('empty', $row['START_TIME'], '', 'h:i:s a', '', '', '');
                        $end_time = $api->check_date('empty', $row['END_TIME'], '', 'h:i:s a', '', '', '');
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $status = $row['STATUS'];
                        $total_hours = number_format($row['TOTAL_HOURS'], 2);
                        $transaction_log_id = $row['TRANSACTION_LOG_ID'];

                        $employee_details = $api->get_employee_details($employee_id);
                        $file_as = $employee_details[0]['FILE_AS'] ?? null;

                        $employee_leave_allocation_details = $api->get_employee_leave_allocation_details($employee_id, $leave_type_id, $leave_date);
                        $duration = $employee_leave_allocation_details[0]['DURATION'] ?? 0;
                        $availed = $employee_leave_allocation_details[0]['AVAILED'] ?? 0;
                        $allocation = $duration - $availed;

                        $leave_type_details = $api->get_leave_type_details($leave_type_id);
                        $leave_type = $leave_type_details[0]['LEAVE_TYPE'] ?? null;
                        $allocation_type = $leave_type_details[0]['ALLOCATION_TYPE'] ?? null;

                        $status_name = $api->get_leave_status($status)[0]['BADGE'];

                        if($allocation_type == 'LIMITED'){
                            $duration = $allocation . ' hour(s) remaining out of ' . $duration . ' hour(s)';
                        }
                        else{
                            $duration = 'No Limit';
                        }

                        if($approve_leave > 0){
                            $approve = '<button type="button" class="btn btn-success waves-effect waves-light approve-leave" data-leave-id="'. $leave_id .'" title="Approve Leave">
                                        <i class="bx bx-check font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $approve = '';
                        }

                        if($reject_leave > 0){
                            $reject = '<button type="button" class="btn btn-danger waves-effect waves-light reject-leave" data-leave-id="'. $leave_id .'" title="Reject Leave">
                                        <i class="bx bx-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $reject = '';
                        }

                        if($cancel_leave > 0){
                            $cancel = '<button type="button" class="btn btn-warning waves-effect waves-light cancel-leave" data-leave-id="'. $leave_id .'" title="Cancel Leave">
                                        <i class="bx bx-calendar-x font-size-16 align-middle"></i>
                                    </button>';
                        }
                        else{
                            $cancel = '';
                        }

                        if($view_transaction_log > 0 && !empty($transaction_log_id)){
                            $transaction_log = '<button type="button" class="btn btn-dark waves-effect waves-light view-transaction-log" data-transaction-log-id="'. $transaction_log_id .'" title="View Transaction Log">
                                                    <i class="bx bx-detail font-size-16 align-middle"></i>
                                                </button>';
                        }
                        else{
                            $transaction_log = '';
                        }

                        if(($approval_type_status == 'INACTIVE' && ($status == 'PEN' || $status == 'FA')) || $status == 'FA'){
                            $response[] = array(
                                'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-cancel="1" data-approve="1" data-reject="1" type="checkbox" value="'. $leave_id .'">',
                                'FILE_AS' => $file_as,
                                'LEAVE_TYPE' => $leave_type,
                                'LEAVE_DATE' => $leave_date . ' ' . $start_time .' - '. $end_time . '<p class="text-muted mb-0"> Total Hours: '. $total_hours .'</p>',
                                'DURATION' => $duration,
                                'STATUS' => $status_name,
                                'ACTION' => '<div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary waves-effect waves-light view-leave" data-leave-id="'. $leave_id .'" title="View Leave">
                                        <i class="bx bx-show font-size-16 align-middle"></i>
                                    </button>
                                    '. $approve .'
                                    '. $reject .'
                                    '. $cancel .'
                                    '. $transaction_log .'
                                </div>'
                            );
                        }
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