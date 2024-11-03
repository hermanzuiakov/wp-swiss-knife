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
		register_setting( 'wp_swiss_knife_redirects', 'wp_swiss_knife_redirects' );

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

		register_setting( 'wp_swiss_knife_security', 'wp_swiss_knife_security' );

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

        register_setting( 'wp_swiss_knife_svg_ico', 'wp_swiss_knife_svg_ico' );

        add_settings_section(
            'support_svg_ico',
            __( 'SVG and ICO Support', 'wp-swiss-knife' ),
            null,
            'wp_swiss_knife_svg_ico'
        );

        add_settings_field(
            'enable_svg_support',
            __( 'Enable SVG Support', 'wp-swiss-knife' ),
            array( $this, 'render_enable_svg_support_field' ),
            'wp_swiss_knife_svg_ico',
            'support_svg_ico'
        );

        add_settings_field(
            'enable_ico_support',
            __( 'Enable ICO Support', 'wp-swiss-knife' ),
            array( $this, 'render_enable_ico_support_field' ),
            'wp_swiss_knife_svg_ico',
            'support_svg_ico'
        );

        register_setting( 'wp_swiss_knife_content', 'wp_swiss_knife_content' );

        add_settings_section(
            'content_settings',
            __( 'Content Settings', 'wp-swiss-knife' ),
            null,
            'wp_swiss_knife_content'
        );

        add_settings_field(
            'disable_guttenberg',
            __( 'Disable Guttenberg', 'wp-swiss-knife' ),
            array( $this, 'render_disable_guttenberg_field' ),
            'wp_swiss_knife_content',
            'content_settings'
        );
	}

	public function render_http_https_field() {
		$options = get_option( 'wp_swiss_knife_redirects' );
		$value = $options['redirect_http_https'] ?? 'disabled';
		?>
		<select class="wp_swiss_knife_select" name="wp_swiss_knife_redirects[redirect_http_https]">
			<option value="disabled" <?php selected( $value, 'disabled' ); ?>><?php _e( 'Disabled', 'wp-swiss-knife' ); ?></option>
			<option value="https" <?php selected( $value, 'https' ); ?>><?php _e( 'Redirect HTTP to HTTPS', 'wp-swiss-knife' ); ?></option>
			<option value="http" <?php selected( $value, 'http' ); ?>><?php _e( 'Redirect HTTPS to HTTP', 'wp-swiss-knife' ); ?></option>
		</select>
		<?php
	}

	public function render_www_field() {
		$options = get_option( 'wp_swiss_knife_redirects' );
		$value = $options['redirect_www'] ?? 'disabled';
		?>
		<select class="wp_swiss_knife_select" name="wp_swiss_knife_redirects[redirect_www]">
			<option value="disabled" <?php selected( $value, 'disabled' ); ?>><?php _e( 'Disabled', 'wp-swiss-knife' ); ?></option>
			<option value="www" <?php selected( $value, 'www' ); ?>><?php _e( 'Redirect to www', 'wp-swiss-knife' ); ?></option>
			<option value="no-www" <?php selected( $value, 'no-www' ); ?>><?php _e( 'Redirect to no www', 'wp-swiss-knife' ); ?></option>
		</select>
		<?php
	}

	public function render_disable_xmlrpc_field() {
		$options = get_option( 'wp_swiss_knife_security' );
		$value = $options['disable_xmlrpc'] ?? 0;
		?>
		<input type="checkbox" id="disable_xmlrpc" name="wp_swiss_knife_security[disable_xmlrpc]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_xmlrpc">
            <?php _e( 'Disable XML-RPC', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}

	public function render_disable_rest_api_field() {
		$options = get_option( 'wp_swiss_knife_security' );
        $value = $options['disable_rest_api'] ?? 0;
		?>
        <input type="checkbox" id="disable_rest_api" name="wp_swiss_knife_security[disable_rest_api]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_rest_api">
            <?php _e( 'Disable REST API for non-authenticated users', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}

	public function render_disable_login_errors_field() {
		$options = get_option( 'wp_swiss_knife_security' );
        $value = $options['disable_login_errors'] ?? 0;
		?>
        <input type="checkbox" id="disable_login_errors" name="wp_swiss_knife_security[disable_login_errors]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_login_errors">
            <?php _e( 'Disable login error messages', 'wp-swiss-knife' ); ?>
        </label>
		<?php
	}


    public function render_enable_svg_support_field() {
        $options = get_option( 'wp_swiss_knife_svg_ico' );
        $value = $options['enable_svg_support'] ?? 0;
        ?>
        <input type="checkbox" id="enable_svg_support" name="wp_swiss_knife_svg_ico[enable_svg_support]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="enable_svg_support">
            <?php _e( 'Enable SVG Support', 'wp-swiss-knife' ); ?>
        </label>
        <?php
    }

    public function render_enable_ico_support_field() {
        $options = get_option( 'wp_swiss_knife_svg_ico' );
        $value = $options['enable_ico_support'] ?? 0;
        ?>
        <input type="checkbox" id="enable_ico_support" name="wp_swiss_knife_svg_ico[enable_ico_support]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="enable_ico_support">
            <?php _e( 'Enable ICO Support', 'wp-swiss-knife' ); ?>
        </label>
        <?php
    }


    public function render_disable_guttenberg_field() {
        $options = get_option( 'wp_swiss_knife_content' );
        $value = $options['disable_gutenberg'] ?? 0;
        ?>
        <input type="checkbox" id="disable_gutenberg" name="wp_swiss_knife_content[disable_gutenberg]" value="1" <?php checked( 1, $value, true ); ?>>
        <label for="disable_gutenberg">
            <?php _e( 'Disable Guttenberg', 'wp-swiss-knife' ); ?>
        </label>
        <?php
    }


	public function options_page() {
		?>
		<h2><?php _e( 'WP Swiss Knife Settings', 'wp-swiss-knife' ); ?></h2>
		<h2 class="nav-tab-wrapper">
			<a href="?page=wp_swiss_knife&tab=redirects" class="nav-tab <?php echo $this->get_active_tab() == 'redirects' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Redirects', 'wp-swiss-knife' ); ?></a>
			<a href="?page=wp_swiss_knife&tab=security" class="nav-tab <?php echo $this->get_active_tab() == 'security' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Security', 'wp-swiss-knife' ); ?></a>
            <a href="?page=wp_swiss_knife&tab=svg_ico" class="nav-tab <?php echo $this->get_active_tab() == 'svg_ico' ? 'nav-tab-active' : ''; ?>"><?php _e( 'SVG & ICO', 'wp-swiss-knife' ); ?></a>
            <a href="?page=wp_swiss_knife&tab=content" class="nav-tab <?php echo $this->get_active_tab() == 'content' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Content', 'wp-swiss-knife' ); ?></a>
		</h2>
		<form action="options.php" method="post">
			<?php
			$tab = $this->get_active_tab();
			settings_fields( 'wp_swiss_knife_' . $tab );

			switch ( $this->get_active_tab() ) {
                case 'redirects':
                    do_settings_sections( 'wp_swiss_knife_redirects' );
                    break;
                case 'security':
                    do_settings_sections( 'wp_swiss_knife_security' );
                    break;
                case 'svg_ico':
                    do_settings_sections( 'wp_swiss_knife_svg_ico' );
                    break;
                case 'content':
                    do_settings_sections( 'wp_swiss_knife_content' );
                    break;
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
