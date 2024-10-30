<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {


    $agentsList = array();


    $list_entries = $this->base_admin->settings->listAllTickets();

    /* Non Administrator User */
    $list_agents = $this->base_admin->settings->listAllAgents();
    if ($list_agents != Null) {
        foreach ($list_agents as $single_agent) {

            $user_id = $single_agent['user_id'];
            $user_info = get_userdata($user_id);
            if ($user_info != false) {
                if (!in_array("administrator", $user_info->roles)) {
                    $profile_url = get_avatar_url($user_id);

                    $title = $this->base_admin->settings->updateAgentSettings($user_id, "title");
                    $title = ($title == Null) ? "Support Staff" : $title;

                    $permissions = $this->base_admin->settings->updateAgentSettings($user_id, "permissions");
                    $permissions = ($permissions == Null) ? "" : $permissions;

                    $total_tickets_assigned = 0;
                    $total_tickets_closed = 0;
                    foreach ($list_entries as $single_entry) {
                        $ticket_id = $single_entry['ticket_id'];
                        $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
                        $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
                        if ($agent_user_id != $user_id) {
                            continue;
                        }
                        $total_tickets_assigned++;
                        if ($status == "close") {
                            $total_tickets_closed++;
                        }
                    }

                    $agentsList[] = array(
                        "user_id" => $user_id,
                        "display_name" => $this->base_admin->utils->getUserDisplayName($user_info),
                        "first_name" => trim($user_info->first_name),
                        "last_name" => trim($user_info->last_name),
                        "email" => $user_info->user_email,
                        "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
                        "position" => $title,
                        "total_tickets_assigned" => $total_tickets_assigned,
                        "total_tickets_closed" => $total_tickets_closed,
                        "permissions" => $permissions,
                    );
                }
            }
        }
    }


    /* Added Hive Support Staff User from WordPress Users */
    $support_users = get_users(array('role__in' => array('hive_support_staff')));
    foreach ($support_users as $user) {
        $is_already_added = False;
        foreach ($agentsList as $already_added_single_user) {
            if ($already_added_single_user["user_id"] == $user->ID) {
                $is_already_added = True;
            }
        }
        if (!$is_already_added) {
            $profile_url = get_avatar_url($user->ID);

            $title = $this->base_admin->settings->updateAgentSettings($user->ID, "title");
            $title = ($title == Null) ? "Support Staff" : $title;

            $permissions = $this->base_admin->settings->updateAgentSettings($user->ID, "permissions");
            $permissions = ($permissions == Null) ? "" : $permissions;

            $total_tickets_assigned = 0;
            $total_tickets_closed = 0;
            foreach ($list_entries as $single_entry) {
                $ticket_id = $single_entry['ticket_id'];
                $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
                $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
                if ($agent_user_id != $user->ID) {
                    continue;
                }
                $total_tickets_assigned++;
                if ($status == "close") {
                    $total_tickets_closed++;
                }
            }

            $agentsList[] = array(
                "user_id" => $user->ID,
                "display_name" => $this->base_admin->utils->getUserDisplayName($user),
                "first_name" => trim($user->first_name),
                "last_name" => trim($user->last_name),
                "email" => $user->user_email,
                "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
                "position" => $title,
                "total_tickets_assigned" => $total_tickets_assigned,
                "total_tickets_closed" => $total_tickets_closed,
                "permissions" => $permissions,
            );
        }
    }


    /* Administrator User */
    $admin_users = get_users(array('role__in' => array('administrator')));
    foreach ($admin_users as $user) {
        $profile_url = get_avatar_url($user->ID);

        $title = $this->base_admin->settings->updateAgentSettings($user->ID, "title");
        $title = ($title == Null) ? "Administrator" : $title;


        $total_tickets_assigned = 0;
        $total_tickets_closed = 0;
        foreach ($list_entries as $single_entry) {
            $ticket_id = $single_entry['ticket_id'];
            $status = $this->base_admin->settings->updateTicketSettings($ticket_id, "status");
            $agent_user_id = $this->base_admin->settings->updateTicketSettings($ticket_id, "agent_user_id");
            if ($agent_user_id != $user->ID) {
                continue;
            }
            $total_tickets_assigned++;
            if ($status == "close") {
                $total_tickets_closed++;
            }
        }

        $agentsList[] = array(
            "user_id" => $user->ID,
            "display_name" => $this->base_admin->utils->getUserDisplayName($user),
            "first_name" => trim($user->first_name),
            "last_name" => trim($user->last_name),
            "email" => $user->user_email,
            "profile_img_url" => $profile_url != false ? $profile_url : HIVE_LITE_SUPPORT_IMG_DIR . "agents/unknown_profile_icon.svg",
            "position" => $title,
            "total_tickets_assigned" => $total_tickets_assigned,
            "total_tickets_closed" => $total_tickets_closed,
            "permissions" => "manage_all_tickets,manage_agents,access_activities,modify_settings",
        );
    }


    $result = array("status" => 'true', "agents" => $agentsList);
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
