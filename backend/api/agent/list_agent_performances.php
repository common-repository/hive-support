<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    if (isset($_REQUEST['search_length'])) {

        $search_length = wp_filter_post_kses($_REQUEST['search_length']);
        $users_id = array();
        $replied_data = array();
        $total_ticket_created = 0;

        /* Administrator User */
        $admin_users = get_users(array('role__in' => array('administrator')));
        foreach ($admin_users as $user) {
            if (!in_array($user->ID, $users_id)) {
                array_push($users_id, $user->ID);
                array_push($replied_data, "0");
            }
        }

        /* Added Hive Support Staff User from WordPress Users */
        $support_users = get_users(array('role__in' => array('hive_support_staff')));
        foreach ($support_users as $user) {
            if (!in_array($user->ID, $users_id)) {
                array_push($users_id, $user->ID);
                array_push($replied_data, "0");
            }
        }

        /* Non Administrator User */
        $list_agents = $this->base_admin->settings->listAllAgents();
        if ($list_agents != Null) {
            foreach ($list_agents as $single_agent) {
                if (!in_array($single_agent['user_id'], $users_id)) {
                    array_push($users_id, $single_agent['user_id']);
                    array_push($replied_data, "0");
                }
            }
        }






        for ($i = 0; $i < $search_length; $i++) {
            $date = strtotime("-$i day");
            $dateStr =  date('d/m/Y', $date);

            $ticket_replied = 0;

            $list_activities = $this->base_admin->settings->listAllActivities();
            foreach ($list_activities as $single_entry) {
                $user_id = $single_entry['user_id'];

                $user_id_index_no = array_search($user_id, $users_id);

                $time = strtotime($single_entry['time']);
                $timeStr =  date('d/m/Y', $time);


                if ($dateStr == $timeStr) {
                    $type = $single_entry['type'];
                    if ($type == "customer_ticket_created") {
                        $total_ticket_created++;
                    }
                    if ($type == "staff_ticket_replied") {
                        $replied_data[$user_id_index_no] = (int)$replied_data[$user_id_index_no] + 1;
                    }
                }
            }
        }

        $datas = array();
        for ($i = 0; $i < count($users_id); $i++) {

            $user_info = get_userdata($users_id[$i]);
            if ($user_info != false) {
                $user_name = $this->base_admin->utils->getUserDisplayName($user_info);
                $user_img = get_avatar_url($users_id[$i]);
            }
            $datas[] = array(
                "user_id" => $users_id[$i],
                "user_name" => $user_name,
                "user_img" => $user_img,
                "replied_no" => $replied_data[$i],
                "replied_perc" => ($total_ticket_created > (int)$replied_data[$i]) ? ((100 / $total_ticket_created) * (int)$replied_data[$i]) : 0
            );
        }







        $result = array(
            "status" => 'true',
            "datas" => $datas,
            "total" => sizeof($list_agents)
        );
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
