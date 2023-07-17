<?php
class contact
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    public function get_laeve_msg($getpost)
    {
        $pagi = $getpost['pagi'];
        $perpage = $getpost['amount'];
        $where = $getpost['where'];
        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }
        $sql = "SELECT * FROM leave_msg WHERE status NOT IN ('delete') " . $where . " ORDER BY submit_date DESC LIMIT " . $lim . "";
       // echo $sql;
        $result = $this->dbcon->query($sql);
        return $result;
    }
}
