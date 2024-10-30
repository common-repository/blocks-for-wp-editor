<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) || ! defined( 'S2_WPEB_VERSION' ) ) {
	exit;
}

/**
 * Implements admin features of S2 WP Editor Blocks Admin
 *
 * @package S2 WP Editor Blocks
 * @since   1.0.0
 * @author  Shuban Studio <shuban.studio@gmail.com>
 */
if ( ! class_exists( 'S2_WP_Editor_Blocks_Admin' ) ) {

	class S2_WP_Editor_Blocks_Admin {

		/**
		 * Single instance of the class
		 *
		 * @var \S2_WP_Editor_Blocks_Admin
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \S2_WP_Editor_Blocks_Admin
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		}

		/**
		 * Load admin scripts.
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts() {
		
			$dependencies = array( 'jquery', 'wp-api-fetch', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-core-data', 'wp-data', 'wp-date', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-primitives' );

			wp_enqueue_script(
		        's2-wp-editor-blocks-editor',
		        S2_WPEB_ASSETS_URL . '/js/s2-wp-editor-blocks-editor' . S2_WPEB_SUFFIX . '.js',
		        $dependencies,
				filemtime( S2_WPEB_DIR . 'assets/js/s2-wp-editor-blocks-editor' . S2_WPEB_SUFFIX . '.js' ),
				true
		    );

		    wp_enqueue_style(
		        's2-wp-editor-blocks-editor',
		        S2_WPEB_ASSETS_URL . '/css/s2-wp-editor-blocks-editor' . S2_WPEB_SUFFIX . '.css',
		        array( 'wp-edit-blocks' ),
		        filemtime( S2_WPEB_DIR . 'assets/css/s2-wp-editor-blocks-editor' . S2_WPEB_SUFFIX . '.css' ),
		    );
		 
		    wp_enqueue_style(
		        's2-wp-editor-blocks-style',
		        S2_WPEB_ASSETS_URL . '/css/s2-wp-editor-blocks-style' . S2_WPEB_SUFFIX . '.css',
		        array(),
		        filemtime( S2_WPEB_DIR . 'assets/css/s2-wp-editor-blocks-style' . S2_WPEB_SUFFIX . '.css' ),
		    );

		}

	}

}

/**
 * Unique access to instance of S2_WP_Editor_Blocks_Admin class
*/
if ( is_admin() || current_user_can( 'manage_options' ) ) {
	S2_WP_Editor_Blocks_Admin::get_instance();
}
