(function($) {
    'use strict';

    $(function() {
        if($('#module-id').length){
            var transaction = 'module details';
            var module_id = $('#module-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {module_id : module_id, transaction : transaction},
                success: function(response) {
                    $('#module_name').val(response[0].MODULE_NAME);
                    $('#module_description').val(response[0].MODULE_DESCRIPTION);
                    $('#module_version').val(response[0].MODULE_VERSION);
                    $('#default_page').val(response[0].DEFAULT_PAGE);
                    $('#order_sequence').val(response[0].ORDER_SEQUENCE);
                    $('#transaction_log_id').val(response[0].TRANSACTION_LOG_ID);

                    document.getElementById('module_icon_image').innerHTML = response[0].MODULE_ICON;
                    
                    $('#module_id').val(module_id);

                    check_empty(response[0].MODULE_CATEGORY, '#module_category', 'select');
                },
                complete: function(){
                    if($('#module-access-datatable').length){
                        initialize_module_access_table('#module-access-datatable');
                    }
        
                    if($('#transaction-log-datatable').length){
                        initialize_transaction_log_table('#transaction-log-datatable');
                    }
                }
            });
        }

        $('#module-form').validate({
            submitHandler: function (form) {
                var transaction = 'submit module';
                var username = $('#username').text();
                
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
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['MODULE_ID'];

                                show_alert_event('Insert Module Success', 'The module has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Module Success', 'The module has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'File Size'){
                            show_alert('Module Error', 'The file uploaded exceeds the maximum file size.', 'error');
                        }
                        else if(response[0]['RESPONSE'] === 'File Type'){
                            show_alert('Module Error', 'The file uploaded is not supported.', 'error');
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Module Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Module Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('Save');
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

function initialize_module_access_table(datatable_name, buttons = false, show_all = false){    
    var username = $('#username').text();
    var module_id = $('#module-id').text();
    var type = 'module access table';
    var settings;

    var column = [ 
        { 'data' : 'ROLE' },
        { 'data' : 'ACTION' }
    ];

    var column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%','bSortable': false, 'aTargets': 1 }
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'module_id' : module_id},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
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
    }
    else{
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
    }

    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_transaction_log_table(datatable_name, buttons = false, show_all = false){    
    var username = $('#username').text();
    var transaction_log_id = $('#transaction_log_id').val();
    var type = 'transaction log table';
    var settings;

    var column = [ 
        { 'data' : 'LOG_TYPE' },
        { 'data' : 'LOG' },
        { 'data' : 'LOG_DATE' },
        { 'data' : 'LOG_BY' }
    ];

    var column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            'order': [[ 2, 'desc' ]],
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
    }
    else{
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'transaction_log_id' : transaction_log_id},
                'dataSrc' : ''
            },
            'order': [[ 2, 'desc' ]],
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
    }
    
    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_role_assignment_table(datatable_name, buttons = false, show_all = false){
    var username = $('#username').text();
    var module_id = $('#module-id').text();
    var type = 'module role assignment table';
    var settings;

    var column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE' }
    ];

    var column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '99%','bSortable': false, 'aTargets': 1 }
    ];

    if(show_all){
        length_menu = [ [-1], ['All'] ];
    }
    else{
        length_menu = [ [20, 50, 100, -1], [20, 50, 100, 'All'] ];
    }

    if(buttons){
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'module_id' : module_id},
                'dataSrc' : ''
            },
            dom:  "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
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
    }
    else{
        settings = {
            'ajax': { 
                'url' : 'system-generation.php',
                'method' : 'POST',
                'dataType': 'JSON',
                'data': {'type' : type, 'username' : username, 'module_id' : module_id},
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
    }

    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    var username = $('#username').text();

    $(document).on('click','#add-module-access',function() {
        generate_modal('module access form', 'Module Access', 'LG' , '1', '1', 'form', 'module-access-form', '1', username);
    });

    $(document).on('click','.delete-module-access',function() {
        var module_id = $(this).data('module-id');
        var role_id = $(this).data('role-id');
        var transaction = 'delete module access';

        Swal.fire({
            title: 'Delete Module Access',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert('Delete Module Access Success', 'The module access has been deleted.', 'success');
                            }
                            else{
                                show_alert('Delete Module Access Error', 'The module access does not exist.', 'info');
                            }

                            reload_datatable('#module-access-datatable');
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Module Access Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Module Access Error', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-module',function() {
        var module_id = $(this).data('module-id');
        var transaction = 'delete module';

        Swal.fire({
            title: 'Delete Module',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Module Success', 'The module has been deleted.', 'success', 'redirect', 'modules.php');
                            }
                            else{
                                show_alert_event('Delete Module Error', 'The module does not exist.', 'info', 'redirect', 'modules.php');
                            }
                        }
                        else if(response === 'Inactive User'){
                            show_alert_event('Delete Module Error', 'Your user account is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Delete Module Error', response, 'error');
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
                window.location.href = 'modules.php';
                return false;
            }
        });
    });

}