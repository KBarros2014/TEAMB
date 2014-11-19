<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class ProductsController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Categories');
		$view->addContent('To be written');
		return $view;
	}
}
?>