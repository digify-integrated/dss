(function($) {
    'use strict';

    $(function() {
        if($('#work-location-id').length){
            var transaction = 'work location details';
            var work_location_id = $('#work-location-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {work_location_id : work_location_id, transaction : transaction},
                success: function(response) {
                    $('#work_location').val(response[0].WORK_LOCATION);
                    $('#work_location_address').val(response[0].WORK_LOCATION_ADDRESS);
                    $('#email').val(response[0].EMAIL);
                    $('#telephone').val(response[0].TELEPHONE);
                    $('#mobile').val(response[0].MOBILE);
                    $('#location_number').val(response[0].LOCATION_NUMBER);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);

                    document.getElementById('work_location_status').innerHTML = response[0].STATUS;
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }

                    /*if($('#work-location-employee-datatable').length){
                        initialize_work_location_employee_table('#work-location-employee-datatable');
                    }*/
                }
            });
        }

        $('#work-location-form').validate({
            submitHandler: function (form) {
                var transaction = 'submit work location';
                var username = $('#username').text();

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
                                var redirect_link = window.location.href + '?id=' + response[0]['WORK_LOCATION_ID'];

                                show_alert_event('Insert Work Location Success', 'The work location has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Work Location Success', 'The work location has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Work Location Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Work Location Error', response, 'error');
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
                work_location: {
                    required: true
                },
                work_location_address: {
                    required: true
                },
                location_number: {
                    required: true
                }
            },
            messages: {
                work_location: {
                    required: 'Please enter the work location',
                },
                work_location_address: {
                    required: 'Please enter the work location address',
                },
                location_number: {
                    required: 'Please enter the location number',
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
    var username = $('#username').text();
    var transaction_log_id = $('#transaction_log_id').val();
    var type = 'transaction log table';
    var settings;

    var column = [ 
        { 'data' : 'LOG_TYPE' },
        { 'data' : 'LOG' },
        { 'data' : 'LOG_DATE' },
        { 'data' : 'LOG_BY' }
    ];

    var column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
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
                'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            'order': [[ 2, 'desc' ]],
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
                'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
                'dataSrc' : ''
            },
            'order': [[ 2, 'desc' ]],
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

function initialize_work_location_employee_table(datatable_name, buttons = false, show_all = false){    
    var username = $('#username').text();
    var work_location_id = $('#work_location_id').val();
    var type = 'work location employee table';
    var settings;

    var column = [ 
        { 'data' : 'EMPLOYEE' }
    ];

    var column_definition = [
        { 'width': '100%', 'aTargets': 0 }
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
                'data': {'type' : type, 'username' : username, 'work_location_id' : work_location_id},
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
                'data': {'type' : type, 'username' : username, 'work_location_id' : work_location_id},
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

    $(document).on('click','#unarchive-work-location',function() {
        var work_location_id = $(this).data('work-location-id');
        var transaction = 'unarchive work location';

        Swal.fire({
            title: 'Unarchive Work Location',
            text: 'Are you sure you want to unarchive this work location?',
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
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Unarchived' || response === 'Not Found'){
                            if(response === 'Unarchived'){
                                show_alert_event('Unarchive Work Location Success', 'The work location has been unarchived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Unarchive Work Location Error', 'The work location does not exist.', 'info', 'redirect', 'work-locations.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Unarchive Work Location Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Unarchive Work Location Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#archive-work-location',function() {
        var work_location_id = $(this).data('work-location-id');
        var transaction = 'archive work location';

        Swal.fire({
            title: 'Archive Work Location',
            text: 'Are you sure you want to archive this work location?',
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
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Archived' || response === 'Not Found'){
                            if(response === 'Archived'){
                                show_alert_event('Archive Work Location Success', 'The work location has been archived.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Archive Work Location Error', 'The work location does not exist.', 'info', 'redirect', 'work-locations.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Archive Work Location Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Archive Work Location Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-work-location',function() {
        var work_location_id = $(this).data('work-location-id');
        var transaction = 'delete work location';

        Swal.fire({
            title: 'Delete Work Location',
            text: 'Are you sure you want to delete this work location?',
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
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Work Location Success', 'The work location has been deleted.', 'success', 'redirect', 'work-locations.php');
                            }
                            else{
                                show_alert_event('Delete Work Location Error', 'The work location does not exist.', 'info', 'redirect', 'work-locations.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Work Location Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Work Location Error', response, 'error');
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
                window.location.href = 'work-locations.php';
                return false;
            }
        });
    });

}