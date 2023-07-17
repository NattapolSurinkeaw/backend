<?php

class manage_bank

{

    private $dbcon;

    private $language_available;

    private $site_url = ROOT_URL;



    public function __construct()

    {

        $this->dbcon = new DBconnect();

        $this->language_available = getData::get_language_array();

    }





}

