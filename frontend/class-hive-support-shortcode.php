<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportShortcodeParser')) {
    class HiveSupportShortcodeParser
    {


        public $utils;
        public $settings;

        public function __construct($base_client)
        {
            $this->utils = $base_client->utils;
            $this->settings = $base_client->settings;

            add_shortcode('hive-support', array($this, 'hive_lite_support_customer_portal'));
            add_shortcode('hive_customer_portal', array($this, 'hive_lite_support_customer_portal'));
        }

        public function hive_lite_support_customer_portal($atts, $content = null)
        {

            $mailbox_id = shortcode_atts(
                array(
                    'id' => ''
                ),
                $atts
            );

            $cust_portal_assset = include(HIVE_LITE_SUPPORT_PATH . "build/customer_portal.asset.php");
            wp_enqueue_script('customer-portal-js', HIVE_LITE_SUPPORT_URL . 'build/customer_portal.js', $cust_portal_assset['dependencies'], time(), true);

            $nonce = wp_create_nonce('hivesuppportgrontendtoken');
            wp_localize_script(
                'customer-portal-js',
                'shortcodes_data',
                array(
                    "current_user" => wp_get_current_user(),
                    "mailbox_id" => (int) $mailbox_id['id'],
                    "__nonce" => $nonce
                )
            );
            wp_set_script_translations(
                'customer-portal-js', // script handle
                'hive-support',  // text domain
                HIVE_LITE_SUPPORT_PATH . 'languages/'
            );
            ob_start(); ?>
            <div id="customer_portal"></div>
<?php return ob_get_clean();
        }
        public function hive_lite_support_shortcode_parser($atts, $content = null)
        {
            return $this->hive_lite_support_client_view_maker($atts);
        }


        public function hive_lite_support_client_view_maker($atts)
        {
            ob_start();
            include HIVE_LITE_SUPPORT_PATH . "frontend/templates/dashboard.php";
            return ob_get_clean();
        }
    }
}
