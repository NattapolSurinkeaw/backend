<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.contents.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new contents();

if(isset($_REQUEST['action'])){
    switch($_REQUEST['action']){
        case'modalHide':
             $sql = "SELECT * FROM post where section = 'hide'";
             $result = $dbcon->query($sql);
             echo json_encode($result);
             echo "aaaaaaaaaaaaa"; 
        break;
        case'modalHiddenBtn':

            echo "WORK";
        break;
        case'updateSection':
            $sql = "UPDATE post SET section = '".$_REQUEST['section']."' ";
            $res = $dbcon->query($sql);
            echo json_encode($res);
        break;
    
    }
}

?>