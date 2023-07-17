<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');


class profile {
	private $dbcon;
	private $site_url = ROOT_URL;
	public function __construct()
	{
		$this->dbcon = new DBconnect();
    }
	public function get_profile($id){
		$sql = "SELECT * FROM user WHERE member_id =  '".$id."'";
		$result = $this->dbcon->query($sql);
		$ret=array();
		foreach($result as $key => $value){
			$ret = $value;	
		}
		return $ret;
	}

}

?>