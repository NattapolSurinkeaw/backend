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
        case'get_sumber': 
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'predict_numb',
                1 => 'predict_name',
                // 2 => 'ription',
                3 => 'predict_pin', 
            );
            $sql = "SELECT * FROM berpredict_sum "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE  predict_numb LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR predict_name LIKE '%" . $requestData['search']['value'] . "%' ";
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
                    $pin = '<div class="col-md-12 btnPin"> 
                                <div class="toggle-switch inTables '.(($value['predict_pin'] == 'no')?"":"ts-active").'" style="margin: auto">
                                    <span class="'.(($value['allow_edit'] == 'no'  && $value['predict_id'] == 0)?"":"switch").'" data-id="'.$value['predict_id'].'"></span>
                                </div>
                                <input type="hidden" class="form-control" id="cate_status" value="'.(($value['predict_pin'] == 'no')?"no":"yes").'">
                            </div>';
                    $nestedData = array();
                    $nestedData[] = "<p class='text-center'>".$value['predict_numb']."</p>"; 
                    $nestedData[] = $value['predict_name'];
                    $nestedData[] = $pin;
                    $nestedData[] = ' <p class="btn-center btn-flex"><a class="btn kt:btn-warning" style="color:white;" onclick="prepareEdit_sumber(event,' . $value['predict_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                     <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['predict_id'].'" onclick="prepareDel_sumber(event,' . $value['predict_id'] . ','. $value['predict_numb'].')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a></p>';
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
        case'add_sumber': 
            $html = 
              ' <div class="title-numb">ผลรวม:</div>
                <input  maxlength="2"class="swal2-input txt_number" value="" placeholder="กรุณาใส่ตัวเลข">
                <div class="title-numb">ความหมาย:</div>
                <textarea class="swal2-input txt_desc" placeholder="รายละเอียดข้อความ"></textarea> ';
            $result = array();
            $result['html'] = $html; 
            echo json_encode($result);    
        break;
        case'insert_sumber':
            $number = FILTER_VAR($_POST['number'],FILTER_SANITIZE_NUMBER_INT);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);

            $table = "berpredict_sum";
            $field = "predict_numb,predict_name,date_create ";
            $key = ":predict_numb,:predict_name,:date_create ";
            $value = array(
                ":predict_numb" => $number,
                ":predict_name" => $desc,
                ":date_create"=> date("Y-m-d H:i:s")
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            echo json_encode($result);
        break;
        case'delete_sumber':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_sum";
            $where  = "predict_id = :predict_id";
            $val = array(
                ':predict_id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'prepare_edit_sumber': 
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM berpredict_sum WHERE predict_id =:id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            $html = 
            ' <div class="title-numb">ผลรวม:</div>
              <input  maxlength="2"class="swal2-input txt_number" value="'.$result->predict_numb.'" placeholder="กรุณาใส่ตัวเลข">
              <div class="title-numb">ความหมาย:</div>
              <textarea style="height:100px;"class="swal2-input txt_desc" placeholder="รายละเอียดข้อความ">'.$result->predict_name.'</textarea>  ';
          $ret = array();
          $ret['id'] = $result->predict_id; 
          $ret['html'] = $html; 
          echo json_encode($ret); 
        break;
        case'update_sumber':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $number = FILTER_VAR($_POST['number'],FILTER_SANITIZE_NUMBER_INT);

            $table = "berpredict_sum";
            $set = "predict_numb = :number,predict_name=:desc";
            $where = "predict_id = :id";
            $value = array(
                ":id" => ($id),
                ":number" => ($number),
                ":desc" => ($desc)  
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
           
        break;
        case'update_pin_numb_category':
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_sum";
            $set = "predict_pin = :pin";
            $where = "predict_id = :id";
            $value = array(
                ":id" => ($id),
                ":pin" => ($pin)
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;

    }
}

?>