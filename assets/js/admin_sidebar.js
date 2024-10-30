function hive_lite_support_sidebar_menu_click(view, menu_slug) {
  "use strict";
  jQuery(".hive_lite_support_sidebar ul li").removeClass("active");
  jQuery(view).addClass("active");
  hive_lite_support_hide_all();
  switch (menu_slug) {
    case "tickets":
      hive_lite_support_tickets_init();
      break;
    case "responses":
      hive_lite_support_responses_init();
      break;
    case "agents":
      hive_lite_support_agents_init();
      break;
    case "activities":
      hive_lite_support_activities_init();
      break;
    case "reports":
      hive_lite_support_reports_init();
      break;
    case "automation":
      hive_lite_support_automation_init();
      break;
    case "settings":
      hive_lite_support_settings_init();
      break;
  }
}
