<?php

use Composer\Autoload\ClassLoader;



class Hive_rest_api
{
    public $namespace = "hivesupport/v1";

    public $ticket_table;

    function __construct()
    {
        global $wpdb;

        // $this->ticket_table = $wpdb->prefix . 'hs_tickets';

        add_action('rest_api_init', array($this, 'rest_api_handle'));
    }
    function get_rest_arg_query()
    {
        global $wpdb;
        // $ticket_limit = 100;
        // $ticket_table = $wpdb->prefix . 'hs_tickets';

        $query = '';
        if (!empty($priority)) {
            $query .= $wpdb->prepare(" AND priority = %s", $priority);
        }
        if (!empty($agent_id)) {
            $query .= $wpdb->prepare(" AND agent_id = %d", $agent_id);
        }
        if (!empty($customer_id)) {
            $query .= $wpdb->prepare(" AND customer_id = %d", $customer_id);
        }
        if (!empty($mailbox_id)) {
            $query .= $wpdb->prepare(" AND mailbox_id = %d", $mailbox_id);
        }
        if (!empty($ticket_status)) {
            $query .= $wpdb->prepare(" AND ticket_status = %s", $ticket_status);
        }
    }


    function fetch_tickets($priority, $agent_id, $customer_id, $ticket_limit, $ticket_status, $mailbox_id, $current_page)
    {
        global $wpdb;
        $ticket_table = $wpdb->prefix . 'hs_tickets';

        $ticket_cols = 'id, title, content, customer_id, ticket_status, agent_id, mailbox_id, priority,seen_status, updated_at, created_at';

        $count_query = "SELECT COUNT(id) FROM {$ticket_table} WHERE 1=1";

        $query = "SELECT {$ticket_cols} FROM {$ticket_table} WHERE 1=1";

        if (!empty($priority)) {
            $count_query .= $wpdb->prepare(" AND priority = %s", $priority);
            $query .= $wpdb->prepare(" AND priority = %s", $priority);
        }
        if (!empty($agent_id)) {
            $count_query .= $wpdb->prepare(" AND agent_id = %d", $agent_id);
            $query .= $wpdb->prepare(" AND agent_id = %d", $agent_id);
        }
        if (!empty($customer_id)) {
            $count_query .= $wpdb->prepare(" AND customer_id = %d", $customer_id);
            $query .= $wpdb->prepare(" AND customer_id = %d", $customer_id);
        }
        if (!empty($mailbox_id)) {
            $count_query .= $wpdb->prepare(" AND mailbox_id = %d", $mailbox_id);
            $query .= $wpdb->prepare(" AND mailbox_id = %d", $mailbox_id);
        }
        if (!empty($ticket_status)) {
            $count_query .= $wpdb->prepare(" AND ticket_status = %s", $ticket_status);
            $query .= $wpdb->prepare(" AND ticket_status = %s", $ticket_status);
        }

        $total_tickets = $wpdb->get_var($count_query);

        $offset = ($current_page - 1) * $ticket_limit;

        $query .= " ORDER BY id DESC LIMIT {$offset}, {$ticket_limit}";

        $paginated_tickets = $wpdb->get_results(
            $wpdb->prepare($query),
            "ARRAY_A"
        );

        return array(
            'total_tickets' => $total_tickets,
            'paginated_tickets' => $paginated_tickets
        );
    }
    function hs_get_columns($tableName, $excludeColumns)
    {
        global $wpdb;

        // Get all column names for the specified table
        $columns = $wpdb->get_col("DESC {$tableName}", 0);

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        // Return comma-separated column names
        return implode(', ', $filteredColumns);
    }

    function fetch_all_tickets($ticket_limit)
    {

        global $wpdb;
        // $ticket_limit = 100;
        $ticket_table = $wpdb->prefix . 'hs_tickets';

        $ticket_cols = 'id,title,content,customer_id,ticket_status,agent_id,mailbox_id,priority,seen_status,updated_at,created_at';

        $query  = "SELECT {$ticket_cols} FROM $ticket_table ORDER BY id DESC LIMIT {$ticket_limit}";

        $all_tickets = $wpdb->get_results(
            $wpdb->prepare($query),
            "ARRAY_A"
        );
        
        return $all_tickets;
    }

    function tickets_controller($request)
    {
        $params = $request->get_params();

        $priority = '';
        $agent_id = '';
        $customer_id = '';
        $mailbox_id = '';
        $ticket_limit = 100;
        $ticket_status = '';
        $current_page = 1;

        if ($params) {
            $priority = isset($params['priority']) ? $params['priority'] : '';
            $agent_id = isset($params['agent_id']) ? $params['agent_id'] : '';
            $customer_id = isset($params['customer_id']) ? $params['customer_id'] : '';
            $mailbox_id = isset($params['mailbox_id']) ? $params['mailbox_id'] : '';
            $ticket_status = isset($params['status']) ? $params['status'] : '';
            $ticket_limit = isset($params['count']) ? $params['count'] : 4;
            $current_page = isset($params['current_page']) ? $params['current_page'] : 1;
        }

        global $wpdb;
        $ticket_table = $wpdb->prefix . 'hs_tickets';
        $ticket_data = [];

        $totalticketcount = $wpdb->get_var(
            "SELECT COUNT(*) FROM $ticket_table"
        );

        $openticketcount = $this->get_tickets_status_count('Open');
        $waitingticketcount = $this->get_tickets_status_count('Waiting');
        $resolveticketcount = $this->get_tickets_status_count('Closed');

        $all_ticket_datas = $this->fetch_tickets($priority, $agent_id, $customer_id, $ticket_limit, $ticket_status, $mailbox_id, $current_page);
        $all_tickets = $all_ticket_datas['paginated_tickets'];

        $fetched_ticekts = count($all_tickets);
        $total_filtered_tickets = $all_ticket_datas['total_tickets'];
        foreach ($all_tickets as $ticket) {
            $ticket['customerdata'] = $this->get_name_by_user_id($ticket['customer_id']);
            $ticket['agentdata'] = $this->get_name_by_user_id($ticket['agent_id']);
            array_push($ticket_data, $ticket);
        }


        $customers = [];
        $agents = [];
        $mailboxes = [];

        $get_mailboxes = $this->get_mailbox_id_name();

        if ($get_mailboxes) {
            foreach ($get_mailboxes as $mailbox) {
                $mailboxes[$mailbox['id']] = $mailbox['mailbox_title'];
            }
        }

        $all_tickets_data = $this->fetch_all_tickets($ticket_limit);

        foreach ($all_tickets_data as $ticket) {

            // Collect Agents and customers
            if (!isset($customers[$ticket['customer_id']])) {
                $ticket['customerdata'] = $this->get_name_by_user_id($ticket['customer_id']);
                $customers[$ticket['customer_id']] = $ticket['customerdata'];
            }
            if (!isset($agents[$ticket['agent_id']])) {
                $ticket['agentdata'] = $this->get_name_by_user_id($ticket['agent_id']);
                $agents[$ticket['agent_id']] = $ticket['agentdata'];
            }
        }
        
        wp_send_json_success(
            array(
                "tickets" => $ticket_data,
                "alltickets" => (int) $totalticketcount,
                "fetched_ticekts" => (int) $fetched_ticekts,
                "total_filtered_tickets" => (int) $total_filtered_tickets,
                "openticketcount" => (int) $openticketcount,
                "waitingticketcount" => (int) $waitingticketcount,
                "resolveticketcount" =>  (int) $resolveticketcount,
                "customers" => $customers,
                "agents" => $agents,
                "mailboxes" => $mailboxes
            )
        );
    }

    function get_mailbox_id_name()
    {
        global $wpdb;
        $mailbox_table = $wpdb->prefix . 'hs_mailbox';
        $mailbox_cols = 'id,mailbox_title';
        $query  = "SELECT {$mailbox_cols} FROM $mailbox_table";
        $all_mailboxes = $wpdb->get_results(
            $wpdb->prepare($query),
            "ARRAY_A"
        );
        return $all_mailboxes;
    }
    function get_all_hs_gents()
    {

        $agents = get_users(array('role__in' => array('author', 'administrator', 'hive_support_staff')));

        $author_array = array();

        foreach ($agents as $user) {
            $author_array[$user->ID] = $user->display_name ? $user->display_name : $user->user_nicename;
        }

        return $author_array;
    }

    function get_tickets_status_count($status)
    {
        global $wpdb;
        $ticket_table = $wpdb->prefix . 'hs_tickets';
        $status_count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $ticket_table WHERE ticket_status = %s",
                array($status)
            )
        );
        return $status_count;
    }
    
    function get_any_name_by_user_id($id)
    {
        $name = '';

        $user = get_user_by('id', $id);
        if ($user) {
            $name = $user->display_name ? $user->display_name : $user->user_nicename;
        }
        return $name;
    }
    function get_name_by_user_id($id)
    {
        global $wpdb;
        $user_table = $wpdb->prefix . 'users';

        $name = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT display_name FROM $user_table WHERE ID = %d",
                array($id)
            )
        );
        return $name;
    }
    function rest_api_callback_permission($request)
    {
        if (is_user_logged_in()) {
            return new WP_Error('rest_forbidden', esc_html('You are not authorized to access this endpoint.', 'text-domain'), array('status' => 401));
        }
        return true;
    }

    function single_ticket_controller($request)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $ticket_table = $wpdb->prefix . 'hs_tickets';
        $replies_table = $wpdb->prefix . 'hs_conversations';
        $ticket_id = $request['id'];


        $previous_conversations = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id,title,agent_id,created_at FROM $ticket_table WHERE agent_id IS NULL ORDER BY id DESC LIMIT %d",
                array(
                    6
                )
            ),
            'ARRAY_A'
        );

        $hive_lite_support_stuffs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT {$prefix}users.ID,{$prefix}users.display_name FROM {$prefix}users INNER JOIN  {$prefix}usermeta ON {$prefix}users.ID = {$prefix}usermeta.user_id WHERE {$prefix}usermeta.meta_key = %s AND {$prefix}usermeta.meta_value LIKE '%hive_support_staff%'",
                array(
                    $prefix . 'capabilities'
                )
            ),
            'ARRAY_A'
        );

        // $hive_lite_support_stuffs = $wpdb->get_results(
        //     $wpdb->prepare(
        //         "SELECT wp_users.ID,wp_users.display_name FROM wp_users INNER JOIN  wp_usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_key = %s AND wp_usermeta.meta_value LIKE '%hive_support_staff%'",
        //         array(
        //             'wp_capabilities'
        //         )
        //     ),
        //     'ARRAY_A'
        // );


        $tickets_by_id = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $ticket_table WHERE id=%d LIMIT %d",
                array(
                    $ticket_id,
                    1
                )
            ),
            'ARRAY_A'
        );
        $customer_data = '';
        if ($tickets_by_id && $tickets_by_id['customer_id']) {
            $customer_data = get_userdata((int) $tickets_by_id['customer_id']);
        }
        $agent_data = '';
        if ($tickets_by_id && $tickets_by_id['agent_id']) {
            $agent_data = get_userdata((int) $tickets_by_id['agent_id']);
        }

        $ticket_replies = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT id,ticket_id,person_id,content,media_urls,created_at FROM $replies_table  WHERE $replies_table.ticket_id=%d ORDER BY id DESC",
                array(
                    $ticket_id
                )
            ),
            'ARRAY_A'
        );

        $replies_data = [];

        foreach ($ticket_replies as $reply) {
            $userdata = get_userdata((int) $reply['person_id']);
            $reply['useobject'] = $userdata;
            array_push($replies_data, $reply);
        }

        wp_send_json_success(
            array(
                "ticket" => $tickets_by_id,
                "ticket_replies" => $replies_data,
                "customer" => $customer_data,
                "agent" => $agent_data,
                "stuffs" => $hive_lite_support_stuffs,
                "previous_conversation" => $previous_conversations
            )
        );
    }

    function reports_controller()
    {
        global $wpdb;
        $data_interval = 7;
        $date_labels = [];
        $ticket_recieved_data = [];
        $ticket_closed_data = [];

        $ticket_table = $wpdb->prefix . 'hs_tickets';

        for ($i = 1; $i < 40; $i++) {
            $timestamps = strtotime("-{$i} days");
            $dateformat = date('Y-m-d', $timestamps); 

            $queryrecieved = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $ticket_table WHERE DATE(created_at) = %s",
                    array(
                        $dateformat
                    )
                ),
            );


            $queryclosed = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $ticket_table WHERE DATE(created_at)= %s AND ticket_status=%s",
                    array(
                        $dateformat,
                        'Closed'
                    )
                )
            );

            $ticket_recieved_data[] = $queryrecieved;
            $ticket_closed_data[] = $queryclosed;
            $date_labels[] = str_replace("-", "/", $dateformat);
        }




        wp_send_json_success(
            array(
                $ticket_recieved_data,
                $ticket_closed_data,
                $date_labels
            )
        );
    }

    function rest_api_handle()
    {

        //tickets
        $args = array(
            'priority' => [],
            'agent_id' => [],
            'customer_id' => [],
            'mailbox_id' => [],
            'status' => [],
            'count' => [],
            'current_page' => [],
        );
        register_rest_route(
            $this->namespace,
            '/tickets',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'tickets_controller'),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
                'args' => $args,
            )
        );

        //Single ticket
        register_rest_route(
            $this->namespace,
            '/tickets/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array(
                    $this,
                    'single_ticket_controller'
                ),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
            )
        );
        //chats
        $args = array(
            'agent_id' => [],
            'customer_id' => [],
            'status' => [],
            'count' => [],
            'current_page' => [],
        );

        register_rest_route(
            $this->namespace,
            '/chat',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'chats_controller'),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
                'args' => $args,
            )
        );


        // Single ticket
        register_rest_route(
            $this->namespace,
            '/chat/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array(
                    $this,
                    'single_chat_controller'
                ),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
            )
        );

        //reports
        register_rest_route(
            $this->namespace,
            '/reports',
            array(
                'methods' => 'GET',
                'callback' => array(
                    $this,
                    'reports_controller'
                ),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
            )
        );



        //slack
        register_rest_route(
            $this->namespace,
            '/slack-post-url',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_slack_url_data_parsing'),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
            )
        );

        //telegram
        register_rest_route(
            $this->namespace,
            '/telegram-post-url',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_telegram_url_data_parsing'),
                'permission_callback' => array($this, 'rest_api_callback_permission'),
            )
        );
    }
}
new Hive_rest_api();
