<?php
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    $api->backup_database('DB_'. date('m.d.Y'), null);

    /*echo $api->encrypt_data(1) . ' - 1<br/>';
    echo $api->encrypt_data(2) . ' - 2<br/>';
    echo $api->encrypt_data(3) . ' - 3<br/>';
    echo $api->encrypt_data(4) . ' - 4<br/>';
    echo $api->encrypt_data(5) . ' - 5<br/>';
    echo $api->encrypt_data(6) . ' - 6<br/>';

    echo 'page.php?module=' . $api->encrypt_data(1) . '&menu=' . $api->encrypt_data(2) . '<br/>';
    echo 'page.php?module=' . $api->encrypt_data(1) . '&menu=' . $api->encrypt_data(3) . '<br/>';
    echo 'page.php?module=' . $api->encrypt_data(1) . '&menu=' . $api->encrypt_data(4) . '<br/>';
    echo 'page.php?module=' . $api->encrypt_data(1) . '&menu=' . $api->encrypt_data(5) . '<br/>';
    echo 'page.php?module=' . $api->encrypt_data(1) . '&menu=' . $api->encrypt_data(6) . '<br/>';*/
?>