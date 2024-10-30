function hive_lite_support_reports_init() {
  "use strict";

  jQuery(document).on("focusout", function () {
    jQuery(".hive_lite_support_report_status_dropdown_menu").slideUp(
      "show-dropdown"
    );
  });

  jQuery(document).on(
    "click",
    ".hive_lite_support_report_status_dropdown_toggler",
    function (e) {
      e.preventDefault();
      jQuery(".hive_lite_support_report_status_dropdown_menu").removeClass(
        "show-dropdown"
      );
      jQuery(this)
        .parent()
        .find(".hive_lite_support_report_status_dropdown_menu")
        .slideToggle("show-dropdown");
      // return false;
    }
  );

  jQuery(document).on(
    "click",
    ".hive_lite_support_report_status_dropdown_menu_list",
    function (e) {
      e.preventDefault();
      jQuery(this)
        .parent(".hive_lite_support_report_status_dropdown_menu")
        .slideUp("show-dropdown");
      jQuery(this).siblings().children().removeClass("selected");
      jQuery(this).children().addClass("selected");

      let hsrspel = jQuery(this);
      let value = hsrspel.data("value");
      let data = hsrspel.data("data");
      let parentWrap = hsrspel.parent().parent();
      parentWrap
        .find(".hive_lite_support_report_add_dropdown_text")
        .text(value);
      parentWrap
        .find(".hive_lite_support_report_add_dropdown_text")
        .attr("value", data);
      hive_lite_support_list_reports();
      return false;
    }
  );

  hive_lite_support_list_reports();
}

function hive_lite_support_list_reports() {
  "use strict";

  hive_lite_support_show_body_loader();
  jQuery(".hive_lite_support_report_bar_chart_wrapper").empty();
  jQuery(".hive_lite_support_report_bar_chart_wrapper").append(
    '<canvas id="hive_lite_support_report_bar_chart"></canvas>'
  );
  var search_length = jQuery(
    ".hive_lite_support_report_status_dropdown_toggler"
  ).attr("value");
  var post_data = {
    action: "hive_lite_support_list_ticket_states",
    search_length: search_length,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var date = obj.date;
        var ticket_created = obj.ticket_created;
        var ticket_closed = obj.ticket_closed;

        if (date.length === 0) {
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
          loadChart(date, ticket_created, ticket_closed);
          hive_lite_support_list_agent_performance();
        }

        hive_lite_support_hide_body_loader();
        jQuery("#hive_lite_support_reports").show();
      }
    },
  });
}

function hive_lite_support_list_agent_performance() {
  "use strict";

  jQuery(".hive_lite_support_reports_row_item").empty();
  var search_length = jQuery(
    ".hive_lite_support_report_status_dropdown_toggler"
  ).attr("value");
  var post_data = {
    action: "hive_lite_support_list_agent_performances",
    search_length: search_length,
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        var datas = obj.datas;
        for (var i = 0; i < datas.length; i++) {
          var view_data =
            '<div class="hive_lite_support_reports_col_4 hive_lite_support_reports_col_6">\n' +
            '                        <div class="hive_lite_support_reports_single_circleProgress">\n' +
            '                            <div class="hive_lite_support_reports_single_circleProgress_box">\n' +
            '                                <div class="hive_lite_support_reports_single_circleProgress_box_contents">\n' +
            '                                    <div class="hive_lite_support_reports_single_circleProgress_box_thumb">\n' +
            '                                        <img src="' +
            datas[i].user_img +
            '">\n' +
            '                                        <h6 class="hive_lite_support_reports_single_circleProgress_box_thumb_name"> ' +
            datas[i].user_name +
            " </h6>\n" +
            "                                    </div>\n" +
            '                                    <span class="hive_lite_support_reports_single_circleProgress_box_para">Total Replied</span>\n' +
            '                                    <h3 class="hive_lite_support_reports_circleProgress" data-percent="' +
            datas[i].replied_perc +
            '" data-scale-color="#000">' +
            datas[i].replied_no +
            "</h3>\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                        </div>\n" +
            "                    </div>";

          jQuery(".hive_lite_support_reports_row_item").append(view_data);
        }

        jQuery(".hive_lite_support_reports_circleProgress").easyPieChart({
          size: 200,
          barColor: "#685BE7",
          scaleLength: 0,
          lineWidth: 8,
          trackColor: "#f3f3f3",
          lineCap: "circle",
          animate: 2500,
        });
      }
    },
  });
}

function loadChart(date, ticket_created, ticket_closed) {
  new Chart(document.getElementById("hive_lite_support_report_bar_chart"), {
    type: "bar",
    data: {
      labels: date,
      datasets: [
        {
          label: "Received",
          backgroundColor: "#685BE7",
          data: ticket_created,
          barThickness: 10,
          hoverBackgroundColor: "#fff",
          hoverBorderColor: "#685BE7",
          borderColor: "#fff",
          borderWidth: 2,
        },
        {
          label: "Closed",
          backgroundColor: "#50DE88",
          data: ticket_closed,
          barThickness: 10,
          hoverBackgroundColor: "#fff",
          hoverBorderColor: "#50DE88",
          borderColor: "#fff",
          borderWidth: 2,
        },
      ],
    },
  });
}
