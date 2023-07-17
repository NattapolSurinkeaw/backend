<?php
class numberpredict 
{
    private static $dbcon;
    private $site_url = ROOT_URL;
    private static $language_available;
    public function __construct()
    {
        self::init();
    }
    public static function init()
    {
        self::$dbcon = new DBconnect();
    }
    
    public function format_thai_date($date){
        $newDate = date_create($date);
        $format = date_format($newDate,"d-m-Y H:i:s");  
        return $format;
    }
    public function fomat_default_date($date){
        $newDate = date_create($date);
        $format = date_format($newDate,"Y-m-d H:i:s");  
        return $format;
    }
 

}
