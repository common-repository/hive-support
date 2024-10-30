<?php

$ticket_fields = $this->settings->updateSettings("ticket_fields");
$ticket_fields = ($ticket_fields == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $ticket_fields;

$fields = json_decode($ticket_fields, false);


// ================== List Agent ======================
$agentsList = array();

/* Non Administrator User */
$list_agents = $this->settings->listAllAgents();
if ($list_agents != Null) {
    foreach ($list_agents as $single_agent) {

        $user_id = $single_agent['user_id'];
        $user_info = get_userdata($user_id);
        if ($user_info != false) {
            if (!in_array("administrator", $user_info->roles)) {
                $profile_url = get_avatar_url($user_id);

                $title = $this->settings->updateAgentSettings($user_id, "title");
                $title = ($title == Null) ? "Support Staff" : $title;

                $agentsList[] = array(
                    "user_id" => $user_id,
                    "display_name" => $this->utils->getUserDisplayName($user_info),
                    "first_name" => trim($user_info->first_name),
                    "last_name" => trim($user_info->last_name),
                    "email" => $user_info->user_email,
                    "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
                    "position" => $title,
                );
            }
        }
    }
}


/* Added Hive Support Staff User from WordPress Users */
$support_users = get_users(array('role__in' => array('hive_support_staff')));
foreach ($support_users as $user) {
    $is_already_added = False;
    foreach ($agentsList as $already_added_single_user) {
        if ($already_added_single_user["user_id"] == $user->ID) {
            $is_already_added = True;
        }
    }
    if (!$is_already_added) {
        $profile_url = get_avatar_url($user->ID);

        $title = $this->settings->updateAgentSettings($user->ID, "title");
        $title = ($title == Null) ? "Support Staff" : $title;


        $agentsList[] = array(
            "user_id" => $user->ID,
            "display_name" => $this->utils->getUserDisplayName($user),
            "first_name" => trim($user->first_name),
            "last_name" => trim($user->last_name),
            "email" => $user->user_email,
            "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
            "position" => $title
        );
    }
}


/* Administrator User */
$admin_users = get_users(array('role__in' => array('administrator')));
foreach ($admin_users as $user) {
    $profile_url = get_avatar_url($user->ID);

    $title = $this->settings->updateAgentSettings($user->ID, "title");
    $title = ($title == Null) ? "Administrator" : $title;

    $agentsList[] = array(
        "user_id" => $user->ID,
        "display_name" => $this->utils->getUserDisplayName($user),
        "first_name" => trim($user->first_name),
        "last_name" => trim($user->last_name),
        "email" => $user->user_email,
        "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
        "position" => $title
    );
}
// ================== List Agent ======================








$get_automation_data = $this->settings->getAutomation();

?>
<div id="hive_lite_support_automation">
    <div class="hive_lite_support_automation_header">
        <div class="hive_lite_support_automation_header_flex">
            <div class="hive_lite_support_automation_header_single">
                <span class="hive_lite_support_automation_header_single_contents"> Automation <!--<img src="<?php /*echo HIVE_LITE_SUPPORT_IMG_DIR */ ?>automation/black-edit.svg">--> </span>
            </div>
            <div class="hive_lite_support_automation_header_single">
            </div>
            <div class="hive_lite_support_automation_header_single">
                <button class="hive_lite_support_automation_add_new_rules_btn" onclick="hive_lite_support_automation_add_new_rules()">+ Add New Rules</button>
                <button class="hive_lite_support_automation_save_btn" onclick="hive_lite_support_automation_field_save()">Save Changes</button>
            </div>
        </div>
    </div>
    <div class="rules_container" id="hive_lite_support_automation_rules_container">
        <?php
        if (sizeof($get_automation_data) > 0) {
            foreach ($get_automation_data as $key => $single_automation_data) {
        ?>

                <div class="rules_content">
                    <div class="hive_lite_support_automation_trigger">
                        <div class="hive_lite_support_automation_trigger_inner hive_lite_support_automation_trigger_inner_width">
                            <div class="hive_lite_support_automation_trigger_header">
                                <h4></h4>
                                <h4 class="hive_lite_support_automation_trigger_title"> Trigger </h4>
                                <button class="hive_lite_support_automation_trigger__header_delete" onclick="delete_trigger(this, '<?php echo esc_attr(($single_automation_data != Null) ? $single_automation_data['id'] : ""); ?>')"></button>
                            </div>
                            <div class="hive_lite_support_automation_trigger_contents">
                                <div class="hive_lite_support_automation_trigger_dropdown_container">
                                    <select id="hive_lite_support_automation_triggers" class="hive_lite_support_automation_trigger_dropdown_menu">
                                        <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="1" <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['trigger_id'] == "1") ? "selected" : ""); ?>>On Ticket Created</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hive_lite_support_automation_trigger_left_shapes">
                            <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>automation/trigger-left.svg">
                        </div>
                    </div>
                    <div class="hive_lite_support_automation_row_item ">
                        <div class="hive-support-automation-col-6 hive-support-automation-col-xl-6 hive-support-automation-col-lg-12 hive-support-automation-col-sm-6 hive-support-automation-col-xxs-12">
                            <div class="hive_lite_support_automation_trigger_inner">
                                <h4 class="hive_lite_support_automation_trigger_title"> Condition </h4>
                                <div class="hive_lite_support_automation_trigger_contents">
                                    <div class="hive_lite_support_automation_custom_radio hive_lite_support_automation_custom_radio_inline">
                                        <div onclick="hive_lite_support_automation_radio(this)" class="hive_lite_support_automation_custom_radio_single <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['match_any'] == "1") ? "active" : ""); ?>">
                                            <input class="radio-input hive_lite_support_automation_conditions_match_any" type="radio" <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['match_any'] == "1") ? "checked" : ""); ?>>
                                            <label for="hive_lite_support_automation_conditions_match_any"> Match any Conditions</label>
                                        </div>
                                        <div onclick="hive_lite_support_automation_radio(this)" class="hive_lite_support_automation_custom_radio_single <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['match_all'] == "1") ? "active" : ""); ?>">
                                            <input class="radio-input hive_lite_support_automation_conditions_match_all" type="radio" <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['match_all'] == "1") ? "checked" : ""); ?>>
                                            <label for="hive_lite_support_automation_conditions_match_all"> Match all Conditions</label>
                                        </div>
                                    </div>

                                    <div class="hive_lite_support_automation_condition_items">
                                        <?php
                                        $conditions = isset($single_automation_data['condition']) ? $single_automation_data['condition'] : [];
                                        if (sizeof($conditions) > 0) {
                                            foreach ($conditions as $key => $condition) { ?>
                                                <div class="hive_lite_support_automation_condition_item">
                                                    <div class="hive_lite_support_automation_trigger_dropdown_container">
                                                        <select class="hive_lite_support_automation_trigger_dropdown_menu">
                                                            <?php foreach ($fields as $field) { ?>
                                                                <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="<?php echo esc_attr($field->field_id); ?>" <?php echo esc_attr(($condition['field_id'] == $field->field_id) ? "selected" : ""); ?>>If <?php echo esc_attr($field->label); ?> Contains</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="hive_lite_support_automation_trigger_input_container">
                                                        <input class="hive_lite_support_automation_trigger_input_item" value="<?php echo esc_attr($condition['field_value']); ?>" type="text" placeholder="Value" />
                                                    </div>
                                                    <?php if ($key > 0) { ?>
                                                        <button class="hive_lite_support_automation_trigger_delete" onclick="delete_condition(this)"></button>
                                                    <?php } ?>
                                                </div>
                                            <?php   }
                                        } else { ?>
                                            <div class="hive_lite_support_automation_condition_item">
                                                <div class="hive_lite_support_automation_trigger_dropdown_container">
                                                    <select class="hive_lite_support_automation_trigger_dropdown_menu">
                                                        <?php foreach ($fields as $field) { ?>
                                                            <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="<?php echo esc_attr($field->field_id); ?>">If <?php echo esc_attr($field->label); ?> Contains</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="hive_lite_support_automation_trigger_input_container">
                                                    <input class="hive_lite_support_automation_trigger_input_item" value="" type="text" placeholder="Value" />
                                                </div>
                                            </div>
                                        <?php } ?>


                                    </div>


                                    <div class="hive_lite_support_automation_btn_wrapper">
                                        <button class="hive_lite_support_automation_cmn_btn hive_lite_support_automation_btn_bg_1" onclick="add_new_condition()"> Add new Condition </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hive-support-automation-col-6 hive-support-automation-col-xl-6 hive-support-automation-col-lg-12 hive-support-automation-col-sm-6 hive-support-automation-col-xxs-12">
                            <div class="hive_lite_support_automation_trigger_inner hive_lite_support_automation_margin">
                                <h4 class="hive_lite_support_automation_trigger_title"> Action </h4>
                                <div class="hive_lite_support_automation_trigger_contents">
                                    <div class="hive_lite_support_automation_trigger_dropdown_container">
                                        <select id="hive_lite_support_automation_action_types" class="hive_lite_support_automation_trigger_dropdown_menu">
                                            <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="1" <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['action_id'] == "1") ? "selected" : ""); ?>>Assign an Agent</option>
                                        </select>
                                    </div>
                                    <div class="hive_lite_support_automation_trigger_dropdown_container">
                                        <select id="hive_lite_support_automation_action_users" class="hive_lite_support_automation_trigger_dropdown_menu">
                                            <?php
                                            if ($agentsList != Null) {
                                                foreach ($agentsList as $single_agent) {
                                                    $user_id = $single_agent['user_id'];
                                                    $user_info = get_userdata($user_id);
                                            ?>
                                                    <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="<?php echo esc_attr($user_id); ?>" <?php echo esc_attr(($single_automation_data != Null && $single_automation_data['agent_id'] == $user_id) ? "selected" : ""); ?>><?php echo esc_attr($this->utils->getUserDisplayName($user_info)); ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="hive_lite_support_automation_trigger_right_shapes">
                                    <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>automation/trigger-right.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="hr-1">
                </div>


        <?php }
        } ?>
    </div>

</div>

<div id="hive_lite_support_trigger_template" style="display: none">
    <div class="rules_content">
        <div class="hive_lite_support_automation_trigger">
            <div class="hive_lite_support_automation_trigger_inner hive_lite_support_automation_trigger_inner_width">
                <div class="hive_lite_support_automation_trigger_header">
                    <h4></h4>
                    <h4 class="hive_lite_support_automation_trigger_title"> Trigger </h4>
                    <button class="hive_lite_support_automation_trigger__header_delete" onclick="delete_trigger(this, '<?php echo esc_attr(($single_automation_data != Null) ? $single_automation_data['id'] : ""); ?>')"></button>
                </div>
                <div class="hive_lite_support_automation_trigger_contents">
                    <div class="hive_lite_support_automation_trigger_dropdown_container">
                        <select id="hive_lite_support_automation_triggers" class="hive_lite_support_automation_trigger_dropdown_menu">
                            <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="1">On Ticket Created</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="hive_lite_support_automation_trigger_left_shapes">
                <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>automation/trigger-left.svg">
            </div>
        </div>
        <div class="hive_lite_support_automation_row_item ">
            <div class="hive-support-automation-col-6 hive-support-automation-col-xl-6 hive-support-automation-col-lg-12 hive-support-automation-col-sm-6 hive-support-automation-col-xxs-12">
                <div class="hive_lite_support_automation_trigger_inner">
                    <h4 class="hive_lite_support_automation_trigger_title"> Condition </h4>
                    <div class="hive_lite_support_automation_trigger_contents">
                        <div class="hive_lite_support_automation_custom_radio hive_lite_support_automation_custom_radio_inline">
                            <div onclick="hive_lite_support_automation_radio(this)" class="hive_lite_support_automation_custom_radio_single active">
                                <input class="radio-input hive_lite_support_automation_conditions_match_any" type="radio" checked>
                                <label for="hive_lite_support_automation_conditions_match_any"> Match any Conditions</label>
                            </div>
                            <div onclick="hive_lite_support_automation_radio(this)" class="hive_lite_support_automation_custom_radio_single">
                                <input class="radio-input hive_lite_support_automation_conditions_match_all" type="radio">
                                <label for="hive_lite_support_automation_conditions_match_all"> Match all Conditions</label>
                            </div>
                        </div>

                        <div class="hive_lite_support_automation_condition_items">
                            <div class="hive_lite_support_automation_condition_item">
                                <div class="hive_lite_support_automation_trigger_dropdown_container">
                                    <select class="hive_lite_support_automation_trigger_dropdown_menu">
                                        <?php foreach ($fields as $field) { ?>
                                            <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="<?php echo esc_attr($field->field_id); ?>">If <?php echo esc_attr($field->label); ?> Contains</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="hive_lite_support_automation_trigger_input_container">
                                    <input class="hive_lite_support_automation_trigger_input_item" value="" type="text" placeholder="Value" />
                                </div>
                            </div>
                        </div>
                        <div class="hive_lite_support_automation_btn_wrapper">
                            <button class="hive_lite_support_automation_cmn_btn hive_lite_support_automation_btn_bg_1" onclick="add_new_condition()"> Add new Condition </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hive-support-automation-col-6 hive-support-automation-col-xl-6 hive-support-automation-col-lg-12 hive-support-automation-col-sm-6 hive-support-automation-col-xxs-12">
                <div class="hive_lite_support_automation_trigger_inner hive_lite_support_automation_margin">
                    <h4 class="hive_lite_support_automation_trigger_title"> Action </h4>
                    <div class="hive_lite_support_automation_trigger_contents">
                        <div class="hive_lite_support_automation_trigger_dropdown_container">
                            <select id="hive_lite_support_automation_action_types" class="hive_lite_support_automation_trigger_dropdown_menu">
                                <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="1">Assign an Agent</option>
                            </select>
                        </div>
                        <div class="hive_lite_support_automation_trigger_dropdown_container">
                            <select id="hive_lite_support_automation_action_users" class="hive_lite_support_automation_trigger_dropdown_menu">
                                <?php
                                if ($agentsList != Null) {
                                    foreach ($agentsList as $single_agent) {
                                        $user_id = $single_agent['user_id'];
                                        $user_info = get_userdata($user_id);
                                ?>
                                        <option class="hive_lite_support_automation_trigger_dropdown_menu_list" value="<?php echo esc_attr($user_id); ?>"><?php echo esc_attr($this->utils->getUserDisplayName($user_info)); ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="hive_lite_support_automation_trigger_right_shapes">
                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>automation/trigger-right.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="hr-1">
</div>