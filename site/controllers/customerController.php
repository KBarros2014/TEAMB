<?php
/*
   
   
   Sample CRUD controller for a customer
   =============================================

   The following URI patterns are handled by this controller: 
   
   /admin/customer/new         	create a new customer
   /admin/customer/edit/nn		edit customer nn
   /admin/customer/delete/nn	delete customer nn
   /admin/customer/view/nn      view customer nn
   
   (nn is the customer ID)
   
 We're just implementing the customer specific stuff
*/

include 'controllers/crudController.php';
include 'models/customer.php';

class CustomerController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);
	}

	protected function getPagename(){
		return 'Customers';
	}
	
	// the following methods are the must-overrides in the Crud controller
	protected function getTemplateForNew () {
		return 'html/forms/adminCustomerNew.html';
	}
	protected function getTemplateForEdit () {
		return 'html/forms/adminCustomerEdit.html';
	}
	protected function getTemplateForDelete () {
		return 'html/forms/adminCustomerDelete.html';
	}
	protected function getTemplateForView () {
		return 'html/forms/adminCustomerView.html';
	}
	protected function createModel($id) {
		return new CustomerModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('firstname', $model->getcustomerFirstName());
		$this->setField('lastName',$model->getcustomerLastName());
		$this->setField('address',$model->getcustomerAddress());
		$this->setField('city',$model->getcustomerCity());
		$this->setField('postcode',$model->getcustomerPostCode());
		$this->setField('email',$model->getcustomerEmail());
		
		
	}
	protected function getFormData() {
		$firstname=$this->getInput('Firstname');
		$this->setField('firstname', $firstname);
		$error=CustomerModel::errorInFirstName($Firstname);
		if ($error!==null) {
			$this->setError ('Firstname',$error);
		}
		$lastname=$this->getInput('Lastname');
		$this->setField('lastname', $Lastname);
		$error=CustomerModel::errorInLastname($Lastname);
		if ($error!==null) {
			$this->setError ('Lastname',$error);
		}
		$address=$this->getInput('Address');
		$this->setField('address', $Address);
		$error=CustomerModel::errorInAddress($Address);
		if ($error!==null) {
			$this->setError ('Address',$error);
		}
		$city=$this->getInput('City');
		$this->setField('city', $City);
		$error=CustomerModel::errorInCity($City);
		if ($error!==null) {
			$this->setError ('City',$error);
		}
		$postcode=$this->getInput('PostCode');
		$this->setField('postcode', $PostCode);
		$error=CustomerModel::errorInPostCode($PostCode);
		if ($error!==null) {
			$this->setError ('PostCode',$error);
		}
		$email=$this->getInput('Email');
		$this->setField('email', $Email);
		$error=CustomerModel::errorInEmail($Email);
		if ($error!==null) {
			$this->setError ('Email',$error);
		}
		
	}
	protected function updateModel($model) {
		$firstname=$this->getField('Firstname');
		$lastname=$this->getField('Lastname');
		$address=$this->getField('Address');
		$city=$this->getField('City');
		$postcode=$this->getField('PostCode');
		$email=$this->getField('Email');
		
		
		$model->setFirstname($Firstname);
		$model->setLastname($Lastname);
		$model->setAddress($Address);
		$model->setCity($City);
		$model->setPostcode($PostCode);
		$model->setEmail($Email);
		
		$model->save();
		$this->redirectTo('admin/customers',"Customer '$Firstname' has been saved");
	}
	protected function deleteModel($model) {
		$firstname=$model->getFirstname();
		$model->delete();
		$this->redirectTo('admin/customers',"Customer '$Firstname' has been deleted");
	}	
}
?>