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
	
	
	public function __construct($db, $productId=null)  {
		parent::__construct($db);
		$this->productId=$productId;
		//$this->setProductName= ($productName);
		//$this->setProductPrice($productPrice);
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
	
	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($id) {
	if (!is_int($id) && !ctype_digit($id)) {
			throw new InvalidDataException("Invalid product ID ($id)");
		}
		$sql="select productName, productDescription, productPrice from products ".
			 "where productID = $id";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("Product ID $id not found");
		}
		$row=$rows[0];
		$this->productName=$row['productName'];
		$this->productDescription=$row['productDescription'];
		$this->producPrice= $row['productPrice'];
<<<<<<< HEAD
		$this->productPic=$row['productPic'];//we do not have picutre 
=======
		$this->productPic=$row['prodPic'];
>>>>>>> origin/testing
		$this->productId=$id;
		$this->changed=false;
	}
	
	public function save() {
		//$id=$this->productId;
		if ($this->changed) {
			if ($this->productName==null || $this->productPrice==null) {
				throw new InvalidDataException('Incomplete data');
		}
	
		$db=$this->getDB();
		$productId=$this->productId;
		$myProd=$this->productName;
		$myDesc=$this->productDescription;
<<<<<<< HEAD
		$myPic = $this->productPic;
		$myPrice =$this->productPrice;
	//	if ($this->id===null) {
	if ($id === null) {
			$sql="insert into products(productName, productDescription, productPrice) values (".
						"'$myProd', '$myDesc', '$myPrice')";
=======
		$myPic = $this->prodPic;
		$myPrice =$this->productPrice;
		if ($this->id===null) {
			$sql="insert into products(productName, productDescription) values (".
						"'$myProd', '$myDesc')";
>>>>>>> origin/testing
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
	public function delete () {
	    $sql="delete from products where productId = $id";
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
		
		
		return null;
	}
}
?>
