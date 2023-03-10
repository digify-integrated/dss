(function($) {
    'use strict';

    $(function() {
        if($('#upload-setting-id').length){
            display_details('upload setting details');

            if($('#upload-setting-file-type-datatable').length){
                initialize_upload_setting_file_type_table('#upload-setting-file-type-datatable');
            }
        }

        $('#upload-setting-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit upload setting';
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
                        switch (response[0]['RESPONSE']) {
                            case 'Inserted':
                                set_toastr('Upload Setting Inserted', 'The upload setting has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['UPLOAD_SETTING_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Upload Setting Updated', 'The upload setting has been updated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Transaction Error', response, 'error');
                                break;
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
                upload_setting: {
                    required: true
                },
                description: {
                    required: true
                }
            },
            messages: {
                upload_setting: {
                    required: 'Please enter the upload setting',
                },
                description: {
                    required: 'Please enter the upload setting description',
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

function initialize_upload_setting_file_type_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const upload_setting_id = $('#upload-setting-id').text();
    const type = 'upload setting file type table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_TYPE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'upload_setting_id' : upload_setting_id},
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

function initialize_upload_file_type_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const upload_setting_id = $('#upload-setting-id').text();
    const type = 'file type assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SYSTEM_DESCRIPTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'username' : username, 'upload_setting_id' : upload_setting_id},
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

    $(document).on('click','#add-upload-setting-file-type',function() {
        generate_modal('upload setting file type form', 'Allowed File Type', 'LG' , '1', '1', 'form', 'upload-setting-file-type-form', '1', username);
    });

    $(document).on('click','#delete-upload-setting',function() {
        const upload_setting_id = $(this).data('upload-setting-id');
        const transaction = 'delete upload setting';

        Swal.fire({
            title: 'Confirm Upload Setting Deletion',
            text: 'Are you sure you want to delete this upload setting?',
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
                        switch (response) {
                            case 'Deleted':
                                set_toastr('Upload Setting Deleted', 'The upload setting has been deleted successfully.', 'success');
                                window.location = 'upload-settings.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Upload Setting Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','.delete-upload-setting-file-type',function() {
        const upload_setting_id = $(this).data('upload-setting-id');
        const file_type = $(this).data('file-type');
        const transaction = 'delete upload setting file type';

        Swal.fire({
            title: 'Confirm File Type Deletion',
            text: 'Are you sure you want to delete this file type?',
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
                    data: {username : username, upload_setting_id : upload_setting_id, file_type : file_type, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('File Type Deleted', 'The selected file type has been deleted successfully.', 'success');
                                reload_datatable('#upload-setting-file-type-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('File Type Deletion Error', 'The selected file type does not exist or has already been deleted.', 'warning');
                                reload_datatable('#upload-setting-file-type-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('File Type Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('upload-settings.php');
    });
}