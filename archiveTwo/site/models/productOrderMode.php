<?php
class OrderModel extends AbstractModel {

	private $productOrderId;
	private $quantity;
	private $productOrderId = null;
	private $productId = null;



	
	public function __construct($db, $productOrderId){
		parent::__construct($db);
		$this->productOrderId = $productOrderId;

		if ($productOrderId!== null) {
			//$this->load($orderId);
		}
	}

	public function getQuantity() {

	}

	public function getProductOrderId() {
		
	}

}