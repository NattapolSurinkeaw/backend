<?php
class product_sel
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
       $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    public function get_product_category() 
    {
        $sql = "SELECT * FROM product_cate WHERE display =  'yes' ORDER BY priority ASC ";
        return ($this->dbcon->query($sql));
    }

    public function get_product_subcategory($cateid) 
    {   
        $id = (isset($cateid) && $cateid != 0)? " AND product_cate = '".$cateid."' ":""; 
        $sql = "SELECT * FROM product_sub_cate WHERE display =  'yes' ".$id."  ORDER BY priority ASC ";  
        return ($this->dbcon->query($sql)); 
    }
 
    public function get_product_all($getpost){ 
        $sql = "SELECT p.* , DATEDIFF(p.auction_time,now()) as time_bid  
                        ,pc.name as pc_name  
                        ,psc.name as psc_name  
                        ,c.url    
                        ,m.star_yellow    
                        ,a.title as status_desc  
                        ,m.name as m_name  
                        ,m.phone as m_phone   
                        ,(SELECT bidder_id FROM record_bid WHERE product_id = p.p_id  
                         ORDER BY price_current  LIMIT 0,1 ) as bidder  
                FROM product as p  
                INNER JOIN members as m ON m.mem_id = p.owner_id  
                INNER JOIN product_cate as pc ON pc.id = p.p_cate_id 
                INNER JOIN product_sub_cate as psc ON psc.id = p.p_sub_cate_id   
                INNER JOIN category as c ON c.cate_id = 12  
                INNER JOIN auction_status as a ON  a.id = p.status         
                WHERE p.id != 0 "; 

        if(!empty($getpost)){  
            foreach($getpost as $key => $val){  
                if($val != "" && $val != "0"){ 
                    switch($key){
                        case'product_cate':
                            $sql .= ' AND pc.id = '.$val;
                        break;
                        case'product_subcate':
                            $sql .= ' AND psc.id = '.$val;
                        break;
                        case'pin': 
                            $sql .= ' AND p.promote = "'.$val.'" ';
                        break;
                        case'status':
                            $sql .= ' AND p.status = "'.$val.'" ';
                        break;
                        case'search':
                            $sql .= ' AND ( psc.id = "'.$val.'" 
                                        OR  p.name LIKE "%'.$val.'%"  
                                        OR  m.name LIKE "%'.$val.'%"  
                                     )';
                        break; 
                    }  
                } 
            }
        }   
        
        
        $sort = strtolower(trim($getpost['sortby']));
        $sql .= ' ORDER BY '; 
        switch ($sort) { 
            case"dc":   $sql .= ' p.price_current DESC ';    break;
            case"de":   $sql .= ' p.price_current ASC ';    break;
            case"dd":   $sql .= ' p.date_update DESC ';    break;
            case"df":   $sql .= ' m.star_yellow DESC ';    break;
            default:    $sql .= ' p.promote DESC, price_current DESC'; break;
        }  
   
        $product_all = $this->dbcon->query($sql);  
         
        return $product_all;
    }
    public function get_product_status()
    {
        $sql ='SELECT * FROM auction_status ORDER BY id ';
        $result = $this->dbcon->query($sql);
        return $result;
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

    public function get_product_brand(){
        $sql = "SELECT * FROM product_bran";
        return $this->dbcon->query($sql);
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
				$return_link .= '<a href = "' . SITE_URL . '?page=contents&bycate=' .$cat['cate_id'] . '">' .$cat['cate_name'] . '</a> , ';
			}
			/* @rtrim  ฟังก์ชั่นลบตัวอักษรที่ต้องการออกจากทางขวาของข้อความ */
			return rtrim($return_link,", ");
    }

   

    public function get_product_subcate($getpost){
        $cate_id = (isset($getpost['product_cate']) && $getpost['product_cate'] != 0)? " AND product_cate = ".$getpost['product_cate']." ": "";
        $sql = "SELECT id,name FROM product_sub_cate WHERE display = 'yes' ".$cate_id." ORDER BY priority ASC  ";
        $product_cate = $this->dbcon->query($sql); 
        return $product_cate;
    }
    public function get_product_cate_all(){
        $sql = "SELECT id , name FROM product_cate";
        $product_cate = $this->dbcon->query($sql);
        return $product_cate;
    }

    public function get_product_cate_name($cate_id){
        $sql = "SELECT name FROM product_cate WHERE id = '".$cate_id."' AND display = 'yes'";
        $product_cate = $this->dbcon->fetch($sql);
        return $product_cate['name'];
    }
    public function get_post_product($getpost){
    
        $pagi = isset($getpost['pagi']) ? $getpost['pagi'] : "";
        $perpage = isset($getpost['amount']) ? $getpost['amount'] : "";
        $sort = isset($getpost['sortby']) ? $getpost['sortby'] : "";
        $cate = isset($getpost['cateid']) ? $getpost['cateid'] : "";
        $subcate = isset($getpost['subcate_id']) ? $getpost['subcate_id'] : "";
        $search = isset($getpost['search']) ? $getpost['search'] : "";
        $pin = isset($getpost['pin']) ? $getpost['pin'] : "";

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
            $cate_src = " AND p_cate_id in (" . $cate . ")";
        }

        if(isset($subcate) && $subcate != '' && $subcate != 0 ){
            $cate_src = " AND p_sub_cate_id in (" . $cate . ")";
        }

        $status = '';
        if (isset($getpost['status']) && $getpost['status'] != '') {
            $status = " AND display = '" . $getpost['status'] . "'";
        }
        $poduct_pin = '';
        if (isset($getpost['pin']) && $getpost['pin'] != '') {
            $poduct_pin = " AND pin = '" . $getpost['pin'] . "'";
        }

        if (!isset($pagi) || $pagi <= 1 || $pagi == '') {
            $lim = "0," . $perpage;
        } else {
            $lim = (($pagi - 1) * $perpage) . ',' . $perpage;
        }

        $product_cate = '';
        if (isset($getpost['cateid']) && $getpost['cateid'] != '') {
            $product_cate = " AND id = '" . $getpost['cateid'] . "'";
        }
 
            # ส่วนของสินค้า ต้องการเช็คการเชื่อมข้อมูลจากหน้าบ้าน

        if ($sort == "priority") {
            $sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%" . $search . "%' " . $cate_src . " " . $status . " " . $product_cate . " ".$poduct_pin ." GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = post.id ORDER BY " . $sort . " ASC, p.id DESC, FIELD( defaults,'yes')DESC";
        } else {
            $sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%" . $search . "%' " . $cate_src . " " . $status . " " . $product_cate . " ".$poduct_pin ." GROUP BY id ORDER BY " . $sort . " DESC LIMIT " . $lim . ")p ON p.id = post.id ORDER BY " . $sort . " DESC, p.id DESC, FIELD( defaults,'yes')DESC";
        }
        

       return getData::convertResultPost($this->dbcon->query($sql));
    }
}
