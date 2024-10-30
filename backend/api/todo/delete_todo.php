<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    if (isset($_REQUEST['ticket_id']) && isset($_REQUEST['todo_id'])) {

        $ticket_id = wp_filter_post_kses($_REQUEST['ticket_id']);
        $todo_id = wp_filter_post_kses($_REQUEST['todo_id']);

        if ($this->base_admin->settings->isToDoExits($todo_id, $ticket_id)) {
            $this->base_admin->settings->deleteToDo($todo_id, $ticket_id);
            $result = array("status" => "true");
        } else {
            $result = array("status" => 'false');
        }
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
