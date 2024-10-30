<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {


    $activities = array();

    $list_activities = $this->base_admin->settings->listAllActivities();
    foreach ($list_activities as $single_entry) {
        $type = $single_entry['type'];
        $time = $single_entry['time'];
        $user_id = $single_entry['user_id'];
        $value1 = $single_entry['value1'];
        $value2 = $single_entry['value2'];
        $user_name = "";
        $user_img = "";
        $user_title = "";
        $action_msg = "";
        $updated_at = $this->base_admin->utils->get_gmt_time_different($time);


        $user_info = get_userdata($user_id);
        if ($user_info != false) {
            $user_name = $this->base_admin->utils->getUserDisplayName($user_info);
            $user_img = get_avatar_url($user_id);
        }




        if ($type == "customer_ticket_created" || $type == "customer_ticket_replied") {
            $user_title = "Customer";
        } else {
            $user_title = $this->base_admin->settings->updateAgentSettings($user_id, "title");
            if ($user_title == Null) {
                if (in_array("administrator", $user_info->roles)) {
                    $user_title = "Administrator";
                } else {
                    $user_title = "Support Staff";
                }
            }
        }

        if ($type == "customer_ticket_created") {
            $action_msg = "created a ticket #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        } else if ($type == "customer_ticket_replied") {
            $action_msg = "added a response to #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        } else if ($type == "staff_ticket_replied") {
            $action_msg = "added a response to #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        } else if ($type == "staff_ticket_closed") {
            $action_msg = "closed a ticket #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        } else if ($type == "staff_ticket_opened") {
            $action_msg = "opened a ticket #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        } else if ($type == "staff_assigned") {
            $staff_name = $this->base_admin->utils->getUserDisplayName(get_userdata($value2));
            $action_msg = "assigned " . $staff_name . " to #" . $this->base_admin->settings->updateTicketSettings($value1, "ticket_number");
        }

        $activities[] = array(
            "user_name" => $user_name,
            "user_img" => $user_img,
            "user_title" => $user_title,
            "action_msg" => $action_msg,
            "updated_at" => $updated_at
        );
    }



    $activities = array_reverse($activities);
    if (sizeof($activities) > 20) {
        $activities = array_slice($activities, 0, 20);
    }


    $result = array(
        "status" => 'true',
        "activities" => $activities
    );
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
