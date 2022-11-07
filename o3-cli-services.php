<?php
/**
 * Plugin Name:     O3 CLI Services
 * Plugin URI:      https://github.com/o3world/o3-cli-wordpress-services/
 * Description:     Exposes APIs that assist the use of the O3 CLI with WordPress projects
 * Author:          Matt Schaff
 * Author URI:      https://www.o3world.com/about/team/matt-schaff/
 * Version:         1.0.2
 */

use O3CliServices\Controller\Url_List_Controller;
use O3CliServices\Controller\Url_Sources_Controller;

require_once dirname( __FILE__ ) . '/class-o3-cli-autoloader.php';

/**
 * Add action hooks
 */
add_action( 'init', 'o3_cli_autoload' );
add_action(
	'rest_api_init',
	function () {
		o3_cli_register_controllers();
	}
);

/**
 * Callback for 'init' action
 */
function o3_cli_autoload() {
	$loader = new \O3_Cli_Autoloader();
	$loader->register();
	$loader->add_namespace( 'O3CliServices', dirname( __FILE__ ) . '/src' );
}

/**
 * Callback for 'rest_api_init' action
 */
function o3_cli_register_controllers() {
	$list_controller = new Url_List_Controller();
	$list_controller->register_route();
	$sources_controler = new Url_Sources_Controller();
	$sources_controler->register_route();
}

