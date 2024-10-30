<?php

if (current_user_can('hive_support_access_plugin')) {

    global $wpdb;
    $ticket_replay_table = $wpdb->prefix . "hs_conversations";
    $replay_id = (int) $_POST['replay_id'];
    $delquery = $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $ticket_replay_table WHERE id=%d",
            array(
                $replay_id
            )
        )
    );

    if ($delquery) {

        /***************
         *  Notification
         * *************** */
        if (HIVES_NOTIFICATION) {

            $current_user = get_current_user_id();
            $author_obj = get_user_by('id', $current_user);
            $current_user_name = $author_obj->user_login;
            $message = 'Your ticket has been closed';


            SlackNotifications::set_settings();
            $slack_reply = SlackNotifications::$slacktriggers['ticket_replied'];
            $telegram_reply = SlackNotifications::$telegramtriggers['ticket_replied'];
            $discord_reply = SlackNotifications::$discordtriggers['ticket_replied'];

            $slack_status = SlackNotifications::$slackstatus;
            $telegram_status = SlackNotifications::$telegramstatus;
            $discord_status = SlackNotifications::$discordstatus;

            $stripReplyMessage = wp_strip_all_tags($replaymessage, true);
            if ($slack_status && $slack_reply) {
                $replaymessage = "Replied From $current_user_name (#$ticketid)\n\nMessage:\n$message\n";
                $thread_ts = SlackNotifications::getThreadTsByTicketId($ticketid);
                $send = SlackNotifications::send($replaymessage, $thread_ts);
            }

            if ($telegram_status && $telegram_reply) {
                $mesage_id = (int) SlackNotifications::getMessageIdByTicketID($ticketid);

                $tegramurl = "https://api.telegram.org";
                // $channel_id = -1001837333962;
                // $bottoken = '6351099716:AAGL6Vst7_J0-qgNRVWXzDYWSgnJTjhF7OY';

                $bottoken = SlackNotifications::$telebottoken;
                $channel_id = (int) SlackNotifications::$telechannelid;

                $replaymessage = "Replied From $current_user_name (#$ticketid)\n\nMessage:\n$message\n";

                $apiResponse = wp_remote_get($tegramurl . '/' . 'bot' . $bottoken . '/' . 'sendMessage?chat_id=' . $channel_id . '&text=' . urlencode($replaymessage) . '&reply_to_message_id=' . $mesage_id);

                $apiResponseBody = json_decode(wp_remote_retrieve_body($apiResponse), true);
            }

            //
            if ($discord_status && $discord_reply) {

                // Prepare the message content
                $discord_message = "Replied From $current_user_name (#$ticketid)\n\n**Message:**\n$message\n";
                //[support server](https://discohook.app/discord)

                $discord_webhook = SlackNotifications::$discordWebHook['settings']['webhookurl'] ?? '';

                $response = wp_remote_post($discord_webhook, array(
                    'body' => json_encode(array('content' => $discord_message)),
                    'headers' => array('Content-Type' => 'application/json'),
                    'timeout' => 15,
                ));

                // Check if the request was successful
                // if (is_wp_error($response)) {
                //     error_log('Failed to send ticket to Discord: ' . $response->get_error_message());
                // } else {
                //     // Log success
                //     error_log('Ticket sent to Discord successfully!');
                // }



            }
        }




        wp_send_json_success("Replay deleted successfully");
    } else {
        wp_send_json_error("Somthing went wrong");
    }
}
