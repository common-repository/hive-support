<?php


if (isset($_REQUEST['security']) && is_user_logged_in()) {

    if (check_ajax_referer('hive_lite_support_client_hashkey', 'security') == 1) {






        /* Get Form Fields from DB to check in future */
        $form_fields = $this->base_client->settings->updateSettings("ticket_fields");
        $form_fields = ($form_fields == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $form_fields;

        $form_fields_obj = json_decode($form_fields, false);
        $form_fields_array = $this->base_client->utils->extractFormFieldsJSONtoArray($form_fields_obj);


        /* Get Current User's Info */
        $submitting_user = wp_get_current_user();
        $submitting_user_id = (int) $submitting_user->ID;
        $gmt_time = gmdate("Y/m/d H:i:s", time() + date("Z"));
        $subject = "";
        $main_msg = "";

        /* Get Form Field Values */
        $cleaned_field_submissions = array();
        $submissions_obj = array();


        $global_requests = isset($_REQUEST) ? (array) $_REQUEST : array();
        $global_requests = $this->base_client->utils->sanitize_global_requests('esc_attr', $global_requests);


        foreach ($global_requests as $field_id => $field_value) {
            if (strpos($field_id, 'field_id_') === 0) {
                $field_id = str_replace("field_id_", "", $field_id);
                $submissions_obj[] = array("field_id" => $field_id, "field_value" => $field_value);
            }
        }


        /* Verify Form Field Values */
        $is_everything_ok = true;
        if (is_array($submissions_obj)) {
            if (sizeof($submissions_obj) > 0) {
                foreach ($submissions_obj as $field) {
                    $field = (object) $field;
                    /* Check Weather the field_id is valid */
                    $is_field_id_valid = false;
                    foreach ($form_fields_array as $field_in_db) {
                        if ($field->field_id == $field_in_db->field_id) {

                            /* Store Subject and First Msg */
                            if ($field_in_db->slug == "subject") {
                                $subject = $field->field_value;
                            }
                            if ($field_in_db->slug == "message") {
                                $main_msg = $field->field_value;
                            }

                            /* Check Validation */
                            /* Required Field Check */
                            $is_required = isset($field_in_db->required) ? $field_in_db->required : "0";
                            if (is_array($field->field_value)) { // For File Input
                                if ($is_required == "1" && sizeof($field->field_value) == 0) {
                                    $is_everything_ok = false;
                                }
                            } else {
                                if ($is_required == "1" && strlen($field->field_value) == 0) {
                                    $is_everything_ok = false;
                                }
                            }

                            /* Enter into Clean Submission Array */
                            $cleaned_field_submissions[] = array(
                                "field_id" => $field->field_id,
                                "field_value" => $field->field_value,
                            );

                            $is_field_id_valid = true;
                            break;
                        }
                    }
                    if (!$is_field_id_valid) {
                        $is_everything_ok = false;
                    }
                }
            } else {
                $is_everything_ok = false;
            }
        } else {
            $is_everything_ok = false;
        }


        /* Record Entries in DB */
        if ($is_everything_ok) {
            $ticket_id = $this->base_client->settings->createNewTicket();
            $this->base_client->settings->updateTicketSettings($ticket_id, "status", "waiting");
            $this->base_client->settings->updateTicketSettings($ticket_id, "ticket_number", 1000 + $this->base_client->settings->countTickets());
            $this->base_client->settings->updateTicketSettings($ticket_id, "created_at", $gmt_time);

            /*  Automation */
            $agent_user_id = "0";
            $get_automation_data = $this->base_client->settings->getAutomation();
            if (sizeof($get_automation_data) > 0) {
                foreach ($get_automation_data as $single_automation_data) {
                    //---------- On Ticket Created -------------------
                    if ($single_automation_data != Null && $single_automation_data['trigger_id'] == "1") {
                        if ($single_automation_data != Null && $single_automation_data['match_any'] == "1") {
                            $conditions = ($single_automation_data != Null) ? $single_automation_data['condition'] : [];
                            if (sizeof($conditions) > 0) {
                                foreach ($conditions as $condition) {
                                    if (sizeof($submissions_obj) > 0) {
                                        foreach ($submissions_obj as $single_obj) {
                                            if ($condition['field_id'] == $single_obj['field_id'] && strstr($single_obj['field_value'], $condition['field_value'])) {
                                                $agent_user_id = $single_automation_data['agent_id'];
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif ($single_automation_data != Null && $single_automation_data['match_all'] == "1") {
                            // get all automation conditions
                            $conditions = ($single_automation_data != Null) ? $single_automation_data['condition'] : [];
                            if (sizeof($conditions) > 0) {
                                $condition_length = sizeof($conditions);
                                $match_conditions = 0;
                                foreach ($conditions as $condition) {

                                    if (sizeof($submissions_obj) > 0) {
                                        foreach ($submissions_obj as $single_obj) {
                                            if ($condition['field_id'] == $single_obj['field_id'] && strstr($single_obj['field_value'], $condition['field_value'])) {
                                                $match_conditions += 1;
                                            }
                                        }
                                    }
                                    if ($condition_length == $match_conditions) {
                                        $agent_user_id = $single_automation_data['agent_id'];
                                    }
                                }
                            }
                        }
                    }
                    //---------- On Ticket Created -------------------
                }
            }
            /*  Automation */

            $this->base_client->settings->updateTicketSettings($ticket_id, "agent_user_id", $agent_user_id);
            $this->base_client->settings->updateTicketSettings($ticket_id, "user_id", $submitting_user_id);
            if ($subject != "") {
                $this->base_client->settings->updateTicketSettings($ticket_id, "subject", $subject);
            }
            if ($main_msg != "") {
                $this->base_client->settings->updateTicketSettings($ticket_id, "message", $main_msg);
            }
            $this->base_client->settings->updateTicketSettings($ticket_id, "responses", addslashes(json_encode(array(),  JSON_UNESCAPED_UNICODE)));
            $this->base_client->settings->updateTicketSettings($ticket_id, "form_data", addslashes(json_encode($cleaned_field_submissions,  JSON_UNESCAPED_UNICODE)));

            /* Record to Activities */
            $this->base_client->settings->recordActivity(
                "customer_ticket_created",
                $submitting_user_id,
                $ticket_id
            );

            /* Email Notification */
            $settings = array();

            $settings["enable_email_to_admin_on_ticket_created"] = $this->base_client->settings->updateSettings("enable_email_to_admin_on_ticket_created");
            $settings["enable_email_to_admin_on_ticket_created"] = ($settings["enable_email_to_admin_on_ticket_created"] == Null) ? "1" : $settings["enable_email_to_admin_on_ticket_created"];

            $settings["email_to_admin_on_ticket_created_subject"] = $this->base_client->settings->updateSettings("email_to_admin_on_ticket_created_subject");
            $settings["email_to_admin_on_ticket_created_subject"] = ($settings["email_to_admin_on_ticket_created_subject"] == Null) ? "#{ticket_id} - New Support Ticket Received" : $settings["email_to_admin_on_ticket_created_subject"];

            $settings["email_to_admin_on_ticket_created_body"] = $this->base_client->settings->updateSettings("email_to_admin_on_ticket_created_body");
            $settings["email_to_admin_on_ticket_created_body"] = ($settings["email_to_admin_on_ticket_created_body"] == Null) ? "Hello Admin\nA new support ticket has been placed in your site.\nBack to your support portal to check it out." : $settings["email_to_admin_on_ticket_created_body"];

            if ($settings["enable_email_to_admin_on_ticket_created"] == "1") {
                $agent_user_id = $this->base_client->settings->updateTicketSettings($ticket_id, "agent_user_id");
                $from_name = get_bloginfo('name');
                $from_email = get_bloginfo('admin_email');
                $to_email = get_bloginfo('admin_email');
                $subject = $this->base_client->utils->emailShortCodeParser($ticket_id, $submitting_user_id, $agent_user_id, $settings["email_to_admin_on_ticket_created_subject"]);
                $body = nl2br($this->base_client->utils->emailShortCodeParser($ticket_id, $submitting_user_id, $agent_user_id, $settings["email_to_admin_on_ticket_created_body"]));
                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                //$headers[] = 'From: '.$from_name. ' <' . $from_email . '>';
                wp_mail($to_email, $subject, $body, $headers);
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
