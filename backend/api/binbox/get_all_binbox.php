<?php

global $wpdb;
$table_name = $wpdb->prefix . 'hs_mailbox';

// Fetching all rows from the table
$results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

global $wpdb;
$table_name = $wpdb->prefix . 'hs_mailbox';

// Fetching all rows from the table
$results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

foreach ($results as &$result) {
    $result['ticket_count'] = $this->get_ticket_count_by_mailbox($result['id']);
}
unset($result);


wp_send_json_success($results);
