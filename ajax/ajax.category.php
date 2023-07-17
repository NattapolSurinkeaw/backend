<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.category.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new category();

if(isset($_REQUEST['action'])) {

    switch($_REQUEST['action']){

		case'getallcategory':
			$getpost = array('amount' => 10, 'pagi' => $_REQUEST['pagi'], 'search' => $_REQUEST['search']);
			$result = $mydata->get_all_category($getpost, $_REQUEST['status']);
			$category = $mydata->get_category();

			$table = "category";
			if ($_REQUEST['status'] == 'show') {
				$where = "main_page = 'no' AND menu = 'no' AND display = 'yes' AND defaults = 'yes'";
			}else if ($_REQUEST['status'] == 'hidden') {
				$where = "main_page = 'no' AND display = 'no' AND defaults = 'yes'";
			}else {
				$where = "main_page = 'no' AND menu = 'yes' AND display = 'yes' AND defaults = 'yes'";
			}

			$output = array();
			$i = 0;
			foreach ($result as $value) {
				$output['data'][$i] = $value;
				$output['data'][$i]['parent_name'] = ($category[$value['parent_id']]['cate_name'] == '') ? 'หน้าหลัก' : $category[$value['parent_id']]['cate_name'];
				$i++;
			}

			$output['rows'] = $data->pagination($table,$where);
			echo json_encode($output);
		break;
		case'getcategoryedit':

			$sql = "SELECT * FROM category WHERE cate_id = '".$_REQUEST['id']."' ORDER BY FIELD( defaults,  'yes')DESC";
			$result = $dbcon->query($sql);
		   //ฟังก์ชั่น convertResultPost($result,$defaultColumId) จะแปลงข้อมูลให้อยู่ในระบบแบบภาษาปัจจุบัน $defaultColumId คือ คอลัมที่เป็น id 
			$category = getData::convertResultPost($result,'cate_id');
			//ฟังก์ชั่น current จะทำการดึงข้อมูลอาร์เรย์ ตัวที่ 1 ออกมา ไม่ว่า อินเดก ของอาร์เรย์จะเป็นอะไรก็ตาม
			echo json_encode(current($category));

		break;

		case'getpaginationcategory':

        	$table = "category";
        	if ($_REQUEST['status'] == 'show') {
				$where = "main_page = 'no' AND menu = 'no' AND display = 'yes' AND defaults = 'yes'";
			}else if ($_REQUEST['status'] == 'hidden') {
				$where = "main_page = 'no' AND display = 'no' AND defaults = 'yes'";
			}else {
				$where = "main_page = 'no' AND menu = 'yes' AND display = 'yes' AND defaults = 'yes'";
			}
        	$result = $data->pagination($table,$where);
        	echo ($result);
        break;
        case 'addcategory':
			$sql = " SELECT COUNT(url) AS 'count_url' FROM category WHERE url = '".$_REQUEST['slug']."' ";
        	$count_url = $dbcon->fetch_assoc($sql);

        	if ($count_url == 0) {
				if ($_REQUEST['parentId']==0) {
					$level=0;
				}else {
					$sql = "SELECT level FROM category WHERE cate_id='".$_REQUEST['parentId']."'";
					$level = $dbcon->fetch_assoc($sql);
					$level++;
				}

				$sql = "SELECT MAX(priority) pmax FROM category WHERE parent_id='".$_REQUEST['parentId']."'";
				$priority = $dbcon->fetch_assoc($sql);

				if ($_REQUEST['priority'] == 0 || $_REQUEST['priority'] > $priority) {
					$data_priority = $priority+1;
				}else {
					$data_priority = $_REQUEST['priority'];
				}

				$sql = "SELECT MAX(cate_id) imax FROM category";
				$imax = $dbcon->fetch_assoc($sql);
				$imax++;

				$table = "category";
		        $field = "cate_id,priority,cate_name,url,topic,title,keyword,description,thumbnail,freetag,h1,h2,parent_id,level,display,menu,position,language,defaults";
		        $value = "	'".$imax."',
		        			'".$data_priority."',
		        			'".$_REQUEST['name']."',
		        			'".$_REQUEST['slug']."',
		        			'".$_REQUEST['topic']."',
		        			'".$_REQUEST['title']."',
		        			'".$_REQUEST['keyword']."',
		        			'".$_REQUEST['description']."',
		        			'-',
		        			'".$_REQUEST['freetag']."',
		        			'".$_REQUEST['h1']."',
		        			'".$_REQUEST['h2']."',
		        			'".$_REQUEST['parentId']."',
		        			'".$level."',
		        			'".$_REQUEST['display']."',
		        			'".$_REQUEST['menu']."',
		        			'".$_REQUEST['position']."',
		        			'".$_SESSION['backend_language']."',
		        			'yes'";
		        $res = $dbcon->insert($table, $field, $value);

		        if ($_REQUEST['priority'] <= $priority && $_REQUEST['priority'] != 0) {
					$table = "category";
			        $set = "priority = priority+1";
			        $where = "parent_id = '".$_REQUEST['parentId']."'
			        		AND priority >= '".$_REQUEST['priority']."'
			        		AND cate_id NOT IN (".$imax.") ";
			        $dbcon->update($table, $set, $where);
		        }

		        $result = array('data' => $res, 'id' => $imax);
        	}else if ($count_url > 0) {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
	        echo json_encode($result);
		break;
		case 'editcategory':
			if ($_REQUEST['slug'] == $_REQUEST['currentUrl']) {
				$count_url = 0;
			}else {
				$sql = " SELECT COUNT(url) AS 'count_url' FROM category WHERE url = '".$_REQUEST['slug']."' ";
        		$count_url = $dbcon->fetch_assoc($sql);
			}

			if ($count_url == 0) {
				if ($_REQUEST['parentId']==0) {
					$level=0;
				}else {
					$sql = "SELECT level FROM category WHERE cate_id='".$_REQUEST['parentId']."'";
					$level = $dbcon->fetch_assoc($sql);
					$level++;
				}

				$sql = "SELECT MAX(priority) pmax FROM category WHERE parent_id='".$_REQUEST['parentId']."'";
				$priority = $dbcon->fetch_assoc($sql);

				if ($_REQUEST['submitType'] == 'edit') {
					$table = "category";
			        $set = "cate_name = '".$_REQUEST['name']."',
			        		title = '".$_REQUEST['title']."',
			        		keyword = '".$_REQUEST['keyword']."',
			        		description = '".$_REQUEST['description']."',
			        		topic = '".$_REQUEST['topic']."',
			        		freetag = '".$_REQUEST['freetag']."',
			        		h1 = '".$_REQUEST['h1']."',
			        		h2 = '".$_REQUEST['h2']."',
			        		url = '".$_REQUEST['slug']."',
			        		display = '".$_REQUEST['display']."',
			        		menu = '".$_REQUEST['menu']."',
			        		position = '".$_REQUEST['position']."',
			        		parent_id = '".$_REQUEST['parentId']."',
			        		level = '".$level."'
			        		";
			        $where = "cate_id = '".$_REQUEST['cateId']."'
			        		AND language = '".$_SESSION['backend_language']."'";
			        $res = $dbcon->update($table, $set, $where);

		    	}else if ($_REQUEST['submitType'] == 'add') {
		    		$table = "category";
			        $field = "cate_id,cate_name,thumbnail,url,topic,title,keyword,description,freetag,h1,h2,parent_id,level,display,menu,position,language";
			        $value = "	'".$_REQUEST['cateId']."',
			        			'".$_REQUEST['name']."',
			        			'".$_REQUEST['images']."',
			        			'".$_REQUEST['slug']."',
			        			'".$_REQUEST['topic']."',
			        			'".$_REQUEST['title']."',
			        			'".$_REQUEST['keyword']."',
			        			'".$_REQUEST['description']."',
			        			'".$_REQUEST['freetag']."',
			        			'".$_REQUEST['h1']."',
			        			'".$_REQUEST['h2']."',
			        			'".$_REQUEST['parentId']."',
			        			'".$level."',
			        			'".$_REQUEST['display']."',
			        			'".$_REQUEST['menu']."',
			        			'".$_REQUEST['position']."',
			        			'".$_SESSION['backend_language']."'";
			        $res = $dbcon->insert($table, $field, $value);
		    	}

		    	$table = "category";
		        $set = "display = '".$_REQUEST['display']."',
			        	menu = '".$_REQUEST['menu']."',
			        	position = '".$_REQUEST['position']."',
		        		parent_id = '".$_REQUEST['parentId']."',
		        		level = '".$level."'
		        		";
		        $where = "cate_id = '".$_REQUEST['cateId']."'";
		        $dbcon->update($table, $set, $where);

		        if ($_REQUEST['parentId'] == $_REQUEST['currentParentId']) {

			        if ($_REQUEST['priority'] < $_REQUEST['currentPriority']) {
			        	$table = "category";
				        $set = "priority = priority+1";
				        $where = "parent_id = '".$_REQUEST['parentId']."'
				        		AND priority >= '".$_REQUEST['priority']."' 
				        		AND priority < '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

			        }else if ($_REQUEST['priority'] > $_REQUEST['currentPriority']) {
			        	$table = "category";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['parentId']."'
				        		AND priority <= '".$_REQUEST['priority']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);
			        }

			        if ($_REQUEST['priority'] == 0) {
						$table = "category";
				        $set = "priority = '".$_REQUEST['currentPriority']."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}
					else if ($_REQUEST['priority'] > $priority) {
						$table = "category";
				        $set = "priority = '".$priority."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}else {
						$table = "category";
				        $set = "priority = '".$_REQUEST['priority']."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}

			    }else {			    	
					if ($priority != null) {

						$table = "category";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['currentParentId']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

						$data_priority = $priority+1;
						$table = "category";
				        $set = "priority = '".$data_priority."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
						
					}else {
						
						$table = "category";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['currentParentId']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

						$table = "category";
				        $set = "priority = '1'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}

			    }

		        $result = array('data' => $res);
        	}else if ($count_url > 0) {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
			echo json_encode($result);
        break;
        case 'uploadimgcate':
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			$images = $data->upload_images($new_folder);

	        $table = "category";
	        $set = "thumbnail = '".$images['0']."'";
	        $where = "cate_id = '".$_REQUEST['id']."'
	        		AND language = '".$_SESSION['backend_language']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;
	}
}
?>