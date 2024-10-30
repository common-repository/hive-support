<?php

$result = array();

/* Check if user has admin capabilities */
if (
    current_user_can('hive_support_access_plugin') && isset($_REQUEST['ticket_id'])
    && isset($_REQUEST['status']) && isset($_REQUEST['agent_id'])
) {

    $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
    $status = sanitize_text_field($_REQUEST['status']);
    $agent_id = sanitize_text_field($_REQUEST['agent_id']);

    /* Get Current User's Info */
    $submitting_user = wp_get_current_user();
    $submitting_user_id = (int) $submitting_user->ID;

    $old_agent = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
    if ($old_agent != $agent_id) {
        $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id", $agent_id);
        /* Record to Activities */
        $this->base_admin->settings->recordActivity(
            "staff_assigned",
            $submitting_user_id,
            $ticket_id,
            $agent_id
        );
    }

    $old_status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
    if ($old_status != $status) {
        $this->base_admin->settings->updateTicketSettings($ticket_id, "status", $status);
        if ($status == "close") {
            /* Record to Activities */
            $this->base_admin->settings->recordActivity(
                "staff_ticket_closed",
                $submitting_user_id,
                $ticket_id
            );
        } else if ($status == "open") {
            /* Record to Activities */
            $this->base_admin->settings->recordActivity(
                "staff_ticket_opened",
                $submitting_user_id,
                $ticket_id
            );
        }
    }

    $result = array("status" => 'true');
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
