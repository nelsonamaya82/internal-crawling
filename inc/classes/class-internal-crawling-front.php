<?php
use Internal_Crawling\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class that handles front logic.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
class Internal_Crawling_Front {

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
	 * Template class instance to render templates and partials.
	 *
	 * @var    Template
	 * @since  1.0
	 * @access protected
	 */
	protected $template;

	/**
	 * The single instance of the class.
	 *
	 * @var    object
	 * @since  1.0
	 * @access protected
	 */
	protected static $instance;


	/**
	 * Constructor
	 *
	 * @author Nelson Amaya
	 * @access protected
	 */
	protected function __construct() {
		$this->template = new Template();
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
		// Shortcode.
		add_shortcode( 'crawling_results', [ $this, 'render_crawling_results' ] );
	}

	/**
	 * Render the crawling results when using a shortcode.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 */
	public function render_crawling_results() {
		ob_start();
		$this->template->print_template( 'partials/results' );
		return ob_get_clean();
	}
}
