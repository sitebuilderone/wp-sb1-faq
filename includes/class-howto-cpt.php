<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_HowTo_CPT {

	public static function register() {
		$labels = array(
			'name'               => __( 'HowTos', 'wp-sb1-faq' ),
			'singular_name'      => __( 'HowTo', 'wp-sb1-faq' ),
			'add_new'            => __( 'Add New', 'wp-sb1-faq' ),
			'add_new_item'       => __( 'Add New HowTo', 'wp-sb1-faq' ),
			'edit_item'          => __( 'Edit HowTo', 'wp-sb1-faq' ),
			'new_item'           => __( 'New HowTo', 'wp-sb1-faq' ),
			'view_item'          => __( 'View HowTo', 'wp-sb1-faq' ),
			'search_items'       => __( 'Search HowTos', 'wp-sb1-faq' ),
			'not_found'          => __( 'No HowTos found', 'wp-sb1-faq' ),
			'not_found_in_trash' => __( 'No HowTos found in trash', 'wp-sb1-faq' ),
			'menu_name'          => __( 'HowTos', 'wp-sb1-faq' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => SB1_FAQ_Admin_Settings::get_howto_slug() ),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'   => array( 'post_tag' ),
			'menu_icon'    => 'dashicons-list-view',
			'show_in_rest' => true,
			'rest_base'    => 'howtos',
		);

		register_post_type( 'howto', $args );
	}
}
