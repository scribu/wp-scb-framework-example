<?php

// Takes care of creating custom database tables
class scbTable
{
	protected $name;
	protected $columns;

	function __construct($name, $file, $columns)
	{
		global $wpdb;

		$wpdb->$name = $this->name = $wpdb->prefix . $name;
		$this->columns = $columns;

# $this->uninstall();
# $this->install();

		register_activation_hook($file, array($this, 'install'));
		register_uninstall_hook($file, array($this, 'uninstall'));
	}

	function install()
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

# $wpdb->show_errors = true;

		dbDelta("CREATE TABLE $this->name ($this->columns);");
	}

	function uninstall()
	{
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS $this->name");
	}
}

