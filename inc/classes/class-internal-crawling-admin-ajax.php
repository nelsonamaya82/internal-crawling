<?php
use Internal_Crawling\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Class that handles admin ajax callbacks.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
class Internal_Crawling_Admin_Ajax {

	/**
	 * Actions to be triggered on admin ajax.
	 *
	 * @var    array
	 * @since  1.0
	 * @access protected
	 * @author Nelson Amaya
	 */
	protected $ajax_actions = [
		'internal_crawling_list_results',
	];

	/**
	 * The single instance of the class.
	 *
	 * @var    object
	 * @since  1.0
	 * @access protected
	 */
	protected static $instance;

	/**
	 * The constructor.
	 *
	 * @since  1.0
	 * @access public
	 * @author Nelson Amaya
	 */
	public function __construct() {

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
	 * @access public
	 * @author Nelson Amaya
	 */
	public function init() {
		if ( wp_doing_ajax() ) {
			// Actions triggered on admin ajax.
			foreach ( $this->ajax_actions as $action ) {
				add_action( 'wp_ajax_' . $action, [ $this, $action . '_callback' ] );
			}
		}
	}

	/**
	 * Get crawling results.
	 *
	 * @since  1.0
	 * @access public
	 * @author Nelson Amaya
	 */
	public function internal_crawling_list_results_callback() {
		if ( ! isset( $_POST['nonce'] ) ) {
			$message = esc_html__( 'Sorry, you are not allowed to do that.', 'internal_crawling' );
			wp_die( $message, esc_html__( 'Failure Notice', 'internal_crawling' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $message is escaped before being passed in.
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'internal_crawling_list_results_nonce' ) ) {
			$message = esc_html__( 'Sorry, you are not allowed to do that.', 'internal_crawling' );
			wp_die( $message, esc_html__( 'Failure Notice', 'internal_crawling' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $message is escaped before being passed in.
		}

		$template = new Template();
		$template->print_template( 'partials/results' );

		wp_die();
	}
}
