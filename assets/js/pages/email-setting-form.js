(function($) {
    'use strict';

    $(function() {
        if($('#email-setting-id').length){
            const transaction = 'email setting details';
            const email_setting_id = $('#email-setting-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {email_setting_id : email_setting_id, transaction : transaction},
                success: function(response) {
                    $('#email_setting_name').val(response[0].EMAIL_SETTING_NAME);
                    $('#mail_host').val(response[0].MAIL_HOST);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);
                    $('#description').val(response[0].DESCRIPTION);
                    $('#mail_username').val(response[0].MAIL_USERNAME);
                    $('#mail_from_name').val(response[0].MAIL_FROM_NAME);
                    $('#port').val(response[0].PORT);
                    $('#mail_password').val(response[0].MAIL_PASSWORD);
                    $('#mail_from_email').val(response[0].MAIL_FROM_EMAIL);

                    document.getElementById('email_setting_status').innerHTML = response[0].STATUS;

                    check_empty(response[0].MAIL_ENCRYPTION, '#mail_encryption', 'select');
                    check_empty(response[0].SMTP_AUTH, '#smtp_auth', 'select');
                    check_empty(response[0].SMTP_AUTO_TLS, '#smtp_auto_tls', 'select');

                    $('#email_setting_id').val(email_setting_id);
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            });
        }

        $('#email-setting-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit email setting';
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
                                var redirect_link = window.location.href + '?id=' + response[0]['EMAIL_SETTING_ID'];

                                show_alert_event('Insert Email Setting Success', 'The email setting has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Email Setting Success', 'The email setting has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Email Setting Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Email Setting Error', response, 'error');
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
                email_setting_name: {
                    required: true
                },
                description: {
                    required: true
                },
                mail_host: {
                    required: true
                },
                mail_username: {
                    required: true
                },
                mail_encryption: {
                    required: true
                },
                mail_from_name: {
                    required: true
                },
                mail_password: {
                    required: true
                },
                mail_from_email: {
                    required: true
                }
            },
            messages: {
                email_setting_name: {
                    required: 'Please enter the email setting name',
                },
                description: {
                    required: 'Please enter the description',
                },
                mail_host: {
                    required: 'Please enter the mail host',
                },
                mail_username: {
                    required: 'Please enter the mail username',
                },
                mail_encryption: {
                    required: 'Please choose the mail encryption',
                },
                mail_from_name: {
                    required: 'Please enter the mail from name',
                },
                mail_password: {
                    required: 'Please enter the mail password',
                },
                mail_from_email: {
                    required: 'Please enter the mail from email',
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
    const username = $('#username').text();

    $(document).on('click','#activate-email-setting',function() {
        const email_setting_id = $(this).data('email-setting-id');
        const transaction = 'activate email setting';

        Swal.fire({
            title: 'Activate Email Setting',
            text: 'Are you sure you want to activate this email setting?',
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
                    data: {username : username, email_setting_id : email_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated' || response === 'Not Found'){
                            if(response === 'Activated'){
                                show_alert_event('Activate Email Setting Success', 'The email setting has been activated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Activate Email Setting Error', 'The email setting does not exist.', 'info', 'redirect', 'email-settings.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Activate Email Setting Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Activate Email Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-email-setting',function() {
        const email_setting_id = $(this).data('email-setting-id');
        const transaction = 'deactivate email setting';

        Swal.fire({
            title: 'Deactivate Email Setting',
            text: 'Are you sure you want to deactivate this email setting?',
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
                    data: {username : username, email_setting_id : email_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated' || response === 'Not Found'){
                            if(response === 'Deactivated'){
                                show_alert_event('Deactivate Email Setting Success', 'The email setting has been deactivated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Deactivate Email Setting Error', 'The email setting does not exist.', 'info', 'redirect', 'email-settings.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Deactivate Email Setting Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Deactivate Email Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-email-setting',function() {
        const email_setting_id = $(this).data('email-setting-id');
        const transaction = 'delete email setting';

        Swal.fire({
            title: 'Delete Email Setting',
            text: 'Are you sure you want to delete this email setting?',
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
                    data: {username : username, email_setting_id : email_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Email Setting Success', 'The email setting has been deleted.', 'success', 'redirect', 'email-settings.php');
                            }
                            else{
                                show_alert_event('Delete Email Setting Error', 'The email setting does not exist.', 'info', 'redirect', 'email-settings.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Email Setting Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Email Setting Error', response, 'error');
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
                window.location.href = 'email-settings.php';
                return false;
            }
        });
    });

}