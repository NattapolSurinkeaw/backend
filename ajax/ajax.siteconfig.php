<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.siteconfig.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new siteconfig();


if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();

    switch($_REQUEST['action']){
		case 'savewebsitedetail':

				if ($_REQUEST['type'] == 'edit') {

			        $set = "cate_name = '".$_REQUEST['language']."',
			        		title = '".$_REQUEST['title']."',
			        		keyword = '".$_REQUEST['keyword']."',
			        		description = '".$_REQUEST['description']."'
			        		";
			        $where = "cate_id = '".$_REQUEST['id']."'
			        		AND language = '".$_REQUEST['language']."'";
			        $res = $dbcon->update('category', $set, $where);

		    	}else if ($_REQUEST['type'] == 'add') {
		    		$table = "category";
			        $field = "cate_id,cate_name,thumbnail,url,topic,title,keyword,description,freetag,h1,h2,parent_id,level,display,menu,position,main_page,language";
			        $value = "	'".$_REQUEST['id']."',
			        			'".$_REQUEST['language']."',
			        			'".$_REQUEST['images']."',
			        			'',
			        			'',
			        			'".$_REQUEST['title']."',
			        			'".$_REQUEST['keyword']."',
			        			'".$_REQUEST['description']."',
			        			'',
			        			'',
			        			'',
			        			'0',
			        			'0',
			        			'yes',
			        			'yes',
			        			NULL,
			        			'yes',
			        			'".$_REQUEST['language']."'";
			        $res = $dbcon->insert($table, $field, $value);
		    	}

		        $result = array('data' => $res);

			echo json_encode($result);
        break;
        case 'uploadimg':
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			// $images = $data->upload_images($new_folder);
			$images = getData::upload_images($new_folder);


	        $table = "category";
	        $set = "thumbnail = '".$images['0']."'";
	        $where = "cate_id = '".$_REQUEST['id']."'
					AND language = '".$_SESSION['backend_language']."'";
			
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;

		case 'getwebinfoedit':
			$output = $mydata->get_web_info_by_field('info_id',$_REQUEST['id']);
			echo json_encode($output);
			
        break;
        case 'savewebinfoedit':
        	if ($_REQUEST['type'] == 'edit') {
		        $table = "web_info";
		        $set = 	"info_title = '".$_REQUEST['info_title']."',
		        		text_title = '".$_REQUEST['text_title']."',
		        		info_link = '".$_REQUEST['info_link']."',
		        		attribute = '".$_REQUEST['attribute']."',
		        		info_date_edit = '".date('Y-m-d H:i:s')."'
		        		";
		        $where = "info_id = '".$_REQUEST['info_id']."'
		                AND language = '".$_SESSION['backend_language']."'";
		        $res = $dbcon->update($table, $set, $where);

		    }else if ($_REQUEST['type'] == 'add') {
		        $table = "web_info";
		        $field = "info_id,info_type,info_title,text_title,info_link,priority,attribute,info_display,info_created,info_date_edit,language,defaults";
		        $value = "
		              '".$_REQUEST['info_id']."',
		              '".$_REQUEST['info_type']."',
		              '".$_REQUEST['info_title']."',
		              '".$_REQUEST['text_title']."',
		              '".$_REQUEST['info_link']."',

		              '".$_REQUEST['priority']."',
		              '".$_REQUEST['attribute']."',
		              '".$_REQUEST['info_display']."',
		              '".date('Y-m-d H:i:s')."',
		              '".date('Y-m-d H:i:s')."',
		              '".$_SESSION['backend_language']."',
		              ''
		              ";
		        $res = $dbcon->insert($table, $field, $value);
		    }

		    if ($res['message'] == 'OK') {
		    	$table = "web_info";
		        $set = 	"priority = '".$_REQUEST['priority']."',
		        		info_display = '".$_REQUEST['info_display']."',
		        		info_date_edit = '".date('Y-m-d H:i:s')."'
		        		";
		        $where = "info_id = '".$_REQUEST['info_id']."'";
		        $ret = $dbcon->update($table, $set, $where);
		        $output = array('data' => $ret);
		    }else {
		        $output = array('data' => $res);
		    }
		    echo json_encode($output);

        break;
        case 'savewebinfoadd':
	      $sql = "SELECT MAX(info_id) imax FROM web_info";
	      $imax = $dbcon->fetch_assoc($sql);
	      $imax++;

	      $table = "web_info";
	      $field = "info_id, info_type, info_title, text_title, info_link, priority, attribute, info_display, info_created, info_date_edit, language, defaults";
	      $value = "  '".$imax."',
	            '".$_REQUEST['info_type']."',
	            '".$_REQUEST['info_title']."',
	            '".$_REQUEST['text_title']."',
	            '".$_REQUEST['info_link']."',
	            '".$_REQUEST['priority']."',
	            '".$_REQUEST['attribute']."',
	            '".$_REQUEST['info_display']."',
	            '".date('Y-m-d H:i:s')."',
	            '".date('Y-m-d H:i:s')."',
	            '".$_SESSION['backend_language']."',
	            'yes'";
	      $res = $dbcon->insert($table, $field, $value);
	      $result = array('data' => $res);
	      echo json_encode($result);

	    break;
	    case 'webinfodelete':
	      	$table = "web_info";
	      	$where = "info_id = '".$_REQUEST['id']."'";
	      	$res = $dbcon->delete($table, $where);
	        $result = $res;
	      	echo json_encode($result);
	    break;
	}
}
?>