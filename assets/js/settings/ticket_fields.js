function hive_lite_support_settings_ticket_fields_init() {
  hive_lite_support_settings_ticket_field_fetch_form();
  hive_lite_support_settings_ticket_fields_drag_and_drop_init();
}

function hive_lite_support_settings_ticket_fields_item_delete(view) {
  "use strict";
  jQuery(view).parent().parent().remove();
}

function hive_lite_support_settings_ticket_fields_item_open_customizer(view) {
  "use strict";
  jQuery(".hive_lite_support_settings_ticket_fields_dropped_form_items")
    .find(".hive_lite_support_settings_ticket_fields_dropped_form_item")
    .removeClass("item_open");
  jQuery(view).parent().parent().addClass("item_open");
}

const hive_lite_support_settings_ticket_fields_sortable_options_1 = {
  revert: false,
  receive: function (event, ui) {
    jQuery(this)
      .find(".hive_lite_support_settings_ticket_fields_droppable_form_item")
      .replaceWith(
        hive_lite_support_settings_ticket_fields_generate_field_html(
          ui.item,
          "0"
        )
      );

    /* Reload All Sortable */
    hive_lite_support_settings_ticket_fields_reload_all_sortable();
  },
  appendTo: "body",
  helper: "clone",
};

function hive_lite_support_settings_ticket_fields_reload_all_sortable() {
  "use strict";
  if (
    jQuery(
      ".hive_lite_support_settings_ticket_fields_dropped_form_items"
    ).hasClass("ui-sortable")
  ) {
    jQuery(
      ".hive_lite_support_settings_ticket_fields_dropped_form_items"
    ).sortable("destroy");
  }
  jQuery(
    ".hive_lite_support_settings_ticket_fields_dropped_form_items"
  ).sortable(hive_lite_support_settings_ticket_fields_sortable_options_1);
}

function hive_lite_support_settings_ticket_fields_drag_and_drop_init() {
  "use strict";

  hive_lite_support_settings_ticket_fields_reload_all_sortable();

  jQuery(
    ".hive_lite_support_settings_ticket_fields_dropped_form_items_empty"
  ).droppable({
    accept: ".hive_lite_support_settings_ticket_fields_droppable_form_item",
    hoverClass: "drop_hover",
    drop: function (event, ui) {
      jQuery(
        ".hive_lite_support_settings_ticket_fields_dropped_form_items"
      ).append(
        hive_lite_support_settings_ticket_fields_generate_field_html(
          ui.draggable,
          "0"
        )
      );

      /* Reload All Sortable */
      hive_lite_support_settings_ticket_fields_reload_all_sortable();
    },
  });

  jQuery(
    ".hive_lite_support_settings_ticket_fields_droppable_form_item"
  ).draggable({
    connectToSortable:
      ".hive_lite_support_settings_ticket_fields_dropped_form_items",
    start: function (event, ui) {
      jQuery(ui.helper).addClass("drag_started");
    },
    appendTo: "body",
    containment: ".hive_lite_support_settings_ticket_fields_body",
    helper: "clone",
  });
}

function hive_lite_support_settings_ticket_fields_generate_unique_field_id() {
  "use strict";
  var hive_lite_support_unique_field_id = [];
  jQuery(".hive_lite_support_settings_ticket_fields_dropped_form_item").each(
    function (i, object) {
      hive_lite_support_unique_field_id.push(
        jQuery(object).attr("data-field_id")
      );
    }
  );

  var id = Math.random().toString(36).substr(2, 9);
  while (jQuery.inArray(id, hive_lite_support_unique_field_id) !== -1) {
    id = Math.random().toString(36).substr(2, 9);
  }
  return id;
}

function hive_lite_support_settings_ticket_fields_list_all_attr(view) {
  "use strict";
  var array = {};
  view.each(function () {
    jQuery.each(this.attributes, function (i, a) {
      if (!(a.value instanceof Object)) {
        if (a.name.indexOf("data-") >= 0) {
          array[a.name.replace("data-", "")] = a.value;
        }
      }
    });
  });
  return array;
}

function hive_lite_support_settings_ticket_fields_generate_field_html(
  view,
  field_id
) {
  "use strict";

  var auto_open_customizer_class = "";

  if (field_id === "0") {
    auto_open_customizer_class = "item_open";
    field_id =
      hive_lite_support_settings_ticket_fields_generate_unique_field_id();
  }
  var data_html = 'data-field_id="' + field_id + '" ';

  jQuery.each(
    hive_lite_support_settings_ticket_fields_list_all_attr(jQuery(view)),
    function (key, value) {
      data_html += "data-" + key + '="' + value + '" ';
    }
  );

  var html = "";
  jQuery.each(jQuery(view).data(), function (key, value) {
    if (typeof value == "string") {
      jQuery(view).data(key, hive_lite_support_admin_unesc_string(value));
    }
  });
  switch (jQuery(view).data("slug")) {
    case "subject":
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Subject</h4>\n" +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "message":
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Message</h4>\n" +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "simple_text":
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Text Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "text_area":
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Textarea Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "number":
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Number Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "dropdown":
      var options = jQuery(view)
        .data("options")
        .replaceAll("::hive_lite_support_separator::", "\n");
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Dropdown Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Dropdown Options</label>\n" +
        '               <textarea rows="4" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `options`)">' +
        options +
        "</textarea>\n" +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "radio":
      var options = jQuery(view)
        .data("options")
        .replaceAll("::hive_lite_support_separator::", "\n");
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Radio Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Radio Options</label>\n" +
        '               <textarea rows="4" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `options`)">' +
        options +
        "</textarea>\n" +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;
    case "checkbox":
      var options = jQuery(view)
        .data("options")
        .replaceAll("::hive_lite_support_separator::", "\n");
      html =
        '<div class="hive_lite_support_settings_ticket_fields_dropped_form_item ' +
        auto_open_customizer_class +
        '" ' +
        data_html +
        ">\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">\n' +
        "           <h4>Checkbox Field</h4>\n" +
        '           <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>\n' +
        '           <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>\n' +
        "       </div>\n" +
        '       <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">\n' +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Field Label</label>\n" +
        '               <input type="text" value="' +
        jQuery(view).data("label") +
        '" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">\n' +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Checkbox Options</label>\n" +
        '               <textarea rows="4" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `options`)">' +
        options +
        "</textarea>\n" +
        "           </div>\n" +
        '           <div class="hive_lite_support_settings_ticket_field_customizer">\n' +
        "               <label>Is Required?</label>\n" +
        '               <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">\n' +
        '                   <option value="0" ' +
        (jQuery(view).data("required") == "0" ? "selected" : "") +
        ">No</option>\n" +
        '                   <option value="1" ' +
        (jQuery(view).data("required") == "1" ? "selected" : "") +
        ">Yes</option>\n" +
        "               </select>\n" +
        "           </div>\n" +
        "       </div>\n" +
        "   </div>";
      break;

    default:
      break;
  }
  return html;
}

function hive_lite_support_settings_ticket_field_customizer_update_data(
  view,
  type
) {
  "use strict";
  switch (type) {
    case "label":
      jQuery(view)
        .parent()
        .parent()
        .parent()
        .attr(
          "data-label",
          hive_lite_support_admin_esc_string(jQuery(view).val())
        );
      break;
    case "required":
      jQuery(view)
        .parent()
        .parent()
        .parent()
        .attr(
          "data-required",
          hive_lite_support_admin_esc_string(jQuery(view).val())
        );
      break;
    case "options":
      var options_arr = jQuery(view).val().split("\n");
      var options_arr_formatted = [];
      jQuery.each(options_arr, function (key, value) {
        if (value.toString().trim().length > 0) {
          options_arr_formatted.push(value.toString().trim());
        }
      });
      jQuery(view)
        .parent()
        .parent()
        .parent()
        .attr(
          "data-options",
          options_arr_formatted.join("::hive_lite_support_separator::")
        );
      break;
    default:
      break;
  }
}

function hive_lite_support_settings_ticket_field_generate_json_to_html(
  jsonObject
) {
  "use strict";
  var html = "";
  jQuery.each(jsonObject, function (i, item) {
    var field_id = "";
    var field_data = "";
    jQuery.each(item, function (key, value) {
      if (key === "field_id") {
        field_id = value;
      } else {
        field_data += "data-" + key + '="' + value + '" ';
      }
    });
    html += hive_lite_support_settings_ticket_fields_generate_field_html(
      jQuery("<div " + field_data + "></div>"),
      field_id
    );
  });
  return html;
}

function hive_lite_support_settings_ticket_fields_generate_html_to_json() {
  "use strict";
  var hive_lite_support_settings_ticket_fields_data = [];

  jQuery(
    ".hive_lite_support_settings_ticket_fields_dropped_form_items .hive_lite_support_settings_ticket_fields_dropped_form_item"
  ).each(function (i, object) {
    var single_field_data = {};
    jQuery.each(
      hive_lite_support_settings_ticket_fields_list_all_attr(jQuery(object)),
      function (key, value) {
        single_field_data[key] = value;
      }
    );
    hive_lite_support_settings_ticket_fields_data.push(single_field_data);
  });

  return hive_lite_support_settings_ticket_fields_data;
}

function hive_lite_support_settings_ticket_field_save() {
  "use strict";

  jQuery(".hive_lite_support_ticket_fields_section_header button").text(
    "Saving..."
  );

  var post_data = {
    action: "hive_lite_support_ticket_fields_save",
    ticket_fields: JSON.stringify(
      hive_lite_support_settings_ticket_fields_generate_html_to_json()
    ),
  };

  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_ticket_fields_section_header button").text(
          "Saved!"
        );
        setTimeout(function () {
          jQuery(".hive_lite_support_ticket_fields_section_header button").text(
            "Save Changes"
          );
        }, 1500);
      }
    },
  });
}

function hive_lite_support_settings_ticket_field_fetch_form() {
  "use strict";

  var hive_lite_support_ticket_fields_fresh =
    hive_lite_support_admin_codify_string(hive_lite_support_ticket_fields);
  var html = hive_lite_support_settings_ticket_field_generate_json_to_html(
    JSON.parse(hive_lite_support_ticket_fields_fresh)
  );
  jQuery(".hive_lite_support_settings_ticket_fields_dropped_form_items")
    .empty()
    .append(html);
  jQuery(
    ".hive_lite_support_settings_ticket_fields_dropped_form_items"
  ).sortable(hive_lite_support_settings_ticket_fields_sortable_options_1);
}
