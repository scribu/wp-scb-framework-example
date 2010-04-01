<?php
/*
Plugin Name: scbFramework
Version: 1.6a
Description: Useful classes for plugin developers
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/scb-framework
*/

require dirname(__FILE__) . '/scb/load.php';

function _scb_test() {
	function dummy() {
		// echo 'hi';
	}
	dummy();
}
new scbLoad3(__FILE__, '_scb_test');
