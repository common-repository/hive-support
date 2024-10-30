<?php
if (!is_user_logged_in()) {
    return;
}
?>

<?php include HIVE_LITE_SUPPORT_PATH . "frontend/templates/views/form.php"; ?>
<?php include HIVE_LITE_SUPPORT_PATH . "frontend/templates/views/thread.php"; ?>
<?php include HIVE_LITE_SUPPORT_PATH . "frontend/templates/views/tickets.php"; ?>


<div class="hive_lite_support_client_loader">
    <div class="hive_lite_support_loader"></div>
</div>

<div class="hive_lite_support_client">

</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        'use strict';
        hive_lite_support_client_show_tickets()
    });
</script>