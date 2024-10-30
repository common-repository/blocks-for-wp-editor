<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) || ! defined( 'S2_WPEB_VERSION' ) ) {
	exit;
}

/**
 * Implement rest api features to manage products
 *
 * @since 1.0.0
 *
 * @see WP_REST_Posts_Controller
 */
class S2_REST_Posts_Controller extends WP_REST_Posts_Controller {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type Post type.
	 */
	public function __construct( $post_type ) {

		// parent::__construct( $post_type );

		$this->post_type = $post_type;
		$this->namespace = S2_WPEB_NAMESPACE;
		$obj             = get_post_type_object( 'post' );
		$this->rest_base = ! empty( $obj->rest_base ) ? $obj->rest_base : $obj->name;

		$this->meta = new WP_REST_Post_Meta_Fields( $this->post_type );

	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @since 1.0.0
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

	}

}
