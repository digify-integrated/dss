(function($) {
    'use strict';

    $(function() {
        check_toastr();
        
        if($('#user-id').length){
            display_details('user account details');

            if($('#user-account-role-datatable').length){
                initialize_user_account_role_table('#user-account-role-datatable');
            }
        }

        $('#user-account-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit user account';
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
                                set_toastr('User Account Inserted', 'The user account has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['USER_ID'];
                                break;
                            case 'Updated':
                                set_toastr('User Account Updated', 'The user account has been updated successfully.', 'success');
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
                file_as: {
                    required: true
                },
                password: {
                    required: true,
                    password_strength : true
                },
                user_id: {
                    required: true
                }
            },
            messages: {
                file_as: {
                    required: 'Please enter the full name',
                },
                password: {
                    required: 'Please enter the password',
                },
                user_id: {
                    required: 'Please enter the username',
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

function initialize_role_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const user_id = $('#user_id').val();
    const type = 'user account role assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'user_id' : user_id},
            'dataSrc' : ''
        },
        'order': [[ 1, 'asc' ]],
        'columns' : column,
        'scrollY': false,
        'scrollX': true,
        'scrollCollapse': true,
        'fnDrawCallback': function( oSettings ) {
            readjust_datatable_column();
        },
        'aoColumnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': '<div class="spinner-border spinner-border-lg text-info" role="status"><span class="sr-only">Loading...</span></div>'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroy_datatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function initialize_user_account_role_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const user_id = $('#user_id').val();
    const type = 'user account role table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'user_id' : user_id},
            'dataSrc' : ''
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'scrollY': false,
        'scrollX': true,
        'scrollCollapse': true,
        'fnDrawCallback': function( oSettings ) {
            readjust_datatable_column();
        },
        'aoColumnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': '<div class="spinner-border spinner-border-lg text-info" role="status"><span class="sr-only">Loading...</span></div>'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroy_datatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-user-account',function() {
        const user_id = $(this).data('user-id');
        const transaction = 'delete user account';

        Swal.fire({
            title: 'Confirm User Account Deletion',
            text: 'Are you sure you want to delete this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'user-accounts.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Account Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-user-account-role',function() {
        generate_modal('user account role form', 'User Account Role', 'LG' , '1', '1', 'form', 'user-account-role-form', '1', username);
    });

    $(document).on('click','.delete-user-account-role',function() {
        const user_id = $(this).data('user-id');
        const role_id = $(this).data('role-id');
        const transaction = 'delete user account role';

        Swal.fire({
            title: 'Confirm Role Deletion',
            text: 'Are you sure you want to delete this role?',
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
                    data: {username : username, user_id : user_id, role_id : role_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Role Deleted', 'The selected role has been deleted successfully.', 'success');
                                reload_datatable('#user-account-role-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Role Deletion Error', 'The selected role does not exist or has already been deleted.', 'warning');
                                reload_datatable('#user-account-role-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Role Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#activate-user-account',function() {
        const user_id = $(this).data('user-id');
        const transaction = 'activate user account';

        Swal.fire({
            title: 'Confirm User Account Activation',
            text: 'Are you sure you want to activate this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Activated':
                                set_toastr('User Account Activated', 'The user account has been activated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Account Activation Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-user-account',function() {
        const user_id = $(this).data('user-id');
        const transaction = 'deactivate user account';

        Swal.fire({
            title: 'Confirm User Account Deactivation',
            text: 'Are you sure you want to deactivate this user account?',
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
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deactivated':
                                set_toastr('User Account Deactivated', 'The user account has been deactivated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Account Deactivation Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#unlock-user-account',function() {
        const user_id = $(this).data('user-id');
        const transaction = 'unlock user account';

        Swal.fire({
            title: 'Confirm User Account Unlock',
            text: 'Are you sure you want to unlock this user account?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Unlock',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Unlocked':
                                set_toastr('User Account Unlocked', 'The user account has been unlocked successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Account Unlock Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#lock-user-account',function() {
        const user_id = $(this).data('user-id');
        const transaction = 'lock user account';

        Swal.fire({
            title: 'Confirm User Account Lock',
            text: 'Are you sure you want to lock this user account?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Lock',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, user_id : user_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Locked':
                                set_toastr('User Account Locked', 'The user account has been locked successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('User Account Lock Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('user-accounts.php');
    });
}