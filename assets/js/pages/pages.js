(function($) {
    'use strict';

    $(function() {
        if($('#pages-datatable').length){
            initialize_pages_table('#pages-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_pages_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const type = 'pages table';
    const filter_module = $('#filter_module').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PAGE_ID' },
        { 'data' : 'PAGE_NAME' },
        { 'data' : 'MODULE' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '64%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_module' : filter_module},
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

    $(document).on('click','#delete-page',function() {
        let page_id = [];
        const transaction = 'delete multiple page';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                page_id.push(element.value);  
            }
        });

        if(page_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Pages',
                text: 'Are you sure you want to delete these pages?',
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
                        data: {username : username, page_id : page_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_alert('Delete Multiple Pages Success', 'The pages have been deleted.', 'success');
    
                                reload_datatable('#pages-datatable');
                            }
                            else if(response === 'Inactive User'){
                                show_alert_event('Delete Multiple Pages Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                            }
                            else{
                                show_alert('Delete Multiple Pages Error', response, 'error');
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
            show_alert('Delete Multiple Pages Error', 'Please select the pages you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_pages_table('#pages-datatable');
    });
}