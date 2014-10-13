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
		$this->setField('name', $model->getProductName());
		//$this->setField('description',$model->productDescription());		
		$this->setField('price',$model->getproductPrice());		
		$this->setField('picture',$model->getproductPic());		
	}
	protected function getFormData() {
		$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=ProductModel::errorInProductName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		$description=$this->getInput('description');
		$this->setField('description', $description);
		//$error=ProductModel::errorInproductDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		} 
	}
	protected function updateModel($model) {
		$name=$this->getField('name');
		$description=$this->getField('description');
		
		$model->setName($ProductName);
		$model->setDescription($description);	
		$model->save();
		$this->redirectTo('admin/products',"Product '$name' has been saved");
	}
	protected function deleteModel($model) {
		$name=$model->getName();
		$model->delete();
		$this->redirectTo('admin/products',"Product '$name' has been deleted");
	}	
}
?>