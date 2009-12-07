<?php
/*
Plugin Name: scbFramework
Version: 1.4a
Description: Useful classes for plugin developers
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/scb-framework
*/

abstract class scbFramework {
	const version = '1.4';

	static function init() {
		require_once dirname(__FILE__) . '/load.php';

		add_action('shutdown', array(__CLASS__, 'put_first'));
	}

	static function put_first() {
		$plugin = plugin_basename(__FILE__);
		$current = get_option('active_plugins');

		if ( $current[0] == $plugin )
			return;		// plugin already first

		$i = array_search($plugin, $current);

		if ( FALSE === $i )
			return;		// plugin not active

		// Move current plugin to the first position
		array_splice($current, $i, 1);
		array_unshift($current, $plugin);

		update_option('active_plugins', $current);
	}
}

scbFramework::init();

