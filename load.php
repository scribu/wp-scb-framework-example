<?php
/*
You can use this code to load the most recent versions of scbFramework available.
This has the advantage that the user is not required to install scbFramework as a separate plugin.
Things to note:
- you have to include the class files with each plugin
- you will have to update the framework manually

To load the classes, you just need to require the file, like so:

	require_once dirname(__FILE__) . '/scb/load.php';

This file needs to be in the same directory as the class files.
*/

if ( !class_exists('scbLoad3') ) :
abstract class scbLoad3 {

	private static $candidates;
	private static $loaded = false;

	static function register($rev, $file, $classes) {
		if ( class_exists('scbFramework') ) {
			self::load(dirname($file) . '/classes/', $classes);
		}
		else {
			self::$candidates[$rev] = array($file, $classes);
			add_action('plugins_loaded', array(__CLASS__, 'init'), 0);
		}
	}

	static function init() {
		krsort(self::$candidates);

		list($file, $classes) = reset(self::$candidates);

		self::load(dirname($file) . '/', $classes);
	}

	private static function load($path, $classes) {
		if ( self::$loaded )
			return;
		self::$loaded = true;

		foreach ( $classes as $class_name ) {
			if ( class_exists($class_name) )
				continue;

			$fpath = $path . substr($class_name, 3) . '.php';

			if ( @file_exists($fpath) )
				include $fpath;
		}
	}

	static function delayed_activation($plugin) {
		register_activation_hook($plugin, array(__CLASS__, '_delayed_activation'));
	}

	static function _delayed_activation() {
		do_action('plugins_loaded');
		do_action(current_filter());
	}
}
endif;

scbLoad3::register('15', __FILE__, array(
	'scbOptions', 'scbForms', 'scbAdminPage', 'scbBoxesPage',
	'scbWidget', 'scbCron', 'scbTable', 'scbUtil', 'scbRewrite',
));

