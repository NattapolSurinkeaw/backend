<?php
class subscribers {
	private $dbcon;
	private $language_available;
	private $site_url = ROOT_URL;

	public function __construct()
	{
			$this->dbcon = new DBconnect();
			$this->language_available = getData::get_language_array();
	}

	public function get_maillist($getpost){
		$pagi  = $getpost['pagi'] ;
		$perpage = $getpost['amount'];
		
		if(!isset($pagi)||$pagi <= 1||$pagi == ''){
			$lim = "0,".$perpage;
		}else{
			$lim = (($pagi-1)*$perpage).','.$perpage;
		}

		$sql="SELECT * FROM email_letter ORDER BY date_regist DESC LIMIT ".$lim."";
		$result = $this->dbcon->query($sql);
		return $result;
	}
}

?>