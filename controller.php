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
    
                    if($insert_module){
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
    
                if($insert_page){
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
    
                if($insert_action){
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
    
                if($insert_system_parameter){
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

    # -------------------------------------------------------------
    #   Unlock transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Lock transactions
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Activate transactions
    # -------------------------------------------------------------
     
    # -------------------------------------------------------------
    #   Deactivate transactions
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

}

?>