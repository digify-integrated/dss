(function($) {
    'use strict';

    $(function() {
        initialize_global_functions();
    });
})(jQuery);

// Initialize function
function initialize_global_functions(){
    check_toastr();

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

    $(document).on('click','#form-edit',function() {
       $('.form-details').addClass('d-none');
       $('.form-edit').removeClass('d-none');

       display_details();
    });

    $(document).on('click','#discard',() => {
        Swal.fire({
            title: 'Discard Changes Confirmation',
            text: 'Are you sure you want to discard the changes made to this item? The changes will be lost permanently once discarded.',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Discard',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                reset_form();
            }
        });
    });

    if ($('.select2').length) {
        $('.select2').select2({}).on("change", function (e) {
            $(this).valid()
        });
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

    if ($('.form-date-picker').length) {
        $('.form-date-picker').datepicker({
            orientation: 'auto top'
        }).on('change', function (e) {
            $(this).valid()
        });;
    }

    if ($('.birthday-date-picker').length) {
        $('.birthday-date-picker').datepicker({
            orientation: 'auto top',
            endDate: new Date()
        }).on('change', function (e) {
            $(this).valid()
        });;
    }
}

function initialize_elements(){
    var form_max_length = $('.form-maxlength');
    var formSelect2 = $('.form-select2');

    if (form_max_length.length) {
        form_max_length.maxlength({
            alwaysShow: true,
            warningClass: 'badge mt-1 bg-info',
            limitReachedClass: 'badge mt-1 bg-danger',
            validate: true
        });
    }

    if (formSelect2.length) {
        formSelect2.select2({
            dropdownParent: $('#System-Modal')
        });

        formSelect2.on('select2:close', function (e) {  
            $(this).valid(); 
        });
    }
}

function initialize_form_validation(form_type){
    var transaction;
    const username = $('#username').text();

    switch (form_type) {
        case 'module access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Module Access Inserted', 'The module access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#module-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'page access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Page Access Inserted', 'The page access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#page-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'action access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Action Access Inserted', 'The action access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#action-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'role module access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Module Access Inserted', 'The module access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                reload_datatable('#module-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'role page access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Page Access Inserted', 'The page access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#page-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'role action access form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Action Access Inserted', 'The action access has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#action-access-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'role user account form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('User Account Inserted', 'The user account has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#user-account-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'upload setting file type form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('File Type Inserted', 'The file type has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#upload-setting-file-type-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'notification role recipient form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Notification Role Recipient Inserted', 'The notification role recipient has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#notification-role-recipients-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'notification user account recipient form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Notification User Account Recipient Inserted', 'The notification user account recipient has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#notification-user-account-recipients-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'notification channel form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Notification Channel Inserted', 'The notification channel has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#notification-channel-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'state form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('State Inserted', 'The state has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#state-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('State Updated', 'The state has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#state-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'user account role form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('User Account Role Inserted', 'The user account role has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#user-account-role-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('User Account Role Updated', 'The user account role has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#user-account-role-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
                            }
                        },
                        complete: function(){
                            document.getElementById('submit-form').disabled = false;
                            $('#submit-form').html('Submit');
                        }
                    });
                    return false;
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
          break;
        case 'job position responsibility form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Responsibility Inserted', 'The responsibility has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-responsibility-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Responsibility Updated', 'The responsibility has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-responsibility-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'job position requirement form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Requirement Inserted', 'The requirement has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-requirement-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Requirement Updated', 'The requirement has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-requirement-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'job position qualification form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Qualification Inserted', 'The qualification has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-qualification-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Qualification Updated', 'The qualification has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-qualification-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'job position attachment form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Attachment Inserted', 'The attachment has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-attachment-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Attachment Updated', 'The attachment has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#job-position-attachment-datatable');
                                    break;
                                case 'File Size':
                                    show_toastr('Attachment Upload Error', 'The file uploaded exceeds the maximum file size.', 'error');
                                    break;
                                case 'File Type':
                                    show_toastr('Attachment Upload Error', 'The file uploaded is not supported.', 'error');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'fixed working hours form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Working Hours Inserted', 'The working hours has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#working-hours-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Working Hours Updated', 'The working hours has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#working-hours-datatable');
                                    break;
                                case 'Overlap':
                                    show_toastr('Overlapping Working Hours Detected', 'The working hours cannot overlap with other working hours. Please adjust your schedule accordingly.', 'warning');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
        case 'flexible working hours form':
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
                            switch (response) {
                                case 'Inserted':
                                    show_toastr('Working Hours Inserted', 'The working hours has been inserted successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#working-hours-datatable');
                                    break;
                                case 'Updated':
                                    show_toastr('Working Hours Updated', 'The working hours has been updated successfully.', 'success');
                                    $('#System-Modal').modal('hide');
                                    reload_datatable('#working-hours-datatable');
                                    break;
                                case 'Overlap':
                                    show_toastr('Overlapping Working Hours Detected', 'The working hours cannot overlap with other working hours. Please adjust your schedule accordingly.', 'warning');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Transaction Error', response, 'error');
                                    break;
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
          break;
    }
}

// Display functions
function display_form_details(form_type){
    var transaction;

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

function display_details(transaction){
    switch (transaction) {
        case 'action details':
            const action_id = $('#action-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {action_id : action_id, transaction : transaction},
                success: function(response) {
                    $('#action_name').val(response[0].ACTION_NAME);
                    
                    $('#action_name_label').text(response[0].ACTION_NAME);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'company details':
            const company_id = $('#company-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {company_id : company_id, transaction : transaction},
                success: function(response) {
                    $('#company_name').val(response[0].COMPANY_NAME);
                    $('#company_address').val(response[0].COMPANY_ADDRESS);
                    $('#tax_id').val(response[0].TAX_ID);
                    $('#email').val(response[0].EMAIL);
                    $('#mobile').val(response[0].MOBILE);
                    $('#telephone').val(response[0].TELEPHONE);
                    $('#website').val(response[0].WEBSITE);

                    $('#company_name_label').text(response[0].COMPANY_NAME);
                    $('#company_address_label').text(response[0].COMPANY_ADDRESS);
                    $('#tax_id_label').text(response[0].TAX_ID);
                    $('#email_label').text(response[0].EMAIL);
                    $('#mobile_label').text(response[0].MOBILE);
                    $('#telephone_label').text(response[0].TELEPHONE);
                    $('#website_label').text(response[0].WEBSITE);
                            
                    document.getElementById('company_logo_image').innerHTML = response[0].COMPANY_LOGO;

                    $('#company_id').val(company_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'country details':
            const country_id = $('#country-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {country_id : country_id, transaction : transaction},
                success: function(response) {
                    $('#country_name').val(response[0].COUNTRY_NAME);
                    
                    $('#country_name_label').text(response[0].COUNTRY_NAME);

                    $('#country_id').val(country_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'department details':
            const department_id = $('#department-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {department_id : department_id, transaction : transaction},
                success: function(response) {
                    $('#department').val(response[0].DEPARTMENT);

                    $('#department_label').text(response[0].DEPARTMENT);
                    $('#parent_department_label').text(response[0].PARENT_DEPARTMENT_NAME);
                    $('#manager_label').text(response[0].MANAGER_NAME);

                    document.getElementById('department_status').innerHTML = response[0].STATUS;

                    check_empty(response[0].PARENT_DEPARTMENT, '#parent_department', 'select');
                    check_empty(response[0].MANAGER, '#manager', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'departure reason details':
            const departure_reason_id = $('#departure-reason-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {departure_reason_id : departure_reason_id, transaction : transaction},
                success: function(response) {
                    $('#departure_reason').val(response[0].DEPARTURE_REASON);
                    
                    $('#departure_reason_label').text(response[0].DEPARTURE_REASON);
                            
                    $('#departure_reason_id').val(departure_reason_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'email setting details':
            const email_setting_id = $('#email-setting-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {email_setting_id : email_setting_id, transaction : transaction},
                success: function(response) {
                    $('#email_setting_name').val(response[0].EMAIL_SETTING_NAME);
                    $('#mail_host').val(response[0].MAIL_HOST);
                    $('#description').val(response[0].DESCRIPTION);
                    $('#mail_username').val(response[0].MAIL_USERNAME);
                    $('#mail_from_name').val(response[0].MAIL_FROM_NAME);
                    $('#port').val(response[0].PORT);
                    $('#mail_password').val(response[0].MAIL_PASSWORD);
                    $('#mail_from_email').val(response[0].MAIL_FROM_EMAIL);

                    $('#email_setting_name_label').text(response[0].EMAIL_SETTING_NAME);
                    $('#mail_host_label').text(response[0].MAIL_HOST);
                    $('#description_label').text(response[0].DESCRIPTION);
                    $('#mail_username_label').text(response[0].MAIL_USERNAME);
                    $('#mail_from_name_label').text(response[0].MAIL_FROM_NAME);
                    $('#port_label').text(response[0].PORT);
                    $('#mail_password_label').text(response[0].MAIL_PASSWORD);
                    $('#mail_from_email_label').text(response[0].MAIL_FROM_EMAIL);
                    $('#mail_encryption_label').text(response[0].MAIL_ENCRYPTION_NAME);
                    $('#smtp_auth_label').text(response[0].SMTP_AUTH_NAME);
                    $('#smtp_auto_tls_label').text(response[0].SMTP_AUTO_TLS_NAME);

                    document.getElementById('email_setting_status').innerHTML = response[0].STATUS;

                    check_empty(response[0].MAIL_ENCRYPTION, '#mail_encryption', 'select');
                    check_empty(response[0].SMTP_AUTH, '#smtp_auth', 'select');
                    check_empty(response[0].SMTP_AUTO_TLS, '#smtp_auto_tls', 'select');

                    $('#email_setting_id').val(email_setting_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'employee type details':
            const employee_type_id = $('#employee-type-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {employee_type_id : employee_type_id, transaction : transaction},
                success: function(response) {
                    $('#employee_type').val(response[0].EMPLOYEE_TYPE);
                    
                    $('#employee_type_label').text(response[0].EMPLOYEE_TYPE);
                            
                    $('#employee_type_id').val(employee_type_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'id type details':
            const id_type_id = $('#id-type-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {id_type_id : id_type_id, transaction : transaction},
                success: function(response) {
                    $('#id_type').val(response[0].ID_TYPE);
                    
                    $('#id_type_label').text(response[0].ID_TYPE);
                            
                    $('#id_type_id').val(id_type_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'interface setting details':
            const interface_setting_id = $('#interface-setting-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {interface_setting_id : interface_setting_id, transaction : transaction},
                success: function(response) {
                    $('#interface_setting_name').val(response[0].INTERFACE_SETTING_NAME);
                    $('#description').val(response[0].DESCRIPTION);
        
                    $('#interface_setting_name_label').text(response[0].INTERFACE_SETTING_NAME);
                    $('#description_label').text(response[0].DESCRIPTION);
                            
                    document.getElementById('interface_setting_status').innerHTML = response[0].STATUS;
                    document.getElementById('login_background_image').innerHTML = response[0].LOGIN_BACKGROUND;
                    document.getElementById('login_logo_image').innerHTML = response[0].LOGIN_LOGO;
                    document.getElementById('menu_logo_image').innerHTML = response[0].MENU_LOGO;
                    document.getElementById('favicon_image').innerHTML = response[0].FAVICON;
        
                    $('#interface_setting_id').val(interface_setting_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'job position details':
            const job_position_id = $('#job-position-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {job_position_id : job_position_id, transaction : transaction},
                success: function(response) {
                    $('#job_position').val(response[0].JOB_POSITION);
                    $('#description').val(response[0].DESCRIPTION);
                    $('#expected_new_employees').val(response[0].EXPECTED_NEW_EMPLOYEES);

                    $('#job_position_label').text(response[0].JOB_POSITION);
                    $('#description_label').text(response[0].DESCRIPTION);
                    $('#expected_new_employees_label').text(response[0].EXPECTED_NEW_EMPLOYEES);
                    $('#department_label').text(response[0].DEPARTMENT_NAME);

                    document.getElementById('job_position_recruitment_status').innerHTML = response[0].RECRUITMENT_STATUS;

                    check_empty(response[0].DEPARTMENT, '#department', 'select');

                    $('#job_position_id').val(job_position_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'module details':
            const module_id = $('#module-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {module_id : module_id, transaction : transaction},
                success: function(response) {
                    $('#module_name').val(response[0].MODULE_NAME);
                    $('#module_description').val(response[0].MODULE_DESCRIPTION);
                    $('#module_version').val(response[0].MODULE_VERSION);
                    $('#default_page').val(response[0].DEFAULT_PAGE);
                    $('#order_sequence').val(response[0].ORDER_SEQUENCE);

                    $('#module_name_label').text(response[0].MODULE_NAME);
                    $('#module_description_label').text(response[0].MODULE_DESCRIPTION);
                    $('#module_version_label').text(response[0].MODULE_VERSION);
                    $('#default_page_label').text(response[0].DEFAULT_PAGE);
                    $('#order_sequence_label').text(response[0].ORDER_SEQUENCE);
                    $('#module_category_label').text(response[0].MODULE_CATEGORY_NAME);

                    document.getElementById('module_icon_image').innerHTML = response[0].MODULE_ICON;
                            
                    $('#module_id').val(module_id);

                    check_empty(response[0].MODULE_CATEGORY, '#module_category', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'notification setting details':
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
            break;
        case 'page details':
            const page_id = $('#page-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {page_id : page_id, transaction : transaction},
                success: function(response) {
                    $('#page_name').val(response[0].PAGE_NAME);

                    $('#page_name_label').text(response[0].PAGE_NAME);
                    $('#module_id_label').text(response[0].MODULE_NAME);

                    check_empty(response[0].MODULE_ID, '#module_id', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'role details':
            const role_id = $('#role-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {role_id : role_id, transaction : transaction},
                success: function(response) {
                    $('#role').val(response[0].ROLE);
                    $('#role_description').val(response[0].ROLE_DESCRIPTION);
        
                    $('#role_label').text(response[0].ROLE);
                    $('#role_description_label').text(response[0].ROLE_DESCRIPTION);
                    $('#assignable_label').text(response[0].ASSIGNABLE_NAME);
        
                    check_empty(response[0].ASSIGNABLE, '#assignable', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'state details':
            const state_id = $('#state-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {state_id : state_id, transaction : transaction},
                success: function(response) {
                    $('#state_name').val(response[0].STATE_NAME);
        
                    $('#state_name_label').text(response[0].STATE_NAME);
                    $('#country_id_label').text(response[0].COUNTRY_NAME);
        
                    check_empty(response[0].COUNTRY_ID, '#country_id', 'select');
        
                    $('#state_id').val(state_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'system code details':
            const system_code_id = $('#system-code-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {system_code_id : system_code_id, transaction : transaction},
                success: function(response) {
                    $('#system_description').val(response[0].SYSTEM_DESCRIPTION);
                    $('#system_code').val(response[0].SYSTEM_CODE);

                    $('#system_description_label').text(response[0].SYSTEM_DESCRIPTION);
                    $('#system_code_label').text(response[0].SYSTEM_CODE);
                    $('#system_type_label').text(response[0].SYSTEM_TYPE_NAME);

                    check_empty(response[0].SYSTEM_TYPE, '#system_type', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'system parameter details':
            const parameter_id = $('#parameter-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {parameter_id : parameter_id, transaction : transaction},
                success: function(response) {
                    $('#parameter').val(response[0].PARAMETER);
                    $('#parameter_description').val(response[0].PARAMETER_DESCRIPTION);
                    $('#parameter_extension').val(response[0].PARAMETER_EXTENSION);
                    $('#parameter_number').val(response[0].PARAMETER_NUMBER);
        
                    $('#parameter_label').text(response[0].PARAMETER);
                    $('#parameter_description_label').text(response[0].PARAMETER_DESCRIPTION);
                    $('#parameter_extension_label').text(response[0].PARAMETER_EXTENSION);
                    $('#parameter_number_label').text(response[0].PARAMETER_NUMBER);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'upload setting details':
            const upload_setting_id = $('#upload-setting-id').text();
        
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {upload_setting_id : upload_setting_id, transaction : transaction},
                success: function(response) {
                    $('#upload_setting').val(response[0].UPLOAD_SETTING);
                    $('#description').val(response[0].DESCRIPTION);
                    $('#max_file_size').val(response[0].MAX_FILE_SIZE);
        
                    $('#upload_setting_label').text(response[0].UPLOAD_SETTING);
                    $('#description_label').text(response[0].DESCRIPTION);
                    $('#max_file_size_label').text(response[0].MAX_FILE_SIZE + ' mb');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'user account details':
            const user_id = $('#user_id').val();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {user_id : user_id, transaction : transaction},
                success: function(response) {
                    $('#file_as').val(response[0].FILE_AS);
                    $('#password').val(response[0].PASSWORD);

                    $('#file_as_label').text(response[0].FILE_AS);
                    $('#user_id_label').text(user_id);

                    document.getElementById('last_connection_date').innerHTML = response[0].LAST_CONNECTION_DATE;
                    document.getElementById('password_expiry_date').innerHTML = response[0].PASSWORD_EXPIRY_DATE;
                    document.getElementById('last_failed_login_date').innerHTML = response[0].LAST_FAILED_LOGIN;
                    document.getElementById('user_status').innerHTML = response[0].USER_STATUS;
                    document.getElementById('failed_login').innerHTML = response[0].FAILED_LOGIN;
                    document.getElementById('lock_status').innerHTML = response[0].LOCK_STATUS;
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'wage type details':
            const wage_type_id = $('#wage-type-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {wage_type_id : wage_type_id, transaction : transaction},
                success: function(response) {
                    $('#wage_type').val(response[0].WAGE_TYPE);
                    
                    $('#wage_type_label').text(response[0].WAGE_TYPE);
                            
                    $('#wage_type_id').val(wage_type_id);
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'work location details':
            const work_location_id = $('#work-location-id').text();

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

                    $('#work_location_label').text(response[0].WORK_LOCATION);
                    $('#work_location_address_label').text(response[0].WORK_LOCATION_ADDRESS);
                    $('#email_label').text(response[0].EMAIL);
                    $('#telephone_label').text(response[0].TELEPHONE);
                    $('#mobile_label').text(response[0].MOBILE);
                    $('#location_number_label').text(response[0].LOCATION_NUMBER);

                    document.getElementById('work_location_status').innerHTML = response[0].STATUS;
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'working schedule details':
            const working_schedule_id = $('#working-schedule-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {working_schedule_id : working_schedule_id, transaction : transaction},
                success: function(response) {
                    $('#working_schedule').val(response[0].WORKING_SCHEDULE);

                    $('#working_schedule_label').text(response[0].WORKING_SCHEDULE);
                    $('#working_schedule_type_label').text(response[0].WORKING_SCHEDULE_TYPE_NAME);

                    check_empty(response[0].WORKING_SCHEDULE_TYPE, '#working_schedule_type', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'working schedule type details':
            const working_schedule_type_id = $('#working-schedule-type-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {working_schedule_type_id : working_schedule_type_id, transaction : transaction},
                success: function(response) {
                    $('#working_schedule_type').val(response[0].WORKING_SCHEDULE_TYPE);

                    $('#working_schedule_type_label').text(response[0].WORKING_SCHEDULE_TYPE);
                    $('#working_schedule_type_category_label').text(response[0].WORKING_SCHEDULE_TYPE_CATEGORY_NAME);

                    check_empty(response[0].WORKING_SCHEDULE_TYPE_CATEGORY, '#working_schedule_type_category', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'zoom api details':
            const zoom_api_id = $('#zoom-api-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {zoom_api_id : zoom_api_id, transaction : transaction},
                success: function(response) {
                    $('#zoom_api_name').val(response[0].ZOOM_API_NAME);
                    $('#api_key').val(response[0].API_KEY);
                    $('#api_secret').val(response[0].API_SECRET);
                    $('#description').val(response[0].DESCRIPTION);

                    $('#zoom_api_name_label').text(response[0].ZOOM_API_NAME);
                    $('#api_key_label').text(response[0].API_KEY);
                    $('#api_secret_label').text(response[0].API_SECRET);
                    $('#description_label').text(response[0].DESCRIPTION);

                    document.getElementById('zoom_api_status').innerHTML = response[0].STATUS;
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
        case 'employee details':
            const employee_id = $('#employee-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {employee_id : employee_id, transaction : transaction},
                success: function(response) {
                    $('#first_name').val(response[0].FIRST_NAME);
                    $('#middle_name').val(response[0].MIDDLE_NAME);
                    $('#last_name').val(response[0].LAST_NAME);
                    $('#badge_id').val(response[0].BADGE_ID);
                    $('#nickname').val(response[0].NICKNAME);
                    $('#birthday').val(response[0].BIRTHDAY);
                    $('#height').val(response[0].HEIGHT);
                    $('#birth_place').val(response[0].PLACE_OF_BIRTH);
                    $('#weight').val(response[0].WEIGHT);
                    $('#permanency_date').val(response[0].PERMANENCY_DATE);
                    $('#onboard_date').val(response[0].ONBOARD_DATE);

                    $('#first_name_label').text(response[0].FIRST_NAME);
                    $('#middle_name_label').text(response[0].MIDDLE_NAME);
                    $('#last_name_label').text(response[0].LAST_NAME);
                    $('#suffix_label').text(response[0].SUFFIX);
                    $('#department_label').text(response[0].DEPARTMENT_NAME);
                    $('#job_position_label').text(response[0].JOB_POSITION_NAME);
                    $('#manager_label').text(response[0].MANAGER_NAME);
                    $('#coach_label').text(response[0].COACH_NAME);
                    $('#company_label').text(response[0].COMPANY_NAME);
                    $('#badge_id_label').text(response[0].BADGE_ID);
                    $('#work_location_label').text(response[0].WORK_LOCATION_NAME);
                    $('#work_schedule_label').text(response[0].WORKING_HOURS_NAME);
                    $('#nickname_label').text(response[0].NICKNAME);
                    $('#nationality_label').text(response[0].NATIONALITY_NAME);
                    $('#birthday_label').text(response[0].BIRTHDAY);
                    $('#blood_type_label').text(response[0].BLOOD_TYPE_NAME);
                    $('#height_label').text(response[0].HEIGHT_LABEL);
                    $('#civil_status_label').text(response[0].CIVIL_STATUS_NAME);
                    $('#gender_label').text(response[0].GENDER_NAME);
                    $('#birth_place_label').text(response[0].PLACE_OF_BIRTH);
                    $('#religion_label').text(response[0].RELIGION_NAME);
                    $('#weight_label').text(response[0].WEIGHT_LABEL);
                    $('#employee_type_label').text(response[0].EMPLOYEE_TYPE_NAME);
                    $('#permanency_date_label').text(response[0].PERMANENCY_DATE);
                    $('#onboard_date_label').text(response[0].ONBOARD_DATE);

                    document.getElementById('employee_status').innerHTML = response[0].STATUS;
                    
                    document.getElementById('employee_image').innerHTML = response[0].EMPLOYEE_IMAGE;
                    document.getElementById('employee_digital_signature').innerHTML = response[0].EMPLOYEE_DIGITAL_SIGNATURE;

                    check_empty(response[0].SUFFIX, '#suffix', 'select');
                    check_empty(response[0].DEPARTMENT, '#department', 'select');
                    check_empty(response[0].JOB_POSITION, '#job_position', 'select');
                    check_empty(response[0].MANAGER, '#manager', 'select');
                    check_empty(response[0].COACH, '#coach', 'select');
                    check_empty(response[0].COMPANY, '#company', 'select');
                    check_empty(response[0].WORK_LOCATION, '#work_location', 'select');
                    check_empty(response[0].WORKING_HOURS, '#work_schedule', 'select');
                    check_empty(response[0].NATIONALITY, '#nationality', 'select');
                    check_empty(response[0].BLOOD_TYPE, '#blood_type', 'select');
                    check_empty(response[0].CIVIL_STATUS, '#civil_status', 'select');
                    check_empty(response[0].GENDER, '#gender', 'select');
                    check_empty(response[0].RELIGION, '#religion', 'select');
                    check_empty(response[0].EMPLOYEE_TYPE, '#employee_type', 'select');
                },
                complete: function(){
                    generate_transaction_logs();
                }
            });
            break;
    }
}

// Reset form
function reset_form(){
    $('.form-details').removeClass('d-none');
    $('.form-edit').addClass('d-none');
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

function generate_transaction_logs(){
    const username = $('#username').text();
    const transaction_log_id = $('#transaction_log_id').val();
    const type = 'transaction logs';

    $.ajax({
        url: 'system-generation.php',
        method: 'POST',
        data: {type : type, transaction_log_id : transaction_log_id, username : username},
        beforeSend: function(){
            $('#transaction-logs').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
        },
        success: function(response) {
            $('#transaction-logs').html(response);
        }
    });
}

// Reset validation functions
function reset_element_validation(element){
    $(element)
    .parent()
    .removeClass('has-danger')
    .end()
    .removeClass('form-control-danger')
    .siblings(element + '-error')
    .remove();
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
    const adjustDataTable = () => $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

    $('a[data-bs-toggle="tab"], a[data-bs-toggle="pill"], #System-Modal').on('shown.bs.tab shown.bs.modal', adjustDataTable);
}

// Check functions
function check_option_exist(element, option, return_value){
    const $element = $(element);
    const $option = $element.find(`option[value="${option}"]`);

    $element.val($option.length ? option : return_value).trigger('change');
}

function check_empty(value, id, type){
    const $element = $(id);

    if (value && value !== null) {
        switch (type) {
            case 'select':
                $element.val(value).change();
                break;
            case 'text':
                $element.text(value);
                break;
            default:
                $element.val(value);
                break;
        }
    }
}

function check_table_check_box(){
    const inputElements = Array.from(document.querySelectorAll('.datatable-checkbox-children'));
    const $multiple = $('.multiple');
    const $multipleAction = $('.multiple-action');
    const checkedValue = inputElements.filter(chk => chk.checked).length;

    $multiple.toggleClass('d-none', checkedValue === 0);
    $multipleAction.toggleClass('d-none', checkedValue === 0);
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

function check_toastr(){
    const { 
        'toastr-title': toastr_title, 
        'toastr-message': toastr_message, 
        'toastr-type': toastr_type 
    } = sessionStorage;
    
    if (toastr_title && toastr_message && toastr_type) {
        sessionStorage.removeItem('toastr-title');
        sessionStorage.removeItem('toastr-message');
        sessionStorage.removeItem('toastr-type');

        show_toastr(toastr_title, toastr_message, toastr_type);
    }
}

// Discard function
function discard(windows_location){
    Swal.fire({
        title: 'Discard Changes Confirmation',
        text: 'Are you sure you want to discard the changes made to this item? The changes will be lost permanently once discarded.',
        icon: 'warning',
        showCancelButton: !0,
        confirmButtonText: 'Discard',
        cancelButtonText: 'Cancel',
        confirmButtonClass: 'btn btn-danger mt-2',
        cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
        buttonsStyling: !1
    }).then(function(result) {
        if (result.value) {
            window.location = windows_location;
        }
    });
}

// toastr
function show_toastr(title, message, toastr_type, redirect_page = null) {
    const toastr_options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: true,
        showDuration: 300,
        hideDuration: 300,
        timeOut: 5000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    const toastr_types = {
        success: toastr.success,
        info: toastr.info,
        warning: toastr.warning,
        error: toastr.error
    };

    if (show_toastr.displayedMessages.includes(message)) {
        return;
    }

    show_toastr.displayedMessages.push(message);

    if (toastr_type in toastr_types) {
        toastr_types[toastr_type](message, title, toastr_options);
    }

    if (redirect_page) {
        toastr.options.onHidden = function() {
            window.location.href = redirect_page;
            toastr.options.onHidden = null;
        }
    }

    toastr.options.onHidden = function() {
        show_toastr.displayedMessages = [];
        toastr.options.onHidden = null;
    };
}

function set_toastr(toastr_title, toastr_message, toastr_type){
    sessionStorage.setItem('toastr-title', toastr_title);
    sessionStorage.setItem('toastr-message', toastr_message);
    sessionStorage.setItem('toastr-type', toastr_type);
}

show_toastr.displayedMessages = [];

// Show alert

function show_alert_event(title, message, type, event, rederict_link){
    const handle_event = (event, rederict_link) => {
        if (event === 'reload') {
            location.reload();
        } else if (event === 'redirect') {
            window.location.href = rederict_link;
        }
    };
    
    Swal.fire(title, message, type).then(() => handle_event(event, rederict_link));
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

function create_employee_qr_code(container, name, employee_id, email, mobile, width, height){
    document.getElementById(container).innerHTML = '';

    let card, qrcode;

    const qrOptions = {
    width: width,
    height: height,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
    };

    card = 'BEGIN:VCARD\r\n' +
        'VERSION:3.0\r\n' +
        `FN:${name}\r\n` +
        `EMAIL:${email}\r\n` +
        `ID NO:[${employee_id}]\r\n` +
        (mobile ? `TEL:${mobile}\r\n` : '') +
        'END:VCARD';

    qrcode = new QRCode(document.getElementById(container), qrOptions);
    qrcode.makeCode(card);
}

// Hide
function hide_multiple_buttons(){
    $('#datatable-checkbox').prop('checked', false);

    const classes = ['multiple', 'multiple-lock', 'multiple-unlock', 'multiple-activate', 'multiple-deactivate', 'multiple-approve',
    'multiple-reject', 'multiple-cancel', 'multiple-delete', 'multiple-pending', 'multiple-for-recommendation',
    'multiple-recommendation', 'multiple-send', 'multiple-print', 'multiple-unarchive', 'multiple-archive',
    'multiple-start', 'multiple-stop'];

    $(classes.join(', ')).toggleClass('d-none');
}