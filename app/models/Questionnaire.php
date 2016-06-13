<?php

namespace gateweb\mvc\app\models;

/**
 * Post model
 *
 */
class Questionnaire extends \gateweb\mvc\core\Model
{

	public $table_name;
	public $id;
	///< enter more variables
	
	function __construct($id=null) {
		parent::__construct($id);
	}

	/**
	 * Get all
	 *
	 * @return array
	 */
	public function getAll(){
		return $this->find_all();
	}

}

