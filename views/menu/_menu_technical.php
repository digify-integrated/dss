<?php
    $menu = '';

    $module_page = $api->check_role_access_rights($username, '1', 'page');

    if($module_page == 1){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-settings" role="button">
                        <i class="bx bx-cog me-2"></i><span key="t-settings">Settings</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-settings">';

        if($module_page == 1){
            $menu .= '<a href="modules.php" class="dropdown-item" key="t-modules">Modules</a>';
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