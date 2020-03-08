<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class to check if the current WordPress and PHP versions meet our requirements.
 *
 * @author Nelson Amaya
 */
class Internal_Crawling_Requirements_Check {
	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin filepath
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Required WordPress version
	 *
	 * @var string
	 */
	private $wp_version;

	/**
	 * Required PHP version
	 *
	 * @var string
	 */
	private $php_version;

	/**
	 * Constructor
	 *
	 * @author Nelson Amaya
	 *
	 * @param array $args {
	 *     Arguments to populate the class properties.
	 *
	 *     @type string $plugin_name    Plugin name.
	 *     @type string $plugin_file    Plugin filepath.
	 *     @type string $plugin_version Plugin version.
	 *     @type string $wp_version     Required WordPress version.
	 *     @type string $php_version    Required PHP version.
	 * }
	 */
	public function __construct( $args ) {
		foreach ( [ 'plugin_name', 'plugin_file', 'plugin_version', 'wp_version', 'php_version' ] as $setting ) {
			if ( isset( $args[ $setting ] ) ) {
				$this->$setting = $args[ $setting ];
			}
		}
	}

	/**
	 * Checks if all requirements are ok, if not, display a notice.
	 *
	 * @return bool
	 */
	public function check() {
		if ( ! $this->php_passes() || ! $this->wp_passes() ) {

			add_action( 'admin_notices', [ $this, 'notice' ] );

			return false;
		}

		return true;
	}

	/**
	 * Checks if the current PHP version is equal or superior to the required PHP version.
	 *
	 * @return bool
	 */
	private function php_passes() {
		return version_compare( PHP_VERSION, $this->php_version ) >= 0;
	}

	/**
	 * Checks if the current WordPress version is equal or superior to the required PHP version.
	 *
	 * @return bool
	 */
	private function wp_passes() {
		global $wp_version;

		return version_compare( $wp_version, $this->wp_version ) >= 0;
	}

	/**
	 * Warns if PHP or WP version are less than the defined values.
	 */
	public function notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Translators: %1$s = Plugin name, %2$s = Plugin version.
		$message = '<p>' . sprintf( __( 'To function properly, %1$s %2$s requires at least:', 'internal_crawling' ), $this->plugin_name, $this->plugin_version ) . '</p><ul>';

		if ( ! $this->php_passes() ) {
			// Translators: %1$s = PHP version required.
			$message .= '<li>' . sprintf( __( 'PHP %1$s. To use this Internal Crawling version, please ask your web host how to upgrade your server to PHP %1$s or higher.', 'internal_crawling' ), $this->php_version ) . '</li>';
		}

		if ( ! $this->wp_passes() ) {
			// Translators: %1$s = WordPress version required.
			$message .= '<li>' . sprintf( __( 'WordPress %1$s. To use this Internal Crawling version, please upgrade WordPress to version %1$s or higher.', 'internal_crawling' ), $this->wp_version ) . '</li>';
		}

		echo '<div class="notice notice-error">' . wp_kses_post( $message ) . '</div>';
	}
}
