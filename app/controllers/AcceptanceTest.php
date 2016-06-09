<?php

namespace gateweb\mvc\app\controllers;
use \gateweb\mvc\core\View;

/**
 * Home controller
 *
 */
class AcceptanceTest extends \gateweb\mvc\core\Controller
{

	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction(){
		View::renderTemplate('test/index.html');
	}

		
	}
}
