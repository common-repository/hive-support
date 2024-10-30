<?php

$user_id = sanitize_text_field($_POST['ID']);
$job_title = sanitize_text_field($_POST['hs_agent_job_title']);
$first_name = sanitize_text_field($_POST['first_name']);
$last_name = sanitize_text_field($_POST['last_name']);
$permissions = sanitize_text_field($_POST['hs_agent_permissions']);
$slack_id = sanitize_text_field($_POST['hs_agent_slack_id']);
$telegram_id = sanitize_text_field($_POST['hs_agent_telegram_id']);

$data = array(
    "ID" => $user_id,
    "first_name" => $first_name,
    "last_name" => $last_name,
    "hs_agent_slack_id" => $slack_id,
    "hs_agent_telegram_id" => $telegram_id,
    "hs_agent_permissions" => $permissions,
    "hs_agent_job_title" => $job_title,
);

// Update user data
$old_user = get_user_by("id", $user_id);
if ($old_user) {
    // $this->update_single_user_meta($user_id, $key, $value);
    // Default data
    $this->update_single_user_meta($user_id, 'first_name', $first_name);
    $this->update_single_user_meta($user_id, 'last_name', $last_name);
    // Hive Support data
    $this->update_single_user_meta($user_id, 'hs_agent_slack_id', $slack_id);
    $this->update_single_user_meta($user_id, 'hs_agent_telegram_id', $telegram_id);
    $this->update_single_user_meta($user_id, 'hs_agent_job_title', $job_title);
    $this->update_single_user_meta($user_id, 'hs_agent_permissions', $permissions);
}

wp_send_json_success($data);
