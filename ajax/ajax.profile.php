<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';

$dbcon = new DBconnect();
getData::init();

if(isset($_REQUEST['action'])) {
    switch($_REQUEST['action']){
    	case'editprofile':
	    	$result = '0';
	    	if ($_REQUEST['email'] !== $_REQUEST['current_email']) {
				$email = $_REQUEST['email'];
		        $ret = getData::check_email($email);
		        if (!$ret) {
		        	$result = '1';
		        }
		    }else {
		    	$result = '1';
		    }
	        $output = array();
	        if ($result == '1') {
	        	$table = "user";
		        $set = "display_name = '".$_REQUEST['displayname']."',
		        		username = '".$_REQUEST['username']."',
		        		email = '".$_REQUEST['email']."',
						phone = '".$_REQUEST['phone']."',
						language_templete = '".$_REQUEST['language_templete']."',
		        		update_date = '".date('Y-m-d H:i:m')."'";
			    $where = "member_id = '".$_REQUEST['id']."'";
				$res = $dbcon->update($table, $set, $where);
				$_SESSION['language_templete'] = $_REQUEST['language_templete'];
				$_SESSION['LANG_LABEL'] = '';
		        $output['data'] = $res;
	        }else {
	        	$output['data'] = 'email_already_exists';
			}
	        echo json_encode($output);	
		break;
		case'adminchangepass':
			$output = array();
			$password = $_REQUEST['currentpass'];
			$sql = "SELECT member_id FROM user WHERE member_id=:id and password=:pass";
		    $stmt = $dbcon->runQuery($sql);
            $stmt->execute(array(':id'=>$_REQUEST['userId'], ':pass'=>md5($password)));
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
		    if($result){
		    	$table = "user";
		        $set = "password = '".md5($_REQUEST['newpass'])."'";
			    $where = "member_id = '".$_REQUEST['userId']."'";
			    $res = $dbcon->update($table, $set, $where);

			    if ($res['message'] == 'OK') {
		        	$output['title'] = "Completed!";
		        	$output['text'] = $res;
		        	$output['message'] = "success";
		        }else {
		        	$output['title'] = "Fail to Change Password!";
		        	$output['text'] = $res;
		        	$output['message'] = "not_success";
		        }
		    }else {
		    	$output['title'] = "Fail to Change Password!";
	        	$output['text'] = $result;
	        	$output['message'] = "password_is_incorrect";
		    }
	    	echo json_encode($output);
		break;
		case 'uploadimgprofile':
			require_once dirname(__DIR__) . '/classes/class.upload.php';

			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			$images = getData::upload_images_thumb($new_folder);
	        $table = "user";
	        $set = "image = '".$images['0']."'";
	        $where = "member_id = '".$_REQUEST['id']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;
	}
}
?>