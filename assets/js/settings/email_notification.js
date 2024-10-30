function hive_lite_support_settings_email_notification_init() {
  //hive_lite_support_settings_ticket_field_fetch_form()
  //hive_lite_support_settings_ticket_fields_drag_and_drop_init()
}

function hive_lite_support_settings_email_settings_changed(view) {
  if (jQuery(view).parent().find("input[type='checkbox']:checked").length > 0) {
    jQuery(view)
      .parent()
      .parent()
      .parent()
      .find(".hive_lite_support_settings_email_notification_body_item_body")
      .show();
  } else {
    jQuery(view)
      .parent()
      .parent()
      .parent()
      .find(".hive_lite_support_settings_email_notification_body_item_body")
      .hide();
  }
}

function hive_lite_support_settings_email_notification_save() {
  jQuery(
    ".hive_lite_support_settings_email_notification_body_header button"
  ).text("Saving...");

  var post_data = {
    action: "hive_lite_support_email_notification_save",

    enable_email_to_staff_on_customer_reply:
      jQuery(
        ".enable_email_to_staff_on_customer_reply input[type='checkbox']:checked"
      ).length > 0
        ? "1"
        : "0",
    email_to_staff_on_customer_reply_subject: jQuery(
      ".email_to_staff_on_customer_reply_subject input"
    ).val(),
    email_to_staff_on_customer_reply_body: jQuery(
      ".email_to_staff_on_customer_reply_body textarea"
    ).val(),

    enable_email_to_customer_on_staff_reply:
      jQuery(
        ".enable_email_to_customer_on_staff_reply input[type='checkbox']:checked"
      ).length > 0
        ? "1"
        : "0",
    email_to_customer_on_staff_reply_subject: jQuery(
      ".email_to_customer_on_staff_reply_subject input"
    ).val(),
    email_to_customer_on_staff_reply_body: jQuery(
      ".email_to_customer_on_staff_reply_body textarea"
    ).val(),

    enable_email_to_admin_on_ticket_created:
      jQuery(
        ".enable_email_to_admin_on_ticket_created input[type='checkbox']:checked"
      ).length > 0
        ? "1"
        : "0",
    email_to_admin_on_ticket_created_subject: jQuery(
      ".email_to_admin_on_ticket_created_subject input"
    ).val(),
    email_to_admin_on_ticket_created_body: jQuery(
      ".email_to_admin_on_ticket_created_body textarea"
    ).val(),
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(
          ".hive_lite_support_settings_email_notification_body_header button"
        ).text("Saved!");
        setTimeout(function () {
          jQuery(
            ".hive_lite_support_settings_email_notification_body_header button"
          ).text("Save Changes");
        }, 1500);
      }
    },
  });
}
