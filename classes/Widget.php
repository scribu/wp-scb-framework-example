<?php

// Adds compatibility methods between WP_Widget and scbForms

class scbWidget extends WP_Widget
{
	// You can use this function if you don't want to wory about widget args
	function content($instance){}

	function widget($args, $instance)
	{
		extract($args);

		echo $before_widget . $before_title . $instance['title'] . $after_title;
		$this->content($instance);
		echo $after_widget;
	}

	function input($args, $formdata = array()) 
	{
		// Add default class
		if ( !isset($args['extra']) )
			$args['extra'] = 'class="widefat"';

		// Add default label position
		if ( !in_array($args['type'], array('checkbox', 'radio')) && empty($args['desc_pos']) )
			$args['desc_pos'] = 'before';

		// Then add prefix to names and formdata
		$new_formdata = array();
		foreach ( (array) $args['name'] as $name )
			$new_formdata[ $this->get_field_name($name) ] = $formdata[$name];
		$new_names = array_keys($new_formdata);

		// Finally, replace the old names
		if ( 1 == count($new_names) )
			$args['name'] = $new_names[0];
		else
			$args['name'] = $new_names;

		// Remember $desc and replace with $title
		if ( $args['desc'] )
			$desc = "<small>{$args['desc']}</small>";
		$args['desc'] = $args['title'];
		unset($args['title']);

		$input = scbForms::input($args, $new_formdata);

		return "<p>{$input}\n<br />\n$desc\n</p>\n";
	}
}

