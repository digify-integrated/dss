<?php
    #$menu_array = $api->generate_menu_array('1', $username);
?>

<div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <?php
                                echo $api->generate_menu(1, $username);
                                #echo json_encode($menu_array['PARENTS'][0]);

                                #echo in_array('1', $menu_array['PARENTS'][0])
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>