<?php

if (current_user_can("hive_support_access_plugin")) {
    global $wpdb;

    $ticket_id = $_POST['ticketid'];
    $ticket_activities = $wpdb->prefix . 'hs_ticket_activities';
    $users_table = $wpdb->prefix . 'users';

    $activities = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $ticket_activities INNER JOIN $users_table ON $ticket_activities.initiator=$users_table.ID WHERE $ticket_activities.ticket_id=%s",
            array(
                $ticket_id
            )
        ),
        "ARRAY_A"
    );




    wp_send_json_success($activities);
}
