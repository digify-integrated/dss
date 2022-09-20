<?php
    $menu = '';

    
    $policy_page = $api->check_role_permissions($username, 1);
    $role_page = $api->check_role_permissions($username, 11);
    $user_account_page = $api->check_role_permissions($username, 17);
    $system_parameter_page = $api->check_role_permissions($username, 25);
    $system_code_page = $api->check_role_permissions($username, 30);
    $upload_setting_page = $api->check_role_permissions($username, 35);
    $company_page = $api->check_role_permissions($username, 40);
    $country_page = $api->check_role_permissions($username, 45);
    $state_page = $api->check_role_permissions($username, 50);
    $notification_setting_page = $api->check_role_permissions($username, 55);
    $general_setting_page = $api->check_role_permissions($username, 35);

    if($policy_page > 0 || $role_page > 0 || $user_account_page > 0 || $system_parameter_page > 0 || $system_code_page > 0 || $upload_setting_page > 0 || $company_page > 0 || $country_page > 0 || $state_page > 0 || $general_setting_page > 0 || $notification_setting_page > 0){
        if($general_setting_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="general-setting.php" class="nav-link">General Setting</a></li>';
        }

        if($company_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="company.php" class="nav-link">Company</a></li>';
        }

        if($policy_page > 0 || $role_page > 0 || $user_account_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-user-access" role="button">
                            <span key="t-user-access">User Access</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-user-access">';

                            if($policy_page > 0){
                                $menu .= '<a href="policy.php" class="dropdown-item" key="t-policy">Policy</a>';
                            }

                            if($role_page > 0){
                                $menu .= '<a href="role.php" class="dropdown-item" key="t-role">Role</a>';
                            }

                            if($user_account_page > 0){
                                $menu .= '<a href="user-account.php" class="dropdown-item" key="t-user-account">User Account</a>';
                            }

                $menu .= '</div>
                    </li>';
        }

        if($country_page > 0 || $state_page > 0 || $upload_setting_page > 0 || $system_code_page > 0 || $system_parameter_page > 0 || $notification_setting_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-configurations" role="button">
                            <span key="t-configurations">Configurations</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-configurations">';

                            if($country_page > 0){
                                $menu .= '<a href="country.php" class="dropdown-item" key="t-country">Country</a>';
                            }

                            if($notification_setting_page > 0){
                                $menu .= '<a href="notification-setting.php" class="dropdown-item" key="t-notification-setting">Notification Setting</a>';
                            }

                            if($state_page > 0){
                                $menu .= '<a href="state.php" class="dropdown-item" key="t-state">State</a>';
                            }

                            if($system_code_page > 0){
                                $menu .= '<a href="system-code.php" class="dropdown-item" key="t-system-code">System Code</a>';
                            }

                            if($system_parameter_page > 0){
                                $menu .= '<a href="system-parameter.php" class="dropdown-item" key="t-system-parameter">System Parameter</a>';
                            }

                            if($upload_setting_page > 0){
                                $menu .= '<a href="upload-setting.php" class="dropdown-item" key="t-upload-setting">Upload Setting</a>';
                            }

                $menu .= '</div>
                    </li>';
        }
    }
?>


<div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <?php echo $menu; ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>