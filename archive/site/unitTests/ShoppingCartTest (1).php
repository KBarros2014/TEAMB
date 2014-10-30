<?php

include 'models/shoppingCart.php';

class ShoppingCartTest extends UnitTest {
	
		function __construct($context) {
			parent::__construct($context);
		}

	protected function doTests() {
		$context=$this->getContext();
	//	$db=$context->getDB();    //... Database not used!

		$cart = new ShoppingCart($context);
		$this->assertEqual($cart->getCount(),0,'cart should be empty');
		
		$item1 = new ShoppingCartItem('abc',1,23.45);
		$item2 = new ShoppingCartItem('def',6,78.90);
		
		$cart->addItem($item1);
		$this->assertEqual($cart->getCount(),1,'cart should have one item');
		$this->assertItemsEqual($item1, $cart->getItemAt(0), 'First item');
		$cart->addItem($item2);
		$this->assertEqual($cart->getCount(),2,'cart should have two items');
		$this->assertItemsEqual($item1, $cart->getItemAt(0), 'First item of two');
		$this->assertItemsEqual($item2, $cart->getItemAt(1), 'Second item of two');
		
		// check persistence
		$cart = new ShoppingCart($context);
		$this->assertEqual($cart->getCount(),2,'cart should still have two items');
		$this->assertItemsEqual($item1, $cart->getItemAt(0), 'First item of two');
		$this->assertItemsEqual($item2, $cart->getItemAt(1), 'Second item of two');
	
		$cart->removeItemAt(0);
		$this->assertEqual($cart->getCount(),1,'cart should now have one item');
		$this->assertItemsEqual($item2, $cart->getItemAt(0), 'First item of one is #2');

		$cart->addItem($item1);
		$this->assertEqual($cart->getCount(),2,'cart with two swapped items');
		$this->assertItemsEqual($item2, $cart->getItemAt(0), '#2 First item of two');
		$this->assertItemsEqual($item1, $cart->getItemAt(1), '#1 Second item of two');
	
		$this->assertEqual($cart->getTotalQuantity(),1 + 6,'total quantity');
		$this->assertEqual($cart->getTotalPrice(),23.45 + 6 * 78.90,'total price');
		
		$cart->delete();
		$this->assertEqual($cart->getCount(),0,'check empty after delete');
		$cart= new ShoppingCart($context);
		$this->assertEqual($cart->getCount(),0,'check still empty after recreating');
		
	}
	
	private function assertItemsEqual ($item1, $item2,$testID) {
		$this->assertEqual($item1->getItemCode(),$item2->getItemCode(),$testID.': item codes should match');
		$this->assertEqual($item1->getQuantity(),$item2->getQuantity(),$testID.': quantities should match');
		$this->assertEqual($item1->getPrice(),$item2->getPrice(),$testID.': Prices should match');
	}
}
?>