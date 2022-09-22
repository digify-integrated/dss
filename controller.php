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

    # Submit policy
    else if($transaction == 'submit policy'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['policy_id']) && isset($_POST['policy']) && !empty($_POST['policy']) && isset($_POST['policy_description']) && !empty($_POST['policy_description'])){
            $username = $_POST['username'];
            $policy_id = $_POST['policy_id'];
            $policy = $_POST['policy'];
            $policy_description = $_POST['policy_description'];

            $check_policy_exist = $api->check_policy_exist($policy_id);

            if($check_policy_exist > 0){
                $update_policy = $api->update_policy($policy_id, $policy, $policy_description, $username);

                if($update_policy){
                    echo 'Updated';
                }
                else{
                    echo $update_policy;
                }
            }
            else{
                $insert_policy = $api->insert_policy($policy, $policy_description, $username);

                if($insert_policy){
                    echo 'Inserted';
                }
                else{
                    echo $insert_policy;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit permission
    else if($transaction == 'submit permission'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['permission_id']) && isset($_POST['policy_id']) && !empty($_POST['policy_id']) && isset($_POST['permission']) && !empty($_POST['permission'])){
            $username = $_POST['username'];
            $permission_id = $_POST['permission_id'];
            $policy_id = $_POST['policy_id'];
            $permission = $_POST['permission'];

            $check_permission_exist = $api->check_permission_exist($permission_id);

            if($check_permission_exist > 0){
                $update_permission = $api->update_permission($permission_id, $policy_id, $permission, $username);

                if($update_permission){
                    echo 'Updated';
                }
                else{
                    echo $update_permission;
                }
            }
            else{
                $insert_permission = $api->insert_permission($policy_id, $permission, $username);

                if($insert_permission){
                    echo 'Inserted';
                }
                else{
                    echo $insert_permission;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit role
    else if($transaction == 'submit role'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && isset($_POST['role']) && !empty($_POST['role']) && isset($_POST['role_description']) && !empty($_POST['role_description'])){
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $role = $_POST['role'];
            $role_description = $_POST['role_description'];

            $check_role_exist = $api->check_role_exist($role_id);

            if($check_role_exist > 0){
                $update_role = $api->update_role($role_id, $role, $role_description, $username);

                if($update_role){
                    echo 'Updated';
                }
                else{
                    echo $update_role;
                }
            }
            else{
                $insert_role = $api->insert_role($role, $role_description, $username);

                if($insert_role){
                    echo 'Inserted';
                }
                else{
                    echo $insert_role;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit role permission
    else if($transaction == 'submit role permission'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id']) && !empty($_POST['role_id']) && isset($_POST['permission'])){
            $error = '';
            $username = $_POST['username'];
            $role_id = $_POST['role_id'];
            $permissions = explode(',', $_POST['permission']);
            $role_details = $api->get_role_details($role_id);
            $transaction_log_id = $role_details[0]['TRANSACTION_LOG_ID'];

            $check_role_exist = $api->check_role_exist($role_id);

            if($check_role_exist){
                $delete_permission_role = $api->delete_permission_role($role_id, $username);

                if($delete_permission_role){
                    foreach($permissions as $permission){
                        $insert_permission_role = $api->insert_permission_role($role_id, $permission, $username);

                        if(!$insert_permission_role){
                            $error = $insert_permission_role;
                            break;
                        }
                    }

                    if(empty($error)){
                        $insert_transaction_log = $api->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated role permission.');
                                    
                        if($insert_transaction_log){
                            echo 'Updated';
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        echo $error;
                    }
                }
                else{
                    echo $delete_permission_role;
                }
            }
            else{
               echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Submit user account
    else if($transaction == 'submit user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['file_as']) && !empty($_POST['file_as']) && isset($_POST['user_code']) && !empty($_POST['user_code']) && isset($_POST['password']) && isset($_POST['related_employee']) && isset($_POST['role'])){
            $username = $_POST['username'];
            $file_as = $_POST['file_as'];
            $user_code = $_POST['user_code'];
            $password = $_POST['password'];
            $related_employee = $_POST['related_employee'];
            $roles = explode(',', $_POST['role']);
            $password_expiry_date = $api->format_date('Y-m-d', $system_date, '+6 months');

            if(!empty($password)){
                $password = $api->encrypt_data($password);
            }

            $check_user_account_exist = $api->check_user_account_exist($user_code);

            if($check_user_account_exist > 0){
                $update_user_account = $api->update_user_account($user_code, $password, $file_as, $password_expiry_date, $username);

                if($update_user_account){
                    $delete_all_employee_related_user = $api->delete_all_employee_related_user($user_code, $username);

                    if($delete_all_employee_related_user){
                        if(!empty($related_employee)){
                            $update_employee_related_user = $api->update_employee_related_user($related_employee, $user_code, $username);

                            if(!$update_employee_related_user){
                                $error = $update_employee_related_user;
                            }
                        }
                    }
                    else{
                        $error = $delete_all_user_account_role;
                    }

                    $delete_all_user_account_role = $api->delete_all_user_account_role($user_code);

                    if($delete_all_user_account_role){
                        foreach($roles as $role){
                            $insert_user_account_role = $api->insert_user_account_role($user_code, $role, $username);

                            if(!$insert_user_account_role){
                                $error = $insert_user_account_role;
                                break;
                            }
                        }
                    }
                    else{
                        $error = $delete_all_user_account_role;
                    }                    
                }
                else{
                    $error = $update_user_account;
                }

                if(empty($error)){
                    echo 'Updated';
                }
                else{
                    echo $error;
                }
            }
            else{
                $insert_user_account = $api->insert_user_account($user_code, $password, $file_as, $password_expiry_date, $username);

                if($insert_user_account){
                    foreach($roles as $role){
                        $insert_user_account_role = $api->insert_user_account_role($user_code, $role, $username);

                        if(!$insert_user_account_role){
                            $error = $insert_user_account_role;
                            break;
                        }
                    }

                    if(empty($error)){
                        echo 'Inserted';
                    }
                    else{
                        echo $error;
                    }
                }
                else{
                    $error = $insert_user_account;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit system parameter
    else if($transaction == 'submit system parameter'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['parameter_id']) && isset($_POST['parameter']) && !empty($_POST['parameter']) && isset($_POST['parameter_description']) && !empty($_POST['parameter_description']) && isset($_POST['extension']) && isset($_POST['parameter_number'])){
            $username = $_POST['username'];
            $parameter_id = $_POST['parameter_id'];
            $parameter = $_POST['parameter'];
            $parameter_description = $_POST['parameter_description'];
            $extension = $_POST['extension'];
            $parameter_number = $api->check_number($_POST['parameter_number']);

            $check_system_parameter_exist = $api->check_system_parameter_exist($parameter_id);

            if($check_system_parameter_exist > 0){
                $update_system_parameter = $api->update_system_parameter($parameter_id, $parameter, $parameter_description, $extension, $parameter_number, $username);
                                        
                if($update_system_parameter){
                    echo 'Updated';
                }
                else{
                    echo $update_system_parameter;
                }
            }
            else{
                $insert_system_parameter = $api->insert_system_parameter($parameter, $parameter_description, $extension, $parameter_number, $username);
                        
                if($insert_system_parameter){
                    echo 'Inserted';
                }
                else{
                    echo $insert_system_parameter;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit system code
    else if($transaction == 'submit system code'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_type']) && !empty($_POST['system_type']) && isset($_POST['system_code']) && !empty($_POST['system_code']) && isset($_POST['system_description']) && !empty($_POST['system_description'])){
            $username = $_POST['username'];
            $system_type = $_POST['system_type'];
            $system_code = $_POST['system_code'];
            $system_description = $_POST['system_description'];

            $check_system_code_exist = $api->check_system_code_exist($system_type, $system_code);
            
            if($check_system_code_exist > 0){
                $update_system_code = $api->update_system_code($system_type, $system_code, $system_description, $username);
                                    
                if($update_system_code){
                    echo 'Updated';
                }
                else{
                    echo $update_system_code;
                }
            }
            else{
                $insert_system_code = $api->insert_system_code($system_type, $system_code, $system_description, $username);
                        
                if($insert_system_code){
                    echo 'Inserted';
                }
                else{
                    echo $insert_system_code;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit upload setting
    else if($transaction == 'submit upload setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id']) && isset($_POST['upload_setting']) && !empty($_POST['upload_setting']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['max_file_size']) && !empty($_POST['max_file_size']) && isset($_POST['file_type']) && !empty($_POST['file_type'])){
            $error = '';
            $username = $_POST['username'];
            $upload_setting_id = $_POST['upload_setting_id'];
            $upload_setting = $_POST['upload_setting'];
            $description = $_POST['description'];
            $max_file_size = $api->remove_comma($_POST['max_file_size']);
            $file_types = explode(',', $_POST['file_type']);

            $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);

            if($check_upload_setting_exist > 0){
                $update_upload_setting = $api->update_upload_setting($upload_setting_id, $upload_setting, $description, $max_file_size, $username);

                if($update_upload_setting){
                    $delete_all_upload_file_type = $api->delete_all_upload_file_type($upload_setting_id, $username);

                    if($delete_all_upload_file_type){
                        foreach($file_types as $file_type){
                            $insert_upload_file_type = $api->insert_upload_file_type($upload_setting_id, $file_type, $username);

                            if(!$insert_upload_file_type){
                                $error = $insert_upload_file_type;
                                break;
                            }
                        }
                    }
                    else{
                        $error = $delete_all_upload_file_type;
                    }

                    if(empty($error)){
                        echo 'Updated';
                    }
                    else{
                        echo $error;
                    }
                }
                else{
                    echo $update_upload_setting;
                }
            }
            else{
                $insert_upload_setting = $api->insert_upload_setting($upload_setting, $description, $max_file_size, $file_types, $username);

                if($insert_upload_setting){
                    echo 'Inserted';
                }
                else{
                    echo $insert_upload_setting;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit company
    else if($transaction == 'submit company'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['company_id']) && isset($_POST['company_name']) && !empty($_POST['company_name']) && isset($_POST['street_1']) && isset($_POST['street_2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip_code']) && isset($_POST['tax_id']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['telephone']) && isset($_POST['website'])){
            $file_type = '';
            $username = $_POST['username'];
            $company_id = $_POST['company_id'];
            $company_name = $_POST['company_name'];
            $street_1 = $_POST['street_1'];
            $street_2 = $_POST['street_2'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zip_code'];
            $tax_id = $_POST['tax_id'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $telephone = $_POST['telephone'];
            $website = $_POST['website'];

            $state_details = $api->get_state_details($state);
            $country_id = $state_details[0]['COUNTRY_ID'] ?? null;

            $company_logo_name = $_FILES['company_logo']['name'];
            $company_logo_size = $_FILES['company_logo']['size'];
            $company_logo_error = $_FILES['company_logo']['error'];
            $company_logo_tmp_name = $_FILES['company_logo']['tmp_name'];
            $company_logo_ext = explode('.', $company_logo_name);
            $company_logo_actual_ext = strtolower(end($company_logo_ext));

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

            $check_company_exist = $api->check_company_exist($company_id);
 
            if($check_company_exist > 0){
                if(!empty($company_logo_tmp_name)){
                    if(in_array($company_logo_actual_ext, $allowed_ext)){
                        if(!$company_logo_error){
                            if($company_logo_size < $file_max_size){
                                $update_company_logo = $api->update_company_logo($company_logo_tmp_name, $company_logo_actual_ext, $company_id, $username);
        
                                if($update_company_logo){
                                    $update_company = $api->update_company($company_id, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);

                                    if($update_company){
                                        echo 'Updated';
                                    }
                                    else{
                                        echo $update_company;
                                    }
                                }
                                else{
                                    echo $update_company_logo;
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
                    $update_company = $api->update_company($company_id, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);

                    if($update_company){
                        echo 'Updated';
                    }
                    else{
                        echo $update_company;
                    }
                }
            }
            else{
                if(!empty($company_logo_tmp_name)){
                    if(in_array($company_logo_actual_ext, $allowed_ext)){
                        if(!$company_logo_error){
                            if($company_logo_size < $file_max_size){
                                $insert_company = $api->insert_company($company_logo_tmp_name, $company_logo_actual_ext, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);
    
                                if($insert_company){
                                    echo 'Inserted';
                                }
                                else{
                                    echo $insert_company;
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
                    $insert_company = $api->insert_company(null, null, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);
    
                    if($insert_company){
                        echo 'Inserted';
                    }
                    else{
                        echo $insert_company;
                    }
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit country
    else if($transaction == 'submit country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id']) && isset($_POST['country_name']) && !empty($_POST['country_name'])){
            $username = $_POST['username'];
            $country_id = $_POST['country_id'];
            $country_name = $_POST['country_name'];

            $check_country_exist = $api->check_country_exist($country_id);

            if($check_country_exist > 0){
                $update_country = $api->update_country($country_id, $country_name, $username);

                if($update_country){
                    echo 'Updated';
                }
                else{
                    echo $update_country;
                }
            }
            else{
                $insert_country = $api->insert_country($country_name, $username);

                if($insert_country){
                    echo 'Inserted';
                }
                else{
                    echo $insert_country;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit state
    else if($transaction == 'submit state'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_id']) && isset($_POST['state_name']) && !empty($_POST['state_name']) && isset($_POST['country']) && !empty($_POST['country'])){
            $username = $_POST['username'];
            $state_id = $_POST['state_id'];
            $state_name = $_POST['state_name'];
            $country = $_POST['country'];

            $check_state_exist = $api->check_state_exist($state_id);

            if($check_state_exist > 0){
                $update_state = $api->update_state($state_id, $state_name, $country, $username);

                if($update_state){
                    echo 'Updated';
                }
                else{
                    echo $update_state;
                }
            }
            else{
                $insert_state = $api->insert_state($state_name, $country, $username);

                if($insert_state){
                    echo 'Inserted';
                }
                else{
                    echo $insert_state;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit notification setting
    else if($transaction == 'submit notification setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && isset($_POST['notification_setting']) && !empty($_POST['notification_setting']) && isset($_POST['notification_setting_description']) && !empty($_POST['notification_setting_description'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_setting = $_POST['notification_setting'];
            $notification_setting_description = $_POST['notification_setting_description'];
            $notification_channels = explode(',', $_POST['notification_channel']);

            $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);

            if($check_notification_setting_exist > 0){
                $update_notification_setting = $api->update_notification_setting($notification_setting_id, $notification_setting, $notification_setting_description, $username);

                if($update_notification_setting){
                    $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                    
                    if($delete_all_notification_channel){
                        foreach($notification_channels as $notification_channel){
                            $insert_notification_channel = $api->insert_notification_channel($notification_setting_id, $notification_channel, $username);
        
                            if(!$insert_notification_channel){
                                $error = $insert_notification_channel;
                                break;
                            }
                        }
                    }
                    else{
                        $error = $delete_all_notification_channel;
                    }

                    if(empty($error)){
                        echo 'Updated';
                    }
                    else{
                        echo $error;
                    }
                }
                else{
                    echo $update_notification_setting;
                }
            }
            else{
                $insert_notification_setting = $api->insert_notification_setting($notification_setting, $notification_setting_description, $notification_channels, $username);

                if($insert_notification_setting){
                    echo 'Inserted';
                }
                else{
                    echo $insert_notification_setting;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit notification template
    else if($transaction == 'submit notification template'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && isset($_POST['notification_title']) && !empty($_POST['notification_title']) && isset($_POST['notification_message']) && !empty($_POST['notification_message']) && isset($_POST['system_link']) && !empty($_POST['system_link']) && isset($_POST['email_link']) && !empty($_POST['email_link']) && isset($_POST['role_recipient']) && isset($_POST['user_account_recipient'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_title = $_POST['notification_title'];
            $notification_message = $_POST['notification_message'];
            $system_link = $_POST['system_link'];
            $email_link = $_POST['email_link'];

            $role_recipients = explode(',', $_POST['role_recipient']);
            $user_account_recipients = explode(',', $_POST['user_account_recipient']);

            $check_notification_template_exist = $api->check_notification_template_exist($notification_setting_id);

            if($check_notification_template_exist > 0){
                $update_notification_template = $api->update_notification_template($notification_setting_id, $notification_title, $notification_message, $system_link, $email_link, $username);

                if($update_notification_template){
                    $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                    
                    if($delete_all_notification_user_account_recipient){
                        $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                    
                        if($delete_all_notification_role_recipient){
                            foreach($role_recipients as $role_id){
                                $insert_notification_role_recipient = $api->insert_notification_role_recipient($notification_setting_id, $role_id, $username);
        
                                if(!$insert_notification_role_recipient){
                                    $error = $insert_notification_role_recipient;
                                    break;
                                }
                            }
        
                            foreach($user_account_recipients as $user_account){
                                $insert_notification_user_account_recipient = $api->insert_notification_user_account_recipient($notification_setting_id, $user_account, $username);
        
                                if(!$insert_notification_user_account_recipient){
                                    $error = $insert_notification_user_account_recipient;
                                    break;
                                }
                            }
                        }
                        else{
                            $error = $delete_all_notification_role_recipient;
                        }
                    }
                    else{
                        $error = $delete_all_notification_user_account_recipient;
                    }
                }
                else{
                    $error = $update_notification_template;
                }

                if(empty($error)){
                    echo 'Updated';
                }
                else{
                    echo $error;
                }
            }
            else{
                $insert_notification_template = $api->insert_notification_template($notification_setting_id, $notification_title, $notification_message, $system_link, $email_link, $username);

                if($insert_notification_template){
                    foreach($role_recipients as $role_id){
                        $insert_notification_role_recipient = $api->insert_notification_role_recipient($notification_setting_id, $role_id, $username);

                        if(!$insert_notification_role_recipient){
                            $error = $insert_notification_role_recipient;
                            break;
                        }
                    }

                    foreach($user_account_recipients as $user_account){
                        $insert_notification_user_account_recipient = $api->insert_notification_user_account_recipient($notification_setting_id, $user_account, $username);

                        if(!$insert_notification_user_account_recipient){
                            $error = $insert_notification_user_account_recipient;
                            break;
                        }
                    }
                }
                else{
                    $error = $insert_notification_template;
                }

                if(empty($error)){
                    echo 'Inserted';
                }
                else{
                    echo $error;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit interface setting
    else if($transaction == 'submit interface setting'){
        if(isset($_POST['username']) && !empty($_POST['username'])){
            $username = $_POST['username'];
            $interface_setting_id = 1;

            $check_interface_settings_exist = $api->check_interface_settings_exist($interface_setting_id);

            if($check_interface_settings_exist > 0){
                $login_background_upload = $api->time_interface_upload($_FILES['login_background'], 'login background', $interface_setting_id, $username);

                if($login_background_upload){
                    $login_logo_upload = $api->time_interface_upload($_FILES['login_logo'], 'login logo', $interface_setting_id, $username);

                    if($login_logo_upload){
                        $menu_logo_upload = $api->time_interface_upload($_FILES['menu_logo'], 'menu logo', $interface_setting_id, $username);

                        if($menu_logo_upload){
                            $menu_icon_upload = $api->time_interface_upload($_FILES['menu_icon'], 'menu icon', $interface_setting_id, $username);

                            if($menu_icon_upload){
                                $favicon_upload = $api->time_interface_upload($_FILES['favicon'], 'favicon', $interface_setting_id, $username);

                                if($favicon_upload){
                                    echo 'Updated';
                                }
                                else{
                                    echo $favicon_upload;
                                }
                            }
                            else{
                                echo $menu_icon_upload;
                            }
                        }
                        else{
                            echo $menu_logo_upload;
                        }
                    }
                    else{
                        echo $login_logo_upload;
                    }
                }
                else{
                    echo $login_background_upload;
                }
            }
            else{
                $insert_interface_settings = $api->insert_interface_settings($interface_setting_id, $username);

                if($insert_interface_settings){
                    $login_background_upload = $api->time_interface_upload($_FILES['login_background'], 'login background', $interface_setting_id, $username);

                    if($login_background_upload){
                        $login_logo_upload = $api->time_interface_upload($_FILES['login_logo'], 'login logo', $interface_setting_id, $username);

                        if($login_logo_upload){
                            $menu_logo_upload = $api->time_interface_upload($_FILES['menu_logo'], 'menu logo', $interface_setting_id, $username);

                            if($menu_logo_upload){
                                $menu_icon_upload = $api->time_interface_upload($_FILES['menu_icon'], 'menu icon', $interface_setting_id, $username);

                                if($menu_icon_upload){
                                    $favicon_upload = $api->time_interface_upload($_FILES['favicon'], 'logo icon dark', $interface_setting_id, $username);

                                    if($favicon_upload){
                                        echo 'Updated';
                                    }
                                    else{
                                        echo $favicon_upload;
                                    }
                                }
                                else{
                                    echo $menu_icon_upload;
                                }
                            }
                            else{
                                echo $menu_logo_upload;
                            }
                        }
                        else{
                            echo $login_logo_upload;
                        }
                    }
                    else{
                        echo $login_background_upload;
                    }
                }
                else{
                    echo $insert_interface_settings;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit mail configuration
    else if($transaction == 'submit mail configuration'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['mail_host']) && !empty($_POST['mail_host']) && isset($_POST['port']) && !empty($_POST['port']) && isset($_POST['smtp_auth']) && isset($_POST['smtp_auto_tls']) && isset($_POST['mail_user']) && !empty($_POST['mail_user']) && isset($_POST['mail_password']) && isset($_POST['mail_encryption']) && !empty($_POST['mail_encryption']) && isset($_POST['mail_from_name']) && !empty($_POST['mail_from_name']) && isset($_POST['mail_from_email']) && !empty($_POST['mail_from_email'])){
            $username = $_POST['username'];
            $mail_configuration_id = 1;
            $mail_host = $_POST['mail_host'];
            $port = $_POST['port'];
            $smtp_auth = $_POST['smtp_auth'];
            $smtp_auto_tls = $_POST['smtp_auto_tls'];
            $mail_user = $_POST['mail_user'];
            $mail_password = $api->encrypt_data($_POST['mail_password']);
            $mail_encryption = $_POST['mail_encryption'];
            $mail_from_name = $_POST['mail_from_name'];
            $mail_from_email = $_POST['mail_from_email'];

            $check_mail_configuration_exist = $api->check_mail_configuration_exist($mail_configuration_id);

            if($check_mail_configuration_exist > 0){
                $update_mail_configuration = $api->update_mail_configuration($mail_configuration_id, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_user, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username);

                if($update_mail_configuration){
                    echo 'Updated';
                }
                else{
                    echo $update_mail_configuration;
                }
            }
            else{
                $insert_mail_configuration = $api->insert_mail_configuration($mail_configuration_id, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_user, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username);

                if($insert_mail_configuration){
                    echo 'Updated';
                }
                else{
                    echo $insert_mail_configuration;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit zoom integration
    else if($transaction == 'submit zoom integration'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['api_key']) && !empty($_POST['api_key']) && isset($_POST['api_secret']) && !empty($_POST['api_secret'])){
            $username = $_POST['username'];
            $zoom_integration_id = 1;
            $api_key = $_POST['api_key'];
            $api_secret = $_POST['api_secret'];

            $check_zoom_integration_exist = $api->check_zoom_integration_exist($zoom_integration_id);

            if($check_zoom_integration_exist > 0){
                $update_zoom_integration = $api->update_zoom_integration($zoom_integration_id, $api_key, $api_secret, $username);

                if($update_zoom_integration){
                    echo 'Updated';
                }
                else{
                    echo $update_zoom_integration;
                }
            }
            else{
                $insert_zoom_integration = $api->insert_zoom_integration($zoom_integration_id, $api_key, $api_secret, $username);

                if($insert_zoom_integration){
                    echo 'Updated';
                }
                else{
                    echo $insert_zoom_integration;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit department
    else if($transaction == 'submit department'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['department_id']) && isset($_POST['department']) && !empty($_POST['department']) && isset($_POST['parent_department']) && isset($_POST['manager'])){
            $username = $_POST['username'];
            $department_id = $_POST['department_id'];
            $department = $_POST['department'];
            $parent_department = $_POST['parent_department'];
            $manager = $_POST['manager'];

            $check_department_exist = $api->check_department_exist($department_id);

            if($check_department_exist > 0){
                $update_department = $api->update_department($department_id, $department, $parent_department, $manager, $username);

                if($update_department){
                    echo 'Updated';
                }
                else{
                    echo $update_department;
                }
            }
            else{
                $insert_department = $api->insert_department($department, $parent_department, $manager, $username);

                if($insert_department){
                    echo 'Inserted';
                }
                else{
                    echo $insert_department;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit job position
    else if($transaction == 'submit job position'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['job_position_id']) && isset($_POST['job_position']) && !empty($_POST['job_position'])){
            $file_type = '';
            $username = $_POST['username'];
            $job_position_id = $_POST['job_position_id'];
            $job_position = $_POST['job_position'];

            $job_description_name = $_FILES['job_description']['name'];
            $job_description_size = $_FILES['job_description']['size'];
            $job_description_error = $_FILES['job_description']['error'];
            $job_description_tmp_name = $_FILES['job_description']['tmp_name'];
            $job_description_ext = explode('.', $job_description_name);
            $job_description_actual_ext = strtolower(end($job_description_ext));

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

            $check_job_position_exist = $api->check_job_position_exist($job_position_id);
 
            if($check_job_position_exist > 0){
                if(!empty($job_description_tmp_name)){
                    if(in_array($job_description_actual_ext, $allowed_ext)){
                        if(!$job_description_error){
                            if($job_description_size < $file_max_size){
                                $update_job_description = $api->update_job_description($job_description_tmp_name, $job_description_actual_ext, $job_position_id, $username);
        
                                if($update_job_description){
                                    $update_job_position = $api->update_job_position($job_position_id, $job_position, $username);

                                    if($update_job_position){
                                        echo 'Updated';
                                    }
                                    else{
                                        echo $update_job_position;
                                    }
                                }
                                else{
                                    echo $update_job_description;
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
                    $update_job_position = $api->update_job_position($job_position_id, $job_position, $username);

                    if($update_job_position){
                        echo 'Updated';
                    }
                    else{
                        echo $update_job_position;
                    }
                }
            }
            else{
                if(!empty($job_description_tmp_name)){
                    if(in_array($job_description_actual_ext, $allowed_ext)){
                        if(!$job_description_error){
                            if($job_description_size < $file_max_size){
                                $insert_job_position = $api->insert_job_position($job_description_tmp_name, $job_description_actual_ext, $job_position, $username);
    
                                if($insert_job_position){
                                    echo 'Inserted';
                                }
                                else{
                                    echo $insert_job_position;
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
                    $insert_job_position = $api->insert_job_position(null, null, $job_position, $username);
    
                    if($insert_job_position){
                        echo 'Inserted';
                    }
                    else{
                        echo $insert_job_position;
                    }
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit work location
    else if($transaction == 'submit work location'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['work_location_id']) && isset($_POST['work_location']) && !empty($_POST['work_location']) && isset($_POST['street_1']) && isset($_POST['street_2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip_code']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['telephone'])){
            $username = $_POST['username'];
            $work_location_id = $_POST['work_location_id'];
            $work_location = $_POST['work_location'];
            $street_1 = $_POST['street_1'];
            $street_2 = $_POST['street_2'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zip_code'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $telephone = $_POST['telephone'];

            $state_details = $api->get_state_details($state);
            $country_id = $state_details[0]['COUNTRY_ID'] ?? null;
          
            $check_work_location_exist = $api->check_work_location_exist($work_location_id);
 
            if($check_work_location_exist > 0){
                $update_work_location = $api->update_work_location($work_location_id, $work_location, $email, $telephone, $mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);

                if($update_work_location){
                    echo 'Updated';
                }
                else{
                    echo $update_work_location;
                }
            }
            else{
                $insert_work_location = $api->insert_work_location($work_location, $email, $telephone, $mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username);
    
                if($insert_work_location){
                    echo 'Inserted';
                }
                else{
                    echo $insert_work_location;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit departure reason
    else if($transaction == 'submit departure reason'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['departure_reason_id']) && isset($_POST['departure_reason']) && !empty($_POST['departure_reason'])){
            $username = $_POST['username'];
            $departure_reason_id = $_POST['departure_reason_id'];
            $departure_reason = $_POST['departure_reason'];
          
            $check_departure_reason_exist = $api->check_departure_reason_exist($departure_reason_id);
 
            if($check_departure_reason_exist > 0){
                $update_departure_reason = $api->update_departure_reason($departure_reason_id, $departure_reason, $username);

                if($update_departure_reason){
                    echo 'Updated';
                }
                else{
                    echo $update_departure_reason;
                }
            }
            else{
                $insert_departure_reason = $api->insert_departure_reason($departure_reason, $username);
    
                if($insert_departure_reason){
                    echo 'Inserted';
                }
                else{
                    echo $insert_departure_reason;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit employee type
    else if($transaction == 'submit employee type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_type_id']) && isset($_POST['employee_type']) && !empty($_POST['employee_type'])){
            $username = $_POST['username'];
            $employee_type_id = $_POST['employee_type_id'];
            $employee_type = $_POST['employee_type'];
          
            $check_employee_type_exist = $api->check_employee_type_exist($employee_type_id);
 
            if($check_employee_type_exist > 0){
                $update_employee_type = $api->update_employee_type($employee_type_id, $employee_type, $username);

                if($update_employee_type){
                    echo 'Updated';
                }
                else{
                    echo $update_employee_type;
                }
            }
            else{
                $insert_employee_type = $api->insert_employee_type($employee_type, $username);
    
                if($insert_employee_type){
                    echo 'Inserted';
                }
                else{
                    echo $insert_employee_type;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit employee
    else if($transaction == 'submit employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && isset($_POST['first_name']) && !empty($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['last_name']) && !empty($_POST['last_name']) && isset($_POST['suffix']) && isset($_POST['job_position']) && isset($_POST['department']) && isset($_POST['manager']) && isset($_POST['coach']) && isset($_POST['company']) && isset($_POST['work_location']) && isset($_POST['employee_type']) && isset($_POST['working_hours']) && isset($_POST['work_email']) && isset($_POST['work_mobile']) && isset($_POST['work_telephone']) && isset($_POST['badge_id']) && isset($_POST['onboard_date']) && isset($_POST['permanency_date']) && isset($_POST['sss']) && isset($_POST['tin']) && isset($_POST['philhealth']) && isset($_POST['pagibig']) && isset($_POST['street_1']) && isset($_POST['street_2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip_code']) && isset($_POST['personal_email']) && isset($_POST['personal_mobile']) && isset($_POST['personal_telephone']) && isset($_POST['bank_account_number']) && isset($_POST['home_work_distance']) && isset($_POST['gender']) && isset($_POST['marital_status']) && isset($_POST['spouse_name']) && isset($_POST['spouse_birthday']) && isset($_POST['emergency_contact']) && isset($_POST['emergency_phone']) && isset($_POST['certificate_level']) && isset($_POST['field_of_study']) && isset($_POST['school']) && isset($_POST['identification_number']) && isset($_POST['passport_number']) && isset($_POST['birthday']) && isset($_POST['place_of_birth']) && isset($_POST['number_of_children']) && isset($_POST['nationality']) && isset($_POST['visa_number']) && isset($_POST['visa_expiry_date']) && isset($_POST['work_permit_number']) && isset($_POST['work_permit_expiry_date'])){
            $error = '';
            $employee_image_file_type = '';
            $work_permit_file_type = '';
            $username = $_POST['username'];
            $employee_id = $_POST['employee_id'];
            $first_name = $_POST['first_name'];
            $middle_name = $_POST['middle_name'];
            $last_name = $_POST['last_name'];
            $suffix = $_POST['suffix'];
            $file_as = $api->get_file_as_format($first_name, $middle_name, $last_name, $suffix);
            $job_position = $_POST['job_position'];
            $department = $_POST['department'];
            $manager = $_POST['manager'];
            $coach = $_POST['coach'];
            $company = $_POST['company'];
            $work_location = $_POST['work_location'];
            $employee_type = $_POST['employee_type'];
            $working_hours = $_POST['working_hours'];
            $work_email = $_POST['work_email'];
            $work_mobile = $_POST['work_mobile'];
            $work_telephone = $_POST['work_telephone'];
            $badge_id = $_POST['badge_id'];
            $onboard_date = $api->check_date('empty', $_POST['onboard_date'], '', 'Y-m-d', '', '', '');
            $permanency_date = $api->check_date('empty', $_POST['permanency_date'], '', 'Y-m-d', '', '', '');
            $sss = $_POST['sss'];
            $tin = $_POST['tin'];
            $philhealth = $_POST['philhealth'];
            $pagibig = $_POST['pagibig'];
            $street_1 = $_POST['street_1'];
            $street_2 = $_POST['street_2'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zip_code'];
            $personal_email = $_POST['personal_email'];
            $personal_mobile = $_POST['personal_mobile'];
            $personal_telephone = $_POST['personal_telephone'];
            $bank_account_number = $_POST['bank_account_number'];
            $home_work_distance = $_POST['home_work_distance'];
            $gender = $_POST['gender'];
            $marital_status = $_POST['marital_status'];
            $spouse_name = $_POST['spouse_name'];
            $spouse_birthday = $api->check_date('empty', $_POST['spouse_birthday'], '', 'Y-m-d', '', '', '');
            $emergency_contact = $_POST['emergency_contact'];
            $emergency_phone = $_POST['emergency_phone'];
            $certificate_level = $_POST['certificate_level'];
            $field_of_study = $_POST['field_of_study'];
            $school = $_POST['school'];
            $identification_number = $_POST['identification_number'];
            $passport_number = $_POST['passport_number'];
            $birthday = $api->check_date('empty', $_POST['birthday'], '', 'Y-m-d', '', '', '');
            $place_of_birth = $_POST['place_of_birth'];
            $number_of_children = $_POST['number_of_children'];
            $nationality = $_POST['nationality'];
            $visa_number = $_POST['visa_number'];
            $visa_expiry_date = $api->check_date('empty', $_POST['visa_expiry_date'], '', 'Y-m-d', '', '', '');
            $work_permit_number = $_POST['work_permit_number'];
            $work_permit_expiry_date = $api->check_date('empty', $_POST['work_permit_expiry_date'], '', 'Y-m-d', '', '', '');

            $state_details = $api->get_state_details($state);
            $country_id = $state_details[0]['COUNTRY_ID'] ?? null;

            $employee_image_name = $_FILES['employee_image']['name'];
            $employee_image_size = $_FILES['employee_image']['size'];
            $employee_image_error = $_FILES['employee_image']['error'];
            $employee_image_tmp_name = $_FILES['employee_image']['tmp_name'];
            $employee_image_ext = explode('.', $employee_image_name);
            $employee_image_actual_ext = strtolower(end($employee_image_ext));

            $work_permit_name = $_FILES['work_permit']['name'];
            $work_permit_size = $_FILES['work_permit']['size'];
            $work_permit_error = $_FILES['work_permit']['error'];
            $work_permit_tmp_name = $_FILES['work_permit']['tmp_name'];
            $work_permit_ext = explode('.', $work_permit_name);
            $work_permit_actual_ext = strtolower(end($work_permit_ext));

            $employee_image_upload_setting_details = $api->get_upload_setting_details(8);
            $employee_image_upload_file_type_details = $api->get_upload_file_type_details(8);
            $employee_image_file_max_size = $employee_image_upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($employee_image_upload_file_type_details); $i++) {
                $employee_image_file_type .= $employee_image_upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($employee_image_upload_file_type_details) - 1)){
                    $employee_image_file_type .= ',';
                }
            }

            $work_permit_upload_setting_details = $api->get_upload_setting_details(9);
            $work_permit_upload_file_type_details = $api->get_upload_file_type_details(9);
            $work_permit_file_max_size = $work_permit_upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($work_permit_upload_file_type_details); $i++) {
                $work_permit_file_type .= $work_permit_upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($work_permit_upload_file_type_details) - 1)){
                    $work_permit_file_type .= ',';
                }
            }

            $employee_image_allowed_ext = explode(',', $employee_image_file_type);
            $work_permit_allowed_ext = explode(',', $work_permit_file_type);

            $check_employee_exist = $api->check_employee_exist($employee_id);
 
            if($check_employee_exist > 0){
                if(!empty($employee_image_tmp_name)){
                    if(in_array($employee_image_actual_ext, $employee_image_allowed_ext)){
                        if(!$employee_image_error){
                            if($employee_image_size < $employee_image_file_max_size){
                                $update_employee_image = $api->update_employee_image($employee_image_tmp_name, $employee_image_actual_ext, $employee_id, $username);
        
                                if(!$update_employee_image){
                                    $error = $update_employee_image;
                                }
                            }
                            else{
                                $error = 'Employee Image File Size';
                            }
                        }
                        else{
                            $error = 'There was an error uploading the file.';
                        }
                    }
                    else{
                        $error = 'Employee Image File Type';
                    }
                }

                if(!empty($work_permit_tmp_name)){
                    if(in_array($work_permit_actual_ext, $work_permit_allowed_ext)){
                        if(!$work_permit_error){
                            if($work_permit_size < $work_permit_file_max_size){
                                $update_work_permit = $api->update_work_permit($work_permit_tmp_name, $work_permit_actual_ext, $employee_id, $username);
        
                                if(!$update_work_permit){
                                    $error = $update_work_permit;
                                }
                            }
                            else{
                                $error = 'Work Permit File Size';
                            }
                        }
                        else{
                            $error = 'There was an error uploading the file.';
                        }
                    }
                    else{
                        $error = 'Work Permit File Type';
                    }
                }

                if(empty($error)){
                    $update_employee = $api->update_employee($employee_id, $badge_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $work_email, $work_telephone, $work_mobile, $sss, $tin, $pagibig, $philhealth, $bank_account_number, $home_work_distance, $personal_email, $personal_telephone, $personal_mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $marital_status, $spouse_name, $spouse_birthday, $emergency_contact, $emergency_phone, $nationality, $identification_number, $passport_number, $gender, $birthday, $certificate_level, $field_of_study, $school, $place_of_birth, $number_of_children, $visa_number, $work_permit_number, $visa_expiry_date, $work_permit_expiry_date, $username);

                    if($update_employee){
                        echo 'Updated';
                    }
                    else{
                        echo $update_employee;
                    }
                }
                else{
                    echo $error;
                }
            }
            else{
                if(!empty($employee_image_tmp_name)){
                    if(in_array($employee_image_actual_ext, $employee_image_allowed_ext)){
                        if(!$employee_image_error){
                            if($employee_image_size > $employee_image_file_max_size){
                                $error = 'File Size';
                            }
                        }
                        else{
                            $error = 'There was an error uploading the file.';
                        }
                    }
                    else{
                        $error = 'File Type';
                    }
                }
                else{
                    $employee_image_tmp_name = null;
                    $employee_image_actual_ext = null;
                }

                if(!empty($work_permit_tmp_name)){
                    if(in_array($work_permit_actual_ext, $work_permit_allowed_ext)){
                        if(!$work_permit_error){
                            if($work_permit_size > $work_permit_file_max_size){
                                $error = 'File Size';
                            }
                        }
                        else{
                            $error = 'There was an error uploading the file.';
                        }
                    }
                    else{
                        $error = 'File Type';
                    }
                }
                else{
                    $work_permit_tmp_name = null;
                    $work_permit_actual_ext = null;
                }
                
                if(empty($error)){
                    $insert_employee = $api->insert_employee($employee_image_tmp_name, $employee_image_actual_ext, $work_permit_tmp_name, $work_permit_actual_ext, $badge_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $work_email, $work_telephone, $work_mobile, $sss, $tin, $pagibig, $philhealth, $bank_account_number, $home_work_distance, $personal_email, $personal_telephone, $personal_mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $marital_status, $spouse_name, $spouse_birthday, $emergency_contact, $emergency_phone, $nationality, $identification_number, $passport_number, $gender, $birthday, $certificate_level, $field_of_study, $school, $place_of_birth, $number_of_children, $visa_number, $work_permit_number, $visa_expiry_date, $work_permit_expiry_date, $username);
    
                    if($insert_employee){
                        echo 'Inserted';
                    }
                    else{
                        echo $insert_employee;
                    }
                }
                else{
                    echo $error;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit working hours
    else if($transaction == 'submit working hours'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['working_hours_id']) && isset($_POST['working_hours']) && !empty($_POST['working_hours']) && isset($_POST['schedule_type']) && !empty($_POST['schedule_type'])){
            $username = $_POST['username'];
            $working_hours_id = $_POST['working_hours_id'];
            $working_hours = $_POST['working_hours'];
            $schedule_type = $_POST['schedule_type'];
          
            $check_working_hours_exist = $api->check_working_hours_exist($working_hours_id);
 
            if($check_working_hours_exist > 0){
                $update_working_hours = $api->update_working_hours($working_hours_id, $working_hours, $schedule_type, $username);

                if($update_working_hours){
                    echo 'Updated';
                }
                else{
                    echo $update_working_hours;
                }
            }
            else{
                $insert_working_hours = $api->insert_working_hours($working_hours, $schedule_type, $username);
    
                if($insert_working_hours){
                    echo 'Inserted';
                }
                else{
                    echo $insert_working_hours;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit regular working hours
    else if($transaction == 'submit regular working hours'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id']) && isset($_POST['employee']) && isset($_POST['monday_morning_work_from']) && isset($_POST['monday_morning_work_to']) && isset($_POST['monday_afternoon_work_from']) && isset($_POST['monday_afternoon_work_to']) && isset($_POST['tuesday_morning_work_from']) && isset($_POST['tuesday_morning_work_to']) && isset($_POST['tuesday_afternoon_work_from']) && isset($_POST['tuesday_afternoon_work_to']) && isset($_POST['wednesday_morning_work_from']) && isset($_POST['wednesday_morning_work_to']) && isset($_POST['wednesday_afternoon_work_from']) && isset($_POST['wednesday_afternoon_work_to']) && isset($_POST['thursday_morning_work_from']) && isset($_POST['thursday_morning_work_to']) && isset($_POST['thursday_afternoon_work_from']) && isset($_POST['thursday_afternoon_work_to']) && isset($_POST['friday_morning_work_from']) && isset($_POST['friday_morning_work_to']) && isset($_POST['friday_afternoon_work_from']) && isset($_POST['friday_afternoon_work_to']) && isset($_POST['saturday_morning_work_from']) && isset($_POST['saturday_morning_work_to']) && isset($_POST['saturday_afternoon_work_from']) && isset($_POST['saturday_afternoon_work_to']) && isset($_POST['sunday_morning_work_from']) && isset($_POST['sunday_morning_work_to']) && isset($_POST['sunday_afternoon_work_from']) && isset($_POST['sunday_afternoon_work_to'])){
            $error = '';
            $username = $_POST['username'];
            $working_hours_id = $_POST['working_hours_id'];
            $monday_morning_work_from = $api->check_date('empty', $_POST['monday_morning_work_from'], '', 'H:i:s', '', '', '');
            $monday_morning_work_to = $api->check_date('empty', $_POST['monday_morning_work_to'], '', 'H:i:s', '', '', '');
            $monday_afternoon_work_from = $api->check_date('empty', $_POST['monday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $monday_afternoon_work_to = $api->check_date('empty', $_POST['monday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $tuesday_morning_work_from = $api->check_date('empty', $_POST['tuesday_morning_work_from'], '', 'H:i:s', '', '', '');
            $tuesday_morning_work_to = $api->check_date('empty', $_POST['tuesday_morning_work_to'], '', 'H:i:s', '', '', '');
            $tuesday_afternoon_work_from = $api->check_date('empty', $_POST['tuesday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $tuesday_afternoon_work_to = $api->check_date('empty', $_POST['tuesday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $wednesday_morning_work_from = $api->check_date('empty', $_POST['wednesday_morning_work_from'], '', 'H:i:s', '', '', '');
            $wednesday_morning_work_to = $api->check_date('empty', $_POST['wednesday_morning_work_to'], '', 'H:i:s', '', '', '');
            $wednesday_afternoon_work_from = $api->check_date('empty', $_POST['wednesday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $wednesday_afternoon_work_to = $api->check_date('empty', $_POST['wednesday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $thursday_morning_work_from = $api->check_date('empty', $_POST['thursday_morning_work_from'], '', 'H:i:s', '', '', '');
            $thursday_morning_work_to = $api->check_date('empty', $_POST['thursday_morning_work_to'], '', 'H:i:s', '', '', '');
            $thursday_afternoon_work_from = $api->check_date('empty', $_POST['thursday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $thursday_afternoon_work_to = $api->check_date('empty', $_POST['thursday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $friday_morning_work_from = $api->check_date('empty', $_POST['friday_morning_work_from'], '', 'H:i:s', '', '', '');
            $friday_morning_work_to = $api->check_date('empty', $_POST['friday_morning_work_to'], '', 'H:i:s', '', '', '');
            $friday_afternoon_work_from = $api->check_date('empty', $_POST['friday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $friday_afternoon_work_to = $api->check_date('empty', $_POST['friday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $saturday_morning_work_from = $api->check_date('empty', $_POST['saturday_morning_work_from'], '', 'H:i:s', '', '', '');
            $saturday_morning_work_to = $api->check_date('empty', $_POST['saturday_morning_work_to'], '', 'H:i:s', '', '', '');
            $saturday_afternoon_work_from = $api->check_date('empty', $_POST['saturday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $saturday_afternoon_work_to = $api->check_date('empty', $_POST['saturday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $sunday_morning_work_from = $api->check_date('empty', $_POST['sunday_morning_work_from'], '', 'H:i:s', '', '', '');
            $sunday_morning_work_to = $api->check_date('empty', $_POST['sunday_morning_work_to'], '', 'H:i:s', '', '', '');
            $sunday_afternoon_work_from = $api->check_date('empty', $_POST['sunday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $sunday_afternoon_work_to = $api->check_date('empty', $_POST['sunday_afternoon_work_to'], '', 'H:i:s', '', '', '');

            $employees = explode(',', $_POST['employee']);

            $monday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to);
            $tuesday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to);
            $wednesday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to);
            $thursday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to);
            $friday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to);
            $saturday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to);
            $sunday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to);
          
            if(!$monday_working_hours_schedule_overlap && !$tuesday_working_hours_schedule_overlap && !$wednesday_working_hours_schedule_overlap && !$thursday_working_hours_schedule_overlap && !$friday_working_hours_schedule_overlap && !$saturday_working_hours_schedule_overlap && !$sunday_working_hours_schedule_overlap){
                $check_working_hours_schedule_exist = $api->check_working_hours_schedule_exist($working_hours_id);
 
                if($check_working_hours_schedule_exist > 0){
                    $update_working_hours_schedule = $api->update_working_hours_schedule($working_hours_id, null, null, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username);

                    if($update_working_hours_schedule){
                        $delete_all_employee_working_hours = $api->delete_all_employee_working_hours($working_hours_id, $username);
                                    
                        if($delete_all_employee_working_hours){
                            foreach($employees as $employee_id){
                                if(!empty($employee_id)){
                                    $update_employee_working_hours = $api->update_employee_working_hours($employee_id, $working_hours_id, $username);
        
                                    if(!$update_employee_working_hours){
                                        $error = $update_employee_working_hours;
                                        break;
                                    }
                                }
                            }
                        }
                        else{
                            $error = $delete_all_employee_working_hours;
                        }
                        
                        if(empty($error)){
                            echo 'Updated';
                        }
                        else{
                            echo $error;
                        }
                    }
                    else{
                        echo $update_working_hours_schedule;
                    }
                }
                else{
                    $insert_working_hours_schedule = $api->insert_working_hours_schedule($working_hours_id, null, null, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username);
        
                    if($insert_working_hours_schedule){
                        $delete_all_employee_working_hours = $api->delete_all_employee_working_hours($working_hours_id, $username);
                                    
                        if($delete_all_employee_working_hours){
                            foreach($employees as $employee_id){
                                if(!empty($employee_id)){
                                    $update_employee_working_hours = $api->update_employee_working_hours($employee_id, $working_hours_id, $username);
        
                                    if(!$update_employee_working_hours){
                                        $error = $update_employee_working_hours;
                                        break;
                                    }
                                }
                            }
                        }
                        else{
                            $error = $delete_all_employee_working_hours;
                        }
                        
                        if(empty($error)){
                            echo 'Updated';
                        }
                        else{
                            echo $error;
                        }
                    }
                    else{
                        echo $insert_working_hours_schedule;
                    }
                }
            }
            else{
                echo 'Overlap';
            }
        }
    }
    # -------------------------------------------------------------

    # Submit scheduled working hours
    else if($transaction == 'submit scheduled working hours'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id']) && isset($_POST['employee']) && isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date']) && isset($_POST['monday_morning_work_from']) && isset($_POST['monday_morning_work_to']) && isset($_POST['monday_afternoon_work_from']) && isset($_POST['monday_afternoon_work_to']) && isset($_POST['tuesday_morning_work_from']) && isset($_POST['tuesday_morning_work_to']) && isset($_POST['tuesday_afternoon_work_from']) && isset($_POST['tuesday_afternoon_work_to']) && isset($_POST['wednesday_morning_work_from']) && isset($_POST['wednesday_morning_work_to']) && isset($_POST['wednesday_afternoon_work_from']) && isset($_POST['wednesday_afternoon_work_to']) && isset($_POST['thursday_morning_work_from']) && isset($_POST['thursday_morning_work_to']) && isset($_POST['thursday_afternoon_work_from']) && isset($_POST['thursday_afternoon_work_to']) && isset($_POST['friday_morning_work_from']) && isset($_POST['friday_morning_work_to']) && isset($_POST['friday_afternoon_work_from']) && isset($_POST['friday_afternoon_work_to']) && isset($_POST['saturday_morning_work_from']) && isset($_POST['saturday_morning_work_to']) && isset($_POST['saturday_afternoon_work_from']) && isset($_POST['saturday_afternoon_work_to']) && isset($_POST['sunday_morning_work_from']) && isset($_POST['sunday_morning_work_to']) && isset($_POST['sunday_afternoon_work_from']) && isset($_POST['sunday_afternoon_work_to'])){
            $error = '';
            $username = $_POST['username'];
            $working_hours_id = $_POST['working_hours_id'];
            $start_date = $api->check_date('empty', $_POST['start_date'], '', 'Y-m-d', '', '', '');
            $end_date = $api->check_date('empty', $_POST['end_date'], '', 'Y-m-d', '', '', '');
            $monday_morning_work_from = $api->check_date('empty', $_POST['monday_morning_work_from'], '', 'H:i:s', '', '', '');
            $monday_morning_work_to = $api->check_date('empty', $_POST['monday_morning_work_to'], '', 'H:i:s', '', '', '');
            $monday_afternoon_work_from = $api->check_date('empty', $_POST['monday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $monday_afternoon_work_to = $api->check_date('empty', $_POST['monday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $tuesday_morning_work_from = $api->check_date('empty', $_POST['tuesday_morning_work_from'], '', 'H:i:s', '', '', '');
            $tuesday_morning_work_to = $api->check_date('empty', $_POST['tuesday_morning_work_to'], '', 'H:i:s', '', '', '');
            $tuesday_afternoon_work_from = $api->check_date('empty', $_POST['tuesday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $tuesday_afternoon_work_to = $api->check_date('empty', $_POST['tuesday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $wednesday_morning_work_from = $api->check_date('empty', $_POST['wednesday_morning_work_from'], '', 'H:i:s', '', '', '');
            $wednesday_morning_work_to = $api->check_date('empty', $_POST['wednesday_morning_work_to'], '', 'H:i:s', '', '', '');
            $wednesday_afternoon_work_from = $api->check_date('empty', $_POST['wednesday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $wednesday_afternoon_work_to = $api->check_date('empty', $_POST['wednesday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $thursday_morning_work_from = $api->check_date('empty', $_POST['thursday_morning_work_from'], '', 'H:i:s', '', '', '');
            $thursday_morning_work_to = $api->check_date('empty', $_POST['thursday_morning_work_to'], '', 'H:i:s', '', '', '');
            $thursday_afternoon_work_from = $api->check_date('empty', $_POST['thursday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $thursday_afternoon_work_to = $api->check_date('empty', $_POST['thursday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $friday_morning_work_from = $api->check_date('empty', $_POST['friday_morning_work_from'], '', 'H:i:s', '', '', '');
            $friday_morning_work_to = $api->check_date('empty', $_POST['friday_morning_work_to'], '', 'H:i:s', '', '', '');
            $friday_afternoon_work_from = $api->check_date('empty', $_POST['friday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $friday_afternoon_work_to = $api->check_date('empty', $_POST['friday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $saturday_morning_work_from = $api->check_date('empty', $_POST['saturday_morning_work_from'], '', 'H:i:s', '', '', '');
            $saturday_morning_work_to = $api->check_date('empty', $_POST['saturday_morning_work_to'], '', 'H:i:s', '', '', '');
            $saturday_afternoon_work_from = $api->check_date('empty', $_POST['saturday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $saturday_afternoon_work_to = $api->check_date('empty', $_POST['saturday_afternoon_work_to'], '', 'H:i:s', '', '', '');
            $sunday_morning_work_from = $api->check_date('empty', $_POST['sunday_morning_work_from'], '', 'H:i:s', '', '', '');
            $sunday_morning_work_to = $api->check_date('empty', $_POST['sunday_morning_work_to'], '', 'H:i:s', '', '', '');
            $sunday_afternoon_work_from = $api->check_date('empty', $_POST['sunday_afternoon_work_from'], '', 'H:i:s', '', '', '');
            $sunday_afternoon_work_to = $api->check_date('empty', $_POST['sunday_afternoon_work_to'], '', 'H:i:s', '', '', '');

            $employees = explode(',', $_POST['employee']);

            $monday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to);
            $tuesday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to);
            $wednesday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to);
            $thursday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to);
            $friday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to);
            $saturday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to);
            $sunday_working_hours_schedule_overlap = $api->check_working_hours_schedue_overlap($sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to);
          
            if(!$monday_working_hours_schedule_overlap && !$tuesday_working_hours_schedule_overlap && !$wednesday_working_hours_schedule_overlap && !$thursday_working_hours_schedule_overlap && !$friday_working_hours_schedule_overlap && !$saturday_working_hours_schedule_overlap && !$sunday_working_hours_schedule_overlap){
                $check_working_hours_schedule_exist = $api->check_working_hours_schedule_exist($working_hours_id);
 
                if($check_working_hours_schedule_exist > 0){
                    $update_working_hours_schedule = $api->update_working_hours_schedule($working_hours_id, $start_date, $end_date, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username);

                    if($update_working_hours_schedule){
                        $delete_all_employee_working_hours = $api->delete_all_employee_working_hours($working_hours_id, $username);
                                    
                        if($delete_all_employee_working_hours){
                            foreach($employees as $employee_id){
                                if(!empty($employee_id)){
                                    $update_employee_working_hours = $api->update_employee_working_hours($employee_id, $working_hours_id, $username);
        
                                    if(!$update_employee_working_hours){
                                        $error = $update_employee_working_hours;
                                        break;
                                    }
                                }
                            }
                        }
                        else{
                            $error = $delete_all_employee_working_hours;
                        }
                        
                        if(empty($error)){
                            echo 'Updated';
                        }
                        else{
                            echo $error;
                        }
                    }
                    else{
                        echo $update_working_hours_schedule;
                    }
                }
                else{
                    $insert_working_hours_schedule = $api->insert_working_hours_schedule($working_hours_id, $start_date, $end_date, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username);
        
                    if($insert_working_hours_schedule){
                        $delete_all_employee_working_hours = $api->delete_all_employee_working_hours($working_hours_id, $username);
                                    
                        if($delete_all_employee_working_hours){
                            foreach($employees as $employee_id){
                                if(!empty($employee_id)){
                                    $update_employee_working_hours = $api->update_employee_working_hours($employee_id, $working_hours_id, $username);
        
                                    if(!$update_employee_working_hours){
                                        $error = $update_employee_working_hours;
                                        break;
                                    }
                                }
                            }
                        }
                        else{
                            $error = $delete_all_employee_working_hours;
                        }
                        
                        if(empty($error)){
                            echo 'Updated';
                        }
                        else{
                            echo $error;
                        }
                    }
                    else{
                        echo $insert_working_hours_schedule;
                    }
                }
            }
            else{
                echo 'Overlap';
            }
        }
    }
    # -------------------------------------------------------------

    # Submit attendance setting
    else if($transaction == 'submit attendance setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['maximum_attendance']) && isset($_POST['late_grace_period']) && isset($_POST['time_out_interval']) && isset($_POST['late_policy']) && isset($_POST['early_leaving_policy']) && isset($_POST['overtime_policy'])){
            $username = $_POST['username'];
            $attendance_setting_id = 1;
            $maximum_attendance = $_POST['maximum_attendance'];
            $late_grace_period = $_POST['late_grace_period'];
            $time_out_interval = $_POST['time_out_interval'];
            $late_policy = $_POST['late_policy'];
            $early_leaving_policy = $_POST['early_leaving_policy'];
            $overtime_policy = $_POST['overtime_policy'];

            $check_attendance_setting_exist = $api->check_attendance_setting_exist($attendance_setting_id);

            if($check_attendance_setting_exist > 0){
                $update_attendance_setting = $api->update_attendance_setting($attendance_setting_id, $maximum_attendance, $late_grace_period, $time_out_interval, $late_policy, $early_leaving_policy, $overtime_policy, $username);

                if($update_attendance_setting){
                    echo 'Updated';
                }
                else{
                    echo $update_attendance_setting;
                }
            }
            else{
                $insert_attendance_setting = $api->insert_attendance_setting($attendance_setting_id, $maximum_attendance, $late_grace_period, $time_out_interval, $late_policy, $early_leaving_policy, $overtime_policy, $username);

                if($insert_attendance_setting){
                    echo 'Updated';
                }
                else{
                    echo $insert_attendance_setting;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit attendance time in
    else if($transaction == 'submit attendance time in'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_position']) && isset($_POST['time_in_note'])){
            $username = $_POST['username'];
            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
            $attendance_position = $_POST['attendance_position'];
            $time_in_note = $_POST['time_in_note'];
          
            $time_in = date('Y-m-d H:i:00');
            $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
            $late = $api->get_attendance_late_total($employee_id, $time_in);

            $attendance_setting_details = $api->get_attendance_setting_details(1);
            $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
            $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));
            $ip_address = $api->get_ip_address();
                
            $notification_template_details = $api->get_notification_template_details(1);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{date}', $api->check_date('empty', $time_in, '', 'F d, Y', '', '', ''), $notification_message);
            $notification_message = str_replace('{time}', $api->check_date('empty', $time_in, '', 'h:i a', '', '', ''), $notification_message);
    
            if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                if($attendance_total_by_date < $max_attendance){
                    $insert_time_in = $api->insert_time_in($employee_id, $time_in, $attendance_position, $ip_address, $time_in_behavior, $time_in_note, $late, $username);

                    if($insert_time_in > 0){
                        $send_notification = $api->send_notification(1, null, $employee_id, $notification_title, $notification_message, $username);

                        if($send_notification){
                            echo 'Time In';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                    else{
                        echo $insert_time_in;
                    }
                }
                else{
                    echo 'Max Attendance';
                }
            }
            else{
                if(!empty($attendance_position)){
                    $insert_time_in = $api->insert_time_in($employee_id, $time_in, $attendance_position, $ip_address, $time_in_behavior, $time_in_note, $late, $username);

                    if($insert_time_in > 0){
                        $send_notification = $api->send_notification(1, null, $employee_id, $notification_title, $notification_message, $username);

                        if($send_notification){
                            echo 'Time In';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                    else{
                        echo $insert_time_in;
                    }
                }
                else{
                    echo 'Location';
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit attendance time out
    else if($transaction == 'submit attendance time out'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && !empty($_POST['attendance_id']) && isset($_POST['attendance_position']) && isset($_POST['time_out_note'])){
            $username = $_POST['username'];
            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
            $attendance_id = $_POST['attendance_id'];
            $attendance_position = $_POST['attendance_position'];
            $time_out_note = $_POST['time_out_note'];

            $attendance_details = $api->get_attendance_details($attendance_id);
            $time_in = $attendance_details[0]['TIME_IN'];
          
            $time_out = date('Y-m-d H:i:00');
            $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
            $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
            $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
            $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
            $ip_address = $api->get_ip_address();
                
            $notification_template_details = $api->get_notification_template_details(2);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{date}', $api->check_date('empty', $time_out, '', 'F d, Y', '', '', ''), $notification_message);
            $notification_message = str_replace('{time}', $api->check_date('empty', $time_out, '', 'h:i a', '', '', ''), $notification_message);

            $check_attendance_exist = $api->check_attendance_exist($attendance_id);
 
            if($check_attendance_exist > 0){
                if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                    $update_time_out = $api->update_time_out($attendance_id, $time_out, $attendance_position, $ip_address, $time_out_behavior, $time_out_note, $early_leaving, $overtime, $total_hours, $username);
    
                    if($update_time_out > 0){
                        $send_notification = $api->send_notification(2, null, $employee_id, $notification_title, $notification_message, $username);
    
                        if($send_notification){
                            echo 'Time Out';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                    else{
                        echo $update_time_out;
                    }
                }
                else{
                    if(!empty($attendance_position)){
                        $update_time_out = $api->update_time_out($attendance_id, $time_out, $attendance_position, $ip_address, $time_out_behavior, $time_out_note, $early_leaving, $overtime, $total_hours, $username);
    
                        if($update_time_out > 0){
                            $send_notification = $api->send_notification(2, null, $employee_id, $notification_title, $notification_message, $username);
        
                            if($send_notification){
                                echo 'Time Out';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                        else{
                            echo $update_time_out;
                        }
                    }
                    else{
                        echo 'Location';
                    }
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Submit attendance
    else if($transaction == 'submit attendance'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && isset($_POST['time_out_time']) && isset($_POST['remarks'])){
            $username = $_POST['username'];
            $attendance_id = $_POST['attendance_id'];
            $employee_id = $_POST['employee_id'];
            $remarks = $_POST['remarks'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');
          
            $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
            $late = $api->get_attendance_late_total($employee_id, $time_in);

            $attendance_setting_details = $api->get_attendance_setting_details(1);
            $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
            $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));
            $time_in_ip_address = $api->get_ip_address();

            if(!empty($time_out)){
                $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                $time_out_ip_address = $api->get_ip_address();
                $time_out_by = $username;
            }
            else{
                $time_out_behavior = '';
                $early_leaving = 0;
                $overtime = 0;
                $total_hours = 0;
                $time_out_ip_address = '';
                $time_out_by = '';
            }

            $check_attendance_exist = $api->check_attendance_exist($attendance_id);

            if($check_attendance_exist > 0){
                $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                if(empty($check_attendance_validation)){
                    $update_attendance = $api->update_attendance($attendance_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, $remarks, $username);
    
                    if($update_attendance > 0){
                        echo 'Updated';
                    }
                    else{
                        echo $update_attendance;
                    }
                }
                else{
                    echo $check_attendance_validation;
                }
            }
            else{
                $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                if(empty($check_attendance_validation)){
                    if($attendance_total_by_date < $max_attendance){
                        $insert_attendance = $api->insert_attendance($employee_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, $remarks, $username);
    
                        if($insert_attendance > 0){
                            echo 'Inserted';
                        }
                        else{
                            echo $insert_attendance;
                        }
                    }
                    else{
                        echo 'Max Attendance';
                    }
                }
                else{
                    echo $check_attendance_validation;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit badge scan
    else if($transaction == 'submit badge scan'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['attendance_position'])){
            $username = $_POST['username'];
            $employee_id = $_POST['employee_id'];
            $attendance_position = $_POST['attendance_position'];

            $recent_employee_attendance_details = $api->get_recent_employee_attendance_details($employee_id, date('Y-m-d'));
            $attendance_id = $recent_employee_attendance_details[0]['ATTENDANCE_ID'] ?? null;

            if(!empty($attendance_id)){
                $attendance_details = $api->get_attendance_details($attendance_id);
                $time_in = $attendance_details[0]['TIME_IN'] ?? null;

                $attendance_setting_details = $api->get_attendance_setting_details(1);
                $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
                $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, $api->check_date('empty', $time_in, '', 'Y-m-d', '', '', ''));

                $time_out = date('Y-m-d H:i:00');
                $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                $ip_address = $api->get_ip_address();
                    
                $notification_template_details = $api->get_notification_template_details(2);
                $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
                $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
                $notification_message = str_replace('{date}', $api->check_date('empty', $time_out, '', 'F d, Y', '', '', ''), $notification_message);
                $notification_message = str_replace('{time}', $api->check_date('empty', $time_out, '', 'h:i a', '', '', ''), $notification_message);

                $attendance_setting_details = $api->get_attendance_setting_details(1);
                $time_out_interval = $attendance_setting_details[0]['TIME_OUT_INTERVAL'] ?? 1;

                $time_difference = round(abs(strtotime($time_out) - strtotime($time_in)) / 60, 2);

                if($time_difference > $time_out_interval){
                    if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                        if($attendance_total_by_date < $max_attendance){
                            $update_time_out = $api->update_time_out($attendance_id, $time_out, $attendance_position, $ip_address, $time_out_behavior, 'Recorded using badge scanning.', $early_leaving, $overtime, $total_hours, $username);
        
                            if($update_time_out > 0){
                                $send_notification = $api->send_notification(2, null, $employee_id, $notification_title, $notification_message, $username);
            
                                if($send_notification){
                                    echo 'Time Out';
                                }
                                else{
                                    echo $send_notification;
                                }
                            }
                            else{
                                echo $update_time_out;
                            }
                        }
                        else{
                            echo 'Max Attendance';
                        }
                    }
                    else{
                        if(!empty($attendance_position)){
                            if($attendance_total_by_date < $max_attendance){
                                $update_time_out = $api->update_time_out($attendance_id, $time_out, $attendance_position, $ip_address, $time_out_behavior, 'Recorded using badge scanning.', $early_leaving, $overtime, $total_hours, $username);
            
                                if($update_time_out > 0){
                                    $send_notification = $api->send_notification(2, null, $employee_id, $notification_title, $notification_message, $username);
                
                                    if($send_notification){
                                        echo 'Time Out';
                                    }
                                    else{
                                        echo $send_notification;
                                    }
                                }
                                else{
                                    echo $update_time_out;
                                }
                            }
                            else{
                                echo 'Max Attendance';
                            }
                        }
                        else{
                            echo 'Location';
                        }
                    }
                }
                else{
                    echo 'Time Allowance';
                }
            }
            else{
                $time_in = date('Y-m-d H:i:00');
                $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
                $late = $api->get_attendance_late_total($employee_id, $time_in);
    
                $attendance_setting_details = $api->get_attendance_setting_details(1);
                $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
                $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));
                $ip_address = $api->get_ip_address();
                    
                $notification_template_details = $api->get_notification_template_details(1);
                $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
                $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
                $notification_message = str_replace('{date}', $api->check_date('empty', $time_in, '', 'F d, Y', '', '', ''), $notification_message);
                $notification_message = str_replace('{time}', $api->check_date('empty', $time_in, '', 'h:i a', '', '', ''), $notification_message);
        
                if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                    if($attendance_total_by_date < $max_attendance){
                        $insert_time_in = $api->insert_time_in($employee_id, $time_in, $attendance_position, $ip_address, $time_in_behavior, 'Recorded using badge scanning.', $late, $username);
    
                        if($insert_time_in > 0){
                            $send_notification = $api->send_notification(1, null, $employee_id, $notification_title, $notification_message, $username);
    
                            if($send_notification){
                                echo 'Time In';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                        else{
                            echo $insert_time_in;
                        }
                    }
                    else{
                        echo 'Max Attendance';
                    }
                }
                else{
                    if(!empty($attendance_position)){
                        $insert_time_in = $api->insert_time_in($employee_id, $time_in, $attendance_position, $ip_address, $time_in_behavior, 'Recorded using badge scanning.', $late, $username);
    
                        if($insert_time_in > 0){
                            $send_notification = $api->send_notification(1, null, $employee_id, $notification_title, $notification_message, $username);
    
                            if($send_notification){
                                echo 'Time In';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                        else{
                            echo $insert_time_in;
                        }
                    }
                    else{
                        echo 'Location';
                    }
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit full attendance adjustment
    else if($transaction == 'submit full attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && !empty($_POST['attendance_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && !empty($_POST['time_out_date']) && isset($_POST['time_out_time']) && !empty($_POST['time_out_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $attendance_id = $_POST['attendance_id'];
            $employee_id = $_POST['employee_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(10);
            $upload_file_type_details = $api->get_upload_file_type_details(10);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            if(in_array($attachment_actual_ext, $allowed_ext)){
                if(!$attachment_error){
                    if($attachment_size < $file_max_size){
                        $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                        if(empty($check_attendance_validation)){
                            $insert_attendance_adjustment = $api->insert_attendance_adjustment($attachment_tmp_name, $attachment_actual_ext, $attendance_id, $employee_id, $time_in, $time_out, $reason, $username);

                            if($insert_attendance_adjustment){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_attendance_adjustment;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
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
    # -------------------------------------------------------------

    # Submit attendance adjustment
    else if($transaction == 'submit attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && !empty($_POST['attendance_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && isset($_POST['time_out_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $attendance_id = $_POST['attendance_id'];
            $employee_id = $_POST['employee_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(10);
            $upload_file_type_details = $api->get_upload_file_type_details(10);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            if(in_array($attachment_actual_ext, $allowed_ext)){
                if(!$attachment_error){
                    if($attachment_size < $file_max_size){
                        $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                        if(empty($check_attendance_validation)){
                            $insert_attendance_adjustment = $api->insert_attendance_adjustment($attachment_tmp_name, $attachment_actual_ext, $attendance_id, $employee_id, $time_in, $time_out, $reason, $username);

                            if($insert_attendance_adjustment){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_attendance_adjustment;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
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
    # -------------------------------------------------------------

    # Submit partial attendance adjustment
    else if($transaction == 'submit partial attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && !empty($_POST['attendance_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $attendance_id = $_POST['attendance_id'];
            $employee_id = $_POST['employee_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(10);
            $upload_file_type_details = $api->get_upload_file_type_details(10);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            if(in_array($attachment_actual_ext, $allowed_ext)){
                if(!$attachment_error){
                    if($attachment_size < $file_max_size){
                        $insert_attendance_adjustment = $api->insert_attendance_adjustment($attachment_tmp_name, $attachment_actual_ext, $attendance_id, $employee_id, $time_in, null, $reason, $username);

                        if($insert_attendance_adjustment){
                            echo 'Inserted';
                        }
                        else{
                            echo $insert_attendance_adjustment;
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
    # -------------------------------------------------------------

    # Submit attendance creation
    else if($transaction == 'submit attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && isset($_POST['time_out_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(11);
            $upload_file_type_details = $api->get_upload_file_type_details(11);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            if(in_array($attachment_actual_ext, $allowed_ext)){
                if(!$attachment_error){
                    if($attachment_size < $file_max_size){
                        $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                        if(empty($check_attendance_validation)){
                            $insert_attendance_creation = $api->insert_attendance_creation($attachment_tmp_name, $attachment_actual_ext, $employee_id, $time_in, $time_out, $reason, $username);

                            if($insert_attendance_creation){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_attendance_creation;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
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
    # -------------------------------------------------------------

    # Submit full attendance adjustment update
    else if($transaction == 'submit full attendance adjustment update'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && !empty($_POST['time_out_date']) && isset($_POST['time_out_time']) && !empty($_POST['time_out_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(10);
            $upload_file_type_details = $api->get_upload_file_type_details(10);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);
 
            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
                $attendance_adjustment_status = $attendance_adjustment_details[0]['STATUS'];

                if($attendance_adjustment_status == 'PEN'){
                    if(!empty($attachment_tmp_name)){
                        if(in_array($attachment_actual_ext, $allowed_ext)){
                            if(!$attachment_error){
                                if($attachment_size < $file_max_size){
                                    $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);
            
                                    if(empty($check_attendance_validation)){
                                        $update_attendance_adjustment = $api->update_attendance_adjustment($adjustment_id, $time_in, $time_out, $reason, $username);
            
                                        if($update_attendance_adjustment){
                                            $update_attendance_adjustment_attachment = $api->update_attendance_adjustment_attachment($attachment_tmp_name, $attachment_actual_ext, $adjustment_id, $username);
            
                                            if($update_attendance_adjustment_attachment){
                                                echo 'Updated';
                                            }
                                            else{
                                                echo $update_attendance_adjustment_attachment;
                                            }
                                        }
                                        else{
                                            echo $update_attendance_adjustment;
                                        }
                                    }
                                    else{
                                        echo $check_attendance_validation;
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
                        $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);
            
                        if(empty($check_attendance_validation)){
                            $update_attendance_adjustment = $api->update_attendance_adjustment($adjustment_id, $time_in, $time_out, $reason, $username);
            
                            if($update_attendance_adjustment){
                                echo 'Updated';
                            }
                            else{
                                echo $update_attendance_adjustment;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
                        }
                    }
                }
                else{
                    echo 'Status';
                }
            }
            else{
                echo 'Not Found';
            }           
        }
    }
    # -------------------------------------------------------------

    # Submit partial attendance adjustment update
    else if($transaction == 'submit partial attendance adjustment update'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(10);
            $upload_file_type_details = $api->get_upload_file_type_details(10);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);
 
            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
                $attendance_adjustment_status = $attendance_adjustment_details[0]['STATUS'];

                if($attendance_adjustment_status == 'PEN'){
                    if(!empty($attachment_tmp_name)){
                        if(in_array($attachment_actual_ext, $allowed_ext)){
                            if(!$attachment_error){
                                if($attachment_size < $file_max_size){
                                    $check_attendance_validation = $api->check_attendance_validation($time_in, null);
            
                                    if(empty($check_attendance_validation)){
                                        $update_attendance_adjustment = $api->update_attendance_adjustment($adjustment_id, $time_in, null, $reason, $username);
            
                                        if($update_attendance_adjustment){
                                            $update_attendance_adjustment_attachment = $api->update_attendance_adjustment_attachment($attachment_tmp_name, $attachment_actual_ext, $adjustment_id, $username);
            
                                            if($update_attendance_adjustment_attachment){
                                                echo 'Updated';
                                            }
                                            else{
                                                echo $update_attendance_adjustment_attachment;
                                            }
                                        }
                                        else{
                                            echo $update_attendance_adjustment;
                                        }
                                    }
                                    else{
                                        echo $check_attendance_validation;
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
                        $check_attendance_validation = $api->check_attendance_validation($time_in, null);
            
                        if(empty($check_attendance_validation)){
                            $update_attendance_adjustment = $api->update_attendance_adjustment($adjustment_id, $time_in, null, $reason, $username);

                            if($update_attendance_adjustment){
                                echo 'Updated';
                            }
                            else{
                                echo $update_attendance_adjustment;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
                        }
                    }
                }
                else{
                    echo 'Status';
                }
            }
            else{
                echo 'Not Found';
            }           
        }
    }
    # -------------------------------------------------------------

    # Submit attendance creation update
    else if($transaction == 'submit attendance creation update'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['time_in_date']) && !empty($_POST['time_in_date']) && isset($_POST['time_in_time']) && !empty($_POST['time_in_time']) && isset($_POST['time_out_date']) && isset($_POST['time_out_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];
            $reason = $_POST['reason'];
            $time_in = $api->check_date('attendance empty', $_POST['time_in_date'] . ' ' . $_POST['time_in_time'], '', 'Y-m-d H:i:00', '', '', '');
            $time_out = $api->check_date('attendance empty', $_POST['time_out_date'] . ' ' . $_POST['time_out_time'], '', 'Y-m-d H:i:00', '', '', '');

            $attachment_name = $_FILES['attachment']['name'];
            $attachment_size = $_FILES['attachment']['size'];
            $attachment_error = $_FILES['attachment']['error'];
            $attachment_tmp_name = $_FILES['attachment']['tmp_name'];
            $attachment_ext = explode('.', $attachment_name);
            $attachment_actual_ext = strtolower(end($attachment_ext));

            $upload_setting_details = $api->get_upload_setting_details(11);
            $upload_file_type_details = $api->get_upload_file_type_details(11);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);
 
            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
                $attendance_creation_status = $attendance_creation_details[0]['STATUS'];

                if($attendance_creation_status == 'PEN'){
                    if(!empty($attachment_tmp_name)){
                        if(in_array($attachment_actual_ext, $allowed_ext)){
                            if(!$attachment_error){
                                if($attachment_size < $file_max_size){
                                    $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);
            
                                    if(empty($check_attendance_validation)){
                                        $update_attendance_creation = $api->update_attendance_creation($creation_id, $time_in, $time_out, $reason, $username);
            
                                        if($update_attendance_creation){
                                            $update_attendance_creation_attachment = $api->update_attendance_creation_attachment($attachment_tmp_name, $attachment_actual_ext, $creation_id, $username);
            
                                            if($update_attendance_creation_attachment){
                                                echo 'Updated';
                                            }
                                            else{
                                                echo $update_attendance_creation_attachment;
                                            }
                                        }
                                        else{
                                            echo $update_attendance_creation;
                                        }
                                    }
                                    else{
                                        echo $check_attendance_validation;
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
                        $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);
            
                        if(empty($check_attendance_validation)){
                            $update_attendance_creation = $api->update_attendance_creation($creation_id, $time_in, $time_out, $reason, $username);
            
                            if($update_attendance_creation){
                                echo 'Updated';
                            }
                            else{
                                echo $update_attendance_creation;
                            }
                        }
                        else{
                            echo $check_attendance_validation;
                        }
                    }
                }
                else{
                    echo 'Status';
                }
            }
            else{
                echo 'Not Found';
            }           
        }
    }
    # -------------------------------------------------------------

    # Submit approval type
    else if($transaction == 'submit approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && isset($_POST['approval_type']) && !empty($_POST['approval_type']) && isset($_POST['approval_type_description']) && !empty($_POST['approval_type_description'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $approval_type = $_POST['approval_type'];
            $approval_type_description = $_POST['approval_type_description'];

            $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

            if($check_approval_type_exist > 0){
                $update_approval_type = $api->update_approval_type($approval_type_id, $approval_type, $approval_type_description, $username);

                if($update_approval_type){
                    echo 'Updated';
                }
                else{
                    echo $update_approval_type;
                }
            }
            else{
                $insert_approval_type = $api->insert_approval_type($approval_type, $approval_type_description, $username);

                if($insert_approval_type){
                    echo 'Inserted';
                }
                else{
                    echo $insert_approval_type;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit approver
    else if($transaction == 'submit approver'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id']) && isset($_POST['employee']) && !empty($_POST['employee']) && isset($_POST['department']) && !empty($_POST['department'])){
            $error = '';
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $employee = $_POST['employee'];

            $departments = explode(',', $_POST['department']);

            foreach($departments as $department){
                $check_approval_type_exist = $api->check_approver_exist($approval_type_id, $employee, $department);

                if($check_approval_type_exist == 0){
                    $insert_approver = $api->insert_approver($approval_type_id, $employee, $department, $username);

                    if(!$insert_approver){
                        $error = $insert_approver;
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

    # Submit approval exception
    else if($transaction == 'submit approval exception'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id']) && isset($_POST['employee']) && !empty($_POST['employee'])){
            $error = '';
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];

            $employees = explode(',', $_POST['employee']);

            foreach($employees as $employee){
                $check_approval_exception_exist = $api->check_approval_exception_exist($approval_type_id, $employee);

                if($check_approval_exception_exist == 0){
                    $insert_approval_exception = $api->insert_approval_exception($approval_type_id, $employee, $username);

                    if(!$insert_approval_exception){
                        $error = $insert_approval_exception;
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

    # Submit public holiday
    else if($transaction == 'submit public holiday'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['public_holiday_id']) && isset($_POST['public_holiday']) && !empty($_POST['public_holiday']) && isset($_POST['holiday_type']) && !empty($_POST['holiday_type']) && isset($_POST['holiday_date']) && !empty($_POST['holiday_date']) && isset($_POST['work_location']) && !empty($_POST['work_location'])){
            $username = $_POST['username'];
            $public_holiday_id = $_POST['public_holiday_id'];
            $public_holiday = $_POST['public_holiday'];
            $holiday_type = $_POST['holiday_type'];
            $holiday_date = $api->check_date('empty', $_POST['holiday_date'], '', 'Y-m-d', '', '', '');
            $work_locations = explode(',', $_POST['work_location']);

            $check_public_holiday_exist = $api->check_public_holiday_exist($public_holiday_id);

            if($check_public_holiday_exist > 0){
                $update_public_holiday = $api->update_public_holiday($public_holiday_id, $public_holiday, $holiday_date, $holiday_type, $username);

                if($update_public_holiday){
                    $delete_all_public_holiday_work_location = $api->delete_all_public_holiday_work_location($public_holiday_id, $username);

                    if($delete_all_public_holiday_work_location){
                        foreach($work_locations as $work_location){
                            $insert_public_holiday_work_location = $api->insert_public_holiday_work_location($public_holiday_id, $work_location, $username);

                            if(!$insert_public_holiday_work_location){
                                $error = $insert_public_holiday_work_location;
                                break;
                            }
                        }
                    }
                    else{
                        $error = $delete_all_user_account_role;
                    }                 
                }
                else{
                    $error = $update_public_holiday;
                }

                if(empty($error)){
                    echo 'Updated';
                }
                else{
                    echo $error;
                }
            }
            else{
                $insert_public_holiday = $api->insert_public_holiday($public_holiday, $holiday_date, $holiday_type, $work_locations, $username);

                if($insert_public_holiday){
                    echo 'Inserted';
                }
                else{
                    $error = $insert_public_holiday;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit leave type
    else if($transaction == 'submit leave type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_type_id']) && isset($_POST['leave_type']) && !empty($_POST['leave_type']) && isset($_POST['paid_type']) && !empty($_POST['paid_type']) && isset($_POST['leave_allocation_type']) && !empty($_POST['leave_allocation_type'])){
            $username = $_POST['username'];
            $leave_type_id = $_POST['leave_type_id'];
            $leave_type = $_POST['leave_type'];
            $paid_type = $_POST['paid_type'];
            $leave_allocation_type = $_POST['leave_allocation_type'];

            $check_leave_type_exist = $api->check_leave_type_exist($leave_type_id);

            if($check_leave_type_exist > 0){
                $update_leave_type = $api->update_leave_type($leave_type_id, $leave_type, $paid_type, $leave_allocation_type, $username);

                if($update_leave_type){
                    echo 'Updated';
                }
                else{
                    echo $update_leave_type;
                }
            }
            else{
                $insert_leave_type = $api->insert_leave_type($leave_type, $paid_type, $leave_allocation_type, $username);

                if($insert_leave_type){
                    echo 'Inserted';
                }
                else{
                    echo $insert_leave_type;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit leave allocation
    else if($transaction == 'submit leave allocation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_allocation_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['leave_type']) && !empty($_POST['leave_type']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['validity_start_date']) && !empty($_POST['validity_start_date']) && isset($_POST['validity_end_date'])){
            $username = $_POST['username'];
            $leave_allocation_id = $_POST['leave_allocation_id'];
            $employee_id = $_POST['employee_id'];
            $leave_type = $_POST['leave_type'];
            $duration = $_POST['duration'];
            $validity_start_date = $api->check_date('empty', $_POST['validity_start_date'], '', 'Y-m-d', '', '', '');
            $validity_end_date = $api->check_date('empty', $_POST['validity_end_date'], '', 'Y-m-d', '', '', '');

            $check_leave_allocation_exist = $api->check_leave_allocation_exist($leave_allocation_id);

            if($check_leave_allocation_exist > 0){
                $check_date_range_validation = $api->check_date_range_validation($validity_start_date, $validity_end_date);

                if(empty($check_date_range_validation)){
                    $leave_allocation_details = $api->get_leave_allocation_details($leave_allocation_id);
                    $leave_allocation_validity_start_date = $leave_allocation_details[0]['VALIDITY_START_DATE'];
                    $leave_allocation_validity_end_date = $leave_allocation_details[0]['VALIDITY_END_DATE'];

                    if(strtotime($leave_allocation_validity_start_date) != strtotime($validity_start_date) || strtotime($leave_allocation_validity_end_date) != strtotime($validity_end_date)){    
                        $check_leave_allocation_overlap = $api->check_leave_allocation_overlap($leave_allocation_id, $validity_start_date, $validity_end_date, $employee_id, $leave_type);

                        if($check_leave_allocation_overlap == 0){
                            $update_leave_allocation = $api->update_leave_allocation($leave_allocation_id, $leave_type, $employee_id, $validity_start_date, $validity_end_date, $duration, $username);

                            if($update_leave_allocation){
                                echo 'Updated';
                            }
                            else{
                                echo $update_leave_allocation;
                            }
                        }
                        else{
                            echo 'Overlap';
                        }
                    }
                    else{
                        $update_leave_allocation = $api->update_leave_allocation($leave_allocation_id, $leave_type, $employee_id, $validity_start_date, $validity_end_date, $duration, $username);

                        if($update_leave_allocation){
                            echo 'Updated';
                        }
                        else{
                            echo $update_leave_allocation;
                        }
                    }
                }
                else{
                    echo $check_date_range_validation;
                }
            }
            else{
                $check_date_range_validation = $api->check_date_range_validation($validity_start_date, $validity_end_date);

                if(empty($check_date_range_validation)){
                    $check_leave_allocation_overlap = $api->check_leave_allocation_overlap(null, $validity_start_date, $validity_end_date, $employee_id, $leave_type);

                    if($check_leave_allocation_overlap == 0){
                        $insert_leave_allocation = $api->insert_leave_allocation($leave_type, $employee_id, $validity_start_date, $validity_end_date, $duration, $username);

                        if($insert_leave_allocation){
                            echo 'Inserted';
                        }
                        else{
                            echo $insert_leave_allocation;
                        }
                    }
                    else{
                        echo 'Overlap';
                    }
                    
                }
                else{
                    echo $check_date_range_validation;
                }
            }
        }
    }
    # -------------------------------------------------------------

    # Submit leave
    else if($transaction == 'submit leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_type']) && !empty($_POST['leave_type']) && isset($_POST['leave_date']) && !empty($_POST['leave_date']) && isset($_POST['start_time']) && !empty($_POST['start_time']) && isset($_POST['end_time']) && !empty($_POST['end_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $leave_type = $_POST['leave_type'];
            $leave_dates = explode(',', $_POST['leave_date']);
            $start_time = $api->check_date('empty', $_POST['start_time'], '', 'H:i:00', '', '', '');
            $end_time = $api->check_date('empty', $_POST['end_time'], '', 'H:i:00', '', '', '');
            $reason = $_POST['reason'];

            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

            foreach($leave_dates as $leave_date){
                $leave_date = $api->check_date('empty', $leave_date, '', 'Y-m-d', '', '', '');
                
                $check_time_validation = $api->check_time_validation($start_time, $end_time);

                if(empty($check_time_validation)){
                    $total_hours = $api->get_leave_total_hours($employee_id, $leave_date, $start_time, $end_time);

                    $insert_leave = $api->insert_leave($employee_id, $leave_type, $reason, $leave_date, $start_time, $end_time, $total_hours, $username);

                    if(!$insert_leave){
                        $error = $insert_leave;
                        break;
                    }
                }
                else{
                    $error = $check_time_validation;
                    break;
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

    # Submit leave update
    else if($transaction == 'submit leave update'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && isset($_POST['leave_type']) && !empty($_POST['leave_type']) && isset($_POST['leave_date']) && !empty($_POST['leave_date']) && isset($_POST['start_time']) && !empty($_POST['start_time']) && isset($_POST['end_time']) && !empty($_POST['end_time'])){
            $file_type = '';
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];
            $leave_type = $_POST['leave_type'];
            $leave_date = $api->check_date('empty', $_POST['leave_date'], '', 'Y-m-d', '', '', '');
            $start_time = $api->check_date('empty', $_POST['start_time'], '', 'H:i:00', '', '', '');
            $end_time = $api->check_date('empty', $_POST['end_time'], '', 'H:i:00', '', '', '');
            $reason = $_POST['reason'];

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $check_time_validation = $api->check_time_validation($start_time, $end_time);

                if(empty($check_time_validation)){
                    $employee_details = $api->get_employee_details($username);
                    $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;

                    $total_hours = $api->get_leave_total_hours($employee_id, $leave_date, $start_time, $end_time);
                    
                    $update_leave = $api->update_leave($leave_id, $leave_type, $reason, $leave_date, $start_time, $end_time, $total_hours, $username);

                    if($update_leave){
                        echo 'Updated';
                    }
                    else{
                        echo $update_leave;
                    }
                }
                else{
                    echo $check_time_validation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Submit leave supporting document
    else if($transaction == 'submit leave supporting document'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['document_name']) && !empty($_POST['document_name'])){
            $file_type = '';
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];
            $document_name = $_POST['document_name'];

            $supporting_document_name = $_FILES['supporting_document']['name'];
            $supporting_document_size = $_FILES['supporting_document']['size'];
            $supporting_document_error = $_FILES['supporting_document']['error'];
            $supporting_document_tmp_name = $_FILES['supporting_document']['tmp_name'];
            $supporting_document_ext = explode('.', $supporting_document_name);
            $supporting_document_actual_ext = strtolower(end($supporting_document_ext));

            $upload_setting_details = $api->get_upload_setting_details(12);
            $upload_file_type_details = $api->get_upload_file_type_details(12);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                if(in_array($supporting_document_actual_ext, $allowed_ext)){
                    if(!$supporting_document_error){
                        if($supporting_document_size < $file_max_size){
                            $insert_leave_supporting_document = $api->insert_leave_supporting_document($supporting_document_tmp_name, $supporting_document_actual_ext, $leave_id, $document_name, $username);
    
                            if($insert_leave_supporting_document){
                                echo 'Inserted';
                            }
                            else{
                                echo $insert_leave_supporting_document;
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
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete transactions
    # -------------------------------------------------------------

    # Delete policy
    else if($transaction == 'delete policy'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['policy_id']) && !empty($_POST['policy_id'])){
            $username = $_POST['username'];
            $policy_id = $_POST['policy_id'];

            $check_policy_exist = $api->check_policy_exist($policy_id);

            if($check_policy_exist > 0){
                $delete_all_permission = $api->delete_all_permission($policy_id, $username);
                                    
                if($delete_all_permission){
                    $delete_policy = $api->delete_policy($policy_id, $username);
                                    
                    if($delete_policy){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_policy;
                    }
                }
                else{
                    echo $delete_all_permission;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple policy
    else if($transaction == 'delete multiple policy'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['policy_id'])){
            $username = $_POST['username'];
            $policy_ids = $_POST['policy_id'];

            foreach($policy_ids as $policy_id){
                $check_policy_exist = $api->check_policy_exist($policy_id);

                if($check_policy_exist > 0){
                    $delete_policy = $api->delete_policy($policy_id, $username);
                                    
                    if($delete_policy){
                        $delete_all_permission = $api->delete_all_permission($policy_id, $username);
                                        
                        if(!$delete_all_permission){
                            $error = $delete_all_permission;
                            break;
                        }
                    }
                    else{
                        $error = $delete_policy;
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

    # Delete permission
    else if($transaction == 'delete permission'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['permission_id']) && !empty($_POST['permission_id'])){
            $username = $_POST['username'];
            $permission_id = $_POST['permission_id'];

            $check_permission_exist = $api->check_permission_exist($permission_id);

            if($check_permission_exist > 0){
                $delete_permission = $api->delete_permission($permission_id, $username);
                                    
                if($delete_permission){
                    echo 'Deleted';
                }
                else{
                    echo $delete_permission;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple permission
    else if($transaction == 'delete multiple permission'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['permission_id'])){
            $username = $_POST['username'];
            $permission_ids = $_POST['permission_id'];

            foreach($permission_ids as $permission_id){
                $check_permission_exist = $api->check_permission_exist($permission_id);

                if($check_permission_exist > 0){
                    $delete_permission = $api->delete_permission($permission_id, $username);
                                        
                    if(!$delete_permission){
                        $error = $delete_permission;
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
                    $delete_permission_role = $api->delete_permission_role($role_id, $username);
                                    
                    if($delete_permission_role){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_permission_role;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['role_id'])){
            $username = $_POST['username'];
            $role_ids = $_POST['role_id'];

            foreach($role_ids as $role_id){
                $check_role_exist = $api->check_role_exist($role_id);

                if($check_role_exist > 0){
                    $delete_role = $api->delete_role($role_id, $username);
                                    
                    if($delete_role){
                        $delete_permission_role = $api->delete_permission_role($role_id, $username);
                                        
                        if(!$delete_permission_role){
                            $error = $delete_permission_role;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['parameter_id'])){
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

    # Delete system code
    else if($transaction == 'delete system code'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_type']) && !empty($_POST['system_type']) && isset($_POST['system_code']) && !empty($_POST['system_code'])){
            $username = $_POST['username'];
            $system_type = $_POST['system_type'];
            $system_code = $_POST['system_code'];

            $check_system_code_exist = $api->check_system_code_exist($system_type, $system_code);

            if($check_system_code_exist > 0){
                $delete_system_code = $api->delete_system_code($system_type, $system_code, $username);
                                    
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['system_type']) && isset($_POST['system_code'])){
            $username = $_POST['username'];
            $system_type = $_POST['system_type'];
            $system_code = $_POST['system_code'];
            $system_type_length = count($system_type);

            for($i = 0; $i < $system_type_length; $i++){
                $check_system_code_exist = $api->check_system_code_exist($system_type[$i], $system_code[$i]);

                if($check_system_code_exist > 0){
                    $delete_system_code = $api->delete_system_code($system_type[$i], $system_code[$i], $username);
                                        
                    if(!$delete_system_code){
                        $error = $delete_system_code;
                    }
                }
                else{
                    $error = 'Not Found';
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
                    $delete_all_upload_file_type = $api->delete_all_upload_file_type($upload_setting_id, $username);
                                    
                    if($delete_all_upload_file_type){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_all_upload_file_type;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['upload_setting_id'])){
            $username = $_POST['username'];
            $upload_setting_ids = $_POST['upload_setting_id'];

            foreach($upload_setting_ids as $upload_setting_id){
                $check_upload_setting_exist = $api->check_upload_setting_exist($upload_setting_id);

                if($check_upload_setting_exist > 0){
                    $delete_upload_setting = $api->delete_upload_setting($upload_setting_id, $username);
                                    
                    if($delete_upload_setting){
                        $delete_all_upload_file_type = $api->delete_all_upload_file_type($upload_setting_id, $username);
                                    
                        if(!$delete_all_upload_file_type){
                            $error = $delete_all_upload_file_type;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['company_id'])){
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

    # Delete country
    else if($transaction == 'delete country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id']) && !empty($_POST['country_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete multiple country
    else if($transaction == 'delete multiple country'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['country_id'])){
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['state_id'])){
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

    # Delete notification setting
    else if($transaction == 'delete notification setting'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $username = $_POST['username'];
            $notification_setting_id = $_POST['notification_setting_id'];

            $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);

            if($check_notification_setting_exist > 0){
                $delete_all_notification_template = $api->delete_all_notification_template($notification_setting_id, $username);
                                    
                if($delete_all_notification_template){
                    $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                    
                    if($delete_all_notification_user_account_recipient){
                        $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                    
                        if($delete_all_notification_role_recipient){
                            $delete_all_notification_channel = $api->delete_all_notification_channel($notification_setting_id, $username);
                                    
                            if($delete_all_notification_channel){
                                $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                                
                                if($delete_notification_setting){
                                    echo 'Deleted';
                                }
                                else{
                                    echo $delete_notification_setting;
                                }
                            }
                            else{
                                echo $delete_all_notification_channel;
                            }
                        }
                        else{
                            echo $delete_all_notification_role_recipient;
                        }
                    }
                    else{
                        echo $delete_all_notification_user_account_recipient;
                    }
                }
                else{
                    echo $delete_all_notification_template;
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
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['notification_setting_id'])){
            $username = $_POST['username'];
            $notification_setting_ids = $_POST['notification_setting_id'];

            foreach($notification_setting_ids as $notification_setting_id){
                $check_notification_setting_exist = $api->check_notification_setting_exist($notification_setting_id);

                if($check_notification_setting_exist > 0){
                    $delete_notification_setting = $api->delete_notification_setting($notification_setting_id, $username);
                                    
                    if($delete_notification_setting){
                        $delete_all_notification_template = $api->delete_all_notification_template($notification_setting_id, $username);
                                        
                        if($delete_all_notification_template){
                            $delete_all_notification_user_account_recipient = $api->delete_all_notification_user_account_recipient($notification_setting_id, $username);
                                        
                            if($delete_all_notification_user_account_recipient){
                                $delete_all_notification_role_recipient = $api->delete_all_notification_role_recipient($notification_setting_id, $username);
                                            
                                if(!$delete_all_notification_role_recipient){
                                    $error = $delete_all_notification_role_recipient;
                                    break;
                                }
                            }
                            else{
                                $error = $delete_all_notification_user_account_recipient;
                                break;
                            }
                        }
                        else{
                            $error = $delete_all_notification_template;
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

    # Delete department
    else if($transaction == 'delete department'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['department_id']) && !empty($_POST['department_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete multiple department
    else if($transaction == 'delete multiple department'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['department_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete job position
    else if($transaction == 'delete job position'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            $username = $_POST['username'];
            $job_position_id = $_POST['job_position_id'];

            $check_job_position_exist = $api->check_job_position_exist($job_position_id);

            if($check_job_position_exist > 0){
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

    # Delete multiple job position
    else if($transaction == 'delete multiple job position'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['job_position_id'])){
            $username = $_POST['username'];
            $job_position_ids = $_POST['job_position_id'];

            foreach($job_position_ids as $job_position_id){
                $check_job_position_exist = $api->check_job_position_exist($job_position_id);

                if($check_job_position_exist > 0){
                    $delete_job_position = $api->delete_job_position($job_position_id, $username);
                                    
                    if(!$delete_job_position){
                        $error = $delete_job_position;
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

    # Delete work location
    else if($transaction == 'delete work location'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete multiple work location
    else if($transaction == 'delete multiple work location'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['work_location_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete departure reason
    else if($transaction == 'delete departure reason'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete multiple departure reason
    else if($transaction == 'delete multiple departure reason'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['departure_reason_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete employee type
    else if($transaction == 'delete employee type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete multiple employee type
    else if($transaction == 'delete multiple employee type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_type_id'])){
            $username = $_POST['username'];
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
    # -------------------------------------------------------------

    # Delete employee
    else if($transaction == 'delete employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
            $username = $_POST['username'];
            $employee_id = $_POST['employee_id'];

            $check_employee_exist = $api->check_employee_exist($employee_id);

            if($check_employee_exist > 0){
                $delete_employee = $api->delete_employee($employee_id, $username);
                                    
                if($delete_employee){
                    echo 'Deleted';
                }
                else{
                    echo $delete_employee;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple employee
    else if($transaction == 'delete multiple employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id'])){
            $username = $_POST['username'];
            $employee_ids = $_POST['employee_id'];

            foreach($employee_ids as $employee_id){
                $check_employee_exist = $api->check_employee_exist($employee_id);

                if($check_employee_exist > 0){
                    $delete_employee = $api->delete_employee($employee_id, $username);
                                    
                    if(!$delete_employee){
                        $error = $delete_employee;
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

    # Delete working hours
    else if($transaction == 'delete working hours'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id'])){
            $username = $_POST['username'];
            $working_hours_id = $_POST['working_hours_id'];

            $check_working_hours_exist = $api->check_working_hours_exist($working_hours_id);

            if($check_working_hours_exist > 0){
                $delete_working_hours = $api->delete_working_hours($working_hours_id, $username);
                                    
                if($delete_working_hours){
                    $delete_working_hours_schedule = $api->delete_working_hours_schedule($working_hours_id, $username);
                                    
                    if($delete_working_hours_schedule){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_working_hours_schedule;
                    }
                }
                else{
                    echo $delete_working_hours;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple working hours
    else if($transaction == 'delete multiple working hours'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['working_hours_id'])){
            $username = $_POST['username'];
            $working_hours_ids = $_POST['working_hours_id'];

            foreach($working_hours_ids as $working_hours_id){
                $check_working_hours_exist = $api->check_working_hours_exist($working_hours_id);

                if($check_working_hours_exist > 0){
                    $delete_working_hours = $api->delete_working_hours($working_hours_id, $username);
                                    
                    if($delete_working_hours){
                        $delete_working_hours_schedule = $api->delete_working_hours_schedule($working_hours_id, $username);
                                    
                        if(!$delete_working_hours_schedule){
                            $error = $delete_working_hours_schedule;
                            break;
                        }
                    }
                    else{
                        $error = $delete_working_hours;
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

    # Delete attendance
    else if($transaction == 'delete attendance'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id']) && !empty($_POST['attendance_id'])){
            $username = $_POST['username'];
            $attendance_id = $_POST['attendance_id'];

            $check_attendance_exist = $api->check_attendance_exist($attendance_id);

            if($check_attendance_exist > 0){
                $delete_attendance = $api->delete_attendance($attendance_id, $username);
                                    
                if($delete_attendance){
                    echo 'Deleted';
                }
                else{
                    echo $delete_attendance;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple attendance
    else if($transaction == 'delete multiple attendance'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['attendance_id'])){
            $username = $_POST['username'];
            $attendance_ids = $_POST['attendance_id'];

            foreach($attendance_ids as $attendance_id){
                $check_attendance_exist = $api->check_attendance_exist($attendance_id);

                if($check_attendance_exist > 0){
                    $delete_attendance = $api->delete_attendance($attendance_id, $username);
                                    
                    if(!$delete_attendance){
                        $error = $delete_attendance;
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

    # Delete attendance adjustment
    else if($transaction == 'delete attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $delete_attendance_adjustment = $api->delete_attendance_adjustment($adjustment_id, $username);
                                    
                if($delete_attendance_adjustment){
                    echo 'Deleted';
                }
                else{
                    echo $delete_attendance_adjustment;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple attendance adjustment
    else if($transaction == 'delete multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_ids = $_POST['adjustment_id'];

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $delete_attendance_adjustment = $api->delete_attendance_adjustment($adjustment_id, $username);
                                    
                    if(!$delete_attendance_adjustment){
                        $error = $delete_attendance_adjustment;
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

    # Delete attendance creation
    else if($transaction == 'delete attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $delete_attendance_creation = $api->delete_attendance_creation($creation_id, $username);
                                    
                if($delete_attendance_creation){
                    echo 'Deleted';
                }
                else{
                    echo $delete_attendance_creation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple attendance creation
    else if($transaction == 'delete multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_ids = $_POST['creation_id'];

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $delete_attendance_creation = $api->delete_attendance_creation($creation_id, $username);
                                    
                    if(!$delete_attendance_creation){
                        $error = $delete_attendance_creation;
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

    # Delete approval type
    else if($transaction == 'delete approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];

            $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

            if($check_approval_type_exist > 0){
                $delete_all_approval_approver = $api->delete_all_approval_approver($approval_type_id, $username);
                                    
                if($delete_all_approval_approver){
                    $delete_all_approval_exception = $api->delete_all_approval_exception($approval_type_id, $username);
                                    
                    if($delete_all_approval_exception){
                        $delete_approval_type = $api->delete_approval_type($approval_type_id, $username);
                                        
                        if($delete_approval_type){
                            echo 'Deleted';
                        }
                        else{
                            echo $delete_approval_type;
                        }
                    }
                    else{
                        echo $delete_all_approval_exception;
                    }
                }
                else{
                    echo $delete_all_approval_approver;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple approval type
    else if($transaction == 'delete multiple approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_ids = $_POST['approval_type_id'];

            foreach($approval_type_ids as $approval_type_id){
                $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

                if($check_approval_type_exist > 0){
                    $delete_approval_type = $api->delete_approval_type($approval_type_id, $username);
                                    
                    if($delete_approval_type){
                        $delete_all_approval_approver = $api->delete_all_approval_approver($approval_type_id, $username);
                                    
                        if($delete_all_approval_approver){
                            $delete_all_approval_exception = $api->delete_all_approval_exception($approval_type_id, $username);
                                            
                            if(!$delete_all_approval_exception){
                                $error = $delete_all_approval_exception;
                                break;
                            }
                        }
                        else{
                            $error = $delete_all_approval_approver;
                            break;
                        }
                    }
                    else{
                        $error = $delete_approval_type;
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

    # Delete approver
    else if($transaction == 'delete approver'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['department']) && !empty($_POST['department'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $employee_id = $_POST['employee_id'];
            $department = $_POST['department'];

            $check_approver_exist = $api->check_approver_exist($approval_type_id, $employee_id, $department);

            if($check_approver_exist > 0){
                $delete_approver = $api->delete_approver($approval_type_id, $employee_id, $department, $username);
                                        
                if($delete_approver){
                    echo 'Deleted';
                }
                else{
                    echo $delete_approver;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple approver
    else if($transaction == 'delete multiple approver'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['department']) && !empty($_POST['department'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $employee_id = $_POST['employee_id'];
            $department = $_POST['department'];
            $employee_length = count($employee_id);

            for($i = 0; $i < $employee_length; $i++){
                $check_approver_exist = $api->check_approver_exist($approval_type_id, $employee_id[$i], $department[$i]);

                if($check_approver_exist > 0){
                    $delete_approver = $api->delete_approver($approval_type_id, $employee_id[$i], $department[$i], $username);
                                        
                    if(!$delete_approver){
                        $error = $delete_approver;
                    }
                }
                else{
                    $error = 'Not Found';
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

    # Delete approval exception
    else if($transaction == 'delete approval exception'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $employee_id = $_POST['employee_id'];

            $check_approval_exception_exist = $api->check_approval_exception_exist($approval_type_id, $employee_id);

            if($check_approval_exception_exist > 0){
                $delete_approval_exception = $api->delete_approval_exception($approval_type_id, $employee_id, $username);
                                        
                if($delete_approval_exception){
                    echo 'Deleted';
                }
                else{
                    echo $delete_approval_exception;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple approval exception
    else if($transaction == 'delete multiple approval exception'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];
            $employee_ids = $_POST['employee_id'];

            foreach($employee_ids as $employee_id){
                $check_approval_exception_exist = $api->check_approval_exception_exist($approval_type_id, $employee_id);

                if($check_approval_exception_exist > 0){
                    $delete_approval_exception = $api->delete_approval_exception($approval_type_id, $employee_id, $username);
                                    
                    if(!$delete_approval_exception){
                        $error = $delete_approval_exception;
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

    # Delete public holiday
    else if($transaction == 'delete public holiday'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['public_holiday_id']) && !empty($_POST['public_holiday_id'])){
            $username = $_POST['username'];
            $public_holiday_id = $_POST['public_holiday_id'];

            $check_public_holiday_exist = $api->check_public_holiday_exist($public_holiday_id);

            if($check_public_holiday_exist > 0){
                $delete_all_public_holiday_work_location = $api->delete_all_public_holiday_work_location($public_holiday_id, $username);

                if($delete_all_public_holiday_work_location){
                    $delete_public_holiday = $api->delete_public_holiday($public_holiday_id, $username);
                                    
                    if($delete_public_holiday){
                        echo 'Deleted';
                    }
                    else{
                        echo $delete_public_holiday;
                    }
                }
                else{
                    echo $delete_all_public_holiday_work_location;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple public holiday
    else if($transaction == 'delete multiple public holiday'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['public_holiday_id'])){
            $username = $_POST['username'];
            $public_holiday_ids = $_POST['public_holiday_id'];

            foreach($public_holiday_ids as $public_holiday_id){
                $check_public_holiday_exist = $api->check_public_holiday_exist($public_holiday_id);

                if($check_public_holiday_exist > 0){
                    $delete_all_public_holiday_work_location = $api->delete_all_public_holiday_work_location($public_holiday_id, $username);

                    if($delete_all_public_holiday_work_location){
                        $delete_public_holiday = $api->delete_public_holiday($public_holiday_id, $username);
                                        
                        if(!$delete_public_holiday){
                            $error = $delete_public_holiday;
                            break;
                        }
                    }
                    else{
                        $error = $delete_all_public_holiday_work_location;
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

    # Delete leave type
    else if($transaction == 'delete leave type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_type_id']) && !empty($_POST['leave_type_id'])){
            $username = $_POST['username'];
            $leave_type_id = $_POST['leave_type_id'];

            $check_leave_type_exist = $api->check_leave_type_exist($leave_type_id);

            if($check_leave_type_exist > 0){
                $delete_leave_type = $api->delete_leave_type($leave_type_id, $username);
                                    
                if($delete_leave_type){
                    echo 'Deleted';
                }
                else{
                    echo $delete_leave_type;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple leave type
    else if($transaction == 'delete multiple leave type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_type_id'])){
            $username = $_POST['username'];
            $leave_type_ids = $_POST['leave_type_id'];

            foreach($leave_type_ids as $leave_type_id){
                $check_leave_type_exist = $api->check_leave_type_exist($leave_type_id);

                if($check_leave_type_exist > 0){
                    $delete_leave_type = $api->delete_leave_type($leave_type_id, $username);
                                    
                    if(!$delete_leave_type){
                        $error = $delete_leave_type;
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

    # Delete leave allocation
    else if($transaction == 'delete leave allocation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_allocation_id']) && !empty($_POST['leave_allocation_id'])){
            $username = $_POST['username'];
            $leave_allocation_id = $_POST['leave_allocation_id'];

            $check_leave_allocation_exist = $api->check_leave_allocation_exist($leave_allocation_id);

            if($check_leave_allocation_exist > 0){
                $delete_leave_allocation = $api->delete_leave_allocation($leave_allocation_id, $username);
                                    
                if($delete_leave_allocation){
                    echo 'Deleted';
                }
                else{
                    echo $delete_leave_allocation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple leave allocation
    else if($transaction == 'delete multiple leave allocation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_allocation_id'])){
            $username = $_POST['username'];
            $leave_allocation_ids = $_POST['leave_allocation_id'];

            foreach($leave_allocation_ids as $leave_allocation_id){
                $check_leave_allocation_exist = $api->check_leave_allocation_exist($leave_allocation_id);

                if($check_leave_allocation_exist > 0){
                    $delete_leave_allocation = $api->delete_leave_allocation($leave_allocation_id, $username);
                                    
                    if(!$delete_leave_allocation){
                        $error = $delete_leave_allocation;
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

    # Delete leave
    else if($transaction == 'delete leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $delete_leave = $api->delete_leave($leave_id, $username);
                                    
                if($delete_leave){
                    echo 'Deleted';
                }
                else{
                    echo $delete_leave;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Delete multiple leave
    else if($transaction == 'delete multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_ids = $_POST['leave_id'];

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $delete_leave = $api->delete_leave($leave_id, $username);
                                    
                    if(!$delete_leave){
                        $error = $delete_leave;
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

    # Delete leave supporting document
    else if($transaction == 'delete leave supporting document'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_supporting_document_id']) && !empty($_POST['leave_supporting_document_id'])){
            $username = $_POST['username'];
            $leave_supporting_document_id = $_POST['leave_supporting_document_id'];

            $check_leave_supporting_document_exist = $api->check_leave_supporting_document_exist($leave_supporting_document_id);

            if($check_leave_supporting_document_exist > 0){
                $delete_leave_supporting_document = $api->delete_leave_supporting_document($leave_supporting_document_id, $username);
                                    
                if($delete_leave_supporting_document){
                    echo 'Deleted';
                }
                else{
                    echo $delete_leave_supporting_document;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unlock transactions
    # -------------------------------------------------------------

    # Unlock user account
    else if($transaction == 'unlock user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $username = $_POST['username'];
            $user_code = $_POST['user_code'];

            $check_user_account_exist = $api->check_user_account_exist($user_code);

            if($check_user_account_exist > 0){
                $update_user_account_lock_status = $api->update_user_account_lock_status($user_code, 'unlock', $system_date, $username);
    
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
    # -------------------------------------------------------------

    # Unlock multiple user account
    else if($transaction == 'unlock multiple user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code'])){
            $username = $_POST['username'];
            $user_codes = $_POST['user_code'];

            foreach($user_codes as $user_code){
                $check_user_account_exist = $api->check_user_account_exist($user_code);

                if($check_user_account_exist > 0){
                    $update_user_account_lock_status = $api->update_user_account_lock_status($user_code, 'unlock', $system_date, $username);
                                    
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Lock transactions
    # -------------------------------------------------------------

    # Lock user account
    else if($transaction == 'lock user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $username = $_POST['username'];
            $user_code = $_POST['user_code'];

            $check_user_account_exist = $api->check_user_account_exist($user_code);

            if($check_user_account_exist > 0){
                $update_user_account_lock_status = $api->update_user_account_lock_status($user_code, 'lock', $system_date, $username);
    
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
    # -------------------------------------------------------------

    # Lock multiple user account
    else if($transaction == 'lock multiple user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code'])){
            $username = $_POST['username'];
            $user_codes = $_POST['user_code'];

            foreach($user_codes as $user_code){
                $check_user_account_exist = $api->check_user_account_exist($user_code);

                if($check_user_account_exist > 0){
                    $update_user_account_lock_status = $api->update_user_account_lock_status($user_code, 'lock', $system_date, $username);
                                    
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Activate transactions
    # -------------------------------------------------------------

    # Activate user account
    else if($transaction == 'activate user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $username = $_POST['username'];
            $user_code = $_POST['user_code'];

            $check_user_account_exist = $api->check_user_account_exist($user_code);

            if($check_user_account_exist > 0){
                $update_user_account_status = $api->update_user_account_status($user_code, 'ACTIVE', $username);
    
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
    # -------------------------------------------------------------

    # Activate multiple user account
    else if($transaction == 'activate multiple user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code'])){
            $username = $_POST['username'];
            $user_codes = $_POST['user_code'];

            foreach($user_codes as $user_code){
                $check_user_account_exist = $api->check_user_account_exist($user_code);

                if($check_user_account_exist > 0){
                    $update_user_account_status = $api->update_user_account_status($user_code, 'ACTIVE', $username);
                                    
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
    # -------------------------------------------------------------

    # Activate approval type
    else if($transaction == 'activate approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];

            $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

            if($check_approval_type_exist > 0){
                $update_approval_type_status = $api->update_approval_type_status($approval_type_id, 'ACTIVE', $username);
    
                if($update_approval_type_status){
                    echo 'Activated';
                }
                else{
                    echo $update_approval_type_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Activate multiple approval type
    else if($transaction == 'activate multiple approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_ids = $_POST['approval_type_id'];

            foreach($approval_type_ids as $approval_type_id){
                $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

                if($check_approval_type_exist > 0){
                    $update_approval_type_status = $api->update_approval_type_status($approval_type_id, 'ACTIVE', $username);
                                    
                    if(!$update_approval_type_status){
                        $error = $update_approval_type_status;
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
    # -------------------------------------------------------------
     
    # -------------------------------------------------------------
    #   Deactivate transactions
    # -------------------------------------------------------------

    # Deactivate user account
    else if($transaction == 'deactivate user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $username = $_POST['username'];
            $user_code = $_POST['user_code'];

            $check_user_account_exist = $api->check_user_account_exist($user_code);

            if($check_user_account_exist > 0){
                $update_user_account_status = $api->update_user_account_status($user_code, 'INACTIVE', $username);
    
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
    # -------------------------------------------------------------

    # Deactivate multiple user account
    else if($transaction == 'deactivate multiple user account'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['user_code'])){
            $username = $_POST['username'];
            $user_codes = $_POST['user_code'];

            foreach($user_codes as $user_code){
                $check_user_account_exist = $api->check_user_account_exist($user_code);

                if($check_user_account_exist > 0){
                    $update_user_account_status = $api->update_user_account_status($user_code, 'INACTIVE', $username);
                                    
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
    # -------------------------------------------------------------

    # Deactivate approval type
    else if($transaction == 'deactivate approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_id = $_POST['approval_type_id'];

            $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

            if($check_approval_type_exist > 0){
                $update_approval_type_status = $api->update_approval_type_status($approval_type_id, 'INACTIVE', $username);
    
                if($update_approval_type_status){
                    echo 'Deactivated';
                }
                else{
                    echo $update_approval_type_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Deactivate multiple approval type
    else if($transaction == 'deactivate multiple approval type'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['approval_type_id'])){
            $username = $_POST['username'];
            $approval_type_ids = $_POST['approval_type_id'];

            foreach($approval_type_ids as $approval_type_id){
                $check_approval_type_exist = $api->check_approval_type_exist($approval_type_id);

                if($check_approval_type_exist > 0){
                    $update_approval_type_status = $api->update_approval_type_status($approval_type_id, 'INACTIVE', $username);
                                    
                    if(!$update_approval_type_status){
                        $error = $update_approval_type_status;
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Archive transactions
    # -------------------------------------------------------------

    # Archive employee
    else if($transaction == 'archive employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['departure_reason']) && !empty($_POST['departure_reason']) && isset($_POST['departure_date']) && !empty($_POST['departure_date']) && isset($_POST['detailed_reason'])){
            $username = $_POST['username'];
            $employee_id = $_POST['employee_id'];
            $departure_reason = $_POST['departure_reason'];
            $departure_date = $api->check_date('empty', $_POST['departure_date'], '', 'Y-m-d', '', '', '');
            $detailed_reason = $_POST['detailed_reason'];

            $check_employee_exist = $api->check_employee_exist($employee_id);

            if($check_employee_exist > 0){
                $update_employee_status = $api->update_employee_status($employee_id, 'ARCHIVED', $departure_date, $departure_reason, $detailed_reason, $username);
    
                if($update_employee_status){
                    echo 'Archived';
                }
                else{
                    echo $update_employee_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
        else{
            echo $_POST['departure_reason'];
        }
    }
    # -------------------------------------------------------------

    # Archive multiple employee
    else if($transaction == 'archive multiple employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['departure_reason']) && !empty($_POST['departure_reason']) && isset($_POST['departure_date']) && !empty($_POST['departure_date']) && isset($_POST['detailed_reason'])){
            $username = $_POST['username'];
            $employee_ids = explode(',', $_POST['employee_id']);
            $departure_reason = $_POST['departure_reason'];
            $departure_date = $api->check_date('empty', $_POST['departure_date'], '', 'Y-m-d', '', '', '');
            $detailed_reason = $_POST['detailed_reason'];

            foreach($employee_ids as $employee_id){
                $check_employee_exist = $api->check_employee_exist($employee_id);

                if($check_employee_exist > 0){
                    $update_employee_status = $api->update_employee_status($employee_id, 'ARCHIVED', $departure_date, $departure_reason, $detailed_reason, $username);
                                    
                    if(!$update_employee_status){
                        $error = $update_employee_status;
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
    # -------------------------------------------------------------
     
    # -------------------------------------------------------------
    #   Unarchive transactions
    # -------------------------------------------------------------

    # Unarchive employee
    else if($transaction == 'unarchive employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
            $username = $_POST['username'];
            $employee_id = $_POST['employee_id'];

            $check_employee_exist = $api->check_employee_exist($employee_id);

            if($check_employee_exist > 0){
                $update_employee_status = $api->update_employee_status($employee_id, 'ACTIVE', null, null, null, $username);
    
                if($update_employee_status){
                    echo 'Unarchived';
                }
                else{
                    echo $update_employee_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Unarchive multiple employee
    else if($transaction == 'unarchive multiple employee'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['employee_id'])){
            $username = $_POST['username'];
            $employee_ids = $_POST['employee_id'];

            foreach($employee_ids as $employee_id){
                $check_employee_exist = $api->check_employee_exist($employee_id);

                if($check_employee_exist > 0){
                    $update_employee_status = $api->update_employee_status($employee_id, 'ACTIVE', null, null, null, $username);
                                    
                    if(!$update_employee_status){
                        $error = $update_employee_status;
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Cancel transactions
    # -------------------------------------------------------------

    # Cancel attendance adjustment
    else if($transaction == 'cancel attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(7);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
                $employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'CAN', $decision_remarks, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(7, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Cancelled';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Cancel multiple attendance adjustment
    else if($transaction == 'cancel multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_ids = explode(',', $_POST['adjustment_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(7);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
					$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'CAN', $decision_remarks, null, $username);
        
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(7, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Cancelled';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Cancel attendance creation
    else if($transaction == 'cancel attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(12);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'CAN', $decision_remarks, null, $username);
    
                if($update_attendance_creation_status){
                    $send_notification = $api->send_notification(12, null, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Cancelled';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_creation_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Cancel multiple attendance creation
    else if($transaction == 'cancel multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_ids = explode(',', $_POST['creation_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(12);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
					$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];
                    
                    $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'CAN', $decision_remarks, null, $username);
        
                    if($update_attendance_creation_status){
                        $send_notification = $api->send_notification(12, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_creation_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Cancelled';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Cancel leave
    else if($transaction == 'cancel leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(16);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $leave_details = $api->get_leave_details($leave_id);
				$employee_id = $leave_details[0]['EMPLOYEE_ID'];
				$leave_status = $leave_details[0]['STATUS'];

                $update_leave_status = $api->update_leave_status($leave_id, 'CAN', $decision_remarks, $username);
    
                if($update_leave_status){
                    if($leave_status == 'FA' || $leave_status == 'APV'){
                        $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'CAN', $username);

                        if($update_employee_leave_allocation){
                            $send_notification = $api->send_notification(16, null, $employee_id, $notification_title, $notification_message, $username);
    
                            if($send_notification){
                                echo 'Cancelled';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                        else{
                            echo $update_employee_leave_allocation;
                        }
                    }
                    else{
                        $send_notification = $api->send_notification(16, null, $employee_id, $notification_title, $notification_message, $username);
    
                        if($send_notification){
                            echo 'Cancelled';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                }
                else{
                    echo $update_leave_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Cancel multiple leave
    else if($transaction == 'cancel multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_ids = explode(',', $_POST['leave_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(16);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $leave_details = $api->get_leave_details($leave_id);
                    $employee_id = $leave_details[0]['EMPLOYEE_ID'];
                    $leave_status = $leave_details[0]['STATUS'];

                    $update_leave_status = $api->update_leave_status($leave_id, 'CAN', $decision_remarks, $username);
        
                    if($update_leave_status){
                        if($leave_status == 'FA' || $leave_status == 'APV'){
                            $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'CAN', $username);

                            if($update_employee_leave_allocation){
                                $send_notification = $api->send_notification(16, null, $employee_id, $notification_title, $notification_message, $username);
    
                                if(!$send_notification){
                                    $error = $send_notification;
                                    break;
                                }
                            }
                            else{
                                $error = $update_employee_leave_allocation;
                                break;
                            }
                        }
                        else{
                            $send_notification = $api->send_notification(16, null, $employee_id, $notification_title, $notification_message, $username);
    
                            if(!$send_notification){
                                $error = $send_notification;
                                break;
                            }
                        }
                    }
                    else{
                        $error = $update_leave_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Cancelled';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   For approval transactions
    # -------------------------------------------------------------

    # For approval leave
    else if($transaction == 'for approval leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(15);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $leave_details = $api->get_leave_details($leave_id);
				$employee_id = $leave_details[0]['EMPLOYEE_ID'] ?? null;
                $leave_type_id = $leave_details[0]['LEAVE_TYPE_ID'] ?? null;
                
                $check_employee_leave_allocation = $api->check_employee_leave_allocation($leave_id, $employee_id);

                if($check_employee_leave_allocation > 0){
                    $update_leave_status = $api->update_leave_status($leave_id, 'FA', null, $username);
    
                    if($update_leave_status){
                        $leave_type_details = $api->get_leave_type_details($leave_type_id);
                        $allocation_type = $leave_type_details[0]['ALLOCATION_TYPE'] ?? null;

                        if($allocation_type == 'LIMITED'){
                            $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'FA', $username);

                            if($update_employee_leave_allocation){
                                $send_notification = $api->send_notification(15, $approver_id, $employee_id, $notification_title, $notification_message, $username);
    
                                if($send_notification){
                                    echo 'For Approval';
                                }
                                else{
                                    echo $send_notification;
                                }
                            }
                            else{
                                echo $update_employee_leave_allocation;
                            }
                        }
                        else{
                            $send_notification = $api->send_notification(15, $approver_id, $employee_id, $notification_title, $notification_message, $username);
    
                            if($send_notification){
                                echo 'For Approval';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                    }
                    else{
                        echo $update_leave_status;
                    }
                }
                else{
                    echo 'Leave Allocation';
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # For approval multiple leave
    else if($transaction == 'for approval multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_ids = $_POST['leave_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(15);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $leave_details = $api->get_leave_details($leave_id);
					$employee_id = $leave_details[0]['EMPLOYEE_ID'] ?? null;
                    $leave_type_id = $leave_details[0]['LEAVE_TYPE_ID'] ?? null;

                    $check_employee_leave_allocation = $api->check_employee_leave_allocation($leave_id, $employee_id);

                    if($check_employee_leave_allocation > 0){
                        $update_leave_status = $api->update_leave_status($leave_id, 'FA', null, $username);
            
                        if($update_leave_status){
                            $leave_type_details = $api->get_leave_type_details($leave_type_id);
                            $allocation_type = $leave_type_details[0]['ALLOCATION_TYPE'] ?? null;

                            if($allocation_type == 'LIMITED'){
                                $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'FA', $username);
    
                                if($update_employee_leave_allocation){
                                    $send_notification = $api->send_notification(15, $approver_id, $employee_id, $notification_title, $notification_message, $username);
        
                                    if(!$send_notification){
                                        $error = $send_notification;
                                        break;
                                    }
                                }
                                else{
                                    $error = $update_employee_leave_allocation;
                                    break;
                                }
                            }
                            else{
                                $send_notification = $api->send_notification(15, $approver_id, $employee_id, $notification_title, $notification_message, $username);
        
                                if(!$send_notification){
                                    $error = $send_notification;
                                    break;
                                }
                            }                            
                        }
                        else{
                            $error = $update_leave_status;
                            break;
                        }
                    }
                    else{
                        $error = 'Leave Allocation';
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'For Approval';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   For recommendation transactions
    # -------------------------------------------------------------

    # For recommendation attendance adjustment
    else if($transaction == 'for recommendation attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(3);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'FORREC', null, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(3, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'For Recommendation';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # For recommendation multiple attendance adjustment
    else if($transaction == 'for recommendation multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_ids = $_POST['adjustment_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(3);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
					$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'FORREC', null, null, $username);
            
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(3, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'For Recommendation';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # For recommendation attendance creation
    else if($transaction == 'for recommendation attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(8);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'FORREC', null, null, $username);
    
                if($update_attendance_creation_status){
                    $send_notification = $api->send_notification(8, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'For Recommendation';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_creation_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # For recommendation multiple attendance creation
    else if($transaction == 'for recommendation multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_ids = $_POST['creation_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(8);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
					$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                    $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'FORREC', null, null, $username);
        
                    if($update_attendance_creation_status){
                        $send_notification = $api->send_notification(8, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_creation_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'For Recommendation';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Reject transactions
    # -------------------------------------------------------------

    # Reject attendance adjustment
    else if($transaction == 'reject attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(6);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REJ', $decision_remarks, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(6, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Rejected';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Reject multiple attendance adjustment
    else if($transaction == 'reject multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_ids = explode(',', $_POST['adjustment_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';
            
            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(6);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				    $employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REJ', $decision_remarks, null, $username);
        
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(6, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Rejected';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Reject attendance creation
    else if($transaction == 'reject attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(11);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'REJ', $decision_remarks, null, $username);
    
                if($update_attendance_creation_status){
                    $send_notification = $api->send_notification(11, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Rejected';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_creation_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Reject multiple attendance creation
    else if($transaction == 'reject multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_ids = explode(',', $_POST['creation_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(11);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				    $employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                    $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'REJ', $decision_remarks, null, $username);
        
                    if($update_attendance_creation_status){
                        $send_notification = $api->send_notification(11, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_creation_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Rejected';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Reject leave
    else if($transaction == 'reject leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(18);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $leave_details = $api->get_leave_details($leave_id);
				$employee_id = $leave_details[0]['EMPLOYEE_ID'];

                $update_leave_status = $api->update_leave_status($leave_id, 'REJ', $decision_remarks, $username);
    
                if($update_leave_status){
                    $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'REJ', $username);

                    if($update_employee_leave_allocation){
                        $send_notification = $api->send_notification(18, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if($send_notification){
                            echo 'Rejected';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                    else{
                        echo $update_employee_leave_allocation;
                    }
                }
                else{
                    echo $update_leave_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Reject multiple leave
    else if($transaction == 'reject multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_ids = explode(',', $_POST['leave_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(18);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $leave_details = $api->get_leave_details($leave_id);
				    $employee_id = $leave_details[0]['EMPLOYEE_ID'];

                    $update_leave_status = $api->update_leave_status($leave_id, 'REJ', $decision_remarks, $username);
        
                    if($update_leave_status){
                        $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'REJ', $username);

                        if($update_employee_leave_allocation){
                            $send_notification = $api->send_notification(18, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                            if(!$send_notification){
                                $error = $send_notification;
                                break;
                            }
                        }
                        else{
                            $error = $update_employee_leave_allocation;
                            break;
                        }
                    }
                    else{
                        $error = $update_leave_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Rejected';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Recommend transactions
    # -------------------------------------------------------------

    # Recommend attendance adjustment
    else if($transaction == 'recommend attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(4);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REC', $decision_remarks, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(4, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Recommended';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Recommend multiple attendance adjustment
    else if($transaction == 'recommend multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_ids = explode(',', $_POST['adjustment_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(4);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
					$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REC', $decision_remarks, null, $username);
        
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(4, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Recommended';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Recommend attendance creation
    else if($transaction == 'recommend attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(9);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'REC', $decision_remarks, null, $username);
    
                if($update_attendance_creation_status){
                    $send_notification = $api->send_notification(9, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Recommended';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_creation_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Recommend multiple attendance creation
    else if($transaction == 'recommend multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_ids = explode(',', $_POST['creation_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(9);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
					$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                    $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'REC', $decision_remarks, null, $username);
        
                    if($update_attendance_creation_status){
                        $send_notification = $api->send_notification(9, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_creation_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Recommended';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Pending transactions
    # -------------------------------------------------------------

    # Pending attendance adjustment
    else if($transaction == 'pending attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(13);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'PEN', null, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(13, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Pending';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Pending multiple attendance adjustment
    else if($transaction == 'pending multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $username = $_POST['username'];
            $adjustment_ids = $_POST['adjustment_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(14);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
					$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'PEN', null, null, $username);
            
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(14, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Pending';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Pending attendance creation
    else if($transaction == 'pending attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(8);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
				$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'PEN', null, null, $username);
    
                if($update_attendance_creation_status){
                    $send_notification = $api->send_notification(8, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Pending';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_creation_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Pending multiple attendance creation
    else if($transaction == 'pending multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $username = $_POST['username'];
            $creation_ids = $_POST['creation_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(8);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
					$employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];

                    $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'PEN', null, null, $username);
        
                    if($update_attendance_creation_status){
                        $send_notification = $api->send_notification(8, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_creation_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Pending';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Pending leave
    else if($transaction == 'pending leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(19);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $leave_details = $api->get_leave_details($leave_id);
				$employee_id = $leave_details[0]['EMPLOYEE_ID'];

                $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'PEN', $username);

                if($update_employee_leave_allocation){
                    $update_leave_status = $api->update_leave_status($leave_id, 'PEN', null, $username);
    
                    if($update_leave_status){
                        $send_notification = $api->send_notification(19, $approver_id, $employee_id, $notification_title, $notification_message, $username);
    
                        if($send_notification){
                            echo 'Pending';
                        }
                        else{
                            echo $send_notification;
                        }
                    }
                    else{
                        echo $update_leave_status;
                    }
                }
                else{
                    echo $update_employee_leave_allocation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Pending multiple leave
    else if($transaction == 'pending multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $username = $_POST['username'];
            $leave_ids = $_POST['leave_id'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(19);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $leave_details = $api->get_leave_details($leave_id);
					$employee_id = $leave_details[0]['EMPLOYEE_ID'];

                    $update_employee_leave_allocation = $api->update_employee_leave_allocation($leave_id, $employee_id, 'PEN', $username);

                    if($update_employee_leave_allocation){
                        $update_leave_status = $api->update_leave_status($leave_id, 'PEN', null, $username);
        
                        if($update_leave_status){
                            $send_notification = $api->send_notification(19, $approver_id, $employee_id, $notification_title, $notification_message, $username);
    
                            if(!$send_notification){
                                $error = $send_notification;
                                break;
                            }
                        }
                        else{
                            $error = $update_leave_status;
                            break;
                        }
                    }
                    else{
                        $error = $update_employee_leave_allocation;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Pending';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Tag for approval transactions
    # -------------------------------------------------------------

    # Tag leave for approval
    else if($transaction == 'tag leave for approval'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(4);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
				$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REC', $decision_remarks, null, $username);
    
                if($update_attendance_adjustment_status){
                    $send_notification = $api->send_notification(4, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Recommended';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_attendance_adjustment_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Tag multiple leave for approval
    else if($transaction == 'tag multiple leave for approval'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_ids = explode(',', $_POST['adjustment_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(4);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
					$employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];

                    $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'REC', $decision_remarks, null, $username);
        
                    if($update_attendance_adjustment_status){
                        $send_notification = $api->send_notification(4, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_attendance_adjustment_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Recommended';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Approve transactions
    # -------------------------------------------------------------

    # Approve attendance adjustment
    else if($transaction == 'approve attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['sanction']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_id = $_POST['adjustment_id'];
            $sanction = $_POST['sanction'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(5);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

            if($check_attendance_adjustment_exist > 0){
                $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
                $attendance_id = $attendance_adjustment_details[0]['ATTENDANCE_ID'];
                $employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];
                $time_in = $api->check_date('attendance empty', $attendance_adjustment_details[0]['TIME_IN'], '', 'Y-m-d H:i:00', '', '', '');
                $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
                $late = $api->get_attendance_late_total($employee_id, $time_in);
    
                $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));
                $time_in_ip_address = $api->get_ip_address();
                
                if(!empty($attendance_adjustment_details[0]['TIME_OUT'])){
                    $time_out = $api->check_date('attendance empty', $attendance_adjustment_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');
                }
                else{
                    $attendance_details = $api->get_attendance_details($attendance_id);
                    $time_out = $api->check_date('attendance empty', $attendance_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');
                }
    
                if(!empty($time_out)){
                    $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                    $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                    $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                    $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                    $time_out_ip_address = $api->get_ip_address();
                    $time_out_by = $username;
                }
                else{
                    $time_out_behavior = '';
                    $early_leaving = 0;
                    $overtime = 0;
                    $total_hours = 0;
                    $time_out_ip_address = '';
                    $time_out_by = '';
                }

                $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                if(empty($check_attendance_validation)){
                    $update_attendance = $api->update_attendance($attendance_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, 'System Generated: Attendance adjusted using attendance adjustment.', $username);
    
                    if($update_attendance > 0){
                        $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'APV', $decision_remarks, $sanction, $username);
            
                        if($update_attendance_adjustment_status){
                            $send_notification = $api->send_notification(5, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                            if($send_notification){
                                echo 'Approved';
                            }
                            else{
                                echo $send_notification;
                            }
                        }
                        else{
                            echo $update_attendance_adjustment_status;
                        }
                    }
                    else{
                        echo $update_attendance;
                    }
                }
                else{
                    echo $check_attendance_validation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Approve multiple attendance adjustment
    else if($transaction == 'approve multiple attendance adjustment'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id']) && isset($_POST['sanction']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $adjustment_ids = explode(',', $_POST['adjustment_id']);
            $sanction = $_POST['sanction'];
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(5);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($adjustment_ids as $adjustment_id){
                $check_attendance_adjustment_exist = $api->check_attendance_adjustment_exist($adjustment_id);

                if($check_attendance_adjustment_exist > 0){
                    $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
                    $attendance_id = $attendance_adjustment_details[0]['ATTENDANCE_ID'];
                    $employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];
                    $time_in = $api->check_date('attendance empty', $attendance_adjustment_details[0]['TIME_IN'], '', 'Y-m-d H:i:00', '', '', '');
                    $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
                    $late = $api->get_attendance_late_total($employee_id, $time_in);
                    $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));
                    $time_in_ip_address = $api->get_ip_address();

                    if(!empty($attendance_adjustment_details[0]['TIME_OUT'])){
                        $time_out = $api->check_date('attendance empty', $attendance_adjustment_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');
                    }
                    else{
                        $attendance_details = $api->get_attendance_details($attendance_id);
                        $time_out = $api->check_date('attendance empty', $attendance_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');
                    }
        
                    if(!empty($time_out)){
                        $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                        $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                        $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                        $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                        $time_out_ip_address = $api->get_ip_address();
                        $time_out_by = $username;
                    }
                    else{
                        $time_out_behavior = '';
                        $early_leaving = 0;
                        $overtime = 0;
                        $total_hours = 0;
                        $time_out_ip_address = '';
                        $time_out_by = '';
                    }

                    $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                    if(empty($check_attendance_validation)){
                        $update_attendance = $api->update_attendance($attendance_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, 'System Generated: Attendance adjusted using attendance adjustment.', $username);
        
                        if($update_attendance > 0){
                            $update_attendance_adjustment_status = $api->update_attendance_adjustment_status($adjustment_id, 'APV', $decision_remarks, $sanction, $username);
                
                            if($update_attendance_adjustment_status){
                                $send_notification = $api->send_notification(5, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                                if(!$send_notification){
                                    $error = $send_notification;
                                    break;
                                }
                            }
                        }
                        else{
                            $error = $update_attendance;
                            break;
                        }
                    }
                    else{
                        $error = $check_attendance_validation;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Approved';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Approve attendance creation
    else if($transaction == 'approve attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['sanction']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_id = $_POST['creation_id'];
            $sanction = $_POST['sanction'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(10);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

            if($check_attendance_creation_exist > 0){
                $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
                $employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];
                $time_in = $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_IN'], '', 'Y-m-d H:i:00', '', '', '');
                $time_out = $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');

                $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
                $late = $api->get_attendance_late_total($employee_id, $time_in);
    
                $attendance_setting_details = $api->get_attendance_setting_details(1);
                $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
                $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_IN'], '', 'Y-m-d', '', '', ''));
                $time_in_ip_address = $api->get_ip_address();
    
                if(!empty($time_out)){
                    $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                    $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                    $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                    $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                    $time_out_ip_address = $api->get_ip_address();
                    $time_out_by = $username;
                }
                else{
                    $time_out_behavior = '';
                    $early_leaving = 0;
                    $overtime = 0;
                    $total_hours = 0;
                    $time_out_ip_address = '';
                    $time_out_by = '';
                }

                $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                if(empty($check_attendance_validation)){
                    if($attendance_total_by_date < $max_attendance){
                        $insert_attendance = $api->insert_attendance($employee_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, 'System Generated: Created using attendance creation.', $username);
    
                        if($insert_attendance > 0){
                            $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'APV', $decision_remarks, $sanction, $username);
    
                            if($update_attendance_creation_status){
                                $send_notification = $api->send_notification(10, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                                if($send_notification){
                                    echo 'Approved';
                                }
                                else{
                                    echo $send_notification;
                                }
                            }
                            else{
                                echo $update_attendance_creation_status;
                            }
                        }
                        else{
                            echo $insert_attendance;
                        }
                    }
                    else{
                        echo 'Max Attendance';
                    }
                }
                else{
                    echo $check_attendance_validation;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Approve multiple attendance creation
    else if($transaction == 'approve multiple attendance creation'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['creation_id']) && !empty($_POST['creation_id']) && isset($_POST['sanction']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $creation_ids = explode(',', $_POST['creation_id']);
            $sanction = $_POST['sanction'];
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(10);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($creation_ids as $creation_id){
                $check_attendance_creation_exist = $api->check_attendance_creation_exist($creation_id);

                if($check_attendance_creation_exist > 0){
                    $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
                    $employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];
                    $time_in = $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_IN'], '', 'Y-m-d H:i:00', '', '', '');
                    $time_out = $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_OUT'], '', 'Y-m-d H:i:00', '', '', '');

                    $time_in_behavior = $api->get_time_in_behavior($employee_id, $time_in);
                    $late = $api->get_attendance_late_total($employee_id, $time_in);
        
                    $attendance_setting_details = $api->get_attendance_setting_details(1);
                    $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
                    $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, $api->check_date('attendance empty', $attendance_creation_details[0]['TIME_IN'], '', 'Y-m-d', '', '', ''));
                    $time_in_ip_address = $api->get_ip_address();
        
                    if(!empty($time_out)){
                        $time_out_behavior = $api->get_time_out_behavior($employee_id, $time_in, $time_out);
                        $early_leaving = $api->get_attendance_early_leaving_total($employee_id, $time_in, $time_out);
                        $overtime = $api->get_attendance_overtime_total($employee_id, $time_in, $time_out);
                        $total_hours = $api->get_attendance_total_hours($employee_id, $time_in, $time_out);
                        $time_out_ip_address = $api->get_ip_address();
                        $time_out_by = $username;
                    }
                    else{
                        $time_out_behavior = '';
                        $early_leaving = 0;
                        $overtime = 0;
                        $total_hours = 0;
                        $time_out_ip_address = '';
                        $time_out_by = '';
                    }

                    $check_attendance_validation = $api->check_attendance_validation($time_in, $time_out);

                    if(empty($check_attendance_validation)){
                        if($attendance_total_by_date < $max_attendance){
                            $insert_attendance = $api->insert_attendance($employee_id, $time_in, $time_in_ip_address, $username, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, 'System Generated: Created using attendance creation.', $username);
        
                            if($insert_attendance > 0){
                                $update_attendance_creation_status = $api->update_attendance_creation_status($creation_id, 'APV', $decision_remarks, $sanction, $username);
        
                                if($update_attendance_creation_status){
                                    $send_notification = $api->send_notification(10, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                                    if(!$send_notification){
                                        $error = $send_notification;
                                        break;
                                    }
                                }
                                else{
                                    $error = $update_attendance_creation_status;
                                    break;
                                }
                            }
                            else{
                                $error = $insert_attendance;
                                break;
                            }
                        }
                        else{
                            $error = 'Max Attendance';
                            break;
                        }
                    }
                    else{
                        $error = $check_attendance_validation;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Approved';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # Approve leave
    else if($transaction == 'approve leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_id = $_POST['leave_id'];
            $decision_remarks = $_POST['decision_remarks'];

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(17);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            $check_leave_exist = $api->check_leave_exist($leave_id);

            if($check_leave_exist > 0){
                $leave_details = $api->get_leave_details($leave_id);
				$employee_id = $leave_details[0]['EMPLOYEE_ID'];

                $update_leave_status = $api->update_leave_status($leave_id, 'APV', $decision_remarks, $username);
    
                if($update_leave_status){
                    $send_notification = $api->send_notification(17, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                    if($send_notification){
                        echo 'Approved';
                    }
                    else{
                        echo $send_notification;
                    }
                }
                else{
                    echo $update_leave_status;
                }
            }
            else{
                echo 'Not Found';
            }
        }
    }
    # -------------------------------------------------------------

    # Approve multiple leave
    else if($transaction == 'approve multiple leave'){
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['leave_id']) && !empty($_POST['leave_id']) && isset($_POST['decision_remarks']) && !empty($_POST['decision_remarks'])){
            $username = $_POST['username'];
            $leave_ids = explode(',', $_POST['leave_id']);
            $decision_remarks = $_POST['decision_remarks'];
            $error = '';

            $employee_details = $api->get_employee_details($username);
            $approver_id = $employee_details[0]['EMPLOYEE_ID'];
            $file_as = $employee_details[0]['FILE_AS'];

            $notification_template_details = $api->get_notification_template_details(17);
            $notification_title = $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null;
            $notification_message = $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null;
            $notification_message = str_replace('{employee}', $file_as, $notification_message);

            foreach($leave_ids as $leave_id){
                $check_leave_exist = $api->check_leave_exist($leave_id);

                if($check_leave_exist > 0){
                    $leave_details = $api->get_leave_details($leave_id);
				    $employee_id = $leave_details[0]['EMPLOYEE_ID'];

                    $update_leave_status = $api->update_leave_status($leave_id, 'APV', $decision_remarks, $username);
        
                    if($update_leave_status){
                        $send_notification = $api->send_notification(17, $approver_id, $employee_id, $notification_title, $notification_message, $username);

                        if(!$send_notification){
                            $error = $send_notification;
                            break;
                        }
                    }
                    else{
                        $error = $update_leave_status;
                        break;
                    }
                }
                else{
                    $error = 'Not Found';
                    break;
                }
            }

            if(empty($error)){
                echo 'Approved';
            }
            else{
                echo $error;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Notification transactions
    # -------------------------------------------------------------

    # Partial notification status
    else if($transaction == 'partial notification status'){
        if(isset($_POST['username']) && !empty($_POST['username'])){
            $username = $_POST['username'];
            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
           
            $update_notification_status = $api->update_notification_status($employee_id, '', 2);

            if($update_notification_status){
                echo 'Updated';
            }
            else{
                echo $update_notification_status;
            }
        }
    }
    # -------------------------------------------------------------

    # Read notification status
    else if($transaction == 'read notification status'){
        if(isset($_POST['username']) && !empty($_POST['username'])){
            $username = $_POST['username'];
            $notification_id = $_POST['notification_id'];
            $employee_details = $api->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
           
            $update_notification_status = $api->update_notification_status($employee_id, $notification_id, 1);

            if($update_notification_status){
                echo 'Updated';
            }
            else{
                echo $update_notification_status;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details transactions
    # -------------------------------------------------------------

    # Policy details
    else if($transaction == 'policy details'){
        if(isset($_POST['policy_id']) && !empty($_POST['policy_id'])){
            $policy_id = $_POST['policy_id'];
            $policy_details = $api->get_policy_details($policy_id);

            $response[] = array(
                'POLICY' => $policy_details[0]['POLICY'],
                'POLICY_DESCRIPTION' => $policy_details[0]['POLICY_DESCRIPTION']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Permission details
    else if($transaction == 'permission details'){
        if(isset($_POST['permission_id']) && !empty($_POST['permission_id'])){
            $permission_id = $_POST['permission_id'];
            $permission_details = $api->get_permission_details($permission_id);

            $response[] = array(
                'PERMISSION' => $permission_details[0]['PERMISSION']
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
                'ROLE_DESCRIPTION' => $role_details[0]['ROLE_DESCRIPTION']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Role permission details
    else if($transaction == 'role permission details'){
        if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
            $response = array();

            $role_id = $_POST['role_id'];
            $role_permission_details = $api->get_role_permission_details($role_id);

            for($i = 0; $i < count($role_permission_details); $i++) {
                array_push($response, $role_permission_details[$i]['PERMISSION_ID']);
            }

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # User account details
    else if($transaction == 'user account details'){
        if(isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $roles = '';
            $user_code = $_POST['user_code'];
            $user_account_details = $api->get_user_account_details($user_code);
            $role_user_details = $api->get_user_account_role_details('', $user_code);
            $employee_details = $api->get_employee_details($user_code);

            for($i = 0; $i < count($role_user_details); $i++) {
                $roles .= $role_user_details[$i]['ROLE_ID'];

                if($i != (count($role_user_details) - 1)){
                    $roles .= ',';
                }
            }

            $response[] = array(
                'EMPLOYEE_ID' => $employee_details[0]['EMPLOYEE_ID'] ?? null,
                'FILE_AS' => $user_account_details[0]['FILE_AS'],
                'ROLES' => $roles,
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # User account summary details
    else if($transaction == 'user account summary details'){
        if(isset($_POST['user_code']) && !empty($_POST['user_code'])){
            $roles = '';
            $user_code = $_POST['user_code'];
            
            $user_account_details = $api->get_user_account_details($user_code);
            $role_user_details = $api->get_user_account_role_details('', $user_code);

            for($i = 0; $i < count($role_user_details); $i++) {
                $role_id = $role_user_details[$i]['ROLE_ID'];
                $role_details = $api->get_role_details($role_id);
                $roles .= $role_details[0]['ROLE'];

                if($i != (count($role_user_details) - 1)){
                    $roles .= ', ';
                }
            }

            $account_status = $api->get_user_account_status($user_account_details[0]['USER_STATUS'])[0]['STATUS'];

            $response[] = array(
                'FILE_AS' => $user_account_details[0]['FILE_AS'],
                'ACTIVE' => $account_status,
                'PASSWORD_EXPIRY_DATE' => $api->check_date('summary', $user_account_details[0]['PASSWORD_EXPIRY_DATE'], '', 'F d, Y', '', '', ''),
                'FAILED_LOGIN' => $user_account_details[0]['FAILED_LOGIN'],
                'LAST_FAILED_LOGIN' => $api->check_date('summary', $user_account_details[0]['LAST_FAILED_LOGIN'], '', 'F d, Y', '', '', ''),
                'ROLES' => $roles
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
                'PARAMETER_NUMBER' => $system_parameter_details[0]['PARAMETER_NUMBER']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # System code details
    else if($transaction == 'system code details'){
        if(isset($_POST['system_type']) && !empty($_POST['system_type']) && isset($_POST['system_code']) && !empty($_POST['system_code'])){
            $response = array();

            $system_type = $_POST['system_type'];
            $system_code = $_POST['system_code'];

            $system_code_details = $api->get_system_code_details($system_type, $system_code);

            $response[] = array(
                'SYSTEM_DESCRIPTION' => $system_code_details[0]['SYSTEM_DESCRIPTION']     
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Upload setting details
    else if($transaction == 'upload setting details'){
        if(isset($_POST['upload_setting_id']) && !empty($_POST['upload_setting_id'])){
            $file_type = '';
            $upload_setting_id = $_POST['upload_setting_id'];
            $upload_setting_details = $api->get_upload_setting_details($upload_setting_id);
            $upload_file_type_details = $api->get_upload_file_type_details($upload_setting_id);

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $response[] = array(
                'UPLOAD_SETTING' => $upload_setting_details[0]['UPLOAD_SETTING'],
                'DESCRIPTION' => $upload_setting_details[0]['DESCRIPTION'],
                'MAX_FILE_SIZE' => $upload_setting_details[0]['MAX_FILE_SIZE'],
                'FILE_TYPE' => $file_type
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

            $response[] = array(
                'COMPANY_NAME' => $company_details[0]['COMPANY_NAME'],
                'EMAIL' => $company_details[0]['EMAIL'],
                'TELEPHONE' => $company_details[0]['TELEPHONE'],
                'MOBILE' => $company_details[0]['MOBILE'],
                'WEBSITE' => $company_details[0]['WEBSITE'],
                'TAX_ID' => $company_details[0]['TAX_ID'],
                'STREET_1' => $company_details[0]['STREET_1'],
                'STREET_2' => $company_details[0]['STREET_2'],
                'STATE_ID' => $company_details[0]['STATE_ID'],
                'CITY' => $company_details[0]['CITY'],
                'ZIP_CODE' => $company_details[0]['ZIP_CODE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Company summary details
    else if($transaction == 'company summary details'){
        if(isset($_POST['company_id']) && !empty($_POST['company_id'])){
            $company_id = $_POST['company_id'];
            
            $company_details = $api->get_company_details($company_id);
            $company_logo_file_path = $company_details[0]['COMPANY_LOGO'];
            $email = $company_details[0]['EMAIL'] ?? null;
            $telephone = $company_details[0]['TELEPHONE'] ?? null;
            $mobile = $company_details[0]['MOBILE'] ?? null;
            $website = $company_details[0]['WEBSITE'] ?? null;
            $state_id = $company_details[0]['STATE_ID'] ?? '--';

            $state_details = $api->get_state_details($state_id);
            $state_name = $state_details[0]['STATE_NAME'] ?? '--';

            if(empty($company_logo_file_path)){
                $company_logo_file_path = $api->check_image($company_logo_file_path ?? null, 'company logo');
            }

            if(!empty($email)){
                $email = '<a href="mailto:'. $email .'">'. $email .'</a>';
            }
            else{
                $email = '--';
            }

            if(!empty($telephone)){
                $telephone = '<a href="tel:'. $telephone .'">'. $telephone .'</a>';
            }
            else{
                $telephone = '--';
            }

            if(!empty($mobile)){
                $mobile = '<a href="tel:'. $mobile .'">'. $mobile .'</a>';
            }
            else{
                $mobile = '--';
            }

            if(!empty($website)){
                $website = '<a href="'. $website .'" target="_blank">'. $website .'</a>';
            }
            else{
                $website = '--';
            }

            $response[] = array(
                'COMPANY_LOGO' => '<img class="img-thumbnail" alt="company logo" width="200" src="'. $company_logo_file_path .'" data-holder-rendered="true">',
                'COMPANY_NAME' => $company_details[0]['COMPANY_NAME'],
                'EMAIL' => $email,
                'TELEPHONE' => $telephone,
                'MOBILE' => $mobile,
                'WEBSITE' => $website,
                'TAX_ID' => $company_details[0]['TAX_ID'],
                'STREET_1' => $company_details[0]['STREET_1'],
                'STREET_2' => $company_details[0]['STREET_2'],
                'STATE_ID' => $state_name,
                'CITY' => $company_details[0]['CITY'],
                'ZIP_CODE' => $company_details[0]['ZIP_CODE']
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
                'COUNTRY_NAME' => $country_details[0]['COUNTRY_NAME']
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
                'COUNTRY_ID' => $state_details[0]['COUNTRY_ID']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Notification setting details
    else if($transaction == 'notification setting details'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $channel = '';

            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_setting_details = $api->get_notification_setting_details($notification_setting_id);
            $notification_channel_details = $api->get_notification_channel_details($notification_setting_id);

            for($i = 0; $i < count($notification_channel_details); $i++) {
                $channel .= $notification_channel_details[$i]['CHANNEL'];

                if($i != (count($notification_channel_details) - 1)){
                    $channel .= ',';
                }
            }

            $response[] = array(
                'NOTIFICATION_SETTING' => $notification_setting_details[0]['NOTIFICATION_SETTING'],
                'NOTIFICATION_SETTING_DESCRIPTION' => $notification_setting_details[0]['NOTIFICATION_SETTING_DESCRIPTION'],
                'CHANNEL' => $channel
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Notification template details
    else if($transaction == 'notification template details'){
        if(isset($_POST['notification_setting_id']) && !empty($_POST['notification_setting_id'])){
            $user_acount_recipient = '';
            $role_recipient = '';
            $notification_setting_id = $_POST['notification_setting_id'];
            $notification_template_details = $api->get_notification_template_details($notification_setting_id);
            $notification_role_recipient_details = $api->get_notification_role_recipient_details($notification_setting_id);
            $notification_user_account_recipient_details = $api->get_notification_user_account_recipient_details($notification_setting_id);

            for($i = 0; $i < count($notification_role_recipient_details); $i++) {
                $role_recipient .= $notification_role_recipient_details[$i]['ROLE_ID'];

                if($i != (count($notification_role_recipient_details) - 1)){
                    $role_recipient .= ',';
                }
            }

            for($i = 0; $i < count($notification_user_account_recipient_details); $i++) {
                $user_acount_recipient .= $notification_user_account_recipient_details[$i]['USERNAME'];

                if($i != (count($notification_user_account_recipient_details) - 1)){
                    $user_acount_recipient .= ',';
                }
            }

            $response[] = array(
                'NOTIFICATION_TITLE' => $notification_template_details[0]['NOTIFICATION_TITLE'] ?? null,
                'NOTIFICATION_MESSAGE' => $notification_template_details[0]['NOTIFICATION_MESSAGE'] ?? null,
                'SYSTEM_LINK' => $notification_template_details[0]['SYSTEM_LINK'] ?? null,
                'EMAIL_LINK' => $notification_template_details[0]['EMAIL_LINK'] ?? null,
                'ROLE_RECIPIENT' => $role_recipient,
                'USER_ACCOUNT_RECIPIENT' => $user_acount_recipient
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Interface setting details
    else if($transaction == 'interface settings details'){
        $interface_setting_id = 1; 
        $interface_settings_details = $api->get_interface_settings_details($interface_setting_id);

        $response[] = array(
            'LOGIN_BACKGROUND' => $api->check_image($interface_settings_details[0]['LOGIN_BACKGROUND'] ?? null, 'login background'),
            'LOGIN_LOGO' => $api->check_image($interface_settings_details[0]['LOGIN_LOGO'] ?? null, 'login logo'),
            'MENU_LOGO' => $api->check_image($interface_settings_details[0]['MENU_LOGO'] ?? null, 'menu logo'),
            'MENU_ICON' => $api->check_image($interface_settings_details[0]['MENU_ICON'] ?? null, 'menu icon'),
            'FAVICON' => $api->check_image($interface_settings_details[0]['FAVICON'] ?? null, 'favicon'),
        );

        echo json_encode($response);
    }
    # -------------------------------------------------------------

    # Mail configuration details
    else if($transaction == 'mail configuration details'){
        $mail_configuration_details = $api->get_mail_configuration_details(1);

        $response[] = array(
            'MAIL_HOST' => $mail_configuration_details[0]['MAIL_HOST'] ?? null,
            'PORT' => $mail_configuration_details[0]['PORT'] ?? null,
            'SMTP_AUTH' => $mail_configuration_details[0]['SMTP_AUTH'] ?? null,
            'SMTP_AUTO_TLS' => $mail_configuration_details[0]['SMTP_AUTO_TLS'] ?? null,
            'USERNAME' => $mail_configuration_details[0]['USERNAME'] ?? null,
            'MAIL_ENCRYPTION' => $mail_configuration_details[0]['MAIL_ENCRYPTION'] ?? null,
            'MAIL_FROM_NAME' => $mail_configuration_details[0]['MAIL_FROM_NAME'] ?? null,
            'MAIL_FROM_EMAIL' => $mail_configuration_details[0]['MAIL_FROM_EMAIL'] ?? null
        );

        echo json_encode($response);
    }
    # -------------------------------------------------------------

    # Zoom integration details
    else if($transaction == 'zoom integration details'){
        $zoom_integration_details = $api->get_zoom_integration_details(1);

        $response[] = array(
            'API_KEY' => $zoom_integration_details[0]['API_KEY'] ?? null,
            'API_SECRET' => $zoom_integration_details[0]['API_SECRET'] ?? null
        );

        echo json_encode($response);
    }
    # -------------------------------------------------------------

    # Department details
    else if($transaction == 'department details'){
        if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
            $department_id = $_POST['department_id'];
            $department_details = $api->get_department_details($department_id);

            $response[] = array(
                'DEPARTMENT' => $department_details[0]['DEPARTMENT'],
                'PARENT_DEPARTMENT' => $department_details[0]['PARENT_DEPARTMENT'],
                'MANAGER' => $department_details[0]['MANAGER']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Job position details
    else if($transaction == 'job position details'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            $job_position_id = $_POST['job_position_id'];
            $job_position_details = $api->get_job_position_details($job_position_id);

            $response[] = array(
                'JOB_POSITION' => $job_position_details[0]['JOB_POSITION']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Job position summary details
    else if($transaction == 'job position summary details'){
        if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
            $job_position_id = $_POST['job_position_id'];
            
            $job_position_details = $api->get_job_position_details($job_position_id);
            $job_description_file_path = $job_position_details[0]['JOB_DESCRIPTION'];

            if(!empty($job_description_file_path)){
                $job_description_file_path = '<a href="'. $job_description_file_path .'" target="_blank">View Job Description</a>';
            }
            else{
                $job_description_file_path = '';
            }

            $response[] = array(
                'JOB_POSITION' => $job_position_details[0]['JOB_POSITION'],
                'JOB_DESCRIPTION' =>  $job_description_file_path
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Work location details
    else if($transaction == 'work location details'){
        if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
            $work_location_id = $_POST['work_location_id'];
            $work_location_details = $api->get_work_location_details($work_location_id);

            $response[] = array(
                'WORK_LOCATION' => $work_location_details[0]['WORK_LOCATION'],
                'EMAIL' => $work_location_details[0]['EMAIL'],
                'TELEPHONE' => $work_location_details[0]['TELEPHONE'],
                'MOBILE' => $work_location_details[0]['MOBILE'],
                'STREET_1' => $work_location_details[0]['STREET_1'],
                'STREET_2' => $work_location_details[0]['STREET_2'],
                'STATE_ID' => $work_location_details[0]['STATE_ID'],
                'CITY' => $work_location_details[0]['CITY'],
                'ZIP_CODE' => $work_location_details[0]['ZIP_CODE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Work location summary details
    else if($transaction == 'work location summary details'){
        if(isset($_POST['work_location_id']) && !empty($_POST['work_location_id'])){
            $work_location_id = $_POST['work_location_id'];
            
            $work_location_details = $api->get_work_location_details($work_location_id);
            $email = $work_location_details[0]['EMAIL'] ?? null;
            $telephone = $work_location_details[0]['TELEPHONE'] ?? null;
            $mobile = $work_location_details[0]['MOBILE'] ?? null;
            $state_id = $work_location_details[0]['STATE_ID'] ?? '--';

            $state_details = $api->get_state_details($state_id);
            $state_name = $state_details[0]['STATE_NAME'] ?? '--';

            if(!empty($email)){
                $email = '<a href="mailto:'. $email .'">'. $email .'</a>';
            }
            else{
                $email = '--';
            }

            if(!empty($telephone)){
                $telephone = '<a href="tel:'. $telephone .'">'. $telephone .'</a>';
            }
            else{
                $telephone = '--';
            }

            if(!empty($mobile)){
                $mobile = '<a href="tel:'. $mobile .'">'. $mobile .'</a>';
            }
            else{
                $mobile = '--';
            }

            $response[] = array(
                'WORK_LOCATION' => $work_location_details[0]['WORK_LOCATION'],
                'EMAIL' => $email,
                'TELEPHONE' => $telephone,
                'MOBILE' => $mobile,
                'STREET_1' => $work_location_details[0]['STREET_1'],
                'STREET_2' => $work_location_details[0]['STREET_2'],
                'STATE_ID' => $state_name,
                'CITY' => $work_location_details[0]['CITY'],
                'ZIP_CODE' => $work_location_details[0]['ZIP_CODE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Departure reason details
    else if($transaction == 'departure reason details'){
        if(isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])){
            $departure_reason_id = $_POST['departure_reason_id'];
            $departure_reason_details = $api->get_departure_reason_details($departure_reason_id);

            $response[] = array(
                'DEPARTURE_REASON' => $departure_reason_details[0]['DEPARTURE_REASON']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Employee type details
    else if($transaction == 'employee type details'){
        if(isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])){
            $employee_type_id = $_POST['employee_type_id'];
            $employee_type_details = $api->get_employee_type_details($employee_type_id);

            $response[] = array(
                'EMPLOYEE_TYPE' => $employee_type_details[0]['EMPLOYEE_TYPE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Employee details
    else if($transaction == 'employee details'){
        if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
            $employee_id = $_POST['employee_id'];
            $employee_details = $api->get_employee_details($employee_id);

            $response[] = array(
                'BADGE_ID' => $employee_details[0]['BADGE_ID'],
                'FIRST_NAME' => $employee_details[0]['FIRST_NAME'],
                'MIDDLE_NAME' => $employee_details[0]['MIDDLE_NAME'],
                'LAST_NAME' => $employee_details[0]['LAST_NAME'],
                'SUFFIX' => $employee_details[0]['SUFFIX'],
                'COMPANY' => $employee_details[0]['COMPANY'],
                'JOB_POSITION' => $employee_details[0]['JOB_POSITION'],
                'DEPARTMENT' => $employee_details[0]['DEPARTMENT'],
                'WORK_LOCATION' => $employee_details[0]['WORK_LOCATION'],
                'WORKING_HOURS' => $employee_details[0]['WORKING_HOURS'],
                'MANAGER' => $employee_details[0]['MANAGER'],
                'COACH' => $employee_details[0]['COACH'],
                'EMPLOYEE_TYPE' => $employee_details[0]['EMPLOYEE_TYPE'],
                'PERMANENCY_DATE' => $api->check_date('empty', $employee_details[0]['PERMANENCY_DATE'], '', 'n/d/Y', '', '', ''),
                'ONBOARD_DATE' => $api->check_date('empty', $employee_details[0]['ONBOARD_DATE'], '', 'n/d/Y', '', '', ''),
                'WORK_EMAIL' => $employee_details[0]['WORK_EMAIL'],
                'WORK_TELEPHONE' => $employee_details[0]['WORK_TELEPHONE'],
                'WORK_MOBILE' => $employee_details[0]['WORK_MOBILE'],
                'SSS' => $employee_details[0]['SSS'],
                'TIN' => $employee_details[0]['TIN'],
                'PAGIBIG' => $employee_details[0]['PAGIBIG'],
                'PHILHEALTH' => $employee_details[0]['PHILHEALTH'],
                'BANK_ACCOUNT_NUMBER' => $employee_details[0]['BANK_ACCOUNT_NUMBER'],
                'HOME_WORK_DISTANCE' => $employee_details[0]['HOME_WORK_DISTANCE'],
                'PERSONAL_EMAIL' => $employee_details[0]['PERSONAL_EMAIL'],
                'PERSONAL_TELEPHONE' => $employee_details[0]['PERSONAL_TELEPHONE'],
                'PERSONAL_MOBILE' => $employee_details[0]['PERSONAL_MOBILE'],
                'STREET_1' => $employee_details[0]['STREET_1'],
                'STREET_2' => $employee_details[0]['STREET_2'],
                'STATE_ID' => $employee_details[0]['STATE_ID'],
                'CITY' => $employee_details[0]['CITY'],
                'ZIP_CODE' => $employee_details[0]['ZIP_CODE'],
                'MARITAL_STATUS' => $employee_details[0]['MARITAL_STATUS'],
                'SPOUSE_NAME' => $employee_details[0]['SPOUSE_NAME'],
                'SPOUSE_BIRTHDAY' => $employee_details[0]['SPOUSE_BIRTHDAY'],
                'EMERGENCY_CONTACT' => $employee_details[0]['EMERGENCY_CONTACT'],
                'EMERGENCY_PHONE' => $employee_details[0]['EMERGENCY_PHONE'],
                'NATIONALITY' => $employee_details[0]['NATIONALITY'],
                'IDENTIFICATION_NUMBER' => $employee_details[0]['IDENTIFICATION_NUMBER'],
                'PASSPORT_NUMBER' => $employee_details[0]['PASSPORT_NUMBER'],
                'GENDER' => $employee_details[0]['GENDER'],
                'BIRTHDAY' => $api->check_date('empty', $employee_details[0]['BIRTHDAY'], '', 'n/d/Y', '', '', ''),
                'CERTIFICATE_LEVEL' => $employee_details[0]['CERTIFICATE_LEVEL'],
                'FIELD_OF_STUDY' => $employee_details[0]['FIELD_OF_STUDY'],
                'SCHOOL' => $employee_details[0]['SCHOOL'],
                'PLACE_OF_BIRTH' => $employee_details[0]['PLACE_OF_BIRTH'],
                'NUMBER_OF_CHILDREN' => $employee_details[0]['NUMBER_OF_CHILDREN'],
                'VISA_NUMBER' => $employee_details[0]['VISA_NUMBER'],
                'WORK_PERMIT_NUMBER' => $employee_details[0]['WORK_PERMIT_NUMBER'],
                'VISA_EXPIRY_DATE' => $api->check_date('empty', $employee_details[0]['VISA_EXPIRY_DATE'], '', 'n/d/Y', '', '', ''),
                'WORK_PERMIT_EXPIRY_DATE' => $api->check_date('empty', $employee_details[0]['WORK_PERMIT_EXPIRY_DATE'], '', 'n/d/Y', '', '', ''),
                'WORK_PERMIT' => $employee_details[0]['WORK_PERMIT'],
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Working hours details
    else if($transaction == 'working hours details'){
        if(isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id'])){
            $working_hours_id = $_POST['working_hours_id'];
            $working_hours_details = $api->get_working_hours_details($working_hours_id);

            $response[] = array(
                'WORKING_HOURS' => $working_hours_details[0]['WORKING_HOURS'],
                'SCHEDULE_TYPE' => $working_hours_details[0]['SCHEDULE_TYPE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Working hours schedule details
    else if($transaction == 'working hours schedule details'){
        if(isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id'])){
            $employee = '';
            $working_hours_id = $_POST['working_hours_id'];
            $working_hours_schedule_details = $api->get_working_hours_schedule_details($working_hours_id);
            $employee_working_hours_details = $api->get_employee_working_hours_details($working_hours_id);

            for($i = 0; $i < count($employee_working_hours_details); $i++) {
                $employee .= $employee_working_hours_details[$i]['EMPLOYEE_ID'];

                if($i != (count($employee_working_hours_details) - 1)){
                    $employee .= ',';
                }
            }

            $response[] = array(
                'START_DATE' => $api->check_date('empty', $working_hours_schedule_details[0]['START_DATE'] ?? null, '', 'n/d/Y', '', '', ''),
                'END_DATE' => $api->check_date('empty', $working_hours_schedule_details[0]['END_DATE'] ?? null, '', 'n/d/Y', '', '', ''),
                'MONDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['MONDAY_MORNING_WORK_FROM'] ?? null,
                'MONDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['MONDAY_MORNING_WORK_TO'] ?? null,
                'MONDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['MONDAY_AFTERNOON_WORK_FROM'] ?? null,
                'MONDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['MONDAY_AFTERNOON_WORK_TO'] ?? null,
                'TUESDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['TUESDAY_MORNING_WORK_FROM'] ?? null,
                'TUESDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['TUESDAY_MORNING_WORK_TO'] ?? null,
                'TUESDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['TUESDAY_AFTERNOON_WORK_FROM'] ?? null,
                'TUESDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['TUESDAY_AFTERNOON_WORK_TO'] ?? null,
                'WEDNESDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['WEDNESDAY_MORNING_WORK_FROM'] ?? null,
                'WEDNESDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['WEDNESDAY_MORNING_WORK_TO'] ?? null,
                'WEDNESDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_FROM'] ?? null,
                'WEDNESDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_TO'] ?? null,
                'THURSDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['THURSDAY_MORNING_WORK_FROM'] ?? null,
                'THURSDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['THURSDAY_MORNING_WORK_TO'] ?? null,
                'THURSDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['THURSDAY_AFTERNOON_WORK_FROM'] ?? null,
                'THURSDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['THURSDAY_AFTERNOON_WORK_TO'] ?? null,
                'FRIDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['FRIDAY_MORNING_WORK_FROM'] ?? null,
                'FRIDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['FRIDAY_MORNING_WORK_TO'] ?? null,
                'FRIDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['FRIDAY_AFTERNOON_WORK_FROM'] ?? null,
                'FRIDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['FRIDAY_AFTERNOON_WORK_TO'] ?? null,
                'SATURDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['SATURDAY_MORNING_WORK_FROM'] ?? null,
                'SATURDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['SATURDAY_MORNING_WORK_TO'] ?? null,
                'SATURDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['SATURDAY_AFTERNOON_WORK_FROM'] ?? null,
                'SATURDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['SATURDAY_AFTERNOON_WORK_TO'] ?? null,
                'SUNDAY_MORNING_WORK_FROM' => $working_hours_schedule_details[0]['SUNDAY_MORNING_WORK_FROM'] ?? null,
                'SUNDAY_MORNING_WORK_TO' => $working_hours_schedule_details[0]['SUNDAY_MORNING_WORK_TO'] ?? null,
                'SUNDAY_AFTERNOON_WORK_FROM' => $working_hours_schedule_details[0]['SUNDAY_AFTERNOON_WORK_FROM'] ?? null,
                'SUNDAY_AFTERNOON_WORK_TO' => $working_hours_schedule_details[0]['SUNDAY_AFTERNOON_WORK_TO'] ?? null,
                'EMPLOYEE' => $employee,
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Working hours summary details
    else if($transaction == 'working hours summary details'){
        if(isset($_POST['working_hours_id']) && !empty($_POST['working_hours_id'])){
            $working_hours_id = $_POST['working_hours_id'];
            $working_hours_details = $api->get_working_hours_details($working_hours_id);
            $working_hours_schedule_details = $api->get_working_hours_schedule_details($working_hours_id);
            $employee_working_hours_details = $api->get_employee_working_hours_details($working_hours_id);
            $schedule_type = $working_hours_details[0]['SCHEDULE_TYPE'];

            $system_code_details = $api->get_system_code_details('SCHEDULETYPE', $schedule_type);
            $schedule_type_name = $system_code_details[0]['SYSTEM_DESCRIPTION'];

            $table = '<table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                    </tr>
                                </thead>
                                <tbody>';

            if(count($employee_working_hours_details) > 0){
                for($i = 0; $i < count($employee_working_hours_details); $i++) {
                    $employee_image = $employee_working_hours_details[$i]['EMPLOYEE_IMAGE'];
                    $file_as = $employee_working_hours_details[$i]['FILE_AS'];
                    $job_position = $employee_working_hours_details[$i]['JOB_POSITION'];

                    $job_position_details = $api->get_job_position_details($job_position);
                    $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;
    
                    if(empty($employee_image)){
                        $employee_image = $api->check_image($employee_image ?? null, 'profile');
                    }

                    $table .= '<tr>
                                    <td><img class="rounded-circle avatar-xs" src="'. $employee_image .'" alt="profile"></td>
                                    <td>'. $file_as . '<p class="text-muted mb-0">'. $job_position_name .'</p></td>
                                </tr>';
                }
            }
            else{
                $table .= '<tr>
                                <td colspan="2"><p class="text-center">No Assigned Employee</p></td>
                            </tr>';
            }

            $table .= '</tbody>
                </table>';

            $response[] = array(
                'WORKING_HOURS' => $working_hours_details[0]['WORKING_HOURS'],
                'SCHEDULE_TYPE' => $schedule_type_name,
                'EMPLOYEE_TABLE' => $table,
                'START_DATE' => $api->check_date('summary', $working_hours_schedule_details[0]['START_DATE'] ?? null, '', 'n/d/Y', '', '', ''),
                'END_DATE' => $api->check_date('summary', $working_hours_schedule_details[0]['END_DATE'] ?? null, '', 'n/d/Y', '', '', ''),
                'MONDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['MONDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'MONDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['MONDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'MONDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['MONDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'MONDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['MONDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'TUESDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['TUESDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'TUESDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['TUESDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'TUESDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['TUESDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'TUESDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['TUESDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'WEDNESDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['WEDNESDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'WEDNESDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['WEDNESDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'WEDNESDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'WEDNESDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'THURSDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['THURSDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'THURSDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['THURSDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'THURSDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['THURSDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'THURSDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['THURSDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'FRIDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['FRIDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'FRIDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['FRIDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'FRIDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['FRIDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'FRIDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['FRIDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'SATURDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['SATURDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'SATURDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['SATURDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'SATURDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['SATURDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'SATURDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['SATURDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'SUNDAY_MORNING_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['SUNDAY_MORNING_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'SUNDAY_MORNING_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['SUNDAY_MORNING_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
                'SUNDAY_AFTERNOON_WORK_FROM' => $api->check_date('summary', $working_hours_schedule_details[0]['SUNDAY_AFTERNOON_WORK_FROM'] ?? null, '', 'h:i a', '', '', ''),
                'SUNDAY_AFTERNOON_WORK_TO' => $api->check_date('summary', $working_hours_schedule_details[0]['SUNDAY_AFTERNOON_WORK_TO'] ?? null, '', 'h:i a', '', '', ''),
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance setting details
    else if($transaction == 'attendance setting details'){
        $attendance_setting_details = $api->get_attendance_setting_details(1);

        $response[] = array(
            'MAX_ATTENDANCE' => $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1,
            'LATE_GRACE_PERIOD' => $attendance_setting_details[0]['LATE_GRACE_PERIOD'] ?? 0,
            'TIME_OUT_INTERVAL' => $attendance_setting_details[0]['TIME_OUT_INTERVAL'] ?? 1,
            'LATE_POLICY' => $attendance_setting_details[0]['LATE_POLICY'] ?? 0,
            'EARLY_LEAVING_POLICY' => $attendance_setting_details[0]['EARLY_LEAVING_POLICY'] ?? 0,
            'OVERTIME_POLICY' => $attendance_setting_details[0]['OVERTIME_POLICY'] ?? 0
        );

        echo json_encode($response);
    }
    # -------------------------------------------------------------

    # Attendance details
    else if($transaction == 'attendance details'){
        if(isset($_POST['attendance_id']) && !empty($_POST['attendance_id'])){
            $attendance_id = $_POST['attendance_id'];

            $attendance_details = $api->get_attendance_details($attendance_id);

            $response[] = array(
                'EMPLOYEE_ID' => $attendance_details[0]['EMPLOYEE_ID'],
                'TIME_IN_DATE' => $api->check_date('empty', $attendance_details[0]['TIME_IN'], '', 'n/d/Y', '', '', ''),
                'TIME_IN' => $api->check_date('empty', $attendance_details[0]['TIME_IN'], '', 'H:i:00', '', '', ''),
                'TIME_OUT_DATE' => $api->check_date('empty', $attendance_details[0]['TIME_OUT'], '', 'n/d/Y', '', '', ''),
                'TIME_OUT' => $api->check_date('empty', $attendance_details[0]['TIME_OUT'], '', 'H:i:00', '', '', ''),
                'REMARKS' => $attendance_details[0]['REMARKS']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance summary details
    else if($transaction == 'attendance summary details'){
        if(isset($_POST['attendance_id']) && !empty($_POST['attendance_id'])){
            $attendance_id = $_POST['attendance_id'];

            $attendance_details = $api->get_attendance_details($attendance_id);

            $time_in_behavior = $api->get_time_in_behavior_status($attendance_details[0]['TIME_IN_BEHAVIOR'])[0]['BADGE'];
            $time_out_behavior = $api->get_time_out_behavior_status($attendance_details[0]['TIME_OUT_BEHAVIOR'])[0]['BADGE'];

            $employee_id = $attendance_details[0]['EMPLOYEE_ID'];
            $time_in_location = $attendance_details[0]['TIME_IN_LOCATION'];
            $time_out_location = $attendance_details[0]['TIME_OUT_LOCATION'];
            $employee_details = $api->get_employee_details($employee_id);
            $file_as = $employee_details[0]['FILE_AS'];

            $generate_attendance_adjustment_table = $api->generate_attendance_adjustment_table($attendance_id, 'APV');

            if(!empty($attendance_details[0]['TIME_IN_BY'])){
                $time_in_by = $attendance_details[0]['TIME_IN_BY'];
                $time_in_by_details = $api->get_employee_details($time_in_by);
                $time_in_by_name = $time_in_by_details[0]['FILE_AS'];
            }
            else{
                $time_in_by_name = '--';
            }

            if(!empty($attendance_details[0]['TIME_OUT_BY'])){
                $time_out_by = $attendance_details[0]['TIME_OUT_BY'];
                $time_out_by_details = $api->get_employee_details($time_out_by);
                $time_out_by_name = $time_out_by_details[0]['FILE_AS'];
            }
            else{
                $time_out_by_name = '--';
            }

            if(!empty($time_in_location)){
                $time_in_location = '<a href="https://maps.google.com/?q=' . $time_in_location . '" target="_blank">View Location</a>';
            }
            else{
                $time_in_location = 'No location available';
            }

            if(!empty($time_out_location)){
                $time_out_location = '<a href="https://maps.google.com/?q=' . $time_out_location . '" target="_blank">View Location</a>';
            }
            else{
                $time_out_location = 'No location available';
            }

            $response[] = array(
                'EMPLOYEE' => $file_as,
                'TIME_IN' => $api->check_date('summary', $attendance_details[0]['TIME_IN'], '', 'F d, Y H:i', '', '', ''),
                'TIME_OUT' => $api->check_date('summary', $attendance_details[0]['TIME_OUT'], '', 'F d, Y H:i', '', '', ''),
                'TIME_IN_NOTE' => $attendance_details[0]['TIME_IN_NOTE'],
                'TIME_IN_IP_ADDRESS' => $attendance_details[0]['TIME_IN_IP_ADDRESS'],
                'TIME_OUT_IP_ADDRESS' => $attendance_details[0]['TIME_OUT_IP_ADDRESS'],
                'TIME_IN_LOCATION' => $time_in_location,
                'TIME_OUT_LOCATION' => $time_out_location,
                'TIME_IN_BY' => $time_in_behavior,
                'TIME_IN_BEHAVIOR' => $time_in_behavior,
                'TIME_OUT_BEHAVIOR' => $time_out_behavior,
                'TIME_IN_BY' => $time_in_by_name,
                'TIME_OUT_BY' => $time_out_by_name,
                'REMARKS' => $attendance_details[0]['REMARKS'],
                'LATE' => $attendance_details[0]['LATE'] . ' minute(s)',
                'EARLY_LEAVING' => $attendance_details[0]['EARLY_LEAVING'] . ' minute(s)',
                'OVERTIME' => $attendance_details[0]['OVERTIME'] . ' minute(s)',
                'TOTAL_WORKING_HOURS' => $attendance_details[0]['TOTAL_WORKING_HOURS'] . ' hour(s)',
                'ATTENDANCE_ADJUSTMENT' => $generate_attendance_adjustment_table
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance adjustment details
    else if($transaction == 'attendance adjustment details'){
        if(isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $adjustment_id = $_POST['adjustment_id'];

            $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);

            $response[] = array(
                'TIME_IN_DATE' => $api->check_date('empty', $attendance_adjustment_details[0]['TIME_IN'], '', 'n/d/Y', '', '', ''),
                'TIME_IN' => $api->check_date('empty', $attendance_adjustment_details[0]['TIME_IN'], '', 'H:i:00', '', '', ''),
                'TIME_OUT_DATE' => $api->check_date('empty', $attendance_adjustment_details[0]['TIME_OUT'], '', 'n/d/Y', '', '', ''),
                'TIME_OUT' => $api->check_date('empty', $attendance_adjustment_details[0]['TIME_OUT'], '', 'H:i:00', '', '', ''),
                'REASON' => $attendance_adjustment_details[0]['REASON']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance creation details
    else if($transaction == 'attendance creation details'){
        if(isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $creation_id = $_POST['creation_id'];

            $attendance_creation_details = $api->get_attendance_creation_details($creation_id);

            $response[] = array(
                'TIME_IN_DATE' => $api->check_date('empty', $attendance_creation_details[0]['TIME_IN'], '', 'n/d/Y', '', '', ''),
                'TIME_IN' => $api->check_date('empty', $attendance_creation_details[0]['TIME_IN'], '', 'H:i:00', '', '', ''),
                'TIME_OUT_DATE' => $api->check_date('empty', $attendance_creation_details[0]['TIME_OUT'], '', 'n/d/Y', '', '', ''),
                'TIME_OUT' => $api->check_date('empty', $attendance_creation_details[0]['TIME_OUT'], '', 'H:i:00', '', '', ''),
                'REASON' => $attendance_creation_details[0]['REASON']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance adjustment summary details
    else if($transaction == 'attendance adjustment summary details'){
        if(isset($_POST['adjustment_id']) && !empty($_POST['adjustment_id'])){
            $adjustment_id = $_POST['adjustment_id'];

            $attendance_adjustment_details = $api->get_attendance_adjustment_details($adjustment_id);
            $attendance_id = $attendance_adjustment_details[0]['ATTENDANCE_ID'];
            $employee_id = $attendance_adjustment_details[0]['EMPLOYEE_ID'];
            $status = $attendance_adjustment_details[0]['STATUS'];
            $sanction = $attendance_adjustment_details[0]['SANCTION'];
            $attachment = $attendance_adjustment_details[0]['ATTACHMENT'];
            $recommendation_by = $attendance_adjustment_details[0]['RECOMMENDATION_BY'];
            $decision_by = $attendance_adjustment_details[0]['DECISION_BY'];
            $time_in = $api->check_date('summary', $attendance_adjustment_details[0]['TIME_IN'], '', 'F d, Y h:i a', '', '', '');
            $time_out = $api->check_date('summary', $attendance_adjustment_details[0]['TIME_OUT'], '', 'F d, Y h:i a', '', '', '');

            $attendance_details = $api->get_attendance_details($attendance_id);
            $attendance_time_in = $api->check_date('summary', $attendance_details[0]['TIME_IN'], '', 'F d, Y h:i:s a', '', '', '');
            $attendance_time_out = $api->check_date('summary', $attendance_details[0]['TIME_OUT'], '', 'F d, Y h:i a', '', '', '');

            $status_name = $api->get_attendance_adjustment_status($status)[0]['BADGE'];
            $sanction_name = $api->get_attendance_adjustment_sanction($sanction)[0]['BADGE'];

            $employee_details = $api->get_employee_details($employee_id);
            $file_as = $employee_details[0]['FILE_AS'];

            if(!empty($recommendation_by)){
                $recommendation_by_details = $api->get_employee_details($recommendation_by);
                $recommendation_by_file_as = $recommendation_by_details[0]['FILE_AS'];
            }
            else{
                $recommendation_by_file_as = '--';
            }

            if(!empty($decision_by)){
                $decision_by_details = $api->get_employee_details($decision_by);
                $decision_by_file_as = $decision_by_details[0]['FILE_AS'];
            }
            else{
                $decision_by_file_as = '--';
            }

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
                $time_out_details = '--';
            }

            if(!empty($attachment)){
                $attachment = '<a href="'. $attachment .'" target="_blank">View Attachment</a>';
            }
            else{
                $attachment = '';
            }

            $response[] = array(
                'EMPLOYEE' => $file_as,
                'TIME_IN' => $time_in_details,
                'TIME_OUT' => $time_out_details,
                'CREATED_DATE' => $api->check_date('summary', $attendance_adjustment_details[0]['CREATED_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'FOR_RECOMMENDATION_DATE' => $api->check_date('summary', $attendance_adjustment_details[0]['FOR_RECOMMENDATION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'RECOMMENDATION_DATE' => $api->check_date('summary', $attendance_adjustment_details[0]['RECOMMENDATION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'DECISION_DATE' => $api->check_date('summary', $attendance_adjustment_details[0]['DECISION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'STATUS' => $status_name,
                'SANCTION' => $sanction_name,
                'ATTACHMENT' => $attachment,
                'RECOMMENDATION_REMARKS' => $attendance_adjustment_details[0]['RECOMMENDATION_REMARKS'],
                'RECOMMENDATION_BY' => $recommendation_by_file_as,
                'DECISION_BY' => $decision_by_file_as,
                'DECISION_REMARKS' => $attendance_adjustment_details[0]['DECISION_REMARKS'],
                'REASON' => $attendance_adjustment_details[0]['REASON']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Attendance creation summary details
    else if($transaction == 'attendance creation summary details'){
        if(isset($_POST['creation_id']) && !empty($_POST['creation_id'])){
            $creation_id = $_POST['creation_id'];

            $attendance_creation_details = $api->get_attendance_creation_details($creation_id);
            $employee_id = $attendance_creation_details[0]['EMPLOYEE_ID'];
            $status = $attendance_creation_details[0]['STATUS'];
            $sanction = $attendance_creation_details[0]['SANCTION'];
            $attachment = $attendance_creation_details[0]['ATTACHMENT'];
            $recommendation_by = $attendance_creation_details[0]['RECOMMENDATION_BY'];
            $decision_by = $attendance_creation_details[0]['DECISION_BY'];
            $time_in = $api->check_date('summary', $attendance_creation_details[0]['TIME_IN'], '', 'F d, Y h:i a', '', '', '');
            $time_out = $api->check_date('summary', $attendance_creation_details[0]['TIME_OUT'], '', 'F d, Y h:i a', '', '', '');

            $status_name = $api->get_attendance_creation_status($status)[0]['BADGE'];
            $sanction_name = $api->get_attendance_creation_sanction($sanction)[0]['BADGE'];

            $employee_details = $api->get_employee_details($employee_id);
            $file_as = $employee_details[0]['FILE_AS'];

            if(!empty($recommendation_by)){
                $recommendation_by_details = $api->get_employee_details($recommendation_by);
                $recommendation_by_file_as = $recommendation_by_details[0]['FILE_AS'];
            }
            else{
                $recommendation_by_file_as = '--';
            }

            if(!empty($decision_by)){
                $decision_by_details = $api->get_employee_details($decision_by);
                $decision_by_file_as = $decision_by_details[0]['FILE_AS'];
            }
            else{
                $decision_by_file_as = '--';
            }

            if(!empty($attachment)){
                $attachment = '<a href="'. $attachment .'" target="_blank">View Attachment</a>';
            }
            else{
                $attachment = '';
            }

            $response[] = array(
                'EMPLOYEE' => $file_as,
                'TIME_IN' => $time_in,
                'TIME_OUT' => $time_out,
                'CREATED_DATE' => $api->check_date('summary', $attendance_creation_details[0]['CREATED_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'FOR_RECOMMENDATION_DATE' => $api->check_date('summary', $attendance_creation_details[0]['FOR_RECOMMENDATION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'RECOMMENDATION_DATE' => $api->check_date('summary', $attendance_creation_details[0]['RECOMMENDATION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'DECISION_DATE' => $api->check_date('summary', $attendance_creation_details[0]['DECISION_DATE'], '', 'F d, Y h:i:s a', '', '', ''),
                'STATUS' => $status_name,
                'SANCTION' => $sanction_name,
                'ATTACHMENT' => $attachment,
                'RECOMMENDATION_REMARKS' => $attendance_creation_details[0]['RECOMMENDATION_REMARKS'],
                'RECOMMENDATION_BY' => $recommendation_by_file_as,
                'DECISION_BY' => $decision_by_file_as,
                'DECISION_REMARKS' => $attendance_creation_details[0]['DECISION_REMARKS'],
                'REASON' => $attendance_creation_details[0]['REASON']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Approval type details
    else if($transaction == 'approval type details'){
        if(isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            $approval_type_id = $_POST['approval_type_id'];
            $approval_type_details = $api->get_approval_type_details($approval_type_id);

            $response[] = array(
                'APPROVAL_TYPE' => $approval_type_details[0]['APPROVAL_TYPE'],
                'APPROVAL_TYPE_DESCRIPTION' => $approval_type_details[0]['APPROVAL_TYPE_DESCRIPTION']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Approval type summary details
    else if($transaction == 'approval type summary details'){
        if(isset($_POST['approval_type_id']) && !empty($_POST['approval_type_id'])){
            $approval_type_id = $_POST['approval_type_id'];
            $approval_type_details = $api->get_approval_type_details($approval_type_id);
            $approver_details = $api->get_approver_details($approval_type_id);
            $approval_exception_details = $api->get_approval_exception_details($approval_type_id);

            $approver_table = '<table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>';

            $approval_exception_table = '<table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                </tr>
            </thead>
            <tbody>';

            if(count($approver_details) > 0){
                for($i = 0; $i < count($approver_details); $i++) {
                $employee_id = $approver_details[$i]['EMPLOYEE_ID'];
                $department = $approver_details[$i]['DEPARTMENT'];
                
                $employee_details = $api->get_employee_details($employee_id);
                $file_as = $employee_details[0]['FILE_AS'];
                $job_position = $employee_details[0]['JOB_POSITION'];
                $employee_image = $employee_details[0]['EMPLOYEE_IMAGE'];

                $department_details = $api->get_department_details($department);
                $department_name = $department_details[0]['DEPARTMENT'];

                $job_position_details = $api->get_job_position_details($job_position);
                $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

                if(empty($employee_image)){
                    $employee_image = $api->check_image($employee_image ?? null, 'profile');
                }

                $approver_table .= '<tr>
                                <td><img class="rounded-circle avatar-xs" src="'. $employee_image .'" alt="profile"></td>
                                <td>'. $file_as . '<p class="text-muted mb-0">'. $job_position_name .'</p></td>
                                <td>'. $department_name .'</td>
                            </tr>';
                }
            }
            else{
                $approver_table .= '<tr>
                            <td colspan="3"><p class="text-center">No Assigned Employee</p></td>
                        </tr>';
            }

            if(count($approval_exception_details) > 0){
                for($i = 0; $i < count($approval_exception_details); $i++) {
                $employee_id = $approval_exception_details[$i]['EMPLOYEE_ID'];
                
                $employee_details = $api->get_employee_details($employee_id);
                $file_as = $employee_details[0]['FILE_AS'];
                $job_position = $employee_details[0]['JOB_POSITION'];
                $employee_image = $employee_details[0]['EMPLOYEE_IMAGE'];

                $job_position_details = $api->get_job_position_details($job_position);
                $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

                if(empty($employee_image)){
                    $employee_image = $api->check_image($employee_image ?? null, 'profile');
                }

                $approval_exception_table .= '<tr>
                                <td><img class="rounded-circle avatar-xs" src="'. $employee_image .'" alt="profile"></td>
                                <td>'. $file_as . '<p class="text-muted mb-0">'. $job_position_name .'</p></td>
                            </tr>';
                }
            }
            else{
                $approval_exception_table .= '<tr>
                            <td colspan="3"><p class="text-center">No Assigned Employee</p></td>
                        </tr>';
            }

            $approver_table .= '</tbody>
            </table>';

            $approval_exception_table .= '</tbody>
            </table>';

            $response[] = array(
                'APPROVAL_TYPE' => $approval_type_details[0]['APPROVAL_TYPE'],
                'APPROVAL_TYPE_DESCRIPTION' => $approval_type_details[0]['APPROVAL_TYPE_DESCRIPTION'],
                'APPROVER_TABLE' => $approver_table,
                'APPROVAL_EXCEPTION_TABLE' => $approval_exception_table,
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Public holiday details
    else if($transaction == 'public holiday details'){
        if(isset($_POST['public_holiday_id']) && !empty($_POST['public_holiday_id'])){
            $work_locations = '';
            $public_holiday_id = $_POST['public_holiday_id'];
            $public_holiday_details = $api->get_public_holiday_details($public_holiday_id);
            $public_holiday_work_location_details = $api->get_public_holiday_work_location_details($public_holiday_id);

            for($i = 0; $i < count($public_holiday_work_location_details); $i++) {
                $work_locations .= $public_holiday_work_location_details[$i]['WORK_LOCATION_ID'];

                if($i != (count($public_holiday_work_location_details) - 1)){
                    $work_locations .= ',';
                }
            }

            $response[] = array(
                'PUBLIC_HOLIDAY' => $public_holiday_details[0]['PUBLIC_HOLIDAY'],
                'HOLIDAY_DATE' => $api->check_date('empty', $public_holiday_details[0]['HOLIDAY_DATE'], '', 'n/d/Y', '', '', ''),
                'HOLIDAY_TYPE' => $public_holiday_details[0]['HOLIDAY_TYPE'],
                'WORK_LOCATION_ID' => $work_locations,
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Public holiday summary details
    else if($transaction == 'public holiday summary details'){
        if(isset($_POST['public_holiday_id']) && !empty($_POST['public_holiday_id'])){
            $public_holiday_id = $_POST['public_holiday_id'];
            $public_holiday_details = $api->get_public_holiday_details($public_holiday_id);
            $public_holiday_work_location_details = $api->get_public_holiday_work_location_details($public_holiday_id);

            $system_code_details = $api->get_system_code_details('HOLIDAYTYPE', $public_holiday_details[0]['HOLIDAY_TYPE']);
            $holiday_type_name = $system_code_details[0]['SYSTEM_DESCRIPTION'];

            $work_location_table = '<table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Work Location</th>
                </tr>
            </thead>
            <tbody>';

            if(count($public_holiday_work_location_details) > 0){
                for($i = 0; $i < count($public_holiday_work_location_details); $i++) {
                    $work_location_id = $public_holiday_work_location_details[$i]['WORK_LOCATION_ID'];

                    $work_location_details = $api->get_work_location_details($work_location_id);

                    $work_location_table .= '<tr>
                                    <td>'. ($i + 1) .'</td>
                                    <td>'. $work_location_details[0]['WORK_LOCATION'] .'</td>
                                </tr>';
                }
            }
            else{
                $work_location_table .= '<tr>
                            <td colspan="2"><p class="text-center">No Assigned Employee</p></td>
                        </tr>';
            }

            $response[] = array(
                'PUBLIC_HOLIDAY' => $public_holiday_details[0]['PUBLIC_HOLIDAY'],
                'HOLIDAY_DATE' => $api->check_date('summary', $public_holiday_details[0]['HOLIDAY_DATE'], '', 'F d, Y', '', '', ''),
                'HOLIDAY_TYPE' => $holiday_type_name,
                'WORK_LOCATION_TABLE' => $work_location_table
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Leave type details
    else if($transaction == 'leave type details'){
        if(isset($_POST['leave_type_id']) && !empty($_POST['leave_type_id'])){
            $leave_type_id = $_POST['leave_type_id'];
            $leave_type_details = $api->get_leave_type_details($leave_type_id);

            $response[] = array(
                'LEAVE_TYPE' => $leave_type_details[0]['LEAVE_TYPE'],
                'PAID_TYPE' => $leave_type_details[0]['PAID_TYPE'],
                'ALLOCATION_TYPE' => $leave_type_details[0]['ALLOCATION_TYPE']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Leave allocation details
    else if($transaction == 'leave allocation details'){
        if(isset($_POST['leave_allocation_id']) && !empty($_POST['leave_allocation_id'])){
            $leave_allocation_id = $_POST['leave_allocation_id'];
            $leave_allocation_details = $api->get_leave_allocation_details($leave_allocation_id);

            $response[] = array(
                'LEAVE_TYPE_ID' => $leave_allocation_details[0]['LEAVE_TYPE_ID'],
                'EMPLOYEE_ID' => $leave_allocation_details[0]['EMPLOYEE_ID'],
                'VALIDITY_START_DATE' => $api->check_date('empty', $leave_allocation_details[0]['VALIDITY_START_DATE'], '', 'n/d/Y', '', '', ''),
                'VALIDITY_END_DATE' => $api->check_date('empty', $leave_allocation_details[0]['VALIDITY_END_DATE'], '', 'n/d/Y', '', '', ''),
                'DURATION' => $leave_allocation_details[0]['DURATION']
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Leave details
    else if($transaction == 'leave details'){
        if(isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $leave_id = $_POST['leave_id'];
            $leave_details = $api->get_leave_details($leave_id);

            $response[] = array(
                'LEAVE_TYPE_ID' => $leave_details[0]['LEAVE_TYPE_ID'],
                'EMPLOYEE_ID' => $leave_details[0]['EMPLOYEE_ID'],
                'REASON' => $leave_details[0]['REASON'],
                'LEAVE_DATE' => $api->check_date('empty', $leave_details[0]['LEAVE_DATE'], '', 'n/d/Y', '', '', ''),
                'START_TIME' => $api->check_date('empty', $leave_details[0]['START_TIME'], '', 'H:i:00', '', '', ''),
                'END_TIME' => $api->check_date('empty', $leave_details[0]['END_TIME'], '', 'H:i:00', '', '', '')
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

    # Leave summary details
    else if($transaction == 'leave summary details'){
        if(isset($_POST['leave_id']) && !empty($_POST['leave_id'])){
            $leave_id = $_POST['leave_id'];

            $leave_details = $api->get_leave_details($leave_id);
            $employee_id = $leave_details[0]['EMPLOYEE_ID'];
            $status = $leave_details[0]['STATUS'];
            $leave_type_id = $leave_details[0]['LEAVE_TYPE_ID'];
            $decision_by = $leave_details[0]['DECISION_BY'];

            $leave_type_details = $api->get_leave_type_details($leave_type_id);
            $leave_type = $leave_type_details[0]['LEAVE_TYPE'];
            
            $status_name = $api->get_leave_status($status)[0]['BADGE'];

            $employee_details = $api->get_employee_details($employee_id);
            $file_as = $employee_details[0]['FILE_AS'];

            $generate_leave_supporting_documents_table = $api->generate_leave_supporting_documents_table($leave_id);

            if(!empty($decision_by)){
                $decision_by_details = $api->get_employee_details($decision_by);
                $decision_by_file_as = $decision_by_details[0]['FILE_AS'] ?? $decision_by;
            }
            else{
                $decision_by_file_as = '--';
            }

            $response[] = array(
                'EMPLOYEE' => $file_as,
                'LEAVE_TYPE' => $leave_type,
                'LEAVE_DATE' => $api->check_date('summary', $leave_details[0]['LEAVE_DATE'], '', 'F d, Y', '', '', ''),
                'CREATED_DATE' => $api->check_date('summary', $leave_details[0]['CREATED_DATE'], '', 'F d, Y h:i a', '', '', ''),
                'FOR_APPROVAL_DATE' => $api->check_date('summary', $leave_details[0]['FOR_APPROVAL_DATE'], '', 'F d, Y h:i a', '', '', ''),
                'DECISION_DATE' => $api->check_date('summary', $leave_details[0]['DECISION_DATE'], '', 'F d, Y h:i a', '', '', ''),
                'START_TIME' => $api->check_date('summary', $leave_details[0]['START_TIME'], '', 'h:i a', '', '', ''),
                'END_TIME' => $api->check_date('summary', $leave_details[0]['END_TIME'], '', 'h:i a', '', '', ''),
                'REASON' => $leave_details[0]['REASON'],
                'TOTAL_HOURS' => $leave_details[0]['TOTAL_HOURS'],
                'DECISION_BY' => $decision_by_file_as,
                'DECISION_REMARKS' => $leave_details[0]['DECISION_REMARKS'],
                'STATUS' => $status_name,
                'SUPPORTING_DOCUMENTS' => $generate_leave_supporting_documents_table
            );

            echo json_encode($response);
        }
    }
    # -------------------------------------------------------------

}

?>