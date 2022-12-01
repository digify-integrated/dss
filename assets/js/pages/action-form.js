(function($) {
    'use strict';

    $(function() {
        if($('#action-id').length){
            var transaction = 'action details';
            var action_id = $('#action-id').text();

            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {action_id : action_id, transaction : transaction},
                success: function(response) {
                    $('#action_name').val(response[0].ACTION_NAME);
                    $('#action_id').val(action_id);
                }
            });
            
            if($('#action-access-datatable').length){
                initialize_action_access_table('#action-access-datatable');
            }
        }

        $('#action-form').validate({
            submitHandler: function (form) {
                var transaction = 'submit action';
                var username = $('#username').text();

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
                                var redirect_link = window.location.href + '?id=' + response[0]['ACTION_ID'];

                                show_alert_event('Insert Action Success', 'The action has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Action Success', 'The action has been updated.', 'success', 'reload');
                            }
                        }
                        else{
                            show_alert('Action Error', response, 'error');
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
                action_name: {
                    required: true
                },
            },
            messages: {
                action_name: {
                    required: 'Please enter the action name',
                },
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

function initialize_action_access_table(datatable_name, buttons = false, show_all = false){    
    var username = $('#username').text();
    var action_id = $('#action-id').text();
    var type = 'action access table';
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
                'data': {'type' : type, 'username' : username, 'action_id' : action_id},
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
                'data': {'type' : type, 'username' : username, 'action_id' : action_id},
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

    $(document).on('click','#add-action-access',function() {
        generate_modal('action access form', 'Action Access', 'R' , '1', '1', 'form', 'action-access-form', '1', username);
    });

    $(document).on('click','.delete-action-access',function() {
        var action_id = $(this).data('action-id');
        var role_id = $(this).data('role-id');
        var transaction = 'delete action access';

        Swal.fire({
            title: 'Delete Action Access',
            text: 'Are you sure you want to delete this action access?',
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
                    data: {username : username, action_id : action_id, role_id : role_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert('Delete Action Access', 'The action access has been deleted.', 'success');
                            }
                            else{
                                show_alert('Delete Action Access', 'The action access does not exist.', 'info');
                            }

                            reload_datatable('#action-access-datatable');
                        }
                        else{
                            show_alert('Delete Action Access', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-action',function() {
        var action_id = $(this).data('action-id');
        var transaction = 'delete action';

        Swal.fire({
            title: 'Delete Action',
            text: 'Are you sure you want to delete this action?',
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
                    data: {username : username, action_id : action_id, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert_event('Delete Action', 'The action has been deleted.', 'success', 'redirect', 'actions.php');
                            }
                            else{
                                show_alert_event('Delete Action', 'The action does not exist.', 'info', 'redirect', 'actions.php');
                            }
                        }
                        else{
                            show_alert('Delete Action', response, 'error');
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
                window.location.href = 'actions.php';
                return false;
            }
        });
    });

}