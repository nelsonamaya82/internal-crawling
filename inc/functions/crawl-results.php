<?php
defined( 'ABSPATH' ) || exit;

/**
 * Get all internal links.
 *
 * @since  1.0
 * @author Nelson Amaya
 *
 * @param string $page The page to be crawled.
 *
 * @return array Internal links objects (Link text and URL).
 */
function internal_crawling_get_internal_links( $page = '' ) {
	delete_transient( 'internal_crawling_results' );

	// Get transient for previously saved results.
	$saved_results = get_transient( 'internal_crawling_results' );

	// If there are previously saved results, show them and return.
	if ( ! empty( $saved_results ) ) {
		return $saved_results;
	}

	// Get the page's HTML source using file_get_contents.
	$page_url = site_url( $page );
	$request  = wp_remote_get( $page_url );
	$html     = wp_remote_retrieve_body( $request );

	// Instantiate the DOMDocument class.
	$html_dom = new DOMDocument();

	// Parse the HTML of the page using DOMDocument::loadHTML.
	@$html_dom->loadHTML( $html ); //phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

	// Extract the links from the HTML.
	$links = $html_dom->getElementsByTagName( 'a' );

	// Array that will contain our extracted links.
	$extracted_links = [];

	// Parsed base URL.
	$base_url        = site_url();
	$parsed_base_url = wp_parse_url( $base_url );

	// Loop through the DOMNodeList.
	foreach ( $links as $link ) {

		// Get the link text.
		$link_text = $link->nodeValue; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		// Get the link in the href attribute.
		$link_href = $link->getAttribute( 'href' );

		$parsed_url = wp_parse_url( $link_href );

		// If the link is empty, skip it and don't add it to our $extracted_links array.
		if ( strlen( trim( $link_href ) ) === 0 ) {
			continue;
		}

		// Skip if it is a hashtag / anchor link.
		if ( '#' === $link_href[0] ) {
			continue;
		}

		// Skip if it has a special protocol (mailto:, tel:, javascript:, etc.).
		if ( ! isset( $parsed_url['host'] ) && strpos( $link_href, ':' ) !== false ) {
			continue;
		}

		// Check for internal urls that begin with '/'.
		if ( '/' === $link_href[0] ) {
			$link_href = $base_url . $link_href;
		}

		// remove trailing slash.
		$link_href = rtrim( $link_href, '/' );

		// Get link host.
		$link_host = isset( $parsed_url['host'] ) ? $parsed_url['host'] : $parsed_base_url['host'];

		// Skip if it's an external link.
		if ( $parsed_base_url['host'] !== $link_host ) {
			continue;
		}

		// Add the link to our $extracted_links array.
		$extracted_links[] = [
			'text' => $link_text,
			'href' => $link_href,
		];
	}

	// Set transient results, with an expiration of 1h.
	set_transient( 'internal_crawling_results', $extracted_links, 3600 );

	return $extracted_links;
}
