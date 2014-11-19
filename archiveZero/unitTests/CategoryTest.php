<?php
include 'models/category.php';

class CategoryTest extends UnitTest {
	
	function __construct($context) {
		parent::__construct($context);
	}
	protected function testStaticMethods() {
		$context=$this->getContext();
		$db=$context->getDB();

		$OKname= str_repeat('x',40);
		$longName= str_repeat('x',41);
		$OKdesc=str_repeat('x',200);
		$longDesc=str_repeat('x',201);
	
		$this->assertEqual(CategoryModel::errorInName(null),'Category name must be specified','name validation');
		$this->assertEqual(CategoryModel::errorInName(''),'Category name must be specified','name  validation');
		$this->assertEqual(CategoryModel::errorInName($longName),'Category name must have no more than 40 characters','name validation');
		$this->assertEqual(CategoryModel::errorInName($OKname),null,'name validation');
		
		$this->assertEqual(CategoryModel::errorInDescription(null),'Description must be specified','description validation');
		$this->assertEqual(CategoryModel::errorInDescription(''),'Description must be specified','description  validation');
		$this->assertEqual(CategoryModel::errorInDescription($longDesc),'Description must have no more than 200 characters','description validation');
		$this->assertEqual(CategoryModel::errorInDescription($OKname),null,'description validation');

		$this->assertFalse(CategoryModel::isExistingId($db,null),'ID existence null');
		$this->assertFalse(CategoryModel::isExistingId($db,'xx'),'ID existence alpha');
		$this->assertFalse(CategoryModel::isExistingId($db,2 ),'ID existence false');
		$this->assert(CategoryModel::isExistingId($db,1 ),'ID existence true');
	}
	protected function testDefaults() {
		$context=$this->getContext();
		$db=$context->getDB();

		$cat=new CategoryModel($db);
		$this->assertEqual($cat->getID(),null,"Default id should be null");
		$this->assertEqual($cat->getName(),null,"Default name should be null");
		$this->assertEqual($cat->getDescription(),null,"Default description should be null");
		$this->assertFalse($cat->hasChanges(),'Default Should be unchanged');
	}
	
	protected function testUpdateMethod() {
		$context=$this->getContext();
		$db=$context->getDB();

		$cat=new CategoryModel($db,1);
		$this->assertEqual($cat->getID(),'1',"category 1 id");
		$this->assertEqual($cat->getName(),'Category one',"category 1 name");
		$this->assertEqual($cat->getDescription(),'Description of category one',"category 1 description");
		$this->assertFalse($cat->hasChanges(),'category 1 should be unchanged');
		$cat->setName('Category one (edited)');
		$this->assertEqual($cat->getName(),'Category one (edited)',"category 1 name");
		$this->assert($cat->hasChanges(),'category 1 should be changed');
		$cat->setDescription('Description of category one (edited)');
		$this->assertEqual($cat->getDescription(),'Description of category one (edited)',"category 1 description");
		$this->assert($cat->hasChanges(),'category 1 should be changed');
		$cat->save();
		$this->assertFalse($cat->hasChanges(),'category 1 should be unchanged');
		// verify properties still have their values after save
		$this->assertEqual($cat->getID(),1,"category 1 id");
		$this->assertEqual($cat->getName(),'Category one (edited)','category 1 name');
		$this->assertEqual($cat->getDescription(),'Description of category one (edited)',"category 1 description");

	}
	protected function testInsertMethods() {
		$context=$this->getContext();
		$db=$context->getDB();
		
		$cat=new CategoryModel($db);
		$this->assertFalse($cat->hasChanges(),'Default Should be unchanged');
		$this->assertEqual($cat->getID(),null,"Default id should be null");
		$cat->setDescription('Description of category two');
		$this->assertEqual($cat->getDescription(),'Description of category two',"category 2 description");
		$this->assert($cat->hasChanges(),'category 2 should be changed');		
		$cat->setName('Category two');
		$this->assert($cat->hasChanges(),'category 2 should be changed');
		$this->assertEqual($cat->getName(),'Category two',"category 2 name");
		$cat->save();		
		$this->assertEqual($cat->getID(),2,"after insert, id should be 2");
		$this->assertFalse($cat->hasChanges(),'category 2 should be unchanged after insert');		
		
		$cat->setName('name changed');
		$this->assertEqual($cat->getName(),'name changed',"category 2 name");
		$this->assert($cat->hasChanges(),'category 2 should be changed');		
		$cat->setDescription('description changed');
		$this->assertEqual($cat->getDescription(),'description changed',"category 2 description");
		$this->assert($cat->hasChanges(),'category 2 should be changed');		
	
		$cat->save();		
		$this->assertEqual($cat->getID(),2,"after save, id should be 2");
		$this->assertFalse($cat->hasChanges(),'category 2 should be unchanged after save');		
	}
	protected function testDeleteMethods() {
		$context=$this->getContext();
		$db=$context->getDB();
		
		
		$cat=new CategoryModel($db,2);
		$this->assertEqual($cat->getId(),2,"category 2 id");
		$this->assertEqual($cat->getName(),'name changed',"category 2 name");
		$this->assertEqual($cat->getDescription(),'description changed',"category 2 description");
		$this->assertFalse($cat->hasChanges(),'category 2 should be changed');		
			
		$cat->delete();
		$this->assertEqual($cat->getID(),null,"After delete id should be null");
		$this->assertEqual($cat->getName(),null,"After delete  name should be null");
		$this->assertEqual($cat->getDescription(),null,"After delete  description should be null");
		$this->assertFalse($cat->hasChanges(),'After delete  Should be unchanged');
		
		}
}
?>
	