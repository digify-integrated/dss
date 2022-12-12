(function($) {
    'use strict';

    $(function() {
        if($('#interface-setting-id').length){
            var transaction = 'interface setting details';
            var interface_setting_id = $('#interface-setting-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {interface_setting_id : interface_setting_id, transaction : transaction},
                success: function(response) {
                    $('#interface_setting_name').val(response[0].INTERFACE_SETTING_NAME);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);
                    $('#description').val(response[0].DESCRIPTION);
                    
                    document.getElementById('login_background_image').innerHTML = response[0].LOGIN_BACKGROUND;
                    document.getElementById('login_logo_image').innerHTML = response[0].LOGIN_LOGO;
                    document.getElementById('menu_logo_image').innerHTML = response[0].MENU_LOGO;
                    document.getElementById('favicon_image').innerHTML = response[0].FAVICON;

                    $('#interface_setting_id').val(interface_setting_id);
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            });
        }

        $('#interface-setting-form').validate({
            submitHandler: function (form) {
                var transaction = 'submit interface setting';
                var username = $('#username').text();

                var formData = new FormData(form);
                formData.append('username', username);
                formData.append('transaction', transaction);

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        document.getElementById('submit-data').disabled = true;
                        $('#submit-data').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['INTERFACE_SETTING_ID'];

                                show_alert_event('Insert Interface Setting Success', 'The interface setting has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Interface Setting Success', 'The interface setting has been updated.', 'success', 'reload');
                            }
                        }
                        else{
                            show_alert('Interface Setting Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('Save');
                    }
                });
                return false;
            },
            rules: {
                interface_setting_name: {
                    required: true
                },
                description: {
                    required: true
                },
            },
            messages: {
                interface_setting_name: {
                    required: 'Please enter the interface setting name',
                },
                description: {
                    required: 'Please enter the description',
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

function initialize_click_events(){
    var username = $('#username').text();

    $(document).on('click','#activate-interface-setting',function() {
        var interface_setting_id = $(this).data('interface-setting-id');
        var transaction = 'activate interface setting';

        Swal.fire({
            title: 'Activate Interface Setting',
            text: 'Are you sure you want to activate this interface setting?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Activate',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated' || response === 'Not Found'){
                            if(response === 'Activated'){
                                show_alert_event('Activate Interface Setting', 'The interface setting has been activated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Activate Interface Setting', 'The interface setting does not exist.', 'info', 'redirect', 'interface-settings.php');
                            }
                        }
                        else{
                            show_alert('Activate Interface Setting', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-interface-setting',function() {
        var interface_setting_id = $(this).data('interface-setting-id');
        var transaction = 'deactivate interface setting';

        Swal.fire({
            title: 'Deactivate Interface Setting',
            text: 'Are you sure you want to deactivate this interface setting?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Deactivate',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated' || response === 'Not Found'){
                            if(response === 'Deactivated'){
                                show_alert_event('Deactivate Interface Setting', 'The interface setting has been deactivated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Deactivate Interface Setting', 'The interface setting does not exist.', 'info', 'redirect', 'interface-settings.php');
                            }
                        }
                        else{
                            show_alert('Deactivate Interface Setting', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-interface-setting',function() {
        var interface_setting_id = $(this).data('interface-setting-id');
        var transaction = 'delete interface setting';

        Swal.fire({
            title: 'Delete Interface Setting',
            text: 'Are you sure you want to delete this interface setting?',
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
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Interface Setting', 'The interface setting has been deleted.', 'success', 'redirect', 'interface-settings.php');
                            }
                            else{
                                show_alert_event('Delete Interface Setting', 'The interface setting does not exist.', 'info', 'redirect', 'interface-settings.php');
                            }
                        }
                        else{
                            show_alert('Delete Interface Setting', response, 'error');
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
                window.location.href = 'interface-settings.php';
                return false;
            }
        });
    });

}