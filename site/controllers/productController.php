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
include 'models/category.php'; 

class ProductController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Products'; 
	} 

	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
		return 'html/forms/adminProductNew.html'; 
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminProductEdit.html'; 
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminProductDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminProductView.html';
	}
	protected function createModel($id) {
		return new ProductModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$category_model = new CategoryModel($this->getDB(), $model->getCategoryID());
		$this->setField('name', $model->getProductName());
		$this->setField('description',$model->getProductDescription());		
		$this->setField('price',$model->getproductPrice());		
		$this->setField('category',$category_model->getName());		
	}
	protected function getFormData() {
		$name = $this->getInput('name');
		$this->setField('name', $name);
		$error = ProductModel::errorInProductName($name);
		if ($error !== null) {
			$this->setError ('name',$error);
		}
		
		$description = $this->getInput('description');
		$this->setField('description', $description);
		/*$error = ProductModel::errorInproductDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		} */

		$price = $this->getInput('price');
		$this->setField('price', $price);
		$error = ProductModel::errorInproductPrice($price);
		if ($error !== null) {
			$this->setError ('price',$error);
		} 
	}
	protected function updateModel($model) {
		$name = $this->getField('name');
		$description = $this->getField('description');
		$price = $this->getField('price');
		
		$model->setProductName($name);
		$model->setProductDescription($description);	
		$model->setProductPrice($price);	
		$model->save();
		$this->redirectTo('admin/products',"Product '$name' has been saved");
	}
	protected function deleteModel($model) {
		$name=$model->getProductName();
		$model->delete();
		$this->redirectTo('admin/products',"Product '$name' has been deleted");
	}	
}
?>