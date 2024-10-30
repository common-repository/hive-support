<?php

// Get all the mailboxes 

if (current_user_can('hive_support_access_plugin')) {
    if (isset($_POST['action'])) {
        global $wpdb;

        $mailbox_db = $wpdb->prefix . 'hs_mailbox';
        $allmailboxes = $wpdb->get_results(
            "SELECT * FROM {$mailbox_db}"
        );
        echo json_encode(
            array(
                "success" => true,
                "mailboxes" => $allmailboxes
            )
        );
    }
}
