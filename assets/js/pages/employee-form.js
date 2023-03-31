(function($) {
    'use strict';

    $(function() {
        if($('#employee-id').length){
            display_details('employee details');

            if($('#employee-contact-information-datatable').length){
                initialize_employee_contact_information_table('#employee-contact-information-datatable');
            }
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

function initialize_employee_contact_information_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const employee_id = $('#employee-id').text();
    const type = 'employee contact information table';
    var settings;

    const column = [ 
        { 'data' : 'CONTACT_INFORMATION_TYPE' },
        { 'data' : 'EMAIL' },
        { 'data' : 'MOBILE' },
        { 'data' : 'TELEPHONE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'employee_id' : employee_id},
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

    $(document).on('click','#delete-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'delete employee';

        Swal.fire({
            title: 'Confirm Employee Deletion',
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
                        switch (response) {
                            case 'Deleted':
                                set_toastr('Employee Deleted', 'The employee has been deleted successfully.', 'success');
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

    $(document).on('click','#update-employee-image',function() {
        const employee_id = $(this).data('employee-id');

        sessionStorage.setItem('employee_id', employee_id);

        generate_modal('update employee image form', 'Employee Image', 'R' , '1', '1', 'form', 'update-employee-image-form', '0', username);
    });

    $(document).on('click','#upload-digital-signature',function() {
        const employee_id = $(this).data('employee-id');

        sessionStorage.setItem('employee_id', employee_id);

        generate_modal('upload employee digital signature form', 'Digital Signature', 'R' , '1', '1', 'form', 'upload-digital-signature-form', '0', username);
    });

    $(document).on('click','#update-digital-signature',function() {
        const employee_id = $(this).data('employee-id');

        sessionStorage.setItem('employee_id', employee_id);

        generate_modal('update employee digital signature form', 'Digital Signature', 'R' , '0', '1', 'form', 'update-digital-signature-form', '0', username);
    });

    $(document).on('click','#archive-employee',function() {
        const employee_id = $(this).data('employee-id');

        sessionStorage.setItem('employee_id', employee_id);

        generate_modal('archive employee form', 'Archive Employee', 'R' , '0', '1', 'form', 'archive-employee-form', '0', username);
    });

    $(document).on('click','#unarchive-employee',function() {
        const employee_id = $(this).data('employee-id');
        const transaction = 'unarchive employee';

        Swal.fire({
            title: 'Confirm Employee Unarchive',
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
                        switch (response) {
                            case 'Unarchived':
                                set_toastr('Employee Unarchived', 'The employee has been unarchived successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Employee Unarchived Error', response, 'error');
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

    $(document).on('click','#add-employee-contact-information',function() {
        generate_modal('employee contact information form', 'Contact Information', 'R' , '1', '1', 'form', 'employee-contact-information-form', '1', username);
    });

    $(document).on('click','.update-employee-contact-information',function() {
        const employee_contact_information_id = $(this).data('employee-contact-information-id');

        sessionStorage.setItem('employee_contact_information_id', employee_contact_information_id);

        generate_modal('employee contact information form', 'Contact Information', 'R' , '1', '1', 'form', 'employee-contact-information-form', '0', username);
    });

    $(document).on('click','.delete-employee-contact-information',function() {
        const employee_contact_information_id = $(this).data('employee-contact-information-id');
        const employee_id = $(this).data('employee-id');
        const transaction = 'delete employee contact information';

        Swal.fire({
            title: 'Confirm Contact Information Deletion',
            text: 'Are you sure you want to delete this contact information?',
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
                    data: {username : username, employee_contact_information_id : employee_contact_information_id, employee_id : employee_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Contact Information Deleted', 'The selected contact information has been deleted successfully.', 'success');
                                reload_datatable('#employee-contact-information-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Contact Information Deletion Error', 'The selected contact information does not exist or has already been deleted.', 'warning');
                                reload_datatable('#employee-contact-information-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Contact Information Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

}