<?php
/**
 * Plugin Name: Blocks for WP Editor
 * Plugin URI: 
 * Description: <code><strong>Blocks for WP Editor</strong></code>
 * Version: 1.0.0
 * Author: Shuban Studio <shuban.studio@gmail.com>
 * Author URI: https://shubanstudio.github.io/
 * Text Domain: s2-wp-editor-blocks
 * Domain Path: /languages/
 */

/**
 * @package S2 WP Editor Blocks
 * @since   1.0.0
 * @author  Shuban Studio <shuban.studio@gmail.com>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Define constants
! defined( 'S2_WPEB_NAMESPACE' ) 		&& define( 'S2_WPEB_NAMESPACE', 's2/v1' );
! defined( 'S2_WPEB_DIR' ) 			&& define( 'S2_WPEB_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'S2_WPEB_VERSION' ) 		&& define( 'S2_WPEB_VERSION', '1.0.0' );
! defined( 'S2_WPEB_FILE' ) 			&& define( 'S2_WPEB_FILE', __FILE__ );
! defined( 'S2_WPEB_URL' ) 			&& define( 'S2_WPEB_URL', plugins_url( '/', __FILE__ ) );
! defined( 'S2_WPEB_ASSETS_URL' ) 	&& define( 'S2_WPEB_ASSETS_URL', S2_WPEB_URL . 'assets' );
! defined( 'S2_WPEB_TEMPLATE_PATH' ) 	&& define( 'S2_WPEB_TEMPLATE_PATH', S2_WPEB_DIR . 'templates' );
! defined( 'S2_WPEB_INC' ) 			&& define( 'S2_WPEB_INC', S2_WPEB_DIR . 'includes/' );
! defined( 'S2_WPEB_TEST_ON' ) 		&& define( 'S2_WPEB_TEST_ON', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) );
! defined( 'S2_WPEB_SUFFIX' ) 		&& define( 'S2_WPEB_SUFFIX', '.min' );
if ( ! defined( 'S2_WPEB_SUFFIX' ) ) {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	define( 'S2_WPEB_SUFFIX', $suffix );
}

/**
 * Load plugin
 *
 * @since 1.0.0
 */
function s2_wp_editor_blocks_install() {
	load_plugin_textdomain( 's2-wp-editor-blocks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	require_once S2_WPEB_INC . 'class.s2-wp-editor-blocks.php';
}
add_action( 'plugins_loaded', 's2_wp_editor_blocks_install', 11 );
