<?php
include 'models/product.php';

class ProductTest extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}

	protected function doTests() {
		$context=$this->getContext();
		$db=$context->getDB();
    
		// static method tests
		$OKname= str_repeat('x',64);
		$longName= str_repeat('x',65);
		$OKdesc=str_repeat('x',200);
		$longDesc=str_repeat('x',201);
        
        // Checking name
		$this->assertEqual(ProductModel::errorInProductName(null),'Product name must be specified','name validation');
		$this->assertEqual(ProductModel::errorInProductName(''),'Product name must be specified','name  validation');
		$this->assertEqual(ProductModel::errorInProductName($longName),'Product name must have no more than 64 characters','name validation');
		$this->assertEqual(ProductModel::errorInProductName($OKname),null,'name validation');
        
        // Checking description
        /*
        //No description implemented yet
        $this->assertEqual(ProductModel::errorInProductDescription(null),'Product description must be given','Description validation');
        $this->assertEqual(ProductModel::errorInProductDescription(''),'Product description must be given','Description validation');
        */
        
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
   		$this->assertEqual($product->getProductPrice(),'123.45',"Product  1 price");

   		$product->setProductName('Product 1');
   		$product->setProductPrice('453.21');
   		$this->assertEqual($product->getProductName(),'Product 1',"Product one name");
   		$this->assertEqual($product->getProductPrice(),'453.21',"Product  1 price");
        
        $product=new ProductModel($db);
        $this->assertEqual($product->getProductID(),null,"Default id should be null");
    	$product->setProductName('Product two');
   		$product->setProductPrice('987.65');
   		$this->assertEqual($product->getProductName(),'Product two',"Product two name");
   		$this->assertEqual($product->getProductPrice(),'987.65',"Product two price");
        
        //$this->save();
        //$this->assertEqual($product->getProductID(),2,"ID should now be 2");
        $this->assertFalse($product->getProductID(),2,"ID should now be 2");
		
        //this->assertEqual($product-getProductID()
        /*
        getProductName
        setProductName
        getProductPrice
        setProductPrice
        setProductPic
        hasChanges
        load
        save
        delete
        errorInProductName
        errorInProductPrice
        */
        
        /*    
        $prod = new ProductModel($db);
        this->assertEqual
    
    
        $prodID = 1;
        $this->assertEqual($prodID->getProductId
        getProductId() {
            return $this->productId;
        }values ('Product one','Description of product one',123.45,null,1);
*/
    }
}
?>
	