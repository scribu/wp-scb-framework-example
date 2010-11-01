<?php

$path = dirname(__FILE__) . '/scb/';

foreach ( array(
	'scbload', 'scbOptions', 'scbForms', 'scbAdminPage', 'scbBoxesPage',
	'scbWidget', 'scbCron', 'scbTable', 'scbUtil', 'scbQueryManipulation'
) as $className ) {
	include $path . substr($className, 3) . '.php';
}

unset($path);

