<?php
//Alex
include 'models/myShoppingCart';
class orderModel extends  AbstractModel{

	private $orderId;
	private $orderDate;
	private $ticketNo;
	private $orderAddress;
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
		$this->orderDate=null;
		$this->ticketNo=null;
		$this->orderAddress=null;
		$this->changed=false;		
	}
	
	/***************************************
 * formats the date passed into format required by 'datetime' attribute of <date> tag
 * if no intDate supplied, uses current date.
 * @param intDate integer optional
 * @return string
 ******************************************/
	function getDateTimeValue( $intDate = null ) {
    $strFormat = 'Y-m-d\TH:i:s.uP';
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
		//$this->customerId=$customerId;
		$this->changed=false;
	}
	
	}
?>
