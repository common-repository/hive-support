function hive_lite_support_activities_init() {
  "use strict";

  hive_lite_support_list_activities();
}

function hive_lite_support_list_activities() {
  "use strict";

  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_activities_container").empty();

  var post_data = {
    action: "hive_lite_support_list_activities",
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var activities = obj.activities;
        for (var i = 0; i < activities.length; i++) {
          var itemHTML =
            '<div class="hive_lite_support_activities_item">\n' +
            '            <img src="' +
            activities[i].user_img +
            '">\n' +
            '            <div class="hive_lite_support_activities_item_details">\n' +
            '                <span class="user_name">' +
            activities[i].user_name +
            "</span>\n" +
            '                <span class="user_action">' +
            activities[i].action_msg +
            "</span>\n" +
            '                <span class="break"></span>\n' +
            '                <span class="user_title">' +
            activities[i].user_title +
            "</span>\n" +
            '                <span class="time">' +
            activities[i].updated_at +
            "</span>\n" +
            "            </div>\n" +
            "        </div>";

          jQuery(".hive_lite_support_activities_container").append(itemHTML);
        }

        if (activities.length === 0) {
          jQuery(".hive_lite_support_activities_empty").css("display", "flex");
          jQuery(".hive_lite_support_activities_container").css(
            "display",
            "none"
          );
        } else {
          jQuery(".hive_lite_support_activities_empty").css("display", "none");
          jQuery(".hive_lite_support_activities_container").css(
            "display",
            "block"
          );
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_activities").show();
      }
    },
  });
}
