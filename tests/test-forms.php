<?php

require __DIR__ . '/../vendor/fluentdom/fluentdom/src/FluentDOM.php';

class FormsTest extends PHPUnit_Framework_TestCase {

	function testTextInput() {
		$fd = self::domify( scbForms::input( array(
			'name' => array( 'foo', 'bar', 'baz' ),
			'type' => 'text',
			'value' => '<em>foobar</em>'
		) ) );

		$el = $fd->find('//input');

		$this->assertEquals( 'foo[bar][baz]', $el->attr('name') );
		$this->assertEquals( '<em>foobar</em>', $el->attr('value') );
	}

	function testTextarea() {
		$fd = self::domify( scbForms::input( array(
			'name' => __FUNCTION__,
			'type' => 'textarea',
			'value' => '<em>foobar</em>'
		) ) );

		$el = $fd->find('//textarea');

		$this->assertEquals( __FUNCTION__, $el->attr('name') );
		$this->assertEquals( '<em>foobar</em>', $el->text() );
	}

	function testRadio() {
		$choices = array( 'foo', 'bar' );

		$fd = self::domify( scbForms::input( array(
			'name' => __FUNCTION__,
			'type' => 'radio',
			'choices' => $choices
		) ) );

		$radios = $fd->find('//input[@type="radio"]');

		$this->assertCount( count( $choices ), $radios );

		foreach ( $radios as $i => $radio ) {
			$el = FluentDOM( $radio );

			$this->assertEquals( __FUNCTION__, $el->attr('name') );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );
		}
	}

	function testCheckbox() {
		$choices = array( 'foo', 'bar' );

		$fd = self::domify( scbForms::input( array(
			'name' => array( 'maxi', 'pads' ),
			'type' => 'checkbox',
			'choices' => $choices
		) ) );

		$checkboxes = $fd->find('//input[@type="checkbox"]');

		$this->assertCount( count( $choices ), $checkboxes );

		foreach ( $checkboxes as $i => $radio ) {
			$el = FluentDOM( $radio );

			$this->assertEquals( 'maxi[pads][]', $el->attr('name') );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );
		}
	}

	function testSelected() {
		$fd = self::domify( scbForms::input( array(
			'name' => __FUNCTION__,
			'type' => 'select',
			'values' => array(
				'1/2',
				'1',
				'1 1/3',
			),
			'selected' => '1'
		) ) );

		$this->assertEquals( '1', $fd->find('//select/option[@selected]')->text() );
	}

	private static function domify( $str ) {
		$fd = new FluentDOM;

		$xml = <<<XML
<html>
<head></head>
<body>
$str
</body>
</html>
XML;

		return FluentDOM( $xml );
	}
}

