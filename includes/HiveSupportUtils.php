<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportUtils')) {
    class HiveSupportUtils
    {

        public $base_admin;
        public static $chat_cookie_name;
        public static $token_length = 30;

        public function __construct($base_admin)
        {
            $this->base_admin = $base_admin;
            self::$chat_cookie_name = HiveSupportUtils::hs_chat_cooike_name();

            // add_filter('cron_schedules', [$this,'hiveAddCronIntervals']);

        }

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
        public static function get_woo_orders_by_user_id($user_id)
        {
            $args = array(
                'customer_id' => $user_id,
                'limit' => 10,
                'orderby' => 'date',
                'order' => 'DESC',
                'status' => array('wc-pending', 'wc-processing', 'wc-completed'),
            );
            $orders_data = array();

            if (class_exists('WooCommerce')) {
                $orders = wc_get_orders($args);
                if (!empty($orders)) {
                    // Loop through the orders and access order data
                    foreach ($orders as $order) {
                        $this_item = array();
                        $this_item['ID'] = $order->get_id();
                        $this_item['Date'] = $order->get_date_created();
                        $this_item['Total'] = $order->get_total();
                        $this_item['Status'] = $order->get_status();
                        $this_item['admin_url'] = get_admin_url() . 'post.php?post=' . $order->get_id() . '&action=edit';
                        $orders_data[] = $this_item;
                    }
                }
            }
            return $orders_data;
        }
        
        public static function add_mailbox()
        {
            // $utils = new HiveSupportUtils(null);
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

                HiveSupportUtils::insert_mailbox($mailbox);
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

            if (!HiveSupportUtils::mailbox_exist($mailbox['email'])) {
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

        public static function getTheArticles($args)
        {
            // $query = new WP_Query( $args );

            $articles = [];

            // The Query.
            $the_query = new WP_Query($args);

            // The Loop.
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();

                    $article = [];
                    $title = get_the_title();
                    $link  = get_the_permalink();
                    $id    = get_the_ID();
                    $article['title'] = $title;
                    $article['link'] = $link;
                    $article['id'] = $id;

                    $articles[] = $article;
                }
            }
            // Restore original Post Data.
            wp_reset_postdata();

            return $articles;
        }
        public static function get_post_title_and_contents($args = [])
        {
            $final_data = '';
            $i = 1;
            $the_query = new WP_Query($args);

            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();

                    $id    = get_the_ID();
                    $title = get_the_title();
                    $pure_title = wp_strip_all_tags($title, true);
                    $content = get_the_content();
                    $pure_content = wp_strip_all_tags($content, true);

                    $final_data .= "Topic $i) " . $pure_title . "\n";
                    $final_data .= "Explanation $i) " . $pure_content . "\n\n";
                    $i++;
                }
            }
            // Restore original Post Data.
            wp_reset_postdata();

            return $final_data;
        }
        public static function get_post_title_and_contents_x($args)
        {
            // $query = new WP_Query( $args );

            /**
             *Topic 1) Topic one title
             *Explanation 1) Explanation one details
             *Topic 2) Topic two title
             *Explanation 2) Explanation two details
             *Topic 3) Topic three title
             *Explanation 3) Explanation three details
             */


            $final_data = '';

            $i = 1;

            // The Query.
            $the_query = new WP_Query($args);

            // The Loop.
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $id    = get_the_ID();
                    $title = get_the_title();
                    $content = get_the_content();
                    $pure_content = wp_strip_all_tags($content);

                    $final_data .= "Topic $i)" . wp_strip_all_tags($title) . "\n";
                    $final_data .= "Explanation $i)" . wp_strip_all_tags($pure_content) . "\n\n";
                }
            }
            // Restore original Post Data.
            wp_reset_postdata();

            return $final_data;
        }
        public  static function get_post_type_array_from_select_options($post_type_options)
        {
            $post_types_settings = [];
            $docs_post_types = ['eazydocs', 'betterdocs', 'wedocs'];
            if (!empty($post_type_options)) {
                if (is_array($post_type_options)) {
                    foreach ($post_type_options as $key => $item) {
                        foreach ($item as $key => $value) {
                            if ($key == 'value') {
                                if (!in_array($value, $docs_post_types)) {
                                    $post_types_settings[] = $value;
                                } else {
                                    if (!in_array('docs', $post_types_settings)) {
                                        $post_types_settings[] = 'docs';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $post_types_settings;
        }
        public static function get_help_post_types()
        {
            $post_types_settings = [];
            $hs_help_settings = '';
            $option_name = 'hs_help_tab_data';
            $docs_post_types = ['eazydocs', 'betterdocs', 'wedocs'];
            $get_hs_help_settings = get_option($option_name);
            if (!empty($get_hs_help_settings)) {
                $hs_help_settings = $get_hs_help_settings;
                $hs_help_settings = stripslashes($hs_help_settings);
                $hs_help_settings = json_decode($hs_help_settings, true);
                if (isset($hs_help_settings['post_types']) && !empty($hs_help_settings['post_types'])) {

                    $get_post_types = $hs_help_settings['post_types'];

                    if (!empty($get_post_types) && is_array($get_post_types)) {
                        foreach ($get_post_types as $key => $item) {
                            foreach ($item as $key => $value) {
                                if ($key == 'value') {
                                    if (!in_array($value, $docs_post_types)) {
                                        $post_types_settings[] = $value;
                                    } else {
                                        if (!in_array('docs', $post_types_settings)) {
                                            $post_types_settings[] = 'docs';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $post_types_settings;
        }

        public static function generateRandomSecret()
        {
            $length = 30;
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
        public static function get_the_guest()
        {
            return HiveSupportUtils::get_cookie(self::$chat_cookie_name) ?? '';
        }
        public static function hs_domain_str()
        {
            $url = site_url();
            $dom = parse_url($url);
            $url = $dom['host'];
            $url = str_replace('.', '', $url);
            if (isset($dom['path']) and !empty($dom['path'])) {
                $url .= $dom['path'];
                $url = str_replace('/', '', $url);
            }
            return $url;
        }
        public static function hs_chat_cooike_name()
        {
            $url = HiveSupportUtils::hs_domain_str();

            $fvc  = 'hvs_gst';
            $fvc .= '_' . $url;
            return $fvc;
        }

        public static function set_cookie($name, $value, $day = 30)
        {
            // $url = '_'.HiveSupportUtils::hs_domain_str();
            // $name .= $url;
            setcookie($name, $value, time() + (86400 * $day), COOKIEPATH, COOKIE_DOMAIN);
        }
        public static function get_cookie($name)
        {
            // $url = '_'.HiveSupportUtils::hs_domain_str();
            // $name .= $url;
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
        }
        public static function rem_cookie($name)
        {
            $url = '_' . HiveSupportUtils::hs_domain_str();
            $name .= $url;
            setcookie($name, '', time() - 3600);
        }

        /**
         * Undocumented function
         *
         * @param [type] $ip
         * @param string $purpose
         * @param boolean $deep_detect
         * @return void
         * 
         * echo ip_info("173.252.110.27", "Country"); // United States
         *echo ip_info("173.252.110.27", "Country Code"); // US
         *echo ip_info("173.252.110.27", "State"); // California
         *echo ip_info("173.252.110.27", "City"); // Menlo Park
         *echo ip_info("173.252.110.27", "Address"); // Menlo Park, California, United States

         *print_r(ip_info("173.252.110.27", "Location")); // Array ( [city] => Menlo Park [state] => California [country] => United States *[country_code] => US [continent] => North America [continent_code] => NA )
         * 
         * echo ip_info("Visitor", "Country"); // India
         *echo ip_info("Visitor", "Country Code"); // IN
         *echo ip_info("Visitor", "State"); // Andhra Pradesh
         *echo ip_info("Visitor", "City"); // Proddatur
         *echo ip_info("Visitor", "Address"); // Proddatur, Andhra Pradesh, India

         *print_r(ip_info("Visitor", "Location")); // Array ( [city] => Proddatur [state] => Andhra Pradesh [country] => India *[country_code] => IN [continent] => Asia [continent_code] => AS )
         */
        public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
        {
            $output = NULL;
            if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
                $ip = $_SERVER["REMOTE_ADDR"];
                if ($deep_detect) {
                    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
            $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
            $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
            $continents = array(
                "AF" => "Africa",
                "AN" => "Antarctica",
                "AS" => "Asia",
                "EU" => "Europe",
                "OC" => "Australia (Oceania)",
                "NA" => "North America",
                "SA" => "South America"
            );
            if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
                $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
                if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                    switch ($purpose) {
                        case "location":
                            $output = array(
                                "city"           => @$ipdat->geoplugin_city,
                                "state"          => @$ipdat->geoplugin_regionName,
                                "country"        => @$ipdat->geoplugin_countryName,
                                "country_code"   => @$ipdat->geoplugin_countryCode,
                                "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                                "continent_code" => @$ipdat->geoplugin_continentCode
                            );
                            break;
                        case "address":
                            $address = array($ipdat->geoplugin_countryName);
                            if (@strlen($ipdat->geoplugin_regionName) >= 1)
                                $address[] = $ipdat->geoplugin_regionName;
                            if (@strlen($ipdat->geoplugin_city) >= 1)
                                $address[] = $ipdat->geoplugin_city;
                            $output = implode(", ", array_reverse($address));
                            break;
                        case "city":
                            $output = @$ipdat->geoplugin_city;
                            break;
                        case "state":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "region":
                            $output = @$ipdat->geoplugin_regionName;
                            break;
                        case "country":
                            $output = @$ipdat->geoplugin_countryName;
                            break;
                        case "countrycode":
                            $output = @$ipdat->geoplugin_countryCode;
                            break;
                    }
                }
            }
            return $output;
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


        public static function getCustomersEddOrders($user_id)
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
                        "ID" => $customer_order->id,
                        "admin_url" => get_admin_url() . "edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=" . $customer_order->id,
                        "Date" => get_date_from_gmt($customer_order->date_created, 'd M Y'),
                        "Status" => $customer_order->status,
                        "Total" => $customer_order->total,
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

        public static function has_permission()
        {
            $lk = get_option('hive_lite_support_license_key');
            return hash_hmac('md5', 'valid', $lk) == get_option('hive_lite_support_skey');
        }
    }
}

// HiveSupportUtils::add_mailbox();