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
                                        echo 'Updated';
                                    }
                                    else{
                                        echo $update_module;
                                    }
                                }
                                else{
                                    echo $update_module_icon;
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
                    $update_module = $api->update_module($module_id, $module_name, $module_version, $module_description, $module_category, $username);

                    if($update_module){
                        echo 'Updated';
                    }
                    else{
                        echo $update_module;
                    }
                }
            }
            else{
                if(!empty($module_icon_tmp_name)){
                    if(in_array($module_icon_actual_ext, $allowed_ext)){
                        if(!$module_icon_error){
                            if($module_icon_size < $file_max_size){
                                $insert_module = $api->insert_module($module_icon_tmp_name, $module_icon_actual_ext, $module_name, $module_version, $module_description, $module_category, $username);
    
                                if($insert_module){
                                    echo 'Inserted';
                                }
                                else{
                                    echo $insert_module;
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
                    $insert_module = $api->insert_module(null, null, $module_name, $module_version, $module_description, $module_category, $username);
    
                    if($insert_module){
                        echo 'Inserted';
                    }
                    else{
                        echo $insert_module;
                    }
                }
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete transactions
    # -------------------------------------------------------------

    # Delete module
    else if($transaction == 'delete module'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            $username = $_POST['username'];
            $job_position_id = $_POST['job_position_id'];

            $check_module_exist = $api->check_module_exist($job_position_id);

            if($check_module_exist > 0){
                $delete_job_position = $api->delete_job_position($job_position_id, $username);
                                    
                if($delete_job_position){
                    echo 'Deleted';
                }
                else{
                    echo $delete_job_position;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['module_id'])){
            $username = $_POST['username'];
            $module_ids = $_POST['module_id'];

            foreach($module_ids as $module_id){
                $check_module_exist = $api->check_module_exist($module_id);

                if($check_module_exist > 0){
                    $delete_module = $api->delete_module($module_id, $username);
                                    
                    if(!$delete_module){
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
                'MODULE_CATEGORY' => $module_details[0]['MODULE_CATEGORY']
            );

            echo json_encode($response);
        }
    }

}

?>