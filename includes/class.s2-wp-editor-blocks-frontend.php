<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) || ! defined( 'S2_WPEB_VERSION' ) ) {
	exit;
}

/**
 * Implements features of S2 WP Editor Blocks Frontend
 *
 * @package S2 WP Editor Blocks
 * @since   1.0.0
 * @author  Shuban Studio <shuban.studio@gmail.com>
 */
if ( ! class_exists( 'S2_WP_Editor_Blocks_Frontend' ) ) {

	class S2_WP_Editor_Blocks_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \S2_WP_Editor_Blocks_Frontend
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \S2_WP_Editor_Blocks_Frontend
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
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			// custom styles and javascripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ), 11 );

		}

		/**
		 * Enqueue styles and scripts
		 *
		 * @since  1.0.0
		 */
		public function enqueue_styles_scripts() {

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
 * Unique access to instance of S2_WP_Editor_Blocks_Frontend class
 */
S2_WP_Editor_Blocks_Frontend::get_instance();
