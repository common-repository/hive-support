<?php

if (current_user_can('hive_support_access_plugin')) {

    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $ticket_id = $ticketid = $_POST['ticket_id'];
    $ticket_status = $_POST['ticket_status'];
    $ticket_update_query = $wpdb->query(
        $wpdb->prepare(
            "UPDATE $ticket_table SET ticket_status=%s WHERE id=%d",
            array(
                $ticket_status,
                (int) $ticket_id
            )
        )
    );

    if (!$ticket_update_query) {
        wp_send_json_error();
    }

    /************************************
     *  Close ticket notification
     * *********************************/

    if (HIVES_NOTIFICATION && $ticket_status === 'Closed') {

        $message = 'Your ticket has been closed';


        SlackNotifications::set_settings();
        $slack_reply = SlackNotifications::$slacktriggers['ticket_closed'];
        $telegram_reply = SlackNotifications::$telegramtriggers['ticket_closed'];
        $discord_reply = SlackNotifications::$discordtriggers['ticket_closed'];

        $slack_status = SlackNotifications::$slackstatus;
        $telegram_status = SlackNotifications::$telegramstatus;
        $discord_status = SlackNotifications::$discordstatus;

        if ($slack_status && $slack_reply) {
            $replaymessage = "Ticket Closed (#$ticketid)\n\nMessage:\n$message\n";
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

            $replaymessage = "Ticket Closed (#$ticketid)\n\nMessage:\n$message\n";

            $apiResponse = wp_remote_get($tegramurl . '/' . 'bot' . $bottoken . '/' . 'sendMessage?chat_id=' . $channel_id . '&text=' . urlencode($replaymessage) . '&reply_to_message_id=' . $mesage_id);

            $apiResponseBody = json_decode(wp_remote_retrieve_body($apiResponse), true);
        }

        //
        if ($discord_status && $discord_reply) {

            // Prepare the message content
            $discord_message = "Ticket Closed (#$ticketid)\n\n**Message:**\n$message\n";
            //[support server](https://discohook.app/discord)

            $discord_webhook = SlackNotifications::$discordWebHook['settings']['webhookurl'] ?? '';

            $response = wp_remote_post($discord_webhook, array(
                'body' => json_encode(array('content' => $discord_message)),
                'headers' => array('Content-Type' => 'application/json'),
                'timeout' => 15,
            ));
        }
    }







    wp_send_json_success();
}
