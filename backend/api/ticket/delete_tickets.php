<?php

if( !isset( $_POST['ticket_ids'] ) ) {
    wp_send_json_error();
}

global $wpdb;

$ticket_table = $wpdb->prefix . 'hs_tickets';

$ids = $_POST['ticket_ids'];

$res = $wpdb->query(
    "DELETE FROM $ticket_table
     WHERE id IN($ids)"     
);


if( is_wp_error( $res ) || empty( $res )  ) {
    wp_send_json_error();
}


wp_send_json_success($res);