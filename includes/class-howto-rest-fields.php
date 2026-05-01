<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_HowTo_Rest_Fields {

	public static function register() {
		register_post_meta( 'howto', '_sb1_howto_description', array(
			'type'         => 'string',
			'description'  => 'Short description for HowTo schema.',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'howto', '_sb1_howto_total_time', array(
			'type'         => 'string',
			'description'  => 'ISO 8601 duration for the full HowTo.',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'howto', '_sb1_howto_supplies', array(
			'type'         => 'string',
			'description'  => 'Supplies for the HowTo, one per line.',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'howto', '_sb1_howto_steps', array(
			'type'         => 'array',
			'description'  => 'Ordered HowTo steps.',
			'single'       => true,
			'show_in_rest' => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'name'  => array( 'type' => 'string' ),
							'text'  => array( 'type' => 'string' ),
							'url'   => array( 'type' => 'string' ),
							'image' => array( 'type' => 'string' ),
						),
					),
				),
			),
		) );
	}
}
