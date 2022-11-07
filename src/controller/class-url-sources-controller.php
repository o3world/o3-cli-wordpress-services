<?php

namespace O3CliServices\Controller;

use O3CliServices\Model\O3_Url_List_Plan;
use O3CliServices\Service\Url_List_Manager;

/**
 * Controller for the /o3-cli-api/url-sources endpoint
 */
class Url_Sources_Controller extends \WP_REST_Controller {

	/**
	 * URL List Manager
	 *
	 * @var Url_List_Manager
	 */
	protected $url_list_manager;

	public function __construct() {
		$this->url_list_manager = new Url_List_Manager();
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_route() {
		$namespace = 'o3-cli-api/';
		register_rest_route(
			$namespace,
			'/url-sources',
			array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_sources' ),
				),
			)
		);
	}

	/**
	 * Get paths of posts by post type
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_sources( $request ) {
		$response_array = array(
			'post_types' => $this->url_list_manager->count_posts_by_type(),
			'menus'      => $this->url_list_manager->count_menu_items(),
		);
		return new \WP_REST_Response( $response_array );
	}

	/**
	 * Parses parameters in the Request query string
	 *
	 * @param \WP_REST_Request $request
	 * @return O3_Url_List_Plan|false
	 */
	protected function get_url_list_plan( $request ) {
		if ( $parameters_array = $request->get_params() ) {
			$list_plan = new O3_Url_List_Plan( $parameters_array );
			if ( $list_plan->is_plan_valid() ) {
				return $list_plan;
			}
		}
		return false;
	}

}
