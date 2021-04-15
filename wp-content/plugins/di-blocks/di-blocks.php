<?php
/**
 * Plugin Name: Di Blocks
 * Description: Awesome Blocks for WordPress ( Gutenberg ) Editor.
 * Version: 1.0.7
 * Author: dithemes
 * Author URI: https://dithemes.com
 * Text Domain: di-blocks
 * Requires at least: 5.0
 * Requires PHP: 5.6
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Handle classic editor
if( class_exists( 'Classic_Editor' ) ) {
	$editor_option = get_option( 'classic-editor-replace' );
	if ( isset( $editor_option ) && 'block' !== $editor_option ) {
		add_action( 'admin_notices', 'di_blocks_failed_to_load_ce' );
		return; // return from scope.
	}
}

function di_blocks_failed_to_load_ce() {
	$message = sprintf( __( 'Di Blocks plugin requires %3$sBlock Editor%4$s. You can change your editor settings to Block Editor from %1$shere%2$s. Plugin is currently NOT RUNNING.', 'di-blocks' ), '<a href="' . admin_url( 'options-writing.php' ) . '">', '</a>', '<strong>', '</strong>' );
	$html_message = sprintf( '<div class="notice notice-warning">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

// Make sure GB active
if( function_exists( 'register_block_type' ) ) {
	// define constants
	define( 'Di_Blocks_VER' , '1.0.7' );
	define( 'Di_Blocks_FILE', __FILE__ );
	define( 'Di_Blocks_PATH', plugin_dir_path( Di_Blocks_FILE ) ); // wp_normalize_path( );
	define( 'Di_Blocks_URL', plugin_dir_url( Di_Blocks_FILE ) );
	define( 'Di_Blocks_BASENAME', plugin_basename( Di_Blocks_FILE ) );
	define( 'Di_Blocks_DIR_NAME', dirname( Di_Blocks_BASENAME ) );
	define( 'Di_Blocks_TABLET_BREAKPOINT', '768' );
	define( 'Di_Blocks_MOBILE_BREAKPOINT', '576' );

	// Load the plugin file
	require_once Di_Blocks_PATH . 'classes/init.php';
} else {
	add_action( 'admin_notices', 'di_blocks_failed_to_load_gb' );
	return; // return from scope.
}

function di_blocks_failed_to_load_gb() {
	$message = esc_html__( 'Di Blocks plugin require New WordPress Editor (Gutenberg). Di Blocks plugin is currently NOT RUNNING.', 'di-blocks' );
	$html_message = sprintf( '<div class="notice notice-error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

