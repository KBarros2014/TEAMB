<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a list of products
   =============================================
   
*/
include 'lib/listController.php';
include 'lib/tableView.php';

class ProductsController extends ListController {

	public function __construct($context) {
		parent::__construct($context);
		$this->setRecordsPerPage(12);
		$this->setMaxPages(10);
		$this->setLinkRoot('##site##admin/products');
	}
	protected function getCountSQL() {
		return "select count(productID) from products";
	}
	protected function getSelectionSQL() {
		return "select productID, name from products order by name asc";
	}
	protected function getPageName() {
		return 'Products';
	}
	protected function getNoRowsHtml() {
		return '<p>There are no products</p>';
	}
	protected function getHtmlForRows($rows) {
			$table = new TableView($rows);
			$table->setColumn('name','Product name');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/product/view/<<productID>>">View</a>'.
				'&nbsp;<a href="##site##admin/product/edit/<<productID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/product/delete/<<productID>>">Delete</a>'.
				'&nbsp;<a href="##site##admin/product/images/<<productID>>">Images</a>');			
			 return $table->getHtml();
	}
	protected function getExtraHtml() {
		return '<p><a href="##site##admin/product/new">Add a new product</a></p>';
	}
}
?>