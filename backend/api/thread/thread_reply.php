<?php

$result = array();

/* Check if user has admin capabilities */
if (
    current_user_can('hive_support_access_plugin') && isset($_REQUEST['ticket_id'])
    && isset($_REQUEST['type']) && isset($_REQUEST['msg'])
) {

    $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
    $type = sanitize_text_field($_REQUEST['type']);
    $msg = wp_filter_post_kses($_REQUEST['msg']);
    $extra_doc = $_REQUEST['extra_doc'];

    /* Get Current User's Info */
    $submitting_user = wp_get_current_user();
    $submitting_user_id = (int) $submitting_user->ID;
    $gmt_time = gmdate("Y/m/d H:i:s", time() + date("Z"));

    $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id", $submitting_user_id);
    $this->base_admin->settings->updateTicketSettings($ticket_id, "status", "open");
    $user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "user_id");
    $responses = $this->base_admin->settings->updateTicketSettings($ticket_id, "responses");

    $responses_obj = json_decode($responses, false);
    $msg = nl2br($msg);
    $responses_obj[] = array(
        "response_id" => time() . "_" . uniqid(),
        "user_id" => $submitting_user_id,
        "created_at" => $gmt_time,
        "msg" => $msg,
        "extra_doc" => $extra_doc,
    );
    $this->base_admin->settings->updateTicketSettings($ticket_id, "responses", addslashes(json_encode($responses_obj,  JSON_UNESCAPED_UNICODE)));

    /* Record to Activities */
    $this->base_admin->settings->recordActivity(
        "staff_ticket_replied",
        $submitting_user_id,
        $ticket_id
    );

    if ($type == "reply_close") {
        $this->base_admin->settings->updateTicketSettings($ticket_id, "status", "close");
        /* Record to Activities */
        $this->base_admin->settings->recordActivity(
            "staff_ticket_closed",
            $submitting_user_id,
            $ticket_id
        );
    }



    /* Email Notification */
    $settings = array();
    $settings["enable_email_to_customer_on_staff_reply"] = $this->base_admin->settings->updateSettings("enable_email_to_customer_on_staff_reply");
    $settings["enable_email_to_customer_on_staff_reply"] = ($settings["enable_email_to_customer_on_staff_reply"] == Null) ? "1" : $settings["enable_email_to_customer_on_staff_reply"];

    $settings["email_to_customer_on_staff_reply_subject"] = $this->base_admin->settings->updateSettings("email_to_customer_on_staff_reply_subject");
    $settings["email_to_customer_on_staff_reply_subject"] = ($settings["email_to_customer_on_staff_reply_subject"] == Null) ? "#{ticket_id} - New Reply Received from {staff_name}" : $settings["email_to_customer_on_staff_reply_subject"];

    $settings["email_to_customer_on_staff_reply_body"] = $this->base_admin->settings->updateSettings("email_to_customer_on_staff_reply_body");
    $settings["email_to_customer_on_staff_reply_body"] = ($settings["email_to_customer_on_staff_reply_body"] == Null) ? "Hello {customer_name}\nYou just received a reply from {staff_name} on Ticket #{ticket_id}.\nBack to your support portal to check it out." : $settings["email_to_customer_on_staff_reply_body"];



    if ($settings["enable_email_to_customer_on_staff_reply"] == "1") {
        $customer_info = get_userdata($user_id);
        if ($customer_info != false) {
            $from_name = get_bloginfo('name');
            $from_email = get_bloginfo('admin_email');
            $to_email = $customer_info->user_email;
            $subject = $this->base_admin->utils->emailShortCodeParser($ticket_id, $user_id, $submitting_user_id, $settings["email_to_customer_on_staff_reply_subject"]);
            $body = nl2br($this->base_admin->utils->emailShortCodeParser($ticket_id, $user_id, $submitting_user_id, $settings["email_to_customer_on_staff_reply_body"]));
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            //$headers[] = 'From: '.$from_name. ' <' . $from_email . '>';
            wp_mail($to_email, $subject, $body, $headers);
        }
    }



    $result = array("status" => 'true');
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
