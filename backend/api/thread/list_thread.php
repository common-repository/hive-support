<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin') && isset($_REQUEST['ticket_id'])) {

    $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);

    $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
    $ticket_number = $this->base_admin->settings->updateTicketSettings($ticket_id, "ticket_number");
    $created_at = $this->base_admin->settings->updateTicketSettings($ticket_id, "created_at");
    $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
    $user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "user_id");
    $responses = $this->base_admin->settings->updateTicketSettings($ticket_id, "responses");
    $form_data = $this->base_admin->settings->updateTicketSettings($ticket_id, "form_data");
    $subject = $this->base_admin->settings->updateTicketSettings($ticket_id, "subject");
    $main_msg = $this->base_admin->settings->updateTicketSettings($ticket_id, "message");
    $agent_user_name = "Not Assigned";
    $agent_user_img = HIVE_LITE_SUPPORT_URL . "/assets/img/tickets/no_user_img.png";
    $user_name = "Anonymous";
    $user_img = "";
    $user_email = "";
    $modified_at = $created_at;




    $user_info = get_userdata($user_id);
    if ($user_info != false) {
        $user_name = $this->base_admin->utils->getUserDisplayName($user_info);
        $user_img = get_avatar_url($user_id);
        $user_email = $user_info->user_email;
    }

    $agent_user_info = get_userdata($agent_user_id);
    if ($agent_user_info != false) {
        $agent_user_name = $this->base_admin->utils->getUserDisplayName($agent_user_info);
        $agent_user_img = get_avatar_url($agent_user_id);
    }


    $responses_arr = array();
    /* ================== Get Initial Ticket Form Field Data ============== */
    $initial_form_field_msg = "";
    /* Get Form Fields from DB to check in future */
    $form_fields = $this->base_admin->settings->updateSettings("ticket_fields");
    $form_fields = ($form_fields == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $form_fields;
    $form_fields_obj = json_decode($form_fields, false);
    $form_fields_array = $this->base_admin->utils->extractFormFieldsJSONtoArray($form_fields_obj);

    $entries_obj = json_decode($form_data, false);
    foreach ($entries_obj as $single_field) {
        $field_in_db = array();
        $field_label = "";
        $field_value = $single_field->field_value;
        /* Get Field in DB */
        foreach ($form_fields_array as $field) {
            if ($single_field->field_id == $field->field_id) {
                $field_in_db = $field;
                break;
            }
        }
        /* Get Field Label */
        $field_label = isset($field_in_db->label) ? $field_in_db->label : "";
        /* If checkbox field, remove ::hive_lite_support_separator:: */
        if ($field_in_db->slug == "checkbox") {
            $field_value = str_replace("::hive_lite_support_separator::", "<br>", $field_value);
        }
        /* If textarea field, replace newline with br */
        if ($field_in_db->slug == "text_area" || $field_in_db->slug == "message") {
            $field_value = nl2br($field_value);
        }
        if ($initial_form_field_msg != "") {
            $initial_form_field_msg .= "<br>";
        }
        $initial_form_field_msg .= "<strong>" . $field_label . "</strong><br>" . $field_value;
    }
    $response_user_info = get_userdata($user_id);
    $response_user_name = ($response_user_info) ? $this->base_admin->utils->getUserDisplayName($response_user_info) : "Anonymous";
    $response_user_img = get_avatar_url($user_id);
    $responses_arr[] = array(
        "response_id" => "0",
        "user_id" => $user_id,
        "user_name" => $response_user_name,
        "user_img" => $response_user_img,
        "user_title" => "Customer",
        "created_at" => $this->base_admin->utils->get_gmt_time_different($created_at),
        "msg" => $initial_form_field_msg,
        "extra_doc" => [],
    );
    /* ================== Get Initial Ticket Form Field Data ============== */



    $responses_obj = json_decode($responses, false);
    foreach ($responses_obj as $single_response) {
        $response_id = $single_response->response_id;
        $response_created_by = $single_response->user_id;
        $response_created_at = $single_response->created_at;
        $modified_at = $response_created_at;
        $response_msg = $single_response->msg;
        $response_extra_doc = $single_response->extra_doc;

        $response_user_info = get_userdata($response_created_by);
        $response_user_name = ($response_user_info) ? $this->base_admin->utils->getUserDisplayName($response_user_info) : "Anonymous";
        $response_user_img = get_avatar_url($response_created_by);

        $user_title = "";
        if ($user_id == $response_created_by) {
            $user_title = "Customer";
        } else {
            $user_title = $this->base_admin->settings->updateAgentSettings($response_created_by, "title");
            if ($user_title == Null) {
                if (in_array("administrator", $response_user_info->roles)) {
                    $user_title = "Administrator";
                } else {
                    $user_title = "Support Staff";
                }
            }
        }

        $responses_arr[] = array(
            "response_id" => $response_id,
            "user_id" => $response_created_by,
            "user_name" => $response_user_name,
            "user_img" => $response_user_img,
            "user_title" => $user_title,
            "created_at" => $this->base_admin->utils->get_gmt_time_different($response_created_at),
            "msg" => $response_msg,
            "extra_doc" => ($response_extra_doc == null) ? [] : $response_extra_doc,
        );
    }

    // List Customer Orders
    $orders_list = array();
    $orders_woo = $this->base_admin->utils->getCustomersWooOrders($user_id);
    $orders_edd = $this->base_admin->utils->getCustomersEddOrders($user_id);
    $orders_list = array_merge($orders_woo, $orders_edd);


    // List Customer Tickets
    $other_tickets = $this->base_admin->settings->getAllTickets($user_id, $ticket_id);

    $result = array(
        "status" => 'true',
        "ticket_id" => $ticket_id,
        "ticket_number" => $ticket_number,
        "ticket_status" => $status,
        "agent_user_id" => $agent_user_id,
        "agent_user_name" => $agent_user_name,
        "agent_user_img" => $agent_user_img,
        "user_id" => $user_id,
        "user_name" => $user_name,
        "user_img" => $user_img,
        "user_email" => $user_email,
        "orders" => $orders_list,
        "other_tickets" => $other_tickets,
        "created_at" => get_date_from_gmt($created_at, 'd M Y'),
        "modified_at" => $this->base_admin->utils->get_gmt_time_different($modified_at),
        "subject" => $subject,
        "responses" => array_reverse($responses_arr)
    );
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
