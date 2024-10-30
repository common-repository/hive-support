<?php
$wp_user_email = sanitize_text_field($_POST['wp_user_email']);
$job_title = sanitize_text_field($_POST['hs_agent_job_title']);
$first_name = sanitize_text_field($_POST['first_name']);
$last_name = sanitize_text_field($_POST['last_name']);
$permissions = sanitize_text_field($_POST['hs_agent_permissions']);

$data = array(
    "first_name" => $first_name,
    "last_name" => $last_name,
    "hs_agent_permissions" => $permissions,
    "hs_agent_job_title" => $job_title,
    "real_success" => 'This is great',
    "notice_text" => 'No user found with this email.',
    "notice_type" => 'warning',
);


// Get all the user roles as an array.

$user = get_user_by("email", $wp_user_email);
// $user_data_array = get_object_vars($user);

if ($user) {
    $user_roles = $user->roles;
    // $data['user_role'] = $user_roles;

    $hive_lite_staff = 'hive_support_staff';

    if (in_array($hive_lite_staff, $user_roles)) {
        // $data['user_role'] = 'already_hive_lite_staff';
        $data['notice_text'] = 'This user is already a Support Agent.';
    } else {
        // Update the user's role.
        $user->set_role($hive_lite_staff);

        // Update first and last names.
        if ($first_name) $user->first_name = $first_name;
        if ($last_name) $user->last_name = $last_name;

        // Hive Support data
        if ($job_title) $this->update_single_user_meta($user->ID, 'hs_agent_job_title', $job_title);
        if ($permissions) $this->update_single_user_meta($user->ID, 'hs_agent_permissions', $permissions);

        // Save the changes.
        wp_update_user($user);

        $data['notice_text'] = 'Agent added Successfully.';
        $data['notice_type'] = 'success';
    }
} else {
    // $data['user_role'] = 'user_not_exists';
    $data['notice_text'] = 'No user found with this email.';
}



wp_send_json_success($data);
