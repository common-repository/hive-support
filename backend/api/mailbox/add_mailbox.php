<?php

$result = array();

/* Check if user has admin capabilities */
if (current_user_can('hive_support_access_plugin')) {

    if (
        isset($_REQUEST['emailPort']) && isset($_REQUEST['emailInboxPath']) && isset($_REQUEST['emailPassword'])
        && isset($_REQUEST['emailId']) && isset($_REQUEST['emailUrl'])
    ) {
        global $wpdb;
        $emailurl = sanitize_text_field($_REQUEST['emailUrl']);
        $emailport = sanitize_text_field($_REQUEST['emailPort']);
        $email_id = sanitize_text_field($_REQUEST['emailId']);
        $emailpassword = sanitize_text_field($_REQUEST['emailPassword']);
        $emailiboxpath = sanitize_text_field($_REQUEST['emailInboxPath']);
        $mailtitle = sanitize_text_field($_REQUEST['mailboxTitle']);


        $queryinsert = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}hs_mailbox(mailbox_title,email_url,email_port,email_path,email_id,email_password) VALUES(%s,%s,%d,%s,%s,%s)",
                $mailtitle,
                $emailurl,
                $emailport,
                $emailiboxpath,
                $email_id,
                $emailpassword
            )
        );




        echo json_encode(
            array(
                "status" => true
            ),
            JSON_UNESCAPED_UNICODE
        );
    } else {
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
