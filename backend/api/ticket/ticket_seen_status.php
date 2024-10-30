<?php

global $wpdb;

if( empty( $_POST['ticket_id'] ) ) {
    wp_send_json_error();
    return;
}

$res = $wpdb->update(
        $wpdb->prefix . 'hs_tickets',
        array(
            'seen_status' => 'yes'
        ),
        array('id' => sanitize_text_field($_POST['ticket_id']) )
    );

if (!$res || is_wp_error($res)) {
    wp_send_json_error();
    return;
} 
wp_send_json_success();
