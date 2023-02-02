(function($) {
    'use strict';

    $(function() {
        initialize_global_functions();
    });
})(jQuery);

// Initialize function
function initialize_global_functions(){
    $(document).on('click','#datatable-checkbox',function() {
        var status = $(this).is(':checked') ? true : false;
        $('.datatable-checkbox-children').prop('checked',status);

        check_table_check_box();
        check_table_multiple_button();
    });

    $(document).on('click','#form-datatable-checkbox',function() {
        var status = $(this).is(':checked') ? true : false;
        $('.datatable-checkbox-children').prop('checked',status);
    });
    
    $(document).on('click','.datatable-checkbox-children',function() {
        check_table_check_box();
        check_table_multiple_button();
    });

    $(document).on('click','.view-transaction-log',function() {
        const username = $('#username').text();
        const transaction_log_id = $(this).data('transaction-log-id');

        sessionStorage.setItem('transaction_log_id', transaction_log_id);

        generate_modal('transaction log', 'Transaction Log', 'XL' , '1', '0', 'element', '', '0', username);
    });

    $(document).on('click','#page-header-notifications-dropdown',function() {
        const username = $('#username').text();
        const transaction = 'partial notification status';

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'text',
            data: {transaction : transaction, username : username},
            success: function () {
                $('#page-header-notifications-dropdown').html('<i class="bx bx-bell">');
            }
        });
    });

    $(document).on('click','.notification-item',function() {
        const username = $('#username').text();
        const transaction = 'read notification status';
        const notification_id = $(this).data('notification-id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'text',
            data: {transaction : transaction, notification_id : notification_id, username : username},
            success: function () {
                $(this).removeClass('text-primary');
            }
        });
    });

    $(document).on('click','#backup-database',function() {
        const username = $('#username').text();
        generate_modal('backup database form', 'Backup Database', 'R' , '1', '1', 'form', 'backup-database-form', '1', username);
    });

    if ($('.select2').length) {
        $('.select2').select2();
    }

    if ($('.filter-select2').length) {
        $('.filter-select2').select2({
            dropdownParent: $('#filter-off-canvas')
        });
    }

    if ($('.form-maxlength').length) {
        $('.form-maxlength').maxlength({
            alwaysShow: true,
            warningClass: 'badge mt-1 bg-info',
            limitReachedClass: 'badge mt-1 bg-danger',
            validate: true
        });
    }
}

function initialize_elements(){
    if ($('.form-maxlength').length) {
        $('.form-maxlength').maxlength({
            alwaysShow: true,
            warningClass: 'badge mt-1 bg-info',
            limitReachedClass: 'badge mt-1 bg-danger',
            validate: true
        });
    }

    if ($('.form-select2').length) {
        $('.form-select2').select2({
            dropdownParent: $('#System-Modal')
        });

        $('.form-select2').on('select2:close', function (e) {  
            $(this).valid(); 
        });
    }

    if ($('.birthday-date-picker').length) {
        $('.birthday-date-picker').datepicker({
            endDate: '-18y'
        });
    }
}

function initialize_form_validation(form_type){
    var transaction;
    const username = $('#username').text();

    if(form_type == 'change password form'){
        $('#change-password-form').validate({
            submitHandler: function (form) {
                transaction = 'change password';

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&transaction=' + transaction,
                    beforeSend: function(){
                        document.getElementById('signin').disabled = true;
                        $('#signin').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated'){
                            show_alert('Change User Account Password Success', 'The user account password has been updated. You can now sign in your account.', 'success');
                            $('#System-Modal').modal('hide');

                            document.getElementById('signin').disabled = false;
                            $('#signin').html('Log In');
                        }
                        else{
                            if(response === 'Not Found'){
                                show_alert('Change User Account Password Error', 'The user account does not exist.', 'error');
                            }
                            else{
                                show_alert('Change User Account Password Error', response, 'error');
                            }                            

                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    }
                });

                return false;
            },
            rules: {
                change_password: {
                    required: true,
                    password_strength : true
                }
            },
            messages: {
                change_password: {
                    required: 'Please enter your password',
                }
            },
            errorPlacement: function(label, element) {
                if(element.hasClass('web-select2') && element.next('.select2-container').length) {
                    label.insertAfter(element.next('.input-group'));
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
    }
    else if(form_type == 'module access form'){
        $('#module-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit module access';
                let role = [];
                const module_id = $('#module_id').val();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        role.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role=' + role + '&module_id=' + module_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Module Access Success', 'The module access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#module-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Module Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Module Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'page access form'){
        $('#page-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit page access';
                let role = [];
                const page_id = $('#page_id').val();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        role.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role=' + role + '&page_id=' + page_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Page Access Success', 'The page access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#page-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Page Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Page Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'action access form'){
        $('#action-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit action access';
                let role = [];
                const action_id = $('#action_id').val();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        role.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role=' + role + '&action_id=' + action_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Action Access Success', 'The action access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#action-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Action Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Action Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'role module access form'){
        $('#role-module-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit role module access';
                let module_id = [];
                const role_id = $('#role-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        module_id.push(element.value);  
                    }
                });


                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role_id=' + role_id + '&module_id=' + module_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Module Access Success', 'The module access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#module-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Module Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Module Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'role page access form'){
        $('#role-page-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit role page access';
                let page_id = [];
                const role_id = $('#role-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        page_id.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role_id=' + role_id + '&page_id=' + page_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Page Access Success', 'The page access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#page-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Page Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Page Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'role action access form'){
        $('#role-action-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit role action access';
                let action_id = [];
                const role_id = $('#role-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        action_id.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role_id=' + role_id + '&action_id=' + action_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Action Access Success', 'The action access has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#action-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Action Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Action Access Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'role user account form'){
        $('#role-user-account-form').validate({
            submitHandler: function (form) {
                transaction = 'submit role user account';
                let user_id = [];
                const role_id = $('#role-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        user_id.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role_id=' + role_id + '&user_id=' + user_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Role User Account Success', 'The user account has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#user-account-assignment');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Role User Account Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Role User Account Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'upload setting file type form'){
        $('#upload-setting-file-type-form').validate({
            submitHandler: function (form) {
                transaction = 'submit upload setting file type';
                let file_type = [];
                const upload_setting_id = $('#upload-setting-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        file_type.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&upload_setting_id=' + upload_setting_id + '&file_type=' + file_type,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Upload Setting File Type Success', 'The file type has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#upload-setting-file-type-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Upload setting File Type Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Upload Setting File Type Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'notification role recipient form'){
        $('#notification-role-recipient-form').validate({
            submitHandler: function (form) {
                transaction = 'submit notification role recipient';
                let role_id = [];
                const notification_setting_id = $('#notification-setting-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        role_id.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id + '&role_id=' + role_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Notification Role Recipient Success', 'The notification role recipient has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#notification-role-recipients-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Notification Role Recipient Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Notification Role Recipient Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'notification user account recipient form'){
        $('#notification-user-account-recipient-form').validate({
            submitHandler: function (form) {
                transaction = 'submit notification user account recipient';
                let user_id = [];
                const notification_setting_id = $('#notification-setting-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        user_id.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id + '&user_id=' + user_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Notification User Account Recipient Success', 'The notification user account recipient has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#notification-user-account-recipients-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Notification User Account Recipient Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Notification User Account Recipient Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'notification channel form'){
        $('#notification-channel-form').validate({
            submitHandler: function (form) {
                transaction = 'submit notification channel';
                let channel = [];
                const notification_setting_id = $('#notification-setting-id').text();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        channel.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id + '&channel=' + channel,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert Notification Channel Success', 'The notification channel has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#notification-channel-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Notification Channel Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Notification Channel Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'state form'){
        $('#state-form').validate({
            submitHandler: function (form) {
                const country_id = $('#country-id').text();
                transaction = 'submit country state'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&country_id=' + country_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert State Success', 'The state has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update State Success', 'The state has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#state-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('State Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('State Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                state_name: {
                    required: true
                }
            },
            messages: {
                state_name: {
                    required: 'Please enter the state',
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
    }
    else if(form_type == 'user account role form'){
        $('#user-account-role-form').validate({
            submitHandler: function (form) {
                transaction = 'submit user account role';
                let role = [];
                const user_id = $('#user_id').val();

                $('.datatable-checkbox-children').each((index, element) => {
                    if ($(element).is(':checked')) {
                        role.push(element.value);  
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&role=' + role + '&user_id=' + user_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Inserted'){
                            show_alert('Insert User Account Role Success', 'The user account role has been inserted.', 'success');
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#user-account-role-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('User Account Role Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('User Account Role Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
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
    }
    else if(form_type == 'job position responsibility form'){
        $('#job-position-responsibility-form').validate({
            submitHandler: function (form) {
                const job_position_id = $('#job-position-id').text();
                transaction = 'submit job position responsibility'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Responsibility Success', 'The responsibility has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Responsibility Success', 'The responsibility has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#job-position-responsibility-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Responsibility Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Responsibility Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                responsibility: {
                    required: true
                }
            },
            messages: {
                responsibility: {
                    required: 'Please enter the responsibility',
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
    }
    else if(form_type == 'job position requirement form'){
        $('#job-position-requirement-form').validate({
            submitHandler: function (form) {
                const job_position_id = $('#job-position-id').text();
                transaction = 'submit job position requirement'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Requirement Success', 'The requirement has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Requirement Success', 'The requirement has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#job-position-requirement-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Requirement Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Requirement Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                requirement: {
                    required: true
                }
            },
            messages: {
                requirement: {
                    required: 'Please enter the requirement',
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
    }
    else if(form_type == 'job position qualification form'){
        $('#job-position-qualification-form').validate({
            submitHandler: function (form) {
                const job_position_id = $('#job-position-id').text();
                transaction = 'submit job position qualification'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Qualification Success', 'The qualification has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Qualification Success', 'The qualification has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#job-position-qualification-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Qualification Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Qualification Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                qualification: {
                    required: true
                }
            },
            messages: {
                qualification: {
                    required: 'Please enter the qualification',
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
    }
    else if(form_type == 'job position attachment form'){
        $('#job-position-attachment-form').validate({
            submitHandler: function (form) {
                const job_position_id = $('#job-position-id').text();
                transaction = 'submit job position attachment'; 

                var formData = new FormData(form);
                formData.append('username', username);
                formData.append('transaction', transaction);
                formData.append('job_position_id', job_position_id);

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Attachment Success', 'The attachment has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Attachment Success', 'The attachment has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#job-position-attachment-datatable');
                        }
                        else if(response === 'File Size'){
                            show_alert('Attachment Error', 'The file uploaded exceeds the maximum file size.', 'error');
                        }
                        else if(response === 'File Type'){
                            show_alert('Attachment Error', 'The file uploaded is not supported.', 'error');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Attachment Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Attachment Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                attachment_name: {
                    required: true
                },
                attachment: {
                    required: function(element){
                        var update = $('#update').val();

                        if(update == '0'){
                            return true;
                        }
                        else{
                            return false;
                        }
                    }
                }
            },
            messages: {
                attachment_name: {
                    required: 'Please enter the attachment name',
                },
                attachment: {
                    required: 'Please choose the attachment',
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
    }
    else if(form_type == 'fixed working hours form'){
        $('#fixed-working-hours-form').validate({
            submitHandler: function (form) {
                const working_schedule_id = $('#working-schedule-id').text();
                transaction = 'submit fixed working hours'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&working_schedule_id=' + working_schedule_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Working Hours Success', 'The working hours has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Working Hours Success', 'The working hours has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#working-hours-datatable');
                        }
                        else if(response === 'Overlap'){
                            show_alert('Working Hours Error', 'Working hours cannot overlap with other wokring hours', 'error');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Working Hours Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Working Hours Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                working_hours: {
                    required: true
                },
                day_of_week: {
                    required: true
                },
                day_period: {
                    required: true
                },
                work_from: {
                    required: true
                },
                work_to: {
                    required: true
                }
            },
            messages: {
                working_hours: {
                    required: 'Please enter the name',
                },
                day_of_week: {
                    required: 'Please choose the day of week',
                },
                day_period: {
                    required: 'Please choose the day period',
                },
                work_from: {
                    required: 'Please choose the work from',
                },
                work_to: {
                    required: 'Please choose the work to',
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
    }
    else if(form_type == 'flexible working hours form'){
        $('#flexible-working-hours-form').validate({
            submitHandler: function (form) {
                const working_schedule_id = $('#working-schedule-id').text();
                transaction = 'submit flexible working hours'; 

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&username=' + username + '&transaction=' + transaction + '&working_schedule_id=' + working_schedule_id,
                    beforeSend: function(){
                        document.getElementById('submit-form').disabled = true;
                        $('#submit-form').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert('Insert Working Hours Success', 'The working hours has been inserted.', 'success');
                            }
                            else{
                                show_alert('Update Working Hours Success', 'The working hours has been updated.', 'success');
                            }
                          
                            $('#System-Modal').modal('hide');
                            reload_datatable('#working-hours-datatable');
                        }
                        else if(response === 'Overlap'){
                            show_alert('Working Hours Error', 'Working hours cannot overlap with other wokring hours', 'error');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Working Hours Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Working Hours Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-form').disabled = false;
                        $('#submit-form').html('Submit');
                    }
                });
                return false;
            },
            rules: {
                working_hours: {
                    required: true
                },
                working_date: {
                    required: true
                },
                day_period: {
                    required: true
                },
                work_from: {
                    required: true
                },
                work_to: {
                    required: true
                }
            },
            messages: {
                working_hours: {
                    required: 'Please enter the name',
                },
                working_date: {
                    required: 'Please choose the working date',
                },
                day_period: {
                    required: 'Please choose the day period',
                },
                work_from: {
                    required: 'Please choose the work from',
                },
                work_to: {
                    required: 'Please choose the work to',
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
    }
}

// Display functions
function display_form_details(form_type){
    var transaction;
    var d = new Date();

    if(form_type == 'state form'){
        transaction = 'state details';

        const state_id = sessionStorage.getItem('state_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {state_id : state_id, transaction : transaction},
            success: function(response) {
                $('#state_id').val(state_id);
                $('#state_name').val(response[0].STATE_NAME);
            }
        });
    }
    else if(form_type == 'job position responsibility form'){
        transaction = 'job position responsibility details';

        const responsibility_id = sessionStorage.getItem('responsibility_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {responsibility_id : responsibility_id, transaction : transaction},
            success: function(response) {
                $('#responsibility_id').val(responsibility_id);
                $('#responsibility').val(response[0].RESPONSIBILITY);
            }
        });
    }
    else if(form_type == 'job position requirement form'){
        transaction = 'job position requirement details';

        const requirement_id = sessionStorage.getItem('requirement_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {requirement_id : requirement_id, transaction : transaction},
            success: function(response) {
                $('#requirement_id').val(requirement_id);
                $('#requirement').val(response[0].REQUIREMENT);
            }
        });
    }
    else if(form_type == 'job position qualification form'){
        transaction = 'job position qualification details';

        const qualification_id = sessionStorage.getItem('qualification_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {qualification_id : qualification_id, transaction : transaction},
            success: function(response) {
                $('#qualification_id').val(qualification_id);
                $('#qualification').val(response[0].QUALIFICATION);
            }
        });
    }
    else if(form_type == 'job position attachment form'){
        transaction = 'job position attachment details';

        const attachment_id = sessionStorage.getItem('attachment_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attachment_id : attachment_id, transaction : transaction},
            success: function(response) {
                $('#attachment_id').val(attachment_id);
                $('#attachment_name').val(response[0].ATTACHMENT_NAME);
                $('#update').val('1');
            }
        });
    }
    else if(form_type == 'fixed working hours form'){
        transaction = 'fixed working hours details';

        const working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#working_hours_id').val(working_hours_id);
                $('#working_hours').val(response[0].WORKING_HOURS);
                $('#work_from').val(response[0].WORK_FROM);
                $('#work_to').val(response[0].WORK_TO);

                check_empty(response[0].DAY_OF_WEEK, '#day_of_week', 'select');
                check_empty(response[0].DAY_PERIOD, '#day_period', 'select');
            }
        });
    }
    else if(form_type == 'flexible working hours form'){
        transaction = 'flexible working hours details';

        const working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#working_hours_id').val(working_hours_id);
                $('#working_hours').val(response[0].WORKING_HOURS);
                $('#working_date').val(response[0].WORKING_DATE);
                $('#work_from').val(response[0].WORK_FROM);
                $('#work_to').val(response[0].WORK_TO);

                check_empty(response[0].DAY_PERIOD, '#day_period', 'select');
            }
        });
    }
}

// Get location function
function get_location(map_div) {
    if(!map_div){
        if (navigator.geolocation) {
            var options = {
                enableHighAccuracy: true,
                timeout: 1000,
                maximumAge: 0
            };

            navigator.geolocation.getCurrentPosition(show_position, show_geolocation_error, options);
        } 
        else {
            show_alert('Geolocation Error', 'Your browser does not support geolocation.', 'error');
        }
    }
    else{
        var map = new GMaps({
            div: '#' + map_div,
            lat: -12.043333,
            lng: -77.028333
        });
    
        GMaps.geolocate({
            success: function(position){
                map.setCenter(position.coords.latitude, position.coords.longitude);
                map.addMarker({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });

                sessionStorage.setItem('latitude', position.coords.latitude);
                sessionStorage.setItem('longitude', position.coords.longitude);
            },
            error: function(error){
                show_alert('Geolocation Error', 'Geolocation failed: ' + error.message, 'error');
            },
            not_supported: function(){
                show_alert('Geolocation Error', 'Your browser does not support geolocation.', 'error');
            },
        });
    }
}

function show_position(position) {
    sessionStorage.setItem('latitude', position.coords.latitude);
    sessionStorage.setItem('longitude', position.coords.longitude);
    sessionStorage.setItem('attendance_position', position.coords.latitude + ', ' + position.coords.longitude);

    if ($('#attendance_position').length) {
        $('#attendance_position').val(position.coords.latitude + ', ' + position.coords.longitude);
    }

    if ($('#position').length) {
        $('#position').val(position.coords.latitude + ', ' + position.coords.longitude);
    }
}

function show_geolocation_error(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            show_alert('Geolocation Error', 'User denied the request for Geolocation.', 'error');
            break;
        case error.POSITION_UNAVAILABLE:
            show_alert('Geolocation Error', 'Location information is unavailable.', 'error');
            break;
        case error.TIMEOUT:
            show_alert('Geolocation Error', 'The request to get user location timed out.', 'error');
            break;
        case error.UNKNOWN_ERROR:
            show_alert('Geolocation Error', 'An unknown error occurred.', 'error');
            break;
    }
}

// Generate function
function generate_modal(form_type, title, size, scrollable, submit_button, generate_type, form_id, add, username){
    const type = 'system modal';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        dataType: 'JSON',
        data: {type : type, username : username, title : title, size : size, scrollable : scrollable, submit_button : submit_button, generate_type : generate_type, form_id : form_id},
        beforeSend: function(){
            $('#System-Modal').remove();
        },
        success: function(response) {
            $('body').append(response[0].MODAL);
        },
        complete : function(){
            if(generate_type == 'form'){
                generate_form(form_type, form_id, add, username);
            }
            else{
                generate_element(form_type, '', '', '1', username);
            }
        }
    });
}

function generate_form(form_type, form_id, add, username){
    const type = 'system form';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        dataType: 'JSON',
        data: { type : type, username : username, form_type : form_type, form_id : form_id },
        success: function(response) {
            document.getElementById('modal-body').innerHTML = response[0].FORM;
        },
        complete: function(){
            if(add == '0'){
                display_form_details(form_type);
            }
            else{
                if(form_type == 'module access form' || form_type == 'page access form' || form_type == 'action access form' || form_type == 'user account role form'){
                    if($('#role-assignment-datatable').length){
                        initialize_role_assignment_table('#role-assignment-datatable');
                    }
                }
                else if(form_type == 'role module access form'){
                    if($('#module-access-assignment-datatable').length){
                        initialize_role_module_access_assignment_table('#module-access-assignment-datatable');
                    }
                }
                else if(form_type == 'role page access form'){
                    if($('#page-access-assignment').length){
                        initialize_role_page_access_assignment_table('#page-access-assignment');
                    }
                }
                else if(form_type == 'role action access form'){
                    if($('#action-access-assignment').length){
                        initialize_role_action_access_assignment_table('#action-access-assignment');
                    }
                }
                else if(form_type == 'role user account form'){
                    if($('#user-account-assignment').length){
                        initialize_role_user_account_assignment_table('#user-account-assignment');
                    }
                }
                else if(form_type == 'upload setting file type form'){
                    if($('#file-type-assignment').length){
                        initialize_upload_file_type_assignment_table('#file-type-assignment');
                    }
                }
                else if(form_type == 'notification role recipient form'){
                    if($('#notification-role-recipient-assignment-datatable').length){
                        initialize_notification_role_recipient_assignment_table('#notification-role-recipient-assignment-datatable');
                    }
                }
                else if(form_type == 'notification user account recipient form'){
                    if($('#notification-user-account-recipient-assignment-datatable').length){
                        initialize_notification_user_account_recipient_assignment_table('#notification-user-account-recipient-assignment-datatable');
                    }
                }
                else if(form_type == 'notification channel form'){
                    if($('#notification-role-recipient-assignment-datatable').length){
                        initialize_notification_channel_assignment_table('#notification-role-recipient-assignment-datatable');
                    }
                }
            }

            initialize_elements();
            initialize_form_validation(form_type);

            $('#System-Modal').modal('show');
        }
    });    
}

function generate_element(element_type, value, container, modal, username){
    const type = 'system element';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        dataType: 'JSON',
        data: { type : type, username : username, value : value, element_type : element_type },
        beforeSend : function(){
            if(container){
                document.getElementById(container).innerHTML = '';
            }
        },
        success: function(response) {
            if(!container){
                document.getElementById('modal-body').innerHTML = response[0].ELEMENT;
            }
            else{
                document.getElementById(container).innerHTML = response[0].ELEMENT;
            }
        },
        complete: function(){
            initialize_elements();

            if(modal == '1'){
                $('#System-Modal').modal('show');

                if(element_type == 'user account details' || element_type == 'system parameter details' || element_type == 'company details' || element_type == 'job position details' || element_type == 'work location details' || element_type == 'working hours details' || element_type == 'attendance details' || element_type == 'attendance adjustment details' || element_type == 'attendance creation details' || element_type == 'attendance cration details' || element_type == 'approval type details' || element_type == 'public holiday details' || element_type == 'leave details'){
                    display_form_details(element_type);
                }
                else if(element_type == 'scan badge form'){
                    $('#badge-reader').html('<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span rclass="sr-only"></span></div></div>');

                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            get_location('');
                            var camera_id = devices[0].id;
            
                            const html5QrCode = new Html5Qrcode('badge-reader');
                            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                                var audio = new Audio('assets/audio/scan.mp3');
                                audio.play();
                                navigator.vibrate([500]);
            
                                var employee_id = decodedText.substring(
                                    decodedText.lastIndexOf('[') + 1, 
                                    decodedText.lastIndexOf(']')
                                );
            
                                var attendance_position = sessionStorage.getItem('attendance_position');
                                var transaction = 'submit badge scan';
                                var username = $('#username').text();
                                    
                                $.ajax({
                                    type: 'POST',
                                    url: 'controller.php',
                                    data: {username : username, attendance_position : attendance_position, employee_id : employee_id, transaction : transaction},
                                    success: function (response) {
                                        if(response === 'Time In'){
                                            show_alert('Attendance Success', 'Your time in has been recorded.', 'success');
                                        }
                                        else if(response === 'Time Out'){
                                            show_alert('Attendance Success', 'Your time out has been recorded.', 'success');
                                        }
                                        else if(response === 'Max Attendance'){
                                            show_alert('Attendance Error', 'Your have reached the maximum time in for the day.', 'error');
                                        }
                                        else if(response === 'Location'){
                                            show_alert('Attendance Error', 'Your location cannot be determined.', 'error');
                                        }
                                        else if(response === 'Time Allowance'){
                                            show_alert('Attendance Error', 'Please wait a few minutes before you can time out.', 'error');
                                        }
                                        else{
                                            show_alert('Attendance Error', response, 'error');
                                        }

                                        navigator.vibrate([500]);
                                    }
                                });
            
                                html5QrCode.stop().then((ignore) => {
                                    $('#badge-reader').html('');
                                    $('#badge-reader').html('<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span rclass="sr-only"></span></div></div>');
                                    
                                    setTimeout(function(){  html5QrCode.start({ deviceId: { exact: camera_id} }, config, qrCodeSuccessCallback); }, 4000);
                                }).catch((err) => {
                                    alert(err);
                                });
                            };
            
                            html5QrCode.start({ deviceId: { exact: camera_id} }, config, qrCodeSuccessCallback);
                        }
                    }).catch(err => {
                        alert(err);
                    });
                }
                else if(element_type == 'transaction log'){
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            }
        }
    });
}

function generate_city_option(province, selected){
    const username = $('#username').text();
    const type = 'city options';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        dataType: 'JSON',
        data: {type : type, province : province, username : username},
        beforeSend: function(){
            $('#city').empty();
        },
        success: function(response) {
            var newOption = new Option('--', '', false, false);
            $('#city').append(newOption);

            for(var i = 0; i < response.length; i++) {
                newOption = new Option(response[i].CITY, response[i].CITY_ID, false, false);
                $('#city').append(newOption);
            }
        },
        complete: function(){
            if(selected != ''){
                $('#city').val(selected).change();
            }
        }
    });
}

// Reset validation functions
function reset_element_validation(element){
    $(element).parent().removeClass('has-danger');
    $(element).removeClass('form-control-danger');
    $(element + '-error').remove();
}

// Reload functions
function reload_datatable(datatable){
    hide_multiple_buttons();
    $(datatable).DataTable().ajax.reload();
}

// Destroy functions
function destroy_datatable(datatable_name){
    $(datatable_name).DataTable().clear().destroy();
}

// Clear
function clear_datatable(datatable_name){
    $(datatable_name).dataTable().fnClearTable();
}

// Re-adjust datatable columns
function readjust_datatable_column(){
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });

    $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });

    $('#System-Modal').on('shown.bs.modal', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });
}

// Check functions
function check_option_exist(element, option, return_value){
    if ($(element).find('option[value="' + option + '"]').length) {
        $(element).val(option).trigger('change');
    }
    else{
        $(element).val(return_value).trigger('change');
    }
}

function check_empty(value, id, type){
    if(value != '' || value != null){
        if(type == 'select'){
            $(id).val(value).change();
        }
        else if(type == 'text'){
            $(id).text(value);
        }
        else {
            $(id).val(value);
        }
    }
}

function check_table_check_box(){
    var input_elements = [].slice.call(document.querySelectorAll('.datatable-checkbox-children'));
    var checked_value = input_elements.filter(chk => chk.checked).length;

    if(checked_value > 0){
        $('.multiple').removeClass('d-none');
        $('.multiple-action').removeClass('d-none');
    }
    else{
        $('.multiple').addClass('d-none');
        $('.multiple-action').addClass('d-none');
    }
}

function check_table_multiple_button(){
    var input_elements = [].slice.call(document.querySelectorAll('.datatable-checkbox-children'));
    var checked_value = input_elements.filter(chk => chk.checked).length;

    if(checked_value > 0){
        var lock_array = [];
        var cancel_array = [];
        var delete_array = [];
        var reject_array = [];
        var approve_array = [];
        var pending_array = [];
        var for_recommendation_array = [];
        var for_approval_array = [];
        var recommend_array = [];
        var active_array = [];
        var paid_array = [];
        var unpaid_array = [];
        var send_array = [];
        var print_array = [];
        var archive_array = [];
        var start_array = [];
        
        $(".datatable-checkbox-children").each(function () {
            var cancel_data = $(this).data('cancel');
            var delete_data = $(this).data('delete');
            var pending_data = $(this).data('pending');
            var for_recommendation_data = $(this).data('for-recommendation');
            var for_approval_data = $(this).data('for-approval');
            var recommend_data = $(this).data('recommend');
            var reject_data = $(this).data('reject');
            var approve_data = $(this).data('approve');
            var lock = $(this).data('lock');
            var active = $(this).data('active');
            var paid = $(this).data('paid');
            var unpaid = $(this).data('unpaid');
            var send = $(this).data('send');
            var print = $(this).data('print');
            var archive = $(this).data('archive');
            var start = $(this).data('start');

            if($(this).prop('checked') === true){
                lock_array.push(lock);
                cancel_array.push(cancel_data);
                approve_array.push(approve_data);
                pending_array.push(pending_data);
                for_approval_array.push(for_approval_data);
                reject_array.push(reject_data);
                delete_array.push(delete_data);
                for_recommendation_array.push(for_recommendation_data);
                recommend_array.push(recommend_data);
                active_array.push(active);
                paid_array.push(paid);
                unpaid_array.push(unpaid);
                send_array.push(send);
                print_array.push(print);
                archive_array.push(archive);
                start_array.push(start);
            }
        });

        var cancel_checker = arr => arr.every(v => v === 1);
        var delete_checker = arr => arr.every(v => v === 1);
        var pending_checker = arr => arr.every(v => v === 1);
        var for_recommendation_checker = arr => arr.every(v => v === 1);
        var for_approval_checker = arr => arr.every(v => v === 1);
        var recommend_checker = arr => arr.every(v => v === 1);
        var reject_checker = arr => arr.every(v => v === 1);
        var approve_checker = arr => arr.every(v => v === 1);
        var unlock_checker = arr => arr.every(v => v === 1);
        var lock_checker = arr => arr.every(v => v === 0);
        var activate_checker = arr => arr.every(v => v === 0);
        var deactivate_checker = arr => arr.every(v => v === 1);
        var paid_checker = arr => arr.every(v => v === 1);
        var unpaid_checker = arr => arr.every(v => v === 1);
        var send_checker = arr => arr.every(v => v === 1);
        var print_checker = arr => arr.every(v => v === 1);
        var archive_checker = arr => arr.every(v => v === 1);
        var unarchive_checker = arr => arr.every(v => v === 0);
        var start_checker = arr => arr.every(v => v === 1);
        var stop_checker = arr => arr.every(v => v === 0);
        
        if(lock_checker(lock_array) || unlock_checker(lock_array)){
            if(lock_checker(lock_array)){
                $('.multiple-lock').removeClass('d-none');
                $('.multiple-unlock').addClass('d-none');
            }

            if(unlock_checker(lock_array)){
                $('.multiple-lock').addClass('d-none');
                $('.multiple-unlock').removeClass('d-none');
            }
        }
        else{
            $('.multiple-lock').addClass('d-none');
            $('.multiple-unlock').addClass('d-none');
        }

        if(archive_checker(archive_array) || unarchive_checker(archive_array)){
            if(archive_checker(archive_array)){
                $('.multiple-archive').removeClass('d-none');
                $('.multiple-unarchive').addClass('d-none');
            }

            if(unarchive_checker(archive_array)){
                $('.multiple-archive').addClass('d-none');
                $('.multiple-unarchive').removeClass('d-none');
            }
        }
        else{
            $('.multiple-archive').addClass('d-none');
            $('.multiple-unarchive').addClass('d-none');
        }

        if(start_checker(start_array) || stop_checker(start_array)){
            if(start_checker(start_array)){
                $('.multiple-start').removeClass('d-none');
                $('.multiple-stop').addClass('d-none');
            }

            if(stop_checker(start_array)){
                $('.multiple-start').addClass('d-none');
                $('.multiple-stop').removeClass('d-none');
            }
        }
        else{
            $('.multiple-start').addClass('d-none');
            $('.multiple-stop').addClass('d-none');
        }

        if(activate_checker(active_array) || deactivate_checker(active_array)){
            if(activate_checker(active_array)){
                $('.multiple-activate').removeClass('d-none');
                $('.multiple-deactivate').addClass('d-none');
            }

            if(deactivate_checker(active_array)){
                $('.multiple-activate').addClass('d-none');
                $('.multiple-deactivate').removeClass('d-none');
            }
        }
        else{
            $('.multiple-activate').addClass('d-none');
            $('.multiple-deactivate').addClass('d-none');
        }
        
        if(for_approval_checker(for_approval_array)){
            $('.multiple-for-approval').removeClass('d-none');
        }
        else{
            $('.multiple-for-approval').addClass('d-none');
        }
        
        if(cancel_checker(cancel_array)){
            $('.multiple-cancel').removeClass('d-none');
        }
        else{
            $('.multiple-cancel').addClass('d-none');
        }
        
        if(reject_checker(reject_array)){
            $('.multiple-reject').removeClass('d-none');
        }
        else{
            $('.multiple-reject').addClass('d-none');
        }
        
        if(approve_checker(approve_array)){
            $('.multiple-approve').removeClass('d-none');
        }
        else{
            $('.multiple-approve').addClass('d-none');
        }
        
        if(delete_checker(delete_array)){
            $('.multiple-delete').removeClass('d-none');
        }
        else{
            $('.multiple-delete').addClass('d-none');
        }

        if(pending_checker(pending_array)){
            $('.multiple-pending').removeClass('d-none');
        }
        else{
            $('.multiple-pending').addClass('d-none');
        }
        
        if(for_recommendation_checker(for_recommendation_array)){
            $('.multiple-for-recommendation').removeClass('d-none');
        }
        else{
            $('.multiple-for-recommendation').addClass('d-none');
        }
        
        if(recommend_checker(recommend_array)){
            $('.multiple-recommendation').removeClass('d-none');
        }
        else{
            $('.multiple-recommendation').addClass('d-none');
        }

        if(paid_checker(paid_array)){
            $('.multiple-tag-loan-details-as-paid').removeClass('d-none');
        }
        else{
            $('.multiple-tag-loan-details-as-paid').addClass('d-none');
        }

        if(unpaid_checker(unpaid_array)){
            $('.multiple-tag-loan-details-as-unpaid').removeClass('d-none');
        }
        else{
            $('.multiple-tag-loan-details-as-unpaid').addClass('d-none');
        }

        if(send_checker(send_array)){
            $('.multiple-send').removeClass('d-none');
        }
        else{
            $('.multiple-send').addClass('d-none');
        }

        if(print_checker(print_array)){
            $('.multiple-print').removeClass('d-none');
        }
        else{
            $('.multiple-print').addClass('d-none');
        }
    }
    else{
        $('.multiple-delete').addClass('d-none');
        $('.multiple-cancel').addClass('d-none');
        $('.multiple-pending').addClass('d-none');
        $('.multiple-for-recommendation').addClass('d-none');
        $('.multiple-for-approval').addClass('d-none');
        $('.multiple-recommendation').addClass('d-none');
        $('.multiple-reject').addClass('d-none');
        $('.multiple-approve').addClass('d-none');
        $('.multiple-lock').addClass('d-none');
        $('.multiple-unlock').addClass('d-none');
        $('.multiple-activate').addClass('d-none');
        $('.multiple-deactivate').addClass('d-none');
        $('.multiple-tag-loan-details-as-paid').addClass('d-none');
        $('.multiple-tag-loan-details-as-unpaid').addClass('d-none');
        $('.multiple-send').addClass('d-none');
        $('.multiple-print').addClass('d-none');
        $('.multiple-archive').addClass('d-none');
        $('.multiple-unarchive').addClass('d-none');
        $('.multiple-start').addClass('d-none');
        $('.multiple-stop').addClass('d-none');
    }
}

// Show alert
function show_alert(title, message, type){
    Swal.fire(title, message, type);
}

function show_alert_event(title, message, type, event, rederict_link){
    Swal.fire(title, message, type).then(function(){ 
            if(event == 'reload'){
                location.reload();
            }
            else if(event == 'redirect'){
                window.location.href = rederict_link;
            }
        }
    );
}

function show_alert_confirmation(confirm_title, confirm_text, confirm_icon, confirm_button_text, button_color, confirm_type){
    Swal.fire({
        title: confirm_title,
        text: confirm_text,
        icon: confirm_icon,
        showCancelButton: !0,
        confirmButtonText: confirm_button_text,
        cancelButtonText: "Cancel",
        confirmButtonClass: "btn btn-"+ button_color +" mt-2",
        cancelButtonClass: "btn btn-secondary ms-2 mt-2",
        buttonsStyling: !1
    }).then(function(result) {
        if (result.value) {
            if(confirm_type == 'expired password'){
                var username = $('#username').val();
                
                generate_modal('change password form', 'Change Password', 'R' , '1', '1', 'form', 'change-password-form', '1', username);
            }
        }
    })
}

function create_employee_qr_code(container, name, employee_id, email, mobile){
    document.getElementById(container).innerHTML = '';

    let card, qrcode;

    card = ['BEGIN:VCARD', 'VERSION:3.0', `FN:${name}`, `EMAIL:${email}`, `ID NO:[${employee_id}]`];

    if (mobile) {
      card.push(`TEL:${mobile}`);
    }
    
    card.push('END:VCARD');
    
    card = card.join('\r\n');

    qrcode = new QRCode(document.getElementById(container), {
        width: 300,
        height: 300,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H,
    });

    qrcode.makeCode(card);
}

// Hide
function hide_multiple_buttons(){
    $('#datatable-checkbox').prop('checked', false);

    $('.multiple').addClass('d-none');
    $('.multiple-lock').addClass('d-none');
    $('.multiple-unlock').addClass('d-none');
    $('.multiple-activate').addClass('d-none');
    $('.multiple-deactivate').addClass('d-none');
    $('.multiple-approve').addClass('d-none');
    $('.multiple-reject').addClass('d-none');
    $('.multiple-cancel').addClass('d-none');
    $('.multiple-delete').addClass('d-none');
    $('.multiple-cancel').addClass('d-none');
    $('.multiple-pending').addClass('d-none');
    $('.multiple-for-recommendation').addClass('d-none');
    $('.multiple-recommendation').addClass('d-none');
    $('.multiple-reject').addClass('d-none');
    $('.multiple-approve').addClass('d-none');
    $('.multiple-send').addClass('d-none');
    $('.multiple-print').addClass('d-none');
    $('.multiple-unarchive').addClass('d-none');
    $('.multiple-archive').addClass('d-none');
    $('.multiple-start').addClass('d-none');
    $('.multiple-stop').addClass('d-none');
}