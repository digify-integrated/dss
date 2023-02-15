(function($) {
    'use strict';

    $(function() {
        if($('#user-accounts-datatable').length){
            initialize_user_accounts_table('#user-accounts-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_user_accounts_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const filter_start_date = $('#filter_start_date').val();
    const filter_end_date = $('#filter_end_date').val();
    const filter_last_connection_start_date = $('#filter_last_connection_start_date').val();
    const filter_last_connection_end_date = $('#filter_last_connection_end_date').val();
    const filter_user_account_status = $('#filter_user_account_status').val();
    const filter_user_account_lock_status = $('#filter_user_account_lock_status').val();
    const type = 'user accounts table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'USERNAME' },
        { 'data' : 'ACCOUNT_STATUS' },
        { 'data' : 'LOCK_STATUS' },
        { 'data' : 'PASSWORD_EXPIRY_DATE' },
        { 'data' : 'LAST_CONNECTION_DATE' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '29%', 'aTargets': 1 },
        { 'width': '10%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '20%', 'aTargets': 4 },
        { 'width': '20%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_start_date' : filter_start_date, 'filter_end_date' : filter_end_date, 'filter_last_connection_start_date' : filter_last_connection_start_date, 'filter_last_connection_end_date' : filter_last_connection_end_date, 'filter_user_account_status' : filter_user_account_status, 'filter_user_account_lock_status' : filter_user_account_lock_status},
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

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-user-account',function() {
        let user_id = [];
        const transaction = 'delete multiple user account';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                user_id.push(element.value);  
            }
        });

        if(user_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple User Account',
                text: 'Are you sure you want to delete these user account?',
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
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_toastr('Delete Multiple User Accounts Successful', 'The user accounts have been deleted successfully.', 'success');
    
                                reload_datatable('#user-accounts-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Delete Multiple User Accounts Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    
                    return false;
                }
            });
        }
        else{
            show_toastr('Delete Multiple User Accounts Error', 'Please select the user accounts you want to delete.', 'error');
        }
    });

    $(document).on('click','#lock-user-account',function() {
        let user_id = [];
        const transaction = 'lock multiple user account';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                user_id.push(element.value);  
            }
        });

        if(user_id.length > 0){
            Swal.fire({
                title: 'Lock Multiple User Accounts',
                text: 'Are you sure you want to lock these user accounts?',
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
                            if(response === 'Locked' || response === 'Not Found'){
                                show_toastr('Lock Multiple User Accounts Successful', 'The user accounts have been locked successfully.', 'success');
    
                                reload_datatable('#user-accounts-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Lock Multiple User Accounts Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_toastr('Lock Multiple User Accounts Error', 'Please select the user accounts you want to lock.', 'error');
        }
    });

    $(document).on('click','#unlock-user-account',function() {
        let user_id = [];
        const transaction = 'unlock multiple user account';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                user_id.push(element.value);  
            }
        });

        if(user_id.length > 0){
            Swal.fire({
                title: 'Unlock Multiple User Accounts',
                text: 'Are you sure you want to unlock these user accounts?',
                icon: 'info',
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
                            if(response === 'Unlocked' || response === 'Not Found'){
                                show_toastr('Unlock Multiple User Accounts Successful', 'The user accounts have been unlocked successfully.', 'success');
    
                                reload_datatable('#user-accounts-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Unlock Multiple User Accounts Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_toastr('Unlock Multiple User Accounts Error', 'Please select the user accounts you want to unlock.', 'error');
        }
    });

    $(document).on('click','#activate-user-account',function() {
        let user_id = [];
        const transaction = 'activate multiple user account';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                user_id.push(element.value);  
            }
        });

        if(user_id.length > 0){
            Swal.fire({
                title: 'Activate Multiple User Accounts',
                text: 'Are you sure you want to activate these user accounts?',
                icon: 'info',
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
                            if(response === 'Activated' || response === 'Not Found'){
                                show_toastr('Activate Multiple User Accounts Successful', 'The user accounts have been activated successfully.', 'success');
    
                                reload_datatable('#user-accounts-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Activate Multiple User Accounts Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_toastr('Activate Multiple User Accounts Error', 'Please select the user accounts you want to activate.', 'error');
        }
    });

    $(document).on('click','#deactivate-user-account',function() {
        let user_id = [];
        const transaction = 'deactivate multiple user account';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                user_id.push(element.value);  
            }
        });

        if(user_id.length > 0){
            Swal.fire({
                title: 'Deactivate Multiple User Accounts',
                text: 'Are you sure you want to deactivate these user accounts?',
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
                            if(response === 'Deactivated' || response === 'Not Found'){
                                show_toastr('Deactivate Multiple User Accounts Successful', 'The user accounts have been deactivated successfully.', 'success');
    
                                reload_datatable('#user-accounts-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Deactivate Multiple User Accounts Error', response, 'error');
                            }
                        },
                        complete: function(){
                            $('.multiple').addClass('d-none');
                            $('.multiple-action').addClass('d-none');
                        }
                    });
                    return false;
                }
            });
        }
        else{
            show_toastr('Deactivate Multiple User Accounts Error', 'Please select the user accounts you want to deactivate.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_user_accounts_table('#user-accounts-datatable');
    });
}