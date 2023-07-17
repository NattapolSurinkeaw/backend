<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.contents.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';

$dbcon = new DBconnect();
getData::init();


if(isset($_REQUEST['action'])) {

  switch($_REQUEST['action']){

    case'getads':

      $sql = "SELECT * FROM ads WHERE ad_id = :id ORDER BY FIELD( defaults,  'yes','' ) ASC";
      $stmt = $dbcon->runQuery($sql);
      $stmt->execute(array(':id'=>$_REQUEST['id']));
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // current ดึงข้อมูลอาร์เรย์ ตัวที่ 1
      $ads = current(getData::convertResultPost($result,'ad_id'));
      if($ads['defaults'] == 'yes'){
        $ads['type_add_edit'] = 'add';
      }
      if($ads['language'] == $_SESSION['backend_language']){
        $ads['type_add_edit'] = 'edit';
      }
      echo json_encode($ads);
    break;

    case'addads':
      $sql = "SELECT MAX(ad_id) FROM ads";
      $max =  $dbcon->fetch_assoc($sql);
      $max++;
      $table = "ads";
      $field = "ad_id, ad_position, ad_priority, ad_image, ad_link, ad_title, ad_display, ad_created, ad_date_display, ad_date_hidden, language, defaults";
      $value = "  '".$max."',
            '".$_REQUEST['position']."',
            '".$_REQUEST['priority']."',
            '',
            '".$_REQUEST['link']."',
            '".$_REQUEST['title']."',
            '".$_REQUEST['display']."',
            '".date('Y-m-d H:i:s')."',
            '".$_REQUEST['dateDisplay']."',
            '".$_REQUEST['dateHidden']."',
            '".$_SESSION['backend_language']."',
            'yes'";
      $res = $dbcon->insert($table, $field, $value);

      $result = array('data' => $res, 'id' => $max);
      echo json_encode($result);
    break;
    case 'editads':
 
      if ($_REQUEST['type'] == 'edit') {
        $table = "ads";
        $set = "ad_title = '".$_REQUEST['title']."',
            ad_link = '".$_REQUEST['link']."'";
        $where = "ad_id = '".$_REQUEST['id']."'
            AND language = '".$_SESSION['backend_language']."'";
        $result = $dbcon->update($table, $set, $where);

      }else if ($_REQUEST['type'] == 'add') {
        $table = "ads";
        $field = "ad_id, ad_position, ad_priority, ad_image, ad_link, ad_title, ad_display, ad_created, ad_date_display, ad_date_hidden, language, defaults";
        $value = "
              '".$_REQUEST['id']."',
              '".$_REQUEST['position']."',
              '".$_REQUEST['priority']."',
              '".$_REQUEST['images']."',
              '".$_REQUEST['link']."',
              '".$_REQUEST['title']."',
              '".$_REQUEST['display']."',
              '".date('Y-m-d H:i:s')."',
              '".$_REQUEST['dateDisplay']."',
              '".$_REQUEST['dateHidden']."',
              '".$_SESSION['backend_language']."',
              ''
              ";
        $result = $dbcon->insert($table, $field, $value);
      }

      if ($result['message'] == 'OK') {
        $table = "ads";
        $set = "ad_position = '".$_REQUEST['position']."',
            ad_priority = '".$_REQUEST['priority']."',
            ad_display = '".$_REQUEST['display']."',
            ad_date_display = '".$_REQUEST['dateDisplay']."',
            ad_date_hidden = '".$_REQUEST['dateHidden']."'   ";
        $where = "ad_id = '".$_REQUEST['id']."'";
        $output = $dbcon->update($table, $set, $where);
      }else {
        $output = $result;
      }
      echo json_encode($output);
    break;
    case 'uploadimgads':
      $new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
      $images = getData::upload_images($new_folder);

      $table = "ads";
      $set = "ad_image = '".$images['0']."'";
      $where = "ad_id = '".$_REQUEST['id']."'
          AND language = '".$_SESSION['backend_language']."'";
      $result = $dbcon->update($table, $set, $where);
      echo json_encode($result);
    break;
	}
}
?>