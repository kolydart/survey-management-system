<?php

use gateweb\mvc\app\Config;
use gateweb\mvc\core\Router;
session_start();
/**
 * Front controller
 *
 */
require_once(__DIR__.'/../../_class/initialize_dist.php');



// extra local classes
$dir = __DIR__."/../app/";
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
$router->add(Config::URL_BASE."/", ["controller" => "Home", "action" => "index"]);
$router->add(Config::URL_BASE."/{controller}/{action}");
$router->add(Config::URL_BASE."/{controller}/{id:\d+}/{action}");
$router->add(Config::URL_BASE."/admin/{controller}/{action}", ["namespace" => "admin"]);

$router->dispatch($router->get_path());
