<?php
/*
Plugin Name: scbFramework Example
Version: 0.1
Description: An example plugin
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/scb-framework
*/

require dirname(__FILE__) . '/scb/load.php';

function _scb_example_init() {
	// Using scbTable
	$t = new scbTable('an_example', __FILE__, "
		example_id int(20),
		example varchar(100),
		PRIMARY KEY  (example_id)
	");
}
scb_init('_scb_example_init');

