<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.contents.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';

$dbcon = new DBconnect();
getData::init();

if(isset($_REQUEST['action'])) {

   //language 
   // getData::lang_config()
   
    switch($_REQUEST['action']){

		case'updatemap':
      if ($_REQUEST['id'] != '') {
        $table = "map_setting";
        $set = "Lat = '".$_REQUEST['lat']."', Lng = '".$_REQUEST['lon']."', Zoom = '".$_REQUEST['zoom']."'";
        $where = "id = '".$_REQUEST['id']."'";
        $result = $dbcon->update($table, $set, $where);
          
      }else {
        $table = "map_setting";
        $field = "Lat, Lng, Zoom, city_id";
        $value = "'".$_REQUEST['lat']."',
                  '".$_REQUEST['lon']."',
                  '".$_REQUEST['zoom']."',
                  '".$_REQUEST['city']."'";
        $result = $dbcon->insert($table, $field, $value);
      }   
      echo json_encode($result);
		break;
		case 'uploadmarker':
      $new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
      $images = getData::upload_images($new_folder);

          $table = "map_setting";
          $set = "marker = '".$images['0']."'";
          $where = "id = '".$_REQUEST['id']."'";
          $result = $dbcon->update($table, $set, $where);
          echo json_encode($result);
    break;
	}
}
?>