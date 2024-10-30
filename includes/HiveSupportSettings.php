<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportSettings')) {
    class HiveSupportSettings
    {

        public $base_admin;

        function __construct($base_admin)
        {

            $this->base_admin = $base_admin;

            $defaultOption = array();
            /* ===== Agents ======== */

            if (!get_option("hive_lite_support_ticketfield_settings")) {
                update_option(
                    "hive_lite_support_ticketfield_settings",
                    json_encode(
                        array(),
                        JSON_UNESCAPED_SLASHES
                    )
                );
            }

            if (!get_option("hs_home_tab_data")) {
                $defaultOption = [
                    'chat_window_home_subtitle' => "how can we help?",
                    'chat_window_home_title' => "Hi there",
                    'position_title' => "Hive Support Team",
                    'enable_ai_chat_form' => true,
                    'enable_article_list' => true,
                    'enable_chat_widget' => true
                ];
                update_option('hs_home_tab_data', wp_json_encode($defaultOption));
            }


            if (!get_option("hive_lite_support_email_msgid")) {
                update_option('hive_lite_support_email_msgid', $defaultOption);
            }

            if (!get_option("hive_lite_support_email_tickets")) {
                update_option('hive_lite_support_email_tickets', $defaultOption);
            }
            if (!get_option("hive_lite_support_agents")) {
                update_option('hive_lite_support_agents', $defaultOption);
            }
            if (!get_option("hive_lite_support_agent_settings")) {
                update_option('hive_lite_support_agent_settings', $defaultOption);
            }



            /* ===== Tickets ======== */
            if (!get_option("hive_lite_support_tickets")) {
                update_option('hive_lite_support_tickets', $defaultOption);
            }
            if (!get_option("hive_lite_support_ticket_settings")) {
                update_option('hive_lite_support_ticket_settings', $defaultOption);
            }

            if (!get_option("hive_lite_support_ticket_todos")) {
                update_option('hive_lite_support_ticket_todos', $defaultOption);
            }

            /* ===== Responses ======== */
            if (!get_option("hive_lite_support_responses")) {
                update_option('hive_lite_support_responses', $defaultOption);
            }

            /* ===== Activities ======== */
            if (!get_option("hive_lite_support_activities")) {
                update_option('hive_lite_support_activities', $defaultOption);
            }


            /* ===== Automation ======== */
            if (!get_option("hive_lite_support_automation")) {
                update_option('hive_lite_support_automation', $defaultOption);
            }


            /* ===== Settings ======== */
            if (!get_option("hive_lite_support_settings")) {
                update_option('hive_lite_support_settings', $defaultOption);
            }

            /* ===== Settings ======== */

            if (!get_option("hive_lite_support_intrigations")) {
                update_option("hive_lite_support_intrigations", json_encode(
                    [
                        "whatsapp" => [
                            "enable" => false,
                            "settings" => ["webhookurl" => "sas"],
                            "triggers" => [
                                "ticket_created" => false,
                                "ticket_closed" => false,
                                "ticket_replied" => false,
                            ],
                        ],
                        "telegram" => [
                            "enable" => false,
                            "settings" => ["webhookurl" => "sas"],
                            "triggers" => [
                                "ticket_created" => false,
                                "ticket_closed" => false,
                                "ticket_replied" => false,

                            ],
                        ],
                        "slack" => [
                            "enable" => false,
                            "settings" => ["webhookurl" => "sas"],
                            "triggers" => [
                                "ticket_created" => false,
                                "ticket_closed" => false,
                                "ticket_replied" => false,

                            ],
                        ],
                        "discord" => [
                            "enable" => false,
                            "settings" => ["webhookurl" => "sas"],
                            "triggers" => [
                                "ticket_created" => false,
                                "ticket_closed" => false,
                                "ticket_replied" => false,

                            ],
                        ],
                        "chatgpt" => [
                            "enable" => false,
                            "settings" => ["webhookurl" => "sas"],
                            "triggers" => [
                                "ticket_created" => false,
                                "ticket_closed" => false,
                                "ticket_replied" => false,

                            ],
                        ]
                    ],
                    JSON_UNESCAPED_SLASHES
                ));
            }
        }




        /* ****************** Settings Operations ****************** */

        public function updateSettings($key, $value = "<<hs_empty_value>>")
        {
            $exits = false;
            $exitingValue = Null;
            $dataAgentSettings = get_option("hive_lite_support_settings");
            $dataNewAgentSettings = array();
            foreach ($dataAgentSettings as $singleSettings) {
                if (isset($singleSettings['key'])) {
                    if ($singleSettings['key'] == $key) {
                        $exits = true;
                        $exitingValue = $singleSettings['value'];
                        $singleSettings['value'] = ($value != "<<hs_empty_value>>") ? $value : $singleSettings['value'];
                    }
                }
                if ($value != "<<hs_empty_value>>") {
                    $dataNewAgentSettings[] = $singleSettings;
                }
            }
            if ($exits && $value != "<<hs_empty_value>>") {
                update_option('hive_lite_support_settings', $dataNewAgentSettings);
            } else if (!$exits && $value != "<<hs_empty_value>>") {
                $dataNewAgentSettings[] = array("key" => $key, "value" => $value);
                update_option('hive_lite_support_settings', $dataNewAgentSettings);
            } else if ($exits && $value == "<<hs_empty_value>>") {
                return stripslashes($exitingValue);
            } else {
                return Null;
            }
        }




        /* ****************** Activities Operations ****************** */

        /*
         * customer_ticket_created => user_id:customer_id, value1:ticket_id
         * customer_ticket_replied => user_id:customer_id, value1:ticket_id
         * staff_ticket_replied => user_id:staff_id, value1:ticket_id
         * staff_ticket_closed => user_id:staff_id, value1:ticket_id
         * staff_ticket_opened => user_id:staff_id, value1:ticket_id
         * staff_assigned => user_id:who_is_assigning, value1:ticket_id, value2:who_is_assigned
         */

        public function recordActivity($type, $user_id, $value1 = "", $value2 = "")
        {
            $dataActivities = get_option("hive_lite_support_activities");
            $dataActivities[] = array(
                "type" => $type,
                "time" => gmdate("Y/m/d H:i:s", time() + date("Z")),
                "user_id" => $user_id,
                "value1" => $value1,
                "value2" => $value2,
            );
            update_option('hive_lite_support_activities', $dataActivities);
        }

        public function listAllActivities()
        {
            $dataActivities = get_option("hive_lite_support_activities");
            return is_array($dataActivities) ? $dataActivities : Null;
        }



        /* ****************** Tickets Operations ****************** */

        public function createNewTicket()
        {
            $dataTickets = get_option("hive_lite_support_tickets");
            $ticket_id = $this->generateTicketID($dataTickets);
            $dataTickets[] = array("ticket_id" => $ticket_id);
            update_option('hive_lite_support_tickets', $dataTickets);
            return $ticket_id;
        }

        public function countTickets()
        {
            $dataTickets = get_option("hive_lite_support_tickets");
            if (is_array($dataTickets)) {
                return sizeof($dataTickets);
            }
            return 0;
        }

        public function listAllTickets()
        {
            $dataTickets = get_option("hive_lite_support_tickets");
            return is_array($dataTickets) ? $dataTickets : Null;
        }

        public function getAllTickets($user_id, $ticket_id)
        {
            $data = get_option("hive_lite_support_ticket_settings");
            $dataNew = array();
            $ticket_id_new = "";
            $user_id_new = "";
            $subject = "";
            $created_at = "";
            foreach ($data as $singleData) {
                if (isset($singleData['key']) && $singleData['key'] == "user_id") {
                    if ($singleData['value'] == $user_id && $singleData['ticket_id'] != $ticket_id) {
                        $ticket_id_new = $singleData['ticket_id'];
                        $user_id_new = $singleData['value'];
                        continue;
                    }
                }
                if (isset($singleData['key']) && $singleData['key'] == "subject" && $singleData['ticket_id'] == $ticket_id_new) {
                    $subject = $singleData['value'];
                    continue;
                }
                if (isset($singleData['key']) && $singleData['key'] == "created_at" && $singleData['ticket_id'] == $ticket_id_new) {
                    $created_at = $singleData['value'];
                    continue;
                }
                if (isset($singleData['key']) && $singleData['key'] == "form_data" && $singleData['ticket_id'] == $ticket_id_new) {

                    $dataNew[] = array(
                        "ticket_id" => $ticket_id_new,
                        "user_id" => $user_id_new,
                        "subject" => $subject,
                        "date" => get_date_from_gmt($created_at, 'd M Y')
                    );
                }
            }

            return $dataNew;
        }

        public function updateTicketSettings($ticket_id, $key, $value = "<<hs_empty_value>>")
        {
            $exits = false;
            $exitingValue = Null;
            $dataTicketSettings = get_option("hive_lite_support_ticket_settings");
            $dataNewTicketSettings = array();
            foreach ($dataTicketSettings as $singleSettings) {
                if (isset($singleSettings['ticket_id']) && isset($singleSettings['key'])) {
                    if ($singleSettings['ticket_id'] == $ticket_id && $singleSettings['key'] == $key) {
                        $exits = true;
                        $exitingValue = $singleSettings['value'];
                        $singleSettings['value'] = ($value != "<<hs_empty_value>>") ? $value : $singleSettings['value'];
                    }
                }
                if ($value != "<<hs_empty_value>>") {
                    $dataNewTicketSettings[] = $singleSettings;
                }
            }
            if ($exits && $value != "<<hs_empty_value>>") {
                update_option('hive_lite_support_ticket_settings', $dataNewTicketSettings);
            } else if (!$exits && $value != "<<hs_empty_value>>") {
                $dataNewTicketSettings[] = array("ticket_id" => $ticket_id, "key" => $key, "value" => $value);
                update_option('hive_lite_support_ticket_settings', $dataNewTicketSettings);
            } else if ($exits && $value == "<<hs_empty_value>>") {
                return stripslashes($exitingValue);
            } else {
                return Null;
            }
        }

        public function generateTicketID($resultData)
        {
            $exits = false;
            $length = 9;
            $key = substr(str_shuffle(str_repeat($x = '123456789', ceil($length / strlen($x)))), 1, $length);
            foreach ($resultData as $singleResult) {
                if (isset($singleResult['ticket_id'])) {
                    if ($singleResult['ticket_id'] == $key) {
                        $exits = true;
                    }
                }
            }
            return (!$exits) ? $key : $this->generateTicketID($resultData);
        }


        /* ****************** TO DO Operations ****************** */

        public function createNewTODO($ticket_id, $agent_id, $todo_text)
        {
            $dataToDos = get_option("hive_lite_support_ticket_todos");
            $todo_id = $this->generateTODOID($dataToDos);
            $dataToDos[] = array("todo_id" => $todo_id, "ticket_id" => $ticket_id, "agent_id" => $agent_id, "data" => $todo_text);

            update_option('hive_lite_support_ticket_todos', $dataToDos);
            return true;
        }


        public function listAllToDo($ticket_id)
        {
            $dataTODO = get_option("hive_lite_support_ticket_todos");
            $dataNewTODO = array();
            foreach ($dataTODO as $singleToDo) {
                if ($singleToDo['ticket_id'] == $ticket_id) {
                    $dataNewTODO[] = $singleToDo;
                }
            }

            return $dataNewTODO;
        }


        public function isToDoExits($todo_id, $ticket_id)
        {
            $dataTODO = get_option("hive_lite_support_ticket_todos");
            $dataTODO = is_array($dataTODO) ? $dataTODO : Null;
            if ($dataTODO != Null) {
                foreach ($dataTODO as $single_data) {
                    if ($single_data['todo_id'] == $todo_id && $single_data['ticket_id'] == $ticket_id) {
                        return True;
                    }
                }
            }
            return False;
        }

        public function deleteToDo($todo_id, $ticket_id)
        {

            $dataFreshTODO = array();
            $dataTODO = get_option("hive_lite_support_ticket_todos");
            foreach ($dataTODO as $singleData) {
                if (isset($singleData['todo_id'])) {
                    if ($singleData['todo_id'] != $todo_id) {
                        $dataFreshTODO[] = $singleData;
                    }
                }
            }

            update_option('hive_lite_support_ticket_todos', $dataFreshTODO);
        }

        public function generateTODOID($resultData)
        {
            $exits = false;
            $length = 9;
            $key = substr(str_shuffle(str_repeat($x = '123456789', ceil($length / strlen($x)))), 1, $length);
            foreach ($resultData as $singleResult) {
                if (isset($singleResult['todo_id'])) {
                    if ($singleResult['todo_id'] == $key) {
                        $exits = true;
                    }
                }
            }
            return (!$exits) ? $key : $this->generateTicketID($resultData);
        }
        /* ****************** TO DO Operations ****************** */


        /* ****************** Agents Operations ****************** */

        public function createNewAgent($user_id)
        {
            $dataAgents = get_option("hive_lite_support_agents");
            $dataAgents[] = array("user_id" => $user_id);
            update_option('hive_lite_support_agents', $dataAgents);
        }

        public function deleteAgent($user_id)
        {

            $dataFreshAgents = array();
            $dataAgents = get_option("hive_lite_support_agents");
            foreach ($dataAgents as $singleData) {
                if (isset($singleData['user_id'])) {
                    if ($singleData['user_id'] != $user_id) {
                        $dataFreshAgents[] = $singleData;
                    }
                }
            }

            $dataFreshAgentSettings = array();
            $dataAgentSettings = get_option("hive_lite_support_agent_settings");
            foreach ($dataAgentSettings as $singleData) {
                if (isset($singleData['user_id'])) {
                    if ($singleData['user_id'] != $user_id) {
                        $dataFreshAgentSettings[] = $singleData;
                    }
                }
            }

            update_option('hive_lite_support_agents', $dataFreshAgents);
            update_option('hive_lite_support_agent_settings', $dataFreshAgentSettings);
        }

        public function isAgentExits($user_id)
        {
            $dataAgents = get_option("hive_lite_support_agents");
            $dataAgents = is_array($dataAgents) ? $dataAgents : Null;
            if ($dataAgents != Null) {
                foreach ($dataAgents as $single_agent) {
                    if ($single_agent['user_id'] == $user_id) {
                        return True;
                    }
                }
            }
            return False;
        }

        public function listAllAgents()
        {
            $dataAgents = get_option("hive_lite_support_agents");
            return is_array($dataAgents) ? $dataAgents : Null;
        }

        public function updateAgentSettings($user_id, $key, $value = "<<hs_empty_value>>")
        {
            $exits = false;
            $exitingValue = Null;
            $dataAgentSettings = get_option("hive_lite_support_agent_settings");
            $dataNewAgentSettings = array();
            foreach ($dataAgentSettings as $singleSettings) {
                if (isset($singleSettings['user_id']) && isset($singleSettings['key'])) {
                    if ($singleSettings['user_id'] == $user_id && $singleSettings['key'] == $key) {
                        $exits = true;
                        $exitingValue = $singleSettings['value'];
                        $singleSettings['value'] = ($value != "<<hs_empty_value>>") ? $value : $singleSettings['value'];
                    }
                }
                if ($value != "<<hs_empty_value>>") {
                    $dataNewAgentSettings[] = $singleSettings;
                }
            }
            if ($exits && $value != "<<hs_empty_value>>") {
                update_option('hive_lite_support_agent_settings', $dataNewAgentSettings);
            } else if (!$exits && $value != "<<hs_empty_value>>") {
                $dataNewAgentSettings[] = array("user_id" => $user_id, "key" => $key, "value" => $value);
                update_option('hive_lite_support_agent_settings', $dataNewAgentSettings);
            } else if ($exits && $value == "<<hs_empty_value>>") {
                return stripslashes($exitingValue);
            } else {
                return Null;
            }
        }


        public function agentHasAccess($permission_key)
        {
            if (in_array("administrator", get_userdata(wp_get_current_user()->ID)->roles)) {
                return True;
            } else {
                $permissions = $this->updateAgentSettings(wp_get_current_user()->ID, "permissions");
                $permissions_arr = ($permissions == Null) ? explode(",", "") : explode(",", $permissions);
                if (in_array($permission_key, $permissions_arr)) {
                    return True;
                }
            }
            return False;
        }


        /* ****************** Response Operations ****************** */
        // Used By react Response Part
        public function createNewResponse($question, $answer)
        {
            $dataResponses = get_option("hive_lite_support_responses");
            $response_id = $this->generateResponsesID($dataResponses);

            $dataResponses[] = array("response_id" => $response_id, "question" => $question, "answer" => $answer);
            update_option('hive_lite_support_responses', $dataResponses);
            return true;
        }

        public function countResponses()
        {
            $dataResponses = get_option("hive_lite_support_responses");
            if (is_array($dataResponses)) {
                return sizeof($dataResponses);
            }
            return 0;
        }

        public function isResponseExits($response_id)
        {
            $dataResponses = get_option("hive_lite_support_responses");
            $dataResponses = is_array($dataResponses) ? $dataResponses : Null;
            if ($dataResponses != Null) {
                foreach ($dataResponses as $single_data) {
                    if ($single_data['response_id'] == $response_id) {
                        return True;
                    }
                }
            }
            return False;
        }

        public function listAllResponses()
        {
            $dataResponses = get_option("hive_lite_support_responses");
            return is_array($dataResponses) ? $dataResponses : Null;
        }

        public function deleteResponse($response_id)
        {

            $dataFreshResponses = array();
            $dataResponses = get_option("hive_lite_support_responses");
            foreach ($dataResponses as $singleData) {
                if (isset($singleData['response_id'])) {
                    if ($singleData['response_id'] != $response_id) {
                        $dataFreshResponses[] = $singleData;
                    }
                }
            }

            update_option('hive_lite_support_responses', $dataFreshResponses);
        }

        public function generateResponsesID($resultData)
        {
            $exits = false;
            $length = 9;
            $key = substr(str_shuffle(str_repeat($x = '123456789', ceil($length / strlen($x)))), 1, $length);
            foreach ($resultData as $singleResult) {
                if (isset($singleResult['response_id'])) {
                    if ($singleResult['response_id'] == $key) {
                        $exits = true;
                    }
                }
            }
            return (!$exits) ? $key : $this->generateTicketID($resultData);
        }


        /* ****************** Response Operations ****************** */


        /* ****************** Automation Operations ****************** */

        public function saveAutomationData($trigger_data)
        {
            update_option('hive_lite_support_automation', $trigger_data);
            return true;
        }

        public function getAutomation()
        {
            $dataAutomation = get_option("hive_lite_support_automation");
            return is_array($dataAutomation) ? $dataAutomation : Null;
        }

        public function isAutomationExits($id)
        {
            $data = get_option("hive_lite_support_automation");
            $data = is_array($data) ? $data : Null;
            if ($data != Null) {
                foreach ($data as $single_data) {
                    if ($single_data['id'] == $id) {
                        return True;
                    }
                }
            }
            return False;
        }


        public function deleteAutomation($id)
        {

            $dataFresh = array();
            $data = get_option("hive_lite_support_automation");
            foreach ($data as $singleData) {
                if (isset($singleData['id'])) {
                    if ($singleData['id'] != $id) {
                        $dataFresh[] = $singleData;
                    }
                }
            }

            update_option('hive_lite_support_automation', $dataFresh);
        }

        /* ****************** Automation Operations ****************** */


        public function get_all_hive_lite_support_settings()
        {
            $settings = array();

            /* Control */

            $settings["ticket_fields"] = $this->updateSettings("ticket_fields");
            $settings["ticket_fields"] = ($settings["ticket_fields"] == Null) ? "[{\"field_id\":\"sedbrgclo\",\"slug\":\"subject\",\"label\":\"Subject\",\"required\":\"1\"},{\"field_id\":\"p49mt89bd\",\"slug\":\"message\",\"label\":\"Message\",\"required\":\"1\"}]" : $settings["ticket_fields"];



            $settings["enable_email_to_staff_on_customer_reply"] = $this->updateSettings("enable_email_to_staff_on_customer_reply");
            $settings["enable_email_to_staff_on_customer_reply"] = ($settings["enable_email_to_staff_on_customer_reply"] == Null) ? "1" : $settings["enable_email_to_staff_on_customer_reply"];

            $settings["email_to_staff_on_customer_reply_subject"] = $this->updateSettings("email_to_staff_on_customer_reply_subject");
            $settings["email_to_staff_on_customer_reply_subject"] = ($settings["email_to_staff_on_customer_reply_subject"] == Null) ? "#{ticket_id} - New Reply Received from {customer_name}" : $settings["email_to_staff_on_customer_reply_subject"];

            $settings["email_to_staff_on_customer_reply_body"] = $this->updateSettings("email_to_staff_on_customer_reply_body");
            $settings["email_to_staff_on_customer_reply_body"] = ($settings["email_to_staff_on_customer_reply_body"] == Null) ? "Hello {staff_name}\nYou just received a reply from {customer_name} on Ticket #{ticket_id}.\nBack to your support portal to check it out." : $settings["email_to_staff_on_customer_reply_body"];



            $settings["enable_email_to_customer_on_staff_reply"] = $this->updateSettings("enable_email_to_customer_on_staff_reply");
            $settings["enable_email_to_customer_on_staff_reply"] = ($settings["enable_email_to_customer_on_staff_reply"] == Null) ? "1" : $settings["enable_email_to_customer_on_staff_reply"];

            $settings["email_to_customer_on_staff_reply_subject"] = $this->updateSettings("email_to_customer_on_staff_reply_subject");
            $settings["email_to_customer_on_staff_reply_subject"] = ($settings["email_to_customer_on_staff_reply_subject"] == Null) ? "#{ticket_id} - New Reply Received from {staff_name}" : $settings["email_to_customer_on_staff_reply_subject"];

            $settings["email_to_customer_on_staff_reply_body"] = $this->updateSettings("email_to_customer_on_staff_reply_body");
            $settings["email_to_customer_on_staff_reply_body"] = ($settings["email_to_customer_on_staff_reply_body"] == Null) ? "Hello {customer_name}\nYou just received a reply from {staff_name} on Ticket #{ticket_id}.\nBack to your support portal to check it out." : $settings["email_to_customer_on_staff_reply_body"];



            $settings["enable_email_to_admin_on_ticket_created"] = $this->updateSettings("enable_email_to_admin_on_ticket_created");
            $settings["enable_email_to_admin_on_ticket_created"] = ($settings["enable_email_to_admin_on_ticket_created"] == Null) ? "1" : $settings["enable_email_to_admin_on_ticket_created"];

            $settings["email_to_admin_on_ticket_created_subject"] = $this->updateSettings("email_to_admin_on_ticket_created_subject");
            $settings["email_to_admin_on_ticket_created_subject"] = ($settings["email_to_admin_on_ticket_created_subject"] == Null) ? "#{ticket_id} - New Support Ticket Received" : $settings["email_to_admin_on_ticket_created_subject"];

            $settings["email_to_admin_on_ticket_created_body"] = $this->updateSettings("email_to_admin_on_ticket_created_body");
            $settings["email_to_admin_on_ticket_created_body"] = ($settings["email_to_admin_on_ticket_created_body"] == Null) ? "Hello Admin\nA new support ticket has been placed in your site.\nBack to your support portal to check it out." : $settings["email_to_admin_on_ticket_created_body"];

            return $settings;
        }
    }
}
