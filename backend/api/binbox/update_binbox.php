<?php

$id = sanitize_text_field($_POST['id']);

$mailbox_title = sanitize_text_field($_POST['inboxName']);
$mailbox_type = sanitize_text_field($_POST['inboxType']);
$email_url = sanitize_text_field($_POST['emailURL']);
$email_id = sanitize_text_field($_POST['pipingEmail']);
$support_from_email = sanitize_text_field($_POST['fromEmail']);
$admin_email = sanitize_text_field($_POST['adminEmail']);
$email_password = sanitize_text_field($_POST['pipingEmailPass']);

global $wpdb;
$mailbox_table = $wpdb->prefix . 'hs_mailbox';

$data = array(
    "mailbox_title" => $mailbox_title,
    "mailbox_type"  => $mailbox_type,
    "support_from_email" => $support_from_email,
    "admin_email" => $admin_email,
    "email_url"     => $email_url,
    "email_id"      => $email_id,
    "email_password" => $email_password
);

$where = array('id' => $id);
$result = $wpdb->update($mailbox_table, $data, $where);

$data['id'] = $id;
if ($result !== false) {
    $data['notice_type'] = 'success';
} else {
    $data['notice_type'] = 'error';
}

wp_send_json_success($data);
