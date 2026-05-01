<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_CPT {

	public static function register() {
		$labels = array(
			'name'               => __( 'FAQs', 'wp-sb1-faq' ),
			'singular_name'      => __( 'FAQ', 'wp-sb1-faq' ),
			'add_new'            => __( 'Add New', 'wp-sb1-faq' ),
			'add_new_item'       => __( 'Add New FAQ', 'wp-sb1-faq' ),
			'edit_item'          => __( 'Edit FAQ', 'wp-sb1-faq' ),
			'new_item'           => __( 'New FAQ', 'wp-sb1-faq' ),
			'view_item'          => __( 'View FAQ', 'wp-sb1-faq' ),
			'search_items'       => __( 'Search FAQs', 'wp-sb1-faq' ),
			'not_found'          => __( 'No FAQs found', 'wp-sb1-faq' ),
			'not_found_in_trash' => __( 'No FAQs found in trash', 'wp-sb1-faq' ),
			'menu_name'          => __( 'FAQs', 'wp-sb1-faq' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => SB1_FAQ_Admin_Settings::get_faq_slug() ),
			'supports'     => array( 'title', 'editor' ),
			'menu_icon'    => 'dashicons-editor-help',
			'show_in_rest' => true,
			'rest_base'    => 'faqs',
		);

		register_post_type( 'faq', $args );
	}
}
