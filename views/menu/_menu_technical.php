<?php
    $menu = '';

    $menu_pages = [
        'modules' => ['id' => '1', 'url' => 'modules.php', 'label' => 'Modules', 'group' => 'Technical'],
        'pages' => ['id' => '33', 'url' => 'pages.php', 'label' => 'Pages', 'group' => 'Technical'],
        'actions' => ['id' => '31', 'url' => 'actions.php', 'label' => 'Actions', 'group' => 'Technical'],
        'country' => ['id' => '35', 'url' => 'country.php', 'label' => 'Country', 'group' => 'Configurations'],
        'email_settings' => ['id' => '35', 'url' => 'email-settings.php', 'label' => 'Email Settings', 'group' => 'Configurations'],
        'system_parameters' => ['id' => '37', 'url' => 'system-parameters.php', 'label' => 'System Parameters', 'group' => 'Configurations'],
        'upload_settings' => ['id' => '41', 'url' => 'upload-settings.php', 'label' => 'Upload Settings', 'group' => 'Configurations'],
        'system_codes' => ['id' => '35', 'url' => 'system-codes.php', 'label' => 'System Codes', 'group' => 'Configurations'],
        'interface_settings' => ['id' => '35', 'url' => 'interface-settings.php', 'label' => 'Interface Settings', 'group' => 'Configurations'],
        'notification_settings' => ['id' => '35', 'url' => 'notification-settings.php', 'label' => 'Notification Settings', 'group' => 'Configurations'],
        'state' => ['id' => '35', 'url' => 'state.php', 'label' => 'State', 'group' => 'Configurations'],
        'zoom_api' => ['id' => '35', 'url' => 'zoom-api.php', 'label' => 'Zoom API', 'group' => 'Configurations'],
        'roles' => ['id' => '39', 'url' => 'roles.php', 'label' => 'Roles', 'group' => 'Administration'],
        'user_accounts' => ['id' => '35', 'url' => 'user-accounts.php', 'label' => 'User Accounts', 'group' => 'Administration'],
        'company' => ['id' => '35', 'url' => 'company.php', 'label' => 'Company', 'group' => null],
    ];

    ksort($menu_pages);
    
    $menu = '';
    $technical_menu = '';
    $configurations_menu = '';
    $administration_menu = '';
    $standalone_menu = '';
    
    foreach ($menu_pages as $page_menu_id => $menu_page) {
        if ($api->check_role_access_rights($username, $menu_page['id'], 'page') > 0) {
            if ($menu_page['group'] == 'Technical') {
                $technical_menu .= '<a href="' . $menu_page['url'] . '" class="dropdown-item" key="t-' . $page_menu_id . '">' . $menu_page['label'] . '</a>';
            } 
            else if ($menu_page['group'] == 'Configurations') {
                $configurations_menu .= '<a href="' . $menu_page['url'] . '" class="dropdown-item" key="t-' . $page_menu_id . '">' . $menu_page['label'] . '</a>';
            } 
            else if ($menu_page['group'] == 'Administration') {
                $administration_menu .= '<a href="' . $menu_page['url'] . '" class="dropdown-item" key="t-' . $page_menu_id . '">' . $menu_page['label'] . '</a>';
            }
            else{
                $standalone_menu .= '<li class="nav-item dropdown"><a href="' . $menu_page['url'] . '" class="nav-link">' .$menu_page['label'] . '</a></li>';
            }
        }
    }
    
    if (!empty($technical_menu)) {
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="technicalDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Technical
                    </a>
                    <div class="dropdown-menu" aria-labelledby="technicalDropdown">' . $technical_menu . '</div>
                  </li>';
    }
    
    if (!empty($configurations_menu)) {
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="configurationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Configurations
                    </a>
                    <div class="dropdown-menu" aria-labelledby="configurationsDropdown">' . $configurations_menu . '</div>
                  </li>';
    }
    
    if (!empty($administration_menu)) {
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="administrationDropdown" role="button" data-toggle="dropdown" aria-haspopup "true" aria-expanded="false">
                      Administration
                    </a>
                    <div class="dropdown-menu" aria-labelledby="administrationDropdown">' . $administration_menu . '</div>
                  </li>';
    }
    
    if (!empty($standalone_menu)) {
        $menu .= $standalone_menu;
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