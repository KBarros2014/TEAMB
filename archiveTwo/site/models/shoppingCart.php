<?php
/*	Note that this model does NOT use the database!

	Usage:
	1) To access the cart (do this before any other actions)
		$cart = new ShoppingCart($context);
	2) to add an item to a cart
		$item = new ShoppingCartItem ($productCode, $quantity, $price)
		$cart->addItem($item);
	3) to inspect the cart ...
		a) number of items
			$n = $cart->getCount();
		b) total quantity
			$totalQuantity = $cart->getTotalQuantity();
		c) total price
			$totalPrice = $cart->getTotalPrice();
		d) retrieve an item
			$item=$cart->getAt($index);
	4) To remove an item from the cart
		$cart->removeAt($index);
	5) To empty the cart
		$cart->delete();

	Further work:
		The Order class will need a method CreateFromCart($cart, ...)
		(This should create the order and empty the cart)
		
*/
class ShoppingCart {
	private $context;
	private $cart;
	
	function __construct (IContext $context) {
		$this->context=$context;
		if ($context->getSession()->isKeySet('cart')) {
			$this->cart=$context->getSession()->get('cart');
		} else {
			$this->cart=array();
		}
	}
	private function  save() {
		$this->context->getSession()->set('cart',$this->cart);
	}
	public function delete() {
		$this->context->getSession()->unsetKey('cart');
		$this->cart=array();
	}
	public function addItem(ShoppingCartItem $item) {
		$this->cart[]=$item;
		$this->save();
	}
	public function removeItemAt($index) {
		unset($this->cart[$index]);
		$this->cart = array_values($this->cart);
		$this->save();
	}
	public function getCount() {
		return count($this->cart);
	}
	public function getItemAt($index) {
		return $this->cart[$index];
	}
	public function getTotalPrice() {
		$total=0;
		foreach ($this->cart as $item) {
			$total+= $item->getTotal();
		}
		return $total;
	}
	public function getTotalQuantity() {
		$total=0;
		foreach ($this->cart as $item) {
			$total+= $item->getQuantity();
		}
		return $total;
	}
}

class ShoppingCartItem {
	private $itemCode;
	private $quantity;
	private $price;
	
	public function __construct($itemCode, $quantity, $price) {
		$this->itemCode =$itemCode;
		$this->quantity =$quantity;
		$this->price =$price;	
	}
	public function getItemCode() {
		return $this->itemCode;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function getPrice() {
		return $this->price;
	}
	public function getTotal() {
		return $this->quantity * $this->price;
	}
}

?>