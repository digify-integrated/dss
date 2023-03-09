(function($) {
    'use strict';

    $(function() {
        check_toastr();
        
        if($('#job-position-id').length){
            display_details('job position details');

            if($('#job-position-attachment-datatable').length){
                initialize_job_position_attachment_table('#job-position-attachment-datatable');
            }

            if($('#job-position-responsibility-datatable').length){
                initialize_job_position_responsibility_table('#job-position-responsibility-datatable');
            }

            if($('#job-position-requirement-datatable').length){
                initialize_job_position_requirement_table('#job-position-requirement-datatable');
            }

            if($('#job-position-qualification-datatable').length){
                initialize_job_position_qualification_table('#job-position-qualification-datatable');
            }
        }

        $('#job-position-form').validate({
            submitHandler: function (form) {
                const transaction = 'submit job position';
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
                                set_toastr('Job Position Inserted', 'The job position has been inserted successfully.', 'success');
                                window.location = window.location.href + '?id=' + response[0]['JOB_POSITION_ID'];
                                break;
                            case 'Updated':
                                set_toastr('Job Position Updated', 'The job position has been updated successfully.', 'success');
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
                job_position: {
                    required: true
                },
                description: {
                    required: true
                },
                department: {
                    required: true
                }
            },
            messages: {
                job_position: {
                    required: 'Please enter the job position',
                },
                description: {
                    required: 'Please enter the description',
                },
                department: {
                    required: 'Please choose the department',
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

function initialize_job_position_attachment_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const job_position_id = $('#job-position-id').text();
    const type = 'job position attachment table';
    var settings;

    const column = [ 
        { 'data' : 'ATTACHMENT' },
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
            'data': {'type' : type, 'username' : username, 'job_position_id' : job_position_id},
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

function initialize_job_position_responsibility_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const job_position_id = $('#job-position-id').text();
    const type = 'job position responsibility table';
    var settings;

    const column = [ 
        { 'data' : 'RESPONSIBILITY' },
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
            'data': {'type' : type, 'username' : username, 'job_position_id' : job_position_id},
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

function initialize_job_position_requirement_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const job_position_id = $('#job-position-id').text();
    const type = 'job position requirement table';
    var settings;

    const column = [ 
        { 'data' : 'REQUIREMENT' },
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
            'data': {'type' : type, 'username' : username, 'job_position_id' : job_position_id},
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

function initialize_job_position_qualification_table(datatable_name, buttons = false, show_all = false){
    const username = $('#username').text();
    const job_position_id = $('#job-position-id').text();
    const type = 'job position qualification table';
    var settings;

    const column = [ 
        { 'data' : 'QUALIFICATION' },
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
            'data': {'type' : type, 'username' : username, 'job_position_id' : job_position_id},
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

    $(document).on('click','#add-attachment',function() {
        generate_modal('job position attachment form', 'Attachment', 'R' , '1', '1', 'form', 'job-position-attachment-form', '1', username);
    });

    $(document).on('click','.update-attachment',function() {
        const attachment_id = $(this).data('attachment-id');

        sessionStorage.setItem('attachment_id', attachment_id);

        generate_modal('job position attachment form', 'Attachment', 'R' , '1', '1', 'form', 'job-position-attachment-form', '0', username);
    });

    $(document).on('click','#delete-job-position',function() {
        const job_position_id = $(this).data('job-position-id');
        const transaction = 'delete job position';

        Swal.fire({
            title: 'Confirm Job Position Deletion',
            text: 'Are you sure you want to delete this job position?',
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
                    data: {username : username, job_position_id : job_position_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                window.location = 'job-positions.php';
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Job Position Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','.delete-attachment',function() {
        const attachment_id = $(this).data('attachment-id');
        const transaction = 'delete job position attachment';

        Swal.fire({
            title: 'Confirm Attachment Deletion',
            text: 'Are you sure you want to delete this attachment?',
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
                    data: {username : username, attachment_id : attachment_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Attachment Deleted', 'The attachment has been deleted successfully.', 'success');
                                reload_datatable('#job-position-attachment-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Attachment Deletion Error', 'The attachment does not exist or has already been deleted.', 'warning');
                                reload_datatable('#job-position-attachment-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Attachment Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-responsibility',function() {
        generate_modal('job position responsibility form', 'Responsibility', 'R' , '1', '1', 'form', 'job-position-responsibility-form', '1', username);
    });

    $(document).on('click','.update-responsibility',function() {
        const responsibility_id = $(this).data('responsibility-id');

        sessionStorage.setItem('responsibility_id', responsibility_id);

        generate_modal('job position responsibility form', 'Responsibility', 'R' , '1', '1', 'form', 'job-position-responsibility-form', '0', username);
    });

    $(document).on('click','.delete-responsibility',function() {
        const responsibility_id = $(this).data('responsibility-id');
        const transaction = 'delete job position responsibility';

        Swal.fire({
            title: 'Confirm Responsibility Deletion',
            text: 'Are you sure you want to delete this responsibility?',
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
                    data: {username : username, responsibility_id : responsibility_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Responsibility Deleted', 'The responsibility has been deleted successfully.', 'success');
                                reload_datatable('#job-position-responsibility-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Responsibility Deletion Error', 'The responsibility does not exist or has already been deleted.', 'warning');
                                reload_datatable('#job-position-responsibility-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Responsibility Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-requirement',function() {
        generate_modal('job position requirement form', 'Requirement', 'R' , '1', '1', 'form', 'job-position-requirement-form', '1', username);
    });

    $(document).on('click','.update-requirement',function() {
        const requirement_id = $(this).data('requirement-id');

        sessionStorage.setItem('requirement_id', requirement_id);

        generate_modal('job position requirement form', 'Requirement', 'R' , '1', '1', 'form', 'job-position-requirement-form', '0', username);
    });

    $(document).on('click','.delete-requirement',function() {
        const requirement_id = $(this).data('requirement-id');
        const transaction = 'delete job position requirement';

        Swal.fire({
            title: 'Confirm Requirement Deletion',
            text: 'Are you sure you want to delete this requirement?',
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
                    data: {username : username, requirement_id : requirement_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Requirement Deleted', 'The requirement has been deleted successfully.', 'success');
                                reload_datatable('#job-position-requirement-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Requirement Deletion Error', 'The requirement does not exist or has already been deleted.', 'warning');
                                reload_datatable('#job-position-requirement-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Requirement Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#add-qualification',function() {
        generate_modal('job position qualification form', 'Qualification', 'R' , '1', '1', 'form', 'job-position-qualification-form', '1', username);
    });

    $(document).on('click','.update-qualification',function() {
        const qualification_id = $(this).data('qualification-id');

        sessionStorage.setItem('qualification_id', qualification_id);

        generate_modal('job position qualification form', 'Qualification', 'R' , '1', '1', 'form', 'job-position-qualification-form', '0', username);
    });

    $(document).on('click','.delete-qualification',function() {
        const qualification_id = $(this).data('qualification-id');
        const transaction = 'delete job position qualification';

        Swal.fire({
            title: 'Confirm Qualification Deletion',
            text: 'Are you sure you want to delete this qualification?',
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
                    data: {username : username, qualification_id : qualification_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Deleted':
                                show_toastr('Qualification Deleted', 'The qualification has been deleted successfully.', 'success');
                                reload_datatable('#job-position-qualification-datatable');
                                break;
                            case 'Not Found':
                                show_toastr('Qualification Deletion Error', 'The qualification does not exist or has already been deleted.', 'warning');
                                reload_datatable('#job-position-qualification-datatable');
                                break;
                            case 'Inactive User':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Qualification Deletion Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#start-job-position-recruitment',function() {
        const job_position_id = $(this).data('job-position-id');
        const transaction = 'start job position recruitment';

        Swal.fire({
            title: 'Confirm Job Position Recruitment Start',
            text: 'Are you sure you want to start this job position recruitment?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Start',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, job_position_id : job_position_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Started':
                                set_toastr('Job Position Recruitment Started', 'The job position recruitment has been started successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Job Position Recruitment Start Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#stop-job-position-recruitment',function() {
        const job_position_id = $(this).data('job-position-id');
        const transaction = 'stop job position recruitment';

        Swal.fire({
            title: 'Confirm Job Position Recruitment Stop',
            text: 'Are you sure you want to stop this job position recruitment?',
            icon: 'warning',
            showCancelButton: !0,
            confirmButtonText: 'Stop',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger mt-2',
            cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: {username : username, job_position_id : job_position_id, transaction : transaction},
                    success: function (response) {
                        switch (response) {
                            case 'Stopped':
                                set_toastr('Job Position Recruitment Stopped', 'The job position recruitment has been stopped successfully.', 'success');
                                window.location.reload();
                                break;
                            case 'Inactive User':
                            case 'Not Found':
                                window.location = '404.php';
                                break;
                            default:
                                show_toastr('Job Position Recruitment Stop Error', response, 'error');
                                break;
                        }
                    }
                });
                return false;
            }
        });
    });

    $(document).on('click','#discard-create',function() {
        discard('job-positions.php');
    });
}