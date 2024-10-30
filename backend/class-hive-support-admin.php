<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('HiveSupportAdmin')) {
    class HiveSupportAdmin
    {

        public $utils;
        public $settings;

        public function __construct()
        {
            $this->utils = new HiveSupportUtils($this);
            $this->settings = new HiveSupportSettings($this);
            new HiveSupportAdminAjax($this);

            add_action("admin_menu", array($this, 'hive_lite_support_admin_menu'));

            if ( !hive_is_pro() ) {
                add_action('admin_enqueue_scripts', array($this, 'hive_lite_support_admin_enqueue'), 10);
                add_action('plugin_action_links_' . HIVE_LITE_SUPPORT_BASE_PATH, array($this, 'hive_lite_support_action_links'));
            }

        }
        
        // action link in plugin page
        function hive_lite_support_action_links($links)
        {
            $settings_url = add_query_arg('page', 'hive-support-dashboard', get_admin_url() . 'admin.php');
            $setting_arr = array('<a href="' . esc_url($settings_url) . '">Dashboard</a>');
            $links = array_merge($setting_arr, $links);
            return $links;
        }


        function hive_lite_support_admin_menu()
        {

            $icon_url = HIVE_LITE_SUPPORT_IMG_DIR . "hive_support_icon.svg";
            add_menu_page("Hive Support", "Hive Support", 'hive_support_access_plugin', "hive-support-dashboard", array($this, 'hive_lite_support_admin_dashboard'), $icon_url, 6);
            add_submenu_page("hive-support-dashboard", "Hive Support", 'Dashboard', "hive_support_access_plugin", 'hive-support-dashboard', array($this, 'hive_lite_support_admin_dashboard'));
        }

        function hideTokenRealValue($string, $count = 3)
        {
            // Get the length of the string
            $length = strlen($string);

            // Check if the string is long enough to mask
            if ($length <= $count * 2) {
                return $string; // If the string is too short, return it unchanged
            }

            // Get the first three characters of the string
            $firstThree = substr($string, 0, $count);

            // Get the last three characters of the string
            $lastThree = substr($string, -$count);

            // Replace all characters in between with asterisks
            $maskedString = $firstThree . str_repeat('*', $length - 6) . $lastThree;

            return $maskedString;
        }

        function hive_lite_support_admin_enqueue($page)
        {


            $hive_lite_dashboard_js_version = microtime();
            $adminsasset = include(HIVE_LITE_SUPPORT_PATH . "build/admin.asset.php");
            //$blockasset = include(HIVE_LITE_SUPPORT_PATH . "build/block/block.asset.php");
            // print_r($adminsasset);
            if ($page == "toplevel_page_hive-support-dashboard") {

                wp_enqueue_script("hive-dashboard", HIVE_LITE_SUPPORT_URL . "build/admin.js", $adminsasset['dependencies'], $hive_lite_dashboard_js_version, true);

                $hs_ai_settings = '';
                $option_name = 'hs_help_tab_data';
                $get_hs_ai_settings = get_option($option_name);
                if (!empty($get_hs_ai_settings)) {
                    $hs_ai_settings = $get_hs_ai_settings;
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

                if (!empty($get_hs_ai_chat_settings)) {
                    $hs_ai_chat_settings = $get_hs_ai_chat_settings;
                }

                $message_count = 0;
                $reply_count = 0;

                $license_expire_key = 'hive_support_license_expires';
                $license_expire = get_option($license_expire_key);


                $hs_woo_active = 'no';
                if (class_exists('WooCommerce')) {
                    $hs_woo_active = 'yes';
                }


                $hive_lite_agent = 'no';
                $hive_lite_agent_permission = '';
                if (is_user_logged_in() && !current_user_can('administrator') && current_user_can('hive_support_staff')) {
                    $hive_lite_agent = 'yes';
                    $current_user_id = get_current_user_id();
                    $hive_lite_agent_permission = get_user_meta($current_user_id, 'hs_agent_permissions', true);;
                }


                wp_localize_script(
                    "hive-dashboard",
                    "hive_lite_admin",
                    array(
                        "current_user" => wp_get_current_user(),
                        "__nonce" => wp_create_nonce(),
                        "rest_namespace" => "hivesupport/v1",
                        "admin_ajax" => admin_url("admin-ajax.php"),
                        "site_url" => site_url(),
                        "has_permission" => HiveSupportUtils::has_permission(),
                        "dir_url" => HIVE_LITE_SUPPORT_URL,
                        "help_tab" => $hs_ai_settings,
                        "messaging_tab" => $hs_messaging_settings,
                        "home_tab" => $hs_home_tab_settings,
                        "ai_tab" => $hs_ai_chat_settings,
                        'message_count' => $message_count,
                        'reply_count' => $reply_count,
                        'hs_license_expire' => $license_expire,
                        'hs_woo_active' => $hs_woo_active,
                        'hive_lite_agent' => $hive_lite_agent,
                        'hive_lite_agent_permission' => $hive_lite_agent_permission,
                    )
                );

                wp_set_script_translations(
                    'hive-dashboard', // script handle
                    'hive-support',  // text domain
                    HIVE_LITE_SUPPORT_PATH . '/languages'
                );

                wp_enqueue_style("hive-dashboard", HIVE_LITE_SUPPORT_URL . "build/admin.css", array(), HIVE_LITE_SUPPORT_VERSION);

                wp_enqueue_style("hive-dashboard-backs", HIVE_LITE_SUPPORT_URL . "assets/css/admin-backs.css", array(), HIVE_LITE_SUPPORT_VERSION);

                // wp_enqueue_style("hive-chat-window", HIVE_LITE_SUPPORT_URL . "assets/css/chat-window.css", array(), microtime());
                wp_enqueue_style("hive-dashboard-fix", HIVE_LITE_SUPPORT_URL . "assets/css/admin-fix.css", array(), microtime());

                wp_enqueue_style('hive-support-notification', HIVE_LITE_SUPPORT_CSS_DIR . 'notification_design.css', array(), HIVE_LITE_SUPPORT_VERSION);

                wp_enqueue_style('hive-support-admin-main', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_main.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-sidebar', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_sidebar.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-tickets', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_tickets.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-responses', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_responses.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-thread', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_thread.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-agents', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_agents.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-activities', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_activities.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-reports', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_reports.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-automation', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_automation.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-settings', HIVE_LITE_SUPPORT_CSS_DIR . 'admin_settings.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-settings-add-new-inbox', HIVE_LITE_SUPPORT_CSS_DIR . 'settings/add_new_inbox.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-settings-business-inbox', HIVE_LITE_SUPPORT_CSS_DIR . 'settings/business_inbox.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-settings-ticket-fields', HIVE_LITE_SUPPORT_CSS_DIR . 'settings/ticket_fields.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-settings-email-notification', HIVE_LITE_SUPPORT_CSS_DIR . 'settings/email_notification.css', array(), HIVE_LITE_SUPPORT_VERSION);

                wp_enqueue_script('hive-support-notification', HIVE_LITE_SUPPORT_JS_DIR . 'library/notification_handler.js', array('jquery'), HIVE_LITE_SUPPORT_VERSION);

                wp_enqueue_editor();
                wp_enqueue_media();

                // admin css libs enqueu
                wp_enqueue_style('hive-support-react-quill', HIVE_LITE_SUPPORT_CSS_DIR . 'lib/quilleditor.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-react-toastify', HIVE_LITE_SUPPORT_CSS_DIR . 'lib/toastify.css', array(), HIVE_LITE_SUPPORT_VERSION);
                wp_enqueue_style('hive-support-admin-custom', HIVE_LITE_SUPPORT_CSS_DIR . 'custom/admin/custom.css', array(), HIVE_LITE_SUPPORT_VERSION);
            }
        }


        function hive_lite_support_admin_dashboard()
        {
            //include_once HIVE_LITE_SUPPORT_PATH . "backend/templates/dashboard.php";
            //React js is pushing in there
            include_once HIVE_LITE_SUPPORT_PATH . "backend/templates/reactdash.php";
        }
    }
}




if (is_admin()) {
    new HiveSupportAdmin();
}
