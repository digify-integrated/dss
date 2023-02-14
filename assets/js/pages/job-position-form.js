(function($) {
    'use strict';

    $(function() {
        if($('#job-position-id').length){
            display_details();

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
                        if(response[0]['RESPONSE'] === 'Updated' || response[0]['RESPONSE'] === 'Inserted'){
                            if(response[0]['RESPONSE'] === 'Inserted'){
                                var redirect_link = window.location.href + '?id=' + response[0]['JOB_POSITION_ID'];

                                show_alert_event('Insert Job Position Success', 'The job position has been inserted.', 'success', 'redirect', redirect_link);
                            }
                            else{
                                show_alert_event('Update Job Position Success', 'The job position has been updated.', 'success', 'reload');
                            }
                        }
                        else if(response[0]['RESPONSE'] === 'Inactive User'){
                            show_alert_event('Job Position Error', 'Your job position is inactive. Kindly contact your administrator.', 'error', 'redirect', 'logout.php?logout');
                        }
                        else{
                            show_alert('Job Position Error', response, 'error');
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

function display_details(){
    const transaction = 'job position details';
    const job_position_id = $('#job-position-id').text();

     $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'JSON',
        data: {job_position_id : job_position_id, transaction : transaction},
        success: function(response) {
            $('#job_position').val(response[0].JOB_POSITION);
            $('#description').val(response[0].DESCRIPTION);
            $('#expected_new_employees').val(response[0].EXPECTED_NEW_EMPLOYEES);

            document.getElementById('job_position_recruitment_status').innerHTML = response[0].RECRUITMENT_STATUS;

            check_empty(response[0].DEPARTMENT, '#department', 'select');

            $('#job_position_id').val(job_position_id);
        }
    });
}

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
            title: 'Delete Job Position',
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
                        if(response === 'Deleted'){
                            show_toastr('Delete Job Position Successful', 'The job position has been deleted successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Job Position Error', response, 'error');
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
            title: 'Delete Attachment',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Attachment Successful', 'The attachment has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Attachment Error', 'The attachment does not exist.', 'warning');
                            }

                            reload_datatable('#job-position-attachment-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Attachment Error', response, 'error');
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
            title: 'Delete Responsibility',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Responsibility Successful', 'The responsibility has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Responsibility Error', 'The responsibility does not exist.', 'warning');
                            }

                            reload_datatable('#job-position-responsibility-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Responsibility Error', response, 'error');
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
            title: 'Delete Requirement',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Requirement Successful', 'The requirement has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Requirement Error', 'The requirement does not exist.', 'warning');
                            }

                            reload_datatable('#job-position-requirement-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Requirement Error', response, 'error');
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
            title: 'Delete Qualification',
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
                        if(response === 'Deleted' || response === 'Not Found'){
                            if(response === 'Deleted'){
                                show_toastr('Delete Qualification Successful', 'The qualification has been deleted successfully.', 'success');
                            }
                            else{
                                show_toastr('Delete Qualification Error', 'The qualification does not exist.', 'warning');
                            }

                            reload_datatable('#job-position-qualification-datatable');
                        }
                        else if(response === 'Inactive User'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Delete Qualification Error', response, 'error');
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
            title: 'Start Job Position Recruitment',
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
                        if(response === 'Started'){
                            show_toastr('Start Job Position Recruitment Successful', 'The job position recruitment has been started successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Start Job Position Recruitment Error', response, 'error');
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
            title: 'Stop Job Position Recruitment',
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
                        if(response === 'Stopped'){
                            show_toastr('Stop Job Position Recruitment Successful', 'The job position recruitment has been stopped successfully.', 'success');
                        }
                        else if(response === 'Inactive User' || response === 'Not Found'){
                            window.location = '404.php';
                        }
                        else{
                            show_toastr('Stop Job Position Recruitment Error', response, 'error');
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
                window.location = 'job-positions.php';
                return false;
            }
        });
    });
}