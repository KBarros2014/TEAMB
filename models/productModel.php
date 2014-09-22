<?php
//kB
class ProductModel extends AbstractModel {

	private $productId;
	private $productName=null;
	private $productPrice=null;
	private $prodPic=null;
	private $changed;

	/*
	
	To create a new product ...
		$p = new Product ($db);
		... call setters
		... $p->save();
		
	To update a product ...
		$p = new Product ($id);
		... call setters
		... $p->save();
		
	*/
	
	
	public function __construct($db, $productId=null) //, $productName=null, $productPrice=null, $productPic=null) {
		parent::__construct($db);
		$this->productId=$productId;
		//$this->setProductName= ($productName);
		//$this->setProductPrice($productPrice);
		$this->changed = false;
		if ($productId !== null) {
			load (productID);
		}
		
	}
	
	public function getProductId() {
		return $this->productId;
	}
	public function getProductName() {
		return $this->productName;
	}
	
	public function setProductName($value) {
		$error=$this->errorInProductName($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productName=$value;
		$this->changed=true;
	}
	public function getProductPrice() {
		return $this->productPrice;
	}
	public function setProductPrice($value) {
		$error=$this->errorInProductPrice($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->producPrice=$value;
		$this->changed=true;
	}
	public function getProductPic() {
		return $this->productPic;
	}
	
	public function setProductPic($value) {
		$error=$this->errorInProductPic($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->producPic=$value;
		$this->changed=true;
	}
	
	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($id) {
		$sql="select givenName, familyName from people ".
			 "where personID = $id";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==0) {
			throw new InvalidDataException("Person ID $id not found");
		}
		$row=$rows[0];
		$this->givenName=$row['givenName'];
		$this->familyName=$row['familyName'];
		$this->id=$id;
		$this->changed=false;
	}
	
	public function save() {
		$id=$this->id;
		if ($this->productName==null || $this->productPrice==null) {
			throw new InvalidDataException('Incomplete data');
		}
		
		// to be changed
		$given=$this->givenName;
		$family=$this->familyName;
		
		if ($this->id===null) {
			$sql="insert into people(givenName, fanilyName) values (".
						"'$given', '$family')";
			$this->getDB()->execute($sql);
			$this->id=getDB()->insertID();	
		} else {
			$sql="update people ".
					"set givenName='$given', ".
			            "familyName='$family' ".
					"where personID= $personID";
			$this->getDB()->execute($sql);
		}
		$this->hasChanges=false;
	}
	
	public function delete () {
	    $sql="delete from people where personID = $id";
		$rows=$this->getDB()->execute($sql);
		$this->id=$null;
		$this->changed=false;
	}
	
	public static function errorInProductName($value) {
		if ($value==null || strlen($value)==0) {
			return 'Product name must be specified';
		}
		if (strlen($value)>30) {
			return 'errorInProductName name must have no more than 30 characters';
		}
		return null;
	}
	
	public static function errorInProductPrice($value) {
		if ($value==null || strlen($value)==0) {
			return 'Price name must be specified';
		}
		
		// more checks
		// numeric
		// not negative
		
		// if (strlen($value)>40) {
			// return 'Family name must have no more than 40 characters';
		// }
		return null;
	}
}
?>
