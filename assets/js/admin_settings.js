function hive_lite_support_settings_init() {
  "use strict";

  jQuery("#hive_lite_support_settings").show();

  //hive_lite_support_settings_menu_click(jQuery(".hive_lite_support_settings_menu .hive_lite_support_settings_menu_item").eq(0), "business_inbox")
  hive_lite_support_settings_menu_click(
    jQuery(
      ".hive_lite_support_settings_section_left_items .hive_lite_support_settings_section_left_item"
    ).eq(0),
    "business_inbox"
  );
  enable_tinymce(
    "hive_lite_support_settings_new_business_inbox_footer_customization_field"
  );
}

function hive_lite_support_hide_all_settings_menu() {
  "use strict";
  jQuery(".hive_lite_support_settings_business_inbox").hide();
  jQuery(".hive_lite_support_settings_ticket_fields").hide();
  jQuery(".hive_lite_support_settings_email_notification").hide();
}

function hive_lite_support_settings_menu_click(view, menu_slug) {
  "use strict";
  //jQuery(".hive_lite_support_settings_menu .hive_lite_support_settings_menu_item").removeClass("active");
  jQuery(
    ".hive_lite_support_settings_section_left_items .hive_lite_support_settings_section_left_item"
  ).removeClass("active");
  jQuery(view).addClass("active");
  hive_lite_support_hide_all_settings_menu();
  switch (menu_slug) {
    case "ticket_fields":
      jQuery(".hive_lite_support_settings_ticket_fields").show();
      hive_lite_support_settings_ticket_fields_init();
      break;
    case "email_notification":
      jQuery(".hive_lite_support_settings_email_notification").show();
      hive_lite_support_settings_email_notification_init();
      break;
    case "business_inbox":
      jQuery(".hive_lite_support_settings_business_inbox").show();
      hive_lite_support_business_inbox_init();
      break;
  }
}

function hive_lite_support_settings_menu_shortcode_click() {
  var temp_shortcode_elements = document.createElement("input");
  var shortcode = "dfsdfsf";
  temp_shortcode_elements.type = "input";
  temp_shortcode_elements.setAttribute("value", shortcode);
  document.body.appendChild(temp_shortcode_elements);
  temp_shortcode_elements.select();
  document.execCommand("copy");
  temp_shortcode_elements.remove();
}
