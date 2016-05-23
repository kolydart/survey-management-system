<?php

namespace gateweb\mvc\app\controllers\admin;

/**
 * User admin controller
 *
 * PHP version 5.4
 */
class Users extends \gateweb\mvc\core\Controller
{

	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before(){
		// Make sure an admin user is logged in for example
		// return false;
	}

	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction(){
		echo 'User admin index';
	}
}
