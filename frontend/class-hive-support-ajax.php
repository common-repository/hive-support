<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportClientAjax')) {
    class HiveSupportClientAjax
    {

        public $base_client;

        public function __construct($base_client)
        {
            $this->base_client = $base_client;

            add_action('wp_ajax_hive_lite_support_client_submit_form', array($this, 'hive_lite_support_client_submit_form'));
            add_action('wp_ajax_nopriv_hive_lite_support_client_submit_form', array($this, 'hive_lite_support_client_submit_form'));

            add_action('wp_ajax_hive_lite_support_client_list_tickets', array($this, 'hive_lite_support_client_list_tickets'));
            add_action('wp_ajax_nopriv_hive_lite_support_client_list_tickets', array($this, 'hive_lite_support_client_list_tickets'));

            add_action('wp_ajax_hive_lite_support_client_reply_thread', array($this, 'hive_lite_support_client_reply_thread'));
            add_action('wp_ajax_nopriv_hive_lite_support_client_reply_thread', array($this, 'hive_lite_support_client_reply_thread'));

            add_action('wp_ajax_hive_lite_support_set_customer_seen_status', array($this, 'hive_lite_support_set_customer_seen_status'));
            add_action('wp_ajax_nopriv_hive_lite_support_set_customer_seen_status', array($this, 'hive_lite_support_set_customer_seen_status'));

            add_action('wp_ajax_hive_lite_support_client_list_thread', array($this, 'hive_lite_support_client_list_thread'));
            add_action('wp_ajax_nopriv_hive_lite_support_client_list_thread', array($this, 'hive_lite_support_client_list_thread'));


            add_action('wp_ajax_hive_lite_support_ticket_add_customer_portal', array($this, 'hive_lite_support_ticket_add_customer_portal'));
        }

        // Test the fucntion
        public function hive_lite_support_ticket_add_customer_portal()
        {
            unset($_POST['action']);
            echo json_encode(
                array(
                    $_POST
                ),
                JSON_UNESCAPED_UNICODE
            );
            wp_die();
        }

        public function hive_lite_support_client_submit_form()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "frontend/api/submit_form.php";
            wp_die();
        }
        public function hive_lite_support_client_list_tickets()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "frontend/api/list_tickets.php";
            wp_die();
        }
        public function hive_lite_support_client_reply_thread()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "frontend/api/reply_thread.php";
            wp_die();
        }
        public function hive_lite_support_set_customer_seen_status()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "frontend/api/ticket_seen_status.php";
            wp_die();
        }
        public function hive_lite_support_client_list_thread()
        {
            include_once HIVE_LITE_SUPPORT_PATH . "frontend/api/list_thread.php";
            wp_die();
        }
    }
}
