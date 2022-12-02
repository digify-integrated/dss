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
    
    $(document).on('click','.datatable-checkbox-children',function() {
        check_table_check_box();
        check_table_multiple_button();
    });

    $(document).on('click','.view-transaction-log',function() {
        var username = $('#username').text();
        var transaction_log_id = $(this).data('transaction-log-id');

        sessionStorage.setItem('transaction_log_id', transaction_log_id);

        generate_modal('transaction log', 'Transaction Log', 'XL' , '1', '0', 'element', '', '0', username);
    });

    $(document).on('click','#page-header-notifications-dropdown',function() {
        var username = $('#username').text();
        var transaction = 'partial notification status';

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
        var username = $('#username').text();
        var transaction = 'read notification status';
        var notification_id = $(this).data('notification-id');

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
        var username = $('#username').text();
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
    var username = $('#username').text();

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
                var role = $('#role').val();
                var module_id = $('#module_id').val();

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
            rules: {
                role: {
                    required: true
                }
            },
            messages: {
                role: {
                    required: 'Please choose at least one (1) role',
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
    else if(form_type == 'page access form'){
        $('#page-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit page access';
                var role = $('#role').val();
                var page_id = $('#page_id').val();

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
            rules: {
                role: {
                    required: true
                }
            },
            messages: {
                role: {
                    required: 'Please choose at least one (1) role',
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
    else if(form_type == 'action access form'){
        $('#action-access-form').validate({
            submitHandler: function (form) {
                transaction = 'submit action access';
                var role = $('#role').val();
                var action_id = $('#action_id').val();

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
            rules: {
                role: {
                    required: true
                }
            },
            messages: {
                role: {
                    required: 'Please choose at least one (1) role',
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

    if(form_type == 'transaction log'){
        transaction = 'transaction log details';
        
        var transaction_log_id = sessionStorage.getItem('transaction_log_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {transaction_log_id : transaction_log_id, transaction : transaction},
            success: function(response) {
                document.getElementById('transaction-log-timeline').innerHTML = response[0].TIMELINE;
            }
        });
    }
    else if(form_type == 'policy form'){
        transaction = 'policy details';

        var policy_id = sessionStorage.getItem('policy_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {policy_id : policy_id, transaction : transaction},
            success: function(response) {
                $('#policy').val(response[0].POLICY);
                $('#policy_description').val(response[0].POLICY_DESCRIPTION);
                $('#policy_id').val(policy_id);
            }
        });
    }
    else if(form_type == 'permission form'){
        transaction = 'permission details';
        
        var permission_id = sessionStorage.getItem('permission_id');
        var policy_id = $('#policy-id').text();
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {permission_id : permission_id, transaction : transaction},
            success: function(response) {
                $('#permission_id').val(permission_id);
                $('#policy_id').val(policy_id);
                $('#permission').val(response[0].PERMISSION);
            }
        });
    }
    else if(form_type == 'role form'){
        transaction = 'role details';
        
        var role_id = sessionStorage.getItem('role_id');
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {role_id : role_id, transaction : transaction},
            success: function(response) {
                $('#role_id').val(role_id);
                $('#role').val(response[0].ROLE);
                $('#role_description').val(response[0].ROLE_DESCRIPTION);
            }
        });
    }
    else if(form_type == 'role permission form'){
        transaction = 'role permission details';
        
        var role_id = sessionStorage.getItem('role_id');
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {role_id : role_id, transaction : transaction},
            success: function(response) {
                var userArray = new Array();
                userArray = response.toString().split(',');

                $('#role_id').val(role_id);

                $('.role-permissions').each(function(index) {
                    var val = $(this).val();
                    if (userArray.includes(val)) {
                        $(this).prop('checked', true);
                    }
                });
            }
        });
    }
    else if(form_type == 'user account form'){
        transaction = 'user account details';

        var user_code = sessionStorage.getItem('user_code');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {user_code : user_code, transaction : transaction},
            success: function(response) {
                $('#user_code').val(user_code);
                $('#file_as').val(response[0].FILE_AS);
                $('#update').val('1');

                check_empty(response[0].EMPLOYEE_ID, '#related_employee', 'select');
                check_empty(response[0].ROLES.split(','), '#role', 'select');
            },
            complete: function(){
                document.getElementById('user_code').readOnly = true;
            }
        });
    }
    else if(form_type == 'user account details'){
        transaction = 'user account summary details';
        
        var user_code = sessionStorage.getItem('user_code');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {user_code : user_code, transaction : transaction},
            success: function(response) {
                $('#user_code').text(user_code);
                $('#file_as').text(response[0].FILE_AS);
                $('#active').text(response[0].ACTIVE);
                $('#password_expiry_date').html(response[0].PASSWORD_EXPIRY_DATE);
                $('#failed_login').text(response[0].FAILED_LOGIN);
                $('#last_failed_login').text(response[0].LAST_FAILED_LOGIN);
                $('#roles').text(response[0].ROLES);
            }
        });
    }
    else if(form_type == 'system parameter form'){
        transaction = 'system parameter details';

        var parameter_id = sessionStorage.getItem('parameter_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {parameter_id : parameter_id, transaction : transaction},
            success: function(response) {
                $('#parameter_id').val(parameter_id);

                $('#parameter').val(response[0].PARAMETER);
                $('#parameter_description').val(response[0].PARAMETER_DESCRIPTION);
                $('#extension').val(response[0].PARAMETER_EXTENSION);
                $('#parameter_number').val(response[0].PARAMETER_NUMBER);
            }
        });
    }
    else if(form_type == 'system parameter details'){
        transaction = 'system parameter details';
        
        var parameter_id = sessionStorage.getItem('parameter_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {parameter_id : parameter_id, transaction : transaction},
            success: function(response) {
                $('#parameter').text(response[0].PARAMETER);
                $('#parameter_description').text(response[0].PARAMETER_DESCRIPTION);
                $('#extension').text(response[0].PARAMETER_EXTENSION);
                $('#parameter_number').text(response[0].PARAMETER_NUMBER);
            }
        });
    }
    else if(form_type == 'system code form'){
        transaction = 'system code details';
        
        var system_type = sessionStorage.getItem('system_type');
        var system_code = sessionStorage.getItem('system_code');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {system_type : system_type, system_code : system_code, transaction : transaction},
            success: function(response) {
                $('#system_description').val(response[0].SYSTEM_DESCRIPTION);
                $('#system_code').val(system_code);

                check_option_exist('#system_type', system_type, '');
            },
            complete: function(){
                document.getElementById('system_type').disabled = true;
                document.getElementById('system_code').readOnly = true;
            }
        });
    }
    else if(form_type == 'upload setting form'){
        transaction = 'upload setting details';
        
        var upload_setting_id = sessionStorage.getItem('upload_setting_id');
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {upload_setting_id : upload_setting_id, transaction : transaction},
            success: function(response) {
                $('#upload_setting_id').val(upload_setting_id);
                $('#upload_setting').val(response[0].UPLOAD_SETTING);
                $('#max_file_size').val(response[0].MAX_FILE_SIZE);
                $('#description').val(response[0].DESCRIPTION);
               
                check_empty(response[0].FILE_TYPE.split(','), '#file_type', 'select');
            }
        });
    }
    else if(form_type == 'company form'){
        transaction = 'company details';

        var company_id = sessionStorage.getItem('company_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {company_id : company_id, transaction : transaction},
            success: function(response) {
                $('#company_name').val(response[0].COMPANY_NAME);
                $('#street_1').val(response[0].STREET_1);
                $('#street_2').val(response[0].STREET_2);
                $('#city').val(response[0].CITY);
                $('#zip_code').val(response[0].ZIP_CODE);
                $('#tax_id').val(response[0].TAX_ID);
                $('#email').val(response[0].EMAIL);
                $('#mobile').val(response[0].MOBILE);
                $('#telephone').val(response[0].TELEPHONE);
                $('#website').val(response[0].WEBSITE);
                $('#company_id').val(company_id);

                check_option_exist('#state', response[0].STATE_ID, '');
            }
        });
    }
    else if(form_type == 'company details'){
        transaction = 'company summary details';

        var company_id = sessionStorage.getItem('company_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {company_id : company_id, transaction : transaction},
            success: function(response) {
                $('#company_name').text(response[0].COMPANY_NAME);
                $('#street_1').text(response[0].STREET_1);
                $('#street_2').text(response[0].STREET_2);
                $('#city').text(response[0].CITY);
                $('#state').text(response[0].STATE_ID);
                $('#zip_code').text(response[0].ZIP_CODE);
                $('#tax_id').text(response[0].TAX_ID);

                document.getElementById('company_logo').innerHTML = response[0].COMPANY_LOGO;
                document.getElementById('email').innerHTML = response[0].EMAIL;
                document.getElementById('telephone').innerHTML = response[0].TELEPHONE;
                document.getElementById('mobile').innerHTML = response[0].MOBILE;
                document.getElementById('website').innerHTML = response[0].WEBSITE;
            }
        });
    }
    else if(form_type == 'country form'){
        transaction = 'country details';

        var country_id = sessionStorage.getItem('country_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {country_id : country_id, transaction : transaction},
            success: function(response) {
                $('#country_name').val(response[0].COUNTRY_NAME);
                $('#country_id').val(country_id);
            }
        });
    }
    else if(form_type == 'state form'){
        transaction = 'state details';

        var state_id = sessionStorage.getItem('state_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {state_id : state_id, transaction : transaction},
            success: function(response) {
                $('#state_name').val(response[0].STATE_NAME);
                $('#state_id').val(state_id);

                check_option_exist('#country', response[0].COUNTRY_ID, '');
            }
        });
    }
    else if(form_type == 'notification setting form'){
        transaction = 'notification setting details';

        var notification_setting_id = sessionStorage.getItem('notification_setting_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {notification_setting_id : notification_setting_id, transaction : transaction},
            success: function(response) {
                $('#notification_setting').val(response[0].NOTIFICATION_SETTING);
                $('#notification_setting_description').val(response[0].NOTIFICATION_SETTING_DESCRIPTION);
                $('#notification_setting_id').val(notification_setting_id);

                check_empty(response[0].CHANNEL.split(','), '#notification_channel', 'select');
            }
        });
    }
    else if(form_type == 'notification template form'){
        transaction = 'notification template details';

        var notification_setting_id = sessionStorage.getItem('notification_setting_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {notification_setting_id : notification_setting_id, transaction : transaction},
            success: function(response) {
                $('#notification_setting_id').val(notification_setting_id);
                $('#notification_title').val(response[0].NOTIFICATION_TITLE);
                $('#notification_message').val(response[0].NOTIFICATION_MESSAGE);
                $('#system_link').val(response[0].SYSTEM_LINK);
                $('#email_link').val(response[0].EMAIL_LINK);

                check_empty(response[0].ROLE_RECIPIENT.split(','), '#role_recipient', 'select');
                check_empty(response[0].USER_ACCOUNT_RECIPIENT.split(','), '#user_account_recipient', 'select');
            }
        });
    }
    else if(form_type == 'interface setting form'){
        transaction = 'interface settings details';

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {transaction : transaction},
            success: function(response) {
                $('#login-bg').attr('src', response[0].LOGIN_BACKGROUND + '?' + d.getMilliseconds());
                $('#login-logo').attr('src', response[0].LOGIN_LOGO + '?' + d.getMilliseconds());
                $('#menu-logo').attr('src', response[0].MENU_LOGO + '?' + d.getMilliseconds());
                $('#menu-icon').attr('src', response[0].MENU_ICON + '?' + d.getMilliseconds());
                $('#favicon-image').attr('src', response[0].FAVICON + '?' + d.getMilliseconds());
            }
        });
    }
    else if(form_type == 'mail configuration form'){
        transaction = 'mail configuration details';
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {transaction : transaction},
            success: function(response) {
                $('#mail_host').val(response[0].MAIL_HOST);
                $('#port').val(response[0].PORT);
                $('#mail_user').val(response[0].USERNAME);
                $('#mail_password').val(response[0].PASSWORD);
                $('#mail_from_name').val(response[0].MAIL_FROM_NAME);
                $('#mail_from_email').val(response[0].MAIL_FROM_EMAIL);

                check_empty(response[0].MAIL_ENCRYPTION, '#mail_encryption', 'select');
                check_empty(response[0].SMTP_AUTH, '#smtp_auth', 'select');
                check_empty(response[0].SMTP_AUTO_TLS, '#smtp_auto_tls', 'select');
            }
        });
    }
    else if(form_type == 'zoom integration form'){
        transaction = 'zoom integration details';
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {transaction : transaction},
            success: function(response) {
                $('#api_key').val(response[0].API_KEY);
                $('#api_secret').val(response[0].API_SECRET);
            }
        });
    }
    else if(form_type == 'department form'){
        transaction = 'department details';
        
        var department_id = sessionStorage.getItem('department_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {department_id : department_id, transaction : transaction},
            success: function(response) {
                $('#department').val(response[0].DEPARTMENT);
                $('#department_id').val(department_id);

                check_option_exist('#parent_department', response[0].PARENT_DEPARTMENT, '');
                check_option_exist('#manager', response[0].MANAGER, '');
            }
        });
    }
    else if(form_type == 'job position form'){
        transaction = 'job position details';

        var job_position_id = sessionStorage.getItem('job_position_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {job_position_id : job_position_id, transaction : transaction},
            success: function(response) {
                $('#job_position').val(response[0].JOB_POSITION);
                $('#job_position_id').val(job_position_id);
            }
        });
    }
    else if(form_type == 'job position details'){
        transaction = 'job position summary details';

        var job_position_id = sessionStorage.getItem('job_position_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {job_position_id : job_position_id, transaction : transaction},
            success: function(response) {
                $('#job_position').text(response[0].JOB_POSITION);

                document.getElementById('job_description').innerHTML = response[0].JOB_DESCRIPTION;
            }
        });
    }
    else if(form_type == 'work location form'){
        transaction = 'work location details';

        var work_location_id = sessionStorage.getItem('work_location_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {work_location_id : work_location_id, transaction : transaction},
            success: function(response) {
                $('#work_location').val(response[0].WORK_LOCATION);
                $('#street_1').val(response[0].STREET_1);
                $('#street_2').val(response[0].STREET_2);
                $('#city').val(response[0].CITY);
                $('#zip_code').val(response[0].ZIP_CODE);
                $('#email').val(response[0].EMAIL);
                $('#mobile').val(response[0].MOBILE);
                $('#telephone').val(response[0].TELEPHONE);
                $('#work_location_id').val(work_location_id);

                check_option_exist('#state', response[0].STATE_ID, '');
            }
        });
    }
    else if(form_type == 'work location details'){
        transaction = 'work location summary details';

        var work_location_id = sessionStorage.getItem('work_location_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {work_location_id : work_location_id, transaction : transaction},
            success: function(response) {
                $('#work_location').text(response[0].WORK_LOCATION);
                $('#street_1').text(response[0].STREET_1);
                $('#street_2').text(response[0].STREET_2);
                $('#city').text(response[0].CITY);
                $('#state').text(response[0].STATE_ID);
                $('#zip_code').text(response[0].ZIP_CODE);
                $('#work_location_id').text(work_location_id);

                document.getElementById('email').innerHTML = response[0].EMAIL;
                document.getElementById('telephone').innerHTML = response[0].TELEPHONE;
                document.getElementById('mobile').innerHTML = response[0].MOBILE;
            }
        });
    }
    else if(form_type == 'departure reason form'){
        transaction = 'departure reason details';

        var departure_reason_id = sessionStorage.getItem('departure_reason_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {departure_reason_id : departure_reason_id, transaction : transaction},
            success: function(response) {
                $('#departure_reason').val(response[0].DEPARTURE_REASON);
                $('#departure_reason_id').val(departure_reason_id);
            }
        });
    }
    else if(form_type == 'employee type form'){
        transaction = 'employee type details';

        var employee_type_id = sessionStorage.getItem('employee_type_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {employee_type_id : employee_type_id, transaction : transaction},
            success: function(response) {
                $('#employee_type').val(response[0].EMPLOYEE_TYPE);
                $('#employee_type_id').val(employee_type_id);
            }
        });
    }
    else if(form_type == 'employee form'){
        transaction = 'employee details';

        var employee_id = sessionStorage.getItem('employee_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {employee_id : employee_id, transaction : transaction},
            success: function(response) {
                $('#first_name').val(response[0].FIRST_NAME);
                $('#middle_name').val(response[0].MIDDLE_NAME);
                $('#last_name').val(response[0].LAST_NAME);
                $('#work_email').val(response[0].WORK_EMAIL);
                $('#work_mobile').val(response[0].WORK_MOBILE);
                $('#work_telephone').val(response[0].WORK_TELEPHONE);
                $('#badge_id').val(response[0].BADGE_ID);
                $('#onboard_date').val(response[0].ONBOARD_DATE);
                $('#permanency_date').val(response[0].PERMANENCY_DATE);
                $('#sss').val(response[0].SSS);
                $('#tin').val(response[0].TIN);
                $('#philhealth').val(response[0].PHILHEALTH);
                $('#pagibig').val(response[0].PAGIBIG);
                $('#street_1').val(response[0].STREET_1);
                $('#street_2').val(response[0].STREET_2);
                $('#city').val(response[0].CITY);
                $('#zip_code').val(response[0].ZIP_CODE);
                $('#personal_email').val(response[0].PERSONAL_EMAIL);
                $('#personal_mobile').val(response[0].PERSONAL_TELEPHONE);
                $('#personal_telephone').val(response[0].PERSONAL_MOBILE);
                $('#bank_account_number').val(response[0].BANK_ACCOUNT_NUMBER);
                $('#home_work_distance').val(response[0].HOME_WORK_DISTANCE);
                $('#spouse_name').val(response[0].SPOUSE_NAME);
                $('#spouse_birthday').val(response[0].SPOUSE_BIRTHDAY);
                $('#emergency_contact').val(response[0].EMERGENCY_CONTACT);
                $('#emergency_phone').val(response[0].EMERGENCY_PHONE);
                $('#field_of_study').val(response[0].FIELD_OF_STUDY);
                $('#school').val(response[0].SCHOOL);
                $('#identification_number').val(response[0].IDENTIFICATION_NUMBER);
                $('#passport_number').val(response[0].PASSPORT_NUMBER);
                $('#birthday').val(response[0].BIRTHDAY);
                $('#place_of_birth').val(response[0].PLACE_OF_BIRTH);
                $('#number_of_children').val(response[0].NUMBER_OF_CHILDREN);
                $('#visa_number').val(response[0].VISA_NUMBER);
                $('#visa_expiry_date').val(response[0].VISA_EXPIRY_DATE);
                $('#work_permit_number').val(response[0].WORK_PERMIT_NUMBER);
                $('#work_permit_expiry_date').val(response[0].WORK_PERMIT_EXPIRY_DATE);
                $('#employee_id').val(employee_id);

                check_option_exist('#suffix', response[0].SUFFIX, '');
                check_option_exist('#job_position', response[0].JOB_POSITION, '');
                check_option_exist('#department', response[0].DEPARTMENT, '');
                check_option_exist('#manager', response[0].MANAGER, '');
                check_option_exist('#coach', response[0].COACH, '');
                check_option_exist('#company', response[0].COMPANY, '');
                check_option_exist('#work_location', response[0].WORK_LOCATION, '');
                check_option_exist('#employee_type', response[0].EMPLOYEE_TYPE, '');
                check_option_exist('#working_hours', response[0].WORKING_HOURS, '');
                check_option_exist('#state', response[0].STATE_ID, '');
                check_option_exist('#marital_status', response[0].MARITAL_STATUS, '');
                check_option_exist('#certificate_level', response[0].CERTIFICATE_LEVEL, '');
                check_option_exist('#nationality', response[0].NATIONALITY, '');
                check_option_exist('#gender', response[0].GENDER, '');
            }
        });
    }
    else if(form_type == 'working hours form'){
        transaction = 'working hours details';

        var working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#working_hours').val(response[0].WORKING_HOURS);
                $('#working_hours_id').val(working_hours_id);
                
                check_option_exist('#schedule_type', response[0].SCHEDULE_TYPE, '');
            }
        });
    }
    else if(form_type == 'regular working hours form'){
        transaction = 'working hours schedule details';

        var working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#monday_morning_work_from').val(response[0].MONDAY_MORNING_WORK_FROM);
                $('#monday_morning_work_to').val(response[0].MONDAY_MORNING_WORK_TO);
                $('#monday_afternoon_work_from').val(response[0].MONDAY_AFTERNOON_WORK_FROM);
                $('#monday_afternoon_work_to').val(response[0].MONDAY_AFTERNOON_WORK_TO);
                $('#tuesday_morning_work_from').val(response[0].TUESDAY_MORNING_WORK_FROM);
                $('#tuesday_morning_work_to').val(response[0].TUESDAY_MORNING_WORK_TO);
                $('#tuesday_afternoon_work_from').val(response[0].TUESDAY_AFTERNOON_WORK_FROM);
                $('#tuesday_afternoon_work_to').val(response[0].TUESDAY_AFTERNOON_WORK_TO);
                $('#wednesday_morning_work_from').val(response[0].WEDNESDAY_MORNING_WORK_FROM);
                $('#wednesday_morning_work_to').val(response[0].WEDNESDAY_MORNING_WORK_TO);
                $('#wednesday_afternoon_work_from').val(response[0].WEDNESDAY_AFTERNOON_WORK_FROM);
                $('#wednesday_afternoon_work_to').val(response[0].WEDNESDAY_AFTERNOON_WORK_TO);
                $('#thursday_morning_work_from').val(response[0].THURSDAY_MORNING_WORK_FROM);
                $('#thursday_morning_work_to').val(response[0].THURSDAY_MORNING_WORK_TO);
                $('#thursday_afternoon_work_from').val(response[0].THURSDAY_AFTERNOON_WORK_FROM);
                $('#thursday_afternoon_work_to').val(response[0].THURSDAY_AFTERNOON_WORK_TO);
                $('#friday_morning_work_from').val(response[0].FRIDAY_MORNING_WORK_FROM);
                $('#friday_morning_work_to').val(response[0].FRIDAY_MORNING_WORK_TO);
                $('#friday_afternoon_work_from').val(response[0].FRIDAY_AFTERNOON_WORK_FROM);
                $('#friday_afternoon_work_to').val(response[0].FRIDAY_AFTERNOON_WORK_TO);
                $('#saturday_morning_work_from').val(response[0].SATURDAY_MORNING_WORK_FROM);
                $('#saturday_morning_work_to').val(response[0].SATURDAY_MORNING_WORK_TO);
                $('#saturday_afternoon_work_from').val(response[0].SATURDAY_AFTERNOON_WORK_FROM);
                $('#saturday_afternoon_work_to').val(response[0].SATURDAY_AFTERNOON_WORK_TO);
                $('#sunday_morning_work_from').val(response[0].SUNDAY_MORNING_WORK_FROM);
                $('#sunday_morning_work_to').val(response[0].SUNDAY_MORNING_WORK_TO);
                $('#sunday_afternoon_work_from').val(response[0].SUNDAY_AFTERNOON_WORK_FROM);
                $('#sunday_afternoon_work_to').val(response[0].SUNDAY_AFTERNOON_WORK_TO);

                check_empty(response[0].EMPLOYEE.split(','), '#employee', 'select');
                
                $('#working_hours_id').val(working_hours_id);
            }
        });
    }
    else if(form_type == 'scheduled working hours form'){
        transaction = 'working hours schedule details';

        var working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#start_date').val(response[0].START_DATE);
                $('#end_date').val(response[0].END_DATE);
                $('#monday_morning_work_from').val(response[0].MONDAY_MORNING_WORK_FROM);
                $('#monday_morning_work_to').val(response[0].MONDAY_MORNING_WORK_TO);
                $('#monday_afternoon_work_from').val(response[0].MONDAY_AFTERNOON_WORK_FROM);
                $('#monday_afternoon_work_to').val(response[0].MONDAY_AFTERNOON_WORK_TO);
                $('#tuesday_morning_work_from').val(response[0].TUESDAY_MORNING_WORK_FROM);
                $('#tuesday_morning_work_to').val(response[0].TUESDAY_MORNING_WORK_TO);
                $('#tuesday_afternoon_work_from').val(response[0].TUESDAY_AFTERNOON_WORK_FROM);
                $('#tuesday_afternoon_work_to').val(response[0].TUESDAY_AFTERNOON_WORK_TO);
                $('#wednesday_morning_work_from').val(response[0].WEDNESDAY_MORNING_WORK_FROM);
                $('#wednesday_morning_work_to').val(response[0].WEDNESDAY_MORNING_WORK_TO);
                $('#wednesday_afternoon_work_from').val(response[0].WEDNESDAY_AFTERNOON_WORK_FROM);
                $('#wednesday_afternoon_work_to').val(response[0].WEDNESDAY_AFTERNOON_WORK_TO);
                $('#thursday_morning_work_from').val(response[0].THURSDAY_MORNING_WORK_FROM);
                $('#thursday_morning_work_to').val(response[0].THURSDAY_MORNING_WORK_TO);
                $('#thursday_afternoon_work_from').val(response[0].THURSDAY_AFTERNOON_WORK_FROM);
                $('#thursday_afternoon_work_to').val(response[0].THURSDAY_AFTERNOON_WORK_TO);
                $('#friday_morning_work_from').val(response[0].FRIDAY_MORNING_WORK_FROM);
                $('#friday_morning_work_to').val(response[0].FRIDAY_MORNING_WORK_TO);
                $('#friday_afternoon_work_from').val(response[0].FRIDAY_AFTERNOON_WORK_FROM);
                $('#friday_afternoon_work_to').val(response[0].FRIDAY_AFTERNOON_WORK_TO);
                $('#saturday_morning_work_from').val(response[0].SATURDAY_MORNING_WORK_FROM);
                $('#saturday_morning_work_to').val(response[0].SATURDAY_MORNING_WORK_TO);
                $('#saturday_afternoon_work_from').val(response[0].SATURDAY_AFTERNOON_WORK_FROM);
                $('#saturday_afternoon_work_to').val(response[0].SATURDAY_AFTERNOON_WORK_TO);
                $('#sunday_morning_work_from').val(response[0].SUNDAY_MORNING_WORK_FROM);
                $('#sunday_morning_work_to').val(response[0].SUNDAY_MORNING_WORK_TO);
                $('#sunday_afternoon_work_from').val(response[0].SUNDAY_AFTERNOON_WORK_FROM);
                $('#sunday_afternoon_work_to').val(response[0].SUNDAY_AFTERNOON_WORK_TO);

                check_empty(response[0].EMPLOYEE.split(','), '#employee', 'select');
                
                $('#working_hours_id').val(working_hours_id);
            }
        });
    }
    else if(form_type == 'working hours details'){
        transaction = 'working hours summary details';

        var working_hours_id = sessionStorage.getItem('working_hours_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {working_hours_id : working_hours_id, transaction : transaction},
            success: function(response) {
                $('#working_hours').text(response[0].WORKING_HOURS);
                $('#schedule_type').text(response[0].SCHEDULE_TYPE);
                $('#start_date').text(response[0].START_DATE);
                $('#end_date').text(response[0].END_DATE);
                $('#monday_morning_work_from').text(response[0].MONDAY_MORNING_WORK_FROM);
                $('#monday_morning_work_to').text(response[0].MONDAY_MORNING_WORK_TO);
                $('#monday_afternoon_work_from').text(response[0].MONDAY_AFTERNOON_WORK_FROM);
                $('#monday_afternoon_work_to').text(response[0].MONDAY_AFTERNOON_WORK_TO);
                $('#tuesday_morning_work_from').text(response[0].TUESDAY_MORNING_WORK_FROM);
                $('#tuesday_morning_work_to').text(response[0].TUESDAY_MORNING_WORK_TO);
                $('#tuesday_afternoon_work_from').text(response[0].TUESDAY_AFTERNOON_WORK_FROM);
                $('#tuesday_afternoon_work_to').text(response[0].TUESDAY_AFTERNOON_WORK_TO);
                $('#wednesday_morning_work_from').text(response[0].WEDNESDAY_MORNING_WORK_FROM);
                $('#wednesday_morning_work_to').text(response[0].WEDNESDAY_MORNING_WORK_TO);
                $('#wednesday_afternoon_work_from').text(response[0].WEDNESDAY_AFTERNOON_WORK_FROM);
                $('#wednesday_afternoon_work_to').text(response[0].WEDNESDAY_AFTERNOON_WORK_TO);
                $('#thursday_morning_work_from').text(response[0].THURSDAY_MORNING_WORK_FROM);
                $('#thursday_morning_work_to').text(response[0].THURSDAY_MORNING_WORK_TO);
                $('#thursday_afternoon_work_from').text(response[0].THURSDAY_AFTERNOON_WORK_FROM);
                $('#thursday_afternoon_work_to').text(response[0].THURSDAY_AFTERNOON_WORK_TO);
                $('#friday_morning_work_from').text(response[0].FRIDAY_MORNING_WORK_FROM);
                $('#friday_morning_work_to').text(response[0].FRIDAY_MORNING_WORK_TO);
                $('#friday_afternoon_work_from').text(response[0].FRIDAY_AFTERNOON_WORK_FROM);
                $('#friday_afternoon_work_to').text(response[0].FRIDAY_AFTERNOON_WORK_TO);
                $('#saturday_morning_work_from').text(response[0].SATURDAY_MORNING_WORK_FROM);
                $('#saturday_morning_work_to').text(response[0].SATURDAY_MORNING_WORK_TO);
                $('#saturday_afternoon_work_from').text(response[0].SATURDAY_AFTERNOON_WORK_FROM);
                $('#saturday_afternoon_work_to').text(response[0].SATURDAY_AFTERNOON_WORK_TO);
                $('#sunday_morning_work_from').text(response[0].SUNDAY_MORNING_WORK_FROM);
                $('#sunday_morning_work_to').text(response[0].SUNDAY_MORNING_WORK_TO);
                $('#sunday_afternoon_work_from').text(response[0].SUNDAY_AFTERNOON_WORK_FROM);
                $('#sunday_afternoon_work_to').text(response[0].SUNDAY_AFTERNOON_WORK_TO);

                document.getElementById('employee').innerHTML = response[0].EMPLOYEE_TABLE;
            }
        });
    }
    else if(form_type == 'attendance setting form'){
        transaction = 'attendance setting details';
  
        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {transaction : transaction},
            success: function(response) {
                $('#maximum_attendance').val(response[0].MAX_ATTENDANCE);
                $('#late_grace_period').val(response[0].LATE_GRACE_PERIOD);
                $('#time_out_interval').val(response[0].TIME_OUT_INTERVAL);
                $('#late_policy').val(response[0].LATE_POLICY);
                $('#early_leaving_policy').val(response[0].EARLY_LEAVING_POLICY);
                $('#overtime_policy').val(response[0].OVERTIME_POLICY);

                if(response[0].ATTENDANCE_CREATION_RECOMMENDATION == 1){
                    $('#attendance_creation_recommendation').prop('checked', true);
                }
                else{
                    $('#attendance_creation_recommendation').prop('checked', false);
                }

                if(response[0].ATTENDANCE_CREATION_APPROVAL == 1){
                    $('#attendance_creation_approval').prop('checked', true);
                }
                else{
                    $('#attendance_creation_approval').prop('checked', false);
                }

                if(response[0].ATTENDANCE_ADJUSTMENT_RECOMMENDATION == 1){
                    $('#attendance_adjustment_recommendation').prop('checked', true);
                }
                else{
                    $('#attendance_adjustment_recommendation').prop('checked', false);
                }

                if(response[0].ATTENDANCE_ADJUSTMENT_APPROVAL == 1){
                    $('#attendance_adjustment_approval').prop('checked', true);
                }
                else{
                    $('#attendance_adjustment_approval').prop('checked', false);
                }
               
                check_empty(response[0].ATTENDANCE_CREATION_RECOMMENDATION_EXCEPTION.split(','), '#attendance_creation_recommendation_exception', 'select');
                check_empty(response[0].ATTENDANCE_CREATION_APPROVAL_EXCEPTION.split(','), '#attendance_creation_approval_exception', 'select');
                check_empty(response[0].ATTENDANCE_ADJUSTMENT_RECOMMENDATION_EXCEPTION.split(','), '#attendance_adjustment_recommendation_exception', 'select');
                check_empty(response[0].ATTENDANCE_ADJUSTMENT_APPROVAL_EXCEPTION.split(','), '#attendance_adjustment_approval_exception', 'select');
            },
            complete: function(){
                if($('#attendance_creation_recommendation').is(':checked')){
                    document.getElementById('attendance_creation_recommendation_exception').disabled = false;
                }
                else{
                    document.getElementById('attendance_creation_recommendation_exception').disabled = true;
                }

                if($('#attendance_creation_approval').is(':checked')){
                    document.getElementById('attendance_creation_approval_exception').disabled = false;
                }
                else{
                    document.getElementById('attendance_creation_approval_exception').disabled = true;
                }

                if($('#attendance_adjustment_recommendation').is(':checked')){
                    document.getElementById('attendance_adjustment_recommendation_exception').disabled = false;
                }
                else{
                    document.getElementById('attendance_adjustment_recommendation_exception').disabled = true;
                }

                if($('#attendance_adjustment_approval').is(':checked')){
                    document.getElementById('attendance_adjustment_approval_exception').disabled = false;
                }
                else{
                    document.getElementById('attendance_adjustment_approval_exception').disabled = true;
                }
            }
        });
    }
    else if(form_type == 'attendance form'){
        transaction = 'attendance details';

        var attendance_id = sessionStorage.getItem('attendance_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attendance_id : attendance_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#time_out_date').val(response[0].TIME_OUT_DATE);
                $('#time_out_time').val(response[0].TIME_OUT);
                $('#remarks').val(response[0].REMARKS);
                $('#attendance_id').val(attendance_id);

                check_option_exist('#employee_id', response[0].EMPLOYEE_ID, '');
            },
            complete: function(){
                document.getElementById('employee_id').disabled = true;
            }
        });
    }
    else if(form_type == 'attendance details'){
        transaction = 'attendance summary details';

        var attendance_id = sessionStorage.getItem('attendance_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attendance_id : attendance_id, transaction : transaction},
            success: function(response) {
                $('#employee').text(response[0].EMPLOYEE);
                $('#late').text(response[0].LATE);
                $('#early_leave').text(response[0].EARLY_LEAVING);
                $('#overtime').text(response[0].OVERTIME);
                $('#total_working_hours').text(response[0].TOTAL_WORKING_HOURS);
                $('#remarks').text(response[0].REMARKS);
                $('#time_in').text(response[0].TIME_IN);
                document.getElementById('time_in_behavior').innerHTML = response[0].TIME_IN_BEHAVIOR;
                document.getElementById('time_in_location').innerHTML = response[0].TIME_IN_LOCATION;
                $('#time_in_ip_address').text(response[0].TIME_IN_IP_ADDRESS);
                $('#time_in_by').text(response[0].TIME_IN_BY);
                $('#time_in_note').text(response[0].TIME_IN_NOTE);
                $('#time_out').text(response[0].TIME_OUT);
                document.getElementById('time_out_behavior').innerHTML = response[0].TIME_OUT_BEHAVIOR;
                document.getElementById('time_out_location').innerHTML = response[0].TIME_OUT_LOCATION;
                $('#time_out_ip_address').text(response[0].TIME_OUT_IP_ADDRESS);
                $('#time_out_by').text(response[0].TIME_OUT_BY);
                $('#time_out_note').text(response[0].TIMEOUT_NOTE);

                document.getElementById('attendance_adjustment').innerHTML = response[0].ATTENDANCE_ADJUSTMENT;
            }
        });
    }
    else if(form_type == 'request full attendance adustment form'){
        transaction = 'attendance details';

        var attendance_id = sessionStorage.getItem('attendance_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attendance_id : attendance_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#time_out_date').val(response[0].TIME_OUT_DATE);
                $('#time_out_time').val(response[0].TIME_OUT);
                $('#employee_id').val(response[0].EMPLOYEE_ID);
                $('#attendance_id').val(attendance_id);
            }
        });
    }
    else if(form_type == 'request partial attendance adustment form'){
        transaction = 'attendance details';

        var attendance_id = sessionStorage.getItem('attendance_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attendance_id : attendance_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#employee_id').val(response[0].EMPLOYEE_ID);
                $('#attendance_id').val(attendance_id);
            }
        });
    }
    else if(form_type == 'request attendance adjustment form'){
        transaction = 'attendance details';

        var attendance_id = sessionStorage.getItem('attendance_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {attendance_id : attendance_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#time_out_date').val(response[0].TIME_OUT_DATE);
                $('#time_out_time').val(response[0].TIME_OUT);
                $('#employee_id').val(response[0].EMPLOYEE_ID);
                $('#attendance_id').val(attendance_id);

                if(response[0].TIME_OUT_DATE && response[0].TIME_OUT){
                    $('#time-out-section').removeClass('d-none');
                    $('#request_type').val('full');
                }
                else{
                    $('#time-out-section').addClass('d-none');
                    $('#request_type').val('partial');
                }
            }
        });
    }
    else if(form_type == 'update full attendance adustment form'){
        transaction = 'attendance adjustment details';

        var adjustment_id = sessionStorage.getItem('adjustment_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {adjustment_id : adjustment_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#time_out_date').val(response[0].TIME_OUT_DATE);
                $('#time_out_time').val(response[0].TIME_OUT);
                $('#reason').val(response[0].REASON);
                $('#adjustment_id').val(adjustment_id);
            }
        });
    }
    else if(form_type == 'update partial attendance adustment form'){
        transaction = 'attendance adjustment details';

        var adjustment_id = sessionStorage.getItem('adjustment_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {adjustment_id : adjustment_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#reason').val(response[0].REASON);
                $('#adjustment_id').val(adjustment_id);
            }
        });
    }
    else if(form_type == 'attendance adjustment details'){
        transaction = 'attendance adjustment summary details';

        var adjustment_id = sessionStorage.getItem('adjustment_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {adjustment_id : adjustment_id, transaction : transaction},
            success: function(response) {
                $('#employee').text(response[0].EMPLOYEE);
                $('#time_in').text(response[0].TIME_IN);
                $('#time_out').text(response[0].TIME_OUT);
                $('#reason').text(response[0].REASON);
                $('#created_date').text(response[0].CREATED_DATE);
                $('#for_recommendation_date').text(response[0].FOR_RECOMMENDATION_DATE);
                $('#recommendation_date').text(response[0].RECOMMENDATION_DATE);
                $('#recommendation_by').text(response[0].RECOMMENDATION_BY);
                $('#recommendation_remarks').text(response[0].RECOMMENDATION_REMARKS);
                $('#decision_date').text(response[0].DECISION_DATE);
                $('#decision_by').text(response[0].DECISION_BY);
                $('#decision_remarks').text(response[0].DECISION_REMARKS);
                
                document.getElementById('attachment').innerHTML = response[0].ATTACHMENT;
                document.getElementById('adjustment_status').innerHTML = response[0].STATUS;
                document.getElementById('sanction').innerHTML = response[0].SANCTION;
            }
        });
    }
    else if(form_type == 'attendance creation details'){
        transaction = 'attendance creation summary details';

        var creation_id = sessionStorage.getItem('creation_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {creation_id : creation_id, transaction : transaction},
            success: function(response) {
                $('#employee').text(response[0].EMPLOYEE);
                $('#time_in').text(response[0].TIME_IN);
                $('#time_out').text(response[0].TIME_OUT);
                $('#reason').text(response[0].REASON);
                $('#created_date').text(response[0].CREATED_DATE);
                $('#for_recommendation_date').text(response[0].FOR_RECOMMENDATION_DATE);
                $('#recommendation_date').text(response[0].RECOMMENDATION_DATE);
                $('#recommendation_by').text(response[0].RECOMMENDATION_BY);
                $('#recommendation_remarks').text(response[0].RECOMMENDATION_REMARKS);
                $('#decision_date').text(response[0].DECISION_DATE);
                $('#decision_by').text(response[0].DECISION_BY);
                $('#decision_remarks').text(response[0].DECISION_REMARKS);
                
                document.getElementById('attachment').innerHTML = response[0].ATTACHMENT;
                document.getElementById('creation_status').innerHTML = response[0].STATUS;
                document.getElementById('sanction').innerHTML = response[0].SANCTION;
            }
        });
    }
    else if(form_type == 'update attendance creation form'){
        transaction = 'attendance creation details';

        var creation_id = sessionStorage.getItem('creation_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {creation_id : creation_id, transaction : transaction},
            success: function(response) {
                $('#time_in_date').val(response[0].TIME_IN_DATE);
                $('#time_in_time').val(response[0].TIME_IN);
                $('#time_out_date').val(response[0].TIME_OUT_DATE);
                $('#time_out_time').val(response[0].TIME_OUT);
                $('#reason').val(response[0].REASON);
                $('#creation_id').val(creation_id);
            }
        });
    }
    else if(form_type == 'approval type form'){
        transaction = 'approval type details';

        var approval_type_id = sessionStorage.getItem('approval_type_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {approval_type_id : approval_type_id, transaction : transaction},
            success: function(response) {
                $('#approval_type').val(response[0].APPROVAL_TYPE);
                $('#approval_type_description').val(response[0].APPROVAL_TYPE_DESCRIPTION);
                $('#approval_type_id').val(approval_type_id);
            }
        });
    }
    else if(form_type == 'approval type details'){
        transaction = 'approval type summary details';

        var approval_type_id = sessionStorage.getItem('approval_type_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {approval_type_id : approval_type_id, transaction : transaction},
            success: function(response) {
                $('#approval_type').text(response[0].APPROVAL_TYPE);
                $('#approval_type_description').text(response[0].APPROVAL_TYPE_DESCRIPTION);

                document.getElementById('approvers').innerHTML = response[0].APPROVER_TABLE;
                document.getElementById('exceptions').innerHTML = response[0].APPROVAL_EXCEPTION_TABLE;
            }
        });
    }
    else if(form_type == 'public holiday form'){
        transaction = 'public holiday details';

        var public_holiday_id = sessionStorage.getItem('public_holiday_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {public_holiday_id : public_holiday_id, transaction : transaction},
            success: function(response) {
                $('#public_holiday').val(response[0].PUBLIC_HOLIDAY);
                $('#holiday_date').val(response[0].HOLIDAY_DATE);
                $('#public_holiday_id').val(public_holiday_id);

                check_option_exist('#holiday_type', response[0].HOLIDAY_TYPE, '');
                check_empty(response[0].WORK_LOCATION_ID.split(','), '#work_location', 'select');
            }
        });
    }
    else if(form_type == 'public holiday details'){
        transaction = 'public holiday summary details';

        var public_holiday_id = sessionStorage.getItem('public_holiday_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {public_holiday_id : public_holiday_id, transaction : transaction},
            success: function(response) {
                $('#public_holiday').text(response[0].PUBLIC_HOLIDAY);
                $('#holiday_date').text(response[0].HOLIDAY_DATE);
                $('#holiday_type').text(response[0].HOLIDAY_TYPE);

                document.getElementById('work_location').innerHTML = response[0].WORK_LOCATION_TABLE;
            }
        });
    }
    else if(form_type == 'leave type form'){
        transaction = 'leave type details';

        var leave_type_id = sessionStorage.getItem('leave_type_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {leave_type_id : leave_type_id, transaction : transaction},
            success: function(response) {
                $('#leave_type').val(response[0].LEAVE_TYPE);
                $('#leave_type_id').val(leave_type_id);

                check_option_exist('#paid_type', response[0].PAID_TYPE, '');
                check_option_exist('#leave_allocation_type', response[0].ALLOCATION_TYPE, '');
            }
        });
    }
    else if(form_type == 'leave allocation form'){
        transaction = 'leave allocation details';

        var leave_allocation_id = sessionStorage.getItem('leave_allocation_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {leave_allocation_id : leave_allocation_id, transaction : transaction},
            success: function(response) {
                $('#duration').val(response[0].DURATION);
                $('#validity_start_date').val(response[0].VALIDITY_START_DATE);
                $('#validity_end_date').val(response[0].VALIDITY_END_DATE);
                $('#leave_allocation_id').val(leave_allocation_id);

                check_option_exist('#employee_id', response[0].EMPLOYEE_ID, '');
                check_option_exist('#leave_type', response[0].LEAVE_TYPE_ID, '');
            },
            complete: function(){
                document.getElementById('employee_id').disabled = true;
            }
        });
    }
    else if(form_type == 'update leave form'){
        transaction = 'leave details';

        var leave_id = sessionStorage.getItem('leave_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {leave_id : leave_id, transaction : transaction},
            success: function(response) {
                $('#leave_date').val(response[0].LEAVE_DATE);
                $('#start_time').val(response[0].START_TIME);
                $('#end_time').val(response[0].END_TIME);
                $('#reason').val(response[0].REASON);
                $('#leave_id').val(leave_id);

                check_option_exist('#leave_type', response[0].LEAVE_TYPE_ID, '');
            }
        });
    }
    else if(form_type == 'add leave supporting document form'){
        var leave_id = sessionStorage.getItem('leave_id');
        $('#leave_id').val(leave_id);

        if($('#leave-supporting-document-table').length){
            initialize_leave_supporting_document_table('#leave-supporting-document-table');
        }
    }
    else if(form_type == 'leave details'){
        transaction = 'leave summary details';

        var leave_id = sessionStorage.getItem('leave_id');

        $.ajax({
            url: 'controller.php',
            method: 'POST',
            dataType: 'JSON',
            data: {leave_id : leave_id, transaction : transaction},
            success: function(response) {
                $('#employee').text(response[0].EMPLOYEE);
                $('#leave_type').text(response[0].LEAVE_TYPE);
                $('#leave_date').text(response[0].LEAVE_DATE);
                $('#start_time').text(response[0].START_TIME);
                $('#end_time').text(response[0].END_TIME);
                $('#total_hours').text(response[0].TOTAL_HOURS);
                $('#reason').text(response[0].REASON);
                $('#created_date').text(response[0].CREATED_DATE);
                $('#for_approval_date').text(response[0].FOR_APPROVAL_DATE);
                $('#decision_date').text(response[0].DECISION_DATE);
                $('#decision_by').text(response[0].DECISION_BY);
                $('#decision_remarks').text(response[0].DECISION_REMARKS);
                document.getElementById('leave_status').innerHTML = response[0].STATUS;

                document.getElementById('supporting_documents').innerHTML = response[0].SUPPORTING_DOCUMENTS;
            }
        });
    }
}

function initialize_transaction_log_table(datatable_name, buttons = false, show_all = false){
    var username = $('#username').text();
    var transaction_log_id = sessionStorage.getItem('transaction_log_id');
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
    var type = 'system modal';

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
    var type = 'system form';

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
                /*if(form_type == 'module access form'){
                    var module_id = $('#module-id').text();

                    $('#module_id').val(module_id);
                }*/
            }

            initialize_elements();
            initialize_form_validation(form_type);

            $('#System-Modal').modal('show');
        }
    });    
}

function generate_element(element_type, value, container, modal, username){
    var type = 'system element';

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
    var username = $('#username').text();
    var type = 'city options';

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

function generate_pay_run_payee_option(pay_run_id){
    var username = $('#username').text();
    var type = 'pay run payee options';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        dataType: 'JSON',
        data: {type : type, pay_run_id : pay_run_id, username : username},
        beforeSend: function(){
            $('#payee').empty();
        },
        success: function(response) {
            for(var i = 0; i < response.length; i++) {
                newOption = new Option(response[i].FILE_AS, response[i].EMPLOYEE_ID, false, false);
                $('#payee').append(newOption);
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

// Truncate functions
function truncate_temporary_table(table_name){
    var transaction = 'truncate temporary table';

    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'TEXT',
        data: {table_name : table_name, transaction : transaction},
        success: function(response) {
            if($('#import-attendance-record-datatable').length){
                initialize_temporary_attendance_record_table('#import-attendance-record-datatable', false, true);
            }

            if($('#import-employee-datatable').length){
                initialize_temporary_employee_table('#import-employee-datatable', false, true);
            }

            if($('#import-leave-entitlement-datatable').length){
                initialize_temporary_leave_entitlement_table('#import-leave-entitlement-datatable', false, true);
            }

            if($('#import-leave-datatable').length){
                initialize_temporary_leave_table('#import-leave-datatable', false, true);
            }

            if($('#import-attendance-adjustment-datatable').length){
                initialize_temporary_attendance_adjustment_table('#import-attendance-adjustment-datatable', false, true);
            }

            if($('#import-attendance-creation-datatable').length){
                initialize_temporary_attendance_creation_table('#import-attendance-creation-datatable', false, true);
            }

            if($('#import-allowance-datatable').length){
                initialize_temporary_allowance_table('#import-allowance-datatable', false, true);
            }

            if($('#import-deduction-datatable').length){
                initialize_temporary_deduction_table('#import-deduction-datatable', false, true);
            }

            if($('#import-government-contribution-datatable').length){
                initialize_temporary_government_contribution_table('#import-government-contribution-datatable', false, true);
            }

            if($('#import-contribution-bracket-datatable').length){
                initialize_temporary_contribution_bracket_table('#import-contribution-bracket-datatable', false, true);
            }

            if($('#import-contribution-deduction-datatable').length){
                initialize_temporary_contribution_deduction_table('#import-contribution-deduction-datatable', false, true);
            }

            if($('#import-withholding-tax-datatable').length){
                initialize_temporary_withholding_tax_table('#import-withholding-tax-datatable', false, true);
            }

            if($('#import-other-income-datatable').length){
                initialize_temporary_other_income_table('#import-other-income-datatable', false, true);
            }
        }
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

    var card = 'BEGIN:VCARD\r\n';
    card += 'VERSION:3.0\r\n';
    card += 'FN:'+ name +'\r\n';
    card += 'EMAIL:' + email +'\r\n';
    card += 'ID NO:[' + employee_id + ']\r\n';

    if(mobile){
        card += 'TEL:' + mobile +'\r\n';
    }
    
    card += 'END:VCARD';

    var qrcode = new QRCode(document.getElementById(container), {
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
}

// Form validation rules
// Rule for password strength
$.validator.addMethod('password_strength', function(value) {
    if(value != ''){
        var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        return re.test(value);
    }
    else{
        return true;
    }

}, 'Password must contain at least 1 lowercase, uppercase letter, number, special character and must be 8 characters or longer');

// Rule for legal age
$.validator.addMethod('employee_age', function(value, element, min) {
    var today = new Date();
    var birthDate = new Date(value);
    var age = today.getFullYear() - birthDate.getFullYear();
  
    if (age > min+1) { return true; }
  
    var m = today.getMonth() - birthDate.getMonth();
  
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) { age--; }
  
    return age >= min;
}, 'The employee must be at least 18 years old and above');