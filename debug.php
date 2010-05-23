<?php

class scbDebug {
	private $args;

	function __construct($args) {
		$this->args = $args;

		register_shutdown_function(array($this, '_delayed'));
	}

	function _delayed() {
		if ( !current_user_can('administrator') )
			return;

		$this->raw($this->args);
	}

	static function raw($args) {
		echo "<pre>";
		foreach ( $args as $arg )
			if ( is_array($arg) || is_object($arg) )
				print_r($arg);
			else
				var_dump($arg);
		echo "</pre>";	
	}

	static function info() {
		self::raw(scbLoad4::get_info());
	}
}


// Integrate with FirePHP
function fb_debug() {
	$args = func_get_args();

	if ( class_exists('FirePHP') ) {
		$firephp = FirePHP::getInstance(true);
		$firephp->group('debug');
		foreach ( $args as $arg )
			$firephp->log($arg);
		$firephp->groupEnd();

		return;
	}

	new scbDebug($args);
}

function debug() {
	$args = func_get_args();

	scbDebug::raw($args);
}

function debug_scb() {
	add_action('shutdown', array('scbDebug', 'info'));
}

