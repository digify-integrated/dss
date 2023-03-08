(function($) {
    'use strict';

    $(function() {
        $('#signin-form').validate({
            submitHandler: function (form) {
                const transaction = 'authenticate';

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&transaction=' + transaction,
                    dataType: 'JSON',
                    beforeSend: function(){
                        document.getElementById('signin').disabled = true;
                        $('#signin').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only"></span></div>');
                    },
                    success: function (response) {
                        switch (response[0]['RESPONSE']) {
                            case 'Authenticated':
                                var username = $('#username').val();
                                sessionStorage.setItem('username', username);

                                window.location = 'apps.php';
                                break;
                            case 'Incorrect':
                                show_toastr('Authentication Error', 'The username or password you entered is incorrect. Please double-check your credentials and try again.', 'error');
                                break;
                            case 'Incorrect':
                                show_toastr('Authentication Error', 'The username or password you entered is incorrect. Please double-check your credentials and try again.', 'error');
                                break;
                            case 'Locked':
                                show_toastr('Account Locked', 'Your account has been locked. Please contact your administrator for assistance.', 'warning');
                                break;
                            case 'Inactive':
                                show_toastr('Account Inactive', 'Your user account is currently inactive. Please contact your administrator for assistance.', 'warning');
                                break;
                            case 'Password Expired':
                                window.location = 'change-password.php?id=' + response[0]['USERNAME'];
                                break;
                            default:
                                show_toastr('Authentication Error', response, 'error');
                                break;
                        }
                    },
                    complete: function(){
                        document.getElementById('signin').disabled = false;
                        $('#signin').html('Log In');
                    }
                });

                return false;
            },
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: 'Please enter your username',
                },
                password: {
                    required: 'Please enter your password',
                }
            },
            errorPlacement: function(label) {
                show_toastr('Authentication Error', label.text(), 'error');
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
    });
})(jQuery);