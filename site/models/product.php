<?php
//kB
class ProductModel extends AbstractModel {

	private $productId;
	private $productName=null;
	private $productDescription = null;
	private $productPrice=null;
	private $prodPic=null;
	private $categoryID = null;
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
	
	public function __construct($db, $productId=null)  {
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

	public function getProductPrice() {
		return $this->productPrice;
	}

	public function setProductPrice($value) {
		$error=$this->errorInProductPrice($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->prodPrice=$value;
		$this->changed=true;
	}

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

	public function getProductPic() {
		return $this->prodPic;
	}
	
	public function setProductPic($value) {
		$error=$this->errorInProductPic($value);
		if ($error!==null ){
			throw new InvalidDataException($error);
		}
		$this->prodPic=$value;
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
	
	private function load($id) {
		if (!is_int($id) && !ctype_digit($id)) {
			throw new InvalidDataException("Invalid product ID ($id)");
		}
		$sql="select * from products ".
			 "where productID = $id";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("Product ID $id not found");
		}
		$row=$rows[0];
		$this->productName=$row['productName'];
		$this->productDescription=$row['productDescription'];
		$this->productPrice= $row['productPrice'];
		$this->categoryID= $row['catID'];
		$this->productId=$id;
		$this->changed=false;
	}
	
	public function save() {
		if ($this->changed) {
			if ($this->productName==null || $this->productPrice==null) {
				throw new InvalidDataException('Incomplete data');
		}
	
		$db=$this->getDB();
		$productId=$this->productId;
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
	}
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

	public static function errorInProductDescription($value) {
		if ($value==null || strlen($value)==0) {
			return 'Product description must be specified';
		}
		
		return null;
	}

	public static function errorInProductPrice($value) {
		if ($value==null || strlen($value)==0) {
			return 'Product price must be specified';
		}
		
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
