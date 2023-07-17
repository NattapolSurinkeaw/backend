<?php
class map {
	private $dbcon;
	private $lan_arr;
	private $site_url = ROOT_URL;
	public function __construct()
	{
		$this->dbcon = new DBconnect();
		$data = new getData();
		$this->lan_arr = $data->get_language_array();
    }
    
	public function get_map($city_id){
		$sql = "SELECT * FROM map_setting WHERE city_id='".$city_id."'";
		$res=$this->dbcon->query($sql);
		return $res;
	}
}

?>