<?php

// Usage: http://scribu.net/wordpress/scb-framework/scb-options.html

class scbOptions
{
	protected $defaults;	// the default value(s)

	protected $key;			// the option name
	protected $data;		// the option value

	public $wp_filter_id;	// used by WP hooks

	function __construct($key, $file = '', $defaults = '')
	{
		$this->key = $key;
		$this->defaults = $defaults;
		$this->data = get_option($this->key);

		if ( is_array($this->defaults) )
		{
			$this->data = (array) $this->data;

			register_activation_hook($file, array($this, '_update_reset'));
		}

		register_uninstall_hook($file, array($this, '_delete'));
	}

	function __get($field)
	{
		return $this->data[$field];
	}

	function __set($field, $data)
	{
		$this->update_part(array($field => $data));
	}

	/**
	 * Get all data fields, certain fields or a single field
	 *
	 * @param string|array $field The field to get or an array of fields
	 * @return mixed Whatever is in those fields
	 */
	function get($field = '')
	{
		if ( empty($field) )
			return $this->data;

		if ( is_string($field) )
			return $this->data[$field];

		foreach ( $field as $key )
			$result[] = $this->data[$key];

		return $result;
	}

	/**
	 * Set all data fields, certain fields or a single field
	 *
	 * @param string|array $field The field to update or an associative array
	 * @param mixed $value The new value (ignored if $field is array)
	 * @return null
	 */
	function set($field, $value = '')
	{
		if ( is_array($field) )
			return $this->update_part($field);
		else
			return $this->update_part(array($field => $data));
	}

	/**
	 * Update one or more fields, leaving the others intact
	 *
	 * @param array $newdata An associative array
	 * @return null
	 */
	function update_part($newdata)
	{
		if ( ! is_array($newdata) )
			trigger_error("Wrong data_type", E_USER_WARNING);
		else
			$this->update(array_merge($this->data, $newdata));
	}

	/**
	 * Update all data fields
	 *
	 * @param array $newdata An associative array
	 * @return null
	 */
	function update($newdata)
	{
		if ( $this->data === $newdata )
			return;

		$this->data = $newdata;

		update_option($this->key, $this->data);
	}

	/**
	 * Reset option to defaults
	 *
	 * @return null
	 */
	function reset()
	{
		$this->update($this->defaults);
	}

	// Add new fields with their default values
	function _update_reset()
	{
		$this->update(array_merge($this->defaults, $this->data));
	}

	// Delete option
	function _delete()
	{
		delete_option($this->key);
	}
}

