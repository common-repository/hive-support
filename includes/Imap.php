<?php


class HiveSupportImap
{
    public $imap_url;
    public $imap_port;
    public $email_id;
    public $email_password;
    public $email_path;

    public function __construct($emailurl, $emailport, $emailid, $email_password, $email_path = "imap/ssl")
    {
        $this->imap_url = $emailurl;
        $this->imap_port = $emailport;
        $this->email_id = $emailid;
        $this->email_password = $email_password;
        $this->email_path = $email_path;
        $this->readImap();
    }

    public function readImap()
    {
        global $wpdb;



        // $custom_array_filter = function ($input, $callback) {
        //     $filterred_array = [];
        //     for ($i = 0; $i < count($input); $i++) {
        //         $callback($input[$i]) ? array_push($filterred_array, $input[$i]) : null;
        //     }
        //     return $filterred_array;
        // };


        // $array = [10, 34, 12, 54, 12, 1];
        // $custom_array_filter($array, function ($number) {

        // });






        $ticket_table = $wpdb->prefix . 'hs_tickets';
        $imap_url = $this->imap_url . ':' . $this->imap_port . '/' . $this->email_path;
        $imap_full_url = "{" . $imap_url . "}" . "INBOX";
        $handler = imap_open($imap_full_url, $this->email_id, $this->email_password);
        $emails = imap_search($handler, "ALL");
        $mailbox_table = $wpdb->prefix . 'hs_mailbox';
        $convo_table = $wpdb->prefix . 'hs_conversations';
        foreach ($emails as $email) {
            // $overview = imap_fetch_overview($handler, $email, 0);
            // $message_id = htmlspecialchars($overview[0]->message_id);
            // $referrance = htmlspecialchars($overview[0]->references);
            // echo "<pre>";
            // print_r($overview[0]);
            // var_dump($message_id);
            // var_dump($referrance);
            // echo "</pre>";

            // file_put_contents('test.txt', print_r($in_replay_to, true), FILE_APPEND);

            $overview = imap_fetch_overview($handler, $email, 0);
            $message = imap_fetchbody($handler, $email, '1');
            $email_msg_id = $overview[0]->message_id;
            $email_subject = mb_decode_mimeheader($overview[0]->subject);
            $refs = isset($overview[0]->references) ? $overview[0]->references : '';
            //$email_msg = imap_body($handler, $email);
            //$email_msg = imap_fetchstructure($handler, $email);
            $tickett_existency = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}hs_tickets WHERE message_id = %s",
                    $email_msg_id
                )
            );
            $convo_existency = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}hs_conversations WHERE message_id = %s",
                    $email_msg_id
                )
            );

            if (!$tickett_existency && !$convo_existency) {

                if (str_contains($refs, 'convo')) {
                    $array_string = explode(" ", str_replace(array('<', '>'), '', $refs));
                    foreach ($array_string as $value) {
                        if (str_contains($value, "convo")) {
                            $query = $wpdb->query(
                                $wpdb->prepare(
                                    "INSERT INTO {$convo_table} (message_id,source,created_at,updated_at) VALUES(%s,%s,%d,%d)",
                                    $email_msg_id,
                                    'web',
                                    date("Y-m-d H:i:s"),
                                    date("Y-m-d H:i:s"),
                                )
                            );
                            break;
                        }
                    }


                    // print_r($array_string);
                    // die();

                } else {
                    $mailbox_id = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT id FROM {$wpdb->prefix}hs_mailbox WHERE email_id = %s",
                            $this->email_id
                        )
                    );
                    $query = $wpdb->query(
                        $wpdb->prepare(
                            "INSERT INTO {$ticket_table} (title,mailbox_id,message_id,created_at,updated_at) VALUES(%s,%d,%s,%d,%d)",
                            $email_subject,
                            $mailbox_id,
                            $email_msg_id,
                            date("Y-m-d H:i:s"),
                            date("Y-m-d H:i:s"),
                        )
                    );
                }
            }



        }
        imap_close($handler);
    }
}