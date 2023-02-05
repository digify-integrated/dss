(function($) {
    'use strict';

    $(function() {
        if($('#zoom-api-id').length){
            const transaction = 'zoom api details';
            const zoom_api_id = $('#zoom-api-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {zoom_api_id : zoom_api_id, transaction : transaction},
                success: function(response) {
                    $('#zoom_api_name').val(response[0].ZOOM_API_NAME);
                    $('#api_key').val(response[0].API_KEY);
                    $('#api_secret').val(response[0].API_SECRET);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);
                    $('#description').val(response[0].DESCRIPTION);

                    document.getElementById('zoom_api_status').innerHTML = response[0].STATUS;

                    $('#zoom_api_id').val(zoom_api_id);
                },
                complete: function(){                    
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            });
        }

        $('#zoom-api-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit zoom api';
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
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['ZOOM_API_ID'];

                                show_alert_event('Insert Zoom API Success', 'The Zoom API has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Zoom API Success', 'The Zoom API has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Zoom API Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Zoom API Error', response, 'error');
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
                zoom_api_name: {
                    required: true
                },
                description: {
                    required: true
                },
                api_key: {
                    required: true
                },
                api_secret: {
                    required: true
                }
            },
            messages: {
                zoom_api_name: {
                    required: 'Please enter the zoom api name',
                },
                description: {
                    required: 'Please enter the description',
                },
                api_key: {
                    required: 'Please enter the API Key',
                },
                api_secret: {
                    required: 'Please enter the API Secret',
                }
            },
            errorPlacement: function(label, element) {
                if((element.hasClass('select2') || element.hasClass('form-select2')) && element.next('.select2-container').length) {
                    label.insertAfter(element.next('.select2-container'));
                }
                else if(element.parent('.input-group').length){
                    label.insertAfter(element.parent());
                }
                else{
                    label.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).parent().addClass('has-danger');
                $(element).addClass('form-control-danger');
            },
            success: function(label,element) {
                $(element).parent().removeClass('has-danger')
                $(element).removeClass('form-control-danger')
                label.remove();
            }
        });

        initialize_click_events();
    });
})(jQuery);

function initialize_transaction_log_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const transaction_log_id = $('#transaction_log_id').val();
    const type = 'transaction log table';
    var settings;

    const column = [ 
        { 'data' : 'LOG_TYPE' },
        { 'data' : 'LOG' },
        { 'data' : 'LOG_DATE' },
        { 'data' : 'LOG_BY' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
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

    $(document).on('click','#activate-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'activate zoom api';

        Swal.fire({
            title: 'Activate Zoom API',
            text: 'Are you sure you want to activate this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Activated' || response === 'Not Found'){
                            if(response === 'Activated'){
                                show_alert_event('Activate Zoom API Success', 'The Zoom API has been activated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Activate Zoom API Error', 'The Zoom API does not exist.', 'info', 'redirect', 'zoom-api.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Activate Zoom API Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Activate Zoom API Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#deactivate-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'deactivate zoom api';

        Swal.fire({
            title: 'Deactivate Zoom API',
            text: 'Are you sure you want to deactivate this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deactivated' || response === 'Not Found'){
                            if(response === 'Deactivated'){
                                show_alert_event('Deactivate Zoom API Success', 'The Zoom API has been deactivated.', 'success', 'reload');
                            }
                            else{
                                show_alert_event('Deactivate Zoom API Error', 'The Zoom API does not exist.', 'info', 'redirect', 'zoom-api.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Deactivate Zoom API Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Deactivate Zoom API Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-zoom-api',function() {
        const zoom_api_id = $(this).data('zoom-api-id');
        const transaction = 'delete zoom api';

        Swal.fire({
            title: 'Delete Zoom API',
            text: 'Are you sure you want to delete this Zoom API?',
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
                    data: {username : username, zoom_api_id : zoom_api_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Zoom API Success', 'The Zoom API has been deleted.', 'success', 'redirect', 'zoom-api.php');
                            }
                            else{
                                show_alert_event('Delete Zoom API Error', 'The Zoom API does not exist.', 'info', 'redirect', 'zoom-api.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Zoom API Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Zoom API Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard',function() {
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
                window.location.href = 'zoom-api.php';
                return false;
            }
        });
    });
}