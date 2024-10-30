<?php

if (current_user_can('hive_support_access_plugin')) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $ticket_data = [];
    $all_tickets = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id,title,content,customer_id,ticket_status,agent_id,seen_status,updated_at,created_at FROM $ticket_table ORDER BY id DESC"
        ),
        "ARRAY_A"
    );
    foreach ($all_tickets as $ticket) {
        $ticket['customerdata'] = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT display_name FROM wp_users WHERE ID = %d",
                array(
                    $ticket['customer_id']
                )
            )
        );
        $ticket['agentdata'] = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT display_name FROM wp_users WHERE ID=%d",
                array(
                    $ticket['agent_id']
                )
            )
        );
        array_push($ticket_data, $ticket);
    }
    wp_send_json_success($ticket_data);
}
