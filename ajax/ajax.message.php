<?php
// use function GuzzleHttp\json_encode;

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.contact_sel.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.protected_web.php';

$dbcon = new DBconnect();
getData::init();

$mydata = new contact_sel();

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {
        case'getMessage':
            $sql = "SELECT * FROM paid_msg ORDER BY id ASC";
            $result = $dbcon->query($sql);
            echo json_encode($result); 
       break;
       case'saveMessage':

            if(isset($_POST['array'])){
                foreach($_POST['array'] as $key =>$val){
                    $table = "paid_msg"; 
                    $set = "message =:message , update_by =:update_by , date_update =:date_update";
                    $where = "id = :id";
                    $value = array(
                        ':id'    => $val['id'],
                        ':message'    => $val['value'],
                        ':update_by' => $_SESSION['user_id'],
                        ':date_update' => date('Y-m-d H:i:s')
                    );
                    $result[$val['id']] = $dbcon->update_prepare($table, $set, $where, $value);
                    
                    if($ret['message'] == "OK" || !isset($ret['message'])){
                        $ret['message'] =  ($result[$val['id']]['status'] != 200)?  "error":"OK"; 
                    }    
                    if($val['id'] == 3){
                        $table = "category"; 
                        $set = "description =:message";
                        $where = "cate_id = :id";
                        $value = array(
                            ':id'    => 20,
                            ':message'    => $val['value'] 
                        );
                        $update = $dbcon->update_prepare($table, $set, $where, $value);
                    }
                }
            }  
    
            echo json_encode($ret);
       break;
     
       

    }
}
