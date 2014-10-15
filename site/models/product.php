<?php
//kB
class ProductModel extends AbstractModel {

	private $productId;
	private $productName=null;
	private $productDescription= null;
	private $productPrice=null;
<<<<<<< HEAD
	private $productPic=null;
=======
	private $prodPic=null;
	private $categoryID = null;
>>>>>>> origin/testing
	private $changed;
	private $CatID = null;
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
	
<<<<<<< HEAD
	
	public function __construct($db, $productId=null)  { //another field to be inserted here I will work on it after holiday
=======
	public function __construct($db, $productId=null)  {
>>>>>>> origin/testing
		parent::__construct($db);
		$this->productId=$productId;

		$this->changed = false;
		if ($productId !== null) {
			$this->load ($productId);
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
<<<<<<< HEAD
	
	public function getProductPrice() {
		return $this->productPrice;
	}
	
=======

	public function getProductPrice() {
		return $this->productPrice;
	}

>>>>>>> origin/testing
	public function setProductPrice($value) {
		$error=$this->errorInProductPrice($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productPrice=$value;
		$this->changed=true;
	}
<<<<<<< HEAD
	
=======

	public function getProductDescription() {
		return $this->productDescription;
	}
	
	public function setProductDescription($value) {
		$error=$this->errorInProductDescription($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->prodDescription=$value;
		$this->changed=true;
	}

>>>>>>> origin/testing
	public function getProductPic() {
		return $this->productPic;
	}
	
	public function setProductPic($value) {
		$error=$this->errorInProductPic($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->prodPict=$value;
		$this->changed=true;
	}

	public function getCategoryID() {
		return $this->categoryID;
	}
	
	public function setCategoryID($value) {
		$error = $this->errorInProductCatID($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->categoryID=$value;
		$this->changed=true;
	}
	
	public function hasChanges() {
		return $this->changed;
	}
<<<<<<< HEAD
	  
	private function load($productId) {
	if (!is_int($productId) && !ctype_digit($productId)) {
			throw new InvalidDataException("Invalid product ID ($productId)");
		}
		$sql="select productName, productDescription, productPrice, productPic from products ".
			 "where productID = $productId";
=======
	
	private function load($id) {
		if (!is_int($id) && !ctype_digit($id)) {
			throw new InvalidDataException("Invalid product ID ($id)");
		}
		$sql="select * from products ".
			 "where productID = $id";
>>>>>>> origin/testing
		$rows=$this->getDB()->query($sql);
		//echo $rows;
		
		if (count($rows)!==1) {
			throw new InvalidDataException("Product ID $productId not found");
		}
		
		$row=$rows[0];
		$this->productName=$row['productName'];
		$this->productDescription=$row['productDescription'];
<<<<<<< HEAD
		$this->producPrice= $row['productPrice'];  
		$this->productPic=$row['productPic'];//we do not have picutre 
		$this->productId=$productId;
		$this->changed=false;
	}
	
	public function save() {//save function to be perfected here // to be added more conditions on the contruct
=======
		$this->productPrice= $row['productPrice'];
		$this->categoryID= $row['catID'];
		$this->productId=$id;
		$this->changed=false;
	}
	
	public function save() {
>>>>>>> origin/testing
		if ($this->changed) {
		              if ($this->productName==null || $this->productPrice==null || $this->productDescription==null) {
				throw new InvalidDataException("Incomplete data Hi it s me testing again make sure you select cat");
		}
		echo $this->productName." ".$this->productPrice;// just for checking where it breaks kb
	    $db=$this->getDB();
		
		$productId=$this->productId;
<<<<<<< HEAD
		$productName=$this->productName;
		$productDescription=$this->productDescription;
		$productPic =$this->productPic;
		$productPrice= $this->productPrice;//lets see if breaks the code
		$catID = $this->catID;
			if ($productId === null) {
				$sql="insert into products(productName, productDescription, productPrice, productPic,CatID) values (".
						"'$productName', '$productDescription', '$productPrice','$productPic',1)" ;
			
		$affected=$db->execute($sql);
		 echo $affected;
			if ($affected !== 1) {
					throw new InvalidDataException("Insert product failed");	
				}
			$this->productId=$db->getInsertID();
		} else {
			$sql="update products ".
					"set productName='$productName', ".
			            "productDescription='$productDescription' ".
						 "productPrice ='$productPrice' ".
						 	 "productPic ='$productPic' ".
					"where productID= $productId";
					if ($db->execute($sql) !== 1) {
					throw new InvalidDataException("Update product failed");	
				}
=======
		$myProd=$this->productName;
		$myDesc=$this->productDescription;
		$myPic = $this->productPic;
		$myPrice =$this->productPrice;

		if ($id === null) {
				$sql="insert into products(productName, productDescription, productPrice) values (".
							"'$myProd', '$myDesc', '$myPrice')";

			$myPic = $this->prodPic;
			$myPrice =$this->productPrice;
			if ($this->id===null) {
				$sql="insert into products(productName, productDescription) values (".
							"'$myProd', '$myDesc')";
				$this->getDB()->execute($sql);
				if ($affected !== 1) {
						throw new InvalidDataException("Insert product failed");	}
				$this->id=getDB()->insertID();
>>>>>>> origin/testing
			
			} else {
				$sql="update products ".
						"set productName='$myProd', ".
				            "productDescription='$myDesc' ".
							 "productPrice ='$myPrice' ".
						"where productID= $id";
					//"where productId= $myProd";
					$this->getDB()->execute($sql);
						if ($db->execute($sql) !== 1) {
						throw new InvalidDataException("Update category failed");
					}
				
			}
			$this->hasChanges=false;
		}
<<<<<<< HEAD
		$this->hasChanges=false;
		//$this->changed =false;
		
	}
	}
		
		
	
	public function delete () {
	    $sql='delete from products where productID = '.$this->productId;;
		$rows=$this->getDB()->execute($sql);
		$this->id=$null;
		$this->changed=false;
=======
	}
>>>>>>> origin/testing
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
		if ($value== null) {
			return 'Price must be specified';
		}
		if ($value <0) {
		return "error";
		}
		return null;
		}
	
	public static function errorInProductPic($value) {
		if ($value==null ) {
			return 'Picture  must be supplied';
		}
		return null;
		}
	
	public function setDescription($value) { //needed  function
		$error=$this->errorInProductDescription($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->productDescription=$value;
		$this->changed=true;
	}
	
	public function getProductDescription() {
		return $this->productDescription;
	}
	
	public static function errorInProductCat($value) {
		if ($value==null) {
			return 'Category  must be supplied';
		}
		return null;
		}
	
	public static function errorInProductDescription($value) {//irrelevant  kb
		if ($value==null || strlen($value)==0) {
<<<<<<< HEAD
			return 'desc name must be specified';
		}
	
      if ($value <0){
	  return "not negative number";
	  }
=======
			return 'Product price must be specified';
		}
>>>>>>> origin/testing
		
		return null;
	}

	public static function errorInProductCatID($value) {
		if ($value==null || strlen($value)==0) {
			return 'Product category must be specified';
		}
		
		return null;
	}

	public function delete () {
		if ($this->productId===null) {
			throw new LogicException('Cannot delete null id');
		}
	    $sql='delete from products where productID = '.$this->productId;
		if ($this->getDB()->execute($sql) !== 1) {
			throw new LogicException('Product delete failed for id '.$this->productId);
		}	
	}
}
?>
