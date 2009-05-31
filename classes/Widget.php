<?php

class scbWidget extends WP_Widget {
	function widget($args, $instance) {
		extract($args);

		echo $before_widget . $before_title . $instance['title'] . $after_title;
		$this->content($instance);
		echo $after_widget;
	}

	function input($args, $options = array()) {
		// Add default label position
		if ( !in_array($args['type'], array('checkbox', 'radio')) && empty($args['desc_pos']) )
			$args['desc_pos'] = 'before';

		// First check names
		if ( FALSE !== $args['check'] ) {
			scbForms::_check_names($args['names'], $options);
			$args['check'] = false;
		}

		// Then add prefix to names and options
		$new_options = array();
		foreach ( (array) $args['names'] as $name )
			$new_options[ $this->get_field_name($name) ] = $options[$name];
		$new_names = array_keys($new_options);

		// Finally, replace the old names
		if ( 1 == count($new_names) )
			$args['names'] = $new_names[0];
		else
			$args['names'] = $new_names;

		// Remember $desc and replace with $title
		if ( $args['desc'] )
			$desc = "<small>{$args['desc']}</small>";
		$args['desc'] = $args['title'];
		unset($args['title']);

		$input = scbForms::input($args, $new_options);

		return "<p>{$input}\n<br />\n$desc\n</p>\n";
	}
}

