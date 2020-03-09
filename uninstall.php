<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_transient( 'internal_crawling_results' );
delete_transient( 'internal_crawling_activation' );
