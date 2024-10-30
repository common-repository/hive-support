<?php

if (true) {
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $replies_table = $wpdb->prefix . 'hs_conversations';
    $ticket_id = $_POST['ticketid'];

    $tickets_by_id = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $ticket_table WHERE id=%d LIMIT %d",
            array(
                $ticket_id,
                1
            )
        )
    );

    if (!empty($tickets_by_id->customer_id)) {
        $author_obj = get_user_by('id', absint($tickets_by_id->customer_id));
        $current_user_name = $author_obj->user_login;
        $tickets_by_id->customer_name = $current_user_name;
    }

    $ticket_replies = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id,ticket_id,person_id,content,media_urls,created_at FROM $replies_table  WHERE $replies_table.ticket_id=%d ORDER BY id DESC",
            array(
                $ticket_id
            )
        ),
        'ARRAY_A'
    );


    $replies_data = [];

    foreach ($ticket_replies as $reply) {
        $userdata =  get_userdata((int) $reply['person_id']);

        $reply['useobject'] = $userdata;
        $reply['user_role'] = !empty( $userdata ) ? array_shift( $userdata->roles ) :'';
        array_push($replies_data, $reply);
    }
    wp_send_json_success(
        array(
            "tickets" => $tickets_by_id,
            "ticket_replies" => $replies_data
        )
    );

    wp_die();
}
