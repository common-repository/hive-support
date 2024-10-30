function hive_lite_support_agents_init() {
  "use strict";

  hive_lite_support_agents_list_staffs();
}

function hive_lite_support_agents_list_staffs() {
  "use strict";

  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_agents_container tbody").empty();
  jQuery(".hive_lite_support_agents_add_error").empty().css("display", "none");

  var post_data = {
    action: "hive_lite_support_list_agents",
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var agents = obj.agents;
        for (var i = 0; i < agents.length; i++) {
          var itemHTML =
            "<tr>\n" +
            "               <td>\n" +
            '                   <div class="hive_lite_support_agents_table_user">\n' +
            '                       <img src="' +
            agents[i].profile_img_url +
            '">\n' +
            '                       <div class="hive_lite_support_agents_table_user_info">\n' +
            "                           <h5>" +
            agents[i].display_name +
            "</h5>\n" +
            "                           <p>" +
            agents[i].position +
            "</p>\n" +
            "                       </div>\n" +
            "                   </div>\n" +
            "               </td>\n" +
            "               <td>\n" +
            '                   <div class="hive_lite_support_agents_table_data">\n' +
            "                       <span>Tickets Assigned</span>\n" +
            "                       <h4>" +
            agents[i].total_tickets_assigned +
            "</h4>\n" +
            "                   </div>\n" +
            "               </td>\n" +
            "               <td>\n" +
            '                   <div class="hive_lite_support_agents_table_data">\n' +
            "                       <span>Tickets Solved</span>\n" +
            "                       <h4>" +
            agents[i].total_tickets_closed +
            "</h4>\n" +
            "                   </div>\n" +
            "               </td>\n" +
            "               <td>\n" +
            '                   <div class="hive_lite_support_agents_table_data">\n' +
            "                       <span>Email:</span>\n" +
            "                       <h4>" +
            agents[i].email +
            "</h4>\n" +
            "                   </div>\n" +
            "               </td>\n" +
            "               <td>\n" +
            '                   <div class="hive_lite_support_agents_table_action">\n' +
            '                       <button class="hive_lite_support_edit" onclick="hive_lite_support_agents_update_staff_popup_open(`' +
            agents[i].user_id +
            "`, `" +
            agents[i].position +
            "`, `" +
            agents[i].first_name +
            "`, `" +
            agents[i].last_name +
            "`, `" +
            agents[i].permissions +
            '`)"></button>\n' +
            '                       <button class="hive_lite_support_delete" onclick="hive_lite_support_agents_delete_staff(`' +
            agents[i].user_id +
            "`, `" +
            agents[i].display_name +
            '`)"></button>\n' +
            "                   </div>\n" +
            "               </td>\n" +
            "           </tr>";

          jQuery(".hive_lite_support_agents_container tbody").append(itemHTML);
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_agents").show();
      }
    },
  });
}

function hive_lite_support_agents_add_staff() {
  "use strict";

  if (
    !hive_lite_support_agents_is_email_valid(
      jQuery(".hive_lite_support_agents_add_form_email").find("input").val()
    )
  ) {
    jQuery(".hive_lite_support_agents_add_form_email").addClass(
      "hive_lite_support_field_error"
    );
    return;
  }

  jQuery(".hive_lite_support_agents_add button").text("Adding...");
  jQuery(".hive_lite_support_agents_add input").prop("disabled", true);
  jQuery(".hive_lite_support_agents_add button").prop("disabled", true);

  var updated_permissions_arr = [];
  jQuery(".hive_lite_support_agents_add_form_permissions")
    .find("input[type='checkbox']")
    .each(function () {
      if (jQuery(this).is(":checked")) {
        updated_permissions_arr.push(jQuery(this).val());
      }
    });

  var post_data = {
    action: "hive_lite_support_add_agent",
    title: jQuery(".hive_lite_support_agents_add_form_title")
      .find("input")
      .val(),
    first_name: jQuery(".hive_lite_support_agents_add_form_first_name")
      .find("input")
      .val(),
    last_name: jQuery(".hive_lite_support_agents_add_form_last_name")
      .find("input")
      .val(),
    email: jQuery(".hive_lite_support_agents_add_form_email")
      .find("input")
      .val(),
    password: jQuery(".hive_lite_support_agents_add_form_password")
      .find("input")
      .val(),
    permissions: updated_permissions_arr.join(","),
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_agents_add_form_title")
          .find("input")
          .val("");
        jQuery(".hive_lite_support_agents_add_form_first_name")
          .find("input")
          .val("");
        jQuery(".hive_lite_support_agents_add_form_last_name")
          .find("input")
          .val("");
        jQuery(".hive_lite_support_agents_add_form_email")
          .find("input")
          .val("");
        jQuery(".hive_lite_support_agents_add_form_password")
          .find("input")
          .val("");
        jQuery(".hive_lite_support_agents_add_form_permissions")
          .find("input[type='checkbox']")
          .prop("checked", false);

        hive_lite_support_agents_list_staffs();
      } else {
        jQuery(".hive_lite_support_agents_add_error")
          .text(obj.msg)
          .css("display", "block");
      }
      jQuery(".hive_lite_support_agents_add button").text("Add Staff");
      jQuery(".hive_lite_support_agents_add input").prop("disabled", false);
      jQuery(".hive_lite_support_agents_add button").prop("disabled", false);
    },
  });
}

function hive_lite_support_agents_is_email_valid(email) {
  "use strict";
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function hive_lite_support_agents_update_staff_popup_close() {
  "use strict";
  jQuery(".hive_lite_support_agents_update_popup").css("display", "none");
}

function hive_lite_support_agents_update_staff_popup_open(
  user_id,
  title,
  first_name,
  last_name,
  permissions_str
) {
  "use strict";

  var permissions_arr = permissions_str.split(",");
  jQuery(".hive_lite_support_agents_update_form_permissions")
    .find("input[type='checkbox']")
    .prop("checked", false);
  jQuery.each(permissions_arr, function (key, value) {
    jQuery(
      ".hive_lite_support_agents_update_form_permissions .permission_" + value
    )
      .find("input[type='checkbox']")
      .prop("checked", true);
  });

  jQuery(".hive_lite_support_agents_update_form_title")
    .find("input")
    .val(title);
  jQuery(".hive_lite_support_agents_update_form_first_name")
    .find("input")
    .val(first_name);
  jQuery(".hive_lite_support_agents_update_form_last_name")
    .find("input")
    .val(last_name);
  jQuery(".hive_lite_support_agents_update_popup").css("display", "flex");

  jQuery(".hive_lite_support_agents_update button")
    .unbind("click")
    .bind("click", function () {
      jQuery(".hive_lite_support_agents_update button")
        .text("Updating...")
        .prop("disabled", true);
      jQuery(".hive_lite_support_agents_update input").prop("disabled", true);

      var updated_permissions_arr = [];
      jQuery(".hive_lite_support_agents_update_form_permissions")
        .find("input[type='checkbox']")
        .each(function () {
          if (jQuery(this).is(":checked")) {
            updated_permissions_arr.push(jQuery(this).val());
          }
        });

      var post_data = {
        action: "hive_lite_support_update_agent",
        user_id: user_id,
        title: jQuery(".hive_lite_support_agents_update_form_title")
          .find("input")
          .val(),
        first_name: jQuery(".hive_lite_support_agents_update_form_first_name")
          .find("input")
          .val(),
        last_name: jQuery(".hive_lite_support_agents_update_form_last_name")
          .find("input")
          .val(),
        permissions: updated_permissions_arr.join(","),
      };

      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: post_data,
        success: function (data) {
          jQuery(".hive_lite_support_agents_update button")
            .text("Update Staff")
            .prop("disabled", false);
          jQuery(".hive_lite_support_agents_update input").prop(
            "disabled",
            false
          );
          hive_lite_support_agents_update_staff_popup_close();
          hive_lite_support_agents_list_staffs();
        },
      });
    });
}

function hive_lite_support_agents_delete_staff(user_id, display_name) {
  var comfirm_msg = "Are you sure to delete the staff (" + display_name + ")?";
  if (confirm(comfirm_msg) === true) {
    hive_lite_support_show_body_loader();

    var post_data = {
      action: "hive_lite_support_delete_agent",
      user_id: user_id,
    };

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        hive_lite_support_agents_list_staffs();
      },
    });
  }
}
