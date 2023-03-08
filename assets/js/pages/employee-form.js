(function($) {
    'use strict';

    $(function() {
        if($('#employee-id').length){
            display_details();
        }

        $('#employee-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit employee';
                const username = $('#username').text();

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction,
                    dataType: 'JSON',
                    beforeSend: function(){
                        document.getElementById('submit-data').disabled = true;
                        $('#submit-data').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        switch (response[0]['RESPONSE']) {
                            case 'Inserted':
                                set_toastr('Employee Inserted', 'The employee has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['EMPLOYEE_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Employee Updated', 'The employee has been updated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Transaction Error', response, 'error');
                                break;
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('<span class="d-block d-sm-none"><i class="bx bx-save"></i></span><span class="d-none d-sm-block">Save</span>');
                    }
                });
                return false;
            },
            ignore: [],
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                department: {
                    required: true
                },
                job_position: {
                    required: true
                },
                company: {
                    required: true
                },
                badge_id: {
                    required: true
                },
                work_location: {
                    required: true
                },
                work_schedule: {
                    required: true
                },
                birthday: {
                    required: true
                },
                gender: {
                    required: true
                },
                employee_type: {
                    required: true
                },
                onboard_date: {
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: 'Please enter the first name',
                },
                last_name: {
                    required: 'Please enter the last name',
                },
                department: {
                    required: 'Please choose the department',
                },
                job_position: {
                    required: 'Please choose the job position',
                },
                company: {
                    required: 'Please choose the company',
                },
                badge_id: {
                    required: 'Please enter the badge id',
                },
                work_location: {
                    required: 'Please choose the work location',
                },
                work_schedule: {
                    required: 'Please choose the work schedule',
                },
                birthday: {
                    required: 'Please choose the birthday',
                },
                gender: {
                    required: 'Please choose the gender',
                },
                employee_type: {
                    required: 'Please choose the employee type',
                },
                onboard_date: {
                    required: 'Please choose the onboard date',
                },
            },
            errorPlacement: function(label) {
                show_toastr('Form Validation', label.text(), 'error');
            },
            highlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next().find('.select2-selection').addClass('is-invalid');
                } 
                else {
                    $(element).addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next().find('.select2-selection').removeClass('is-invalid');
                }
                else {
                    $(element).removeClass('is-invalid');
                }
            }
        });

        initialize_click_events();
    });
})(jQuery);

function display_details(){
    const transaction = 'employee details';
    const employee_id = $('#employee-id').text();

    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'JSON',
        data: {employee_id : employee_id, transaction : transaction},
        success: function(response) {
            $('#employee').val(response[0].DEPARTMENT);

            document.getElementById('employee_status').innerHTML = response[0].STATUS;

            check_empty(response[0].PARENT_DEPARTMENT, '#parent_employee', 'select');
            check_empty(response[0].MANAGER, '#manager', 'select');
        },
        complete: function(){
            generate_transaction_logs();
        }
    });
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'delete employee';

        Swal.fire({
            title: 'Confirm Employee Deactivation',
            text: 'Are you sure you want to deactivate this employee?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, employee_id : employee_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'employees.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Employee Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('employees.php');
    });

}