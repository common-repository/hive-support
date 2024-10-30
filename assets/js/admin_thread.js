var current_thread_ticket_id = "0";
function hive_lite_support_thread_init(ticket_id) {
  "use strict";
  current_thread_ticket_id = ticket_id;
  hive_lite_support_thread_list_staffs();
  hive_lite_support_thread_right_tab_init();
  jQuery(
    ".hive_lite_support_thread_section_center_thread_form textarea"
  ).empty();
  enable_tinymce("hive_lite_support_thread_ticket_reply_field");
}

function hive_lite_support_thread_center_controll(view) {
  if (jQuery(".lrhactive")[0]) {
    jQuery(view).removeClass("lrhactive");
    jQuery(".leftrighthidden").show(600);
  } else {
    jQuery(view).addClass("lrhactive");
    jQuery(".leftrighthidden").hide(600);
  }
}

function hive_lite_support_thread_reload_page() {
  //wp.media.editor.open();
  hive_lite_support_thread_init(current_thread_ticket_id);
}

function hive_lite_support_thread_list_staffs() {
  "use strict";
  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_thread_agents").empty();
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
        jQuery(".hive_lite_support_thread_agents").append(
          '<option value="0">Not Assigned</option>'
        );

        var agents = obj.agents;
        for (var i = 0; i < agents.length; i++) {
          var itemHTML =
            '<option value="' +
            agents[i].user_id +
            '">' +
            agents[i].display_name +
            "</option>";
          jQuery(".hive_lite_support_thread_agents").append(itemHTML);
        }
      }
      hive_lite_support_list_thread();
    },
  });
}

function hive_lite_support_thread_action_popup_open(response_id) {
  var view = "";
  view = jQuery(".hive_lite_support_thread_action_popup_edit");
  jQuery(
    ".hive_lite_support_thread_action_popup_edit, .hive_lite_support_thread_action_popup_overlay"
  ).addClass("show");
  view.attr("data-id", response_id);
  view.attr("data-type", "response");
}
function hive_lite_support_thread_action_popup_close(view) {
  jQuery(
    ".hive_lite_support_thread_action_popup_edit, .hive_lite_support_thread_action_popup_overlay"
  ).removeClass("show");
  jQuery(view).parent().attr("data-id", " ");
  jQuery(view).parent().attr("data-type", " ");
}
function hive_lite_support_thread_action_delete(view) {
  var type = jQuery(view).parent().parent().attr("data-type");
  if (type === "response") {
    hive_lite_support_show_body_loader();
    var response_id = jQuery(view).parent().parent().attr("data-id");
    var post_data = {
      action: "hive_lite_support_delete_thread_response",
      ticket_id: current_thread_ticket_id,
      response_id: response_id,
    };

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        hive_lite_support_thread_init(current_thread_ticket_id);
      },
    });
  }
  if (type === "todo") {
    var todo_id = jQuery(view).parent().parent().attr("data-id");
    var post_data = {
      action: "hive_lite_support_delete_todo",
      ticket_id: current_thread_ticket_id,
      todo_id: todo_id,
    };

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        hive_lite_support_thread_list_todos();
      },
    });
  }
  jQuery(
    ".hive_lite_support_thread_action_popup_edit, .hive_lite_support_thread_action_popup_overlay"
  ).removeClass("show");
  jQuery(view).parent().parent().attr("data-id", " ");
  jQuery(view).parent().parent().attr("data-type", " ");
}
function convertToPlain(html) {
  // Create a new div element
  var tempDivElement = document.createElement("div");

  // Set the HTML content with the given value
  tempDivElement.innerHTML = html;

  // Retrieve the text property of the element
  return tempDivElement.textContent || tempDivElement.innerText || "";
}
function hive_lite_support_list_thread() {
  "use strict";

  jQuery(".hive_lite_support_thread_section_center_thread_items").empty();
  var post_data = {
    action: "hive_lite_support_list_thread",
    ticket_id: current_thread_ticket_id,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_thread_status").val(obj.ticket_status);
        jQuery(".hive_lite_support_thread_agents").val(obj.agent_user_id);
        jQuery(".hive_lite_support_thread_last_update").val(obj.modified_at);
        jQuery(".hive_lite_support_thread_created_at").val(obj.created_at);
        jQuery(".hive_lite_support_thread_subject").html(
          hive_lite_support_admin_unesc_string(obj.subject) +
            "<span>#" +
            obj.ticket_number +
            "</span>"
        );

        if (obj.ticket_status === "close") {
          jQuery(".hive_lite_support_thread_section_center_thread_form").hide();
        } else {
          jQuery(".hive_lite_support_thread_section_center_thread_form").show();
        }

        // Customer Information
        jQuery(".hive_lite_support_thread_customer_img").attr(
          "src",
          obj.user_img
        );
        jQuery(".hive_lite_support_thread_customer_name").text(obj.user_name);
        jQuery(".hive_lite_support_thread_customer_email").text(obj.user_email);

        var responses = obj.responses;
        for (var i = 0; i < responses.length; i++) {
          var extra_doc = responses[i].extra_doc;
          var extraData = "";
          var innerExtraData = "";
          if (extra_doc.length > 0) {
            for (var x = 0; x < extra_doc.length; x++) {
              extraData += "<img src='" + extra_doc[x] + "' >";
            }
            innerExtraData +=
              "<div class='hive_lite_support_thread_section_center_thread_item_body_extra_document'>" +
              extraData +
              "</div>";
          }

          var itemHTML =
            '<div class="hive_lite_support_thread_section_center_thread_item">\n' +
            '               <div class="hive_lite_support_thread_section_center_thread_item_header">\n' +
            '                   <img src="' +
            responses[i].user_img +
            '">\n' +
            '                   <div class="hive_lite_support_thread_section_center_thread_item_header_details">\n' +
            '                       <a href="#">' +
            responses[i].user_name +
            "</a>\n" +
            '                       <span class="response_by">' +
            responses[i].user_title +
            "</span>\n" +
            '                       <span class="response_at">' +
            responses[i].created_at +
            "</span>\n" +
            "                   </div>\n" +
            '                   <span class="hive_lite_support_thread_elipsis_icon" onclick="hive_lite_support_thread_action_popup_open(`' +
            responses[i].response_id +
            '`)"> <img src=""> </span>\n' +
            "               </div>\n" +
            '               <div class="hive_lite_support_thread_section_center_thread_item_body">\n' +
            //"                   <p>"+hive_lite_support_auto_linkify_string(hive_lite_support_admin_unesc_string(responses[i].msg))+"</p>\n" +
            "                   " +
            convertToPlain(
              hive_lite_support_admin_unesc_string(responses[i].msg).replace(
                /<[^>]+>/g,
                ""
              )
            ) +
            "\n" +
            "                   " +
            innerExtraData +
            "\n" +
            "               </div>\n" +
            "           </div>";

          jQuery(
            ".hive_lite_support_thread_section_center_thread_items"
          ).append(itemHTML);
        }

        var orders = obj.orders;
        jQuery(".hive_lite_support_thread_section_user_profile_order").empty();
        if (orders.length > 0) {
          for (var i = 0; i < orders.length; i++) {
            var itemHTML =
              '<div class="hive_lite_support_thread_section_user_profile_order_item">\n' +
              '               <a target="_blank" href="' +
              orders[i].admin_url +
              '">🛒 ' +
              orders[i].id +
              '</a> on <span class="hive_lite_support_thread_order_date">' +
              orders[i].date +
              "</span>\n" +
              '               <span class="hive_lite_support_thread_order_status">' +
              orders[i].status +
              "</span>\n" +
              "           </div>";

            jQuery(
              ".hive_lite_support_thread_section_user_profile_order"
            ).append(itemHTML);
          }
        } else {
          var itemHTML =
            '<div class="hive_lite_support_thread_section_user_profile_order_item">\n' +
            '               <span class="hive_lite_support_thread_order_date">No order placed</span>\n' +
            "           </div>";

          jQuery(".hive_lite_support_thread_section_user_profile_order").append(
            itemHTML
          );
        }

        var other_tickets = obj.other_tickets;
        jQuery(
          ".hive_lite_support_thread_section_user_previous_ticket"
        ).empty();
        if (other_tickets.length > 0) {
          for (var i = 0; i < other_tickets.length; i++) {
            var itemHTML =
              '<div class="hive_lite_support_thread_section_user_previous_ticket_item">\n' +
              "               <a onclick='hive_lite_support_thread_init(`" +
              other_tickets[i].ticket_id +
              "`)'>✉︎ " +
              other_tickets[i].subject +
              '</a> on <span class="hive_lite_support_thread_order_date">' +
              other_tickets[i].date +
              "</span>\n" +
              "           </div>";

            jQuery(
              ".hive_lite_support_thread_section_user_previous_ticket"
            ).append(itemHTML);
          }
        } else {
          var itemHTML =
            '<div class="hive_lite_support_thread_section_user_previous_ticket_item">\n' +
            '               <span class="hive_lite_support_thread_order_date">No Previous Conversation</span>\n' +
            "           </div>";

          jQuery(
            ".hive_lite_support_thread_section_user_previous_ticket"
          ).append(itemHTML);
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_thread").show();
      }
    },
  });
}

function hive_lite_support_thread_add_wp_media(view) {
  wp.media.editor.send.attachment = function (props, attachment) {
    var img = '<img src="' + attachment.url + '">';
    wp.media.editor.insert(img);
  };
  wp.media.editor.open();
}

function hive_lite_support_thread_doc_upload(view) {
  var mediaUploader;
  if (mediaUploader) {
    mediaUploader.open();
    return;
  }
  var fileUrl = "";
  mediaUploader = wp.media.frames.file_frame = wp.media({
    title: "Select a File",
    button: {
      text: "Select File",
    },
    multiple: true, // Set this to true if you want to allow multiple file selection
    library: {
      type: "application", // Only show files of this type
    },
    // Use the file URL as the source for the media uploader
    file: {
      url: fileUrl,
    },
  });
  mediaUploader.on("select", function () {
    var attachments = mediaUploader
      .state()
      .get("selection")
      .map(function (attachment) {
        return attachment.toJSON();
      });
    attachments.map(function (attachment) {
      var innerHTML =
        "<li>\n" +
        '                            <img src="' +
        attachment.url +
        '" alt="Example Image" class="image">\n' +
        '                            <div class="info">\n' +
        '                                <span class="name">' +
        attachment.url +
        "</span>\n" +
        '                                <span class="size">' +
        attachment.filesizeHumanReadable +
        "</span>\n" +
        "                            </div>\n" +
        "                            <button class=\"close-button\" onclick='remove_added_document(this)'>X</button>\n" +
        "                        </li>";
      jQuery(
        "#hive_lite_support_thread_section_center_thread_form_media_upload_area_container"
      ).append(innerHTML);
    });
  });
  mediaUploader.open();
}
function remove_added_document(view) {
  jQuery(view).parent().remove();
}

function hive_lite_support_thread_reply_submit(view, type) {
  "use strict";
  var submit_btn_text = jQuery(view).text();
  if (type === "close") {
    jQuery(view).empty().append('<div class="submitting_loader"></div>');

    var post_data = {
      action: "hive_lite_support_thread_close",
      ticket_id: current_thread_ticket_id,
      type: type,
    };

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        jQuery(view).empty().text(submit_btn_text);
        hive_lite_support_thread_list_staffs();
      },
    });
  } else {
    if (
      jQuery(
        ".hive_lite_support_thread_section_center_thread_form textarea"
      ).val().length === 0
    ) {
      jQuery(
        ".hive_lite_support_thread_section_center_thread_form textarea"
      ).addClass("hive_lite_support_field_error_occurred");
    } else {
      jQuery(
        ".hive_lite_support_thread_section_center_thread_form textarea"
      ).removeClass("hive_lite_support_field_error_occurred");

      jQuery(view).empty().append('<div class="submitting_loader"></div>');

      var extra_doc = [];
      jQuery(
        "#hive_lite_support_thread_section_center_thread_form_media_upload_area_container li"
      ).each(function () {
        extra_doc.push(jQuery(this).find("img").attr("src"));
      });

      var post_data = {
        action: "hive_lite_support_thread_reply",
        ticket_id: current_thread_ticket_id,
        type: type,
        msg: hive_lite_support_admin_esc_string(
          jQuery(
            ".hive_lite_support_thread_section_center_thread_form textarea"
          ).val()
        ),
        extra_doc: extra_doc,
      };

      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: post_data,
        success: function (data) {
          jQuery(view).empty().text(submit_btn_text);
          hive_lite_support_thread_list_staffs();
          jQuery(
            ".hive_lite_support_thread_section_center_thread_form textarea"
          ).val("");
        },
      });
    }
  }
}

function hive_lite_support_thread_property_update() {
  "use strict";

  hive_lite_support_show_body_loader();

  var post_data = {
    action: "hive_lite_support_thread_property_update",
    ticket_id: current_thread_ticket_id,
    status: jQuery(".hive_lite_support_thread_status").val(),
    agent_id: jQuery(".hive_lite_support_thread_agents").val(),
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      hive_lite_support_thread_list_staffs();
    },
  });
}
function hive_lite_support_thread_reply_remove_errors(view) {
  "use strict";
  jQuery(view).removeClass("hive_lite_support_field_error_occurred");
}

function hive_lite_support_thread_right_tab_init() {
  "use strict";
  jQuery(".hive_lite_support_thread_right_tab_body").hide();
  var active_menu_slug = jQuery(
    ".hive_lite_support_thread_right_tab_menu.active"
  ).attr("data-slug");
  jQuery(
    ".hive_lite_support_thread_right_tab_body[data-slug='" +
      active_menu_slug +
      "']"
  ).show();
  switch (active_menu_slug) {
    case "templates":
      hive_lite_support_thread_list_responses();
      break;
    case "activities":
      hive_lite_support_thread_list_activities();
      break;
    case "todo":
      hive_lite_support_thread_list_todos();
      break;
  }
}
function hive_lite_support_thread_right_tab_menu_click(view) {
  "use strict";
  jQuery(".hive_lite_support_thread_right_tab_menu").removeClass("active");
  jQuery(view).addClass("active");
  hive_lite_support_thread_right_tab_init();
}

function hive_lite_support_thread_list_responses() {
  "use strict";

  jQuery(".hive_lite_support_thread_right_template_contents").empty();

  var post_data = {
    action: "hive_lite_support_list_responses",
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var responses = obj.responses;
        for (var i = 0; i < responses.length; i++) {
          var itemHTML =
            '<div class="hive_lite_support_thread_right_template_item" data-wow-delay=".1s" onclick=\'click_to_add(this)\'>\n' +
            '                                <div class="hive_lite_support_thread_right_template_item_title">' +
            responses[i].response_question +
            "</div>\n" +
            '                                <div class="hive_lite_support_thread_right_template_item_desc_panel">\n' +
            '                                    <p class="hive_lite_support_thread_right_template_item_desc">' +
            responses[i].response_answer +
            "</p>\n" +
            "                                </div>\n" +
            "                            </div>";

          jQuery(".hive_lite_support_thread_right_template_contents").append(
            itemHTML
          );
        }
      }
    },
  });
}

function hive_lite_support_thread_list_activities() {
  "use strict";

  jQuery(".hive_lite_support_thread_right_template_contents")
    .empty()
    .append('<div class="submitting_loader"></div>');

  var post_data = {
    action: "hive_lite_support_list_thread_activities",
    ticket_id: current_thread_ticket_id,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_thread_right_template_contents").empty();
        var activities = obj.activities;
        for (var i = 0; i < activities.length; i++) {
          var itemHTML =
            '<div class="hive_lite_support_thread_item">\n' +
            '            <img src="' +
            activities[i].user_img +
            '">\n' +
            '            <div class="hive_lite_support_thread_item_details">\n' +
            '                <span class="user_name">' +
            activities[i].user_name +
            "</span><br>\n" +
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

          jQuery(".hive_lite_support_thread_right_template_contents").append(
            itemHTML
          );
        }
      }
    },
  });
}

function hive_lite_support_thread_add_todo() {
  var input_data = jQuery("#hive-support-thread-todo-input-field").val();
  if (input_data.length > 0) {
    var post_data = {
      action: "hive_lite_support_add_todo",
      ticket_id: current_thread_ticket_id,
      data: input_data,
    };

    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: post_data,
      success: function (data) {
        var obj = JSON.parse(data);
        if (obj.status === "true") {
          jQuery("#hive-support-thread-todo-input-field").val("");
          hive_lite_support_thread_list_todos();
        }
      },
    });
  }
}

function hive_lite_support_thread_list_todos() {
  "use strict";

  jQuery(".hive_lite_support_thread_right_template_contents")
    .empty()
    .append('<div class="submitting_loader"></div>');

  var post_data = {
    action: "hive_lite_support_list_todos",
    ticket_id: current_thread_ticket_id,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_thread_right_template_contents").empty();
        var todos = obj.todos;
        for (var i = 0; i < todos.length; i++) {
          var itemHTML =
            '<div class="hive_lite_support_thread_todo_item">\n' +
            "                   <p>" +
            hive_lite_support_auto_linkify_string(
              hive_lite_support_admin_unesc_string(todos[i].data)
            ) +
            "</p>\n" +
            '                   <span class="hive_lite_support_thread_elipsis_icon" onclick="hive_lite_support_thread_todo_action_popup_open(`' +
            todos[i].todo_id +
            '`)"> <img src=""> </span>\n' +
            "           </div>";

          jQuery(".hive_lite_support_thread_right_template_contents").append(
            itemHTML
          );
        }
      }
    },
  });
}

function hive_lite_support_thread_todo_action_popup_open(todo_id) {
  jQuery(
    ".hive_lite_support_thread_action_popup_edit, .hive_lite_support_thread_action_popup_overlay"
  ).addClass("show");
  jQuery(".hive_lite_support_thread_action_popup_edit").attr(
    "data-id",
    todo_id
  );
  jQuery(".hive_lite_support_thread_action_popup_edit").attr(
    "data-type",
    "todo"
  );
}

function click_to_add(view) {
  var questions = jQuery(view)
    .find(".hive_lite_support_thread_right_template_item_title")
    .text();
  var answer = jQuery(view)
    .find(".hive_lite_support_thread_right_template_item_desc")
    .text();
  answer += "\n\nThanks,\nSupport Team";
  jQuery(
    ".hive_lite_support_thread_section_center_thread_form textarea"
  ).empty();
  jQuery(".hive_lite_support_thread_section_center_thread_form textarea").val(
    answer
  );
}

function hive_lite_support_thread_response_search() {
  var search_data = jQuery("#hive-support-thread-search-input-field")
    .val()
    .toLowerCase();

  jQuery(
    ".hive_lite_support_thread_right_template_contents .hive_lite_support_thread_right_template_item"
  ).each(function () {
    jQuery(this)
      .find(".hive_lite_support_thread_right_template_item_title")
      .text()
      .toLowerCase()
      .indexOf(search_data) !== -1 ||
    jQuery(this)
      .find(".hive_lite_support_thread_right_template_item_desc")
      .text()
      .toLowerCase()
      .indexOf(search_data) !== -1
      ? jQuery(this).show()
      : jQuery(this).hide();
  });
}

jQuery(document).on(
  "click",
  ".hive_lite_support_thread_right_template_contents .hive_lite_support_thread_right_template_item_title",
  function (e) {
    var hstrt_item = jQuery(this).parent(
      ".hive_lite_support_thread_right_template_item"
    );
    if (hstrt_item.hasClass("open")) {
      hstrt_item.removeClass("open");
      hstrt_item
        .find(".hive_lite_support_thread_right_template_item_desc_panel")
        .removeClass("open");
      hstrt_item
        .find(".hive_lite_support_thread_right_template_item_desc_panel")
        .slideUp(300, "swing");
    } else {
      hstrt_item.addClass("open");
      hstrt_item
        .children(".hive_lite_support_thread_right_template_item_desc_panel")
        .slideDown(300, "swing");
      hstrt_item
        .siblings(".hive_lite_support_thread_right_template_item")
        .children(".hive_lite_support_thread_right_template_item_desc_panel")
        .slideUp(300, "swing");
      hstrt_item
        .siblings(".hive_lite_support_thread_right_template_item")
        .removeClass("open");
      hstrt_item
        .siblings(".hive_lite_support_thread_right_template_item")
        .find(".hive_lite_support_thread_right_template_item_title")
        .removeClass("open");
      hstrt_item
        .siblings(".hive_lite_support_thread_right_template_item")
        .find(".hive_lite_support_thread_right_template_item_desc_panel")
        .slideUp(300, "swing");
    }
  }
);
