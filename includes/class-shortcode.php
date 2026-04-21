<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_Shortcode {

	public static function register() {
		add_shortcode( 'sb1_faq', array( __CLASS__, 'render' ) );
	}

	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'count'   => -1,
				'service' => '',
				'orderby' => 'menu_order',
				'order'   => 'ASC',
			),
			$atts,
			'sb1_faq'
		);

		$query_args = array(
			'post_type'      => 'faq',
			'posts_per_page' => intval( $atts['count'] ),
			'orderby'        => sanitize_key( $atts['orderby'] ),
			'order'          => in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true ) ? strtoupper( $atts['order'] ) : 'ASC',
		);

		if ( ! empty( $atts['service'] ) ) {
			$service_id = self::resolve_service_id( $atts['service'] );

			if ( $service_id ) {
				$query_args['meta_query'] = array(
					array(
						'key'   => '_sb1_faq_related_service',
						'value' => $service_id,
						'type'  => 'NUMERIC',
					),
				);
			}
		}

		$faqs = new WP_Query( $query_args );

		if ( ! $faqs->have_posts() ) {
			return '';
		}

		$faq_posts = $faqs->posts;

		ob_start();

		$template = self::locate_template();
		include $template;

		$schema = SB1_FAQ_Schema::build_schema( $faq_posts );
		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Accepts a service post ID (int or numeric string) or slug and returns the post ID.
	 *
	 * @param string $value
	 * @return int|null
	 */
	private static function resolve_service_id( $value ) {
		if ( is_numeric( $value ) ) {
			return absint( $value );
		}

		$service = get_page_by_path( sanitize_title( $value ), OBJECT, 'service' );

		return $service ? $service->ID : null;
	}

	private static function locate_template() {
		$theme_template = locate_template( 'sb1-faq/faq-list.php' );

		if ( $theme_template ) {
			return $theme_template;
		}

		return SB1_FAQ_DIR . 'templates/faq-list.php';
	}
}
