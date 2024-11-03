<?php


class WP_Swiss_Knife_SVG_ICO_Support {

	public function __construct() {
		// Add support for SVG and ICO file types if enabled in settings
		add_filter( 'upload_mimes', array( $this, 'allow_svg_ico_upload' ) );

		// Sanitize SVG files on upload
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'check_svg_upload' ), 10, 4 );

		// Fix SVG display in the media library
		add_action( 'admin_head', array( $this, 'add_svg_styles' ) );
	}

	/**
	 * Allow SVG and ICO file upload in WordPress based on settings
	 */
	public function allow_svg_ico_upload( $mimes ) {
		$options = get_option( 'wp_swiss_knife_settings', array() );

		// Check if SVG support is enabled
		if ( isset( $options['enable_svg_support'] ) && $options['enable_svg_support'] ) {
			$mimes['svg'] = 'image/svg+xml';
		}

		// Check if ICO support is enabled
		if ( isset( $options['enable_ico_support'] ) && $options['enable_ico_support'] ) {
			$mimes['ico'] = 'image/x-icon';
		}

		return $mimes;
	}

	/**
	 * Sanitize SVG files on upload to prevent malicious code execution
	 */
	public function check_svg_upload( $data, $file, $filename, $mimes ) {
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );

		// Only process SVG files
		if ( 'svg' === $ext ) {
			$file_contents = file_get_contents( $file );

			// Check for potentially unsafe content
			if ( strpos( $file_contents, '<script' ) !== false || strpos( $file_contents, 'onload=' ) !== false ) {
				// If SVG contains suspicious code, disallow the upload
				return array( 'ext' => false, 'type' => false, 'proper_filename' => false );
			}
		}

		return $data;
	}

	/**
	 * Add inline styles to display SVGs correctly in the media library
	 */
	public function add_svg_styles() {
		echo '<style>
            .attachment-266x266, .thumbnail img[src$=".svg"] {
                width: 100% !important;
                height: auto !important;
            }
        </style>';
	}
}