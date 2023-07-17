<?php   


use Dompdf\FrameDecorator\Page;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;  

use \PhpOffice\PhpSpreadsheet\IOFactory; 
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class productber {  

	private static $dbcon;
    private $site_url = ROOT_URL; 
    public function __construct()
     {
        self::init();
     }

    public static function init()
     {
        self::$dbcon = new DBconnect();
	 }
	public function get_productcategory(){
		$lan_arr = self::$lan_arr;
		$sql = "SELECT * FROM products_category ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes') DESC";
		$result=self::$dbcon->query($sql);

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
 
		$lan_arr = self::$lan_arr;
		$sql = "SELECT * FROM products_category WHERE parent_id = 0 ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		$result=self::$dbcon->query($sql);
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
	public function get_productSubCateSlc($getpost){
		$sort = 'WHERE parent_id != 0 ';
		if($getpost['id'] != 'ทั้งหมด'){ 	$sort .= 'WHERE parent_id = '.$getpost['id'].' '; 	}
			$lan_arr = self::$lan_arr;
			$sql = 'SELECT * FROM products_category '.$sort.' ORDER BY level, priority ASC, position ASC, FIELD( defaults,  "yes")DESC ';
			$result=self::$dbcon->query($sql);
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
			 
		$lan_arr = self::$lan_arr;

		if($sort == "priority"){
			$sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC LIMIT ".$lim.")p ON p.id = product.id ORDER BY ".$sort." ASC, p.id DESC, FIELD( defaults,'yes')DESC";
		}else{
			$sql = "SELECT * FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC LIMIT ".$lim.")p ON p.id = product.id ORDER BY ".$sort." DESC, p.id DESC, FIELD( defaults,'yes')DESC";
		}
		
		$result = self::$dbcon->query($sql);

		if($sort == "priority"){
			$sqlNum = "SELECT `title` FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC )p ON p.id = product.id ORDER BY ".$sort." ASC, p.id DESC, FIELD( defaults,'yes')DESC";
		}else{
			$sqlNum = "SELECT `title` FROM product INNER JOIN (SELECT id FROM product WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC )p ON p.id = product.id ORDER BY ".$sort." DESC, p.id DESC, FIELD( defaults,'yes')DESC";
		}
		$rescount =self::$dbcon->query($sqlNum);
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
			$result = self::$dbcon->query($sql);
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
			$return['catename'] .= '<a href = "'.SITE_URL.'?page=contents&bycate='.$cateid.'">'.$catename.' <i class="fa fa-link fa-pulse linker" aria-hidden="true"></i></a> , ';
		}
		return $return;
	 }
	public function priorityControl($getpost){ 
		if(isset($getpost['old'])){ $priority_old = $getpost['old']; }else{ $priority_old ='0';}
		if(isset($getpost['id'])){ $id = $getpost['id']; }else{ $id = 'emp'; }
		$priority_new = $getpost['new']; 
		if($priority_old != $priority_new && $id != 'emp'){
			$set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
			$where = "bercate_id <> :id AND (CASE WHEN :old < :new THEN priority > :old AND priority <= :new 
												  WHEN :old > :new THEN priority >= :new AND priority < :old END)
					";  
			$value = array(
				":id" => $id,			 
				":old" => $priority_old,
				":new" => $priority_new
			); 
			$res['sec1'] = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value);
  			$set = "priority = :new";
			$where = "bercate_id = :id";
			$value = array(
				":id" => $id,
				":new" => $priority_new
			);
		 	$res['sec2'] = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value);
		 
		} 
		if($res['sec1']['status'] != 200){
			$ret['status'] = 400;
			$ret['case'] = 'sec1';
		}else if($res['sec2']['status'] != 200){
			$ret['status'] = 400;
			$ret['case'] = 'sec2';
		}else{
			$ret['status'] = 200;
		} 
		return $ret;
	 }
	public function updateCategorySpace(){
		$table = "berproduct";
		$set = " product_category = REPLACE(product_category, ',,' ,  ',' ) ";
		$where = "product_id  != :id ";
		$value = array(
			":id" => ('0')							
		); 
		$result = self::$dbcon->update_prepare($table, $set, $where,$value); 
		$table2 = "berproduct";
		$set2 = " product_category = REPLACE(product_category, ',0,' ,  ',' ) ";
		$where2 = "product_id  != :id ";
		$value2 = array(
			":id" => ('0')							
		); 
		$result2 = self::$dbcon->update_prepare($table2, $set2, $where2,$value2);

		return $result;
	 }  
	public function updateCateQuadNumb(){ 	// เบอร์ตอง เบอร์โฟร
		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
		FROM berproduct WHERE product_category NOT LIKE "%,15,%" ORDER BY product_id ASC  ';
		$resSrc = self::$dbcon->query($sql); 
		$numbArr = array();  
		$len = 7;
		$limit = 6;    
		$position = -7; 
		$numChk = array(); 
		$product_id ='';

		 /*
      	 *  ประเภท four  
      	 */
		foreach($resSrc as $key =>$value){ 
			$numChk = array();  
			for($i=0; $i < $limit ;$i++){  
				$round =  $position + $i; 
				$numb = substr($value['pp'],$round,2); 
				$numbKey[$i] = $numb; 
				
			}    

			if($numbKey[0] == $numbKey[2]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[0]) && preg_match('/^(.)\1*$/u',  $numbKey[2])  ){  
					$item = $numbKey[0].''.$numbKey[2]; 
					array_push($numChk, $item);  
				 }  
			} 
			if($numbKey[1] == $numbKey[3]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[1]) && preg_match('/^(.)\1*$/u',  $numbKey[3]) ){   
					$item = $numbKey[1].''.$numbKey[3]; 
					array_push($numChk, $item);  
				}   
			} 
			if($numbKey[2] == $numbKey[4]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[2]) && preg_match('/^(.)\1*$/u',  $numbKey[4]) ){   
					$item = $numbKey[2].''.$numbKey[4];  
					array_push($numChk, $item); 
				}    
			} 
			if($numbKey[3] == $numbKey[5]){ 
				if (preg_match('/^(.)\1*$/u',  $numbKey[3]) && preg_match('/^(.)\1*$/u',  $numbKey[5]) ){ 
					$item = $numbKey[3].''.$numbKey[5];
					array_push($numChk, $item);   
				} 
			}    

			if(!empty($numChk)){
				$numbArr[$value['product_id']][$value['pp']]  = $numChk; 
				$product_id .= $value['product_id'].',';
			}

		} 
		
		$res['cate_total'] = count($numbArr);
		$res['product_id'] = substr($product_id,0,-1);
		$res['arr'] = $numbArr; //onwork 
		
		if($res['product_id'] != ''){ 
				$set = "product_category = CONCAT(product_category,:cate_id ) ";
				$where = 'product_id IN ('.$res['product_id'].')  ';
				$value = array( 
					":cate_id" =>  '15,'
				);
			$allproduct = self::$dbcon->update_prepare("berproduct",$set,$where,$value); 
		}   

		$newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_category LIKE "%,15,%" AND product_sold NOT LIKE "%y%" ';
		$resSql = self::$dbcon->query($newSql);  

		if(!empty($resSql)){ 
			$set = "bercate_total = :total";
			$where = "bercate_id = 15";
			$value = array( ":total" => $resSql[0]['total'] );
			$allproduct = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
		} 
		
		return $ret;
	 }
	public function updateCateTripleNumb(){ 	// เบอร์ตอง เบอร์โฟร
		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
		FROM berproduct WHERE product_category NOT LIKE "%,15,%" ORDER BY product_id ASC  ';
		$resSrc = self::$dbcon->query($sql); 
		$numbArr = array();  
		$len = 7;
		$limit = 5;    
		$position = -7; 
		$numChk = array(); 
		$product_id ='';

		 /*
      	 *  ประเภท four  
      	 */
		foreach($resSrc as $key =>$value){ 
			$numChk = array();  
			for($i=0; $i < $limit ;$i++){  
				$round =  $position + $i; 
				$numb = substr($value['pp'],$round,3); 
				$numbKey[$i] = $numb; 
				
			}    

			if (preg_match('/^(.)\1*$/u',  $numbKey[0])   ){   
				$item = $numbKey[0];
				array_push($numChk, $item);  
			}  
			if (preg_match('/^(.)\1*$/u',  $numbKey[1])   ){   
				$item = $numbKey[1];
				array_push($numChk, $item);  
			} 
			if (preg_match('/^(.)\1*$/u',  $numbKey[2])   ){   
				$item = $numbKey[2];
				array_push($numChk, $item);  
			} 
			if (preg_match('/^(.)\1*$/u',  $numbKey[3])   ){   
				$item = $numbKey[3];
				array_push($numChk, $item);  
			} 
			if (preg_match('/^(.)\1*$/u',  $numbKey[4])   ){   
				$item = $numbKey[4]; 
				array_push($numChk, $item);  
			} 

			if(!empty($numChk)){
				$numbArr[$value['product_id']][$value['pp']]  = $numChk; 
				$product_id .= $value['product_id'].',';
			}

		} 
		
		$res['cate_total'] = count($numbArr);
		$res['product_id'] = substr($product_id,0,-1);
		$res['arr'] = $numbArr; //onwork 
		
		if($res['product_id'] != ''){ 
				$set = "product_category = CONCAT(product_category,:cate_id ) ";
				$where = 'product_id IN ('.$res['product_id'].')   ';
				$value = array( 
					":cate_id" =>  '15,'
				);
			$allproduct = self::$dbcon->update_prepare("berproduct",$set,$where,$value); 
		}   

		$newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_category LIKE "%,15,%" AND product_sold NOT LIKE "%y%" ';
		$resSql = self::$dbcon->query($newSql);  

		if(!empty($resSql)){ 
			$set = "bercate_total = :total";
			$where = "bercate_id = 15";
			$value = array( ":total" => $resSql[0]['total'] );
			$allproduct = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
		} 
		
		return $ret;
	 }
	public function updateCateXYxy(){ 	//xyxy id = 19
		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
		FROM berproduct WHERE product_category NOT LIKE "%,19,%" ORDER BY product_id ASC  ';
		$resSrc = self::$dbcon->query($sql); 
		$numbArr = array();  
		$len = 7;
		$limit = 6;    
		$position = -7; 
		$numChk = array(); 
		$product_id ='';
 
		foreach($resSrc as $key =>$value){ 
			$numChk = array();  
			for($i=0; $i < $limit ;$i++){  
				$round =  $position + $i; 
				$numb = substr($value['pp'],$round,2); 
				$numbKey[$i] = $numb; 
				
			}    
			if($numbKey[0] == $numbKey[2]){
				if (!preg_match('/^(.)\1*$/u',  $numbKey[2])) {  
					$item = $numbKey[0].''.$numbKey[2];
					array_push($numChk, $item); 
				 }  
			} 
			if($numbKey[1] == $numbKey[3]){
				if (!preg_match('/^(.)\1*$/u',  $numbKey[1])) {   
					$item = $numbKey[1].''.$numbKey[3];
					array_push($numChk, $item); 
				}  
			} 
			if($numbKey[2] == $numbKey[4]){
				if (!preg_match('/^(.)\1*$/u',  $numbKey[2])) {   
					$item = $numbKey[2].''.$numbKey[4]; 
				}   
			} 
			if($numbKey[3] == $numbKey[5]){
				if (!preg_match('/^(.)\1*$/u',  $numbKey[3])) { 
					$item = $numbKey[3].''.$numbKey[5];
					array_push($numChk, $item);
				}   
			} 

			if(!empty($numChk)){
				$numbArr[$value['product_id']][$value['pp']]  = $numChk; 
				$product_id .= $value['product_id'].',';
			}

		} 
		
		$res['cate_total'] = count($numbArr);
		$res['product_id'] = substr($product_id,0,-1);
		$res['arr'] = $numbArr; //onwork 
		
		if($res['product_id'] != ''){ 
				$set = "product_category = CONCAT(product_category,:cate_id ) ";
				$where = 'product_id IN ('.$res['product_id'].')   ';
				$value = array( 
					":cate_id" =>  '19,'
				);
			$allproduct = self::$dbcon->update_prepare("berproduct",$set,$where,$value); 
		}   

		$newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_category LIKE "%,19,%" AND product_sold NOT LIKE "%y%" ';
		$resSql = self::$dbcon->query($newSql);  

		if(!empty($resSql)){ 
			$set = "bercate_total = :total";
			$where = "bercate_id = 19";
			$value = array( ":total" => $resSql[0]['total'] );
			$allproduct = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
		} 
		
		return $ret;
	 }
	public function updateCateXXyy(){ 	//XXyy id = 19
		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
		FROM berproduct WHERE product_category NOT LIKE "%,19,%" ORDER BY product_id ASC  ';
		$resSrc = self::$dbcon->query($sql); 
		$numbArr = array();  
		$len = 7;
		$limit = 6;    
		$position = -7; 
		$numChk = array(); 
		$product_id ='';
 
		foreach($resSrc as $key =>$value){ 
			$numChk = array();  
			for($i=0; $i < $limit ;$i++){  
				$round =  $position + $i; 
				$numb = substr($value['pp'],$round,2); 
				$numbKey[$i] = $numb; 
				
			}    

			if($numbKey[0] != $numbKey[2]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[0]) && preg_match('/^(.)\1*$/u',  $numbKey[2])  ){  
					$item = $numbKey[0].''.$numbKey[2]; 
					array_push($numChk, $item);  
				 }  
			} 
			if($numbKey[1] != $numbKey[3]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[1]) && preg_match('/^(.)\1*$/u',  $numbKey[3]) ){   
					$item = $numbKey[1].''.$numbKey[3]; 
					array_push($numChk, $item);  
				}   
			} 
			if($numbKey[2] != $numbKey[4]){
				if (preg_match('/^(.)\1*$/u',  $numbKey[2]) && preg_match('/^(.)\1*$/u',  $numbKey[4]) ){   
					$item = $numbKey[2].''.$numbKey[4];  
					array_push($numChk, $item); 
				}    
			} 
			if($numbKey[3] != $numbKey[5]){ 
				if (preg_match('/^(.)\1*$/u',  $numbKey[3]) && preg_match('/^(.)\1*$/u',  $numbKey[5]) ){ 
					$item = $numbKey[3].''.$numbKey[5];
					array_push($numChk, $item);   
				} 
			}    

			if(!empty($numChk)){
				$numbArr[$value['product_id']][$value['pp']]  = $numChk; 
				$product_id .= $value['product_id'].',';
			}

		} 
		
		$res['cate_total'] = count($numbArr);
		$res['product_id'] = substr($product_id,0,-1);
		$res['arr'] = $numbArr; //onwork 
		
		if($res['product_id'] != ''){ 
				$set = "product_category = CONCAT(product_category,:cate_id ) ";
				$where = 'product_id IN ('.$res['product_id'].')  ';
				$value = array( 
					":cate_id" =>  '19,'
				);
			$allproduct = self::$dbcon->update_prepare("berproduct",$set,$where,$value); 
		}   

		$newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_category LIKE "%,19,%" AND product_sold NOT LIKE "%y%" ';
		$resSql = self::$dbcon->query($newSql);  

		if(!empty($resSql)){ 
			$set = "bercate_total = :total";
			$where = "bercate_id = 19";
			$value = array( ":total" => $resSql[0]['total'] );
			$allproduct = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
		} 
		
		return $ret;
	 }
	public function updateCateTripleRepeat(){ 	//เบอร์มันไม่ดี มันห่ามมมมมมมมมมมมมมมม   id = 19
		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
    			FROM berproduct WHERE product_category NOT LIKE "%,19,%" ORDER BY product_id ASC ';
    	$resSrc= self::$dbcon->query($sql); 
    	$numbArr = array();  
    	$len = 7;
    	$limit = 5;    
    	$position = -7; 
    	$numChk = array(); 
	 foreach($resSrc as $key =>$value){ 
    	 	$numChk = array();  
    	 	for($i=0; $i < $limit ;$i++){ 
    	 	     $round =  $position + $i; 
    	 	     $numb = substr($value['pp'],$round,3); 
    	 	     $numbKey[$i] = $numb;     
    	 	 }  
    	 	if( $numbKey[0] == $numbKey[3] ){
    	 	    array_push($numChk, $numbKey[0]);
    	 	}else if( $numbKey[0] == $numbKey[4] ){
    	 	     array_push($numChk,  $numbKey[4]);

    	 	}else if($numbKey[1] == $numbKey[4]){
    	 	     array_push($numChk,  $numbKey[1]);

    	 	}else if($numbKey[3] == $numbKey[0]){
    	 	     array_push($numChk,   $numbKey[3]);
    	 	}
		 
    	 	if(!empty($numChk)){
    	 	     $numbArr[$value['product_id']][$value['pp']]  = $numChk; 
    	 	     $product_id .= $value['product_id'].',';
    	 	}    
		} 
	 
    	$res['cate_total'] = count($numbArr);
    	$res['product_id'] = substr($product_id,0,-1);
		$res['arr'] = $numbArr;   
	    if($res['product_id'] != ''){ 
				$set = "product_category = CONCAT(product_category,:cate_id ) ";
				$where = 'product_id IN ('.$res['product_id'].')  ';
				$value = array( 
					":cate_id" =>  '19,'
				);
			$allproduct = self::$dbcon->update_prepare("berproduct",$set,$where,$value); 
		}   

	 	$newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_category LIKE "%,19,%" AND product_sold NOT LIKE "%y%" ';
		$resSql = self::$dbcon->query($newSql);  

	    if(!empty($resSql)){ 
	    	$set = "bercate_total = :total";
	    	$where = "bercate_id = 19";
	    	$value = array( ":total" => $resSql[0]['total'] );
	    	$allproduct = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
		 }  
		 
		return $ret; 

	 }
	public function getSlcNetwork(){
		$sql ='SELECT * FROM bernetwork  ORDER by network_id';
		$result = self::$dbcon->query($sql); 
		foreach($result as $keys => $value){
				$res['option'] .= '<option  value="'.$value['network_name'].'" data-id="'.$value['network_id'].'"> ['.$value['network_id'].'] '.$value['network_name'].' </option>	';
			} 
		return $res['option'];
	 }
	public function saveImageWithText($text, $color, $source_file) { 
		global $website; 
		$public_file_path = ROOT_URL; 
		// Copy and resample the imag
		list($width, $height) = getimagesize($source_file);
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($source_file);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
		
		// Prepare font size and colors
		$text_color = imagecolorallocate($image_p, 0, 0, 0);
		$bg_color = imagecolorallocate($image_p, 255, 255, 255);
		$font =  '../../css/cordia_new_bold.ttf'; 
		$font_size = 12; 
	
		// Set the offset x and y for the text position
		$offset_x = 0;
		$offset_y = 20;
	
		// Get the size of the text area
		$dims = imagettfbbox($font_size, 0, $font, $text);
		$text_width = $dims[4] - $dims[6] + $offset_x;
		$text_height = $dims[3] - $dims[5] + $offset_y; 
		// Add text background
		imagefilledrectangle($image_p, 0, 0, $text_width, $text_height, $bg_color);
	
		// Add text
		imagettftext($image_p, $font_size, 0, $offset_x, $offset_y, $text_color, $font, $text);
	
		// Save the picture
		$aa = imagejpeg($image_p, $public_file_path . 'upload/test.jpeg', 100); 
	 
		// Clear
		imagedestroy($image); 
		imagedestroy($image_p); 

		/* /-----------------------*/ 
			if(file_exists($imagefile))
			    {    
			    /*** create image ***/
			    $im = @imagecreatefrompng($imagefile); 
			    /*** create the text color ***/
			    $text_color = imagecolorallocate($im, 0, 255, 0); 
			    /*** set the font file ***/
			    $font_file = './msjh.ttf'; 
			    /*** splatter the image with text ***/
			    imagefttext($im, 20, 0, 25, 150, $text_color, $font_file, $text); 
			    }
			else
			    {
			    /*** if the file does not exist we will create our own image ***/
			    /*** Create a black image ***/
			    $im  = imagecreatetruecolor(150, 30); /* Create a black image */ 
			    /*** the background color ***/
			    $bgc = imagecolorallocate($im, 255, 255, 255); 
			    /*** the text color ***/
			    $tc  = imagecolorallocate($im, 0, 0, 0); 
			    /*** a little rectangle ***/
			    imagefilledrectangle($im, 0, 0, 150, 30, $bgc); 
			    /*** output and error message ***/
			    imagestring($im, 1, 5, 5, "Error loading $imagefile", $tc);
			}
		return $im;
	 }


	public function loveRelateCase1(){

			$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 10) as pp 
			FROM berproduct WHERE product_category NOT LIKE "%,15,%" ORDER BY product_id ASC  ';
			$resSrc = self::$dbcon->query($sql); 
 
		return $ret;
	 }

	public function getProductByCategoryManual($getpost){ #นับจำนวนเบอร์แต่ละหมวดหมู่
		$sqlCate = 'SELECT bercate_id,bercate_name,status FROM berproduct_category WHERE bercate_id != 0 AND status  != "yes" ';
	 	if($getpost['order'] != '' && isset($getpost['order'])){ 
	 		$order = trim($getpost['order']);				
	 		$sqlCate .= ' AND bercate_id = '.$order.' ';	 
	 	}
		$sqlCate .= ' ORDER BY priority'; 
		$resultCate = self::$dbcon->query($sqlCate); 
		$bercate = array();  
		if(!empty($resultCate)){ 
		foreach($resultCate as $keyCate => $cateVal){  
				$bercate[$cateVal['bercate_id']]['cate_id']  = $cateVal['bercate_id'];
				$sqlProd[$cateVal['bercate_id']]  = 'SELECT  count(product_id) as numtotal FROM berproduct 
													 WHERE product_sold NOT LIKE "%y%" AND product_category LIKE "%,'.$cateVal['bercate_id'].',%" ';  
				$resultSlcUpdate = self::$dbcon->fetchAll($sqlProd[$cateVal['bercate_id']],[]); 
				$total = $resultSlcUpdate[0]['numtotal']; 
				//update count ber product category
				$tableCate = "berproduct_category";	 
				$whereCate = "bercate_id = :bercate_id";		
				$setCate = "bercate_total = :bercate_total, date_created = :date_created";
				$valueCate = array(		
							":bercate_id" => ($cateVal['bercate_id']),
							":bercate_total" => ($total),
							":date_created" => date('Y-m-d H:i:s')
						); 
				$resCate = self::$dbcon->update_prepare($tableCate, $setCate, $whereCate, $valueCate);  
			}
		}
		return $resCate; 
	 } // end of manual	 
	public function  getProductByCategory($getpost){  
	 	$sqlCate = 'SELECT * FROM berproduct_category WHERE bercate_id != 0 AND status = "yes"  ';
	 	if($getpost['order'] != '' && isset($getpost['order'])){
	 		$order = trim($getpost['order']);	
	 		$sqlCate .= ' AND bercate_id = '.$order.' ';	 
		 } 
		$sqlCate .= ' ORDER BY priority ASC'; 
		$resultCate = self::$dbcon->query($sqlCate);  
		$bercate = array();      
		if(!empty($resultCate)){  
		foreach($resultCate as $keyCate => $cateVal){  
				$table = "berproduct"; 
				$set = "product_category = replace(product_category, ',".$cateVal['bercate_id'].",' , ',')"; 
				$where = 'product_category LIKE  "%,'.$cateVal['bercate_id'].',%" AND default_cate NOT LIKE "%,'.$cateVal['bercate_id'].',%"	';
				$value = array(			 
					":product_cateId" => $cateVal['bercate_id']
				);  
				$ret['resetCate'][] = self::$dbcon->update_prepare($table, $set, $where,$value); 
 
				$valIdIn =''; 
				$total = 0;
				$bercate = array();
				$sqlProd = array();
                $bercate[$cateVal['bercate_id']]['cate_id']  = $cateVal['bercate_id'];
                $sqlProd[$cateVal['bercate_id']]  ="";
				$sqlProd[$cateVal['bercate_id']]  .= 'SELECT  product_id,product_sold,product_phone,MID(product_phone,4, 10) as pp 
														FROM berproduct HAVING product_sold NOT LIKE "%y%" AND ';
				$sqlProd[$cateVal['bercate_id']]  .='(';  
				$needfulArr = explode(',',$cateVal['bercate_needful']); 
			foreach($needfulArr as $nfKey => $nfVal){ 
					$bercate[$cateVal['bercate_id']]['needful'][$nfKey]  = $nfVal;	 
					if($nfKey != 0){	
						$sqlProd[$cateVal['bercate_id']] .=' OR '; 
						}						
					$sqlProd[$cateVal['bercate_id']]  .=' pp LIKE "%'.$nfVal.'%" ';	
				}	  
				$needlessArr = explode(',',$cateVal['bercate_needless']);
				if($needlessArr[0] != ''){ 
					$sqlProd[$cateVal['bercate_id']]  .=') AND (';
				foreach($needlessArr as $nlKey => $nlVal){				 
						$bercate[$cateVal['bercate_id']]['needless'][$nlKey]  = $nlVal;
						/* sql select product needless  */
						if($nlKey != 0){	$sqlProd[$cateVal['bercate_id']]  .=' AND '; }
						$sqlProd[$cateVal['bercate_id']]  .=' pp NOT LIKE "%'.$nlVal.'%" ';	
					}	
				 }
				$sqlProd[$cateVal['bercate_id']]  .= ')';   
				 /* 	หาค่าจำนวนสูงสุดของ สินค้า เพื่อบันทึกจำนวนลงใส่ หมวดหมู่  */
				$resultSlcUpdate = self::$dbcon->fetchAll($sqlProd[$cateVal['bercate_id']],[]);   
				$cateIdUpdate  =  ','.$cateVal['bercate_id'].','; 
				$table = "berproduct";					 	
				$set = "product_category = CONCAT(product_category, :product_category )";
				$keySlc = 0;
				$value = array(			 
					":product_category" => $cateIdUpdate
				);	  
				foreach($resultSlcUpdate as $keySlc => $valSlc){	
					if($keySlc != 0 ){  $valIdIn .= ',';	}
					$valIdIn .= $valSlc['product_id'];		
				}   
				if($valIdIn != ""){
					$where = 'product_id IN ( '.$valIdIn.' ) AND product_category NOT LIKE "%'.$cateIdUpdate.'%" '; 	
					$ret['resUpProd'][] = self::$dbcon->update_prepare($table, $set, $where,$value); 
				}
 
				$total = 0;	
				$chkold = 'SELECT count(product_id) as num FROM berproduct 
							WHERE product_sold != "yes" AND product_category LIKE "%,'.$cateVal['bercate_id'].',%"';
				$resold = self::$dbcon->query($chkold);  
				/*  set values  */
				$category_id = $cateVal['bercate_id'];  
				if(!empty($resold)){ $total = $resold[0]['num'];	} 	 
				/* 	update count ber product category */
				$tableCate = "berproduct_category";	 
				$whereCate = "bercate_id = :bercate_id";		
				$setCate = "bercate_total = :bercate_total, date_created = :date_created";
				$valueCate = array(		
							":bercate_id" => ($category_id),
							":bercate_total" => ($total),
							":date_created" => date('Y-m-d H:i:s')
						); 
				$ret['resCate'][]  = self::$dbcon->update_prepare($tableCate, $setCate, $whereCate,$valueCate);
			 
			} 

		}     
	    $newSql ='SELECT COUNT(product_phone) as total FROM berproduct WHERE product_sold !=  "yes" AND display = "yes"';
	    $resSql = self::$dbcon->query($newSql); 
	    if(!empty($resSql)){ 
	    	$set = "bercate_total = :total";
	    	$where = "bercate_id = 0 ";
	    	$value = array( ":total" => $resSql[0]['total'] );
	    	$ret['allproduct'] = self::$dbcon->update_prepare("berproduct_category",$set,$where,$value); 
	    } 
	 
		
		return $ret;
	 }
	public function  getProductByCategoryBySet($getpost){  
		#ลบข้อมูลเก่าออกจากตาราง berproduct_alover
		#ลบหมวดหมู่ 3 และ 4 ออกจากเบอร์ทุกเบอร์
		$res['reset'] = self::prepare_Byset_reset_category();  
		#ดึงข้อมูลเบอร์ทั้งหมด						
		#SQLSELECT 
		$sqlBer = 'SELECT product_id,product_pin,product_category,product_discount,product_comment,product_phone,product_sumber,product_network,product_price,product_sold,product_grade,monthly_status
                            ,MID(product_phone,2, 9) as nn 
                            ,MID(product_phone,4, 7) as pp 
                            ,MID(product_phone,7, 4) as ff 
				FROM berproduct WHERE  product_category  NOT LIKE "%,0,%" AND product_sold NOT LIKE "%y%" ORDER BY product_id ASC ';
		$allBer = self::$dbcon->query($sqlBer);  
 		#ดึงฟังก์ชั่นที่ต้องการให้แสดง
		$sqlApprove = 'SELECT * FROM berproduct_category_approve WHERE  func_display = "yes"  ';
		$approve_arr = self::$dbcon->query($sqlApprove); 
		#ตั้งค่าฟังก์ชั่นที่อนุมัติให้แสดงผล display = yes
		 if(!empty($approve_arr)){
			$approve = array();
			foreach($approve_arr as $key => $val){
				$approve["c".$val['func_id']]['id'] = $val['func_id'];
				$approve["c".$val['func_id']]['cate_id'] = $val['func_cate_id'];
			}
		 } 
      
		/* ********* section 1 แปลงข้อมูลเข้าแต่ละ function ************ */    
		$var_case = self::prepare_Byset_variable_condition($allBer,$approve); 
 
		/* ********** section 2 ส่วนของการกรองข้อมูลออก ***************** */ 
		$case = self::prepare_Byset_filter_condition($var_case,$approve);  
  
		/* ********** section 3 ส่วนของการบันทึกข้อมูล ****************** */
		$res['ins'] = self::insert_Byset_category($case,$approve);
	
		  
		return $res; 
	 } 
	
	public function prepare_Byset_reset_category(){
		/* จัดการหมวดหมู่ lover and xxyy */ 
		$table ='berproduct_alover'; 
		$where ='status = :param ';
		$value = [
			':param' => 'auto'
		];			
		$res['del'] = self::$dbcon->deletePrepare($table, $where, $value);

		$table = "berproduct";
		$set = "product_category = REPLACE(product_category, ',3,', :param ) ";
		$where = " product_category LIKE  '%,3,%'  ";
		$value = array( 
			":param" => ''	
		); 
		$res['reset3'] = self::$dbcon->update_prepare($table, $set, $where,$value);

		$table = "berproduct";
		$set2 = "product_category = REPLACE(product_category, ',4,', :param ) "; 
		$where = " product_category LIKE  '%,4,%' ";
		$value = array( 
			":param" => ''	
		); 
		$res['reset4'] = self::$dbcon->update_prepare($table, $set2, $where,$value);
		return $res;
	}

	public function prepare_Byset_variable_condition($allBer,$approve){  
		#(getProductByCategoryBySet)
		#product_id ไว้เก็บไอดีนำไปใช้อัพเดทข้อมูล product_id IN (..,..)
		$product_id = '';
		#set array 
		# x = เลขที่เหมือนกัน
		$condition1 = array(); #case1 = xxxxxx1 | xxxxxx2 หลักสุดท้ายอะไรก็ได้  
		$condition2 = array(); #case2 = 1xxxxxx | 2xxxxxx หลักที่ 1 อะไรก็ได้ 
		$condition3 = array(); #case3 = 12xxxxx | 21xxxxx สองหลักแรก! เลขเหมือนกันสลับตำแหน่ง 
		$condition4 = array(); #case4 = xxxxx21 | xxxxx12 สองหลักหลัง! เลขเหมือนกันสลับตำแหน่ง 
		$condition5 = array(); #case5 = xxxxxxx | xxxxxxx 7หลักหลังเหมือนกันทุกตำแหน่ง 
		$condition6 = array(); #case6 = xxx1212 เบอร์ xyxy
		$condition7 = array(); #case7 = xxx1122 เบอร์ xxyy
		$condition8 = array(); #case8 = 123x123 เบอร์ห่าม
		$condition9 = array(); #case9 = xxxx111 เบอร์ตอง
		$condition10= array(); #case10= xxx1111 เบอร์โฟร 
		#จัดข้อมูลเข้าหมวดหมู่
    #แต่ละหมวดหมู่จะจับข้อมูลที่ตรงกันตามเงื่อนไขไว้รวมกลุ่มกัน
		foreach($allBer as $keys => $vals){  
			#case1 
			if(isset($approve['c1'])){
				$con1 = substr($vals['nn'],0,-1);  
				if(!empty($condition1[$con1])){  
					$len1 = count($condition1[$con1]); 
				}else{ 
					$len1  = 0;
				} 
				$condition1[$con1][$len1]['id'] = $vals['product_id'];
				$condition1[$con1][$len1]['price'] = $vals['product_price'];
				$condition1[$con1][$len1]['numb'] = $vals['product_phone'];  
				$condition1[$con1][$len1]['sumber'] = $vals['product_sumber']; 
				$condition1[$con1][$len1]['comment'] = $vals['product_comment'];   
                $condition1[$con1][$len1]['network'] = $vals['product_network'];   
                $condition1[$con1][$len1]['discount'] = $vals['product_discount'];   
				$condition1[$con1][$len1]['grade'] = $vals['product_grade'];  
				$condition1[$con1][$len1]['p_price'] = $vals['product_price'];  
				$condition1[$con1][$len1]['monthly'] = $vals['monthly_status'];  	
				$condition1[$con1][$len1]['pp'] = $vals['nn'];   
				$condition1[$con1][$len1]['value'] = $con1;
			} 
			#case2 
			if(isset($approve['c2'])){   
                $con2 = substr($vals['nn'],2,1);  
                $setA = substr($vals['nn'],0,2);  
                $setB = substr($vals['nn'],3);  
                $setResc = $setA.$setB;
				if(!empty($condition2[$setResc])){  
					$len2 = count($condition2[$setResc]); 
				}else{
					$len2  = 0;
				}
				$condition2[$setResc][$len2]['id'] = $vals['product_id'];
				$condition2[$setResc][$len2]['price'] = $vals['product_price'];
				$condition2[$setResc][$len2]['numb'] = $vals['product_phone'];  
				$condition2[$setResc][$len2]['sumber'] = $vals['product_sumber'];  
				$condition2[$setResc][$len2]['comment'] = $vals['product_comment'];   
                $condition2[$setResc][$len2]['network'] = $vals['product_network']; 
                $condition2[$setResc][$len2]['discount'] = $vals['product_discount']; 
				$condition2[$setResc][$len2]['grade'] = $vals['product_grade'];  
				$condition2[$setResc][$len2]['p_price'] = $vals['product_price'];  
				$condition2[$setResc][$len2]['monthly'] = $vals['monthly_status'];  
				$condition2[$setResc][$len2]['pp'] = $setResc;
                $condition2[$setResc][$len2]['value'] = $con2; 
			}
			#case3 
			if(isset($approve['c3'])){
                $con3 = substr($vals['nn'],2,2); 
                $setA = substr($vals['nn'],0,2);  
                $setB = substr($vals['nn'],4);  

				if(!empty($condition3[$con3])){  
					$len3 = count($condition3[$con3]); 
				}else{
					$len3  = 0;
                }
                if(substr($con3,0,1) != substr($con3,1,1)){
                    $condition3[$con3][$len3]['id'] = $vals['product_id'];
                    $condition3[$con3][$len3]['price'] = $vals['product_price'];
                    $condition3[$con3][$len3]['numb'] = $vals['product_phone']; 
                    $condition3[$con3][$len3]['sumber'] = $vals['product_sumber']; 
                    $condition3[$con3][$len3]['comment'] = $vals['product_comment'];   
                    $condition3[$con3][$len3]['network'] = $vals['product_network'];   
                    $condition3[$con3][$len3]['discount'] = $vals['product_discount'];
                    $condition3[$con3][$len3]['grade'] = $vals['product_grade'];   
                    $condition3[$con3][$len3]['p_price'] = $vals['product_price'];  
                    $condition3[$con3][$len3]['monthly'] = $vals['monthly_status'];  
                    $condition3[$con3][$len3]['pp'] = $setA.$setB;   
                    $condition3[$con3][$len3]['value'] = $con3; 
                }
			}
			#case4
			if(isset($approve['c4'])){ 
				$con4 = substr($vals['nn'],0,-2);
				if(!empty($condition4[$con4])){  
					$len4 = count($condition4[$con4]); 
				}else{
					$len4  = 0;
				}
				$condition4[$con4][$len4]['id'] = $vals['product_id'];
				$condition4[$con4][$len4]['price'] = $vals['product_price'];
				$condition4[$con4][$len4]['numb'] = $vals['product_phone'];  
				$condition4[$con4][$len4]['sumber'] = $vals['product_sumber']; 
				$condition4[$con4][$len4]['comment'] = $vals['product_comment'];   
                $condition4[$con4][$len4]['network'] = $vals['product_network'];   
                $condition4[$con4][$len4]['discount'] = $vals['product_discount'];
				$condition4[$con4][$len4]['grade'] = $vals['product_grade'];  
				$condition4[$con4][$len4]['p_price'] = $vals['product_price'];  
				$condition4[$con4][$len4]['monthly'] = $vals['monthly_status'];  
				$condition4[$con4][$len4]['pp'] = $vals['nn'];  
				$condition4[$con4][$len4]['value'] = $con4; 
      }
            
        
			#case5 
			if(isset($approve['c5'])){
				$con5 = $vals['pp'];  
				if(!empty($condition5[$con5])){
					$len5 = count($condition5[$con5]); 
				}else{
					$len5  = 0;
				}
				$condition5[$con5][$len5]['id'] = $vals['product_id'];
				$condition5[$con5][$len5]['price'] = $vals['product_price'];
				$condition5[$con5][$len5]['numb'] = $vals['product_phone'];  
				$condition5[$con5][$len5]['pp'] = $vals['pp'];  
				$condition5[$con5][$len5]['sumber'] = $vals['product_sumber']; 
				$condition5[$con5][$len5]['comment'] = $vals['product_comment'];   
                $condition5[$con5][$len5]['network'] = $vals['product_network']; 
                $condition5[$con5][$len5]['discount'] = $vals['product_discount'];  
				$condition5[$con5][$len5]['grade'] = $vals['product_grade'];  
				$condition5[$con5][$len5]['p_price'] = $vals['product_price'];  
				$condition5[$con5][$len5]['monthly'] = $vals['monthly_status'];  
				$condition5[$con5][$len5]['value'] = $con5;  
			}
			#case6  
			if(isset($approve['c6'])){
				$numbKey6 = array();
				$numChk6 = array();   
				$limit6 = 3;    
				$position6 = -4;  
				for($i=0; $i < $limit6 ;$i++){ 
						$round =  $position6 + $i; 
						$numb = substr($vals['ff'],$round,2); 
						$numbKey6[$i] = $numb;   
                 }  
	
				if(substr($numbKey6[0],0,1) != substr($numbKey6[0],1,1)   &&  substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)  ){
					if($numbKey6[0] == $numbKey6[2] ){
						$numChk6['value'] =  $numbKey6[0].$numbKey6[2];
						} 
				 } 
				// if(substr($numbKey6[1],0,1) != substr($numbKey6[1],1,1)   &&  substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)  ){
				// 	if($numbKey6[1] == $numbKey6[3]){
				// 		$numChk6['value'] =  $numbKey6[1].$numbKey6[3];
				// 		}
				//  }
				// if(substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)   &&  substr($numbKey6[4],0,1) != substr($numbKey6[4],1,1)  ){
				// 	if($numbKey6[2] == $numbKey6[4]){
				// 		$numChk6['value'] =  $numbKey6[2].$numbKey6[4];
				// 		}
				//  }
	
				// if(substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)   &&  substr($numbKey6[5],0,1) != substr($numbKey6[5],1,1)  ){
				// 	if($numbKey6[3] == $numbKey6[5]){
				// 		$numChk6['value'] =  $numbKey6[3].$numbKey6[5];
				// 		}
				//  } 
				if(!empty($numChk6)){  
					$numChk6['numb'] =  $vals['product_phone'];
					$numChk6['pp'] =  $vals['ff'];
					$numChk6['id'] =  $vals['product_id'];  
					$numChk6['monthly'] =  $vals['monthly_status'];    
					$condition6[$vals['product_id']][$vals['ff']]  = $numChk6;  
					$product_id .= $vals['product_id'].',';
				 }   
            } 
      
          
			#case7 
			if(isset($approve['c7'])){
				$numChk10 = array(); 
				$numbKey7 = array();
				$numChk7 = array();   
				$limit7 = 3;    
				$position7 = -4;  
				for($i=0; $i < $limit7 ;$i++){ 
						$round =  $position7 + $i; 
						$numb = substr($vals['ff'],$round,2); 
						$numbKey7[$i] = $numb;    
				}  
				
				#ถ้าชุดเลขตำแหน่งแรกกับตำแหน่งที่สองเหมือนกัน ทั้งฝั่งซ้ายและขวาจะเกิดเบอร์ xxyy
				#ถ้าเลขที่อยู่คู่ระหว่างกันตรงกัน จะเชื่อมโยงเลขเท่ากับเบอร์โฟร์ เช่น [9(9)-> 9 == 9 <-(9)9] == 9999 จะเกิดเบอร์โฟร์
				if(substr($numbKey7[0],0,1) == substr($numbKey7[0],1,1)   &&  substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)  ){ 
					if(substr($numbKey7[0],1,1) != substr($numbKey7[2],0,1)){
                        $numChk7['value'] =  $numbKey7[0].$numbKey7[2]; 
					}  
                } 
                
				// #เงื่อนไขนี้เข้าข่ายของฟังก์ชั่น case10 เบอร์โฟร์
				// if(substr($numbKey7[1],0,1) == substr($numbKey7[1],1,1)   &&  substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)  ){
				// 	if(substr($numbKey7[1],1,1) == substr($numbKey7[3],0,1)){
				// 		$numChk10['value'] =  $numbKey7[1].$numbKey7[3];  
				// 	} else {
				// 		$numChk7['value'] =  $numbKey7[1].$numbKey7[3];  
				// 	}  
				// }
				// if(substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)   &&  substr($numbKey7[4],0,1) == substr($numbKey7[4],1,1)  ){ 
				// 	if(substr($numbKey7[2],1,1) == substr($numbKey7[4],0,1)){
				// 		$numChk10['value'] =  $numbKey7[2].$numbKey7[4];  
				// 	} else {
				// 		$numChk7['value'] =  $numbKey7[2].$numbKey7[4];  
				// 	} 
				// } 
				// if(substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)   &&  substr($numbKey7[5],0,1) == substr($numbKey7[5],1,1)  ){
				// 	if(substr($numbKey7[3],1,1) == substr($numbKey7[5],0,1)){
				// 		$numChk10['value'] =  $numbKey7[3].$numbKey7[5];  
				// 	} else {
				// 		$numChk7['value'] =  $numbKey7[3].$numbKey7[5];  
				// 	}  
				// }   
		
				if(!empty($numChk7)){
					$numChk7['numb'] =  $vals['product_phone'];
					$numChk7['pp'] =  $vals['ff'];
					$numChk7['id'] =  $vals['product_id'];   
					$numChk7['monthly'] =  $vals['monthly_status'];   
					$condition7[$vals['product_id']][$vals['ff']]  = $numChk7;  
					$product_id .= $vals['product_id'].','; 
                } 
                // else if(!empty($numChk10)) {  
				// 	$numChk10['numb'] =  $vals['product_phone'];
				// 	$numChk10['pp'] =  $vals['ff'];
				// 	$numChk10['id'] =  $vals['product_id'];   
				// 	$condition10[$vals['product_id']][$vals['ff']]  = $numChk10;  
				// 	$product_id .= $vals['product_id'].','; 
				// } 
				
			} 
			#case8  
			if(isset($approve['c8'])){
				$numbKey8 = array();
				$numChk8 = array();   
				$limit8 = 5;    
				$position8 = -7;  
				for($i=0; $i < $limit8 ;$i++){ 
						$round =  $position8 + $i; 
						$numb = substr($vals['pp'],$round,3); 
						$numbKey8[$i] = $numb;  
				}  
				if( $numbKey8[0] == $numbKey8[3] ){  		$numChk8['value'] =  $numbKey8[0].$numbKey8[0]; 
				}else if( $numbKey8[0] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[4].$numbKey8[4]; 
				}else if($numbKey8[1] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[1].$numbKey8[1]; 
				}else if($numbKey8[3] == $numbKey8[0]){ 	$numChk8['value'] =  $numbKey8[3].$numbKey8[3];
				} 
				if(!empty($numChk8)){  
					$numChk8['numb'] =  $vals['product_phone'];
					$numChk8['pp'] =  $vals['pp'];
					$numChk8['id'] =  $vals['product_id'];  
					$numChk8['monthly'] =  $vals['monthly_status'];  
					$condition8[$vals['product_id']][$vals['pp']]  = $numChk8;  
					$product_id .= $vals['product_id'].',';
				}  
			} 
			#case9
			// if(isset($approve['c9'])){
			// 	$numbKey9 = array();
			// 	$numChk9 = array();   
			// 	$limit9 = 7;    
			// 	$position9 = -7;  
			// 	for($i=0; $i < $limit9 ;$i++){ 
			// 			$round =  $position9 + $i; 
			// 			$numb = substr($vals['pp'],$round,1); 
			// 			$numbKey9[$i] = $numb;   
			// 	}   
			// 	if( $numbKey9[0]  ==  $numbKey9[1] && $numbKey9[1] == $numbKey9[2] ){ 
			// 		$numChk9['value'] =  $numbKey9[0].$numbKey9[1].$numbKey9[2]; 
			// 	}
			// 	if( $numbKey9[1]  ==  $numbKey9[2] && $numbKey9[2] == $numbKey9[3] ){ 
			// 		$numChk9['value'] =  $numbKey9[1].$numbKey9[2].$numbKey9[3]; 
			// 	}
			// 	if( $numbKey9[2]  ==  $numbKey9[3] && $numbKey9[3] == $numbKey9[4] ){ 
			// 		$numChk9['value'] =  $numbKey9[2].$numbKey9[3].$numbKey9[4]; 
			// 	} 
			// 	if( $numbKey9[3]  ==  $numbKey9[4] && $numbKey9[4] == $numbKey9[5] ){ 
			// 		$numChk9['value'] =  $numbKey9[3].$numbKey9[4].$numbKey9[5]; 
			// 	}
			// 	if( $numbKey9[4]  ==  $numbKey9[5] && $numbKey9[5] == $numbKey9[6] ){ 
			// 		$numChk9['value'] =  $numbKey9[4].$numbKey9[5].$numbKey9[6]; 
			// 	} 
			// 	if(!empty($numChk9)){  
			// 		$numChk9['numb'] =  $vals['product_phone'];
			// 		$numChk9['pp'] =  $vals['pp'];
			// 		$numChk9['id'] =  $vals['product_id'];  
			// 		$condition9[$vals['product_id']][$vals['pp']]  = $numChk9;  
			// 		$product_id .= $vals['product_id'].',';
			// 	}
            // }  
       
            #case11
            #xxx1221
			if(isset($approve['c11'])){
				$numbKey11 = array();
				$numChk11 = array();   
				$limit11 = 7;    
				$position11 = -7;  
				for($i=0; $i < $limit11 ;$i++){ 
						$round =  $position11 + $i; 
						$numb = substr($vals['pp'],$round,1); 
						$numbKey11[$i] = $numb;   
				}   
				// if( $numbKey11[0]  ==  $numbKey11[3] && $numbKey11[1] == $numbKey11[2] ){ 
				// 	$numChk11['value'] =  $numbKey11[0].$numbKey11[1].$numbKey11[2].$numbKey11[3]; 
				// }
				// if( $numbKey11[1]  ==  $numbKey11[4] && $numbKey11[2] == $numbKey11[3] ){ 
				// 	$numChk11['value'] =  $numbKey11[1].$numbKey11[2].$numbKey11[3].$numbKey11[4]; 
				// }
				// if( $numbKey11[2]  ==  $numbKey11[5] && $numbKey11[3] == $numbKey11[4] ){ 
				// 	$numChk11['value'] =  $numbKey11[2].$numbKey11[3].$numbKey11[4].$numbKey11[5]; 
				// } 
				if( $numbKey11[3]  ==  $numbKey11[6] && $numbKey11[4] == $numbKey11[5] ){ 
					$numChk11['value'] =  $numbKey11[3].$numbKey11[4].$numbKey11[5].$numbKey11[6]; 
				}
			
				if(!empty($numChk11)){  
					$numChk11['numb'] =  $vals['product_phone'];
					$numChk11['pp'] =  $vals['pp'];
					$numChk11['id'] =  $vals['product_id'];  
					$numChk11['monthly'] =  $vals['monthly_status'];  
					$condition11[$vals['product_id']][$vals['pp']]  = $numChk11;  
                    $product_id .= $vals['product_id'].',';
                    
				}
			}   
        }   
        

	    #ส่งค่ากลับ
	    $ret['condition1'] = $condition1;
	    $ret['condition2'] = $condition2;
	    $ret['condition3'] = $condition3;
	    $ret['condition4'] = $condition4;
	    $ret['condition5'] = $condition5;
	    $ret['condition6'] = $condition6;
	    $ret['condition7'] = $condition7;
	    $ret['condition8'] = $condition8;
	    # $ret['condition9'] = $condition9;
        # $ret['condition10']= $condition10;   
        $ret['condition11']= $condition11; 
	    $ret['product_id'] = $product_id;  
		
 	  	return $ret;
	 } 

	public function prepare_Byset_filter_condition($case,$approve){
        
		#(getProductByCategoryBySet)
		#case1 
		if(isset($approve['c1']) && !empty($case['condition1'])){ 
			#ลูปลบกลุ่มข้อมูลที่มีไม่ถึง 2 เบอร์
			$resultCase1 = array();  
				foreach($case['condition1'] as $index => $valz ){  
					$len = count($valz); 
					if($len  < 2){ 	
						unset($case['condition1'][$index]);
					}else{  
						#จัดกลุ่มตามราคา
						$price = 0;
						foreach($valz as $key => $value){  
							if($value['price'] >  $price){
								$price =  $value['price'];
							} 
						} 
						foreach($valz as $key => $value){   
							$case['condition1'][$index][$key]['price'] = $price;    
						}   
					} 
				} 
				$ret['resultCase1'] = $case['condition1'];  
		}   
		
		#case2 
		if(isset($approve['c2']) && !empty($case['condition2'])){

            #ลูปลบกลุ่มข้อมูลที่มีไม่ถึง 2 เบอร์  
            $resultCase2 = array(); 
			foreach($case['condition2'] as $index => $valz ){ 
                if(count($case['condition2'][$index]) < 2){
                    unset($case['condition2'][$index]); 
                }else{
                    $setPrice = 0;
					foreach($valz as $key => $value){  
					  if($value['price'] >  $setPrice){
						  $setPrice  =  $value['price'];
					  } 
					} 
					foreach($valz as $key => $value){  
					  $case['condition2'][$index][$key]['price'] = $setPrice;    
					}   
                }
            }
            $ret['resultCase2'] = $case['condition2']; 

           
        }

     
		#case3 
		if(isset($approve['c3']) && !empty($case['condition3'])){ 
			#part1 #ลูปลบกลุ่มข้อมูลที่มีไม่ถึง 2 เบอร์
            $resultCase3 = array(); 
			foreach($case['condition3'] as $index => $valz ){    
				$len = count($valz); 
				if($len  < 2){  
					unset($case['condition3'][$index]);
				}else{  
					$price = 0;
					foreach($valz as $key => $value){  
						if($value['price'] >  $price){
							$price =  $value['price'];
						} 
					} 
					foreach($valz as $key => $value){  
						$case['condition3'][$index][$key]['dprice'] = $price;    
					}   
				}  
             }   
             
			#part2 #กรองข้อมูล 2 หลักด้านหน้า
			foreach($case['condition3'] as $keys => $valp){   
				foreach($valp as $key => $gg){  
					$lastKey = $key -1; 
					$value['st'] = substr($gg['value'],0,1);
					$value['nd'] = substr($gg['value'],1,1); 
                    $resc = $value['nd'].''.$value['st']; 
                    $resultCase3[$gg['pp']][$keys]['id'] = $gg['id'];
                    $resultCase3[$gg['pp']][$keys]['numb'] = $gg['numb'];
                    $resultCase3[$gg['pp']][$keys]['sumber'] = $gg['sumber']; 
                    $resultCase3[$gg['pp']][$keys]['comment'] = $gg['comment']; 
                    $resultCase3[$gg['pp']][$keys]['network'] = $gg['network']; 
                    $resultCase3[$gg['pp']][$keys]['discount'] = $gg['discount']; 
                    $resultCase3[$gg['pp']][$keys]['grade'] = $gg['grade'];   
                    $resultCase3[$gg['pp']][$keys]['p_price'] = $gg['p_price'];   
                    $resultCase3[$gg['pp']][$keys]['value'] = $gg['value'];
                    $resultCase3[$gg['pp']][$keys]['price'] = $gg['dprice']; 
                    $resultCase3[$gg['pp']][$keys]['monthly'] = $gg['monthly']; 
                    $resultCase3[$gg['pp']][$keys]['pp'] = $gg['pp']; 
                    $resultCase3[$gg['pp']][$keys]['flip'] = $resc; 
				}   
             }  
             foreach($resultCase3 as $key => $valp){
                 if(count($resultCase3[$key]) > 1){
                     foreach($valp as $find){  
                        if(!isset($resultCase3[$key][$find['flip']])){
                            unset($resultCase3[$key]);
                        }
                     }
                 }else{
                    unset($resultCase3[$key]);
                 }
             }
             $ret['resultCase3'] = $resultCase3; 
        }


    #case4 
		if(isset($approve['c4']) && !empty($case['condition4'])){ 
			#ลูปลบกลุ่มข้อมูลที่มีไม่ถึง 2 เบอร์
			$resultCase4 = array();
			foreach($case['condition4'] as $index => $valz ){   
			$len = count($valz); 
			if($len  < 2){
			    unset($case['condition4'][$index]);
			   } 
      } 
  
			foreach($case['condition4'] as $keys => $valp){    
				foreach($valp as $key => $gg){ 
				   $rescArr= array();
				   $lastKey = $key -1; 
				   $value['st'] = substr($gg['pp'],7,1);
				   $value['nd'] = substr($gg['pp'],8,1); 
				   $resc =  $gg['value'].$value['nd'].$value['st'];  
				   $rescArr[] = substr($gg['pp'],7,1);
				   $rescArr[] = substr($gg['pp'],8,1); 
				   sort($rescArr);
				   $sort =  $rescArr[0].$rescArr[1];   
			   		foreach($case['condition4'][$gg['value']] as $index => $aa ){   
					   if($gg['id'] != $aa['id']){   
						   if( $aa['pp'] == $resc ){    
							   $oldSort = $sort;  
							   $resultCase4[$gg['value']][$sort][$key]['id'] = $gg['id'];
							   $resultCase4[$gg['value']][$sort][$key]['numb'] = $gg['numb'];   
							   $resultCase4[$gg['value']][$sort][$key]['sumber'] = $gg['sumber']; 
                               $resultCase4[$gg['value']][$sort][$key]['comment'] = $gg['comment']; 
                               $resultCase4[$gg['value']][$sort][$key]['discount'] = $gg['discount']; 
							   $resultCase4[$gg['value']][$sort][$key]['network'] = $gg['network']; 
							   $resultCase4[$gg['value']][$sort][$key]['grade'] = $gg['grade']; 
							   $resultCase4[$gg['value']][$sort][$key]['p_price'] = $gg['p_price']; 
							   $resultCase4[$gg['value']][$sort][$key]['value'] = $gg['value'].$sort;  
							   $resultCase4[$gg['value']][$sort][$key]['pp'] = $gg['pp']; 
							   $resultCase4[$gg['value']][$sort][$key]['flip'] = $resc; 
							   $resultCase4[$gg['value']][$sort][$key]['port'] = $sort;  
							   $resultCase4[$gg['value']][$sort][$key]['oldPrice'] = $aa['price']; 
							   $resultCase4[$gg['value']][$sort][$key]['monthly'] = $gg['monthly']; 
						   }   
					   } 
					}   
        }
    
			  if(!empty($resultCase4[$gg['value']][$sort])){
				   if(count($resultCase4[$gg['value']][$sort]) < 2){  
					   unset($resultCase4[$gg['value']][$sort]);
				   }  
        } 
      } 

       
			foreach($resultCase4 as $index => $value){
				foreach($value as $key => $val){  
				    $price = 0;  
			   		foreach($resultCase4[$index][$key] as $keyPrice => $var){ 
					   if($var['oldPrice'] >  $price){
						   $price =  $var['oldPrice'];
					   }   
				    }   
					foreach($resultCase4[$index][$key] as $keyId => $var){  
						$resultCase4[$index][$key][$keyId]['price'] =  $price;
					} 
			    } 
			}
      $ret['resultCase4'] = $resultCase4;
    }
    
		#case5 
		if(isset($approve['c5']) && !empty($case['condition5'])){ 
			#ลูปลบกลุ่มข้อมูลที่มีไม่ถึง 2 เบอร์
			$resultCase5 = array(); 
			foreach($case['condition5'] as $index => $valz ){ 
				$len = count($valz);    
				if($len  < 2){    
					unset($case['condition5'][$index]);
				} else {  
					$price = 0;
					foreach($valz as $key => $value){  
						if($value['price'] >  $price){
							$price =  $value['price'];
						} 
					} 
					foreach($valz as $key => $value){  
						$case['condition5'][$index][$key]['price'] = $price;    
					}   
				}
			}  
			foreach($case['condition5'] as $keys => $valp){   
				foreach($valp as $key => $gg){  
					$lastKey = $key -1;  
					$resc =  $gg['value']; 
					foreach($case['condition5'][$gg['value']] as $index => $aa ){   
						if($aa['pp'] == $resc && $gg['id'] != $aa['id'] ){ 
							$resultCase5[$gg['value']][$key]['id'] = $gg['id']; 
							$resultCase5[$gg['value']][$key]['numb'] = $gg['numb'];    
							$resultCase5[$gg['value']][$key]['price'] = $gg['price'];
							$resultCase5[$gg['value']][$key]['sumber'] = $gg['sumber']; 
							$resultCase5[$gg['value']][$key]['comment'] = $gg['comment']; 
                            $resultCase5[$gg['value']][$key]['network'] = $gg['network']; 
                            $resultCase5[$gg['value']][$key]['discount'] = $gg['discount']; 
							$resultCase5[$gg['value']][$key]['grade'] = $gg['grade'];  
							$resultCase5[$gg['value']][$key]['p_price'] = $gg['p_price'];  
							$resultCase5[$gg['value']][$key]['val'] = $gg['value'];  
							$resultCase5[$gg['value']][$key]['pp'] = $gg['pp'];   
							$resultCase5[$gg['value']][$key]['value'] = $gg['pp'];  
							$resultCase5[$gg['value']][$key]['monthly'] = $gg['monthly'];  
						} 
					}  
				}  
				if(!empty($resultCase5[$gg['value']])){
					if(count($resultCase5[$gg['value']]) < 2){  
						unset($resultCase5[$keys]); 
					} 
				}  	
			}
            $ret['resultCase5'] = $case['condition5'];
		}

		#case6
		if(isset($approve['c6']) && !empty($case['condition6'])){  
			$ret['resultCase6'] = $case['condition6'];
		}

		#case7
		if(isset($approve['c7']) && !empty($case['condition7'])){  
			$ret['resultCase7'] = $case['condition7'];
		}

		#case8
		if(isset($approve['c8']) && !empty($case['condition8'])){  
			$ret['resultCase8'] = $case['condition8'];
		}

		// #case9
		// if(isset($approve['c9']) && !empty($case['condition9'])){  
		// 	$ret['resultCase9'] = $case['condition9'];
		// }

		// #case10
		// if(isset($approve['c10']) && !empty($case['condition10'])){  
		// 	$ret['resultCase10'] = $case['condition10'];
        // }

        #case11
		if(isset($approve['c11']) && !empty($case['condition11'])){  
			$ret['resultCase11'] = $case['condition11'];
		}
		 
		return $ret;
	 }

	public function insert_Byset_category($case,$approve){
			#(getProductByCategoryBySet)
			#part1 category id = 3 
			$idArr_1 = array(); 
			$category = 3; 
			#case1
			if(isset($approve['c1']) && !empty($case['resultCase1'])){ 
				foreach($case['resultCase1'] as $keys => $vals){
					$ii= 0; 
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr_1[$kk['id']])){ $idArr_1[$kk['id']] = $kk['id']; }  
						$func_id = 1;
						$group = $kk['value'];
						$sort_by = 0;
						$priority = $ii; 
						$price =  $kk['price'];
						$id = $kk['id']; 
						$number = $kk['numb']; 
						$sumber = $kk['sumber']; 
                        $comment = $kk['comment'];   
                        $discount = $kk['discount'];   
						$network = $kk['network'];   
						$grade = $kk['grade'];  
						$monthly = $kk['monthly'];  
						$p_price = $kk['p_price'];  

						$listBer[] = array( 'category' => $category,
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'group_price' =>($price),
											'product_id' => ($id),
											'product_phone' => ($number),
											'product_sumber' => ($sumber),
                                            'product_comment' => ($comment),
                                            'product_discount' => ($discount),
											'product_network' => ($network),
											'product_grade' => ($grade),
											'product_price' => ($p_price),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				}
			}
			#case2
			if(isset($approve['c2'])  && !empty($case['resultCase2'])){ 
				foreach($case['resultCase2'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr_1[$kk['id']])){ $idArr_1[$kk['id']] = $kk['id']; } 
						$func_id = 2;
						$group = $kk['pp'];
						$sort_by = 0;
						$priority = $ii;
						$price =  $kk['price'];
						$id = $kk['id']; 
						$number = $kk['numb']; 
						$sumber = $kk['sumber']; 
                        $comment = $kk['comment'];   
                        $discount = $kk['discount'];  
						$network = $kk['network'];   
						$grade = $kk['grade'];  
						$monthly = $kk['monthly'];  
						$p_price = $kk['p_price'];  
						$listBer[] = array( 'category' => $category,
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'group_price' =>($price),
											'product_id' => ($id),
											'product_phone' => ($number),
											'product_sumber' => ($sumber),
                                            'product_comment' => ($comment),
                                            'product_discount' => ($discount),
											'product_network' => ($network),
											'product_grade' => ($grade), 
											'product_price' => ($p_price),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				}
			}
			#case3
			if(isset($approve['c3'])  && !empty($case['resultCase3'])){ 
				foreach($case['resultCase3'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr_1[$kk['id']])){ $idArr_1[$kk['id']] = $kk['id']; }  
						$func_id = 3;
						$group = $kk['pp']; 
						$sort_by = 0;
						$priority = $ii;
						$price =  $kk['price'];
						$id = $kk['id']; 
						$number = $kk['numb']; 
						$sumber = $kk['sumber']; 
                        $comment = $kk['comment'];   
                        $discount = $kk['discount']; 
						$network = $kk['network'];   
						$grade = $kk['grade'];  
						$p_price = $kk['p_price'];  
						$monthly = $kk['monthly'];  
							 
						$listBer[] = array( 'category' => $category,
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'group_price' =>($price),
											'product_id' => ($id),
											'product_phone' => ($number),
											'product_sumber' => ($sumber),
                                            'product_comment' => ($comment),
                                            'product_discount' => ($discount),
											'product_network' => ($network),
											'product_grade' => ($grade),
											'product_price' => ($p_price),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				}
			}
      #case4

			if(isset($approve['c4'])  && !empty($case['resultCase4'])){ 
				foreach($case['resultCase4'] as $keys => $vals){  
					foreach( $vals as $cc => $kk){ 
						foreach( $kk as $tt => $mm){
							if(!isset($idArr_1[$mm['id']])){ $idArr_1[$mm['id']] = $mm['id']; } 
							$func_id = 4;
							$group = $mm['value'];
							$sort_by = $mm['port'];
							$priority = 0;
							$price = $mm['price'];
							$number = $mm['numb']; 
							$sumber = $mm['sumber']; 
                            $comment = $mm['comment'];   
                            $discount = $mm['discount']; 
							$network = $mm['network'];   
							$grade = $mm['grade'];  
							$p_price = $mm['p_price'];   
							$monthly = $mm['monthly'];   
							$listBer[] = array( 'category' => $category,
													'func_id' => ($func_id),
												'lover_group' => ($group),
												'sort' => ($sort_by),
												'love_priority' =>($priority),
												'group_price' =>($price),
												'product_id' => ($id),
												'product_phone' => ($number),
												'product_sumber' => ($sumber),
                                                'product_comment' => ($comment),
                                                'product_discount' => ($discount),
												'product_network' => ($network),
												'product_grade' => ($grade),
												'product_price' => ($p_price),
												'monthly_status' => ($monthly),
												'status' => 'auto' );   
							$ii++;
						}
					} 
				} 
			}
			#case5
			if(isset($approve['c5'])  && !empty($case['resultCase5'])){ 
				foreach($case['resultCase5'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr_1[$kk['id']])){ $idArr_1[$kk['id']] = $kk['id']; } 
						$func_id = 5;
						$group = $kk['value'];
						$sort_by = 0;
						$priority = $ii;
						$price = $kk['price'];
						$number = $kk['numb']; 
						$sumber = $kk['sumber']; 
                        $comment = $kk['comment'];  
                        $discount = $kk['discount'];    
						$network = $kk['network'];   
						$grade = $kk['grade'];  
						$p_price = $kk['p_price'];  
						$monthly = $kk['monthly'];  
						$listBer[] = array( 'category' => $category,
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'group_price' =>($price),
											'product_id' => ($id),
											'product_phone' => ($number),
											'product_sumber' => ($sumber),
                                            'product_comment' => ($comment),
                                            'product_discount' => ($discount),
											'product_network' => ($network),
											'product_grade' => ($grade),
											'product_price' => ($p_price),
											'monthly_status' => ($monthly),
											'status' => 'auto');   
						$ii++;
					} 
				}
			}   
			#insert category 3 
			if(!empty($idArr_1)){ 
				$idIn =''; 
				foreach($idArr_1 as $vals){  $idIn .= $vals.','; } 
				$idIn = substr($idIn,0,-1); 
				$table = "berproduct";
				$set = "product_category = CONCAT(product_category,:cate_id )";
				$where = " product_id IN (".$idIn.") ";
				$value = array( ":cate_id" => ',3,' ); 
				$ret['cate3'] = self::$dbcon->update_prepare($table, $set, $where,$value); 
				$ret['lover3'] = self::$dbcon->multiInsert('berproduct_alover',$listBer); 
				$idArr_1 = array_unique($idArr_1);
				$table = "berproduct_category";
				$set = "bercate_total =  :cate_id ";
				$where = "bercate_id = 3 ";
				$value = array( ":cate_id" => count($idArr_1) ); 
				$ret['count3'] = self::$dbcon->update_prepare($table, $set, $where,$value); 
		 	}  
			#part category id = 4
			$idArr2 = array();  
			$category = 4;
			#case6 
			if(isset($approve['c6'])  && !empty($case['resultCase6'])){ 
				foreach($case['resultCase6'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr2[$kk['id']])){ $idArr2[$kk['id']] = $kk['id'];  }   
						$func_id = 6;
						$group = $kk['value'];
						$sort_by = $kk['id'];
						$priority = $ii;
						$number = $kk['numb']; 
						$monthly = $kk['monthly']; 
						$listBer2[] = array('category' => ($category),
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'product_phone' => ($number),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				}	
			}
			#case7
			if(isset($approve['c7'])  && !empty($case['resultCase7'])){ 
				foreach($case['resultCase7'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr2[$kk['id']])){
							$idArr2[$kk['id']] = $kk['id']; 
						}   
						$func_id = 7;
						$group = $kk['value'];
						$sort_by = $kk['id'];
						$priority = $ii;
						$number = $kk['numb']; 
						$monthly = $kk['monthly']; 
						$listBer2[] = array('category' => ($category),
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'product_phone' => ($number),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				} 
			}
			#case8
			if(isset($approve['c8'])  && !empty($case['resultCase8'])){ 
				foreach($case['resultCase8'] as $keys => $vals){ 
					$ii= 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr2[$kk['id']])){
							$idArr2[$kk['id']] = $kk['id']; 
						}  
						$category = 4;
						$func_id = 8;
						$group = $kk['value'];
						$sort_by = $kk['id'];
						$priority = $ii;
						$number = $kk['numb']; 
						$monthly = $kk['monthly']; 
						$listBer2[] = array('category' => ($category), 
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'product_phone' => ($number),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				} 
			}
			// #case9
			// if(isset($approve['c9'])  && !empty($case['resultCase9'])){  
			// 	foreach($case['resultCase9'] as $keys => $vals){ 
			// 		$ii = 0;
			// 		foreach( $vals as $cc => $kk){ 
			// 			if(!isset($idArr2[$kk['id']])){ $idArr2[$kk['id']] = $kk['id'];  }   
			// 			$func_id = 9;
			// 			$group = $kk['value'];
			// 			$sort_by = $kk['id'];
			// 			$priority = $ii;
			// 			$number = $kk['numb']; 
			// 			$listBer2[] = array('category' => ($category), 
			// 								'func_id' => ($func_id),
			// 								'lover_group' => ($group),
			// 								'sort' => ($sort_by),
			// 								'love_priority' =>($priority),
			// 								'product_phone' => ($number),
			// 								'status' => 'auto' );   
			// 			$ii++;
			// 		} 
			// 	}
			// }
			// #case10
			// if(isset($approve['c10'])  && !empty($case['resultCase10'])){ 
			// 	foreach($case['resultCase10'] as $keys => $vals){ 
			// 		$ii= 0;
			// 		foreach( $vals as $cc => $kk){ 
			// 			if(!isset($idArr2[$kk['id']])){
			// 				$idArr2[$kk['id']] = $kk['id']; 
			// 			}   
			// 			$func_id = 10;
			// 			$group = $kk['value'];
			// 			$sort_by = $kk['id'];
			// 			$priority = $ii;
			// 			$number = $kk['numb']; 
			// 			$listBer2[] = array('category' => ($category),
			// 								'func_id' => ($func_id),
			// 								'lover_group' => ($group),
			// 								'sort' => ($sort_by),
			// 								'love_priority' =>($priority),
			// 								'product_phone' => ($number),
			// 								'status' => 'auto' );   
			// 			$ii++;
			// 		} 
			// 	} 
            // }

            #case11
			if(isset($approve['c11'])  && !empty($case['resultCase11'])){  
				foreach($case['resultCase11'] as $keys => $vals){ 
					$ii = 0;
					foreach( $vals as $cc => $kk){ 
						if(!isset($idArr2[$kk['id']])){ $idArr2[$kk['id']] = $kk['id'];  }   
						$func_id = 11;
						$group = $kk['value'];
						$sort_by = $kk['id'];
						$priority = $ii;
						$number = $kk['numb']; 
						$monthly = $kk['monthly']; 
						$listBer2[] = array('category' => ($category), 
											'func_id' => ($func_id),
											'lover_group' => ($group),
											'sort' => ($sort_by),
											'love_priority' =>($priority),
											'product_phone' => ($number),
											'monthly_status' => ($monthly),
											'status' => 'auto' );   
						$ii++;
					} 
				}
			}

			if(!empty($idArr2)){ 
				$idIn2 = '';
				foreach($idArr2 as $vals){ 
					$idIn2 .= $vals.',';
				}  
				$idIn2 = substr($idIn2,0,-1); 
				$table = "berproduct";
				$set = "product_category = CONCAT(product_category,:cate_id )";
				$where = "product_id IN (".$idIn2.")";
				$value = array(
					":cate_id" => ',4,' 
				); 
				$idArr2 = array_unique($idArr2); 
				$ret['cate4'] = self::$dbcon->update_prepare($table, $set, $where,$value); 
				$ret['lover4'] = self::$dbcon->multiInsert('berproduct_alover',$listBer2); 
				$table = "berproduct_category";
				$set = "bercate_total =  :cate_id ";
				$where = "bercate_id = 4 ";
				$value = array(
					":cate_id" => count($idArr2)
				); 
				$ret['count4'] = self::$dbcon->update_prepare($table, $set, $where,$value); 
			}

		return $ret;
	 }
 
	public function getProductByCategoryGrade(){ 
		#ดึงเบอร์ทั้งหมด โดย pp = 7 หลักหลัง 
		$sql = "SELECT product_id,product_phone, MID(product_phone,4, 10) as pp  FROM berproduct WHERE product_grade = '' ";  
		$resNumber = self::$dbcon->fetchAll($sql,[]);
		if(!empty($resNumber)){ 
			#ดึงข้อมูลเกรดมาเซ็ตค่าก่อน	
			#เช่น  $grade[700]['name'] = A+ 
			$sql = "SELECT * FROM berproduct_grade ORDER BY grade_priority DESC";
			$result = self::$dbcon->fetchAll($sql,[]);
			if(!empty($result)){
				foreach($result as $key =>$val){
					$grade[$val['grade_min']]['name']= $val['grade_name'];
					$grade[$val['grade_min']]['min'] = $val['grade_min'];
					$grade[$val['grade_min']]['max'] = $val['grade_max'];
				}
			}  
			#ดึงเปอร์เซ็นของคู่เลข มาเซ็ตค่าไว้ 
			#เช่น  $prophecy[29] = 69%
			$sql ="SELECT prophecy_id,prophecy_percent,prophecy_numb FROM berpredict_prophecy";
			$result = self::$dbcon->fetchAll($sql,[]);
			if(!empty($result)){
				foreach($result as $key =>$val){
					$prophecy[$val['prophecy_numb']] = $val['prophecy_percent'];
				}
			} 
			#เริ่มการกรองข้อมูล
			$group = array();
			foreach($resNumber as $key =>$val){ 
				#แยกข้อมูลชุดละ 2 เลขจาก 7 หลักหลังออกมาแล้วนำไป + กัน
				$pos[1] = substr($val['pp'],0,2);  
				$pos[2] = substr($val['pp'],1,2);  
				$pos[3] = substr($val['pp'],2,2); 
				$pos[4] = substr($val['pp'],3,2);
				$pos[5] = substr($val['pp'],4,2); 
				$pos[6] = substr($val['pp'],5,2);
				#แยกข้อมูลชุดละ 2 เลขจาก 7 หลักหลังออกมาจะได้เป็น เลข prophecy_numb จึงนำไปเทียบเป็น prophecy_percent แล้วมานำเลขทั้งหมดมา + กันจะได้ค่า range
				$range = $prophecy[$pos[1]]+$prophecy[$pos[2]]+$prophecy[$pos[3]]+$prophecy[$pos[4]]+$prophecy[$pos[5]]+$prophecy[$pos[6]]; 
				#เมื่อได้ range ของเบอร์แล้วนำไปแปลงค่าจาก 0/600 เป็น 0/1000
				#นำค่า range มาหาเป็น % ของ 600 ก่อน 
				$percent = ($range / 6);
				#ค่า $percent นำมาหาเป็นค่า range ของ 1000 จะได้เป็นผลลัพธ์แล้วนำไปเช็คเกรด
				$result = round(((1000 * (float)$percent) / 100));  
				#วนลูปเช็คเงื่อนไขว่าอยู่เกรดไหนเริ่มจาก เกรดที่ค่าต่ำสุดไปมากสุดถ้าเจอแล้วให้ออกจากลูป  
				foreach($grade as $mygrade){    
					if($result <= $mygrade['max']){ 
						$result = $mygrade['name'];
						$group[$mygrade['name']] = (isset($group[$mygrade['name']]))? $group[$mygrade['name']] .= $val['product_id']."," :$val['product_id'].",";
						break;
					} 
				}  
			} 
			if(!empty($group)){
				#อัพเดทเกรดแต่ละหมวดหมู่ 
				foreach($group as $key => $var){
					$id = substr($var,0,-1);   
					$table = "berproduct";
					$set = "product_grade = :grade,product_category = REPLACE(product_category,',,' , ',')";
					$where = " product_id IN (".$id.") ";
					$value = array( ":grade" => $key ); 
					$updates[$key] = self::$dbcon->update_prepare($table, $set, $where,$value);  
				} 
			}
		}
		return $updates; 
	 }	

	public function getPredictWantUnwantArr(){
		GLOBAL $predictArr;
		$sql ="SELECT numb.numb_id, 
			numb.numb_category_id, 
			numb.numb_name, 
			numb.numb_number, 
			numb.numb_unwanted, 
			numb.priority, 
			cate.numbcate_id as cate_id, 
			cate.numbcate_want, 
			cate.numbcate_unwant
			FROM berpredict_numb  as numb
			LEFT JOIN berpredict_numbcate as cate 
			ON cate.numbcate_id = numb.numb_category_id
			ORDER BY cate.numbcate_id  ASC";
		$pdArr = self::$dbcon->fetchAll($sql,[]);
		if(!empty($pdArr)){
			$predictArr = array();
			foreach($pdArr as $nnn){
				if(!isset($predictArr[$nnn['cate_id']]['id'])){
					$predictArr[$nnn['cate_id']]['id'] = $nnn['cate_id'];
					$predictArr[$nnn['cate_id']]['cate_id'] = $nnn['cate_id'];
					$predictArr[$nnn['cate_id']]['wanted'] = explode(",",$nnn['numbcate_want']); 
					$predictArr[$nnn['cate_id']]['unwanted'] =  explode(",",$nnn['numbcate_unwant']); 
				}
				if(!isset($predictArr[$nnn['numb_id']]['id'])){
					$predictArr[$nnn['numb_id']]['id'] = $nnn['numb_id'];
					$predictArr[$nnn['numb_id']]['cate_id'] = $nnn['cate_id'];
					$predictArr[$nnn['numb_id']]['wanted'] = explode(",",$nnn['numb_number']);
					$predictArr[$nnn['numb_id']]['unwanted'] =  explode(",",$nnn['numb_unwanted']);  
				}
			}
		}
	 }
	
	 public static function getProductByCategoryPredict($pp){ 
		GLOBAL $predictArr;
		if(isset($pp)){
		    $improve = ","; 
			foreach($predictArr as $predVal){ 
				$founded = "";   
				$wanted = $predVal['wanted'];
				$unwanted = $predVal['unwanted'];
				if(!empty($unwanted)){
					foreach($unwanted as $unw){
						if($unw=="") continue;
						$founded = strpos($pp,$unw);
						if($founded) break;
					} 
				}
				if(!empty($wanted) && $founded==""){
					foreach($wanted as $wan){
						if($wan=="") continue;
						$required = strpos($pp,$wan);   
						if($required){
							$improve .= $predVal['id'].","; 
							break;
						} 
					}
				}
			}
		}
		return $improve; 
	 }

	 /**
 * Create array from a range of cells
 *
 * @param string $pRange Range of cells (i.e. "A1:B10"), or just one cell (i.e. "A1")
 * @param mixed $nullValue Value returned in the array entry if a cell doesn't exist
 * @param boolean $calculateFormulas Should formulas be calculated?
 * @param boolean $formatData Should formatting be applied to cell values?
 * @param boolean $returnCellRef False - Return a simple array of rows and columns indexed by number counting from zero
 *                               True - Return rows and columns indexed by their actual row and column IDs
 * @return array 
 */
	public function import_by_excel_productber($path){ 

		require_once dirname(__DIR__) .'/classes/PhpSpreadsheet/vendor/autoload.php';
	
		// $sqlNetBuffer = 'set global net_buffer_length=1000000';
		// $querySet = self::$dbcon->fetchAll($sqlNetBuffer);
		// $sqlMaxAllowed = 'set global max_allowed_packet=1000000000';
		// $querySet = self::$dbcon->fetchAll($sqlMaxAllowed);

		$spreadsheet = IOFactory::load($path);  

		$sheetData = $spreadsheet->setActiveSheetIndex(0);	
        $highestRow = $sheetData->getHighestRow();		 
		$highestColumn = $sheetData->getHighestColumn();
		$dataExcel = $sheetData->rangeToArray('A1:' . 'L' . $highestRow, null, true, true, false);	
	 
        $sql = 'SELECT * FROM bernetwork';
		$resultNetwork = self::$dbcon->query($sql);
		  
		if(!empty($resultNetwork)){
			foreach($resultNetwork as $keys => $val){  
				$networkArr[$val['network_name']] = $val['network_name']; 
				if($val['network_name']  == "TRUE"){	
					$networkArr[1] = $val['network_name']; 	
				}	
			}
		}
	 

		$sql ="SELECT * FROM berpredict_numb ORDER BY numb_category_id ASC, numb_id ASC ";
		$resultPredict = self::$dbcon->fetchAll($sql,[]);
		/* ดึงข้อมูล PredictCate Want & Unwant comment */
		$this->getPredictWantUnwantArr();

		
		/* เช็คความหมายจาก comment */
		$sqlSum = "SELECT * FROM berpredict_sum ORDER BY predict_numb ";
		$resultArr = self::$dbcon->query($sqlSum);
		if(!empty($resultArr)){
			foreach($resultArr as $key => $value){
				$sumber[$value['predict_numb']]['name'] = $value['predict_name'];
			}
		}

        $dataSql = count($dataExcel);
		$listBer = array();
		$chkArr = array();
		$failed = array();
		$fail_text = "";
	    for ($i=1; $i < $dataSql; $i++) { 
			
            $tel = trim(FILTER_VAR($dataExcel[$i][0],FILTER_SANITIZE_NUMBER_INT));  
            if(empty($tel)){  
				# $failed[$i] = "Empty";
				continue; 
			}  
            $exNet =  strtoupper(trim($dataExcel[$i][3]));
			$networkName = $networkArr[$exNet]; 
			$pp = substr($tel,3,10);
            #จัดหมวดหมู่จากความหมาย #automatic  
			$mood = strtolower(trim($dataExcel[$i][5]));
			$mood = ($mood != "" && $mood != "no"  && $mood != "n"  )?"yes":"";
			$comment = ($dataExcel[$i][8] != "")?$dataExcel[$i][8]:$sumber[$dataExcel[$i][1]]['name'];

			$improve = self::getProductByCategoryPredict($pp); 
            if(!isset($chkArr[$tel])){ 
                $listBer[] = array('product_phone' => $tel,
                        'product_sumber' => $dataExcel[$i][1],
                        'product_price' => $dataExcel[$i][2],
                        'product_network' => $networkName, 
                        'product_category' => ','.$dataExcel[$i][4].',',
                        'default_cate' => ','.$dataExcel[$i][4].',',
                        'product_pin' => $mood,
                        'product_sold' => $dataExcel[$i][6],
                        'product_new' => $dataExcel[$i][7],
                        'product_comment' => $comment,
                        'product_grade' => strtoupper($dataExcel[$i][9]),
                        'product_discount' => $dataExcel[$i][10],
						'product_id' => $i,
						'product_improve' => $improve,
						'monthly_status' => $dataExcel[$i][11]
                    );  
                $chkArr[$tel] =  $tel;  
            } else {
				$failed[$i] = $tel;
				$fail_text .= (count($failed)).": ".$tel;
			} 
		} 
		
		#Delete data before new upload   
		$table ='berproduct'; 
		$where ='display != :display';
		$value = [ ':display' => 'something' ];	 
 
		$delData = self::$dbcon->deletePrepare($table, $where, $value);	
		$result = self::$dbcon->multiInsert('berproduct',$listBer);
		$result['duplicate'] = ($fail_text != "")?"<div>พบเบอร์ที่ซ้ำกัน</div>".$fail_text:"";
		
		// $result['duplicate'] = $failed;
 
        return $result;
	 }


    #class export as excel #รับข้อมูลมาเป็น array
	public function exportExcelAllProducts($arr){ 
		#ลบไฟล์ที่เคยมีออก
		 ini_set('memory_limit', '1024M');
		 array_map('unlink', glob(__DIR__.'/excel/*.xlsx'));  
		#เรียกใช้งาน
        require_once dirname(__DIR__) .'/classes/PhpSpreadsheet/vendor/autoload.php';
		global $site_url; 
		$spreadsheet = new Spreadsheet(); 
	
		$sheet = $spreadsheet->getActiveSheet(); 
		#header ประกาศหัวข้อแถวบนสุด
		$spreadsheet->getActiveSheet()->setCellValue('A1', 'number')
					->setCellValue('B1', 'sum')
					->setCellValue('C1', 'price')
					->setCellValue('D1', 'network')
					->setCellValue('E1', 'category')
					->setCellValue('F1', 'VIP')
					->setCellValue('G1', 'sold') 
					->setCellValue('H1', 'new')
					->setCellValue('I1', 'comment')
					->setCellValue('J1', 'grade')
					->setCellValue('K1', 'discount')
					->setCellValue('L1', 'monthly'); 
		 $spreadsheet->getActiveSheet()->fromArray($arr, null, 'A2');   
		 $writer = new Xlsx($spreadsheet);
		 /*  save file to server and create link*/ 
		 $nameId = date('dmyhis');
		 $writer->save(__DIR__.'/excel/exportproduct'.$nameId.'.xlsx');
		 $link = ('excel/exportproduct'.$nameId.'.xlsx');
		return $link;
	} 

	public function checkCategoryDiscount(){
		$sql ="SELECT 	bercate_id,bercate_discount FROM berproduct_category WHERE bercate_discount > 0 ORDER BY bercate_discount ASC";
		$get = self::$dbcon->fetchAll($sql,[]);
		
		if(!empty($get)){
			#ดึงค่าส่วนลดสูงสุดจากหมวดที่ทับซ้อน กรณีที่ข้อมูลทับซ้อนกัน
			#จะไม่ทำส่วนลดทับข้อมูลจาก excel  *** WHERE pro.product_discount = 0 ***
			$discount = 0;
			$id = 0;
			$value = array( [] ); 
			$table = 'pro  INNER JOIN (
							SELECT max(bc.bercate_discount) as discount,bp.product_phone,bp.product_discount,bp.product_category 
							FROM berproduct bp 
							INNER JOIN berproduct_category bc 
								ON bp.product_category LIKE concat("%,",bc.bercate_id,",%")  
							GROUP BY bp.product_phone ASC 
						) as gg ON pro.product_phone = gg.product_phone';
			$set = ' pro.product_discount = if(gg.discount >= '.$discount.' , gg.discount, '.$discount.' )';
			$where = ' pro.product_discount = 0 ';
			
			$result['maincate'] = self::$dbcon->update_prepare(" berproduct ".$table, $set, $where,$value);
			$result['alover'] = self::$dbcon->update_prepare(" berproduct_alover ".$table, $set, $where,$value);

		
		}else{
			$result['message'] = "OK";
			$result['description'] ="empty";
		}
		return $result;
    }
	 
}

?>