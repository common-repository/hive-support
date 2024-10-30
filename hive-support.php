<?php
/**
 * Plugin Name:       Hive Support
 * Plugin URI:        https://gethivesupport.com/features
 * Description:       One stop support solution for WordPress.
 * Version:           1.1.1
 * Author:            Hive Support
 * Author URI:        https://gethivesupport.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hive-support
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('HIVE_LITE_SUPPORT_VERSION', '1.1.1');
define('HIVE_LITE_SUPPORT_VERSION_TYPE', 'LITE');
defined('HIVE_LITE_SUPPORT_PATH') or define('HIVE_LITE_SUPPORT_PATH', plugin_dir_path(__FILE__));
defined('HIVE_LITE_SUPPORT_URL') or define('HIVE_LITE_SUPPORT_URL', plugin_dir_url(__FILE__));
defined('HIVE_LITE_SUPPORT_BASE_FILE') or define('HIVE_LITE_SUPPORT_BASE_FILE', __FILE__);
defined('HIVE_LITE_SUPPORT_BASE_PATH') or define('HIVE_LITE_SUPPORT_BASE_PATH', plugin_basename(__FILE__));
defined('HIVE_LITE_SUPPORT_IMG_DIR') or define('HIVE_LITE_SUPPORT_IMG_DIR', plugin_dir_url(__FILE__) . 'assets/img/');
defined('HIVE_LITE_SUPPORT_CSS_DIR') or define('HIVE_LITE_SUPPORT_CSS_DIR', plugin_dir_url(__FILE__) . 'assets/css/');
defined('HIVE_LITE_SUPPORT_JS_DIR') or define('HIVE_LITE_SUPPORT_JS_DIR', plugin_dir_url(__FILE__) . 'assets/js/');

defined('HIVES_NOTIFICATION') or define('HIVES_NOTIFICATION', true);

// Include files
require_once HIVE_LITE_SUPPORT_PATH . 'includes/HiveSupportDB.php';
//
register_activation_hook( HIVE_LITE_SUPPORT_BASE_FILE, 'hive_support_lite_plugin_activation' );
register_deactivation_hook( HIVE_LITE_SUPPORT_BASE_FILE, 'hive_support_lite_plugin_deactivation' );

function hive_support_lite_plugin_activation() {
    HiveSupportDB::db_create();

    add_role("hive_support_staff", "Hive Support Staff", array('delete_posts' => true, 'edit_posts' => true, 'read' => true, 'hive_support_access_plugin' => true));
    $role = get_role('administrator');
    $role->add_cap('hive_support_access_plugin', true);
    //
    update_option( 'hive_lite_support_version', HIVE_LITE_SUPPORT_VERSION );

}

function hive_support_lite_plugin_deactivation() {
    remove_role("hive_support_staff");
    $role = get_role('administrator');
    $role->remove_cap('hive_support_access_plugin');
}


function hive_is_pro()
{
    return defined('HIVE_SUPPORT_VERSION_TYPE') && HIVE_SUPPORT_VERSION_TYPE == "PRO";
}

if( !class_exists('Appsero\Client') ) {
    require __DIR__ . '/appsero/src/Client.php';
}

if( hive_is_pro() && ( defined('HIVE_SUPPORT_VERSION') && HIVE_SUPPORT_VERSION < '1.1.0'  )  ) {

    function hs_admin_notice_pro_version_compatibility() {
        $class = 'notice notice-error';
        $message = __( 'Please use Hive Support PRO version 1.1.1 or higher.', 'hive-support' );
        
        printf( '<div class="%1$s"><p style="font-size: 16px;">%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }
    add_action( 'admin_notices', 'hs_admin_notice_pro_version_compatibility' );


    return;
}


/**
 * Initialize the tracker
 *
 * @return void
 */
function hs_appsero_init_tracker() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( 'ce75ac20-417c-432a-a629-a0d6309c4819', 'Hive Support', __FILE__ );

    // Active insights
    $client->insights()->init();
}

hs_appsero_init_tracker();


// echo HIVE_LITE_SUPPORT_PATH;
function hive_lite_support_check_premium_activation()
{

    // Admin panel file 
    if ( !hive_is_pro() ) {

        // This is helper file
        require_once HIVE_LITE_SUPPORT_PATH . 'includes/HiveSupportUtils.php';

        // HiveSupportSettings
        require_once HIVE_LITE_SUPPORT_PATH . 'includes/HiveSupportSettings.php';

        // All ajax requests
        require_once HIVE_LITE_SUPPORT_PATH . 'backend/class-hive-support-ajax.php';
    }

    // Email notfication for tickets
    require_once HIVE_LITE_SUPPORT_PATH . 'includes/HiveSupportSendEmails.php';
    require_once HIVE_LITE_SUPPORT_PATH . 'backend/class-hive-support-admin.php';

    require_once HIVE_LITE_SUPPORT_PATH . 'includes/DBUpdate.php';

    if ( !hive_is_pro() ) {

        // All rest api 
        require_once HIVE_LITE_SUPPORT_PATH . 'backend/class-hive-support-rest-api.php';

        // Front customer panel Sortcode here 
        require_once HIVE_LITE_SUPPORT_PATH . 'frontend/class-hive-support-shortcode.php';

        // Front end ajax requests here
        require_once HIVE_LITE_SUPPORT_PATH . 'frontend/class-hive-support-ajax.php';

        // Front end customer panel
        require_once HIVE_LITE_SUPPORT_PATH . 'frontend/class-hive-support-client.php';
    }

}

add_action('hive_support_init', 'hive_lite_support_check_premium_activation', 10);
do_action('hive_support_init');

add_action('init', function() {
    load_plugin_textdomain( 'hive-support', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

});

add_action( 'admin_init', 'hive_lite_support_update_db' );
function hive_lite_support_update_db() {
    DBUpdate::db_update();
}
