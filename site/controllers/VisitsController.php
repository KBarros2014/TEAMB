<?php
/*
   A PHP framework for web sites by Alex
   
   Sample CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';


class VisitsController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB(); 
		$sql="select productID, productName,productPrice,productPrice * 1.2 AS NewPrice from products order by productName asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no Products</p>'; 
		
		} else {
			$table = new TableView($rows);
			$table->setColumn('productName','Product Name'); 
			$table->setColumn('productPrice','Member product Price'); 
			$table->setColumn('NewPrice','Non Member product Price'); 
			$table->setColumn('Action','Action',
				'&nbsp;<a href="##site##admin/product/view/<<productID>>">Add to check out</a>'); 
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/checkOut">Check out</a></p>';
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','List All Products');
		$view->addContent($html);
		return $view;
	}
}
?>