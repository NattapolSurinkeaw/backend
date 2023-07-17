<?php
class payments
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    
    public function get_payments($_memberID)
    {
        $sql = "SELECT rp.*,m.name as m_name , b.number as b_number , b.name as b_name
                FROM record_paid as rp
                INNER JOIN members as m ON m.mem_id = rp.mem_id
                LEFT JOIN bank_info as b ON b.id = rp.bank_id
                WHERE rp.mem_id=:mem_id
                ORDER BY id DESC
            ";
        // return $this->fetchAll($sql, [":mem_id" => $_memberID]);
    }


}
