<?php
//Alex
include 'models/myShoppingCart.php';
include 'models/productOrderModel.php';

class orderModel extends  AbstractModel{

	private $orderId;
	private $orderDate;
	private $ticketNo;
	private $orderAddress;
	private $sendDate;
	private $changed;
	private $customerId;
	private $intDate;
	pricate $count=0;
	
	public function __construct($db, $orderId=null) {
		parent::__construct($db);
		$this->init();
		if ($orderId !== null) {
			$this->load($orderId);
		}	
	}
	
		private function init() {
		$this->orderId=null;
		$this->orderDate=null;
		$this->ticketNo=null;
		$this->orderAddress=null;
		$this->sendDate=null;
		$this->changed=false;
		$this->customerId=null;
	}
	
	/***************************************
 * formats the date passed into format required by 'datetime' attribute of <date> tag
 * if no intDate supplied, uses current date.
 * @param intDate integer optional
 * @return string
 ******************************************/
 
	function getDateTimeValue( $intDate = null ) {
    $strFormat = 'Y-m-d H:i:s';
    $strDate = $intDate ? date( $strFormat, $intDate ) : date( $strFormat ) ; 
    return $strDate;
}
	
	public function getOrderId() {
		return $this->orderId;
	}

	public function getOrderDate() {
		return $this->getOrderDate;
	}
	
	public function setorderId($value) {
		$this->orderId=$value;
		$this->changed=true;
	}
	public function setorderDate($value) {
		$this->orderDate=$value;
		$this->changed=true;
	}

	public function setorderAddress($value) {
		$this->orderAddress=$value;
		$this->changed=true;
	}

	public function hasChanges() {
		return $this->changed;
	}
	
	private function load($orderId) {
		$sql="select orderId,orderDate,sendDate,customerId,TicketNo from orders where orderId = $orderId";
		$rows=$this->getDB()->query($sql);
		if (count($rows)!==1) {
			throw new InvalidDataException("order  ($orderId) not found");
		}
		$row=$rows[0];
		$this->orderId=$row['orderId'];
		$this->orderDate=$row['orderDate'];
		$this->sendDate=$row['sendDate'];
		$this->customerId=$row['customerId'];
		$this->TicketNo=$row['TicketNo'];
		$this->changed=false;
	}
	
	static function createFromCart (ShoppingCart $cart, $customerID)
	) {
		$db=$this->getDB();
		if($orderId==null)
		{
		//order table
			$orderDate=$strDate;
			$sendDate=null;
			$ticketNo=null;
			$customerId=$cart->customerID;
			$orderAddress=$cart->getCustomerAddress;
			$sql="insert into order (orderDate,sendDate,customerId,TicketNo,orderAddress) values ('$orderDate','$orderAddress','$customerId','$TicketNo','$orderAddress)";				
			$this->orderId=$db->getorderID();	
		}
		//order product
		$count = $cart->getCount();
		for ($i=0 ; $i < $count ; $i++) {
			$item = $cart->getItemAt($i);
			$productID = $item->getItemCode();
			$quantity = $item->getQuantity();
			$productId = null;
			$sql="insert into productOrder (orderProductID,quantity,orderId,productId) values ('$productID','$quantity','$productID','$productId')";
			}
	}
}
?>
