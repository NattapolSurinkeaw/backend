<?php

// use function GuzzleHttp\json_encode; 
session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.manage_products.php';
#require_once dirname(__DIR__) . '/classes/class.uploadimage.php';

$dbcon = new DBconnect();
getData::init(); 
$mydata = new manage_products();

$action = isset($_POST['action'])?$_POST['action']:'view';

switch ($action) {
  case 'view':
    $sql = "SELECT * FROM bernetwork";
    $stmt = $dbcon->query($sql);
    echo json_encode($stmt);
    // print_r($stmt);

    break;

  case 'add':
    if(isset($_POST['name'])){
      $network_name = $_POST['name'];
      $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
      $images = $App->upload_image_thumb($new_folder);
  
      if(count($images[0]) > 0){
        $field = "network_name,thumbnail,display";
        $key = ":network_name,:thumbnail,:display";
        $value = array(
          ":network_name" => $network_name,
          ":thumbnail" => $images[0],
          ":display" => "yes",
        );
        $result = $dbcon->insertPrepare('bernetwork', $field, $key , $value);
  
        echo json_encode($result);
      }
      else{
        echo "false";
        return false;
      }
    }
    else{
      echo "false";
      return false;
    }
    
    break;

  case 'delete':
    $id = "network_id=".$_POST['id'];
    $table = "bernetwork";
    $sql = $dbcon->delete($table,$id);

    echo json_encode($sql);
    break;
  case 'edit':
    $sql = "SELECT * FROM bernetwork WHERE network_id = ".$_POST['id']." ;";
    $stmt = $dbcon->query($sql);
    echo json_encode($stmt);

    break;

  case 'update':
    if(isset($_POST['name'])){
      $network_id = $_POST['id'];
      $network_name = $_POST['name'];
      $display = $_POST['status'];
      $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
      $images = $mydata->upload_images_thumb($new_folder);
  
      if(count($images[0]) > 0){
        $set = "network_name = :network_name,thumbnail = :thumbnail,display = :display";
        $value = array(
            ":network_id" => $network_id,
            ":network_name" => $network_name,
            ":display" => $display,
            ":thumbnail" => $images[0]
        ); 
      }
      else{
        $set = "network_name = :network_name,display = :display";
        $value = array(
          ":network_id" => $network_id,
          ":network_name" => $network_name,
          ":display" => $display
        ); 
      }

      $where = "network_id = :network_id";

      $result = $dbcon->update_prepare('bernetwork', $set,$where,$value);
      if($result){
        echo json_encode($result);
      }
      else{
        echo "error";
      }
      
    }
    else{
      echo "false";
      return false;
    }

    break;
}