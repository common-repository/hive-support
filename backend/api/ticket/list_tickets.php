<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {


    $tickets = array();
    $list_entries = $this->base_admin->settings->listAllTickets();
    foreach ($list_entries as $single_entry) {
        $ticket_id = $single_entry['ticket_id'];
        $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
        $ticket_number = $this->base_admin->settings->updateTicketSettings($ticket_id, "ticket_number");
        $created_at = $this->base_admin->settings->updateTicketSettings($ticket_id, "created_at");
        $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
        $user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "user_id");
        $subject = $this->base_admin->settings->updateTicketSettings($ticket_id, "subject");
        $main_msg = $this->base_admin->settings->updateTicketSettings($ticket_id, "message");
        $responses = $this->base_admin->settings->updateTicketSettings($ticket_id, "responses");
        $form_data = $this->base_admin->settings->updateTicketSettings($ticket_id, "form_data");
        $agent_user_name = "Not Assigned";
        $agent_user_img = HIVE_LITE_SUPPORT_URL . "/assets/img/tickets/no_user_img.png";
        $user_name = "Anonymous";
        $user_img = "";
        $modified_at = $created_at;
        $last_msg = $main_msg;


        if (!$this->base_admin->settings->agentHasAccess("manage_all_tickets")) {
            if ($agent_user_id != wp_get_current_user()->ID) {
                continue;
            }
        }

        $responses_obj = json_decode($responses, false);
        if (sizeof($responses_obj) > 0) {
            $responses_obj_reverse = array_reverse($responses_obj, true);
            foreach ($responses_obj_reverse as $single_response) {
                $modified_at = $single_response->created_at;
                $last_msg = $single_response->msg;
                break;
            }
        }

        $user_info = get_userdata($user_id);
        if ($user_info != false) {
            $user_name = $this->base_admin->utils->getUserDisplayName($user_info);
            $user_img = get_avatar_url($user_id);
        }

        $agent_user_info = get_userdata($agent_user_id);
        if ($agent_user_info != false) {
            $agent_user_name = $this->base_admin->utils->getUserDisplayName($agent_user_info);
            $agent_user_img = get_avatar_url($agent_user_id);
        }

        $tickets[] = array(
            "ticket_id" => $ticket_id,
            "ticket_number" => $ticket_number,
            "status" => $status,
            "agent_user_id" => $agent_user_id,
            "agent_user_name" => $agent_user_name,
            "agent_user_img" => $agent_user_img,
            "user_id" => $user_id,
            "user_name" => $user_name,
            "user_img" => $user_img,
            "created_at" => get_date_from_gmt($created_at, 'd M Y'),
            "modified_at" => $this->base_admin->utils->get_gmt_time_different($modified_at),
            "subject" => $subject,
            "last_msg" => strip_tags($last_msg)
        );
    }




    /* Ticket Summary */
    $total_tickets_count = 0;
    $open_tickets_count = 0;
    $waiting_tickets_count = 0;
    $close_tickets_count = 0;
    foreach ($list_entries as $single_entry) {
        $ticket_id = $single_entry['ticket_id'];
        $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");

        $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
        if (!$this->base_admin->settings->agentHasAccess("manage_all_tickets")) {
            if ($agent_user_id != wp_get_current_user()->ID) {
                continue;
            }
        }

        $total_tickets_count++;
        if ($status == "open") {
            $open_tickets_count++;
        } else if ($status == "waiting") {
            $waiting_tickets_count++;
        } else if ($status == "close") {
            $close_tickets_count++;
        }
    }


    $result = array(
        "status" => 'true',
        "total_tickets_count" => $total_tickets_count,
        "open_tickets_count" => $open_tickets_count,
        "waiting_tickets_count" => $waiting_tickets_count,
        "close_tickets_count" => $close_tickets_count,
        "tickets" => array_reverse($tickets)
    );
} else {
    $result = array("status" => 'false');
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
