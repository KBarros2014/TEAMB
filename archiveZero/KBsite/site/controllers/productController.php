<?php
/*
/admin/product/new         	create a new product
   /admin/product/edit/nn		edit product nn
   /admin/productn/delete/nn	delete product nn
   /admin/product/view/nn      view product nn
   
   (nn is the category ID)







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
		return 'html/forms/adminProductsNew.html';
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminProductsEdit.html';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminProductsDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminProductsView.html';
	}
	protected function createModel($id) {
		return new ProductModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('name', $model->getProductName());
		//$this->setField('description',$model->getDescription());		
	}
	protected function getFormData() {
		$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=ProductModel::errorInName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		$description=$this->getInput('description');
		$this->setField('description', $description);
		$error=CategoryModel::errorInDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		}
	}
	protected function updateModel($model) {
		$name=$this->getField('productName');
		$description=$this->getField('description');
		
		$model->setName($name);
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