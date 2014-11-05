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
		// $sql="select orderId from orders where dateSent=null";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no orders</p>';
		}
		
		else {
			$table = new TableView($rows);
			$table->setColumn('orderId','Order name');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/order/view/<<orderId>>">View</a>'.
				'&nbsp;<a href="##site##admin/order/submit/<<orderId>>">Submit</a>');
			$html=$table->getHtml();
			//$html.='<p><a href="##site##admin/order/new">Make New Order</a></p>';
		}	
		
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','unsent orders');
		$view->addContent($html);
		return $view;
	}
}
?>