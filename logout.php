<?php
session_start();

require('config/config.php');
require('classes/api.php');
$api = new Api;

if (!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
}

if (isset($_GET['logout'])) {
    $username = $_SESSION['username'];
    $user_account_details = $api->get_user_account_details($username);
    $transaction_log_id = $user_account_details[0]['TRANSACTION_LOG_ID'];

    $insert_transaction_log = $api->insert_transaction_log($transaction_log_id, $username, 'Log In', 'User ' . $username . ' logged out.');
                                        
    if($insert_transaction_log){
        session_destroy();
        session_unset();
    
        header('Location: index.php');
        exit();
    }
    else{
        echo $insert_transaction_log;
    }
}
else{
    header('Location: dashboard.php');
}
 
?>