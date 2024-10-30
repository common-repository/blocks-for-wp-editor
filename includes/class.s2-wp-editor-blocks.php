<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) || ! defined( 'S2_WPEB_VERSION' ) ) {
	exit;
}

/**
 * Implements features of S2 WP Editor Blocks
 *
 * @package S2 WP Editor Blocks
 * @since   1.0.0
 * @author  Shuban Studio <shuban.studio@gmail.com>
 */
if ( ! class_exists( 'S2_WP_Editor_Blocks' ) ) {

	class S2_WP_Editor_Blocks {

		/**
		 * Plugin settings
		 */
		public $s2gb_settings;

		/**
		 * Single instance of the class
		 *
		 * @var \S2_WP_Editor_Blocks
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \S2_WP_Editor_Blocks
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
		 * Initialize plugin and register actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			if ( ! function_exists( 'register_block_type' ) ) {
				// WP Editor is not active.
				return;
			}

			// Plugin settings
			// $this->s2gb_settings = get_option('s2gb_settings');

			require_once S2_WPEB_INC . 'class.s2-wp-editor-blocks-admin.php';
			require_once S2_WPEB_INC . 'class.s2-wp-editor-blocks-frontend.php';
			require_once S2_WPEB_INC . 'blocks/post/class.s2-wp-editor-blocks-post.php';

			// rest api
			require_once S2_WPEB_INC . 'rest/class.s2-rest-posts-controller.php';

			// Register rest routes
			add_action( 'rest_api_init', array( $this, 's2_rest_api_init' ) );

		}

		/**
		 * Register rest route
		 *
		 * @since 1.0.0
		 */
		public function s2_rest_api_init() {

			// Posts.
			if( ! empty( $_GET['post_type'] ) ) {
			
				// error_log( 'get_items--' . print_r($_GET, true), 3, WP_CONTENT_DIR . '/debug.log' );
				// $controller = new S2_REST_Posts_Controller( $_GET['post_type'] );
				// $controller->register_routes();

			}

		}

	}

}

/**
 * Unique access to instance of S2_WP_Editor_Blocks class
 *
 * @return \S2_WP_Editor_Blocks
 */
S2_WP_Editor_Blocks::get_instance();
