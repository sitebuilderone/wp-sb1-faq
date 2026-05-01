<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_HowTo_Shortcode {

	public static function register() {
		add_shortcode( 'sb1_howto', array( __CLASS__, 'render' ) );
	}

	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'count'   => -1,
				'tag'     => '',
				'tags'    => '',
				'orderby' => 'menu_order',
				'order'   => 'ASC',
			),
			$atts,
			'sb1_howto'
		);

		$query_args = array(
			'post_type'      => 'howto',
			'posts_per_page' => intval( $atts['count'] ),
			'orderby'        => sanitize_key( $atts['orderby'] ),
			'order'          => in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true ) ? strtoupper( $atts['order'] ) : 'ASC',
		);

		$tag_terms = self::parse_tag_terms( $atts['tag'] ? $atts['tag'] : $atts['tags'] );

		if ( ! empty( $tag_terms['slugs'] ) || ! empty( $tag_terms['ids'] ) ) {
			$tax_queries = array();

			if ( ! empty( $tag_terms['slugs'] ) ) {
				$tax_queries[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $tag_terms['slugs'],
				);
			}

			if ( ! empty( $tag_terms['ids'] ) ) {
				$tax_queries[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag_terms['ids'],
				);
			}

			$query_args['tax_query'] = array(
				'relation' => 'OR',
			);

			$query_args['tax_query'] = array_merge( $query_args['tax_query'], $tax_queries );
		}

		$howtos = new WP_Query( $query_args );

		if ( ! $howtos->have_posts() ) {
			return '';
		}

		$howto_posts = $howtos->posts;

		ob_start();

		$template = self::locate_template();
		include $template;

		$schema = SB1_FAQ_HowTo_Schema::build_graph( $howto_posts );
		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	private static function parse_tag_terms( $value ) {
		$terms = array(
			'ids'   => array(),
			'slugs' => array(),
		);

		if ( ! $value ) {
			return $terms;
		}

		$tags = array_filter( array_map( 'trim', explode( ',', $value ) ) );

		foreach ( $tags as $tag ) {
			if ( is_numeric( $tag ) ) {
				$terms['ids'][] = absint( $tag );
			} else {
				$terms['slugs'][] = sanitize_title( $tag );
			}
		}

		$terms['ids']   = array_values( array_unique( array_filter( $terms['ids'] ) ) );
		$terms['slugs'] = array_values( array_unique( array_filter( $terms['slugs'] ) ) );

		return $terms;
	}

	private static function locate_template() {
		$theme_template = locate_template( 'sb1-faq/howto-list.php' );

		if ( $theme_template ) {
			return $theme_template;
		}

		return SB1_FAQ_DIR . 'templates/howto-list.php';
	}
}
