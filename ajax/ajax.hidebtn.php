<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.contact.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new contact();

$lang = $_SESSION['language'];
if(isset($_REQUEST['action'])) {

    switch($_REQUEST['action']){
        case'slc_modalHide':
             $c = '';
             $sql = "SELECT `id`,`title`,`section` from post where section = 'hide' && language = '".$lang."' ";
             $res = $dbcon->query($sql);
             echo json_encode([$res]);
                   
        break;
        case'updateModalHide':

            $sql = "UPDATE post SET section = 'hide' WHERE id = '".$_REQUEST['id']."' ";
            $res = $dbcon->query($sql);
            echo json_encode([$res]);

        break;
        case'updateModalShow':

            $sql = "UPDATE post SET section = '' WHERE id = '".$_REQUEST['id']."' ";
            $res = $dbcon->query($sql);
            echo json_encode([$res]);

        break;

        case'test':
        
            $sql = "SELECT `id`,`title`,`section` FROM post WHERE language = 'EN' && section = 'hide' ";
            $res = $dbcon->query($sql);
            foreach($res as $b =>$c){
                echo"<br/>";
                foreach($c as $g){
                    print_r($g);
                    echo "    ";
               
                 }
             }
            //echo json_encode([$res]);
        break;
    

    }
}



?>