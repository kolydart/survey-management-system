<?php

namespace gateweb\mvc\app\controllers;

use \gateweb\mvc\core\View;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Home extends \gateweb\mvc\core\Controller
{

	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before(){
		//echo "(before) ";
		//return false;
	}

	/**
	 * After filter
	 *
	 * @return void
	 */
	protected function after(){
		//echo " (after)";
	}

	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction(){
		View::renderTemplate('home/index.html', [
			'name'    => 'George',
			'colours' => ['red', 'green', 'blue']
		]);
	}

	public function customViewAction(){

		View::render('home/customView.php', [
			'name'    => 'John',
			'colours' => ['red', 'green', 'blue']
		]);
		
	}
}
