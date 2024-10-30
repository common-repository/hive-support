<?php

if (true) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $mailbox = $_POST['mailbox'];
    $customer_id = $_POST['customerid'];
    $tickets = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id,mailbox_id,title,content,ticket_status,newest_agent_response,customer_seen_status,updated_at,created_at FROM $ticket_table WHERE mailbox_id=%d AND customer_id=%d  ORDER BY id DESC",
            array(
                $mailbox,
                $customer_id
            )
        )
    );
    wp_send_json_success($tickets);
    wp_die();
}
