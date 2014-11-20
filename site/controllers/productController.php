<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a product
   =============================================

   The following URI patterns are handled by this controller: 
   
   /admin/product/new         	create a new product
   /admin/Product/edit/nn		edit product nn
   /admin/product/delete/nn	delete product nn
   /admin/product/view/nn      view product nn
   
   (nn is the product ID)
   
   Note that most of the logic is in the parent CRUD controller
   Here, We're just implementing the product specific stuff
*/

include 'controllers/crudController.php';
include 'models/product.php'; 
include 'lib/listSelectionView.php';

class ProductController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Products'; 
	} 

	//Add new product page
	protected function getTemplateForNew () {
		return 'html/forms/adminProductNew.html'; 
	}

	//Edit product page
	protected function getTemplateForEdit () {
		return 'html/forms/adminProductEdit.html'; 
	}

	//Delete product
	protected function getTemplateForDelete () {
		return 'html/forms/adminProductDelete.html';
	}

	//View product page
	protected function getTemplateForView () {
		return 'html/forms/adminProductView.html';
	}

	//Initialises the model
	protected function createModel($id) {
		return new ProductModel($this->getDB(),$id);
	}

	//This function takes the rows supplied and returns
	//the html for select drop down
	private function getCategoryListView($rows, $categoryID = null) {
		$list = new ListSelectionView($rows);
		$list->setFormName('catID');
		$list->setIdColumn('catID');
		$list->setValueColumn('catName');
		if(isset($categoryID)){
			$list->setSelectedId($categoryID);
		}
		return $list->getHtml();
	}

	//Gets the product data 
	protected function getModelData($model) {
		$this->setField('name', $model->getProductName());
		$this->setField('description',$model->getProductDescription());	//	for the tests
		$this->setField('price',$model->getProductPrice());		
		$this->setField('picture',$model->getProductPic());	

		if($model->getCategoryId() !== null){
			$category_model = new CategoryModel($this->getDB(), $model->getCategoryId()); //initialise the category model
		}else{
			$category_model = new CategoryModel($this->getDB()); //initialise the category model
		}

		$this->setField('category', $category_model->getName());
		$this->setField('categoryList', $this->getCategoryListView($category_model->getAll(), $model->getCategoryId()));
	}
	

	//Get the input form data and set the product model fields
	protected function getFormData() {
		$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=ProductModel::errorInProductName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}

		$description=$this->getInput('description');
		$this->setField('description', $description);
		$error=ProductModel::errorInProductDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		}

		$price=$this->getInput('price');
		$this->setField('price', $price);
		$error=ProductModel::errorInProductPrice($price);
		if ($error!==null) {
			$this->setError ('price',$error);
		}
		
		$catID=$this->getInput('catID');
		$this->setField('catID', $catID);
		$error=ProductModel::errorInProductCat($catID);
		if ($error!==null) {
			$this->setError ('catID',$error);
		}
	}
	
	//Duplicate? You should get the categories using category model
	private function getCategories($selectedID) {
		$db = $this->getDB();
		$sql ='select catID, name from categories order by name';
		$rowset= $db->query($sql);
		echo $rowset;
		$list = new ListSelectionView($rowset);
		$list = setFormName('categoryID');
		$list = setIdColumn('categogyID');
		return $list->getHtml();
	}

	protected function updateModel($model) {
		$productName=$this->getField('name');//get infro from input boxes 
		$description=$this->getField('description');
		$productPrice=$this->getField('price');
		$catID=$this->getField('catID'); // for test get html data which is a list of categories ex technology furniture ect  
		//$catID =(int)$catID; //categroy id is an integer kab transforms that options into integers
		//var_dump($catID);//what type we got
		$model->setProductName($productName);
		$model->setProductPrice($productPrice);
		$model->setProductDescription($description);
		$model->setCategoryId($catID);
		$model->save();
		$this->redirectTo('admin/products',"Product '$productName' has been saved");
	}
	protected function deleteModel($model) {
		$name=$model->getProductName();
		$model->delete();
		$this->redirectTo('admin/products',"Product '$name' has been deleted");
	}	
}
?>