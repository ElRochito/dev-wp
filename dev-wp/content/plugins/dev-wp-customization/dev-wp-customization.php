<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/ElRochito/dev-wp
 * @since             1.0.0
 * @package           Dev_Wp_Customization
 *
 * @wordpress-plugin
 * Plugin Name:       DevWP Customization
 * Plugin URI:        https://github.com/ElRochito/dev-wp
 * Description:       This include some minor improvements
 * Version:           1.0.0
 * Author:            DevWP
 * Author URI:        https://github.com/ElRochito/dev-wp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dev-wp-customization
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dev-wp-customization.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dev_wp_customization() {

    $plugin = new Dev_Wp_Customization();
    $plugin->run();

}
run_dev_wp_customization();
