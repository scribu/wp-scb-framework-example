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
			'choices' => $choices,
		) ) );

		$labels = $fd->find('//label');

		$this->assertCount( count( $choices ), $labels );

		foreach ( $labels as $i => $label ) {
			$label = FluentDOM( $label );

			$el = $label->find('.//input[@type="radio"]');

			$this->assertEquals( __FUNCTION__, $el->attr('name') );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );

			$this->assertEquals( ' ' . $choices[ $i ], $label->text() );
		}
	}

	function testCheckbox() {
		$choices = array( 'foo', 'bar' );

		$fd = self::domify( scbForms::input( array(
			'name' => array( 'maxi', 'pads' ),
			'type' => 'checkbox',
			'choices' => $choices
		) ) );

		$labels = $fd->find('//label');

		$this->assertCount( count( $choices ), $labels );

		foreach ( $labels as $i => $label ) {
			$label = FluentDOM( $label );

			$el = $label->find('.//input[@type="checkbox"]');

			$this->assertEquals( 'maxi[pads][]', $el->attr('name') );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );

			$this->assertEquals( ' ' . $choices[ $i ], $label->text() );
		}
	}

	function testSelect() {
		$choices = array(
			'1/2',
			'1',
			'1 1/3',
		);

		$args = array(
			'name' => __FUNCTION__,
			'type' => 'select',
			'choices' => $choices,
		);

		$fd = self::domify( scbForms::input( $args ) );

		$this->assertCount( 0, $fd->find('//select/option[@selected]') );

		$options = $fd->find('//select/option');

		$this->assertCount( count( $choices ), $options );

		foreach ( $options as $i => $option ) {
			$el = FluentDOM( $option );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );

			$this->assertEquals( $choices[ $i ], $el->text() );
		}

		$fd = self::domify( scbForms::input( array_merge( $args, array( 'selected' => '1 1/3' ) ) ) );

		$selected = $fd->find('//select/option[@selected]');

		$this->assertCount( 1, $selected );

		$this->assertEquals( '1 1/3', $selected->attr('value') );
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

