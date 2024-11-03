<?php
/**
 *
 * WP Swiss Knife
 *
 * @package WP Swiss Knife
 * @author Herman Zuiakov
 * @license GPL-2.0+
 * @version 1.0.0
 *
 *
 * @wordpress-plugin
 * Plugin Name: WP Swiss Knife
 * Plugin URI: https://wp-swiss-knife.website
 * Description: A powerful developer's toolkit plugin for WordPress.
 * Version: 1.0.0
 * Author: Herman Zuiakov
 * Author URI: https://zuiakovwebdev.website
 * Update URI: false
 * License: GPLv2 or later
 * Text Domain: wp-swiss-knife
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define constants
define( 'WP_SWISS_KNIFE_VERSION', '1.0.0' );
define( 'WP_SWISS_KNIFE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_SWISS_KNIFE_URL', plugin_dir_url( __FILE__ ) );

// Load localization
function wp_swiss_knife_load_textdomain() {
	load_plugin_textdomain( 'wp-swiss-knife', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wp_swiss_knife_load_textdomain' );

function wp_swiss_knife_enqueue_admin_styles( $hook ) {
	// Check if we are on the settings page of the plugin
	if ( 'settings_page_wp_swiss_knife' !== $hook ) {
		return;
	}

	// Enqueue admin styles
	wp_enqueue_style(
		'wp-swiss-knife-admin-style',
		WP_SWISS_KNIFE_URL . 'assets/css/style.css',
		array(), // No dependencies
		WP_SWISS_KNIFE_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'wp_swiss_knife_enqueue_admin_styles' );

// Autoload classes
spl_autoload_register( function( $class ) {
	if ( strpos( $class, 'WP_Swiss_Knife_' ) === 0 ) {
		// First try to load from 'includes' directory
		$class_file = WP_SWISS_KNIFE_PATH . 'includes/' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
		if ( file_exists( $class_file ) ) {
			require_once $class_file;
		} else {
			// If not found, try to load from 'admin' directory
			$class_file = WP_SWISS_KNIFE_PATH . 'admin/' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
			if ( file_exists( $class_file ) ) {
				require_once $class_file;
			}
		}
	}
});

// Initialize the plugin
function wp_swiss_knife_init() {
	if ( class_exists( 'WP_Swiss_Knife_Redirects' ) ) {
		new WP_Swiss_Knife_Redirects();
	}

	if ( class_exists( 'WP_Swiss_Knife_Security' ) ) {
		new WP_Swiss_Knife_Security();
	}

	if ( class_exists( 'WP_Swiss_Knife_SVG_ICO_Support' ) ) {
		new WP_Swiss_Knife_SVG_ICO_Support();
	}

	if ( class_exists( 'WP_Swiss_Knife_Content' ) ) {
		new WP_Swiss_Knife_Content();
	}

	if ( is_admin() && class_exists( 'WP_Swiss_Knife_Admin_Settings' ) ) {
		new WP_Swiss_Knife_Admin_Settings();
	}
}
add_action( 'plugins_loaded', 'wp_swiss_knife_init' );