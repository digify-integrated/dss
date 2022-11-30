<?php
    $menu = '';

    $module_page = $api->check_role_access_rights($username, '1', 'page');
    $pages_page = $api->check_role_access_rights($username, '3', 'page');

    if($module_page > 0 || $pages_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-settings" role="button">
                        <i class="bx bx-cog me-2"></i><span key="t-settings">Settings</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-settings">';

        if($module_page > 0){
            $menu .= '<a href="modules.php" class="dropdown-item" key="t-modules">Modules</a>';
        }

        if($pages_page > 0){
            $menu .= '<a href="pages.php" class="dropdown-item" key="t-pages">Pages</a>';
        }

        $menu .= '</div>
        </li>';
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