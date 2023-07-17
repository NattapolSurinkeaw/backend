<?php
class car_config
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    public function get_carTypeList(){
        $sql = "select * from car_type";
        $return =  $this->dbcon->query($sql);            
        return $return;
    }

    public function get_brandList(){
        $sql = "select * from car_brand";
        $return =  $this->dbcon->query($sql);            
        return $return;
    }

    public function get_colorList(){
        $sql = "select * from car_color";
        $return =  $this->dbcon->query($sql);            
        return $return;
    }

}

?>