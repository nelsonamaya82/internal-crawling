<?php
namespace Internal_Crawling;

defined( 'ABSPATH' ) || exit;

/**
 * Class that handles templates logic.
 *
 * @since  1.0
 * @author Nelson Amaya
 */
class Template {

	/**
	 * Get a template contents.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @param  string $template The template name.
	 * @param  mixed  $data     Some data to pass to the template.
	 * @return string|bool      The page contents. False if the template doesn't exist.
	 */
	public function get_template( $template, $data = [] ) {
		$path = str_replace( '_', '-', $template );
		$path = INTERNAL_CRAWLING_PATH . 'views/' . $template . '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		ob_start();
		include $path;
		$contents = ob_get_clean();

		return trim( (string) $contents );
	}

	/**
	 * Print a template.
	 *
	 * @since  1.0
	 * @author Nelson Amaya
	 * @access public
	 *
	 * @param string $template The template name.
	 * @param mixed  $data     Some data to pass to the template.
	 */
	public function print_template( $template, $data = [] ) {
		echo wp_kses_post( $this->get_template( $template, $data ) );
	}
}
