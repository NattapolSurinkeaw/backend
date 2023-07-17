<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');


class rooms {
	private $dbcon;
	private $lan_arr;
	private $site_url = ROOT_URL;
	public function __construct()
	{
		$this->dbcon = new DBconnect();
		$data = new getData();
		$this->lan_arr = $data->get_language_array();
    }
    public function get_category(){
		$lan_arr = $this->lan_arr;
		$sql = "SELECT * FROM category ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
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

	public function get_rooms($getpost){
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
			}
		}else {
			$sort='date_created';
		}

		$cate_src = '';
		if(isset($cate)&&$cate != ''&&$cate != 0){
			$cate_src = " AND category LIKE '%,".$cate.",%'";
		}

		$status = '';
		if(isset($getpost['status'])&&$getpost['status'] != ''){
			$status = " AND display = '".$getpost['status']."'";
		}
			
		if(!isset($pagi)||$pagi <= 1||$pagi == ''){
			$lim = "0,".$perpage;
		}else{
			$lim = (($pagi-1)*$perpage).','.$perpage;
		}
		$lan_arr = $this->lan_arr;

		$sql = "SELECT * FROM rooms INNER JOIN (SELECT id FROM rooms WHERE title LIKE '%".$search."%' ".$cate_src." ".$status." GROUP BY id ORDER BY ".$sort." DESC LIMIT ".$lim.")p ON p.id = rooms.id ORDER BY ".$sort." DESC, p.id DESC, FIELD( defaults,'yes')DESC";
		$result=$this->dbcon->query($sql);

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
			$sql = "SELECT * FROM category WHERE cate_id =  '".$cate_id[$i]."' ORDER BY field(defaults,'yes')DESC";
			$result = $this->dbcon->query($sql);
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$catename = $a['cate_name'];
				}
				if($a['language']==$_SESSION['backend_language']){
					$catename = $a['cate_name'];
				}
			}
			$return .= $catename.', ';
		}
		return $return;
	}
}

?>