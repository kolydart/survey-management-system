<?php

namespace gateweb\mvc\app\controllers;

use \gateweb\mvc\core\View;
use \gateweb\mvc\app\models\Post;
use \gateweb\mvc\app\Config;

/**
 * Posts controller
 *
 * PHP version 5.4
 */
class Posts extends \gateweb\mvc\core\Controller
{

	/**
	 * Show the index page
	 *
	 * @return void
	 */
	public function indexAction(){
		$post = new Post();
		View::renderTemplate('posts/index.html', ['posts' => $post->find_all() ]);
	}

	public function recordTwoAction(){
		View::renderTemplate('posts/record2.html',['post' => new Post(2)]);
	}

	/**
	 * Show the add new page
	 *
	 * @return void
	 */
	public function addNewAction(){
		echo 'Hello from the addNew action in the Posts controller!';
		View::renderTemplate('posts/form.html');
	}
	
	/**
	 * Show the edit page
	 *
	 * @return void
	 */
	public function editAction(){
		echo 'Hello from the edit action in the Posts controller!';
		echo '<p>Route parameters: <pre>' .
			 htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
	}
}
