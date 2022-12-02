<?php
    $menu = '';

    $module_page = $api->check_role_access_rights($username, '1', 'page');
    $pages_page = $api->check_role_access_rights($username, '3', 'page');
    $action_page = $api->check_role_access_rights($username, '5', 'page');
    $system_parameters_page = $api->check_role_access_rights($username, '7', 'page');
    $roles_page = $api->check_role_access_rights($username, '9', 'page');

    if($module_page > 0 || $pages_page > 0 || $action_page > 0 || $system_parameters_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-settings" role="button">
                        <i class="bx bx-cog me-2"></i><span key="t-settings">Configurations</span> <div class="arrow-down"></div>
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

        $menu .= '</div>
        </li>';
    }

    if($roles_page > 0){
        $menu .= '<li class="nav-item dropdown"><a href="roles.php" class="nav-link">Roles</a></li>';
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