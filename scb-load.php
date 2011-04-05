<?php

define( 'SCB_LOAD_MU', true );

foreach ( array(
	'scbOptions', 'scbForms', 'scbAdminPage', 'scbBoxesPage',
	'scbWidget', 'scbCron', 'scbTable', 'scbUtil', 'scbQueryManipulation'
) as $className ) {
	include dirname( __FILE__ ) . '/scb/' . substr( $className, 3 ) . '.php';
}

function scb_init( $callback ) {
	call_user_func( $callback );
}

