<?php

class scbQueryManipulation {

	private $bits = array();
	private $wp_query;

	function __construct( $callback ) {

		$this->callback = $callback;

		$filters = array(
			'posts_where',
			'posts_join',
			'posts_groupby',
			'posts_orderby',
			'posts_distinct',
			'post_limits',
			'posts_fields'
		);

		foreach ( $filters as $filter ) {
			add_filter( $filter, array( $this, 'collect' ), 999, 2 );
			add_filter( $filter . '_request' , array( $this, 'update' ), 9 );
		}

		add_action( 'posts_selection' , array( $this, 'alter' ) );
	}

	function collect( $value, $wp_query ) {
		// remove 'posts_'
		$key = explode( '_', current_filter() );
		$key = array_slice( $key, 1 );
		$key = implode( '_', $key );

		$this->bits[ $key ] = $value;

		$this->wp_query = $wp_query;

		return $value;
	}

	function alter( $query ) {
		$this->bits = call_user_func( $this->callback, $this->bits, $this->wp_query );
	}

	function update( $value ) {
		// remove 'posts_' and '_request'
		$key = explode( '_', current_filter() );
		$key = array_slice( $key, 1, -1 );
		$key = implode( '_', $key );

		return $this->bits[ $key ];
	}
}

