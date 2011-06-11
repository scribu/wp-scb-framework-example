<?php

class scbExampleAdmin extends scbAdminPage {

	function setup() {
		$this->args = array(
			'page_title' => 'scb Example',
		);
	}

	function page_content() {
		echo $this->form_table( array(
			array(
				'title' => 'A text field',
				'type' => 'text',
				'name' => 'text_field',
			),
			array(
				'title' => 'A textarea',
				'type' => 'textarea',
				'name' => 'text_area',
			),
			array(
				'title' => 'Some checkboxes',
				'type' => 'checkbox',
				'name' => 'check_box',
				'value' => array( 'foo', 'bar', 'baz' )
			),
			array(
				'title' => 'Some radio buttons',
				'type' => 'radio',
				'name' => 'radio_box',
				'value' => array( 'foo', 'bar', 'baz' ),
				'checked' => 'foo'
			),
			array(
				'title' => 'A dropdown',
				'type' => 'select',
				'name' => 'radio_box',
				'value' => array( 'foo', 'bar', 'baz' ),
				'selected' => 'bar'
			),
		) );
	}

	function page_footer() {
		parent::page_footer();

		// Reset all forms
?>
		<script type="text/javascript">
		(function() {
			var forms = document.getElementsByTagName('form');
			for (var i = 0; i < forms.length; i++) {
				forms[i].reset();
			}
		}());
		</script>
<?php
	}
}

