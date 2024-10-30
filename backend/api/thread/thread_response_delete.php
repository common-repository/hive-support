<?php

$result = array();

/* Check if user has admin capabilities */
if (
    current_user_can('hive_support_access_plugin') && isset($_REQUEST['ticket_id'])
    && isset($_REQUEST['response_id'])
) {

    $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
    $response_id_for_deletion = sanitize_text_field($_REQUEST['response_id']);

    $responses = $this->base_admin->settings->updateTicketSettings($ticket_id, "responses");

    $updated_response_arr = array();
    $responses_obj = json_decode($responses, false);
    foreach ($responses_obj as $single_response) {
        $response_id = $single_response->response_id;
        if ($response_id != $response_id_for_deletion) {
            $updated_response_arr[] = $single_response;
        }
    }
    $this->base_admin->settings->updateTicketSettings($ticket_id, "responses", addslashes(json_encode($updated_response_arr,  JSON_UNESCAPED_UNICODE)));

    $result = array("status" => 'true');
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
