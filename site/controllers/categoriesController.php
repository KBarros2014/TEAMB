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
		$uri=$this->getURI();
		$part = $uri->getPart();
		$db=$this->getDB();	
		$limit=5;	
		$totalPages=10;
		if ($part==="") {
			$rows= $db->query("select count(CatID) as totalCount from categories");
			$row=$rows[0];
			$rowCount =$row['totalCount'];
			$totalPages=ceil ($rowCount / $limit); // total number of pages
		}
	
		if (is_numeric($part)) {
			$pageNumber = (int) $part;
			$part2= $uri->getPart();
			if (is_numeric($part2)) {
				$totalPages = (int) $part2;
			}
		} else {
			$pageNumber=1;
		}

		$offset=($pageNumber - 1) * $limit;
		echo"Total html page $totalPages, you are in page $pageNumber now";	
		$sql="select catID, catName from categories order by catName asc Limit $limit offset $offset";
		$rows=$db->query($sql);
		$table = new TableView($rows);
		$table->setColumn('catName','Category name');
		$table->setColumn('action','Action',
			'&nbsp;<a href="##site##admin/category/view/<<catID>>">View</a>'.
			'&nbsp;<a href="##site##admin/category/edit/<<catID>>">Edit</a>'.
			'&nbsp;<a href="##site##admin/category/delete/<<catID>>">Delete</a>');
		$html=$table->getHtml();
		$html.='<p><a href="##site##admin/category/new">Add a new category</a></p>';				
		
	
		// add links to other pages	
		for ($i=1; $i <= $totalPages; $i++ ) {
			$html .="<a href=\"##site##admin/categories/$i\" > $i </a>";
		}
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Categories');
		$view->addContent($html);
		return $view;
	}
}
?>