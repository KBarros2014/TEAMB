<?php

include 'lib/abstractController.php';
include 'lib/view.php';
include 'models/product.php';
include 'lib/tableView.php';
class CheckoutController extends AbstractController {
	private $context;
	private $subviewTemplate;
	public function __construct($context) {
		parent::__construct ( $context );
		$this->context = $context;
	}
	protected function getView($isPostback) {
		//$cart = new ShoppingCart ( $this->context );
		// testing only
		//$cart->delete ();
		$cart = new ShoppingCart ( $this->context );
		
		//$cart->addItem ( new ShoppingCartItem ( 1, 20, 45.95 ) );
		///$cart->addItem ( new ShoppingCartItem ( 1, 10, 45.95 ) );
		
		$cart->setCustomerId ( 1 ); // tests data
		                         // end test patches
		
		$uri = $this->getURI ();
		$action = $uri->getPart ();
		switch ($action) {
			case '' :
				return $this->handleBlank ( $isPostback );
			case 'delivery' :
				// $this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleDelivery ( $isPostback, $uri->getID () );
			case 'newCustomer':
			   return $this->handlePurchase($isPostback, $uri->getID() ) ;
			case 'payment' :
				// $this->subviewTemplate = $this->getTemplateForView();
				return $this->handlePayment ( $isPostback, $uri->getID () );
			case 'final' :
				// $this->subviewTemplate = $this->getTemplateForDelete();
				return $this->processOrder ( $isPostback );
			default :
				throw new InvalidRequestException ( "Invalid action in URI" );
		}
	}
	public function handleBlank($postback, $id = null) {
		if (!$postback) {
			// display cart has a link to /deliver
			return $this->createShoppingCartView ( $id ); // form with data from DB (or blank if ID null)
		}
	}
	// display cart
	// return 'html/productPanel.html';
	// user confirms they want to purchase (link to /delivery
	public function handleDelivery($isPostback,$id=null) {
		// if not postbnack, display form
		// if postback, get post data, create customer, save id, redirect to final
		return $this->createDeliveryView ( $id );
	}
	public function handlePayment() {
	//skipping this function
	}
	public function processOrder() {
		$cart = new ShoppingCart ( $this->getContext () );
		OrderModel::createFromCart ( $this->context->getDB (), $cart );
		$cart->delete ();
	}
	protected function getPagename() {
		return 'checkout';
	}
	private function createShoppingCartView($id) {
	    $view = new View ( $this->getContext () );
		$view->setTemplate ( 'html/masterPage.html' );
		$view->setTemplateField ( 'pagename', 'Cart' );
		$cart = new ShoppingCart ( $this->getContext () );
		$html = '';
		$db = $this->getDB ();
		$count = $cart->getCount ();
		if ($count ==null) {
		$html .="<br/>Cart is empty <br/>";
		$view->addContent($html);
		return $view;
		}
	
	else { 
	
		$html ='<form action ="##delivery" method="POST">
	<label>First name:</label>
		<input type="text" name="first name" value="##name##" /> <br/><br/>
		<label>Last name:</label>
			<input type="text" name="last name" value="##name##" /><br/><br/>
			<label>Street name:</label>
			<input type="text" name="street name" value="##name##" /><br/><br/>
			<label>Post code:</label>
			<input type="text" name="Post code" value="##number##" /><br/><br/>
			<input type="submit" value="Submit"><br>
			</form>';
		$view->addContent($html);
		
      return $view;
	}
	}
	private function handlePurchase($id) {
	    $cart = new ShoppingCart ( $this->context );
		$db = $this->getDB ();
		$product = new ProductModel ( $db, $id );
		$price = $product->getProductPrice ();
		$this->productPrice = $product->getProductPrice ();

	
	    $cart->getTotalPrice();
	
		$this->redirectTo ( "productsViewer");
		
		return null;
	}
	
	private function createDeliveryView($id) {
		
		$view = new View ( $this->getContext () );
		$view->setModel ( null );
		$view->setTemplate ( 'html/masterPage.html' );
		$view->setTemplateField ( 'pagename', 'Products' );
		$view->addContent ( $html );
		return $view;
	
	}
	private function createFinalView($id) {
		$view = new View ( $this->getContext () );
		$view->setTemplate ( 'html/productPanel.html' );
		$view->setTemplateField ( 'pagename', $this->getPagename () );
		$view->setSubviewTemplate ( $this->subviewTemplate );
		
		return $view;
	}
}
?>