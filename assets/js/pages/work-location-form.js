(function($) {
    'use strict';

    $(function() {
        if($('#work-location-id').length){
            display_details();
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
                        if(response[0]['RESPONSE'] === 'Inserted'){
                            window.location = window.location.href + '?id=' + response[0]['WORK_LOCATION_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details();
                            reset_form();
                            
                            show_toastr('Update Successful', 'The work location has been updated successfully.', 'success');
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

function display_details(){
    const transaction = 'work location details';
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

            document.getElementById('work_location_status').innerHTML = response[0].STATUS;
        }
    });
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-work-location',function() {
        const work_location_id = $(this).data('work-location-id');
        const transaction = 'delete work location';

        Swal.fire({
            title: 'Delete Work Location',
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
                        if(response === 'Deleted'){
                            show_toastr('Delete Work Location Successful', 'The work location has been deleted successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Work Location Error', response, 'error');
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
            title: 'Unarchive Work Location',
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
                        if(response === 'Unarchived'){
                            show_toastr('Unarchived Work Location Successful', 'The work location has been unarchived successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Unarchived Work Location Error', response, 'error');
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
            title: 'Archive Work Location',
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
                        if(response === 'Archived'){
                            show_toastr('Archived Work Location Successful', 'The work location has been archived successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Archived Work Location Error', response, 'error');
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
                window.location = 'work-locations.php';
                return false;
            }
        });
    });
}