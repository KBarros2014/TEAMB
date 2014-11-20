<?php
//include 'models/myShoppingCart.php';
include 'models/orderModel.php';
include 'lib/abstractController.php';
include 'lib/view.php';
include 'lib/tableView.php';

class CheckoutController extends AbstractController {
	private $context;
	private $subviewTemplate;
	
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
	protected function getView($isPostback) {
		$cart = new ShoppingCart( $this->context);
		// testing only
		$cart->delete();
		$cart = new ShoppingCart( $this->context);
	
		$cart->addItem (new ShoppingCartItem(1,20,45.95));
		$cart->addItem (new ShoppingCartItem(1,10,45.95));
		
		$cart->setCustomerId(1); // tests data
		// end test patches
		

        $uri=$this->getURI();
		$action=$uri->getPart();
		switch ($action) {
			case '':
				return $this->handleBlank($isPostback);
			case 'delivery':
			//	$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleDelivery($isPostback,$uri->getID());	
			case 'payment':
				//$this->subviewTemplate = $this->getTemplateForView();
				return $this->handlePayment($isPostback,$uri->getID());	
			case 'final':
				//$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->processOrder($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
	}
	public function  handleBlank($postback, $id =null){
	      if (!$postback) {
		//display cart has a link to /deliver
           return $this->createShoppingCartView($id); 			// form with data from DB (or blank if ID null)		
		  
			}
		}
		// display cart
	//	return 'html/productPanel.html';
		// user confirms they want to purchase (link to /delivery
	
	public function handleDelivery($isPostback,$uri) {
	 //    if not postbnack, display form 
	 // if postback, get post data, create customer, save id, redirect to final
	     return $this->createDeliveryView($id); 	
	}
	public function handlePayment()  {
	
	}
	public function processOrder()  {
			OrderModel::createFromCart($this->context->getDB(),$cart);
	
	
	}
	protected function getPagename(){
		return 'checkout';
	
	}
	
	private function createShoppingCartView($id) {
	    
		$view=new View($this->getContext());
		$view->setTemplate('html/productPanel.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate($this->subviewTemplate);		

		
		
		return $view;
	}
	
	private function createDeliveryView($id) {
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
		}	
		$view= new View($this->getContext());	
		$view->setModel(null);
		$view->setTemplate('html/masterPage.html');
		$view->setTemplateField('pagename','Products');
		$view->addContent($html);
		return $view;
	}
	
	
	private function createFinalView($id) {
		$view=new View($this->getContext());
		
		$view->setTemplate('html/productPanel.html');
		$view->setTemplateField('pagename',$this->getPagename());
		$view->setSubviewTemplate($this->subviewTemplate);		

		
		
		return $view;
	}
	
	
	}
?>