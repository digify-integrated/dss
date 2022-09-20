<?php
    $menu = '';
    
    $public_holiday_page = $api->check_role_permissions($username, 171);
    $leave_type_page = $api->check_role_permissions($username, 176);
    $leave_allocation_page = $api->check_role_permissions($username, 181);
    $my_leave_page = $api->check_role_permissions($username, 186);

    if($public_holiday_page > 0 || $leave_type_page > 0 || $leave_allocation_page > 0 || $my_leave_page > 0){
        if($my_leave_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-user-access" role="button">
                            <span key="t-user-access">Leave</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-user-access">';

                            if($my_leave_page > 0){
                                $menu .= '<a href="my-leave.php" class="dropdown-item" key="t-my-leave">My Leave</a>';
                            }

                $menu .= '</div>
                    </li>';
        }

        if($public_holiday_page > 0 || $leave_type_page > 0 || $leave_allocation_page > 0){
            $menu .= '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="javascript: void(0);" id="topnav-user-access" role="button">
                            <span key="t-user-access">Configurations</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-user-access">';

                            if($leave_allocation_page > 0){
                                $menu .= '<a href="leave-allocation.php" class="dropdown-item" key="t-leave-allocation">Leave Allocation</a>';
                            }

                            if($leave_type_page > 0){
                                $menu .= '<a href="leave-type.php" class="dropdown-item" key="t-leave-type">Leave Type</a>';
                            }

                            if($public_holiday_page > 0){
                                $menu .= '<a href="public-holiday.php" class="dropdown-item" key="t-public-holiday">Public Holiday</a>';
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