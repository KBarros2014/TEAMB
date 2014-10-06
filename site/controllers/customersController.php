<?php
/*
  
   
   Sample CRUD controller for all customers
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class customersController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB();
		$sql="select custID, custFirstName from customers order by custFirstName asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no customers</p>';
		} else {
			$table = new TableView($rows);
			$table->setColumn('custName','Customer firstname');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/customer/view/<<custID>>">View</a>'.
				'&nbsp;<a href="##site##admin/customer/edit/<<custID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/customer/delete/<<custID>>">Delete</a>');
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/customer/new">Add a new customer</a></p>';
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Customers');
		$view->addContent($html);
		return $view;
	}
}
?>