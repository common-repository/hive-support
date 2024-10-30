<?php

if( !isset( $_POST['ticket_ids'] ) ) {
    wp_send_json_error();
}

global $wpdb;

$ticket_table = $wpdb->prefix . 'hs_tickets';

$ids = $_POST['ticket_ids'];

$sql = $wpdb->prepare(
    "UPDATE $ticket_table SET ticket_status = %s WHERE id IN ($ids)",
    'Closed'
);

$res = $wpdb->query($sql);

if( is_wp_error( $res ) || empty( $res )  ) {
    wp_send_json_error();
}


wp_send_json_success($res);