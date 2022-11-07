<?php

namespace O3CliServices\Service;

use O3CliServices\Url_List_Plan_Interface;

/**
 * A generic service that returns paths of posts and menu items based on HTTP
 * request query parameters
 */
class Url_List_Manager {


	/**
	 * Build generic test array, to be converted to JSON
	 *
	 * @param Url_List_Plan_Interface $test_plan
	 * @return array
	 */
	public function build_paths_array( Url_List_Plan_Interface $test_plan ) {
		$node_paths   = $this->build_post_paths( $test_plan );
		$menu_paths   = $this->build_menu_paths( $test_plan );
		$merged_paths = array_merge( $node_paths, $menu_paths );
		sort( $merged_paths );
		return $merged_paths;
	}

	/**
	 * Build the node paths from the provided test plan
	 *
	 * @param Url_List_Plan_Interface $test_plan
	 * @return array $paths
	 */
	protected function build_post_paths( Url_List_Plan_Interface $test_plan ) {
		$properties = $test_plan->get_properties();
		$post_data  = $this->get_post_type_data( 'path', $properties['post_types'], $properties['limit'], $properties['categories'] );
		return $post_data;
	}

	/**
	 * Get post types, with count of each
	 *
	 * @return array
	 */
	public function count_posts_by_type() {
		$post_types = get_post_types();
		sort( $post_types );
		return $this->get_post_type_data( 'count', $post_types );
	}

	/**
	 * Get post type IDs, depending on count or path
	 *
	 * @param string $data_type
	 * @param array $post_types
	 * @param integer $limit
	 * @param array|null $categories
	 * @return void
	 */
	protected function get_post_type_data( $data_type = 'path', $post_types, $limit = -1, $categories = null ) {
		$post_ids = array();
		foreach ( $post_types as $type ) {
			if ( $categories ) {
				$post_ids[ $type ] = array();
				foreach ( $categories as $category_slug ) {
					$category          = get_category_by_slug( $category_slug );
					$post_ids[ $type ] = array_merge( $post_ids[ $type ], $this->get_post_ids( $limit, $type, $category->term_id ) );
				}
			} else {
				$post_ids[ $type ] = $this->get_post_ids( $limit, $type );
			}
		}
		$extracted_data = array();
		if ( $data_type === 'path' ) {
			// Extract paths from all post types, flattening array.
			foreach ( $post_ids as $type_data ) {
				$extracted_data = array_merge(
					$extracted_data,
					array_map(
						function( $id ) {
							return str_replace( home_url(), '', get_permalink( $id ) );
						},
						$type_data
					)
				);
			}
			sort( $extracted_data );
		} elseif ( $data_type === 'count' ) {
			// Count ids per post type.
			foreach ( $post_ids as $type => $type_data ) {
				if ( count( $type_data ) ) {
					$extracted_data[ $type ] = array( 'count' => count( $type_data ) );
				}
			}
		}
		return $extracted_data;
	}

	/**
	 * Get IDs of posts
	 *
	 * @param integer $limit
	 * @param string $type
	 * @param integer $category
	 *   The term ID of the category term.
	 * @return void
	 */
	protected function get_post_ids( $limit, $type, $category = null ) {
		$args = array(
			'fields'         => 'ids',
			'posts_per_page' => $limit,
			'post_type'      => $type,
		);
		if ( $category ) {
			$args['category'] = $category;
		}
		return get_posts( $args );
	}

	/**
	 * Build the node paths from the provided test plan
	 *
	 * @param Url_List_Plan_Interface $test_plan
	 * @return array $paths
	 */
	protected function build_menu_paths( Url_List_Plan_Interface $test_plan ) {
		// Get the nodes, limit by limit.
		$properties = $test_plan->get_properties();
		return isset( $properties['menus'] ) ? $this->get_menu_data( $properties['menus'] ) : array();
	}

	/**
	 * Count items in all menus
	 *
	 * @return array
	 */
	public function count_menu_items() {
		$menus = wp_list_pluck( get_terms( 'nav_menu' ), 'slug' );
		sort( $menus );
		return $this->get_menu_data( $menus, 'count' );
	}

	/**
	 * Get node either count or path data from menus
	 *
	 * @param string $menus
	 * @param string $data_type
	 *   - Either 'path' or 'count'
	 * @return array
	 */
	protected function get_menu_data( $menus, $data_type = 'path' ) {
		$data = array();
		if ( ! empty( $menus ) ) {
			// Load each menu & extract all child links, recursively.
			foreach ( $menus as $menu_slug ) {
				$menu_objects       = wp_get_nav_menu_items( $menu_slug );
				$data[ $menu_slug ] = array_filter(
					array_map(
						function ( $menu_object ) {
							if ( $menu_object instanceof \WP_Post && strpos( $menu_object->url, home_url() ) !== false ) {
								return str_replace( home_url(), '', $menu_object->url );
							}
						},
						$menu_objects
					)
				);
			}
			$extracted_data = array();
			if ( $data_type === 'path' ) {
				// Extract paths from all menus, flattening array.
				foreach ( $data as $urls ) {
					$extracted_data = array_merge(
						$extracted_data,
						array_map(
							function( $url ) {
									return $url;
							},
							$urls
						)
					);
				}
				sort( $extracted_data );
			} elseif ( $data_type === 'count' ) {
				// Count menu items per menu.
				foreach ( $data as $slug => $urls ) {
					if ( count( $urls ) > 0 ) {
						$extracted_data[ $slug ] = array( 'count' => count( $urls ) );
					}
				}
			}
		}
		return $extracted_data;
	}

}
