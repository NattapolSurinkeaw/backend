<?php
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.contact.php';

$dbcon = new DBconnect();
getData::init();
$mydata = new contact();

if(isset($_REQUEST['action'])) {
    switch($_REQUEST['action']){

		case'getmessage':
			$getpost = $_REQUEST;
			$message = $mydata->get_laeve_msg($getpost);
			$i=0;
			if ($message) {
				$msg = '';
				$star = '';
		        foreach ($message as $a) {
				
					
		            if (date_format(date_create($a["submit_date"]),"Y-m-d") == date('Y-m-d')) {
		              $time = date_format(date_create($a["submit_date"]),"H:i");
		            }else {
		              $time = date_format(date_create($a["submit_date"]),"d/m/Y");
		            }

		            $time_in_read_box = date_format(date_create($a["submit_date"]),"d/m/Y - H:i");

					if ($a['favorite'] == 'yes') {
						$star = 'fas fa-star';
					  }else if ($a['favorite'] == 'no') {
						$star = 'far fa-star';
					  }
									

		            if ($a['status']=='new') {
		            	$i++;
			        	$msg .= '
			            <tr class="new" data-id="'.$a['id'].'" data-name="'.$a['fullname'].'" data-email="'.$a['email'].'" data-phone="'.$a['phone'].'" data-topic="'.$a['topic'].'" data-message="'.$a['message'].'" data-time="'.$time_in_read_box.'" data-status="'.$a['status'].'">
			                <td class="check-box"><input type="checkbox"></td>
			                <td class="mailbox-star" data-id="'.$a['id'].'"><a href="#"><i class="'.$star.' text-yellow"></i></a></td>
			                <td class="mailbox-name"><b><a>'.$a['fullname'].'</a></b></td>
			                <td class="mailbox-subject">
			                  <div>
			                    <b>'.$a['topic'].'</b>
			                    <span class="blog-message"> - '.$a['message'].'</span>
			                  </div>
			                </td>
			                <td class="mailbox-date"><b>'.$time.'</b></td>
			            </tr>';

		            }else {

		            	$msg .= '
			            <tr class="read" data-id="'.$a['id'].'" data-name="'.$a['fullname'].'" data-email="'.$a['email'].'" data-phone="'.$a['phone'].'" data-topic="'.$a['topic'].'" data-message="'.$a['message'].'" data-time="'.$time_in_read_box.'" data-status="'.$a['status'].'">
			                <td><input type="checkbox"></td>
			                <td class="mailbox-star" data-id="'.$a['id'].'"><a href="#"><i class="'.$star.' text-yellow"></i></a></td>
			                <td class="mailbox-name"><a>'.$a['fullname'].'</a></td>
			                <td class="mailbox-subject">
			                  <div>
			                    <span>'.$a['topic'].'</span>
			                    <span class="blog-message"> - '.$a['message'].'</span>
			                  </div>
			                </td>
			                <td class="mailbox-date">'.$time.'</td>
			            </tr>';

		            }
		        }
	    	}else {
	    		$msg = '';
	    	}

	        $result = array('data' => $msg, 'new' => $i);
					echo json_encode($result);
					
		break;
		
		case'updatestatus':
			$table = "leave_msg";
	        $set = "status = 'read'";
	        $where = "id = '".$_REQUEST['id']."'";
	        $result = $dbcon->update($table, $set, $where);
	        	
	        $new_msg = getData::new_laeve_msg();
	        if ($new_msg != false) {
	        	$output = $new_msg;
	        }else {
	        	$output = 'no_data';
	        }
	        echo json_encode($output);

		break;
		case'getpaginationcontact':
        	$table = "leave_msg";
        	if (!@$_REQUEST['where']) {
        		$where = "status NOT IN ('delete')";
        	}else {
        		$where = $_REQUEST['where'];
        	}
        	
        	$result = getData::pagination($table,$where);
        	echo ($result);

        break;
        case'addstar':
        	$table = "leave_msg";
	        $set = "favorite = 'yes'";
	        $where = "id = '".$_REQUEST['id']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);

        break;
        case'removestar':
        	$table = "leave_msg";
	        $set = "favorite = 'no'";
	        $where = "id = '".$_REQUEST['id']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);

        break;
        case'deletecontact':
	      $table = "leave_msg";
	      $where = "id = '".$_REQUEST['id']."'";
	      $result = $dbcon->delete($table, $where);
	      echo json_encode($result);

	    break;
		
	}
}
?>