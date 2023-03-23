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

    const TIME_UNITS = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    ];

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
        if ($this->db_connection) {
            return $this->db_connection;
        }
    
        try {
            $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception('Error connecting to database: ' . $e->getMessage());
        }
    
        return $this->db_connection;
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
    
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = sprintf('C:\xampp\mysql\bin\mysqldump.exe --routines --single-transaction -u %s -p%s %s -r %s',
                    escapeshellarg(DB_USER), escapeshellarg(DB_PASS), escapeshellarg(DB_NAME), escapeshellarg($backup_file)
                );
            }
            else {
                $cmd = sprintf('/usr/bin/mysqldump --routines --single-transaction -u %s -p%s %s -r %s',
                    escapeshellarg(DB_USER), escapeshellarg(DB_PASS), escapeshellarg(DB_NAME), escapeshellarg($backup_file)
                );
            }
            
            exec($cmd, $output, $return);
            
            if ($return === 0) {
                return true;
            } 
            else {
                return 'Error: mysqldump command failed with error code ' . $return;
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
    public function format_date($date, $format = 'Y-m-d H:i:s', $modify = null) {
        if (empty($date) || empty($format)) {
            return 'Error: Missing required input';
        }
    
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
    
        if (!$datetime) {
            return 'Error: Invalid date format';
        }
    
        if ($modify) {
            $datetime->modify($modify);
        }
    
        return $datetime->format($format);
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
    public function encrypt_data($plaintext) {
        if (empty(trim($plaintext))) return false;

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt(trim($plaintext), 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$ciphertext) return false;
        
        return rawurlencode(base64_encode($iv . $ciphertext));
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
    public function decrypt_data($ciphertext) {
        $ciphertext = base64_decode(rawurldecode($ciphertext));

        if (!$ciphertext) {
            return false;
        }
        
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');

        if (strlen($ciphertext) < $iv_length) {
            return false;
        }
        
        $iv = substr($ciphertext, 0, $iv_length);
        $ciphertext = substr($ciphertext, $iv_length);
        
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        
        if (!$plaintext) {
            return false;
        }
        
        return $plaintext;
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
        $dateObject->modify('last day of +' . $months . ' month');
        $next = new DateTime($dateObject->format('Y-m-d'));

        return ($dateObject->format('d') > $next->format('d')) ? $dateObject->diff($next) : new DateInterval('P' . $months . 'M');        
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
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Error: Invalid email';
        }

        if (empty($email)) {
            return 'Error: Missing email';
        }

        return true;
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
                        $decrypted_password = $this->decrypt_data($user_account_details[0]['PASSWORD']);
                        
                        if($decrypted_password === $password){
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
    public function time_elapsed_string($datetime, $full = false, $units = TIME_UNITS) {
        $ago = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $datetime);
        
        if ($ago === false) {
            return 'Invalid datetime format';
        }
    
        $now = new DateTimeImmutable();
        $diff = $now->diff($ago);
    
        $elaped_time = [];
        
        foreach ($units as $unit => $label) {
            if ($diff->$unit) {
                $elaped_time[] = sprintf('%d %s%s', $diff->$unit, $label, $diff->$unit > 1 ? 's' : '');
                if (!$full) {
                    break;
                }
            }
        }
        return $elaped_time ? implode(', ', $elaped_time) . ' ago' : 'just now';
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
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                $error = error_get_last();
                return 'Error creating directory: ' . $error['message'];
            }
        }
        else {
            if (!is_writable($directory)) {
                return 'Directory exists, but is not writable';
            }
        }

        return true;
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_password_history_exist
    # Purpose    : Checks if the password history exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_password_history_exist($username, $password){
        if ($this->databaseConnection()) {
            $total = 0;

            $sql = $this->db_connection->prepare('SELECT PASSWORD FROM global_password_history WHERE USERNAME = :username');
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $password_history = $this->decrypt_data($row['PASSWORD']);
                    
                    if($password_history === $password){
                        $total = $total + 1;
                    }
                }
                
                return (int) $total;
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_interface_setting_exist
    # Purpose    : Checks if the interface setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_interface_setting_exist($interface_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_interface_setting_exist(:interface_setting_id)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_email_setting_exist
    # Purpose    : Checks if the email setting exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_email_setting_exist($email_setting_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_email_setting_exist(:email_setting_id)');
            $sql->bindValue(':email_setting_id', $email_setting_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_notification_user_account_recipient_exist
    # Purpose    : Checks if the notification user account recipient exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_user_account_recipient_exist($notification_setting_id, $user_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_user_account_recipient_exist(:notification_setting_id, :user_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':user_id', $user_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_notification_role_recipient_exist
    # Purpose    : Checks if the notification role recipient exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_role_recipient_exist($notification_setting_id, $role_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_role_recipient_exist(:notification_setting_id, :role_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_notification_channel_exist
    # Purpose    : Checks if the notification channel exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_notification_channel_exist($notification_setting_id, $channel){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_notification_channel_exist(:notification_setting_id, :channel)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':channel', $channel);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_zoom_api_exist
    # Purpose    : Checks if the zoom API exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_zoom_api_exist($zoom_api_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_zoom_api_exist(:zoom_api_id)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_job_position_responsibility_exist
    # Purpose    : Checks if the job position responsibility exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_job_position_responsibility_exist($responsibility_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_job_position_responsibility_exist(:responsibility_id)');
            $sql->bindValue(':responsibility_id', $responsibility_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_job_position_requirement_exist
    # Purpose    : Checks if the job position requirement exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_job_position_requirement_exist($requirement_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_job_position_requirement_exist(:requirement_id)');
            $sql->bindValue(':requirement_id', $requirement_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_job_position_qualification_exist
    # Purpose    : Checks if the job position qualification exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_job_position_qualification_exist($qualification_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_job_position_qualification_exist(:qualification_id)');
            $sql->bindValue(':qualification_id', $qualification_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_job_position_attachment_exist
    # Purpose    : Checks if the job position attachment exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_job_position_attachment_exist($attachment_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_job_position_attachment_exist(:attachment_id)');
            $sql->bindValue(':attachment_id', $attachment_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_id_type_exist
    # Purpose    : Checks if the ID type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_id_type_exist($id_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_id_type_exist(:id_type_id)');
            $sql->bindValue(':id_type_id', $id_type_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_wage_type_exist
    # Purpose    : Checks if the wage type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_wage_type_exist($wage_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_wage_type_exist(:wage_type_id)');
            $sql->bindValue(':wage_type_id', $wage_type_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_working_schedule_exist
    # Purpose    : Checks if the working schedule exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_working_schedule_exist($working_schedule_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_working_schedule_exist(:working_schedule_id)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_working_schedule_type_exist
    # Purpose    : Checks if the working schedule type exists.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_working_schedule_type_exist($working_schedule_type_id){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_working_schedule_type_exist(:working_schedule_type_id)');
            $sql->bindValue(':working_schedule_type_id', $working_schedule_type_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
    public function update_module($module_id, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username){
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

            $sql = $this->db_connection->prepare('CALL update_module(:module_id, :module_name, :module_version, :module_description, :module_category, :default_page, :transaction_log_id, :record_log, :order_sequence)');
            $sql->bindValue(':module_id', $module_id);
            $sql->bindValue(':module_name', $module_name);
            $sql->bindValue(':module_version', $module_version);
            $sql->bindValue(':module_description', $module_description);
            $sql->bindValue(':module_category', $module_category);
            $sql->bindValue(':default_page', $default_page);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
            $sql->bindValue(':order_sequence', $order_sequence);
        
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
                return $stmt->errorInfo()[2];
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

                $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE. 'module_icon/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'module_icon/' . $file_new;

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
                                    return $stmt->errorInfo()[2];
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
                                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
            $system_code_details = $this->get_system_code_details($system_code_id, null, null);
            
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
                return $stmt->errorInfo()[2];
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
            $upload_setting_details = $this->get_upload_setting_details($upload_setting_id, null, null);
            
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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

                $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'company/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'company/' . $file_new;

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
                                    return $stmt->errorInfo()[2];
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
                                return $stmt->errorInfo()[2];
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
    # Name       : update_interface_setting
    # Purpose    : Updates interface setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_interface_setting($interface_setting_id, $interface_setting_name, $description, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $interface_setting_details = $this->get_interface_setting_details($interface_setting_id);
            
            if(!empty($interface_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $interface_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_interface_setting(:interface_setting_id, :interface_setting_name, :description, :transaction_log_id, :record_log)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);
            $sql->bindValue(':interface_setting_name', $interface_setting_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($interface_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated interface setting.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated interface setting.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_interface_setting_status
    # Purpose    : Updates interface setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_interface_setting_status($interface_setting_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $interface_setting_details = $this->get_interface_setting_details($interface_setting_id);
            $transaction_log_id = $interface_setting_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Activate';
            }
            else{
                $log_status = 'Deactivate';
            }

            $sql = $this->db_connection->prepare('CALL update_interface_setting_status(:interface_setting_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated interface setting status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_other_interface_setting_status
    # Purpose    : Updates the other interface settings to deactivated.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_other_interface_setting_status($interface_setting_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $interface_setting_details = $this->get_interface_setting_details($interface_setting_id);
            $transaction_log_id = $interface_setting_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare('CALL update_other_interface_setting_status(:interface_setting_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_interface_settings_upload
    # Purpose    : Checks the interface setting upload.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function update_interface_settings_upload($file, $request, $interface_setting_id, $username){
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
                    $upload_setting_id = 3;
                    break;
                case 'login logo':
                    $file_size_error = 'The file uploaded for login logo exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for login logo is not supported.';
                    $upload_setting_id = 4;
                    break;
                case 'menu logo':
                    $file_size_error = 'The file uploaded for menu logo exceeds the maximum file size.';
                    $file_type_error = 'The file uploaded for menu logo is not supported.';
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
                $interface_setting_details = $this->get_interface_setting_details($interface_setting_id);

                if(!empty($interface_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $transaction_log_id = $interface_setting_details[0]['TRANSACTION_LOG_ID'];
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
                        $image = $interface_setting_details[0]['LOGIN_BACKGROUND'] ?? null;
                        $log = 'User ' . $username . ' updated login background.';
                        break;
                    case 'login logo':
                        $file_new = 'login-logo.' . $file_actual_ext;
                        $image = $interface_setting_details[0]['LOGIN_LOGO'] ?? null;
                        $log = 'User ' . $username . ' updated login logo.';
                        break;
                    case 'menu logo':
                        $file_new = 'menu-logo.' . $file_actual_ext;
                        $image = $interface_setting_details[0]['MENU_LOGO'] ?? null;
                        $log = 'User ' . $username . ' updated menu logo.';
                        break;
                    default:
                        $file_new = 'favicon.' . $file_actual_ext;
                        $image = $interface_setting_details[0]['FAVICON'] ?? null;
                        $log = 'User ' . $username . ' updated favicon.';
                }

                $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'interface_setting/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'interface_setting/' . $file_new;

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
                                    if(!empty($interface_setting_details[0]['TRANSACTION_LOG_ID'])){
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
                                    return $stmt->errorInfo()[2];
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
                                if(!empty($interface_setting_details[0]['TRANSACTION_LOG_ID'])){
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
                                return $stmt->errorInfo()[2];
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
    # Name       : update_email_setting
    # Purpose    : Updates email setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_email_setting($email_setting_id, $email_setting_name, $description, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_username, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $email_setting_details = $this->get_email_setting_details($email_setting_id);
            
            if(!empty($email_setting_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $email_setting_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_email_setting(:email_setting_id, :email_setting_name, :description, :mail_host, :port, :smtp_auth, :smtp_auto_tls, :mail_username, :mail_password, :mail_encryption, :mail_from_name, :mail_from_email, :transaction_log_id, :record_log)');
            $sql->bindValue(':email_setting_id', $email_setting_id);
            $sql->bindValue(':email_setting_name', $email_setting_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':mail_host', $mail_host);
            $sql->bindValue(':port', $port);
            $sql->bindValue(':smtp_auth', $smtp_auth);
            $sql->bindValue(':smtp_auto_tls', $smtp_auto_tls);
            $sql->bindValue(':mail_username', $mail_username);
            $sql->bindValue(':mail_password', $mail_password);
            $sql->bindValue(':mail_encryption', $mail_encryption);
            $sql->bindValue(':mail_from_name', $mail_from_name);
            $sql->bindValue(':mail_from_email', $mail_from_email);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($email_setting_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated email setting.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated email setting.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_email_setting_status
    # Purpose    : Updates email setting status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_email_setting_status($email_setting_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $email_setting_details = $this->get_email_setting_details($email_setting_id);
            $transaction_log_id = $email_setting_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Activate';
            }
            else{
                $log_status = 'Deactivate';
            }

            $sql = $this->db_connection->prepare('CALL update_email_setting_status(:email_setting_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':email_setting_id', $email_setting_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated email setting status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_other_email_setting_status
    # Purpose    : Updates the other email settings to deactivated.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_other_email_setting_status($email_setting_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $email_setting_details = $this->get_email_setting_details($email_setting_id);
            $transaction_log_id = $email_setting_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare('CALL update_other_email_setting_status(:email_setting_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':email_setting_id', $email_setting_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function update_notification_setting($notification_setting_id, $notification_setting, $description, $notification_title, $notification_message, $system_link, $email_link, $username){
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

            $sql = $this->db_connection->prepare('CALL update_notification_setting(:notification_setting_id, :notification_setting, :description, :notification_title, :notification_message, :system_link, :email_link, :transaction_log_id, :record_log)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':notification_setting', $notification_setting);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':notification_title', $notification_title);
            $sql->bindValue(':notification_message', $notification_message);
            $sql->bindValue(':system_link', $system_link);
            $sql->bindValue(':email_link', $email_link);
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : update_zoom_api
    # Purpose    : Updates zoom API.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_zoom_api($zoom_api_id, $zoom_api_name, $description, $api_key, $api_secret, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $zoom_api_details = $this->get_zoom_api_details($zoom_api_id);
            
            if(!empty($zoom_api_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $zoom_api_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_zoom_api(:zoom_api_id, :zoom_api_name, :description, :api_key, :api_secret, :transaction_log_id, :record_log)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);
            $sql->bindValue(':zoom_api_name', $zoom_api_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':api_key', $api_key);
            $sql->bindValue(':api_secret', $api_secret);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($zoom_api_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated zoom API.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated zoom API.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_zoom_api_status
    # Purpose    : Updates zoom API status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_zoom_api_status($zoom_api_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $zoom_api_details = $this->get_zoom_api_details($zoom_api_id);
            $transaction_log_id = $zoom_api_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Activate';
            }
            else{
                $log_status = 'Deactivate';
            }

            $sql = $this->db_connection->prepare('CALL update_zoom_api_status(:zoom_api_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated zoom API status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_other_zoom_api_status
    # Purpose    : Updates the other zoom API to deactivated.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_other_zoom_api_status($zoom_api_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $zoom_api_details = $this->get_zoom_api_details($zoom_api_id);
            $transaction_log_id = $zoom_api_details[0]['TRANSACTION_LOG_ID'];

            $sql = $this->db_connection->prepare('CALL update_other_zoom_api_status(:zoom_api_id, :transaction_log_id, :record_log)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Name       : update_user_account_password
    # Purpose    : Updates user account's password.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_user_account_password($username, $password, $password_expiry_date){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL update_user_account_password(:username, :password, :password_expiry_date)');
            $sql->bindValue(':username', $username);
            $sql->bindValue(':password', $password);
            $sql->bindValue(':password_expiry_date', $password_expiry_date);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function update_user_account($user_id, $password, $file_as, $password_expiry_date, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $user_account_details = $this->get_user_account_details($user_id);
            $user_account_password_expiry_date = $this->check_date('empty', $user_account_details[0]['PASSWORD_EXPIRY_DATE'], '', 'Y-m-d', '', '', '');
            $user_account_password = $user_account_details[0]['PASSWORD'];

            if($password == $user_account_password){
                $password_expiry_date = $user_account_password_expiry_date;
            }
            
            if(!empty($user_account_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_user_account(:user_id, :password, :file_as, :password_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':user_id', $user_id);
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
                return $stmt->errorInfo()[2];
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
    public function update_user_account_lock_status($user_id, $transaction_type, $system_date, $username){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($user_id);
            $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

            if($transaction_type == 'unlock'){
                $record_log = 'ULCK->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Unlock';
                $log = 'User ' . $username . ' unlocked user account.';
                $system_date = null;
            }
            else{
                $record_log = 'LCK->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Lock';
                $log = 'User ' . $username . ' locked user account.';
            }

            $sql = $this->db_connection->prepare('CALL update_user_account_lock_status(:user_id, :transaction_type, :system_date, :record_log)');
            $sql->bindValue(':user_id', $user_id);
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
                return $stmt->errorInfo()[2];
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
    public function update_user_account_status($user_id, $user_status, $username){
        if ($this->databaseConnection()) {
            $user_account_details = $this->get_user_account_details($user_id);
            $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

            if($user_status == 'Active'){
                $record_log = 'ACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Activate';
                $log = 'User ' . $username . ' activated user account.';
            }
            else{
                $record_log = 'DACT->' . $username . '->' . date('Y-m-d h:i:s');
                $log_type = 'Deactivated';
                $log = 'User ' . $username . ' deactivated user account.';
            }

            $sql = $this->db_connection->prepare('CALL update_user_account_status(:user_id, :user_status, :record_log)');
            $sql->bindValue(':user_id', $user_id);
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_department_status
    # Purpose    : Updates department status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_department_status($department_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $department_details = $this->get_department_details($department_id);
            $transaction_log_id = $department_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Unarchive';
            }
            else{
                $log_status = 'Archive';
            }

            $sql = $this->db_connection->prepare('CALL update_department_status(:department_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':department_id', $department_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated department status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function update_job_position($job_position_id, $job_position, $description, $department, $expected_new_employees, $username){
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

            $sql = $this->db_connection->prepare('CALL update_job_position(:job_position_id, :job_position, :description, :department, :expected_new_employees, :transaction_log_id, :record_log)');
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':expected_new_employees', $expected_new_employees);
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   
    # Name       : update_job_position_attachment
    # Purpose    : Updates job position attachment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_attachment($attachment_tmp_name, $attachment_actual_ext, $attachment_id, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            
            if(!empty($attachment_tmp_name)){ 
                $file_name = $this->generate_file_name(10);
                $file_new = $file_name . '.' . $attachment_actual_ext;
                
                $directory = DEFAULT_EMPLOYEE_RELATIVE_PATH_FILE . 'job_position_attachment/';
                $file_destination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_EMPLOYEE_FULL_PATH_FILE . 'job_position_attachment/' . $file_new;
                
                $file_path = $directory . $file_new;

                $directory_checker = $this->directory_checker($directory);

                if($directory_checker){
                    $job_position_attachment_details = $this->get_job_position_attachment_details($attachment_id);
                    $attachment = $job_position_attachment_details[0]['ATTACHMENT'];
                    $transaction_log_id = $job_position_attachment_details[0]['TRANSACTION_LOG_ID'];
    
                    if(file_exists($attachment)){
                        if (unlink($attachment)) {
                            if(move_uploaded_file($attachment_tmp_name, $file_destination)){
                                $sql = $this->db_connection->prepare('CALL update_job_position_attachment(:attachment_id, :file_path)');
                                $sql->bindValue(':attachment_id', $attachment_id);
                                $sql->bindValue(':file_path', $file_path);
                            
                                if($sql->execute()){
                                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position attachment.');
                                        
                                    if($insert_transaction_log){
                                        return true;
                                    }
                                    else{
                                        return $insert_transaction_log;
                                    }
                                }
                                else{
                                    return $stmt->errorInfo()[2];
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
                            $sql = $this->db_connection->prepare('CALL update_job_position_attachment(:attachment_id, :file_path)');
                                $sql->bindValue(':attachment_id', $attachment_id);
                                $sql->bindValue(':file_path', $file_path);
                        
                            if($sql->execute()){
                                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position attachment.');
                                    
                                if($insert_transaction_log){
                                    return true;
                                }
                                else{
                                    return $insert_transaction_log;
                                }
                            }
                            else{
                                return $stmt->errorInfo()[2];
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
    # Name       : update_job_position_recruitment_status
    # Purpose    : Updates job position recruitment status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_recruitment_status($job_position_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_details = $this->get_job_position_details($job_position_id);
            $transaction_log_id = $job_position_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Start';
            }
            else{
                $log_status = 'Stop';
            }

            $sql = $this->db_connection->prepare('CALL update_job_position_recruitment_status(:job_position_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated job position recruitment status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_job_position_responsibility
    # Purpose    : Updates job position responsibility.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_responsibility($responsibility_id, $job_position_id, $responsibility, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_responsibility_details = $this->get_job_position_responsibility_details($job_position_id);
            
            if(!empty($job_position_responsibility_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $job_position_responsibility_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_job_position_responsibility(:responsibility_id, :job_position_id, :responsibility, :transaction_log_id, :record_log)');
            $sql->bindValue(':responsibility_id', $responsibility_id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':responsibility', $responsibility);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($job_position_responsibility_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position responsibility.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position responsibility.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_job_position_requirement
    # Purpose    : Updates job position requirement.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_requirement($requirement_id, $job_position_id, $requirement, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_requirement_details = $this->get_job_position_requirement_details($job_position_id);
            
            if(!empty($job_position_requirement_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $job_position_requirement_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_job_position_requirement(:requirement_id, :job_position_id, :requirement, :transaction_log_id, :record_log)');
            $sql->bindValue(':requirement_id', $requirement_id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':requirement', $requirement);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($job_position_requirement_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position requirement.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position requirement.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_job_position_qualification
    # Purpose    : Updates job position qualification.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_qualification($qualification_id, $job_position_id, $qualification, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_qualification_details = $this->get_job_position_qualification_details($job_position_id);
            
            if(!empty($job_position_qualification_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $job_position_qualification_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_job_position_qualification(:qualification_id, :job_position_id, :qualification, :transaction_log_id, :record_log)');
            $sql->bindValue(':qualification_id', $qualification_id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':qualification', $qualification);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($job_position_qualification_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position qualification.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position qualification.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_job_position_attachment_details
    # Purpose    : Updates job position attachment details.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_job_position_attachment_details($attachment_id, $job_position_id, $attachment_name, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $job_position_attachment_details = $this->get_job_position_attachment_details($job_position_id);
            
            if(!empty($job_position_attachment_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $job_position_attachment_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_job_position_attachment_details(:attachment_id, :job_position_id, :attachment_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':attachment_id', $attachment_id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':attachment_name', $attachment_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($job_position_attachment_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position attachment.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated job position attachment.');
                                    
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
                return $stmt->errorInfo()[2];
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
    public function update_work_location($work_location_id, $work_location, $work_location_address, $email, $telephone, $mobile, $location_number, $username){
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

            $sql = $this->db_connection->prepare('CALL update_work_location(:work_location_id, :work_location, :work_location_address, :email, :telephone, :mobile, :location_number, :transaction_log_id, :record_log)');
            $sql->bindValue(':work_location_id', $work_location_id);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':work_location_address', $work_location_address);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':location_number', $location_number);
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_work_location_status
    # Purpose    : Updates work location status.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_work_location_status($work_location_id, $status, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $work_location_details = $this->get_work_location_details($work_location_id);
            $transaction_log_id = $work_location_details[0]['TRANSACTION_LOG_ID'];

            if($status == 1){
                $log_status = 'Unarchive';
            }
            else{
                $log_status = 'Archive';
            }

            $sql = $this->db_connection->prepare('CALL update_work_location_status(:work_location_id, :status, :transaction_log_id, :record_log)');
            $sql->bindValue(':work_location_id', $work_location_id);
            $sql->bindValue(':status', $status);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, $log_status, 'User ' . $username . ' updated work location status.');
                                    
                if($insert_transaction_log){
                    return true;
                }
                else{
                    return $insert_transaction_log;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_id_type
    # Purpose    : Updates employee type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_id_type($id_type_id, $id_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $id_type_details = $this->get_id_type_details($id_type_id);
            
            if(!empty($id_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $id_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_id_type(:id_type_id, :id_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id_type_id', $id_type_id);
            $sql->bindValue(':id_type', $id_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($id_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated ID type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated ID type.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_wage_type
    # Purpose    : Updates wage type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_wage_type($wage_type_id, $wage_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $wage_type_details = $this->get_wage_type_details($wage_type_id);
            
            if(!empty($wage_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $wage_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_wage_type(:wage_type_id, :wage_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':wage_type_id', $wage_type_id);
            $sql->bindValue(':wage_type', $wage_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($wage_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated wage type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated wage type.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_working_schedule
    # Purpose    : Updates working schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_working_schedule($working_schedule_id, $working_schedule, $working_schedule_type, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $working_schedule_details = $this->get_working_schedule_details($working_schedule_id);
            $working_schedule_type_actual = $working_schedule_details[0]['WORKING_SCHEDULE_TYPE'];
            
            if(!empty($working_schedule_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $working_schedule_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_working_schedule(:working_schedule_id, :working_schedule, :working_schedule_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
            $sql->bindValue(':working_schedule', $working_schedule);
            $sql->bindValue(':working_schedule_type', $working_schedule_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($working_schedule_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working schedule.');
                                    
                    if($insert_transaction_log){
                        if($working_schedule_type_actual != $working_schedule_type){
                            $delete_all_working_hours = $this->delete_all_working_hours($working_schedule_id, $username);

                            if($delete_all_working_hours){
                                return true;
                            }
                            else{
                                return $delete_all_working_hours;
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
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working schedule.');
                                    
                        if($insert_transaction_log){
                            if($working_schedule_type_actual != $working_schedule_type){
                                $delete_all_working_hours = $this->delete_all_working_hours($working_schedule_id, $username);
    
                                if($delete_all_working_hours){
                                    return true;
                                }
                                else{
                                    return $delete_all_working_hours;
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
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function update_working_hours($working_hours_id, $working_schedule_id, $working_hours, $working_date, $day_of_week, $day_period, $work_from, $work_to, $username){
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

            $sql = $this->db_connection->prepare('CALL update_working_hours(:working_hours_id, :working_schedule_id, :working_hours, :working_date, :day_of_week, :day_period, :work_from, :work_to, :transaction_log_id, :record_log)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':working_date', $working_date);
            $sql->bindValue(':day_of_week', $day_of_week);
            $sql->bindValue(':day_period', $day_period);
            $sql->bindValue(':work_from', $work_from);
            $sql->bindValue(':work_to', $work_to);
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_working_schedule_type
    # Purpose    : Updates working schedule type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_working_schedule_type($working_schedule_type_id, $working_schedule_type, $working_schedule_type_category, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');
            $working_schedule_type_details = $this->get_working_schedule_type_details($working_schedule_type_id);
            
            if(!empty($working_schedule_type_details[0]['TRANSACTION_LOG_ID'])){
                $transaction_log_id = $working_schedule_type_details[0]['TRANSACTION_LOG_ID'];
            }
            else{
                # Get transaction log id
                $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
                $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
                $transaction_log_id = $transaction_log_system_parameter[0]['ID'];
            }

            $sql = $this->db_connection->prepare('CALL update_working_schedule_type(:working_schedule_type_id, :working_schedule_type, :working_schedule_type_category, :transaction_log_id, :record_log)');
            $sql->bindValue(':working_schedule_type_id', $working_schedule_type_id);
            $sql->bindValue(':working_schedule_type', $working_schedule_type);
            $sql->bindValue(':working_schedule_type_category', $working_schedule_type_category);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                if(!empty($working_schedule_type_details[0]['TRANSACTION_LOG_ID'])){
                    $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working schedule type.');
                                    
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
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Update', 'User ' . $username . ' updated working schedule type.');
                                    
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
                return $stmt->errorInfo()[2];
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
    public function update_employee($employee_id, $badge_id, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $username){
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

            $sql = $this->db_connection->prepare('CALL update_employee(:employee_id, :badge_id, :company, :job_position, :department, :work_location, :working_hours, :manager, :coach, :employee_type, :permanency_date, :onboard_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':badge_id', $badge_id);
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : update_employee_personal_information
    # Purpose    : Updates employee personal information.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function update_employee_personal_information($employee_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $nickname, $civil_status, $nationality, $gender, $birthday, $birth_place, $blood_type, $height, $weight, $religion, $username){
        if ($this->databaseConnection()) {
            $record_log = 'UPD->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL update_employee_personal_information(:employee_id, :file_as, :first_name, :middle_name, :last_name, :suffix, :nickname, :civil_status, :nationality, :gender, :birthday, :birth_place, :blood_type, :height, :weight, :religion, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':first_name', $first_name);
            $sql->bindValue(':middle_name', $middle_name);
            $sql->bindValue(':last_name', $last_name);
            $sql->bindValue(':suffix', $suffix);
            $sql->bindValue(':nickname', $nickname);
            $sql->bindValue(':civil_status', $civil_status);
            $sql->bindValue(':nationality', $nationality);
            $sql->bindValue(':gender', $gender);
            $sql->bindValue(':birthday', $birthday);
            $sql->bindValue(':birth_place', $birth_place);
            $sql->bindValue(':blood_type', $blood_type);
            $sql->bindValue(':height', $height);
            $sql->bindValue(':weight', $weight);
            $sql->bindValue(':religion', $religion);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
    public function insert_module($module_icon_tmp_name, $module_icon_actual_ext, $module_name, $module_version, $module_description, $module_category, $default_page, $order_sequence, $username){
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

            $sql = $this->db_connection->prepare('CALL insert_module(:id, :module_name, :module_version, :module_description, :module_category, :default_page, :transaction_log_id, :record_log, :order_sequence)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':module_name', $module_name);
            $sql->bindValue(':module_version', $module_version);
            $sql->bindValue(':module_description', $module_description);
            $sql->bindValue(':module_category', $module_category);
            $sql->bindValue(':default_page', $default_page);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
            $sql->bindValue(':order_sequence', $order_sequence); 
        
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                return $stmt->errorInfo()[2];
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                return $stmt->errorInfo()[2];
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                return $stmt->errorInfo()[2];
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                return $stmt->errorInfo()[2];
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
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
                return $stmt->errorInfo()[2];
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
                                        'RESPONSE' => $update_company_logo
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_interface_setting
    # Purpose    : Insert interface setting.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_interface_setting($login_background, $login_logo, $menu_logo, $favicon, $interface_setting_name, $description, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(10, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_interface_setting(:id, :interface_setting_name, :description, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':interface_setting_name', $interface_setting_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log); 
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 10, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted interface setting.');
                                    
                        if($insert_transaction_log){
                            $login_background = $this->update_interface_settings_upload($login_background, 'login background', $id, $username);

                            if($login_background){
                                $login_logo = $this->update_interface_settings_upload($login_logo, 'login logo', $id, $username);
        
                                if($login_logo){
                                    $menu_logo = $this->update_interface_settings_upload($menu_logo, 'menu logo', $id, $username);
        
                                    if($menu_logo){
                                        $favicon = $this->update_interface_settings_upload($favicon, 'favicon', $id, $username);
        
                                        if($favicon){
                                            $response[] = array(
                                                'RESPONSE' => true,
                                                'INTERFACE_SETTING_ID' => $this->encrypt_data($id)
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
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_email_setting
    # Purpose    : Insert email setting.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_email_setting($email_setting_name, $description, $mail_host, $port, $smtp_auth, $smtp_auto_tls, $mail_username, $mail_password, $mail_encryption, $mail_from_name, $mail_from_email, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(11, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_email_setting(:id, :email_setting_name, :description, :mail_host, :port, :smtp_auth, :smtp_auto_tls, :mail_username, :mail_password, :mail_encryption, :mail_from_name, :mail_from_email, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':email_setting_name', $email_setting_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':mail_host', $mail_host);
            $sql->bindValue(':port', $port);
            $sql->bindValue(':smtp_auth', $smtp_auth);
            $sql->bindValue(':smtp_auto_tls', $smtp_auto_tls);
            $sql->bindValue(':mail_username', $mail_username);
            $sql->bindValue(':mail_password', $mail_password);
            $sql->bindValue(':mail_encryption', $mail_encryption);
            $sql->bindValue(':mail_from_name', $mail_from_name);
            $sql->bindValue(':mail_from_email', $mail_from_email);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 11, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted email setting.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'EMAIL_SETTING_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_notification_setting
    # Purpose    : Insert notification setting.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_notification_setting($notification_setting, $description, $notification_title, $notification_message, $system_link, $email_link, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(12, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_notification_setting(:id, :notification_setting, :description, :notification_title, :notification_message, :system_link, :email_link, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':notification_setting', $notification_setting);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':notification_title', $notification_title);
            $sql->bindValue(':notification_message', $notification_message);
            $sql->bindValue(':system_link', $system_link);
            $sql->bindValue(':email_link', $email_link);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 12, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted notification setting.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'NOTIFICATION_SETTING_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_notification_user_account_recipient
    # Purpose    : Inserts notification user account recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_user_account_recipient($notification_setting_id, $user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_notification_user_account_recipient(:notification_setting_id, :user_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':user_id', $user_id);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_notification_role_recipient
    # Purpose    : Inserts notification role recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_role_recipient($notification_setting_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_notification_role_recipient(:notification_setting_id, :role_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':role_id', $role_id);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_notification_channel
    # Purpose    : Inserts notification channel.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_notification_channel($notification_setting_id, $channel, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_notification_channel(:notification_setting_id, :channel)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':channel', $channel);

            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_country
    # Purpose    : Insert country.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_country($country_name, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(13, 1);
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
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 13, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted country.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'COUNTRY_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log,
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value,
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_state
    # Purpose    : Insert state.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_state($state_name, $country_id, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(14, 1);
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
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 14, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted state.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'STATE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_zoom_api
    # Purpose    : Insert zoom API.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_zoom_api($zoom_api_name, $description, $api_key, $api_secret, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(15, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_zoom_api(:id, :zoom_api_name, :description, :api_key, :api_secret, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':zoom_api_name', $zoom_api_name);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':api_key', $api_key);
            $sql->bindValue(':api_secret', $api_secret);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 15, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted zoom API.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'ZOOM_API_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
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
    public function insert_user_account($user_id, $password, $file_as, $password_expiry_date, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_user_account(:user_id, :password, :file_as, :password_expiry_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':user_id', $user_id);
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_password_history
    # Purpose    : Insert password history.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_password_history($username, $password){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL insert_password_history(:username, :password)');
            $sql->bindValue(':username', $username);
            $sql->bindValue(':password', $password);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_department
    # Purpose    : Insert department.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_department($department, $parent_department, $manager, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(16, 1);
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
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 16, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted department.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'DEPARTMENT_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_departure_reason
    # Purpose    : Insert departure reason.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_departure_reason($departure_reason, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(23, 1);
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
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 23, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted departure reason.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'DEPARTURE_REASON_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_job_position
    # Purpose    : Insert job position.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_job_position($job_position, $description, $department, $expected_new_employees, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(17, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position(:id, :job_position, :description, :department, :expected_new_employees, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position', $job_position);
            $sql->bindValue(':description', $description);
            $sql->bindValue(':department', $department);
            $sql->bindValue(':expected_new_employees', $expected_new_employees);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 17, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'JOB_POSITION_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_job_position_responsibility
    # Purpose    : Insert job position responsibility.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_job_position_responsibility($job_position_id, $responsibility, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(18, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position_responsibility(:id, :job_position_id, :responsibility, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':responsibility', $responsibility);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 18, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position responsibility.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_job_position_requirement
    # Purpose    : Insert job position requirement.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_job_position_requirement($job_position_id, $requirement, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(18, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position_requirement(:id, :job_position_id, :requirement, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':requirement', $requirement);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 18, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position requirement.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_job_position_qualification
    # Purpose    : Insert job position qualification.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function insert_job_position_qualification($job_position_id, $qualification, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(20, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position_qualification(:id, :job_position_id, :qualification, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':qualification', $qualification);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 20, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position qualification.');
                                    
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_job_position_attachment
    # Purpose    : Insert job position attachment.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_job_position_attachment($attachment_tmp_name, $attachment_actual_ext, $job_position_id, $attachment_name, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(21, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_job_position_attachment(:id, :job_position_id, :attachment_name, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':job_position_id', $job_position_id);
            $sql->bindValue(':attachment_name', $attachment_name);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 21, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted job position attachment.');
                                    
                        if($insert_transaction_log){
                            if(!empty($attachment_tmp_name) && !empty($attachment_actual_ext)){
                                $update_job_position_attachment = $this->update_job_position_attachment($attachment_tmp_name, $attachment_actual_ext, $id, $username);
        
                                if($update_job_position_attachment){
                                    return true;
                                }
                                else{
                                    return $update_job_position_attachment;
                                }
                            }
                            else{
                                return 'true';
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_work_location
    # Purpose    : Insert work location.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_work_location($work_location, $work_location_address, $email, $telephone, $mobile, $location_number, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(22, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_work_location(:id, :work_location, :work_location_address, :email, :telephone, :mobile, :location_number, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':work_location', $work_location);
            $sql->bindValue(':work_location_address', $work_location_address);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':telephone', $telephone);
            $sql->bindValue(':mobile', $mobile);
            $sql->bindValue(':location_number', $location_number);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 22, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted work location.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'WORK_LOCATION_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_employee_type
    # Purpose    : Insert employee type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_employee_type($employee_type, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(24, 1);
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
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 24, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted employee type.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'EMPLOYEE_TYPE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_wage_type
    # Purpose    : Insert wage type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_wage_type($wage_type, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(25, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_wage_type(:id, :wage_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':wage_type', $wage_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 25, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted wage type.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'WAGE_TYPE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_id_type
    # Purpose    : Insert ID type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_id_type($id_type, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(29, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_id_type(:id, :id_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':id_type', $id_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 29, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted ID type.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'ID_TYPE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_working_schedule
    # Purpose    : Insert working schedule.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_working_schedule($working_schedule, $working_schedule_type, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(26, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_working_schedule(:id, :working_schedule, :working_schedule_type, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':working_schedule', $working_schedule);
            $sql->bindValue(':working_schedule_type', $working_schedule_type);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 26, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted working schedule.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'WORKING_SCHEDULE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
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
    public function insert_working_hours($working_schedule_id, $working_hours, $working_date, $day_of_week, $day_period, $work_from, $work_to, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(27, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_working_hours(:id, :working_schedule_id, :working_hours, :working_date, :day_of_week, :day_period, :work_from, :work_to, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
            $sql->bindValue(':working_hours', $working_hours);
            $sql->bindValue(':working_date', $working_date);
            $sql->bindValue(':day_of_week', $day_of_week);
            $sql->bindValue(':day_period', $day_period);
            $sql->bindValue(':work_from', $work_from);
            $sql->bindValue(':work_to', $work_to);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 27, $username);

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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_working_schedule_type
    # Purpose    : Insert working schedule type.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_working_schedule_type($working_schedule_type, $working_schedule_type_category, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(28, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_working_schedule_type(:id, :working_schedule_type, :working_schedule_type_category, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':working_schedule_type', $working_schedule_type);
            $sql->bindValue(':working_schedule_type_category', $working_schedule_type_category);
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 28, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted working schedule type.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'WORKING_SCHEDULE_TYPE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_employee
    # Purpose    : Insert employee.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_employee($badge_id, $company, $job_position, $department, $work_location, $working_hours, $manager, $coach, $employee_type, $permanency_date, $onboard_date, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            # Get system parameter id
            $system_parameter = $this->get_system_parameter(30, 1);
            $parameter_number = $system_parameter[0]['PARAMETER_NUMBER'];
            $id = $system_parameter[0]['ID'];

            # Get transaction log id
            $transaction_log_system_parameter = $this->get_system_parameter(2, 1);
            $transaction_log_parameter_number = $transaction_log_system_parameter[0]['PARAMETER_NUMBER'];
            $transaction_log_id = $transaction_log_system_parameter[0]['ID'];

            $sql = $this->db_connection->prepare('CALL insert_employee(:id, :badge_id, :company, :job_position, :department, :work_location, :working_hours, :manager, :coach, :employee_type, :permanency_date, :onboard_date, :transaction_log_id, :record_log)');
            $sql->bindValue(':id', $id);
            $sql->bindValue(':badge_id', $badge_id);
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
            $sql->bindValue(':transaction_log_id', $transaction_log_id);
            $sql->bindValue(':record_log', $record_log);
        
            if($sql->execute()){
                # Update system parameter value
                $update_system_parameter_value = $this->update_system_parameter_value($parameter_number, 30, $username);

                if($update_system_parameter_value){
                    # Update transaction log value
                    $update_system_parameter_value = $this->update_system_parameter_value($transaction_log_parameter_number, 2, $username);

                    if($update_system_parameter_value){
                        $insert_transaction_log = $this->insert_transaction_log($transaction_log_id, $username, 'Insert', 'User ' . $username . ' inserted employee.');
                                    
                        if($insert_transaction_log){
                            $response[] = array(
                                'RESPONSE' => true,
                                'EMPLOYEE_ID' => $this->encrypt_data($id)
                            );
                        }
                        else{
                            $response[] = array(
                                'RESPONSE' => $insert_transaction_log
                            );
                        }
                    }
                    else{
                        $response[] = array(
                            'RESPONSE' => $update_system_parameter_value
                        );
                    }
                }
                else{
                    $response[] = array(
                        'RESPONSE' => $update_system_parameter_value
                    );
                }
            }
            else{
                $response[] = array(
                    'RESPONSE' => $sql->errorInfo()[2]
                );
            }

            return $response;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : insert_employee_personal_information
    # Purpose    : Insert employee personal information.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function insert_employee_personal_information($employee_id, $file_as, $first_name, $middle_name, $last_name, $suffix, $nickname, $civil_status, $nationality, $gender, $birthday, $place_of_birth, $blood_type, $height, $weight, $religion, $username){
        if ($this->databaseConnection()) {
            $record_log = 'INS->' . $username . '->' . date('Y-m-d h:i:s');

            $sql = $this->db_connection->prepare('CALL insert_employee_personal_information(:employee_id, :file_as, :first_name, :middle_name, :last_name, :suffix, :nickname, :civil_status, :nationality, :gender, :birthday, :place_of_birth, :blood_type, :height, :weight, :religion, :record_log)');
            $sql->bindValue(':employee_id', $employee_id);
            $sql->bindValue(':file_as', $file_as);
            $sql->bindValue(':first_name', $first_name);
            $sql->bindValue(':middle_name', $middle_name);
            $sql->bindValue(':last_name', $last_name);
            $sql->bindValue(':suffix', $suffix);
            $sql->bindValue(':nickname', $nickname);
            $sql->bindValue(':civil_status', $civil_status);
            $sql->bindValue(':nationality', $nationality);
            $sql->bindValue(':gender', $gender);
            $sql->bindValue(':birthday', $birthday);
            $sql->bindValue(':place_of_birth', $place_of_birth);
            $sql->bindValue(':blood_type', $blood_type);
            $sql->bindValue(':height', $height);
            $sql->bindValue(':weight', $weight);
            $sql->bindValue(':religion', $religion);
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
                return $stmt->errorInfo()[2];
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
            $module_details = $this->get_module_details($module_id);
            $module_icon = $module_details[0]['MODULE_ICON'] ?? null;

            $sql = $this->db_connection->prepare('CALL delete_module(:module_id)');
            $sql->bindValue(':module_id', $module_id);
        
            if($sql->execute()){ 
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
            $company_logo = $company_details[0]['COMPANY_LOGO'] ?? null;

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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_interface_setting
    # Purpose    : Delete interface setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_interface_setting($interface_setting_id, $username){
        if ($this->databaseConnection()) {
            $error = '';
            $interface_setting_details = $this->get_interface_setting_details($interface_setting_id);
            $login_background = $interface_setting_details[0]['LOGIN_BACKGROUND'] ?? null;
            $login_logo = $interface_setting_details[0]['LOGIN_LOGO'] ?? null;
            $menu_logo = $interface_setting_details[0]['MENU_LOGO'] ?? null;
            $favicon = $interface_setting_details[0]['FAVICON'] ?? null;

            $sql = $this->db_connection->prepare('CALL delete_interface_setting(:interface_setting_id)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);
        
            if($sql->execute()){ 
                if(!empty($login_background)){
                    if(file_exists($login_background)){
                        if (!unlink($login_background)) {
                            $error = $login_background . ' cannot be deleted due to an error.';
                        }
                    }
                }

                if(!empty($login_logo)){
                    if(file_exists($login_logo)){
                        if (!unlink($login_logo)) {
                            $error = $login_logo . ' cannot be deleted due to an error.';
                        }
                    }
                }

                if(!empty($menu_logo)){
                    if(file_exists($menu_logo)){
                        if (!unlink($menu_logo)) {
                            $error = $menu_logo . ' cannot be deleted due to an error.';
                        }
                    }
                }

                if(!empty($favicon)){
                    if(file_exists($favicon)){
                        if (!unlink($favicon)) {
                            $error = $favicon . ' cannot be deleted due to an error.';
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_email_setting
    # Purpose    : Delete email setting.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_email_setting($email_setting_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_email_setting(:email_setting_id)');
            $sql->bindValue(':email_setting_id', $email_setting_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_notification_user_account_recipient
    # Purpose    : Delete notification user account recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_notification_user_account_recipient($notification_setting_id, $user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_notification_user_account_recipient(:notification_setting_id, :user_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':user_id', $user_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_notification_role_recipient
    # Purpose    : Delete notification role recipient.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_notification_role_recipient($notification_setting_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_notification_role_recipient(:notification_setting_id, :role_id)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_notification_channel
    # Purpose    : Delete notification channel.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_notification_channel($notification_setting_id, $channel, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_notification_channel(:notification_setting_id, :channel)');
            $sql->bindValue(':notification_setting_id', $notification_setting_id);
            $sql->bindValue(':channel', $channel);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_notification_role_recipient
    # Purpose    : Delete all notification role recipient.
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_notification_user_account_recipient
    # Purpose    : Delete all notification user account recipient.
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_notification_channel
    # Purpose    : Delete all notification channel.
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_state
    # Purpose    : Delete all state.
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_zoom_api
    # Purpose    : Delete zoom API.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_zoom_api($zoom_api_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_zoom_api(:zoom_api_id)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_user_account
    # Purpose    : Delete user account.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_user_account($user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_user_account(:user_id)');
            $sql->bindValue(':user_id', $user_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_user_account_role
    # Purpose    : Delete all user account role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_user_account_role($user_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_user_account_role(:user_id)');
            $sql->bindValue(':user_id', $user_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_user_account_role
    # Purpose    : Delete user account role.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_user_account_role($user_id, $role_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_user_account_role(:user_id, :role_id)');
            $sql->bindValue(':user_id', $user_id);
            $sql->bindValue(':role_id', $role_id);
        
            if($sql->execute()){ 
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
            $sql = $this->db_connection->prepare('CALL delete_job_position(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_job_position_responsibility
    # Purpose    : Delete all job position responsibilities.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_job_position_responsibility($job_position_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_job_position_responsibility(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_job_position_requirement
    # Purpose    : Delete all job position requirements.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_job_position_requirement($job_position_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_job_position_requirement(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_job_position_qualification
    # Purpose    : Delete all job position qualifications.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_job_position_qualification($job_position_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_job_position_qualification(:job_position_id)');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_job_position_attachment
    # Purpose    : Delete all job position attachments.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_job_position_attachment($job_position_id, $username){
        if ($this->databaseConnection()) {
            $error = '';

            $sql = $this->db_connection->prepare('SELECT ATTACHMENT_ID FROM employee_job_position_attachment WHERE JOB_POSITION_ID = :job_position_id');
            $sql->bindValue(':job_position_id', $job_position_id);
        
            if($sql->execute()){
                while($row = $sql->fetch()){
                    $attachment_id = $row['ATTACHMENT_ID'];

                    $delete_job_position_attachment = $this->delete_job_position_attachment($attachment_id, $username);

                    if(!$delete_job_position_attachment){
                        $error = $delete_job_position_attachment;
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_job_position_responsibility
    # Purpose    : Delete job position responsibility.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_job_position_responsibility($responsibility_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_job_position_responsibility(:responsibility_id)');
            $sql->bindValue(':responsibility_id', $responsibility_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_job_position_requirement
    # Purpose    : Delete job position requirement.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_job_position_requirement($requirement_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_job_position_requirement(:requirement_id)');
            $sql->bindValue(':requirement_id', $requirement_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_job_position_qualification
    # Purpose    : Delete job position qualification.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_job_position_qualification($qualification_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_job_position_qualification(:qualification_id)');
            $sql->bindValue(':qualification_id', $qualification_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_job_position_attachment
    # Purpose    : Delete job position attachment.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_job_position_attachment($attachment_id, $username){
        if ($this->databaseConnection()) {
            $job_position_attachment_details = $this->get_job_position_attachment_details($attachment_id);
            $attachment = $job_position_attachment_details[0]['ATTACHMENT'] ?? null;

            $sql = $this->db_connection->prepare('CALL delete_job_position_attachment(:attachment_id)');
            $sql->bindValue(':attachment_id', $attachment_id);
        
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_id_type
    # Purpose    : Delete ID type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_id_type($id_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_id_type(:id_type_id)');
            $sql->bindValue(':id_type_id', $id_type_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_wage_type
    # Purpose    : Delete wage type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_wage_type($wage_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_wage_type(:wage_type_id)');
            $sql->bindValue(':wage_type_id', $wage_type_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_working_schedule
    # Purpose    : Delete working schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_working_schedule($working_schedule_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_working_schedule(:working_schedule_id)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_all_working_hours
    # Purpose    : Delete working schedule.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_all_working_hours($working_schedule_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_all_working_hours(:working_schedule_id)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : delete_working_schedule_type
    # Purpose    : Delete working schedule type.
    #
    # Returns    : Number/String
    #
    # -------------------------------------------------------------
    public function delete_working_schedule_type($working_schedule_type_id, $username){
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL delete_working_schedule_type(:working_schedule_type_id)');
            $sql->bindValue(':working_schedule_type_id', $working_schedule_type_id);
        
            if($sql->execute()){
                return true;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                        'DEFAULT_PAGE' => $row['DEFAULT_PAGE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG'],
                        'ORDER_SEQUENCE' => $row['ORDER_SEQUENCE']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_all_accessible_module_details
    # Purpose    : Gets the all accessible modules of the user.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_all_accessible_module_details($username){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_all_accessible_module_details(:username)');
            $sql->bindValue(':username', $username);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MODULE_ID' => $row['MODULE_ID'],
                        'MODULE_NAME' => $row['MODULE_NAME'],
                        'MODULE_VERSION' => $row['MODULE_VERSION'],
                        'MODULE_DESCRIPTION' => $row['MODULE_DESCRIPTION'],
                        'MODULE_ICON' => $row['MODULE_ICON'],
                        'MODULE_CATEGORY' => $row['MODULE_CATEGORY'],
                        'DEFAULT_PAGE' => $row['DEFAULT_PAGE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG'],
                        'ORDER_SEQUENCE' => $row['ORDER_SEQUENCE']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_interface_setting_details
    # Purpose    : Gets the interface setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_interface_setting_details($interface_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_interface_setting_details(:interface_setting_id)');
            $sql->bindValue(':interface_setting_id', $interface_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'INTERFACE_SETTING_NAME' => $row['INTERFACE_SETTING_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'STATUS' => $row['STATUS'],
                        'LOGIN_BACKGROUND' => $row['LOGIN_BACKGROUND'],
                        'LOGIN_LOGO' => $row['LOGIN_LOGO'],
                        'MENU_LOGO' => $row['MENU_LOGO'],
                        'FAVICON' => $row['FAVICON'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_activated_interface_setting_details
    # Purpose    : Gets the activated interface setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_activated_interface_setting_details(){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_activated_interface_setting_details()');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'INTERFACE_SETTING_ID' => $row['INTERFACE_SETTING_ID'],
                        'INTERFACE_SETTING_NAME' => $row['INTERFACE_SETTING_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'STATUS' => $row['STATUS'],
                        'LOGIN_BACKGROUND' => $row['LOGIN_BACKGROUND'],
                        'LOGIN_LOGO' => $row['LOGIN_LOGO'],
                        'MENU_LOGO' => $row['MENU_LOGO'],
                        'FAVICON' => $row['FAVICON'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_email_setting_details
    # Purpose    : Gets the email setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_email_setting_details($email_setting_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_email_setting_details(:email_setting_id)');
            $sql->bindValue(':email_setting_id', $email_setting_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMAIL_SETTING_NAME' => $row['EMAIL_SETTING_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'STATUS' => $row['STATUS'],
                        'MAIL_HOST' => $row['MAIL_HOST'],
                        'PORT' => $row['PORT'],
                        'SMTP_AUTH' => $row['SMTP_AUTH'],
                        'SMTP_AUTO_TLS' => $row['SMTP_AUTO_TLS'],
                        'MAIL_USERNAME' => $row['MAIL_USERNAME'],
                        'MAIL_PASSWORD' => $row['MAIL_PASSWORD'],
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_activated_email_setting_details
    # Purpose    : Gets the activated email setting details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_activated_email_setting_details(){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_activated_email_setting_details()');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'EMAIL_SETTING_ID' => $row['EMAIL_SETTING_ID'],
                        'EMAIL_SETTING_NAME' => $row['EMAIL_SETTING_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'STATUS' => $row['STATUS'],
                        'MAIL_HOST' => $row['MAIL_HOST'],
                        'PORT' => $row['PORT'],
                        'SMTP_AUTH' => $row['SMTP_AUTH'],
                        'SMTP_AUTO_TLS' => $row['SMTP_AUTO_TLS'],
                        'MAIL_USERNAME' => $row['MAIL_USERNAME'],
                        'MAIL_PASSWORD' => $row['MAIL_PASSWORD'],
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
                return $stmt->errorInfo()[2];
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
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'NOTIFICATION_TITLE' => $row['NOTIFICATION_TITLE'],
                        'NOTIFICATION_MESSAGE' => $row['NOTIFICATION_MESSAGE'],
                        'SYSTEM_LINK' => $row['SYSTEM_LINK'],
                        'EMAIL_LINK' => $row['EMAIL_LINK'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_zoom_api_details
    # Purpose    : Gets the zoom API details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_zoom_api_details($zoom_api_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_zoom_api_details(:zoom_api_id)');
            $sql->bindValue(':zoom_api_id', $zoom_api_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ZOOM_API_NAME' => $row['ZOOM_API_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'API_KEY' => $row['API_KEY'],
                        'API_SECRET' => $row['API_SECRET'],
                        'STATUS' => $row['STATUS'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_activated_zoom_api_details
    # Purpose    : Gets the activated zoom API details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_activated_zoom_api_details(){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_activated_zoom_api_details()');

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ZOOM_API_ID' => $row['ZOOM_API_ID'],
                        'ZOOM_API_NAME' => $row['ZOOM_API_NAME'],
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'API_KEY' => $row['API_KEY'],
                        'API_SECRET' => $row['API_SECRET'],
                        'STATUS' => $row['STATUS'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                        'STATUS' => $row['STATUS'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                        'DESCRIPTION' => $row['DESCRIPTION'],
                        'RECRUITMENT_STATUS' => $row['RECRUITMENT_STATUS'],
                        'DEPARTMENT' => $row['DEPARTMENT'],
                        'EXPECTED_NEW_EMPLOYEES' => $row['EXPECTED_NEW_EMPLOYEES'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_job_position_responsibility_details
    # Purpose    : Gets the job position responsibility details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_responsibility_details($responsibility_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_job_position_responsibility_details(:responsibility_id)');
            $sql->bindValue(':responsibility_id', $responsibility_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'JOB_POSITION_ID' => $row['JOB_POSITION_ID'],
                        'RESPONSIBILITY' => $row['RESPONSIBILITY'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_job_position_requirement_details
    # Purpose    : Gets the job position requirement details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_requirement_details($requirement_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_job_position_requirement_details(:requirement_id)');
            $sql->bindValue(':requirement_id', $requirement_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'JOB_POSITION_ID' => $row['JOB_POSITION_ID'],
                        'REQUIREMENT' => $row['REQUIREMENT'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_job_position_qualification_details
    # Purpose    : Gets the job position qualification details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_qualification_details($qualification_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_job_position_qualification_details(:qualification_id)');
            $sql->bindValue(':qualification_id', $qualification_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'JOB_POSITION_ID' => $row['JOB_POSITION_ID'],
                        'QUALIFICATION' => $row['QUALIFICATION'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_job_position_attachment_details
    # Purpose    : Gets the job position attachment details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_attachment_details($attachment_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_job_position_attachment_details(:attachment_id)');
            $sql->bindValue(':attachment_id', $attachment_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'JOB_POSITION_ID' => $row['JOB_POSITION_ID'],
                        'ATTACHMENT_NAME' => $row['ATTACHMENT_NAME'],
                        'ATTACHMENT' => $row['ATTACHMENT'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                        'WORK_LOCATION_ADDRESS' => $row['WORK_LOCATION_ADDRESS'],
                        'EMAIL' => $row['EMAIL'],
                        'TELEPHONE' => $row['TELEPHONE'],
                        'MOBILE' => $row['MOBILE'],
                        'LOCATION_NUMBER' => $row['LOCATION_NUMBER'],
                        'STATUS' => $row['STATUS'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_id_type_details
    # Purpose    : Gets the ID type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_id_type_details($id_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_id_type_details(:id_type_id)');
            $sql->bindValue(':id_type_id', $id_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ID_TYPE' => $row['ID_TYPE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_wage_type_details
    # Purpose    : Gets the wage type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_wage_type_details($wage_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_wage_type_details(:wage_type_id)');
            $sql->bindValue(':wage_type_id', $wage_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WAGE_TYPE' => $row['WAGE_TYPE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_working_schedule_details
    # Purpose    : Gets the working schedule details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_working_schedule_details($working_schedule_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_working_schedule_details(:working_schedule_id)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WORKING_SCHEDULE' => $row['WORKING_SCHEDULE'],
                        'WORKING_SCHEDULE_TYPE' => $row['WORKING_SCHEDULE_TYPE'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                        'WORKING_SCHEDULE_ID' => $row['WORKING_SCHEDULE_ID'],
                        'WORKING_HOURS' => $row['WORKING_HOURS'],
                        'WORKING_DATE' => $row['WORKING_DATE'],
                        'DAY_OF_WEEK' => $row['DAY_OF_WEEK'],
                        'DAY_PERIOD' => $row['DAY_PERIOD'],
                        'WORK_FROM' => $row['WORK_FROM'],
                        'WORK_TO' => $row['WORK_TO'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_working_schedule_type_details
    # Purpose    : Gets the working schedule type details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_working_schedule_type_details($working_schedule_type_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_working_schedule_type_details(:working_schedule_type_id)');
            $sql->bindValue(':working_schedule_type_id', $working_schedule_type_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'WORKING_SCHEDULE_TYPE' => $row['WORKING_SCHEDULE_TYPE'],
                        'WORKING_SCHEDULE_TYPE_CATEGORY' => $row['WORKING_SCHEDULE_TYPE_CATEGORY'],
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
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
                        'USERNAME' => $row['USERNAME'],
                        'BADGE_ID' => $row['BADGE_ID'],
                        'EMPLOYEE_IMAGE' => $row['EMPLOYEE_IMAGE'],
                        'EMPLOYEE_DIGITAL_SIGNATURE' => $row['EMPLOYEE_DIGITAL_SIGNATURE'],
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
                        'TRANSACTION_LOG_ID' => $row['TRANSACTION_LOG_ID'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_employee_personal_information_details
    # Purpose    : Gets the employee personal information details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_personal_information_details($employee_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_employee_personal_information_details(:employee_id)');
            $sql->bindValue(':employee_id', $employee_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'FILE_AS' => $row['FILE_AS'],
                        'FIRST_NAME' => $row['FIRST_NAME'],
                        'MIDDLE_NAME' => $row['MIDDLE_NAME'],
                        'LAST_NAME' => $row['LAST_NAME'],
                        'SUFFIX' => $row['SUFFIX'],
                        'NICKNAME' => $row['NICKNAME'],
                        'CIVIL_STATUS' => $row['CIVIL_STATUS'],
                        'NATIONALITY' => $row['NATIONALITY'],
                        'GENDER' => $row['GENDER'],
                        'BIRTHDAY' => $row['BIRTHDAY'],
                        'PLACE_OF_BIRTH' => $row['PLACE_OF_BIRTH'],
                        'BLOOD_TYPE' => $row['BLOOD_TYPE'],
                        'HEIGHT' => $row['HEIGHT'],
                        'WEIGHT' => $row['WEIGHT'],
                        'RELIGION' => $row['RELIGION'],
                        'RECORD_LOG' => $row['RECORD_LOG']
                    );
                }

                return $response;
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_file_as_format
    # Purpose    : Returns the file as name format
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_file_as_format($first_name, $middle_name = null, $last_name, $suffix = null){
        $suffix = $suffix ? $this->get_system_code_details(null, 'SUFFIX', $suffix)[0]['SYSTEM_DESCRIPTION'] : null;

        $name_parts = [$last_name, $first_name];
        if (!empty($middle_name)) {
            $name_parts[] = $middle_name;
        }
        if (!empty($suffix)) {
            $name_parts[] = $suffix;
        }

        return implode(', ', $name_parts);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_default_image
    # Purpose    : returns the default image.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_default_image($type) {
        $defaultImages = [
            'profile' => DEFAULT_AVATAR_IMAGE,
            'login background' => DEFAULT_BG_IMAGE,
            'login logo' => DEFAULT_LOGIN_LOGO_IMAGE,
            'menu logo' => DEFAULT_MENU_LOGO_IMAGE,
            'module icon' => DEFAULT_MODULE_ICON_IMAGE,
            'favicon' => DEFAULT_FAVICON_IMAGE,
            'company logo' => DEFAULT_COMPANY_LOGO_IMAGE,
        ];
    
        return $defaultImages[$type] ?? DEFAULT_PLACEHOLDER_IMAGE;
    }
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

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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
                return $stmt->errorInfo()[2];
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
        $status = ($stat === 1) ? 'True' : 'False';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_interface_setting_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_interface_setting_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Deactivated';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_email_setting_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_email_setting_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Deactivated';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Name       : get_zoom_api_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_zoom_api_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Deactivated';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
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
        $status = ($stat === 'Active') ? 'Active' : 'Deactivated';
        $button_class = ($stat === 'Active') ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
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
        $status = ($failed_login >= 5) ? 'Locked' : 'Unlocked';
        $button_class = ($failed_login >= 5) ? 'bg-danger' : 'bg-success';
        
        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
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
        $response = [];

        $start = new DateTime($date_1);
        $end = new DateTime($date_2);
        $diff = $start->diff($end);

        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        $years = $years . ' Year' . ($years === 1 ? '' : 's');
        $months = $months . ' Month' . ($months === 1 ? '' : 's');
        $days = $days . ' Day' . ($days === 1 ? '' : 's');

        $response[] = [
            'YEARS' => $years,
            'MONTHS' => $months,
            'DAYS' => $days
        ];

        return $response;

    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_department_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_department_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Archived';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_employee_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_employee_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Archived';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_job_position_recruitment_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_job_position_recruitment_status($stat){
        $status = ($stat === 1) ? 'Recruitment In Progress' : 'Not Recruiting';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-info';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_work_location_status
    # Purpose    : Returns the status, badge.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_work_location_status($stat){
        $status = ($stat === 1) ? 'Active' : 'Archived';
        $button_class = ($stat === 1) ? 'bg-success' : 'bg-danger';

        $response[] = array(
            'STATUS' => $status,
            'BADGE' => '<span class="badge ' . $button_class . '">' . $status . '</span>'
        );

        return $response;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_working_schedule_type_category
    # Purpose    : Gets the working schedule type category.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_working_schedule_type_category($working_schedule_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_working_schedule_type(:working_schedule_id)');
            $sql->bindValue(':working_schedule_id', $working_schedule_id);

            if($sql->execute()){
                $row = $sql->fetch();

                return $row['WORKING_SCHEDULE_TYPE_CATEGORY'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
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
        return $scrollable ? 'modal-dialog-scrollable' : null;
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
        $sizes = ['SM' => 'modal-sm', 'LG' => 'modal-lg', 'XL' => 'modal-xl'];

        return $sizes[$size] ?? null;
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
        return is_numeric($number) && $number > 0 && !empty($number) ? $number : '0';
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
        $types = [
            'default' => [$date, $system_date, 'format_date'],
            'empty' => [$date, null, 'format_date'],
            'attendance empty' => [$date, null, 'format_date'],
            'summary' => [$date, '--', 'format_date'],
            'na' => [$date, 'N/A', 'format_date'],
            'complete' => [$date, 'N/A', 'format_date'],
            'encoded' => [$date, 'N/A', 'format_date'],
            'date time' => [$date, 'N/A', 'format_date'],
            'default time' => [$date, $current_time, 'format_date']
        ];
        
        if (array_key_exists($type, $types)) {
            [$date_value, $default, $function] = $types[$type];
            return !empty($date_value) ? $this->$function($format, $date_value, $modify) : $default;
        }
        
        return null;
        
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
        $image = $image ?? '';
        
        return (empty($image) || !file_exists($image)) ? $this->get_default_image($type) : $image;
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

            return ($user_status == 'Active' && $failed_login < 5) ? true : false;
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

            if ($sql->execute()) {
                while ($row = $sql->fetch()) {
                    $role_id = $row['ROLE_ID'];
                    $total += $this->get_access_rights_count($role_id, $access_right_id, $access_type);
                }

                return $total;
            } else {
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_fixed_working_schedule_overlap
    # Purpose    : Checks if the fixed working hours overlaps with the other working hours.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_fixed_working_schedule_overlap($working_hours_id, $working_schedule_id, $day_of_week, $work_from, $work_to) {
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_fixed_working_schedule_overlap(:working_hours_id, :working_schedule_id, :day_of_week, :work_from, :work_to)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
            $sql->bindValue(':day_of_week', $day_of_week);
            $sql->bindValue(':work_from', $work_from);
            $sql->bindValue(':work_to', $work_to);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_flexible_working_schedule_overlap
    # Purpose    : Checks if the fixed working hours overlaps with the other working hours.
    #
    # Returns    : Number
    #
    # -------------------------------------------------------------
    public function check_flexible_working_schedule_overlap($working_hours_id, $working_schedule_id, $working_date, $work_from, $work_to) {
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('CALL check_flexible_working_schedule_overlap(:working_hours_id, :working_schedule_id, :working_date, :work_from, :work_to)');
            $sql->bindValue(':working_hours_id', $working_hours_id);
            $sql->bindValue(':working_schedule_id', $working_schedule_id);
            $sql->bindValue(':working_date', $working_date);
            $sql->bindValue(':work_from', $work_from);
            $sql->bindValue(':work_to', $work_to);

            if($sql->execute()){
                $row = $sql->fetch();

                return (int) $row['TOTAL'];
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function generate_file_name($length, $prefix = '') {
        $keys = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        $maxIndex = count($keys) - 1;

        $key = $prefix . array_reduce(array_fill(0, $length, null), function ($carry, $val) use ($keys, $maxIndex) {
            return $carry . $keys[random_int(0, $maxIndex)];
        }) . uniqid('', true);

        return $key;        
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_transaction_log_timeline
    # Purpose    : generates transaction log timeline.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_transaction_log_timeline($transaction_log_id) {
        if ($this->databaseConnection()) {
            $sql = $this->db_connection->prepare('SELECT USERNAME, LOG_TYPE, LOG_DATE, LOG FROM global_transaction_log WHERE TRANSACTION_LOG_ID = :transaction_log_id ORDER BY LOG_DATE DESC LIMIT 50');
            $sql->bindValue(':transaction_log_id', $transaction_log_id);

            if($sql->execute()){
                $count = $sql->rowCount();

                if($count > 0){
                    $timeline = '<ul class="verti-timeline list-unstyled">';

                    while($row = $sql->fetch()){
                        $username = $row['USERNAME'];
                        $log_type = $row['LOG_TYPE'];
                        $log = $row['LOG'];
                        $log_date = $this->check_date('empty', $row['LOG_DATE'], '', 'm/d/Y h:i:s a', '', '', '');

                        $user_account_details = $this->get_user_account_details($username);
                        $file_as = $user_account_details[0]['FILE_AS'];

                        $timeline .= '<li class="event-list">
                                        <div class="event-timeline-dot">
                                            <i class="bx bx-right-arrow-circle"></i>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <div>
                                                    <h6 class="font-size-14 mb-1">'. $log_type .'</h6>
                                                    <p class="text-muted">'. $file_as .' '. $log_date .'</p>
                                                    <p class="text-muted mb-0">'. $log .'</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>';

                    }

                    $timeline .= '</ul>';
                }
                else{
                    $timeline = '<div class="alert alert-info" role="alert">No transaction log found.</div>';
                }
               
                echo $timeline;
            }
            else{
                echo $sql->errorInfo()[2];
            }
        }
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

                        $option .= "<option value='". htmlspecialchars($system_code, ENT_QUOTES) ."'>". htmlspecialchars($system_description, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($role_id, ENT_QUOTES) ."'>". htmlspecialchars($role, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($module_id, ENT_QUOTES) ."'>". htmlspecialchars($module_name, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($company_id, ENT_QUOTES) ."'>". htmlspecialchars($company_name, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($country_id, ENT_QUOTES) ."'>". htmlspecialchars($country_name, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function generate_employee_options($generation_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_employee_options(:generation_type)');
            $sql->bindValue(':generation_type', $generation_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $employee_id = $row['EMPLOYEE_ID'];
                        $file_as = $row['FILE_AS'];
    
                        $option .= "<option value='". htmlspecialchars($employee_id, ENT_QUOTES) ."'>". htmlspecialchars($file_as, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function generate_department_options($generation_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_department_options(:generation_type)');
            $sql->bindValue(':generation_type', $generation_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $department_id = $row['DEPARTMENT_ID'];
                        $department = $row['DEPARTMENT'];
    
                        $option .= "<option value='". htmlspecialchars($department_id, ENT_QUOTES) ."'>". htmlspecialchars($department, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_job_position_options
    # Purpose    : Generates job position options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_job_position_options($generation_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_job_position_options(:generation_type)');
            $sql->bindValue(':generation_type', $generation_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $job_position_id = $row['JOB_POSITION_ID'];
                        $job_position = $row['JOB_POSITION'];
    
                        $option .= "<option value='". htmlspecialchars($job_position_id, ENT_QUOTES) ."'>". htmlspecialchars($job_position, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    public function generate_work_location_options($generation_type){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_work_location_options(:generation_type)');
            $sql->bindValue(':generation_type', $generation_type);

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $work_location_id = $row['WORK_LOCATION_ID'];
                        $work_location = $row['WORK_LOCATION'];
    
                        $option .= "<option value='". htmlspecialchars($work_location_id, ENT_QUOTES) ."'>". htmlspecialchars($work_location, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($departure_reason_id, ENT_QUOTES) ."'>". htmlspecialchars($departure_reason, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
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
    
                        $option .= "<option value='". htmlspecialchars($employee_type_id, ENT_QUOTES) ."'>". htmlspecialchars($employee_type, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_id_type_options
    # Purpose    : Generates ID type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_id_type_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_id_type_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $id_type_id = $row['ID_TYPE_ID'];
                        $id_type = $row['ID_TYPE'];
    
                        $option .= "<option value='". htmlspecialchars($id_type_id, ENT_QUOTES) ."'>". htmlspecialchars($id_type, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_wage_type_options
    # Purpose    : Generates wage type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_wage_type_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_wage_type_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $wage_type_id = $row['WAGE_TYPE_ID'];
                        $wage_type = $row['WAGE_TYPE'];
    
                        $option .= "<option value='". htmlspecialchars($wage_type_id, ENT_QUOTES) ."'>". htmlspecialchars($wage_type, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_working_schedule_options
    # Purpose    : Generates working schedule options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_working_schedule_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_working_schedule_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $working_schedule_id = $row['WORKING_SCHEDULE_ID'];
                        $working_schedule = $row['WORKING_SCHEDULE'];
    
                        $option .= "<option value='". htmlspecialchars($working_schedule_id, ENT_QUOTES) ."'>". htmlspecialchars($working_schedule, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_working_schedule_type_options
    # Purpose    : Generates working schedule type options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_working_schedule_type_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_working_schedule_type_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $working_schedule_type_id = $row['WORKING_SCHEDULE_TYPE_ID'];
                        $working_schedule_type = $row['WORKING_SCHEDULE_TYPE'];
    
                        $option .= "<option value='". htmlspecialchars($working_schedule_type_id, ENT_QUOTES) ."'>". htmlspecialchars($working_schedule_type, ENT_QUOTES) ."</option>";
                    }
    
                    return $option;
                }
            }
            else{
                return $stmt->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

}

?>