<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    $page_title = 'Apps';

    #$page_access = $api->check_role_permissions($username, 1);
    $check_user_account_status = $api->check_user_account_status($username);

    if(!$check_user_account_status){
        header('location: logout.php?logout');
    }

    require('views/_interface_settings.php');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <?php require('views/_preloader.php'); ?>

        <div id="layout-wrapper">            
            
            <?php require('views/_top_bar.php'); ?>

            <div class="main-content">

                <div class="page-content" style="margin-top:10px !important;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Apps</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                            <li class="breadcrumb-item active">Apps</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php 
                            include('views/_apps.php');
                        ?>
                    </div>
                </div>
                
                <?php require('views/_footer.php'); ?>
            </div>
        </div>
       

        <?php require('views/_script.php'); ?>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/dashboard.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>
