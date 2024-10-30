<?php

if (current_user_can('hive_support_access_plugin')) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $ticket_id = $_POST['ticket_id'];
    $priority = $_POST['priority'];


    $ticket_update_query = $wpdb->query(
        $wpdb->prepare(
            "UPDATE $ticket_table SET priority = %s WHERE id = %d ",
            array(
                $priority,
                (int) $ticket_id
            )
        )
    );

    if (!$ticket_update_query) {
        wp_send_json_error();
    }
    wp_send_json_success();
}
