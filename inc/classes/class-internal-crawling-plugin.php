<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
class Internal_Crawling_Plugin {
	/**
	 * Absolute path to the plugin (with trailing slash).
	 *
	 * @var    string
	 * @since  1.0
	 * @access private
	 * @author Nelson Amaya
	 */
	private $plugin_path;

	/**
	 * Constructor.
	 *
	 * @since  1.0
	 * @access public
	 * @author Nelson Amaya
	 *
	 * @param array $plugin_args {
	 *     An array of arguments.
	 *
	 *     @type string $plugin_path Absolute path to the plugin (with trailing slash).
	 * }
	 */
	public function __construct( $plugin_args ) {
		$this->plugin_path = $plugin_args['plugin_path'];
	}

	/**
	 * Plugin init.
	 *
	 * @since  1.0
	 * @access public
	 * @author Nelson Amaya
	 */
	public function init() {
		$this->include_files();

		add_action( 'init', [ $this, 'maybe_activate' ] );

		// Load plugin translations.
		internal_crawling_load_translations();

		/**
		 * Fires when Internal Crawling is fully loaded.
		 *
		 * @since 1.0
		 *
		 * @param \Internal_Crawling_Plugin $plugin Instance of this class.
		 */
		do_action( 'internal_crawling_loaded', $this );
	}

	/**
	 * Include plugin files.
	 *
	 * @since  1.0
	 * @access public
	 * @author Nelson Amaya
	 */
	public function include_files() {
		if ( file_exists( $this->plugin_path . 'vendor/autoload.php' ) ) {
			require_once $this->plugin_path . 'vendor/autoload.php';
		}
	}

	/**
	 * Trigger a hook on plugin activation after the plugin is loaded.
	 *
	 * @since  1.0
	 * @access public
	 * @see    internal_crawling_set_activation()
	 * @author Nelson Amaya
	 */
	public function maybe_activate() {
		if ( internal_crawling_is_active_for_network() ) {
			$user_id = get_site_transient( 'internal_crawling_activation' );
		} else {
			$user_id = get_transient( 'internal_crawling_activation' );
		}

		if ( ! is_numeric( $user_id ) ) {
			return;
		}

		if ( internal_crawling_is_active_for_network() ) {
			delete_site_transient( 'internal_crawling_activation' );
		} else {
			delete_transient( 'internal_crawling_activation' );
		}

		/**
		 * Internal Crawling activation.
		 *
		 * @since  1.0
		 * @author Nelson Amaya
		 *
		 * @param int $user_id ID of the user activating the plugin.
		 */
		do_action( 'internal_crawling_activation', (int) $user_id );
	}
}
