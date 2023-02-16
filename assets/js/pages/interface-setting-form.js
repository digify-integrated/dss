(function($) {
    'use strict';

    $(function() {
        if($('#interface-setting-id').length){
            display_details('interface setting details');
        }

        $('#interface-setting-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit interface setting';
                const username = $('#username').text();

                var formData = new FormData(form);
                formData.append('username', username);
                formData.append('transaction', transaction);

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        document.getElementById('submit-data').disabled = true;
                        $('#submit-data').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response[0]['RESPONSE'] === 'Inserted'){
                            window.location = window.location.href + '?id=' + response[0]['INTERFACE_SETTING_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details('interface setting details');
                            reset_form();
                            
                            show_toastr('Update Successful', 'The interface setting has been updated successfully.', 'success');
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
                interface_setting_name: {
                    required: true
                },
                description: {
                    required: true
                },
            },
            messages: {
                interface_setting_name: {
                    required: 'Please enter the interface setting name',
                },
                description: {
                    required: 'Please enter the description',
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

    $(document).on('click','#delete-interface-setting',function() {
        const interface_setting_id = $(this).data('interface-setting-id');
        const transaction = 'delete interface setting';

        Swal.fire({
            title: 'Delete Interface Setting',
            text: 'Are you sure you want to delete this interface setting?',
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
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted'){
                            window.location = 'interface-settings.php';
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Interface Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#activate-interface-setting',function() {
        const interface_setting_id = $(this).data('interface-setting-id');
        const transaction = 'activate interface setting';

        Swal.fire({
            title: 'Activate Interface Setting',
            text: 'Are you sure you want to activate this interface setting?',
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
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated'){
                            show_toastr('Activate Interface Setting Successful', 'The interface setting has been activated successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Deactivate Interface Setting Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-interface-setting',function() {
        const interface_setting_id = $(this).data('interface-setting-id');
        const transaction = 'deactivate interface setting';

        Swal.fire({
            title: 'Deactivate Interface Setting',
            text: 'Are you sure you want to deactivate this interface setting?',
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
                    data: {username : username, interface_setting_id : interface_setting_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated'){
                            show_toastr('Deactivate Interface Setting Successful', 'The interface setting has been deactivated successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Deactivate Interface Setting Error', response, 'error');
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
                window.location = 'interface-settings.php';
                return false;
            }
        });
    });
}