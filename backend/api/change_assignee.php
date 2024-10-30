<?php

if (current_user_can('hive_support_access_plugin')) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $ticket_id = $_POST['ticket_id'];
    $assigne = $_POST['assigne_id'];


    $ticket_update_query = $wpdb->query(
        $wpdb->prepare(
            "UPDATE $ticket_table SET agent_id = %d WHERE id = %d ",
            array(
                (int) $assigne,
                (int) $ticket_id
            )
        )
    );


    $ticket_data = [];
    $ticket_data['ticket_id'] = $ticket_id;
    $ticket_data['agent_id'] = $assigne;
    do_action('hs_ticket_assigned', $ticket_data);

    if (!$ticket_update_query) {
        wp_send_json_error();
    }
    wp_send_json_success();
}
