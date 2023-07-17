<?php
class category
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
			@get_category ดึงข้อมูล Category
		 */
    public function get_category($getpost)
    {
        $search = isset($getpost['search']) ? $getpost['search'] : "";
        if (!empty($search)) {
            $sql = "SELECT cate_id as 'id',category.* FROM category INNER JOIN (SELECT cate_id FROM category WHERE cate_name LIKE '%" . $search . "%' GROUP BY cate_id)p ON p.cate_id = category.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
        } else {
            $sql = "SELECT cate_id as 'id',category.* FROM category ORDER BY parent_id,cate_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
        }
        return getData::convertResultPost($this->dbcon->query($sql));
    }

    public function get_all_category($getpost, $status)
    {
        $pagi = $getpost['pagi'];
        $perpage = $getpost['amount'];
        $search = $getpost['search'];
        $lan_arr = $this->lan_arr;

        if ($status == 'show') {
            $where = "main_page = 'no' AND menu = 'no' AND display = 'yes'";
        } else if ($status == 'hidden') {
            $where = "main_page = 'no' AND display = 'no'";
        } else {
            $where = "main_page = 'no' AND menu = 'yes' AND display = 'yes'";
        }

        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        if (!empty($search)) {
            $sql = "SELECT * FROM category INNER JOIN (SELECT cate_id FROM category WHERE cate_name LIKE '%" . $search . "%' GROUP BY cate_id)p ON p.cate_id = category.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
        } else {
            $sql = "SELECT * FROM category INNER JOIN (SELECT cate_id FROM category WHERE " . $where . " GROUP BY cate_id LIMIT " . $lim . ")p ON p.cate_id = category.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
        }

        $result = $this->dbcon->query($sql);

        $category = array();
        $ret = array();

        if (!empty($result)) {
            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $category[$a['cate_id']]['defaults'] = $a;
                }
                $category[$a['cate_id']][$a['language']] = $a;
            }
        }

        foreach ($category as $a) {
            foreach ($a as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
                        $lang_info .= ',' . $c['language'];
                    }
                }

                if ($b == 'defaults') {
                    $ret[$c['cate_id']] = $c;
                }

                if ($b == $_SESSION['backend_language']) {
                    $ret[$c['cate_id']] = $c;
                }

            }
            $ret[$c['cate_id']]['lang_info'] = $lang_info;
            $lang_info = '';
        }
        return $ret;
    }
		/*
		@get_cate_radio ฟังก์ชั่นสร้าง radio สำหรับให้เลือกกรณีหน้าเพิ่มหมวดหมู่ และแก้ไขหมวดหมู่
		
		@category ค่า category ที่ดึงจากฐานข้อมูล
		@$type ประเภทของการแสดงผลในหน้าเว็บมี 2 ค่า   add,edit
        */
    public function get_cate_radio($category, $type)
    {
                $radioHtml = array();
                $cateid_all = array_keys($category); // ดึงเอา id ของ category ทั้หมดออกมาก่อน
                
                for ($i = count($cateid_all)-1; $i >= 0 ; $i--) { // ทำการ loop จากหลังสุดมาหน้าสุด  เพราะว่าด้านหน้าของอาร์จะเป็น พ่อ ของ category
                  
                    $cateId_current =  $cateid_all[$i];  //ดึง id ออกมาเพื่อว่าเอาไปใช้งานแล้วอาเรมันจะไม่เยอะเกินไปทำให้ไม่ลายตา
                    $cate_parentId =   $category[$cateId_current ]['parent_id']; //ดึง id ของพ่อออกมา
                   
                    if ($category[$cateId_current]['main_page'] != 'yes') { //เช็คว่าแสดงหน้าหลักหรือไม่
                       
                        if(!isset($radioHtml[$cate_parentId])){ $radioHtml[$cate_parentId] = ""; } //ถ้ายังไม่ได้กำหนดค่า ให้เซตค่าว่างไว้ก่อน
                       
                        if($cate_parentId  == 0){ // เช็คว่าเป็น พ่อ หรือไม่
                            $radioHtml[$cateId_current] = '
                            <div class="radio">
                                <label>
                                    <input type="radio" name="parent-id-' . $type . '" id="' . $type . '-cate-' . $cateId_current . '" value="' .  $cateId_current . '">' . $category[$cateId_current]['cate_name'] . '
                                </label>
                                <div class="radio-children">' . $radioHtml[$cateId_current] . '</div>
                            </div>';
                        }else{
                            $radioHtml[$cate_parentId] .= '
                            <div class="radio">
                                <label>
                                    <input type="radio" name="parent-id-' . $type . '" id="' . $type . '-cate-' . $cateId_current . '" value="' .  $cateId_current . '">' . $category[$cateId_current]['cate_name'] . '
                                </label>
                            </div>';
                        }
                    }
                }
               return implode(array_reverse($radioHtml));
    }
}
