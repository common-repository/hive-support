<?php

$settings = $this->settings->get_all_hive_lite_support_settings();

// echo "<pre>";
// error_log(print_r($settings),true);
// echo "</pre>";

?>


<div class="hive_lite_support_main">

    <div class="hive_lite_support_sidebar">
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/sidebar.php"; ?>
    </div>


    <div class="hive_lite_support_body">
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/tickets.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/responses.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/thread.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/agents.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/activities.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/reports.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/automation.php"; ?>
        <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/settings.php"; ?>


        <div class="hive_lite_support_body_loader">
            <div class="hive_lite_support_loader"></div>
        </div>
    </div>

</div>


<script type="text/javascript">
    var hive_lite_support_ticket_fields = "<?php echo esc_attr($settings["ticket_fields"]); ?>";

    var hive_lite_support_host = "<?php echo esc_url(HIVE_LITE_SUPPORT_URL); ?>";

    jQuery(document).ready(function($) {
        'use strict';
        hive_lite_support_tickets_init();
        //hive_lite_support_thread_init(`1`)
    });
</script>