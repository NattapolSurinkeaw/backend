<?php

class langconfig {
	private $dbcon;
	private $lan_arr;
	private $site_url = ROOT_URL;
	public function __construct()
	{
		$this->dbcon = new DBconnect();
    }

}
?>