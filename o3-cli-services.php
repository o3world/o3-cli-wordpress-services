<?php
/**
 * Plugin Name:     O3 CLI Services
 * Plugin URI:      https://github.com/o3world/o3-cli-wordpress-services/
 * Description:     Exposes APIs that assist the use of the O3 CLI with Wordpress projects
 * Author:          Matt Schaff
 * Author URI:      https://www.o3world.com/about/team/matt-schaff/
 * Version:         1.0
 */

use O3CliServices\Controller\Url_List_Controller;

/**
 * Add action hooks
 */
add_action( 'init', 'o3_cli_autoload' );
add_action( 'rest_api_init', function () {
  o3_cli_register_url_list();
} );

/**
 * Callback for 'init' action
 */
function o3_cli_autoload() {
  require_once dirname(__FILE__) . '/auto-load.php';
  $loader = new \O3_Cli_Autoloader;
  $loader->register();
  $loader->add_namespace('O3CliServices', dirname(__FILE__) . '/src');
}

/**
 * Callback for 'rest_api_init' action
 */
function o3_cli_register_url_list() {
  $controller = new Url_List_Controller;
  $controller->register_route();
}

