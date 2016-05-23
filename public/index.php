<?php

use gateweb\mvc\core\Router;

/**
 * Front controller
 *
 */
/**
 * Composer
 */
require_once(__DIR__.'/../../_class/initialize_dist.php');
	

// extra local classes
$dir = "../app/";
if(is_executable($dir)){
	$loader->addNamespace('gateweb\mvc\app', $dir);
}


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('gateweb\mvc\core\Error::errorHandler');
set_exception_handler('gateweb\mvc\core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'admin']);


		
$router->dispatch($router->get_path());



