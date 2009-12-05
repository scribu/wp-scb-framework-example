<?php
/*
Plugin Name: scbFramework
Version: 1.3.1b
Description: Useful classes for plugin developers
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/scb-framework
*/

scbFramework::init();

abstract class scbFramework {
	const version = '1.3.1';

	static function init() {
		// Set autoload
		if ( function_exists('spl_autoload_register') )
			spl_autoload_register(array(__CLASS__, 'autoload'));
		else
			// Load all classes manually
			foreach ( array('scbForms', 'scbOptions', 'scbWidget', 'scbCron',
				'scbAdminPage', 'scbBoxesPage', 'scbTable', 'scbUtil') as $class )
				self::autoload($class);

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

	static function autoload($className) {
		if ( substr($className, 0, 3) != 'scb' )
			return false;

		if ( class_exists($className) )
			return false;

		$fname = self::get_file_path($className);

		if ( ! @file_exists($fname) )
			return false;

		include_once($fname);
		return true;
	}

	static function get_file_path($className) {
		return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . substr($className, 3) . '.php';
	}
}

