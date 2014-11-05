<?php
include 'models/myShoppingCart.php';
include 'models/orderModel.php';

class CheckoutController extends AbstractController {

	public function __construct($context) {
		parent::__construct($context);
	}
	protected function getView($isPostback) {
		
		// test data
		// 1 and 2 are productIDs
		$cart = new ShoppingCart();
		$cart->addItem (new ShoppingCartItem(1,20,45.95));
		$cart->addItem (new ShoppingCartItem(2,10,45.95));
		
		$customerID=5; // tests data
		
		OrderModel::createFromCart($cart, $customerID);
		
	}
?>