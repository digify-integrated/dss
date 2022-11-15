(function($) {
    'use strict';

    $(function() {
        if($('#menu-item-datatable').length){
            initialize_menu_item_table('#menu-item-datatable');
        }

        if($('#card-header').length){
            var menu_id = $('#menu_id').val();

            initialize_card_actions(menu_id, true);
        }

        initialize_click_events();
    });
})(jQuery);

function initialize_menu_item_table(datatable_name, buttons = false, show_all = false){
    hide_multiple_buttons();
    
    var username = $('#username').text();
    var type = 'menu item table';
    var settings;

    var column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'MENU' },
        { 'data' : 'PARENT_MENU' },
        { 'data' : 'MODULE' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    var column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '24%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '20%', 'aTargets': 4 },
        { 'width': '10%','bSortable': false, 'aTargets': 5 },
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

    $(document).on('click','#add-system-code',function() {
        generate_modal('system code form', 'System Code', 'R' , '1', '1', 'form', 'system-code-form', '1', username);
    });

    $(document).on('click','.update-system-code',function() {
        var system_type = $(this).data('system-type');
        var menu_item = $(this).data('system-code');

        sessionStorage.setItem('system_type', system_type);
        sessionStorage.setItem('menu_item', menu_item);

        generate_modal('system code form', 'System Code', 'R' , '1', '1', 'form', 'system-code-form', '0', username);
    });

    $(document).on('click','.delete-system-code',function() {
        var system_type = $(this).data('system-type');
        var menu_item = $(this).data('system-code');

        var transaction = 'delete system code';

        Swal.fire({
            title: 'Delete System Code',
            text: 'Are you sure you want to delete this system code?',
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
                    data: {username : username, system_type : system_type, menu_item : menu_item, transaction : transaction},
                    success: function (response) {
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_alert('Delete System Code', 'The system code has been deleted.', 'success');
                            }
                            else{
                                show_alert('Delete System Code', 'The system code does not exist.', 'info');
                            }

                            reload_datatable('#system-code-datatable');
                        }
                        else{
                          show_alert('Delete System Code', response, 'error');
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#delete-system-code',function() {
        var system_type = [];
        var menu_item = [];
        var transaction = 'delete multiple system code';

        $('.datatable-checkbox-children').each(function(){
            if($(this).is(':checked')){  
                system_type.push($(this).data('system-type'));  
                menu_item.push($(this).data('system-code'));  
            }
        });

        if(system_type.length > 0 && menu_item.length > 0){
            Swal.fire({
                title: 'Delete Multiple System Codes',
                text: 'Are you sure you want to delete these system codes?',
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
                        data: {username : username, system_type : system_type, menu_item : menu_item, transaction : transaction},
                        success: function (response) {
                            if(response === 'Deleted' || response === 'Not Found'){
                                if(response === 'Deleted'){
                                    show_alert('Delete Multiple System Codes', 'The system codes have been deleted.', 'success');
                                }
                                else{
                                    show_alert('Delete Multiple System Codes', 'The system code does not exist.', 'info');
                                }
    
                                reload_datatable('#system-code-datatable');
                            }
                            else{
                                show_alert('Delete Multiple System Codes', response, 'error');
                            }
                        }
                    });
                    
                    return false;
                }
            });
        }
        else{
            show_alert('Delete Multiple System Codes', 'Please select the system codes you want to delete.', 'error');
        }
    });

}