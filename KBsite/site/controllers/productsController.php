<?php
/*
   A PHP framework for web sites by KBarros
   
   CRUD controller for a list of categories
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
		$db=$this->getDB();
		$sql="select productId, productName from products order by productName asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no products</p>';
		} else {
			$table = new TableView($rows);
			$table->setColumn('productName','Product name');
		  // $table->setColumn('productPrice', 'Product prIce');
			//$table->setColumn('productPic','Product picture');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/product/view/<<productId>>">View</a>'.
				'&nbsp;<a href="##site##admin/product/edit/<<productId>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/product/delete/<<productId>>">Delete</a>');
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/product/new">Add a new product</a></p>';
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename ','Products');
		$view->addContent($html);
		return $view;
		}
	}
}
?>