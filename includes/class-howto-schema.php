<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_HowTo_Schema {

	public static function register() {
		add_action( 'wp_head', array( __CLASS__, 'output_single' ) );
	}

	public static function output_single() {
		if ( ! is_singular( 'howto' ) ) {
			return;
		}

		$schema = self::build_schema( get_post( get_the_ID() ) );

		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
		}
	}

	/**
	 * Builds one HowTo schema object from a HowTo post.
	 *
	 * @param WP_Post $howto
	 * @return array|null
	 */
	public static function build_schema( $howto ) {
		if ( ! $howto instanceof WP_Post || ! $howto->post_title ) {
			return null;
		}

		$steps = get_post_meta( $howto->ID, '_sb1_howto_steps', true );
		$steps = is_array( $steps ) ? $steps : array();

		if ( empty( $steps ) ) {
			return null;
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'HowTo',
			'name'     => wp_strip_all_tags( $howto->post_title ),
		);

		$description = get_post_meta( $howto->ID, '_sb1_howto_description', true );
		if ( ! $description && has_excerpt( $howto ) ) {
			$description = $howto->post_excerpt;
		}

		if ( $description ) {
			$schema['description'] = wp_strip_all_tags( $description );
		}

		$total_time = get_post_meta( $howto->ID, '_sb1_howto_total_time', true );
		if ( $total_time ) {
			$schema['totalTime'] = sanitize_text_field( $total_time );
		}

		$supplies = self::parse_supplies( get_post_meta( $howto->ID, '_sb1_howto_supplies', true ) );
		if ( ! empty( $supplies ) ) {
			$schema['supply'] = $supplies;
		}

		$schema_steps = array();

		foreach ( $steps as $step ) {
			if ( ! is_array( $step ) ) {
				continue;
			}

			$name = isset( $step['name'] ) ? wp_strip_all_tags( $step['name'] ) : '';
			$text = isset( $step['text'] ) ? wp_strip_all_tags( $step['text'] ) : '';

			if ( ! $name && ! $text ) {
				continue;
			}

			$schema_step = array( '@type' => 'HowToStep' );

			if ( $name ) {
				$schema_step['name'] = $name;
			}

			if ( $text ) {
				$schema_step['text'] = $text;
			}

			if ( ! empty( $step['url'] ) ) {
				$schema_step['url'] = esc_url_raw( $step['url'] );
			}

			if ( ! empty( $step['image'] ) ) {
				$schema_step['image'] = esc_url_raw( $step['image'] );
			}

			$schema_steps[] = $schema_step;
		}

		if ( empty( $schema_steps ) ) {
			return null;
		}

		$schema['step'] = $schema_steps;

		return $schema;
	}

	/**
	 * Builds a JSON-LD graph from a list of HowTo posts.
	 *
	 * @param WP_Post[] $howto_posts
	 * @return array|null
	 */
	public static function build_graph( array $howto_posts ) {
		$items = array();

		foreach ( $howto_posts as $howto ) {
			$schema = self::build_schema( $howto );

			if ( $schema ) {
				unset( $schema['@context'] );
				$items[] = $schema;
			}
		}

		if ( empty( $items ) ) {
			return null;
		}

		return array(
			'@context' => 'https://schema.org',
			'@graph'   => $items,
		);
	}

	private static function parse_supplies( $supplies ) {
		if ( ! $supplies ) {
			return array();
		}

		$items = array();
		$lines = preg_split( '/\r\n|\r|\n/', $supplies );

		foreach ( $lines as $line ) {
			$name = trim( wp_strip_all_tags( $line ) );

			if ( ! $name ) {
				continue;
			}

			$items[] = array(
				'@type' => 'HowToSupply',
				'name'  => $name,
			);
		}

		return $items;
	}
}
