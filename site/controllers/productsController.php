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
		$uri=$this->getURI();
		$part = $uri->getPart();
		$db=$this->getDB();	
		$limit=5;	
		$totalPages=10;
		if ($part==="") {
			$rows= $db->query("select count(productID) as totalCount from products");
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
		
		$db=$this->getDB();
		$sql="select productID, productName,productPrice from products order by productName asc Limit $limit offset $offset";
		$rows=$db->query($sql);
		if (count($rows)==0) {
			$html='<p>There are no Products</p>'; 
		
		} else {
			$table = new TableView($rows);
			$table->setColumn('productName','Product name'); 
			$table->setColumn('action','Action',
				'&nbsp;<a href="##site##admin/product/view/<<productID>>">View</a>'.
				'&nbsp;<a href="##site##admin/product/edit/<<productID>>">Edit</a>'.
				'&nbsp;<a href="##site##admin/product/delete/<<productID>>">Delete</a>'); 
			$html=$table->getHtml();
			$html.='<p><a href="##site##admin/product/new">Add a new product</a></p>';
			for ($i=1; $i <= $totalPages; $i++ ) {
			$html .="<a href=\"##site##admin/products/$i\" > $i </a>";
			
			}		
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Products');
		$view->addContent($html);
		return $view;
	}
}
?>