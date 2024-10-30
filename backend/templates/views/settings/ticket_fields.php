<div class="hive_lite_support_settings_ticket_fields">




    <div class="hive_lite_support_settings_ticket_fields_body">


        <div class="hive_lite_support_settings_ticket_fields_body_left_part">
            <div class="hive_lite_support_ticket_fields_section_header">
                <div class="hive_lite_support_ticket_fields_section_header_details">
                    <h3>Build Custom Ticket Submission Form</h3>
                    <p>Drag and drop form fields here from right sidebar to build custom form</p>
                </div>
                <button onclick="hive_lite_support_settings_ticket_field_save()">Save Changes</button>

            </div>
            <div class="hive_lite_support_settings_ticket_fields_dropped_form_items">

                <!--<div class="hive_lite_support_settings_ticket_fields_dropped_form_item item_open">
                    <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_header">
                        <h4>Single Line Text Area</h4>
                        <button class="delete_form_element" onclick="hive_lite_support_settings_ticket_fields_item_delete(this)"></button>
                        <button class="edit_form_element" onclick="hive_lite_support_settings_ticket_fields_item_open_customizer(this)">Edit</button>
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_dropped_form_item_body">
                        <div class="hive_lite_support_settings_ticket_field_customizer">
                            <label>Field Label</label>
                            <input type="text" value="" onkeyup="hive_lite_support_settings_ticket_field_customizer_update_data(this, `label`)">
                        </div>
                        <div class="hive_lite_support_settings_ticket_field_customizer">
                            <label>Is Required?</label>
                            <select onchange="hive_lite_support_settings_ticket_field_customizer_update_data(this, `required`)">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                </div>-->

            </div>
            <div class="hive_lite_support_settings_ticket_fields_dropped_form_items_empty">Drop Here</div>
        </div>



        <div class="hive_lite_support_settings_ticket_fields_body_right_part">
            <div class="hive_lite_support_ticket_fields_section_header">
                <h3>Form Fields</h3>
            </div>
            <div class="hive_lite_support_settings_ticket_fields_droppable_form_items">
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("simple_text"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_simple_text.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Text Field</h4>
                        <p>Use it if user required to input single line texts</p>
                    </div>
                </div>
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("text_area"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_text_area.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Textarea Field</h4>
                        <p>Use it if user required to input multiline texts</p>
                    </div>
                </div>
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("number"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_number.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Number Field</h4>
                        <p>Use it if user required to input number</p>
                    </div>
                </div>
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("dropdown"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_dropdown.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Dropdown Field</h4>
                        <p>Use it if user required to select from dropdown</p>
                    </div>
                </div>
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("radio"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_radio.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Radio Field</h4>
                        <p>Use it if user required to choose one from many</p>
                    </div>
                </div>
                <div class="hive_lite_support_settings_ticket_fields_droppable_form_item" <?php echo ($this->utils->generateBuilderFieldDataHTML($this->utils->getDefaultFieldData("checkbox"))); ?>>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_icons">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/drag_icon.svg") ?>">
                        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "ticket_fields/field_checkbox.svg") ?>">
                    </div>
                    <div class="hive_lite_support_settings_ticket_fields_droppable_form_item_details">
                        <h4>Checkbox Field</h4>
                        <p>Use it if user required to choose one or more from many</p>
                    </div>
                </div>
            </div>
        </div>
    </div>






</div>