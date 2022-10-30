<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;

    if(isset($_GET['module']) && isset($_GET['menu']) && !empty($_GET['module']) && !empty($_GET['menu'])){
        $module_id = $api->decrypt_data($_GET['module']);
        $menu_id = $api->decrypt_data($_GET['menu']);

        $menu_access_right = $api->check_role_access_rights($username, $menu_id, 'menu');

        if($menu_access_right == 0){
            header('location: apps.php');
        }
        else{
            $technical_menu_details = $api->get_technical_menu_details($menu_id);
            $full_path = $technical_menu_details[0]['FULL_PATH'];
            $page_title = $technical_menu_details[0]['MENU'];
            $menu_web_icon = $api->check_image($technical_menu_details[0]['MENU_WEB_ICON'], 'favicon');

            if(isset($_GET['system']) && !empty($_GET['system'])){
                $system = $api->decrypt_data($_GET['system']);
                $full_path = $full_path . '<li class="breadcrumb-item" id="system-id">'. $system .'</li>';
            }

            if(isset($_GET['views']) && !empty($_GET['views'])){
                $generate_technical_view = $api->generate_technical_view($menu_id, $_GET['views']);
            }
            else{
                $generate_technical_view = $api->generate_technical_view($menu_id);
            }

            #$technical_view_css = $generate_technical_view[0]['CSS'];
            #$technical_view_view = $generate_technical_view[0]['VIEW'];
            #$technical_view_javascript = $generate_technical_view[0]['JAVASCRIPT'];
        }
    }
    else{
        header('location: apps.php');
    }

    require('views/_interface_settings.php');
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <?php require('views/_preloader.php'); ?>

        <div id="layout-wrapper">

            <?php 
                require('views/_top_bar.php');
                require('views/menu/_menu.php');
            ?>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $page_title; ?></h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <?php echo $full_path; ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php echo $generate_technical_view; ?>
                    </div>
                </div>

                <?php require('views/_footer.php'); ?>
            </div>

        </div>

        <?php require('views/_script.php'); ?>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/leave-type.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>
