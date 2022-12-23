(function($) {
    'use strict';

    $(function() {
        if($('#departments-datatable').length){
            initialize_department_table('#departments-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_department_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    var username = $('#username').text();
    var filter_status = $('#filter_status').val();
    var type = 'departments table';
    var settings;

    var column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DEPARTMENT_ID' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'STATUS' },
        { 'data' : 'MANAGER' },
        { 'data' : 'EMPLOYEES' },
        { 'data' : 'PARENT_DEPARTMENT' },
        { 'data' : 'VIEW' }
    ];

    var column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '24%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'aTargets': 5 },
        { 'width': '15%', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'filter_status' : filter_status},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
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
    }
    else{
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'filter_status' : filter_status},
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
    }

    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    var username = $('#username').text();

    $(document).on('click','#delete-department',function() {
        var department_id = [];
        var transaction = 'delete multiple department';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                department_id.push(this.value);  
            }
        });

        if(department_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Departments',
                text: 'Are you sure you want to delete these Departments?',
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
                                show_alert('Delete Multiple Departments Success', 'The Departments have been deleted.', 'success');
    
                                reload_datatable('#departments-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Delete Multiple Departments Error', 'Your department is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                                show_alert('Delete Multiple Departments Error', response, 'error');
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
            show_alert('Delete Multiple Departments', 'Please select the Departments you want to delete.', 'error');
        }
    });

    $(document).on('click','#unarchive-department',function() {
        var department_id = [];
        var transaction = 'unarchive multiple department';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                department_id.push(this.value);  
            }
        });

        if(department_id.length > 0){
            Swal.fire({
                title: 'Unarchive Multiple Departments',
                text: 'Are you sure you want to unarchive these departments?',
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
                        data: {username : username, department_id : department_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Unarchived' || response === 'Not Found'){
                                if(response === 'Unarchived'){
                                    show_alert('Unarchive Multiple Departments Success', 'The departments have been unarchived.', 'success');
                                }
                                else{
                                    show_alert('Unarchive Multiple Departments Error', 'The department does not exist.', 'info');
                                }
    
                                reload_datatable('#departments-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Unarchive Multiple Departments Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                              show_alert('Unarchive Multiple Departments Error', response, 'error');
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
            show_alert('Unarchive Multiple Departments Error', 'Please select the departments you want to unarchive.', 'error');
        }
    });

    $(document).on('click','#archive-department',function() {
        var department_id = [];
        var transaction = 'archive multiple department';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                department_id.push(this.value);  
            }
        });

        if(department_id.length > 0){
            Swal.fire({
                title: 'Archive Multiple Departments',
                text: 'Are you sure you want to archive these departments?',
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
                                    show_alert('Archive Multiple Departments Success', 'The departments have been archived.', 'success');
                                }
                                else{
                                    show_alert('Archive Multiple Departments Error', 'The department does not exist.', 'info');
                                }
    
                                reload_datatable('#departments-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Archive Multiple Departments Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                              show_alert('Archive Multiple Departments Error', response, 'error');
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
            show_alert('Archive Multiple Departments Error', 'Please select the departments you want to archive.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_department_table('#departments-datatable');
    });

}