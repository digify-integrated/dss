<?php
    $menu = '';

    $module_page = $api->check_role_access_rights($username, '1', 'page');
    $pages_page = $api->check_role_access_rights($username, '3', 'page');
    $action_page = $api->check_role_access_rights($username, '5', 'page');
    $system_parameters_page = $api->check_role_access_rights($username, '7', 'page');
    $roles_page = $api->check_role_access_rights($username, '9', 'page');
    $upload_settings_page = $api->check_role_access_rights($username, '11', 'page');
    $system_codes_page = $api->check_role_access_rights($username, '13', 'page');
    $company_page = $api->check_role_access_rights($username, '15', 'page');
    $interface_settings_page = $api->check_role_access_rights($username, '17', 'page');
    $email_settings_page = $api->check_role_access_rights($username, '19', 'page');
    $notification_settings_page = $api->check_role_access_rights($username, '21', 'page');
    $country_page = $api->check_role_access_rights($username, '23', 'page');
    $state_page = $api->check_role_access_rights($username, '25', 'page');
    $zoom_api_page = $api->check_role_access_rights($username, '27', 'page');
    $user_accounts_page = $api->check_role_access_rights($username, '29', 'page');

    if($module_page > 0 || $pages_page > 0 || $action_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="technical-menu" role="button">
                        </i><span key="t-technical">Technical</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="technical-menu">';


        if($action_page > 0){
            $menu .= '<a href="actions.php" class="dropdown-item" key="t-actions">Actions</a>';
        }

        if($module_page > 0){
            $menu .= '<a href="modules.php" class="dropdown-item" key="t-modules">Modules</a>';
        }

        if($pages_page > 0){
            $menu .= '<a href="pages.php" class="dropdown-item" key="t-pages">Pages</a>';
        }
       
        $menu .= '</div>
        </li>';
    }

    if($country_page > 0 || $email_settings_page > 0 || $interface_settings_page > 0 || $notification_settings_page > 0 || $state_page > 0 || $system_codes_page > 0 || $system_parameters_page > 0 || $upload_settings_page > 0 || $zoom_api_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="configurations-menu" role="button">
                        </i><span key="t-configuration">Configurations</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="configurations-menu">';

        if($country_page > 0){
            $menu .= '<a href="country.php" class="dropdown-item" key="t-country">Country</a>';
        }

        if($email_settings_page > 0){
            $menu .= '<a href="email-settings.php" class="dropdown-item" key="t-email-settings">Email Settings</a>';
        }

        if($interface_settings_page > 0){
            $menu .= '<a href="interface-settings.php" class="dropdown-item" key="t-interface-settings">Interface Settings</a>';
        }

        if($notification_settings_page > 0){
            $menu .= '<a href="notification-settings.php" class="dropdown-item" key="t-notification-settings">Notification Settings</a>';
        }
            
        if($state_page > 0){
            $menu .= '<a href="state.php" class="dropdown-item" key="t-state">State</a>';
        }

        if($system_codes_page > 0){
            $menu .= '<a href="system-codes.php" class="dropdown-item" key="t-system-codes">System Codes</a>';
        }

        if($system_parameters_page > 0){
            $menu .= '<a href="system-parameters.php" class="dropdown-item" key="t-system-parameters">System Parameters</a>';
        }

        if($upload_settings_page > 0){
            $menu .= '<a href="upload-settings.php" class="dropdown-item" key="t-upload-settings">Upload Settings</a>';
        }
        
        if($zoom_api_page > 0){
            $menu .= '<a href="zoom-api.php" class="dropdown-item" key="t-zoom-account">Zoom API</a>';
        }
       
        $menu .= '</div>
        </li>';
    }

    if($roles_page > 0 || $user_accounts_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="administration-menu" role="button">
                        </i><span key="t-administration">Administration</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="administration-menu">';

        if($roles_page > 0){
            $menu .= '<a href="roles.php" class="dropdown-item" key="t-roles">Roles</a>';
        }

        if($user_accounts_page > 0){
            $menu .= '<a href="user-accounts.php" class="dropdown-item" key="t-user-accounts">User Accounts</a>';
        }
       
        $menu .= '</div>
        </li>';
    }

    if($company_page > 0){
        $menu .= '<li class="nav-item dropdown"><a href="company.php" class="nav-link">Company</a></li>';
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