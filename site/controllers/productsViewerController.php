
<?php
/*
   A PHP framework for web sites by Mike Lopez
   
   Sample CRUD controller for a list of products
   =============================================
   
*/
include 'lib/listController.php';
include 'models/product.php';
//include 'lib/abstractModel.php';

class ProductsViewerController extends ListController {
	private $panelTemplate;

	public function __construct($context) {
		parent::__construct($context);
		$this->setRecordsPerPage(4);
		$this->setMaxPages(20);
		$this->setLinkRoot('##site##products');
		$this->panelTemplate = file_get_contents('html\productPanel.html');
	}
	protected function getCountSQL() {
		return "select count(productID) from products";
	}
	protected function getSelectionSQL() {
		return "select productID from products order by productName asc";
	}
	protected function getPageName() {
		return 'our products';
	}
	protected function getNoRowsHtml() {
		return '<p>There are no matching products</p>';
	}
	protected function getHtmlForRows($rows) {
		$html='';
		foreach ($rows as $row){
			$html.=$this->getHtmlForRow($row);
		}
		return $html;
	}
	
	private function getHtmlForRow ($row) {
		$id = $row['productID'];
		$product = new ProductModel ($this->getDB(), $id);
	
		$html=$this->panelTemplate;
		
		$html=str_replace ("##id##",$id,$html);
		$html=str_replace ("##name##",$product->getProductName(),$html);
		$html=str_replace ("##description##",$product->getProductDescription(),$html);
		$html=str_replace ("##price##",$product->getProductPrice(),$html);

		$thumbnail = $product->getThumbnail();
		
		if ($thumbnail==null) { // toodo implement this
			$thumbnail='';
		} else {
			$thumbnail="<img src=\"##site##/images/products/$thumbnail\" />";	
		}
		$html=str_replace ("##thumbnail##",$thumbnail,$html);

		return $html;
	}
	protected function getExtraHtml() {
		return '<br/><br/>';
	}
}
?>
 
