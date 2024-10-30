<?php
// $wp_user_email = sanitize_text_field($_POST['wp_user_email']);
// $job_title = sanitize_text_field($_POST['hs_agent_job_title']);
// $first_name = sanitize_text_field($_POST['first_name']);
// $last_name = sanitize_text_field($_POST['last_name']);
// $permissions = sanitize_text_field($_POST['hs_agent_permissions']);

$agentToDelete = sanitize_text_field($_POST['agentToDelete']);
$fallbackAgent = sanitize_text_field($_POST['fallbackAgent']);


// $data = array(
//     "delete_success" => 'Agent is successfully deleted.',
//     "fallback_success" => 'Fallback agent successfully set.',
//     "not_agent" => 'This user is not an agent.',
//     "notice_type" => 'success',
//     "agentDeleted" => $agentToDelete,
//     "fallbackAgent" => $fallbackAgent,
// );
$data = array(
    "delete_success" => '',
    "fallback_success" => '',
    "not_agent" => 'This user is not an agent.',
    "notice_type" => 'success',
    "agentDeleted" => $agentToDelete,
    "fallbackAgent" => $fallbackAgent,
);


// Get all the user roles as an array.

$user = get_user_by("id", $agentToDelete);
// $user_data_array = get_object_vars($user);

if ($user) {
    $user_roles = $user->roles;
    // $data['user_role'] = $user_roles;

    $hive_lite_staff = 'hive_support_staff';
    $subscriber = 'subscriber';

    if (in_array($hive_lite_staff, $user_roles)) {

        $user->set_role($subscriber);
        // Remove support permissons
        $this->update_single_user_meta($user->ID, 'hs_agent_permissions', '');


        // $this->set_fallback_agent($agentToDelete, $fallbackAgent);

        // Save the changes.
        wp_update_user($user);

        $data['delete_success'] = 'Agent is successfully deleted.';
    } else {
        $data['not_agent'] = 'This user is not an agent.';
    }
} else {
    // $data['user_role'] = 'user_not_exists';
    $data['notice_text'] = 'No user found with this ID.';
}


wp_send_json_success($data);
