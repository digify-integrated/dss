(function($) {
    'use strict';

    $(function() {
        if($('#upload-settings-datatable').length){
            initialize_upload_settings_table('#upload-settings-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_upload_settings_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const type = 'upload settings table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'UPLOAD_SETTING_ID' },
        { 'data' : 'UPLOAD_SETTING' },
        { 'data' : 'MAX_FILE_SIZE' },
        { 'data' : 'FILE_TYPE' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '29%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '30%', 'aTargets': 4 },
        { 'width': '10%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username},
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

    $(document).on('click','#delete-upload-setting',function() {
        let upload_setting_id = [];
        const transaction = 'delete multiple upload setting';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                upload_setting_id.push(element.value);  
            }
        });

        if(upload_setting_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Upload Settings',
                text: 'Are you sure you want to delete these upload settings?',
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
                        data: {username : username, upload_setting_id : upload_setting_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_alert('Delete Multiple Upload Settings Success', 'The upload settings have been deleted.', 'success');
    
                                reload_datatable('#upload-settings-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Delete Multiple Upload Settings Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
    
                            else{
                                show_alert('Delete Multiple Upload Settings Error', response, 'error');
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
            show_alert('Delete Multiple Upload Settings Error', 'Please select the upload settings you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_upload_settings_table('#upload-settings-datatable');
    });
}