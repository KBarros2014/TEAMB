<?php
//include 'models/myShoppingCart.php';
include 'models/orderModel.php';
include 'lib/abstractController.php';
class CheckoutController extends AbstractController {
	private $context;
	
	public function __construct($context) {
		parent::__construct($context);
		$this->context=$context;
	}
	protected function getView($isPostback) {
		$cart = new ShoppingCart( $this->context);
		$cart->delete();
		$cart = new ShoppingCart( $this->context);
	
		$cart->addItem (new ShoppingCartItem(1,20,45.95));
		$cart->addItem (new ShoppingCartItem(1,10,45.95));
		
		$cart->setCustomerId(1); // tests data
		
		OrderModel::createFromCart($this->context->getDB(),$cart);
        $uri=$this->getURI();
		$action=$uri->getPart();
		switch ($action) {
			case 'blank':
				$this->subviewTemplate = $this->getTemplateForNew();
				return $this->handleBlank($isPostback);
			case 'delivery':
				$this->subviewTemplate = $this->getTemplateForEdit();
				return $this->handleEdit($isPostback,$uri->getID());	
			case 'payment':
				$this->subviewTemplate = $this->getTemplateForView();
				return $this->handleView($isPostback,$uri->getID());	
			case 'final':
				$this->subviewTemplate = $this->getTemplateForDelete();
				return $this->handleDelete($isPostback,$uri->getID());	
			default:
				throw new InvalidRequestException ("Invalid action in URI");
		}	
	}
	public function  handleBlank(
	
	}
	
?>