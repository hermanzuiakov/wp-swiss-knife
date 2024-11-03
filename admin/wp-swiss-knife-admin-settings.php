<?php

class WP_Swiss_Knife_Admin_Settings {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	public function add_admin_menu() {
		add_options_page(
			__( 'WP Swiss Knife Settings', 'wp-swiss-knife' ),
			__( 'WP Swiss Knife', 'wp-swiss-knife' ),
			'manage_options',
			'wp_swiss_knife',
			array( $this, 'options_page' )
		);
	}

	public function settings_init() {
		register_setting( 'wp_swiss_knife', 'wp_swiss_knife_settings' );

		// Redirect settings section
		add_settings_section(
			'wp_swiss_knife_section_redirects',
			__( 'Redirect Settings', 'wp-swiss-knife' ),
			null,
			'wp_swiss_knife_redirects'
		);

		add_settings_field(
			'redirect_http_https',
			__( 'HTTP to HTTPS Redirect', 'wp-swiss-knife' ),
			array( $this, 'render_http_https_field' ),
			'wp_swiss_knife_redirects',
			'wp_swiss_knife_section_redirects'
		);

		add_settings_field(
			'redirect_www',
			__( 'WWW Redirect', 'wp-swiss-knife' ),
			array( $this, 'render_www_field' ),
			'wp_swiss_knife_redirects',
			'wp_swiss_knife_section_redirects'
		);

		// Security settings section
		add_settings_section(
			'wp_swiss_knife_section_security',
			__( 'Security Settings', 'wp-swiss-knife' ),
			null,
			'wp_swiss_knife_security'
		);

		// XML-RPC disable field
		add_settings_field(
			'disable_xmlrpc',
			__( 'Disable XML-RPC', 'wp-swiss-knife' ),
			array( $this, 'render_disable_xmlrpc_field' ),
			'wp_swiss_knife_security',
			'wp_swiss_knife_section_security'
		);

		// Disable REST API for non-authenticated users
		add_settings_field(
			'disable_rest_api',
			__( 'Disable REST API for non-authenticated users', 'wp-swiss-knife' ),
			array( $this, 'render_disable_rest_api_field' ),
			'wp_swiss_knife_security',
			'wp_swiss_knife_section_security'
		);

		// Disable login error messages
		add_settings_field(
			'disable_login_errors',
			__( 'Disable login error messages', 'wp-swiss-knife' ),
			array( $this, 'render_disable_login_errors_field' ),
			'wp_swiss_knife_security',
			'wp_swiss_knife_section_security'
		);
	}

	public function render_http_https_field() {
		$options = get_option( 'wp_swiss_knife_settings' );
		$value = $options['redirect_http_https'] ?? 'disabled';
		?>
		<select class="wp_swiss_knife_select" name="wp_swiss_knife_settings[redirect_http_https]">
			<option value="disabled" <?php selected( $value, 'disabled' ); ?>><?php _e( 'Disabled', 'wp-swiss-knife' ); ?></option>
			<option value="https" <?php selected( $value, 'https' ); ?>><?php _e( 'Redirect HTTP to HTTPS', 'wp-swiss-knife' ); ?></option>
			<option value="http" <?php selected( $value, 'http' ); ?>><?php _e( 'Redirect HTTPS to HTTP', 'wp-swiss-knife' ); ?></option>
		</select>
		<?php
	}

	public function render_www_field() {
		$options = get_option( 'wp_swiss_knife_settings' );
		$value = $options['redirect_www'] ?? 'disabled';
		?>
		<select class="wp_swiss_knife_select" name="wp_swiss_knife_settings[redirect_www]">
			<option value="disabled" <?php selected( $value, 'disabled' ); ?>><?php _e( 'Disabled', 'wp-swiss-knife' ); ?></option>
			<option value="www" <?php selected( $value, 'www' ); ?>><?php _e( 'Redirect to www', 'wp-swiss-knife' ); ?></option>
			<option value="no-www" <?php selected( $value, 'no-www' ); ?>><?php _e( 'Redirect to no www', 'wp-swiss-knife' ); ?></option>
		</select>
		<?php
	}

	public function render_disable_xmlrpc_field() {
		$options = get_option( 'wp_swiss_knife_settings' );
		$value = $options['disable_xmlrpc'] ?? 0;
		?>
		<input type="checkbox" id="disable_xmlrpc" name="wp_swiss_knife_settings[disable_xmlrpc]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_xmlrpc">
            <?php _e( 'Disable XML-RPC', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}

	public function render_disable_rest_api_field() {
		$options = get_option( 'wp_swiss_knife_settings' );
        $value = $options['disable_rest_api'] ?? 0;
		?>
        <input type="checkbox" id="disable_rest_api" name="wp_swiss_knife_settings[disable_rest_api]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_rest_api">
            <?php _e( 'Disable REST API for non-authenticated users', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}

	public function render_disable_login_errors_field() {
		$options = get_option( 'wp_swiss_knife_settings' );
        $value = $options['disable_login_errors'] ?? 0;
		?>
        <input type="checkbox" id="disable_login_errors" name="wp_swiss_knife_settings[disable_login_errors]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_login_errors">
            <?php _e( 'Disable login error messages', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}


	public function options_page() {
		?>
		<h2><?php _e( 'WP Swiss Knife Settings', 'wp-swiss-knife' ); ?></h2>
		<h2 class="nav-tab-wrapper">
			<a href="?page=wp_swiss_knife&tab=redirects" class="nav-tab <?php echo $this->get_active_tab() == 'redirects' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Redirects', 'wp-swiss-knife' ); ?></a>
			<a href="?page=wp_swiss_knife&tab=security" class="nav-tab <?php echo $this->get_active_tab() == 'security' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Security', 'wp-swiss-knife' ); ?></a>
		</h2>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'wp_swiss_knife' );

			if ( $this->get_active_tab() == 'redirects' ) {
				do_settings_sections( 'wp_swiss_knife_redirects' );
			} else {
				do_settings_sections( 'wp_swiss_knife_security' );
			}

			submit_button();
			?>
		</form>
		<?php
	}

	private function get_active_tab() {
		return $_GET['tab'] ?? 'redirects';
	}
}
