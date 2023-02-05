(function($) {
    'use strict';

    $(function() {
        if($('#department-id').length){
            const transaction = 'department details';
            const department_id = $('#department-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {department_id : department_id, transaction : transaction},
                success: function(response) {
                    $('#department').val(response[0].DEPARTMENT);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);

                    document.getElementById('department_status').innerHTML = response[0].STATUS;

                    check_empty(response[0].PARENT_DEPARTMENT, '#parent_department', 'select');
                    check_empty(response[0].MANAGER, '#manager', 'select');
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }

                    /*if($('#department-employee-datatable').length){
                        initialize_department_employee_table('#department-employee-datatable');
                    }*/
                }
            });
        }

        $('#department-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit department';
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

                                show_alert_event('Insert Department Success', 'The department has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Department Success', 'The department has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Department Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Department Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('<span class="d-block d-sm-none"><i class="bx bx-save"></i></span><span class="d-none d-sm-block">Save</span>');
                    }
                });
                return false;
            },
            rules: {
                department: {
                    required: true
                }
            },
            messages: {
                department: {
                    required: 'Please enter the department',
                }
            },
            errorPlacement: function(label, element) {
                if((element.hasClass('select2') || element.hasClass('form-select2')) && element.next('.select2-container').length) {
                    label.insertAfter(element.next('.select2-container'));
                }
                else if(element.parent('.input-group').length){
                    label.insertAfter(element.parent());
                }
                else{
                    label.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).parent().addClass('has-danger');
                $(element).addClass('form-control-danger');
            },
            success: function(label,element) {
                $(element).parent().removeClass('has-danger')
                $(element).removeClass('form-control-danger')
                label.remove();
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

    $(document).on('click','#unarchive-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'unarchive department';

        Swal.fire({
            title: 'Unarchive Department',
            text: 'Are you sure you want to unarchive this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Unarchived' || response === 'Not Found'){
                            if(response === 'Unarchived'){
                                show_alert_event('Unarchive Department Success', 'The department has been unarchived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Unarchive Department Error', 'The department does not exist.', 'info', 'redirect', 'departments.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Unarchive Department Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Unarchive Department Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#archive-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'archive department';

        Swal.fire({
            title: 'Archive Department',
            text: 'Are you sure you want to archive this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Archived' || response === 'Not Found'){
                            if(response === 'Archived'){
                                show_alert_event('Archive Department Success', 'The department has been archived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Archive Department Error', 'The department does not exist.', 'info', 'redirect', 'departments.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Archive Department Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Archive Department Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'delete department';

        Swal.fire({
            title: 'Delete Department',
            text: 'Are you sure you want to delete this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Department Success', 'The department has been deleted.', 'success', 'redirect', 'departments.php');
                            }
                            else{
                                show_alert_event('Delete Department Error', 'The department does not exist.', 'info', 'redirect', 'departments.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Department Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Department Error', response, 'error');
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
                window.location.href = 'departments.php';
                return false;
            }
        });
    });

}