function hive_lite_support_responses_init() {
  "use strict";

  hive_lite_support_list_responses();
}

function hive_lite_support_response_template_details(view) {
  jQuery(view).toggleClass("open");
  jQuery(view)
    .parent()
    .parent()
    .find(".hive_lite_support_response_single_content")
    .slideToggle("open");
}
function hive_lite_support_response_popup_open(response_id) {
  jQuery(
    ".hive_lite_support_response_popup_edit, .hive_lite_support_response_popup_overlay"
  ).addClass("show");
  jQuery(".hive_lite_support_response_popup_overlay").attr(
    "data-response_id",
    response_id
  );
}
function hive_lite_support_response_popup_close() {
  jQuery(
    ".hive_lite_support_response_popup_edit, .hive_lite_support_response_popup_overlay"
  ).removeClass("show");
  jQuery(".hive_lite_support_response_popup_overlay").attr(
    "data-response_id",
    " "
  );
}

function hive_lite_support_list_responses() {
  "use strict";

  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_response_container").empty();

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
          var responseData =
            '<div class="hive_lite_support_response_item">\n' +
            '            <div class="hive_lite_support_response_single">\n' +
            '                <div class="hive_lite_support_response_single_heading">\n' +
            '                    <div class="hive_lite_support_response_single_heading_flex">\n' +
            '                        <h5 class="title hive_lite_support_response_qsn" onclick="hive_lite_support_response_template_details(this)"> ' +
            responses[i].response_question +
            " </h5>\n" +
            '                        <span class="hive_lite_support_response_single_elipsis_icon" onclick="hive_lite_support_response_popup_open(`' +
            responses[i].response_id +
            '`)"> <img src=""> </span>\n' +
            "                    </div>\n" +
            '                    <div class="hive_lite_support_response_single_content">\n' +
            '                        <p class="hive_lite_support_response_single_content_para hive_lite_support_response_ans"> ' +
            responses[i].response_answer +
            " </p>\n" +
            '                        <span class="hive_lite_support_response_single_content_span"> Thanks, </span>\n' +
            '                        <span class="hive_lite_support_response_single_content_span"> Support Team </span>\n' +
            "                    </div>\n" +
            "                </div>\n" +
            "            </div>\n" +
            "        </div>";

          // var itemHTML = "<div class=\"hive_lite_support_response_item\">\n" +
          //     "            <h5 class=\"hive_lite_support_response_qsn\"> "+responses[i].response_question+" </h5>\n" +
          //     "            <p class=\"hive_lite_support_response_ans\"> "+responses[i].response_answer+" </p>\n" +
          //     "            <button class=\"hive_lite_support_delete_template_btn\" onclick=\"hive_lite_support_responses_delete(`"+responses[i].response_id+"`, `"+responses[i].response_question+"`)\"> Delete</button>\n" +
          //     "        </div>";

          jQuery(".hive_lite_support_response_container").append(responseData);
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_responses").show();
      }
    },
  });
}

function hive_lite_support_response_add_popup_close() {
  "use strict";
  jQuery(".hive_lite_support_response_add_popup").css("display", "none");
}

function hive_lite_support_response_add_popup_open() {
  "use strict";
  jQuery(".hive_lite_support_response_add_popup").css("display", "flex");

  jQuery(".hive_lite_support_response_add button")
    .unbind("click")
    .bind("click", function () {
      jQuery(".hive_lite_support_response_add button")
        .text("Saving...")
        .prop("disabled", true);
      jQuery(".hive_lite_support_response_add input").prop("disabled", true);
      jQuery(".hive_lite_support_response_add textarea").prop("disabled", true);

      var post_data = {
        action: "hive_lite_support_add_response",
        response_question: jQuery(".hive_lite_support_response_add_question")
          .find("input")
          .val(),
        response_answer: jQuery(".hive_lite_support_response_add_answer")
          .find("textarea")
          .val(),
      };
      console.log(post_data);

      jQuery.ajax({
        url: ajaxurl,
        type: "POST",
        data: post_data,
        success: function (data) {
          jQuery(".hive_lite_support_response_add button")
            .text("Add")
            .prop("disabled", false);
          jQuery(".hive_lite_support_response_add input")
            .prop("disabled", false)
            .val("");
          jQuery(".hive_lite_support_response_add textarea")
            .prop("disabled", false)
            .val("");

          hive_lite_support_response_add_popup_close();
          hive_lite_support_list_responses();
        },
      });
    });
}

function hive_lite_support_responses_delete() {
  hive_lite_support_show_body_loader();
  var response_id = jQuery(".hive_lite_support_response_popup_overlay").data(
    "response_id"
  );
  var post_data = {
    action: "hive_lite_support_delete_response",
    response_id: response_id,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      hive_lite_support_list_responses();
      jQuery(
        ".hive_lite_support_response_popup_edit, .hive_lite_support_response_popup_overlay"
      ).removeClass("show");
    },
  });
}

function hive_lite_support_response_search() {
  var search_data = jQuery("#hive-support-search-input-field")
    .val()
    .toLowerCase();

  jQuery(
    ".hive_lite_support_response_container .hive_lite_support_response_item"
  ).each(function () {
    jQuery(this)
      .find(".hive_lite_support_response_qsn")
      .text()
      .toLowerCase()
      .indexOf(search_data) !== -1 ||
    jQuery(this)
      .find(".hive_lite_support_response_ans")
      .text()
      .toLowerCase()
      .indexOf(search_data) !== -1
      ? jQuery(this).show()
      : jQuery(this).hide();
  });
}
