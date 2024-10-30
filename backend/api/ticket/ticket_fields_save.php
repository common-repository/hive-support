<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {


    if (isset($_REQUEST['ticket_fields'])) {

        $ticket_fields = sanitize_text_field($_REQUEST['ticket_fields']);
        $this->base_admin->settings->updateSettings("ticket_fields", $ticket_fields);

        $result = array("status" => "true");
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
