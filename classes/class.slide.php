<?php
class slide 
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    
	public function get_advertise(){
		$lan_arr = $this->lan_arr;
		$sql = "SELECT * FROM ads ORDER BY field(ad_display,'yes' )ASC ,FIELD(ad_position,'pin')DESC ,ad_position ASC,ad_id DESC ,FIELD(defaults,'yes') DESC, ad_created DESC";
		$adsList = getData::convertResultPost($this->dbcon->query($sql),'ad_id');
		return $adsList;	    
	 
	}
}
?>