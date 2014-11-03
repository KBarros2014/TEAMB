<?php
/*
 AL
*/

include 'controllers/crudController.php';
//include 'models/orderModel.php';

class orderController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}

	protected function getPagename(){
		return 'order';
	
	}
	
	// the following methods are the must-overrides in the Crud controller
	
	protected function getTemplateForNew () {
		//return 'html/forms/adminOrderNew.html';
	}
	protected function getTemplateForView () {
		//return 'html/forms/adminOrderNew.html';
	}
	protected function getTemplateForEdit () {
		//return 'html/forms/adminOrderNew.html';
	}
	protected function getTemplateForDelete () {
		//return 'html/forms/adminOrderNew.html';
	}
	
	protected function createModel($id) {
		/*return new OrderModel($this->getDB(),$orderId);*/
	}
	
	protected function getModelData($model) {
		
	}
	protected function getFormData() {
		
	}
	protected function updateModel($model) {
		
	}
	protected function deleteModel($model) {
	
	}
}
?>