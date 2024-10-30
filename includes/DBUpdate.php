<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('DBUpdate')) {
    class DBUpdate
    {

        public static function db_update()
        {
            // Add ticket table 2 new columns customer_name and customer_email
            self::add_ticket_table_columns();
            self::add_mailbox_table_columns();
            self::add_ticket_table_seen_status_column();
            self::add_conversations_table_seen_status_column();
        }
        /**
         * Add ticket table 2 new columns customer_name and customer_email
         * @since 1.0.7
         * @return void
         */
        public static function add_ticket_table_columns() {

            $isUpdateDb = get_option( 'hs_db_version_update_check' );
    
            if( empty( $isUpdateDb ) ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'hs_tickets';
                $res = [];
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'customer_name'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD customer_name VARCHAR(255) NOT NULL AFTER newest_customer_response");
                    $res[] = 'true';
                }

                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'customer_email'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD customer_email VARCHAR(255) NOT NULL AFTER customer_name");
                    $res[] = 'true';
                }
                //
                if( !empty($res) && count($res) == 2 ) {
                    update_option( 'hs_db_version_update_check', '1' );
                }

            }

        }
        /**
         * Add ticket table 2 new columns customer_name and customer_email
         * @since 1.0.8
         * @return void
         */
        public static function add_mailbox_table_columns() {

            $isUpdateDb = get_option( 'hs_db_mailbox_update_check' );
    
            if( empty( $isUpdateDb ) ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'hs_mailbox';
                $res = [];
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'support_from_email'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD support_from_email VARCHAR(255) NOT NULL AFTER mailbox_title");
                    $res[] = 'true';
                }
                
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'admin_email'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD admin_email VARCHAR(255) NOT NULL AFTER support_from_email");
                    $res[] = 'true';
                }

                if( !empty($res) && count($res) == 2 ) {
                    update_option( 'hs_db_mailbox_update_check', '1' );
                }

            }

        }
        /**
         * Add ticket table 1 new columns seen_status
         * @since 1.0.9
         * @return void
         */
        public static function add_ticket_table_seen_status_column() {

            $isUpdateDb = get_option( 'hs_db_ticket_seencol_update' );
    
            if( empty( $isUpdateDb ) ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'hs_tickets';
                $res = [];
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'seen_status'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD seen_status enum('yes','new_reply','no') NULL DEFAULT 'no' AFTER closed_by");
                    $res[] = 'true';
                }
                
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'customer_seen_status'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD customer_seen_status enum('yes','new_reply','no') NULL DEFAULT 'no' AFTER seen_status");
                    $res[] = 'true';
                }
                
                if( !empty($res) && count($res) == 2 ) {
                    update_option( 'hs_db_ticket_seencol_update', '1' );
                }
                

            }

        }
        /**
         * Add ticket conversations table 1 new columns seen_status
         * @since 1.0.9
         * @return void
         */
        public static function add_conversations_table_seen_status_column() {

            $isUpdateDb = get_option( 'hs_db_conversations_seencol_update' );
    
            if( empty( $isUpdateDb ) ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'hs_conversations';
                $res = false;
                // Check if the columns exist
                $row = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'seen_status'");
                if (empty($row)) {
                    $wpdb->query("ALTER TABLE {$table_name} ADD seen_status enum('yes','new_reply','no') NULL DEFAULT 'no' AFTER is_important");
                    $res = true;
                }
                
                if( $res ) {
                    update_option( 'hs_db_conversations_seencol_update', '1' );
                }
                

            }

        }

    }
}
