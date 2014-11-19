<?php
class OrderModel extends AbstractModel {
	private $orderId;
	private $orderDate;
	private $orderIsSuccess = null;
	private $customerId = null;
	private $orderProducts;

	public function __construct($db, $orderId=null){
		parent::__construct($db);
		$this->orderId = $orderId;
		if ($orderId !== null) {
			$this->load($orderId);
		}
	}
	public function getOrderId() {
		return $this->orderId;
	}
	public function getOrderDate() {
		return $this->orderDate;
	}
	public function getOrderIsSuccess() {
		return $this->orderIsSuccess;
	}
	public function getCustomerId(){
		return $this->customerId;
	}	
	private function load($orderId) {
		if (!is_int($orderId) && !ctype_digit($orderId)) {
			throw new InvalidDataException("Invalid order ID ($orderId)");
		}
		$sql="select * from orders where orderId = $orderId";
		$rows=$this->getDB()->query($sql);
		
		if (count($rows)!==1) {
			throw new InvalidDataException("Order ID $orderId not found");
		}
		
		$row=$rows[0];
		$this->orderDate = $row['orderDate'];
		$this->orderIsSuccess = $row['orderIsSuccess'];
		$this->orderId = $orderId;
		$this->customerId = $row['customerId'];
	}
	public function getOrderProducts($orderId){
		if (!is_int($orderId) && !ctype_digit($orderId)) {
			throw new InvalidDataException("Invalid order ID ($orderId)");
		}
		$sql="select * from orderproducts where orderId = $orderId";
		$rows=$this->getDB()->query($sql);

		if (count($rows) < 1) {
			throw new InvalidDataException("Order products not found");
		}
		
		return $rows;
	}
}