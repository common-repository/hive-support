<div id="hive_lite_support_settings">

    <div class="hive_lite_support_settings_sections_container">
        <div class="hive_lite_support_settings_section_left">
            <div class="hive_lite_support_settings_section_stats">
                <h5>Setting Options</h5>
            </div>
            <div class="hive_lite_support_settings_section_left_items">
                <div class="hive_lite_support_settings_section_left_item active" onclick="hive_lite_support_settings_menu_click(this, `business_inbox`)">Business Inbox</div>
                <div class="hive_lite_support_settings_section_left_item" onclick="hive_lite_support_settings_menu_click(this, `ticket_fields`)">Ticket Fields</div>
                <div class="hive_lite_support_settings_section_left_item" onclick="hive_lite_support_settings_menu_click(this, `email_notification`)">Email Notification</div>
                <div class="hive_lite_support_settings_section_left_item hive_lite_support_settings_menu_item_shortcode" onclick="hive_lite_support_settings_menu_shortcode_click()">Copy Short Code</div>

            </div>

        </div>
        <div class="hive_lite_support_settings_section_right">
            <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/settings/business_inbox.php"; ?>
            <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/settings/ticket_fields.php"; ?>
            <?php include HIVE_LITE_SUPPORT_PATH . "backend/templates/views/settings/email_notification.php"; ?>
        </div>

    </div>





</div>