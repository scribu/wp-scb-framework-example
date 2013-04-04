<?php

require_once getenv( 'WP_TESTS_DIR' ) . '/includes/functions.php';

tests_add_filter( 'muplugins_loaded', function() {
	require __DIR__ . '/../plugin.php';
} );

require getenv( 'WP_TESTS_DIR' ) . '/includes/bootstrap.php';
