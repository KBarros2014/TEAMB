<?php
/*
 AL
*/

include 'controllers/crudController.php';
include 'models/orderModel.php';

class orderController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
		echo "Order controller created<br/>";
		
	}

	protected function getPagename(){
		return 'order';
	
	}
	
	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
	}
	protected function getTemplateForView () {
		return 'html/forms/adminOrderView.html';
	}
	protected function getTemplateForEdit () {
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminOrderSubmit.html';
	}
	
	protected function createModel($id) {
		return new OrderModel($this->getDB(),$id);
	}
	
	protected function getModelData($model) {
		$this->setField('OrderId', $model->getOrderId());
		$this->setField('orderDate',$model->getOrderDate());	
	}
	protected function getFormData() {
		echo 'i here';
	}
	protected function updateModel($model) {
		echo 'i here';
	}
	protected function deleteModel($model) {
		echo 'i here';
	}
}
?>