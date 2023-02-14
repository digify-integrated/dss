(function($) {
    'use strict';

    $(function() {
        if($('#company-id').length){
            display_details();
        }

        $('#company-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit company';
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
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['COMPANY_ID'];

                                show_alert_event('Insert Company Success', 'The company has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Company Success', 'The company has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Company Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Company Error', response, 'error');
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
                company_name: {
                    required: true
                }
            },
            messages: {
                company_name: {
                    required: 'Please enter the company name',
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
                } else {
                    $(element).removeClass('is-invalid');
                }
            }
        });

        initialize_click_events();
    });
})(jQuery);

function display_details(){
    const transaction = 'company details';
    const company_id = $('#company-id').text();

    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'JSON',
        data: {company_id : company_id, transaction : transaction},
        success: function(response) {
            $('#company_name').val(response[0].COMPANY_NAME);
            $('#company_address').val(response[0].COMPANY_ADDRESS);
            $('#tax_id').val(response[0].TAX_ID);
            $('#email').val(response[0].EMAIL);
            $('#mobile').val(response[0].MOBILE);
            $('#telephone').val(response[0].TELEPHONE);
            $('#website').val(response[0].WEBSITE);
                    
            document.getElementById('company_logo_image').innerHTML = response[0].COMPANY_LOGO;

            $('#company_id').val(company_id);
        }
    });
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-company',function() {
        const company_id = $(this).data('company-id');
        const transaction = 'delete company';

        Swal.fire({
            title: 'Delete Company',
            text: 'Are you sure you want to delete this company?',
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
                    data: {username : username, company_id : company_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Company Success', 'The company has been deleted.', 'success', 'redirect', 'company.php');
                            }
                            else{
                                show_alert_event('Delete Company Error', 'The company does not exist.', 'info', 'redirect', 'company.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Company Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Company Error', response, 'error');
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
                window.location.href = 'company.php';
            }
        });
    });

}