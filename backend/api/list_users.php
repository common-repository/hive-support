<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    $all_users = get_users();
    foreach ($all_users as $user) {

        $usersList[] = array(
            "user_id" => $user->ID,
            "display_name" => $this->base_admin->utils->getUserDisplayName($user),
            "first_name" => trim($user->first_name),
            "last_name" => trim($user->last_name),
            "email" => $user->user_email
        );
    }


    $result = array("status" => 'true', "users" => $usersList);
} else {
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);
