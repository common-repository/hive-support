<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportAdminAjax')) {
    class HiveSupportAdminAjax
    {

        public $base_admin;
        public $title_key;
        public $message_key;
        public $priority_key;
        public $ticket_fields_key = 'hive_lite_support_ticketfield_settings';
        public $email_templates_key = 'hs_email_templates';
        public $ticket_fields_data;
        public $ticketfields = [];
        public $all_custom_fields_key = [];
        function __construct($base_admin)
        {
            $this->base_admin = $base_admin;

            // Make them array ------- Optimaztion
            // Default fields key
            $this->title_key = 'hs_ticket_title';
            $this->message_key = 'hs_ticket_message';
            $this->priority_key = 'hs_ticket_priority';

            // Custom fields
            $this->ticket_fields_data = get_option($this->ticket_fields_key);
            $this->ticketfields = json_decode($this->ticket_fields_data, true);

            // Set all ticket field keys
            $this->set_all_custom_fields_keys($this->ticketfields);

            // Run all actions
            $this->hive_lite_support_backend_all_actions();
        }

        function set_all_custom_fields_keys($fields)
        {
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $this->all_custom_fields_key[] = $field['metakey'];
                }
            }
        }

        function hive_lite_support_backend_all_actions()
        {

            add_action('wp_ajax_hive_lite_support_list_tickets', array($this, 'hive_lite_support_list_tickets'));
            add_action('wp_ajax_hive_lite_support_list_users', array($this, 'hive_lite_support_list_users'));
            add_action('wp_ajax_hive_lite_support_list_thread', array($this, 'hive_lite_support_list_thread'));
            add_action('wp_ajax_hive_lite_support_thread_reply', array($this, 'hive_lite_support_thread_reply'));
            add_action('wp_ajax_hive_lite_support_thread_close', array($this, 'hive_lite_support_thread_close'));
            add_action('wp_ajax_hive_lite_support_thread_property_update', array($this, 'hive_lite_support_thread_property_update'));
            add_action('wp_ajax_hive_lite_support_delete_thread_response', array($this, 'hive_lite_support_delete_thread_response'));
            add_action('wp_ajax_hive_lite_support_list_thread_activities', array($this, 'hive_lite_support_list_thread_activities'));
            add_action('wp_ajax_hs_get_woo_orders_by_user_id', array($this, 'hs_get_woo_orders_by_user_id'));
            add_action('wp_ajax_hs_get_edd_orders_by_user_id', array($this, 'hs_get_edd_orders_by_user_id'));

            add_action('wp_ajax_hive_lite_support_add_todo', array($this, 'hive_lite_support_add_todo'));
            add_action('wp_ajax_hive_lite_support_list_todos', array($this, 'hive_lite_support_list_todos'));
            add_action('wp_ajax_hive_lite_support_delete_todo', array($this, 'hive_lite_support_delete_todo'));

            add_action('wp_ajax_hive_lite_support_list_agents', array($this, 'hive_lite_support_list_agents'));

            add_action('wp_ajax_hive_lite_support_add_agent', array($this, 'hive_lite_support_add_agent'));
            add_action('wp_ajax_nopriv_hive_lite_support_add_agent', array($this, 'hive_lite_support_add_agent'));

            add_action('wp_ajax_hive_lite_support_update_agent', array($this, 'hive_lite_support_update_agent'));
            add_action('wp_ajax_nopriv_hive_lite_support_update_agent', array($this, 'hive_lite_support_update_agent'));

            add_action('wp_ajax_hive_lite_support_delete_agent', array($this, 'hive_lite_support_delete_agent'));

            add_action('wp_ajax_hive_lite_support_list_ticket_states', array($this, 'hive_lite_support_list_ticket_states'));
            add_action('wp_ajax_hive_lite_support_list_agent_performances', array($this, 'hive_lite_support_list_agent_performances'));

            add_action('wp_ajax_hive_lite_support_list_activities', array($this, 'hive_lite_support_list_activities'));

            add_action('wp_ajax_hive_lite_support_ticket_fields_save', array($this, 'hive_lite_support_ticket_fields_save'));
            add_action('wp_ajax_hive_lite_support_email_notification_save', array($this, 'hive_lite_support_email_notification_save'));



            // React ajax 
            add_action('wp_ajax_hive_lite_support_add_mailboxes', array($this, 'hive_lite_support_add_mail'));
            add_action('wp_ajax_hive_lite_support_get_mailboxes', array($this, 'hive_lite_support_get_mailboxes'));
            add_action('wp_ajax_hive_lite_support_save_ticketfields', array($this, 'hive_lite_support_save_ticketfields'));
            add_action('wp_ajax_hive_lite_support_get_ticketfields_frontend', array($this, 'hive_lite_support_get_ticketfields_frontend'));
            add_action('wp_ajax_hive_lite_support_get_ticketfields', array($this, 'hive_lite_support_get_ticketfields'));

            add_action('wp_ajax_add_ticket_from_customer_panel', array($this, 'add_ticket_from_customer_panel'));

            add_action('wp_ajax_get_tickets_by_mailbox', array($this, 'get_tickets_by_mailbox_frontend'));
            add_action('wp_ajax_hive_lite_support_get_ticket_replies', array($this, 'hive_lite_support_get_ticket_replies_frontend'));
            add_action('wp_ajax_hive_lite_support_add_replay_from_frontend', array($this, 'hive_lite_support_add_replay_from_frontend'));
            add_action('wp_ajax_hive_lite_support_add_replay_from_backend', array($this, 'hive_lite_support_add_replay_from_backend'));
            add_action('wp_ajax_hive_lite_support_get_all_ticket_backend', array($this, 'hive_lite_support_get_all_ticket_backend'));
            add_action('wp_ajax_hive_lite_support_delete_tickets', array($this, 'hive_lite_support_delete_tickets'));
            add_action('wp_ajax_hive_lite_support_close_tickets', array($this, 'hive_lite_support_close_tickets'));

            add_action('wp_ajax_hive_lite_support_change_status', array($this, 'hive_lite_support_change_status'));
            add_action('wp_ajax_hive_lite_support_change_assignee', array($this, 'hive_lite_support_chnage_assignee'));
            add_action('wp_ajax_hive_lite_support_change_priority', array($this, 'hive_lite_support_change_priority'));

            add_action("wp_ajax_hive_lite_support_fetch_reports", array($this, 'hive_lite_support_fetch_reports'));

            add_action("wp_ajax_hive_lite_support_get_all_binbox", array($this, 'hive_lite_support_get_all_binbox'));

            add_action("wp_ajax_hive_lite_support_get_all_stuffs", array($this, 'hive_lite_support_get_all_stuffs'));

            add_action("wp_ajax_hive_lite_support_get_all_mailboxes", array($this, 'hive_lite_support_get_all_mailboxes'));
            add_action("wp_ajax_hive_lite_support_add_binbox", array($this, 'hive_lite_support_add_binbox'));
            add_action("wp_ajax_hive_lite_support_update_binbox", array($this, 'hive_lite_support_update_binbox'));
            add_action("wp_ajax_hive_lite_support_delete_binbox", array($this, 'hive_lite_support_delete_binbox'));

            add_action("wp_ajax_hive_lite_support_get_all_email_templates", array($this, 'hive_lite_support_get_all_email_templates'));
            add_action("wp_ajax_hive_lite_support_update_email_templates", array($this, 'hive_lite_support_update_email_templates'));

            add_action("wp_ajax_hive_lite_support_delete_ticket_replay", array($this, 'hive_lite_support_delete_ticket_replay'));

            add_action("wp_ajax_hive_lite_support_backend_fetch_activities", array($this, 'hive_lite_support_backend_fetch_activities'));

            add_action("wp_ajax_hive_lite_support_backend_fetch_activities_by_ticketid", array($this, 'hive_lite_support_backend_fetch_activities_by_ticketid'));
            add_action("wp_ajax_hive_lite_support_set_seen_status", array($this, 'hive_lite_support_set_seen_status'));
        }

        function hs_get_woo_orders_by_user_id()
        {

            // $user_id = 5;

            $user_id = $_POST['user_id'];

            $orders = HiveSupportUtils::get_woo_orders_by_user_id($user_id);

            $data = array(
                'orders' => $orders,
                'sucess' => 'yes',
                'user_id' => $user_id,
            );

            wp_send_json_success($data);
            wp_die();
        }
        function hs_get_edd_orders_by_user_id()
        {

            // $user_id = 5;

            $user_id = $_POST['user_id'];

            // $orders = HiveSupportUtils::get_woo_orders_by_user_id($user_id);

            $orders = HiveSupportUtils::getCustomersEddOrders($user_id);

            $data = array(
                'orders' => $orders,
                'sucess' => 'yes',
                'user_id' => $user_id,
            );

            wp_send_json_success($data);
            wp_die();
        }


        function hive_lite_support_backend_fetch_activities_by_ticketid()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/activities/fetch_activities_by_ticketid.php';
            wp_die();
        }
        function hive_lite_support_set_seen_status() {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/ticket/ticket_seen_status.php';
            wp_die();
        }
        function hive_lite_support_backend_fetch_activities()
        {

            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/activities/fetch_activities.php';
            wp_die();
        }

        function hive_lite_support_delete_ticket_replay()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/reply/delete_ticket_reply.php';
            wp_die();
        }
        function get_staff_data_P($user_data, $user_metadata)
        {
            global $wpdb;
            $prefix = $wpdb->prefix;

            // Prepare sql for user data
            $users_query = '';
            foreach ($user_data as $data) {
                $users_query .= "{$prefix}users.{$data},";
            }

            // Prepare sql for user meta data
            $users_meta_query = [];
            foreach ($user_metadata as $data) {
                $users_meta_query[] = "(SELECT meta_value FROM {$prefix}usermeta WHERE user_id = {$prefix}users.ID AND meta_key = '{$data}') as {$data}";
            }

            $users_meta_sql = implode(",", $users_meta_query);

            $hive_lite_support_stuffs = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT {$users_query}
                    {$users_meta_sql}
                    FROM {$prefix}users 
                    INNER JOIN  {$prefix}usermeta ON {$prefix}users.ID = {$prefix}usermeta.user_id 
                    WHERE {$prefix}usermeta.meta_key = %s AND {$prefix}usermeta.meta_value LIKE '%hive_support_staff%'",
                    array(
                        $prefix . 'capabilities'
                    )
                ),
                'ARRAY_A'
            );
            return $hive_lite_support_stuffs;
        }

        function get_staff_data_chat($user_data, $user_metadata)
        {
            global $wpdb;
            $prefix = $wpdb->prefix;

            // Prepare sql for user data
            $users_query = '';
            foreach ($user_data as $data) {
                $users_query .= "{$prefix}users.{$data},";
            }

            // Prepare LEFT JOINs for user meta data
            $users_meta_query = [];
            foreach ($user_metadata as $data) {
                $users_meta_query[] = "MAX(CASE WHEN {$prefix}usermeta.meta_key = '{$data}' THEN {$prefix}usermeta.meta_value END) as {$data}";
            }

            $users_meta_sql = implode(",", $users_meta_query);

            $hive_lite_support_stuffs = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT {$users_query}
                    {$users_meta_sql}
                    FROM {$prefix}users 
                    LEFT JOIN {$prefix}usermeta ON {$prefix}users.ID = {$prefix}usermeta.user_id 
                    WHERE {$prefix}usermeta.meta_key = %s AND {$prefix}usermeta.meta_value LIKE '%%hive_support_staff%%'
                    GROUP BY {$prefix}users.ID",
                    array(
                        $prefix . 'capabilities'
                    )
                ),
                'ARRAY_A'
            );
            return $hive_lite_support_stuffs;
        }
        function get_staff_data_x($user_data, $user_metadata)
        {
            global $wpdb;
            $prefix = $wpdb->prefix;

            // Prepare sql for user data
            $users_query = '';
            foreach ($user_data as $data) {
                $users_query .= "{$prefix}users.{$data},";
            }

            // Prepare sql for user meta data
            $users_meta_query = [];
            foreach ($user_metadata as $data) {
                $users_meta_query[] = "(SELECT meta_value FROM {$prefix}usermeta WHERE user_id = {$prefix}users.ID AND meta_key = '{$data}') as {$data}";
            }

            $users_meta_sql = implode(",", $users_meta_query);

            $hive_lite_support_stuffs = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT {$users_query}
                    {$users_meta_sql}
                    FROM {$prefix}users 
                    INNER JOIN  {$prefix}usermeta ON {$prefix}users.ID = {$prefix}usermeta.user_id 
                    WHERE {$prefix}usermeta.meta_key = %s AND {$prefix}usermeta.meta_value LIKE '%hive_support_staff%'",
                    array(
                        $prefix . 'capabilities'
                    )
                ),
                'ARRAY_A'
            );
            return $hive_lite_support_stuffs;
        }
        function get_staff_data_simple_ok()
        {


            $users_columns = 'display_name, user_email, user_nicename';
            $meta_keys = array(
                'first_name',
                'last_name',
                'hs_agent_slack_id',
                'hs_agent_telegram_id',
                'nickname',
                'hs_agent_job_title',
                'hs_agent_permissions'
            );


            global $wpdb;
            $prefix = $wpdb->prefix;

            // Step 1: Get all user IDs with the role 'Hive Support Staff'
            $user_ids_query = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT user_id FROM {$prefix}usermeta WHERE meta_key = %s AND meta_value LIKE '%hive_support_staff%'",
                    array(
                        $prefix . 'capabilities'
                    )
                )
            );

            // Step 2: Get 'display_name', 'user_email', 'user_nicename' from $prefix .users
            $users_data = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT ID, {$users_columns} FROM {$prefix}users WHERE ID IN (" . implode(',', $user_ids_query) . ")"
                ),
                'ARRAY_A'
            );

            // Step 3: Get additional meta values and Build the final array
            $result_array = array();

            foreach ($users_data as $user) {
                $user_meta_query = array();

                // Get additional meta values for each user
                foreach ($meta_keys as $meta_key) {
                    $user_meta_query[$meta_key] = get_user_meta($user['ID'], $meta_key, true);
                }

                // Combine user data and additional meta values
                $result_array[] = array(
                    "ID" => $user['ID'],
                    "display_name" => $user['display_name'],
                    "user_email" => $user['user_email'],
                    "user_nicename" => $user['user_nicename'],
                    "first_name" => $user_meta_query['first_name'],
                    "last_name" => $user_meta_query['last_name'],
                    "nickname" => $user_meta_query['nickname'],
                    "hs_agent_slack_id" => $user_meta_query['hs_agent_slack_id'],
                    "hs_agent_telegram_id" => $user_meta_query['hs_agent_telegram_id'],
                    "hs_agent_job_title" => $user_meta_query['hs_agent_job_title'],
                    "hs_agent_permissions" => $user_meta_query['hs_agent_permissions'],
                );
            }

            // $result_array now contains the final associative array with the required data
            return $result_array;
        }
        function get_staff_data_optimzed()
        {
            global $wpdb;
            $prefix = $wpdb->prefix;

            // Dynamic user data columns
            $user_data = [
                'ID',
                'display_name',
                'user_email',
                'user_nicename'
            ];

            // Combine all necessary meta keys for the final result
            $meta_keys = array(
                'first_name',
                'last_name',
                'hs_agent_slack_id',
                'hs_agent_telegram_id',
                'nickname',
                'hs_agent_job_title',
                'hs_agent_permissions'
            );

            // Build the SELECT clause for user meta data
            $user_meta_select = implode(",", array_map(function ($key) use ($prefix) {
                return "(SELECT MAX(meta_value) FROM {$prefix}usermeta WHERE user_id = {$prefix}users.ID AND meta_key = '{$key}') as {$key}";
            }, $meta_keys));

            // Build the SELECT clause for dynamic user data
            $user_data_select = implode(",", array_map(function ($column) use ($prefix) {
                return "{$prefix}users.{$column}";
            }, $user_data));

            // Combine both user data and user meta data in the final query
            $query = $wpdb->prepare(
                "SELECT {$user_data_select}, {$user_meta_select}
                FROM {$prefix}users
                WHERE {$prefix}users.ID IN (
                    SELECT user_id
                    FROM {$prefix}usermeta
                    WHERE meta_key = %s AND meta_value LIKE '%hive_support_staff%'
                )",
                array(
                    $prefix . 'capabilities'
                )
            );

            // Execute the query and fetch the results
            $hive_lite_support_stuffs = $wpdb->get_results($query, 'ARRAY_A');

            // The $hive_lite_support_stuffs array now contains the required data
            return $hive_lite_support_stuffs;
        }
        function get_staff_data_complex()
        {
            global $wpdb;
            $prefix = $wpdb->prefix;

            // Dynamic user data columns
            $user_data = [
                'ID',
                'display_name',
                'user_email',
                'user_nicename'
            ];

            // Combine all necessary meta keys for the final result
            $meta_keys = array(
                'first_name',
                'last_name',
                'hs_agent_slack_id',
                'hs_agent_telegram_id',
                'nickname',
                'hs_agent_job_title',
                'hs_agent_permissions'
            );

            // Additional ticket-related information
            $ticket_info = [
                'assgined_ticket' => 'COUNT(DISTINCT t1.ticket_id)',
                'solved_ticket' => 'COUNT(DISTINCT CASE WHEN LOWER(t1.ticket_status) = LOWER("Closed") THEN t1.ticket_id END)'
            ];

            // Build the SELECT clause for user meta data
            $user_meta_select = implode(",", array_map(function ($key) use ($prefix) {
                return "MAX(CASE WHEN {$prefix}usermeta.meta_key = '{$key}' THEN {$prefix}usermeta.meta_value END) as {$key}";
            }, $meta_keys));

            // Build the SELECT clause for dynamic user data
            $user_data_select = implode(",", array_map(function ($column) use ($prefix) {
                return "{$prefix}users.{$column}";
            }, $user_data));

            // Build the SELECT clause for ticket-related information
            $ticket_info_select = implode(",", array_map(function ($key, $value) use ($prefix) {
                return "{$value} as {$key}";
            }, array_keys($ticket_info), $ticket_info));

            // Combine both user data, user meta data, and ticket-related information in the final query
            $query = $wpdb->prepare(
                "SELECT {$user_data_select}, {$user_meta_select}, {$ticket_info_select}
                FROM {$prefix}users
                LEFT JOIN {$prefix}usermeta ON {$prefix}users.ID = {$prefix}usermeta.user_id
                LEFT JOIN {$prefix}hs_tickets t1 ON {$prefix}users.ID = t1.agent_id
                WHERE {$prefix}usermeta.meta_key = %s AND {$prefix}usermeta.meta_value LIKE '%hive_support_staff%'
                GROUP BY {$prefix}users.ID",
                array(
                    $prefix . 'capabilities'
                )
            );

            // Execute the query and fetch the results
            $hive_lite_support_stuffs = $wpdb->get_results($query, 'ARRAY_A');

            // The $hive_lite_support_stuffs array now contains the required data
            return $hive_lite_support_stuffs;
        }
        function hive_lite_support_get_all_stuffs()
        {
            // if (current_user_can('hive_support_access_plugin')) {
            // $hive_lite_support_stuffs = $this->get_staff_data($user_data, $user_metadata);
            // $hive_lite_support_stuffs = $this->get_staff_data_ok();
            // $hive_lite_support_stuffs = $this->get_staff_data();

            $hive_lite_support_stuffs = $this->get_staff_data_optimzed();

            // Add Ticekt data
            if (is_array($hive_lite_support_stuffs)) {
                foreach ($hive_lite_support_stuffs as &$stuff) {
                    $stuff['assgined_ticket'] = $this->get_staffs_ticket_count($stuff['ID']);
                    $stuff['solved_ticket'] = $this->get_staffs_resolved_ticket_count($stuff['ID']);
                }
                // Unset the reference to avoid any unintended side effects
                unset($stuff);
            }

            wp_send_json_success($hive_lite_support_stuffs);
            // }

        }
        function get_staffs_ticket_count($agent_id)
        {
            global $wpdb;
            $ticket_table = $wpdb->prefix . 'hs_tickets';
            $query = "SELECT COUNT(*) FROM $ticket_table WHERE agent_id = %d";
            $count_agent_tickets = $wpdb->get_var($wpdb->prepare($query, $agent_id));
            return $count_agent_tickets;
        }
        function get_staffs_resolved_ticket_count($agent_id)
        {
            global $wpdb;
            $ticket_table = $wpdb->prefix . 'hs_tickets';
            $ticket_status = 'Closed';
            $query = "SELECT COUNT(*) FROM $ticket_table WHERE agent_id = %d AND LOWER(ticket_status) = LOWER(%s)";
            $count_closed_agent_tickets = $wpdb->get_var($wpdb->prepare($query, $agent_id, $ticket_status));
            return $count_closed_agent_tickets;
        }



        function get_ticket_count_by_mailbox($id)
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'hs_tickets';
            $results = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE mailbox_id = {$id}");
            return $results;
        }
        function hive_lite_support_get_all_binbox()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/binbox/get_all_binbox.php';
            wp_die();
        }
        
        function emailTemplates()
        {
            $templates = [];

            $name_ids = [
                'to_admin_on_assign',
                'to_admin_on_create',
                'to_admin_on_creply',
                'to_customer_on_closed',
                'to_customer_on_create',
                'to_customer_on_areply',
            ];

            $to_admin = [];
            // Ticket Assigned

            $item1 = [
                'id' => 1,
                'name_id' => 'to_admin_on_assign',
                'title' => 'Ticket Assigned (To Agent)',
                'status' => 'on',
                'subject' => 'Ticket Assigned: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'A ticket {{ticket_title}} has been assigned to you
    
                Ticket Body:
                
                {{ticket_body}}',
            ];

            // Ticket Created
            $item2 = [
                'id' => 2,
                'name_id' => 'to_admin_on_create',
                'title' => 'Ticket Created (To Admin/Agent)',
                'status' => 'on',
                'subject' => 'New Ticket: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'A new ticket {{ticket_title}} has been submitted by {{ticket_user}}
    
                Ticket Body:
                {{ticket_body}}',
            ];
            // Ticket Replied
            $item3 = [
                'id' => 3,
                'name_id' => 'to_admin_on_creply',
                'title' => 'Replied by Customer (To Admin/Agent)',
                'status' => 'on',
                'subject' => 'New Response: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'A new response has been added to ticket {{ticket_title}} by {{ticket_user}}
    
                Ticket Reply Text:
                
                {{replied_text}}
                
                Thanks
                {{site_name}}',
            ];

            $to_admin = [$item1, $item2, $item3];

            $to_customer = [];
            // Ticket Created
            $item1 = [
                'id' => 4,
                'name_id' => 'to_customer_on_create',
                'title' => 'Ticket Created by Agent (To Customer)',
                'status' => '',
                'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'Dear {{ticket_user}},
    
                Your request (#{{ticket_track_id}}) has been received and is being reviewed by our support staff. You will receive a response as soon as possible.
                                
                Thanks,
                {{site_name}}',
            ];

            // Ticket CLosed
            $item2 = [
                'id' => 5,
                'name_id' => 'to_customer_on_closed',
                'title' => 'Ticket Closed by Agent (To Customer)',
                'status' => '',
                'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'Dear {{ticket_user}},
    
                Your ticket (#{{ticket_track_id}}) has been closed.
                
                We hope that the ticket was resolved to your satisfaction.
                
                Thanks,
                {{site_name}}',
            ];
            // Ticket Replied
            $item3 = [
                'id' => 6,
                'name_id' => 'to_customer_on_areply',
                'title' => 'Ticket Replied by Agent (To Customer)',
                'status' => 'on',
                'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
                'content' => 'Dear {{ticket_user}},
    
                One of our team members just replied to your ticket (#{{ticket_track_id}}).
                
                Thanks,
                {{ticket_replied_user}}
                {{site_name}}',
            ];
            $to_customer = [$item1, $item2, $item3];

            $templates = array_merge($to_admin, $to_customer);
            
            return $templates;
        }
        function hive_lite_support_get_all_email_templates()
        {
            $defaultTemplate = $this->emailTemplates();

            $templates = get_option($this->email_templates_key);
            if ($templates) {
                $templates = json_decode(stripslashes($templates), true);
                $defaultTemplate = $templates;
            }

            $data = [];
            $data['templates'] = $defaultTemplate;
            $data['notice'] = 'success';

            wp_send_json_success($data);
        }


        function update_or_add_option($option_name, $new_value)
        {

            // $option_name = 'my_custom_color_option' ;
            // $new_value = 'red';

            if (get_option($option_name) !== false) {
                // The option already exists, so update it.
                $updated = update_option($option_name, $new_value);
                return $updated;
            } else {
                // The option hasn't been created yet, so add it with $autoload set to 'no'.
                $deprecated = null;
                $autoload = 'no';
                $added = add_option($option_name, $new_value, $deprecated, $autoload);
                return $added;
            }
        }

        function hive_lite_support_update_email_templates()
        {

            $templates = sanitize_text_field($_POST['templates']);

            $success = $this->update_or_add_option($this->email_templates_key, $templates);

            $templates = json_decode(stripslashes($templates), true);

            $cute = [
                'one' => 'ONE',
                'two' => 'TWO',
                'three' => 'THREE'
            ];

            $data = [];

            if ($success) {
                $data['notice_type'] = 'success';
            }
            $data['prio'] = 'Hello WPC!';
            $data['templates'] = $templates;
            $data['cute'] = $cute;

            wp_send_json_success($data);
            wp_die();
            exit();
        }


        function hive_lite_support_get_all_mailboxes()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'hs_mailbox';

            // Fetching all rows from the table
            $results = $wpdb->get_results("SELECT id, mailbox_title FROM $table_name", ARRAY_A);
            wp_send_json_success($results);
        }

        function hive_lite_support_add_binbox()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/binbox/add_binbox.php';
            wp_die();
        }
        function hive_lite_support_update_binbox()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/binbox/update_binbox.php';
            wp_die();
        }
        function hive_lite_support_delete_binbox()
        {
            include_once HIVE_LITE_SUPPORT_PATH . 'backend/api/binbox/delete_binbox.php';
            wp_die();
        }

        function update_mailbox_by_id($id, $fallback_id)
        {
            if (!$fallback_id) $fallback_id = NULL;

            // mailbox_id
            global $wpdb;
            $mailbox_table = $wpdb->prefix . 'hs_tickets';
            $where = array('mailbox_id' => $id);
            $data = [
                'mailbox_id' => $fallback_id
            ];
            $result = $wpdb->update($mailbox_table, $data, $where);
            return $result;
        }

        function hive_lite_support_fetch_reports()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/get_reports.php";
            wp_die();
        }

        function hive_lite_support_chnage_assignee()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/change_assignee.php";
            wp_die();
        }
        function hive_lite_support_change_priority()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/change_priority.php";
            wp_die();
        }

        function hive_lite_support_change_status()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/change_status.php";
            wp_die();
        }

        function hive_lite_support_add_replay_from_backend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/reply/add_reply_from_backend.php";
        }

        function hive_lite_support_add_replay_from_frontend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/reply/add_reply_from_frontend.php";
        }

        function hive_lite_support_get_all_ticket_backend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/get_all_ticket_backend.php";
            wp_die();
        }
        function hive_lite_support_delete_tickets()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/delete_tickets.php";
            wp_die();
        }
        function hive_lite_support_close_tickets()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/close_tickets.php";
            wp_die();
        }

        function hive_lite_support_get_ticket_replies_frontend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/get_ticket_replies_frontend.php";
        }
        function get_tickets_by_mailbox_frontend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/get_tickets_by_mailbox_frontend.php";
        }

        function get_form_value_by_key($key)
        {
            // $value = '';
            $field_json = stripslashes($_POST[$key]);
            $field_data = json_decode($field_json, true);
            $value = $field_data['fieldvalue'];
            //  Unset from $_POST
            unset($_POST[$key]);

            return $value;
        }



        function add_ticket_from_customer_panel()
        {

            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/add_ticket_from_customer_panel.php";
            wp_die();
        }


        function hive_lite_support_get_ticketfields()
        {

            // $data = get_option($this->ticket_fields_key);
            wp_send_json_success($this->ticket_fields_data);
            wp_die();
        }
        function hive_lite_support_save_ticketfields()
        {
            if (current_user_can('hive_support_access_plugin')) {
                if ($_POST['ticket_fields']) {
                    update_option($this->ticket_fields_key, stripslashes($_POST['ticket_fields']));
                    wp_send_json_success($_POST['ticket_fields']);
                    wp_die();
                }
            }
        }
        function hive_lite_support_get_ticketfields_frontend()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/get_ticketfields_frontend.php";
            wp_die();
        }
        function hive_lite_support_get_mailboxes()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/mailbox/get_mailboxes.php";
            wp_die();
        }
        function hive_lite_support_add_mail()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/mailbox/add_mailbox.php";
            wp_die();
        }
        function hive_lite_support_list_tickets()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket/list_tickets.php";
            wp_die();
        }

        function hive_lite_support_list_users()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/list_users.php";
            wp_die();
        }
        function hive_lite_support_list_thread()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/list_thread.php";
            wp_die();
        }
        function hive_lite_support_thread_reply()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/thread_reply.php";
            wp_die();
        }
        function hive_lite_support_thread_close()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/thread_reply_close.php";
            wp_die();
        }
        function hive_lite_support_thread_property_update()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/thread_property_update.php";
            wp_die();
        }
        function hive_lite_support_delete_thread_response()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/thread_response_delete.php";
            wp_die();
        }
        function hive_lite_support_list_thread_activities()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/thread/list_thread_activities.php";
            wp_die();
        }

        function hive_lite_support_add_todo()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/todo/add_todo.php";
            wp_die();
        }

        function hive_lite_support_list_todos()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/todo/list_todos.php";
            wp_die();
        }
        function hive_lite_support_delete_todo()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/todo/delete_todo.php";
            wp_die();
        }

        function hive_lite_support_list_agents()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/agent/list_agents.php";
            wp_die();
        }

        function hive_lite_support_add_agent()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/agent/add_agent.php";
            wp_die();
        }


        function hive_lite_support_update_agent()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/agent/update_agent.php";
            wp_die();
        }

        function update_single_user_meta($user_id, $key, $value)
        {
            // Check if the user meta already exists
            if (get_user_meta($user_id, $key, true)) {
                // Update the user meta
                update_user_meta($user_id, $key, $value);
            } else {
                // Add the user meta if it doesn't exist
                add_user_meta($user_id, $key, $value);
            }
        }

        function set_fallback_agent($agentToDelete, $fallbackAgent)
        {
        }
        function hive_lite_support_delete_agent()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/agent/delete_agent.php";
            wp_die();
        }



        function hive_lite_support_list_activities()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/activiities/list_activities.php";
            wp_die();
        }

        function hive_lite_support_list_ticket_states()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/list_ticket_states.php";
            wp_die();
        }
        function hive_lite_support_list_agent_performances()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/agent/list_agent_performances.php";
            wp_die();
        }

        function hive_lite_support_ticket_fields_save()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/ticket_fields_save.php";
            wp_die();
        }
        function hive_lite_support_email_notification_save()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "backend/api/email_notification_save.php";
            wp_die();
        }
    }
}
