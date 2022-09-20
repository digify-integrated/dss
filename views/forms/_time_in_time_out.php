<?php
    $employee_details = $api->get_employee_details($username);
    $employee_id = $employee_details[0]['EMPLOYEE_ID'] ?? null;
    $file_as = $employee_details[0]['FILE_AS'] ?? $username;
    $job_position = $employee_details[0]['JOB_POSITION'] ?? null;
    $employee_image = $employee_details[0]['EMPLOYEE_IMAGE'] ?? null;
    $working_hours = $employee_details[0]['WORKING_HOURS'] ?? null;

    $job_position_details = $api->get_job_position_details($job_position);
    $job_position_name = $job_position_details[0]['JOB_POSITION'] ?? null;

    if(empty($employee_image)){
        $employee_image = $api->check_image($employee_image, 'profile');
    }

    $recent_employee_attendance_details = $api->get_recent_employee_attendance_details($employee_id, date('Y-m-d'));
    $attendance_id = $recent_employee_attendance_details[0]['ATTENDANCE_ID'] ?? null;
    $time_in_details = $api->check_date('summary', $recent_employee_attendance_details[0]['TIME_IN'] ?? null, '', 'F d, Y h:i a', '', '', '');
    $time_out_details = $api->check_date('summary', $recent_employee_attendance_details[0]['TIME_OUT'] ?? null, '', 'F d, Y h:i a', '', '', '');

    if($time_in_time_out > 0 && !empty($working_hours)){
        $attendance_setting_details = $api->get_attendance_setting_details(1);
        $max_attendance = $attendance_setting_details[0]['MAX_ATTENDANCE'] ?? 1;
        $attendance_total_by_date = $api->get_attendance_total_by_date($employee_id, date('Y-m-d'));

        if($attendance_total_by_date < $max_attendance){
            if(empty($attendance_id)){
                $attendance_guide = 'Click to time in';

                $button = '<button type="button" class="btn btn-success waves-effect waves-light w-lg" id="time-in">
                                <i class="bx bx-log-in d-block font-size-16 mb-1"></i> Time In
                            </button>';

            }
            else{
                $attendance_guide = 'Click to time out';

                $button = '<button type="button" class="btn btn-warning waves-effect waves-light w-lg" data-attendance-id="'. $attendance_id .'" id="time-out">
                            <i class="bx bx-log-out d-block font-size-16 mb-1"></i> Time Out
                        </button>';
            }
        }
        else{
            $attendance_guide = '';

            $button = '';
        }
    }
    else{
        $attendance_guide = '';

        $button = '';
    }
?>

<div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary">
                                        <div class="row">
                                            <div class="col-12 p-4"></div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0"> 
                                        <div class="row">
                                            <div class="col-12 d-flex flex-row justify-content-center">
                                                <div class="auth-logo">
                                                    <div class="avatar-md profile-user-wid mb-4">
                                                        <span class="avatar-title rounded-circle bg-light">
                                                            <img src="<?php echo $employee_image; ?>" alt="" class="rounded-circle" height="60">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-4">
                                                        <h3 class="text-center"><?php echo $file_as; ?></h3>
                                                        <h4 class="text-center"><?php echo $job_position_name; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mb-4 text-center">
                                                        <p class="text-muted text-truncate mb-2">Time In</p>
                                                        <h5 class="mb-0"><?php echo $time_in_details; ?></h5>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-4 text-center">
                                                        <p class="text-muted text-truncate mb-2">Time Out</p>
                                                        <h5 class="mb-0"><?php echo $time_out_details; ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <div class="mb-4">
                                                        <?php echo $button; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <h5 class="text-center text-muted"><?php echo $attendance_guide; ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>