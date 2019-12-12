<?php

namespace O3CliServices\Controller;

use O3CliServices\Model\O3_Url_List_Plan;
use O3CliServices\Service\Url_List_Manager;

/**
 * Controller for the /o3-cli-api/urls endpoint
 */
class Url_List_Controller extends \WP_REST_Controller {

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
			'/urls',
			array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_urls' ),
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
	public function get_urls( $request ) {
		// Return URL list based on valid list plan.
		if ( $list_plan = $this->get_url_list_plan( $request ) ) {
			$response_array = $this->url_list_manager->build_paths_array( $list_plan );
			$http_code      = 200;
		} else {
			$response_array = array(
				'success' => false,
				'message' => __( 'The generic test generator requires either valid query parameters for either \'post_types\' or \'menus\'.' ),
			);
			$http_code      = 400;
		}
		return new \WP_REST_Response( $response_array, $http_code );
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
