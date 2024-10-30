<?php


if (
    isset($_REQUEST['security']) && isset($_REQUEST['ticket_id'])
    && isset($_REQUEST['type']) && isset($_REQUEST['msg']) && is_user_logged_in()
) {

    if (check_ajax_referer('hive_lite_support_client_hashkey', 'security') == 1) {


        $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
        $type = sanitize_text_field($_REQUEST['type']);
        $msg = wp_filter_post_kses($_REQUEST['msg']);
        /* Get Current User's Info */
        $submitting_user = wp_get_current_user();
        $submitting_user_id = (int) $submitting_user->ID;
        $gmt_time = gmdate("Y/m/d H:i:s", time() + date("Z"));

        $status = $this->base_client->settings->updateTicketSettings($ticket_id, "status");
        $user_id = $this->base_client->settings->updateTicketSettings($ticket_id, "user_id");
        $agent_user_id = $this->base_client->settings->updateTicketSettings($ticket_id, "agent_user_id");
        $responses = $this->base_client->settings->updateTicketSettings($ticket_id, "responses");

        if ($user_id == $submitting_user_id) {

            $responses_obj = json_decode($responses, false);
            $msg = nl2br($msg);
            $responses_obj[] = array(
                "response_id" => time() . "_" . uniqid(),
                "user_id" => $submitting_user_id,
                "created_at" => $gmt_time,
                "msg" => $msg,
            );
            $this->base_client->settings->updateTicketSettings($ticket_id, "responses", addslashes(json_encode($responses_obj,  JSON_UNESCAPED_UNICODE)));

            /* Record to Activities */
            $this->base_client->settings->recordActivity(
                "customer_ticket_replied",
                $submitting_user_id,
                $ticket_id
            );

            if ($type == "reply_close") {
                $this->base_client->settings->updateTicketSettings($ticket_id, "status", "close");
            }

            /* Email Notification */
            if ($agent_user_id != "0") {
                $settings = array();

                $settings["enable_email_to_staff_on_customer_reply"] = $this->base_client->settings->updateSettings("enable_email_to_staff_on_customer_reply");
                $settings["enable_email_to_staff_on_customer_reply"] = ($settings["enable_email_to_staff_on_customer_reply"] == Null) ? "1" : $settings["enable_email_to_staff_on_customer_reply"];

                $settings["email_to_staff_on_customer_reply_subject"] = $this->base_client->settings->updateSettings("email_to_staff_on_customer_reply_subject");
                $settings["email_to_staff_on_customer_reply_subject"] = ($settings["email_to_staff_on_customer_reply_subject"] == Null) ? "{ticket_id} - New Reply Received from {customer_name}" : $settings["email_to_staff_on_customer_reply_subject"];

                $settings["email_to_staff_on_customer_reply_body"] = $this->base_client->settings->updateSettings("email_to_staff_on_customer_reply_body");
                $settings["email_to_staff_on_customer_reply_body"] = ($settings["email_to_staff_on_customer_reply_body"] == Null) ? "Hello {staff_name}\nYou just received a reply from {customer_name} on Ticket {ticket_id}.\nBack to your support portal to check it out." : $settings["email_to_staff_on_customer_reply_body"];

                if ($settings["enable_email_to_staff_on_customer_reply"] == "1") {
                    $agent_info = get_userdata($agent_user_id);
                    if ($agent_info != false) {
                        $from_name = get_bloginfo('name');
                        $from_email = get_bloginfo('admin_email');
                        $to_email = $agent_info->user_email;
                        $subject = $this->base_client->utils->emailShortCodeParser($ticket_id, $user_id, $agent_user_id, $settings["email_to_staff_on_customer_reply_subject"]);
                        $body = nl2br($this->base_client->utils->emailShortCodeParser($ticket_id, $user_id, $agent_user_id, $settings["email_to_staff_on_customer_reply_body"]));
                        $headers[] = 'Content-Type: text/html; charset=UTF-8';
                        //$headers[] = 'From: '.$from_name. ' <' . $from_email . '>';
                        wp_mail($to_email, $subject, $body, $headers);
                    }
                }
            }


            $result = array("status" => 'true');
        } else {
            $result = array("status" => 'false');
        }
    } else {
        $result = array("status" => 'false');
    }
} else {
    $result = array("status" => 'false');
}


echo json_encode($result,  JSON_UNESCAPED_UNICODE);
