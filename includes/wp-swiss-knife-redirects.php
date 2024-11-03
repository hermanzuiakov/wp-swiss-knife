<?php

class WP_Swiss_Knife_Redirects {
	public function __construct() {
		// Hook for handling redirects
		add_action( 'template_redirect', array( $this, 'handle_redirects' ) );
	}

	public function handle_redirects() {
		$options = get_option( 'wp_swiss_knife_settings' );

		// Redirect HTTP -> HTTPS
		if ( isset( $options['redirect_http_https'] ) && $options['redirect_http_https'] === 'https' && !is_ssl() ) {
			wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
			exit;
		}

		// Redirect HTTPS -> HTTP
		if ( isset( $options['redirect_http_https'] ) && $options['redirect_http_https'] === 'http' && is_ssl() ) {
			wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
			exit;
		}

		// Redirect non-www -> www
		if ( isset( $options['redirect_www'] ) && $options['redirect_www'] === 'no-www' && strpos( $_SERVER['HTTP_HOST'], 'www.' ) === 0 ) {
			wp_redirect( 'https://' . substr( $_SERVER['HTTP_HOST'], 4 ) . $_SERVER['REQUEST_URI'], 301 );
			exit;
		}

		// Redirect www -> non-www
		if ( isset( $options['redirect_www'] ) && $options['redirect_www'] === 'www' && strpos( $_SERVER['HTTP_HOST'], 'www.' ) === false ) {
			wp_redirect( 'https://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
			exit;
		}
	}
}
