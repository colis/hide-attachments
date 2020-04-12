<?php
/**
 * Media Library related functionalities.
 *
 * @package colis/hide-attachments
 */

namespace HideAttachments\MediaLibrary;

use HideAttachments\Registrable;
use HideAttachments\Plugin;
use WP_Query;

defined( 'ABSPATH' ) || die();

/**
 * Class MediaLibrary
 *
 * @package colis/hide-attachments
 */
class MediaLibrary extends Plugin implements Registrable {
	/**
	 * Register into WordPress
	 */
	public function register() {
		add_filter( 'ajax_query_attachments_args', [ $this, 'filter_hide_from_media_overlay_view' ] );
		add_action( 'pre_get_posts', [ $this, 'action_hide_from_media_list_view' ] );
	}

	/**
	 * Hide attachments from the media overlay view.
	 *
	 * @param array $query An array of query variables.
	 *
	 * @return array The filtered array of query variables.
	 */
	public function filter_hide_from_media_overlay_view( array $query ) : array {
		// Bail early if this is not the admin area.
		if ( ! is_admin() ) {
			return $query;
		}

		$query['post_parent__not_in'] = $this->get_posts_by_category();

		return $query;
	}

	/**
	 * Hide attachments from the media list view.
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	public function action_hide_from_media_list_view( WP_Query $query ) {
		// Bail early if this is not the admin area.
		if ( ! is_admin() ) {
			return;
		}

		// Bail early if this is not the main query.
		if ( ! $query->is_main_query() ) {
			return;
		}

		// Only proceed if this is the attachment upload screen.
		$screen = get_current_screen();
		if ( ! $screen || 'upload' !== $screen->id || 'attachment' !== $screen->post_type ) {
			return;
		}

		$query->set( 'post_parent__not_in', $this->get_posts_by_category() );
	}

	/**
	 * Return an array of post IDs that belong to a specific category.
	 *
	 * @return array The array of post IDs.
	 */
	private function get_posts_by_category() : array {
		$option_value = get_option( self::OPTION_NAME );

		$category_terms = array_keys( ( ! empty( $option_value ) ) ? $option_value : [] );

		$query = new WP_Query(
			[
				'post_status'            => 'publish',
				'post_type'              => 'post',
				'posts_per_page'         => -1,
				'tax_query'              => [
					[
						'taxonomy' => 'category',
						'terms'    => $category_terms,
					],
				],
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'fields'                 => 'ids',
			]
		);

		return $query->posts;
	}
}
