<?php
require('assets/libs/PHPMailer/src/Exception.php');
require('assets/libs/PHPMailer/src/PHPMailer.php');
require('assets/libs/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Api{
    # @var object $db_connection The database connection
    public $db_connection = null;

    public $response = array();

    # -------------------------------------------------------------
    #   Custom methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : databaseConnection
    # Purpose    : Checks if database connection is opened.
    #              If not, then this method tries to open it.
    #              @return bool Success status of the
    #              database connecting process
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function databaseConnection(){
        // if connection already exists
        if ($this->db_connection != null) {
            return true;
        } 
        else {
            try {
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';character_set=utf8', DB_USER, DB_PASS);
                return true;
            } 
            catch (PDOException $e) {
                $this->errors[] = $e->getMessage();
            }
        }
        // default return
        return false;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : backup_database
    # Purpose    : Backs-up the database.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function backup_database($file_name, $username){
        if ($this->databaseConnection()) {
            $backup_file = 'backup/' . $file_name . '_' . time() . '.sql';
            
            exec('C:\xampp\mysql\bin\mysqldump.exe --routines -u '. DB_USER .' -p'. DB_PASS .' '. DB_NAME .' -r "'. $backup_file .'"  2>&1', $output, $return);

            if(!$return) {
                return true;
            }
            else {
                return $return;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : format_date
    # Purpose    : Returns date with a custom formatting
    #              Avoids error when date is greater 
    #              than the year 2038 or less than 
    #              January 01, 1970.
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function format_date($format, $date, $modify){
        if(!empty($modify)){
            $datestring = (new DateTime($date))->modify($modify)->format($format);
        }
        else{
            $datestring = (new DateTime($date))->format($format);
        }

        return $datestring;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : CryptRC4
    # Purpose    : Returns the encrypted password using RC4-40.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function CryptRC4($text) {
        return openssl_encrypt($text, 'RC4-40', ENCRYPTION_KEY, 1 | 2);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : ToHexDump
    # Purpose    : Encrypt the text or password to binary hex.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function ToHexDump($text) {
        return bin2hex($text);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : FromHexDump
    # Purpose    : Decrypt the text or password to binary hex.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function FromHexDump($text) {
        return hex2bin($text);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : encrypt_data
    # Purpose    : Encrypt the text.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function encrypt_data($text) {
        return $this->ToHexDump($this->CryptRC4($text));
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : decrypt_data
    # Purpose    : Decrypt the text.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function decrypt_data($text) {
        return $this->CryptRC4($this->FromHexDump($text));
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : add_months
    # Purpose    : Add months to calculated date.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function add_months($months, DateTime $dateObject){
        # Format date to Y-m-d
        # Get the last day of the given month
        $next = new DateTime($dateObject->format('Y-m-d'));
        $next->modify('last day of +'.$months.' month');
    
        # If $dateObject day is greater than the day of $next
        # Return the difference
        # Else create a new interval
        if($dateObject->format('d') > $next->format('d')) {
            return $dateObject->diff($next);
        } else {
            return new DateInterval('P'.$months.'M');
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : validate_email
    # Purpose    : Validate if email is valid.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function validate_email($email){
        $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";

        if (preg_match($regex, $email)) {
            return true;
        }
        else{
            return 'The email is not valid';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : authenticate
    # Purpose    : Authenticates the user.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function authenticate($username, $password){
        if ($this->databaseConnection()) {
            $system_date = date('Y-m-d');
            $system_date_time = date('Y-m-d H:i:s');

            $check_user_account_exist = $this->check_user_account_exist($username);
           
            if($check_user_account_exist > 0){
                $user_account_details = $this->get_user_account_details($username);
                $user_status = $user_account_details[0]['USER_STATUS'];
                $login_attemp = $user_account_details[0]['FAILED_LOGIN'];
                $password_expiry_date = $user_account_details[0]['PASSWORD_EXPIRY_DATE'];
                $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

                if($user_status == 'Active'){
                    if($login_attemp < 5){
                        if($user_account_details[0]['PASSWORD'] === $password){
                            if(strtotime($system_date) > strtotime($password_expiry_date)){
                                return 'Password Expired';
                            }
                            else{
                                $update_login_attempt = $this->update_login_attempt($username, 0, null);

                                if($update_login_attempt){
                                    $update_user_last_connection = $this->update_user_last_connection($username);

                                    if($update_user_last_connection){
                                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Log In', 'User ' . $username . ' logged in.');
                                        
                                        if($insert_transaction_log){
                                            return 'Authenticated';
                                        }
                                        else{
                                            return $insert_transaction_log;
                                        }
                                    }
                                    else{
                                        return $update_user_last_connection;
                                    }
                                }
                                else{
                                    return $update_login_attempt;
                                }
                            }
                        }
                        else{
                            $update_login_attempt = $this->update_login_attempt($username, ($login_attemp + 1), $system_date_time);

                            if($update_login_attempt){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Attempt Log In', 'User ' . $username . ' attempted to log in.');
                                        
                                if($insert_transaction_log){
                                    return 'Incorrect';
                                }
                                else{
                                    return $insert_transaction_log;
                                }
                            }
                            else{
                                return $update_login_attempt;
                            }
                        }
                    }
                    else{
                        return 'Locked';
                    }
                }
                else{
                    return 'Inactive';
                }
            }
            else{
                return 'Incorrect';
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : send_email_notification
    # Purpose    : Sends notification email.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function send_email_notification($notification_type, $email, $subject, $body, $link, $is_menu_item, $character_set){
        $email_configuration_details = $this->get_email_configuration_details(1);
        $mail_host = $email_configuration_details[0]['MAIL_HOST'];
        $port = $email_configuration_details[0]['PORT'];
        $smtp_auth = $email_configuration_details[0]['SMTP_AUTH'];
        $smtp_auto_tls = $email_configuration_details[0]['SMTP_AUTO_TLS'];
        $mail_username = $email_configuration_details[0]['USERNAME'];
        $mail_password = $this->decrypt_data($email_configuration_details[0]['PASSWORD']);
        $mail_encryption = $email_configuration_details[0]['MAIL_ENCRYPTION'];
        $mail_from_name = $email_configuration_details[0]['MAIL_FROM_NAME'];
        $mail_from_email = $email_configuration_details[0]['MAIL_FROM_EMAIL'];

        $company_setting_details = $this->get_company_setting_details(1);
        $company_name = $company_setting_details[0]['COMPANY_NAME'];

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;

        $mail->Host = $mail_host;
        $mail->Port = $port;
        $mail->SMTPSecure = $mail_encryption;
        $mail->SMTPAuth = $smtp_auth;
        $mail->SMTPAutoTLS = $smtp_auto_tls;
        $mail->Username = $mail_username;
        $mail->Password = $mail_password;
        $mail->setFrom($mail_from_email, $mail_from_name);
        $mail->addAddress($email, $email);
        $mail->Subject = $subject;

        if($notification_type == 1 || $notification_type == 2 || $notification_type == 3 || $notification_type == 4 || $notification_type == 5 || $notification_type == 6 || $notification_type == 7 || $notification_type == 8 || $notification_type == 9 || $notification_type == 10 || $notification_type == 11 || $notification_type == 12 || $notification_type == 13 || $notification_type == 14 || $notification_type == 15 || $notification_type == 16 || $notification_type == 17 || $notification_type == 18 || $notification_type == 19){
            if(!empty($link)){
                $message = file_get_contents('email_template/basic-notification-with-button.menu_item');
                $message = str_replace('@link', $link, $message);

                if($notification_type == 1 || $notification_type == 2){
                    $message = str_replace('@button_title', 'View Attendance Record', $message);
                }
                else if($notification_type == 3 || $notification_type == 4 || $notification_type == 5 || $notification_type == 6 || $notification_type == 7 || $notification_type == 13){
                    $message = str_replace('@button_title', 'View Attendance Adjustment', $message);
                }
                else if($notification_type == 8 || $notification_type == 9 || $notification_type == 10 || $notification_type == 11 || $notification_type == 12 || $notification_type == 14){
                    $message = str_replace('@button_title', 'View Attendance Creation', $message);
                }
            }
            else{
                $message = file_get_contents('email_template/basic-notification.menu_item'); 
            }
            
            $message = str_replace('@company_name', $company_name, $message);
            $message = str_replace('@year', date('Y'), $message);
            $message = str_replace('@title', $subject, $message);
            $message = str_replace('@body', $body, $message);
        }
        else if($notification_type == 'send payslip'){
            $message = $body;
        }

        if($is_menu_item){
            $mail->isHTML(true);
            $mail->MsgHTML($message);
            $mail->CharSet = $character_set;
        }
        else{
            $mail->Body = $body;
        }

        if ($mail->send()) {
            return true;
        } 
        else {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : send_notification
    # Purpose    : Sends notification.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function send_notification($notification_id, $from, $sent_to, $title, $message, $username){
        $system_notification = $this->check_notification_channel($notification_id, 'SYSTEM');
        $email_notification = $this->check_notification_channel($notification_id, 'EMAIL');

        if($system_notification > 0 || $email_notification > 0){
            $error = '';
            $employee_details = $this->get_employee_details($sent_to);
            $work_email = $employee_details[0]['WORK_EMAIL'] ?? null;
            $validate_email = $this->validate_email($work_email);

            $notification_template_details = $this->get_notification_template_details($notification_id);
            $system_link = $notification_template_details[0]['SYSTEM_LINK'] ?? null;
            $web_link = $notification_template_details[0]['WEB_LINK'] ?? null;

            if($system_notification > 0){
                $insert_system_notification = $this->insert_system_notification($notification_id, $from, $sent_to, $title, $message, $system_link, $username);

                if(!$insert_system_notification){
                    $error = $insert_system_notification;
                }
            }

            if($email_notification > 0){
                if(!empty($work_email) && $validate_email){
                    $send_email_notification = $this->send_email_notification($notification_id, $email, $title, $message, $web_link, 1, 'utf-8');
    
                    if(!$send_email_notification){
                        $error = $send_email_notification;
                    }
                }
            }

            if(empty($error)){
                return true;
            }
            else{
                return $error;
            }
        }
        else{
            return true;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : time_elapsed_string
    # Purpose    : returns the time elapsed
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } 
            else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : directory_checker
    # Purpose    : Checks the directory if it exists and create if not exist
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function directory_checker($directory) {
        if(!file_exists($directory)) {
            mkdir($directory, 0777);
            return true;
        } 
        else {
            return true;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check data exist methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : check_user_account_exist
    # Purpose    : Checks if the user exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_user_account_exist($username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_user_account_exist(:username)');
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_module_exist
    # Purpose    : Checks if the module exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_module_exist($module_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_module_exist(:module_id)');
            $sql->bindValue(':module_id', $module_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_module_access_exist
    # Purpose    : Checks if the module access exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_module_access_exist($module_id, $role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_module_access_exist(:module_id, :role_id)');
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_page_exist
    # Purpose    : Checks if the page exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_page_exist($page_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_page_exist(:page_id)');
            $sql->bindValue(':page_id', $page_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_page_access_exist
    # Purpose    : Checks if the page access exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_page_access_exist($page_id, $role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_page_access_exist(:page_id, :role_id)');
            $sql->bindValue(':page_id', $page_id);
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_action_exist
    # Purpose    : Checks if the action exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_action_exist($action_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_action_exist(:action_id)');
            $sql->bindValue(':action_id', $action_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_action_access_exist
    # Purpose    : Checks if the action access exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_action_access_exist($action_id, $role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_action_access_exist(:action_id, :role_id)');
            $sql->bindValue(':action_id', $action_id);
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_system_parameter_exist
    # Purpose    : Checks if the system parameter exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_system_parameter_exist($parameter_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_system_parameter_exist(:parameter_id)');
            $sql->bindValue(':parameter_id', $parameter_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_role_exist
    # Purpose    : Checks if the role exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_role_exist($role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_role_exist(:role_id)');
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_role_user_account_exist
    # Purpose    : Checks if the role user account exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_role_user_account_exist($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_role_user_account_exist(:role_id, :username)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_system_code_exist
    # Purpose    : Checks if the system code exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_system_code_exist($system_code_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_system_code_exist(:system_code_id)');
            $sql->bindValue(':system_code_id', $system_code_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_upload_setting_exist
    # Purpose    : Checks if the upload setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_upload_setting_exist($upload_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_upload_setting_exist(:upload_setting_id)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_upload_setting_file_type_exist
    # Purpose    : Checks if the upload setting file type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_upload_setting_file_type_exist($upload_setting_id, $file_type){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_upload_setting_file_type_exist(:upload_setting_id, :file_type)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
            $sql->bindValue(':file_type', $file_type);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_company_exist
    # Purpose    : Checks if the upload setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_company_exist($company_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_company_exist(:company_id)');
            $sql->bindValue(':company_id', $company_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : update_login_attempt
    # Purpose    : Updates the login attempt of the user.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_login_attempt($username, $login_attemp, $last_failed_attempt_date){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_login_attempt(:username, :login_attemp, :last_failed_attempt_date)');
            $sql->bindValue(':username', $username);
            $sql->bindValue(':login_attemp', $login_attemp);
            $sql->bindValue(':last_failed_attempt_date', $last_failed_attempt_date);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_user_last_connection
    # Purpose    : Updates the last user connection date.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_last_connection($username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_user_last_connection(:username, :system_date)');
            $sql->bindValue(':username', $username);
            $sql->bindValue(':system_date', date('Y-m-d H:i:s'));

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_system_parameter_value
    # Purpose    : Updates system parameter value.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_system_parameter_value($parameter_number, $parameter_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL update_system_parameter_value(:parameter_id, :parameter_number, :record_log)');
            $sql->bindValue(':parameter_id', $parameter_id);
            $sql->bindValue(':parameter_number', $parameter_number);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_system_parameter
    # Purpose    : Updates system parameter.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_system_parameter($parameter_id, $parameter, $parameter_description, $parameter_extension, $parameter_number, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $system_parameter_details = $this->get_system_parameter_details($parameter_id);
            
            if(!empty($system_parameter_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $system_parameter_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_system_parameter(:parameter_id, :parameter, :parameter_description, :parameter_extension, :parameter_number, :transaction_log_id, :record_log)');
            $sql->bindValue(':parameter_id', $parameter_id);
            $sql->bindValue(':parameter', $parameter);
            $sql->bindValue(':parameter_description', $parameter_description);
            $sql->bindValue(':parameter_extension', $parameter_extension);
            $sql->bindValue(':parameter_number', $parameter_number);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($system_parameter_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated system parameter.');
                                        
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated system parameter.');
                                        
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_module
    # Purpose    : Updates module.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_module($module_id, $module_name, $module_version, $module_description, $module_category, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $module_details = $this->get_module_details($module_id);
            
            if(!empty($module_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $module_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_module(:module_id, :module_name, :module_version, :module_description, :module_category, :transaction_log_id, :record_log)');
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':module_name', $module_name);
            $sql->bindValue(':module_version', $module_version);
            $sql->bindValue(':module_description', $module_description);
            $sql->bindValue(':module_category', $module_category);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($module_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated module.');
                                    
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated module.');
                                    
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_module_icon
    # Purpose    : Updates module icon.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_module_icon($module_icon_tmp_name, $module_icon_actual_ext, $module_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            if(!empty($module_icon_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $module_icon_actual_ext;

                $directory = './assets/images/module_icon/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/dss/assets/images/module_icon/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $module_details = $this->get_module_details($module_id);
                    $module_icon = $module_details[0]['MODULE_ICON'];
                    $transaction_log_id = $module_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($module_icon)){
                        if (unlink($module_icon)) {
                            if(move_uploaded_file($module_icon_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_module_icon(:module_id, :file_path)');
                                $sql->bindValue(':module_id', $module_id);
                                $sql->bindValue(':file_path', $file_path);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated module icon.');
                                        
                                    if($insert_transaction_log){
                                        return true;
                                    }
                                    else{
                                        return $insert_transaction_log;
                                    }
                                }
                                else{
                                    return $sql->errorInfo()[2];
                                }
                            }
                            else{
                                return 'There was an error uploading your file.';
                            }
                        }
                        else {
                            return $module_icon . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($module_icon_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_module_icon(:module_id, :file_path)');
                            $sql->bindValue(':module_id', $module_id);
                            $sql->bindValue(':file_path', $file_path);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated icon.');
                                    
                                if($insert_transaction_log){
                                    return true;
                                }
                                else{
                                    return $insert_transaction_log;
                                }
                            }
                            else{
                                return $sql->errorInfo()[2];
                            }
                        }
                        else{
                            return 'There was an error uploading your file.';
                        }
                    }
                }
                else{
                    return $directory_checker;
                }
            }
            else{
                return true;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_page
    # Purpose    : Updates page.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_page($page_id, $page_name, $module_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $page_details = $this->get_page_details($page_id);
            
            if(!empty($page_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $page_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_page(:page_id, :page_name, :module_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':page_id', $page_id);
            $sql->bindValue(':page_name', $page_name);
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($page_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated page.');
                                    
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated page.');
                                    
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_action
    # Purpose    : Updates action.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_action($action_id, $action_name, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $action_details = $this->get_action_details($action_id);
            
            if(!empty($action_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $action_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_action(:action_id, :action_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':action_id', $action_id);
            $sql->bindValue(':action_name', $action_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($action_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated action.');
                                    
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated action.');
                                    
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_role
    # Purpose    : Updates role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_role($role_id, $role, $role_description, $assignable, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $role_details = $this->get_role_details($role_id);
            
            if(!empty($role_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $role_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_role(:role_id, :role, :role_description, :assignable, :transaction_log_id, :record_log)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':role', $role);
            $sql->bindValue(':role_description', $role_description);
            $sql->bindValue(':assignable', $assignable);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($role_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated role.');
                                    
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated role.');
                                    
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_system_code
    # Purpose    : Updates system code.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_system_code($system_code_id, $system_type, $system_code, $system_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $system_code_details = $this->get_system_code_details($parameter_id, null, null);
            
            if(!empty($system_code_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $system_code_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_system_code(:system_code_id, :system_type, :system_code, :system_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':system_code_id', $system_code_id);
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);
            $sql->bindValue(':system_description', $system_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($system_code_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated system code.');
                                        
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated system code.');
                                        
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_upload_setting
    # Purpose    : Updates upload setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_upload_setting($upload_setting_id, $upload_setting, $description, $max_file_size, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $upload_setting_details = $this->get_upload_setting_details($parameter_id, null, null);
            
            if(!empty($upload_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $upload_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_upload_setting(:upload_setting_id, :upload_setting, :description, :max_file_size, :transaction_log_id, :record_log)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
            $sql->bindValue(':upload_setting', $upload_setting);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':max_file_size', $max_file_size);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($upload_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated upload setting.');
                                        
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated upload setting.');
                                        
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_company
    # Purpose    : Updates company.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_company($company_id, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $company_details = $this->get_company_details($company_id);
            
            if(!empty($company_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $company_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_company(:company_id, :company_name, :company_address, :email, :telephone, :mobile, :website, :tax_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':company_id', $company_id);
            $sql->bindValue(':company_name', $company_name);
            $sql->bindValue(':company_address', $company_address);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':website', $website);
            $sql->bindValue(':tax_id', $tax_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($company_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated company.');
                                    
                    if($insert_transaction_log){
                        return true;
                    }
                    else{
                        return $insert_transaction_log;
                    }
                }
                else{
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated company.');
                                    
                        if($insert_transaction_log){
                            return true;
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_company_logo
    # Purpose    : Updates company logo.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_company_logo($company_logo_tmp_name, $company_logo_actual_ext, $company_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            if(!empty($company_logo_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $company_logo_actual_ext;

                $directory = './assets/images/company/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/dss/assets/images/company/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $company_details = $this->get_company_details($company_id);
                    $company_logo = $company_details[0]['COMPANY_LOGO'];
                    $transaction_log_id = $company_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($company_logo)){
                        if (unlink($company_logo)) {
                            if(move_uploaded_file($company_logo_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_company_logo(:company_id, :file_path)');
                                $sql->bindValue(':company_id', $company_id);
                                $sql->bindValue(':file_path', $file_path);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated company logo.');
                                        
                                    if($insert_transaction_log){
                                        return true;
                                    }
                                    else{
                                        return $insert_transaction_log;
                                    }
                                }
                                else{
                                    return $sql->errorInfo()[2];
                                }
                            }
                            else{
                                return 'There was an error uploading your file.';
                            }
                        }
                        else {
                            return $company_logo . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($company_logo_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_company_logo(:company_id, :file_path)');
                            $sql->bindValue(':company_id', $company_id);
                            $sql->bindValue(':file_path', $file_path);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated company logo.');
                                    
                                if($insert_transaction_log){
                                    return true;
                                }
                                else{
                                    return $insert_transaction_log;
                                }
                            }
                            else{
                                return $sql->errorInfo()[2];
                            }
                        }
                        else{
                            return 'There was an error uploading your file.';
                        }
                    }
                }
                else{
                    return $directory_checker;
                }
            }
            else{
                return true;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : insert_transaction_log
    # Purpose    : Inserts user log activities.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_transaction_log($transaction_log_id, $username, $log_type, $log){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_transaction_log(:transaction_log_id, :username, :log_type, :log_date, :log)');
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':log_type', $log_type);
            $sql->bindValue(':log_date', date('Y-m-d H:i:s'));
            $sql->bindValue(':log', $log);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_system_parameter
    # Purpose    : Insert system parameter.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_system_parameter($parameter, $parameter_description, $parameter_extension, $number, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(1, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_system_parameter(:id, :parameter, :parameter_description, :parameter_extension, :number, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':parameter', $parameter);
            $sql->bindValue(':parameter_description', $parameter_description);
            $sql->bindValue(':parameter_extension', $parameter_extension);
            $sql->bindValue(':number', $number);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 1, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted system parameter.');
                                        
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'PARAMETER_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'PARAMETER_ID' => $this->encrypt_data($id)
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'PARAMETER_ID' => $this->encrypt_data($id)
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'PARAMETER_ID' => $this->encrypt_data($id)
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'PARAMETER_ID' => $this->encrypt_data($id)
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_module
    # Purpose    : Insert module.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_module($module_icon_tmp_name, $module_icon_actual_ext, $module_name, $module_version, $module_description, $module_category, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(3, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_module(:id, :module_name, :module_version, :module_description, :module_category, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':module_name', $module_name);
            $sql->bindValue(':module_version', $module_version);
            $sql->bindValue(':module_description', $module_description);
            $sql->bindValue(':module_category', $module_category);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 3, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted module.');
                                    
                        if($insert_transaction_log){
                            if(!empty($module_icon_tmp_name) && !empty($module_icon_actual_ext)){
                                $update_module_icon = $this->update_module_icon($module_icon_tmp_name, $module_icon_actual_ext, $id, $username);
        
                                if($update_module_icon){
                                    $response[] = array(
                                        'RESPONSE' => true,
                                        'MODULE_ID' => $this->encrypt_data($id)
                                    );
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
                                    'RESPONSE' => true,
                                    'MODULE_ID' => $this->encrypt_data($id)
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'MODULE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'MODULE_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'MODULE_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'MODULE_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_module_access
    # Purpose    : Inserts module access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_module_access($module_id, $role, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_module_access(:module_id, :role)');
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':role', $role);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_page
    # Purpose    : Insert page.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_page($page_name, $module_id, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(4, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_page(:id, :page_name, :module_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':page_name', $page_name);
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 4, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted page.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'PAGE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'PAGE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'PAGE_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'PAGE_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'PAGE_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_page_access
    # Purpose    : Inserts page access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_page_access($page_id, $role, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_page_access(:page_id, :role)');
            $sql->bindValue(':page_id', $page_id);
            $sql->bindValue(':role', $role);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_action
    # Purpose    : Insert action.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_action($action_name, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(5, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_action(:id, :action_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':action_name', $action_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 5, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted action.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'ACTION_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'ACTION_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'ACTION_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'ACTION_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'ACTION_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_action_access
    # Purpose    : Inserts action access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_action_access($action_id, $role, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_action_access(:action_id, :role)');
            $sql->bindValue(':action_id', $action_id);
            $sql->bindValue(':role', $role);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_role
    # Purpose    : Insert role.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_role($role, $role_description, $assignable, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(6, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_role(:id, :role, :role_description, :assignable, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':role', $role);
            $sql->bindValue(':role_description', $role_description);
            $sql->bindValue(':assignable', $assignable);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 6, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted role.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'ROLE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'ROLE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'ROLE_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'ROLE_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'ROLE_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_role_user_account
    # Purpose    : Inserts role user account.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_role_user_account($role_id, $user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_role_user_account(:role_id, :user_id)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':user_id', $user_id);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_system_code
    # Purpose    : Insert system code.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_system_code($system_type, $system_code, $system_description, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(8, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_system_code(:id, :system_type, :system_code, :system_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);
            $sql->bindValue(':system_description', $system_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 8, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted system code.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'SYSTEM_CODE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'SYSTEM_CODE_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'SYSTEM_CODE_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'SYSTEM_CODE_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'SYSTEM_CODE_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_upload_setting
    # Purpose    : Insert upload setting.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_upload_setting($upload_setting, $description, $max_file_size, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(7, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_upload_setting(:id, :upload_setting, :description, :max_file_size, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':upload_setting', $upload_setting);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':max_file_size', $max_file_size);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 7, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted upload setting.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'UPLOAD_SETTING_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'UPLOAD_SETTING_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'UPLOAD_SETTING_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'UPLOAD_SETTING_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'UPLOAD_SETTING_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_upload_setting_file_type
    # Purpose    : Inserts upload setting file type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_upload_setting_file_type($upload_setting_id, $file_type, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_upload_setting_file_type(:upload_setting_id, :file_type)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
            $sql->bindValue(':file_type', $file_type);

            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_company
    # Purpose    : Insert company.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_company($company_logo_tmp_name, $company_logo_actual_ext, $company_name, $company_address, $email, $telephone, $mobile, $website, $tax_id, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(9, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_company(:id, :company_name, :company_address, :email, :telephone, :mobile, :website, :tax_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':company_name', $company_name);
            $sql->bindValue(':company_address', $company_address);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':website', $website);
            $sql->bindValue(':tax_id', $tax_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 9, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted company.');
                                    
                        if($insert_transaction_log){
                            if(!empty($company_logo_tmp_name) && !empty($company_logo_actual_ext)){
                                $update_company_logo = $this->update_company_logo($company_logo_tmp_name, $company_logo_actual_ext, $id, $username);
        
                                if($update_company_logo){
                                    $response[] = array(
                                        'RESPONSE' => true,
                                        'COMPANY_ID' => $this->encrypt_data($id)
                                    );
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
                                    'RESPONSE' => true,
                                    'COMPANY_ID' => $this->encrypt_data($id)
                                );
                            }
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                                'COMPANY_ID' => null
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                            'COMPANY_ID' => null
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value,
                        'COMPANY_ID' => null
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2],
                    'COMPANY_ID' => null
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : delete_system_parameter
    # Purpose    : Delete system parameter.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_system_parameter($parameter_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_system_parameter(:parameter_id)');
            $sql->bindValue(':parameter_id', $parameter_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_module
    # Purpose    : Delete module.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_module($module_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_module(:module_id)');
            $sql->bindValue(':module_id', $module_id);
        
            if($sql->execute()){ 
                $module_details = $this->get_module_details($module_id);
                $module_icon = $module_details[0]['MODULE_ICON'] ?? null;

                if(!empty($module_icon)){
                    if(file_exists($module_icon)){
                        if (unlink($module_icon)) {
                            return true;
                        }
                        else {
                            return $module_icon . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        return true;
                    }
                }
                else{
                    return true;
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_module_access
    # Purpose    : Delete all module access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_module_access($module_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_module_access(:module_id)');
            $sql->bindValue(':module_id', $module_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_module_access
    # Purpose    : Delete module access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_module_access($module_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_module_access(:module_id, :role_id)');
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_page
    # Purpose    : Delete page.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_page($page_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_page(:page_id)');
            $sql->bindValue(':page_id', $page_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_page_access
    # Purpose    : Delete all page access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_page_access($page_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_page_access(:page_id)');
            $sql->bindValue(':page_id', $page_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_page_access
    # Purpose    : Delete page access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_page_access($page_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_page_access(:page_id, :role_id)');
            $sql->bindValue(':page_id', $page_id);
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_action
    # Purpose    : Delete action.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_action($action_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_action(:action_id)');
            $sql->bindValue(':action_id', $action_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_action_access
    # Purpose    : Delete all action access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_action_access($action_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_action_access(:action_id)');
            $sql->bindValue(':action_id', $action_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_action_access
    # Purpose    : Delete action access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_action_access($action_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_action_access(:action_id, :role_id)');
            $sql->bindValue(':action_id', $action_id);
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_role
    # Purpose    : Delete role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_role($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_role(:role_id)');
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_role_module_access
    # Purpose    : Delete role module access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_role_module_access($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_role_module_access(:role_id)');
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_role_page_access
    # Purpose    : Delete role page access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_role_page_access($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_role_page_access(:role_id)');
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_role_action_access
    # Purpose    : Delete role action access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_role_action_access($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_role_action_access(:role_id)');
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_role_user_account
    # Purpose    : Delete role user account access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_role_user_account($role_id, $user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_role_user_account(:role_id, :user_id)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':user_id', $user_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_role_user_account
    # Purpose    : Delete all role user account access.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_role_user_account($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_role_user_account(:role_id)');
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_system_code
    # Purpose    : Delete system code.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_system_code($system_code_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_system_code(:system_code_id)');
            $sql->bindValue(':system_code_id', $system_code_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_upload_setting
    # Purpose    : Delete upload setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_upload_setting($upload_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_upload_setting(:upload_setting_id)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_upload_setting_file_type
    # Purpose    : Delete all upload setting file type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_upload_setting_file_type($upload_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_upload_setting_file_type(:upload_setting_id)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_upload_setting_file_type
    # Purpose    : Delete upload setting file type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_upload_setting_file_type($upload_setting_id, $file_type, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_upload_setting_file_type(:upload_setting_id, :file_type)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);
            $sql->bindValue(':file_type', $file_type);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_company
    # Purpose    : Delete company.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_company($company_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_company(:company_id)');
            $sql->bindValue(':company_id', $company_id);
        
            if($sql->execute()){ 
                $company_details = $this->get_company_details($company_id);
                $company_logo = $company_details[0]['COMPANY_LOGO'] ?? null;

                if(!empty($company_logo)){
                    if(file_exists($company_logo)){
                        if (unlink($company_logo)) {
                            return true;
                        }
                        else {
                            return $company_logo . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        return true;
                    }
                }
                else{
                    return true;
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_user_account_details
    # Purpose    : Gets the user account details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_account_details($username){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_user_account_details(:username)');
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PASSWORD' => $row['PASSWORD'],
                        'FILE_AS' => $row['FILE_AS'],
                        'USER_STATUS' => $row['USER_STATUS'],
                        'PASSWORD_EXPIRY_DATE' => $row['PASSWORD_EXPIRY_DATE'],
                        'FAILED_LOGIN' => $row['FAILED_LOGIN'],
                        'LAST_FAILED_LOGIN' => $row['LAST_FAILED_LOGIN'],
                        'LAST_CONNECTION_DATE' => $row['LAST_CONNECTION_DATE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_system_code_details
    # Purpose    : Gets the system code details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_system_code_details($system_code_id, $system_type, $system_code){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_system_code_details(:system_code_id, :system_type, :system_code)');
            $sql->bindValue(':system_code_id', $system_code_id);
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'SYSTEM_CODE_ID' => $row['SYSTEM_CODE_ID'],
                        'SYSTEM_TYPE' => $row['SYSTEM_TYPE'],
                        'SYSTEM_CODE' => $row['SYSTEM_CODE'],
                        'SYSTEM_DESCRIPTION' => $row['SYSTEM_DESCRIPTION'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_role_details
    # Purpose    : Gets the role details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_role_details($role_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_role_details(:role_id)');
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ROLE' => $row['ROLE'],
                        'ROLE_DESCRIPTION' => $row['ROLE_DESCRIPTION'],
                        'ASSIGNABLE' => $row['ASSIGNABLE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_system_parameter_details
    # Purpose    : Gets the system parameter details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_system_parameter_details($parameter_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_system_parameter_details(:parameter_id)');
            $sql->bindValue(':parameter_id', $parameter_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PARAMETER' => $row['PARAMETER'],
                        'PARAMETER_DESCRIPTION' => $row['PARAMETER_DESCRIPTION'],
                        'PARAMETER_EXTENSION' => $row['PARAMETER_EXTENSION'],
                        'PARAMETER_NUMBER' => $row['PARAMETER_NUMBER'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_module_details
    # Purpose    : Gets the module details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_module_details($module_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_module_details(:module_id)');
            $sql->bindValue(':module_id', $module_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MODULE_NAME' => $row['MODULE_NAME'],
                        'MODULE_VERSION' => $row['MODULE_VERSION'],
                        'MODULE_DESCRIPTION' => $row['MODULE_DESCRIPTION'],
                        'MODULE_ICON' => $row['MODULE_ICON'],
                        'MODULE_CATEGORY' => $row['MODULE_CATEGORY'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG'],
                        'ORDER_SEQUENCE' => $row['ORDER_SEQUENCE']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_page_details
    # Purpose    : Gets the page details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_page_details($page_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_page_details(:page_id)');
            $sql->bindValue(':page_id', $page_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PAGE_NAME' => $row['PAGE_NAME'],
                        'MODULE_ID' => $row['MODULE_ID'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_action_details
    # Purpose    : Gets the action details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_action_details($action_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_action_details(:action_id)');
            $sql->bindValue(':action_id', $action_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ACTION_NAME' => $row['ACTION_NAME'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_upload_setting_details
    # Purpose    : Gets the upload setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_upload_setting_details($upload_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_upload_setting_details(:upload_setting_id)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'UPLOAD_SETTING' => $row['UPLOAD_SETTING'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'MAX_FILE_SIZE' => $row['MAX_FILE_SIZE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_upload_file_type_details
    # Purpose    : Gets the upload file type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_upload_file_type_details($upload_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_upload_file_type_details(:upload_setting_id)');
            $sql->bindValue(':upload_setting_id', $upload_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'FILE_TYPE' => $row['FILE_TYPE']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_company_details
    # Purpose    : Gets the company details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_company_details($company_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_company_details(:company_id)');
            $sql->bindValue(':company_id', $company_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'COMPANY_NAME' => $row['COMPANY_NAME'],
                        'COMPANY_LOGO' => $row['COMPANY_LOGO'],
                        'COMPANY_ADDRESS' => $row['COMPANY_ADDRESS'],
                        'EMAIL' => $row['EMAIL'],
                        'TELEPHONE' => $row['TELEPHONE'],
                        'MOBILE' => $row['MOBILE'],
                        'WEBSITE' => $row['WEBSITE'],
                        'TAX_ID' => $row['TAX_ID'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : get_access_rights_count
    # Purpose    : Gets the roles' access right count based on access type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_access_rights_count($role_id, $access_right_id, $access_type){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL get_access_rights_count(:role_id, :access_right_id, :access_type)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':access_right_id', $access_right_id);
            $sql->bindValue(':access_type', $access_type);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'];
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_system_parameter
    # Purpose    : Gets the system parameter.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_system_parameter($parameter_id, $add){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL get_system_parameter(:parameter_id)');
            $sql->bindValue(':parameter_id', $parameter_id);

            if($sql->execute()){
                $row = $sql->fetch();

                $parameter_number = $row['PARAMETER_NUMBER'] + $add;
                $parameter_extension = $row['PARAMETER_EXTENSION'];

                $response[] = array(
                    'PARAMETER_NUMBER' => $parameter_number,
                    'ID' => $parameter_extension . $parameter_number
                );

                return $response;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_roles_assignable_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_roles_assignable_status($stat){
        $response = array();

        switch ($stat) {
            case 1:
                $status = 'True';
                $button_class = 'bg-success';
                break;
            default:
                $status = 'False';
                $button_class = 'bg-danger';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_modal_scrollable
    # Purpose    : Check if the modal to be generated
    #              is scrollable or not.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_modal_scrollable($scrollable){
        if($scrollable){
            return 'modal-dialog-scrollable';
        }
        else{
            return '';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_modal_size
    # Purpose    : Check the size of the modal.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_modal_size($size){
        if($size == 'SM'){
            return 'modal-sm';
        }
        else if($size == 'LG'){
            return 'modal-lg';
        }
        else if($size == 'XL'){
            return 'modal-xl';
        }
        else {
            return '';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_number
    # Purpose    : Checks the number if empty or 0 
    #              return 0 or return number given.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_number($number){
        if(is_numeric($number) && (!empty($number) || $number > 0) && !empty($number)){
            return $number;
        }
        else{
            return '0';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_date
    # Purpose    : Checks the date with different format
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function check_date($type, $date, $time, $format, $modify, $system_date, $current_time){
        if($type == 'default'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return $system_date;
            }
        }
        else if($type == 'empty'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return null;
            }
        }
        else if($type == 'attendance empty'){
            if(!empty($date) && $date != ' '){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return null;
            }
        }
        else if($type == 'summary'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return '--';
            }
        }
        else if($type == 'na'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify);
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'complete'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'encoded'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'date time'){
            if(!empty($date)){
                return $this->format_date($format, $date, $modify) . ' ' . $time;
            }
            else{
                return 'N/A';
            }
        }
        else if($type == 'default time'){
            if(!empty($date)){
                return $time;
            }
            else{
                return $current_time;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_image
    # Purpose    : Checks the image.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_image($image, $type){
        if(empty($image) || !file_exists($image)){
            switch ($type) {
                case 'profile':
                    return './assets/images/default/default-avatar.png';
                break;
                case 'login background':
                    return './assets/images/default/default-bg.jpg';
                break;
                case 'login logo':
                    return './assets/images/default/default-login-logo.png';
                break;
                case 'menu logo':
                    return './assets/images/default/default-menu-logo.png';
                break;
                case 'menu icon':
                    return './assets/images/default/default-menu-icon.png';
                break;
                case 'favicon':
                    return './assets/images/default/default-favicon.png';
                break;
                case 'company logo':
                    return './assets/images/default/default-company-logo.png';
                break;
                default:
                    return './assets/images/default/default-image-placeholder.png';
            }
        }
        else{
            return $image;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_user_account_status
    # Purpose    : Checks the user account status. 
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function check_user_account_status($username){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($username);
            $user_status = $user_account_details[0]['USER_STATUS'];
            $failed_login = $user_account_details[0]['FAILED_LOGIN'];

            if($user_status == 'Active' && $failed_login < 5){
                return true;
            }
            else{
                return false;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_role_access_rights
    # Purpose    : Checks the access rights of the role based on type.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_role_access_rights($username, $access_right_id, $access_type){
        if ($this->databaseConnection()) {
            $total = 0;

            $sql = $this->db_connection->prepare('SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = :username');
            $sql->bindValue(':username', $username);

            if($sql->execute()){       
                while($row = $sql->fetch()){
                    $role_id = $row['ROLE_ID'];
                    $total += $this->get_access_rights_count($role_id, $access_right_id, $access_type);
                }

                return $total;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_file_name
    # Purpose    : generates random file name.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_file_name($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate options methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_system_code_options
    # Purpose    : Generates system code options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_system_code_options($system_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_system_code_options(:system_type)');
            $sql->bindValue(':system_type', $system_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $system_code = $row['SYSTEM_CODE'];
                        $system_description = $row['SYSTEM_DESCRIPTION'];
    
                        $option .= "<option value='". $system_code ."'>". $system_description ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_role_options
    # Purpose    : Generates role options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_role_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_role_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $role_id = $row['ROLE_ID'];
                        $role = $row['ROLE'];
    
                        $option .= "<option value='". $role_id ."'>". $role ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_module_options
    # Purpose    : Generates module options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_module_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_module_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $module_id = $row['MODULE_ID'];
                        $module_name = $row['MODULE_NAME'];
    
                        $option .= "<option value='". $module_id ."'>". $module_name ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

}

?>