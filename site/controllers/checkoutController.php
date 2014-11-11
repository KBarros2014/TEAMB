<?php
include 'controllers/crudController.php';
include 'models/ShoppingCart.php'; 

class CheckoutController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Products';
	}

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

	protected function createModel($CustomerId) {
		return new ShoppingCartModel($this->getDB(),$CustomerId);
	}
	protected function getModelData($model) {
		$this->setField('id', $model->getProductId());
		$this->setField('quantity',$model->getProductQuantity());
		$this->setField('price',$model->getProductPrice());
	}
	protected function getFormData() {
		
	}
	protected function updateModel($model) {
		
	}
	protected function deleteModel($model) {
		
	}	
}
?>
