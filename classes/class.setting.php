<?php
class setting
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {	
		getData::init();
		$this->dbcon = new DBconnect();
        //$this->language_available = getData::get_language_array();
    }

    public function get_web_info_type()
    {
        $sql = "SELECT * FROM web_info_type ORDER BY id ASC, FIELD(defaults,'yes') DESC";
        $result = $this->dbcon->query($sql);
        //ฟังก์ชั่น current ดึงข้อมูลอาร์เรตัวแรก
        return getData::convertResultPost($result);
    }

    public function get_web_info_type_edit($id)
    {
        $sql = "SELECT * FROM web_info_type WHERE id = '" . $id . "' ORDER BY id ASC, FIELD(defaults,'yes') DESC";
        $result = $this->dbcon->query($sql);
        //ฟังก์ชั่น current ดึงข้อมูลอาร์เรตัวแรก
        return current(getData::convertResultPost($result));
    }

    public function get_feature()
    {
        $sql = "SELECT * FROM feature";
        $res = $this->dbcon->query($sql);
        return $res;
    }

    public function get_all_language()
    {
        $sql = "SELECT * FROM language ORDER BY id ASC";
        $res = $this->dbcon->query($sql);
        return $res;
    }

    public function get_language($id)
    {
        $sql = "SELECT * FROM language WHERE id = '" . $id . "'";
        $res = $this->dbcon->query($sql);
        return $res;
    }

    public function get_all_ads_type()
    {
        $sql = "SELECT * FROM ad_type ORDER BY id ASC";
        $res = $this->dbcon->query($sql);
        return $res;
    }

    public function get_ads_type($id)
    {
        $sql = "SELECT * FROM ad_type WHERE id = '" . $id . "'";
        $res = $this->dbcon->query($sql);
        return $res;
    }
}
