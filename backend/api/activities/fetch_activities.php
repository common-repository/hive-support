<?php

$current_page = sanitize_text_field($_POST['current_page']);
$count = sanitize_text_field($_POST['per_page']);

global $wpdb;
$ticket_activities = $wpdb->prefix . 'hs_ticket_activities';
$users_table = $wpdb->prefix . 'users';

$offset = ($current_page - 1) * $count;

$query = "SELECT * FROM {$ticket_activities} INNER JOIN {$users_table} ON {$ticket_activities}.initiator = {$users_table}.ID";
$query .= " ORDER BY {$ticket_activities}.created_at DESC";
$query .= " LIMIT {$offset}, {$count}";

$activities = $wpdb->get_results($query, "ARRAY_A");

$total_result_count = $wpdb->get_var("SELECT COUNT(*) FROM {$ticket_activities}");

wp_send_json_success(array(
    'activities' => $activities,
    'total_result_count' => $total_result_count,
));
