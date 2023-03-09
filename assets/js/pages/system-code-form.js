(function($) {
    'use strict';

    $(function() {
        check_toastr();
        
        if($('#system-code-id').length){
            display_details('system code details');
        }

        $('#system-code-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit system code';
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
                        switch (response[0]['RESPONSE']) {
                            case 'Inserted':
                                set_toastr('System Code Inserted', 'The system code has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['SYSTEM_CODE_ID'];
                                break;
                            case 'Updated':
                                set_toastr('System Code Updated', 'The system code has been updated successfully.', 'success');
                                window.location.reload();
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
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('<span class="d-block d-sm-none"><i class="bx bx-save"></i></span><span class="d-none d-sm-block">Save</span>');
                    }
                });
                return false;
            },
            rules: {
                system_type: {
                    required: true
                },
                system_description: {
                    required: true
                },
                system_code: {
                    required: true
                },
            },
            messages: {
                system_type: {
                    required: 'Please choose the system type',
                },
                system_description: {
                    required: 'Please enter the system code description',
                },
                system_code: {
                    required: 'Please enter the system code',
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

    $(document).on('click','#delete-system-code',function() {
        const system_code_id = $(this).data('system-code-id');
        const transaction = 'delete system code';

        Swal.fire({
            title: 'Confirm System Code Deletion',
            text: 'Are you sure you want to delete this system code?',
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
                    data: {username : username, system_code_id : system_code_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'system-codes.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('System Code Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('system-codes.php');
    });
}