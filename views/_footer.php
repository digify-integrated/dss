<?php
    $module_details = $api->get_module_details($module_id ?? 0);
    $module_version = $module_details[0]['MODULE_VERSION'] ?? '1.0.0';
?>

<footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                Copyright © <?php echo date('Y'); ?> Digify Integrated Solutions. All rights reserved.
                            </div>
                            <div class="col-sm-6 text-end">
                                <?php echo $module_version; ?>
                            </div>
                        </div>
                    </div>
                </footer>