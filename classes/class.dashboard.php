<?php
class dashboard
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    /*
     * @get_post  ฟังก์ชั่นดึงข้อมูลจากตารางโพสต์เป็นข้อมูลเนื้อหาบทความ
     * สามารถค้นหาได้และรับพารามิเตอร์มาด้วย
     */
    public function get_post($getpost)
    {
        /* พารามิเตอร์สำหรับค้นหา */
        $pagi = $getpost['pagi'];
        $perpage = $getpost['amount'];
        $sort = @$getpost['sortby'];
        $cate = @$getpost['cateid'];
        $search = @$getpost['search'];
        $sort = 'date_created';

        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        $sql = "SELECT * FROM post INNER JOIN
						(SELECT id FROM post WHERE display = 'yes' GROUP BY id ORDER BY date_created DESC LIMIT 0,5) postLast
							ON postLast.id = post.id
								ORDER BY date_created DESC, postLast.id DESC, FIELD( defaults,'yes') DESC";

        return getData::convertResultPost($this->dbcon->query($sql));
    }

    public function get_rooms($getpost)
    {
        $pagi = $getpost['pagi'];
        $perpage = $getpost['amount'];
        $sort = $getpost['sortby'];
        $cate = $getpost['cateid'];
        $search = $getpost['search'];
        $sort = 'date_created';

        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        $sql = "SELECT * FROM rooms INNER JOIN (SELECT id FROM rooms WHERE display = 'yes' GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = rooms.id ORDER BY " . $sort . " DESC, p.id DESC, FIELD( defaults,'yes')DESC";

        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();
        $content = array();
        if (!empty($result)) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $content[$a['id']]['defaults'] = $a;
                }
                $content[$a['id']][$a['language']] = $a;
            }
            foreach ($content as $a) {
                foreach ($a as $b => $c) {
                    if ($b != 'defaults') {
                        if (in_array($b, $this->language_available)) {
                            $lang_info .= ',' . $c['language'];
                        }
                    }

                    if ($b == 'defaults') {
                        $ret[$c['id']] = $c;
                    }

                    if ($b == $_SESSION['backend_language']) {
                        $ret[$c['id']] = $c;
                    }

                }
                $ret[$c['id']]['lang_info'] = $lang_info;
                $lang_info = '';
            }
            return $ret;
        }
    }

    public function get_location_route()
    {
        $sql = "SELECT * FROM web_info WHERE info_display = 'yes' AND info_type = 'location_route' ORDER BY priority ASC, FIELD(defaults,'yes') DESC";
        $result = $this->dbcon->query($sql);

        $content = array();
        $ret = array();
        $ads = array();

        if ($result != false) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $content[$a['info_id']] = $a['info_title'];
                }
                if ($a['language'] == $_SESSION['language']) {
                    $content[$a['info_id']] = $a['info_title'];
                }

            }
        }
        return $content;
    }

    public function getcontentcate($cate)
    {

        $cate_id = explode(',', $cate);
        $return = '';
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM category WHERE cate_id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['cate_name'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['cate_name'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

    public function get_advertise($amount)
    {
        $sql = "SELECT * FROM ads WHERE ad_display = 'yes' ORDER BY FIELD(ad_position,'pin')DESC ,ad_position ASC,ad_id DESC ,FIELD(defaults,'yes') DESC, ad_created DESC LIMIT 0," . $amount . "";
        $result = $this->dbcon->query($sql);
        $category = array();
        $ret = array();
        $ads = array();
        if ($result !== false) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $content[$a['ad_id']]['defaults'] = $a;
                }
                $content[$a['ad_id']][$a['language']] = $a;
            }
            foreach ($content as $a) {
                foreach ($a as $b => $c) {
                    if ($b != 'defaults') {
                        if (in_array($b, $this->language_available)) {
                            @$lang_info .= ',' . $c['language'];
                        }
                    }

                    if ($b == 'defaults') {
                        $ret[$c['ad_id']] = $c;
                    }

                    if ($b == $_SESSION['backend_language']) {
                        $ret[$c['ad_id']] = $c;
                    }

                }
                $ret[$c['ad_id']]['lang_info'] = $lang_info;
                $lang_info = '';
            }

            return $ret;
        } else {
            return ('no_result');
        }
    }

    public function get_profile($id)
    {
        $sql = "SELECT * FROM user WHERE member_id =  '" . $id . "'";
        $result = $this->dbcon->query($sql);
        $ret = array();
        foreach ($result as $key => $value) {
            $ret = $value;
        }
        return $ret;
    }

    public function get_laeve_msg()
    {
        $sql = "SELECT * FROM leave_msg WHERE status NOT IN ('delete') ORDER BY submit_date DESC LIMIT 0,5";
        $result = $this->dbcon->query($sql);
        return $result;
    }

    public function get_member()
    {
        $sql = "SELECT * FROM member LIMIT 0,8";
        $result = $this->dbcon->query($sql);

        $table = "member";
        $where = "1 = 1";
        $num = $this->pagination($table, $where);
        $output = array('data' => $result, 'amount' => $num);
        return $output;
    }

    public function get_member_detail($id)
    {
        $sql = "SELECT * FROM member WHERE member_id = '" . $id . "'";
        $result = $this->dbcon->query($sql);
        return $result;
    }

    public function get_maillist($getpost)
    {
        $pagi = @$getpost['pagi'];
        $perpage = $getpost['amount'];
        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        $sql = "SELECT * FROM email_letter ORDER BY date_regist DESC LIMIT " . $lim . "";
        $result = $this->dbcon->query($sql);
        return $result;
    }

    public function pagination($table, $where)
    {
        $sql = "SELECT count(*) FROM $table WHERE $where";
        $result = $this->dbcon->runQuery($sql);
        $result->execute();
        $number_of_rows = $result->fetchColumn();
        return $number_of_rows;
    }

}
