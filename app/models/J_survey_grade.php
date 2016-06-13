<?php
namespace gateweb\mvc\app\models;

/**
 * 
 *
 */
class J_survey_grade extends \gateweb\mvc\core\Model
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