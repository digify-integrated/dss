(function($) {
    'use strict';

    $(function() {
        if($('#user-id').length){
            var transaction = 'user account details';
            var user_id = $('#user_id').val();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {user_id : user_id, transaction : transaction},
                success: function(response) {
                    $('#file_as').val(response[0].FILE_AS);
                    $('#password').val(response[0].PASSWORD);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);

                    document.getElementById('last_connection_date').innerHTML = response[0].LAST_CONNECTION_DATE;
                    document.getElementById('password_expiry_date').innerHTML = response[0].PASSWORD_EXPIRY_DATE;
                    document.getElementById('last_failed_login_date').innerHTML = response[0].LAST_FAILED_LOGIN;
                    document.getElementById('user_status').innerHTML = response[0].USER_STATUS;
                    document.getElementById('failed_login').innerHTML = response[0].FAILED_LOGIN;
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }

                    if($('#user-account-role-datatable').length){
                        initialize_user_account_role_table('#user-account-role-datatable');
                    }
                }
            });
        }

        $('#user-account-form').validate({
            submitHandler: function (form) {
                var transaction = 'submit user account';
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
                                var redirect_link = window.location.href + '?id=' + response[0]['USER_ID'];

                                show_alert_event('Insert User Account Success', 'The user account has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update User Account Success', 'The user account has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('User Account Error', response, 'error');
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
                file_as: {
                    required: true
                },
                password: {
                    required: true,
                    password_strength : true
                },
                user_id: {
                    required: true
                }
            },
            messages: {
                file_as: {
                    required: 'Please enter the full name',
                },
                password: {
                    required: 'Please enter the password',
                },
                user_id: {
                    required: 'Please enter the username',
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

function initialize_role_assignment_table(datatable_name, buttons = false, show_all = false){
    var username = $('#username').text();
    var user_id = $('#user_id').val();
    var type = 'user account role assignment table';
    var settings;

    var column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE' }
    ];

    var column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [20, 50, 100, -1], [20, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'user_id' : user_id},
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
                'data': {'type' : type, 'username' : username, 'user_id' : user_id},
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

function initialize_user_account_role_table(datatable_name, buttons = false, show_all = false){    
    var username = $('#username').text();
    var user_id = $('#user_id').val();
    var type = 'user account role table';
    var settings;

    var column = [ 
        { 'data' : 'ROLE' },
        { 'data' : 'ACTION' }
    ];

    var column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
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
                'data': {'type' : type, 'username' : username, 'user_id' : user_id},
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
                'data': {'type' : type, 'username' : username, 'user_id' : user_id},
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

    $(document).on('click','#add-user-account-role',function() {
        generate_modal('user account role form', 'User Account Role', 'LG' , '1', '1', 'form', 'user-account-role-form', '1', username);
    });

    $(document).on('click','.delete-user-account-role',function() {
        var user_id = $(this).data('user-id');
        var role_id = $(this).data('role-id');
        var transaction = 'delete user account role';

        Swal.fire({
            title: 'Delete User Account Role',
            text: 'Are you sure you want to delete this user account role?',
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
                    data: {username : username, user_id : user_id, role_id : role_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert('Delete User Account Role', 'The user account role has been deleted.', 'success');
                            }
                            else{
                                show_alert('Delete User Account Role', 'The user account role does not exist.', 'info');
                            }

                            reload_datatable('#user-account-role-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete User Account Role Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete User Account Role Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#activate-user-account',function() {
        var user_id = $(this).data('user-id');
        var transaction = 'activate user account';

        Swal.fire({
            title: 'Activate User Account',
            text: 'Are you sure you want to activate this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated' || response === 'Not Found'){
                            if(response === 'Activated'){
                                show_alert_event('Activate User Account', 'The user account has been activated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Activate User Account', 'The user account does not exist.', 'info', 'redirect', 'user-accounts.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Activate User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Activate User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-user-account',function() {
        var user_id = $(this).data('user-id');
        var transaction = 'deactivate user account';

        Swal.fire({
            title: 'Deactivate User Account',
            text: 'Are you sure you want to deactivate this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated' || response === 'Not Found'){
                            if(response === 'Deactivated'){
                                show_alert_event('Deactivate User Account', 'The user account has been deactivated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Deactivate User Account', 'The user account does not exist.', 'info', 'redirect', 'user-accounts.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Deactivate User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Deactivate User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#unlock-user-account',function() {
        var user_id = $(this).data('user-id');
        var transaction = 'unlock user account';

        Swal.fire({
            title: 'Unlock User Account',
            text: 'Are you sure you want to unlock this user account?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Unlock',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Unlocked' || response === 'Not Found'){
                            if(response === 'Unlocked'){
                                show_alert_event('Unlock User Account', 'The user account has been unlocked.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Unlock User Account', 'The user account does not exist.', 'info', 'redirect', 'user-accounts.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Unlock User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Unlock User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#lock-user-account',function() {
        var user_id = $(this).data('user-id');
        var transaction = 'lock user account';

        Swal.fire({
            title: 'Lock User Account',
            text: 'Are you sure you want to lock this user account?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Lock',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Locked' || response === 'Not Found'){
                            if(response === 'Locked'){
                                show_alert_event('Lock User Account', 'The user account has been locked.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Lock User Account', 'The user account does not exist.', 'info', 'redirect', 'user-accounts.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Lock User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Lock User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-user-account',function() {
        var user_id = $(this).data('user-id');
        var transaction = 'delete user account';

        Swal.fire({
            title: 'Delete User Account',
            text: 'Are you sure you want to delete this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete User Account', 'The user account has been deleted.', 'success', 'redirect', 'user-accounts.php');
                            }
                            else{
                                show_alert_event('Delete User Account', 'The user account does not exist.', 'info', 'redirect', 'user-accounts.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete User Account Error', response, 'error');
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
                window.location.href = 'user-accounts.php';
                return false;
            }
        });
    });

}