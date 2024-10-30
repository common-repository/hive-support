/* =========== GLOBAL OPERATIONS ========== */

function hive_lite_support_hide_all() {
  "use strict";
  jQuery("#hive_lite_support_agents").hide();
  jQuery("#hive_lite_support_settings").hide();
  jQuery("#hive_lite_support_tickets").hide();
  jQuery("#hive_lite_support_responses").hide();
  jQuery("#hive_lite_support_thread").hide();
  jQuery("#hive_lite_support_activities").hide();
  jQuery("#hive_lite_support_reports").hide();
  jQuery("#hive_lite_support_automation").hide();
}

function hive_lite_support_show_body_loader() {
  "use strict";
  hive_lite_support_hide_all();
  jQuery(".hive_lite_support_body_loader").css("display", "flex");
}
function hive_lite_support_hide_body_loader() {
  "use strict";
  jQuery(".hive_lite_support_body_loader").css("display", "none");
}

function hive_lite_support_cap_first_letter(string) {
  "use strict";
  return string.charAt(0).toUpperCase() + string.slice(1);
}
function hive_lite_support_admin_esc_string(str) {
  "use strict";
  return str
    .replaceAll("&", "::hive_lite_support_amp::")
    .replaceAll("<", "::hive_lite_support_left_arrow::")
    .replaceAll(">", "::hive_lite_support_right_arrow::")
    .replaceAll('"', "::hive_lite_support_dbl_quote::")
    .replaceAll("'", "::hive_lite_support_sin_quote::")
    .replaceAll("`", "::hive_lite_support_grave::")
    .replaceAll("\\", "::hive_lite_support_backslash::");
}

function hive_lite_support_admin_unesc_string(str) {
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

function hive_lite_support_admin_codify_string(str) {
  "use strict";
  return str
    .replaceAll("&amp;", "&")
    .replaceAll("&lt;", "<")
    .replaceAll("&gt;", ">")
    .replaceAll("&quot;", '"')
    .replaceAll("&#039;", "'")
    .replaceAll("&#96;", "`")
    .replaceAll("&#92;", "\\");
}

function hive_lite_support_admin_unesc_and_codify_string(str) {
  "use strict";
  return str
    .replaceAll("::hive_lite_support_amp::", "&")
    .replaceAll("::hive_lite_support_left_arrow::", "<")
    .replaceAll("::hive_lite_support_right_arrow::", ">")
    .replaceAll("::hive_lite_support_dbl_quote::", '"')
    .replaceAll("::hive_lite_support_sin_quote::", "'")
    .replaceAll("::hive_lite_support_grave::", "`")
    .replaceAll("::hive_lite_support_backslash::", "\\");
}
function hive_lite_support_auto_linkify_string(inputText) {
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

function enable_tinymce(field_name) {
  "use strict";
  var tinymce_plugins = "textcolor,image,lists,link";
  if (tinymce.PluginManager.lookup.link === undefined) {
    tinymce_plugins = "textcolor,image,lists";
  }
  wp.editor.remove(field_name);
  wp.editor.initialize(field_name, {
    tinymce: {
      wpautop: true,
      footer: false,
      plugins: tinymce_plugins,
      toolbar1:
        "undo,redo,formatselect,table,bold,italic,bullist,numlist,link,blockquote,image,alignleft,aligncenter,alignright,underline,strikethrough,forecolor,removeformat,codeformat,outdent,indent",
      height: 300,
    },
  });
  tinymce.get(field_name).on("Paste Change input Undo Redo", function () {
    jQuery("#" + field_name)
      .val(tinymce.get(field_name).getContent())
      .trigger("change");
  });
}
