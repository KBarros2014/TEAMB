<?php
include 'controllers/crudController.php';
include 'models/order.php'; 
include 'models/CustomerModel.php'; 
include 'models/product.php'; 
include 'lib/tableView.php'; 

class OrderController extends CrudController {

	public function __construct(IContext $context) {
		parent::__construct($context);  
	}

	protected function getPagename(){
		return 'Orders';
	}

	protected function getTemplateForNew () {
		return 'html/forms/adminCategoryNew.html';
	}
	
	protected function getTemplateForEdit () {
		return 'html/forms/adminCategoryEdit.html';
	}
	
	protected function getTemplateForDelete () {
		return 'html/forms/adminCategoryDelete.html';
	}
	
	protected function getTemplateForView () {
		return 'html/forms/adminOrderView.html';
	}

	protected function createModel($id) {
		return new OrderModel($this->getDB(),$id);
	}
	protected function getModelData($model) {
		$this->setField('id', $model->getOrderId());
		$this->setField('date',$model->getOrderDate());
		$this->setField('success',$model->getOrderIsSuccess());
		$customer_model = new CustomerModel($this->getDB(), $model->getCustomerId());
		$this->setField('customer',$customer_model->getCustomerFirstName().' '.$customer_model->getCustomerLastName());
		$order_products = $model->getOrderProducts($model->getOrderId());
		foreach ($order_products as &$order_product){
			$product_model = new ProductModel($this->getDB(), $order_product['productId']);
			$order_product['productName'] = $product_model->getProductName();
			$order_product['productPrice'] = $product_model->getProductPrice();
		}
		$productsTable = new TableView($order_products);
		$productsTable->setColumn('productName','Product Name');
		$productsTable->setColumn('productQuantity','Quantity');
		$productsTable->setColumn('productPrice','Product Price (each)');
		$this->setField('orderProducts', $productsTable->getHTML());
	}
	protected function getFormData() {
		
	}
	protected function updateModel($model) {
		
	}
	protected function deleteModel($model) {
		
	}	
}
?>
