<?php
session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
$dbcon = new DBconnect();
$data = new getData();
getData::init();

if(isset($_REQUEST['action'])) { 
    switch($_REQUEST['action']){
        case'transmit_news': 
            if(!isset($_POST['id']) || $_POST['id'] == ""){
                echo json_encode([
                    "status" => "error",
                    "message" => "invalid_id" ]);
             exit();
            }
            $id = filter_var($_POST['id'], FILTER_SANITIZE_MAGIC_QUOTES); 
            $cate_id = '24';
            $sql = 'SELECT * FROM `email_letter`  GROUP BY e_mail ';
            $res['member'] = $dbcon->query($sql);

            $sql = 'SELECT title,date_edit,(SELECT email FROM contact_sel WHERE id = 1 ) as sys_mail
                    ,(SELECT name FROM contact_sel WHERE id = 1 ) as name ,content
                    FROM post WHERE category = '.$cate_id.' AND id = '.$id.'  ';
            $result = $dbcon->fetch($sql);

            if(!empty($res['member'])){
                foreach($res['member']  as $key => $val){   
                   $arr[$key]['email'] = $val['e_mail'];
                   $arr[$key]['name'] = $val['e_mail'];  
                }
          
       
             /* send mail */  
             $sys_email = $result['sys_mail'];  
             $message = $result['content']; 
             $getpost = array(
                'sendFromName' => $result['name'],
                'email' => $result['sys_mail'],
                'subject' =>   $result['title'],
                'mail_system' =>   $sys_email, 
                'addAddress' =>  $sys_email,  
                'addBcc' => $arr,  
                'content' =>  $message
             );
             $statusEmail = getData::sendemailnew($getpost); 
            } 
             if($statusEmail){
               $table = "post";
               $set = "example =:example,date_edit =:date";
               $where = "id = :id ";
               $value = array(
                   ':id' => $id, 
                   ':example' => "send",
                   ':date' => date("Y-m-d H:i:s")
               );
               $result = $dbcon->update_prepare($table, $set, $where, $value); 
               $ret['message'] = "OK"; 
             } else {
                $ret['message'] = "error"; 
             }
           
             echo json_encode($ret);
        
 
        break;
	}
}
?>