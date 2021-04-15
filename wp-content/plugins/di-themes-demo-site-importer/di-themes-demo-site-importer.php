<?php
/**
 * Plugin Name:	Di Themes Demo Site Importer
 * Description:	Import demo website of theme developed by Di Themes.
 * Version:		1.1.4
 * Author:		dithemes
 * Author URI:	https://dithemes.com
 * Text Domain: di-themes-demo-site-importer
 * Requires at least: 4.7
 * Requires PHP: 5.6
 *
 */

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No direct access, please!' );

/**
 * Display admin error message if PHP version is older than 5.3.2.
 * Otherwise execute the main plugin class.
 */
if ( version_compare( phpversion(), '5.6', '<' ) ) {

	/**
	 * Display an admin error notice when PHP is older the version 5.6.
	 * Hook it to the 'admin_notices' action.
	 */
	function dtdsi_old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$s Di Themes Demo Site Importer %3$s plugin requires %2$sPHP 5.6+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.6.%4$s Your current version of PHP: %2$s%1$s%3$s', 'di-themes-demo-site-importer' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	add_action( 'admin_notices', 'dtdsi_old_php_admin_error_notice' );
} else {

	define( 'DTDSI_VERSION' , '1.1.4' ); // Return version of this plugin.
	define( 'DTDSI_FILE', __FILE__ ); // Return 'path of this file'.
	define( 'DTDSI_PATH', wp_normalize_path( plugin_dir_path( DTDSI_FILE ) ) ); // Return 'path of this directory'.
	define( 'DTDSI_URL', plugin_dir_url( DTDSI_FILE ) ); // Return 'URL of this directory'.
	define( 'DTDSI_BASENAME', plugin_basename( DTDSI_FILE ) ); // Return base name like 'plugin-name/plugin-name.php'
	define( 'DTDSI_DIR_NAME', dirname( DTDSI_BASENAME ) ); // Return name of directory like 'plugin-name'

	// Find the correct template name.
	if( wp_get_theme()->Template ) {
		$DTDSI_Template = wp_get_theme()->Template;
	} else {
		$DTDSI_Template = 'not-set';
	}

	function dtdsi_plugin_action_links( $actions ) {
		$custom_link = array(
			'configure' => sprintf( '<a href="%s">%s</a>', admin_url() . 'themes.php?page=dtdsi', __( 'Import Demo', 'di-themes-demo-site-importer' ) ),
			);
		return array_merge( $custom_link, $actions );
	}

	function dtdsi_allow_svg_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
			return $mimes;
	}

	// Set import files and settings according template name.
	if( $DTDSI_Template == 'di-business' ) {

		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-business/import-settings.php';

	} elseif( $DTDSI_Template == 'di-blog' ) {

		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-blog/import-settings.php';

	} elseif( $DTDSI_Template == 'di-responsive' ) {

		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-responsive/import-settings.php';

	} elseif( $DTDSI_Template == 'di-ecommerce' ) {

		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-ecommerce/import-settings.php';

	} elseif( $DTDSI_Template == 'di-magazine' ) {

		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-magazine/import-settings.php';

	} elseif( $DTDSI_Template == 'di-restaurant' ) {
		
		// dtdsi_allow_svg_mime_types
		add_filter( 'upload_mimes', 'dtdsi_allow_svg_mime_types' );

		// check for plugin using plugin name
		if( ! in_array( 'one-click-demo-import/one-click-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			// Plugin action link : Import Demo
			add_filter( 'plugin_action_links_' . DTDSI_BASENAME, 'dtdsi_plugin_action_links', 10, 4 );
		    require DTDSI_PATH . 'inc/ocdi/one-click-demo-import.php';
		}

		// the theme setting after import.
		require DTDSI_PATH . 'inc/di-themes/di-restaurant/import-settings.php';

	} elseif( $DTDSI_Template == 'di-multipurpose' ) {
		// Importing demos of di-multipurpose theme.
		require( DTDSI_PATH . 'inc/di-multipurpose/demos.php' );
	} else {
		return;
	}


}