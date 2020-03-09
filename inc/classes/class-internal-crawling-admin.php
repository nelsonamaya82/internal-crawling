<?php
use Internal_Crawling\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class that handles admin logic.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
class Internal_Crawling_Admin {

	/**
	 * Slug used for the settings page URL.
	 *
	 * @var    string
	 * @since  1.0
	 * @access protected
	 */
	protected $slug_settings;

	/**
	 * The Internal Crawling settings page URL.
	 *
	 * @var    string
	 * @since  1.0
	 * @access protected
	 */
	protected $url_settings;

	/**
	 * Title used for the settings page.
	 *
	 * @var    string
	 * @since  1.0
	 * @access protected
	 */
	protected $title_settings;

	/**
	 * The single instance of the class.
	 *
	 * @var    object
	 * @since  1.0
	 * @access protected
	 */
	protected static $instance;


	/** ----------------------------------------------------------------------------------------- */
	/** INSTANCE/INIT =========================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Constructor
	 *
	 * @author Nelson Amaya
	 * @access protected
	 */
	protected function __construct() {
		$this->slug_settings  = INTERNAL_CRAWLING_SLUG;
		$this->url_settings   = admin_url( 'options-general.php?page=' . $this->slug_settings );
		$this->title_settings = INTERNAL_CRAWLING_PLUGIN_NAME;
	}

	/**
	 * Get the main Instance.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @return object Main instance.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Launch the hooks.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 */
	public function init() {
		// Menu items.
		add_action( 'admin_menu', [ $this, 'add_admin_menus' ] );

		// Action links in plugins list.
		$basename = plugin_basename( INTERNAL_CRAWLING_FILE );
		add_filter( 'plugin_action_links_' . $basename, [ $this, 'plugin_action_links' ] );
	}


	/** ----------------------------------------------------------------------------------------- */
	/** MENU ITEMS ============================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Add admin menu.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 */
	public function add_admin_menus() {
		add_options_page( $this->get_settings_page_title(), $this->get_settings_page_title(), 'manage_options', $this->get_settings_page_slug(), [ $this, 'display_settings_page' ] );
	}



	/** ----------------------------------------------------------------------------------------- */
	/** PLUGIN ACTION LINKS ===================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Add links to the plugin row in the plugins list.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @param  array $actions An array of action links.
	 * @return array
	 */
	public function plugin_action_links( $actions ) {
		array_unshift( $actions, sprintf( '<a href="%s">%s</a>', esc_url( $this->get_settings_page_url() ), __( 'Settings', 'internal_crawling' ) ) );
		return $actions;
	}


	/** ----------------------------------------------------------------------------------------- */
	/** MAIN PAGE TEMPLATES ===================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * The main settings page.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 */
	public function display_settings_page() {
		$template = new Template();
		$template->print_template( 'admin/page-settings' );
	}



	/** ----------------------------------------------------------------------------------------- */
	/** GETTERS ================================================================================= */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Get the settings page slug.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @return string
	 */
	public function get_settings_page_slug() {
		return $this->slug_settings;
	}

	/**
	 * Get the settings page URL.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @return string
	 */
	public function get_settings_page_url() {
		return $this->url_settings;
	}

	/**
	 * Get the settings page Title.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @return string
	 */
	public function get_settings_page_title() {
		return $this->title_settings;
	}



	/** ----------------------------------------------------------------------------------------- */
	/** PAGE TESTS ============================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Tell if we’re displaying the settings page.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @return bool
	 */
	public function is_settings_page() {
		global $pagenow;

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( $this->get_settings_page_slug() !== $page ) {
			return false;
		}

		return 'options-general.php' === $pagenow;
	}
}
