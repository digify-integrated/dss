(function($) {
    'use strict';

    $(function() {
        if($('#working-schedule-id').length){
            display_details('working schedule details');

            if($('#working-hours-datatable').length){
                initialize_working_hours_table('#working-hours-datatable');
            }
        }

        $('#working-schedule-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit working schedule';
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
                        if(response[0]['RESPONSE'] === 'Inserted'){
                            window.location = window.location.href + '?id=' + response[0]['WORKING_SCHEDULE_ID'];
                        }
                        else if(response[0]['RESPONSE'] === 'Updated'){
                            display_details('working schedule details');
                            reset_form();
                            
                            show_toastr('Update Successful', 'The working schedule has been updated successfully.', 'success');
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Transaction Error', response, 'error');
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
                working_schedule: {
                    required: true
                },
                working_schedule_type: {
                    required: true
                },
            },
            messages: {
                working_schedule: {
                    required: 'Please enter the working schedule',
                },
                working_schedule_type: {
                    required: 'Please choose the working schedule type',
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

function initialize_working_hours_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const working_schedule_id = $('#working-schedule-id').text();
    const type = 'working hours table';
    var settings;

    const column = [ 
        { 'data' : 'WORKING_HOURS' },
        { 'data' : 'WORKING_DATE' },
        { 'data' : 'DAY_OF_WEEK' },
        { 'data' : 'DAY_PERIOD' },
        { 'data' : 'WORK_FROM' },
        { 'data' : 'WORK_TO' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'working_schedule_id' : working_schedule_id},
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

    $(document).on('click','#delete-working-schedule',function() {
        const working_schedule_id = $(this).data('working-schedule-id');
        const transaction = 'delete working schedule';

        Swal.fire({
            title: 'Delete Working Schedule',
            text: 'Are you sure you want to delete this working schedule?',
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
                    data: {username : username, working_schedule_id : working_schedule_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted'){
                            window.location = 'working-schedules.php';
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Working Schedule Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-working-hours',function() {
        const category = $(this).data('category');

        if(category == 'FIXED'){
            generate_modal('fixed working hours form', 'Working Hours', 'R' , '0', '1', 'form', 'fixed-working-hours-form', '1', username);
        }
        else{
            generate_modal('flexible working hours form', 'Working Hours', 'R' , '0', '1', 'form', 'flexible-working-hours-form', '1', username);
        }
    });

    $(document).on('click','.update-working-hours',function() {
        const working_hours_id = $(this).data('working-hours-id');
        const category = $(this).data('category');

        sessionStorage.setItem('working_hours_id', working_hours_id);
        sessionStorage.setItem('category', category);

        if(category == 'FIXED'){
            generate_modal('fixed working hours form', 'Working Hours', 'R' , '0', '1', 'form', 'fixed-working-hours-form', '0', username);
        }
        else{
            generate_modal('flexible working hours form', 'Working Hours', 'R' , '0', '1', 'form', 'flexible-working-hours-form', '0', username);
        }
    });

    $(document).on('click','.delete-working-hours',function() {
        const working_hours_id = $(this).data('working-hours-id');
        const transaction = 'delete working hours';

        Swal.fire({
            title: 'Delete Working Hours',
            text: 'Are you sure you want to delete this working hours?',
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
                    data: {username : username, working_hours_id : working_hours_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Working Hours Successful', 'The working hours has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Working Hours Error', 'The working hours does not exist.', 'warning');
                            }

                            reload_datatable('#working-hours-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Working Hours Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        Swal.fire({
            title: 'Discard Changes',
            text: 'Are you sure you want to discard the changes associated with this item? Once discarded the changes are permanently lost.',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Discard',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                window.location = 'working-schedules.php';
                return false;
            }
        });
    });
}