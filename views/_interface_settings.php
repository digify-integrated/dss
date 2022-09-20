<?php
    $interface_settings_details = $api->get_interface_settings_details(1);
    
    $login_background = $api->check_image($interface_settings_details[0]['LOGIN_BACKGROUND'] ?? null, 'login background');
    $login_logo = $api->check_image($interface_settings_details[0]['LOGIN_LOGO'] ?? null, 'login logo');
    $menu_logo = $api->check_image($interface_settings_details[0]['MENU_LOGO'] ?? null, 'menu logo');
    $menu_icon = $api->check_image($interface_settings_details[0]['MENU_ICON'] ?? null, 'menu icon');
    $favicon = $api->check_image($interface_settings_details[0]['FAVICON'] ?? null, 'favicon');
?>