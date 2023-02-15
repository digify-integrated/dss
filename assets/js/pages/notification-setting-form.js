(function($) {
    'use strict';

    $(function() {
        if($('#notification-setting-id').length){
            display_details();

            if($('#notification-role-recipients-datatable').length){
                initialize_notification_role_recipient_table('#notification-role-recipients-datatable');
            }

            if($('#notification-user-account-recipients-datatable').length){
                initialize_notification_user_account_recipient_table('#notification-user-account-recipients-datatable');
            }

            if($('#notification-channel-datatable').length){
                initialize_notification_channel_table('#notification-channel-datatable');
            }
        }

        $('#notification-setting-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit notification setting';
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
                        if(response[0]['RESPONSE'] === 'Inserted'){
                            window.location = window.location.href + '?id=' + response[0]['NOTIFICATION_SETTING_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details();
                            reset_form();
                            
                            show_toastr('Update Successful', 'The notification setting has been updated successfully.', 'success');
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Transaction Error', response, 'error');
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
                notification_setting: {
                    required: true
                },
                notification_title: {
                    required: true
                },
                description: {
                    required: true
                },
                notification_message: {
                    required: true
                },
            },
            messages: {
                notification_setting: {
                    required: 'Please enter the notification setting',
                },
                notification_title: {
                    required: 'Please enter the notification title',
                },
                description: {
                    required: 'Please enter the description',
                },
                notification_message: {
                    required: 'Please enter the notification message',
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
    const transaction = 'notification setting details';
    const notification_setting_id = $('#notification-setting-id').text();

    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'JSON',
        data: {notification_setting_id : notification_setting_id, transaction : transaction},
        success: function(response) {
            $('#notification_setting').val(response[0].NOTIFICATION_SETTING);
            $('#notification_title').val(response[0].NOTIFICATION_TITLE);
            $('#system_link').val(response[0].SYSTEM_LINK);
            $('#description').val(response[0].DESCRIPTION);
            $('#notification_message').val(response[0].NOTIFICATION_MESSAGE);
            $('#email_link').val(response[0].EMAIL_LINK);

            $('#notification_setting_label').text(response[0].NOTIFICATION_SETTING);
            $('#notification_title_label').text(response[0].NOTIFICATION_TITLE);
            $('#system_link_label').text(response[0].SYSTEM_LINK);
            $('#description_label').text(response[0].DESCRIPTION);
            $('#notification_message_label').text(response[0].NOTIFICATION_MESSAGE);
            $('#email_link_label').text(response[0].EMAIL_LINK);
        },
        complete: function(){
            generate_transaction_logs();
        }
    });
}

function initialize_notification_role_recipient_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification role recipient table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

function initialize_notification_user_account_recipient_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification user account recipient table';
    var settings;

    const column = [ 
        { 'data' : 'USERNAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

function initialize_notification_channel_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification channel table';
    var settings;

    const column = [ 
        { 'data' : 'CHANNEL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

function initialize_notification_role_recipient_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification role recipient assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

function initialize_notification_user_account_recipient_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification user account recipient assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'USERNAME' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

function initialize_notification_channel_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'notification channel assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CHANNEL' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'notification_setting_id' : notification_setting_id},
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

    $(document).on('click','#delete-notification-setting',function() {
        const notification_setting_id = $(this).data('notification-setting-id');
        const transaction = 'delete notification setting';

        Swal.fire({
            title: 'Delete Notification Setting',
            text: 'Are you sure you want to delete this notification setting?',
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
                    data: {username : username, notification_setting_id : notification_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted'){
                            window.location = 'notification-settings.php';
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Notification Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-notification-role-recipient',function() {
        generate_modal('notification role recipient form', 'Notification Role Recipient', 'LG' , '1', '1', 'form', 'notification-role-recipient-form', '1', username);
    });

    $(document).on('click','#add-notification-user-account-recipient',function() {
        generate_modal('notification user account recipient form', 'Notification User Account Recipient', 'LG' , '1', '1', 'form', 'notification-user-account-recipient-form', '1', username);
    });

    $(document).on('click','#add-notification-channel',function() {
        generate_modal('notification channel form', 'Notification Channel', 'LG' , '1', '1', 'form', 'notification-channel-form', '1', username);
    });

    $(document).on('click','.delete-notification-role-recipient',function() {
        const role_id = $(this).data('role-id');
        const notification_setting_id = $(this).data('notification-setting-id');
        const transaction = 'delete notification role recipient';

        Swal.fire({
            title: 'Delete Notification Role Recipient',
            text: 'Are you sure you want to delete this notification role recipient?',
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
                    data: {username : username, role_id : role_id, notification_setting_id : notification_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Notification Role Recipient Successful', 'The notification role recipient has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Notification Role Recipient Error', 'The notification role recipient does not exist.', 'warning');
                            }

                            reload_datatable('#notification-role-recipients-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Notification Role Recipient Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','.delete-notification-user-account-recipient',function() {
        const user_id = $(this).data('user-id');
        const notification_setting_id = $(this).data('notification-setting-id');
        const transaction = 'delete notification user account recipient';

        Swal.fire({
            title: 'Delete Notification User Account Recipient',
            text: 'Are you sure you want to delete this notification user account recipient?',
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
                    data: {username : username, user_id : user_id, notification_setting_id : notification_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Notification User Account Successful', 'The notification user account has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Notification User Account Error', 'The notification user account does not exist.', 'warning');
                            }

                            reload_datatable('#notification-user-account-recipients-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Notification User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','.delete-notification-channel',function() {
        const channel = $(this).data('channel');
        const notification_setting_id = $(this).data('notification-setting-id');
        const transaction = 'delete notification channel';

        Swal.fire({
            title: 'Delete Notification Channel',
            text: 'Are you sure you want to delete this notification channel?',
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
                    data: {username : username, channel : channel, notification_setting_id : notification_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Notification Channel Successful', 'The notification channel has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Notification Channel Error', 'The notification channel does not exist.', 'warning');
                            }

                            reload_datatable('#notification-channel-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Notification Channel Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
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
                window.location = 'notification-settings.php';
                return false;
            }
        });
    });
}