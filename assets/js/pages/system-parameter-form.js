(function($) {
    'use strict';

    $(function() {
        if($('#parameter-id').length){
            display_details();
        }

        $('#system-parameter-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit system parameter';
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
                            window.location = window.location.href + '?id=' + response[0]['PARAMETER_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details();
                            reset_form();
                            
                            show_toastr('Update Successful', 'The system parameter has been updated successfully.', 'success');
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
                parameter: {
                    required: true
                },
                parameter_description: {
                    required: true
                }
            },
            messages: {
                parameter: {
                    required: 'Please enter the parameter',
                },
                parameter_description: {
                    required: 'Please enter the parameter description',
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
    const transaction = 'system parameter details';
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

            $('#parameter_id').val(parameter_id);
        }
    });
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-system-parameter',function() {
        const parameter_id = $(this).data('parameter-id');
        const transaction = 'delete system parameter';

        Swal.fire({
            title: 'Delete System Parameter',
            text: 'Are you sure you want to delete this system parameter?',
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
                    data: {username : username, parameter_id : parameter_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted'){
                            show_toastr('Delete System Parameter Successful', 'The system parameter has been deleted successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete System Parameter Error', response, 'error');
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
                window.location = 'system-parameters.php';
                return false;
            }
        });
    });
}