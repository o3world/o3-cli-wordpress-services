<?php

namespace O3CliServices;

interface Url_List_Plan_Interface {
	/**
	 * Checks if the plan is valid
	 *
	 * @return boolean
	 */
	public function is_plan_valid();

	/**
	 * Get properties, keyed by property name
	 *
	 * @return array
	 */
	public function get_properties();

}
