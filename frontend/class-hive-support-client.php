<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportClient')) {
    class HiveSupportClient
    {

        public $utils;
        public $settings;
        public $live_chat = true;

        public function __construct()
        {

            $this->utils = new HiveSupportUtils($this);
            $this->settings = new HiveSupportSettings($this);
            new HiveSupportClientAjax($this);
            new HiveSupportShortcodeParser($this);

            add_action('wp_enqueue_scripts', array($this, 'hive_lite_support_client_enqueue'));

            // Menu Item Operation in WooCommerce My Account Page
            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                add_filter('woocommerce_account_menu_items', array($this, 'hive_lite_support_account_page_menu'));
                add_action('init', array($this, 'hive_lite_support_rewrite_endpoints'));
                add_action('woocommerce_account_hive-support_endpoint', array($this, 'hive_lite_support_render_menu_page_content'));
            }
        }

        function is_user_admin_or_support_staff()
        {
            $user = wp_get_current_user();

            // Check if the user has the 'administrator' or 'hive_support_staff' role
            if (in_array('administrator', $user->roles) || in_array('hive_support_staff', $user->roles)) {
                return true;
            }

            return false;
        }

        function hive_lite_support_client_enqueue()
        {
            wp_enqueue_style('hive-support-client-main', HIVE_LITE_SUPPORT_CSS_DIR . 'client_main.css', array(), HIVE_LITE_SUPPORT_VERSION);
            wp_enqueue_script('hive-support-client-main', HIVE_LITE_SUPPORT_JS_DIR . 'client_main.js', array('jquery'), HIVE_LITE_SUPPORT_VERSION);
            wp_enqueue_style('hive-support-client-fix', HIVE_LITE_SUPPORT_CSS_DIR . 'front-fix.css', array(), HIVE_LITE_SUPPORT_VERSION);

            $nonce = wp_create_nonce('hive_lite_support_client_hashkey');
            $hive_lite_agent = 'no';
            if ($this->is_user_admin_or_support_staff()) {
                $hive_lite_agent = 'yes';
            }

            // $hive_lite_agent = $this->is_user_admin_or_support_staff();
            $guest_token = HiveSupportUtils::get_the_guest() ?? '';

            $hs_help_settings = '';
            $option_name = 'hs_help_tab_data';
            $get_hs_help_settings = get_option($option_name);
            if (!empty($get_hs_help_settings)) {
                $hs_help_settings = $get_hs_help_settings;
            }
            $hs_messaging_settings = '';
            $option_name = 'hs_messaging_tab_data';
            $get_hs_messaging_settings = get_option($option_name);
            if (!empty($get_hs_messaging_settings)) {
                $hs_messaging_settings = $get_hs_messaging_settings;
            }
            $hs_home_tab_settings = '';
            $option_name = 'hs_home_tab_data';
            $get_hs_home_tab_settings = get_option($option_name);
            if (!empty($get_hs_home_tab_settings)) {
                $hs_home_tab_settings = $get_hs_home_tab_settings;
            }

            $hs_ai_chat_settings = '';
            $option_name = 'hs_ai_chat_settings';
            $get_hs_ai_chat_settings = get_option($option_name);


            $open_ai_ok = 'no';

            if (!empty($get_hs_ai_chat_settings)) {
                $hs_ai_chat_settings = $get_hs_ai_chat_settings;
                $hs_ai_chat_settings = stripslashes($hs_ai_chat_settings);
                $hs_ai_chat_settings = json_decode($hs_ai_chat_settings, true);

                if (
                    !empty($hs_ai_chat_settings['open_ai_api']) &&
                    !empty($hs_ai_chat_settings['open_ai_model']) &&
                    !empty($hs_ai_chat_settings['gpt_data']) &&
                    !empty($hs_ai_chat_settings['gpt_instructions'])
                ) {
                    $open_ai_ok = 'yes';
                }

                unset($hs_ai_chat_settings['open_ai_api']);
                unset($hs_ai_chat_settings['open_ai_model']);
                unset($hs_ai_chat_settings['gpt_data']);
                unset($hs_ai_chat_settings['gpt_instructions']);

                $hs_ai_chat_settings['open_ai_ok'] = 'yes';
            }

            $current_user_id = get_current_user_id();

            $total_reply_count = 0;
            
            wp_localize_script('hive-support-client-main', 'hive_lite_front', array(
                'site_url' => get_site_url(),
                'current_user' => $current_user_id,
                'hs_guest' => $guest_token,
                'hive_lite_agent' => $hive_lite_agent,
                'plugin_url' => HIVE_LITE_SUPPORT_URL,
                'ajaxurl' => admin_url('admin-ajax.php'),
                'security' => $nonce,
                "help_tab" => $hs_help_settings,
                "messaging_tab" => $hs_messaging_settings,
                "home_tab" => $hs_home_tab_settings,
                "ai_tab" => $hs_ai_chat_settings,
                "total_reply_count" => $total_reply_count,
                "open_ai_ok" => $open_ai_ok,
                "agent_avatar" => HIVE_LITE_SUPPORT_IMG_DIR . 'agents/user_default.png'
            ));

            wp_localize_script('hive-support-client-main', 'hive_lite_support_client_script_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'security' => $nonce
            ));
        }

        function hive_lite_support_account_page_menu($menu_links)
        {

            unset($menu_links['customer-logout']);
            $menu_links['hive-support'] = 'Support Portal';
            $menu_links['customer-logout'] = 'Logout';

            return $menu_links;
        }
        function hive_lite_support_rewrite_endpoints()
        {
            add_rewrite_endpoint('hive-support', EP_PAGES);
            flush_rewrite_rules();
        }
        function hive_lite_support_render_menu_page_content()
        {
            echo do_shortcode('[hive_customer_portal]');
        }
    }
}

new HiveSupportClient();
