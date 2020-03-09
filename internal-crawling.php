<?php
/**
 * Plugin Name: Internal Crawling
 * Plugin URI: https://github.com/nelsonamaya82/internal-crawling
 * Description: A WordPress plugin to check how a website web pages are linked together to the home page.
 * Version: 1.0.0
 * Author: Nelson Amaya
 * Author URI: https://github.com/nelsonamaya82
 * Licence: GPLv2 or later
 * Text Domain: internal_crawling
 * Domain Path: languages
 * */

defined( 'ABSPATH' ) || exit;

// Internal Crawling defines.
define( 'INTERNAL_CRAWLING_VERSION', '1.0.0' );
define( 'INTERNAL_CRAWLING_WP_VERSION', '5.0' );
define( 'INTERNAL_CRAWLING_WP_VERSION_TESTED', '5.3.2' );
define( 'INTERNAL_CRAWLING_PHP_VERSION', '5.6' );
define( 'INTERNAL_CRAWLING_PLUGIN_NAME', 'Internal Crawling' );
define( 'INTERNAL_CRAWLING_SLUG', 'internal_crawling' );
define( 'INTERNAL_CRAWLING_FILE', __FILE__ );
define( 'INTERNAL_CRAWLING_PATH', realpath( plugin_dir_path( INTERNAL_CRAWLING_FILE ) ) . '/' );
define( 'INTERNAL_CRAWLING_INC_PATH', realpath( INTERNAL_CRAWLING_PATH . 'inc/' ) . '/' );
define( 'INTERNAL_CRAWLING_URL', plugin_dir_url( INTERNAL_CRAWLING_FILE ) );


add_action( 'plugins_loaded', 'internal_crawling_init' );
/**
 * Plugin init.
 *
 * @since 1.0
 * @author Nelson Amaya
 */
function internal_crawling_init() {
	// Nothing to do during autosave.
	if ( defined( 'DOING_AUTOSAVE' ) ) {
		return;
	}

	// Check for WordPress and PHP version.
	if ( ! internal_crawling_pass_requirements() ) {
		return;
	}

	// Init the plugin.
	require_once INTERNAL_CRAWLING_PATH . 'inc/classes/class-internal-crawling-plugin.php';

	$plugin = new Internal_Crawling_Plugin(
		[
			'plugin_path' => INTERNAL_CRAWLING_PATH,
		]
	);

	$plugin->init();
}

/**
 * Check if Internal Crawling is activated on the network.
 *
 * @since 1.0
 * @author Nelson Amaya
 *
 * return bool True if Internal Crawling is activated on the network.
 */
function internal_crawling_is_active_for_network() {
	static $is;

	if ( isset( $is ) ) {
		return $is;
	}

	if ( ! is_multisite() ) {
		$is = false;
		return $is;
	}

	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$is = is_plugin_active_for_network( plugin_basename( INTERNAL_CRAWLING_FILE ) );

	return $is;
}

/**
 * Check for WordPress and PHP version.
 *
 * @since  1.0
 * @author Nelson Amaya
 *
 * @return bool True if WP and PHP versions are OK.
 */
function internal_crawling_pass_requirements() {
	static $check;

	if ( isset( $check ) ) {
		return $check;
	}

	require_once INTERNAL_CRAWLING_PATH . 'inc/classes/class-internal-crawling-requirements-check.php';

	$requirements_check = new Internal_Crawling_Requirements_Check(
		[
			'plugin_name'    => INTERNAL_CRAWLING_PLUGIN_NAME,
			'plugin_file'    => INTERNAL_CRAWLING_FILE,
			'plugin_version' => INTERNAL_CRAWLING_VERSION,
			'wp_version'     => INTERNAL_CRAWLING_WP_VERSION,
			'php_version'    => INTERNAL_CRAWLING_PHP_VERSION,
		]
	);

	$check = $requirements_check->check();

	return $check;
}

/**
 * Load plugin translations.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
function internal_crawling_load_translations() {
	static $done = false;

	if ( $done ) {
		return;
	}

	$done = true;

	load_plugin_textdomain( 'internal_crawling', false, dirname( plugin_basename( INTERNAL_CRAWLING_FILE ) ) . '/languages/' );
}

register_activation_hook( INTERNAL_CRAWLING_FILE, 'internal_crawling_set_activation' );
/**
 * Set a transient on plugin activation, it will be used later to trigger activation hooks after the plugin is loaded.
 * The transient contains the ID of the user that activated the plugin.
 *
 * @since  1.0
 * @see    Internal_Crawling_Plugin->maybe_activate()
 * @author Nelson Amaya
 */
function internal_crawling_set_activation() {
	if ( ! internal_crawling_pass_requirements() ) {
		return;
	}

	if ( internal_crawling_is_active_for_network() ) {
		set_site_transient( 'internal_crawling_activation', get_current_user_id(), 30 );
	} else {
		set_transient( 'internal_crawling_activation', get_current_user_id(), 30 );
	}
}

register_deactivation_hook( INTERNAL_CRAWLING_FILE, 'internal_crawling_deactivation' );
/**
 * Trigger a hook on plugin deactivation.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
function internal_crawling_deactivation() {
	if ( ! internal_crawling_pass_requirements() ) {
		return;
	}

	/**
	 * Internal Crawling deactivation.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 */
	do_action( 'internal_crawling_deactivation' );
}
