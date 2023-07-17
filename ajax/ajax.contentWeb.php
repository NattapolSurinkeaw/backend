<?php	
session_start();
 
// ProtectWeb::admin_only();
// ProtectWeb::method_post_only();


include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.contents.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new contents();


if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();
	
    switch($_REQUEST['action']){
  
		case'getcategorycontent': #Protect Success
			$category = $mydata->get_category();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
		break;
		case'getpaginationcontent':#Protect Success
			//filter cate_id
			$cate_id = 4;
			$value = array(); //เอาไว้เก็บค่า value ของแต่ละ parameter cate,display,search 
			$cate_src = '';
			if($cate_id != ''){
				$cate_src = " AND category LIKE ?";
				array_push($value,"%,".$cate_id.",%");
			} 
			$status = '';
			if($_REQUEST['display'] != '' && in_array($_REQUEST['display'],['yes','no'])){
				$status = " AND display = ?";
				array_push($value,$_REQUEST['display']);
			}

			$search = '';
			if($_REQUEST['search'] != ''){
				$search = " AND title LIKE ?";
				array_push($value,"%".$_REQUEST['search']."%");
			}
			if($_REQUEST['cate_slc'] != 'all' ){
				$src =" AND freetag LIKE '".$_REQUEST['cate_slc']."' ";
			}else{
				$src = '';
			}
        	$table = "post";
			$where = "defaults = 'yes' ".$src." ".$search.$cate_src.$status;
			$result = $data->pagination_v2($table,$where,$value);
			echo ($result);
        break;
		case'getcontent': #Protect Success			
			$id = isset($_REQUEST['id'])?filter_var($_REQUEST['id'],FILTER_SANITIZE_NUMBER_INT):'';
			$lan_arr = $data->get_language_array();
			$sql = "SELECT * FROM post WHERE id = :id  ORDER BY FIELD(defaults ,'yes')DESC";
			$stmt = $dbcon->runQuery($sql);
            $stmt->execute(array(':id'=>$id));
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			$content = array();
			$ret = array();
			$tag = array();

			foreach($result as $a){
				if($a['defaults']=='yes'){
					$content['defaults']=$a;
				}
				$content[$a['language']]=$a;
			}

			foreach($content as $b => $c){
				if($b != 'defaults')
					if(in_array($b,$lan_arr))
						$lang_info .= ','.$c['language'];
				if($b == 'defaults')
					$ret=$c;
				if($b == $_SESSION['backend_language'])
					$ret=$c;

			}
			$ret['lang_info'] = $lang_info;
			$lang_info = '';
	
			if ($ret['tag'] != '') {
				$alltag = explode(',',$ret['tag']);
				for($i=1;$i < count($alltag)-1 ;$i++){
					$sql = "SELECT tag_name,tag_id FROM tag WHERE tag_id = '".$alltag[$i]."'";
					$tag[] = $dbcon->query($sql);
				}
			}
 
			$sql = "SELECT * FROM post_image WHERE post_id = ? ORDER BY position ASC";
			$res = $dbcon->fetchAll($sql,[$id]);

			if ($res == false) {
				$res = 'no_image';
			}

			$return_arr = array("data" => [$ret], 'tag' => $tag, 'images' => $res);

			echo json_encode($return_arr);
		break;
	 
		case'editcontent': #Protect Success 
			if ($_REQUEST['slug'] == $_REQUEST['currentUrl']) {
				$count_url_cate = 0;
				$count_url_post = 0;
			}else {
				$sql = " SELECT COUNT(url) AS 'count_url_cate' FROM category WHERE url = '".$_REQUEST['slug']."' ";
	        	$count_url_cate = $dbcon->fetch_assoc($sql);
	        	$sql = " SELECT COUNT(slug) AS 'count_url_post' FROM post WHERE slug = '".$_REQUEST['slug']."' ";
	        	$count_url_post = $dbcon->fetch_assoc($sql);
			} 

		
			#ถ้าไม่มีมีข้อมูล หรือ ข้อมูลไม่เหมือนเดิม
			if ($count_url_cate == 0 && $count_url_post == 0) { 
				if ($_REQUEST['submitType'] == 'edit') { 
					$post_id = isset($_REQUEST['id'])?filter_var($_REQUEST['id'],FILTER_SANITIZE_NUMBER_INT):null;
					$post_priority_new = isset($_REQUEST['priority'])?filter_var($_REQUEST['priority'],FILTER_SANITIZE_NUMBER_INT):null; 
					if(!empty($post_id) AND (!empty($post_priority_new) OR $post_priority_new == "0") ){ 
						$sql = "SELECT priority,category FROM post WHERE id = $post_id LIMIT 1";
						$result = $dbcon->query($sql);
						$priority_old = $result[0]['priority'];
						$category_ = $result[0]['category'];  
						$cate_id = "%".$category_."%";
						#ดูว่ามันมีค่า priority เท่าเดิมหรือไม่
						$ress = $dbcon->query("SELECT * FROM post WHERE priority = '".$post_priority_new."' AND id = '".$post_id."'"); 
						if(empty($ress)){
							$set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
							$where = "id <> :id AND (CASE WHEN :old < :new THEN priority > :old AND priority <= :new WHEN :old > :new THEN priority >= :new AND priority < :old END) AND category LIKE :category";
							$value = array(
								":id" => $post_id,
								":category" => $cate_id,
								":old" => $priority_old,
								":new" => $post_priority_new
							);
							$r1 = $dbcon->update_prepare("post",$set,$where,$value);

							$set = "priority = :new";
							$where = "id = :id";
							$value = array(
								":id" => $post_priority_new,
								":new" => $post_priority_new
							);
							$r2 = $dbcon->update_prepare("post",$set,$where,$value);
						}

						$table = "post"; 
						$set = "title = :title,
								description = :description,
								slug = :slug,
								h1 = :h1,
								h2 = :h2,
								video = :video,
								tag = :tag,
								content = :content, 
								display = :display,
								date_edit = :date_edit,
								date_display = :date_display,
								author = :author,
								pin = :pin,
								priority = :priority,
								meta_title = :meta_title,
								meta_description = :meta_description
								";
						$where = "id = :id AND language = :backend_language";
						$value = array(
							":title" => ($_REQUEST['title'])	,
							":description" => ($_REQUEST['description']),
							":slug" => ($_REQUEST['slug']),
							":h1" => ($_REQUEST['h1']),
							":h2" => ($_REQUEST['h2']),
							":video" =>( $_REQUEST['video']),
							":tag" => $_REQUEST['tag'],
							":content" => preg_replace('/(<(.*)script>)|(javascript:[\w\d\s();]*)|(\s+on[\w\d\s="\'();]*)/i',"",$_REQUEST['content']),
							":display" => ($_REQUEST['display']),
							":date_edit" => date('Y-m-d H:i:s'),
							":date_display" => $_REQUEST['dateDisplay'],
							":author" => $_SESSION['user_id'],
							":pin" => $_REQUEST['pin'],
							":priority" => ($_REQUEST['priority']),
							":id" => ($_REQUEST['id']),
							":backend_language" => $_SESSION['backend_language'],
							":meta_title" => ($_REQUEST['meta_title']),
							":meta_description" => ($_REQUEST['meta_desc']) 
						);
						$res = $dbcon->update_prepare($table,$set,$where,$value);

					}else{
						$result = array([
							'event'  => 'check_request-',
							'status' => '400',
							'message' => 'request_error'
						]);
						echo json_encode($result); exit();
					} 
			    }else if ($_REQUEST['submitType'] == 'add') {
				 
						$table = "post";
						$field = "id,title,keyword,description,slug,thumbnail,h1,h2,video,tag,content,saleprice,specialprice,display,
								  date_created,date_edit,date_display,author,post_view,comment_allow,comment_count,pin,language";
						$param = ":id,:title,:keyword,:description,:slug,:thumbnail,:h1,:h2,:video,:tag,:content,:saleprice,:specialprice,:display,
									:date_created,:date_edit,:date_display,:author,:post_view,:comment_allow,:comment_count,:pin,:language";
						$value = array(
									":id" => ($_REQUEST['id']),
									":title" => ($_REQUEST['title']),
									":keyword" => ($_REQUEST['title']),
									":description" => ($_REQUEST['description']),
									":slug" => ($_REQUEST['slug']),
									":thumbnail" => $_REQUEST['images'],
									":h1" => $_REQUEST['h1'],
									":h2" => $_REQUEST['h2'],
									":video" => ($_REQUEST['video']),
									":tag" => $_REQUEST['tag'],
									":content" => preg_replace('/(<(.*)script>)|(javascript:[\w\d\s();]*)|(on[\w\d\s="\'();]*)/i',"",$_REQUEST['content']),
									":saleprice" => '0',
									":specialprice" => '0', 
									":display" => ($_REQUEST['display']),
									":date_created" => $_REQUEST['created'],
									":date_edit" => date('Y-m-d H:i:s'),
									":date_display" => $_REQUEST['dateDisplay'],
									":author" => $_SESSION['user_id'],
									":post_view" => '0',
									":comment_allow" => 'no',
									":comment_count" => '0',
									":pin" => $_REQUEST['pin'],
									":language" => $_SESSION['backend_language']
						);
						$res = $dbcon->insert_prepare($table, $field,$param, $value);
				
				}

		        $table = "post_image";
		        $set = "status = 'publish'";
				$where = "post_id = :id";
				$value = array(
					":id" => $_REQUEST['id']
				);
			    $dbcon->update_prepare($table, $set, $where,$value);

			    $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);

		        $result = array('data' => $res);
	    	}else {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
	        echo json_encode($result);
		break;
		case 'uploadimgcontent':
			#ยังไม่ได้ทดสอบ และ ทำ protect
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			// $images = $data->upload_images($new_folder);
			$images = $data->upload_images_thumb($new_folder);
 
	        $table = "post";
	        $set = "thumbnail = '".$images['0']."'";
	        $where = "id = '".$_REQUEST['id']."'
	        		AND language = '".$_SESSION['backend_language']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;
		case 'uploadmoreimgcontent':
			#ยังไม่ได้ทดสอบ และ ทำ protect
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			// $images = $data->upload_images($new_folder);
			$images = $data->upload_images_thumb($new_folder); 
	        $sql = "SELECT MAX(position) max FROM post_image WHERE post_id = '".$_REQUEST['id']."'";
			$max = $dbcon->fetch_assoc($sql);
			$max++; 
	        $ret=array();
	        foreach ($images as $key => $img_link) {
		        $table = "post_image";
			    $field = "post_id, image_link, position, language, status";
			    $value = "	'".$_REQUEST['id']."',
			    			'".$img_link."',
			    			'".($key+$max)."',
			    			'',
			    			'draft'";
			    $res = $dbcon->insert($table, $field, $value);
			    $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
			}
			echo json_encode($ret);
		break;
		case 'deleteimagedraft':
			#ยังไม่ได้ ทดสอบ
			$sql = "SELECT * FROM post_image WHERE status = 'draft' ";
			$ret = $dbcon->query($sql);

			if ($ret != false) {
				foreach ($ret as $key => $value) {
					unlink('../../'.$value['image_link']);
				}
				$table = "post_image";
			    $where = "status = 'draft'";
			    $ret = $dbcon->delete($table, $where);
			}
		break;
		case 'deleteimagecontent':
			#ทำprotect แต่ยังไม่ได้ ทดสอบ
			unlink('../../'.$_REQUEST['filename']);
			$table = "post_image";
			$where = "image_id = :image_id";
			$value = [
				':image_id' => $_REQEUST['id']
			];
			$ret = $dbcon->delete_prepare($table, $where, $value);
			// code old
		  // $sql = "SELECT * FROM post_image WHERE post_id = '".$_REQUEST['postId']."' ORDER BY position ASC";
			// $res = $dbcon->query($sql);
			//kot new code
			$sql = "SELECT * FROM post_image WHERE post_id = :post_id ORDER BY position ASC";
			$stmt = $dbcon->runQuery($sql);
			$stmt->execute([
				":post_id" => $_REQEUST['postId']
			]);
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// end new code -----

			if ($res == false) {
				$res = 'no_image';
			}
			$return_arr = array('images' => $res);
			echo json_encode($return_arr);
		break;
		case'searchtag': #Protect Success
			#code_old
			// $sql = "SELECT tag_id,tag_name FROM tag WHERE tag_name LIKE '%".$_REQUEST['key']."%'";
			// $result = $dbcon->query($sql);

			#kot select with prepare
			$tagname = "%{$_REQUEST['key']}%";
			$sql = "SELECT tag_id,tag_name FROM tag WHERE tag_name LIKE :tagname";
			$stmt = $dbcon->runQuery($sql);
			$stmt->execute([
				":tagname" => $tagname
			]);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($result);
		break;
		case'addtag': #Protect Success
			$sql = "SELECT tag_name FROM tag WHERE tag_name = :tage_name";
			$stmt = $dbcon->runQuery($sql);
            $stmt->execute([
				":tage_name" => $_REQUEST['key']
			]);
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($result){
				$result = array('data' => 'exist');
			}else{
				$table = "tag";
				$field = "tag_name,post_count,display";
				$prepare = ":tag_name,:post_count,:display";
				$value = [
					':tag_name' 	=> ($_REQUEST['key']),
					':post_count'   => 0,
					':display'  	=> 'yes'
				];
		        $res = $dbcon->insert_prepare($table, $field,$prepare, $value);
		        $result = array('data' => $res);
			}
			echo json_encode($result);
		break;
		case'deletecontent': #Protec Success
			#access superadmin , admin , editor only
			ProtectWeb::access_role_only();

			$id = ($_REQUEST['id']);
			
			# get category and priority ของ id ที่จะลบ
			$result_cate_pri = $dbcon->query("SELECT category,priority FROM post WHERE id = ".$id." LIMIT 1");
			//$result_cate_pri[0]['category'];  #category
			//$result_cate_pri[0]['priority'];	#priority

			$set = "priority = priority-1";
			$where = "priority > '".$result_cate_pri[0]['priority']."' AND category LIKE '%".$result_cate_pri[0]['category']."%'";
			$dbcon->update('post',$set,$where);

			$table = "post";
			$where = "id = '".$id."'";
			$result = $dbcon->delete($table, $where);
			echo json_encode($result);
		break;
		case'checkurl': #Protect Success
			
			$sql = " SELECT COUNT(url) AS 'count_url_cate' FROM category WHERE url = ? ";
			$count_url_cate = $dbcon->fetch_assoc_prepare($sql,$_REQUEST['slug']);
			$sql = " SELECT COUNT(slug) AS 'count_url_post' FROM post WHERE slug = ? ";
			$count_url_post = $dbcon->fetch_assoc_prepare($sql,$_REQUEST['slug']);

			

			if ($count_url_cate == 0 && $count_url_post == 0) {
				$result['data'] = array('message' => 'success');
			}else {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
			echo json_encode($result);
		break;
 
	}
}
?>