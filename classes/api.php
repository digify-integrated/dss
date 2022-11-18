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
    #   Delete methods
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
    # Name       : get_technical_menu_details
    # Purpose    : Gets the technical menu details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_technical_menu_details($menu_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_technical_menu_details(:menu_id)');
            $sql->bindValue(':menu_id', $menu_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MODULE_ID' => $row['MODULE_ID'],
                        'PARENT_MENU' => $row['PARENT_MENU'],
                        'MENU' => $row['MENU'],
                        'MENU_ICON' => $row['MENU_ICON'],
                        'MENU_WEB_ICON' => $row['MENU_WEB_ICON'],
                        'FULL_PATH' => $row['FULL_PATH'],
                        'IS_LINK' => $row['IS_LINK'],
                        'MENU_LINK' => $row['MENU_LINK'],
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
    # Name       : get_technical_plugin_details
    # Purpose    : Gets the technical plugin details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_technical_plugin_details($plugin_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_technical_plugin_details(:plugin_id)');
            $sql->bindValue(':plugin_id', $plugin_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'PLUGIN_NAME' => $row['PLUGIN_NAME'],
                        'CSS_CODE' => $row['CSS_CODE'],
                        'JAVSCRIPT_CODE' => $row['JAVSCRIPT_CODE'],
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
                        'MODULE_DESCRIPION' => $row['MODULE_DESCRIPION'],
                        'MODULE_ICON' => $row['MODULE_ICON'],
                        'MODULE_CATEGORY' => $row['MODULE_CATEGORY'],
                        'IS_INSTALLABLE' => $row['IS_INSTALLABLE'],
                        'IS_APPLICATION' => $row['IS_APPLICATION'],
                        'IS_INSTALLED' => $row['IS_INSTALLED'],
                        'INSTALLATION_DATE' => $row['INSTALLATION_DATE'],
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
    # Name       : get_menu_details
    # Purpose    : Gets the menu details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_menu_details($menu_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_menu_details(:menu_id)');
            $sql->bindValue(':menu_id', $menu_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'MODULE_ID' => $row['MODULE_ID'],
                        'PARENT_MENU' => $row['PARENT_MENU'],
                        'MENU' => $row['MENU'],
                        'MENU_ICON' => $row['MENU_ICON'],
                        'MENU_WEB_ICON' => $row['MENU_WEB_ICON'],
                        'FULL_PATH' => $row['FULL_PATH'],
                        'IS_LINK' => $row['IS_LINK'],
                        'MENU_LINK' => $row['MENU_LINK'],
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
    # Name       : get_action_details
    # Purpose    : Gets the action details.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_action_details($view_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_action_details(:view_id)');
            $sql->bindValue(':view_id', $view_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ACTION_NAME' => $row['ACTION_NAME'],
                        'ACTION_TYPE' => $row['ACTION_TYPE'],
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
    # Name       : get_view_action_details
    # Purpose    : Gets the actions assigned to views.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function get_view_action_details($view_id){
        if ($this->databaseConnection()) {
            $response = array();

            $sql = $this->db_connection->prepare('CALL get_view_action_details(:view_id)');
            $sql->bindValue(':view_id', $view_id);

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $response[] = array(
                        'ACTION_ID' => $row['ACTION_ID']
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
    # Name       : generate_menu
    # Purpose    : Generates menu generation.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_menu($module_id, $username){
        if ($this->databaseConnection()) {
            $menu = '';
            $menu .= $this->generate_multilevel_menu($module_id, $username);

           return $menu;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_multilevel_menu
    # Purpose    : Generates multi-level menu.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_multilevel_menu($module_id, $username, $parent_menu = null){
        if ($this->databaseConnection()) {     
            $menu = '';

            if(!empty($parent_menu)){
                $query = 'SELECT MENU_ID, MENU, PARENT_MENU, MENU_LINK FROM technical_menu WHERE MODULE_ID = :module_id AND PARENT_MENU = :parent_menu ORDER BY ORDER_SEQUENCE, MENU';
            }
            else{
                $query = 'SELECT MENU_ID, MENU, PARENT_MENU, MENU_LINK FROM technical_menu WHERE MODULE_ID = :module_id AND PARENT_MENU IS NULL ORDER BY ORDER_SEQUENCE, MENU';
            }

            $sql = $this->db_connection->prepare($query);
            $sql->bindValue(':module_id', $module_id);

            if(!empty($parent_menu)){
                $sql->bindValue(':parent_menu', $parent_menu);
            }

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $menu_id = $row['MENU_ID'];

                    $menu_access_right = $this->check_role_access_rights($username, $menu_id, 'menu');

                    if($menu_access_right > 0){
                        if(empty($row['PARENT_MENU']) && empty($row['MENU_LINK'])){
                            $menu .= '<li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="m-'. $menu_id .'" role="button">
                                            <span key="t-user-access">'. $row['MENU'] .'</span> <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="m-'. $menu_id .'">';
                        }
                        else if(!empty($row['PARENT_MENU']) && empty($row['MENU_LINK'])){
                            $menu .= '<div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="javascript: void(0);" id="m-'. $menu_id .'"
                                            role="button">
                                            <span key="t-email-templates">'. $row['MENU'] .'</span> <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="m-'. $menu_id .'">';
                        }
                        else if(empty($row['PARENT_MENU']) && !empty($row['MENU_LINK'])){
                            $menu .= '<li class="nav-item dropdown"><a href="'. $row['MENU_LINK'] .'" class="nav-link">'. $row['MENU'] .'</a></li>';
                        }
                        else{
                            $menu .= '<a href="'. $row['MENU_LINK'] .'" class="dropdown-item" key="m-'. $menu_id .'">'. $row['MENU'] .'</a>';
                        }

                        $menu .= $this->generate_multilevel_menu($module_id, $username, $row['MENU_ID']);

                        if(empty($row['PARENT_MENU']) && empty($row['MENU_LINK'])){
                            $menu .= '</div>';
                        }
                        else if(!empty($row['PARENT_MENU']) && empty($row['MENU_LINK'])){
                            $menu .= '</div></div>';
                        }
                    }
                }

                return $menu;
            }
            else{
                return $sql->errorInfo()[2];
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_technical_view
    # Purpose    : Generates technical view.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function generate_technical_view($menu_id, $view = null, $username){
        if ($this->databaseConnection()) {
            $response = array();
            $generated_view = '';
            $generated_css_code = '';
            $generated_javascript_code = '';

            if(!empty($view)){
                $query = 'SELECT VIEW_ID, ARCHITECTURE, HAS_FILTER, HAS_ACTION, CSS_CODE, JAVASCRIPT_CODE FROM technical_view WHERE VIEW_ID IN (:view) ORDER BY ORDER_SEQUENCE, VIEW_NAME';
            }
            else{
                $query = 'SELECT VIEW_ID, ARCHITECTURE, HAS_FILTER, HAS_ACTION, CSS_CODE, JAVASCRIPT_CODE FROM technical_view WHERE VIEW_ID IN (SELECT VIEW_ID FROM technical_menu_view WHERE MENU_ID = :menu_id) ORDER BY ORDER_SEQUENCE, VIEW_NAME';
            }

            $sql = $this->db_connection->prepare($query);

            if(!empty($view)){
                $sql->bindValue(':view', $view);
            }
            else{
                $sql->bindValue(':menu_id', $menu_id);
            }

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $view_id = $row['VIEW_ID'];
                    $architecture = $row['ARCHITECTURE'];
                    $has_filter = $row['HAS_FILTER'];
                    $has_action = $row['HAS_ACTION'];

                    if($has_filter){
                        $architecture = str_replace('{filter_button}', '<button type="button" class="btn btn-info waves-effect btn-label waves-light" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas"><i class="bx bx-filter-alt label-icon"></i> Filter</button>', $architecture);
                        
                        if($view_id == '1'){
                            $filter_canvas = '<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-off-canvas" data-bs-backdrop="true" aria-labelledby="filter-off-canvas-label">
                                                <div class="offcanvas-header">
                                                    <h5 class="offcanvas-title" id="filter-off-canvas-label">Filter</h5>
                                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                </div>
                                                <div class="offcanvas-body">
                                                    <div class="mb-3">
                                                        <p class="text-muted">Module</p>
                                                        <select class="form-control filter-select2" id="filter_module">
                                                            <option value="">All Modules</option>
                                                            '. $this->generate_module_options() .'
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                    <p class="text-muted">Parent Menu</p>

                                                    <select class="form-control filter-select2" id="filter_parent_menu">
                                                        <option value="">All Parent Menus</option>
                                                        '. $this->generate_menu_options() .'
                                                    </select>
                                                </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary waves-effect waves-light" id="apply-filter" data-bs-toggle="offcanvas" data-bs-target="#filter-off-canvas" aria-controls="filter-off-canvas">Apply Filter</button>
                                                    </div>
                                                </div>
                                            </div>';
                        }
                        else{
                            $filter_canvas = '';
                        }

                        $architecture = str_replace('{filter}', $filter_canvas, $architecture);
                    }
                    else{
                        $architecture = str_replace('{filter_button}', null, $architecture);
                        $architecture = str_replace('{filter}', null, $architecture);
                    }

                    if($has_action){
                        $action_button = '';
                        $action_dropdown = '<div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu">
                                                    {action_button}
                                                </div>
                                            </div>';

                        $view_action_details = $this->get_view_action_details($view_id);

                        for($i = 0; $i < count($view_action_details); $i++) {
                            $action_id = $view_action_details[$i]['ACTION_ID'];

                            $actions_details = $this->get_action_details($action_id);
                            $action_name = $actions_details[0]['ACTION_NAME'];
                            $action_type = $actions_details[0]['ACTION_TYPE'];

                            $action_access_right = $this->check_role_access_rights($username, $action_id, 'action');

                            if($action_access_right > 0){
                                $action_button .= ' <a class="dropdown-item action-button" href="javascript: void(0);" data-action-type="'. $action_type .'">'. $action_name .'</a>';
                            }
                        }

                        if(!empty($action_button)){
                            $action_dropdown = str_replace('{action_button}', $action_button, $action_dropdown);
                            $architecture = str_replace('{action}', $action_dropdown, $architecture);
                        }
                        else{
                            $action_dropdown = '';
                        }
                    }
                    else{
                        $architecture = str_replace('{action}', null, $architecture);
                    }

                    $generated_view .= $architecture;
                    $generated_css_code .= $row['CSS_CODE'];
                    $generated_javascript_code .= $row['JAVASCRIPT_CODE'];
                }

                $response[] = array(
                    'VIEW' => $generated_view,
                    'CSS' => $generated_css_code,
                    'JAVASCRIPT' => $generated_javascript_code
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
    # Name       : generate_technical_view_plugins
    # Purpose    : Generates technical view plugins.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function generate_technical_view_plugins($menu_id, $views = null){
        if ($this->databaseConnection()) {
            $response = array();
            $generated_css_code = '';
            $generated_javascript_code = '';

            if(!empty($views)){
                $query = 'SELECT DISTINCT(PLUGIN_ID) AS PLUGIN_ID FROM technical_view_plugin WHERE VIEW_ID IN (:views)';
            }
            else{
                $query = 'SELECT DISTINCT(PLUGIN_ID) AS PLUGIN_ID FROM technical_view_plugin WHERE VIEW_ID IN (SELECT VIEW_ID FROM technical_menu_view WHERE MENU_ID = :menu_id)';
            }

            $sql = $this->db_connection->prepare($query);

            if(!empty($views)){
                $sql->bindValue(':views', $views);
            }
            else{
                $sql->bindValue(':menu_id', $menu_id);
            }

            if($sql->execute()){
                while($row = $sql->fetch()){
                    $plugin_id = $row['PLUGIN_ID'];
                    
                    $get_technical_plugin_details = $this->get_technical_plugin_details($plugin_id);
                    $generated_css_code .= $get_technical_plugin_details[0]['CSS_CODE'];
                    $generated_javascript_code .= $get_technical_plugin_details[0]['JAVSCRIPT_CODE'];

                }

                $response[] = array(
                    'CSS' => $generated_css_code,
                    'JAVASCRIPT' => $generated_javascript_code
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
    #   Generate options methods
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

    # -------------------------------------------------------------
    #
    # Name       : generate_menu_options
    # Purpose    : Generates menu options of dropdown.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_menu_options(){
        if ($this->databaseConnection()) {
            $option = '';
            
            $sql = $this->db_connection->prepare('CALL generate_menu_options()');

            if($sql->execute()){
                $count = $sql->rowCount();
        
                if($count > 0){
                    while($row = $sql->fetch()){
                        $menu_id = $row['MENU_ID'];
                        $menu = $row['MENU'];
    
                        $option .= "<option value='". $menu_id ."'>". $menu ."</option>";
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