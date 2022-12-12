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

    if($module_page > 0 || $pages_page > 0 || $action_page > 0 || $system_parameters_page > 0 || $upload_settings_page > 0 || $system_codes_page > 0 || $system_interface_page > 0 || $interface_settings_page > 0 || $email_settings_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-settings" role="button">
                        </i><span key="t-settings">Configurations</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-settings">';

        if($module_page > 0){
            $menu .= '<a href="modules.php" class="dropdown-item" key="t-modules">Modules</a>';
        }

        if($pages_page > 0){
            $menu .= '<a href="pages.php" class="dropdown-item" key="t-pages">Pages</a>';
        }

        if($action_page > 0){
            $menu .= '<a href="actions.php" class="dropdown-item" key="t-actions">Actions</a>';
        }

        if($system_parameters_page > 0){
            $menu .= '<a href="system-parameters.php" class="dropdown-item" key="t-actions">System Parameters</a>';
        }

        if($system_codes_page > 0){
            $menu .= '<a href="system-codes.php" class="dropdown-item" key="t-actions">System Codes</a>';
        }

        if($email_settings_page > 0){
            $menu .= '<a href="email-settings.php" class="dropdown-item" key="t-actions">Email Settings</a>';
        }

        if($interface_settings_page > 0){
            $menu .= '<a href="interface-settings.php" class="dropdown-item" key="t-actions">Interface Settings</a>';
        }

        if($upload_settings_page > 0){
            $menu .= '<a href="upload-settings.php" class="dropdown-item" key="t-actions">Upload Settings</a>';
        }

        $menu .= '</div>
        </li>';
    }

    if($roles_page > 0){
        $menu .= '<li class="nav-item dropdown"><a href="roles.php" class="nav-link">Roles</a></li>';
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