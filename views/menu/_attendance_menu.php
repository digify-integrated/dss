<?php
    $menu = '';

    
    $attendance_setting_page = $api->check_role_permissions($username, 108);
    $time_in_time_out_page = $api->check_role_permissions($username, 111);
    $attendance_page = $api->check_role_permissions($username, 113);
    $my_attendance_page = $api->check_role_permissions($username, 118);
    $my_attendance_adjustment_page = $api->check_role_permissions($username, 122);
    $my_attendance_creation_page = $api->check_role_permissions($username, 129);
    $kiosk_mode_page = $api->check_role_permissions($username, 169);

    if($attendance_setting_page > 0 || $time_in_time_out_page > 0 || $attendance_page > 0 || $my_attendance_page > 0 || $my_attendance_adjustment_page > 0 || $my_attendance_creation_page > 0){
        if($time_in_time_out_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="time-in-time-out.php" class="nav-link">Time In / Time Out</a></li>';
        }

        if($kiosk_mode_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="kiosk-mode.php" class="nav-link">Kiosk Mode</a></li>';
        }

        if($my_attendance_page > 0 || $my_attendance_adjustment_page > 0 || $my_attendance_creation_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-user-access" role="button">
                            <span key="t-user-access">Attendance Record</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-user-access">';

                            if($my_attendance_page > 0){
                                $menu .= '<a href="my-attendance.php" class="dropdown-item" key="t-attendance">My Attendance</a>';
                            }

                            if($my_attendance_adjustment_page > 0){
                                $menu .= '<a href="my-attendance-adjustment.php" class="dropdown-item" key="t-my-attendance-adjustment">My Attendance Adjustment</a>';
                            }

                            if($my_attendance_creation_page > 0){
                                $menu .= '<a href="my-attendance-creation.php" class="dropdown-item" key="t-my-attendance-creation">My Attendance Creation</a>';
                            }

                $menu .= '</div>
                    </li>';
        }

        if($attendance_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="attendances.php" class="nav-link">Attendances</a></li>';
        }

        if($attendance_setting_page > 0){
            $menu .= '<li class="nav-item dropdown"><a href="attendance-setting.php" class="nav-link">Attendance Setting</a></li>';
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