(function($) {
    'use strict';

    $(function() {
        if($('#country-id').length){
            display_details('country details');

            if($('#state-datatable').length){
                initialize_state_table('#state-datatable');
            }
        }

        $('#country-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit country';
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
                                set_toastr('Country Inserted', 'The country has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['COUNTRY_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Country Updated', 'The country has been updated successfully.', 'success');
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
                country_name: {
                    required: true
                }
            },
            messages: {
                country_name: {
                    required: 'Please enter the country',
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

function initialize_state_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const country_id = $('#country-id').text();
    const type = 'country state table';
    var settings;

    const column = [ 
        { 'data' : 'STATE_NAME' },
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
            'data': {'type' : type, 'username' : username, 'country_id' : country_id},
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

    $(document).on('click','#delete-country',function() {
        const country_id = $(this).data('country-id');
        const transaction = 'delete country';

        Swal.fire({
            title: 'Confirm Country Deletion',
            text: 'Are you sure you want to delete this country?',
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
                    data: {username : username, country_id : country_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                set_toastr('Country Deleted', 'The country has been deleted successfully.', 'success');
                                window.location = 'country.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Country Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-state',function() {
        generate_modal('state form', 'State', 'LG' , '1', '1', 'form', 'state-form', '1', username);
    });

    $(document).on('click','.update-state',function() {
        const state_id = $(this).data('state-id');

        sessionStorage.setItem('state_id', state_id);

        generate_modal('state form', 'State', 'LG' , '1', '1', 'form', 'state-form', '0', username);
    });

    $(document).on('click','.delete-state',function() {
        const state_id = $(this).data('state-id');
        const country_id = $(this).data('country-id');
        const transaction = 'delete state';

        Swal.fire({
            title: 'Confirm State Deletion',
            text: 'Are you sure you want to delete this state?',
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
                    data: {username : username, state_id : state_id, country_id : country_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('State Deleted', 'The selected action access has been deleted successfully.', 'success');
                                reload_datatable('#state-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('State Deletion Error', 'The selected action access does not exist or has already been deleted.', 'warning');
                                reload_datatable('#state-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('State Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('country.php');
    });
}