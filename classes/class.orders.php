<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');


class orders {
	private $dbcon;
	private $lan_arr;
	private $site_url = ROOT_URL;
	public function __construct()
	{
		$this->dbcon = new DBconnect();
		$data = new getData();
		$this->lan_arr = $data->get_language_array();
    }

	function get_order($search,$searchby,$srch){
		if (isset($srch)) {
			if ($searchby=='st') {

				$sql = "SELECT * FROM orders INNER JOIN order_status ON orders.orderStatus=order_status.order_status_id WHERE orders.orderStatus='".$_REQUEST['srch']."' ORDER BY OrderDate DESC";

			}elseif ($searchby=='db') {
				$expArray = explode("_",$srch);
				$sql = "SELECT * FROM orders INNER JOIN order_status ON orders.orderStatus=order_status.order_status_id WHERE orders.OrderDate BETWEEN '".$expArray[0]."' AND '".$expArray[1]." 23:59:59:999' ORDER BY OrderDate DESC";
			}
		}else{
			if(isset($search)){
				$sql = "SELECT * FROM orders INNER JOIN order_status ON orders.orderStatus=order_status.order_status_id WHERE orders.OrderID='".$search."' OR orders.Name LIKE '%".$search."%' OR orders.LastName LIKE '%".$search."%' ORDER BY OrderDate DESC";
			}else{
				$sql = "SELECT * FROM orders INNER JOIN order_status ON orders.orderStatus=order_status.order_status_id INNER JOIN payment_status ON orders.paymentStatus=payment_status.payment_status_id ORDER BY OrderDate DESC";
			}
			
		}
		$result = $this->dbcon->query($sql);
		return $result;
	}

	function get_customer_detail($orderID){
		$sql = "SELECT * FROM orders 
				INNER JOIN order_status ON orders.orderStatus = order_status.order_status_id 
        		INNER JOIN payment_status ON orders.paymentStatus = payment_status.payment_status_id 
        		INNER JOIN web_info ON orders.PaymentTypeId = web_info.info_id 
				WHERE orders.OrderID='".$orderID."'";
		$result = $this->dbcon->query($sql);
		return $result;
	}

	function get_order_detail($orderID){
		$sql = "SELECT * FROM orders_detail WHERE OrderID='".$orderID."'";
		$result = $this->dbcon->query($sql);
		return $result;
	}

	function get_order_status(){
		$sql = "SELECT * FROM order_status ORDER BY order_status_id";
		$result = $this->dbcon->query($sql);
		return $result;
	}

	function get_payment_status(){
		$sql = "SELECT * FROM payment_status ORDER BY payment_status_id";
		$result = $this->dbcon->query($sql);
		return $result;
	}
	function get_status(){
		$sql = "SELECT * FROM orders_status ORDER BY status_id";
		$result = $this->dbcon->query($sql);
		return $result;
	}
}

?>