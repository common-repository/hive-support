function hive_lite_support_automation_init() {
  "use strict";
  jQuery("#hive_lite_support_automation").show();

  hive_lite_support_automationrules_container_checker();
}

function delete_trigger(view, id) {
  var post_data = {
    action: "hive_lite_support_automation_trigger_delete",
    id: id,
  };
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(view).parent().parent().parent().parent().remove();
        hive_lite_support_automationrules_container_checker();
      }
    },
  });
}

function hive_lite_support_automationrules_container_checker() {
  if (
    jQuery("#hive_lite_support_automation_rules_container").children().length ==
    0
  ) {
    var innerHTML = jQuery("#hive_lite_support_trigger_template")
      .children()
      .clone();
    jQuery(innerHTML)
      .find(".hive_lite_support_automation_trigger__header_delete")
      .removeAttr("onclick");
    jQuery("#hive_lite_support_automation_rules_container").append(innerHTML);
  }
}

function hive_lite_support_automation_radio(view) {
  jQuery(view)
    .parent()
    .find(".hive_lite_support_automation_custom_radio_single")
    .removeClass("active");
  jQuery(view).parent().find("input").prop("checked", false);
  jQuery(view).addClass("active");
  jQuery(view).find("input").prop("checked", true);
}

function add_new_condition() {
  var contentData = jQuery(".hive_lite_support_automation_condition_items")
    .find(".hive_lite_support_automation_condition_item")
    .eq(0)
    .clone();
  //jQuery(contentData).find('select').val("")
  //jQuery(contentData).find('select option:first').prop('selected', true);
  jQuery(contentData).find("input[type=text]").val("");
  jQuery(contentData).append(
    '<button class="hive_lite_support_automation_trigger_delete" onclick="delete_condition(this)"></button>'
  );
  jQuery(".hive_lite_support_automation_condition_items").append(contentData);
}
function delete_condition(view) {
  jQuery(view).parent().remove();
}

function hive_lite_support_automation_add_new_rules() {
  var innerHTML = jQuery("#hive_lite_support_trigger_template")
    .children()
    .clone();
  jQuery("#hive_lite_support_automation_rules_container").append(innerHTML);

  /*var contentData = jQuery(".rules_container").find(".rules_content").eq(0).clone();
    jQuery(contentData).find('select').val("");
    jQuery(contentData).find('select option:first').prop('selected', true);
    jQuery(contentData).find('input').val("");
    jQuery(".rules_container").append(contentData);*/
}

function hive_lite_support_automation_field_save() {
  "use strict";
  jQuery(".hive_lite_support_automation_save_btn")
    .text("Saving...")
    .prop("disabled", true);

  var hive_lite_support_automation_data = [];

  jQuery(".rules_container")
    .find(".rules_content")
    .each(function (i, obj) {
      var id = Math.random().toString(36).substr(2, 9);
      var hive_lite_support_automation_triggers_data = jQuery(obj)
        .find("#hive_lite_support_automation_triggers")
        .val();
      var hive_lite_support_automation_conditions_match_any = jQuery(obj)
        .find(".hive_lite_support_automation_conditions_match_any")
        .prop("checked")
        ? "1"
        : "0";
      var hive_lite_support_automation_conditions_match_all = jQuery(obj)
        .find(".hive_lite_support_automation_conditions_match_all")
        .prop("checked")
        ? "1"
        : "0";
      var hive_lite_support_automation_conditions = [];
      jQuery(obj)
        .find(".hive_lite_support_automation_condition_items")
        .children()
        .each(function () {
          if (jQuery(this).find("input").val().length > 0) {
            hive_lite_support_automation_conditions.push({
              field_id: jQuery(this).find("select").val(),
              field_value: jQuery(this).find("input").val(),
            });
          }
        });

      var hive_lite_support_automation_action_types = jQuery(obj)
        .find("#hive_lite_support_automation_action_types")
        .val();
      var hive_lite_support_automation_action_users = jQuery(obj)
        .find("#hive_lite_support_automation_action_users")
        .val();

      hive_lite_support_automation_data.push({
        id: id,
        trigger_id: hive_lite_support_automation_triggers_data,
        match_any: hive_lite_support_automation_conditions_match_any,
        match_all: hive_lite_support_automation_conditions_match_all,
        condition: hive_lite_support_automation_conditions,
        action_id: hive_lite_support_automation_action_types,
        agent_id: hive_lite_support_automation_action_users,
      });
    });

  var post_data = {
    action: "hive_lite_support_automation_data",
    datas: hive_lite_support_automation_data,
  };
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      var obj = JSON.parse(data);
      if (obj.status === "true") {
        jQuery(".hive_lite_support_automation_save_btn")
          .text("Save Changes")
          .prop("disabled", false);
      }
    },
  });
}
