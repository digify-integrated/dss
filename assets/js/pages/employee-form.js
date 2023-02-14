(function($) {
    'use strict';

    $(function() {
        if($('#employee-id').length){
            const transaction = 'employee details';
            const employee_id = $('#employee-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {employee_id : employee_id, transaction : transaction},
                success: function(response) {
                    $('#employee').val(response[0].DEPARTMENT);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);

                    document.getElementById('employee_status').innerHTML = response[0].STATUS;

                    check_empty(response[0].PARENT_DEPARTMENT, '#parent_employee', 'select');
                    check_empty(response[0].MANAGER, '#manager', 'select');
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            });
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
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['DEPARTMENT_ID'];

                                show_alert_event('Insert Employee Success', 'The employee has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Employee Success', 'The employee has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Employee Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Employee Error', response, 'error');
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
                toastr.error(label.text(), 'Form Submission Error', {
                    closeButton: false,
                    debug: false,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true,
                    showDuration: 300,
                    hideDuration: 1000,
                    timeOut: 3000,
                    extendedTimeOut: 3000,
                    showEasing: 'swing',
                    hideEasing: 'linear',
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut'
                });
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
                } else {
                    $(element).removeClass('is-invalid');
                }
            }
        });

        initialize_click_events();
    });
})(jQuery);

function initialize_transaction_log_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const transaction_log_id = $('#transaction_log_id').val();
    const type = 'transaction log table';
    var settings;

    const column = [ 
        { 'data' : 'LOG_TYPE' },
        { 'data' : 'LOG' },
        { 'data' : 'LOG_DATE' },
        { 'data' : 'LOG_BY' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
            'dataSrc' : ''
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'scrollY': false,
        'scrollX': true,
        'scrollCollapse': true,
        'fnDrawCallback': function( oSettings ) {
            readjust_datatable_column();
        },
        'aoColumnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': '<div class="spinner-border spinner-border-lg text-info" role="status"><span class="sr-only">Loading...</span></div>'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroy_datatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#unarchive-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'unarchive employee';

        Swal.fire({
            title: 'Unarchive Employee',
            text: 'Are you sure you want to unarchive this employee?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Unarchive',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, employee_id : employee_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Unarchived' || response === 'Not Found'){
                            if(response === 'Unarchived'){
                                show_alert_event('Unarchive Employee Success', 'The employee has been unarchived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Unarchive Employee Error', 'The employee does not exist.', 'info', 'redirect', 'employees.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Unarchive Employee Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Unarchive Employee Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#archive-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'archive employee';

        Swal.fire({
            title: 'Archive Employee',
            text: 'Are you sure you want to archive this employee?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Archive',
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
                        if(response === 'Archived' || response === 'Not Found'){
                            if(response === 'Archived'){
                                show_alert_event('Archive Employee Success', 'The employee has been archived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Archive Employee Error', 'The employee does not exist.', 'info', 'redirect', 'employees.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Archive Employee Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Archive Employee Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'delete employee';

        Swal.fire({
            title: 'Delete Employee',
            text: 'Are you sure you want to delete this employee?',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Employee Success', 'The employee has been deleted.', 'success', 'redirect', 'employees.php');
                            }
                            else{
                                show_alert_event('Delete Employee Error', 'The employee does not exist.', 'info', 'redirect', 'employees.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Employee Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Employee Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard',function() {
        Swal.fire({
            title: 'Discard Changes',
            text: 'Are you sure you want to discard the changes associated with this item? Once discarded the changes are permanently lost.',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Discard',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                window.location.href = 'employees.php';
                return false;
            }
        });
    });

}