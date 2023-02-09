(function($) {
    'use strict';

    $(function() {
        if($('#employees-datatable').length){
            initialize_employee_table('#employees-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_employee_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();

    const username = $('#username').text();
    const filter_status = $('#filter_status').val();
    const filter_department = $('#filter_department').val();
    const filter_job_position = $('#filter_job_position').val();
    const filter_work_location = $('#filter_work_location').val();
    const filter_employee_type = $('#filter_employee_type').val();
    const type = 'employees table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE_ID' },
        { 'data' : 'FILE_AS' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'JOB_POSITION' },
        { 'data' : 'WORK_LOCATION' },
        { 'data' : 'EMPLOYEE_STATUS' },
        { 'data' : 'EMPLOYEE_TYPE' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '12%', 'aTargets': 3 },
        { 'width': '12%', 'aTargets': 4 },
        { 'width': '15%', 'aTargets': 5 },
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%', 'aTargets': 7 },
        { 'width': '10%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_status' : filter_status, 'filter_department' : filter_department, 'filter_job_position' : filter_job_position, 'filter_work_location' : filter_work_location, 'filter_employee_type' : filter_employee_type},
            'dataSrc' : ''
        },
        'order': [[ 1, 'asc' ]],
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
        let employee_id = [];
        const transaction = 'delete multiple employee';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                employee_id.push(element.value);  
            }
        });

        if(employee_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Employees',
                text: 'Are you sure you want to delete these Employees?',
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
                                show_alert('Delete Multiple Employees Success', 'The Employees have been deleted.', 'success');
    
                                reload_datatable('#employees-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Delete Multiple Employees Error', 'Your employee is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                                show_alert('Delete Multiple Employees Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    
                    return false;
                }
            });
        }
        else{
            show_alert('Delete Multiple Employees', 'Please select the Employees you want to delete.', 'error');
        }
    });

    $(document).on('click','#unarchive-employee',function() {
        let employee_id = [];
        const transaction = 'unarchive multiple employee';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                employee_id.push(element.value);  
            }
        });

        if(employee_id.length > 0){
            Swal.fire({
                title: 'Unarchive Multiple Employees',
                text: 'Are you sure you want to unarchive these employees?',
                icon: 'info',
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
                                    show_alert('Unarchive Multiple Employees Success', 'The employees have been unarchived.', 'success');
                                }
                                else{
                                    show_alert('Unarchive Multiple Employees Error', 'The employee does not exist.', 'info');
                                }
    
                                reload_datatable('#employees-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Unarchive Multiple Employees Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                              show_alert('Unarchive Multiple Employees Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_alert('Unarchive Multiple Employees Error', 'Please select the employees you want to unarchive.', 'error');
        }
    });

    $(document).on('click','#archive-employee',function() {
        let employee_id = [];
        const transaction = 'archive multiple employee';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                employee_id.push(element.value);  
            }
        });

        if(employee_id.length > 0){
            Swal.fire({
                title: 'Archive Multiple Employees',
                text: 'Are you sure you want to archive these employees?',
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
                                    show_alert('Archive Multiple Employees Success', 'The employees have been archived.', 'success');
                                }
                                else{
                                    show_alert('Archive Multiple Employees Error', 'The employee does not exist.', 'info');
                                }
    
                                reload_datatable('#employees-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Archive Multiple Employees Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                              show_alert('Archive Multiple Employees Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_alert('Archive Multiple Employees Error', 'Please select the employees you want to archive.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_employee_table('#employees-datatable');
    });
}