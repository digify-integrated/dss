<?php
session_start();
require('config/config.php');
require('classes/api.php');

if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = $_POST['transaction'];
    $api = new Api;
    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');

    switch ($transaction) {
        # Authenticate
        case 'authenticate':
            if(isset($_POST['password']) && !empty($_POST['password'])){
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
        break;
        # -------------------------------------------------------------

        # Change password
        case 'change password':
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
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Submit transactions
        # -------------------------------------------------------------

        # Submit module
        case 'submit module':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['module_id']) && isset($_POST['module_name']) && !empty($_POST['module_name']) && isset($_POST['module_description']) && !empty($_POST['module_description']) && isset($_POST['module_category']) && !empty($_POST['module_category']) && isset($_POST['default_page']) && !empty($_POST['default_page']) && isset($_POST['order_sequence']) && isset($_POST['module_version']) && !empty($_POST['module_version'])){
                        $file_type = '';
                        $module_id = $_POST['module_id'];
                        $module_name = $_POST['module_name'];
                        $module_description = $_POST['module_description'];
                        $module_category = $_POST['module_category'];
                        $module_version = $_POST['module_version'];
                        $default_page = $_POST['default_page'];
                        $order_sequence = $_POST['order_sequence'] ?? 0;
            
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
                                                $update_module = $api->update_module($module_id, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username);
            
                                                if($update_module){
                                                    $response[] = array(
                                                        'RESPONSE' => 'Updated'
                                                    );
                                                }
                                                else{
                                                    $response[] = array(
                                                        'RESPONSE' => $update_module
                                                    );
                                                }
                                            }
                                            else{
                                                $response[] = array(
                                                    'RESPONSE' => $update_module_icon
                                                );
                                            }
                                        }
                                        else{
                                            $response[] = array(
                                                'RESPONSE' => 'File Size'
                                            );
                                        }
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => 'There was an error uploading the file.'
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => 'File Type'
                                    );
                                }
                            }
                            else{
                                $update_module = $api->update_module($module_id, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username);
            
                                if($update_module){
                                    $response[] = array(
                                        'RESPONSE' => 'Updated'
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $update_module
                                    );
                                }
                            }
                        }
                        else{
                            if(!empty($module_icon_tmp_name)){
                                if(in_array($module_icon_actual_ext, $allowed_ext)){
                                    if(!$module_icon_error){
                                        if($module_icon_size < $file_max_size){
                                            $insert_module = $api->insert_module($module_icon_tmp_name, $module_icon_actual_ext, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username);
                
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
                                $insert_module = $api->insert_module(null, null, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username);
                
                                if($insert_module[0]['RESPONSE']){
                                    $response[] = array(
                                        'RESPONSE' => 'Inserted',
                                        'MODULE_ID' => $insert_module[0]['MODULE_ID']
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $insert_module[0]['RESPONSE']
                                    );
                                }
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit module access
        case 'submit module access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['module_id']) && !empty($_POST['module_id']) && isset($_POST['role']) && !empty($_POST['role'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                } 
            }
        break;
        # -------------------------------------------------------------

        # Submit page
        case 'submit page':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['page_id']) && isset($_POST['page_name']) && !empty($_POST['page_name']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
                        $page_id = $_POST['page_id'];
                        $page_name = $_POST['page_name'];
                        $module_id = $_POST['module_id'];
            
                        $check_page_exist = $api->check_page_exist($page_id);
             
                        if($check_page_exist > 0){
                            $update_page = $api->update_page($page_id, $page_name, $module_id, $username);
            
                            if($update_page){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_page
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
                                    'RESPONSE' => $insert_page[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit page access
        case 'submit page access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['page_id']) && !empty($_POST['page_id']) && isset($_POST['role']) && !empty($_POST['role'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit action
        case 'submit action':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['action_id']) && isset($_POST['action_name']) && !empty($_POST['action_name'])){
                        $action_id = $_POST['action_id'];
                        $action_name = $_POST['action_name'];
            
                        $check_action_exist = $api->check_action_exist($action_id);
             
                        if($check_action_exist > 0){
                            $update_action = $api->update_action($action_id, $action_name, $username);
            
                            if($update_action){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_action
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
                                    'RESPONSE' => $insert_action[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit action access
        case 'submit action access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['action_id']) && !empty($_POST['action_id']) && isset($_POST['role']) && !empty($_POST['role'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit system parameter
        case 'submit system parameter':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['parameter_id']) && isset($_POST['parameter']) && !empty($_POST['parameter']) && isset($_POST['parameter_description']) && !empty($_POST['parameter_description']) && isset($_POST['parameter_extension']) && isset($_POST['parameter_number'])){
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
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_system_parameter
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
                                    'RESPONSE' => $insert_system_parameter[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }   
        break;
        # -------------------------------------------------------------

        # Submit role
        case 'submit role':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && isset($_POST['role']) && !empty($_POST['role']) && isset($_POST['role_description']) && !empty($_POST['role_description']) && isset($_POST['assignable']) && !empty($_POST['assignable'])){
                        $role_id = $_POST['role_id'];
                        $role = $_POST['role'];
                        $role_description = $_POST['role_description'];
                        $assignable = $_POST['assignable'];
            
                        $check_role_exist = $api->check_role_exist($role_id);
             
                        if($check_role_exist > 0){
                            $update_role = $api->update_role($role_id, $role, $role_description, $assignable, $username);
            
                            if($update_role){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_role
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
                                    'RESPONSE' => $insert_role[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit role module access
        case 'submit role module access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['module_id']) && !empty($_POST['module_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit role page access
        case 'submit role page access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['page_id']) && !empty($_POST['page_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit role action access
        case 'submit role action access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['action_id']) && !empty($_POST['action_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit role user account
        case 'submit role user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit system code
        case 'submit system code':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['system_code_id']) && isset($_POST['system_type']) && !empty($_POST['system_type']) && isset($_POST['system_code']) && !empty($_POST['system_code']) && isset($_POST['system_description']) && !empty($_POST['system_description'])){
                        $system_code_id = $_POST['system_code_id'];
                        $system_type = $_POST['system_type'];
                        $system_code = $_POST['system_code'];
                        $system_description = $_POST['system_description'];
            
                        $check_system_code_exist = $api->check_system_code_exist($system_code_id);
             
                        if($check_system_code_exist > 0){
                            $update_system_code = $api->update_system_code($system_code_id, $system_type, $system_code, $system_description, $username);
            
                            if($update_system_code){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_system_code
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
                                    'RESPONSE' => $insert_system_code[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit upload setting
        case 'submit upload setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['upload_setting_id']) && isset($_POST['upload_setting']) && !empty($_POST['upload_setting']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['max_file_size']) && !empty($_POST['max_file_size'])){
                        $upload_setting_id = $_POST['upload_setting_id'];
                        $upload_setting = $_POST['upload_setting'];
                        $description = $_POST['description'];
                        $max_file_size = $_POST['max_file_size'];
            
                        $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);
             
                        if($check_upload_setting_exist > 0){
                            $update_upload_setting = $api->update_upload_setting($upload_setting_id, $upload_setting, $description, $max_file_size, $username);
            
                            if($update_upload_setting){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_upload_setting
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
                                    'RESPONSE' => $insert_upload_setting[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit upload setting file type
        case 'submit upload setting file type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id']) && isset($_POST['file_type']) && !empty($_POST['file_type'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit company
        case 'submit company':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();    
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['company_id']) && isset($_POST['company_name']) && !empty($_POST['company_name']) && isset($_POST['company_address']) && isset($_POST['tax_id']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['telephone']) && isset($_POST['website'])){
                        $file_type = '';
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
                                                        'RESPONSE' => 'Updated'
                                                    );
                                                }
                                                else{
                                                    $response[] = array(
                                                        'RESPONSE' => $update_company
                                                    );
                                                }
                                            }
                                            else{
                                                $response[] = array(
                                                    'RESPONSE' => $update_company_logo
                                                );
                                            }
                                        }
                                        else{
                                            $response[] = array(
                                                'RESPONSE' => 'File Size'
                                            );
                                        }
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => 'There was an error uploading the file.'
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => 'File Type'
                                    );
                                }
                            }
                            else{
                                $update_company = $api->update_company($company_id, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username);
            
                                if($update_company){
                                    $response[] = array(
                                        'RESPONSE' => 'Updated'
                                    );
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $update_company
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
                                                    'RESPONSE' => $insert_company[0]['RESPONSE']
                                                );
                                            }
                                        }
                                        else{
                                            $response[] = array(
                                                'RESPONSE' => 'File Size'
                                            );
                                        }
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => 'There was an error uploading the file.'
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => 'File Type'
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
                                        'RESPONSE' => $insert_company[0]['RESPONSE']
                                    );
                                }
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit interface setting
        case 'submit interface setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['interface_setting_id']) && isset($_POST['interface_setting_name']) && !empty($_POST['interface_setting_name']) && isset($_POST['description']) && !empty($_POST['description']) ){
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
                                                    'RESPONSE' => 'Updated'
                                                );
                                            }
                                            else{
                                                $response[] = array(
                                                    'RESPONSE' => $favicon
                                                );
                                            }
                                        }
                                        else{
                                            $response[] = array(
                                                'RESPONSE' => $menu_logo
                                            );
                                        }
                                    }
                                    else{
                                        $response[] = array(
                                            'RESPONSE' => $login_logo
                                        );
                                    }
                                }
                                else{
                                    $response[] = array(
                                        'RESPONSE' => $login_background
                                    );
                                }
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_interface_setting
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
                                    'RESPONSE' => $insert_interface_setting[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                 echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit email setting
        case 'submit email setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['email_setting_id']) && isset($_POST['email_setting_name']) && !empty($_POST['email_setting_name']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['mail_host']) && !empty($_POST['mail_host']) && isset($_POST['port']) && isset($_POST['smtp_auth']) && isset($_POST['smtp_auto_tls']) && isset($_POST['mail_username']) && !empty($_POST['mail_username']) && isset($_POST['mail_password']) && !empty($_POST['mail_password']) && isset($_POST['mail_encryption']) && !empty($_POST['mail_encryption']) && isset($_POST['mail_from_name']) && !empty($_POST['mail_from_name']) && isset($_POST['mail_from_email']) && !empty($_POST['mail_from_email'])){
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
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_email_setting
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
                                    'RESPONSE' => $insert_email_setting[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit notification setting
        case 'submit notification setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && isset($_POST['notification_setting']) && !empty($_POST['notification_setting']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['notification_title']) && !empty($_POST['notification_title']) && isset($_POST['notification_message']) && !empty($_POST['notification_message']) && isset($_POST['system_link']) && isset($_POST['email_link'])){
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
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_notification_setting
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
                                    'RESPONSE' => $insert_notification_setting[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit notification role recipient
        case 'submit notification role recipient':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit notification user account recipient
        case 'submit notification user account recipient':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit notification channel
        case 'submit notification channel':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['channel']) && !empty($_POST['channel']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                        $error = '';
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit country
        case 'submit country':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['country_id']) && isset($_POST['country_name']) && !empty($_POST['country_name'])){
                        $country_id = $_POST['country_id'];
                        $country_name = $_POST['country_name'];
            
                        $check_country_exist = $api->check_country_exist($country_id);
             
                        if($check_country_exist > 0){
                            $update_country = $api->update_country($country_id, $country_name, $username);
            
                            if($update_country){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_country
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
                                    'RESPONSE' => $insert_country[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit country state
        case 'submit country state':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['state_name']) && isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
                        $state_id = $_POST['state_id'];
                        $state_name = $_POST['state_name'];
                        $country_id = $_POST['country_id'];
    
                        $check_state_exist = $api->check_state_exist($state_id);
             
                        if($check_state_exist > 0){
                            $update_state = $api->update_state($state_id, $state_name, $country_id, $username);
            
                            if($update_state){
                                echo 'Updated';
                            }
                            else{
                                echo $update_state;
                            }
                        }
                        else{
                            $insert_state = $api->insert_state($state_name, $country_id, $username);
                
                            if($insert_state[0]['RESPONSE']){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_state[0]['RESPONSE'];
                            }
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit state
        case 'submit state':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['state_id']) && isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
                        $state_id = $_POST['state_id'];
                        $state_name = $_POST['state_name'];
                        $country_id = $_POST['country_id'];
            
                        $check_state_exist = $api->check_state_exist($state_id);
             
                        if($check_state_exist > 0){
                            $update_state = $api->update_state($state_id, $state_name, $country_id, $username);
            
                            if($update_state){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_state
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
                                    'RESPONSE' => $insert_state[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit zoom api
        case 'submit zoom api':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['zoom_api_id']) && isset($_POST['zoom_api_name']) && !empty($_POST['zoom_api_name']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['api_key']) && !empty($_POST['api_key']) && isset($_POST['api_secret']) && !empty($_POST['api_secret'])){
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
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_zoom_api
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
                                    'RESPONSE' => $insert_zoom_api[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit user account
        case 'submit user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['file_as']) && !empty($_POST['file_as']) && isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['password']) && !empty($_POST['password'])){
                        $file_as = $_POST['file_as'];
                        $user_id = $_POST['user_id'];
                        $password = $api->encrypt_data($_POST['password']);
                        $password_expiry_date = $api->format_date('Y-m-d', $system_date, '+6 months');
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $update_user_account = $api->update_user_account($user_id, $password, $file_as, $password_expiry_date, $username);
            
                            if($update_user_account){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_user_account
                                );
                            }
                        }
                        else{
                            $insert_user_account = $api->insert_user_account($user_id, $password, $file_as, $password_expiry_date, $username);
            
                            if($insert_user_account){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'USER_ID' =>  $api->encrypt_data($user_id)
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_user_account
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit user account role
        case 'submit user account role':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role']) && !empty($_POST['role'])){
                        $error = '';
                        $user_id = $_POST['user_id'];
                        $roles = explode(',', $_POST['role']);
            
                        foreach($roles as $role){
                            $check_role_user_account_exist = $api->check_role_user_account_exist($role, $user_id);
            
                            if($check_role_user_account_exist == 0){
                                $insert_role_user_account = $api->insert_role_user_account($role, $user_id, $username);
            
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit department
        case 'submit department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && isset($_POST['department']) && !empty($_POST['department']) && isset($_POST['parent_department']) && isset($_POST['manager'])){
                        $department_id = $_POST['department_id'];
                        $department = $_POST['department'];
                        $parent_department = $_POST['parent_department'];
                        $manager = $_POST['manager'];
            
                        $check_department_exist = $api->check_department_exist($department_id);
             
                        if($check_department_exist > 0){
                            $update_department = $api->update_department($department_id, $department, $parent_department, $manager, $username);
            
                            if($update_department){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_department
                                );
                            }
                        }
                        else{
                            $insert_department = $api->insert_department($department, $parent_department, $manager, $username);
                
                            if($insert_department[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'DEPARTMENT_ID' => $insert_department[0]['DEPARTMENT_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_department[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit job position
        case 'submit job position':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['job_position_id']) && isset($_POST['job_position']) && !empty($_POST['job_position']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['department']) && !empty($_POST['department']) && isset($_POST['expected_new_employees'])){
                        $job_position_id = $_POST['job_position_id'];
                        $job_position = $_POST['job_position'];
                        $description = $_POST['description'];
                        $department = $_POST['department'];
                        $expected_new_employees = $_POST['expected_new_employees'] ?? 0;
            
                        $check_job_position_exist = $api->check_job_position_exist($job_position_id);
             
                        if($check_job_position_exist > 0){
                            $update_job_position = $api->update_job_position($job_position_id, $job_position, $description, $department, $expected_new_employees, $username);
            
                            if($update_job_position){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_job_position
                                );
                            }
                        }
                        else{
                            $insert_job_position = $api->insert_job_position($job_position, $description, $department, $expected_new_employees, $username);
                
                            if($insert_job_position[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'JOB_POSITION_ID' => $insert_job_position[0]['JOB_POSITION_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_job_position[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit job position responsibility
        case 'submit job position responsibility':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['responsibility_id']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id']) && isset($_POST['responsibility']) && !empty($_POST['responsibility'])){
                        $responsibility_id = $_POST['responsibility_id'];
                        $job_position_id = $_POST['job_position_id'];
                        $responsibility = $_POST['responsibility'];
            
                        $check_job_position_responsibility_exist = $api->check_job_position_responsibility_exist($responsibility_id);
             
                        if($check_job_position_responsibility_exist > 0){
                            $update_job_position_responsibility = $api->update_job_position_responsibility($responsibility_id, $job_position_id, $responsibility, $username);
            
                            if($update_job_position_responsibility){
                                echo 'Updated';
                            }
                            else{
                                echo $update_job_position_responsibility;
                            }
                        }
                        else{
                            $insert_job_position_responsibility = $api->insert_job_position_responsibility($job_position_id, $responsibility, $username);
                
                            if($insert_job_position_responsibility){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_job_position_responsibility;
                            }
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit job position requirement
        case 'submit job position requirement':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['requirement_id']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id']) && isset($_POST['requirement']) && !empty($_POST['requirement'])){
                        $requirement_id = $_POST['requirement_id'];
                        $job_position_id = $_POST['job_position_id'];
                        $requirement = $_POST['requirement'];
            
                        $check_job_position_requirement_exist = $api->check_job_position_requirement_exist($requirement_id);
             
                        if($check_job_position_requirement_exist > 0){
                            $update_job_position_requirement = $api->update_job_position_requirement($requirement_id, $job_position_id, $requirement, $username);
            
                            if($update_job_position_requirement){
                                echo 'Updated';
                            }
                            else{
                                echo $update_job_position_requirement;
                            }
                        }
                        else{
                            $insert_job_position_requirement = $api->insert_job_position_requirement($job_position_id, $requirement, $username);
                
                            if($insert_job_position_requirement){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_job_position_requirement;
                            }
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit job position qualification
        case 'submit job position qualification':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['qualification_id']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id']) && isset($_POST['qualification']) && !empty($_POST['qualification'])){
                        $qualification_id = $_POST['qualification_id'];
                        $job_position_id = $_POST['job_position_id'];
                        $qualification = $_POST['qualification'];
            
                        $check_job_position_qualification_exist = $api->check_job_position_qualification_exist($qualification_id);
             
                        if($check_job_position_qualification_exist > 0){
                            $update_job_position_qualification = $api->update_job_position_qualification($qualification_id, $job_position_id, $qualification, $username);
            
                            if($update_job_position_qualification){
                                echo 'Updated';
                            }
                            else{
                                echo $update_job_position_qualification;
                            }
                        }
                        else{
                            $insert_job_position_qualification = $api->insert_job_position_qualification($job_position_id, $qualification, $username);
                
                            if($insert_job_position_qualification){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_job_position_qualification;
                            }
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit job position attachment
        case 'submit job position attachment':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['attachment_id']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id']) && isset($_POST['attachment_name']) && !empty($_POST['attachment_name'])){
                        $file_type = '';
                        $attachment_id = $_POST['attachment_id'];
                        $job_position_id = $_POST['job_position_id'];
                        $attachment_name = $_POST['attachment_name'];
            
                        $attachment_file_name = $_FILES['attachment']['name'];
                        $attachment_size = $_FILES['attachment']['size'];
                        $attachment_error = $_FILES['attachment']['error'];
                        $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
                        $attachment_ext = explode('.', $attachment_file_name);
                        $attachment_actual_ext = strtolower(end($attachment_ext));
            
                        $upload_setting_details = $api->get_upload_setting_details(7);
                        $upload_file_type_details = $api->get_upload_file_type_details(7);
                        $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;
            
                        for($i = 0; $i < count($upload_file_type_details); $i++) {
                            $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];
            
                            if($i != (count($upload_file_type_details) - 1)){
                                $file_type .= ',';
                            }
                        }
            
                        $allowed_ext = explode(',', $file_type);
            
                        $check_job_position_attachment_exist = $api->check_job_position_attachment_exist($attachment_id);
             
                        if($check_job_position_attachment_exist > 0){
                            if(!empty($attachment_tmp_name)){
                                if(in_array($attachment_actual_ext, $allowed_ext)){
                                    if(!$attachment_error){
                                        if($attachment_size < $file_max_size){
                                            $update_job_position_attachment = $api->update_job_position_attachment($attachment_tmp_name, $attachment_actual_ext, $attachment_id, $username);
                    
                                            if($update_job_position_attachment){
                                                $update_job_position_attachment_details = $api->update_job_position_attachment_details($attachment_id, $job_position_id, $attachment_name, $username);
            
                                                if($update_job_position_attachment_details){
                                                    echo 'Updated';
                                                }
                                                else{
                                                    echo $update_job_position_attachment_details;
                                                }
                                            }
                                            else{
                                                echo $update_job_position_attachment;
                                            }
                                        }
                                        else{
                                            echo 'File Size';
                                        }
                                    }
                                    else{
                                        echo 'There was an error uploading the file.';
                                    }
                                }
                                else{
                                    echo 'File Type';
                                }
                            }
                            else{
                                $update_job_position_attachment_details = $api->update_job_position_attachment_details($attachment_id, $job_position_id, $attachment_name, $username);
            
                                if($update_job_position_attachment_details){
                                    echo 'Updated';
                                }
                                else{
                                    echo $update_job_position_attachment_details;
                                }
                            }
                        }
                        else{
                            if(in_array($attachment_actual_ext, $allowed_ext)){
                                if(!$attachment_error){
                                    if($attachment_size < $file_max_size){
                                        $insert_job_position_attachment = $api->insert_job_position_attachment($attachment_tmp_name, $attachment_actual_ext, $job_position_id, $attachment_name, $username);
            
                                        if($insert_job_position_attachment){
                                            echo 'Inserted';
                                        }
                                        else{
                                            echo $insert_job_position_attachment;
                                        }
                                    }
                                    else{
                                        echo 'File Size';
                                    }
                                }
                                else{
                                    echo 'There was an error uploading the file.';
                                }
                            }
                            else{
                                echo 'File Type';
                            }
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Submit work location
        case 'submit work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && isset($_POST['work_location']) && !empty($_POST['work_location']) && isset($_POST['work_location_address']) && !empty($_POST['work_location_address']) && isset($_POST['email']) && isset($_POST['telephone']) && isset($_POST['mobile']) && isset($_POST['location_number'])){
                        $work_location_id = $_POST['work_location_id'];
                        $work_location = $_POST['work_location'];
                        $work_location_address = $_POST['work_location_address'];
                        $email = $_POST['email'];
                        $telephone = $_POST['telephone'];
                        $mobile = $_POST['mobile'];
                        $location_number = $_POST['location_number'] ?? 1;
            
                        $check_work_location_exist = $api->check_work_location_exist($work_location_id);
             
                        if($check_work_location_exist > 0){
                            $update_work_location = $api->update_work_location($work_location_id, $work_location, $work_location_address, $email, $telephone, $mobile, $location_number, $username);
            
                            if($update_work_location){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_work_location
                                );
                            }
                        }
                        else{
                            $insert_work_location = $api->insert_work_location($work_location, $work_location_address, $email, $telephone, $mobile, $location_number, $username);
                
                            if($insert_work_location[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'WORK_LOCATION_ID' => $insert_work_location[0]['WORK_LOCATION_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_work_location[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit departure reason
        case 'submit departure reason':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['departure_reason_id']) && isset($_POST['departure_reason']) && !empty($_POST['departure_reason'])){
                        $departure_reason_id = $_POST['departure_reason_id'];
                        $departure_reason = $_POST['departure_reason'];
            
                        $check_departure_reason_exist = $api->check_departure_reason_exist($departure_reason_id);
             
                        if($check_departure_reason_exist > 0){
                            $update_departure_reason = $api->update_departure_reason($departure_reason_id, $departure_reason, $username);
            
                            if($update_departure_reason){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_departure_reason
                                );
                            }
                        }
                        else{
                            $insert_departure_reason = $api->insert_departure_reason($departure_reason, $username);
                
                            if($insert_departure_reason[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'DEPARTURE_REASON_ID' => $insert_departure_reason[0]['DEPARTURE_REASON_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_departure_reason[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit employee type
        case 'submit employee type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['employee_type_id']) && isset($_POST['employee_type']) && !empty($_POST['employee_type'])){
                        $employee_type_id = $_POST['employee_type_id'];
                        $employee_type = $_POST['employee_type'];
            
                        $check_employee_type_exist = $api->check_employee_type_exist($employee_type_id);
             
                        if($check_employee_type_exist > 0){
                            $update_employee_type = $api->update_employee_type($employee_type_id, $employee_type, $username);
            
                            if($update_employee_type){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_employee_type
                                );
                            }
                        }
                        else{
                            $insert_employee_type = $api->insert_employee_type($employee_type, $username);
                
                            if($insert_employee_type[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'EMPLOYEE_TYPE_ID' => $insert_employee_type[0]['EMPLOYEE_TYPE_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_employee_type[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Submit wage type
        case 'submit wage type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $response = array();
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['wage_type_id']) && isset($_POST['wage_type']) && !empty($_POST['wage_type'])){
                        $wage_type_id = $_POST['wage_type_id'];
                        $wage_type = $_POST['wage_type'];
            
                        $check_wage_type_exist = $api->check_wage_type_exist($wage_type_id);
             
                        if($check_wage_type_exist > 0){
                            $update_wage_type = $api->update_wage_type($wage_type_id, $wage_type, $username);
            
                            if($update_wage_type){
                                $response[] = array(
                                    'RESPONSE' => 'Updated'
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $update_wage_type
                                );
                            }
                        }
                        else{
                            $insert_wage_type = $api->insert_wage_type($wage_type, $username);
                
                            if($insert_wage_type[0]['RESPONSE']){
                                $response[] = array(
                                    'RESPONSE' => 'Inserted',
                                    'WAGE_TYPE_ID' => $insert_wage_type[0]['WAGE_TYPE_ID']
                                );
                            }
                            else{
                                $response[] = array(
                                    'RESPONSE' => $insert_wage_type[0]['RESPONSE']
                                );
                            }
                        }
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => 'Inactive User'
                    );
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Delete transactions
        # -------------------------------------------------------------

        # Delete module
        case 'delete module':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
                        $module_id = $_POST['module_id'];
            
                        $check_module_exist = $api->check_module_exist($module_id);
            
                        if($check_module_exist > 0){
                            $delete_all_module_access = $api->delete_all_module_access($module_id, $username);
                                                
                            if($delete_all_module_access){
                                $delete_module = $api->delete_module($module_id, $username);
            
                                if($delete_module){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_module;
                                }
                            }
                            else{
                                echo $delete_all_module_access;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple module
        case 'delete multiple module':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
                        $module_ids = $_POST['module_id'];
            
                        foreach($module_ids as $module_id){
                            $check_module_exist = $api->check_module_exist($module_id);
            
                            if($check_module_exist > 0){
                                $delete_all_module_access = $api->delete_all_module_access($module_id, $username);
                                                
                                if($delete_all_module_access){
                                    $delete_module = $api->delete_module($module_id, $username);
            
                                    if(!$delete_module){
                                        $error = $delete_module;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_module_access;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete module access
        case 'delete module access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['module_id']) && !empty($_POST['module_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete page
        case 'delete page':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['page_id']) && !empty($_POST['page_id'])){
                        $page_id = $_POST['page_id'];
            
                        $check_page_exist = $api->check_page_exist($page_id);
            
                        if($check_page_exist > 0){
                            $delete_all_page_access = $api->delete_all_page_access($page_id, $username);
                                                
                            if($delete_all_page_access){
                                $delete_page = $api->delete_page($page_id, $username);
            
                                if($delete_page){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_page;
                                }
                            }
                            else{
                                echo $delete_all_page_access;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple page
        case 'delete multiple page':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['page_id']) && !empty($_POST['page_id'])){
                        $page_ids = $_POST['page_id'];
            
                        foreach($page_ids as $page_id){
                            $check_page_exist = $api->check_page_exist($page_id);
            
                            if($check_page_exist > 0){
                                $delete_all_page_access = $api->delete_all_page_access($page_id, $username);
                                                
                                if($delete_all_page_access){
                                    $delete_page = $api->delete_page($page_id, $username);
            
                                    if(!$delete_page){
                                        $error = $delete_page;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_page_access;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete page access
        case 'delete page access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['page_id']) && !empty($_POST['page_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete action
        case 'delete action':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
                        $action_id = $_POST['action_id'];
            
                        $check_action_exist = $api->check_action_exist($action_id);
            
                        if($check_action_exist > 0){
                            $delete_all_action_access = $api->delete_all_action_access($action_id, $username);
                                                
                            if($delete_all_action_access){
                                $delete_action = $api->delete_action($action_id, $username);
            
                                if($delete_action){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_action;
                                }
                            }
                            else{
                                echo $delete_all_action_access;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple action
        case 'delete multiple action':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
                        $action_ids = $_POST['action_id'];
            
                        foreach($action_ids as $action_id){
                            $check_action_exist = $api->check_action_exist($action_id);
            
                            if($check_action_exist > 0){
                                $delete_all_action_access = $api->delete_all_action_access($action_id, $username);
                                                
                                if($delete_all_action_access){
                                    $delete_action = $api->delete_action($action_id, $username);
            
                                    if(!$delete_action){
                                        $error = $delete_action;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_action_access;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete action access
        case 'delete action access':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['action_id']) && !empty($_POST['action_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete system parameter
        case 'delete system parameter':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['parameter_id']) && !empty($_POST['parameter_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple system parameter
        case 'delete multiple system parameter':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['parameter_id']) && !empty($_POST['parameter_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete role
        case 'delete role':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                        $role_id = $_POST['role_id'];
            
                        $check_role_exist = $api->check_role_exist($role_id);
            
                        if($check_role_exist > 0){
                            $delete_all_role_user_account = $api->delete_all_role_user_account($role_id, $username);
                                                
                            if($delete_all_role_user_account){
                                $delete_role_module_access = $api->delete_role_module_access($role_id, $username);
                                                
                                if($delete_role_module_access){
                                    $delete_role_page_access = $api->delete_role_page_access($role_id, $username);
                                                
                                    if($delete_role_page_access){
                                        $delete_role_action_access = $api->delete_role_action_access($role_id, $username);
                                                
                                        if($delete_role_action_access){
                                            $delete_role = $api->delete_role($role_id, $username);
                                                
                                            if($delete_role){
                                                echo 'Deleted';
                                            }
                                            else{
                                                echo $delete_role;
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
                                echo $delete_all_role_user_account;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple role
        case 'delete multiple role':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                        $role_ids = $_POST['role_id'];
            
                        foreach($role_ids as $role_id){
                            $check_role_exist = $api->check_role_exist($role_id);
            
                            if($check_role_exist > 0){
                                $delete_all_role_user_account = $api->delete_all_role_user_account($role_id, $username);
            
                                if($delete_all_role_user_account){
                                    $delete_role_module_access = $api->delete_role_module_access($role_id, $username);
                                                    
                                    if($delete_role_module_access){
                                        $delete_role_page_access = $api->delete_role_page_access($role_id, $username);
                                                    
                                        if($delete_role_page_access){
                                            $delete_role_action_access = $api->delete_role_action_access($role_id, $username);
                                                    
                                            if($delete_role_action_access){
                                                $delete_role = $api->delete_role($role_id, $username);
                                                    
                                                if(!$delete_role){
                                                    $error = $delete_role;
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
                                    $error = $delete_all_role_user_account;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete role user account
        case 'delete role user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete system code
        case 'delete system code':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['system_code_id']) && !empty($_POST['system_code_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple system code
        case 'delete multiple system code':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['system_code_id']) && !empty($_POST['system_code_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete upload setting
        case 'delete upload setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
                        $upload_setting_id = $_POST['upload_setting_id'];
            
                        $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);
            
                        if($check_upload_setting_exist > 0){
                            $delete_all_upload_setting_file_type = $api->delete_all_upload_setting_file_type($upload_setting_id, $username);
                                                
                            if($delete_all_upload_setting_file_type){
                                $delete_upload_setting = $api->delete_upload_setting($upload_setting_id, $username);
                                                
                                if($delete_upload_setting){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_upload_setting;
                                }
                            }
                            else{
                                echo $delete_all_upload_setting_file_type;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple upload setting
        case 'delete multiple upload setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
                        $upload_setting_ids = $_POST['upload_setting_id'];
            
                        foreach($upload_setting_ids as $upload_setting_id){
                            $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);
            
                            if($check_upload_setting_exist > 0){
                                $delete_all_upload_setting_file_type = $api->delete_all_upload_setting_file_type($upload_setting_id, $username);
                                                
                                if($delete_all_upload_setting_file_type){
                                    $delete_upload_setting = $api->delete_upload_setting($upload_setting_id, $username);
                                                
                                    if(!$delete_upload_setting){
                                        $error = $delete_upload_setting;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_upload_setting_file_type;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete upload setting file type
        case 'delete upload setting file type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id']) && isset($_POST['file_type']) && !empty($_POST['file_type'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete company
        case 'delete company':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['company_id']) && !empty($_POST['company_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple company
        case 'delete multiple company':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['company_id']) && !empty($_POST['company_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete interface setting
        case 'delete interface setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple interface setting
        case 'delete multiple interface setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete email setting
        case 'delete email setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple email setting
        case 'delete multiple email setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete notification setting
        case 'delete notification setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                        $notification_setting_id = $_POST['notification_setting_id'];
            
                        $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);
            
                        if($check_notification_setting_exist > 0){
                            $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                                
                            if($delete_all_notification_channel){
                                $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                                
                                if($delete_all_notification_role_recipient){
                                    $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                                
                                    if($delete_all_notification_user_account_recipient){
                                        $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                                
                                        if($delete_notification_setting){
                                            echo 'Deleted';
                                        }
                                        else{
                                            echo $delete_notification_setting;
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
                                echo $delete_all_notification_channel;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple notification setting
        case 'delete multiple notification setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
                        $notification_setting_ids = $_POST['notification_setting_id'];
            
                        foreach($notification_setting_ids as $notification_setting_id){
                            $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);
            
                            if($check_notification_setting_exist > 0){
                                $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                                
                                if($delete_all_notification_channel){
                                    $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                                    
                                    if($delete_all_notification_role_recipient){
                                        $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                                    
                                        if($delete_all_notification_user_account_recipient){
                                            $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                                    
                                            if(!$delete_notification_setting){
                                                $error = $delete_notification_setting;
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
                                    $error = $delete_all_notification_channel;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete notification role recipient
        case 'delete notification role recipient':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete notification user account recipient
        case 'delete notification user account recipient':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['user_id']) && !empty($_POST['notification_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete notification channel
        case 'delete notification channel':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id']) && isset($_POST['channel']) && !empty($_POST['channel'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete country
        case 'delete country':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['country_id']) && !empty($_POST['country_id'])){
                        $country_id = $_POST['country_id'];
            
                        $check_country_exist = $api->check_country_exist($country_id);
            
                        if($check_country_exist > 0){
                            $delete_all_state = $api->delete_all_state($country_id, $username);
                                                
                            if($delete_all_state){
                                $delete_country = $api->delete_country($country_id, $username);
                                                
                                if($delete_country){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_country;
                                }
                            }
                            else{
                                echo $delete_all_state;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple country
        case 'delete multiple country':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['country_id']) && !empty($_POST['country_id'])){
                        $country_ids = $_POST['country_id'];
            
                        foreach($country_ids as $country_id){
                            $check_country_exist = $api->check_country_exist($country_id);
            
                            if($check_country_exist > 0){
                                $delete_all_state = $api->delete_all_state($country_id, $username);
                                                
                                if($delete_all_state){
                                    $delete_country = $api->delete_country($country_id, $username);
                                                
                                    if(!$delete_country){
                                        $error = $delete_country;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_state;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete state
        case 'delete state':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['state_id']) && !empty($_POST['state_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple state
        case 'delete multiple state':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['state_id']) && !empty($_POST['state_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete zoom api
        case 'delete zoom api':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple zoom api
        case 'delete multiple zoom api':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete user account
        case 'delete user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_id = $_POST['user_id'];
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $delete_user_account = $api->delete_user_account($user_id, $username);
                                                
                            if($delete_user_account){
                                $delete_all_user_account_role = $api->delete_all_user_account_role($user_id, $username);
                                                
                                if($delete_all_user_account_role){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_all_user_account_role;
                                }
                            }
                            else{
                                echo $delete_user_account;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple user account
        case 'delete multiple user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_ids = $_POST['user_id'];
            
                        foreach($user_ids as $user_id){
                            $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                            if($check_user_account_exist > 0){
                                $delete_user_account = $api->delete_user_account($user_id, $username);
                                                
                                if($delete_user_account){
                                    $delete_all_user_account_role = $api->delete_all_user_account_role($user_id, $username);
                                                
                                    if(!$delete_all_user_account_role){
                                        $error = $delete_all_user_account_role;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_user_account;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete role user account
        case 'delete user account role':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['role_id']) && !empty($_POST['role_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete department
        case 'delete department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_id = $_POST['department_id'];
            
                        $check_department_exist = $api->check_department_exist($department_id);
            
                        if($check_department_exist > 0){
                            $delete_department = $api->delete_department($department_id, $username);
                                                
                            if($delete_department){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_department;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple department
        case 'delete multiple department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_ids = $_POST['department_id'];
            
                        foreach($department_ids as $department_id){
                            $check_department_exist = $api->check_department_exist($department_id);
            
                            if($check_department_exist > 0){
                                $delete_department = $api->delete_department($department_id, $username);
                                                
                                if(!$delete_department){
                                    $error = $delete_department;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete job position
        case 'delete job position':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                        $job_position_id = $_POST['job_position_id'];
            
                        $check_job_position_exist = $api->check_job_position_exist($job_position_id);
            
                        if($check_job_position_exist > 0){
                            $delete_all_job_position_attachment = $api->delete_all_job_position_attachment($job_position_id, $username);
                                                
                            if($delete_all_job_position_attachment){
                                $delete_all_job_position_responsibility = $api->delete_all_job_position_responsibility($job_position_id, $username);
                                                
                                if($delete_all_job_position_responsibility){
                                    $delete_all_job_position_requirement = $api->delete_all_job_position_requirement($job_position_id, $username);
                                                
                                    if($delete_all_job_position_requirement){
                                        $delete_all_job_position_qualification = $api->delete_all_job_position_qualification($job_position_id, $username);
                                                
                                        if($delete_all_job_position_qualification){
                                            $delete_job_position = $api->delete_job_position($job_position_id, $username);
                                                
                                            if($delete_job_position){
                                                echo 'Deleted';
                                            }
                                            else{
                                                echo $delete_job_position;
                                            }
                                        }
                                        else{
                                            echo $delete_all_job_position_qualification;
                                        }
                                    }
                                    else{
                                        echo $delete_all_job_position_requirement;
                                    }
                                }
                                else{
                                    echo $delete_all_job_position_responsibility;
                                }
                            }
                            else{
                                echo $delete_all_job_position_attachment;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple job position
        case 'delete multiple job position':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                        $job_position_ids = $_POST['job_position_id'];
            
                        foreach($job_position_ids as $job_position_id){
                            $check_job_position_exist = $api->check_job_position_exist($job_position_id);
            
                            if($check_job_position_exist > 0){
                                $delete_all_job_position_attachment = $api->delete_all_job_position_attachment($job_position_id, $username);
                                                
                                if($delete_all_job_position_attachment){
                                    $delete_all_job_position_responsibility = $api->delete_all_job_position_responsibility($job_position_id, $username);
                                                
                                    if($delete_all_job_position_responsibility){
                                        $delete_all_job_position_requirement = $api->delete_all_job_position_requirement($job_position_id, $username);
                                                
                                        if($delete_all_job_position_requirement){
                                            $delete_all_job_position_qualification = $api->delete_all_job_position_qualification($job_position_id, $username);
                                                
                                            if($delete_all_job_position_qualification){
                                                $delete_job_position = $api->delete_job_position($job_position_id, $username);
                                                            
                                                if(!$delete_job_position){
                                                    $error = $delete_job_position;
                                                    break;
                                                }
                                            }
                                            else{
                                                $error = $delete_all_job_position_qualification;
                                                break;
                                            }
                                        }
                                        else{
                                            $error = $delete_all_job_position_requirement;
                                            break;
                                        }
                                    }
                                    else{
                                        $error = $delete_all_job_position_responsibility;
                                        break;
                                    }
                                }
                                else{
                                    $error = $delete_all_job_position_attachment;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete job position responsibility
        case 'delete job position responsibility':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['responsibility_id']) && !empty($_POST['responsibility_id'])){
                        $responsibility_id = $_POST['responsibility_id'];
            
                        $check_job_position_responsibility_exist = $api->check_job_position_responsibility_exist($responsibility_id);
            
                        if($check_job_position_responsibility_exist > 0){
                            $delete_job_position_responsibility = $api->delete_job_position_responsibility($responsibility_id, $username);
                                                
                            if($delete_job_position_responsibility){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_job_position_responsibility;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete job position requirement
        case 'delete job position requirement':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['requirement_id']) && !empty($_POST['requirement_id'])){
                        $requirement_id = $_POST['requirement_id'];
            
                        $check_job_position_requirement_exist = $api->check_job_position_requirement_exist($requirement_id);
            
                        if($check_job_position_requirement_exist > 0){
                            $delete_job_position_requirement = $api->delete_job_position_requirement($requirement_id, $username);
                                                
                            if($delete_job_position_requirement){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_job_position_requirement;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete job position qualification
        case 'delete job position qualification':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['qualification_id']) && !empty($_POST['qualification_id'])){
                        $qualification_id = $_POST['qualification_id'];
            
                        $check_job_position_qualification_exist = $api->check_job_position_qualification_exist($qualification_id);
            
                        if($check_job_position_qualification_exist > 0){
                            $delete_job_position_qualification = $api->delete_job_position_qualification($qualification_id, $username);
                                                
                            if($delete_job_position_qualification){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_job_position_qualification;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete job position attachment
        case 'delete job position attachment':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['attachment_id']) && !empty($_POST['attachment_id'])){
                        $attachment_id = $_POST['attachment_id'];
            
                        $check_job_position_attachment_exist = $api->check_job_position_attachment_exist($attachment_id);
            
                        if($check_job_position_attachment_exist > 0){
                            $delete_job_position_attachment = $api->delete_job_position_attachment($attachment_id, $username);
                                                
                            if($delete_job_position_attachment){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_job_position_attachment;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete work location
        case 'delete work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_id = $_POST['work_location_id'];
            
                        $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                        if($check_work_location_exist > 0){
                            $delete_work_location = $api->delete_work_location($work_location_id, $username);
                                                
                            if($delete_work_location){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_work_location;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple work location
        case 'delete multiple work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_ids = $_POST['work_location_id'];
            
                        foreach($work_location_ids as $work_location_id){
                            $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                            if($check_work_location_exist > 0){
                                $delete_work_location = $api->delete_work_location($work_location_id, $username);
                                                
                                if(!$delete_work_location){
                                    $error = $delete_work_location;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete departure reason
        case 'delete departure reason':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])){
                        $departure_reason_id = $_POST['departure_reason_id'];
            
                        $check_departure_reason_exist = $api->check_departure_reason_exist($departure_reason_id);
            
                        if($check_departure_reason_exist > 0){
                            $delete_departure_reason = $api->delete_departure_reason($departure_reason_id, $username);
                                                
                            if($delete_departure_reason){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_departure_reason;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple departure reason
        case 'delete multiple departure reason':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])){
                        $departure_reason_ids = $_POST['departure_reason_id'];
            
                        foreach($departure_reason_ids as $departure_reason_id){
                            $check_departure_reason_exist = $api->check_departure_reason_exist($departure_reason_id);
            
                            if($check_departure_reason_exist > 0){
                                $delete_departure_reason = $api->delete_departure_reason($departure_reason_id, $username);
                                                
                                if(!$delete_departure_reason){
                                    $error = $delete_departure_reason;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete employee type
        case 'delete employee type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])){
                        $employee_type_id = $_POST['employee_type_id'];
            
                        $check_employee_type_exist = $api->check_employee_type_exist($employee_type_id);
            
                        if($check_employee_type_exist > 0){
                            $delete_employee_type = $api->delete_employee_type($employee_type_id, $username);
                                                
                            if($delete_employee_type){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_employee_type;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple employee type
        case 'delete multiple employee type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])){
                        $employee_type_ids = $_POST['employee_type_id'];
            
                        foreach($employee_type_ids as $employee_type_id){
                            $check_employee_type_exist = $api->check_employee_type_exist($employee_type_id);
            
                            if($check_employee_type_exist > 0){
                                $delete_employee_type = $api->delete_employee_type($employee_type_id, $username);
                                                
                                if(!$delete_employee_type){
                                    $error = $delete_employee_type;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete wage type
        case 'delete wage type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['wage_type_id']) && !empty($_POST['wage_type_id'])){
                        $wage_type_id = $_POST['wage_type_id'];
            
                        $check_wage_type_exist = $api->check_wage_type_exist($wage_type_id);
            
                        if($check_wage_type_exist > 0){
                            $delete_wage_type = $api->delete_wage_type($wage_type_id, $username);
                                                
                            if($delete_wage_type){
                                echo 'Deleted';
                            }
                            else{
                                echo $delete_wage_type;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Delete multiple wage type
        case 'delete multiple wage type':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['wage_type_id']) && !empty($_POST['wage_type_id'])){
                        $wage_type_ids = $_POST['wage_type_id'];
            
                        foreach($wage_type_ids as $wage_type_id){
                            $check_wage_type_exist = $api->check_wage_type_exist($wage_type_id);
            
                            if($check_wage_type_exist > 0){
                                $delete_wage_type = $api->delete_wage_type($wage_type_id, $username);
                                                
                                if(!$delete_wage_type){
                                    $error = $delete_wage_type;
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Unlock transactions
        # -------------------------------------------------------------

        # Unlock user account
        case 'unlock user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_id = $_POST['user_id'];
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $update_user_account_lock_status = $api->update_user_account_lock_status($user_id, 'unlock', $system_date, $username);
                
                            if($update_user_account_lock_status){
                                echo 'Unlocked';
                            }
                            else{
                                echo $update_user_account_lock_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Unlock multiple user account
        case 'unlock multiple user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_ids = $_POST['user_id'];
            
                        foreach($user_ids as $user_id){
                            $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                            if($check_user_account_exist > 0){
                                $update_user_account_lock_status = $api->update_user_account_lock_status($user_id, 'unlock', $system_date, $username);
                                                
                                if(!$update_user_account_lock_status){
                                    $error = $update_user_account_lock_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Unlocked';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Lock transactions
        # -------------------------------------------------------------

        # Lock user account
        case 'lock user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_id = $_POST['user_id'];
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $update_user_account_lock_status = $api->update_user_account_lock_status($user_id, 'lock', $system_date, $username);
                
                            if($update_user_account_lock_status){
                                echo 'Locked';
                            }
                            else{
                                echo $update_user_account_lock_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Lock multiple user account
        case 'lock multiple user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_ids = $_POST['user_id'];
            
                        foreach($user_ids as $user_id){
                            $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                            if($check_user_account_exist > 0){
                                $update_user_account_lock_status = $api->update_user_account_lock_status($user_id, 'lock', $system_date, $username);
                                                
                                if(!$update_user_account_lock_status){
                                    $error = $update_user_account_lock_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Locked';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Activate transactions
        # -------------------------------------------------------------

        # Activate interface setting
        case 'activate interface setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Activate email setting
        case 'activate email setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Activate zoom api
        case 'activate zoom api':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Activate user account
        case 'activate user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_id = $_POST['user_id'];
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $update_user_account_status = $api->update_user_account_status($user_id, 'Active', $username);
                
                            if($update_user_account_status){
                                echo 'Activated';
                            }
                            else{
                                echo $update_user_account_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Activate multiple user account
        case 'activate multiple user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_ids = $_POST['user_id'];
            
                        foreach($user_ids as $user_id){
                            $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                            if($check_user_account_exist > 0){
                                $update_user_account_status = $api->update_user_account_status($user_id, 'Active', $username);
                                                
                                if(!$update_user_account_status){
                                    $error = $update_user_account_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Activated';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Deactivate transactions
        # -------------------------------------------------------------

        # Deactivate interface setting
        case 'deactivate interface setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['interface_setting_id']) && !empty($_POST['interface_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Deactivate email setting
        case 'deactivate email setting':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['email_setting_id']) && !empty($_POST['email_setting_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Deactivate zoom api
        case 'deactivate zoom api':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['zoom_api_id']) && !empty($_POST['zoom_api_id'])){
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
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Deactivate user account
        case 'deactivate user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_id = $_POST['user_id'];
            
                        $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                        if($check_user_account_exist > 0){
                            $update_user_account_status = $api->update_user_account_status($user_id, 'Inactive', $username);
                
                            if($update_user_account_status){
                                echo 'Deactivated';
                            }
                            else{
                                echo $update_user_account_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Deactivate multiple user account
        case 'deactivate multiple user account':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                        $user_ids = $_POST['user_id'];
            
                        foreach($user_ids as $user_id){
                            $check_user_account_exist = $api->check_user_account_exist($user_id);
            
                            if($check_user_account_exist > 0){
                                $update_user_account_status = $api->update_user_account_status($user_id, 'Inactive', $username);
                                                
                                if(!$update_user_account_status){
                                    $error = $update_user_account_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Deactivated';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Archive transactions
        # -------------------------------------------------------------

        # Archive department
        case 'archive department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_id = $_POST['department_id'];
            
                        $check_department_exist = $api->check_department_exist($department_id);
            
                        if($check_department_exist > 0){
                            $update_department_status = $api->update_department_status($department_id, '2', $username);
                
                            if($update_department_status){
                                echo 'Archived';
                            }
                            else{
                                echo $update_department_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Archive multiple department
        case 'archive multiple department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_ids = $_POST['department_id'];
            
                        foreach($department_ids as $department_id){
                            $check_department_exist = $api->check_department_exist($department_id);
            
                            if($check_department_exist > 0){
                                $update_department_status = $api->update_department_status($department_id, '2', $username);
                                                
                                if(!$update_department_status){
                                    $error = $update_department_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Archived';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Archive work location
        case 'archive work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_id = $_POST['work_location_id'];
            
                        $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                        if($check_work_location_exist > 0){
                            $update_work_location_status = $api->update_work_location_status($work_location_id, '2', $username);
                
                            if($update_work_location_status){
                                echo 'Archived';
                            }
                            else{
                                echo $update_work_location_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Archive multiple work location
        case 'archive multiple work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_ids = $_POST['work_location_id'];
            
                        foreach($work_location_ids as $work_location_id){
                            $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                            if($check_work_location_exist > 0){
                                $update_work_location_status = $api->update_work_location_status($work_location_id, '2', $username);
                                                
                                if(!$update_work_location_status){
                                    $error = $update_work_location_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Archived';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Unarchive transactions
        # -------------------------------------------------------------

        # Unarchive department
        case 'unarchive department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_id = $_POST['department_id'];
            
                        $check_department_exist = $api->check_department_exist($department_id);
            
                        if($check_department_exist > 0){
                            $update_department_status = $api->update_department_status($department_id, '1', $username);
                
                            if($update_department_status){
                                echo 'Unarchived';
                            }
                            else{
                                echo $update_department_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Unarchive multiple department
        case 'unarchive multiple department':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                        $department_ids = $_POST['department_id'];
            
                        foreach($department_ids as $department_id){
                            $check_department_exist = $api->check_department_exist($department_id);
            
                            if($check_department_exist > 0){
                                $update_department_status = $api->update_department_status($department_id, '1', $username);
                                                
                                if(!$update_department_status){
                                    $error = $update_department_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Unarchived';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Unarchive work location
        case 'unarchive work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_id = $_POST['work_location_id'];
            
                        $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                        if($check_work_location_exist > 0){
                            $update_work_location_status = $api->update_work_location_status($work_location_id, '1', $username);
                
                            if($update_work_location_status){
                                echo 'Unarchived';
                            }
                            else{
                                echo $update_work_location_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # Unarchive multiple work location
        case 'unarchive multiple work location':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                        $work_location_ids = $_POST['work_location_id'];
            
                        foreach($work_location_ids as $work_location_id){
                            $check_work_location_exist = $api->check_work_location_exist($work_location_id);
            
                            if($check_work_location_exist > 0){
                                $update_work_location_status = $api->update_work_location_status($work_location_id, '1', $username);
                                                
                                if(!$update_work_location_status){
                                    $error = $update_work_location_status;
                                    break;
                                }
                            }
                            else{
                                $error = 'Not Found';
                                break;
                            }
                        }
            
                        if(empty($error)){
                            echo 'Unarchived';
                        }
                        else{
                            echo $error;
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Start transactions
        # -------------------------------------------------------------

        # Start job position recruitment
        case 'start job position recruitment':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                        $job_position_id = $_POST['job_position_id'];
            
                        $check_job_position_exist = $api->check_job_position_exist($job_position_id);
            
                        if($check_job_position_exist > 0){
                            $update_job_position_recruitment_status = $api->update_job_position_recruitment_status($job_position_id, '1', $username);
                
                            if($update_job_position_recruitment_status){
                                echo 'Started';
                            }
                            else{
                                echo $update_job_position_recruitment_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #   Stop transactions
        # -------------------------------------------------------------

        # Stop job position recruitment
        case 'stop job position recruitment':
            if(isset($_POST['username']) && !empty($_POST['username'])){
                $username = $_POST['username'];
                $check_user_account_status = $api->check_user_account_status($username);
    
                if($check_user_account_status){
                    if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                        $job_position_id = $_POST['job_position_id'];
            
                        $check_job_position_exist = $api->check_job_position_exist($job_position_id);
            
                        if($check_job_position_exist > 0){
                            $update_job_position_recruitment_status = $api->update_job_position_recruitment_status($job_position_id, '2', $username);
                
                            if($update_job_position_recruitment_status){
                                echo 'Stopped';
                            }
                            else{
                                echo $update_job_position_recruitment_status;
                            }
                        }
                        else{
                            echo 'Not Found';
                        }
                    }
                }
                else{
                    echo 'Inactive User';
                }
            }
        break;
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
        case 'module details':
            if(isset($_POST['module_id']) && !empty($_POST['module_id'])){
                $module_id = $_POST['module_id'];
                $module_details = $api->get_module_details($module_id);
                $module_icon_file_path = $module_details[0]['MODULE_ICON'] ?? null;
    
                if(empty($module_icon_file_path)){
                    $module_icon_file_path = $api->check_image($module_icon_file_path, 'module icon');
                }
    
                $response[] = array(
                    'MODULE_NAME' => $module_details[0]['MODULE_NAME'],
                    'MODULE_VERSION' => $module_details[0]['MODULE_VERSION'],
                    'MODULE_DESCRIPTION' => $module_details[0]['MODULE_DESCRIPTION'],
                    'MODULE_CATEGORY' => $module_details[0]['MODULE_CATEGORY'],
                    'MODULE_ICON' => '<img class="img-thumbnail" alt="module icon" width="200" src="'. $module_icon_file_path .'" data-holder-rendered="true">',
                    'DEFAULT_PAGE' => $module_details[0]['DEFAULT_PAGE'],
                    'TRANSACTION_LOG_ID' => $module_details[0]['TRANSACTION_LOG_ID'],
                    'ORDER_SEQUENCE' => $module_details[0]['ORDER_SEQUENCE']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Page details
        case 'page details':
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
        break;
        # -------------------------------------------------------------

        # Action details
        case 'action details':
            if(isset($_POST['action_id']) && !empty($_POST['action_id'])){
                $action_id = $_POST['action_id'];
                $action_details = $api->get_action_details($action_id);
    
                $response[] = array(
                    'ACTION_NAME' => $action_details[0]['ACTION_NAME'],
                    'TRANSACTION_LOG_ID' => $action_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # System parameter details
        case 'system parameter details':
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
        break;
        # -------------------------------------------------------------

        # Role details
        case 'role details':
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
        break;
        # -------------------------------------------------------------

        # System code details
        case 'system code details':
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
        break;
        # -------------------------------------------------------------

        # Upload setting details
        case 'upload setting details':
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
        break;
        # -------------------------------------------------------------

        # Company details
        case 'company details':
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
        break;
        # -------------------------------------------------------------

        # Interface setting details
        case 'interface setting details':
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
        break;
        # -------------------------------------------------------------

        # Email setting details
        case 'email setting details':
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
        break;
        # -------------------------------------------------------------

        # Notification setting details
        case 'notification setting details':
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
        break;
        # -------------------------------------------------------------

        # Country details
        case 'country details':
            if(isset($_POST['country_id']) && !empty($_POST['country_id'])){
                $country_id = $_POST['country_id'];
                $country_details = $api->get_country_details($country_id);
    
                $response[] = array(
                    'COUNTRY_NAME' => $country_details[0]['COUNTRY_NAME'],
                    'TRANSACTION_LOG_ID' => $country_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # State details
        case 'state details':
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
        break;
        # -------------------------------------------------------------

        # Zoom API details
        case 'zoom api details':
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
        break;
        # -------------------------------------------------------------

        # User account details
        case 'user account details':
            if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                $user_id = $_POST['user_id'];
                $user_account_details = $api->get_user_account_details($user_id);
                $password_expiry_date = $user_account_details[0]['PASSWORD_EXPIRY_DATE'];
    
                $password_expiry_date_difference = $api->get_date_difference($system_date, $password_expiry_date);
                $expiry_difference = 'Expiring in ' . $password_expiry_date_difference[0]['MONTHS'] . ' ' . $password_expiry_date_difference[0]['DAYS'];
                
                $response[] = array(
                    'FILE_AS' => $user_account_details[0]['FILE_AS'],
                    'PASSWORD' => $api->decrypt_data($user_account_details[0]['PASSWORD']),
                    'FAILED_LOGIN' => $user_account_details[0]['FAILED_LOGIN'],
                    'PASSWORD_EXPIRY_DATE' => $api->check_date('summary', $password_expiry_date, '', 'F d, Y', '', '', '') . ' (<span class="text-muted mb-0">'. $expiry_difference .'</span>)',
                    'LAST_CONNECTION_DATE' => $api->check_date('summary', $user_account_details[0]['LAST_CONNECTION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                    'LAST_FAILED_LOGIN' => $api->check_date('summary', $user_account_details[0]['LAST_FAILED_LOGIN'], '', 'F d, Y h:i:s a', '', '', ''),
                    'USER_STATUS' =>  $api->get_user_account_status($user_account_details[0]['USER_STATUS'])[0]['BADGE'],
                    'LOCK_STATUS' =>  $api->get_user_account_lock_status($user_account_details[0]['FAILED_LOGIN'])[0]['BADGE'],
                    'TRANSACTION_LOG_ID' => $user_account_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Department details
        case 'department details':
            if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                $department_id = $_POST['department_id'];
                $department_details = $api->get_department_details($department_id);
    
                $response[] = array(
                    'DEPARTMENT' => $department_details[0]['DEPARTMENT'],
                    'PARENT_DEPARTMENT' => $department_details[0]['PARENT_DEPARTMENT'],
                    'MANAGER' => $department_details[0]['MANAGER'],
                    'STATUS' =>  $api->get_department_status($department_details[0]['STATUS'])[0]['BADGE'],
                    'TRANSACTION_LOG_ID' => $department_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Job position details
        case 'job position details':
            if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                $job_position_id = $_POST['job_position_id'];
                $job_position_details = $api->get_job_position_details($job_position_id);
    
                $response[] = array(
                    'JOB_POSITION' => $job_position_details[0]['JOB_POSITION'],
                    'DESCRIPTION' => $job_position_details[0]['DESCRIPTION'],
                    'EXPECTED_NEW_EMPLOYEES' => $job_position_details[0]['EXPECTED_NEW_EMPLOYEES'],
                    'RECRUITMENT_STATUS' =>  $api->get_job_position_recruitment_status($job_position_details[0]['RECRUITMENT_STATUS'])[0]['BADGE'],
                    'DEPARTMENT' => $job_position_details[0]['DEPARTMENT'],
                    'TRANSACTION_LOG_ID' => $job_position_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Job position responsibility details
        case 'job position responsibility details':
            if(isset($_POST['responsibility_id']) && !empty($_POST['responsibility_id'])){
                $responsibility_id = $_POST['responsibility_id'];
                $job_position_responsibility_details = $api->get_job_position_responsibility_details($responsibility_id);
    
                $response[] = array(
                    'RESPONSIBILITY' => $job_position_responsibility_details[0]['RESPONSIBILITY']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Job position requirement details
        case 'job position requirement details':
            if(isset($_POST['requirement_id']) && !empty($_POST['requirement_id'])){
                $requirement_id = $_POST['requirement_id'];
                $job_position_requirement_details = $api->get_job_position_requirement_details($requirement_id);
    
                $response[] = array(
                    'REQUIREMENT' => $job_position_requirement_details[0]['REQUIREMENT']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Job position qualification details
        case 'job position qualification details':
            if(isset($_POST['qualification_id']) && !empty($_POST['qualification_id'])){
                $qualification_id = $_POST['qualification_id'];
                $job_position_qualification_details = $api->get_job_position_qualification_details($qualification_id);
    
                $response[] = array(
                    'QUALIFICATION' => $job_position_qualification_details[0]['QUALIFICATION']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Job position attachment details
        case 'job position attachment details':
            if(isset($_POST['attachment_id']) && !empty($_POST['attachment_id'])){
                $attachment_id = $_POST['attachment_id'];
                $job_position_attachment_details = $api->get_job_position_attachment_details($attachment_id);
    
                $response[] = array(
                    'ATTACHMENT_NAME' => $job_position_attachment_details[0]['ATTACHMENT_NAME']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Work location details
        case 'work location details':
            if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
                $work_location_id = $_POST['work_location_id'];
                $work_location_details = $api->get_work_location_details($work_location_id);
    
                $response[] = array(
                    'WORK_LOCATION' => $work_location_details[0]['WORK_LOCATION'],
                    'WORK_LOCATION_ADDRESS' => $work_location_details[0]['WORK_LOCATION_ADDRESS'],
                    'EMAIL' => $work_location_details[0]['EMAIL'],
                    'TELEPHONE' => $work_location_details[0]['TELEPHONE'],
                    'MOBILE' => $work_location_details[0]['MOBILE'],
                    'LOCATION_NUMBER' => $work_location_details[0]['LOCATION_NUMBER'],
                    'STATUS' =>  $api->get_work_location_status($work_location_details[0]['STATUS'])[0]['BADGE'],
                    'TRANSACTION_LOG_ID' => $work_location_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Departure reason details
        case 'departure reason details':
            if(isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])){
                $departure_reason_id = $_POST['departure_reason_id'];
                $departure_reason_details = $api->get_departure_reason_details($departure_reason_id);
    
                $response[] = array(
                    'DEPARTURE_REASON' => $departure_reason_details[0]['DEPARTURE_REASON'],
                    'TRANSACTION_LOG_ID' => $departure_reason_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Employee type details
        case 'employee type details':
            if(isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])){
                $employee_type_id = $_POST['employee_type_id'];
                $employee_type_details = $api->get_employee_type_details($employee_type_id);
    
                $response[] = array(
                    'EMPLOYEE_TYPE' => $employee_type_details[0]['EMPLOYEE_TYPE'],
                    'TRANSACTION_LOG_ID' => $employee_type_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # Wage type details
        case 'wage type details':
            if(isset($_POST['wage_type_id']) && !empty($_POST['wage_type_id'])){
                $wage_type_id = $_POST['wage_type_id'];
                $wage_type_details = $api->get_wage_type_details($wage_type_id);
    
                $response[] = array(
                    'WAGE_TYPE' => $wage_type_details[0]['WAGE_TYPE'],
                    'TRANSACTION_LOG_ID' => $wage_type_details[0]['TRANSACTION_LOG_ID']
                );
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>