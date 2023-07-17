<?php
class contact_sel
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
       $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    public function get_category($catid)
    {
        $sql = "SELECT  cate_id as 'id',category.* FROM category WHERE cate_id = ".$catid." ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
        return getData::convertResultPost($this->dbcon->query($sql));
    }

    public function get_post($getpost)
    {
        $pagi = isset($getpost['pagi']) ? $getpost['pagi'] : "";
        $perpage = isset($getpost['amount']) ? $getpost['amount'] : "";
        $sort = isset($getpost['sortby']) ? $getpost['sortby'] : "";
        $cate = isset($getpost['cateid']) ? $getpost['cateid'] : "";
        $search = isset($getpost['search']) ? $getpost['search'] : "";

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
                case 'df':
                    $sort = 'priority';
                    break;
            }
        } else {
            $sort = 'date_created';
        }

        $cate_src = '';
        if (isset($cate) && $cate != '' && $cate != 0) {
            $cate_src = " AND category in (" . $cate . ")";
        }

        $status = '';
        if (isset($getpost['status']) && $getpost['status'] != '') {
            $status = " AND display = '" . $getpost['status'] . "'";
        }

        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        if ($sort == "priority") {
            $sql = "SELECT * FROM post INNER JOIN (SELECT id FROM post WHERE title LIKE '%" . $search . "%' " . $cate_src . " " . $status . " GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = post.id ORDER BY " . $sort . " ASC, p.id DESC, FIELD( defaults,'yes')DESC";
        } else {
            $sql = "SELECT * FROM post INNER JOIN (SELECT id FROM post WHERE title LIKE '%" . $search . "%' " . $cate_src . " " . $status . " GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = post.id ORDER BY " . $sort . " DESC, p.id DESC, FIELD( defaults,'yes')DESC";
        }
        
       return getData::convertResultPost($this->dbcon->query($sql));
    }

    public function get_category_tree($res)
    {
        $parent = array();
        $return = array();
        foreach ($res as $a) {
            if (!in_array($a['parent_id'], $parent)) {
                array_push($parent, $a['parent_id']);
                $return[$a['parent_id']] = array();
                $return[$a['parent_id']][$a['cate_id']] = $a;
            } else {
                $return[$a['parent_id']][$a['cate_id']] = $a;
            }
        }
        $count = count($return) - 1;
        for ($i = $count; $i >= 0; $i--) {
            foreach ($return[$parent[$i]] as $b => $c) {
                if ($c['main_page'] != 'yes') {
                    if ($c['parent_id'] == 0) {
                        $ret[] = ['id' => $c['cate_id'], 'text' => $c['cate_name'], 'parent' => "#"];
                    } else {
                        $ret[] = ['id' => $c['cate_id'], 'text' => $c['cate_name'], 'parent' => $c['parent_id']];
                    }
                }
            }
        }
        return $ret;
    }
	/*@getcontentcate  ฟังก์ชั่นดึงข้อมูลหมวดหมู่ของบทความ
	  @categoryId  พารามิเตอร์ที่ส่งมาจะเป็น id ของบทความส่งมาหลายตัวก็ได้ เช่น 1,5,3
	*/
    public function getcontentcate($categoryId)
    {
			$sql = "SELECT cate_id as 'id',category.* FROM category WHERE cate_id in  (" .trim($categoryId,","). ") ORDER BY field(defaults,'yes')DESC";
			$category = getData::convertResultPost($this->dbcon->query($sql)); 
			$return_link = "";
			foreach ($category as $cat) {
				$return_link .= '<a href = "' . SITE_URL . '?page=contents&bycate=' .$cat['cate_id'] . '">' .$cat['cate_name'] . ' <i class="fa fa-link fa-pulse linker" aria-hidden="true"></i></a> , ';
			}
			/* @rtrim  ฟังก์ชั่นลบตัวอักษรที่ต้องการออกจากทางขวาของข้อความ */
			return rtrim($return_link,", ");
    }

    public function upload_images_thumb($new_folder,$fieldImg = 'images')
    {
        $files = array();
        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }
        umask($oldmask);
        $images = array();
        $totalFile = count($_FILES[$fieldImg]['name']);
        for ($i = 0; $i < $totalFile; $i++) {
            $handle = new Upload(
                array(
                    'name' => $_FILES[$fieldImg]['name'][$i],
                    'type' => $_FILES[$fieldImg]['type'][$i],
                    'tmp_name' => $_FILES[$fieldImg]['tmp_name'][$i],
                    'error' => $_FILES[$fieldImg]['error'][$i],
                    'size' => $_FILES[$fieldImg]['size'][$i],
                )
            );
            if ($handle->uploaded) {
                $newname = uniqid() . self::randomString(5); // . microtime(true)
                $ext = strchr($_FILES[$fieldImg]['name'][$i], ".");

                $handle->file_new_name_body = $newname;
                $handle->Process($new_folder);

                $images[$i] = 'upload/' . date('Y') . '/' . date('m') . '/' . $newname . strtolower($ext);
                $handle->Clean();
            }
        }
        return $images;
    }
    
     public function randomString($length = 5)
    { //กำหนดความยาวข้อความที่ต้องการ
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
