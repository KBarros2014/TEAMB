<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   A sample entity model for a product
   ===================================

   Design features:
   ** Static methods (ErrorIn...) can be used to check fields before creation
   ** anything invalid after creation throws an exception
   ** Creating with an ID loads from the database
   ** after setting data, a save will update the database
   ** delete will remove the category from the database and clear all data

*/
include_once 'lib/abstractEntityModel.php';
include_once 'models/category.php';

class ProductModel extends AbstractEntityModel {

	/* 		Section one - Private instance data
			===================================	
	*/

	private $name;
	private $description;
	private $price;
	private $categoryID;
	
	// lazy properties
	private $category;
	private $image;
	private $thumbnail;
	
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
	public function getPrice() {
		return $this->price;
	}
	public function getCategoryID() {
		return $this->categoryID;
	}
	public function getCategory() {
		if ($this->category==null) {
			$this->category = new CategoryModel ($this->getDB(), $this->categoryID);
		}
		return $this->category;
	}
	public function getImage() {
		if ($this->image==null) {
			$filename = 'p'.$this->getID();
			if (file_exists('images/products/'.$filename.'.jpg')) {
				$this->image=$filename.'.jpg';
			} else {
				$this->image=$filename.'.png';
			}
		}
		return $this->image;
	}	
	public function getThumbnail() {
		if ($this->thumbnail==null) {
			$filename = 't'.$this->getID();
			if (file_exists('images/products/'.$filename.'.jpg')) {
				$this->thumbnail=$filename.'.jpg';
			} else {
				$this->thumbnail=$filename.'.png';
			}
		}
		return $this->thumbnail;
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
	public function setPrice($value) {
		$this->assertNoError($this->errorInPrice($value));
		$this->price=(float)$value;
		$this->didChange();
	}	
	public function setCategoryID($value) {
		$this->assertNoError($this->errorInCategoryID($this->getDB(), $value));
		$this->categoryID=(int)$value;
		$this->didChange();
		$this->category=null;
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
		$this->price=null;
		$this->categoryID=null;
		$category=null;
		$image=null;
		$thumbnail=null;
	}

	// load instance data from database
	protected function loadData($row) {
		$this->name=$row['name'];
		$this->description=$row['description'];
		$this->price=$row['price'];
		$this->categoryID=$row['categoryID'];	
	}

	// return false if any required field is null
	protected function allRequiredFieldsArePresent() {
		return $this->name !== null && 
		       $this->description !==null &&
			   $this->price!==null &&
			   $this->categoryID!==null;
	}
	
	// load instance data from database
	protected function getLoadSql($id) {
		return 	"select name, description, price, categoryID from products ".
				"where productID = $id";
	}
	// sql to insert instance data into database
	protected function getInsertionSql() {	
		$name=$this->safeSqlString($this->name);
		$description=$this->safeSqlString($this->description);
		$price=$this->safeSqlNumber($this->price);
		$categoryID=$this->safeSqlNumber($this->categoryID);
		
		return "insert into products(name, description, price, categoryID) ".
		       "values ($name, $description, $price, $categoryID)";	
	}	
	// sql to update database record from instance data
	protected function getUpdateSql() {
		$name=$this->safeSqlString($this->name);
		$description=$this->safeSqlString($this->description);	
		$price=$this->safeSqlNumber($this->price);
		$categoryID=$this->safeSqlNumber($this->categoryID);
		
		return "update products set ".
			   "name=$name, ".
			   "description=$description, ".
			   "price=$price, ".
			   "categoryID=$categoryID ".
			   "where productID=".$this->getId();
	}
	// sql to delete this instance
	protected function getDeletionSql() {
	     return 'delete from products where productID = '.$this->getId();
	}
		
	/* 		Section four - Validation functions for all fields
			==================================================
		
			These are all static (shared class level) functions
	*/
	public static function errorInName($value) {
		return self::errorInRequiredField('Product name',$value,40);	
	}
	public static function errorInDescription($value) {
		return self::errorInRequiredField('Description',$value,300);
	}	
	public static function errorInPrice($value) {
		return self::errorInRequiredNumericField('Price',$value,2,0.01);
	}		
	public static function errorInCategoryID($db,$value) {
		if (CategoryModel::isExistingID($db,$value)) {
			return null;
		}
		return "Invalid category ID ($value)";
	}	
	public static function isExistingId($db,$id) {
		return self::checkExistingId($db,$id, 
			'select 1 from products where productID='.$id);
	}	
}