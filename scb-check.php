<?php

/*
This code displays a friendly notice instead of a fatal error when scbFramework is not found.

Add these two lines at the beginning of your main plugin file:

	require_once dirname(__FILE__) . '/scb-check.php';
	if ( ! scb_check(__FILE__) ) return;
*/

if ( !class_exists('_scbNotice') ):
class _scbNotice {

	function __construct($file) {
		$this->file = $file;

		add_action('admin_notices', array($this, 'notice'), 1);
	}

	function notice() {
		global $wp_version;

		$slug = 'scb-framework';

		$link = '[<a href="' . admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $slug .
			'&amp;TB_iframe=true&amp;width=600&amp;height=550') . '" class="thickbox onclick">' . __('Install now') . '</a>]';

		$file = plugin_basename($this->file);

		echo "<div class='error'><p>The <code>$file</code> plugin requires that <a href='http://scribu.net/wordpress/scb-framework'>scbFramework</a> be installed and active. $link</p></div>";
	}
}
endif;

if ( !function_exists('scb_check') ):
function scb_check($file) {
	if ( class_exists('scbLoad3') )
		return true;

	new _scbNotice($file);

	return false;
}
endif;

