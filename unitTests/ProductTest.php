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
		$OKname= str_repeat('x',40);
		$longName= str_repeat('x',41);
		$OKdesc=str_repeat('x',300);
		$longDesc=str_repeat('x',301);
	
		$this->assertEqual(ProductModel::errorInName(null),'Product name must be specified','name validation');
		$this->assertEqual(ProductModel::errorInName(''),'Product name must be specified','name  validation');
		$this->assertEqual(ProductModel::errorInName($longName),'Product name must have no more than 40 characters','name validation');
		$this->assertEqual(ProductModel::errorInName($OKname),null,'name validation');
		
		$this->assertEqual(ProductModel::errorInDescription(null),'Description must be specified','description validation');
		$this->assertEqual(ProductModel::errorInDescription(''),'Description must be specified','description  validation');
		$this->assertEqual(ProductModel::errorInDescription($longDesc),'Description must have no more than 300 characters','description validation');
		$this->assertEqual(ProductModel::errorInDescription($OKname),null,'description validation');

		$this->assertEqual(ProductModel::errorInPrice(null),'Price must be specified','price validation');
		$this->assertEqual(ProductModel::errorInPrice(''),'Price must be specified','price  validation');
		$this->assertEqual(ProductModel::errorInPrice('xx'),'Price must be a number of at least 0.01, with no more than 2 decimal places.','price  validation');
		$this->assertEqual(ProductModel::errorInPrice('123.456'),'Price must be a number of at least 0.01, with no more than 2 decimal places.','price  validation decimals');
		$this->assertEqual(ProductModel::errorInPrice('0.00'),'Price must be a number of at least 0.01, with no more than 2 decimal places.','price  validation low');
		$this->assertEqual(ProductModel::errorInPrice('123.45'),null,'price  validation');

		$this->assertEqual(ProductModel::errorInCategoryID($db,null),'Invalid category ID ()','description validation');
		$this->assertEqual(ProductModel::errorInCategoryID($db,''),'Invalid category ID ()','description  validation');
		$this->assertEqual(ProductModel::errorInCategoryID($db,2),'Invalid category ID (2)','description validation');
		$this->assertEqual(ProductModel::errorInCategoryID($db,1),null,'Category ID validation');
		
		$this->assertFalse(ProductModel::isExistingId($db,null),'ID existence null');
		$this->assertFalse(ProductModel::isExistingId($db,'xx'),'ID existence alpha');
		$this->assertFalse(ProductModel::isExistingId($db,2 ),'ID existence false');
		$this->assert(ProductModel::isExistingId($db,1 ),'ID existence true');

		$product=new ProductModel($db);
		$this->assertEqual($product->getID(),null,"Default id should be null");
		$this->assertEqual($product->getName(),null,"Default name should be null");
		$this->assertEqual($product->getDescription(),null,"Default description should be null");
		$this->assertEqual($product->getPrice(),null,"Default price should be null");
		$this->assertEqual($product->getCategoryID(),null,"Default category ID should be null");
		$this->assertFalse($product->hasChanges(),'Default Should be unchanged');

		$product=new ProductModel($db,1);
		$this->assertEqual($product->getID(),'1',"Product 1 id");
		$this->assertEqual($product->getName(),'Product one',"Product 1 name");
		$this->assertEqual($product->getDescription(),'Description of product one',"Product 1 description");
		$this->assertEqual($product->getPrice(),'123.45',"Product 1 description");
		$this->assertEqual($product->getCategoryID(),1,"Product 1 description");
		$this->assertFalse($product->hasChanges(),'product 1 should be unchanged');
		$product->setName('Product one (edited)');
		$this->assertEqual($product->getName(),'Product one (edited)',"Product 1 name");
		$this->assert($product->hasChanges(),'Product 1 should be changed');
		$product->setDescription('Description of Product one (edited)');
		$this->assertEqual($product->getDescription(),'Description of Product one (edited)',"Product 1 description");
		$this->assert($product->hasChanges(),'Product 1 should be changed');
		$product->setPrice(234.56);
		$this->assertEqual($product->getPrice(),'234.56',"Product 1 price");
		$this->assert($product->hasChanges(),'Product 1 should be changed');
		
		$cat=new CategoryModel($db);
		$cat->setName('Testing cat');
		$cat->setDescription('Testing cat');
		$cat->save();
		$catID=$cat->getID();
		
		$product->setCategoryID($catID);
		$this->assertEqual($product->getcategoryID(),$catID,"Product 1 categoryID");
		$this->assert($product->hasChanges(),'Product 1 should be changed');
		
		$product->save();
		$this->assertFalse($product->hasChanges(),'Product 1 should be unchanged');
		// verify properties still have their values after save
		$this->assertEqual($product->getID(),1,"Product 1 id");
		$this->assertEqual($product->getName(),'Product one (edited)','Product 1 name');
		$this->assertEqual($product->getDescription(),'Description of Product one (edited)',"Product 1 price");
		$this->assertEqual($product->getPrice(),234.56,"Product 1 description");
		$this->assertEqual($product->getCategoryID(),$catID,"Product 1 category ID");

		$product=new ProductModel($db);
		$this->assertFalse($product->hasChanges(),'Default Should be unchanged');
		$this->assertEqual($product->getID(),null,"Default id should be null");
		$product->setDescription('Description of product two');
		$this->assertEqual($product->getDescription(),'Description of product two',"product 2 description");
		$this->assert($product->hasChanges(),'product 2 should be changed');		
		$product->setName('Product two');
		$this->assert($product->hasChanges(),'product 2 should be changed');
		$this->assertEqual($product->getName(),'Product two',"category 2 name");
		$product->setPrice(1.23);
		$this->assert($product->hasChanges(),'product 2 should be changed');
		$this->assertEqual($product->getPrice(),1.23,"product 2 price");
		$product->setCategoryID(1);
		$this->assert($product->hasChanges(),'product 2 should be changed');
		$this->assertEqual($product->getCategoryID(),1,"product 2 categoryID");
		
		$product->save();		
		$this->assertEqual($product->getID(),2,"after insert, id");
		$this->assertFalse($product->hasChanges(),'product 2 ');		
		$this->assertEqual($product->getName(),'Product two',"after insert, name ");
		$this->assertEqual($product->getDescription(),'Description of product two',"after insert, description");
		$this->assertEqual($product->getPrice(),1.23,"after insert, price");
		$this->assertEqual($product->getCategoryID(),1,"after insert, category id");
		
		//
		$product->setName('name changed');
		$this->assertEqual($product->getName(),'name changed',"product 2 name");
		$this->assert($product->hasChanges(),'product 2 should be changed');		
		$product->setDescription('description changed');
		$this->assertEqual($product->getDescription(),'description changed',"product 2 description");
		$this->assert($product->hasChanges(),'product 2 should be changed');		
		$product->setPrice('3.45');
		$this->assertEqual($product->getPrice(),'3.45',"product 2 price");
		$this->assert($product->hasChanges(),'product 2 should be changed');		
		$product->setcategoryID(1);
		$this->assertEqual($product->getCategoryID(),1,"product 2 categoryID");
		$this->assert($product->hasChanges(),'product 2 should be changed');		
		$product->save();		
		$this->assertEqual($product->getID(),2,"after save, id");
		$this->assertFalse($product->hasChanges(),'product 2 should be unchanged');		
		$this->assertEqual($product->getName(),'name changed',"product 2 name");
		$this->assertEqual($product->getDescription(),'description changed',"product 2 description");
		$this->assertEqual($product->getPrice(),'3.45',"product 2 price");
		$this->assertEqual($product->getCategoryID(),1,"product 2 categoryID");
						
		$product=new ProductModel($db,2);
		$this->assertEqual($product->getId(),2,"product 2 id");
		$this->assertEqual($product->getName(),'name changed',"product 2 name");
		$this->assertEqual($product->getDescription(),'description changed',"product 2 description");
		$this->assertEqual($product->getPrice(),'3.45',"product 2 price");
		$this->assertEqual($product->getCategoryID(),'1',"product 2 category ID");
		$this->assertFalse($product->hasChanges(),'product 2 should be unchanged');		
			
		$product->delete();
		$this->assertEqual($product->getID(),null,"After delete id should be null");
		$this->assertEqual($product->getName(),null,"After delete  name should be null");
		$this->assertEqual($product->getDescription(),null,"After delete  description should be null");
		$this->assertEqual($product->getPrice(),null,"After delete  price should be null");
		$this->assertEqual($product->getCategoryID(),null,"After delete  category ID should be null");
		$this->assertFalse($product->hasChanges(),'After delete  Should be unchanged');

		$product=new ProductModel($db,1);
		$product->setcategoryID(1);		
		$product->save();
		$cat->delete(); // clean up	
	}
}
?>
	