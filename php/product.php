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

class ProductModel extends AbstractModel {

	private $id;
	private $name;
	private $description;
	private $changed;
	private $producPic;
	private $productPrice;
	
	public function __construct($db, $id=null) {
		parent::__construct($db);///I am working on it;
		$this->init();
		if ($id !== null) {
			$this->load($id);
		}
	}
	private function init() {
		$this->id=null;
		$this->name=null;
		$this->description=null;
		$this->changed=false;	
		$this->productPrice = null;
		
	}
	public function load($id) {
		if (!is_int($id) && !ctype_digit($id)) {
			throw new InvalidDataException("Invalid product ID ($id)");
		}
		$sql="select name, description from products ".
			 "where productID = $id";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("Category (ID $id) not found");
		}
		$row=$rows[0];
		$this->name=$row['name'];
		$this->description=$row['description'];
		$this->id=$id;
		$this->changed=false;
	}
	public function getID() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getDescription() {
		return $this->description;
	}
	public function hasChanges() {
		return $this->changed;
	}
	public function setName($value) {
		$error=$this->errorInName($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->name=$value;
		$this->changed=true;
	}		
	public function setDescription($value) {
		$error=$this->errorInDescription($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->description=$value;
		$this->changed=true;
	}
	public function save() {
		if ($this->changed) {
			if ($this->name==null || $this->description==null) {
				throw new InvalidDataException("Attempt to save invalid category data");	
			}
			$db=$this->getDB();
			$id=$this->id;
			$name=$db->escape($this->name);
			$description=$db->escape($this->description);	
			if ($id === null) {
				$sql="insert into categories(name, description) values (".
							"'$name', '$description')";
				$affected=$db->execute($sql);
				if ($affected !== 1) {
					throw new InvalidDataException("Insert category failed");	
				}
				$this->id=$db->getInsertID();	
			} else {
				$sql="update categories ".
						"set name='$name', ".
							"description='$description' ".
						"where categoryID= $id";
				if ($db->execute($sql) !== 1) {
					throw new InvalidDataException("Update category failed");	
				}
			}
			$this->changed=false;
		}
	}
	public function delete () {
		if ($this->id===null) {
			throw new LogicException('Cannot delete null id');
		}
	    $sql='delete from categories where categoryID = '.$this->id;;
		if ($this->getDB()->execute($sql) !== 1) {
			throw new LogicException('Category delete failed for id '.$this->id);
		}
		$this->init();	
	}
	public static function errorInName($value) {
		if ($value==null || strlen($value)==0) {
			return 'Category name must be specified';
		}
		if (strlen($value)>40) {
			return 'Category name must have no more than 40 characters';
		}
		return null;
	}
	public static function errorInDescription($value) {
		if ($value==null || strlen($value)==0) {
			return 'Description must be specified';
		}
		if (strlen($value)>200) {
			return 'Description must have no more than 200 characters';
		}
		return null;
	}
	public static function isExistingId($db,$id) {
		if ($id==null){
			return false;
		}
		if (!is_int($id) && !(ctype_digit($id))) {
			return false;
		}
		$sql = "select 1 from categories where categoryID=$id";
		$rows=$db->query($sql);
		return count($rows)==1;
	}	
}