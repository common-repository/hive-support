<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {
    if (isset($_REQUEST['ticket_id'])) {
        $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);

        $responses = array();

        $list_entries = $this->base_admin->settings->listAllToDo($ticket_id);
        foreach ($list_entries as $single_entry) {
            $responses[] = array(
                "todo_id" => $single_entry['todo_id'],
                "ticket_id" => $single_entry['ticket_id'],
                "agent_id" => $single_entry['agent_id'],
                "data" => $single_entry['data']
            );
        }

        $result = array(
            "status" => 'true',
            "todos" => array_reverse($responses)
        );
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
