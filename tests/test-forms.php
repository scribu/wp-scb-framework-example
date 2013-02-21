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

	function testInputWithValue() {
		$data = array(
			'foo' => array(
				'bar' => 42
			),
			'bar' => 'wrong'
		);

		$output = scbForms::input( array(
			'name' => array( 'foo', 'bar' ),
			'type' => 'text',
			'value' => '<em>foobar</em>'
		), $data );

		$el = self::domify( $output )->find('//input');

		$this->assertEquals( 'foo[bar]', $el->attr('name') );
		$this->assertEquals( 42, $el->attr('value') );
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

		// should always have the first radio checked
		$checked = $fd->find('//input[@checked]');

		$this->assertCount( 1, $checked );

		$this->assertEquals( $choices[0], $checked->attr('value') );
	}

	function testSingleCheckbox() {
		$output = scbForms::input( array(
			'name' => 'fruit',
			'type' => 'checkbox',
			'value' => 'orange',
			'desc' => 'Orange'
		) );

		$label = self::domify( $output )->find('//label');

		$this->assertEquals( ' Orange', $label->text() );

		$el = $label->find('.//input[@type="checkbox"]');

		$this->assertEquals( 'fruit', $el->attr('name') );

		$this->assertEquals( 'orange', $el->attr('value') );

		$this->assertEmpty( $el->attr( 'checked' ) );
	}

	function testCheckbox() {
		$choices = array( 'foo', 'bar' );

		$args = array(
			'name' => array( 'maxi', 'pads' ),
			'type' => 'checkbox',
			'choices' => $choices
		);

		$fd = self::domify( scbForms::input( $args ) );

		$labels = $fd->find('//label');

		$this->assertCount( count( $choices ), $labels );

		foreach ( $labels as $i => $label ) {
			$label = FluentDOM( $label );

			$el = $label->find('.//input[@type="checkbox"]');

			$this->assertEquals( 'maxi[pads][]', $el->attr('name') );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );

			$this->assertEmpty( $el->attr( 'checked' ) );

			$this->assertEquals( ' ' . $choices[ $i ], $label->text() );
		}
	}

	function testCheckboxWithValues() {
		$selected = array( 'a', 'c' );
		$choices = array( 'a', 'b', 'c', 'd' );

		$data = array(
			__FUNCTION__ => $selected
		);

		$output = scbForms::input( array(
			'name' => __FUNCTION__,
			'type' => 'checkbox',
			'choices' => $choices
		), $data );

		$labels = self::domify( $output )->find('//label');

		$this->assertCount( count( $choices ), $labels );

		foreach ( $labels as $i => $label ) {
			$value = $choices[ $i ];

			$label = FluentDOM( $label );

			$el = $label->find('.//input[@type="checkbox"]');

			$this->assertEquals( __FUNCTION__ . '[]', $el->attr('name') );

			$this->assertEquals( $value, $el->attr('value') );

			if ( in_array( $value, $selected ) )
				$this->assertNotEmpty( $el->attr( 'checked' ) );
			else
				$this->assertEmpty( $el->attr( 'checked' ) );

			$this->assertEquals( ' ' . $value, $label->text() );
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

		// no pre-selected value
		$fd = self::domify( scbForms::input( $args ) );

		$options = $fd->find('//select/option');

		$this->assertCount( count( $choices ), $options );

		foreach ( $options as $i => $option ) {
			$el = FluentDOM( $option );

			$this->assertEquals( $choices[ $i ], $el->attr('value') );

			$this->assertEquals( $choices[ $i ], $el->text() );

			$this->assertEmpty( $el->attr( 'selected' ) );
		}

		// pre-select a value
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

