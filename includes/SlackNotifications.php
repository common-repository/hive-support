<?php
if (!defined('ABSPATH')) {
    exit;
}

class SlackNotifications
{
    public static $post_url = "https://slack.com/api/chat.postMessage";

    // public static $oauth_token = "xoxp-5069813927808-5048598203748-5490910089841-f0cd2c56ce9334f064e33a58ca4df9c1";
    // public static $channel_id = "C05E4M50G76";
    // public static $channel_name = "hive_lite_api_inte";

    public static $oauthtoken;
    public static $channelid;
    public static $channelname;

    public static $telebottoken;

    public static $telechannelid;

    public static $telegramtriggers;
    public static $slacktriggers;
    public static $discordtriggers;

    public static $slackstatus;
    public static $telegramstatus;
    public static $discordstatus;
    public static $discordWebHook;

    public $channel_id = '6522269794';

    public function __construct()
    {

        // $this->use_tel_message();
    }
    function use_tel_message()
    {
        $messages = $this->handle_telegram_integraion();

        echo "<pre>";
        var_dump($messages);
        echo "</pre>";

        // foreach ($messages as $message) {
        //     if (isset($message["message"]["text"]) && isset($message["message"]["from"]["first_name"])) {
        //         $text = $message["message"]["text"];
        //         $sender = $message["message"]["from"]["first_name"];
        //         $direction = $message["message"]["chat"]["id"] == $this->channel_id ? "Outgoing" : "Incoming";
        //         $timestamp = date("Y-m-d H:i:s", $message["message"]["date"]);
        //         echo "<li>$direction message from $sender ($timestamp): $text</li>";
        //     } else {
        //         echo "<li>Error: Unable to parse message.</li>";
        //     }
        // }
    }

    // START: Added by tushar, you can remove after check
    function handle_telegram_integraion()
    {
        $token = '6373856429:AAFykbGuLvzcBR5IXehJdal2bSH4dW8NsVI';
        $channel_id = '6522269794';
        // Function to make API requests


        // Fetch updates (messages) from the channel
        $response = $this->apiRequest("getUpdates", ["chat_id" => $channel_id], $token);
        // Process and display messages
        $messages = [];
        if ($response["ok"]) {
            $messages = $response["result"];
        } else {
            echo "Error fetching messages: " . $response["description"];
        }
        return $messages;
    }
    function apiRequest($method, $params = [], $token = '')
    {
        // global $token;
        $url = "https://api.telegram.org/bot$token/$method";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($curl);
        if (!$response) {
            exit("Error: " . curl_error($curl));
        }
        curl_close($curl);
        return json_decode($response, true);
    }
    function enter_tel_messages_into_db()
    {

        global $wpdb;
        $conversation_table = $wpdb->prefix . 'hs_conversations';

        $ticketid = '';
        $personid = '';
        $replaymessage = '';
        $files_url = '';
        $contenthash = '';
        $timestamps = '';

        $ticket_data = array(
            $ticketid,
            $personid,
            $replaymessage,
            json_encode($files_url),
            $contenthash,
            $timestamps
        );

        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $conversation_table (ticket_id,person_id,content,media_urls,content_hash,created_at) VALUES(
                    %d,%d,%s,%s,%s,%s
                )",
                $ticket_data
            )
        );
    }
    // END:Added by tushar, you can remove after check



    // Get Settings from database
    public static function set_settings()
    {

        $self = new self();
        $integrations = get_option('hive_lite_support_intrigations');
        $integrationsarray = json_decode($integrations, true);


        self::$oauthtoken = $integrationsarray['slack']['settings']['oauth'] ?? '';
        self::$channelid = $integrationsarray['slack']['settings']['channelid'] ?? '';
        self::$channelname = $integrationsarray['slack']['settings']['channelname'] ?? '';

        self::$telebottoken = $integrationsarray['telegram']['settings']['bot_token'] ?? '';
        self::$telechannelid = $integrationsarray['telegram']['settings']['channel_id'] ?? '';

        self::$telegramtriggers = $integrationsarray['telegram']['triggers'] ?? [];
        self::$discordtriggers = $integrationsarray['discord']['triggers'] ?? [];
        self::$slacktriggers = $integrationsarray['slack']['triggers'] ?? [];

        self::$telegramstatus = $integrationsarray['telegram']['enable'] ?? false;
        self::$discordstatus = $integrationsarray['discord']['enable'] ?? false;
        self::$slackstatus = $integrationsarray['slack']['enable'] ?? false;

        self::$discordWebHook = $integrationsarray['discord'];
    }
    public static function get_setting()
    {
        return false;
    }
    // getThreadTsByTicketId
    public static function getThreadTsByTicketId($ticketid)
    {
        if (empty($ticketid)) {
            return false;
        }
        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_slack_ts";

        $query = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT meta_value FROM {$tickets_meta} WHERE meta_key=%s AND ticket_id=%d",
                array(
                    $meta_key,
                    (int) $ticketid,
                )
            )
        );

        if (!$query) {
            return false;
        }

        return (string) $query;
    }
    public static function getMessageIdByTicketID($ticketid)
    {
        if ($ticketid == "" || empty($ticketid) || $ticketid == null || !isset($ticketid)) {
            return false;
        }
        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_tele_id";

        $query = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT meta_value FROM {$tickets_meta} WHERE meta_key=%s AND ticket_id=%d",
                array(
                    $meta_key,
                    (int) $ticketid,
                )
            )
        );

        if (!$query) {
            return false;
        }

        return (string) $query;
    }
    public static function getTicketIddByMessageId($message_id)
    {
        if ($message_id == "" || empty($message_id) || $message_id == null || !isset($message_id)) {
            return false;
        }
        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_tele_id";

        $query = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ticket_id FROM {$tickets_meta} WHERE meta_key=%s AND meta_value=%s",
                array(
                    $meta_key,
                    (string) $message_id,
                )
            )
        );

        if (!$query) {
            return false;
        }

        return (int) $query;
    }
    public static function getTicketIDByThreadTs($thread_ts)
    {

        if (empty($thread_ts)) {
            return false;
        }

        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_slack_ts";

        $query = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ticket_id FROM {$tickets_meta} WHERE meta_key=%s AND meta_value=%s",
                array(
                    $meta_key,
                    $thread_ts,
                )
            )
        );

        return $query;
    }
    // add_ticket_meta_for_telegram (telegram thread)
    public static function add_ticket_meta_for_telegram($message_id, $ticketid)
    {
        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_tele_id";
        $query = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$tickets_meta} (ticket_id,meta_key,meta_value) VALUES (%d,%s,%s)",
                array(
                    (int) $ticketid,
                    (string) $meta_key,
                    (string) $message_id,
                )
            )
        );
    }
    // add_ticket_meta_for_slack (slack thread)
    public static function add_ticket_meta_for_slack($ts, $ticket_id)
    {
        global $wpdb;
        $tickets_meta = $wpdb->prefix . 'hs_ticketsmeta';
        $meta_key = "ticket_slack_ts";
        $query = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$tickets_meta} (ticket_id,meta_key,meta_value) VALUES (%d,%s,%s)",
                array(
                    (int) $ticket_id,
                    (string) $meta_key,
                    (string) $ts,
                )
            )
        );
    }

    // May be not used but chek before remove
    public static function send($message, $thraed_id = '')
    {

        //$self = new self();
        $message_payload = array(
            'channel' => self::$channelid,
            'text' => $message,
            'reply_broadcast' => true
        );

        if (!empty($thraed_id)) {
            $message_payload['thread_ts'] = $thraed_id;
        }

        $apiResponse = wp_remote_post(
            self::$post_url,
            array(
                'method' => 'POST',
                'sslverify' => false,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer' . ' ' . self::$oauthtoken
                ),
                'body' => json_encode(
                    $message_payload
                )
            )
        );

        $apiResponseBody = json_decode(wp_remote_retrieve_body($apiResponse), true);
        return $apiResponseBody;
    }
}

// $hello = new SlackNotifications();