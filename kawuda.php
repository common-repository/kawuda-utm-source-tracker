<?php
/**
 * Plugin Name: Kawuda UTM source tracker
 * Plugin URI:
 * Description: Kawuda is a simple UTM source tracker.
 * Version: 1.4.1
 * Author: WAP Nishantha <wapnishantha@gmail.com>
 * Author URI: https://bitbucket.org/wapnishantha/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Repo: https://bitbucket.org/wapnishantha/kawuda/src/master/
 *
 * @package           Kawuda
 **/

define( 'KAWUDA_PATH', dirname( __FILE__ ) );
define( 'KAWUDA_URL_PATH', plugin_dir_url( __FILE__ ) );

require KAWUDA_PATH . '/activate.php';
require KAWUDA_PATH . '/deactivate.php';

require KAWUDA_PATH . '/models/class-kawuda-tracking.php';
require KAWUDA_PATH . '/models/class-kawuda-chart.php';
require KAWUDA_PATH . '/controllers/class-kawudas-tracking.php';


/**
 * Activation and Deactivation hooks
 */
register_activation_hook( __FILE__, 'kawuda_activate' );
register_deactivation_hook( __FILE__, 'kawuda_deactivate' );

add_filter( 'plugin_action_links', 'kawuda_plugin_action_links', 10, 2 );

function kawuda_plugin_action_links( $links, $file ) {
    $plugin_file = basename( __FILE__ );
    if ( basename( $file ) == $plugin_file ) {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=kawuda_setting' ) . '">Settings</a>';
        array_unshift( $links, $settings_link );
    }

    return $links;
}

new Kawudas_Tracking();
