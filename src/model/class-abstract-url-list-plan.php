<?php

namespace O3CliServices\Model;

use O3CliServices\Url_List_Plan_Interface;

/**
 * An abstract URL list plan
 */
class Abstract_Url_List_Plan implements Url_List_Plan_Interface {

	/**
	 * Constructs the Abstract_Url_List_Plan object
	 *
	 * @param array $parameters
	 */
	public function __construct( array $parameters ) {
	}

	/**
	 * @inheritDoc
	 */
	public function get_properties() {
		return array();
	}

	/**
	 * @inheritDoc
	 */
	public function is_plan_valid() {
		return true;
	}

}
