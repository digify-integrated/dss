<?php
    $menu = '';

    
    $department_page = $api->check_role_permissions($username, 70);
    $job_position_page = $api->check_role_permissions($username, 75);
    $work_location_page = $api->check_role_permissions($username, 80);
    $departure_reason_page = $api->check_role_permissions($username, 85);
    $employee_page = $api->check_role_permissions($username, 90);
    $employee_type_page = $api->check_role_permissions($username, 97);
    $working_hours_page = $api->check_role_permissions($username, 102);

    if($department_page > 0 || $job_position_page > 0 || $work_location_page > 0 || $departure_reason_page > 0 || $employee_page > 0 || $employee_type_page > 0 || $working_hours_page > 0){
        if($employee_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="employee.php" class="nav-link">Employee</a></li>';
        }

        if($working_hours_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="working-hours.php" class="nav-link">Working Hours</a></li>';
        }

        if($department_page > 0 || $job_position_page > 0 || $work_location_page > 0 || $departure_reason_page > 0 || $employee_type_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-configurations" role="button">
                            <span key="t-configurations">Configurations</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-configurations">';

                            if($department_page > 0){
                                $menu .= '<a href="department.php" class="dropdown-item" key="t-department">Department</a>';
                            }

                            if($departure_reason_page > 0){
                                $menu .= '<a href="departure-reason.php" class="dropdown-item" key="t-departure-reason">Departure Reason</a>';
                            }

                            if($employee_type_page > 0){
                                $menu .= '<a href="employee-type.php" class="dropdown-item" key="t-employee-type">Employee Type</a>';
                            }

                            if($job_position_page > 0){
                                $menu .= '<a href="job-position.php" class="dropdown-item" key="t-job-position">Job Position</a>';
                            }

                            if($work_location_page > 0){
                                $menu .= '<a href="work-location.php" class="dropdown-item" key="t-work-location">Work Location</a>';
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