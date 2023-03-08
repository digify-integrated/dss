(function($) {
    'use strict';

    $(function() {
        if($('#module-id').length){
            display_details('module details');

            if($('#module-access-datatable').length){
                initialize_module_access_table('#module-access-datatable');
            }
        }

        $('#module-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit module';
                const username = $('#username').text();
                
                var formData = new FormData(form);
                formData.append('username', username);
                formData.append('transaction', transaction);

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        document.getElementById('submit-data').disabled = true;
                        $('#submit-data').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        switch (response[0]['RESPONSE']) {
                            case 'Inserted':
                                set_toastr('Module Inserted', 'The module has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['MODULE_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Module Updated', 'The module has been updated successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'File Size':
                                show_toastr('Module Icon Upload Error', 'The file uploaded exceeds the maximum file size.', 'error');
                                break;
                            case 'File Type':
                                show_toastr('Module Icon Upload Error', 'The file uploaded is not supported.', 'error');
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
                module_name: {
                    required: true
                },
                module_description: {
                    required: true
                },
                module_category: {
                    required: true
                },
                default_page: {
                    required: true
                },
                module_version: {
                    required: true
                }
            },
            messages: {
                module_name: {
                    required: 'Please enter the module name',
                },
                module_description: {
                    required: 'Please enter the module description',
                },
                module_category: {
                    required: 'Please choose the module category',
                },
                default_page: {
                    required: 'Please enter the default page',
                },
                module_version: {
                    required: 'Please enter the module version',
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

function initialize_module_access_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const module_id = $('#module-id').text();
    const type = 'module access table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE' },
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
            'data': {'type' : type, 'username' : username, 'module_id' : module_id},
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

function initialize_role_assignment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const module_id = $('#module-id').text();
    const type = 'module role assignment table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE' }
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
            'data': {'type' : type, 'username' : username, 'module_id' : module_id},
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

    $(document).on('click','#add-module-access',function() {
        generate_modal('module access form', 'Module Access', 'LG' , '1', '1', 'form', 'module-access-form', '1', username);
    });

    $(document).on('click','#delete-module',function() {
        const module_id = $(this).data('module-id');
        const transaction = 'delete module';

        Swal.fire({
            title: 'Confirm Module Deletion',
            text: 'Are you sure you want to delete this module?',
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
                    data: {username : username, module_id : module_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'modules.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Module Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','.delete-module-access',function() {
        const module_id = $(this).data('module-id');
        const role_id = $(this).data('role-id');
        const transaction = 'delete module access';

        Swal.fire({
            title: 'Confirm Module Access Deletion',
            text: 'Are you sure you want to delete this module access?',
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
                    data: {username : username, module_id : module_id, role_id : role_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Module Access Deleted', 'The selected module access has been deleted successfully.', 'success');
                                reload_datatable('#module-access-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Module Access Deletion Error', 'The selected module access does not exist or has already been deleted.', 'warning');
                                reload_datatable('#module-access-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Module Access Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('modules.php');
    });
}