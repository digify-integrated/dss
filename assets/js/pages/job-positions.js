(function($) {
    'use strict';

    $(function() {
        if($('#job-positions-datatable').length){
            initialize_job_positions_table('#job-positions-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_job_positions_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const filter_status = $('#filter_status').val();
    const filter_department = $('#filter_department').val();
    const type = 'job positions table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_POSITION_ID' },
        { 'data' : 'JOB_POSITION' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'NUMBER_OF_EMPLOYEE' },
        { 'data' : 'EXPECTED_NEW_EMPLOYEES' },
        { 'data' : 'FORECASTED_EMPLOYEE' },
        { 'data' : 'RECRUITMENT_STATUS' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '19%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%', 'aTargets': 7 },
        { 'width': '10%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'filter_status' : filter_status, 'filter_department' : filter_department},
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

    $(document).on('click','#delete-job-position',function() {
        let job_position_id = [];
        const transaction = 'delete multiple job position';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                job_position_id.push(element.value);  
            }
        });

        if(job_position_id.length > 0){
            Swal.fire({
                title: 'Confirm Multiple Job Positions Deletion',
                text: 'Are you sure you want to delete these job positions?',
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
                        data: {username : username, job_position_id : job_position_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                case 'Not Found':
                                    show_toastr('Multiple Job Positions Deleted', 'The selected job positions have been deleted successfully.', 'success');
                                    reload_datatable('#job-positions-datatable');
                                    break;
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    show_toastr('Multiple Job Positions Deletion Error', response, 'error');
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
            show_toastr('Multiple Job Positions Deletion Error', 'Please select the job positions you wish to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_job_positions_table('#job-positions-datatable');
    });
}