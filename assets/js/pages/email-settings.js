(function($) {
    'use strict';

    $(function() {
        if($('#email-settings-datatable').length){
            initialize_email_settings_table('#email-settings-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_email_settings_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    var username = $('#username').text();
    var filter_status = $('#filter_status').val();
    var type = 'email settings table';
    var settings;

    var column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMAIL_SETTING_ID' },
        { 'data' : 'EMAIL_SETTING_NAME' },
        { 'data' : 'STATUS' },
        { 'data' : 'VIEW' }
    ];

    var column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '64%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%','bSortable': false, 'aTargets': 4 }
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'filter_status' : filter_status},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
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
    }
    else{
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'filter_status' : filter_status},
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
    }

    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    var username = $('#username').text();

    $(document).on('click','#delete-email-setting',function() {
        var email_setting_id = [];
        var transaction = 'delete multiple email setting';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                email_setting_id.push(this.value);  
            }
        });

        if(email_setting_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Email Setting',
                text: 'Are you sure you want to delete these email setting?',
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
                        data: {username : username, email_setting_id : email_setting_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_alert('Delete Multiple Email Settings Success', 'The email settings have been deleted.', 'success');
    
                                reload_datatable('#email-settings-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Delete Multiple Email Settings Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                                show_alert('Delete Multiple Email Settings Error', response, 'error');
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
            show_alert('Delete Multiple Email Settings Error', 'Please select the email settings you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_email_settings_table('#email-settings-datatable');
    });

}