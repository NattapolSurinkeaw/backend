
 <?php
session_start();
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.setting.php';

$dbcon = new DBconnect();
$mydata = new setting(); 
getData::init();

if(isset($_REQUEST['action'])) {
   $output = $_SESSION['backend_language'];
 
  switch($_REQUEST['action']){
    case'getwebinfotype':
      $output = $mydata->get_web_info_type_edit($_REQUEST['id']);
      echo json_encode($output);

    break;
    case 'editwebinfotype':
      if ($_REQUEST['type'] == 'edit') {
        $table = "web_info_type";
        $set = "info_title = '".$_REQUEST['info_title']."'";
        $where = "id = '".$_REQUEST['id']."'
                AND language = '".$_SESSION['backend_language']."'";
        $res = $dbcon->update($table, $set, $where);

      }else if ($_REQUEST['type'] == 'add') {
        $table = "web_info_type";
        $field = "id,info_type,info_title,language,defaults";
        $value = "
              '".$_REQUEST['id']."',
              '".$_REQUEST['current_info_type']."',
              '".$_REQUEST['info_title']."',
              '".$_SESSION['backend_language']."',
              ''
              ";
        $res = $dbcon->insert($table, $field, $value);
      }

      if ($res['message'] == 'OK') {
        if ($_REQUEST['info_type'] != $_REQUEST['current_info_type']) {
          $table = "web_info_type";
          $set = "info_type = '".$_REQUEST['info_type']."'";
          $where = "id = '".$_REQUEST['id']."'";
          $ret = $dbcon->update($table, $set, $where);

          if ($ret['message'] == 'OK') {
            $table = "web_info";
            $set = "info_type = '".$_REQUEST['info_type']."'";
            $where = "info_type = '".$_REQUEST['current_info_type']."'";
            $result = $dbcon->update($table, $set, $where);

            $output = array('data' => $result);
          }
        }else {
          $output = array('data' => $res);
        }
      }else {
        $output = array('data' => $res);
      }
      echo json_encode($output);

    break;
    case 'addwebinfotype':
      $sql = "SELECT MAX(id) imax FROM web_info_type";
      $imax = $dbcon->fetch_assoc($sql);
      $imax++;

      $table = "web_info_type";
      $field = "id,info_type,info_title,language,defaults";
      $value = "  '".$imax."',
            '".$_REQUEST['info_type']."',
            '".$_REQUEST['info_title']."',
            '".$_SESSION['backend_language']."',
            'yes'";
      $res = $dbcon->insert($table, $field, $value);
      $result = array('data' => $res);
      echo json_encode($result);

    break;
    case 'webinfotypedelete':
      $table = "web_info_type";
      $where = "id = '".$_REQUEST['id']."'";
      $res = $dbcon->delete($table, $where);
      if ($res['message'] == 'OK') {
        $table = "web_info";
        $where = "info_type = '".$_REQUEST['type']."'";
        $ret = $dbcon->delete($table, $where);

        $result = $ret;
      }else {
        $result = $res;
      }
      echo json_encode($result);

    break;
    case 'editfeature':
      $table = "feature";
      $set = "status = '".$_POST['status']."'";
      $where = "id = '".$_POST['id']."'";
      $result = $dbcon->update($table, $set, $where);
      echo json_encode($result);
    break;
    case 'getlangusge':
      $result = $mydata->get_language($_POST['id']);
      echo json_encode($result);

    break;
    case 'addlangusge':
      $table = "language";
      $field = "language,display_name";
      $value = "'".$_REQUEST['name']."',
            '".$_REQUEST['display']."'";
      $result = $dbcon->insert($table, $field, $value);
      echo json_encode($result);

    break;
    case 'editlangusge':
      $table = "language";
      $set = "display_name = '".$_POST['display']."'";
      $where = "id = '".$_POST['id']."'";
      $result = $dbcon->update($table, $set, $where);
      echo json_encode($result);
    break;
    case 'deletelanguage':
      $table = "language";
      $where = "id = '".$_POST['id']."'";
      $result = $dbcon->delete($table, $where);
      echo json_encode($result);
      
    break;
    case 'getadstype':
      $result = $mydata->get_ads_type($_POST['id']);
      echo json_encode($result);

    break;
    case 'editadstype':
      $table = "ad_type";
      $set = "position = '".$_POST['position']."',
              type = '".$_POST['type']."',
              dimension = '".$_POST['dimension']."'";
      $where = "id = '".$_POST['id']."'";
      $result = $dbcon->update($table, $set, $where);
      echo json_encode($result);

    break;
    case 'addadstype':
      $table = "ad_type";
      $field = "position, type, dimension";
      $value = "'".$_POST['position']."',
                '".$_POST['type']."',
                '".$_POST['dimension']."'";
      $result = $dbcon->insert($table, $field, $value);
      echo json_encode($result);

    break;
    case 'deleteadstype':
      $table = "ad_type";
      $where = "id = '".$_POST['id']."'";
      $result = $dbcon->delete($table, $where);
      echo json_encode($result);
      
    break;
	}
}
?>