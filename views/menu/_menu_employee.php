<?php
    $menu = '';

    $departments_page = $api->check_role_access_rights($username, '31', 'page');
    $job_positions_page = $api->check_role_access_rights($username, '33', 'page');

    if($departments_page > 0 || $job_positions_page > 0){
        $menu .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="configurations-menu" role="button">
                        </i><span key="t-configuration">Configurations</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="configurations-menu">';

        if($job_positions_page > 0){
            $menu .= '<a href="job-positions.php" class="dropdown-item" key="t-job-positions">Job Positions</a>';
        }

        if($departments_page > 0){
            $menu .= '<a href="departments.php" class="dropdown-item" key="t-departments">Departments</a>';
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