<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    if (isset($_REQUEST['search_length'])) {

        $search_length = wp_filter_post_kses($_REQUEST['search_length']);


        $stats = array();
        $date_list = array();
        $ticket_created_list = array();
        $ticket_closed_list = array();





        for ($i = 0; $i < $search_length; $i++) {
            $date = strtotime("-$i day");
            $dateStr =  date('d/m/Y', $date);

            $ticket_created = 0;
            $ticket_closed = 0;

            $list_activities = $this->base_admin->settings->listAllActivities();
            foreach ($list_activities as $single_entry) {

                $time = strtotime($single_entry['time']);
                $timeStr =  date('d/m/Y', $time);


                if ($dateStr == $timeStr) {
                    $type = $single_entry['type'];

                    if ($type == "customer_ticket_created") {
                        $ticket_created++;
                    }
                    if ($type == "staff_ticket_closed") {
                        $ticket_closed++;
                    }
                }
            }

            array_push($date_list, $dateStr);
            array_push($ticket_created_list, $ticket_created);
            array_push($ticket_closed_list, $ticket_closed);

            $stats[] = array(
                "date" => $dateStr,
                "ticket_created" => $ticket_created,
                "ticket_closed" => $ticket_closed,
            );
        }







        $result = array(
            "status" => 'true',
            "date" => array_reverse($date_list),
            "ticket_created" => array_reverse($ticket_created_list),
            "ticket_closed" => array_reverse($ticket_closed_list)
        );
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
