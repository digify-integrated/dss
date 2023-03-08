(function($) {
    'use strict';

    $(function() {
        $('#change-password-form').validate({
            submitHandler: function (form) {
                const transaction = 'change password';

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: $(form).serialize() + '&transaction=' + transaction,
                    beforeSend: function(){
                        document.getElementById('change-password').disabled = true;
                        $('#change-password').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated'){
                            $('#message').removeClass('d-none');
                            $('#change-password-form').addClass('d-none');

                            $('#message').html('Success! Your password has been successfully updated. For security reasons, please use your new password to log in. Click <a href="index.php">here</a> to go to the login page.');
                        }
                        else if (response === 'Password Exist'){
                            show_toastr('Password Change Error', 'Your new password must not match your previous one. Please choose a different password.', 'error');
                        }
                        else{
                            show_toastr('Password Change Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('change-password').disabled = false;
                        $('#change-password').html('Submit');
                    }
                });

                return false;
            },
            rules: {
                password: {
                    required: true,
                    password_strength : true
                }
            },
            messages: {
                password: {
                    required: 'Please enter your password',
                }
            },
            errorPlacement: function(label) {
                show_toastr('Password Change Error', label.text(), 'error');
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