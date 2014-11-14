<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a list of categories
   ===============================================
   
*/
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class CategoriesController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		$db=$this->getDB();
		$sql="select categoryID, name from categories order by name asc";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no categories</p>';
		} else {
			$table = new TableView($rows);
			$table->setColumn('name','Category name');
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/category/view/<<categoryID>>">View</a>'.
				'&nbsp;<a href="##site##admin/category/edit/<<categoryID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/category/delete/<<categoryID>>">Delete</a>');
			$html=$table->getHtml();
		}	
		$html.='<p><a href="##site##admin/category/new">Add a new category</a></p>';
		
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Categories');
		$view->addContent($html);
		return $view;
	}
}
?>