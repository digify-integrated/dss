<?php
    $menu_pages = [
        'job_positions' => ['id' => '33', 'url' => 'job-positions.php', 'label' => 'Job Positions', 'group' => 'Configurations'],
        'departments' => ['id' => '31', 'url' => 'departments.php', 'label' => 'Departments', 'group' => 'Configurations'],
        'departure_reasons' => ['id' => '37', 'url' => 'departure-reasons.php', 'label' => 'Departure Reasons', 'group' => 'Configurations'],
        'employee_types' => ['id' => '39', 'url' => 'employee-types.php', 'label' => 'Employee Types', 'group' => 'Configurations'],
        'wage_types' => ['id' => '41', 'url' => 'wage-types.php', 'label' => 'Wage Types', 'group' => 'Configurations'],
        'work_locations' => ['id' => '35', 'url' => 'work-locations.php', 'label' => 'Work Locations', 'group' => 'Configurations'],
        'working_schedules' => ['id' => '43', 'url' => 'working-schedules.php', 'label' => 'Working Schedules', 'group' => 'Configurations'],
    ];

    ksort($menu_pages);
    
    $menu = '';
    $configurations_menu = '';
    $standalone_menu = '';
    
    foreach ($menu_pages as $page_menu_id => $menu_page) {
        if ($api->check_role_access_rights($username, $menu_page['id'], 'page') > 0) {
            if ($menu_page['group'] == 'Configurations') {
                $configurations_menu .= '<a href="' . $menu_page['url'] . '" class="dropdown-item" key="t-' . $page_menu_id . '">' . $menu_page['label'] . '</a>';
            }
            else{
                $standalone_menu .= '<li class="nav-item dropdown"><a href="' . $menu_page['url'] . '" class="nav-link">' .$menu_page['label'] . '</a></li>';
            }
        }
    }

    if (!empty($standalone_menu)) {
        $menu .= $standalone_menu;
    }
    
    if (!empty($configurations_menu)) {
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="configurationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Configurations
                    </a>
                    <div class="dropdown-menu" aria-labelledby="configurationsDropdown">' . $configurations_menu . '</div>
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