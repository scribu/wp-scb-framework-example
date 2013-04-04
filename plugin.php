<?php
/*
Plugin Name: scbFramework Example
Version: r58
Description: A dummy plugin for scbFramework
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/scb-framework


Copyright (C) 2010-2012 Cristi Burcă (scribu@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


require dirname(__FILE__) . '/scb/load.php';

function _scb_example_init() {
	// Creating a custom table
	new scbTable( 'example_table', __FILE__, "
		example_id int(20),
		example varchar(100),
		PRIMARY KEY  (example_id)
	");

	// Creating an options object
	$options = new scbOptions( 'example_options', __FILE__, array(
		'default_option_a' => 'foo',
		'default_option_b' => 'bar',
	) );

	// Creating settings page objects
	if ( is_admin() ) {
		require_once( dirname( __FILE__ ) . '/example.php' );
		new Example_Admin_Page( __FILE__, $options );
		new Example_Boxes_Page( __FILE__ );
	}
}
scb_init( '_scb_example_init' );

