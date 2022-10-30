<div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <?php
                                echo $api->generate_menu($module_id, $username);
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>