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
        case'get_grades': 
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'grade_priority',
                2 => 'grade_description',
                3 => 'grade_max', 
                4 => 'grade_min'  
            );
            $sql = "SELECT * FROM berproduct_grade "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE  grade_name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR grade_description LIKE '%" . $requestData['search']['value'] . "%' ";
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
                    $nestedData[] = "<p class='text-center'>".$value['grade_name']."</p>"; 
                    $nestedData[] = '<input style="width:100%; text-align:center;" class="form-control txt_desc" data-id="'.$value['grade_id'].'" value="'.$value['grade_description'].'">';
                    $nestedData[] = '<input style="width:100%; text-align:center;" title="กด Enter เพื่ออัพเดทข้อมูล" class="form-control txt_max" data-id="'.$value['grade_id'].'" value="'.$value['grade_max'].'">';
                    $nestedData[] = '<input style="width:100%; text-align:center;" class="form-control txt_min"title="กด ENTER เพื่ออัพเดทข้อมูล" data-id="'.$value['grade_id'].'" value="'.$value['grade_min'].'">';
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
        case'update_grades':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $value = FILTER_VAR($_POST['value'],FILTER_SANITIZE_MAGIC_QUOTES);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);

            $table = "berproduct_grade";
            $set   = ' grade_'.$name.' = "'.$value.'" ';
            $where = "grade_id = :id";
            $value = array(
                ':id' => $id 
            );
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;

    }
}

?>