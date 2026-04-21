<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_Schema {

	public static function register() {
		add_action( 'wp_head', array( __CLASS__, 'output_single' ) );
	}

	/**
	 * Outputs FAQPage schema on single FAQ post pages.
	 */
	public static function output_single() {
		if ( ! is_singular( 'faq' ) ) {
			return;
		}

		$faq_posts = array( get_post( get_the_ID() ) );
		$schema    = self::build_schema( $faq_posts );

		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
		}
	}

	/**
	 * Builds a FAQPage schema array from an array of FAQ post objects.
	 * Called by both the single post hook and the shortcode.
	 *
	 * @param WP_Post[] $faq_posts
	 * @return array|null
	 */
	public static function build_schema( array $faq_posts ) {
		$entities = array();

		foreach ( $faq_posts as $faq ) {
			$answer = get_post_meta( $faq->ID, '_sb1_faq_answer', true );

			if ( ! $faq->post_title || ! $answer ) {
				continue;
			}

			$entities[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $faq->post_title ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $answer ),
				),
			);
		}

		if ( empty( $entities ) ) {
			return null;
		}

		return array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);
	}
}
