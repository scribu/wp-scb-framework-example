<?php

class scbForms
{
	/* Generates one or more form elements of the same type,
		including <select>s and <textarea>s

		$args =	array (
			'type' => string  (mandatory)
			'name' => string | array  (mandatory)
			'value' => string | array
			'desc' => string | array | false
			'desc_pos' => 'before' | 'after' | 'foo %input% bar'  (default: after)
			'extra' => string  (default: class="regular-text")
		);

		$formdata = associative array with the formdata with which to fill the elements
	*/
	function input($args, $formdata = array())
	{
		// Backwards compat
		foreach ( array('name', 'value') as $key )
		{
			$old = $key . 's';
			if ( isset($args[$old]) )
				$args[$key] = $args[$old];
		}

		// Check required fields
		foreach ( array('name', 'type') as $key )
		{
			if ( isset($args[$key]) )
				continue;

			$error = true;
			trigger_error("No $key specified", E_USER_WARNING);
		}

		if ( $error )
			return;

		switch ( $args['type'] )
		{
			case 'select':  	return self::_select($args, $formdata);
			case 'textarea':	return self::_textarea($args, $formdata);
		}

		return self::_input($args, $formdata);
	}

	static $token = '%input%';

	// Deprecated
	function select($args, $options = array())
	{
		if ( !empty($options) )
			$args['value'] = $options;

		return self::_select($args);
	}

	// Deprecated
	function textarea($args, $content = '')
	{
		if ( !empty($content) )
			$args['value'] = $content;

		return self::_textarea($args);
	}


// ____________UTILITIES____________


	function form($inputs, $formdata = NULL, $nonce)
	{
		$output = '';
		foreach ( $inputs as $input )
			$output .= self::input($input, $formdata);

		$output = self::form_wrap($output, $nonce);

		return $output;
	}

	function table($rows, $formdata = NULL)
	{
		$output = '';
		foreach ( $rows as $row )
			$output .= self::table_row($row, $formdata);

		$output = self::table_wrap($output);

		return $output;
	}

	// Generates multiple rows and wraps them in a form table
	function form_table($rows, $formdata = NULL)
	{
		$output = '';
		foreach ( $rows as $row )
			$output .= self::table_row($row, $formdata);

		$output = self::form_table_wrap($output);

		return $output;
	}

	function table_row($args, $formdata = NULL)
	{
		return self::row_wrap($args['title'], self::input($args, $formdata));
	}


// ____________WRAPPERS____________


	function table_wrap($content)
	{
		$output = "\n<table class='form-table'>\n" . $content . "\n</table>\n";

		return $output;
	}

	function form_wrap($content, $nonce = 'update_options')
	{
		$output = "\n<form method='post' action=''>\n";
		$output .= $content;
		$output .= wp_nonce_field($action = $nonce, $name = "_wpnonce", $referer = true , $echo = false);
		$output .= "\n</form>\n";

		return $output;
	}

	function form_table_wrap($content, $nonce = 'update_options')
	{
		$output = self::table_wrap($content);
		$output = self::form_wrap($output, $nonce);

		return $output;
	}

	function row_wrap($title, $content)
	{
		return "\n<tr>\n\t<th scope='row'>" . $title . "</th>\n\t<td>\n\t\t" . $content . "\t</td>\n\n</tr>";
	}


// ____________PRIVATE METHODS____________


	private static function _input($args, $formdata)
	{
		extract(wp_parse_args($args, array(
			'desc_pos' => 'after',
			'extra' => 'class="regular-text"'
		)), EXTR_SKIP);

		// Set default values
		if ( 'text' == $type && !isset($value) )
			if ( !is_array($name) )
				$value = stripslashes(wp_specialchars(@$formdata[$name], ENT_QUOTES));
			else
				foreach ( $name as $cur_name )
					$value[] = stripslashes(wp_specialchars(@$formdata[$cur_name], ENT_QUOTES));

		if ( in_array($type, array('checkbox', 'radio')) )
		{
			if ( !isset($value) )
				$value = true;

			if ( !isset($desc)
				&& !is_array($name)
				&& !is_array($value)
				&& !is_bool($value)
			)
				$desc = $value;
		}

		// Expand names or values
		if ( !is_array($name) && !is_array($value) )
			$a = array($name => $value);
		elseif ( is_array($name) && !is_array($value) )
			$a = array_fill_keys($name, $value);
		elseif ( !is_array($name) && is_array($value) )
			$a = array_fill_keys($value, $name);
		else
			$a = array_combine($name, $value);

		// Determine what goes where
		if ( !is_array($name) && is_array($value) )
		{
			$i1 = 'val';
			$i2 = 'name';
		}
		else 
		{
			$i1 = 'name';
			$i2 = 'val';
		}

		// Set label
		if ( !isset($desc) )
			$l1 = 'name';
		else
			$l1 = 'desc';


		// Generate output
		$i = 0; $j = 0;
		foreach ( $a as $name => $val )
		{
			$cur_name = $$i1;
			$cur_val = $$i2;

			if ( $l1 == 'desc' && is_array($desc) )
				$cur_desc = $desc[$j++];
			else
				$cur_desc = str_replace('[]', '', @$$l1);

			// Build extra string
			$cur_extra = $extra;

			// Checked or not
			if ( in_array($type, array('checkbox', 'radio')) )
			{
				$match = @$formdata[str_replace('[]', '', $cur_name)];
				if ( is_array($match) )
					$match = $match[$i++];

				if ( $match == $cur_val )
					$cur_extra .= " checked='checked'";
			}

			if ( FALSE === strpos($cur_name, '[]') )
				$cur_extra .= " id='{$cur_name}'";

			// Build the item
			$input = "<input name='{$cur_name}' value='{$cur_val}' type='{$type}' {$cur_extra}/> ";

			// Set label
			if ( FALSE === strpos($cur_desc, self::$token) )
				switch ($desc_pos)
				{
					case 'before': $cur_desc .= ' ' . self::$token; break;
					case 'after': $cur_desc = self::$token . ' ' . $cur_desc;
				}
			$cur_desc = trim(str_replace(self::$token, $input, $cur_desc));

			// Add label
			if ( FALSE === $desc || empty($cur_desc) )
				$output[] = $input . "\n";
			else
				$output[] = "<label>{$cur_desc}</label>\n";
		}

		return implode("\n", $output);
	}

	private static function _select($args, $formdata)
	{
		extract(wp_parse_args($args, array(
			'name' => '',
			'value' => array(),
			'text' => '',
			'selected' => array('foo'),	// hack to make default transparent
			'extra' => NULL,
			'numeric' => false	// use numeric array instead of associative
		)), EXTR_SKIP);

		$cur_val = $selected;
		if ( isset($formdata[$name]) )
			$cur_val = $formdata[$name];

		if ( !is_array($value) )
			return trigger_error("Second argument is expected to be an array", E_USER_WARNING);

		if ( !self::is_associative($value) && !$numeric )
			$value = array_combine($value, $value);

		if ( FALSE === $text )
		{
			$opts = '';
		}
		else
		{
			$opts = "\t<option";
			if ( $cur_val == array('foo') )
				$opts .= " selected='selected'";
			$opts .= ">{$text}</option>\n";
		}

		foreach ( $value as $key => $value )
		{
			$cur_extra = "";
			if ( $key == $cur_val )
				$cur_extra .= " selected='selected'";

			$opts .= "\t<option value='{$key}'{$cur_extra}>{$value}</option>\n";
		}

		if ( FALSE === strpos($name, '[]') )
			$extra .= " id='{$name}'";

		return "<select name='{$name}' $extra>\n{$opts}</select>\n";
	}

	private static function _textarea($args, $formdata)
	{
		extract(wp_parse_args($args, array(
			'name' => '', 
			'extra' => 'class="widefat"',
			'value' => '',
			'escaped' => FALSE,
		)), EXTR_SKIP);

		if ( !$escaped )
			$value = stripslashes(wp_specialchars($value, ENT_QUOTES));

		if ( FALSE === strpos($name, '[]') )
			$extra .= " id='{$name}'";

		return "<textarea name='{$name}'{$extra}>\n{$value}\n</textarea>\n";
	}

	private static function is_associative($array)
	{
		if ( !is_array($array) || empty($array) )
			return false;

		$keys = array_keys($array);

		return array_keys($keys) !== $keys;
	}
}

// PHP < 5.2
if ( !function_exists('array_fill_keys') ) :
function array_fill_keys($keys, $value) 
{
	if ( !is_array($keys) )
		trigger_error('First argument is expected to be an array.' . gettype($keys) . 'given', E_USER_WARNING);

	foreach($keys as $key)
		$r[$key] = $value;

	return $r;
}
endif;

