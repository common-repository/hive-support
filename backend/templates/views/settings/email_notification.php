<div class="hive_lite_support_settings_email_notification">




    <div class="hive_lite_support_settings_email_notification_body">
        <div class="hive_lite_support_settings_email_notification_body_header">
            <div>
                <h3>Email Notifications</h3>
                <p>Control all types of email notification sent from Hive Support.</p>
            </div>
            <button onclick="hive_lite_support_settings_email_notification_save()">Save Changes</button>
        </div>

        <div class="hive_lite_support_settings_email_notification_body_items">

            <div class="hive_lite_support_settings_email_notification_body_item">
                <div class="hive_lite_support_settings_email_notification_body_item_header">
                    <h4>Send email to support staff when customer replies</h4>
                    <label class="toggle_switch enable_email_to_staff_on_customer_reply"><input type="checkbox" <?php echo esc_attr($settings["enable_email_to_staff_on_customer_reply"] == "1" ? "checked" : "") ?> onchange="hive_lite_support_settings_email_settings_changed(this)"><span class="slider round"></span></label>
                </div>
                <div class="hive_lite_support_settings_email_notification_body_item_body" style="<?php echo esc_attr($settings["enable_email_to_staff_on_customer_reply"] == "1" ? "" : "display: none;") ?>">
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_staff_on_customer_reply_subject">
                        <label>Subject</label>
                        <input type="text" value="<?php echo esc_attr($settings["email_to_staff_on_customer_reply_subject"]) ?>" />
                    </div>
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_staff_on_customer_reply_body">
                        <label>Email Body</label>
                        <textarea rows="4"><?php echo esc_attr($settings["email_to_staff_on_customer_reply_body"]) ?></textarea>
                    </div>
                </div>
            </div>


            <div class="hive_lite_support_settings_email_notification_body_item">
                <div class="hive_lite_support_settings_email_notification_body_item_header">
                    <h4>Send email to customer when support staff replies</h4>
                    <label class="toggle_switch enable_email_to_customer_on_staff_reply"><input type="checkbox" <?php echo esc_attr($settings["enable_email_to_customer_on_staff_reply"] == "1" ? "checked" : "") ?> onchange="hive_lite_support_settings_email_settings_changed(this)"><span class="slider round"></span></label>
                </div>
                <div class="hive_lite_support_settings_email_notification_body_item_body" style="<?php echo esc_attr($settings["enable_email_to_customer_on_staff_reply"] == "1" ? "" : "display: none;") ?>">
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_customer_on_staff_reply_subject">
                        <label>Subject</label>
                        <input type="text" value="<?php echo esc_attr($settings["email_to_customer_on_staff_reply_subject"]) ?>" />
                    </div>
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_customer_on_staff_reply_body">
                        <label>Email Body</label>
                        <textarea rows="4"><?php echo esc_attr($settings["email_to_customer_on_staff_reply_body"]) ?></textarea>
                    </div>
                </div>
            </div>



            <div class="hive_lite_support_settings_email_notification_body_item">
                <div class="hive_lite_support_settings_email_notification_body_item_header">
                    <h4>Send email to admin when new ticket created</h4>
                    <label class="toggle_switch enable_email_to_admin_on_ticket_created"><input type="checkbox" <?php echo esc_attr($settings["enable_email_to_admin_on_ticket_created"] == "1" ? "checked" : "") ?> onchange="hive_lite_support_settings_email_settings_changed(this)"><span class="slider round"></span></label>
                </div>
                <div class="hive_lite_support_settings_email_notification_body_item_body" style="<?php echo esc_attr($settings["enable_email_to_admin_on_ticket_created"] == "1" ? "" : "display: none;") ?>">
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_admin_on_ticket_created_subject">
                        <label>Subject</label>
                        <input type="text" value="<?php echo esc_attr($settings["email_to_admin_on_ticket_created_subject"]) ?>" />
                    </div>
                    <div class="hive_lite_support_settings_email_notification_single_form_element email_to_admin_on_ticket_created_body">
                        <label>Email Body</label>
                        <textarea rows="4"><?php echo esc_attr($settings["email_to_admin_on_ticket_created_body"]) ?></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>






</div>