<?php

class certify
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    public function getCertifyTitleAll(){
        $sql = "SELECT id,title FROM certify_title WHERE display = 'yes'";
        $result = $this->dbcon->query($sql);

        return $result;
    }
}
