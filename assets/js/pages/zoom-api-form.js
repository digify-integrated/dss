(function($) {
    'use strict';

    $(function() {
        check_toastr();
        
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
                        switch (response[0]['RESPONSE']) {
                            case 'Inserted':
                                set_toastr('Zoom API Inserted', 'The Zoom API has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['ZOOM_API_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Zoom API Updated', 'The Zoom API has been updated successfully.', 'success');
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
            title: 'Confirm Zoom API Deletion',
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
                        switch (response) {
                            case 'Deleted':
                                window.location = 'zoom-api.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Zoom API Error', response, 'error');
                                break;
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
            title: 'Confirm Zoom API Activation',
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
                        switch (response) {
                            case 'Activated':
                                set_toastr('Zoom API Activated', 'The Zoom API has been activated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Zoom API Activation Error', response, 'error');
                                break;
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
            title: 'Confirm Zoom API Deactivation',
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
                        switch (response) {
                            case 'Deactivated':
                                set_toastr('Zoom API Deactivated', 'The Zoom API has been deactivated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Zoom API Deactivation Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('zoom-api.php');
    });
}