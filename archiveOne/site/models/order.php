<?php
class OrderModel extends AbstractModel {

	private $orderId;
	private $orderDate;
	private $orderIsSuccess = null;
	private $customerId = null;

	public function __construct($db){
		die('<pre>'.print_r($db, true));
	}

	
}