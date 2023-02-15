(function($) {
    'use strict';

    $(function() {
        if($('#work-locations-datatable').length){
            initialize_work_location_table('#work-locations-datatable');
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_work_location_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    const username = $('#username').text();
    const filter_status = $('#filter_status').val();
    const type = 'work locations table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WORK_LOCATION_ID' },
        { 'data' : 'WORK_LOCATION' },
        { 'data' : 'STATUS' },
        { 'data' : 'VIEW' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '59%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%','bSortable': false, 'aTargets': 4 }
    ];


    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

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

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroy_datatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    const username = $('#username').text();

    $(document).on('click','#delete-work-location',function() {
        let work_location_id = [];
        const transaction = 'delete multiple work location';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                work_location_id.push(element.value);  
            }
        });

        if(work_location_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Work Locations',
                text: 'Are you sure you want to delete these work locations?',
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
                        data: {username : username, work_location_id : work_location_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                show_toastr('Delete Multiple Work Locations Successful', 'The work locations have been deleted successfully.', 'success');
    
                                reload_datatable('#work-locations-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Delete Multiple Work Locations Error', response, 'error');
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
            show_toastr('Delete Multiple Work Locations Error', 'Please select the work locations you want to delete.', 'error');
        }
    });

    $(document).on('click','#unarchive-work-location',function() {
        let work_location_id = [];
        const transaction = 'unarchive multiple work location';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                work_location_id.push(element.value);  
            }
        });

        if(work_location_id.length > 0){
            Swal.fire({
                title: 'Unarchive Multiple Work Locations',
                text: 'Are you sure you want to unarchive these work locations?',
                icon: 'info',
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
                        data: {username : username, work_location_id : work_location_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Unarchived' || response === 'Not Found'){
                                show_toastr('Unarchive Multiple Work Locations Successful', 'The work locations have been unarchived successfully.', 'success');
    
                                reload_datatable('#work-locations-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Unarchive Multiple Work Locations Error', response, 'error');
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
            show_toastr('Unarchive Multiple Work Locations Error', 'Please select the work locations you want to unarchive.', 'error');
        }
    });

    $(document).on('click','#archive-work-location',function() {
        let work_location_id = [];
        const transaction = 'archive multiple work location';

        $('.datatable-checkbox-children').each((index, element) => {
            if ($(element).is(':checked')) {
                work_location_id.push(element.value);  
            }
        });

        if(work_location_id.length > 0){
            Swal.fire({
                title: 'Archive Multiple Work Locations',
                text: 'Are you sure you want to archive these work locations?',
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
                        data: {username : username, work_location_id : work_location_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Archived' || response === 'Not Found'){
                                show_toastr('Archive Multiple Work Locations Successful', 'The work locations have been archived successfully.', 'success');
    
                                reload_datatable('#work-locations-datatable');
                            }
                            else if(response === 'Inactive User'){
                                window.location = '404.php';
                            }
                            else{
                                show_toastr('Archive Multiple Work Locations Error', response, 'error');
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
            show_toastr('Archive Multiple Work Locations Error', 'Please select the work locations you want to archive.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_work_location_table('#work-locations-datatable');
    });
}