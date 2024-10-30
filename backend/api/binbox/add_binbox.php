<?php

$mailbox_title = sanitize_text_field($_POST['inboxName']);
$mailbox_type = sanitize_text_field($_POST['inboxType']);
$email_url = sanitize_text_field($_POST['emailURL']);
$email_id = sanitize_text_field($_POST['pipingEmail']);
$support_from_email = sanitize_text_field($_POST['fromEmail']);
$admin_email = sanitize_text_field($_POST['adminEmail']);
$email_password = sanitize_text_field($_POST['pipingEmailPass']);

$data = array(
    "title" => $mailbox_title,
    "support_from_email" => $support_from_email,
    "admin_email" => $admin_email,
    "mailbox_type" => $mailbox_type,
    "email_url" => $email_url,
    "email_id" => $email_id,
    "email_password" => $email_password,
);

global $wpdb;
$mailbox_table = $wpdb->prefix . 'hs_mailbox';
$cols = "mailbox_title,support_from_email,admin_email,mailbox_type,email_url,email_id,email_password";
$formats = "%s,%s,%s,%s,%s,%s,%s";
$col_values = [
    $mailbox_title,
    $support_from_email,
    $admin_email,
    $mailbox_type,
    $email_url,
    $email_id,
    $email_password,
];

$sql = "INSERT INTO {$mailbox_table} ({$cols}) VALUES({$formats})";

$query = $wpdb->query(
    $wpdb->prepare($sql, $col_values)
);

$inserted_id = $wpdb->insert_id;

// $data['notice_type'] = 'success';
if ($inserted_id) {
    $data['inserted_id'] = $inserted_id;
    $data['notice_type'] = 'success';
}

wp_send_json_success($data);
