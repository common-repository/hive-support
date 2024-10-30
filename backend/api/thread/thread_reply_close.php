<?php

$result = array();

/* Check if user has admin capabilities */
if (
    current_user_can('hive_support_access_plugin') && isset($_REQUEST['ticket_id'])
    && isset($_REQUEST['type'])
) {

    $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
    $type = sanitize_text_field($_REQUEST['type']);

    /* Get Current User's Info */
    $submitting_user = wp_get_current_user();
    $submitting_user_id = (int) $submitting_user->ID;

    $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id", $submitting_user_id);
    $this->base_admin->settings->updateTicketSettings($ticket_id, "status", "close");

    $this->base_admin->settings->recordActivity(
        "staff_ticket_closed",
        $submitting_user_id,
        $ticket_id
    );


    $result = array("status" => 'true');
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
