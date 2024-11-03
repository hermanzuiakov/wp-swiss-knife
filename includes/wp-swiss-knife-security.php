<?php

class WP_Swiss_Knife_Security {
	public function __construct() {
		// Add the necessary actions/hooks based on user settings
		add_action( 'init', array( $this, 'apply_security_settings' ) );
	}

	public function apply_security_settings() {
		$options = get_option( 'wp_swiss_knife_settings' );

		// Disable XML-RPC
		if ( isset( $options['disable_xmlrpc'] ) && $options['disable_xmlrpc'] ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
		}

		// Disable REST API for non-authenticated users
		if ( isset( $options['disable_rest_api'] ) && $options['disable_rest_api'] ) {
			add_filter( 'rest_authentication_errors', array( $this, 'restrict_rest_api' ) );
		}

		// Disable login error messages
		if ( isset( $options['disable_login_errors'] ) && $options['disable_login_errors'] ) {
			add_filter( 'login_errors', array( $this, 'hide_login_errors' ) );
		}
	}

	// Restrict REST API to authenticated users only
	public function restrict_rest_api( $result ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error( 'rest_cannot_access', __( 'REST API restricted to authenticated users only.', 'wp-swiss-knife' ), array( 'status' => 401 ) );
		}

		return $result;
	}

	// Hide login error messages
	public function hide_login_errors(): ?string {
		return __( 'Login failed.', 'wp-swiss-knife' );
	}
}