<?php
//Manish OrderModel
class OrderModel extends AbstractModel {

public function addOrder($data) {
		$this->event->trigger('pre.order.add', $data);

		$this->db->query("INSERT INTO `" . Database_PREFIX . "order` SET customerId  = '" . $this->db->escape((int)$data['customerId']) . "', , customerFirstName = '" . $this->db->escape($data['customerFirstName']) . "', customerLastName = '" . $this->db->escape($data['customerLastName']) . "', customerEmail = '" . $this->db->escape($data['customerEmail']) . "', isBusinessAccount = '" . $this->db->escape($data['isBusinessAccount']) . "', customerAddress = '" . $this->db->escape($data['customerAddress']) . "', customerCity = '" . $this->db->escape($data['customerCity']) . "', customerPostCode = '" . $this->db->escape($data['customerPostCode']) . "',paymentId = '" . $this->db->escape($data['paymentId']) . "' , paymentQuantity = '" . $this->db->escape($data['paymentQuantity']) . "', totalamount = '" . $this->db->escape($data['totalamount']) . "', paymentDate = '" . $this->db->escape($data['paymentDate']) . "', paymentType = '" . $this->db->escape($data['paymentType']) . "', shippingId = '" . $this->db->escape($data['shippingId']) . "', date_added = NOW(), date_modified = NOW()");

		$orderId = $this->db->getInsertID();
	
	// Products
		foreach ($data['products'] as $products) {
        	$this->db->query("INSERT INTO " . Database_PREFIX . "orderProduct SET orderId = '" . (int)$orderId . "', productId = '" . (int)$products['productID'] . "', productName = '" . $products['productName'] . "', productQuantity = '" . (int)$products['productQuantity'] . "', productPrice = '" . (decimal)$products['productPrice'] . "');
        
		    $orderProduct['orderId'] = $this->db->getInsertID();        
     
     // Payment
        foreach ($data['Payment'] as $Payment) {
			$this->db->query("INSERT INTO " . Database_PREFIX . "Payment_total SET paymentId = '" . (int)$paymentId . "', paymentQuantity = '" . $this->db->escape($payment['paymentQuantity']) . "', totalamount = '" . (decimal)$Payment['totalamount'] . "'");
		}

		$this->event->trigger('post.order.add', $orderId);

		return $orderId;

	public function editOrder($orderId, $data) {
		$this->event->trigger('pre.order.edit', $data);

		// Void the order first
		$this->addOrderHistory($orderId, 0);

		$this->db->query("UPDATE `" . Database_PREFIX . "order` customerId  = '" . $this->db->escape((int)$data['customerId']) . "', , customerFirstName = '" . $this->db->escape($data['customerFirstName']) . "', customerLastName = '" . $this->db->escape($data['customerLastName']) . "', customerEmail = '" . $this->db->escape($data['customerEmail']) . "', isBusinessAccount = '" . $this->db->escape($data['isBusinessAccount']) . "', customerAddress = '" . $this->db->escape($data['customerAddress']) . "', customerCity = '" . $this->db->escape($data['customerCity']) . "', customerPostCode = '" . $this->db->escape($data['customerPostCode']) . "',paymentId = '" . $this->db->escape($data['paymentId']) . "' , paymentQuantity = '" . $this->db->escape($data['paymentQuantity']) . "', totalamount = '" . $this->db->escape($data['totalamount']) . "', paymentDate = '" . $this->db->escape($data['paymentDate']) . "', paymentType = '" . $this->db->escape($data['paymentType']) . "', shippingId = '" . $this->db->escape($data['shippingId']) . "', date_modified = NOW() WHERE orderId = '" . (int)$orderId . "'");

		$this->db->query("DELETE FROM " . Database_PREFIX . "orderProduct WHERE orderId = '" . (int)$orderId . "'");
	
	// Products
		foreach ($data['products'] as $product) {
        	$this->db->query("INSERT INTO " . Database_PREFIX . "orderProduct SET orderId = '" . (int)$orderId . "', productId = '" . (int)$products['productID'] . "', productName = '" . $products['productName'] . "', productQuantity = '" . (int)$products['productQuantity'] . "', productPrice = '" . (decimal)$products['productPrice'] . "');

			$orderproduct = $this->db->getInsertID();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . Database_PREFIX . "order_option SET orderId = '" . (int)$orderId . "', productId = '" . (int)$productId . "', , `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
		}
       
        // Totals
		$this->db->query("DELETE FROM " . Database_PREFIX . "order_total WHERE orderId = '" . (int)$orderId . "'");

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . Database_PREFIX . "order_total SET orderId = '" . (int)$orderId . "', `orderPrice` = '" . (float)$total['orderPrice'] . "'");
		}

		$this->event->trigger('post.order.edit', $order_id);
	}

	public function deleteOrder($orderId) {
		$this->event->trigger('pre.order.delete', $orderId);

		// Void the order first
		$this->addOrderHistory($orderId, 0);

		$this->db->query("DELETE FROM `" . Database_PREFIX . "order` WHERE orderId = '" . (int)$orderId . "'");
		$this->db->query("DELETE FROM `" . Database_PREFIX . "orderProduct` WHERE orderId = '" . (int)$orderId . "'");
		$this->db->query("DELETE FROM `" . Database_PREFIX . "orderOption` WHERE orderId = '" . (int)$orderId . "'");
		$this->db->query("DELETE FROM `" . Database_PREFIX . "totalamount` WHERE orderId = '" . (int)$orderId . "'");
		}
        
public function getOrder($orderId) {
		
			return array(
				'orderId'                 => $order_query->row['orderId'],
				'customerId'              => $order_query->row['customerId'],
				'customerFirstName'       => $order_query->row['CustomerFirstName'],
				'customerLastName'        => $order_query->row['customerLastName'],
				'customerEmail'           => $order_query->row['customerEmail'],
				'isBusinessAccount'       => $order_query->row['isBusinessAccount'],
				'customerAddress'         => $order_query->row['customerAddress'],
				'customerCity'            => $order_query->row['customerCity'],
				'customerPostCode'        => $order_query->row['customerPostCode'],
                'paymentId'               => $order_query->row['paymentId'],
                'paymentQunatity'         => $order_query->row['paymentQuantity'],
                'totalamount'             => $order_query->row['totalamount'],
                'paymentDate'             => $order_query->row['paymentDate'],
                'paymentType'             => $order_query->row['paymentType'],
                'shippingId'              => $order_query->row['shippingId'],
                'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added']
			);
		} else {
			return false;
		}
	}
    }
}