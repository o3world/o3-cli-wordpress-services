<?php

namespace O3CliServices\Model;

/**
 * A URL list plant with queried post types, max post limit and menu names for
 * which paths will be generated
 */
class O3_Url_List_Plan extends Abstract_Url_List_Plan {

	const DEFAULT_LIMIT = 10;

	/**
	 * Content types
	 *
	 * @var array
	 */
	protected $post_types;

	/**
	 * Categories
	 *
	 * @var array
	 */
	protected $categories;

	/**
	 * Menus
	 *
	 * @var array
	 */
	protected $menus;

	/**
	 * Number of nodes to include in test
	 *
	 * @var integer
	 */
	protected $limit;

	/**
	 * Constructs the O3_Url_List_Plan object
	 *
	 * @param array $parameters
	 */
	public function __construct( array $parameters ) {
		$this->set_properties( $parameters );
	}

	/**
	 * Set properties
	 *
	 * @param array $parameters
	 */
	public function set_properties( $parameters ) {
		$this->limit = self::DEFAULT_LIMIT;
		foreach ( $parameters as $key => $value ) {
			switch ( $key ) {
				case 'post_types':
					$this->post_types = $this->get_valid_post_types( $value );
					break;
				case 'categories':
					$this->categories = $this->get_valid_categories( $value );
					break;
				case 'menus':
					$this->menus = $this->get_valid_menus( $value );
					break;
				case 'limit':
					// User-entered '0' should evaluate to 'unlimited'
					$this->limit = $value == 0 ? -1 : $value;
					break;
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function get_properties() {
		return array(
			'post_types' => $this->post_types,
			'categories' => $this->categories,
			'limit'      => $this->limit,
			'menus'      => $this->menus,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function is_plan_valid() {
		if ( ! empty( $this->post_types ) || ! empty( $this->menus ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get valid post types
	 *
	 * @param string $string
	 * @return array
	 */
	protected function get_valid_post_types( $string ) {
		$post_types     = array();
		$existing_types = get_post_types();
		foreach ( explode( ',', $string ) as $raw_post_type ) {
			if ( in_array( $raw_post_type, $existing_types ) ) {
				$post_types[] = $raw_post_type;
			}
		}
		return $post_types;
	}

	/**
	 * Get valid category slugs
	 *
	 * @param string $string
	 * @return array
	 */
	protected function get_valid_categories( $string ) {
		$categories          = array();
		$existing_categories = wp_list_pluck( get_categories(), 'slug' );
		foreach ( explode( ',', $string ) as $raw_category_slug ) {
			if ( in_array( $raw_category_slug, $existing_categories ) ) {
				$categories[] = $raw_category_slug;
			}
		}
		return $categories;
	}

	/**
	 * Get valid menu slugs
	 *
	 * @param string $string
	 * @return array
	 */
	protected function get_valid_menus( $string ) {
		$menus          = array();
		$existing_menus = wp_list_pluck( get_terms( 'nav_menu' ), 'slug' );
		foreach ( explode( ',', $string ) as $raw_menu_slug ) {
			if ( in_array( $raw_menu_slug, $existing_menus ) ) {
				$menus[] = $raw_menu_slug;
			}
		}
		return $menus;
	}
}
