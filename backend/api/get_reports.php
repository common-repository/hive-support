<?php

if (current_user_can('hive_support_access_plugin')) {
    global $wpdb;
    $reportfilterdata = $_POST['reportsfilter'] ? (int) $_POST['reportsfilter'] : 7;
    $date_labels = [];
    $ticket_recieved_data = [];
    $ticket_closed_data = [];

    $ticket_table = $wpdb->prefix . 'hs_tickets';

    for ($i = 0; $i < $reportfilterdata; $i++) {
        $timestamps = strtotime("-{$i} days");
        $dateformat = date('Y-m-d', $timestamps);

        $queryrecieved = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $ticket_table WHERE DATE(created_at) = %s",
                array(
                    $dateformat
                )
            ),
        );

        $queryclosed = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $ticket_table WHERE DATE(created_at)= %s AND ticket_status=%s",
                array(
                    $dateformat,
                    'Closed'
                )
            )
        );

        $ticket_recieved_data[] = (int) $queryrecieved;
        $ticket_closed_data[] = (int) $queryclosed;
        $date_labels[] = str_replace("-", "/", $dateformat);
    }


    $data = array(
        "labels" => $date_labels,
        "datasets" => array(
            array(
                "label" => "Recived",
                "data" => $ticket_recieved_data,
                "backgroundColor" => "#685BE7",
                "hoverBackgroundColor" => '#fff',
                "hoverBorderColor" => '#685BE7',
                "borderColor" => '#fff',
                "borderWidth" => 2,
            ),
            array(
                "labels" => "Closed",
                "data" => $ticket_closed_data,
                "backgroundColor" => "#50DE88",
                "hoverBackgroundColor" => '#fff',
                "hoverBorderColor" => '#50DE88',
                "borderColor" => '#fff',
                "borderWidth" => 2,
            )
        )
    );

    wp_send_json_success(
        array(
            "chatsdata" => json_encode($data)
        )
    );
}
