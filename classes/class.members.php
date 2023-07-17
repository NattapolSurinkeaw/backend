<?php
class members
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    
    public function get_province()
    {
        $sql = "SELECT * FROM province ORDER BY id ASC";
        return $this->dbcon->fetchAll($sql, []);
    }


}
