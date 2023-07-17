<?php
// use function GuzzleHttp\json_encode; 
session_start();
error_reporting(1);
ini_set('display_errors', 1);
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.uploadimage.php';
require_once dirname(__DIR__) . '/classes/class.predicts.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new predicts();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
    switch($_REQUEST['action']){
        case'get_prophecy': 
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'prophecy_numb',
                1 => 'prophecy_desc',
                2 => 'prophecy_desc',
                3 => 'prophecy_percent', 
            );
            $sql = "SELECT * FROM berpredict_prophecy "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE  prophecy_numb LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR prophecy_desc LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR prophecy_percent LIKE '%" . $requestData['search']['value'] . "%' ";
            } 
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;
            
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);
            
            $output = array();
            if ($result) {
                foreach ($result as $value) { 
                    $nestedData = array();
                    $nestedData[] = "<p class='text-center'>".$value['prophecy_numb']."</p>"; 
                    $nestedData[] = $value['prophecy_name'];
                    $nestedData[] = $value['prophecy_desc'];
                    $nestedData[] = "<p class='text-center'>".$value['prophecy_percent']."%</p>";  
                    $nestedData[] = ' <p class="btn-center btn-flex"><a class="btn kt:btn-warning" style="color:white;" onclick="prepareEdit_prophecy(event,' . $value['prophecy_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                     <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['prophecy_id'].'" onclick="prepareDel_prophecy(event,' . $value['prophecy_id'] . ','. $value['prophecy_numb'].')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a></p>';
                    $output[] = $nestedData;
                }
            }
    
            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
            );
            echo json_encode($json_data); 
        break;
        case'add_prophecy': 
            $html = 
              ' <div class="title-numb">หมายเลข: 00-99</div>
                <input  maxlength="2"class="swal2-input txt_number" value="" placeholder="กรุณาใส่ตัวเลข">
                <div class="title-numb">ความหมาย:</div>
                <input  class="swal2-input txt_title " value="" placeholder="กรุณาใส่ข้อความ">
                <div class="title-numb">รายละเอียด:</div>
                <textarea class="swal2-input txt_desc" placeholder="รายละเอียดข้อความ"></textarea>
                <div class="title-numb">Percent: </div>
                <input  class="swal2-input  txt_percent " value="" placeholder="100%">  ';
            $result = array();
            $result['html'] = $html; 
            echo json_encode($result);    
        break;
        case'insert_prophecy':
            $number = FILTER_VAR($_POST['number'],FILTER_SANITIZE_NUMBER_INT);
            $title = FILTER_VAR($_POST['title'],FILTER_SANITIZE_MAGIC_QUOTES);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $percentile = FILTER_VAR($_POST['percentile'],FILTER_SANITIZE_NUMBER_INT);

            $table = "berpredict_prophecy";
            $field = "prophecy_numb,prophecy_name,prophecy_desc,prophecy_percent,date_create ";
            $key = ":prophecy_numb,:prophecy_name,:prophecy_desc,:prophecy_percent,:date_create ";
            $value = array(
                ":prophecy_numb" => $number,
                ":prophecy_name" => $title,
                ":prophecy_desc" => $desc,
                ":prophecy_percent" => $percentile,
                ":date_create"=> date("Y-m-d H:i:s")
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            echo json_encode($result);
        break;
        case'delete_prophecy':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_prophecy";
            $where  = "prophecy_id = :prophecy_id";
            $val = array(
                ':prophecy_id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'prepare_edit_prophecy': 
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM berpredict_prophecy WHERE prophecy_id =:id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            $html = 
            ' <div class="title-numb">หมายเลข: 00-99</div>
              <input  maxlength="2"class="swal2-input txt_number" value="'.$result->prophecy_numb.'" placeholder="กรุณาใส่ตัวเลข">
              <div class="title-numb">ความหมาย:</div>
              <input  class="swal2-input txt_title " value="'.$result->prophecy_name.'" placeholder="กรุณาใส่ข้อความ">
              <div class="title-numb">รายละเอียด:</div>
              <textarea class="swal2-input txt_desc" placeholder="รายละเอียดข้อความ">'.$result->prophecy_desc.'</textarea>
              <div class="title-numb">Percent: </div>
              <input  class="swal2-input  txt_percent " value="'.$result->prophecy_percent.'" placeholder="100%">  ';
          $ret = array();
          $ret['id'] = $result->prophecy_id; 
          $ret['html'] = $html; 
          echo json_encode($ret); 
        break;
        case'update_prophecy':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $title = FILTER_VAR($_POST['title'],FILTER_SANITIZE_MAGIC_QUOTES);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $number = FILTER_VAR($_POST['number'],FILTER_SANITIZE_NUMBER_INT);
            $percentile = FILTER_VAR($_POST['percentile'],FILTER_SANITIZE_NUMBER_INT);

            $table = "berpredict_prophecy";
            $set = "prophecy_numb = :number,prophecy_name=:title,prophecy_desc=:desc,prophecy_percent=:percentile";
            $where = "prophecy_id = :id";
            $value = array(
                ":id" => ($id),
                ":number" => ($number),
                ":title" => ($title),
                ":desc" => ($desc),
                ":percentile" => ($percentile) 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
           
        break;

    }
}

?>