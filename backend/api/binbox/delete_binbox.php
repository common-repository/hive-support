<?php

$id = sanitize_text_field($_POST['delete_id']);
$fallback_id = sanitize_text_field($_POST['fallback_id']);
$data = array(
    "id" => $id,
    "fallback_id" => $fallback_id,
);

global $wpdb;
$mailbox_table = $wpdb->prefix . 'hs_mailbox';
$where = array('id' => $id);
$result = $wpdb->delete($mailbox_table, $where);

if ($result !== false) {
    $data['notice_type'] = 'success';
} else {
    $data['notice_type'] = 'error';
}
if ($fallback_id == '0' || $fallback_id == 0) {
    $this->update_mailbox_by_id($id, NULL);
} else {
    $this->update_mailbox_by_id($id, $fallback_id);
}

// $data['notice_type'] = 'success';
wp_send_json_success($data);
