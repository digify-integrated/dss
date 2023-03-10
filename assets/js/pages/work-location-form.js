(function($) {
    'use strict';

    $(function() {
        if($('#work-location-id').length){
            display_details('work location details');
        }

        $('#work-location-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit work location';
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
                                set_toastr('Work Location Inserted', 'The work location has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['WORK_LOCATION_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Work Location Updated', 'The work location has been updated successfully.', 'success');
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
                work_location: {
                    required: true
                },
                work_location_address: {
                    required: true
                },
                location_number: {
                    required: true
                }
            },
            messages: {
                work_location: {
                    required: 'Please enter the work location',
                },
                work_location_address: {
                    required: 'Please enter the work location address',
                },
                location_number: {
                    required: 'Please enter the location number',
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

    $(document).on('click','#delete-work-location',function() {
        const work_location_id = $(this).data('work-location-id');
        const transaction = 'delete work location';

        Swal.fire({
            title: 'Confirm Work Location Deletion',
            text: 'Are you sure you want to delete this work location?',
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
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                set_toastr('Work Location Deleted', 'The work location has been deleted successfully.', 'success');
                                window.location = 'work-locations.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Work Location Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#unarchive-work-location',function() {
        const work_location_id = $(this).data('work-location-id');
        const transaction = 'unarchive work location';

        Swal.fire({
            title: 'Confirm Work Location Unarchive',
            text: 'Are you sure you want to unarchive this work location?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Unarchive',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Unarchived':
                                set_toastr('Work Location Unarchived', 'The work location has been unarchived successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Work Location Unarchived Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#archive-work-location',function() {
        const work_location_id = $(this).data('work-location-id');
        const transaction = 'archive work location';

        Swal.fire({
            title: 'Confirm Work Location Archive',
            text: 'Are you sure you want to archive this work location?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Archive',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, work_location_id : work_location_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Archived':
                                set_toastr('Work Location Archived', 'The work location has been archived successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Work Location Archived Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('work-locations.php');
    });
}