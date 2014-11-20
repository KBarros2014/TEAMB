<?php
    /*
    Unit tests for products
    Author: Stephen Reynolds
    
    To do: delete()
    */
include 'models/product.php';

class ProductTest extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}

	protected function doTests() {
		$context=$this->getContext();
		$db=$context->getDB();
    
		// static method tests
		$OKname= str_repeat('x',30);
		$longName= str_repeat('x',31);
		$OKdesc=str_repeat('x',200);
		$longDesc=str_repeat('x',201);
      
    // Checking null states and lengths
    // Checking Name
		$this->assertEqual(ProductModel::errorInProductName(null),'Product name must be specified','name validation');
		$this->assertEqual(ProductModel::errorInProductName(''),'Product name must be specified','name  validation');
		$this->assertEqual(ProductModel::errorInProductName($OKname),null,'name validation');
		$this->assertEqual(ProductModel::errorInProductName($longName),'errorInProductName name must have no more than 30 characters','name validation');
    // Checking Description
        $this->assertEqual(ProductModel::errorInProductDescription(null),'Product description must be given','Description validation');
        $this->assertEqual(ProductModel::errorInProductDescription(''),'Product description must be given','Description validation');
    
  
    $product=new ProductModel($db);
		$this->assertEqual($product->getProductID(),null,"Default id should be null");
		$this->assertEqual($product->getProductName(),null,"Default name should be null");
		// No description implemented yet
    //$this->assertEqual($product->getDescription(),null,"Default description should be null");
		
    // insert into products (productName, productDescription, productPrice, productPic, catID) 
    // values ('Product one','Description of product one',123.45,null,1);

    $product=new ProductModel($db,1);
    $this->assertEqual($product->getProductID(),'1',"Product 1 id");
    $this->assertEqual($product->getProductName(),'Product one',"Product one name");
   	//$this->assertEqual($product->getProductPrice(),123.45,"Product 1 price should be 123.45");

 	$product->setProductName('Product 1');
 	$product->setProductPrice(453.21);
    
 	$this->assertEqual($product->getProductName(),'Product 1',"Product one name");
 	$this->assertEqual($product->getProductPrice(),'453.21',"Product 1 priceshould be 453.21");

          
    $product=new ProductModel($db);
    $this->assertEqual($product->getProductID(),null,"Default id should be null");
    $product->setProductName('Product two');
    $product->setProductPrice(987.65);
    $product->setProductDescription("Item 2s description");
    $product->setProductPic("No picture supplied");
    $product->setCategoryId(1);
    
    $this->assertEqual($product->getProductName(),'Product two',"Product two name");
    $this->assertEqual($product->getProductPrice(),'987.65',"Product two price");
   
    // Save test
    //$this->assertFalse($product->save());
    $product->save();
    $this->assertEqual($product->getProductID(),2,"ID should now be 2");
    $this->assertEqual($product->getProductName(),'Product two',"Product two name");
    $this->assertEqual($product->getProductPrice(),'987.65',"Product two price");
    $this->assertEqual($product->getProductDescription(),'Item 2s description',"Product description");
    $this->assertEqual($product->getCategoryId(),'1',"Product category");

    /*
    //Load test
    //Can't perform because its a private function
    $product = new ProductModel($db, 2);
    $product->load(2);
    $this->assertEqual($product->getProductID(),'2',"Product 2 id");
    $this->assertEqual($product->getProductName(),'Product one',"Product one name");
    */
    
    $product = new ProductModel($db, 2);
    $product->delete();
    //$this->assertFalse(getProductID(2));
    

  }
}
?>