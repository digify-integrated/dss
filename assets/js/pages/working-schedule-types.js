(function($) {
    'use strict';

    $(function() {
        if($('#working-schedule-types-datatable').length){
            initialize_working_schedule_type_table('#working-schedule-types-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_working_schedule_type_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const type = 'working schedule types table';
    const filter_category = $('#filter_category').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WORKING_SCHEDULE_TYPE_ID' },
        { 'data' : 'WORKING_SCHEDULE_TYPE' },
        { 'data' : 'WORKING_SCHEDULE_TYPE_CATEGORY' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '44%', 'aTargets': 2 },
        { 'width': '30%', 'aTargets': 3 },
        { 'width': '10%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_category' : filter_category},
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

    $(document).on('click','#delete-working-schedule-type',function() {
        let working_schedule_type_id = [];
        const transaction = 'delete multiple working schedule type';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                working_schedule_type_id.push(element.value);  
            }
        });

        if(working_schedule_type_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Working Schedule Types',
                text: 'Are you sure you want to delete these working schedule types?',
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
                        data: {username : username, working_schedule_type_id : working_schedule_type_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_toastr('Delete Multiple Working Schedule Types Successful', 'The working schedule types have been deleted successfully.', 'success');
    
                                reload_datatable('#working-schedule-types-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Delete Multiple Working Schedule Types Error', response, 'error');
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
            show_toastr('Delete Multiple Working Schedule Types Error', 'Please select the working schedule types you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_working_schedule_type_table('#working-schedule-types-datatable');
    });
}