function hive_lite_support_tickets_init() {
  "use strict";

  hive_lite_support_tickets_list_staffs();
}

function hive_lite_support_tickets_list_staffs() {
  "use strict";
  hive_lite_support_show_body_loader();
  jQuery("#hive_lite_support_ticket_filter_assign").empty();
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
        jQuery("#hive_lite_support_ticket_filter_assign").append(
          '<option value="">Select Agent</option>'
        );

        var agents = obj.agents;
        for (var i = 0; i < agents.length; i++) {
          var itemHTML =
            '<option value="' +
            agents[i].display_name +
            '">' +
            agents[i].display_name +
            "</option>";
          jQuery("#hive_lite_support_ticket_filter_assign").append(itemHTML);
        }
      }
      hive_lite_support_tickets_list_users();
    },
  });
}

function hive_lite_support_tickets_list_users() {
  "use strict";
  jQuery("#hive_lite_support_ticket_filter_customers").empty();
  var post_data = {
    action: "hive_lite_support_list_users",
  };
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery("#hive_lite_support_ticket_filter_customers").append(
          '<option value="">Select User</option>'
        );

        var users = obj.users;
        for (var i = 0; i < users.length; i++) {
          var itemHTML =
            '<option value="' +
            users[i].display_name +
            '">' +
            users[i].display_name +
            "</option>";
          jQuery("#hive_lite_support_ticket_filter_customers").append(itemHTML);
        }
      }
      hive_lite_support_list_tickets();
    },
  });
}

function hive_lite_support_list_tickets() {
  "use strict";

  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_tickets_container tbody").empty();

  var post_data = {
    action: "hive_lite_support_list_tickets",
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var tickets = obj.tickets;
        for (var i = 0; i < tickets.length; i++) {
          var last_msg = hive_lite_support_admin_unesc_string(
            tickets[i].last_msg
          );
          if (last_msg.length > 100) {
            //last_msg = last_msg.substring(0,100) + "...";
          }
          var itemHTML =
            '<tr onclick="hive_lite_support_thread_init(`' +
            tickets[i].ticket_id +
            '`)">\n' +
            "                <td>\n" +
            '                    <div class="hive_lite_support_tickets_table_user">\n' +
            '                        <img src="' +
            tickets[i].user_img +
            '">\n' +
            "                        <h5><span>" +
            hive_lite_support_admin_unesc_string(tickets[i].subject) +
            "</span><span>#" +
            tickets[i].ticket_number +
            "</span></h5>\n" +
            //"                        <p>"+last_msg+"</p>\n" +
            "                        " +
            convertToPlain(last_msg).replace(/(<([^>]+)>)/gi, "") +
            "\n" +
            '                        <span class="ticket_user">' +
            tickets[i].user_name +
            "</span>\n" +
            "                    </div>\n" +
            "                </td>\n" +
            "                <td>\n" +
            '                    <div class="hive_lite_support_tickets_table_data">\n' +
            '                        <span class="ticket_status">' +
            hive_lite_support_cap_first_letter(tickets[i].status) +
            "</span>\n" +
            "                    </div>\n" +
            "                </td>\n" +
            "                <td>\n" +
            '                    <div class="hive_lite_support_tickets_table_staff">\n' +
            '                        <img src="' +
            tickets[i].agent_user_img +
            '">\n' +
            "                        <h5>" +
            tickets[i].agent_user_name +
            "</h5>\n" +
            "                    </div>\n" +
            "                </td>\n" +
            "                <td>\n" +
            '                    <div class="hive_lite_support_tickets_table_data">\n' +
            '                        <span class="ticket_updated">' +
            tickets[i].modified_at +
            "</span>\n" +
            "                    </div>\n" +
            "                </td>\n" +
            "                <td>\n" +
            '                    <div class="hive_lite_support_tickets_table_data">\n' +
            '                        <span class="ticket_created">' +
            tickets[i].created_at +
            "</span>\n" +
            "                    </div>\n" +
            "                </td>\n" +
            "            </tr>";

          jQuery(".hive_lite_support_tickets_container tbody").append(itemHTML);
        }

        jQuery(".total_tickets_count").text(obj.total_tickets_count);
        jQuery(".waiting_tickets_count").text(obj.waiting_tickets_count);
        jQuery(".open_tickets_count").text(obj.open_tickets_count);
        jQuery(".close_tickets_count").text(obj.close_tickets_count);

        if (tickets.length === 0) {
          jQuery(".hive_lite_support_tickets_empty").css("display", "flex");
        } else {
          jQuery(".hive_lite_support_tickets_empty").css("display", "none");
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_tickets").show();
      }
    },
  });
}

function hive_lite_support_tickets_filter_update() {
  "use strict";

  var HST_filter_status = jQuery(
    "#hive_lite_support_ticket_filter_status"
  ).val();
  var HST_filter_assign = jQuery(
    "#hive_lite_support_ticket_filter_assign"
  ).val();
  var HST_filter_customer = jQuery(
    "#hive_lite_support_ticket_filter_customers"
  ).val();
  if (
    HST_filter_status.length > 0 ||
    HST_filter_assign.length > 0 ||
    HST_filter_customer.length > 0
  ) {
    jQuery(".hive_lite_support_tickets_container tbody tr").hide();
  } else {
    jQuery(".hive_lite_support_tickets_container tbody tr").show();
  }

  jQuery(".hive_lite_support_tickets_container tbody tr").each(function () {
    if (
      jQuery(this)
        .find(".hive_lite_support_tickets_table_data .ticket_status")
        .text()
        .includes(HST_filter_status) &&
      jQuery(this)
        .find(".hive_lite_support_tickets_table_staff h5")
        .text()
        .includes(HST_filter_assign) &&
      jQuery(this)
        .find(".hive_lite_support_tickets_table_user .ticket_user")
        .text()
        .includes(HST_filter_customer)
    ) {
      jQuery(this).show();
    }
  });
}

function hive_lite_support_tickets_filter_reset() {
  "use strict";

  jQuery("#hive_lite_support_ticket_filter_status").val("");
  jQuery("#hive_lite_support_ticket_filter_priority").val("");
  jQuery("#hive_lite_support_ticket_filter_assign").val("");
  jQuery("#hive_lite_support_ticket_filter_customers").val("");

  jQuery(".hive_lite_support_tickets_container tbody tr").each(function () {
    jQuery(this).show();
  });
}
