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
    # Name       : remove_comma
    # Purpose    : Removes comma from number.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function remove_comma($number){
        return str_replace(',', '', $number);
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

                if($user_status == 'ACTIVE'){
                    if($login_attemp < 5){
                        if($user_account_details[0]['PASSWORD'] === $password){
                            if(strtotime($system_date) > strtotime($password_expiry_date)){
                                return 'Password Expired';
                            }
                            else{
                                $update_login_attempt = $this->update_login_attempt($username, 0, null);

                                if($update_login_attempt){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Log In', 'User ' . $username . ' logged in.');
                                        
                                    if($insert_transaction_log){
                                        return 'Authenticated';
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
    public function send_email_notification($notification_type, $email, $subject, $body, $link, $is_html, $character_set){
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
                $message = file_get_contents('email_template/basic-notification-with-button.html');
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
                $message = file_get_contents('email_template/basic-notification.html'); 
            }
            
            $message = str_replace('@company_name', $company_name, $message);
            $message = str_replace('@year', date('Y'), $message);
            $message = str_replace('@title', $subject, $message);
            $message = str_replace('@body', $body, $message);
        }
        else if($notification_type == 'send payslip'){
            $message = $body;
        }

        if($is_html){
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
    # Name       : check_policy_exist
    # Purpose    : Checks if the policy exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_policy_exist($policy_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_policy_exist(:policy_id)');
            $sql->bindValue(':policy_id', $policy_id);

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
    # Name       : check_permission_exist
    # Purpose    : Checks if the permission exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_permission_exist($permission_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_permission_exist(:permission_id)');
            $sql->bindValue(':permission_id', $permission_id);

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
    # Name       : check_system_code_exist
    # Purpose    : Checks if the system code exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_system_code_exist($system_type, $system_code){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_system_code_exist(:system_type, :system_code)');
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);

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
    # Name       : check_company_exist
    # Purpose    : Checks if the company exists.
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
    #
    # Name       : check_country_exist
    # Purpose    : Checks if the country exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_country_exist($country_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_country_exist(:country_id)');
            $sql->bindValue(':country_id', $country_id);

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
    # Name       : check_state_exist
    # Purpose    : Checks if the state exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_state_exist($state_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_state_exist(:state_id)');
            $sql->bindValue(':state_id', $state_id);

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
    # Name       : check_notification_setting_exist
    # Purpose    : Checks if the notification setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_setting_exist($notification_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_setting_exist(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

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
    # Name       : check_notification_template_exist
    # Purpose    : Checks if the notification template exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_template_exist($notification_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_template_exist(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

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
    # Name       : check_interface_settings_exist
    # Purpose    : Checks if the interface setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_interface_settings_exist($interface_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_interface_settings_exist(:interface_setting_id)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);

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
    # Name       : check_mail_configuration_exist
    # Purpose    : Checks if the mail configuration exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_mail_configuration_exist($mail_configuration_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_mail_configuration_exist(:mail_configuration_id)');
            $sql->bindValue(':mail_configuration_id', $mail_configuration_id);

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
    # Name       : check_zoom_integration_exist
    # Purpose    : Checks if the zoom integration exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_zoom_integration_exist($zoom_integration_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_zoom_integration_exist(:zoom_integration_id)');
            $sql->bindValue(':zoom_integration_id', $zoom_integration_id);

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
    # Name       : check_department_exist
    # Purpose    : Checks if the department exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_department_exist($department_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_department_exist(:department_id)');
            $sql->bindValue(':department_id', $department_id);

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
    # Name       : check_job_position_exist
    # Purpose    : Checks if the job position exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_job_position_exist($job_position_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_job_position_exist(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);

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
    # Name       : check_work_location_exist
    # Purpose    : Checks if the work location exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_work_location_exist($work_location_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_work_location_exist(:work_location_id)');
            $sql->bindValue(':work_location_id', $work_location_id);

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
    # Name       : check_departure_reason_exist
    # Purpose    : Checks if the departure reason exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_departure_reason_exist($departure_reason_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_departure_reason_exist(:departure_reason_id)');
            $sql->bindValue(':departure_reason_id', $departure_reason_id);

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
    # Name       : check_employee_type_exist
    # Purpose    : Checks if the employee type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_employee_type_exist($employee_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_employee_type_exist(:employee_type_id)');
            $sql->bindValue(':employee_type_id', $employee_type_id);

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
    # Name       : check_employee_exist
    # Purpose    : Checks if the employee exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_employee_exist($employee_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_employee_exist(:employee_id)');
            $sql->bindValue(':employee_id', $employee_id);

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
    # Name       : check_working_hours_exist
    # Purpose    : Checks if the working hours exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_working_hours_exist($working_hours_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_working_hours_exist(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);

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
    # Name       : check_working_hours_schedule_exist
    # Purpose    : Checks if the working hours schedule exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_working_hours_schedule_exist($working_hours_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_working_hours_schedule_exist(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);

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
    # Name       : check_attendance_setting_exist
    # Purpose    : Checks if the attendance setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_attendance_setting_exist($attendance_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_attendance_setting_exist(:attendance_setting_id)');
            $sql->bindValue(':attendance_setting_id', $attendance_setting_id);

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
    # Name       : check_attendance_exist
    # Purpose    : Checks if the attendance exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_attendance_exist($attendance_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_attendance_exist(:attendance_id)');
            $sql->bindValue(':attendance_id', $attendance_id);

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
    # Name       : check_attendance_adjustment_exist
    # Purpose    : Checks if the attendance adjustment exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_attendance_adjustment_exist($adjustment_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_attendance_adjustment_exist(:adjustment_id)');
            $sql->bindValue(':adjustment_id', $adjustment_id);

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
    # Name       : check_attendance_creation_exist
    # Purpose    : Checks if the attendance creation exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_attendance_creation_exist($creation_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_attendance_creation_exist(:creation_id)');
            $sql->bindValue(':creation_id', $creation_id);

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
    # Name       : check_approval_type_exist
    # Purpose    : Checks if the approval type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_approval_type_exist($approval_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_approval_type_exist(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);

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
    # Name       : check_approver_exist
    # Purpose    : Checks if the approver exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_approver_exist($approval_type_id, $employee_id, $department){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_approver_exist(:approval_type_id, :employee_id, :department)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':department', $department);

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
    # Name       : check_approval_exception_exist
    # Purpose    : Checks if the approval exception exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_approval_exception_exist($approval_type_id, $employee_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_approval_exception_exist(:approval_type_id, :employee_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);

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
    # Name       : check_public_holiday_exist
    # Purpose    : Checks if the public holiday exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_public_holiday_exist($public_holiday_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_public_holiday_exist(:public_holiday_id)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);

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
    # Name       : check_leave_type_exist
    # Purpose    : Checks if the leave type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_leave_type_exist($leave_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_leave_type_exist(:leave_type_id)');
            $sql->bindValue(':leave_type_id', $leave_type_id);

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
    # Name       : check_leave_allocation_exist
    # Purpose    : Checks if the leave allocation exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_leave_allocation_exist($leave_allocation_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_leave_allocation_exist(:leave_allocation_id)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);

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
    # Name       : check_leave_exist
    # Purpose    : Checks if the leave exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_leave_exist($leave_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_leave_exist(:leave_id)');
            $sql->bindValue(':leave_id', $leave_id);

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
    # Name       : check_leave_supporting_document_exist
    # Purpose    : Checks if the leave supporting document exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_leave_supporting_document_exist($leave_supporting_document_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_leave_supporting_document_exist(:leave_supporting_document_id)');
            $sql->bindValue(':leave_supporting_document_id', $leave_supporting_document_id);

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
    # Name       : update_user_account_password
    # Purpose    : Updates the user account password.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_account_password($username, $password, $password_expiry_date){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($username);
            $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare('CALL update_user_account_password(:username, :password, :password_expiry_date)');
            $sql->bindValue(':password', $password);
            $sql->bindValue(':password_expiry_date', $password_expiry_date);
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated user account password.');
                                        
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_policy
    # Purpose    : Updates policy.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_policy($policy_id, $policy, $policy_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $policy_details = $this->get_policy_details($policy_id);
            
            if(!empty($policy_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $policy_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_policy(:policy_id, :policy, :policy_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':policy_id', $policy_id);
            $sql->bindValue(':policy', $policy);
            $sql->bindValue(':policy_description', $policy_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($policy_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated policy.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated policy.');
                                    
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
    # Name       : update_permission
    # Purpose    : Updates permission.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_permission($permission_id, $policy_id, $permission, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $permission_details = $this->get_permission_details($permission_id);
            
            if(!empty($permission_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $permission_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_permission(:permission_id, :policy_id, :permission, :transaction_log_id, :record_log)');
            $sql->bindValue(':permission_id', $permission_id);
            $sql->bindValue(':policy_id', $policy_id);
            $sql->bindValue(':permission', $permission);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($permission_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated permission.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated permission.');
                                    
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
    public function update_role($role_id, $role, $description, $username){
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

            $sql = $this->db_connection->prepare('CALL update_role(:role_id, :role, :description, :transaction_log_id, :record_log)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':role', $role);
            $sql->bindValue(':description', $description);
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
    # Name       : update_user_account
    # Purpose    : Updates user account.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_account($user_code, $password, $file_as, $password_expiry_date, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $user_account_details = $this->get_user_account_details($user_code);

            if(!empty($user_account_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_user_account(:user_code, :password, :file_as, :password_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':password', $password);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':password_expiry_date', $password_expiry_date);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($user_account_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated user account.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated user account.');
                                    
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
    # Name       : update_user_account_lock_status
    # Purpose    : Updates user account lock status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_account_lock_status($user_code, $transaction_type, $system_date, $username){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($user_code);
            $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

            if($transaction_type == 'unlock'){
                $record_log = 'ULCK->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Unlock';
                $log = 'User ' . $username . ' unlocked user account.';
            }
            else{
                $record_log = 'LCK->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Lock';
                $log = 'User ' . $username . ' locked user account.';
            }

            $sql = $this->db_connection->prepare('CALL update_user_account_lock_status(:user_code, :transaction_type, :system_date, :record_log)');
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':transaction_type', $transaction_type);
            $sql->bindValue(':system_date', $system_date);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_user_account_status
    # Purpose    : Updates user account status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_account_status($user_code, $user_status, $username){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($user_code);
            $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

            if($user_status == 'ACTIVE'){
                $record_log = 'ACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Activate';
                $log = 'User ' . $username . ' activated user account.';
            }
            else{
                $record_log = 'DACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Deactivated';
                $log = 'User ' . $username . ' deactivated user account.';
            }

            $sql = $this->db_connection->prepare('CALL update_user_account_status(:user_code, :user_status, :record_log)');
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':user_status', $user_status);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_employee_related_user
    # Purpose    : Updates employee related user.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_related_user($employee_id, $user_code, $username){
        if ($this->databaseConnection()) {
            $record_log = 'LNK->' . $username . '->' . date('Y-m-d h:i:s');
            $employee_details = $this->get_employee_details($employee_id);
            $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'] ?? null;

            $sql = $this->db_connection->prepare('CALL update_employee_related_user(:employee_id, :user_code, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Link', 'User ' . $username . ' linked user account to employee.');
                                    
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
    public function update_system_parameter($parameter_id, $parameter, $parameter_description, $extension, $parameter_number, $username){
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

            $sql = $this->db_connection->prepare('CALL update_system_parameter(:parameter_id, :parameter, :parameter_description, :extension, :parameter_number, :transaction_log_id, :record_log)');
            $sql->bindValue(':parameter_id', $parameter_id);
            $sql->bindValue(':parameter', $parameter);
            $sql->bindValue(':parameter_description', $parameter_description);
            $sql->bindValue(':extension', $extension);
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
    # Name       : update_system_code
    # Purpose    : Updates system code.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_system_code($system_type, $system_code, $system_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $system_code_details = $this->get_system_code_details($system_type, $system_code);

            if(!empty($system_code_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $system_code_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_system_code(:system_type, :system_code, :system_description, :transaction_log_id, :record_log)');
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
    public function update_upload_setting($setting_id, $upload_setting, $description, $max_file_size, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $upload_setting_details = $this->get_upload_setting_details($setting_id);

            if(!empty($upload_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $upload_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_upload_setting(:setting_id, :upload_setting, :description, :max_file_size, :transaction_log_id, :record_log)');
            $sql->bindValue(':setting_id', $setting_id);
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
    public function update_company($company_id, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username){
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

            $sql = $this->db_connection->prepare('CALL update_company(:company_id, :company_name, :email, :telephone, :mobile, :website, :tax_id, :street_1, :street_2, :country_id, :state, :city, :zip_code, :transaction_log_id, :record_log)');
            $sql->bindValue(':company_id', $company_id);
            $sql->bindValue(':company_name', $company_name);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':website', $website);
            $sql->bindValue(':tax_id', $tax_id);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
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

                $directory = './company/logo/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/logo/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $company_details = $this->get_company_details($company_id);
                    $company_logo = $company_details[0]['COMPANY_LOGO'];
                    $transaction_log_id = $company_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($company_logo)){
                        if (unlink($company_logo)) {
                            if(move_uploaded_file($company_logo_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_company_logo(:company_id, :file_path, :transaction_log_id, :record_log)');
                                $sql->bindValue(':company_id', $company_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':transaction_log_id', $transaction_log_id);
                                $sql->bindValue(':record_log', $record_log);
                            
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
                            $sql = $this->db_connection->prepare('CALL update_company_logo(:company_id, :file_path, :transaction_log_id, :record_log)');
                            $sql->bindValue(':company_id', $company_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':transaction_log_id', $transaction_log_id);
                            $sql->bindValue(':record_log', $record_log);
                        
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
    #
    # Name       : update_country
    # Purpose    : Updates country.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_country($country_id, $country_name, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $country_details = $this->get_country_details($country_id);
            
            if(!empty($country_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $country_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_country(:country_id, :country_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':country_name', $country_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($country_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated country.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated country.');
                                    
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
    # Name       : update_state
    # Purpose    : Updates state.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_state($state_id, $state_name, $country_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $state_details = $this->get_state_details($state_id);
            
            if(!empty($state_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $state_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_state(:state_id, :state_name, :country_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':state_id', $state_id);
            $sql->bindValue(':state_name', $state_name);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($state_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated state.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated state.');
                                    
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
    # Name       : update_notification_setting
    # Purpose    : Updates notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_notification_setting($notification_setting_id, $notification_setting, $notification_setting_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $notification_setting_details = $this->get_notification_setting_details($notification_setting_id);
            
            if(!empty($notification_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $notification_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_notification_setting(:notification_setting_id, :notification_setting, :notification_setting_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':notification_setting', $notification_setting);
            $sql->bindValue(':notification_setting_description', $notification_setting_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($notification_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated notification setting.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated notification setting.');
                                    
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
    # Name       : update_notification_template
    # Purpose    : Updates notification template.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_notification_template($notification_setting_id, $notification_title, $notification_message, $system_link, $email_link, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $notification_setting_details = $this->get_notification_setting_details($notification_setting_id);
            $transaction_log_id = $notification_setting_details[0]['TRANSACTION_LOG_ID'] ?? null;

            $sql = $this->db_connection->prepare('CALL update_notification_template(:notification_setting_id, :notification_title, :notification_message, :system_link, :email_link, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':notification_title', $notification_title);
            $sql->bindValue(':notification_message', $notification_message);
            $sql->bindValue(':system_link', $system_link);
            $sql->bindValue(':email_link', $email_link);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated notification template.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    # Name       : update_interface_settings_images
    # Purpose    : Updates interface setting images
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function update_interface_settings_images($file_tmp_name, $file_actual_ext, $request_type, $interface_setting_id, $username){
        if ($this->databaseConnection()) {
            if(!empty($file_tmp_name)){
                $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
                $interface_settings_details = $this->get_interface_settings_details($interface_setting_id);

                if(!empty($interface_settings_details[0]['TRANSACTION_LOG_ID'])){
                    $transaction_log_id = $interface_settings_details[0]['TRANSACTION_LOG_ID'];
                }
                else{
                    # Get transaction log id
                    $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                    $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                    $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
                }

                switch ($request_type) {
                    case 'login background':
                        $file_new = 'login-bg.' . $file_actual_ext;
                        $image = $interface_settings_details[0]['LOGIN_BACKGROUND'] ?? null;
                        $log = 'User ' . $username . ' updated login background.';
                        break;
                    case 'login logo':
                        $file_new = 'login-logo.' . $file_actual_ext;
                        $image = $interface_settings_details[0]['LOGIN_LOGO'] ?? null;
                        $log = 'User ' . $username . ' updated login logo.';
                        break;
                    case 'menu logo':
                        $file_new = 'menu-logo.' . $file_actual_ext;
                        $image = $interface_settings_details[0]['MENU_LOGO'] ?? null;
                        $log = 'User ' . $username . ' updated menu logo.';
                        break;
                    case 'menu icon':
                        $file_new = 'logo-icon-light.' . $file_actual_ext;
                        $image = $interface_settings_details[0]['MENU_ICON'] ?? null;
                        $log = 'User ' . $username . ' updated menu icon.';
                        break;
                    default:
                        $file_new = 'favicon.' . $file_actual_ext;
                        $image = $interface_settings_details[0]['FAVICON'] ?? null;
                        $log = 'User ' . $username . ' updated favicon.';
                }

                $directory = './assets/images/application_settings/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/assets/images/application_settings/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    if(file_exists($image)){
                        if (unlink($image)) {
                            if(move_uploaded_file($file_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_interface_settings_images(:interface_setting_id, :file_path, :transaction_log_id, :record_log, :request_type)');
                                $sql->bindValue(':interface_setting_id', $interface_setting_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':transaction_log_id', $transaction_log_id);
                                $sql->bindValue(':record_log', $record_log);
                                $sql->bindValue(':request_type', $request_type);
                            
                                if($sql->execute()){
                                    if(!empty($interface_settings_details[0]['TRANSACTION_LOG_ID'])){
                                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', $log);
                                    
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
                                            $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', $log);
                                    
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
                            else{
                                return 'There was an error uploading your image.';
                            }
                        }
                        else {
                            return $profile_image . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($file_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_interface_settings_images(:interface_setting_id, :file_path, :transaction_log_id, :record_log, :request_type)');
                            $sql->bindValue(':interface_setting_id', $interface_setting_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':transaction_log_id', $transaction_log_id);
                            $sql->bindValue(':record_log', $record_log);
                            $sql->bindValue(':request_type', $request_type);
                        
                            if($sql->execute()){
                                if(!empty($interface_settings_details[0]['TRANSACTION_LOG_ID'])){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', $log);
                                
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
                                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', $log);
                                
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
                        else{
                            return 'There was an error uploading your image.';
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
    # Name       : update_mail_configuration
    # Purpose    : Updates mail configuration.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_mail_configuration($mail_configuration_id, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_user, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $mail_configuration_details = $this->get_mail_configuration_details($mail_configuration_id);

            if(!empty($mail_configuration_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $mail_configuration_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_mail_configuration(:mail_configuration_id, :mail_host, :port, :smtp_auth, :smtp_auto_tls, :mail_user, :mail_password, :mail_encryption, :mail_from_name, :mail_from_email, :transaction_log_id, :record_log)');
            $sql->bindValue(':mail_configuration_id', $mail_configuration_id);
            $sql->bindValue(':mail_host', $mail_host);
            $sql->bindValue(':port', $port);
            $sql->bindValue(':smtp_auth', $smtp_auth);
            $sql->bindValue(':smtp_auto_tls', $smtp_auto_tls);
            $sql->bindValue(':mail_user', $mail_user);
            $sql->bindValue(':mail_password', $mail_password);
            $sql->bindValue(':mail_encryption', $mail_encryption);
            $sql->bindValue(':mail_from_name', $mail_from_name);
            $sql->bindValue(':mail_from_email', $mail_from_email);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($mail_configuration_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated mail configuration.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated mail configuration.');
                                    
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
    # Name       : update_zoom_integration
    # Purpose    : Updates zoom integration.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_zoom_integration($zoom_integration_id, $api_key, $api_secret, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $zoom_integration_details = $this->get_zoom_integration_details($mail_configuration_id);

            if(!empty($zoom_integration_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $zoom_integration_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_zoom_integration(:zoom_integration_id, :api_key, :api_secret, :transaction_log_id, :record_log)');
            $sql->bindValue(':zoom_integration_id', $zoom_integration_id);
            $sql->bindValue(':api_key', $api_key);
            $sql->bindValue(':api_secret', $api_secret);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($zoom_integration_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated zoom integration.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated zoom integration.');
                                    
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
    # Name       : update_department
    # Purpose    : Updates department.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_department($department_id, $department, $parent_department, $manager, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $department_details = $this->get_department_details($department_id);
            
            if(!empty($department_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $department_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_department(:department_id, :department, :parent_department, :manager, :transaction_log_id, :record_log)');
            $sql->bindValue(':department_id', $department_id);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':parent_department', $parent_department);
            $sql->bindValue(':manager', $manager);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($department_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated department.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated department.');
                                    
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
    # Name       : update_job_position
    # Purpose    : Updates job position.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position($job_position_id, $job_position, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_details = $this->get_job_position_details($job_position_id);
            
            if(!empty($job_position_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $job_position_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_job_position(:job_position_id, :job_position, :transaction_log_id, :record_log)');
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($job_position_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position.');
                                    
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
    # Name       : update_job_description
    # Purpose    : Updates job description.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_description($job_description_tmp_name, $job_description_actual_ext, $job_position_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            if(!empty($job_description_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $job_description_actual_ext;

                $directory = './company/job_description/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/job_description/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $job_position_details = $this->get_job_position_details($job_position_id);
                    $job_description = $job_position_details[0]['JOB_DESCRIPTION'];
                    $transaction_log_id = $job_position_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($job_description)){
                        if (unlink($job_description)) {
                            if(move_uploaded_file($job_description_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_job_description(:job_position_id, :file_path, :record_log)');
                                $sql->bindValue(':job_position_id', $job_position_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':record_log', $record_log);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job description.');
                                        
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
                            return $job_description . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($job_description_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_job_description(:job_position_id, :file_path, :record_log)');
                            $sql->bindValue(':job_position_id', $job_position_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':record_log', $record_log);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job description.');
                                    
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
    # Name       : update_work_location
    # Purpose    : Updates work location.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_work_location($work_location_id, $work_location, $email, $telephone, $mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $work_location_details = $this->get_work_location_details($work_location_id);
            
            if(!empty($work_location_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $work_location_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_work_location(:work_location_id, :work_location, :email, :telephone, :mobile, :street_1, :street_2, :country_id, :state, :city, :zip_code, :transaction_log_id, :record_log)');
            $sql->bindValue(':work_location_id', $work_location_id);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($work_location_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated work location.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated work location.');
                                    
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
    # Name       : update_departure_reason
    # Purpose    : Updates departure reason.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_departure_reason($departure_reason_id, $departure_reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $departure_reason_details = $this->get_departure_reason_details($departure_reason_id);
            
            if(!empty($departure_reason_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $departure_reason_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_departure_reason(:departure_reason_id, :departure_reason, :transaction_log_id, :record_log)');
            $sql->bindValue(':departure_reason_id', $departure_reason_id);
            $sql->bindValue(':departure_reason', $departure_reason);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($departure_reason_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated departure reason.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated departure reason.');
                                    
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
    # Name       : update_employee_type
    # Purpose    : Updates employee type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_type($employee_type_id, $employee_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $employee_type_details = $this->get_employee_type_details($employee_type_id);
            
            if(!empty($employee_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $employee_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_employee_type(:employee_type_id, :employee_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':employee_type_id', $employee_type_id);
            $sql->bindValue(':employee_type', $employee_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($employee_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee type.');
                                    
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
    # Name       : update_employee
    # Purpose    : Updates employee.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee($employee_id, $badge_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $work_email, $work_telephone, $work_mobile, $sss, $tin, $pagibig, $philhealth, $bank_account_number, $home_work_distance, $personal_email, $personal_telephone, $personal_mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $marital_status, $spouse_name, $spouse_birthday, $emergency_contact, $emergency_phone, $nationality, $identification_number, $passport_number, $gender, $birthday, $certificate_level, $field_of_study, $school, $place_of_birth, $number_of_children, $visa_number, $work_permit_number, $visa_expiry_date, $work_permit_expiry_date, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $employee_details = $this->get_employee_details($employee_id);
            
            if(!empty($employee_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_employee(:employee_id, :badge_id, :file_as, :first_name, :middle_name, :last_name, :suffix, :company, :job_position, :department, :work_location, :working_hours, :manager, :coach, :employee_type, :permanency_date, :onboard_date, :work_email, :work_telephone, :work_mobile, :sss, :tin, :pagibig, :philhealth, :bank_account_number, :home_work_distance, :personal_email, :personal_telephone, :personal_mobile, :street_1, :street_2, :country_id, :state, :city, :zip_code, :marital_status, :spouse_name, :spouse_birthday, :emergency_contact, :emergency_phone, :nationality, :identification_number, :passport_number, :gender, :birthday, :certificate_level, :field_of_study, :school, :place_of_birth, :number_of_children, :visa_number, :work_permit_number, :visa_expiry_date, :work_permit_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':badge_id', $badge_id);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':first_name', $first_name);
            $sql->bindValue(':middle_name', $middle_name);
            $sql->bindValue(':last_name', $last_name);
            $sql->bindValue(':suffix', $suffix);
            $sql->bindValue(':company', $company);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':manager', $manager);
            $sql->bindValue(':coach', $coach);
            $sql->bindValue(':employee_type', $employee_type);
            $sql->bindValue(':permanency_date', $permanency_date);
            $sql->bindValue(':onboard_date', $onboard_date);
            $sql->bindValue(':work_email', $work_email);
            $sql->bindValue(':work_telephone', $work_telephone);
            $sql->bindValue(':work_mobile', $work_mobile);
            $sql->bindValue(':sss', $sss);
            $sql->bindValue(':tin', $tin);
            $sql->bindValue(':pagibig', $pagibig);
            $sql->bindValue(':philhealth', $philhealth);
            $sql->bindValue(':bank_account_number', $bank_account_number);
            $sql->bindValue(':home_work_distance', $home_work_distance);
            $sql->bindValue(':personal_email', $personal_email);
            $sql->bindValue(':personal_telephone', $personal_telephone);
            $sql->bindValue(':personal_mobile', $personal_mobile);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
            $sql->bindValue(':marital_status', $marital_status);
            $sql->bindValue(':spouse_name', $spouse_name);
            $sql->bindValue(':spouse_birthday', $spouse_birthday);
            $sql->bindValue(':emergency_contact', $emergency_contact);
            $sql->bindValue(':emergency_phone', $emergency_phone);
            $sql->bindValue(':nationality', $nationality);
            $sql->bindValue(':identification_number', $identification_number);
            $sql->bindValue(':passport_number', $passport_number);
            $sql->bindValue(':gender', $gender);
            $sql->bindValue(':birthday', $birthday);
            $sql->bindValue(':certificate_level', $certificate_level);
            $sql->bindValue(':field_of_study', $field_of_study);
            $sql->bindValue(':school', $school);
            $sql->bindValue(':place_of_birth', $place_of_birth);
            $sql->bindValue(':number_of_children', $number_of_children);
            $sql->bindValue(':visa_number', $visa_number);
            $sql->bindValue(':work_permit_number', $work_permit_number);
            $sql->bindValue(':visa_expiry_date', $visa_expiry_date);
            $sql->bindValue(':work_permit_expiry_date', $work_permit_expiry_date);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($employee_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee.');
                                    
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
    # Name       : update_employee_image
    # Purpose    : Updates employee image.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_image($employee_image_tmp_name, $employee_image_actual_ext, $employee_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            if(!empty($employee_image_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $employee_image_actual_ext;

                $directory = './company/employee/image/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/employee/image/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $employee_details = $this->get_employee_details($employee_id);
                    $employee_image = $employee_details[0]['EMPLOYEE_IMAGE'];
                    $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($employee_image)){
                        if (unlink($employee_image)) {
                            if(move_uploaded_file($employee_image_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_employee_image(:employee_id, :file_path, :transaction_log_id, :record_log)');
                                $sql->bindValue(':employee_id', $employee_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':transaction_log_id', $transaction_log_id);
                                $sql->bindValue(':record_log', $record_log);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee image.');
                                        
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
                            return $employee_image . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($employee_image_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_employee_image(:employee_id, :file_path, :transaction_log_id, :record_log)');
                            $sql->bindValue(':employee_id', $employee_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':transaction_log_id', $transaction_log_id);
                            $sql->bindValue(':record_log', $record_log);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee image.');
                                    
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
    # Name       : update_employee_type
    # Purpose    : Updates employee type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_status($employee_id, $employee_status, $offboard_date, $departure_reason, $detailed_reason, $username){
        if ($this->databaseConnection()) {
            $employee_details = $this->get_employee_details($employee_id);
            $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'];

            if($employee_status == 'ACTIVE'){
                $record_log = 'UNARC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Unarchive';
                $log = 'User ' . $username . ' unarchived employee.';
            }
            else{
                $record_log = 'ARC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Archive';
                $log = 'User ' . $username . ' archive employee.';
            }
            
            $sql = $this->db_connection->prepare('CALL update_employee_status(:employee_id, :employee_status, :offboard_date, :departure_reason, :detailed_reason, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':employee_status', $employee_status);
            $sql->bindValue(':offboard_date', $offboard_date);
            $sql->bindValue(':departure_reason', $departure_reason);
            $sql->bindValue(':detailed_reason', $detailed_reason);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_work_permit
    # Purpose    : Updates work permit.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_work_permit($work_permit_tmp_name, $work_permit_actual_ext, $employee_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            if(!empty($work_permit_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $work_permit_actual_ext;

                $directory = './company/employee/work_permit/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/employee/work_permit/' . $file_new;
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $employee_details = $this->get_employee_details($employee_id);
                    $work_permit = $employee_details[0]['WORK_PERMIT'];
                    $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($work_permit)){
                        if (unlink($work_permit)) {
                            if(move_uploaded_file($work_permit_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_work_permit(:employee_id, :file_path, :transaction_log_id, :record_log)');
                                $sql->bindValue(':employee_id', $employee_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':transaction_log_id', $transaction_log_id);
                                $sql->bindValue(':record_log', $record_log);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated work permit.');
                                        
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
                            return $work_permit . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($work_permit_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_work_permit(:employee_id, :file_path, :transaction_log_id, :record_log)');
                            $sql->bindValue(':employee_id', $employee_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':transaction_log_id', $transaction_log_id);
                            $sql->bindValue(':record_log', $record_log);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated work permit.');
                                    
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
    # Name       : update_working_hours
    # Purpose    : Updates working hours.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_working_hours($working_hours_id, $working_hours, $schedule_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $working_hours_details = $this->get_working_hours_details($working_hours_id);
            
            if(!empty($working_hours_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $working_hours_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_working_hours(:working_hours_id, :working_hours, :schedule_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':schedule_type', $schedule_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($working_hours_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working hours.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working hours.');
                                    
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
    # Name       : update_working_hours_schedule
    # Purpose    : Updates working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_working_hours_schedule($working_hours_id, $start_date, $end_date, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $working_hours_details = $this->get_working_hours_details($working_hours_id);
            $transaction_log_id = $working_hours_details[0]['TRANSACTION_LOG_ID'];
            
            $sql = $this->db_connection->prepare('CALL update_working_hours_schedule(:working_hours_id, :start_date, :end_date, :monday_morning_work_from, :monday_morning_work_to, :monday_afternoon_work_from, :monday_afternoon_work_to, :tuesday_morning_work_from, :tuesday_morning_work_to, :tuesday_afternoon_work_from, :tuesday_afternoon_work_to, :wednesday_morning_work_from, :wednesday_morning_work_to, :wednesday_afternoon_work_from, :wednesday_afternoon_work_to, :thursday_morning_work_from, :thursday_morning_work_to, :thursday_afternoon_work_from, :thursday_afternoon_work_to, :friday_morning_work_from, :friday_morning_work_to, :friday_afternoon_work_from, :friday_afternoon_work_to, :saturday_morning_work_from, :saturday_morning_work_to, :saturday_afternoon_work_from, :saturday_afternoon_work_to, :sunday_morning_work_from, :sunday_morning_work_to, :sunday_afternoon_work_from, :sunday_afternoon_work_to, :record_log)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':start_date', $start_date);
            $sql->bindValue(':end_date', $end_date);
            $sql->bindValue(':monday_morning_work_from', $monday_morning_work_from);
            $sql->bindValue(':monday_morning_work_to', $monday_morning_work_to);
            $sql->bindValue(':monday_afternoon_work_from', $monday_afternoon_work_from);
            $sql->bindValue(':monday_afternoon_work_to', $monday_afternoon_work_to);
            $sql->bindValue(':tuesday_morning_work_from', $tuesday_morning_work_from);
            $sql->bindValue(':tuesday_morning_work_to', $tuesday_morning_work_to);
            $sql->bindValue(':tuesday_afternoon_work_from', $tuesday_afternoon_work_from);
            $sql->bindValue(':tuesday_afternoon_work_to', $tuesday_afternoon_work_to);
            $sql->bindValue(':wednesday_morning_work_from', $wednesday_morning_work_from);
            $sql->bindValue(':wednesday_morning_work_to', $wednesday_morning_work_to);
            $sql->bindValue(':wednesday_afternoon_work_from', $wednesday_afternoon_work_from);
            $sql->bindValue(':wednesday_afternoon_work_to', $wednesday_afternoon_work_to);
            $sql->bindValue(':thursday_morning_work_from', $thursday_morning_work_from);
            $sql->bindValue(':thursday_morning_work_to', $thursday_morning_work_to);
            $sql->bindValue(':thursday_afternoon_work_from', $thursday_afternoon_work_from);
            $sql->bindValue(':thursday_afternoon_work_to', $thursday_afternoon_work_to);
            $sql->bindValue(':friday_morning_work_from', $friday_morning_work_from);
            $sql->bindValue(':friday_morning_work_to', $friday_morning_work_to);
            $sql->bindValue(':friday_afternoon_work_from', $friday_afternoon_work_from);
            $sql->bindValue(':friday_afternoon_work_to', $friday_afternoon_work_to);
            $sql->bindValue(':saturday_morning_work_from', $saturday_morning_work_from);
            $sql->bindValue(':saturday_morning_work_to', $saturday_morning_work_to);
            $sql->bindValue(':saturday_afternoon_work_from', $saturday_afternoon_work_from);
            $sql->bindValue(':saturday_afternoon_work_to', $saturday_afternoon_work_to);
            $sql->bindValue(':sunday_morning_work_from', $sunday_morning_work_from);
            $sql->bindValue(':sunday_morning_work_to', $sunday_morning_work_to);
            $sql->bindValue(':sunday_afternoon_work_from', $sunday_afternoon_work_from);
            $sql->bindValue(':sunday_afternoon_work_to', $sunday_afternoon_work_to);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working hours schedule.');
                                    
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
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : update_employee_working_hours
    # Purpose    : Updates employee working hours.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_working_hours($employee_id, $working_hours_id, $username){
        if ($this->databaseConnection()) {
            $employee_details = $this->get_employee_details($employee_id);
            $transaction_log_id = $employee_details[0]['TRANSACTION_LOG_ID'] ?? null;

            $sql = $this->db_connection->prepare('CALL update_employee_working_hours(:employee_id, :working_hours_id)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':working_hours_id', $working_hours_id);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated employee working hours.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_notification_status
    # Purpose    : Updates notification status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_notification_status($employee_id, $notification_id, $status){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_notification_status(:employee_id, :notification_id, :status)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':notification_id', $notification_id);
            $sql->bindValue(':status', $status);
        
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
    # Name       : update_attendance_setting
    # Purpose    : Updates attendance setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_setting($attendance_setting_id, $maximum_attendance, $late_grace_period, $time_out_interval, $late_policy, $early_leaving_policy, $overtime_policy, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $attendance_setting_details = $this->get_attendance_setting_details($attendance_setting_id);
            
            if(!empty($attendance_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $attendance_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_attendance_setting(:attendance_setting_id, :maximum_attendance, :late_grace_period, :time_out_interval, :late_policy, :early_leaving_policy, :overtime_policy, :transaction_log_id, :record_log)');
            $sql->bindValue(':attendance_setting_id', $attendance_setting_id);
            $sql->bindValue(':maximum_attendance', $maximum_attendance);
            $sql->bindValue(':late_grace_period', $late_grace_period);
            $sql->bindValue(':time_out_interval', $time_out_interval);
            $sql->bindValue(':late_policy', $late_policy);
            $sql->bindValue(':early_leaving_policy', $early_leaving_policy);
            $sql->bindValue(':overtime_policy', $overtime_policy);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($attendance_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance setting.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance setting.');
                                    
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
    # Name       : update_time_out
    # Purpose    : Update time out.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_time_out($attendance_id, $time_out, $attendance_position, $ip_address, $time_out_behavior, $time_out_note, $early_leaving, $overtime, $total_hours, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $attendance_details = $this->get_attendance_details($attendance_id);
            
            if(!empty($attendance_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $attendance_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_time_out(:attendance_id, :time_out, :attendance_position, :ip_address, :username, :time_out_behavior, :time_out_note, :early_leaving, :overtime, :total_hours, :transaction_log_id, :record_log)');
            $sql->bindValue(':attendance_id', $attendance_id);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':attendance_position', $attendance_position);
            $sql->bindValue(':ip_address', $ip_address);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':time_out_behavior', $time_out_behavior);
            $sql->bindValue(':time_out_note', $time_out_note);
            $sql->bindValue(':early_leaving', $early_leaving);
            $sql->bindValue(':overtime', $overtime);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($attendance_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Time Out', 'User ' . $username . ' time out.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Time Out', 'User ' . $username . ' time out.');
                                    
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
    # Name       : update_attendance
    # Purpose    : Update attendance.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance($attendance_id, $time_in, $time_in_ip_address, $time_in_by, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, $remarks, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $attendance_details = $this->get_attendance_details($attendance_id);
            
            if(!empty($attendance_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $attendance_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_attendance(:attendance_id, :time_in, :time_in_ip_address, :time_in_by, :time_in_behavior, :time_out, :time_out_ip_address, :time_out_by, :time_out_behavior, :late, :early_leaving, :overtime, :total_hours, :remarks, :transaction_log_id, :record_log)');
            $sql->bindValue(':attendance_id', $attendance_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_in_ip_address', $time_in_ip_address);
            $sql->bindValue(':time_in_by', $time_in_by);
            $sql->bindValue(':time_in_behavior', $time_in_behavior);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':time_out_ip_address', $time_out_ip_address);
            $sql->bindValue(':time_out_by', $time_out_by);
            $sql->bindValue(':time_out_behavior', $time_out_behavior);
            $sql->bindValue(':late', $late);
            $sql->bindValue(':early_leaving', $early_leaving);
            $sql->bindValue(':overtime', $overtime);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':remarks', $remarks);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($attendance_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated the attendance.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated the attendance.');
                                    
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
    # Name       : update_attendance_adjustment_attachment
    # Purpose    : Updates attendance adjustment attachment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_adjustment_attachment($attachment_tmp_name, $attachment_actual_ext, $adjustment_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $file_name = $this->generate_file_name(10);
            $file_new = $file_name . '.' . $attachment_actual_ext;

            $directory = './company/employee/attendance_adjustment/';
            $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/employee/attendance_adjustment/' . $file_new;
            $file_path = $directory . $file_new;

            $directory_checker = $this->directory_checker($directory);

            if($directory_checker){
                $job_position_details = $this->get_attendance_adjustment_details($adjustment_id);
                $attachment = $job_position_details[0]['ATTACHMENT'];
                $transaction_log_id = $job_position_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($attachment)){
                        if (unlink($attachment)) {
                            if(move_uploaded_file($attachment_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_attendance_adjustment_attachment(:adjustment_id, :file_path, :record_log)');
                                $sql->bindValue(':adjustment_id', $adjustment_id);
                                $sql->bindValue(':file_path', $file_path);
                                $sql->bindValue(':record_log', $record_log);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance adjustment attachment.');
                                        
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
                            return $attachment . ' cannot be deleted due to an error.';
                        }
                    }
                    else{
                        if(move_uploaded_file($attachment_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_attendance_adjustment_attachment(:adjustment_id, :file_path, :record_log)');
                            $sql->bindValue(':adjustment_id', $adjustment_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':record_log', $record_log);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance adjustment attachment.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_attendance_adjustment
    # Purpose    : Updates attendance adjustment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_adjustment($adjustment_id, $time_in, $time_out, $reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $attendance_adjustment_details = $this->get_attendance_adjustment_details($adjustment_id);
            
            if(!empty($attendance_adjustment_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $attendance_adjustment_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_attendance_adjustment(:adjustment_id, :time_in, :time_out, :reason, :transaction_log_id, :record_log)');
            $sql->bindValue(':adjustment_id', $adjustment_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($attendance_adjustment_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance adjustment.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance adjustment.');
                                    
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
    # Name       : update_attendance_creation_attachment
    # Purpose    : Updates attendance creation attachment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_creation_attachment($attachment_tmp_name, $attachment_actual_ext, $attendance_creation_attachment_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $file_name = $this->generate_file_name(10);
            $file_new = $file_name . '.' . $attachment_actual_ext;

            $directory = './company/employee/attendance_creation/';
            $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/employee/attendance_creation/' . $file_new;
            $file_path = $directory . $file_new;

            $directory_checker = $this->directory_checker($directory);

            if($directory_checker){
                $job_position_details = $this->get_attendance_creation_details($attendance_creation_attachment_id);
                $attachment = $job_position_details[0]['ATTACHMENT'];
                $transaction_log_id = $job_position_details[0]['TRANSACTION_LOG_ID'];
    
                if(file_exists($attachment)){
                    if (unlink($attachment)) {
                        if(move_uploaded_file($attachment_tmp_name, $file_destination)){
                            $sql = $this->db_connection->prepare('CALL update_attendance_creation_attachment(:attendance_creation_attachment_id, :file_path, :record_log)');
                            $sql->bindValue(':attendance_creation_attachment_id', $attendance_creation_attachment_id);
                            $sql->bindValue(':file_path', $file_path);
                            $sql->bindValue(':record_log', $record_log);
                            
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance creation attachment.');
                                        
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
                        return $attachment . ' cannot be deleted due to an error.';
                    }
                }
                else{
                    if(move_uploaded_file($attachment_tmp_name, $file_destination)){
                        $sql = $this->db_connection->prepare('CALL update_attendance_creation_attachment(:attendance_creation_attachment_id, :file_path, :record_log)');
                        $sql->bindValue(':attendance_creation_attachment_id', $attendance_creation_attachment_id);
                        $sql->bindValue(':file_path', $file_path);
                        $sql->bindValue(':record_log', $record_log);
                        
                        if($sql->execute()){
                            $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance creation attachment.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_attendance_creation
    # Purpose    : Updates attendance creation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_creation($creation_id, $time_in, $time_out, $reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $attendance_creation_details = $this->get_attendance_creation_details($creation_id);
            
            if(!empty($attendance_creation_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $attendance_creation_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_attendance_creation(:creation_id, :time_in, :time_out, :reason, :transaction_log_id, :record_log)');
            $sql->bindValue(':creation_id', $creation_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($attendance_creation_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance creation.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated attendance creation.');
                                    
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
    # Name       : update_attendance_creation_status
    # Purpose    : Update attendance creation status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_creation_status($creation_id, $status, $decision_remarks, $sanction, $username){
        if ($this->databaseConnection()) {
            
            $system_date_time = date('Y-m-d H:i:s');

            if($status == 'APV'){
                $record_log = 'APV->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Approve';
                $log = 'User ' . $username . ' approved attendance creation (' . $creation_id . ').';
            }
            else if($status == 'CAN'){
                $record_log = 'CAN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Cancel';
                $log = 'User ' . $username . ' cancelled attendance creation (' . $creation_id . ').';
            }
            else if($status == 'FORREC'){
                $record_log = 'FORREC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'For Recommendation';
                $log = 'User ' . $username . ' tagged the attendance creation for recommendation (' . $creation_id . ').';
            }
            else if($status == 'REC'){
                $record_log = 'REC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Recommend';
                $log = 'User ' . $username . ' recommended attendance creation (' . $creation_id . ').';
            }
            else if($status == 'PEN'){
                $record_log = 'PEN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Pending';
                $log = 'User ' . $username . ' tagged the attendance creation as pending (' . $creation_id . ').';
            }
            else{
                $record_log = 'REJ->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Reject';
                $log = 'User ' . $username . ' rejected attendance creation (' . $creation_id . ').';
            }

            $attendance_creation_details = $this->get_attendance_creation_details($creation_id);
            $transaction_log_id = $attendance_creation_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare("CALL update_attendance_creation_status(:creation_id, :status, :sanction, :decision_remarks, :system_date_time, :username, :transaction_log_id, :record_log)");
            $sql->bindValue(':creation_id', $creation_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':sanction', $sanction);
            $sql->bindValue(':decision_remarks', $decision_remarks);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);

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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_attendance_adjustment_status
    # Purpose    : Update attendance adjustment status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_attendance_adjustment_status($adjustment_id, $status, $decision_remarks, $sanction, $username){
        if ($this->databaseConnection()) {
            
            $system_date_time = date('Y-m-d H:i:s');

            if($status == 'APV'){
                $record_log = 'APV->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Approve';
                $log = 'User ' . $username . ' approved attendance adjustment (' . $adjustment_id . ').';
            }
            else if($status == 'CAN'){
                $record_log = 'CAN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Cancel';
                $log = 'User ' . $username . ' cancelled attendance adjustment (' . $adjustment_id . ').';
            }
            else if($status == 'FORREC'){
                $record_log = 'FORREC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'For Recommendation';
                $log = 'User ' . $username . ' tagged the attendance adjustment for recommendation (' . $adjustment_id . ').';
            }
            else if($status == 'REC'){
                $record_log = 'REC->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Recommend';
                $log = 'User ' . $username . ' recommended attendance adjustment (' . $adjustment_id . ').';
            }
            else if($status == 'PEN'){
                $record_log = 'PEN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Pending';
                $log = 'User ' . $username . ' tagged the attendance adjustment as pending (' . $adjustment_id . ').';
            }
            else{
                $record_log = 'REJ->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Reject';
                $log = 'User ' . $username . ' rejected attendance adjustment (' . $adjustment_id . ').';
            }

            $attendance_adjustment_details = $this->get_attendance_adjustment_details($adjustment_id);
            $transaction_log_id = $attendance_adjustment_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare("CALL update_attendance_adjustment_status(:adjustment_id, :status, :sanction, :decision_remarks, :system_date_time, :username, :transaction_log_id, :record_log)");
            $sql->bindValue(':adjustment_id', $adjustment_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':sanction', $sanction);
            $sql->bindValue(':decision_remarks', $decision_remarks);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);

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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_approval_type
    # Purpose    : Updates approval type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_approval_type($approval_type_id, $approval_type, $approval_type_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $approval_type_details = $this->get_approval_type_details($approval_type_id);
            
            if(!empty($approval_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $approval_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_approval_type(:approval_type_id, :approval_type, :approval_type_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':approval_type', $approval_type);
            $sql->bindValue(':approval_type_description', $approval_type_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($approval_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated approval type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated approval type.');
                                    
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
    # Name       : update_approval_type_status
    # Purpose    : Updates approval type status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_approval_type_status($approval_type_id, $status, $username){
        if ($this->databaseConnection()) {
            $approval_type_details = $this->get_approval_type_details($approval_type_id);
            $transaction_log_id = $approval_type_details[0]['TRANSACTION_LOG_ID'];

            if($status == 'ACTIVE'){
                $record_log = 'ACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Activate';
                $log = 'User ' . $username . ' activated approval type.';
            }
            else{
                $record_log = 'DACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Deactivated';
                $log = 'User ' . $username . ' deactivated approval type.';
            }

            $sql = $this->db_connection->prepare('CALL update_approval_type_status(:approval_type_id, :status, :record_log)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_public_holiday
    # Purpose    : Updates public holiday.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_public_holiday($public_holiday_id, $public_holiday, $holiday_date, $holiday_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $public_holiday_details = $this->get_public_holiday_details($public_holiday_id);
            
            if(!empty($public_holiday_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $public_holiday_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_public_holiday(:public_holiday_id, :public_holiday, :holiday_date, :holiday_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);
            $sql->bindValue(':public_holiday', $public_holiday);
            $sql->bindValue(':holiday_date', $holiday_date);
            $sql->bindValue(':holiday_type', $holiday_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($public_holiday_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated public holiday.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated public holiday.');
                                    
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
    # Name       : update_leave_type
    # Purpose    : Updates leave type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_leave_type($leave_type_id, $leave_type, $paid_type, $allocation_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $leave_type_details = $this->get_leave_type_details($leave_type_id);
            
            if(!empty($leave_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $leave_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_leave_type(:leave_type_id, :leave_type, :paid_type, :allocation_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':leave_type_id', $leave_type_id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':paid_type', $paid_type);
            $sql->bindValue(':allocation_type', $allocation_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($leave_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave type.');
                                    
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
    # Name       : update_leave_allocation
    # Purpose    : Updates leave allocation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_leave_allocation($leave_allocation_id, $leave_type, $employee_id, $validity_start_date, $validity_end_date, $duration, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $leave_allocation_details = $this->get_leave_allocation_details($leave_allocation_id);
            
            if(!empty($leave_allocation_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $leave_allocation_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_leave_allocation(:leave_allocation_id, :leave_type, :employee_id, :validity_start_date, :validity_end_date, :duration, :transaction_log_id, :record_log)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':validity_start_date', $validity_start_date);
            $sql->bindValue(':validity_end_date', $validity_end_date);
            $sql->bindValue(':duration', $duration);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($leave_allocation_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave allocation.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave allocation.');
                                    
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
    # Name       : update_leave
    # Purpose    : Updates leave.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_leave($leave_id, $leave_type, $reason, $leave_date, $start_time, $end_time, $total_hours, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $leave_details = $this->get_leave_details($leave_id);
            
            if(!empty($leave_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $leave_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_leave(:leave_id, :leave_type, :reason, :leave_date, :start_time, :end_time, :total_hours, :transaction_log_id, :record_log)');
            $sql->bindValue(':leave_id', $leave_id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':leave_date', $leave_date);
            $sql->bindValue(':start_time', $start_time);
            $sql->bindValue(':end_time', $end_time);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($leave_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave.');
                                    
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
    # Name       : update_leave_status
    # Purpose    : Update leave status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_leave_status($leave_id, $status, $decision_remarks, $username){
        if ($this->databaseConnection()) {
            
            $system_date_time = date('Y-m-d H:i:s');

            if($status == 'APV'){
                $record_log = 'APV->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Approve';
                $log = 'User ' . $username . ' approved leave (' . $leave_id . ').';
            }
            else if($status == 'CAN'){
                $record_log = 'CAN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Cancel';
                $log = 'User ' . $username . ' cancelled leave (' . $leave_id . ').';
            }
            else if($status == 'FA'){
                $record_log = 'FA->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'For Approval';
                $log = 'User ' . $username . ' tagged the leave for approval (' . $leave_id . ').';
            }
            else if($status == 'PEN'){
                $record_log = 'PEN->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Pending';
                $log = 'User ' . $username . ' tagged the leave as pending (' . $leave_id . ').';
            }
            else{
                $record_log = 'REJ->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Reject';
                $log = 'User ' . $username . ' rejected leave (' . $leave_id . ').';
            }

            $leave_details = $this->get_leave_details($leave_id);
            $transaction_log_id = $leave_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare("CALL update_leave_status(:leave_id, :status, :decision_remarks, :system_date_time, :username, :transaction_log_id, :record_log)");
            $sql->bindValue(':leave_id', $leave_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':decision_remarks', $decision_remarks);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_type, $log);

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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_employee_leave_allocation
    # Purpose    : Updates employee leave allocation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_leave_allocation($leave_id, $employee_id, $transaction_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $leave_details = $this->get_leave_details($leave_id);
			$leave_type_id = $leave_details[0]['LEAVE_TYPE_ID'] ?? null;
			$leave_date = $leave_details[0]['LEAVE_DATE'] ?? null;
			$total_hours = $leave_details[0]['TOTAL_HOURS'] ?? null;
            $transaction_log_id = $leave_details[0]['TRANSACTION_LOG_ID'];

            $employee_leave_allocation_details = $this->get_employee_leave_allocation_details($employee_id, $leave_type_id, $leave_date);
            $leave_allocation_id = $employee_leave_allocation_details[0]['LEAVE_ALLOCATION_ID'] ?? null;

            $sql = $this->db_connection->prepare('CALL update_employee_leave_allocation(:leave_allocation_id, :total_hours, :transaction_type, :record_log)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':transaction_type', $transaction_type);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated leave allocation availment.');
                                    
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
    # Name       : insert_policy
    # Purpose    : Insert policy.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_policy($policy, $policy_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(3, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_policy(:id, :policy, :policy_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':policy', $policy);
            $sql->bindValue(':policy_description', $policy_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 3, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted policy.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_permission
    # Purpose    : Insert permission.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_permission($policy_id, $permission, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(4, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_permission(:id, :policy_id, :permission, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':policy_id', $policy_id);
            $sql->bindValue(':permission', $permission);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 4, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted permission.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_role
    # Purpose    : Insert role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_role($role, $description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(5, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_role(:id, :role, :description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':role', $role);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 5, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted role.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_permission_role
    # Purpose    : Insert role permission.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_permission_role($role_id, $permission_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_permission_role(:role_id, :permission_id, :record_log)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':permission_id', $permission_id);
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
    # Name       : insert_user_account
    # Purpose    : Insert user account.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_user_account($user_code, $password, $file_as, $password_expiry_date, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_user_account(:user_code, :password, :file_as, :password_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':password', $password);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':password_expiry_date', $password_expiry_date);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted user account.');
                                 
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------
     
    # -------------------------------------------------------------
    #
    # Name       : insert_user_account_role
    # Purpose    : Insert user account role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_user_account_role($user_code, $role, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_user_account_role(:user_code, :role, :record_log)');
            $sql->bindValue(':user_code', $user_code);
            $sql->bindValue(':role', $role);
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
    # Name       : insert_system_parameter
    # Purpose    : Insert system parameter.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_system_parameter($parameter, $parameter_description, $extension, $number, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(1, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_system_parameter(:id, :parameter, :parameter_description, :extension, :number, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':parameter', $parameter);
            $sql->bindValue(':parameter_description', $parameter_description);
            $sql->bindValue(':extension', $extension);
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_system_code
    # Purpose    : Insert system code.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_system_code($system_type, $system_code, $system_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_system_code(:system_type, :system_code, :system_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);
            $sql->bindValue(':system_description', $system_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted system code.');
                                    
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_upload_setting
    # Purpose    : Insert upload setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_upload_setting($upload_setting, $description, $max_file_size, $file_types, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $error = '';

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(6, 1);
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
                foreach($file_types as $file_type){
                    $insert_upload_file_type = $this->insert_upload_file_type($id, $file_type, $username);

                    if(!$insert_upload_file_type){
                        $error = $insert_upload_file_type;
                        break;
                    }
                }

                if(empty($error)){
                    # Update system parameter value
                    $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 6, $username);

                    if($update_system_parameter_value){
                        # Update transaction log value
                        $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                        if($update_system_parameter_value){
                            $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted upload setting.');
                                        
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
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $error;
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
    # Name       : insert_upload_file_type
    # Purpose    : Insert upload file type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_upload_file_type($setting_id, $file_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_upload_file_type(:setting_id, :file_type, :record_log)');
            $sql->bindValue(':setting_id', $setting_id);
            $sql->bindValue(':file_type', $file_type);
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
    # Name       : insert_company
    # Purpose    : Insert company.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_company($company_logo_tmp_name, $company_logo_actual_ext, $company_name, $email, $telephone, $mobile, $website, $tax_id, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(7, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_company(:id, :company_name, :email, :telephone, :mobile, :website, :tax_id, :street_1, :street_2, :country_id, :state, :city, :zip_code, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':company_name', $company_name);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':website', $website);
            $sql->bindValue(':tax_id', $tax_id);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 7, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted company.');
                                    
                        if($insert_transaction_log){
                            if(!empty($company_logo_tmp_name) && !empty($company_logo_actual_ext)){
                                $update_company_logo = $this->update_company_logo($company_logo_tmp_name, $company_logo_actual_ext, $id, $username);
        
                                if($update_company_logo){
                                    return true;
                                }
                                else{
                                    return $update_company_logo;
                                }
                            }
                            else{
                                return true;
                            }
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_country
    # Purpose    : Insert country.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_country($country_name, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(8, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_country(:id, :country_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':country_name', $country_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 8, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted country.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_state
    # Purpose    : Insert state.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_state($state_name, $country_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(9, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_state(:id, :state_name, :country_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':state_name', $state_name);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 9, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted state.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_notification_setting
    # Purpose    : Insert notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_setting($notification_setting, $notification_setting_description, $notification_channels, $username){
        if ($this->databaseConnection()) {
            $error = '';
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(10, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_notification_setting(:id, :notification_setting, :notification_setting_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':notification_setting', $notification_setting);
            $sql->bindValue(':notification_setting_description', $notification_setting_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 10, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted notification setting.');
                                    
                        if($insert_transaction_log){
                            foreach($notification_channels as $notification_channel){
                                $insert_notification_channel = $this->insert_notification_channel($id, $notification_channel, $username);
            
                                if(!$insert_notification_channel){
                                    $error = $insert_notification_channel;
                                    break;
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
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_notification_template
    # Purpose    : Insert notification template.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_template($notification_setting_id, $notification_title, $notification_message, $system_link, $email_link, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $notification_setting_details = $this->get_notification_setting_details($notification_setting_id);
            $transaction_log_id = $notification_setting_details[0]['TRANSACTION_LOG_ID'] ?? null;

            $sql = $this->db_connection->prepare('CALL insert_notification_template(:notification_setting_id, :notification_title, :notification_message, :system_link, :email_link, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':notification_title', $notification_title);
            $sql->bindValue(':notification_message', $notification_message);
            $sql->bindValue(':system_link', $system_link);
            $sql->bindValue(':email_link', $email_link);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted notification template.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_notification_role_recipient
    # Purpose    : Insert notification role recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_role_recipient($notification_setting_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_notification_role_recipient(:notification_setting_id, :role_id, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':role_id', $role_id);
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
    # Name       : insert_notification_user_account_recipient
    # Purpose    : Insert notification role recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_user_account_recipient($notification_setting_id, $user_account, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_notification_user_account_recipient(:notification_setting_id, :user_account, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':user_account', $user_account);
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
    # Name       : insert_notification_channel
    # Purpose    : Insert notification channel.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_channel($notification_setting_id, $notification_channel, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_notification_channel(:notification_setting_id, :notification_channel, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':notification_channel', $notification_channel);
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
    # Name       : insert_interface_settings
    # Purpose    : Insert interface settings.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_interface_settings($interface_setting_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_interface_settings(:interface_setting_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id); 
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted interface setting.');
                                
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_mail_configuration
    # Purpose    : Insert mail configuration.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_mail_configuration($mail_configuration_id, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_user, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_mail_configuration(:mail_configuration_id, :mail_host, :port, :smtp_auth, :smtp_auto_tls, :mail_user, :mail_password, :mail_encryption, :mail_from_name, :mail_from_email, :transaction_log_id, :record_log)');
            $sql->bindValue(':mail_configuration_id', $mail_configuration_id);
            $sql->bindValue(':mail_host', $mail_host);
            $sql->bindValue(':port', $port);
            $sql->bindValue(':smtp_auth', $smtp_auth);
            $sql->bindValue(':smtp_auto_tls', $smtp_auto_tls);
            $sql->bindValue(':mail_user', $mail_user);
            $sql->bindValue(':mail_password', $mail_password);
            $sql->bindValue(':mail_encryption', $mail_encryption);
            $sql->bindValue(':mail_from_name', $mail_from_name);
            $sql->bindValue(':mail_from_email', $mail_from_email);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted mail configuration.');
                                    
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_zoom_integration
    # Purpose    : Insert zoom integration.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_zoom_integration($zoom_integration_id, $api_key, $api_secret, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_zoom_integration(:zoom_integration_id, :api_key, :api_secret, :transaction_log_id, :record_log)');
            $sql->bindValue(':zoom_integration_id', $zoom_integration_id);
            $sql->bindValue(':api_key', $api_key);
            $sql->bindValue(':api_secret', $api_secret);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted zoom integration.');
                                    
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_department
    # Purpose    : Insert department.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_department($department, $parent_department, $manager, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(11, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_department(:id, :department, :parent_department, :manager, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':parent_department', $parent_department);
            $sql->bindValue(':manager', $manager);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 11, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted department.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_job_position
    # Purpose    : Insert job position.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_job_position($job_description_tmp_name, $job_description_actual_ext, $job_position, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(12, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position(:id, :job_position, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 12, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position.');
                                    
                        if($insert_transaction_log){
                            if(!empty($job_description_tmp_name) && !empty($job_description_actual_ext)){
                                $update_job_description = $this->update_job_description($job_description_tmp_name, $job_description_actual_ext, $id, $username);
        
                                if($update_job_description){
                                    return true;
                                }
                                else{
                                    return $update_job_description;
                                }
                            }
                            else{
                                return true;
                            }
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_work_location
    # Purpose    : Insert work location.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_work_location($work_location, $email, $telephone, $mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(13, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_work_location(:id, :work_location, :email, :telephone, :mobile, :street_1, :street_2, :country_id, :state, :city, :zip_code, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 13, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted work location.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_departure_reason
    # Purpose    : Insert departure reason.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_departure_reason($departure_reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(14, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_departure_reason(:id, :departure_reason, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':departure_reason', $departure_reason);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 14, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted departure reason.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_employee_type
    # Purpose    : Insert employee type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_employee_type($employee_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(15, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_employee_type(:id, :employee_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':employee_type', $employee_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 15, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted employee type.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_employee
    # Purpose    : Insert employee.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_employee($employee_image_tmp_name, $employee_image_actual_ext, $work_permit_tmp_name, $work_permit_actual_ext, $badge_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $work_email, $work_telephone, $work_mobile, $sss, $tin, $pagibig, $philhealth, $bank_account_number, $home_work_distance, $personal_email, $personal_telephone, $personal_mobile, $street_1, $street_2, $country_id, $state, $city, $zip_code, $marital_status, $spouse_name, $spouse_birthday, $emergency_contact, $emergency_phone, $nationality, $identification_number, $passport_number, $gender, $birthday, $certificate_level, $field_of_study, $school, $place_of_birth, $number_of_children, $visa_number, $work_permit_number, $visa_expiry_date, $work_permit_expiry_date, $username){
        if ($this->databaseConnection()) {
            $error = '';
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(16, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_employee(:id, :badge_id, :file_as, :first_name, :middle_name, :last_name, :suffix, :company, :job_position, :department, :work_location, :working_hours, :manager, :coach, :employee_type, :permanency_date, :onboard_date, :work_email, :work_telephone, :work_mobile, :sss, :tin, :pagibig, :philhealth, :bank_account_number, :home_work_distance, :personal_email, :personal_telephone, :personal_mobile, :street_1, :street_2, :country_id, :state, :city, :zip_code, :marital_status, :spouse_name, :spouse_birthday, :emergency_contact, :emergency_phone, :nationality, :identification_number, :passport_number, :gender, :birthday, :certificate_level, :field_of_study, :school, :place_of_birth, :number_of_children, :visa_number, :work_permit_number, :visa_expiry_date, :work_permit_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':badge_id', $badge_id);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':first_name', $first_name);
            $sql->bindValue(':middle_name', $middle_name);
            $sql->bindValue(':last_name', $last_name);
            $sql->bindValue(':suffix', $suffix);
            $sql->bindValue(':company', $company);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':manager', $manager);
            $sql->bindValue(':coach', $coach);
            $sql->bindValue(':employee_type', $employee_type);
            $sql->bindValue(':permanency_date', $permanency_date);
            $sql->bindValue(':onboard_date', $onboard_date);
            $sql->bindValue(':work_email', $work_email);
            $sql->bindValue(':work_telephone', $work_telephone);
            $sql->bindValue(':work_mobile', $work_mobile);
            $sql->bindValue(':sss', $sss);
            $sql->bindValue(':tin', $tin);
            $sql->bindValue(':pagibig', $pagibig);
            $sql->bindValue(':philhealth', $philhealth);
            $sql->bindValue(':bank_account_number', $bank_account_number);
            $sql->bindValue(':home_work_distance', $home_work_distance);
            $sql->bindValue(':personal_email', $personal_email);
            $sql->bindValue(':personal_telephone', $personal_telephone);
            $sql->bindValue(':personal_mobile', $personal_mobile);
            $sql->bindValue(':street_1', $street_1);
            $sql->bindValue(':street_2', $street_2);
            $sql->bindValue(':country_id', $country_id);
            $sql->bindValue(':state', $state);
            $sql->bindValue(':city', $city);
            $sql->bindValue(':zip_code', $zip_code);
            $sql->bindValue(':marital_status', $marital_status);
            $sql->bindValue(':spouse_name', $spouse_name);
            $sql->bindValue(':spouse_birthday', $spouse_birthday);
            $sql->bindValue(':emergency_contact', $emergency_contact);
            $sql->bindValue(':emergency_phone', $emergency_phone);
            $sql->bindValue(':nationality', $nationality);
            $sql->bindValue(':identification_number', $identification_number);
            $sql->bindValue(':passport_number', $passport_number);
            $sql->bindValue(':gender', $gender);
            $sql->bindValue(':birthday', $birthday);
            $sql->bindValue(':certificate_level', $certificate_level);
            $sql->bindValue(':field_of_study', $field_of_study);
            $sql->bindValue(':school', $school);
            $sql->bindValue(':place_of_birth', $place_of_birth);
            $sql->bindValue(':number_of_children', $number_of_children);
            $sql->bindValue(':visa_number', $visa_number);
            $sql->bindValue(':work_permit_number', $work_permit_number);
            $sql->bindValue(':visa_expiry_date', $visa_expiry_date);
            $sql->bindValue(':work_permit_expiry_date', $work_permit_expiry_date);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 16, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted employee.');
                                    
                        if($insert_transaction_log){
                            if(!empty($employee_image_tmp_name) && !empty($employee_image_actual_ext)){
                                $update_employee_image = $this->update_employee_image($employee_image_tmp_name, $employee_image_actual_ext, $id, $username);
        
                                if(!$update_employee_image){
                                    $error = $update_employee_image;
                                }

                                $update_work_permit = $this->update_work_permit($work_permit_tmp_name, $work_permit_actual_ext, $id, $username);
        
                                if(!$update_work_permit){
                                    $error = $update_work_permit;
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
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_working_hours
    # Purpose    : Insert working hours.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_working_hours($working_hours, $schedule_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(17, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_working_hours(:id, :working_hours, :schedule_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':schedule_type', $schedule_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 17, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted working hours.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_working_hours_schedule
    # Purpose    : Insert working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_working_hours_schedule($working_hours_id, $start_date, $end_date, $monday_morning_work_from, $monday_morning_work_to, $monday_afternoon_work_from, $monday_afternoon_work_to, $tuesday_morning_work_from, $tuesday_morning_work_to, $tuesday_afternoon_work_from, $tuesday_afternoon_work_to, $wednesday_morning_work_from, $wednesday_morning_work_to, $wednesday_afternoon_work_from, $wednesday_afternoon_work_to, $thursday_morning_work_from, $thursday_morning_work_to, $thursday_afternoon_work_from, $thursday_afternoon_work_to, $friday_morning_work_from, $friday_morning_work_to, $friday_afternoon_work_from, $friday_afternoon_work_to, $saturday_morning_work_from, $saturday_morning_work_to, $saturday_afternoon_work_from, $saturday_afternoon_work_to, $sunday_morning_work_from, $sunday_morning_work_to, $sunday_afternoon_work_from, $sunday_afternoon_work_to, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $working_hours_details = $this->get_working_hours_details($working_hours_id);
            $transaction_log_id = $working_hours_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare('CALL insert_working_hours_schedule(:working_hours_id, :start_date, :end_date, :monday_morning_work_from, :monday_morning_work_to, :monday_afternoon_work_from, :monday_afternoon_work_to, :tuesday_morning_work_from, :tuesday_morning_work_to, :tuesday_afternoon_work_from, :tuesday_afternoon_work_to, :wednesday_morning_work_from, :wednesday_morning_work_to, :wednesday_afternoon_work_from, :wednesday_afternoon_work_to, :thursday_morning_work_from, :thursday_morning_work_to, :thursday_afternoon_work_from, :thursday_afternoon_work_to, :friday_morning_work_from, :friday_morning_work_to, :friday_afternoon_work_from, :friday_afternoon_work_to, :saturday_morning_work_from, :saturday_morning_work_to, :saturday_afternoon_work_from, :saturday_afternoon_work_to, :sunday_morning_work_from, :sunday_morning_work_to, :sunday_afternoon_work_from, :sunday_afternoon_work_to, :record_log)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':start_date', $start_date);
            $sql->bindValue(':end_date', $end_date);
            $sql->bindValue(':monday_morning_work_from', $monday_morning_work_from);
            $sql->bindValue(':monday_morning_work_to', $monday_morning_work_to);
            $sql->bindValue(':monday_afternoon_work_from', $monday_afternoon_work_from);
            $sql->bindValue(':monday_afternoon_work_to', $monday_afternoon_work_to);
            $sql->bindValue(':tuesday_morning_work_from', $tuesday_morning_work_from);
            $sql->bindValue(':tuesday_morning_work_to', $tuesday_morning_work_to);
            $sql->bindValue(':tuesday_afternoon_work_from', $tuesday_afternoon_work_from);
            $sql->bindValue(':tuesday_afternoon_work_to', $tuesday_afternoon_work_to);
            $sql->bindValue(':wednesday_morning_work_from', $wednesday_morning_work_from);
            $sql->bindValue(':wednesday_morning_work_to', $wednesday_morning_work_to);
            $sql->bindValue(':wednesday_afternoon_work_from', $wednesday_afternoon_work_from);
            $sql->bindValue(':wednesday_afternoon_work_to', $wednesday_afternoon_work_to);
            $sql->bindValue(':thursday_morning_work_from', $thursday_morning_work_from);
            $sql->bindValue(':thursday_morning_work_to', $thursday_morning_work_to);
            $sql->bindValue(':thursday_afternoon_work_from', $thursday_afternoon_work_from);
            $sql->bindValue(':thursday_afternoon_work_to', $thursday_afternoon_work_to);
            $sql->bindValue(':friday_morning_work_from', $friday_morning_work_from);
            $sql->bindValue(':friday_morning_work_to', $friday_morning_work_to);
            $sql->bindValue(':friday_afternoon_work_from', $friday_afternoon_work_from);
            $sql->bindValue(':friday_afternoon_work_to', $friday_afternoon_work_to);
            $sql->bindValue(':saturday_morning_work_from', $saturday_morning_work_from);
            $sql->bindValue(':saturday_morning_work_to', $saturday_morning_work_to);
            $sql->bindValue(':saturday_afternoon_work_from', $saturday_afternoon_work_from);
            $sql->bindValue(':saturday_afternoon_work_to', $saturday_afternoon_work_to);
            $sql->bindValue(':sunday_morning_work_from', $sunday_morning_work_from);
            $sql->bindValue(':sunday_morning_work_to', $sunday_morning_work_to);
            $sql->bindValue(':sunday_afternoon_work_from', $sunday_afternoon_work_from);
            $sql->bindValue(':sunday_afternoon_work_to', $sunday_afternoon_work_to);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted working hours schedule.');
                                
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_attendance_setting
    # Purpose    : Insert attendance setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance_setting($attendance_setting_id, $maximum_attendance, $late_grace_period, $time_out_interval, $late_policy, $early_leaving_policy, $overtime_policy, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_attendance_setting(:attendance_setting_id, :maximum_attendance, :late_grace_period, :time_out_interval, :late_policy, :early_leaving_policy, :overtime_policy, :transaction_log_id, :record_log)');
            $sql->bindValue(':attendance_setting_id', $attendance_setting_id);
            $sql->bindValue(':maximum_attendance', $maximum_attendance);
            $sql->bindValue(':late_grace_period', $late_grace_period);
            $sql->bindValue(':time_out_interval', $time_out_interval);
            $sql->bindValue(':late_policy', $late_policy);
            $sql->bindValue(':early_leaving_policy', $early_leaving_policy);
            $sql->bindValue(':overtime_policy', $overtime_policy);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update transaction log value
                $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                if($update_system_parameter_value){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted attendance setting.');
                                
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
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_attendance_creation_exception
    # Purpose    : Insert attendance creation exception.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance_creation_exception($employee_id, $exception_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_attendance_creation_exception(:employee_id, :exception_type, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':exception_type', $exception_type);
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
    # Name       : insert_attendance_adjustment_exception
    # Purpose    : Insert attendance creation exception.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance_adjustment_exception($employee_id, $exception_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_attendance_adjustment_exception(:employee_id, :exception_type, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':exception_type', $exception_type);
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
    # Name       : insert_time_in
    # Purpose    : Insert time in.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_time_in($employee_id, $time_in, $attendance_position, $ip_address, $time_in_behavior, $time_in_note, $late, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(18, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_time_in(:id, :employee_id, :time_in, :attendance_position, :ip_address, :username, :time_in_behavior, :time_in_note, :late, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':attendance_position', $attendance_position);
            $sql->bindValue(':ip_address', $ip_address);
            $sql->bindValue(':username', $username);
            $sql->bindValue(':time_in_behavior', $time_in_behavior);
            $sql->bindValue(':time_in_note', $time_in_note);
            $sql->bindValue(':late', $late);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 18, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Time In', 'User ' . $username . ' time in.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_system_notification
    # Purpose    : Insert system notification.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_system_notification($notification_id, $notification_from, $notification_to, $title, $message, $link, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $notification_date = date('Y-m-d H:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(19, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_system_notification(:id, :notification_from, :notification_to, :title, :message, :link, :notification_date, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':notification_from', $notification_from);
            $sql->bindValue(':notification_to', $notification_to);
            $sql->bindValue(':title', $title);
            $sql->bindValue(':message', $message);
            $sql->bindValue(':link', $link);
            $sql->bindValue(':notification_date', $notification_date);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 19, $username);

                if($update_system_parameter_value){
                    return true;
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_attendance
    # Purpose    : Insert attendance.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance($employee_id, $time_in, $time_in_ip_address, $time_in_by, $time_in_behavior, $time_out, $time_out_ip_address, $time_out_by, $time_out_behavior, $late, $early_leaving, $overtime, $total_hours, $remarks, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(18, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_attendance(:id, :employee_id, :time_in, :time_in_ip_address, :time_in_by, :time_in_behavior, :time_out, :time_out_ip_address, :time_out_by, :time_out_behavior, :late, :early_leaving, :overtime, :total_hours, :remarks, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_in_ip_address', $time_in_ip_address);
            $sql->bindValue(':time_in_by', $time_in_by);
            $sql->bindValue(':time_in_behavior', $time_in_behavior);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':time_out_ip_address', $time_out_ip_address);
            $sql->bindValue(':time_out_by', $time_out_by);
            $sql->bindValue(':time_out_behavior', $time_out_behavior);
            $sql->bindValue(':late', $late);
            $sql->bindValue(':early_leaving', $early_leaving);
            $sql->bindValue(':overtime', $overtime);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':remarks', $remarks);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 18, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted the attendance.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_attendance_adjustment
    # Purpose    : Insert attendance adjustment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance_adjustment($attachment_tmp_name, $attachment_actual_ext, $attendance_id, $employee_id, $time_in, $time_out, $reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $system_date_time = date('Y-m-d H:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(20, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_attendance_adjustment(:id, :attendance_id, :employee_id, :time_in, :time_out, :reason, :system_date_time, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':attendance_id', $attendance_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 20, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Request', 'User ' . $username . ' requested an attendance adjustment.');
                                    
                        if($insert_transaction_log){
                            $update_attendance_adjustment_attachment = $this->update_attendance_adjustment_attachment($attachment_tmp_name, $attachment_actual_ext, $id, $username);
        
                            if($update_attendance_adjustment_attachment){
                                return true;
                            }
                            else{
                                return $update_attendance_adjustment_attachment;
                            }
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_attendance_creation
    # Purpose    : Insert attendance creation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_attendance_creation($attachment_tmp_name, $attachment_actual_ext, $employee_id, $time_in, $time_out, $reason, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $system_date_time = date('Y-m-d H:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(21, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_attendance_creation(:id, :employee_id, :time_in, :time_out, :reason, :system_date_time, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);
            $sql->bindValue(':time_out', $time_out);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 21, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Request', 'User ' . $username . ' requested an attendance creation.');
                                    
                        if($insert_transaction_log){
                            $update_attendance_creation_attachment = $this->update_attendance_creation_attachment($attachment_tmp_name, $attachment_actual_ext, $id, $username);
        
                            if($update_attendance_creation_attachment){
                                return true;
                            }
                            else{
                                return $update_attendance_creation_attachment;
                            }
                        }
                        else{
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_approval_type
    # Purpose    : Insert approval type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_approval_type($approval_type, $approval_type_description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(22, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_approval_type(:id, :approval_type, :approval_type_description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':approval_type', $approval_type);
            $sql->bindValue(':approval_type_description', $approval_type_description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 22, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted approval type.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_approver
    # Purpose    : Insert approver.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_approver($approval_type_id, $employee_id, $department, $username){
        if ($this->databaseConnection()) {
            $approval_type_details = $this->get_approval_type_details($approval_type_id);
            $transaction_log_id = $approval_type_details[0]['TRANSACTION_LOG_ID'];

            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_approver(:approval_type_id, :employee_id, :department, :record_log)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted approver.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_approval_exception
    # Purpose    : Insert approval exception.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_approval_exception($approval_type_id, $employee_id, $username){
        if ($this->databaseConnection()) {
            $approval_type_details = $this->get_approval_type_details($approval_type_id);
            $transaction_log_id = $approval_type_details[0]['TRANSACTION_LOG_ID'];

            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_approval_exception(:approval_type_id, :employee_id, :record_log)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted approval exception.');
                                    
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
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_public_holiday
    # Purpose    : Insert public holiday.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_public_holiday($public_holiday, $holiday_date, $holiday_type, $work_locations, $username){
        if ($this->databaseConnection()) {
            $error = '';
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(23, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_public_holiday(:id, :public_holiday, :holiday_date, :holiday_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':public_holiday', $public_holiday);
            $sql->bindValue(':holiday_date', $holiday_date);
            $sql->bindValue(':holiday_type', $holiday_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 23, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted public holiday.');
                                    
                        if($insert_transaction_log){
                            foreach($work_locations as $work_location){
                                $insert_public_holiday_work_location = $this->insert_public_holiday_work_location($id, $work_location, $username);
    
                                if(!$insert_public_holiday_work_location){
                                    $error = $insert_public_holiday_work_location;
                                    break;
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
                            return $insert_transaction_log;
                        }
                    }
                    else{
                        return $update_system_parameter_value;
                    }
                }
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_public_holiday_work_location
    # Purpose    : Insert public holiday work location.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_public_holiday_work_location($public_holiday_id, $work_location, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_public_holiday_work_location(:public_holiday_id, :work_location, :record_log)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);
            $sql->bindValue(':work_location', $work_location);
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
    # Name       : insert_leave_type
    # Purpose    : Insert leave type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_leave_type($leave_type, $paid_type, $allocation_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(24, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_leave_type(:id, :leave_type, :paid_type, :allocation_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':paid_type', $paid_type);
            $sql->bindValue(':allocation_type', $allocation_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 24, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted leave type.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_leave_allocation
    # Purpose    : Insert leave allocation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_leave_allocation($leave_type, $employee_id, $validity_start_date, $validity_end_date, $duration, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(25, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_leave_allocation(:id, :leave_type, :employee_id, :validity_start_date, :validity_end_date, :duration, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':validity_start_date', $validity_start_date);
            $sql->bindValue(':validity_end_date', $validity_end_date);
            $sql->bindValue(':duration', $duration);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 25, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted leave allocation.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_leave
    # Purpose    : Insert leave.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_leave($employee_id, $leave_type, $reason, $leave_date, $start_time, $end_time, $total_hours, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $system_date_time = date('Y-m-d H:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(26, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_leave(:id, :employee_id, :leave_type, :reason, :leave_date, :start_time, :end_time, :total_hours, :system_date_time, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':leave_type', $leave_type);
            $sql->bindValue(':reason', $reason);
            $sql->bindValue(':leave_date', $leave_date);
            $sql->bindValue(':start_time', $start_time);
            $sql->bindValue(':end_time', $end_time);
            $sql->bindValue(':total_hours', $total_hours);
            $sql->bindValue(':system_date_time', $system_date_time);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 26, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted leave.');
                                    
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
                else{
                    return $update_system_parameter_value;
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
    # Name       : insert_leave_supporting_document
    # Purpose    : Insert leave supporting document.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_leave_supporting_document($supporting_document_tmp_name, $supporting_document_actual_ext, $leave_id, $document_name, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');
            $system_date_time = date('Y-m-d H:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(27, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            $file_name = $this->generate_file_name(10);
            $file_new = $file_name . '.' . $supporting_document_actual_ext;

            $directory = './company/employee/leave_supporting_document/';
            $file_destination = $_SERVER['DOCUMENT_ROOT'] . '/digify/company/employee/leave_supporting_document/' . $file_new;
            $file_path = $directory . $file_new;

            $directory_checker = $this->directory_checker($directory);

            if($directory_checker){
                $leave_details = $this->get_leave_details($leave_id);
                $transaction_log_id = $leave_details[0]['TRANSACTION_LOG_ID'];
    
                if(move_uploaded_file($supporting_document_tmp_name, $file_destination)){
                    $sql = $this->db_connection->prepare('CALL insert_leave_supporting_document(:id, :leave_id, :document_name, :file_path, :username, :system_date_time, :record_log)');
                    $sql->bindValue(':id', $id);
                    $sql->bindValue(':leave_id', $leave_id);
                    $sql->bindValue(':document_name', $document_name);
                    $sql->bindValue(':file_path', $file_path);
                    $sql->bindValue(':username', $username);
                    $sql->bindValue(':system_date_time', $system_date_time);
                    $sql->bindValue(':record_log', $record_log);
                    
                    if($sql->execute()){
                        # Update system parameter value
                        $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 27, $username);

                        if($update_system_parameter_value){
                            $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted leave supporting document.');
                                
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
                    else{
                        return $sql->errorInfo()[2];
                    }
                }
                else{
                    return 'There was an error uploading your file.';
                }
            }
            else{
                return $directory_checker;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_policy
    # Purpose    : Delete policy.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_policy($policy_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_policy(:policy_id)');
            $sql->bindValue(':policy_id', $policy_id);
        
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
    # Name       : delete_all_permission
    # Purpose    : Delete all permission linked to policy.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_permission($policy_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_permission(:policy_id)');
            $sql->bindValue(':policy_id', $policy_id);
        
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
    # Name       : delete_permission
    # Purpose    : Delete permission.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_permission($permission_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_permission(:permission_id)');
            $sql->bindValue(':permission_id', $permission_id);
        
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
    # Name       : delete_permission_role
    # Purpose    : Delete assigned permissions to role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_permission_role($role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_permission_role(:role_id)');
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
    # Name       : delete_all_user_account_role
    # Purpose    : Delete all user role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_user_account_role($user_code){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_user_account_role(:user_code)');
            $sql->bindValue(':user_code', $user_code);
        
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
    # Name       : delete_system_code
    # Purpose    : Delete system code.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_system_code($system_type, $system_code, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_system_code(:system_type, :system_code)');
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);
        
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
    # Purpose    : Delete upload setitng.
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
    # Name       : delete_all_upload_file_type
    # Purpose    : Delete upload file type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_upload_file_type($upload_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_upload_file_type(:upload_setting_id)');
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
    # Name       : delete_company
    # Purpose    : Delete company.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_company($company_id, $username){
        if ($this->databaseConnection()) {
            $company_details = $this->get_company_details($company_id);
            $company_logo = $company_details[0]['COMPANY_LOGO'];

            $sql = $this->db_connection->prepare('CALL delete_company(:company_id)');
            $sql->bindValue(':company_id', $company_id);
        
            if($sql->execute()){ 
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
    #
    # Name       : delete_country
    # Purpose    : Delete country.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_country($country_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_country(:country_id)');
            $sql->bindValue(':country_id', $country_id);
        
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
    # Name       : delete_all_state
    # Purpose    : Delete all state linked to country.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_state($country_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_state(:country_id)');
            $sql->bindValue(':country_id', $country_id);
        
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
    # Name       : delete_state
    # Purpose    : Delete state.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_state($state_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_state(:state_id)');
            $sql->bindValue(':state_id', $state_id);
        
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
    # Name       : delete_notification_setting
    # Purpose    : Delete notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_notification_setting($notification_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_notification_setting(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
        
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
    # Name       : delete_all_notification_template
    # Purpose    : Delete all notification template linked to notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_notification_template($notification_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_notification_template(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
        
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
    # Name       : delete_all_notification_user_account_recipient
    # Purpose    : Delete all notification user account recipient linked to notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_notification_user_account_recipient($notification_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_notification_user_account_recipient(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
        
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
    # Name       : delete_all_notification_role_recipient
    # Purpose    : Delete all notification role recipient linked to notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_notification_role_recipient($notification_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_notification_role_recipient(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
        
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
    # Name       : delete_all_notification_channel
    # Purpose    : Delete all notification channel linked to notification setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_notification_channel($notification_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_notification_channel(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
        
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
    # Name       : delete_department
    # Purpose    : Delete department.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_department($department_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_department(:department_id)');
            $sql->bindValue(':department_id', $department_id);
        
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
    # Name       : delete_job_position
    # Purpose    : Delete job position.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_job_position($job_position_id, $username){
        if ($this->databaseConnection()) {
            $job_position_details = $this->get_job_position_details($job_position_id);
            $job_description = $job_position_details[0]['JOB_DESCRIPTION'];

            $sql = $this->db_connection->prepare('CALL delete_job_position(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){ 
                if(!empty($job_description)){
                    if(file_exists($job_description)){
                        if (unlink($job_description)) {
                            return true;
                        }
                        else {
                            return $job_description . ' cannot be deleted due to an error.';
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
    # Name       : delete_work_location
    # Purpose    : Delete work location.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_work_location($work_location_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_work_location(:work_location_id)');
            $sql->bindValue(':work_location_id', $work_location_id);
        
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
    # Name       : delete_departure_reason
    # Purpose    : Delete departure reason.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_departure_reason($departure_reason_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_departure_reason(:departure_reason_id)');
            $sql->bindValue(':departure_reason_id', $departure_reason_id);
        
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
    # Name       : delete_employee_type
    # Purpose    : Delete employee type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_employee_type($employee_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_employee_type(:employee_type_id)');
            $sql->bindValue(':employee_type_id', $employee_type_id);
        
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
    # Name       : delete_employee
    # Purpose    : Delete employee.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_employee($employee_id, $username){
        if ($this->databaseConnection()) {
            $error = '';
            $employee_details = $this->get_employee_details($employee_id);
            $employee_image = $employee_details[0]['EMPLOYEE_IMAGE'];
            $work_permit = $employee_details[0]['WORK_PERMIT'];

            $sql = $this->db_connection->prepare('CALL delete_employee(:employee_id)');
            $sql->bindValue(':employee_id', $employee_id);
        
            if($sql->execute()){ 
                if(!empty($employee_image)){
                    if(file_exists($employee_image)){
                        if (!unlink($employee_image)) {
                            $error = $employee_image . ' cannot be deleted due to an error.';
                        }
                    }
                }

                if(!empty($work_permit)){
                    if(file_exists($work_permit)){
                        if (!unlink($work_permit)) {
                            $error = $work_permit . ' cannot be deleted due to an error.';
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
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_working_hours
    # Purpose    : Delete working hours.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_working_hours($working_hours_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_working_hours(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
        
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
    # Name       : delete_working_hours_schedule
    # Purpose    : Delete working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_working_hours_schedule($working_hours_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_working_hours_schedule(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
        
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
    # Name       : delete_all_employee_working_hours
    # Purpose    : Delete all employee working hours linked to working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_employee_working_hours($working_hours_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_employee_working_hours(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
        
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
    # Name       : delete_all_attendance_creation_exception
    # Purpose    : Delete working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_attendance_creation_exception($username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_attendance_creation_exception()');
        
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
    # Name       : delete_all_attendance_adjustment_exception
    # Purpose    : Delete working hours schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_attendance_adjustment_exception($username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_attendance_adjustment_exception()');
        
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
    # Name       : delete_all_employee_related_user
    # Purpose    : Delete all employee related user linked to user account.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_employee_related_user($user_code, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_employee_related_user(:user_code)');
            $sql->bindValue(':user_code', $user_code);
        
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
    # Name       : delete_attendance
    # Purpose    : Delete attendance.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_attendance($attendance_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_attendance(:attendance_id)');
            $sql->bindValue(':attendance_id', $attendance_id);
        
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
    # Name       : delete_attendance_adjustment
    # Purpose    : Delete attendance adjustment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_attendance_adjustment($adjustment_id, $username){
        if ($this->databaseConnection()) {
            $attendance_adjustment_details = $this->get_attendance_adjustment_details($adjustment_id);
            $attachment = $attendance_adjustment_details[0]['ATTACHMENT'];

            $sql = $this->db_connection->prepare('CALL delete_attendance_adjustment(:adjustment_id)');
            $sql->bindValue(':adjustment_id', $adjustment_id);
        
            if($sql->execute()){ 
                if(!empty($attachment)){
                    if(file_exists($attachment)){
                        if (unlink($attachment)) {
                            return true;
                        }
                        else {
                            return $attachment . ' cannot be deleted due to an error.';
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
    # Name       : delete_attendance_creation
    # Purpose    : Delete attendance creation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_attendance_creation($creation_id, $username){
        if ($this->databaseConnection()) {
            $attendance_creation_details = $this->get_attendance_creation_details($creation_id);
            $attachment = $attendance_creation_details[0]['ATTACHMENT'];

            $sql = $this->db_connection->prepare('CALL delete_attendance_creation(:creation_id)');
            $sql->bindValue(':creation_id', $creation_id);
        
            if($sql->execute()){ 
                if(!empty($attachment)){
                    if(file_exists($attachment)){
                        if (unlink($attachment)) {
                            return true;
                        }
                        else {
                            return $attachment . ' cannot be deleted due to an error.';
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
    # Name       : delete_approval_type
    # Purpose    : Delete approval type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_approval_type($approval_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_approval_type(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
        
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
    # Name       : delete_all_approval_approver
    # Purpose    : Delete all approver linked to approval type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_approval_approver($approval_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_approval_approver(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
        
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
    # Name       : delete_all_approval_exception
    # Purpose    : Delete all exception linked to approval type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_approval_exception($approval_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_approval_exception(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
        
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
    # Name       : delete_approver
    # Purpose    : Delete approver.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_approver($approval_type_id, $employee_id, $department, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_approver(:approval_type_id, :employee_id, :department)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':department', $department);
        
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
    # Name       : delete_approval_exception
    # Purpose    : Delete approval exception.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_approval_exception($approval_type_id, $employee_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_approval_exception(:approval_type_id, :employee_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);
            $sql->bindValue(':employee_id', $employee_id);
        
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
    # Name       : delete_public_holiday
    # Purpose    : Delete public holiday.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_public_holiday($public_holiday_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_public_holiday(:public_holiday_id)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);
        
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
    # Name       : delete_all_public_holiday_work_location
    # Purpose    : Delete public holiday work location.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_public_holiday_work_location($public_holiday_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_public_holiday_work_location(:public_holiday_id)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);
        
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
    # Name       : delete_leave_type
    # Purpose    : Delete leave type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_leave_type($leave_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_leave_type(:leave_type_id)');
            $sql->bindValue(':leave_type_id', $leave_type_id);
        
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
    # Name       : delete_leave_allocation
    # Purpose    : Delete leave allocation.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_leave_allocation($leave_allocation_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_leave_allocation(:leave_allocation_id)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);
        
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
    # Name       : delete_leave
    # Purpose    : Delete leave.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_leave($leave_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_leave(:leave_id)');
            $sql->bindValue(':leave_id', $leave_id);
        
            if($sql->execute()){
                $delete_all_leave_supporting_document = $this->delete_all_leave_supporting_document($leave_id, $username);

                if($delete_all_leave_supporting_document){
                    return true;
                }
                else{
                    return $delete_all_leave_supporting_document;
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
    # Name       : delete_all_leave_supporting_document
    # Purpose    : Delete all supporting document linked to leave.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_leave_supporting_document($leave_id, $username){
        if ($this->databaseConnection()) {
            $error = '';

            $sql = $this->db_connection->prepare('SELECT LEAVE_SUPPORTING_DOCUMENT_ID, SUPPORTING_DOCUMENT FROM leave_supporting_document WHERE LEAVE_ID = :leave_id');
            $sql->bindValue(':leave_id', $leave_id);
        
            if($sql->execute()){
                while($row = $sql->fetch()){
                    $leave_supporting_document_id = $row['LEAVE_SUPPORTING_DOCUMENT_ID'];
                    $supporting_document = $row['SUPPORTING_DOCUMENT'];

                    $delete_leave_supporting_document = $this->delete_leave_supporting_document($leave_supporting_document_id, $username);
                                    
                    if($delete_leave_supporting_document){
                        if(!empty($supporting_document)){
                            if(file_exists($supporting_document)){
                                if (!unlink($supporting_document)) {
                                    $error = $supporting_document . ' cannot be deleted due to an error.';
                                }
                            }
                        }
                    }
                    else{
                        $error = $delete_leave_supporting_document;
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
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_leave_supporting_document
    # Purpose    : Delete leave supporting document.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_leave_supporting_document($leave_supporting_document_id, $username){
        if ($this->databaseConnection()) {
            $leave_supporting_document_details = $this->get_leave_supporting_document_details($leave_supporting_document_id);
            $supporting_document = $leave_supporting_document_details[0]['SUPPORTING_DOCUMENT'] ?? null;

            $sql = $this->db_connection->prepare('CALL delete_leave_supporting_document(:leave_supporting_document_id)');
            $sql->bindValue(':leave_supporting_document_id', $leave_supporting_document_id);
        
            if($sql->execute()){ 
                if(!empty($supporting_document)){
                    if(file_exists($supporting_document)){
                        if (unlink($supporting_document)) {
                            return true;
                        }
                        else {
                            return $supporting_document . ' cannot be deleted due to an error.';
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
    # Name       : get_policy_details
    # Purpose    : Gets the policy details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_policy_details($policy_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_policy_details(:policy_id)');
            $sql->bindValue(':policy_id', $policy_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'POLICY' => $row['POLICY'],
                        'POLICY_DESCRIPTION' => $row['POLICY_DESCRIPTION'],
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
    # Name       : get_permission_details
    # Purpose    : Gets the permission details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_permission_details($permission_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_permission_details(:permission_id)');
            $sql->bindValue(':permission_id', $permission_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'POLICY_ID' => $row['POLICY_ID'],
                        'PERMISSION' => $row['PERMISSION'],
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
    # Name       : get_role_permission_details
    # Purpose    : Gets the role permission details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_role_permission_details($role_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_role_permission_details(:role_id)');
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PERMISSION_ID' => $row['PERMISSION_ID'],
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
    # Name       : get_user_account_role_details
    # Purpose    : Gets the role user details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_account_role_details($role_id, $user_code){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_user_account_role_details(:role_id, :user_code)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':user_code', $user_code);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ROLE_ID' => $row['ROLE_ID'],
                        'USERNAME' => $row['USERNAME'],
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
    # Name       : get_system_code_details
    # Purpose    : Gets the system code details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_system_code_details($system_type, $system_code){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_system_code_details(:system_type, :system_code)');
            $sql->bindValue(':system_type', $system_type);
            $sql->bindValue(':system_code', $system_code);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
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
                        'FILE_TYPE' => $row['FILE_TYPE'],
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
                        'EMAIL' => $row['EMAIL'],
                        'TELEPHONE' => $row['TELEPHONE'],
                        'MOBILE' => $row['MOBILE'],
                        'WEBSITE' => $row['WEBSITE'],
                        'TAX_ID' => $row['TAX_ID'],
                        'STREET_1' => $row['STREET_1'],
                        'STREET_2' => $row['STREET_2'],
                        'COUNTRY_ID' => $row['COUNTRY_ID'],
                        'STATE_ID' => $row['STATE_ID'],
                        'CITY' => $row['CITY'],
                        'ZIP_CODE' => $row['ZIP_CODE'],
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
    # Name       : get_country_details
    # Purpose    : Gets the country details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_country_details($country_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_country_details(:country_id)');
            $sql->bindValue(':country_id', $country_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'COUNTRY_NAME' => $row['COUNTRY_NAME'],
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
    # Name       : get_state_details
    # Purpose    : Gets the state details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_state_details($state_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_state_details(:state_id)');
            $sql->bindValue(':state_id', $state_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'STATE_NAME' => $row['STATE_NAME'],
                        'COUNTRY_ID' => $row['COUNTRY_ID'],
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
    # Name       : get_notification_setting_details
    # Purpose    : Gets the notification setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_notification_setting_details($notification_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_notification_setting_details(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'NOTIFICATION_SETTING' => $row['NOTIFICATION_SETTING'],
                        'NOTIFICATION_SETTING_DESCRIPTION' => $row['NOTIFICATION_SETTING_DESCRIPTION'],
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
    # Name       : get_notification_template_details
    # Purpose    : Gets the notification template details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_notification_template_details($notification_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_notification_template_details(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'NOTIFICATION_TITLE' => $row['NOTIFICATION_TITLE'],
                        'NOTIFICATION_MESSAGE' => $row['NOTIFICATION_MESSAGE'],
                        'SYSTEM_LINK' => $row['SYSTEM_LINK'],
                        'EMAIL_LINK' => $row['EMAIL_LINK'],
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
    # Name       : get_notification_user_account_recipient_details
    # Purpose    : Gets the notification user account recipient details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_notification_user_account_recipient_details($notification_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_notification_user_account_recipient_details(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'USERNAME' => $row['USERNAME'],
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
    # Name       : get_notification_role_recipient_details
    # Purpose    : Gets the notification user account recipient details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_notification_role_recipient_details($notification_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_notification_role_recipient_details(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ROLE_ID' => $row['ROLE_ID'],
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
    # Name       : get_notification_channel_details
    # Purpose    : Gets the notification channel details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_notification_channel_details($notification_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_notification_channel_details(:notification_setting_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'CHANNEL' => $row['CHANNEL'],
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
    # Name       : get_interface_settings_details
    # Purpose    : Gets the interface settings details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_interface_settings_details($interface_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_interface_settings_details(:interface_setting_id)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'LOGIN_BACKGROUND' => $row['LOGIN_BACKGROUND'],
                        'LOGIN_LOGO' => $row['LOGIN_LOGO'],
                        'MENU_LOGO' => $row['MENU_LOGO'],
                        'MENU_ICON' => $row['MENU_ICON'],
                        'FAVICON' => $row['FAVICON'],
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
    # Name       : get_mail_configuration_details
    # Purpose    : Gets the mail cofiguration details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_mail_configuration_details($mail_configuration_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_mail_configuration_details(:mail_configuration_id)');
            $sql->bindValue(':mail_configuration_id', $mail_configuration_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MAIL_HOST' => $row['MAIL_HOST'],
                        'PORT' => $row['PORT'],
                        'SMTP_AUTH' => $row['SMTP_AUTH'],
                        'SMTP_AUTO_TLS' => $row['SMTP_AUTO_TLS'],
                        'USERNAME' => $row['USERNAME'],
                        'PASSWORD' => $row['PASSWORD'],
                        'MAIL_ENCRYPTION' => $row['MAIL_ENCRYPTION'],
                        'MAIL_FROM_NAME' => $row['MAIL_FROM_NAME'],
                        'MAIL_FROM_EMAIL' => $row['MAIL_FROM_EMAIL'],
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
    # Name       : get_zoom_integration_details
    # Purpose    : Gets the zoom integration details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_zoom_integration_details($zoom_integration_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_zoom_integration_details(:zoom_integration_id)');
            $sql->bindValue(':zoom_integration_id', $zoom_integration_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'API_KEY' => $row['API_KEY'],
                        'API_SECRET' => $row['API_SECRET'],
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
    # Name       : get_department_details
    # Purpose    : Gets the department details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_department_details($department_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_department_details(:department_id)');
            $sql->bindValue(':department_id', $department_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'DEPARTMENT' => $row['DEPARTMENT'],
                        'PARENT_DEPARTMENT' => $row['PARENT_DEPARTMENT'],
                        'MANAGER' => $row['MANAGER'],
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
    # Name       : get_job_position_details
    # Purpose    : Gets the job position details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_details($job_position_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_job_position_details(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'JOB_POSITION' => $row['JOB_POSITION'],
                        'JOB_DESCRIPTION' => $row['JOB_DESCRIPTION'],
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
    # Name       : get_work_location_details
    # Purpose    : Gets the work location details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_work_location_details($work_location_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_work_location_details(:work_location_id)');
            $sql->bindValue(':work_location_id', $work_location_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WORK_LOCATION' => $row['WORK_LOCATION'],
                        'EMAIL' => $row['EMAIL'],
                        'TELEPHONE' => $row['TELEPHONE'],
                        'MOBILE' => $row['MOBILE'],
                        'STREET_1' => $row['STREET_1'],
                        'STREET_2' => $row['STREET_2'],
                        'COUNTRY_ID' => $row['COUNTRY_ID'],
                        'STATE_ID' => $row['STATE_ID'],
                        'CITY' => $row['CITY'],
                        'ZIP_CODE' => $row['ZIP_CODE'],
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
    # Name       : get_departure_reason_details
    # Purpose    : Gets the departure reason details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_departure_reason_details($departure_reason_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_departure_reason_details(:departure_reason_id)');
            $sql->bindValue(':departure_reason_id', $departure_reason_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'DEPARTURE_REASON' => $row['DEPARTURE_REASON'],
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
    # Name       : get_employee_type_details
    # Purpose    : Gets the employee type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_type_details($employee_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_employee_type_details(:employee_type_id)');
            $sql->bindValue(':employee_type_id', $employee_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_TYPE' => $row['EMPLOYEE_TYPE'],
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
    # Name       : get_employee_details
    # Purpose    : Gets the employee details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_details($employee_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_employee_details(:employee_id)');
            $sql->bindValue(':employee_id', $employee_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'USERNAME' => $row['USERNAME'],
                        'BADGE_ID' => $row['BADGE_ID'],
                        'EMPLOYEE_IMAGE' => $row['EMPLOYEE_IMAGE'],
                        'FILE_AS' => $row['FILE_AS'],
                        'FIRST_NAME' => $row['FIRST_NAME'],
                        'MIDDLE_NAME' => $row['MIDDLE_NAME'],
                        'LAST_NAME' => $row['LAST_NAME'],
                        'SUFFIX' => $row['SUFFIX'],
                        'COMPANY' => $row['COMPANY'],
                        'JOB_POSITION' => $row['JOB_POSITION'],
                        'DEPARTMENT' => $row['DEPARTMENT'],
                        'WORK_LOCATION' => $row['WORK_LOCATION'],
                        'WORKING_HOURS' => $row['WORKING_HOURS'],
                        'MANAGER' => $row['MANAGER'],
                        'COACH' => $row['COACH'],
                        'EMPLOYEE_TYPE' => $row['EMPLOYEE_TYPE'],
                        'EMPLOYEE_STATUS' => $row['EMPLOYEE_STATUS'],
                        'PERMANENCY_DATE' => $row['PERMANENCY_DATE'],
                        'ONBOARD_DATE' => $row['ONBOARD_DATE'],
                        'OFFBOARD_DATE' => $row['OFFBOARD_DATE'],
                        'DEPARTURE_REASON' => $row['DEPARTURE_REASON'],
                        'DETAILED_REASON' => $row['DETAILED_REASON'],
                        'WORK_EMAIL' => $row['WORK_EMAIL'],
                        'WORK_TELEPHONE' => $row['WORK_TELEPHONE'],
                        'WORK_MOBILE' => $row['WORK_MOBILE'],
                        'SSS' => $row['SSS'],
                        'TIN' => $row['TIN'],
                        'PAGIBIG' => $row['PAGIBIG'],
                        'PHILHEALTH' => $row['PHILHEALTH'],
                        'BANK_ACCOUNT_NUMBER' => $row['BANK_ACCOUNT_NUMBER'],
                        'HOME_WORK_DISTANCE' => $row['HOME_WORK_DISTANCE'],
                        'PERSONAL_EMAIL' => $row['PERSONAL_EMAIL'],
                        'PERSONAL_TELEPHONE' => $row['PERSONAL_TELEPHONE'],
                        'PERSONAL_MOBILE' => $row['PERSONAL_MOBILE'],
                        'STREET_1' => $row['STREET_1'],
                        'STREET_2' => $row['STREET_2'],
                        'COUNTRY_ID' => $row['COUNTRY_ID'],
                        'STATE_ID' => $row['STATE_ID'],
                        'CITY' => $row['CITY'],
                        'ZIP_CODE' => $row['ZIP_CODE'],
                        'MARITAL_STATUS' => $row['MARITAL_STATUS'],
                        'SPOUSE_NAME' => $row['SPOUSE_NAME'],
                        'SPOUSE_BIRTHDAY' => $row['SPOUSE_BIRTHDAY'],
                        'EMERGENCY_CONTACT' => $row['EMERGENCY_CONTACT'],
                        'EMERGENCY_PHONE' => $row['EMERGENCY_PHONE'],
                        'NATIONALITY' => $row['NATIONALITY'],
                        'IDENTIFICATION_NUMBER' => $row['IDENTIFICATION_NUMBER'],
                        'PASSPORT_NUMBER' => $row['PASSPORT_NUMBER'],
                        'GENDER' => $row['GENDER'],
                        'BIRTHDAY' => $row['BIRTHDAY'],
                        'CERTIFICATE_LEVEL' => $row['CERTIFICATE_LEVEL'],
                        'FIELD_OF_STUDY' => $row['FIELD_OF_STUDY'],
                        'SCHOOL' => $row['SCHOOL'],
                        'PLACE_OF_BIRTH' => $row['PLACE_OF_BIRTH'],
                        'NUMBER_OF_CHILDREN' => $row['NUMBER_OF_CHILDREN'],
                        'VISA_NUMBER' => $row['VISA_NUMBER'],
                        'WORK_PERMIT_NUMBER' => $row['WORK_PERMIT_NUMBER'],
                        'VISA_EXPIRY_DATE' => $row['VISA_EXPIRY_DATE'],
                        'WORK_PERMIT_EXPIRY_DATE' => $row['WORK_PERMIT_EXPIRY_DATE'],
                        'WORK_PERMIT' => $row['WORK_PERMIT'],
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
    # Name       : get_working_hours_details
    # Purpose    : Gets the working hours details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_working_hours_details($working_hours_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_working_hours_details(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WORKING_HOURS' => $row['WORKING_HOURS'],
                        'SCHEDULE_TYPE' => $row['SCHEDULE_TYPE'],
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
    # Name       : get_working_hours_schedule_details
    # Purpose    : Gets the working hours schedule details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_working_hours_schedule_details($working_hours_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_working_hours_schedule_details(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'START_DATE' => $row['START_DATE'],
                        'END_DATE' => $row['END_DATE'],
                        'MONDAY_MORNING_WORK_FROM' => $row['MONDAY_MORNING_WORK_FROM'],
                        'MONDAY_MORNING_WORK_TO' => $row['MONDAY_MORNING_WORK_TO'],
                        'MONDAY_AFTERNOON_WORK_FROM' => $row['MONDAY_AFTERNOON_WORK_FROM'],
                        'MONDAY_AFTERNOON_WORK_TO' => $row['MONDAY_AFTERNOON_WORK_TO'],
                        'TUESDAY_MORNING_WORK_FROM' => $row['TUESDAY_MORNING_WORK_FROM'],
                        'TUESDAY_MORNING_WORK_TO' => $row['TUESDAY_MORNING_WORK_TO'],
                        'TUESDAY_AFTERNOON_WORK_FROM' => $row['TUESDAY_AFTERNOON_WORK_FROM'],
                        'TUESDAY_AFTERNOON_WORK_TO' => $row['TUESDAY_AFTERNOON_WORK_TO'],
                        'WEDNESDAY_MORNING_WORK_FROM' => $row['WEDNESDAY_MORNING_WORK_FROM'],
                        'WEDNESDAY_MORNING_WORK_TO' => $row['WEDNESDAY_MORNING_WORK_TO'],
                        'WEDNESDAY_AFTERNOON_WORK_FROM' => $row['WEDNESDAY_AFTERNOON_WORK_FROM'],
                        'WEDNESDAY_AFTERNOON_WORK_TO' => $row['WEDNESDAY_AFTERNOON_WORK_TO'],
                        'THURSDAY_MORNING_WORK_FROM' => $row['THURSDAY_MORNING_WORK_FROM'],
                        'THURSDAY_MORNING_WORK_TO' => $row['THURSDAY_MORNING_WORK_TO'],
                        'THURSDAY_AFTERNOON_WORK_FROM' => $row['THURSDAY_AFTERNOON_WORK_FROM'],
                        'THURSDAY_AFTERNOON_WORK_TO' => $row['THURSDAY_AFTERNOON_WORK_TO'],
                        'FRIDAY_MORNING_WORK_FROM' => $row['FRIDAY_MORNING_WORK_FROM'],
                        'FRIDAY_MORNING_WORK_TO' => $row['FRIDAY_MORNING_WORK_TO'],
                        'FRIDAY_AFTERNOON_WORK_FROM' => $row['FRIDAY_AFTERNOON_WORK_FROM'],
                        'FRIDAY_AFTERNOON_WORK_TO' => $row['FRIDAY_AFTERNOON_WORK_TO'],
                        'SATURDAY_MORNING_WORK_FROM' => $row['SATURDAY_MORNING_WORK_FROM'],
                        'SATURDAY_MORNING_WORK_TO' => $row['SATURDAY_MORNING_WORK_TO'],
                        'SATURDAY_AFTERNOON_WORK_FROM' => $row['SATURDAY_AFTERNOON_WORK_FROM'],
                        'SATURDAY_AFTERNOON_WORK_TO' => $row['SATURDAY_AFTERNOON_WORK_TO'],
                        'SUNDAY_MORNING_WORK_FROM' => $row['SUNDAY_MORNING_WORK_FROM'],
                        'SUNDAY_MORNING_WORK_TO' => $row['SUNDAY_MORNING_WORK_TO'],
                        'SUNDAY_AFTERNOON_WORK_FROM' => $row['SUNDAY_AFTERNOON_WORK_FROM'],
                        'SUNDAY_AFTERNOON_WORK_TO' => $row['SUNDAY_AFTERNOON_WORK_TO'],
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
    # Name       : get_employee_working_hours_details
    # Purpose    : Gets the employee working hours schedule details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_working_hours_details($working_hours_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_employee_working_hours_details(:working_hours_id)');
            $sql->bindValue(':working_hours_id', $working_hours_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_IMAGE' => $row['EMPLOYEE_IMAGE'],
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'FILE_AS' => $row['FILE_AS'],
                        'JOB_POSITION' => $row['JOB_POSITION']
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
    # Name       : get_attendance_setting_details
    # Purpose    : Gets the attendance setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_setting_details($attendance_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_setting_details(:attendance_setting_id)');
            $sql->bindValue(':attendance_setting_id', $attendance_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MAX_ATTENDANCE' => $row['MAX_ATTENDANCE'],
                        'LATE_GRACE_PERIOD' => $row['LATE_GRACE_PERIOD'],
                        'TIME_OUT_INTERVAL' => $row['TIME_OUT_INTERVAL'],
                        'LATE_POLICY' => $row['LATE_POLICY'],
                        'EARLY_LEAVING_POLICY' => $row['EARLY_LEAVING_POLICY'],
                        'OVERTIME_POLICY' => $row['OVERTIME_POLICY'],
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
    # Name       : get_attendance_creation_exception_details
    # Purpose    : Gets the attendance creation exception details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_creation_exception_details($exception_type){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_creation_exception_details(:exception_type)');
            $sql->bindValue(':exception_type', $exception_type);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
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
    # Name       : get_attendance_adjustment_exception_details
    # Purpose    : Gets the attendance adjustment exception details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_adjustment_exception_details($exception_type){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_adjustment_exception_details(:exception_type)');
            $sql->bindValue(':exception_type', $exception_type);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
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
    # Name       : get_recent_employee_attendance_details
    # Purpose    : Gets the recent employee attendance details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_recent_employee_attendance_details($employee_id, $time_in){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_recent_employee_attendance_details(:employee_id, :time_in)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ATTENDANCE_ID' => $row['ATTENDANCE_ID'],
                        'TIME_IN' => $row['TIME_IN'],
                        'TIME_IN_LOCATION' => $row['TIME_IN_LOCATION'],
                        'TIME_IN_IP_ADDRESS' => $row['TIME_IN_IP_ADDRESS'],
                        'TIME_IN_BY' => $row['TIME_IN_BY'],
                        'TIME_IN_BEHAVIOR' => $row['TIME_IN_BEHAVIOR'],
                        'TIME_IN_NOTE' => $row['TIME_IN_NOTE'],
                        'TIME_OUT' => $row['TIME_OUT'],
                        'TIME_OUT_LOCATION' => $row['TIME_OUT_LOCATION'],
                        'TIME_OUT_IP_ADDRESS' => $row['TIME_OUT_IP_ADDRESS'],
                        'TIME_OUT_BY' => $row['TIME_OUT_BY'],
                        'TIME_OUT_BEHAVIOR' => $row['TIME_OUT_BEHAVIOR'],
                        'TIME_OUT_NOTE' => $row['TIME_OUT_NOTE'],
                        'LATE' => $row['LATE'],
                        'EARLY_LEAVING' => $row['EARLY_LEAVING'],
                        'OVERTIME' => $row['OVERTIME'],
                        'TOTAL_WORKING_HOURS' => $row['TOTAL_WORKING_HOURS'],
                        'REMARKS' => $row['REMARKS'],
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
    # Name       : get_attendance_details
    # Purpose    : Gets the attendance details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_details($attendance_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_details(:attendance_id)');
            $sql->bindValue(':attendance_id', $attendance_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'TIME_IN' => $row['TIME_IN'],
                        'TIME_IN_LOCATION' => $row['TIME_IN_LOCATION'],
                        'TIME_IN_IP_ADDRESS' => $row['TIME_IN_IP_ADDRESS'],
                        'TIME_IN_BY' => $row['TIME_IN_BY'],
                        'TIME_IN_BEHAVIOR' => $row['TIME_IN_BEHAVIOR'],
                        'TIME_IN_NOTE' => $row['TIME_IN_NOTE'],
                        'TIME_OUT' => $row['TIME_OUT'],
                        'TIME_OUT_LOCATION' => $row['TIME_OUT_LOCATION'],
                        'TIME_OUT_IP_ADDRESS' => $row['TIME_OUT_IP_ADDRESS'],
                        'TIME_OUT_BY' => $row['TIME_OUT_BY'],
                        'TIME_OUT_BEHAVIOR' => $row['TIME_OUT_BEHAVIOR'],
                        'TIME_OUT_NOTE' => $row['TIME_OUT_NOTE'],
                        'LATE' => $row['LATE'],
                        'EARLY_LEAVING' => $row['EARLY_LEAVING'],
                        'OVERTIME' => $row['OVERTIME'],
                        'TOTAL_WORKING_HOURS' => $row['TOTAL_WORKING_HOURS'],
                        'REMARKS' => $row['REMARKS'],
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
    # Name       : get_attendance_adjustment_details
    # Purpose    : Gets the attendance adjustment details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_adjustment_details($adjustment_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_adjustment_details(:adjustment_id)');
            $sql->bindValue(':adjustment_id', $adjustment_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ATTENDANCE_ID' => $row['ATTENDANCE_ID'],
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'TIME_IN' => $row['TIME_IN'],
                        'TIME_OUT' => $row['TIME_OUT'],
                        'REASON' => $row['REASON'],
                        'ATTACHMENT' => $row['ATTACHMENT'],
                        'STATUS' => $row['STATUS'],
                        'SANCTION' => $row['SANCTION'],
                        'CREATED_DATE' => $row['CREATED_DATE'],
                        'FOR_RECOMMENDATION_DATE' => $row['FOR_RECOMMENDATION_DATE'],
                        'RECOMMENDATION_DATE' => $row['RECOMMENDATION_DATE'],
                        'RECOMMENDATION_BY' => $row['RECOMMENDATION_BY'],
                        'RECOMMENDATION_REMARKS' => $row['RECOMMENDATION_REMARKS'],
                        'DECISION_DATE' => $row['DECISION_DATE'],
                        'DECISION_BY' => $row['DECISION_BY'],
                        'DECISION_REMARKS' => $row['DECISION_REMARKS'],
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
    # Name       : get_attendance_creation_details
    # Purpose    : Gets the attendance creation details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_creation_details($creation_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_attendance_creation_details(:creation_id)');
            $sql->bindValue(':creation_id', $creation_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'TIME_IN' => $row['TIME_IN'],
                        'TIME_OUT' => $row['TIME_OUT'],
                        'REASON' => $row['REASON'],
                        'ATTACHMENT' => $row['ATTACHMENT'],
                        'STATUS' => $row['STATUS'],
                        'SANCTION' => $row['SANCTION'],
                        'CREATED_DATE' => $row['CREATED_DATE'],
                        'FOR_RECOMMENDATION_DATE' => $row['FOR_RECOMMENDATION_DATE'],
                        'RECOMMENDATION_DATE' => $row['RECOMMENDATION_DATE'],
                        'RECOMMENDATION_BY' => $row['RECOMMENDATION_BY'],
                        'RECOMMENDATION_REMARKS' => $row['RECOMMENDATION_REMARKS'],
                        'DECISION_DATE' => $row['DECISION_DATE'],
                        'DECISION_BY' => $row['DECISION_BY'],
                        'DECISION_REMARKS' => $row['DECISION_REMARKS'],
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
    # Name       : get_approval_type_details
    # Purpose    : Gets the approval type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_approval_type_details($approval_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_approval_type_details(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'APPROVAL_TYPE' => $row['APPROVAL_TYPE'],
                        'APPROVAL_TYPE_DESCRIPTION' => $row['APPROVAL_TYPE_DESCRIPTION'],
                        'STATUS' => $row['STATUS'],
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
    # Name       : get_approver_details
    # Purpose    : Gets the approver details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_approver_details($approval_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_approver_details(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'DEPARTMENT' => $row['DEPARTMENT'],
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
    # Name       : get_approval_exception_details
    # Purpose    : Gets the approval exception details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_approval_exception_details($approval_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_approval_exception_details(:approval_type_id)');
            $sql->bindValue(':approval_type_id', $approval_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
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
    # Name       : get_public_holiday_details
    # Purpose    : Gets the public holiday details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_public_holiday_details($public_holiday_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_public_holiday_details(:public_holiday_id)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PUBLIC_HOLIDAY' => $row['PUBLIC_HOLIDAY'],
                        'HOLIDAY_DATE' => $row['HOLIDAY_DATE'],
                        'HOLIDAY_TYPE' => $row['HOLIDAY_TYPE'],
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
    # Name       : get_public_holiday_work_location_details
    # Purpose    : Gets the public holiday work location details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_public_holiday_work_location_details($public_holiday_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_public_holiday_work_location_details(:public_holiday_id)');
            $sql->bindValue(':public_holiday_id', $public_holiday_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WORK_LOCATION_ID' => $row['WORK_LOCATION_ID'],
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
    # Name       : get_leave_type_details
    # Purpose    : Gets the leave type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_type_details($leave_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_leave_type_details(:leave_type_id)');
            $sql->bindValue(':leave_type_id', $leave_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'LEAVE_TYPE' => $row['LEAVE_TYPE'],
                        'PAID_TYPE' => $row['PAID_TYPE'],
                        'ALLOCATION_TYPE' => $row['ALLOCATION_TYPE'],
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
    # Name       : get_leave_allocation_details
    # Purpose    : Gets the leave allocation details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_allocation_details($leave_allocation_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_leave_allocation_details(:leave_allocation_id)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'LEAVE_TYPE_ID' => $row['LEAVE_TYPE_ID'],
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'VALIDITY_START_DATE' => $row['VALIDITY_START_DATE'],
                        'VALIDITY_END_DATE' => $row['VALIDITY_END_DATE'],
                        'DURATION' => $row['DURATION'],
                        'AVAILED' => $row['AVAILED'],
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
    # Name       : get_employee_leave_allocation_details
    # Purpose    : Gets the employee leave allocation details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_leave_allocation_details($employee_id, $leave_type_id, $leave_date){
        if ($this->databaseConnection()) {
            $response = array();

            $leave_date = $this->check_date('empty', $leave_date, '', 'Y-m-d', '', '', '');

            $sql = $this->db_connection->prepare('CALL get_employee_leave_allocation_details(:employee_id, :leave_type_id, :leave_date)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':leave_type_id', $leave_type_id);
            $sql->bindValue(':leave_date', $leave_date);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'LEAVE_ALLOCATION_ID' => $row['LEAVE_ALLOCATION_ID'],
                        'VALIDITY_START_DATE' => $row['VALIDITY_START_DATE'],
                        'VALIDITY_END_DATE' => $row['VALIDITY_END_DATE'],
                        'DURATION' => $row['DURATION'],
                        'AVAILED' => $row['AVAILED'],
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
    # Name       : get_leave_details
    # Purpose    : Gets the leave details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_details($leave_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_leave_details(:leave_id)');
            $sql->bindValue(':leave_id', $leave_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
                        'LEAVE_TYPE_ID' => $row['LEAVE_TYPE_ID'],
                        'REASON' => $row['REASON'],
                        'LEAVE_DATE' => $row['LEAVE_DATE'],
                        'START_TIME' => $row['START_TIME'],
                        'END_TIME' => $row['END_TIME'],
                        'TOTAL_HOURS' => $row['TOTAL_HOURS'],
                        'STATUS' => $row['STATUS'],
                        'CREATED_DATE' => $row['CREATED_DATE'],
                        'FOR_APPROVAL_DATE' => $row['FOR_APPROVAL_DATE'],
                        'DECISION_DATE' => $row['DECISION_DATE'],
                        'DECISION_BY' => $row['DECISION_BY'],
                        'DECISION_REMARKS' => $row['DECISION_REMARKS'],
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
    # Name       : get_leave_supporting_document_details
    # Purpose    : Gets the leave supporting document details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_supporting_document_details($leave_supporting_document_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_leave_supporting_document_details(:leave_supporting_document_id)');
            $sql->bindValue(':leave_supporting_document_id', $leave_supporting_document_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'LEAVE_ID' => $row['LEAVE_ID'],
                        'DOCUMENT_NAME' => $row['DOCUMENT_NAME'],
                        'SUPPORTING_DOCUMENT' => $row['SUPPORTING_DOCUMENT'],
                        'UPLOADED_BY' => $row['UPLOADED_BY'],
                        'UPLOAD_DATE' => $row['UPLOAD_DATE'],
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
    # Name       : get_next_date
    # Purpose    : Returns the calculated date 
    #              based on the frequency
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function get_next_date($previous_date, $frequency){
        if($frequency == 'MONTHLY'){
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+1 month', '', '');
        }
        else if($frequency == 'DAILY'){
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+1 day', '', '');
        }
        else if($frequency == 'WEEKLY'){
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+1 week', '', '');
        }
        else if($frequency == 'BIWEEKLY'){
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+2 weeks', '', '');
        }
        else if($frequency == 'QUARTERLY'){
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+3 months', '', '');
        }
        else{
            $date = $this->check_date('empty', $previous_date, '', 'Y-m-d', '+1 year', '', '');
        }
    
        return $date;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_permission_count
    # Purpose    : Gets the roles' sub permission count.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_permission_count($role_id, $permission_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL get_permission_count(:role_id, :permission_id)');
            $sql->bindValue(':role_id', $role_id);
            $sql->bindValue(':permission_id', $permission_id);

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
    # Name       : get_user_account_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_account_status($stat){
        $response = array();

        switch ($stat) {
            case 'ACTIVE':
                $status = 'Active';
                $button_class = 'bg-success';
                break;
            default:
                $status = 'Inactive';
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
    #
    # Name       : get_user_account_lock_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_user_account_lock_status($failed_login){
        $response = array();

        if ($failed_login >= 5) {
            $status = 'Locked';
            $button_class = 'bg-danger';
        }
        else{
            $status = 'Unlocked';
            $button_class = 'bg-success';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_date_difference
    # Purpose    : Returns the year, month and days difference.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_date_difference($date_1, $date_2){
        $response = array();

        $diff = abs(strtotime($date_2) - strtotime($date_1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24)/ (60 * 60 * 24));

        if($years > 1 || $years == 0){
            $years = $years . ' Years';
        }
        else{
            $years = $years . ' Year';
        }

        if($months > 1 || $months == 0){
            $months = $months . ' Months';
        }
        else{
            $months = $months . ' Month';
        }

        if($days > 1 || $days == 0){
            $days = $days . ' Days';
        }
        else{
            $days = $days . ' Day';
        }

        $response[] = array(
            'YEARS' => $years,
            'MONTHS' => $months,
            'DAYS' => $days
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_file_as_format
    # Purpose    : Returns the file as name format
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_file_as_format($first_name, $middle_name, $last_name, $suffix){
        $suffix = $this->get_system_code_details('SUFFIX', $suffix)[0]['SYSTEM_DESCRIPTION'] ?? null;

        if(!empty($middle_name) && !empty($suffix)){
            return $last_name . ', ' . $first_name . ' ' . $middle_name . ', ' . $suffix;
        }
        else if(!empty($middle_name) && empty($suffix)){
            return $last_name . ', ' . $first_name . ' ' . $middle_name;
        }
        else if(empty($middle_name) && !empty($suffix)){
            return $last_name . ', ' . $first_name . ', ' . $suffix;
        }
        else{
            return $last_name . ', ' . $first_name;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_total_by_date
    # Purpose    : Gets the total attendance by date.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_attendance_total_by_date($employee_id, $time_in){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL get_attendance_total_by_date(:employee_id, :time_in)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':time_in', $time_in);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['TOTAL'] ?? 0;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_notification_count
    # Purpose    : Gets the number of notifications based on employee and status.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_notification_count($employee_id, $status){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL get_notification_count(:employee_id, :status)');
            $sql->bindParam(':employee_id', $employee_id);
            $sql->bindParam(':status', $status);

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
    # Name       : get_ip_address
    # Purpose    : Returns the ip address of the client
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_ip_address(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }  
        
        return $ip;  
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_time_in_behavior
    # Purpose    : Returns the time in behavior.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_time_in_behavior($employee_id, $time_in){
        $time_in_day = date('N', strtotime($time_in));
        $time_in_time = $this->check_date('empty', $time_in, '', 'H:i:00', '', '', '');
        $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', '');

        $attendance_setting_details = $this->get_attendance_setting_details(1);
        $late_grace_period = $attendance_setting_details[0]['LATE_GRACE_PERIOD'] ?? 0;

        $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
        $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
        $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
        $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;

        if(!empty($morning_work_from)){
            $working_hours_start = $morning_work_from;
        }
        else{
            $working_hours_start = $afternoon_work_from;
        }

        $working_hours_start_late_grace_period = $this->check_date('empty', $working_hours_start, '', 'H:i:00', '+'. $late_grace_period .' minutes', '', '');

        if(strtotime($time_in_time) < strtotime($working_hours_start)){
            return 'EARLY';
        }
        else if(strtotime($time_in_time) > strtotime($working_hours_start_late_grace_period)){
            return 'LATE';
        }
        else{
            return 'REG';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_time_out_behavior
    # Purpose    : Returns the time out behavior.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_time_out_behavior($employee_id, $time_in, $time_out){
        $time_in_day = date('N', strtotime($time_in));  
        $time_out_time = $this->check_date('empty', $time_out, '', 'H:i:00', '', '', '');     
        $time_out_date = $this->check_date('empty', $time_out, '', 'Y-m-d', '', '', ''); 
        $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', ''); 

        $attendance_setting_details = $this->get_attendance_setting_details(1);
        $overtime_policy = $attendance_setting_details[0]['OVERTIME_POLICY'] ?? 0;

        $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
        $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
        $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;
        $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;

        if(!empty($afternoon_work_to)){
            $working_hours_end = $afternoon_work_to;
        }
        else{
            $working_hours_end = $morning_work_to;
        }

        $working_hours_end_overtime_policy = $this->check_date('empty', $working_hours_end, '', 'H:i:00', '+'. $overtime_policy .' minutes', '', '');

        if(strtotime($time_out_date . ' ' . $time_out_time) < strtotime($time_in_date . ' ' . $working_hours_end)){
            return 'EL';
        }
        else if(strtotime($time_out_date . ' ' . $time_out_time) > strtotime($time_in_date . ' ' . $working_hours_end_overtime_policy)){
            return 'OT';
        }
        else{
            return 'REG';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_working_hours_schedule
    # Purpose    : Gets the working hours schedule.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_working_hours_schedule($employee_id, $date, $day){
        if ($this->databaseConnection()) {
            $response = array();

            $employee_details = $this->get_employee_details($employee_id);
            $working_hours = $employee_details[0]['WORKING_HOURS'];

            $working_hours_details = $this->get_working_hours_details($working_hours);
            $schedule_type = $working_hours_details[0]['SCHEDULE_TYPE'];

            $work_shift_schedule_details = $this->get_working_hours_schedule_details($working_hours);
            $start_date = $work_shift_schedule_details[0]['START_DATE'];
            $end_date = $work_shift_schedule_details[0]['END_DATE'];

            if($schedule_type == 'REGULAR' || ($schedule_type == 'SCHEDULED' && (strtotime($date) >= strtotime($start_date) && strtotime($date) <= strtotime($end_date)))){
                switch ($day) {
                    case 1:
                        $morning_work_from = $work_shift_schedule_details[0]['MONDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['MONDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['MONDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['MONDAY_AFTERNOON_WORK_TO'];
                        break;
                    case 2:
                        $morning_work_from = $work_shift_schedule_details[0]['TUESDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['TUESDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['TUESDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['TUESDAY_AFTERNOON_WORK_TO'];
                        break;
                    case 3:
                        $morning_work_from = $work_shift_schedule_details[0]['WEDNESDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['WEDNESDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['WEDNESDAY_AFTERNOON_WORK_TO'];
                        break;
                    case 4:
                        $morning_work_from = $work_shift_schedule_details[0]['THURSDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['THURSDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['THURSDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['THURSDAY_AFTERNOON_WORK_TO'];
                        break;
                    case 5:
                        $morning_work_from = $work_shift_schedule_details[0]['FRIDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['FRIDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['FRIDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['FRIDAY_AFTERNOON_WORK_TO'];
                        break;
                    case 6:
                        $morning_work_from = $work_shift_schedule_details[0]['SATURDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['SATURDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['SATURDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['SATURDAY_AFTERNOON_WORK_TO'];
                        break;
                    default:
                        $morning_work_from = $work_shift_schedule_details[0]['SUNDAY_MORNING_WORK_FROM'];
                        $morning_work_to = $work_shift_schedule_details[0]['SUNDAY_MORNING_WORK_TO'];
                        $afternoon_work_from = $work_shift_schedule_details[0]['SUNDAY_AFTERNOON_WORK_FROM'];
                        $afternoon_work_to = $work_shift_schedule_details[0]['SUNDAY_AFTERNOON_WORK_TO'];
                }

                $response[] = array(
                    'WORKING_HOURS_ID' => $working_hours,
                    'MORNING_WORK_FROM' => $morning_work_from,
                    'MORNING_WORK_TO' => $morning_work_to,
                    'AFTERNOON_WORK_FROM' => $afternoon_work_from,
                    'AFTERNOON_WORK_TO' => $afternoon_work_to
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_late_total
    # Purpose    : Returns the total late minutes
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_attendance_late_total($employee_id, $time_in, $include_late_policy = true){
        if ($this->databaseConnection()) {
            $time_in_day = date('N', strtotime($time_in));
            $time_in_time = $this->check_date('empty', $time_in, '', 'H:i:00', '', '', '');
            $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', '');

            $attendance_setting_details = $this->get_attendance_setting_details(1);
            $late_grace_period = $attendance_setting_details[0]['LATE_GRACE_PERIOD'] ?? 0;
            $late_policy = $attendance_setting_details[0]['LATE_POLICY'] ?? 0;

            $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
            $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
            $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
            $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;
            $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;
            $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;

            if(!empty($morning_work_from)){
                $working_hours_start = $morning_work_from;
            }
            else{
                $working_hours_start = $afternoon_work_from;
            }

            if(strtotime($time_in_time) >= strtotime($working_hours_start)){
                if(!empty($morning_work_from) && !empty($morning_work_to) && !empty($afternoon_work_from) && !empty($afternoon_work_to)){
                    if(strtotime($time_in_time) >= strtotime($afternoon_work_from)){
                        $late = floor(((strtotime($time_in_time) - strtotime($afternoon_work_from)) / 3600) * 60);
                    }
                    else{
                        $late = floor(((strtotime($time_in_time) - strtotime($morning_work_from)) / 3600) * 60);
                    }
                }
                else if(!empty($morning_work_from) && !empty($morning_work_to) && empty($afternoon_work_from) && empty($afternoon_work_to)){
                    if(strtotime($time_in_time) >= strtotime($morning_work_to)){
                        $late = floor(((strtotime($morning_work_to) - strtotime($morning_work_from)) / 3600) * 60);
                    }
                    else{
                        $late = floor(((strtotime($time_in_time) - strtotime($morning_work_from)) / 3600) * 60);
                    }
                }
                else if(empty($morning_work_from) && empty($morning_work_to) && !empty($afternoon_work_from) && !empty($afternoon_work_to)){
                    if(strtotime($time_in_time) >= strtotime($afternoon_work_to)){
                        $late = floor(((strtotime($afternoon_work_to) - strtotime($afternoon_work_from)) / 3600) * 60);
                    }
                    else{
                        $late = floor(((strtotime($time_in_time) - strtotime($afternoon_work_from)) / 3600) * 60);
                    }
                }
                else{
                    $late = 0;
                }

                if($include_late_policy){
                    $late = $late - $late_grace_period;

                    if($late_policy > 0){
                        if($late > $late_policy){
                            $late = (floor(((strtotime($morning_work_to) - strtotime($morning_work_from)) / 3600) * 60) + floor(((strtotime($afternoon_work_to) - strtotime($afternoon_work_from)) / 3600) * 60)) / 2;
                        }
                    }
                }
            }
            else{
                $late = 0;
            }

            if($late <= 0){
                $late = 0;
            }

            return $late;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_early_leaving_total
    # Purpose    : Returns the total early leaving minutes
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_attendance_early_leaving_total($employee_id, $time_in, $time_out, $include_early_leaving_policy = true){
        if ($this->databaseConnection()) {
            $time_in_day = date('N', strtotime($time_in));
            $time_out_time = $this->check_date('empty', $time_out, '', 'H:i:00', '', '', '');
            $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', '');

            $attendance_setting_details = $this->get_attendance_setting_details(1);
            $early_leaving_policy = $attendance_setting_details[0]['EARLY_LEAVING_POLICY'] ?? 0;

            $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
            $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
            $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
            $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;
            $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;
            $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;

            if(!empty($afternoon_work_to)){
                $working_hours_end = $afternoon_work_to;
            }
            else{
                $working_hours_end = $morning_work_to;
            }

            if((!empty($morning_work_from) && !empty($morning_work_to) && !empty($afternoon_work_from) && !empty($afternoon_work_to)) || (empty($morning_work_from) && empty($morning_work_to) && !empty($afternoon_work_from) && !empty($afternoon_work_to))){
                $early_leaving = floor(((strtotime($time_in_date . ' ' . $afternoon_work_to) - strtotime($time_out)) / 3600) * 60);
            }
            else if(!empty($morning_work_from) && !empty($morning_work_to) && empty($afternoon_work_from) && empty($afternoon_work_to)){
                $early_leaving = floor(((strtotime($time_in_date . ' ' . $morning_work_to) - strtotime($time_out)) / 3600) * 60);
            }
            else{
                $early_leaving = 0;
            }

            if($include_early_leaving_policy){
                if($early_leaving_policy > 0){
                    if($early_leaving > $early_leaving_policy){
                        $early_leaving = (floor(((strtotime($morning_work_to) - strtotime($morning_work_from)) / 3600) * 60) + floor(((strtotime($afternoon_work_to) - strtotime($afternoon_work_from)) / 3600) * 60)) / 2;
                    }
                }
            }

            if($early_leaving <= 0){
                $early_leaving = 0;
            }

            return $early_leaving;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_overtime_total
    # Purpose    : Returns the total overtime minutes
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_attendance_overtime_total($employee_id, $time_in, $time_out){
        if ($this->databaseConnection()) {
            $time_in_day = date('N', strtotime($time_in));
            $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', '');

            $attendance_setting_details = $this->get_attendance_setting_details(1);
            $overtime_policy = $attendance_setting_details[0]['OVERTIME_POLICY'];

            $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
            $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
            $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
            $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;
            $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;
            $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;

            if(!empty($afternoon_work_to)){
                $working_hours_end = $afternoon_work_to;
            }
            else{
                $working_hours_end = $morning_work_to;
            }

            if($overtime_policy > 0){
                $overtime_allowance = $this->check_date('empty', $time_in_date . ' ' . $working_hours_end, '', 'Y-m-d H:i:00', '+'. $overtime_policy .' minutes', '', '');

                $overtime = floor(((strtotime($time_out) - strtotime($overtime_allowance)) / 3600));
            }
            else{
                $overtime = floor(((strtotime($time_out) - strtotime($time_in_date . ' ' . $working_hours_end)) / 3600));
            }

            if($overtime <= 0){
                $overtime = 0;
            }

            return floor($overtime);
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_total_hours
    # Purpose    : Returns the total hours worked
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_attendance_total_hours($employee_id, $time_in, $time_out){
        if ($this->databaseConnection()) {
            $time_in_day = date('N', strtotime($time_in));
            $time_in_date = $this->check_date('empty', $time_in, '', 'Y-m-d', '', '', '');

            $late = $this->get_attendance_late_total($employee_id, $time_in) / 60;
            $early_leaving = $this->get_attendance_early_leaving_total($employee_id, $time_in, $time_out) / 60;

            $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $time_in_date, $time_in_day);
            $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;

            if(!empty($working_hours_id)){
                $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
                $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;
                $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;
                $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;

                $total_hours = (floor(((strtotime($morning_work_to) - strtotime($morning_work_from)) / 3600)) + floor(((strtotime($afternoon_work_to) - strtotime($afternoon_work_from)) / 3600))) - ($late + $early_leaving);
            }
            else{
                $total_hours = floor(((strtotime($time_out) - strtotime($time_in)) / 3600));
            }

            if($total_hours <= 0){
                $total_hours = 0;
            }

            return $total_hours;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_leave_total_hours
    # Purpose    : Returns the total hours of leave
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function get_leave_total_hours($employee_id, $leave_date, $leave_start_time, $leave_end_time){
        if ($this->databaseConnection()) {
            $leave_day = date('N', strtotime($leave_date));
            $leave_start_time = $this->check_date('empty', $leave_date . ' ' . $leave_start_time, '', 'H:i:00 Y-m-d', '', '', '');
            $leave_end_time = $this->check_date('empty', $leave_date . ' ' . $leave_end_time, '', 'H:i:00 Y-m-d', '', '', '');

            $late = $this->get_attendance_late_total($employee_id, $leave_start_time, false) / 60;
            $early_leaving = $this->get_attendance_early_leaving_total($employee_id, $leave_start_time, $leave_end_time, false) / 60;

            $working_hours_schedule = $this->get_working_hours_schedule($employee_id, $leave_date, $leave_day);
            $working_hours_id = $working_hours_schedule[0]['WORKING_HOURS_ID'] ?? null;
            
            if(!empty($working_hours_id)){
                $morning_work_from = $working_hours_schedule[0]['MORNING_WORK_FROM'] ?? null;
                $morning_work_to = $working_hours_schedule[0]['MORNING_WORK_TO'] ?? null;
                $afternoon_work_from = $working_hours_schedule[0]['AFTERNOON_WORK_FROM'] ?? null;
                $afternoon_work_to = $working_hours_schedule[0]['AFTERNOON_WORK_TO'] ?? null;

                $total_hours = (floor(((strtotime($morning_work_to) - strtotime($morning_work_from)) / 3600)) + floor(((strtotime($afternoon_work_to) - strtotime($afternoon_work_from)) / 3600))) - ($late + $early_leaving);
            }
            else{
                $total_hours = floor(((strtotime($leave_end_time) - strtotime($leave_start_time)) / 3600));
            }

            if($total_hours <= 0){
                $total_hours = 0;
            }

            return $total_hours;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_time_in_behavior_status
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_time_in_behavior_status($stat){
        $response = array();

        switch ($stat) {
            case 'REG':
                $status = 'Regular';
                $button_class = 'bg-success';
                break;
            case 'EARLY':
                $status = 'Early';
                $button_class = 'bg-info';
                break;
            case 'LATE':
                $status = 'Late';
                $button_class = 'bg-danger';
                break;
            default:
                $status = '--';
                $button_class = 'bg-info';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_time_out_behavior_status
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_time_out_behavior_status($stat){
        $response = array();

        switch ($stat) {
            case 'REG':
                $status = 'Regular';
                $button_class = 'bg-success';
                break;
            case 'OT':
                $status = 'Overtime';
                $button_class = 'bg-warning';
                break;
            case 'EL':
                $status = 'Early Leaving';
                $button_class = 'bg-danger';
                break;
            default:
                $status = '--';
                $button_class = 'bg-info';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_adjustment_status
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_adjustment_status($stat){
        $response = array();

        switch ($stat) {
            case 'PEN':
                $status = 'Pending';
                $button_class = 'bg-primary';
                break;
            case 'FORREC':
                $status = 'For Recommendation';
                $button_class = 'bg-info';
                break;
            case 'REC':
                $status = 'Recommended';
                $button_class = 'bg-info';
                break;
            case 'APV':
                $status = 'Approved';
                $button_class = 'bg-success';
                break;
            case 'REJ':
                $status = 'Rejected';
                $button_class = 'bg-danger';
                break;
            default:
                $status = 'Cancelled';
                $button_class = 'bg-warning';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_adjustment_sanction
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_adjustment_sanction($stat){
        $response = array();

        switch ($stat) {
            case 1:
                $status = 'True';
                $button_class = 'bg-danger';
                break;
            default:
                $status = 'False';
                $button_class = 'bg-warning';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_creation_status
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_creation_status($stat){
        $response = array();

        switch ($stat) {
            case 'PEN': 
                $status = 'Pending';
                $button_class = 'bg-primary';
                break;
            case 'FORREC':
                $status = 'For Recommendation';
                $button_class = 'bg-info';
                break;
            case 'REC':
                $status = 'Recommended';
                $button_class = 'bg-info';
                break;
            case 'APV':
                $status = 'Approved';
                $button_class = 'bg-success';
                break;
            case 'REJ':
                $status = 'Rejected';
                $button_class = 'bg-danger';
                break;
            default:
                $status = 'Cancelled';
                $button_class = 'bg-warning';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_attendance_creation_sanction
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_attendance_creation_sanction($stat){
        $response = array();

        switch ($stat) {
            case 1:
                $status = 'True';
                $button_class = 'bg-danger';
                break;
            default:
                $status = 'False';
                $button_class = 'bg-warning';
        }

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge '. $button_class .'">'. $status .'</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_approval_type_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_approval_type_status($stat){
        $response = array();

        switch ($stat) {
            case 'ACTIVE':
                $status = 'Active';
                $button_class = 'bg-success';
                break;
            default:
                $status = 'Inactive';
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
    #
    # Name       : get_leave_type_paid_type
    # Purpose    : Returns the leave type paid type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_type_paid_type($paid_type){
        if ($paid_type == 'PAID') {
            return 'Paid';
        }
        else{
            return 'Unpaid';
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_leave_type_allocation_type
    # Purpose    : Returns the leave type allocation type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_type_allocation_type($allocation_type){
        if ($allocation_type == 'LIMITED') {
            return 'Limited';
        }
        else{
            return 'No Limit';
        }
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Name       : get_leave_status
    # Purpose    : Returns the status, badge
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_leave_status($stat){
        $response = array();

        switch ($stat) {
            case 'PEN':
                $status = 'Pending';
                $button_class = 'bg-primary';
                break;
            case 'FA':
                $status = 'For Approval';
                $button_class = 'bg-info';
                break;
            case 'APV':
                $status = 'Approved';
                $button_class = 'bg-success';
                break;
            case 'REJ':
                $status = 'Rejected';
                $button_class = 'bg-danger';
                break;
            default:
                $status = 'Cancelled';
                $button_class = 'bg-warning';
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
    # Name       : check_role_permissions
    # Purpose    : Checks the permissions of the role.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_role_permissions($username, $permission_id){
        if ($this->databaseConnection()) {
            $total = 0;

            $sql = $this->db_connection->prepare('SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = :username');
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $role_id = $row['ROLE_ID'];

                    $total += $this->get_permission_count($role_id, $permission_id);
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

            if($user_status == 'ACTIVE' || $failed_login < 5){
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
    # Name       : time_interface_upload
    # Purpose    : Checks the interface upload.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function time_interface_upload($file, $request, $interface_setting_id, $username){
        $file_type = '';
        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_tmp_name = $file['tmp_name'];
        $file_ext = explode('.', $file_name);
        $file_actual_ext = strtolower(end($file_ext));

        if(!empty($file_name)){
            switch ($request) {
                case 'login background':
                    $file_size_error = 'The file uploaded for login background exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for login background is not supported.';
                    $upload_setting_id = 2;
                    break;
                case 'login logo':
                    $file_size_error = 'The file uploaded for login logo exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for login logo is not supported.';
                    $upload_setting_id = 3;
                    break;
                case 'menu logo':
                    $file_size_error = 'The file uploaded for menu logo exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for menu logo is not supported.';
                    $upload_setting_id = 4;
                    break;
                case 'menu icon':
                    $file_size_error = 'The file uploaded for menu icon exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for menu icon is not supported.';
                    $upload_setting_id = 5;
                    break;
                default:
                    $file_size_error = 'The file uploaded for favicon exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for favicon is not supported.';
                    $upload_setting_id = 6;
            }

            $upload_setting_details = $this->get_upload_setting_details($upload_setting_id);
            $upload_file_type_details = $this->get_upload_file_type_details($upload_setting_id);
            $file_max_size = $upload_setting_details[0]['MAX_FILE_SIZE'] * 1048576;

            for($i = 0; $i < count($upload_file_type_details); $i++) {
                $file_type .= $upload_file_type_details[$i]['FILE_TYPE'];

                if($i != (count($upload_file_type_details) - 1)){
                    $file_type .= ',';
                }
            }

            $allowed_ext = explode(',', $file_type);

            if(in_array($file_actual_ext, $allowed_ext)){
                if(!$file_error){
                    if($file_size < $file_max_size){
                        $update_interface_settings_images = $this->update_interface_settings_images($file_tmp_name, $file_actual_ext, $request, $interface_setting_id, $username);

                        if($update_interface_settings_images){
                            return true;
                        }
                        else{
                            return $update_interface_settings_images;
                        }
                    }
                    else{
                        return $file_size_error;
                    }
                }
                else{
                    return 'There was an error uploading the file.';
                }
            }
            else {
                return $file_type_error;
            }
        }
        else{
            return true;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_working_hours_schedule_overlap
    # Purpose    : Checks if working hours schedule overlaps.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_working_hours_schedue_overlap($morning_work_from, $morning_work_to, $afternoon_work_from, $afternoon_work_to){
        if(!empty($morning_work_from) && !empty($morning_work_to) && !empty($afternoon_work_from) && !empty($afternoon_work_to)){
            if((strtotime($morning_work_from) <= strtotime($afternoon_work_from) && strtotime($morning_work_to) >= strtotime($afternoon_work_from)) || (strtotime($morning_work_from) <= strtotime($afternoon_work_to) && strtotime($morning_work_to) >= strtotime($afternoon_work_to))){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_notification_channel
    # Purpose    : Checks if the notification channel is enabled.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_channel($notification_id, $channel){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_channel(:notification_id, :channel)');
            $sql->bindValue(':notification_id', $notification_id);
            $sql->bindValue(':channel', $channel);

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
    # Name       : check_attendance_validation
    # Purpose    : Checks attendance validation.
    #
    # Returns    : String/null
    #
    # -------------------------------------------------------------
    public function check_attendance_validation($time_in, $time_out){
        if(!empty($time_in) && !empty($time_out)){
            if(strtotime($time_in) > strtotime($time_out) || strtotime($time_out) < strtotime($time_in)){
                return 'Invalid';
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_time_validation
    # Purpose    : Checks time validation.
    #
    # Returns    : String/null
    #
    # -------------------------------------------------------------
    public function check_time_validation($start_time, $time_out){
        if(!empty($start_time) && !empty($time_out)){
            if(strtotime($start_time) > strtotime($time_out) || strtotime($time_out) < strtotime($start_time)){
                return 'Invalid';
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_date_range_validation
    # Purpose    : Checks date range validation.
    #
    # Returns    : String/null
    #
    # -------------------------------------------------------------
    public function check_date_range_validation($start_date, $end_date){
        if(!empty($start_date) && !empty($end_date)){
            if(strtotime($start_date) > strtotime($end_date) || strtotime($end_date) < strtotime($start_date)){
                return 'Invalid';
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_leave_allocation_overlap
    # Purpose    : Checks the leave allocation overlap.
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function check_leave_allocation_overlap($leave_allocation_id, $validity_start_date, $validity_end_date, $employee_id, $leave_type){
        if ($this->databaseConnection()) {
            $overlap_count = 0;

            $sql = $this->db_connection->prepare('CALL check_leave_allocation_overlap(:leave_allocation_id, :employee_id, :leave_type)');
            $sql->bindValue(':leave_allocation_id', $leave_allocation_id);
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':leave_type', $leave_type);
                                                        
            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $start_date = $row['VALIDITY_START_DATE'];
                        $end_date = $row['VALIDITY_END_DATE'];

                        if((strtotime($validity_start_date) >= strtotime($start_date) && strtotime($validity_start_date) <= strtotime($end_date)) || (strtotime($validity_end_date) >= strtotime($start_date) && strtotime($validity_end_date) <= strtotime($end_date)) || (strtotime($validity_start_date) <= strtotime($start_date) && strtotime($validity_end_date) >= strtotime($end_date))){
                            $overlap_count++;
                        }
                    }
    
                    return $overlap_count;
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
    # Name       : check_employee_leave_allocation
    # Purpose    : Checks the employee leave allocation.
    #
    # Returns    : Date
    #
    # -------------------------------------------------------------
    public function check_employee_leave_allocation($leave_id, $employee_id){
        if ($this->databaseConnection()) {
            $leave_details = $this->get_leave_details($leave_id);
			$leave_type_id = $leave_details[0]['LEAVE_TYPE_ID'] ?? null;
			$leave_date = $leave_details[0]['LEAVE_DATE'] ?? null;
			$total_hours = $leave_details[0]['TOTAL_HOURS'] ?? 0;

            if(!empty($leave_type_id) && !empty($leave_date)){
                $leave_type_details = $this->get_leave_type_details($leave_type_id);
                $allocation_type = $leave_type_details[0]['ALLOCATION_TYPE'] ?? null;

                if($allocation_type == 'LIMITED'){
                    $employee_leave_allocation_details = $this->get_employee_leave_allocation_details($employee_id, $leave_type_id, $leave_date);
                    $duration = $employee_leave_allocation_details[0]['DURATION'] ?? 0;
                    $availed = $employee_leave_allocation_details[0]['AVAILED'] ?? 0;
                    $total = $duration - ($total_hours + $availed);
    
                    if($total < 0){
                        $total = 0;
                    }
                }
                else{
                    $total = 1;   
                }
            }
            else{
                $total = 0;
            }

            return $total;
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
    #
    # Name       : generate_role_permission_form
    # Purpose    : Generates permission check box.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_role_permission_form(){
        if ($this->databaseConnection()) {
            $counter = 0;
            $column = '<div class="accordion" id="permission-accordion">';
        
            $sql = $this->db_connection->prepare('SELECT POLICY_ID, POLICY FROM global_policy ORDER BY POLICY');
        
            if($sql->execute()){
                while($row = $sql->fetch()){
                    $policy_id = $row['POLICY_ID'];
                    $policy = $row['POLICY'];

                    $column .= ' 
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-'. $policy_id .'">
                                            <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-'. $policy_id .'" aria-expanded="false" aria-controls="collapse-'. $policy_id .'">
                                                '. $policy .'
                                            </button>
                                        </h2>
                                        <div id="collapse-'. $policy_id .'" class="accordion-collapse collapse" aria-labelledby="heading-'. $policy_id .'" data-bs-parent="#permission-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <tbody>';
                                                        
                                                        $sql2 = $this->db_connection->prepare('SELECT PERMISSION_ID, PERMISSION FROM global_permission WHERE POLICY_ID = :policy_id ORDER BY PERMISSION_ID');
                                                        $sql2->bindValue(':policy_id', $policy_id);
                                    
                                                        if($sql2->execute()){
                                                            while($res = $sql2->fetch()){
                                                                $permission_id = $res['PERMISSION_ID'];
                                                                $permission = $res['PERMISSION'];
                                    
                                                                $column .= '<tr>
                                                                    <td><label class="form-check-label" for="'. $permission_id .'">'. $permission .'</label></td>
                                                                    <td>
                                                                        <div class="form-check form-switch mb-3">
                                                                            <input class="form-check-input role-permissions" type="checkbox" id="'. $permission_id .'" value="'. $permission_id .'">
                                                                        </div>
                                                                    </td>
                                                                </tr>';
                                                            }
                                                        }

                                            $column .= '</tbody>   
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               ';
                }

                $column .= '</div>';

                return $column;
            }
            else{
                return $sql->errorInfo();
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
    # Name       : generate_role_permission_form
    # Purpose    : Generates permission check box.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_state_options(){
        if ($this->databaseConnection()) {      
            $option = '';

            $sql = $this->db_connection->prepare('SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country ORDER BY COUNTRY_NAME');
        
            if($sql->execute()){
                while($row = $sql->fetch()){
                    $country_id = $row['COUNTRY_ID'];
                    $country_name = $row['COUNTRY_NAME'];

                    $option .= '<optgroup label="'. $country_name .'">';

                                    $sql2 = $this->db_connection->prepare('SELECT STATE_ID, STATE_NAME FROM global_state WHERE COUNTRY_ID = :country_id ORDER BY STATE_NAME');
                                    $sql2->bindValue(':country_id', $country_id);
                
                                    if($sql2->execute()){
                                        while($res = $sql2->fetch()){
                                            $state_id = $res['STATE_ID'];
                                            $state_name = $res['STATE_NAME'];

                                            $option .= '<option value="'. $state_id .'">'. $state_name .'</option>';
                                        }
                                    }

                    $option .= '</optgroup>';
                }

                return $option;
            }
            else{
                return $sql->errorInfo();
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_country_options
    # Purpose    : Generates country options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_country_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_country_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $country_id = $row['COUNTRY_ID'];
                        $country_name = $row['COUNTRY_NAME'];
    
                        $option .= "<option value='". $country_id ."'>". $country_name ."</option>";
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
    # Name       : generate_user_account_options
    # Purpose    : Generates user account options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_user_account_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_user_account_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
    
                        $option .= "<option value='". $username ."'>". $username ."</option>";
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
    # Name       : generate_work_location_options
    # Purpose    : Generates work location options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_work_location_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_work_location_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $work_location_id = $row['WORK_LOCATION_ID'];
                        $work_location = $row['WORK_LOCATION'];
    
                        $option .= "<option value='". $work_location_id ."'>". $work_location ."</option>";
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
    # Name       : generate_department_options
    # Purpose    : Generates department options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_department_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_department_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $department_id = $row['DEPARTMENT_ID'];
                        $department = $row['DEPARTMENT'];
    
                        $option .= "<option value='". $department_id ."'>". $department ."</option>";
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
    # Name       : generate_job_position_options
    # Purpose    : Generates job positions options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_job_position_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_job_position_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $job_position_id = $row['JOB_POSITION_ID'];
                        $job_position = $row['JOB_POSITION'];
    
                        $option .= "<option value='". $job_position_id ."'>". $job_position ."</option>";
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
    # Name       : generate_employee_type_options
    # Purpose    : Generates employee type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_employee_type_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_employee_type_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $employee_type_id = $row['EMPLOYEE_TYPE_ID'];
                        $employee_type = $row['EMPLOYEE_TYPE'];
    
                        $option .= "<option value='". $employee_type_id ."'>". $employee_type ."</option>";
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
    # Name       : generate_working_hours_options
    # Purpose    : Generates working hours options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_working_hours_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_working_hours_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $working_hours_id = $row['WORKING_HOURS_ID'];
                        $working_hours = $row['WORKING_HOURS'];
    
                        $option .= "<option value='". $working_hours_id ."'>". $working_hours ."</option>";
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
    # Name       : generate_employee_options
    # Purpose    : Generates employee options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_employee_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_employee_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $employee_id = $row['EMPLOYEE_ID'];
                        $file_as = $row['FILE_AS'];
    
                        $option .= "<option value='". $employee_id ."'>". $file_as ."</option>";
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
    # Name       : generate_company_options
    # Purpose    : Generates company options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_company_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_company_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $company_id = $row['COMPANY_ID'];
                        $company_name = $row['COMPANY_NAME'];
    
                        $option .= "<option value='". $company_id ."'>". $company_name ."</option>";
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
    # Name       : generate_departure_reason_options
    # Purpose    : Generates departure reason options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_departure_reason_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_departure_reason_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $departure_reason_id = $row['DEPARTURE_REASON_ID'];
                        $departure_reason = $row['DEPARTURE_REASON'];
    
                        $option .= "<option value='". $departure_reason_id ."'>". $departure_reason ."</option>";
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
    # Name       : generate_notification_list
    # Purpose    : Generates employee notification list table.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_notification_list($employee_id){
        if ($this->databaseConnection()) {
            $notification_list = '';
            $system_date = date('Y-m-d');

            $sql = $this->db_connection->prepare('SELECT NOTIFICATION_ID, NOTIFICATION_FROM, NOTIFICATION_TO, STATUS, NOTIFICATION_TITLE, NOTIFICATION, LINK, NOTIFICATION_DATE FROM global_notification WHERE NOTIFICATION_TO = :employee_id ORDER BY NOTIFICATION_DATE DESC LIMIT 20');
            $sql->bindValue(':employee_id', $employee_id);
        
            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $notification_id = trim($row['NOTIFICATION_ID']);
                        $notification_from = trim($row['NOTIFICATION_FROM']);
                        $notification_to = trim($row['NOTIFICATION_TO']);
                        $status = $row['STATUS'];
                        $notification_title = trim($row['NOTIFICATION_TITLE']);
                        $notification = trim($row['NOTIFICATION']);
                        $notification_date = $this->check_date('empty', $row['NOTIFICATION_DATE'], '', 'd M Y h:i:s a', '', '', '');
                        $notification_id_encrypted = $this->encrypt_data($notification_id);

                        $date_diff = round((strtotime($notification_date) - strtotime($system_date)) / (60 * 60 * 24));

                        if($date_diff <= 7){
                            $date_elapsed = $this->time_elapsed_string($notification_date);
                        }
                        else{
                            $date_elapsed = $notification_date;
                        }

                        if($status == 0 || $status == 2){
                            $text_color = 'text-primary';
                        }
                        else{
                            $text_color = '';
                        }

                        if(!empty($row['LINK'])){
                            $link = $row['LINK'];
                        }
                        else{
                            $link = 'javascript: void(0);';
                        }

                        $notification_list .= '<a href="'. $link .'" class="text-reset notification-item" data-notification-id="'. $notification_id .'">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title bg-info rounded-circle font-size-16">
                                                                    <i class="bx bx-info-circle"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 '. $text_color .'" key="t-your-order">'. $notification_title .'</h6>
                                                            <div class="font-size-12 text-muted">
                                                                <p class="mb-1" key="t-grammer">'. $notification .'</p>
                                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">'. $date_elapsed .'</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>';
                    }
                }
                else{
                    $notification_list .= '<a href="javascript: void(0);" class="text-reset notification-item">
                        <p class="mb-2 text-center" key="t-grammer">No New Notifications</p>
                    </a>';
                }

                return $notification_list;
            }
            else{
                return $sql->errorInfo();
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_employee_attendance_options
    # Purpose    : Generates employee attendance options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_employee_attendance_options($username){
        if ($this->databaseConnection()) {
            $option = '';

            $employee_details = $this->get_employee_details($username);
            $employee_id = $employee_details[0]['EMPLOYEE_ID'];
            
            $sql = $this->db_connection->prepare('CALL generate_employee_attendance_options(:employee_id)');
            $sql->bindValue(':employee_id', $employee_id);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $attendance_id = $row['ATTENDANCE_ID'];
                        $time_in = $this->check_date('empty', $row['TIME_IN'], '', 'm/d/Y h:i:s a', '', '', '');
                        $time_out = $this->check_date('empty', $row['TIME_OUT'], '', 'm/d/Y h:i:s a', '', '', '');

                        if(!empty($time_out)){
                            $attendance_details = $time_in . ' - ' . $time_out;
                        }
                        else{
                            $attendance_details = $time_in;
                        }
    
                        $option .= "<option value='". $attendance_id ."'>". $attendance_details ."</option>";
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
    # Name       : generate_leave_type_options
    # Purpose    : Generates leave type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_leave_type_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_leave_type_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $leave_type = $row['LEAVE_TYPE'];
    
                        $option .= "<option value='". $leave_type_id ."'>". $leave_type ."</option>";
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
    # Name       : generate_leave_type_variation_options
    # Purpose    : Generates leave type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_leave_type_variation_options($variation_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_leave_type_variation_options(:variation_type)');
            $sql->bindValue(':variation_type', $variation_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $leave_type_id = $row['LEAVE_TYPE_ID'];
                        $leave_type = $row['LEAVE_TYPE'];
    
                        $option .= "<option value='". $leave_type_id ."'>". $leave_type ."</option>";
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
    # Name       : generate_attendance_adjustment_table
    # Purpose    : Generates attendance adjustment table.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_attendance_adjustment_table($attendance_id, $status){
        if ($this->databaseConnection()) {
            $table = '<table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th>Sanction</th>
                            </tr>
                        </thead>
                        <tbody>';

            $query = 'SELECT TIME_IN, TIME_OUT, REASON, ATTACHMENT, STATUS, SANCTION FROM attendance_adjustment WHERE ATTENDANCE_ID = :attendance_id';

            if(!empty($status)){
                $query .= ' AND STATUS = :status';
            }
            
            $sql = $this->db_connection->prepare($query);
            $sql->bindValue(':attendance_id', $attendance_id);

            if(!empty($status)){
                $sql->bindValue(':status', $status);
            }
           
            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $status = $row['STATUS'];
                        $sanction = $row['SANCTION'];
                        $attachment = $row['ATTACHMENT'];
                        $time_in = $this->check_date('summary', $row['TIME_IN'], '', 'F d, Y h:i a', '', '', '');
                        $time_out = $this->check_date('summary', $row['TIME_OUT'], '', 'F d, Y h:i a', '', '', '');

                        $status_name = $this->get_attendance_adjustment_status($status)[0]['BADGE'];
                        $sanction_name = $this->get_attendance_adjustment_sanction($sanction)[0]['BADGE'];

                        $attendance_details = $this->get_attendance_details($attendance_id);
                        $attendance_time_in = $this->check_date('summary', $attendance_details[0]['TIME_IN'], '', 'F d, Y h:i a', '', '', '');
                        $attendance_time_out = $this->check_date('summary', $attendance_details[0]['TIME_OUT'], '', 'F d, Y h:i a', '', '', '');

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

                        $table .= '<tr>
                                    <td>'. $time_in_details .'</td>
                                    <td>'. $time_out_details .'</td>
                                    <td>'. $attachment .'</td>
                                    <td>'. $status_name .'</td>
                                    <td>'. $sanction_name .'</td>
                                </tr>';
                    }
                }
                else{
                    $table .= '<tr>
                        <td colspan="5"><p class="text-center">No Attendance Adjustments</p></td>
                    </tr>';
                }

                $table .= '</tbody>
                </table>';

                return $table;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Name       : generate_leave_supporting_documents_table
    # Purpose    : Generates leave supporting document table.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_leave_supporting_documents_table($leave_id){
        if ($this->databaseConnection()) {
            $table = '<table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Supporting Document</th>
                                <th>Upload Date</th>
                                <th>Uploaded By</th>
                            </tr>
                        </thead>
                        <tbody>';
            
            $sql = $this->db_connection->prepare('SELECT DOCUMENT_NAME, SUPPORTING_DOCUMENT, UPLOADED_BY, UPLOAD_DATE FROM leave_supporting_document WHERE LEAVE_ID = :leave_id');
            $sql->bindValue(':leave_id', $leave_id);
           
            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $document_name = $row['DOCUMENT_NAME'];
                        $supporting_document = $row['SUPPORTING_DOCUMENT'];
                        $uploaded_by = $row['UPLOADED_BY'];
                        $upload_date = $this->check_date('summary', $row['UPLOAD_DATE'], '', 'F d, Y h:i a', '', '', '');

                        $table .= '<tr>
                                    <td><a href="'. $supporting_document .'" target="_blank">'. $document_name .'</a></td>
                                    <td>'. $upload_date .'</td>
                                    <td>'. $uploaded_by .'</td>
                                </tr>';
                    }
                }
                else{
                    $table .= '<tr>
                        <td colspan="5"><p class="text-center">No Leave Supporting Document</p></td>
                    </tr>';
                }

                $table .= '</tbody>
                </table>';

                return $table;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

}

?>