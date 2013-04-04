<?php

class WPDB_Mock {

	public $_query_log;

	function query( $sql ) {
		$this->_query_log .= $sql;
	}

	function __construct( $wpdb ) {
		$this->wpdb = $wpdb;
	}

	function __get( $key ) {
		return $this->wpdb->$key;
	}

	function __isset( $key ) {
		return isset( $this->wpdb->$key );
	}

	function __call( $method, $args ) {
		return call_user_func_array( array( $this->wpdb, $method ), $args );
	}
}


class scb_Constraint_StringContains extends PHPUnit_Framework_Constraint {

    /**
     * @var string
     */
    protected $needle;

    /**
     * @param string $needle
     */
    public function __construct( $needle )
    {
        $this->needle = $needle;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns TRUE if the
     * constraint is met, FALSE otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    protected function matches( $haystack )
    {
		return false !== strpos( $haystack, $this->needle );
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  mixed $other Evaluated value or object.
     * @return string
     */
    protected function failureDescription($other)
    {
        return sprintf(
          '%s contains "%s"',

          PHPUnit_Util_Type::shortenedExport($other),
          $this->needle
        );
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf(
          'contains "%s"',

          $this->needle
        );
    }
}


class TableTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		parent::setUp();

		global $wpdb;

		$wpdb = new WPDB_Mock( $wpdb );

		scb_register_table( 'foo' );
	}

	function tearDown() {
		parent::tearDown();

		global $wpdb;

		$wpdb = $wpdb->wpdb;
	}

	function test_simple_install() {
		scb_install_table( 'foo', 'ID int(20)' );

		$this->assertQueryContains( 'ID int(20)' );
	}

	function test_drop_first_install() {
		scb_install_table( 'foo', 'ID int(20)', 'delete_first' );

		$this->assertQueryContains( 'DROP TABLE' );
	}

	function test_innodb_install() {
		$table_opts = 'ENGINE InnoDB';

		scb_install_table( 'foo', 'ID int(20)', array(
			'table_options' => $table_opts
		) );

		$this->assertQueryDoesntContain( 'DROP TABLE' );
		$this->assertQueryContains( $table_opts );
	}

	function assertQueryContains( $str ) {
		global $wpdb;

		$constraint = new scb_Constraint_StringContains( $str );
		$this->assertThat( $wpdb->_query_log, $constraint );
	}

	function assertQueryDoesntContain( $str ) {
		global $wpdb;

		$constraint = $this->logicalNot( new scb_Constraint_StringContains( $str ) );

		$this->assertThat( $wpdb->_query_log, $constraint );
	}
}

