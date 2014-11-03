<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class ordersController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB();
		$sql="select orderId from orders";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no orders</p>';
		}
		
		/*else {
			$table = new TableView($rows);
			$table->setColumn('orderId','Order name');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/category/view/<<catID>>">View</a>'.
				'&nbsp;<a href="##site##admin/category/edit/<<catID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/category/delete/<<catID>>">Delete</a>');
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/category/new">Add a new category</a></p>';
		}	
		*/
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','orders');
		$view->addContent($html);
		return $view;
	}
}
?>