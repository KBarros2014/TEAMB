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
		$sql="select customerFirstName, customerLastName, customerAddress, customerEmail  from customers ".
			 "where customerId = $customerId";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("Customer  ($customerId) not found");
		}
		$row=$rows[0];
		$this->customerFirstName=$row['customerFirstName'];
		$this->customerLastName=$row['customerLastName'];
		$this->customerAddress=$row['customerAddress'];
		$this->customerEmail=$row['customerEmail'];
		$this->customerId=$customerId;
		$this->changed=false;
	}
	
	public function save() {//the save function is to be fixed
		$id=$this->customerId;
		if ($this->customerFirstName==null || $this->customerLastName==null|| $this->customerAddress==null || $this->customerEmail==null) {
			throw new InvalidDataException('Incomplete data');
		}
		$firstName=$this->customerFirstName;
		$lastName=$this->customerLastName;
		$address=$this->customerAddress;
		$city = $this->customerCity;
		$postCode = $this->customerPostCode;
		$email =$this->customerEmail;
		
		if ($this->id===null) {
			$sql="insert into customers (customerFirstName, customerLastName, customerAddress, customerCity, customerEmail, customerEmail) values (".
						"'$firstName', '$lastName','$$address', '$city','$postCode', '$email')";
			$this->getDB()->execute($sql);
			$this->id=getDB()->insertID();	
		} else {
			$sql="update customers ".
					"set customerFirstName='$firstName', ".
			            "customerLastName='$lastName' ".
						 "customerAddress='$address' ".
						  "customerCity='$city' ".
						   "customerPostCode='$postCode' ".
						"where customerId= $customerID";
			$this->getDB()->execute($sql);
		}
		$this->hasChanges=false;
	}
	
	public function delete () {
	    $sql="delete from customers where customerId = $id";
		$rows=$this->getDB()->execute($sql);
		$this->id=$null;
		$this->changed=false;
	}
	
	}
?>
