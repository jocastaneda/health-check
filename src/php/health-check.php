<?php
/**
 * Plugins primary file, in charge of including all other dependencies.
 *
 * @package Health Check
 *
 * @wordpress-plugin
 * Plugin Name: Health Check & Troubleshooting
 * Plugin URI: https://wordpress.org/plugins/health-check/
 * Description: Checks the health of your WordPress install.
 * Author: The WordPress.org community
 * Version: 1.4.6-beta
 * Author URI: https://wordpress.org/plugins/health-check/
 * Text Domain: health-check
 */

namespace HealthCheck;

// Check that the file is not accessed directly.
use Health_Check;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

// Set the plugin version.
define( 'HEALTH_CHECK_PLUGIN_VERSION', '1.4.6-beta' );

// Set the plugin file.
define( 'HEALTH_CHECK_PLUGIN_FILE', __FILE__ );

// Set the absolute path for the plugin.
define( 'HEALTH_CHECK_PLUGIN_DIRECTORY', plugin_dir_path( __FILE__ ) );

// Set the plugin URL root.
define( 'HEALTH_CHECK_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

// Set the current cURL version.
define( 'HEALTH_CHECK_CURL_VERSION', '7.58' );

// Set the minimum cURL version that we've tested that core works with.
define( 'HEALTH_CHECK_CURL_MIN_VERSION', '7.38' );

// Always include our compatibility file first.
require_once( dirname( __FILE__ ) . '/compat.php' );

// Backwards compatible pull in of extra resources
if ( ! class_exists( 'WP_Debug_Data' ) ) {
	$original_path = ABSPATH . '/wp-admin/includes/class-wp-debug-data.php';
	if ( file_exists( $original_path ) ) {
		require_once $original_path;
	} else {
		require_once __DIR__ . '/HealthCheck/BackCompat/class-wp-debug-data.php';
	}
}

// Include class-files used by our plugin.
require_once( dirname( __FILE__ ) . '/HealthCheck/class-health-check.php' );
require_once( dirname( __FILE__ ) . '/HealthCheck/class-health-check-loopback.php' );
require_once( dirname( __FILE__ ) . '/HealthCheck/class-health-check-troubleshoot.php' );

// Tools section.
require_once( dirname( __FILE__ ) . '/HealthCheck/Tools/class-health-check-tool.php' );
require_once( dirname( __FILE__ ) . '/HealthCheck/Tools/class-health-check-files-integrity.php' );
require_once( dirname( __FILE__ ) . '/HealthCheck/Tools/class-health-check-mail-check.php' );
require_once( dirname( __FILE__ ) . '/HealthCheck/Tools/class-health-check-plugin-compatibility.php' );

// Initialize our plugin.
new Health_Check();

// Setup up scheduled events.
register_activation_hook( __FILE__, array( 'Health_Check', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Health_Check', 'plugin_deactivation' ) );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( dirname( __FILE__ ) . '/HealthCheck/class-cli.php' );
}
