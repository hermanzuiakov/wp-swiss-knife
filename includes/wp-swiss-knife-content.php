<?php

class WP_Swiss_Knife_Content {

	public function __construct() {
		// Check if Gutenberg should be disabled
		add_action( 'init', array( $this, 'disable_gutenberg' ) );
	}

	/**
	 * Disable Gutenberg editor if the setting is enabled
	 */
	public function disable_gutenberg() {
		$options = get_option( 'wp_swiss_knife_settings', array() );

		// Check if the option to disable Gutenberg is set
		if ( isset( $options['disable_gutenberg'] ) && $options['disable_gutenberg'] ) {
			add_filter( 'use_block_editor_for_post', '__return_false', 10 );
			add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );
		}
	}
}