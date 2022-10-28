<?php
    $menu_array = $api->generate_menu_array('1', $username);
?>

<div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title" key="t-menu">Menu</li>

                            <!--<li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-home-circle"></i>
                                    <span key="t-dashboards">Dashboards</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="index.html" key="t-default">Default</a></li>
                                    <li><a href="dashboard-saas.html" key="t-saas">Saas</a></li>
                                    <li><a href="dashboard-crypto.html" key="t-crypto">Crypto</a></li>
                                    <li><a href="dashboard-blog.html" key="t-blog">Blog</a></li>
                                    <li><a href="dashboard-job.html"><span class="badge rounded-pill text-bg-success float-end" key="t-new">New</span> <span key="t-jobs">Jobs</span></a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="apps-filemanager.html" class="waves-effect">
                                    <i class="bx bx-file"></i>
                                    <span key="t-file-manager">File Manager</span>
                                </a>
                            </li>-->
                            <?php
                                echo $api->generate_menu(0, $menu_array);
                                #echo json_encode($menu_array['PARENTS'][0]);

                                #echo in_array('1', $menu_array['PARENTS'][0])
                            ?>
                        </ul>
                    </div>
                </div>
            </div>