const back_to_business_inbox = () => {
  jQuery(".hive_lite_support_settings_business_inbox_container").show();
  jQuery(".hive_lite_support_settings_add_new_business_inbox_container").hide();
};

const hive_lite_support_settings_new_business_inbox_short_code_click = () => {
  jQuery(
    ".hive_lite_support_settings_new_business_inbox_short_code_popup, .hive_lite_support_settings_new_business_inbox_popup_overlay"
  ).addClass("show");
};

const hive_lite_support_settings_new_business_inbox_popup_close = () => {
  jQuery(
    ".hive_lite_support_settings_new_business_inbox_short_code_popup, .hive_lite_support_settings_new_business_inbox_popup_overlay"
  ).removeClass("show");
};
const hive_lite_support_add_mailbox = (event) => {
  var post_data = {
    action: "hive_lite_support_add_mailboxes",
    emailPort: jQuery(".email_port").val(),
    emailInboxPath: jQuery(".email_inbox_path").val(),
    emailPassword: jQuery(".email_password").val(),
    emailId: jQuery(".email_id").val(),
    emailUrl: jQuery(".email_url").val(),
    mailboxTitle: jQuery(".mailbox_title").val(),
  };
  console.log(post_data);
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: post_data,
    success: function (data) {
      console.log(data);
    },
  });
};
const hive_lite_support_settings_short_code_popup_click_copy = (view) => {
  var temp_shortcode_elements = document.createElement("input");

  let copyText = jQuery(view).parent().find(".myInput");
  copyText.select();
  /* Copy the text inside the text field */
  //wp.media.editor.insert(copyText.val());

  temp_shortcode_elements.type = "input";
  temp_shortcode_elements.setAttribute("value", copyText.val());
  document.body.appendChild(temp_shortcode_elements);
  temp_shortcode_elements.select();
  document.execCommand("copy");
  temp_shortcode_elements.remove();
  notifySuccessMsg("Code copied in Clipboard");
};
