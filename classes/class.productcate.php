<?php
class productcate
{
    private $dbcon;
    private $lan_arr;
    private $site_url = ROOT_URL;
    private $_data;
    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->_data = new getData();
        $this->lan_arr = $this->_data->get_language_array();
    }
    public function get_product_category($getpost)
    {  
    	$search = $getpost['search'];
		$lan_arr = $this->lan_arr; 
		if(isset($getpost['cate_id'] )){ 
			$cate_id = $getpost['cate_id'];
			if(!empty($cate_id) AND $cate_id != 0 ){
					$sql = 'SELECT * FROM products_category WHERE parent_id = "'.$cate_id.'"  AND cate_id != 1 ORDER BY parent_id, level, priority asc, position asc, FIELD( defaults,  "yes")DESC';
			}else{ 
					$sql = "SELECT * FROM products_category WHERE parent_id = 0 AND cate_id != 1 ORDER BY parent_id, level, priority asc, position asc, FIELD( defaults,  'yes')DESC";
			}
		}else{
				$sql = "SELECT * FROM products_category WHERE cate_id != 1 ORDER BY parent_id, level, priority asc, position asc, FIELD( defaults,  'yes')DESC"; 
		} 
		if (!empty($search)) {
			$sql = "SELECT * FROM products_category INNER JOIN (SELECT cate_id FROM products_category WHERE cate_name LIKE '%" . $search . "%' GROUP BY cate_id)p ON p.cate_id = products_category.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		 } 
		$result = $this->dbcon->query($sql); 
        $category = array();
        $ret = array(); 
        foreach ($result as $a) {
            if ($a['defaults'] == 'yes') {
                $category[$a['cate_id']]['defaults'] = $a;
            }
            $category[$a['cate_id']][$a['language']] = $a;
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
    public function get_productcate_radio($category, $type) 
    { 
        $radioHtml = array();
        $cateid_all = array_keys($category); // ดึงเอา id ของ category ทั้หมดออกมาก่อน 
        for ($i = count($cateid_all) - 1; $i >= 0; $i--) { // ทำการ loop จากหลังสุดมาหน้าสุด  เพราะว่าด้านหน้าของอาร์จะเป็น พ่อ ของ category 
			$cateId_current = $cateid_all[$i]; //ดึง id ออกมาเพื่อว่าเอาไปใช้งานแล้วอาเรมันจะไม่เยอะเกินไปทำให้ไม่ลายตา
            $cate_parentId = $category[$cateId_current]['parent_id']; //ดึง id ของพ่อออกมา 
            //  echo $category[$cateId_current]['cate_name'].'/'; 
			if ($category[$cateId_current]['main_page'] != 'yes') { //เช็คว่าแสดงหน้าหลักหรือไม่ 
				if (!isset($radioHtml[$cate_parentId])) {$radioHtml[$cate_parentId] = "";} //ถ้ายังไม่ได้กำหนดค่า ให้เซตค่าว่างไว้ก่อน
                if ($cate_parentId == 0) { // เช็คว่าเป็น พ่อ หรือไม่ 
                    $radioHtml[$cateId_current] = '
						<div class="radio">
							<label>
								<input type="radio" name="parent-id-' . $type . '" id="' . $type . '-cate-' . $cateId_current . '" value="' . $cateId_current . '">' . $category[$cateId_current]['cate_name'] . '
							</label>
							<div class="radio-children">' . $radioHtml[$cateId_current] . '</div>
						</div>'; 
                }else{ 
                    $radioHtml[$cate_parentId] .= '
						<div class="radio"> 
							<label>
							<i class="fa fa-th-large" aria-hidden="true"></i>
									' . $category[$cateId_current]['cate_name'] . '
							</label>
						</div>'; 
                }
            }
         } 
     	return implode(($radioHtml));
	 }

 	public function get_all_Categories(){
			$sql = 'SELECT * FROM products_category WHERE cate_id != 1';
			$result=$this->dbcon->query($sql);
			$category=array();
			$ret=array();
	
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$category[$a['cate_id']]['defaults']=$a;
				}
				$category[$a['cate_id']][$a['language']]=$a;
			}
	
			foreach($category as $a){
				foreach($a as $b => $c){
					if($b != 'defaults')
						if(in_array($b,$lan_arr))
							$lang_info .= ','.$c['language'];
					if($b == 'defaults')
						$ret[$c['cate_id']]=$c;
					if($b == $_SESSION['backend_language'])
						$ret[$c['cate_id']]=$c;
				}
				$ret[$c['cate_id']]['lang_info'] = $lang_info;
				$lang_info = '';
			}
			return $ret;	

	 } 
	public function get_productSubCateSlc($getpost){
			$sort = 'WHERE parent_id != 0 ';
			if($getpost['id'] != 'ทั้งหมด'){ 	$sort .= 'WHERE parent_id = '.$getpost['id'].' '; 	}
				$lan_arr = $this->lan_arr;
				$sql = 'SELECT * FROM products_category '.$sort.' ORDER BY level, priority ASC, position ASC, FIELD( defaults,  "yes")DESC ';
				$result=$this->dbcon->query($sql);
				$category=array();
				$ret=array();
				$lang_info ='';
				foreach($result as $a){
					if($a['defaults']=='yes'){
						$category[$a['cate_id']]['defaults']=$a;
					}
					$category[$a['cate_id']][$a['language']]=$a;
				}
	
				foreach($category as $a){
					foreach($a as $b => $c){
					
						if($b != 'defaults')
							if(in_array($b,$lan_arr))
								$lang_info .= ','.$c['language'];
						if($b == 'defaults')
							$ret[$c['cate_id']]=$c;
						if($b == $_SESSION['backend_language'])
							$ret[$c['cate_id']]=$c;
					}
	
					$ret[$c['cate_id']]['lang_info'] = $lang_info;
					$lang_info = '';
				}
	
			return $ret;	
 	 }
	
		
		
	public function get_productCateSlc(){
 
		$lan_arr = $this->lan_arr;
		$sql = "SELECT * FROM products_category WHERE parent_id = 0 ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		$result=$this->dbcon->query($sql);
		$category=array();
		$ret=array();
		$lang_info ='';
		foreach($result as $a){
			if($a['defaults']=='yes'){
				$category[$a['cate_id']]['defaults']=$a;
			}
			$category[$a['cate_id']][$a['language']]=$a;
		}

		foreach($category as $a){
			foreach($a as $b => $c){
			
				if($b != 'defaults')
					if(in_array($b,$lan_arr))
						$lang_info .= ','.$c['language'];
				if($b == 'defaults')
					$ret[$c['cate_id']]=$c;
				if($b == $_SESSION['backend_language'])
					$ret[$c['cate_id']]=$c;
			}

			$ret[$c['cate_id']]['lang_info'] = $lang_info;
			$lang_info = '';
		}
		return $ret;	
	}

	//schwein this product

	public function get_productcategory(){
		$lan_arr = $this->lan_arr;
		$sql = "SELECT * FROM products_category ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		$result=$this->dbcon->query($sql);

		$category=array();
		$ret=array();
		$lang_info ='';
		foreach($result as $a){
			if($a['defaults']=='yes'){
				$category[$a['cate_id']]['defaults']=$a;
			}
			$category[$a['cate_id']][$a['language']]=$a;
		}

		foreach($category as $a){
			foreach($a as $b => $c){
			
				if($b != 'defaults')
					if(in_array($b,$lan_arr))
						$lang_info .= ','.$c['language'];
				if($b == 'defaults')
					$ret[$c['cate_id']]=$c;
				if($b == $_SESSION['backend_language'])
					$ret[$c['cate_id']]=$c;
			}

			$ret[$c['cate_id']]['lang_info'] = $lang_info;
			$lang_info = '';
		}
		return $ret;	
	} 
	public function get_productpost($getpost){
 
		$pagi  = $getpost['pagi'] ;
		$perpage = $getpost['amount'];
		$sort = $getpost['sortby'];
		$cate = $getpost['cateid'];
		$search = $getpost['search'];
	
		if( $sort != ''){
			switch($sort){
				case'dc':
 					$sort = 'date_created';
				break;
				case'dd':
 					$sort = 'date_display';
				break;
				case'de':
 					$sort = 'date_edit';
				break;
				case'df':
					 $sort = 'priority';
				break;
			}
		}else { 
			$sort='date_created';
		}
		//ทำ pagination
		 $page = 1; 
		 if(!empty($getpost['page'])){ $page = $getpost['page']; }
		 $limit = 10;
		 $max = $page * $limit;
		 $min =  $max - $limit;
		 $lim = '  '.$min.','.$limit.' ';

		$cate_src = '';
		if(isset($cate)&&$cate != ''&&$cate != 0){
			$cate_src = " AND category LIKE '%,".$cate.",%'";
		}
		
		
		$status = '';
		if(isset($getpost['status'])&&$getpost['status'] != ''){
			$status = " AND display = '".$getpost['status']."'";
		}
			
		// if(!isset($pagi)||$pagi <= 1||$pagi == ''){
		// 	$lim = "0,".$perpage;
		// }else{
		// 	$lim = (($pagi-1)*$perpage).','.$perpage;
		// }
		$lan_arr = $this->lan_arr;

		if($sort == "priority"){
			$sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC LIMIT ".$lim.")p ON p.id = product.id ORDER BY ".$sort." ASC, p.id DESC, FIELD( defaults,'yes')DESC";
		}else{
			$sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC LIMIT ".$lim.")p ON p.id = product.id ORDER BY ".$sort." DESC, p.id DESC, FIELD( defaults,'yes')DESC";
		}
		
		$result = $this->dbcon->query($sql);

		if($sort == "priority"){
			$sqlNum = "SELECT `title` FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC )p ON p.id = product.id ORDER BY ".$sort." ASC, p.id DESC, FIELD( defaults,'yes')DESC";
		}else{
			$sqlNum = "SELECT `title` FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC )p ON p.id = product.id ORDER BY ".$sort." DESC, p.id DESC, FIELD( defaults,'yes')DESC";
		}
		$rescount =$this->dbcon->query($sqlNum);
		$category=array();
		$ret=array();
		$content = array();
		if(!empty($result)){
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$content[$a['id']]['defaults']=$a;
				}
				$content[$a['id']][$a['language']]=$a;
				
			}
			foreach($content as $a){
				
				foreach($a as $b => $c){
					if($b != 'defaults')
						if(in_array($b,$lan_arr))
							$lang_info .= ','.$c['language'];
					if($b == 'defaults')
						$ret[$c['id']]=$c;
					if($b == $_SESSION['backend_language'])
						$ret[$c['id']]=$c;
				}
				$ret[$c['id']]['lang_info'] = $lang_info;
				$lang_info = '';
			}
				$ret['items'] = count($rescount);
			return $ret;
		}
	}
	

	public function get_category_tree($res){
		
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
	}

	public function getcontentcate($cate){
		$cate_id = explode(',',$cate);
		for($i = 1;$i < count($cate_id)-1;$i++){
			$sql = "SELECT * FROM products_category WHERE cate_id =  '".$cate_id[$i]."' ORDER BY field(defaults,'yes')DESC";
			$result = $this->dbcon->query($sql);
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$catename = $a['cate_name'];
					$cateid = $a['cate_id'];
				}
				if($a['language']==$_SESSION['backend_language']){
					$catename = $a['cate_name'];
					$cateid = $a['cate_id'];
				}
			}
			#game
			//$return['catename'] .= $catename.', ';
			$return['catename'] .= '<a href = "'.SITE_URL.'?page=contents&bycate='.$cateid.'">'.$catename.' <i class="fa fa-link fa-pulse linker" aria-hidden="true"></i></a> , ';
		}
		return $return;
	}







	
	

}
