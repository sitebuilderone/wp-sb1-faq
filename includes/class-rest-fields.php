<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_Rest_Fields {

	public static function register() {
		register_post_meta( 'faq', '_sb1_faq_answer', array(
			'type'         => 'string',
			'description'  => 'Plain-text answer for the FAQ.',
			'single'       => true,
			'show_in_rest' => true,
		) );

		register_post_meta( 'faq', '_sb1_faq_related_service', array(
			'type'         => 'integer',
			'description'  => 'Post ID of the related service, if any.',
			'single'       => true,
			'show_in_rest' => true,
		) );
	}
}
