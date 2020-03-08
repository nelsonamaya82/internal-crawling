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
define( 'INTERNAL_CRAWLING_SLUG', 'internal_crawling' );
define( 'INTERNAL_CRAWLING_FILE', __FILE__ );
define( 'INTERNAL_CRAWLING_PATH', realpath( plugin_dir_path( INTERNAL_CRAWLING_FILE ) ) . '/' );
define( 'INTERNAL_CRAWLING_INC_PATH', realpath( INTERNAL_CRAWLING_PATH . 'inc/' ) . '/' );

require INTERNAL_CRAWLING_INC_PATH . 'classes/class-internal-crawling-requirements-check.php';

/**
 * Loads Internal Crawling translations
 *
 * @since 1.0
 * @author Nelson Amaya
 *
 * @return void
 */
function internal_crawling_load_textdomain() {
	// Load translations from the languages directory.
	$locale = get_locale();

	// This filter is documented in /wp-includes/l10n.php.
	$locale = apply_filters( 'plugin_locale', $locale, 'internal_crawling' );
	load_textdomain( 'internal_crawling', WP_LANG_DIR . '/plugins/internal-crawling-' . $locale . '.mo' );

	load_plugin_textdomain( 'internal_crawling', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'internal_crawling_load_textdomain' );

/**
 * Plugin requirements check and initialization.
 */
$internal_crawling_requirements_check = new Internal_Crawling_Requirements_Check(
	[
		'plugin_name'    => 'Internal Crawling',
		'plugin_file'    => INTERNAL_CRAWLING_FILE,
		'plugin_version' => INTERNAL_CRAWLING_VERSION,
		'wp_version'     => INTERNAL_CRAWLING_WP_VERSION,
		'php_version'    => INTERNAL_CRAWLING_PHP_VERSION,
	]
);

if ( $internal_crawling_requirements_check->check() ) {
	require INTERNAL_CRAWLING_INC_PATH . 'main.php';
}

unset( $internal_crawling_requirements_check );
