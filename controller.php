<?php
session_start();
require('config/config.php');
require('classes/api.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = $_POST['transaction'];
    $api = new Api;
    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');

    # Authenticate
    if($transaction == 'authenticate'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
            $username = $_POST['username'];
            $password = $api->encrypt_data($_POST['password']);

            $authenticate = $api->authenticate($username, $password);
            
            if($authenticate === 'Authenticated'){
                $_SESSION['lock'] = 0;
                $_SESSION['logged_in'] = 1;
                $_SESSION['username'] = $username;
            }

            echo $authenticate;
        }
    }
    # -------------------------------------------------------------

    # Change password
    else if($transaction == 'change password'){
        if(isset($_POST['change_username']) && !empty($_POST['change_username']) && isset($_POST['change_password']) && !empty($_POST['change_password'])){
            $username = $_POST['change_username'];
            $password = $api->encrypt_data($_POST['change_password']);
            $password_expiry_date = $api->format_date('Y-m-d', $system_date, '+6 months');

            $check_user_account_exist = $api->check_user_account_exist($username);

            if($check_user_account_exist){
                $update_user_account_password = $api->update_user_account_password($username, $password, $password_expiry_date);

                if($update_user_account_password){
                    $update_login_attempt = $api->update_login_attempt($username, '', 0, NULL);

                    if($update_login_attempt){
                        echo 'Updated';
                    }
                    else{
                        echo $update_login_attempt;
                    }
                }
                else{
                    echo $update_user_account_password;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Submit transactions
    # -------------------------------------------------------------

    # Submit module
    else if($transaction == 'submit module'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id']) && isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['module_description']) && !empty($_POST['module_description']) && isset($_POST['module_category']) && !empty($_POST['module_category']) && isset($_POST['module_version']) && !empty($_POST['module_version'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $module_id = $_POST['module_id'];
            $module_name = $_POST['module_name'];
            $module_description = $_POST['module_description'];
            $module_category = $_POST['module_category'];
            $module_version = $_POST['module_version'];

            $module_icon_name = $_FILES['module_icon']['name'];
            $module_icon_size = $_FILES['module_icon']['size'];
            $module_icon_error = $_FILES['module_icon']['error'];
            $module_icon_tmp_name = $_FILES['module_icon']['tmp_name'];
            $module_icon_ext = explode('.', $module_icon_name);
            $module_icon_actual_ext = strtolower(end($module_icon_ext));

            $upload_setting_details = $api->get_upload_setting_details(1);
            $upload_file_type_details = $api->get_upload_file_type_details(1);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_module_exist = $api->check_module_exist($module_id);
 
            if($check_module_exist > 0){
                if(!empty($module_icon_tmp_name)){
                    if(in_array($module_icon_actual_ext, $allowed_ext)){
                        if(!$module_icon_error){
                            if($module_icon_size < $file_max_size){
                                $update_module_icon = $api->update_module_icon($module_icon_tmp_name, $module_icon_actual_ext, $module_id, $username);
        
                                if($update_module_icon){
                                    $update_module = $api->update_module($module_id, $module_name, $module_version, $module_description, $module_category, $username);

                                    if($update_module){
                                        $response[] = array(
                                            'RESPONSE' => 'Updated',
                                            'MODULE_ID' => null
                                        );
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => $update_module,
                                            'MODULE_ID' => null
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $update_module_icon,
                                        'MODULE_ID' => null
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => 'File Size',
                                    'MODULE_ID' => null
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'There was an error uploading the file.',
                                'MODULE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'File Type',
                            'MODULE_ID' => null
                        );
                    }
                }
                else{
                    $update_module = $api->update_module($module_id, $module_name, $module_version, $module_description, $module_category, $username);

                    if($update_module){
                        $response[] = array(
                            'RESPONSE' => 'Updated',
                            'MODULE_ID' => null
                        );
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_module,
                            'MODULE_ID' => null
                        );
                    }
                }
            }
            else{
                if(!empty($module_icon_tmp_name)){
                    if(in_array($module_icon_actual_ext, $allowed_ext)){
                        if(!$module_icon_error){
                            if($module_icon_size < $file_max_size){
                                $insert_module = $api->insert_module($module_icon_tmp_name, $module_icon_actual_ext, $module_name, $module_version, $module_description, $module_category, $username);
    
                                if($insert_module[0]['RESPONSE']){
                                    $response[] = array(
                                        'RESPONSE' => 'Inserted',
                                        'MODULE_ID' => $insert_module[0]['MODULE_ID']
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $insert_module[0]['RESPONSE'],
                                        'MODULE_ID' => null
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => 'File Size',
                                    'MODULE_ID' => null
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'There was an error uploading the file.',
                                'MODULE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'File Type',
                            'MODULE_ID' => null
                        );
                    }
                }
                else{
                    $insert_module = $api->insert_module(null, null, $module_name, $module_version, $module_description, $module_category, $username);
    
                    if($insert_module[0]['RESPONSE']){
                        $response[] = array(
                            'RESPONSE' => 'Inserted',
                            'MODULE_ID' => $insert_module[0]['MODULE_ID']
                        );
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $insert_module[0]['RESPONSE'],
                            'MODULE_ID' => null
                        );
                    }
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit module access
    else if($transaction == 'submit module access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id']) && !empty($_POST['module_id']) && isset($_POST['role']) && !empty($_POST['role'])){
            $error = '';
            $username = $_POST['username'];
            $module_id = $_POST['module_id'];
            $roles = explode(',', $_POST['role']);

            foreach($roles as $role){
                $check_module_access_exist = $api->check_module_access_exist($module_id, $role);

                if($check_module_access_exist == 0){
                    $insert_module_access = $api->insert_module_access($module_id, $role, $username);

                    if(!$insert_module_access){
                        $error = $insert_module_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit page
    else if($transaction == 'submit page'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['page_id']) && isset($_POST['page_name']) && !empty($_POST['page_name']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
            $response = array();
            $username = $_POST['username'];
            $page_id = $_POST['page_id'];
            $page_name = $_POST['page_name'];
            $module_id = $_POST['module_id'];

            $check_page_exist = $api->check_page_exist($page_id);
 
            if($check_page_exist > 0){
                $update_page = $api->update_page($page_id, $page_name, $module_id, $username);

                if($update_page){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'PAGE_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_page,
                        'PAGE_ID' => null
                    );
                }
            }
            else{
                $insert_page = $api->insert_page($page_name, $module_id, $username);
    
                if($insert_page[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'PAGE_ID' => $insert_page[0]['PAGE_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_page[0]['RESPONSE'],
                        'PAGE_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit page access
    else if($transaction == 'submit page access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['page_id']) && !empty($_POST['page_id']) && isset($_POST['role']) && !empty($_POST['role'])){
            $error = '';
            $username = $_POST['username'];
            $page_id = $_POST['page_id'];
            $roles = explode(',', $_POST['role']);

            foreach($roles as $role){
                $check_page_access_exist = $api->check_page_access_exist($page_id, $role);

                if($check_page_access_exist == 0){
                    $insert_page_access = $api->insert_page_access($page_id, $role, $username);

                    if(!$insert_page_access){
                        $error = $insert_page_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit action
    else if($transaction == 'submit action'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['action_id']) && isset($_POST['action_name']) && !empty($_POST['action_name'])){
            $response = array();
            $username = $_POST['username'];
            $action_id = $_POST['action_id'];
            $action_name = $_POST['action_name'];

            $check_action_exist = $api->check_action_exist($action_id);
 
            if($check_action_exist > 0){
                $update_action = $api->update_action($action_id, $action_name, $username);

                if($update_action){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'ACTION_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_action,
                        'ACTION_ID' => null
                    );
                }
            }
            else{
                $insert_action = $api->insert_action($action_name, $username);
    
                if($insert_action[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'ACTION_ID' => $insert_action[0]['ACTION_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_action[0]['RESPONSE'],
                        'ACTION_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit action access
    else if($transaction == 'submit action access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['action_id']) && !empty($_POST['action_id']) && isset($_POST['role']) && !empty($_POST['role'])){
            $error = '';
            $username = $_POST['username'];
            $action_id = $_POST['action_id'];
            $roles = explode(',', $_POST['role']);

            foreach($roles as $role){
                $check_action_access_exist = $api->check_action_access_exist($action_id, $role);

                if($check_action_access_exist == 0){
                    $insert_action_access = $api->insert_action_access($action_id, $role, $username);

                    if(!$insert_action_access){
                        $error = $insert_action_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit system parameter
    else if($transaction == 'submit system parameter'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['parameter_id']) && isset($_POST['parameter']) && !empty($_POST['parameter']) && isset($_POST['parameter_description']) && !empty($_POST['parameter_description']) && isset($_POST['parameter_extension']) && isset($_POST['parameter_number'])){
            $response = array();
            $username = $_POST['username'];
            $parameter_id = $_POST['parameter_id'];
            $parameter = $_POST['parameter'];
            $parameter_description = $_POST['parameter_description'];
            $parameter_extension = $_POST['parameter_extension'];
            $parameter_number = $_POST['parameter_number'] ?? 0;

            $check_system_parameter_exist = $api->check_system_parameter_exist($parameter_id);
 
            if($check_system_parameter_exist > 0){
                $update_system_parameter = $api->update_system_parameter($parameter_id, $parameter, $parameter_description, $parameter_extension, $parameter_number, $username);

                if($update_system_parameter){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'PARAMETER_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter,
                        'PARAMETER_ID' => null
                    );
                }
            }
            else{
                $insert_system_parameter = $api->insert_system_parameter($parameter, $parameter_description, $parameter_extension, $parameter_number, $username);
    
                if($insert_system_parameter[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'PARAMETER_ID' => $insert_system_parameter[0]['PARAMETER_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_system_parameter[0]['RESPONSE'],
                        'PARAMETER_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit role
    else if($transaction == 'submit role'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && isset($_POST['role']) && !empty($_POST['role']) && isset($_POST['role_description']) && !empty($_POST['role_description']) && isset($_POST['assignable']) && !empty($_POST['assignable'])){
            $response = array();
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $role = $_POST['role'];
            $role_description = $_POST['role_description'];
            $assignable = $_POST['assignable'];

            $check_role_exist = $api->check_role_exist($role_id);
 
            if($check_role_exist > 0){
                $update_role = $api->update_role($role_id, $role, $role_description, $assignable, $username);

                if($update_role){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'ROLE_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_role,
                        'ROLE_ID' => null
                    );
                }
            }
            else{
                $insert_role = $api->insert_role($role, $role_description, $assignable, $username);
    
                if($insert_role[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'ROLE_ID' => $insert_role[0]['ROLE_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_role[0]['RESPONSE'],
                        'ROLE_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit role module access
    else if($transaction == 'submit role module access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
            $error = '';
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $module_ids = explode(',', $_POST['module_id']);

            foreach($module_ids as $module_id){
                $check_module_access_exist = $api->check_module_access_exist($module_id, $role_id);

                if($check_module_access_exist == 0){
                    $insert_module_access = $api->insert_module_access($module_id, $role_id, $username);

                    if(!$insert_module_access){
                        $error = $insert_module_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit role page access
    else if($transaction == 'submit role page access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['page_id']) && !empty($_POST['page_id'])){
            $error = '';
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $page_ids = explode(',', $_POST['page_id']);

            foreach($page_ids as $page_id){
                $check_page_access_exist = $api->check_page_access_exist($page_id, $role_id);

                if($check_page_access_exist == 0){
                    $insert_page_access = $api->insert_page_access($page_id, $role_id, $username);

                    if(!$insert_page_access){
                        $error = $insert_page_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit role action access
    else if($transaction == 'submit role action access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['action_id']) && !empty($_POST['action_id'])){
            $error = '';
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $action_ids = explode(',', $_POST['action_id']);

            foreach($action_ids as $action_id){
                $check_action_access_exist = $api->check_action_access_exist($action_id, $role_id);

                if($check_action_access_exist == 0){
                    $insert_action_access = $api->insert_action_access($action_id, $role_id, $username);

                    if(!$insert_action_access){
                        $error = $insert_action_access;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit role user account
    else if($transaction == 'submit role user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $error = '';
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $user_ids = explode(',', $_POST['user_id']);

            foreach($user_ids as $user_id){
                $check_role_user_account_exist = $api->check_role_user_account_exist($role_id, $user_id);

                if($check_role_user_account_exist == 0){
                    $insert_role_user_account = $api->insert_role_user_account($role_id, $user_id, $username);

                    if(!$insert_role_user_account){
                        $error = $insert_role_user_account;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit system code
    else if($transaction == 'submit system code'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_code_id']) && isset($_POST['system_type']) && !empty($_POST['system_type']) && isset($_POST['system_code']) && !empty($_POST['system_code']) && isset($_POST['system_description']) && !empty($_POST['system_description'])){
            $response = array();
            $username = $_POST['username'];
            $system_code_id = $_POST['system_code_id'];
            $system_type = $_POST['system_type'];
            $system_code = $_POST['system_code'];
            $system_description = $_POST['system_description'];

            $check_system_code_exist = $api->check_system_code_exist($system_code_id);
 
            if($check_system_code_exist > 0){
                $update_system_code = $api->update_system_code($system_code_id, $system_type, $system_code, $system_description, $username);

                if($update_system_code){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'SYSTEM_CODE_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_code,
                        'SYSTEM_CODE_ID' => null
                    );
                }
            }
            else{
                $insert_system_code = $api->insert_system_code($system_type, $system_code, $system_description, $username);
    
                if($insert_system_code[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'SYSTEM_CODE_ID' => $insert_system_code[0]['SYSTEM_CODE_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_system_code[0]['RESPONSE'],
                        'SYSTEM_CODE_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit upload setting
    else if($transaction == 'submit upload setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && isset($_POST['upload_setting']) && !empty($_POST['upload_setting']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['max_file_size']) && !empty($_POST['max_file_size'])){
            $response = array();
            $username = $_POST['username'];
            $upload_setting_id = $_POST['upload_setting_id'];
            $upload_setting = $_POST['upload_setting'];
            $description = $_POST['description'];
            $max_file_size = $_POST['max_file_size'];

            $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);
 
            if($check_upload_setting_exist > 0){
                $update_upload_setting = $api->update_upload_setting($upload_setting_id, $upload_setting, $description, $max_file_size, $username);

                if($update_upload_setting){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'UPLOAD_SETTING_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_upload_setting,
                        'UPLOAD_SETTING_ID' => null
                    );
                }
            }
            else{
                $insert_upload_setting = $api->insert_upload_setting($upload_setting, $description, $max_file_size, $username);
    
                if($insert_upload_setting[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'UPLOAD_SETTING_ID' => $insert_upload_setting[0]['UPLOAD_SETTING_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_upload_setting[0]['RESPONSE'],
                        'UPLOAD_SETTING_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit upload setting file type
    else if($transaction == 'submit upload setting file type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id']) && isset($_POST['file_type']) && !empty($_POST['file_type'])){
            $error = '';
            $username = $_POST['username'];
            $upload_setting_id = $_POST['upload_setting_id'];
            $file_types = explode(',', $_POST['file_type']);

            foreach($file_types as $file_type){
                $check_upload_setting_file_type_exist = $api->check_upload_setting_file_type_exist($upload_setting_id, $file_type);

                if($check_upload_setting_file_type_exist == 0){
                    $insert_upload_setting_file_type = $api->insert_upload_setting_file_type($upload_setting_id, $file_type, $username);

                    if(!$insert_upload_setting_file_type){
                        $error = $insert_upload_setting_file_type;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit company
    else if($transaction == 'submit company'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['company_id']) && isset($_POST['company_name']) && !empty($_POST['company_name']) && isset($_POST['company_address']) && isset($_POST['tax_id']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['telephone']) && isset($_POST['website'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $company_id = $_POST['company_id'];
            $company_name = $_POST['company_name'];
            $company_address = $_POST['company_address'];
            $tax_id = $_POST['tax_id'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $telephone = $_POST['telephone'];
            $website = $_POST['website'];

            $company_logo_name = $_FILES['company_logo']['name'];
            $company_logo_size = $_FILES['company_logo']['size'];
            $company_logo_error = $_FILES['company_logo']['error'];
            $company_logo_tmp_name = $_FILES['company_logo']['tmp_name'];
            $company_logo_ext = explode('.', $company_logo_name);
            $company_logo_actual_ext = strtolower(end($company_logo_ext));

            $upload_setting_details = $api->get_upload_setting_details(2);
            $upload_file_type_details = $api->get_upload_file_type_details(2);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_company_exist = $api->check_company_exist($company_id);
 
            if($check_company_exist > 0){
                if(!empty($company_logo_tmp_name)){
                    if(in_array($company_logo_actual_ext, $allowed_ext)){
                        if(!$company_logo_error){
                            if($company_logo_size < $file_max_size){
                                $update_company_logo = $api->update_company_logo($company_logo_tmp_name, $company_logo_actual_ext, $company_id, $username);
        
                                if($update_company_logo){
                                    $update_company = $api->update_company($company_id, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username);

                                    if($update_company){
                                        $response[] = array(
                                            'RESPONSE' => 'Updated',
                                            'COMPANY_ID' => null
                                        );
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => $update_company,
                                            'COMPANY_ID' => null
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $update_company_logo,
                                        'COMPANY_ID' => null
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => 'File Size',
                                    'COMPANY_ID' => null
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'There was an error uploading the file.',
                                'COMPANY_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'File Type',
                            'COMPANY_ID' => null
                        );
                    }
                }
                else{
                    $update_company = $api->update_company($company_id, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username);

                    if($update_company){
                        $response[] = array(
                            'RESPONSE' => 'Updated',
                            'COMPANY_ID' => null
                        );
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_company,
                            'COMPANY_ID' => null
                        );
                    }
                }
            }
            else{
                if(!empty($company_logo_tmp_name)){
                    if(in_array($company_logo_actual_ext, $allowed_ext)){
                        if(!$company_logo_error){
                            if($company_logo_size < $file_max_size){
                                $insert_company = $api->insert_company($company_logo_tmp_name, $company_logo_actual_ext, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username);
    
                                if($insert_company[0]['RESPONSE']){
                                    $response[] = array(
                                        'RESPONSE' => 'Inserted',
                                        'COMPANY_ID' => $insert_company[0]['COMPANY_ID']
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $insert_company[0]['RESPONSE'],
                                        'COMPANY_ID' => null
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => 'File Size',
                                    'COMPANY_ID' => null
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => 'There was an error uploading the file.',
                                'COMPANY_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => 'File Type',
                            'COMPANY_ID' => null
                        );
                    }
                }
                else{
                    $insert_company = $api->insert_company(null, null, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username);
    
                    if($insert_company[0]['RESPONSE']){
                        $response[] = array(
                            'RESPONSE' => 'Inserted',
                            'COMPANY_ID' => $insert_company[0]['COMPANY_ID']
                        );
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $insert_company[0]['RESPONSE'],
                            'COMPANY_ID' => null
                        );
                    }
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit interface setting
    else if($transaction == 'submit interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['interface_setting_id']) && isset($_POST['interface_setting_name']) && !empty($_POST['interface_setting_name']) && isset($_POST['description']) && !empty($_POST['description']) ){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $interface_setting_id = $_POST['interface_setting_id'];
            $interface_setting_name = $_POST['interface_setting_name'];
            $description = $_POST['description'];

            $check_interface_setting_exist = $api->check_interface_setting_exist($interface_setting_id);
 
            if($check_interface_setting_exist > 0){
                $update_interface_setting = $api->update_interface_setting($interface_setting_id, $interface_setting_name, $description, $username);

                if($update_interface_setting){
                    $login_background = $api->update_interface_settings_upload($_FILES['login_background'], 'login background', $interface_setting_id, $username);

                    if($login_background){
                        $login_logo = $api->update_interface_settings_upload($_FILES['login_logo'], 'login logo', $interface_setting_id, $username);

                        if($login_logo){
                            $menu_logo = $api->update_interface_settings_upload($_FILES['menu_logo'], 'menu logo', $interface_setting_id, $username);

                            if($menu_logo){
                                $favicon = $api->update_interface_settings_upload($_FILES['favicon'], 'favicon', $interface_setting_id, $username);

                                if($menu_logo){
                                    $response[] = array(
                                        'RESPONSE' => 'Updated',
                                        'INTERFACE_SETTING_ID' => null
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $favicon,
                                        'INTERFACE_SETTING_ID' => null
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $menu_logo,
                                    'INTERFACE_SETTING_ID' => null
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $login_logo,
                                'INTERFACE_SETTING_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $login_background,
                            'INTERFACE_SETTING_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_interface_setting,
                        'INTERFACE_SETTING_ID' => null
                    );
                }
            }
            else{
                $insert_interface_setting = $api->insert_interface_setting($_FILES['login_background'], $_FILES['login_logo'], $_FILES['menu_logo'], $_FILES['favicon'], $interface_setting_name, $description, $username);
    
                if($insert_interface_setting[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'INTERFACE_SETTING_ID' => $insert_interface_setting[0]['INTERFACE_SETTING_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_interface_setting[0]['RESPONSE'],
                        'INTERFACE_SETTING_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit email setting
    else if($transaction == 'submit email setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email_setting_id']) && isset($_POST['email_setting_name']) && !empty($_POST['email_setting_name']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['mail_host']) && !empty($_POST['mail_host']) && isset($_POST['port']) && isset($_POST['smtp_auth']) && isset($_POST['smtp_auto_tls']) && isset($_POST['mail_username']) && !empty($_POST['mail_username']) && isset($_POST['mail_password']) && !empty($_POST['mail_password']) && isset($_POST['mail_encryption']) && !empty($_POST['mail_encryption']) && isset($_POST['mail_from_name']) && !empty($_POST['mail_from_name']) && isset($_POST['mail_from_email']) && !empty($_POST['mail_from_email'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $email_setting_id = $_POST['email_setting_id'];
            $email_setting_name = $_POST['email_setting_name'];
            $description = $_POST['description'];
            $mail_host = $_POST['mail_host'];
            $port = $_POST['port'] ?? 0;
            $smtp_auth = $_POST['smtp_auth'];
            $smtp_auto_tls = $_POST['smtp_auto_tls'];
            $mail_username = $_POST['mail_username'];
            $mail_password = $_POST['mail_password'];
            $mail_encryption = $_POST['mail_encryption'];
            $mail_from_name = $_POST['mail_from_name'];
            $mail_from_email = $_POST['mail_from_email'];

            $check_email_setting_exist = $api->check_email_setting_exist($email_setting_id);
 
            if($check_email_setting_exist > 0){
                $update_email_setting = $api->update_email_setting($email_setting_id, $email_setting_name, $description, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_username, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username);

                if($update_email_setting){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'EMAIL_SETTING_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_email_setting,
                        'EMAIL_SETTING_ID' => null
                    );
                }
            }
            else{
                $insert_email_setting = $api->insert_email_setting($email_setting_name, $description, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_username, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username);
    
                if($insert_email_setting[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'EMAIL_SETTING_ID' => $insert_email_setting[0]['EMAIL_SETTING_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_email_setting[0]['RESPONSE'],
                        'EMAIL_SETTING_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit notification setting
    else if($transaction == 'submit notification setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && isset($_POST['notification_setting']) && !empty($_POST['notification_setting']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['notification_title']) && !empty($_POST['notification_title']) && isset($_POST['notification_message']) && !empty($_POST['notification_message']) && isset($_POST['system_link']) && isset($_POST['email_link'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_setting = $_POST['notification_setting'];
            $description = $_POST['description'];
            $notification_title = $_POST['notification_title'];
            $notification_message = $_POST['notification_message'];
            $system_link = $_POST['system_link'];
            $email_link = $_POST['email_link'];

            $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);
 
            if($check_notification_setting_exist > 0){
                $update_notification_setting = $api->update_notification_setting($notification_setting_id, $notification_setting, $description, $notification_title, $notification_message, $system_link, $email_link, $username);

                if($update_notification_setting){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'NOTIFICATION_SETTING_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_notification_setting,
                        'NOTIFICATION_SETTING_ID' => null
                    );
                }
            }
            else{
                $insert_notification_setting = $api->insert_notification_setting($notification_setting, $description, $notification_title, $notification_message, $system_link, $email_link, $username);
    
                if($insert_notification_setting[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'NOTIFICATION_SETTING_ID' => $insert_notification_setting[0]['NOTIFICATION_SETTING_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_notification_setting[0]['RESPONSE'],
                        'NOTIFICATION_SETTING_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit notification role recipient
    else if($transaction == 'submit notification role recipient'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $error = '';
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $role_ids = explode(',', $_POST['role_id']);

            foreach($role_ids as $role_id){
                $check_notification_role_recipient_exist = $api->check_notification_role_recipient_exist($notification_setting_id, $role_id);

                if($check_notification_role_recipient_exist == 0){
                    $insert_notification_role_recipient = $api->insert_notification_role_recipient($notification_setting_id, $role_id, $username);

                    if(!$insert_notification_role_recipient){
                        $error = $insert_notification_role_recipient;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit notification user account recipient
    else if($transaction == 'submit notification user account recipient'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $error = '';
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $user_ids = explode(',', $_POST['user_id']);

            foreach($user_ids as $user_id){
                $check_notification_user_account_recipient_exist = $api->check_notification_user_account_recipient_exist($notification_setting_id, $user_id);

                if($check_notification_user_account_recipient_exist == 0){
                    $insert_notification_user_account_recipient = $api->insert_notification_user_account_recipient($notification_setting_id, $user_id, $username);

                    if(!$insert_notification_user_account_recipient){
                        $error = $insert_notification_user_account_recipient;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit notification channel
    else if($transaction == 'submit notification channel'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['channel']) && !empty($_POST['channel']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $error = '';
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $channels = explode(',', $_POST['channel']);

            foreach($channels as $channel){
                $check_notification_channel_exist = $api->check_notification_channel_exist($notification_setting_id, $channel);

                if($check_notification_channel_exist == 0){
                    $insert_notification_channel = $api->insert_notification_channel($notification_setting_id, $channel, $username);

                    if(!$insert_notification_channel){
                        $error = $insert_notification_channel;
                        break;
                    }
                }
            }

            if(empty($error)){
                echo 'Inserted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Submit country
    else if($transaction == 'submit country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id']) && isset($_POST['country_name']) && !empty($_POST['country_name'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $country_id = $_POST['country_id'];
            $country_name = $_POST['country_name'];

            $check_country_exist = $api->check_country_exist($country_id);
 
            if($check_country_exist > 0){
                $update_country = $api->update_country($country_id, $country_name, $username);

                if($update_country){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'COUNTRY_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_country,
                        'COUNTRY_ID' => null
                    );
                }
            }
            else{
                $insert_country = $api->insert_country($country_name, $username);
    
                if($insert_country[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'COUNTRY_ID' => $insert_country[0]['COUNTRY_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_country[0]['RESPONSE'],
                        'COUNTRY_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit country state
    else if($transaction == 'submit country state'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $state_name = $_POST['state_name'];
            $country_id = $_POST['country_id'];

            $insert_state = $api->insert_state($state_name, $country_id, $username);
    
            if($insert_state[0]['RESPONSE']){
                $response[] = array(
                    'RESPONSE' => 'Inserted',
                    'STATE_ID' => $insert_state[0]['STATE_ID']
                );
            }
            else{
                $response[] = array(
                    'RESPONSE' => $insert_state[0]['RESPONSE'],
                    'STATE_ID' => null
                );
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------
    
    # Submit state
    else if($transaction == 'submit state'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_id']) && isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $state_id = $_POST['state_id'];
            $state_name = $_POST['state_name'];
            $country_id = $_POST['country_id'];

            $check_state_exist = $api->check_state_exist($state_id);
 
            if($check_state_exist > 0){
                $update_state = $api->update_state($state_id, $state_name, $country_id, $username);

                if($update_state){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'STATE_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_state,
                        'STATE_ID' => null
                    );
                }
            }
            else{
                $insert_state = $api->insert_state($state_name, $country_id, $username);
    
                if($insert_state[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'STATE_ID' => $insert_state[0]['STATE_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_state[0]['RESPONSE'],
                        'STATE_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Submit zoom api
    else if($transaction == 'submit zoom api'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['zoom_api_id']) && isset($_POST['zoom_api_name']) && !empty($_POST['zoom_api_name']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['api_key']) && !empty($_POST['api_key']) && isset($_POST['api_secret']) && !empty($_POST['api_secret'])){
            $response = array();
            $file_type = '';
            $username = $_POST['username'];
            $zoom_api_id = $_POST['zoom_api_id'];
            $zoom_api_name = $_POST['zoom_api_name'];
            $description = $_POST['description'];
            $api_key = $_POST['api_key'];
            $api_secret = $_POST['api_secret'];

            $check_zoom_api_exist = $api->check_zoom_api_exist($zoom_api_id);
 
            if($check_zoom_api_exist > 0){
                $update_zoom_api = $api->update_zoom_api($zoom_api_id, $zoom_api_name, $description, $api_key, $api_secret, $username);

                if($update_zoom_api){
                    $response[] = array(
                        'RESPONSE' => 'Updated',
                        'ZOOM_API_ID' => null
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_zoom_api,
                        'ZOOM_API_ID' => null
                    );
                }
            }
            else{
                $insert_zoom_api = $api->insert_zoom_api($zoom_api_name, $description, $api_key, $api_secret, $username);
    
                if($insert_zoom_api[0]['RESPONSE']){
                    $response[] = array(
                        'RESPONSE' => 'Inserted',
                        'ZOOM_API_ID' => $insert_zoom_api[0]['ZOOM_API_ID']
                    );
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $insert_zoom_api[0]['RESPONSE'],
                        'ZOOM_API_ID' => null
                    );
                }
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete transactions
    # -------------------------------------------------------------

    # Delete module
    else if($transaction == 'delete module'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
            $username = $_POST['username'];
            $module_id = $_POST['module_id'];

            $check_module_exist = $api->check_module_exist($module_id);

            if($check_module_exist > 0){
                $delete_module = $api->delete_module($module_id, $username);
                                    
                if($delete_module){
                    $delete_all_module_access = $api->delete_all_module_access($module_id, $username);

                    if($delete_all_module_access){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_module_access;
                    }
                }
                else{
                    echo $delete_module;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple module
    else if($transaction == 'delete multiple module'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
            $username = $_POST['username'];
            $module_ids = $_POST['module_id'];

            foreach($module_ids as $module_id){
                $check_module_exist = $api->check_module_exist($module_id);

                if($check_module_exist > 0){
                    $delete_module = $api->delete_module($module_id, $username);
                                    
                    if($delete_module){
                        $delete_all_module_access = $api->delete_all_module_access($module_id, $username);

                        if(!$delete_all_module_access){
                            $error = $delete_all_module_access;
                            break;
                        }
                    }
                    else{
                        $error = $delete_module;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete module access
    else if($transaction == 'delete module access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id']) && !empty($_POST['module_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $module_id = $_POST['module_id'];
            $role_id = $_POST['role_id'];

            $check_module_access_exist = $api->check_module_access_exist($module_id, $role_id);

            if($check_module_access_exist > 0){
                $delete_module_access = $api->delete_module_access($module_id, $role_id, $username);
                                    
                if($delete_module_access){
                    echo 'Deleted';
                }
                else{
                    echo $delete_module_access;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete page
    else if($transaction == 'delete page'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['page_id']) && !empty($_POST['page_id'])){
            $username = $_POST['username'];
            $page_id = $_POST['page_id'];

            $check_page_exist = $api->check_page_exist($page_id);

            if($check_page_exist > 0){
                $delete_page = $api->delete_page($page_id, $username);
                                    
                if($delete_page){
                    $delete_all_page_access = $api->delete_all_page_access($page_id, $username);

                    if($delete_all_page_access){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_page_access;
                    }
                }
                else{
                    echo $delete_page;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple page
    else if($transaction == 'delete multiple page'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['page_id']) && !empty($_POST['page_id'])){
            $username = $_POST['username'];
            $page_ids = $_POST['page_id'];

            foreach($page_ids as $page_id){
                $check_page_exist = $api->check_page_exist($page_id);

                if($check_page_exist > 0){
                    $delete_page = $api->delete_page($page_id, $username);
                                    
                    if($delete_page){
                        $delete_all_page_access = $api->delete_all_page_access($page_id, $username);

                        if(!$delete_all_page_access){
                            $error = $delete_all_page_access;
                            break;
                        }
                    }
                    else{
                        $error = $delete_page;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete page access
    else if($transaction == 'delete page access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['page_id']) && !empty($_POST['page_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $page_id = $_POST['page_id'];
            $role_id = $_POST['role_id'];

            $check_page_access_exist = $api->check_page_access_exist($page_id, $role_id);

            if($check_page_access_exist > 0){
                $delete_page_access = $api->delete_page_access($page_id, $role_id, $username);
                                    
                if($delete_page_access){
                    echo 'Deleted';
                }
                else{
                    echo $delete_page_access;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete action
    else if($transaction == 'delete action'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['action_id']) && !empty($_POST['action_id'])){
            $username = $_POST['username'];
            $action_id = $_POST['action_id'];

            $check_action_exist = $api->check_action_exist($action_id);

            if($check_action_exist > 0){
                $delete_action = $api->delete_action($action_id, $username);
                                    
                if($delete_action){
                    $delete_all_action_access = $api->delete_all_action_access($action_id, $username);

                    if($delete_all_action_access){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_action_access;
                    }
                }
                else{
                    echo $delete_action;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple action
    else if($transaction == 'delete multiple action'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['action_id']) && !empty($_POST['action_id'])){
            $username = $_POST['username'];
            $action_ids = $_POST['action_id'];

            foreach($action_ids as $action_id){
                $check_action_exist = $api->check_action_exist($action_id);

                if($check_action_exist > 0){
                    $delete_action = $api->delete_action($action_id, $username);
                                    
                    if($delete_action){
                        $delete_all_action_access = $api->delete_all_action_access($action_id, $username);

                        if(!$delete_all_action_access){
                            $error = $delete_all_action_access;
                            break;
                        }
                    }
                    else{
                        $error = $delete_action;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete action access
    else if($transaction == 'delete action access'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['action_id']) && !empty($_POST['action_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $action_id = $_POST['action_id'];
            $role_id = $_POST['role_id'];

            $check_action_access_exist = $api->check_action_access_exist($action_id, $role_id);

            if($check_action_access_exist > 0){
                $delete_action_access = $api->delete_action_access($action_id, $role_id, $username);
                                    
                if($delete_action_access){
                    echo 'Deleted';
                }
                else{
                    echo $delete_action_access;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete system parameter
    else if($transaction == 'delete system parameter'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['parameter_id']) && !empty($_POST['parameter_id'])){
            $username = $_POST['username'];
            $parameter_id = $_POST['parameter_id'];

            $check_system_parameter_exist = $api->check_system_parameter_exist($parameter_id);

            if($check_system_parameter_exist > 0){
                $delete_system_parameter = $api->delete_system_parameter($parameter_id, $username);
                                    
                if($delete_system_parameter){
                    echo 'Deleted';
                }
                else{
                    echo $delete_system_parameter;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple system parameter
    else if($transaction == 'delete multiple system parameter'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['parameter_id']) && !empty($_POST['parameter_id'])){
            $username = $_POST['username'];
            $parameter_ids = $_POST['parameter_id'];

            foreach($parameter_ids as $parameter_id){
                $check_system_parameter_exist = $api->check_system_parameter_exist($parameter_id);

                if($check_system_parameter_exist > 0){
                    $delete_system_parameter = $api->delete_system_parameter($parameter_id, $username);
                                    
                    if(!$delete_system_parameter){
                        $error = $delete_system_parameter;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete role
    else if($transaction == 'delete role'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];

            $check_role_exist = $api->check_role_exist($role_id);

            if($check_role_exist > 0){
                $delete_role = $api->delete_role($role_id, $username);
                                    
                if($delete_role){
                    $delete_role_module_access = $api->delete_role_module_access($role_id, $username);
                                    
                    if($delete_role_module_access){
                        $delete_role_page_access = $api->delete_role_page_access($role_id, $username);
                                    
                        if($delete_role_page_access){
                            $delete_role_action_access = $api->delete_role_action_access($role_id, $username);
                                    
                            if($delete_role_action_access){
                                $delete_all_role_user_account = $api->delete_all_role_user_account($role_id, $username);
                                    
                                if($delete_all_role_user_account){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_all_role_user_account;
                                }
                            }
                            else{
                                echo $delete_role_action_access;
                            }
                        }
                        else{
                            echo $delete_role_page_access;
                        }
                    }
                    else{
                        echo $delete_role_module_access;
                    }
                }
                else{
                    echo $delete_role;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple role
    else if($transaction == 'delete multiple role'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $role_ids = $_POST['role_id'];

            foreach($role_ids as $role_id){
                $check_role_exist = $api->check_role_exist($role_id);

                if($check_role_exist > 0){
                    $delete_role = $api->delete_role($role_id, $username);

                    if($delete_role){
                        $delete_role_module_access = $api->delete_role_module_access($role_id, $username);
                                        
                        if($delete_role_module_access){
                            $delete_role_page_access = $api->delete_role_page_access($role_id, $username);
                                        
                            if($delete_role_page_access){
                                $delete_role_action_access = $api->delete_role_action_access($role_id, $username);
                                        
                                if($delete_role_action_access){
                                    $delete_role_user_account = $api->delete_role_user_account($role_id, $username);
                                        
                                    if(!$delete_role_user_account){
                                        $error = $delete_role_user_account;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_role_action_access;
                                    break;
                                }
                            }
                            else{
                                $error = $delete_role_page_access;
                                break;
                            }
                        }
                        else{
                            $error = $delete_role_module_access;
                            break;
                        }
                    }
                    else{
                        $error = $delete_role;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete role user account
    else if($transaction == 'delete role user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $user_id = $_POST['user_id'];
            $role_id = $_POST['role_id'];

            $check_role_user_account_exist = $api->check_role_user_account_exist($role_id, $user_id);

            if($check_role_user_account_exist > 0){
                $delete_role_user_account = $api->delete_role_user_account($role_id, $user_id, $username);

                if($delete_role_user_account){
                    echo 'Deleted';
                }
                else{
                    echo $delete_role_user_account;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete system code
    else if($transaction == 'delete system code'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_code_id']) && !empty($_POST['system_code_id'])){
            $username = $_POST['username'];
            $system_code_id = $_POST['system_code_id'];

            $check_system_code_exist = $api->check_system_code_exist($system_code_id);

            if($check_system_code_exist > 0){
                $delete_system_code = $api->delete_system_code($system_code_id, $username);
                                    
                if($delete_system_code){
                    echo 'Deleted';
                }
                else{
                    echo $delete_system_code;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple system code
    else if($transaction == 'delete multiple system code'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_code_id']) && !empty($_POST['system_code_id'])){
            $username = $_POST['username'];
            $system_code_ids = $_POST['system_code_id'];

            foreach($system_code_ids as $system_code_id){
                $check_system_code_exist = $api->check_system_code_exist($system_code_id);

                if($check_system_code_exist > 0){
                    $delete_system_code = $api->delete_system_code($system_code_id, $username);
                                    
                    if(!$delete_system_code){
                        $error = $delete_system_code;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete upload setting
    else if($transaction == 'delete upload setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            $username = $_POST['username'];
            $upload_setting_id = $_POST['upload_setting_id'];

            $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);

            if($check_upload_setting_exist > 0){
                $delete_upload_setting = $api->delete_upload_setting($upload_setting_id, $username);
                                    
                if($delete_upload_setting){
                    $delete_all_upload_setting_file_type = $api->delete_all_upload_setting_file_type($upload_setting_id, $username);
                                    
                    if($delete_all_upload_setting_file_type){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_upload_setting_file_type;
                    }
                }
                else{
                    echo $delete_upload_setting;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple upload setting
    else if($transaction == 'delete multiple upload setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            $username = $_POST['username'];
            $upload_setting_ids = $_POST['upload_setting_id'];

            foreach($upload_setting_ids as $upload_setting_id){
                $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);

                if($check_upload_setting_exist > 0){
                    $delete_upload_setting = $api->delete_upload_setting($upload_setting_id, $username);
                                    
                    if($delete_upload_setting){
                        $delete_all_upload_setting_file_type = $api->delete_all_upload_setting_file_type($upload_setting_id, $username);
                                    
                        if(!$delete_all_upload_setting_file_type){
                            $error = $delete_all_upload_setting_file_type;
                            break;
                        }
                    }
                    else{
                        $error = $delete_upload_setting;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete upload setting file type
    else if($transaction == 'delete upload setting file type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id']) && isset($_POST['file_type']) && !empty($_POST['file_type'])){
            $username = $_POST['username'];
            $upload_setting_id = $_POST['upload_setting_id'];
            $file_type = $_POST['file_type'];

            $check_upload_setting_file_type_exist = $api->check_upload_setting_file_type_exist($upload_setting_id, $file_type);

            if($check_upload_setting_file_type_exist > 0){
                $delete_upload_setting_file_type = $api->delete_upload_setting_file_type($upload_setting_id, $file_type, $username);

                if($delete_upload_setting_file_type){
                    echo 'Deleted';
                }
                else{
                    echo $delete_upload_setting_file_type;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete company
    else if($transaction == 'delete company'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['company_id']) && !empty($_POST['company_id'])){
            $username = $_POST['username'];
            $company_id = $_POST['company_id'];

            $check_company_exist = $api->check_company_exist($company_id);

            if($check_company_exist > 0){
                $delete_company = $api->delete_company($company_id, $username);
                                    
                if($delete_company){
                    echo 'Deleted';
                }
                else{
                    echo $delete_company;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple company
    else if($transaction == 'delete multiple company'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['company_id']) && !empty($_POST['company_id'])){
            $username = $_POST['username'];
            $company_ids = $_POST['company_id'];

            foreach($company_ids as $company_id){
                $check_company_exist = $api->check_company_exist($company_id);

                if($check_company_exist > 0){
                    $delete_company = $api->delete_company($company_id, $username);
                                    
                    if(!$delete_company){
                        $error = $delete_company;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete interface setting
    else if($transaction == 'delete interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
            $username = $_POST['username'];
            $interface_setting_id = $_POST['interface_setting_id'];

            $check_interface_setting_exist = $api->check_interface_setting_exist($interface_setting_id);

            if($check_interface_setting_exist > 0){
                $delete_interface_setting = $api->delete_interface_setting($interface_setting_id, $username);
                                    
                if($delete_interface_setting){
                    echo 'Deleted';
                }
                else{
                    echo $delete_interface_setting;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple interface setting
    else if($transaction == 'delete multiple interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
            $username = $_POST['username'];
            $interface_setting_ids = $_POST['interface_setting_id'];

            foreach($interface_setting_ids as $interface_setting_id){
                $check_interface_setting_exist = $api->check_interface_setting_exist($interface_setting_id);

                if($check_interface_setting_exist > 0){
                    $delete_interface_setting = $api->delete_interface_setting($interface_setting_id, $username);
                                    
                    if(!$delete_interface_setting){
                        $error = $delete_interface_setting;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete email setting
    else if($transaction == 'delete email setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
            $username = $_POST['username'];
            $email_setting_id = $_POST['email_setting_id'];

            $check_email_setting_exist = $api->check_email_setting_exist($email_setting_id);

            if($check_email_setting_exist > 0){
                $delete_email_setting = $api->delete_email_setting($email_setting_id, $username);
                                    
                if($delete_email_setting){
                    echo 'Deleted';
                }
                else{
                    echo $delete_email_setting;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple email setting
    else if($transaction == 'delete multiple email setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
            $username = $_POST['username'];
            $email_setting_ids = $_POST['email_setting_id'];

            foreach($email_setting_ids as $email_setting_id){
                $check_email_setting_exist = $api->check_email_setting_exist($email_setting_id);

                if($check_email_setting_exist > 0){
                    $delete_email_setting = $api->delete_email_setting($email_setting_id, $username);
                                    
                    if(!$delete_email_setting){
                        $error = $delete_email_setting;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete notification setting
    else if($transaction == 'delete notification setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];

            $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);

            if($check_notification_setting_exist > 0){
                $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                    
                if($delete_notification_setting){
                    $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                    
                    if($delete_all_notification_role_recipient){
                        $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                    
                        if($delete_all_notification_user_account_recipient){
                            $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                    
                            if($delete_all_notification_channel){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_all_notification_channel;
                            }
                        }
                        else{
                            echo $delete_all_notification_user_account_recipient;
                        }
                    }
                    else{
                        echo $delete_all_notification_role_recipient;
                    }
                }
                else{
                    echo $delete_notification_setting;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple notification setting
    else if($transaction == 'delete multiple notification setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $username = $_POST['username'];
            $notification_setting_ids = $_POST['notification_setting_id'];

            foreach($notification_setting_ids as $notification_setting_id){
                $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);

                if($check_notification_setting_exist > 0){
                    $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                    
                    if($delete_notification_setting){
                        $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                        
                        if($delete_all_notification_role_recipient){
                            $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                        
                            if($delete_all_notification_user_account_recipient){
                                $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                        
                                if(!$delete_all_notification_channel){
                                    $error = $delete_all_notification_channel;
                                    break;
                                }
                            }
                            else{
                                $error = $delete_all_notification_user_account_recipient;
                                break;
                            }
                        }
                        else{
                            $error = $delete_all_notification_role_recipient;
                            break;
                        }
                    }
                    else{
                        $error = $delete_notification_setting;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete notification role recipient
    else if($transaction == 'delete notification role recipient'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $role_id = $_POST['role_id'];

            $check_notification_role_recipient_exist = $api->check_notification_role_recipient_exist($notification_setting_id, $role_id);

            if($check_notification_role_recipient_exist > 0){
                $delete_notification_role_recipient = $api->delete_notification_role_recipient($notification_setting_id, $role_id, $username);
                                    
                if($delete_notification_role_recipient){
                    echo 'Deleted';
                }
                else{
                    echo $delete_notification_role_recipient;
                }
            }
            else{
                echo 'Not Found';
            }
        }
        else{
            echo $_POST['notification_setting_id'];
        }
    }
    # -------------------------------------------------------------

    # Delete notification user account recipient
    else if($transaction == 'delete notification user account recipient'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['user_id']) && !empty($_POST['notification_setting_id'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $user_id = $_POST['user_id'];

            $check_notification_user_account_recipient_exist = $api->check_notification_user_account_recipient_exist($notification_setting_id, $user_id);

            if($check_notification_user_account_recipient_exist > 0){
                $delete_notification_user_account_recipient = $api->delete_notification_user_account_recipient($notification_setting_id, $user_id, $username);
                                    
                if($delete_notification_user_account_recipient){
                    echo 'Deleted';
                }
                else{
                    echo $delete_notification_user_account_recipient;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete notification channel
    else if($transaction == 'delete notification channel'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['channel']) && !empty($_POST['channel'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $channel = $_POST['channel'];

            $check_notification_channel_exist = $api->check_notification_channel_exist($notification_setting_id, $channel);

            if($check_notification_channel_exist > 0){
                $delete_notification_channel = $api->delete_notification_channel($notification_setting_id, $channel, $username);
                                    
                if($delete_notification_channel){
                    echo 'Deleted';
                }
                else{
                    echo $delete_notification_channel;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete country
    else if($transaction == 'delete country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $username = $_POST['username'];
            $country_id = $_POST['country_id'];

            $check_country_exist = $api->check_country_exist($country_id);

            if($check_country_exist > 0){
                $delete_country = $api->delete_country($country_id, $username);
                                    
                if($delete_country){
                    $delete_all_state = $api->delete_all_state($country_id, $username);
                                    
                    if($delete_all_state){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_state;
                    }
                }
                else{
                    echo $delete_country;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple country
    else if($transaction == 'delete multiple country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $username = $_POST['username'];
            $country_ids = $_POST['country_id'];

            foreach($country_ids as $country_id){
                $check_country_exist = $api->check_country_exist($country_id);

                if($check_country_exist > 0){
                    $delete_country = $api->delete_country($country_id, $username);
                                    
                    if($delete_country){
                        $delete_all_state = $api->delete_all_state($country_id, $username);
                                    
                        if(!$delete_all_state){
                            $error = $delete_all_state;
                            break;
                        }
                    }
                    else{
                        $error = $delete_country;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete state
    else if($transaction == 'delete state'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_id']) && !empty($_POST['state_id'])){
            $username = $_POST['username'];
            $state_id = $_POST['state_id'];

            $check_state_exist = $api->check_state_exist($state_id);

            if($check_state_exist > 0){
                $delete_state = $api->delete_state($state_id, $username);
                                    
                if($delete_state){
                    echo 'Deleted';
                }
                else{
                    echo $delete_state;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple state
    else if($transaction == 'delete multiple state'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_id']) && !empty($_POST['state_id'])){
            $username = $_POST['username'];
            $state_ids = $_POST['state_id'];

            foreach($state_ids as $state_id){
                $check_state_exist = $api->check_state_exist($state_id);

                if($check_state_exist > 0){
                    $delete_state = $api->delete_state($state_id, $username);
                                    
                    if(!$delete_state){
                        $error = $delete_state;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Delete zoom api
    else if($transaction == 'delete zoom api'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
            $username = $_POST['username'];
            $zoom_api_id = $_POST['zoom_api_id'];

            $check_zoom_api_exist = $api->check_zoom_api_exist($zoom_api_id);

            if($check_zoom_api_exist > 0){
                $delete_zoom_api = $api->delete_zoom_api($zoom_api_id, $username);
                                    
                if($delete_zoom_api){
                    echo 'Deleted';
                }
                else{
                    echo $delete_zoom_api;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple zoom api
    else if($transaction == 'delete multiple zoom api'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
            $username = $_POST['username'];
            $zoom_api_ids = $_POST['zoom_api_id'];

            foreach($zoom_api_ids as $zoom_api_id){
                $check_zoom_api_exist = $api->check_zoom_api_exist($zoom_api_id);

                if($check_zoom_api_exist > 0){
                    $delete_zoom_api = $api->delete_zoom_api($zoom_api_id, $username);
                                    
                    if(!$delete_zoom_api){
                        $error = $delete_zoom_api;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Deleted';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unlock transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Lock transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Activate transactions
    # -------------------------------------------------------------

    # Activate interface setting
    else if($transaction == 'activate interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
            $username = $_POST['username'];
            $interface_setting_id = $_POST['interface_setting_id'];

            $check_interface_setting_exist = $api->check_interface_setting_exist($interface_setting_id);

            if($check_interface_setting_exist > 0){
                $update_interface_setting_status = $api->update_interface_setting_status($interface_setting_id, 1, $username);
                                    
                if($update_interface_setting_status){
                    $update_other_interface_setting_status = $api->update_other_interface_setting_status($interface_setting_id, $username);
                                    
                    if($update_interface_setting_status){
                        echo 'Activated';
                    }
                    else{
                        echo $update_interface_setting_status;
                    }
                }
                else{
                    echo $update_interface_setting_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Activate email setting
    else if($transaction == 'activate email setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
            $username = $_POST['username'];
            $email_setting_id = $_POST['email_setting_id'];

            $check_email_setting_exist = $api->check_email_setting_exist($email_setting_id);

            if($check_email_setting_exist > 0){
                $update_email_setting_status = $api->update_email_setting_status($email_setting_id, 1, $username);
                                    
                if($update_email_setting_status){
                    $update_other_email_setting_status = $api->update_other_email_setting_status($email_setting_id, $username);
                                    
                    if($update_email_setting_status){
                        echo 'Activated';
                    }
                    else{
                        echo $update_email_setting_status;
                    }
                }
                else{
                    echo $update_email_setting_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Activate zoom api
    else if($transaction == 'activate zoom api'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
            $username = $_POST['username'];
            $zoom_api_id = $_POST['zoom_api_id'];

            $check_zoom_api_exist = $api->check_zoom_api_exist($zoom_api_id);

            if($check_zoom_api_exist > 0){
                $update_zoom_api_status = $api->update_zoom_api_status($zoom_api_id, 1, $username);
                                    
                if($update_zoom_api_status){
                    $update_other_zoom_api_status = $api->update_other_zoom_api_status($zoom_api_id, $username);
                                    
                    if($update_zoom_api_status){
                        echo 'Activated';
                    }
                    else{
                        echo $update_zoom_api_status;
                    }
                }
                else{
                    echo $update_zoom_api_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------
     
    # -------------------------------------------------------------
    #   Deactivate transactions
    # -------------------------------------------------------------

    # Deactivate interface setting
    else if($transaction == 'deactivate interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
            $username = $_POST['username'];
            $interface_setting_id = $_POST['interface_setting_id'];

            $check_interface_setting_exist = $api->check_interface_setting_exist($interface_setting_id);

            if($check_interface_setting_exist > 0){
                $update_interface_setting_status = $api->update_interface_setting_status($interface_setting_id, 2, $username);
                                    
                if($update_interface_setting_status){
                    echo 'Deactivated';
                }
                else{
                    echo $update_interface_setting_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Deactivate email setting
    else if($transaction == 'deactivate email setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
            $username = $_POST['username'];
            $email_setting_id = $_POST['email_setting_id'];

            $check_email_setting_exist = $api->check_email_setting_exist($email_setting_id);

            if($check_email_setting_exist > 0){
                $update_email_setting_status = $api->update_email_setting_status($email_setting_id, 2, $username);
                                    
                if($update_email_setting_status){
                    echo 'Deactivated';
                }
                else{
                    echo $update_email_setting_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Deactivate zoom api
    else if($transaction == 'deactivate zoom api'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
            $username = $_POST['username'];
            $zoom_api_id = $_POST['zoom_api_id'];

            $check_zoom_api_exist = $api->check_zoom_api_exist($zoom_api_id);

            if($check_zoom_api_exist > 0){
                $update_zoom_api_status = $api->update_zoom_api_status($zoom_api_id, 2, $username);
                                    
                if($update_zoom_api_status){
                    echo 'Deactivated';
                }
                else{
                    echo $update_zoom_api_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Archive transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unarchive transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Cancel transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   For approval transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   For recommendation transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Reject transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Recommend transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Pending transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Tag for approval transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Approve transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Notification transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details transactions
    # -------------------------------------------------------------

    # Module details
    else if($transaction == 'module details'){
        if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
            $module_id = $_POST['module_id'];
            $module_details = $api->get_module_details($module_id);

            $response[] = array(
                'MODULE_NAME' => $module_details[0]['MODULE_NAME'],
                'MODULE_VERSION' => $module_details[0]['MODULE_VERSION'],
                'MODULE_DESCRIPTION' => $module_details[0]['MODULE_DESCRIPTION'],
                'MODULE_CATEGORY' => $module_details[0]['MODULE_CATEGORY'],
                'TRANSACTION_LOG_ID' => $module_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Page details
    else if($transaction == 'page details'){
        if(isset($_POST['page_id']) && !empty($_POST['page_id'])){
            $page_id = $_POST['page_id'];
            $page_details = $api->get_page_details($page_id);

            $response[] = array(
                'PAGE_NAME' => $page_details[0]['PAGE_NAME'],
                'MODULE_ID' => $page_details[0]['MODULE_ID'],
                'TRANSACTION_LOG_ID' => $page_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Action details
    else if($transaction == 'action details'){
        if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
            $action_id = $_POST['action_id'];
            $action_details = $api->get_action_details($action_id);

            $response[] = array(
                'ACTION_NAME' => $action_details[0]['ACTION_NAME'],
                'TRANSACTION_LOG_ID' => $action_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # System parameter details
    else if($transaction == 'system parameter details'){
        if(isset($_POST['parameter_id']) && !empty($_POST['parameter_id'])){
            $parameter_id = $_POST['parameter_id'];
            $system_parameter_details = $api->get_system_parameter_details($parameter_id);

            $response[] = array(
                'PARAMETER' => $system_parameter_details[0]['PARAMETER'],
                'PARAMETER_DESCRIPTION' => $system_parameter_details[0]['PARAMETER_DESCRIPTION'],
                'PARAMETER_EXTENSION' => $system_parameter_details[0]['PARAMETER_EXTENSION'],
                'PARAMETER_NUMBER' => $system_parameter_details[0]['PARAMETER_NUMBER'],
                'TRANSACTION_LOG_ID' => $system_parameter_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Role details
    else if($transaction == 'role details'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $role_id = $_POST['role_id'];
            $role_details = $api->get_role_details($role_id);

            $response[] = array(
                'ROLE' => $role_details[0]['ROLE'],
                'ROLE_DESCRIPTION' => $role_details[0]['ROLE_DESCRIPTION'],
                'ASSIGNABLE' => $role_details[0]['ASSIGNABLE'],
                'TRANSACTION_LOG_ID' => $role_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # System code details
    else if($transaction == 'system code details'){
        if(isset($_POST['system_code_id']) && !empty($_POST['system_code_id'])){
            $system_code_id = $_POST['system_code_id'];
            $system_code_details = $api->get_system_code_details($system_code_id, null, null);

            $response[] = array(
                'SYSTEM_TYPE' => $system_code_details[0]['SYSTEM_TYPE'],
                'SYSTEM_CODE' => $system_code_details[0]['SYSTEM_CODE'],
                'SYSTEM_DESCRIPTION' => $system_code_details[0]['SYSTEM_DESCRIPTION'],
                'TRANSACTION_LOG_ID' => $system_code_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Upload setting details
    else if($transaction == 'upload setting details'){
        if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            $upload_setting_id = $_POST['upload_setting_id'];
            $upload_setting_details = $api->get_upload_setting_details($upload_setting_id);

            $response[] = array(
                'UPLOAD_SETTING' => $upload_setting_details[0]['UPLOAD_SETTING'],
                'DESCRIPTION' => $upload_setting_details[0]['DESCRIPTION'],
                'MAX_FILE_SIZE' => $upload_setting_details[0]['MAX_FILE_SIZE'],
                'TRANSACTION_LOG_ID' => $upload_setting_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Company details
    else if($transaction == 'company details'){
        if(isset($_POST['company_id']) && !empty($_POST['company_id'])){
            $company_id = $_POST['company_id'];
            $company_details = $api->get_company_details($company_id);
            $company_logo_file_path = $company_details[0]['COMPANY_LOGO'] ?? null;

            if(empty($company_logo_file_path)){
                $company_logo_file_path = $api->check_image($company_logo_file_path, 'company logo');
            }

            $response[] = array(
                'COMPANY_NAME' => $company_details[0]['COMPANY_NAME'],
                'COMPANY_LOGO' => '<img class="img-thumbnail" alt="company logo" width="200" src="'. $company_logo_file_path .'" data-holder-rendered="true">',
                'COMPANY_ADDRESS' => $company_details[0]['COMPANY_ADDRESS'],
                'EMAIL' => $company_details[0]['EMAIL'],
                'TELEPHONE' => $company_details[0]['TELEPHONE'],
                'MOBILE' => $company_details[0]['MOBILE'],
                'WEBSITE' => $company_details[0]['WEBSITE'],
                'TAX_ID' => $company_details[0]['TAX_ID'],
                'TRANSACTION_LOG_ID' => $company_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Interface setting details
    else if($transaction == 'interface setting details'){
        if(isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
            $interface_setting_id = $_POST['interface_setting_id'];
            $interface_setting_details = $api->get_interface_setting_details($interface_setting_id);
            $login_background_file_path = $interface_setting_details[0]['LOGIN_BACKGROUND'] ?? null;
            $login_logo_file_path = $interface_setting_details[0]['LOGIN_LOGO'] ?? null;
            $menu_logo_file_path = $interface_setting_details[0]['MENU_LOGO'] ?? null;
            $favicon_file_path = $interface_setting_details[0]['FAVICON'] ?? null;

            if(empty($login_background_file_path)){
                $login_background_file_path = $api->check_image($login_background_file_path, 'login background');
            }

            if(empty($login_logo_file_path)){
                $login_logo_file_path = $api->check_image($login_logo_file_path, 'login logo');
            }

            if(empty($menu_logo_file_path)){
                $menu_logo_file_path = $api->check_image($menu_logo_file_path, 'menu logo');
            }

            if(empty($favicon_file_path)){
                $favicon_file_path = $api->check_image($favicon_file_path, 'favicon');
            }

            $response[] = array(
                'INTERFACE_SETTING_NAME' => $interface_setting_details[0]['INTERFACE_SETTING_NAME'],
                'DESCRIPTION' => $interface_setting_details[0]['DESCRIPTION'],
                'STATUS' => $api->get_email_setting_status($interface_setting_details[0]['STATUS'])[0]['BADGE'],
                'TRANSACTION_LOG_ID' => $interface_setting_details[0]['TRANSACTION_LOG_ID'],
                'LOGIN_BACKGROUND' => '<img class="img-thumbnail" alt="login background" width="200" src="'. $login_background_file_path .'" data-holder-rendered="true">',
                'LOGIN_LOGO' => '<img class="img-thumbnail" alt="login logo" width="200" src="'. $login_logo_file_path .'" data-holder-rendered="true">',
                'MENU_LOGO' => '<img class="img-thumbnail" alt="menu logo" width="200" src="'. $menu_logo_file_path .'" data-holder-rendered="true">',
                'FAVICON' => '<img class="img-thumbnail" alt="favicon" width="200" src="'. $favicon_file_path .'" data-holder-rendered="true">',
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Email setting details
    else if($transaction == 'email setting details'){
        if(isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
            $email_setting_id = $_POST['email_setting_id'];
            $email_setting_details = $api->get_email_setting_details($email_setting_id);

            $response[] = array(
                'EMAIL_SETTING_NAME' => $email_setting_details[0]['EMAIL_SETTING_NAME'],
                'DESCRIPTION' => $email_setting_details[0]['DESCRIPTION'],
                'STATUS' => $api->get_email_setting_status($email_setting_details[0]['STATUS'])[0]['BADGE'],
                'MAIL_HOST' => $email_setting_details[0]['MAIL_HOST'],
                'PORT' => $email_setting_details[0]['PORT'],
                'SMTP_AUTH' => $email_setting_details[0]['SMTP_AUTH'],
                'SMTP_AUTO_TLS' => $email_setting_details[0]['SMTP_AUTO_TLS'],
                'MAIL_USERNAME' => $email_setting_details[0]['MAIL_USERNAME'],
                'MAIL_PASSWORD' => $email_setting_details[0]['MAIL_PASSWORD'],
                'MAIL_ENCRYPTION' => $email_setting_details[0]['MAIL_ENCRYPTION'],
                'MAIL_FROM_NAME' => $email_setting_details[0]['MAIL_FROM_NAME'],
                'MAIL_FROM_EMAIL' => $email_setting_details[0]['MAIL_FROM_EMAIL'],
                'TRANSACTION_LOG_ID' => $email_setting_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Notification setting details
    else if($transaction == 'notification setting details'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_setting_details = $api->get_notification_setting_details($notification_setting_id);

            $response[] = array(
                'NOTIFICATION_SETTING' => $notification_setting_details[0]['NOTIFICATION_SETTING'],
                'DESCRIPTION' => $notification_setting_details[0]['DESCRIPTION'],
                'NOTIFICATION_TITLE' => $notification_setting_details[0]['NOTIFICATION_TITLE'],
                'NOTIFICATION_MESSAGE' => $notification_setting_details[0]['NOTIFICATION_MESSAGE'],
                'SYSTEM_LINK' => $notification_setting_details[0]['SYSTEM_LINK'],
                'EMAIL_LINK' => $notification_setting_details[0]['EMAIL_LINK'],
                'TRANSACTION_LOG_ID' => $notification_setting_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Country details
    else if($transaction == 'country details'){
        if(isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $country_id = $_POST['country_id'];
            $country_details = $api->get_country_details($country_id);

            $response[] = array(
                'COUNTRY_NAME' => $country_details[0]['COUNTRY_NAME'],
                'TRANSACTION_LOG_ID' => $country_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # State details
    else if($transaction == 'state details'){
        if(isset($_POST['state_id']) && !empty($_POST['state_id'])){
            $state_id = $_POST['state_id'];
            $state_details = $api->get_state_details($state_id);

            $response[] = array(
                'STATE_NAME' => $state_details[0]['STATE_NAME'],
                'COUNTRY_ID' => $state_details[0]['COUNTRY_ID'],
                'TRANSACTION_LOG_ID' => $state_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Zoom API details
    else if($transaction == 'zoom api details'){
        if(isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
            $zoom_api_id = $_POST['zoom_api_id'];
            $zoom_api_details = $api->get_zoom_api_details($zoom_api_id);

            $response[] = array(
                'ZOOM_API_NAME' => $zoom_api_details[0]['ZOOM_API_NAME'],
                'DESCRIPTION' => $zoom_api_details[0]['DESCRIPTION'],
                'API_KEY' => $zoom_api_details[0]['API_KEY'],
                'API_SECRET' => $zoom_api_details[0]['API_SECRET'],
                'STATUS' =>  $api->get_zoom_api_status($zoom_api_details[0]['STATUS'])[0]['BADGE'],
                'TRANSACTION_LOG_ID' => $zoom_api_details[0]['TRANSACTION_LOG_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

}

?>