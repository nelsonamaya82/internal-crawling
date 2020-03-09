<?php
defined( 'ABSPATH' ) || exit;

/**
 * Get all translations we can use with wp_localize_script().
 *
 * @since  1.0
 * @author Nelson Amaya
 *
 * @param  string $context      The translation context.
 * @return array  $translations The translations.
 */
function internal_crawling_get_localize_script_translations( $context ) {
	global $post_id;

	switch ( $context ) {
		case 'admin':
			return [
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajaxnonce'  => wp_create_nonce( 'internal_crawling_list_results_nonce' ),
				'ajaxaction' => 'internal_crawling_list_results',
			];

		default:
			return [];
	}
}
