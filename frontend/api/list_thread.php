<?php


if (isset($_REQUEST['security']) && isset($_REQUEST['ticket_id']) && is_user_logged_in()) {

    if (check_ajax_referer('hive_lite_support_client_hashkey', 'security') == 1) {


        $ticket_id = sanitize_text_field($_REQUEST['ticket_id']);
        /* Get Current User's Info */
        $submitting_user = wp_get_current_user();
        $submitting_user_id = (int) $submitting_user->ID;

        $status = $this->base_client->settings->updateTicketSettings($ticket_id, "status");
        $created_at = $this->base_client->settings->updateTicketSettings($ticket_id, "created_at");
        $user_id = $this->base_client->settings->updateTicketSettings($ticket_id, "user_id");
        $responses = $this->base_client->settings->updateTicketSettings($ticket_id, "responses");
        $form_data = $this->base_client->settings->updateTicketSettings($ticket_id, "form_data");
        $subject = $this->base_client->settings->updateTicketSettings($ticket_id, "subject");
        $main_msg = $this->base_client->settings->updateTicketSettings($ticket_id, "message");

        if ($user_id == $submitting_user_id) {

            $responses_arr = array();


            /* ================== Get Initial Ticket Form Field Data ============== */
            $initial_form_field_msg = "";
            /* Get Form Fields from DB to check in future */
            $form_fields = $this->base_client->settings->updateSettings("ticket_fields");
            $form_fields = ($form_fields == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $form_fields;
            $form_fields_obj = json_decode($form_fields, false);
            $form_fields_array = $this->base_client->utils->extractFormFieldsJSONtoArray($form_fields_obj);

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
            $response_user_name = ($response_user_info) ? $this->base_client->utils->getUserDisplayName($response_user_info) : "Anonymous";
            $response_user_img = get_avatar_url($user_id);

            $responses_arr[] = array(
                "response_id" => "0",
                "user_id" => $user_id,
                "user_name" => $response_user_name,
                "user_img" => $response_user_img,
                "user_title" => "You",
                "created_at" => $this->base_client->utils->get_gmt_time_different($created_at),
                "msg" => $initial_form_field_msg
            );
            /* ================== Get Initial Ticket Form Field Data ============== */



            $responses_obj = json_decode($responses, false);
            foreach ($responses_obj as $single_response) {
                $response_id = $single_response->response_id;
                $response_created_by = $single_response->user_id;
                $response_created_at = $single_response->created_at;
                $response_msg = $single_response->msg;

                $response_user_info = get_userdata($response_created_by);
                $response_user_name = ($response_user_info) ? $this->base_client->utils->getUserDisplayName($response_user_info) : "Anonymous";
                $response_user_img = get_avatar_url($response_created_by);

                $user_title = "";
                if ($user_id == $response_created_by) {
                    $user_title = "You";
                } else {
                    $user_title = $this->base_client->settings->updateAgentSettings($response_created_by, "title");
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
                    "created_at" => $this->base_client->utils->get_gmt_time_different($response_created_at),
                    "msg" => $response_msg
                );
            }

            $result = array(
                "status" => 'true',
                "ticket_status" => $status,
                "responses" => array_reverse($responses_arr)
            );
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
