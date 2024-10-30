<?php


if (isset($_REQUEST['security']) && is_user_logged_in()) {

    if (check_ajax_referer('hive_lite_support_client_hashkey', 'security') == 1) {

        $tickets = array();

        /* Get Current User's Info */
        $submitting_user = wp_get_current_user();
        $submitting_user_id = (int) $submitting_user->ID;

        $list_entries = $this->base_client->settings->listAllTickets();
        foreach ($list_entries as $single_entry) {
            $ticket_id = $single_entry['ticket_id'];
            $status = $this->base_client->settings->updateTicketSettings($ticket_id, "status");
            $ticket_number = $this->base_client->settings->updateTicketSettings($ticket_id, "ticket_number");
            $created_at = $this->base_client->settings->updateTicketSettings($ticket_id, "created_at");
            $user_id = $this->base_client->settings->updateTicketSettings($ticket_id, "user_id");
            $subject = $this->base_client->settings->updateTicketSettings($ticket_id, "subject");
            $main_msg = $this->base_client->settings->updateTicketSettings($ticket_id, "message");
            $responses = $this->base_client->settings->updateTicketSettings($ticket_id, "responses");
            $form_data = $this->base_client->settings->updateTicketSettings($ticket_id, "form_data");
            $modified_at = "";
            $last_msg = "";
            $last_msg_user_id = "";

            if ($user_id != $submitting_user_id) {
                continue;
            }

            $responses_obj = json_decode($responses, false);
            if (sizeof($responses_obj) > 0) {
                $responses_obj_reverse = array_reverse($responses_obj, true);
                foreach ($responses_obj_reverse as $single_response) {
                    $modified_at = $single_response->created_at;
                    $last_msg = $single_response->msg;
                    $last_msg_user_id = $single_response->user_id;
                    break;
                }
            } else {
                $modified_at = $created_at;
                $last_msg = $main_msg;
                $last_msg_user_id = $user_id;
            }



            $tickets[] = array(
                "ticket_id" => $ticket_id,
                "ticket_number" => $ticket_number,
                "status" => $status,
                "created_at" => get_date_from_gmt($created_at, 'd M Y'),
                "modified_at" => $this->base_client->utils->get_gmt_time_different($modified_at),
                "subject" => $subject,
                "last_msg" => strip_tags($last_msg),
                "last_msg_user_img" => get_avatar_url($last_msg_user_id),
            );
        }

        $result = array("status" => 'true', "tickets" => array_reverse($tickets));
    } else {
        $result = array("status" => 'false 1');
    }
} else {
    $result = array("status" => 'false 2');
}


echo json_encode($result,  JSON_UNESCAPED_UNICODE);
