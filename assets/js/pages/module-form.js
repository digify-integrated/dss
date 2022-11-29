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
                    $('#module_id').val(module_id);

                    check_empty(response[0].MODULE_CATEGORY, '#module_category', 'select');
                }
            });

            
            /*if($('#modules-datatable').length){
                initialize_module_access_table('#modules-datatable');
            }*/
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
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        document.getElementById('submit-data').disabled = true;
                        $('#submit-data').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span rclass="sr-only"></span></div>');
                    },
                    success: function (response) {
                        if(response === 'Updated' || response === 'Inserted'){
                            if(response === 'Inserted'){
                                show_alert_event('Insert Module Success', 'The module has been inserted.', 'success', 'redirect');
                            }
                            else{
                                show_alert_event('Update Module Success', 'The module has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response === 'File Size'){
                            show_alert('Module Error', 'The file uploaded exceeds the maximum file size.', 'error');
                        }
                        else if(response === 'File Type'){
                            show_alert('Module Error', 'The file uploaded is not supported.', 'error');
                        }
                        else{
                            show_alert('Module Error', response, 'error');
                        }
                    },
                    complete: function(){
                        document.getElementById('submit-data').disabled = false;
                        $('#submit-data').html('Submit');
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
    var type = 'module access table';
    var settings;

    var column = [ 
        { 'data' : 'ROLE' },
        { 'data' : 'ACTION' }
    ];

    var column_definition = [
        { 'width': '90%', 'aTargets': 1 },
        { 'width': '10%','bSortable': false, 'aTargets': 2 }
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
                'data': {'type' : type, 'username' : username},
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
    }

    destroy_datatable(datatable_name);
    
    $(datatable_name).dataTable(settings);
}

function initialize_click_events(){
    var username = $('#username').text();

    $(document).on('click','#delete-public-holiday',function() {
        var modules_id = [];
        var transaction = 'delete multiple public holiday';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                modules_id.push(this.value);  
            }
        });

        if(modules_id.length > 0){
            Swal.fire({
                title: 'Delete Multiple Public Holidays',
                text: 'Are you sure you want to delete these public holidays?',
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
                        data: {username : username, modules_id : modules_id, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                if(response === 'Deleted'){
                                    show_alert('Delete Multiple Public Holidays', 'The public holidays have been deleted.', 'success');
                                }
                                else{
                                    show_alert('Delete Multiple Public Holidays', 'The public holidays does not exist.', 'info');
                                }
    
                                reload_datatable('#public-holiday-datatable');
                            }
                            else{
                                show_alert('Delete Multiple Public Holidays', response, 'error');
                            }
                        }
                    });
                    
                    return false;
                }
            });
        }
        else{
            show_alert('Delete Multiple Public Holidays', 'Please select the public holidays you want to delete.', 'error');
        }
    });

    $(document).on('click','#apply-filter',function() {
        initialize_modules_table('#modules-datatable');
    });

}