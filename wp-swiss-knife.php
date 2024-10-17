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