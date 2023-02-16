(function($) {
    'use strict';

    $(function() {
        if($('#zoom-api-id').length){
            display_details('zoom api details');
        }

        $('#zoom-api-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit zoom api';
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
                            window.location = window.location.href + '?id=' + response[0]['ZOOM_API_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details('zoom api details');
                            reset_form();
                            
                            show_toastr('Update Successful', 'The Zoom API has been updated successfully.', 'success');
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
                zoom_api_name: {
                    required: true
                },
                description: {
                    required: true
                },
                api_key: {
                    required: true
                },
                api_secret: {
                    required: true
                }
            },
            messages: {
                zoom_api_name: {
                    required: 'Please enter the zoom api name',
                },
                description: {
                    required: 'Please enter the description',
                },
                api_key: {
                    required: 'Please enter the API Key',
                },
                api_secret: {
                    required: 'Please enter the API Secret',
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

    $(document).on('click','#delete-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'delete zoom api';

        Swal.fire({
            title: 'Delete Zoom API',
            text: 'Are you sure you want to delete this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted'){
                            window.location = 'zoom-api.php';
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Zoom API Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#activate-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'activate zoom api';

        Swal.fire({
            title: 'Activate Zoom API',
            text: 'Are you sure you want to activate this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated'){
                            show_toastr('Activate Zoom API Successful', 'The Zoom API has been activated successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Activate User Account Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'deactivate zoom api';

        Swal.fire({
            title: 'Deactivate Zoom API',
            text: 'Are you sure you want to deactivate this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated'){
                            show_toastr('Deactivate Zoom API Successful', 'The Zoom API has been deactivated successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Deactivate Zoom API Error', response, 'error');
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
                window.location = 'zoom-api.php';
                return false;
            }
        });
    });
}