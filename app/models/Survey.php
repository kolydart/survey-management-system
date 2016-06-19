<?php
namespace gateweb\mvc\app\models;

/**
 * Survey model
 *
 */
class Survey extends \gateweb\mvc\core\Model
{

	public $table_name;
	public $id;
	///< enter more variables
	
	function __construct($id=null) {
		parent::__construct($id);
	}

	/**
	 * Get all posts
	 * @return array
	 */
	public function getAll(){
		return $this->find_all();
	}

}