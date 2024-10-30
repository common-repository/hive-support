var current_thread_ticket_id = "0";

function hive_lite_support_client_esc_string(str) {
  "use strict";
  return str
    .replaceAll("&", "::hive_lite_support_amp::")
    .replaceAll("<", "::hive_lite_support_left_arrow::")
    .replaceAll(">", "::hive_lite_support_right_arrow::")
    .replaceAll('"', "::hive_lite_support_dbl_quote::")
    .replaceAll("'", "::hive_lite_support_sin_quote::")
    .replaceAll("`", "::hive_lite_support_grave::")
    .replaceAll("\\", "::hive_lite_support_backslash::");
  return str;
}
function hive_lite_support_client_unesc_string(str) {
  "use strict";
  return str
    .replaceAll("::hive_lite_support_amp::", "&amp;")
    .replaceAll("::hive_lite_support_left_arrow::", "&lt;")
    .replaceAll("::hive_lite_support_right_arrow::", "&gt;")
    .replaceAll("::hive_lite_support_dbl_quote::", "&quot;")
    .replaceAll("::hive_lite_support_sin_quote::", "&#039;")
    .replaceAll("::hive_lite_support_grave::", "&#96;")
    .replaceAll("::hive_lite_support_backslash::", "&#92;");
}

function hive_lite_support_client_hide_all() {
  "use strict";
  jQuery("#hive_lite_support_client_form").hide();
  jQuery("#hive_lite_support_client_thread").hide();
  jQuery("#hive_lite_support_client_tickets").hide();
}

function hive_lite_support_client_show_tickets() {
  "use strict";
  hive_lite_support_client_hide_all();
  hive_lite_support_client_list_tickets();
}

function hive_lite_support_client_show_form() {
  "use strict";
  hive_lite_support_client_hide_all();
  jQuery("#hive_lite_support_client_form").show();
}

function hive_lite_support_client_show_thread(ticket_id) {
  "use strict";
  current_thread_ticket_id = ticket_id;
  hive_lite_support_client_hide_all();
  hive_lite_support_client_list_thread();
}

function hive_lite_support_show_client_loader() {
  "use strict";
  hive_lite_support_client_hide_all();
  jQuery(".hive_lite_support_client_loader").css("display", "flex");
}
function hive_lite_support_hide_client_loader() {
  "use strict";
  jQuery(".hive_lite_support_client_loader").css("display", "none");
}

function hive_lite_support_client_thread_reply_submit(view, type) {
  "use strict";

  if (
    jQuery(".hive_lite_support_thread_reply_form textarea").val().length === 0
  ) {
    jQuery(".hive_lite_support_thread_reply_msg_field").addClass(
      "hive_lite_support_field_error_occurred"
    );
  } else {
    jQuery(".hive_lite_support_thread_reply_msg_field").removeClass(
      "hive_lite_support_field_error_occurred"
    );

    var submit_btn_text = jQuery(view).text();
    jQuery(view).empty().append('<div class="submitting_loader"></div>');

    var post_data = {
      action: "hive_lite_support_client_reply_thread",
      ticket_id: current_thread_ticket_id,
      type: type,
      msg: hive_lite_support_client_esc_string(
        jQuery(".hive_lite_support_thread_reply_form textarea").val()
      ),
      security: hive_lite_support_client_script_object.security,
    };

    jQuery.ajax({
      url: hive_lite_support_client_script_object.ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        jQuery(view).empty().text(submit_btn_text);
        jQuery(".hive_lite_support_thread_reply_form textarea").val("");
        hive_lite_support_client_list_thread();
      },
    });
  }
}

function hive_lite_support_client_list_thread() {
  "use strict";
  hive_lite_support_show_client_loader();
  jQuery(".hive_lite_support_client_thread_items").empty();

  var post_data = {
    action: "hive_lite_support_client_list_thread",
    ticket_id: current_thread_ticket_id,
    security: hive_lite_support_client_script_object.security,
  };

  jQuery.ajax({
    url: hive_lite_support_client_script_object.ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var responses = obj.responses;
        for (var i = 0; i < responses.length; i++) {
          var itemHTML =
            '<div class="hive_lite_support_client_thread_item">\n' +
            '            <div class="hive_lite_support_client_thread_item_header">\n' +
            '                <img src="' +
            responses[i].user_img +
            '">\n' +
            '                <div class="hive_lite_support_client_thread_item_header_details">\n' +
            '                    <a href="#">' +
            responses[i].user_name +
            "</a>\n" +
            '                    <span class="response_by">' +
            responses[i].user_title +
            "</span>\n" +
            '                    <span class="response_at">' +
            responses[i].created_at +
            "</span>\n" +
            "                </div>\n" +
            "            </div>\n" +
            '            <div class="hive_lite_support_client_thread_item_body">\n' +
            "                <p>" +
            hive_lite_support_client_auto_linkify_string(
              hive_lite_support_client_unesc_string(responses[i].msg)
            ) +
            "</p>\n" +
            "            </div>\n" +
            "        </div>";

          jQuery(".hive_lite_support_client_thread_items").append(itemHTML);
        }

        if (obj.ticket_status === "close") {
          jQuery(".hive_lite_support_thread_reply_form").hide();
        } else {
          jQuery(".hive_lite_support_thread_reply_form").show();
        }
      }
      hive_lite_support_hide_client_loader();
      jQuery("#hive_lite_support_client_thread").show();
    },
  });
}

function hive_lite_support_client_list_tickets() {
  "use strict";
  hive_lite_support_show_client_loader();
  jQuery(".hive_lite_support_client_tickets_items").empty();

  var post_data = {
    action: "hive_lite_support_client_list_tickets",
    security: hive_lite_support_client_script_object.security,
  };

  jQuery.ajax({
    url: hive_lite_support_client_script_object.ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var tickets = obj.tickets;
        for (var i = 0; i < tickets.length; i++) {
          var last_msg = hive_lite_support_client_unesc_string(
            tickets[i].last_msg
          );
          if (last_msg.length > 200) {
            last_msg = last_msg.substring(0, 200) + "...";
          }
          var itemHTML =
            '<div class="hive_lite_support_client_tickets_item">\n' +
            '            <div class="hive_lite_support_client_tickets_item_body">\n' +
            '                <img src="' +
            tickets[i].last_msg_user_img +
            '">\n' +
            '                <div class="hive_lite_support_client_tickets_item_body_details">\n' +
            '                    <a href="#" onclick="hive_lite_support_client_show_thread(`' +
            tickets[i].ticket_id +
            '`)">' +
            hive_lite_support_client_unesc_string(tickets[i].subject) +
            "<span>#" +
            tickets[i].ticket_number +
            "</span></a>\n" +
            "                    <p>" +
            last_msg +
            "</p>\n" +
            "                </div>\n" +
            "            </div>\n" +
            '            <div class="hive_lite_support_client_tickets_item_footer">\n' +
            "                <ul>\n" +
            "                    <li> <span> Last Update: </span> <strong> " +
            tickets[i].modified_at +
            " </strong> </li>\n" +
            "                    <li> <span> Status: </span> <strong> " +
            hive_lite_support_client_cap_first_letter(tickets[i].status) +
            " </strong> </li>\n" +
            "                    <li> <span> Ticket Created: </span> <strong> " +
            tickets[i].created_at +
            " </strong> </li>\n" +
            "                </ul>\n" +
            "            </div>\n" +
            "        </div>";

          jQuery(".hive_lite_support_client_tickets_items").append(itemHTML);
        }

        if (tickets.length === 0) {
          jQuery(".hive_lite_support_client_tickets_empty").css(
            "display",
            "flex"
          );
        } else {
          jQuery(".hive_lite_support_client_tickets_empty").css(
            "display",
            "none"
          );
        }
      }
      hive_lite_support_hide_client_loader();
      jQuery("#hive_lite_support_client_tickets").show();
    },
  });
}

function hive_lite_support_client_form_submit() {
  "use strict";

  hive_lite_support_form_remove_all_errors();
  var is_error_occurred = false;
  var form_data = new FormData();
  jQuery(".hive_lite_support_single_form_element[data-field_id]").each(
    function (i, object) {
      var field_id = jQuery(object).attr("data-field_id");
      var field_slug = jQuery(object).attr("data-field_slug");
      var field_value = "";

      if (field_slug === "dropdown") {
        field_value = jQuery(object).find("select").val();
      } else if (field_slug === "radio") {
        field_value = jQuery(object).find("input[type ='radio']:checked").val();
      } else if (field_slug === "checkbox") {
        field_value = jQuery(object)
          .find("input[type ='checkbox']:checked")
          .map(function () {
            return this.value;
          })
          .get()
          .join("::hive_lite_support_separator::");
      } else if (field_slug === "text_area") {
        field_value = jQuery(object).find("textarea").val();
      } else if (field_slug === "message") {
        field_value = jQuery(object).find("textarea").val();
      } else {
        field_value = jQuery(object).find("input").val();
      }

      field_value = field_value === undefined ? "" : field_value;

      /* Check Validation */
      if (field_slug === "dropdown") {
        /* Required Field Check */
        if (
          jQuery(object).find("select").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      } else if (field_slug === "radio") {
        /* Required Field Check */
        if (
          jQuery(object).find("input[type ='radio']").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      } else if (field_slug === "checkbox") {
        /* Required Field Check */
        if (
          jQuery(object).find("input[type ='checkbox']").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      } else if (field_slug === "text_area") {
        /* Required Field Check */
        if (
          jQuery(object).find("textarea").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      } else if (field_slug === "message") {
        /* Required Field Check */
        if (
          jQuery(object).find("textarea").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      } else {
        /* Required Field Check */
        if (
          jQuery(object).find("input").prop("required") &&
          field_value.length === 0
        ) {
          is_error_occurred = true;
          jQuery(object).addClass("hive_lite_support_field_error_occurred");
        }
      }

      if (typeof field_value == "string") {
        field_value = hive_lite_support_client_esc_string(field_value);
      }

      form_data.append("field_id_" + field_id, field_value);
    }
  );

  if (!is_error_occurred) {
    var submit_btn = jQuery(
      "#hive_lite_support_client_form .hive_lite_support_single_form_element[data-field_slug='submit_btn'] button"
    );
    var submit_btn_text = jQuery(submit_btn).text();
    jQuery(submit_btn).empty().append('<div class="submitting_loader"></div>');

    form_data.append("action", "hive_lite_support_client_submit_form");
    form_data.append(
      "security",
      hive_lite_support_client_script_object.security
    );

    jQuery.ajax({
      url: hive_lite_support_client_script_object.ajaxurl,
      type: "POST",
      data: form_data,
      enctype: "multipart/form-data",
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        console.log(data);
        jQuery(submit_btn).empty().text(submit_btn_text);
        var obj = JSON.parse(data);
        if (obj.status === "true") {
          hive_lite_support_client_show_tickets();
        }
      },
    });
  } else {
    jQuery([document.documentElement, document.body]).animate(
      {
        scrollTop:
          jQuery(".hive_lite_support_field_error_occurred").offset().top - 50,
      },
      500
    );
  }
}

function hive_lite_support_form_remove_all_errors(unique_id) {
  "use strict";
  jQuery(".hive_lite_support_single_form_element[data-field_id]").each(
    function (i, object) {
      jQuery(object).removeClass("hive_lite_support_field_error_occurred");
    }
  );
}

function hive_lite_support_field_remove_errors(view) {
  "use strict";
  jQuery(view)
    .closest(".hive_lite_support_single_form_element")
    .removeClass("hive_lite_support_field_error_occurred");
}

function hive_lite_support_client_cap_first_letter(string) {
  "use strict";
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function hive_lite_support_client_auto_linkify_string(inputText) {
  "use strict";
  var replacedText, replacePattern1, replacePattern2, replacePattern3;

  //URLs starting with http://, https://, or ftp://
  replacePattern1 =
    /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
  replacedText = inputText.replace(
    replacePattern1,
    '<a href="$1" target="_blank">$1</a>'
  );

  //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
  replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
  replacedText = replacedText.replace(
    replacePattern2,
    '$1<a href="http://$2" target="_blank">$2</a>'
  );

  //Change email addresses to mailto:: links.
  replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
  replacedText = replacedText.replace(
    replacePattern3,
    '<a href="mailto:$1">$1</a>'
  );

  return replacedText;
}
