<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) || ! defined( 'S2_WPEB_VERSION' ) ) {
	exit;
}

/**
 * Implements features of S2 WP Editor Blocks Post
 *
 * @package S2 WP Editor Blocks
 * @since   1.0.0
 * @author  Shuban Studio <shuban.studio@gmail.com>
 */
if ( ! class_exists( 'S2_WP_Editor_Blocks_Post' ) ) {

	class S2_WP_Editor_Blocks_Post {

		/**
		 * Single instance of the class
		 *
		 * @var \S2_WP_Editor_Blocks_Post
		 */
		protected static $instance;

		/**
		 * Default attributes
		 *
		 * @var \S2_WP_Editor_Blocks_Post
		 */
		private $block_attributes;

		/**
		 * Returns single instance of the class
		 *
		 * @return \S2_WP_Editor_Blocks_Post
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
		 * Register blocks, actions and filters to be used
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

		    register_block_type( 's2-wp-editor-blocks/post', 
		    	array(
		        	'api_version' 	  => 2,
		        	'style' 		  => 's2-wp-editor-blocks-style',
        			'editor_style' 	  => 's2-wp-editor-blocks-editor',
        			'editor_script'   => 's2-wp-editor-blocks-editor',
        			'render_callback' => array( $this, 'dynamic_render_callback' ),
		    	) 
		    );

		    $metadata_file = S2_WPEB_INC . 'blocks/post/block.json';
		    $this->block_attributes = json_decode( file_get_contents( $metadata_file ), true );

		}

		public function dynamic_render_callback( $attributes ) {

			$attributes_defaults = array();
			foreach ( $this->block_attributes['attributes'] as $key => $attribute ) {
				$attributes_defaults[ $key ] = $attribute['default'];
			}

			$attributes = array_merge( $attributes_defaults, $attributes );

			$args = array(
				'posts_per_page'   => $attributes['postsToShow'],
				'post_type'        => $attributes['postType'],
				'post_status'      => 'publish',
				'order'            => $attributes['order'],
				'orderby'          => $attributes['orderBy'],
				'suppress_filters' => false,
			);

			if ( isset( $attributes['categories'] ) ) {
				$args['category__in'] = array_column( $attributes['categories'], 'id' );
			}
			if ( post_type_supports( $attributes['postType'], 'author' ) && isset( $attributes['selectedAuthor'] ) ) {
				$args['author'] = $attributes['selectedAuthor'];
			}

			$recent_posts = get_posts( $args );

			$list_items_markup = '';

			foreach ( $recent_posts as $post ) {
				$post_link = esc_url( get_permalink( $post ) );

				$list_items_markup .= '<li>';

				$post_title_classes = 'wp-block-s2-wp-editor-blocks-post__post-title';
				if ( isset( $attributes['postTitleAlign'] ) ) {
					$post_title_classes .= ' align' . $attributes['postTitleAlign'];
				}
				$list_items_markup .= sprintf(
					'<span class="%1$s">',
					$post_title_classes,
				);

				$title = get_the_title( $post );
				if ( ! $title ) {
					$title = __( '(no title)' );
				}
				$title = wp_trim_words( $title, $attributes['postTitleLength'] );
				$list_items_markup .= sprintf(
					'<a href="%1$s">%2$s</a>',
					$post_link,
					$title
				);

				if ( post_type_supports( $attributes['postType'], 'author' ) 
						&& isset( $attributes['displayPostAuthor'] ) 
						&& $attributes['displayPostAuthor'] ) {

					$author_display_name = get_the_author_meta( 'display_name', $post->post_author );

					$byline = sprintf( __( 'by %s' ), $author_display_name );

					$post_author_classes = 'wp-block-s2-wp-editor-blocks-post__post-author';
					/*if ( isset( $attributes['postTitleAlign'] ) ) {
						$post_author_classes .= ' align' . $attributes['postTitleAlign'];
					}*/

					if ( ! empty( $author_display_name ) ) {
						$list_items_markup .= sprintf(
							'<span class="%1$s">%2$s</span>',
							$post_author_classes,
							esc_html( $byline )
						);
					}

				}

				if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {

					$post_date_classes = 'wp-block-s2-wp-editor-blocks-post__post-date';
					/*if ( isset( $attributes['postTitleAlign'] ) ) {
						$post_date_classes .= ' align' . $attributes['postTitleAlign'];
					}*/

					$list_items_markup .= sprintf(
						'<time class="%1$s" datetime="%2$s">%3$s</time>',
						$post_date_classes,
						esc_attr( get_the_date( 'c', $post ) ),
						esc_html( get_the_date( '', $post ) )
					);
				
				}

				$list_items_markup .= '</span>';

				if ( $attributes['displayFeaturedImage'] && has_post_thumbnail( $post ) ) {

					$image_classes = 'wp-block-s2-wp-editor-blocks-post__featured-image';
					if ( isset( $attributes['featuredImageAlign'] ) ) {
						$image_classes .= ' align' . $attributes['featuredImageAlign'];
					}

					$featured_image = get_the_post_thumbnail(
						$post,
						$attributes['featuredImageSizeSlug']
					);

					if ( $attributes['addLinkToFeaturedImage'] ) {
						$featured_image = sprintf(
							'<a href="%1$s">%2$s</a>',
							$post_link,
							$featured_image
						);
					}
					$list_items_markup .= sprintf(
						'<div class="%1$s">%2$s</div>',
						$image_classes,
						$featured_image
					);
				}

				$content_classes = '';
				if ( isset( $attributes['postContentAlign'] ) ) {
					$content_classes .= ' align' . $attributes['postContentAlign'];
				}

				if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
					&& isset( $attributes['displayPostContentRadio'] ) && 'excerpt' === $attributes['displayPostContentRadio'] ) {

					$trimmed_excerpt = get_the_excerpt( $post );

					if ( post_password_required( $post ) ) {
						$trimmed_excerpt = __( 'This content is password protected.' );
					}

					$trimmed_excerpt = wp_trim_words( $trimmed_excerpt, $attributes['postContentLength'] );

					$list_items_markup .= sprintf(
						'<div class="wp-block-s2-wp-editor-blocks-post__post-excerpt %1$s">%2$s</div>',
						$content_classes,
						$trimmed_excerpt
					);
				}

				if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent']
					&& ( isset( $attributes['displayPostContentRadio'] ) && 'content' === $attributes['displayPostContentRadio'] ) ) {

					$post_content = wp_kses_post( html_entity_decode( $post->post_content, ENT_QUOTES, get_option( 'blog_charset' ) ) );

					if( $post_content ) {
				
						if ( post_password_required( $post ) ) {
							$post_content = __( 'This content is password protected.' );
						}

						$post_content = wp_trim_words( $post_content, $attributes['postContentLength'] );

						$list_items_markup .= sprintf(
							'<div class="wp-block-s2-wp-editor-blocks-post__post-content %1$s">%2$s</div>',
							$content_classes,
							$post_content
						);

					}
				
				}

				$list_items_markup .= "</li>\n";
			}

			$class = 'wp-block-s2-wp-editor-blocks-post__list';

			if ( isset( $attributes['postTemplateColumn'] ) ) {
				$class .= ' is-grid columns-' . $attributes['postTemplateColumn'];
			}

			if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
				$class .= ' has-dates';
			}

			if ( isset( $attributes['displayAuthor'] ) && $attributes['displayAuthor'] ) {
				$class .= ' has-author';
			}

			$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class ) );

			return sprintf(
				'<ul %1$s>%2$s</ul>',
				$wrapper_attributes,
				$list_items_markup
			);

		}

	}

}

/**
 * Unique access to instance of S2_WP_Editor_Blocks_Post class
 */
S2_WP_Editor_Blocks_Post::get_instance();
