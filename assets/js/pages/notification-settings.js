(function($) {
    'use strict';

    $(function() {
        if($('#notification-settings-datatable').length){
            initialize_notification_settings_table('#notification-settings-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_notification_settings_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const filter_notification_channel = $('#filter_notification_channel').val();
    const type = 'notification settings table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'NOTIFICATION_SETTING_ID' },
        { 'data' : 'NOTIFICATION_SETTING' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '79%', 'aTargets': 2 },
        { 'width': '10%','bSortable': false, 'aTargets': 3 }
    ];


    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_notification_channel' : filter_notification_channel},
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

    $(document).on('click','#delete-notification-setting',function() {
        let notification_setting_id = [];
        const transaction = 'delete multiple notification setting';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                notification_setting_id.push(element.value);  
            }
        });

        if(notification_setting_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Notification Setting',
                text: 'Are you sure you want to delete these notification setting?',
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
                        data: {username : username, notification_setting_id : notification_setting_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_toastr('Delete Multiple Notification Settings Successful', 'The notification settings have been deleted successfully.', 'success');
    
                                reload_datatable('#notification-settings-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Delete Multiple Notification Settings Error', response, 'error');
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
            show_toastr('Delete Multiple Notification Settings Error', 'Please select the notification settings you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_notification_settings_table('#notification-settings-datatable');
    });
}