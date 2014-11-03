<?php
//Alex
class orderModel extends  AbstractModel{

	private $orderId;
	private $orderPrice;
	private $orderDate;
	private $changed;

	public function __construct($db, $orderId=null) {
		parent::__construct($db);
		$this->init();
		if ($orderId !== null) {
			$this->load($orderId);
		}	
	}
	
		private function init() {
		$this->orderId=null;
		$this->orderPrice=null;
		$this->changed=false;		
	}
	
	public function getOrderId() {
		return $this->orderId;
	}

	public function getOrderPrice() {
		return $this->orderPrice;
	}

	
	public function setorderId($value) {
		$this->orderId=$value;
		$this->changed=true;
	}
	public function setorderPrice($value) {
		$this->orderPrice=$value;
		$this->changed=true;
	}


	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($orderId) {
	
	}
	
	public function save() {//the save function is to be fixed
		
	}
	
	public function delete () {
	    
	}
	
	}
?>
