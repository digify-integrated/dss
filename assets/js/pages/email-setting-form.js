(function($) {
    'use strict';

    $(function() {
        if($('#email-setting-id').length){
            display_details('email setting details');
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
                        if(response[0]['RESPONSE'] === 'Inserted'){
                            window.location = window.location.href + '?id=' + response[0]['EMAIL_SETTING_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details('email setting details');
                            reset_form();
                            
                            show_toastr('Update Successful', 'The email setting has been updated successfully.', 'success');
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

function initialize_click_events(){
    const username = $('#username').text();

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
                        if(response === 'Deleted'){
                            window.location = 'email-settings.php';
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Email Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

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
                        if(response === 'Activated'){
                            location.reload();
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Activate Email Setting Error', response, 'error');
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
                        if(response === 'Deactivated'){
                            location.reload();
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Deactivate Email Setting Error', response, 'error');
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
                window.location = 'email-settings.php';
                return false;
            }
        });
    });

}