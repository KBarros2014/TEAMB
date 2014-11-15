<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   A sample entity model for a product category
   ============================================

   Design features:
   ** Static methods (ErrorIn...) can be used to check fields before creation
   ** anything invalid after creation throws an exception
   ** Creating with an ID loads from the database
   ** after setting data, a save will update the database
   ** delete will remove the category from the database and clear all data
   
   NB if cascade delete is not used, deletion will fail if there are dependent
      entities in the database
*/
include_once 'lib/abstractEntityModel.php';

class CategoryModel extends AbstractEntityModel {

	/* 		Section one - Private instance data
			===================================	
	*/

	private $name;
	private $description;

	// standard constructor
	public function __construct($db, $id=null) {
		parent::__construct($db,$id);
	}

	/* 		Section two - Getters and setters for private instance data
			===========================================================
			
			Setters validate data before setting and 
			notify changes via didChange()
	*/
	public function getName() {
		return $this->name;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setName($value) {
		$this->assertNoError($this->errorInName($value));
		$this->name=$value;
		$this->didChange();
	}		
	public function setDescription($value) {
		$this->assertNoError($this->errorInDescription($value));
		$this->description=$value;
		$this->didChange();
	}	

	/* 		Section three - Implementation of all must overrides
			====================================================
			
			These modify the generic logic in the abstract model to 
			define the specific logic that applies to categories
	*/

	// 	set default values for instance data 
	// (required fields should be set to null)		
	protected function init() {
		$this->name=null;
		$this->description=null;	
	}

	// load instance data from database
	protected function loadData($row) {
		$this->name=$row['name'];
		$this->description=$row['description'];
	}

	// return false if any required field is null
	protected function allRequiredFieldsArePresent() {
		return $this->name !== null && 
		       $this->description !==null;
	}
	
	// load instance data from database
	protected function getLoadSql($id) {
		return 	"select name, description from categories ".
				"where categoryID = $id";
	}
	// sql to insert instance data into database
	protected function getInsertionSql() {	
		$name=$this->safeSqlString($this->name);
		$description=$this->safeSqlString($this->description);	
		return "insert into categories(name, description) values ($name, $description)";	
	}	
	// sql to update database record from instance data
	protected function getUpdateSql() {
		$name=$this->safeSqlString($this->name);
		$description=$this->safeSqlString($this->description);	
		return "update categories set ".
			   "name=$name, ".
			   "description=$description ".
			   "where categoryID=".$this->getId();
	}
	// sql to delete this instance
	protected function getDeletionSql() {
	     return 'delete from categories where categoryID = '.$this->getId();
	}
		
	/* 		Section four - Validation functions for all fields
			==================================================
		
			These are all static (shared class level) functions
	*/

	public static function errorInName($value) {
		return self::errorInRequiredField('Category name',$value,40);	
	}
	public static function errorInDescription($value) {
		return self::errorInRequiredField('Description',$value,200);
	}	
	public static function isExistingId($db,$id) {
		return self::checkExistingId($db,$id, 
			'select 1 from categories where categoryID='.$id);
	}	
}