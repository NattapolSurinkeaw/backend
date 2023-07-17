<?php
class product
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

    public function get_days()
    {
        $lan_arr = $this->lan_arr;
        //$sql = "SELECT * FROM post WHERE display = 'yes' AND category LIKE '%,10,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $sql = "SELECT * FROM post WHERE category LIKE '%,10,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['id']]['defaults'] = $a;
            }
            $category[$a['id']][$a['language']] = $a;
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
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
        //print_r($ret);
        return $ret;
    }

    public function get_power()
    {
        $lan_arr = $this->lan_arr;
        $sql = "SELECT * FROM post WHERE category LIKE '%,11,%' ORDER BY title ASC, FIELD( defaults,  'yes')DESC";
        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['id']]['defaults'] = $a;
            }
            $category[$a['id']][$a['language']] = $a;
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
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
        //print_r($ret);
        return $ret;
    }

    public function get_promotion()
    {
        $lan_arr = $this->lan_arr;
        //$sql = "SELECT * FROM post WHERE display = 'yes' AND category LIKE '%,4,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $sql = "SELECT * FROM post WHERE category LIKE '%,4,%' ORDER BY title ASC, FIELD( defaults,  'yes')DESC";
        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['id']]['defaults'] = $a;
            }
            $category[$a['id']][$a['language']] = $a;
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
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
        //print_r($ret);
        return $ret;
    }

    public function get_network()
    {
        $lan_arr = $this->lan_arr;
        //$sql = "SELECT * FROM post WHERE display = 'yes' AND category LIKE '%,8,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $sql = "SELECT * FROM post WHERE category LIKE '%,8,%' ORDER BY title ASC, FIELD( defaults,  'yes')DESC";
        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['id']]['defaults'] = $a;
            }
            $category[$a['id']][$a['language']] = $a;
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
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
        //print_r($ret);
        return $ret;
    }

    public function get_bermongkol()
    {
        $lan_arr = $this->lan_arr;
        // $sql = "SELECT * FROM post WHERE display = 'yes' AND category LIKE '%,9,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $sql = "SELECT * FROM post WHERE category LIKE '%,9,%' ORDER BY id ASC, FIELD( defaults,  'yes')DESC";
        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['id']]['defaults'] = $a;
            }
            $category[$a['id']][$a['language']] = $a;
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
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
        //print_r($ret);
        return $ret;
    }

    public function get_post($getpost)
    {
        $pagi = $getpost['pagi'];
        $perpage = $getpost['amount'];
        $sort = $getpost['sortby'];

        $cate_days = $getpost['cate_days'];
        $cate_bermongkol = $getpost['cate_bermongkol'];
        $cate_power = $getpost['cate_power'];
        $cate_promotion = $getpost['cate_promotion'];
        $cate_network = $getpost['cate_network'];

        $search = $getpost['search'];
        if ($sort != '') {
            switch ($sort) {
                case 'dc':
                    $sort = 'date_created';
                    break;
                case 'dd':
                    $sort = 'date_display';
                    break;
                case 'de':
                    $sort = 'date_edit';
                    break;
            }
        } else {
            $sort = 'date_created';
        }

        if (isset($cate_days) && $cate_days != '' && $cate_days != 0) {
            $cate_days = " AND cate_days LIKE '%," . $cate_days . ",%'";
        } else {
            $cate_days = '';
        }

        if (isset($cate_bermongkol) && $cate_bermongkol != '' && $cate_bermongkol != 0) {
            $cate_bermongkol = " AND cate_bermongkol LIKE '%," . $cate_bermongkol . ",%'";
        } else {
            $cate_bermongkol = '';
        }

        if (isset($cate_power) && $cate_power != '' && $cate_power != 0) {
            $cate_power = " AND cate_power LIKE '%," . $cate_power . ",%'";
        } else {
            $cate_power = '';
        }

        if (isset($cate_promotion) && $cate_promotion != '' && $cate_promotion != 0) {
            $cate_promotion = " AND cate_promotion LIKE '%," . $cate_promotion . ",%'";
        } else {
            $cate_promotion = '';
        }

        if (isset($cate_network) && $cate_network != '' && $cate_network != 0) {
            $cate_network = " AND cate_network LIKE '%," . $cate_network . ",%'";
        } else {
            $cate_network = '';
        }

        $status = '';
        if ($getpost['status'] != '') {
            $status = " AND display = '" . $getpost['status'] . "'";
        }
        $topic = '';
        if ($getpost['topic'] != '') {
            $status = " AND topic = '" . $getpost['topic'] . "'";
        }
/*
echo '<br><br><br><br><br><br>show1:'.$getpost['cate_days']."/".$getpost['cate_power']."/".$getpost['cate_promotion']."/".$getpost['network'];
echo '<br><br><br><br><br><br>show2:'.$cate_days."/".$cate_power."/".$cate_promotion."/".$cate_network;
 */
        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }
        $lan_arr = $this->lan_arr;

        //isset($search)&&$search != ''&&$search != 0
        if ($search != '' && isset($search)) {
            $sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%" . $search . "%' " . $cate_days . " " . $cate_bermongkol . " " . $cate_power . " " . $cate_promotion . " " . $cate_network . " " . $status . " " . $topic . " GROUP BY id ORDER BY " . $sort . ")p ON p.id = product.id ORDER BY FIELD( defaults,'yes')DESC, " . $sort . " DESC, p.id DESC ";
        } else {
            $sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%" . $search . "%' " . $cate_days . " " . $cate_bermongkol . " " . $cate_power . " " . $cate_promotion . " " . $cate_network . " " . $status . " " . $topic . " GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = product.id ORDER BY FIELD( defaults,'yes')DESC, " . $sort . " DESC, p.id DESC ";
        }

        $result = $this->dbcon->query($sql);
        // echo $sql;
        //echo "/////////".$search." ,, ".$sql;
        //print_r($result);
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
            //print_r($content);
            foreach ($content as $a) {
                foreach ($a as $b => $c) {
                    if ($b != 'defaults') {
                        if (in_array($b, $lan_arr)) {
                            $lang_info .= ',' . $c['language'];
                        }
                    }

                    if ($b == 'defaults') {
                        $ret[$c['id']] = $c;
                    }

                    if ($b == $_SESSION['backend_language']) {
                        $ret[$c['id']] = $c;
                        //print_r($ret);
                    }

                }
                $ret[$c['id']]['lang_info'] = $lang_info;
                $lang_info = '';
            }
            //print_r($ret);
            return $ret;
        }
    }

    /*public function get_category_tree($res){
    $parent = array();
    $return = array();
    foreach($res as $a){
    if(!in_array($a['parent_id'],$parent)){
    array_push($parent,$a['parent_id']);
    $return[$a['parent_id']]=array();
    $return[$a['parent_id']][$a['cate_id']]=$a;
    }else{
    $return[$a['parent_id']][$a['cate_id']]=$a;
    }
    }
    $count = count($return)-1;
    for($i = $count;$i >=0 ;$i--){
    foreach($return[$parent[$i]] as $b => $c){
    if($c['main_page']!='yes'){
    if ($c['parent_id']==0) {
    $ret[] = ['id'=>$c['cate_id'],'text'=>$c['cate_name'],'parent'=>"#"];
    }else {
    $ret[] = ['id'=>$c['cate_id'],'text'=>$c['cate_name'],'parent'=>$c['parent_id']];
    }
    }
    }
    }
    return $ret;
    }*/

    public function get_category_tree($res)
    {
        $parent = array();
        $return = array();
        foreach ($res as $a) {
            if (!in_array($a['category'], $parent)) {
                array_push($parent, $a['category']);
                $return[$a['category']] = array();
                $return[$a['category']][$a['id']] = $a;
            } else {
                $return[$a['category']][$a['id']] = $a;
            }
        }
        $count = count($return) - 1;
        for ($i = $count; $i >= 0; $i--) {
            foreach ($return[$parent[$i]] as $b => $c) {
                if ($c['main_page'] != 'yes') {
                    if ($c['category'] == 0) {
                        $ret[] = ['id' => $c['id'], 'text' => $c['title'], 'parent' => "#"];
                    } else {
                        $ret[] = ['id' => $c['id'], 'text' => $c['title'], 'parent' => $c['category']];
                    }
                }
            }
        }
        return $ret;
    }

    public function getcontentcate_cate_days($cate)
    {
        $cate_id = explode(',', $cate);
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM post WHERE id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['title'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['title'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

    public function getcontentcate_cate_bermongkol($cate)
    {
        $cate_id = explode(',', $cate);
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM post WHERE id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['title'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['title'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

    public function getcontentcate_cate_power($cate)
    {
        $cate_id = explode(',', $cate);
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM post WHERE id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['title'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['title'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

    public function getcontentcate_cate_promotion($cate)
    {
        $cate_id = explode(',', $cate);
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM post WHERE id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['title'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['title'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

    public function getcontentcate_cate_network($cate)
    {
        $cate_id = explode(',', $cate);
        for ($i = 1; $i < count($cate_id) - 1; $i++) {
            $sql = "SELECT * FROM post WHERE id =  '" . $cate_id[$i] . "' ORDER BY field(defaults,'yes')DESC";
            $result = $this->dbcon->query($sql);
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $catename = $a['title'];
                }
                if ($a['language'] == $_SESSION['backend_language']) {
                    $catename = $a['title'];
                }
            }
            $return .= $catename . ', ';
        }
        return $return;
    }

   
}
