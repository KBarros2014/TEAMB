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
	/*protected function getTemplateForNew () {
		return 'html/forms/adminCategoryNew.html';
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminCategoryEdit.html';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminCategoryDelete.html';
	}
	*/
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
		/*$this->setField('OrderName', $model->getName());
		$this->setField('orderPRice',$model->getDescription());	*/	
	}
	protected function getFormData() {
		/*$name=$this->getInput('name');
		$this->setField('name', $name);
		$error=CategoryModel::errorInName($name);
		if ($error!==null) {
			$this->setError ('name',$error);
		}
		$description=$this->getInput('description');
		$this->setField('description', $description);
		$error=CategoryModel::errorInDescription($description);
		if ($error!==null) {
			$this->setError ('description',$error);
		}*/
	}
	protected function updateModel($model) {
		/*$name=$this->getField('name');
		$description=$this->getField('description');
		
		$model->setName($name);
		$model->setDescription($description);	
		$model->save();
		$this->redirectTo('admin/categories',"Category '$name' has been saved");*/
	}
	protected function deleteModel($model) {
	/*
		$name=$model->getName();
		$model->delete();
		$this->redirectTo('admin/categories',"Category '$name' has been deleted");*/
	}
}
?>