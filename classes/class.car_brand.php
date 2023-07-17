<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Bangkok');


class car_brand {
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
		$sql = "SELECT * FROM car_brand ORDER BY level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		$result=$this->dbcon->query($sql);

		$category=array();
		$ret=array();

		if (!empty($result)) {
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$category[$a['cate_id']]['defaults']=$a;
				}
				$category[$a['cate_id']][$a['language']]=$a;
			}
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

	public function get_all_category($getpost, $status){
		$pagi  = $getpost['pagi'] ;
		$perpage = $getpost['amount'];
		$search = $getpost['search'];
		$lan_arr = $this->lan_arr;

		if(!isset($pagi)||$pagi <= 1||$pagi == ''){
			$lim = "0,".$perpage;
		}else{
			$lim = (($pagi-1)*$perpage).','.$perpage;
		}

		if (!empty($search)) {
			$sql = "SELECT * FROM car_brand INNER JOIN (SELECT cate_id FROM car_brand WHERE cate_name LIKE '%".$search."%' GROUP BY cate_id)p ON p.cate_id = car_brand.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		}else {
			$sql = "SELECT * FROM car_brand INNER JOIN (SELECT cate_id FROM car_brand GROUP BY cate_id LIMIT ".$lim.")p ON p.cate_id = car_brand.cate_id ORDER BY parent_id, level, priority ASC, position ASC, FIELD( defaults,  'yes')DESC";
		}

		$result=$this->dbcon->query($sql);

		$category=array();
		$ret=array();

		if (!empty($result)) {
			foreach($result as $a){
				if($a['defaults']=='yes'){
					$category[$a['cate_id']]['defaults']=$a;
				}
				$category[$a['cate_id']][$a['language']]=$a;
			}
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

	public function get_cate_radio($res,$type){
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
					if(isset($ret[$c['cate_id']])){
						$ret[$c['parent_id']] .='
								<div class="radio">
									<label>
										<input type="radio" name="parent-id-'.$type.'" id="'.$type.'-cate-'.$c['cate_id'].'" value="'.$c['cate_id'].'">'.$c['cate_name'].'
									</label>
									<div class="radio-children">'.$ret[$c['cate_id']].'</div>
								</div>';
					}else{
						$ret[$c['parent_id']] .='
						<div class="radio">
							<label>
								<input type="radio" name="parent-id-'.$type.'" id="'.$type.'-cate-'.$c['cate_id'].'" value="'.$c['cate_id'].'">'.$c['cate_name'].'
							</label>
						</div>';
					}
				}
			}
		}
		return $ret[0];
	}
}
?>