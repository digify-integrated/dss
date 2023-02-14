<?php
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $username = $api->decrypt_data($id);
    
        $check_user_account_exist = $api->check_user_account_exist($username);
    
        if ($check_user_account_exist == 0) {
            header('location: index.php');
        }

        $get_user_account_details = $api->get_user_account_details($username);
        $file_as = $get_user_account_details[0]['FILE_AS'];

        $page_title = 'Change Password';

        require('views/_interface_settings.php');
    } 
    else {
        header('location: index.php');
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link rel="stylesheet" type="text/css" href="assets/libs/toastr/build/toastr.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <?php require('views/_required_css.php'); ?>
    </head>

    <body>
        
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary"><?php echo $page_title; ?></h5>
                                            <p>Your password has expired. To reset it, please update your password.</p>
                                        </div>
                                    </div>
                                    <div class="col-4 align-self-end">
                                        <img src="assets/images/default/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div class="p-2">
                                    <div id="message" class="alert alert-success text-center mb-0 mt-4 d-none" role="alert">
                                        Enter your Email and instructions will be sent to you!
                                    </div>
                                    <form class="form-horizontal" id="change-password-form" method="post" action="#">
            
                                        <div class="user-thumb text-center mb-4">
                                            <h5 class="font-size-15 mt-3"><?php echo ucwords($file_as); ?></h5>
                                            <input type="hidden" id="username" name="username" value="<?php echo $username; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" id="password" name="password" class="form-control" aria-label="Password" aria-describedby="password-addon">
                                                <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>
            
                                        <div class="text-end">
                                            <button id="change-password" class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                        </div>
    
                                    </form>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p class="mb-0">Copyright © <?php echo date('Y') ?> Digify Integrated Solutions. All rights reserved.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php require('views/_script.php'); ?>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/jquery-validation/js/jquery.validate.min.js"></script>
        <script src="assets/libs/toastr/build/toastr.min.js"></script>
        <script src="assets/js/system.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/pages/change-password.js?v=<?php echo rand(); ?>"></script>
        <script src="assets/js/form-validation-rules.js?v=<?php echo rand(); ?>"></script>
    </body>
</html>