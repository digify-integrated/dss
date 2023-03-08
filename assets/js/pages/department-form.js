(function($) {
    'use strict';

    $(function() {
        if($('#department-id').length){
            display_details('department details');
        }

        $('#department-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit department';
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
                                set_toastr('Department Inserted', 'The department has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['DEPARTMENT_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Department Updated', 'The department has been updated successfully.', 'success');
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
                department: {
                    required: true
                }
            },
            messages: {
                department: {
                    required: 'Please enter the department',
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

    $(document).on('click','#delete-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'delete department';

        Swal.fire({
            title: 'Confirm Department Deletion',
            text: 'Are you sure you want to delete this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'departments.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Department Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#unarchive-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'unarchive department';

        Swal.fire({
            title: 'Confirm Department Unarchive',
            text: 'Are you sure you want to unarchive this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Unarchived':
                                set_toastr('Department Unarchived', 'The department has been unarchived successfully.', 'success');
                                indow.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Department Unarchived Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#archive-department',function() {
        const department_id = $(this).data('department-id');
        const transaction = 'archive department';

        Swal.fire({
            title: 'Confirm Department Archive',
            text: 'Are you sure you want to archive this department?',
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
                    data: {username : username, department_id : department_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Archived':
                                set_toastr('Department Archived', 'The department has been archived successfully.', 'success');
                                indow.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Department Archived Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('departments.php');
    });

}