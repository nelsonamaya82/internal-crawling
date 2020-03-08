<?php

defined( 'ABSPATH' ) || exit;

// Composer autoload.
if ( file_exists( INTERNAL_CRAWLING_PATH . 'vendor/autoload.php' ) ) {
	require INTERNAL_CRAWLING_PATH . 'vendor/autoload.php';
}
