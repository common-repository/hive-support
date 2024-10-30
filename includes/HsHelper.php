<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HsHelp')) {
    class HsHelp
    {



        public $base_admin;
        public function __construct($base_admin)
        {
            $this->base_admin = $base_admin;

            // add_filter('cron_schedules', [$this,'hiveAddCronIntervals']);

        }
        // public static function hiveAddCronIntervals()
        // {

        // }

        public static function get_mailbox_data()
        {

            // Gmail:
            // 'server_url' => '{imap.gmail.com:993/imap/ssl/novalidate-cert}',

            // Outlook (formerly known as Hotmail):
            // 'server_url' => '{outlook.office365.com:993/imap/ssl}',

            // Yahoo Mail:
            // 'server_url' => '{imap.mail.yahoo.com:993/imap/ssl}',

            // AOL Mail:
            // 'server_url' => '{imap.aol.com:993/imap/ssl}',

            // iCloud Mail:
            // 'server_url' => '{imap.mail.me.com:993/imap/ssl}',
            // 'server_url' => '{imap.gmail.com:143/imap}', // Without SSL

            $mailbox_title = 'Darklup';

            $host = 'imap.gmail.com';
            $port = 993;
            $eamil = 'hiveemail2ticekt@gamil.com';
            $is_ssl = true;

            $mailboxes = [];
            $item0 = [
                'mailbox_ttile' => 'Darklup',
                'host' => '',
                'port' => '',
                'is_ssl' => '',
                'email' => 'unknowneye6767@gmail.com',
                'pass' => '',
            ];
            $item1 = [
                'mailbox_ttile' => 'Guidant',
                'host' => 'imap.gmail.com',
                'port' => '993',
                'is_ssl' => 'ssl/novalidate-cert',
                'email' => 'hiveemail2ticekt@gamil.com',
                'pass' => 'qhsx oyhg nkxr kjza',
            ];

            $item2 = [
                'mailbox_ttile' => 'wcEazy',
                'host' => 'outlook.office365.com',
                'port' => '993',
                'is_ssl' => 'ssl',
                'email' => 'hivewpc@outlook.com',
                'pass' => 'ps&at8Qf',
            ];
            // $item3 = [
            //     'mailbox_ttile'=> 'Hive Support',
            //     'host'=> 'outlook.office365.com',
            //     'port'=> '993',
            //     'is_ssl'=> '',
            //     'email'=> 'hivewpcok@outlook.com',
            //     'pass'=> 'ps&at8Qf',
            // ];
            $mailboxes = [$item0, $item1, $item2];
            return $mailboxes;
        }

        public static function add_mailbox()
        {
            // $utils = new HsHelp(null);
            // $mailboxes = $utils->get_mailbox_data();
            // echo "<pre>";
            // var_dump($mailboxes);
            // echo "</pre>";

            $mailboxes = [];
            $item0 = [
                'mailbox_ttile' => 'Darklup',
                'host' => '',
                'port' => '',
                'is_ssl' => '',
                'mailbox_type' => 'web',
                'email' => 'unknowneye6767@gmail.com',
                'pass' => '',
            ];
            $item1 = [
                'mailbox_ttile' => 'Guidant',
                'host' => 'imap.gmail.com',
                'port' => '993',
                'is_ssl' => 'ssl/novalidate-cert',
                'mailbox_type' => 'email',
                'email' => 'hivemailt2ticket@gmail.com',
                'pass' => 'qhsx oyhg nkxr kjza',
            ];

            $item2 = [
                'mailbox_ttile' => 'wcEazy',
                'host' => 'outlook.office365.com',
                'port' => '993',
                'is_ssl' => 'ssl',
                'mailbox_type' => 'email',
                'email' => 'hivewpc@outlook.com',
                'pass' => 'ps&at8Qf',
            ];


            $mailboxes = [$item0, $item1, $item2];

            foreach ($mailboxes as $mailbox) {

                HsHelp::insert_mailbox($mailbox);
            }
            return;
        }
        public static function mailbox_exist($email)
        {
            global $wpdb;
            $mailbox_exist = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}hs_mailbox WHERE email_id = %s",
                    $email
                )
            );
            return $mailbox_exist;
        }
        public static function insert_mailbox($mailbox)
        {
            global $wpdb;
            $mailbox_table = $wpdb->prefix . 'hs_mailbox';
            $cols = "mailbox_title,email_url,mailbox_type,email_port,email_path,email_id,email_password";
            $formats = "%s,%s,%s,%s,%s,%s";

            $col_values = [];

            if ($mailbox['host']) {
                $ssl = '';
                if ($mailbox['is_ssl']) $ssl = '/' . $mailbox['is_ssl'];
                $imap_url = "{" . $mailbox['host'] . ':' . $mailbox['port'] . '/imap' . $ssl . "}" . "INBOX";
            } else {
                $imap_url = '';
            }
            // Maintain sequence with columns
            $col_values[] = $mailbox['mailbox_ttile'];
            $col_values[] = $imap_url;
            $col_values[] = $mailbox['mailbox_type'];
            $col_values[] = '';
            $col_values[] = '';
            $col_values[] = $mailbox['email'];
            $col_values[] = $mailbox['pass'];

            // echo "<pre>";
            // var_dump($col_values);
            // echo "</pre>";

            if (!HsHelp::mailbox_exist($mailbox['email'])) {
                $query = $wpdb->query(
                    $wpdb->prepare("INSERT INTO {$mailbox_table} ({$cols}) VALUES({$formats})", $col_values)
                );
            } else {
                // echo 'Mailbox exists';
            }
        }
        public static function add_activities($title, $initiator, $role, $ticket_id)
        {
            global $wpdb;
            $ticket_activities = $wpdb->prefix . 'hs_ticket_activities';
            $timestamps = current_time('Y-m-d H:i:s');

            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO {$ticket_activities} (ticket_id,initiator,initiator_role,title,created_at) VALUES(%d,%d,%s,%s,%s)",
                    array(
                        $ticket_id,
                        (int) $initiator,
                        $role,
                        $title,
                        $timestamps
                    )
                )
            );


            return true;
        }
        public function getDefaultFieldData($slug)
        {
            $fieldData = array();
            switch ($slug) {
                case "subject":
                    $fieldData = array(
                        "slug" => "subject",
                        "label" => "Subject",
                        "required" => "0",
                    );
                    break;
                case "message":
                    $fieldData = array(
                        "slug" => "message",
                        "label" => "Message",
                        "required" => "0",
                    );
                    break;
                case "simple_text":
                    $fieldData = array(
                        "slug" => "simple_text",
                        "label" => "Text Input",
                        "required" => "0",
                    );
                    break;
                case "text_area":
                    $fieldData = array(
                        "slug" => "text_area",
                        "label" => "Multiline Text",
                        "required" => "0",
                    );
                    break;
                case "number":
                    $fieldData = array(
                        "slug" => "number",
                        "label" => "Numeric Field",
                        "required" => "0",
                    );
                    break;
                case "dropdown":
                    $fieldData = array(
                        "slug" => "dropdown",
                        "label" => "Dropdown Field",
                        "required" => "0",
                        "options" => "Option 1::hive_lite_support_separator::Option 2::hive_lite_support_separator::Option 3",
                    );
                    break;
                case "radio":
                    $fieldData = array(
                        "slug" => "radio",
                        "label" => "Radio Field",
                        "required" => "0",
                        "options" => "Option 1::hive_lite_support_separator::Option 2::hive_lite_support_separator::Option 3",
                    );
                    break;
                case "checkbox":
                    $fieldData = array(
                        "slug" => "checkbox",
                        "label" => "Checkbox Field",
                        "required" => "0",
                        "options" => "Option 1::hive_lite_support_separator::Option 2::hive_lite_support_separator::Option 3",
                    );
                    break;
            }

            return $fieldData;
        }

        public function generateBuilderFieldDataHTML($fieldData)
        {
            $html = "";
            foreach ($fieldData as $key => $value) {
                $html .= 'data-' . $key . '="' . $value . '" ';
            }
            return $html;
        }



        public function extractFormFieldsJSONtoArray($form_fields_obj)
        {
            $result = array();
            foreach ($form_fields_obj as $field) {
                $result[] = $field;
            }
            return $result;
        }

        public function getUserDisplayName($user)
        {
            $name = trim($user->first_name . " " . $user->last_name);
            if (strlen(trim($name)) == 0) {
                $name = $user->display_name;
            }
            return $name;
        }



        public function emailShortCodeParser($ticket_id, $customer_id, $agent_id, $msg)
        {
            preg_match_all('/\{(.*?)\}/', $msg, $matches);
            if (is_array($matches[1])) {
                if (sizeof($matches[1]) > 0) {
                    foreach ($matches[1] as $shortcode) {
                        // Replace Predefined Shortcode
                        if ($shortcode == "ticket_id") {
                            $msg = str_replace("{" . $shortcode . "}", $ticket_id, $msg);
                        }
                        if ($shortcode == "customer_name") {
                            $msg = str_replace("{" . $shortcode . "}", $this->getUserDisplayName(get_userdata($customer_id)), $msg);
                        }
                        if ($shortcode == "staff_name") {
                            $msg = str_replace("{" . $shortcode . "}", $this->getUserDisplayName(get_userdata($agent_id)), $msg);
                        }
                    }
                }
            }
            return $msg;
        }


        public function getCustomersWooOrders($user_id)
        {
            $orders = array();
            if (class_exists('WooCommerce')) {
                $customer_orders = get_posts(
                    array(
                        'meta_key' => '_customer_user',
                        'meta_value' => $user_id,
                        'post_type' => 'shop_order',
                        'post_status' => array_keys(wc_get_order_statuses()),
                        'numberposts' => -1
                    )
                );

                foreach ($customer_orders as $customer_order) {
                    $orderInfo = wc_get_order($customer_order);
                    $orders[] = array(
                        "id" => $orderInfo->get_id(),
                        "admin_url" => get_admin_url() . "post.php?post=" . $orderInfo->get_id() . "&action=edit",
                        "date" => $orderInfo->get_date_created()->date_i18n('d M Y'),
                        "status" => $orderInfo->get_status(),
                    );
                }
            }
            return $orders;
        }


        public function getCustomersEddOrders($user_id)
        {
            $orders = array();
            if (class_exists('Easy_Digital_Downloads')) {
                $customer_orders = edd_get_orders(
                    array(
                        'user_id' => $user_id,
                    )
                );

                foreach ($customer_orders as $customer_order) {
                    $orders[] = array(
                        "id" => $customer_order->id,
                        "admin_url" => get_admin_url() . "edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=" . $customer_order->id,
                        "date" => get_date_from_gmt($customer_order->date_created, 'd M Y'),
                        "status" => $customer_order->status,
                    );
                }
            }
            return $orders;
        }


        public function hive_lite_support_esc_string($str)
        {
            $str = str_replace('&', '::hive_lite_support_amp::', $str);
            $str = str_replace('<', '::hive_lite_support_left_arrow::', $str);
            $str = str_replace('>', '::hive_lite_support_right_arrow::', $str);
            $str = str_replace('"', '::hive_lite_support_dbl_quote::', $str);
            $str = str_replace("'", '::hive_lite_support_sin_quote::', $str);
            $str = str_replace("`", '::hive_lite_support_grave::', $str);
            $str = str_replace('\\', '::hive_lite_support_backslash::', $str);
            return $str;
        }

        public function hive_lite_support_unesc_string($str)
        {
            $str = str_replace('::hive_lite_support_amp::', '&amp;', $str);
            $str = str_replace('::hive_lite_support_left_arrow::', '&lt;', $str);
            $str = str_replace('::hive_lite_support_right_arrow::', '&gt;', $str);
            $str = str_replace('::hive_lite_support_dbl_quote::', '&quot;', $str);
            $str = str_replace('::hive_lite_support_sin_quote::', "&#039;", $str);
            $str = str_replace('::hive_lite_support_grave::', "&#96;", $str);
            $str = str_replace('::hive_lite_support_backslash::', "&#92;", $str);
            return $str;
        }

        public function hive_lite_support_codify_string($str)
        {
            $str = str_replace('&amp;', '&', $str);
            $str = str_replace('&lt;', '<', $str);
            $str = str_replace('&gt;', '>', $str);
            $str = str_replace('&quot;', '"', $str);
            $str = str_replace('&#039;', "'", $str);
            $str = str_replace('&#96;', "`", $str);
            $str = str_replace('&#92;', "\\", $str);
            return $str;
        }

        public function hive_lite_support_unesc_and_codify_string($str)
        {
            $str = str_replace('::hive_lite_support_amp::', '&', $str);
            $str = str_replace('::hive_lite_support_left_arrow::', '<', $str);
            $str = str_replace('::hive_lite_support_right_arrow::', '>', $str);
            $str = str_replace('::hive_lite_support_dbl_quote::', '"', $str);
            $str = str_replace('::hive_lite_support_sin_quote::', "'", $str);
            $str = str_replace('::hive_lite_support_grave::', "`", $str);
            $str = str_replace('::hive_lite_support_backslash::', "\\", $str);
            return $str;
        }


        public function sanitize_global_requests($function, $array)
        {
            return array_map($function, $array);
        }

        public function is_email_valid($email)
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        public function get_gmt_time_different($timeStr)
        {
            $current_gmt_time = get_date_from_gmt(gmdate("Y/m/d H:i:s", time() + date("Z")), 'Y-m-d H:i:s');
            $time1 = new DateTime(get_date_from_gmt($timeStr, 'Y-m-d H:i:s'));
            $time2 = new DateTime($current_gmt_time);
            $timediff = $time1->diff($time2);
            if ($timediff->format("%y") > 0) {
                return $timediff->format("%y") . "y" . ($timediff->format("%y") > 1 ? "" : "") . " ago";
            } else if ($timediff->format("%m") > 0) {
                return $timediff->format("%m") . "m" . ($timediff->format("%m") > 1 ? "" : "") . " ago";
            } else if ($timediff->format("%d") > 0) {
                return $timediff->format("%d") . "d" . ($timediff->format("%d") > 1 ? "" : "") . " ago";
            } else if ($timediff->format("%h") > 0) {
                return $timediff->format("%h") . "h" . ($timediff->format("%h") > 1 ? "" : "") . " ago";
            } else if ($timediff->format("%i") > 0) {
                return $timediff->format("%i") . "m" . ($timediff->format("%i") > 1 ? "" : "") . " ago";
            } else if ($timediff->format("%s") > 0) {
                return $timediff->format("%s") . "s" . ($timediff->format("%s") > 1 ? "" : "") . " ago";
            } else {
                return "0s ago";
            }
        }
    }
}

// HsHelp::add_mailbox();