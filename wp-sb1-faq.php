<?php
/**
 * Plugin Name: WP SiteBuilderOne FAQ
 * Description: FAQ and HowTo custom post types with shortcodes, schema output, and REST API support. No dependencies required.
 * Version:     1.1.0
 * Author:      SiteBuilderOne
 * Text Domain: wp-sb1-faq
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SB1_FAQ_VERSION', '1.1.0' );
define( 'SB1_FAQ_DIR', plugin_dir_path( __FILE__ ) );
define( 'SB1_FAQ_URL', plugin_dir_url( __FILE__ ) );

require_once SB1_FAQ_DIR . 'includes/class-cpt.php';
require_once SB1_FAQ_DIR . 'includes/class-howto-cpt.php';
require_once SB1_FAQ_DIR . 'includes/class-admin-settings.php';
require_once SB1_FAQ_DIR . 'includes/class-meta-boxes.php';
require_once SB1_FAQ_DIR . 'includes/class-howto-meta-boxes.php';
require_once SB1_FAQ_DIR . 'includes/class-rest-fields.php';
require_once SB1_FAQ_DIR . 'includes/class-howto-rest-fields.php';
require_once SB1_FAQ_DIR . 'includes/class-shortcode.php';
require_once SB1_FAQ_DIR . 'includes/class-howto-shortcode.php';
require_once SB1_FAQ_DIR . 'includes/class-schema.php';
require_once SB1_FAQ_DIR . 'includes/class-howto-schema.php';

add_action( 'init', array( 'SB1_FAQ_CPT', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_HowTo_CPT', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_Shortcode', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_HowTo_Shortcode', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_Rest_Fields', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_HowTo_Rest_Fields', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_Schema', 'register' ) );
add_action( 'init', array( 'SB1_FAQ_HowTo_Schema', 'register' ) );
add_action( 'admin_init', array( 'SB1_FAQ_Admin_Settings', 'register' ) );
add_action( 'admin_menu', array( 'SB1_FAQ_Admin_Settings', 'add_menu' ), 99 );
add_action( 'add_meta_boxes', array( 'SB1_FAQ_Meta_Boxes', 'add' ) );
add_action( 'add_meta_boxes', array( 'SB1_FAQ_HowTo_Meta_Boxes', 'add' ) );
add_action( 'save_post_faq', array( 'SB1_FAQ_Meta_Boxes', 'save' ) );
add_action( 'save_post_howto', array( 'SB1_FAQ_HowTo_Meta_Boxes', 'save' ) );
add_action( 'admin_enqueue_scripts', array( 'SB1_FAQ_Meta_Boxes', 'enqueue_styles' ) );
add_action( 'admin_enqueue_scripts', array( 'SB1_FAQ_HowTo_Meta_Boxes', 'enqueue_styles' ) );

register_activation_hook( __FILE__, function() {
	SB1_FAQ_CPT::register();
	SB1_FAQ_HowTo_CPT::register();
	flush_rewrite_rules();
} );
