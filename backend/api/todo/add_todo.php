<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    if (isset($_REQUEST['ticket_id']) && isset($_REQUEST['data'])) {

        $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
        $data = wp_filter_post_kses($_REQUEST['data']);

        if (strlen(trim($ticket_id)) > 0 && strlen(trim($data)) > 0) {
            $user_id = (is_user_logged_in()) ? get_current_user_id() : "";

            $this->base_admin->settings->createNewTODO($ticket_id, $user_id, $data);
            $result = array("status" => "true");
        } else {
            $result = array("status" => 'false', "msg" => 'Write all the field is required.');
        }
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
