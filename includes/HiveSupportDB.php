<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportDB')) {
    class HiveSupportDB
    {
        public static function db_create ()
        {

            global $wpdb;

            $mailbox_table = $wpdb->prefix . 'hs_mailbox';
            $ticket_table = $wpdb->prefix . 'hs_tickets';
            $conversation_table = $wpdb->prefix . 'hs_conversations';
            $ticket_meta = $wpdb->prefix . 'hs_ticketsmeta';
            $ticket_activities = $wpdb->prefix . 'hs_ticket_activities';


            // DB collate
            $db_collate = $wpdb->collate;

            // Ticket activities table
            $ticket_activities_table = "CREATE TABLE IF NOT EXISTS {$ticket_activities} (
                id mediumint(8) unsigned NOT NULL auto_increment,
                ticket_id mediumint(8)   unsigned NOT NULL,
                initiator varchar(100) NOT NULL,
                initiator_role varchar(100) NOT NULL,
                title varchar(200) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )
            COLLATE {$db_collate}";

            // Ticket meta table
            $ticket_meta_table = "CREATE TABLE IF NOT EXISTS {$ticket_meta} (
                meta_id mediumint(8) unsigned NOT NULL auto_increment,
                ticket_id mediumint(8)   unsigned NOT NULL,
                meta_key varchar(100) NOT NULL,
                meta_value varchar(200) NOT NULL,
                PRIMARY KEY (meta_id)
            )
            COLLATE {$db_collate}";

            // Mailbox table
            $mailbox_sql = "CREATE TABLE IF NOT EXISTS {$mailbox_table} (
                id mediumint(8) unsigned NOT NULL auto_increment ,
                mailbox_title varchar(100) NULL,
                support_from_email varchar(100) NOT NULL,
                admin_email varchar(100) NOT NULL,
                email_url varchar(100) NULL,
                mailbox_type varchar(100) NULL  DEFAULT 'web',
                email_port INT(9) NULL,
                email_path varchar(100) NULL,
                email_id varchar(100) NOT NULL,
                email_password varchar(100) NULL,
                PRIMARY KEY  (id)
            )
            COLLATE {$db_collate}";


            // Ticket table
            $ticket_sql = "CREATE TABLE IF NOT EXISTS {$ticket_table}(
                id mediumint(8) unsigned NOT NULL auto_increment ,
                customer_id bigint(20) NULL,
                agent_id bigint(20) unsigned NULL,
                mailbox_id bigint(20) unsigned NULL,
                product_id bigint(20) unsigned NULL,
                product_source  varchar(200) NULL,
                privacy varchar(100) NULL DEFAULT 'private',
                priority  varchar(100) NULL DEFAULT 'normal',
                client_priority  varchar(100) NULL DEFAULT 'normal',
                ticket_status enum('Open','Closed','Waiting') DEFAULT 'Open',
                title varchar(200) NULL,
                ticket_fields longtext NULL,
                slug varchar(200) NULL,
                hash varchar(200) NULL,
                content_hash varchar(200) NULL,
                message_id varchar(200) NULL,
                source varchar(200) NULL,
                media_urls longtext NULL,
                content longtext NULL,
                secret_content longtext NULL,
                newest_agent_response timestamp NULL,
                newest_customer_response timestamp NULL,
                customer_email varchar(200) NULL,
                customer_name varchar(200) NULL,
                holding_time timestamp NULL,
                total_response int(9) NULL DEFAULT 0,
                total_close_time int(9) NULL,
                resolved_at timestamp NULL,
                closed_by bigint(20) NULL,
                seen_status enum('yes','new_reply','no') NULL DEFAULT 'no',
                customer_seen_status enum('yes','new_reply','no') NULL DEFAULT 'no',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )
            COLLATE {$db_collate}";
            
            // Ticket Conversation table
            $conversations_sql = "CREATE TABLE IF NOT EXISTS {$conversation_table}(
                id mediumint(8) unsigned NOT NULL auto_increment,
                ticket_id bigint(20) unsigned,
                person_id bigint(20) unsigned,
                conversation_type varchar(100) NULL  DEFAULT 'response',
                content longtext NULL,
                media_urls longtext NULL,
                mailbox_id bigint(20) unsigned NULL,
                source varchar(200) NULL,
                -- source enum('web','email') DEFAULT 'web',
                content_hash varchar(200) NULL,
                message_id varchar(200) NULL,
                is_important enum('yes','no') NULL DEFAULT 'no',
                seen_status enum('yes','no') NULL DEFAULT 'no',
                created_at timestamp NULL,
                updated_at timestamp NULL, 
                PRIMARY KEY (id)
            )
            COLLATE {$db_collate}";
            
            if (!function_exists('dbDelta')) {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            }

            dbDelta($mailbox_sql);
            dbDelta($ticket_sql);
            dbDelta($conversations_sql);
            dbDelta($ticket_meta_table);
            dbDelta($ticket_activities_table);
   
        }

    }
}
