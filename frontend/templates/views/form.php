<?php

$ticket_fields = $this->settings->updateSettings("ticket_fields");
$ticket_fields = ($ticket_fields == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $ticket_fields;

$fields = json_decode($ticket_fields, false);

?>

<div id="hive_lite_support_client_form">


    <div class="hive_lite_support_client_form_header">
        <h4>Create New Ticket</h4>
        <button class="show_tickets" onclick="hive_lite_support_client_show_tickets()">All Tickets</button>
    </div>


    <div class="hive_lite_support_client_form_body">
        <?php foreach ($fields as $field) { ?>

            <?php if ($field->slug == "subject") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <input id="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>" type="text" onkeyup="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "message") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <textarea id="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>" rows="4" onkeyup="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>></textarea>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "simple_text") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <input id="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>" type="text" onkeyup="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "text_area") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <textarea id="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>" rows="4" onkeyup="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>></textarea>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "number") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <input id="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>" type="number" onkeyup="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "dropdown") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label" for="hive_lite_support_field_id_<?php echo esc_attr($field->field_id); ?>"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <select onchange="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>>
                            <?php foreach (explode("::hive_lite_support_separator::", $field->options) as $option) { ?>
                                <option value="<?php echo esc_attr($option); ?>"><?php echo esc_attr($option); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <?php if ($field->slug == "radio") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <div class="radio_container">
                            <?php foreach (explode("::hive_lite_support_separator::", $field->options) as $option) { ?>
                                <label class="radio_item"><?php echo esc_attr($option); ?><input type="radio" class="radio_<?php echo esc_attr($field->field_id); ?>" value="<?php echo esc_attr($option); ?>" onclick="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>><span class="checkmark"></span></label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        'use strict';
                        jQuery('.radio_<?php echo esc_attr($field->field_id); ?>').change(function() {
                            jQuery('.radio_<?php echo esc_attr($field->field_id); ?>').not(this).prop('checked', false);
                        });
                    });
                </script>
            <?php } ?>

            <?php if ($field->slug == "checkbox") { ?>
                <div class="hive_lite_support_single_form_element" data-field_id="<?php echo esc_attr($field->field_id); ?>" data-field_slug="<?php echo esc_attr($field->slug); ?>">
                    <label class="hive_lite_support_element_label"><?php echo esc_attr($field->label); ?></label>
                    <div class="hive_lite_support_element_field_main">
                        <div class="checkbox_container">
                            <?php foreach (explode("::hive_lite_support_separator::", $field->options) as $option) { ?>
                                <label class="checkbox_item"><?php echo esc_attr($option); ?><input type="checkbox" class="checkbox_<?php echo esc_attr($field->field_id); ?>" value="<?php echo esc_attr($option); ?>" onclick="hive_lite_support_field_remove_errors(this)" <?php echo esc_attr(($field->required == "1") ? "required" : ""); ?>><span class="checkmark"></span></label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php if ($field->required == "1") { ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            'use strict';
                            jQuery('.checkbox_<?php echo esc_attr($field->field_id); ?>').change(function() {
                                if (jQuery('.checkbox_<?php echo esc_attr($field->field_id); ?>:checked').length > 0) {
                                    jQuery('.checkbox_<?php echo esc_attr($field->field_id); ?>').removeAttr('required');
                                } else {
                                    jQuery('.checkbox_<?php echo esc_attr($field->field_id); ?>').attr('required', 'required');
                                }
                            });
                        });
                    </script>
                <?php } ?>
            <?php } ?>

        <?php } ?>



        <div class="hive_lite_support_single_form_element hello-prio-submit" data-field_slug="submit_btn">
            <div class="custom_btn_container">
                <button class="reply" onclick="hive_lite_support_client_form_submit()">Submit</button>
            </div>
        </div>


    </div>

</div>