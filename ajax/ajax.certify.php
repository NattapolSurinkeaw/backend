<?php
session_start();
error_reporting(-1);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once '../vendor/autoload.php';

$dbcon = new DBconnect();
getData::init();

$dbinstance = Database::getInstance();
$dbconn = $dbinstance->DB();

/**
 * Excel Write New File
 * @param $_name ชื่อไฟล์ที่จะให้ Export ออกมา
 * @param $_data ตัวแปร array ที่จะเป็น input ให้กับ excel
 */
function excelWriteNew($_name, $_data = [])
{
    try {
        $spreadsheetWrite = new Spreadsheet();
        $sheet = $spreadsheetWrite->getActiveSheet();
        $row = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        foreach ($_data as $key => $cell) {
            foreach ($cell as $k => $c) {
                // echo $row[($k)].($key+1)." => ".$c."<br>";
                $sheet->setCellValue($row[($k)] . ($key + 1), $c);
            }
        }
        $writer = new Xlsx($spreadsheetWrite);
        $writer->save($_name . '.xlsx');
        echo "Export Excel Success";
    } catch (PHPException $e) {
        echo $e->getMessage();
    }
}

/**
 * Excel Read
 * @param $_path ที่อย่ของไฟล์ Excel ที่จะอ่าน
 * @param $_start cell เริ่มต้น ค่า Default = A1
 * @param $_end cell สิ้นสุด ค่า Default = C1
 * @return array
 */
function excelRead($_path)
{   

    $spreadsheetRead = IOFactory::load($_path);
    $sheetData = $spreadsheetRead->setActiveSheetIndex(0);
    $highestRow = $sheetData->getHighestRow();
    $highestColumn = $sheetData->getHighestColumn();
    // $dataExcel = $sheetData->rangeToArray('A1:' . 'C' . $highestRow, null, true, true, false);	
    
    // $dataExcel = $sheetData->rangeToArray('A2:H4');
    $dataExcel = $sheetData->rangeToArray('A2:i'.$highestRow);
    
	$dataArray = array();
	foreach($dataExcel as $key => $data){
		if(	empty($data[0]) && empty($data[1]) && empty($data[2]) && empty($data[3]) &&
			empty($data[4]) && empty($data[5]) && empty($data[6]) && empty($data[7]))
		{
			break;
		}
		array_push($dataArray,$data);
	}
	return $dataArray;

    // return $dataExcel;
}


function InsertExcelToDB($_fileExcel)
{
    global $dbconn;
    // $fileName = "TestCertify.xlsx";
    $fileName = $_fileExcel;
    $path = "./../../upload/excel/" . $fileName;
    $dataExcel = excelRead($path);
    

    if (empty($dataExcel[0][0])) {
        echo json_encode([
            'message' => 'Error',
            'detail'  => 'Excel_Title_Empty'
        ]);
        exit();
    }

    try {
        $dbconn->beginTransaction();
        $dateTime = date("Y-m-d H:i:s");


        $sql = "INSERT INTO certify_pre (id,title,create_date,update_date) VALUES (null,:title,:create,:update)";
        $value = array(
            ':title' => $dataExcel[0][8],
            ':create' => $dateTime,
            ':update' => $dateTime,
        );
        $stmt = $dbconn->prepare($sql);
        $stmt->execute($value);
        $cp_last_id = $dbconn->lastInsertId();

        //Insert certify_title NEW
        
        $sql = "INSERT INTO certify_title
                                (id,title,pre_id,filename,create_date,update_date)
                                VALUES
                                (null,:title,:pre_id,:filename,:create,:update)";
        $value = array(
            ":title"  => $dataExcel[0][0],
            ":pre_id"  => $cp_last_id,
            ":filename"  => $fileName,
            ":create" =>    $dateTime,
            ":update" => $dateTime
        );
        $stmt = $dbconn->prepare($sql);
        $stmt->execute($value);
        $ct_last_id = $dbconn->lastInsertId();

        //Get Last Id FROM certify_ask
        $sql = "SELECT MAX(id) as lastID FROM certify_ask LIMIT 1";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchObject();
        // print_r($res->lastID);

        //Create ca_id FOR  insert certify_ask
        $ca_id = array();
        for ($i = 1; $i <= count($dataExcel); $i++) {
            $ca_id[$i] = ++$res->lastID;
        }
        // print_r($ct_id);

        //Insert certify_ask
        $sql = "INSERT INTO certify_ask (id,ct_id,title,img,create_date,update_date) VALUES ";
        foreach ($dataExcel as $key => $dataAsk) {
            $sql .= "('" . $ca_id[++$key] . "','" . $ct_last_id . "','" . $dataAsk[1] . "','" . $dataAsk[2] . "','" . $dateTime . "','" . $dateTime . "'),";
        }
        $sql = rtrim($sql, ",");
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();

        //Insert certify_answ
        $sql = "INSERT INTO certify_answ (id,ct_id,ca_id,answ_id,title,goal) VALUES ";
        foreach ($dataExcel as $key => $dataIn) {
            $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[++$key] . "',1,'" . $dataIn[3] . "','" . (($dataIn[7] == "A") ? "true" : "false") . "'),"; //Choice A
            $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',2,'" . $dataIn[4] . "','" . (($dataIn[7] == "B") ? "true" : "false") . "'),"; //Choice B
            $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',3,'" . $dataIn[5] . "','" . (($dataIn[7] == "C") ? "true" : "false") . "'),"; //Choice C
            $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',4,'" . $dataIn[6] . "','" . (($dataIn[7] == "D") ? "true" : "false") . "'),"; //Choice E
        }
        $sql = rtrim($sql, ",");
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();


        $dbconn->commit();
    } catch (PDOException $e) {
        $dbconn->rollBack();
        echo json_encode([
            "message" => "Error",
            "detail"  => $e->getMessage()
        ]);
        exit();
    }
}

if (isset($_REQUEST['action'])) {

    $lang_config = getData::lang_config();

    switch ($_REQUEST['action']) {
        case 'test':
            echo json_encode([
                'message' => 'Test',
            ]);
        break;

        case 'test_':
            
            header('Content-type: text/html;charset=utf-8');

            $fileName = "TestCertify.xlsx";
            $path = "../../upload/excel/" . $fileName;
            $dataExcel = excelRead($path);
            print_r($dataExcel); exit();

            if (empty($dataExcel[0][0])) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'Excel_Title_Empty'
                ]);
                exit();
            }

            try {
                $dbconn->beginTransaction();

                //Insert certify_title NEW
                $dateTime = date("Y-m-d H:i:s");
                $sql = "INSERT INTO certify_title
                                (id,title,filename,create_date,update_date)
                                VALUES
                                (null,:title,:filename,:create,:update)";
                $value = array(
                    ":title"  => $dataExcel[0][0],
                    ":filename"  => $fileName,
                    ":create" =>    $dateTime,
                    ":update" => $dateTime
                );
                $stmt = $dbconn->prepare($sql);
                $stmt->execute($value);
                $ct_last_id = $dbconn->lastInsertId();

                //Get Last Id FROM certify_ask
                $sql = "SELECT MAX(id) as lastID FROM certify_ask LIMIT 1";
                $stmt = $dbconn->prepare($sql);
                $stmt->execute();
                $res = $stmt->fetchObject();
                // print_r($res->lastID);

                //Create ca_id FOR  insert certify_ask
                $ca_id = array();
                for ($i = 1; $i <= count($dataExcel); $i++) {
                    $ca_id[$i] = ++$res->lastID;
                }
                // print_r($ct_id);

                //Insert certify_ask
                $sql = "INSERT INTO certify_ask (id,ct_id,title,img,create_date,update_date) VALUES ";
                foreach ($dataExcel as $key => $dataAsk) {
                    $sql .= "('" . $ca_id[++$key] . "','" . $ct_last_id . "','" . $dataAsk[1] . "','" . $dataAsk[2] . "','" . $dateTime . "','" . $dateTime . "'),";
                }
                $sql = rtrim($sql, ",");
                $stmt = $dbconn->prepare($sql);
                $stmt->execute();

                //Insert certify_answ
                $sql = "INSERT INTO certify_answ (id,ct_id,ca_id,answ_id,title,goal) VALUES ";
                foreach ($dataExcel as $key => $dataIn) {
                    $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[++$key] . "',1,'" . $dataIn[3] . "','" . (($dataIn[7] == "A") ? "true" : "false") . "'),"; //Choice A
                    $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',2,'" . $dataIn[4] . "','" . (($dataIn[7] == "B") ? "true" : "false") . "'),"; //Choice B
                    $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',3,'" . $dataIn[5] . "','" . (($dataIn[7] == "C") ? "true" : "false") . "'),"; //Choice C
                    $sql .= "(null,'" . $ct_last_id . "','" . $ca_id[$key] . "',4,'" . $dataIn[6] . "','" . (($dataIn[7] == "D") ? "true" : "false") . "'),"; //Choice E
                }
                $sql = rtrim($sql, ",");
                $stmt = $dbconn->prepare($sql);
                $stmt->execute();

                echo "OK";


                $dbconn->commit();
            } catch (PDOException $e) {
                $dbconn->rollBack();
                echo json_encode([
                    "message" => "Error",
                    "detail"  => $e->getMessage()
                ]);
                exit();
            }
        break;

        case 'uploadExcel':

            /**
             * file info
             */
            $fileName = basename($_FILES['inputFile']['name']);
            $fileExt = pathinfo($_FILES["inputFile"]["name"], PATHINFO_EXTENSION);
            $fileType = $_FILES['inputFile']['type'];
            $fileTMP = $_FILES['inputFile']['tmp_name'];
            $fileSize = $_FILES['inputFile']['size'];
            $fileErr = $_FILES['inputFile']['error'];

            /*
                    create Name
                */
            $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;

            /*
                    Check FILE TYPE
                    application/octet-stream
                */
            if (strtolower($fileType) !== "application/octet-stream" &&
                strtolower($fileType) !== "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                ) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_type',
                    'type'    => $fileType
                ]);
                exit();
            }

            /*
                    Check File Extension 
                    xlsx , xlsm , xls
                */
            if (!in_array($fileExt, ["xlsx", "xlsm", "xls"])) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_extension'
                ]);
                exit();
            }

            ///home/kotapisc/domains/kotapis.com/public_html/sel/backend/ajax
            $filePath = __DIR__ . "/../../upload/excel/" . $fileNameNew;
            if (move_uploaded_file($fileTMP, $filePath)) {

                
                InsertExcelToDB($fileNameNew);

                echo json_encode([
                    'message' => 'OK',
                    'detail'  => 'Upload_Success'
                ]);
                exit();
            } else {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'Upload_Failed',
                    'test' => $filePath
                ]);
                exit();
            }
        break;

        case 'uploadImg':
            /**
             * file info
             */
            $fileName = basename($_FILES['inputFile']['name']);
            $fileExt = pathinfo($_FILES["inputFile"]["name"], PATHINFO_EXTENSION);
            $fileType = $_FILES['inputFile']['type'];
            $fileTMP = $_FILES['inputFile']['tmp_name'];
            $fileSize = $_FILES['inputFile']['size'];
            $fileErr = $_FILES['inputFile']['error'];

            /*
                    create Name
                */
            $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;

            /*
                    Check FILE TYPE
                    application/octet-stream
                */
            if ((strtolower($fileType) !== "image/jpeg") && (strtolower($fileType) !== "image/png")) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_type',
                    'type'    => $fileType
                ]);
                exit();
            }

            /*
                    Check File Extension 
                    xlsx , xlsm , xls
                */
            if (!in_array($fileExt, ["jpg", "jpeg", "png"])) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_extension',
                    'extension' => $fileExt
                ]);
                exit();
            }

            ///home/kotapisc/domains/kotapis.com/public_html/sel/backend/ajax
            $filePath = __DIR__ . "/../../upload/excel/" . $fileNameNew;
            if (move_uploaded_file($fileTMP, $filePath)) {

                echo json_encode([
                    'message' => 'OK',
                    'detail'  => 'Upload_Success',
                    'urlimg'  => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/upload/excel/" . $fileNameNew
                ]);
                exit();
            } else {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'Upload_Failed'
                ]);
                exit();
            }
        break;
        case 'uploadImgCert':
            /**
             * file info
             */
            $fileName = basename($_FILES['inputFile']['name']);
            $fileExt = pathinfo($_FILES["inputFile"]["name"], PATHINFO_EXTENSION);
            $fileType = $_FILES['inputFile']['type'];
            $fileTMP = $_FILES['inputFile']['tmp_name'];
            $fileSize = $_FILES['inputFile']['size'];
            $fileErr = $_FILES['inputFile']['error'];

            /*
                    create Name
                */
            $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;

            /*
                    Check FILE TYPE
                    application/octet-stream
                */
            if ((strtolower($fileType) !== "image/jpeg") && (strtolower($fileType) !== "image/png")) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_type',
                    'type'    => $fileType
                ]);
                exit();
            }

            /*
                    Check File Extension 
                    xlsx , xlsm , xls
                */
            if (!in_array($fileExt, ["jpg", "jpeg", "png"])) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_extension',
                    'extension' => $fileExt
                ]);
                exit();
            }
                
            // print_r($_POST); exit();
                

            ///home/kotapisc/domains/kotapis.com/public_html/sel/backend/ajax
            $filePath = __DIR__ . "/../../upload/certify/" . $fileNameNew;
            if (move_uploaded_file($fileTMP, $filePath)) {


                //เช็คว่ามีข้อมูลหรือยัง
                $sql = "SELECT * FROM certify_property WHERE ct_id = :ct_id AND cp_type = :cp_type";
                $value = array(
                    ":ct_id" => $_POST['ct_id'],
                    ":cp_type" => $_POST['cp_type']
                );
                $res = $dbcon->fetchObject($sql,$value);


                if(!$res){
                    
                    $field = "cp_id,ct_id,cp_img,cp_name,cp_size,cp_weight,cp_type,cp_y,cp_x,cp_create,cp_update";
                    $key = ":cp_id,:ct_id,:cp_img,:cp_name,:cp_size,:cp_weight,:cp_type,:cp_y,:cp_x,:cp_create,:cp_update";
                    $value = array(
                        ":cp_id" => 0,
                        ":ct_id" => $_POST['ct_id'],
                        ":cp_img" => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/upload/certify/" . $fileNameNew,
                        ":cp_name" => $_POST['cp_type'],
                        ":cp_size" => 100,
                        ":cp_weight" => 500,
                        ":cp_type" => $_POST['cp_type'],
                        ":cp_y" => 50,
                        ":cp_x" => 50,
                        ":cp_create" => date('Y-m-d H:i:s'),
                        ":cp_update" => date('Y-m-d H:i:s'),
                    );
                    $res = $dbcon->insertPrepare("certify_property", $field, $key , $value);
                    // print_r($res); exit();
                }
                else {

                    // print_r($_POST);
                    // exit();

                    $id = $_POST['ct_id'];
                    if(!is_numeric($id)){
                        echo json_encode([
                            'message' => 'Error',
                            'detail'  => 'Upload_Failed id type invalid'
                        ]);
                        exit();
                    }

                    $set = "cp_img = :img , cp_update = :cp_update";
                    $where = "ct_id = :id AND cp_type = :cp_type";
                    $value = array(
                        ":img" => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/upload/certify/" . $fileNameNew,
                        ":cp_update" => date('Y-m-d H:i:s'),
                        ":id" => $id,
                        ":cp_type" => $_POST['cp_type']
                    );
                    $res = $dbcon->update_prepare("certify_property", $set, $where, $value);
                    // echo json_encode($res); exit();
                }


                echo json_encode([
                    'message' => 'OK',
                    'detail'  => 'Upload_Success',
                    'urlimg'  => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/upload/certify/" . $fileNameNew
                ]);
                exit();
            } else {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'Upload_Failed'
                ]);
                exit();
            }
        break;

        case 'get_certifyTableGrid':
            $requestData = $_REQUEST;
            $columns = array(
                0 => '',
                1 => 'ct.id',
                2 => '',
                3 => '',
                4 => '',
                5 => '',
                6 => '',
                7 => '',

            );

            $sql = "SELECT ct.id , ct.title ,ct.percent_score, ct.status  , ct.filename ,COUNT(ca.ct_id) as qty 
                        FROM certify_title as ct
                        INNER JOIN certify_ask as ca ON ca.ct_id = ct.id
                        ";

            if (!empty($requestData['search']['value'])) {

                $sql .= " WHERE display = 'yes' AND ct.title LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " GROUP BY ca.ct_id ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            } else {
                $sql .= " WHERE display = 'yes' ";
                $sql .= " GROUP BY ca.ct_id ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];
            }

            
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);

            $output = array();
            if ($result) {
                foreach ($result as $key => $value) {

                    $nestedData = array();

                    $nestedData[] = '';
                    $nestedData[] = $key + 1;
                    $nestedData[] = $value['title'];
                    $nestedData[] = $value['qty'];
                    $nestedData[] = '<input class="form-control percent-score" data-id="id-'.$value['id'].'" style="width:60px;" type="number" value="'.$value['percent_score'].'">
                                    <button class="btn btn-primary" onclick="savePercentScore(event,'.$value['id'].')">บันทึก</button>
                                    ';
                    
                    $nestedData[] = "
                                    <div class=\"toggle-switch ".( ($value['status'] === "open")?"ts-active":"" )."  \" style=\"margin: auto\">
                                        <span class=\"switch\" onclick=\"toggle_switch(event,".$value['id'].")\"></span>
                                    </div>
                    ";
                    $nestedData[] = '
                                <!-- <a class="btn kt:btn-primary" style="color:white;" ><i class="fa fa-eye" aria-hidden="true"></i> ดู</a> -->
                                <!-- <a class="btn kt:btn-warning" style="color:white;" ><i class="fa fa-pencil-square-o"></i> แก้ไข</a> -->
                                <a href="/backend/?page=certify&subpage=set_certify&id='.$value['id'].'" target="_blank" class="btn kt:btn-success" style="color:white;" ><i class="fa fa-pencil-square-o"></i> ใบประกาศ</a>
                                <a class="btn kt:btn-danger" onclick="delCertifyByCT_ID(event,'.$value['id'].')" style="color:white;"  ><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                        ';
                    $nestedData[] = "<a href=\"/upload/excel/".$value['filename']."\" download>ดาวน์โหลด</a>";

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

        case 'get_certifyReportTableGrid':
            $requestData = $_REQUEST;
            $columns = array(
                0 => '',
                1 => 'log_id',
                2 => 'ct.title',
                3 => 'm.member_name',
                4 => '',
                5 => '',
                6 => '',
                7 => '',
                8 => '',

            );

            $sql = "SELECT cl.log_id,count(cl.goal) as qty , ct.title , m.member_name , ct.create_date 
                    FROM certify_log as cl
                    INNER JOIN certify_title as ct ON cl.ct_id = ct.id
                    INNER JOIN members as m ON cl.member_id = m.member_id
                    ";

            $sql2 = "SELECT cl.log_id,count(cl.goal) as point 
                    FROM certify_log as cl
                    INNER JOIN certify_title as ct ON cl.ct_id = ct.id
                    INNER JOIN members as m ON cl.member_id = m.member_id
                    ";
            

            if (!empty($requestData['search']['value'])) {
                if(!empty($_POST['selectCertifyTitle'])){
                    $sql .= " WHERE ct.title LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR member_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR log_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " AND ct.id = '".$_POST['selectCertifyTitle']."' ";
                    $sql .= " GROUP BY cl.log_id  ";
                    $sql .= " ORDER BY cl.id ASC";

                    $sql2 .= " WHERE ct.title LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql2 .= " OR member_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql2 .= " OR log_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql2 .= " AND ct.id = '".$_POST['selectCertifyTitle']."' ";
                    $sql2 .= " AND cl.goal = 'true' ";
                    $sql2 .= " GROUP BY cl.log_id ";
                    $sql2 .= " ORDER BY cl.id ASC ";

                }else{
                    $sql .= " WHERE ct.title LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR log_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR member_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " GROUP BY cl.log_id  ";
                    $sql .= " ORDER BY cl.id ASC";

                    $sql2 .= " WHERE ct.title LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql2 .= " OR log_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql2 .= " AND cl.goal = 'true' GROUP BY cl.log_id ORDER BY cl.id ASC ";
                }
            } else {
                if(!empty($_POST['selectCertifyTitle'])){
                    $sql .= " WHERE ct.id = '".$_POST['selectCertifyTitle']."' ";
                    $sql .= " GROUP BY cl.log_id  ";
                    $sql .= " ORDER BY cl.id ASC";
                    
                    $sql2 .= " WHERE cl.goal = 'true' AND ct.id = '".$_POST['selectCertifyTitle']."' ";
                    $sql2 .= " GROUP BY cl.log_id ORDER BY cl.id ASC ";
                }else{
                    $sql .= " GROUP BY cl.log_id  ";
                    $sql .= " ORDER BY cl.id ASC";
                    
                    $sql2 .= "  WHERE cl.goal = 'true' 
                                GROUP BY cl.log_id 
                                ORDER BY cl.id ASC ";
                }
            }

            
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $sql2 .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

            $result = $dbcon->query($sql);
            $point = $dbcon->query($sql2);
            $output = array();
            // print_r($result); 
            // print_r($sql2);
            // exit();
            if ($result) {
                foreach ($result as $key => $value) {

                    $nestedData = array();

                    $nestedData[] = '';
                    $nestedData[] = ($key + 1);
                    $nestedData[] = $value['log_id'];
                    $nestedData[] = $value['title'];
                    $nestedData[] = $value['member_name'];
                    $nestedData[] = $value['qty'];

                    //หาคะแนน
                    $sqlpoint = " SELECT count(cl.goal) as point 
                                FROM certify_log as cl
                                WHERE cl.goal = 'true' AND cl.log_id =:log_id 
                                GROUP BY cl.log_id ";
                    $valPoint = array(":log_id" => $value['log_id']);
                    $resPoint = $dbcon->fetchObject($sqlpoint,$valPoint);

                    $nestedData[] = number_format($resPoint->point);
                    // $nestedData[] = number_format($point[$key]['point']);
                    
                    $nestedData[] = $value['create_date'];
                    $nestedData[] = '
                                <a class="btn kt:btn-primary" style="color:white;" onclick="showCertifyLogByLog_id(event,'.$value['log_id'].')"><i class="fa fa-eye" aria-hidden="true"></i> ดู</a>
                                <!-- <a class="btn kt:btn-warning" style="color:white;" ><i class="fa fa-pencil-square-o"></i> แก้ไข</a> -->
                                <a class="btn kt:btn-danger" onclick="delCertifyLogByLog_id(event,'.$value['log_id'].')" style="color:white;"  ><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                        ';

                    $output[] = $nestedData;
                }
            }

            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
                "sql"  => $sql
            );
            echo json_encode($json_data);
        break;

        case 'editStatusCertify':
            if(!is_numeric($_POST['ct_id'])){
                echo json_encode([
                    'message' => 'error',
                    'id' => 'id not number',
                ]); exit();
            }
            $ct_id = filter_var($_POST['ct_id'],FILTER_SANITIZE_NUMBER_INT);
            $status = $_POST['status'];

            $sql = "SELECT id FROM certify_title WHERE id =:id";
            $value = array(
                ':id' => $ct_id
            );
            $stmt = $dbconn->prepare($sql);
            $stmt->execute($value);
            $res1 = $stmt->rowCount();
            
            if(empty($res1)){
                echo json_encode([
                    'message' => 'error',
                    'id' => 'id not has',
                ]); exit();
            }

            try{
                $dbconn->beginTransaction();
                //set certify_title 
                $sql = "UPDATE certify_title SET status = :status WHERE certify_title.id = :id";
                $stmt = $dbconn->prepare($sql);
                $stmt->execute([
                    ':status' => $status,
                    ':id'      => $ct_id
                ]);
                $dbconn->commit();
                echo json_encode([
                    'message' => 'OK'
                ]); exit();
            }catch(PDOException $e){
                $dbconn->rollBack();
                echo json_encode([
                    'message' => 'error',
                    'detail'  => $e->getMessage()
                ]); exit();
            }

        break;

        case 'deleteCertify':
            if(!is_numeric($_POST['ct_id'])){
                echo json_encode([
                    'message' => 'error',
                    'id' => 'id not number',
                ]); exit();
            }

            $ct_id = filter_var($_POST['ct_id'],FILTER_SANITIZE_NUMBER_INT);


            $sql = "SELECT id FROM certify_title WHERE id =:id";
            $value = array(
                ':id' => $ct_id
            );
            $stmt = $dbconn->prepare($sql);
            $stmt->execute($value);
            $res1 = $stmt->rowCount();
            
            if(empty($res1)){
                echo json_encode([
                    'message' => 'error',
                    'id' => 'id not has',
                ]); exit();
            }

            try{
                $dbconn->beginTransaction();
                //set certify_title 
                $sql = "UPDATE certify_title SET display = :display WHERE certify_title.id = :id";
                $stmt = $dbconn->prepare($sql);
                $stmt->execute([
                    ':display' => 'no',
                    ':id'      => $ct_id
                ]);
                $dbconn->commit();
                echo json_encode([
                    'message' => 'OK'
                ]); exit();
            }catch(PDOException $e){
                $dbconn->rollBack();
                echo json_encode([
                    'message' => 'error',
                    'detail'  => $e->getMessage()
                ]); exit();
            }

        break;
        
        case 'deleteCertifyLogBy_logID':
            if(!is_numeric($_POST['log_id'])){
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'log_id not number'
                ]); exit();
            }    

            $log_id = filter_var($_POST['log_id'],FILTER_SANITIZE_NUMBER_INT);
            
            $sql = "SELECT log_id FROM certify_log WHERE log_id =:log_id";
            $res = $dbcon->fetchObject($sql,[':log_id' => $log_id]);

            if(empty($res)){
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'empty'
                ]); exit();
            }


            try{
                $dbconn->begintransaction();
                $sql = "DELETE FROM certify_log WHERE log_id =:log_id";
                $stmt = $dbconn->prepare($sql);
                $stmt->bindParam(":log_id",$log_id);
                $stmt->execute();


                $dbconn->commit();
                echo json_encode([
                    'message' => 'OK',
                    'detail'  => 'delete success'
                ]); exit();
            }catch(PDOException $e){
                $dbconn->rollBack();
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'exception delete'
                ]); exit();
            }
            

        break;

        case 'getCertifyLogBy_logID':

            if(!isset($_POST['log_id'])){
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'log_id none'
                ]); exit();
            }

            if(!is_numeric($_POST['log_id'])){
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'log_id not number'
                ]); exit();
            }    

            $log_id = filter_var($_POST['log_id'],FILTER_SANITIZE_NUMBER_INT);
            
            $sql = "SELECT cl.id,cl.log_id,cl.ct_id,cl.answ_id,cl.goal,cl.create_date,
                            ct.title , cask.title as cask_title , ca.title as ca_title,
                            m.member_id , m.member_name

                    FROM certify_log as cl
                    INNER JOIN members as m ON m.member_id = cl.member_id 
                    INNER JOIN certify_title as ct ON ct.id = cl.ct_id 
                    INNER JOIN certify_ask as cask ON cask.id = cl.ca_id 
                    INNER JOIN certify_answ as ca ON ca.answ_id = cl.answ_id AND ca.ct_id = cl.ct_id AND ca.ca_id = cl.ca_id
                    WHERE cl.log_id =:log_id
                    ";
            $res = $dbcon->fetchAll($sql,[':log_id' => $log_id]);

            if(empty($res)){
                echo json_encode([
                    'message' => 'error',
                    'detail'  => 'empty'
                ]); exit();
            }
            $out = "
            <table class=\"table\">
                <thead>
                <tr>
                    <th scope=\"col\" style=\"text-align:center\">ลำดับ</th>
                    <th scope=\"col\" style=\"text-align:center\">คำถาม</th>
                    <th scope=\"col\" style=\"text-align:center\">คำตอบที่เลือก</th>
                    <th scope=\"col\" style=\"text-align:center\">ผลลัพธ์</th>
                </tr>
            </thead>
            ";
            foreach($res as $key => $val){
                if($val['answ_id'] == 1) $select = "A"; 
                if($val['answ_id'] == 2) $select = "B";
                if($val['answ_id'] == 3) $select = "C";
                if($val['answ_id'] == 4) $select = "D";
                $out .= "
                    <tbody>
                        <tr>
                            <th scope=\"row\" style=\"text-align:center\">".($key+1)."</th>
                            <td style=\"text-align:center\">".$val['cask_title']."</td>
                            <td style=\"text-align:center\">".$select.'. '.$val['ca_title']."</td>
                            <td>".($val['goal']==="true"?"<i class=\"fa fa-check\" style=\"color:mediumseagreen;display:block;text-align:center;\"></i>":"<i class=\"fa fa-times\" style=\"color:red;display:block;text-align:center;\"></i>")."</td>
                        </tr>
                    </tbody>
                ";
            }

            $out .= "</table>";


            $sqlpoint = "SELECT count(cl.goal) as point 
                        FROM certify_log as cl
                        INNER JOIN certify_title as ct ON cl.ct_id = ct.id
                        INNER JOIN members as m ON cl.member_id = m.member_id
                        WHERE cl.log_id =:log_id AND cl.goal = 'true' GROUP BY cl.log_id ORDER BY cl.id ASC
                        ";
            $resPoint = $dbcon->fetchObject($sqlpoint,[':log_id' => $log_id]);

            echo json_encode([
                'message' => 'OK',
                'title' => $res[0]['title'],
                'memberName' => $res[0]['member_name'],
                'date' => $res[0]['create_date'],
                'res' => $out,
                'point' => number_format($resPoint->point)
            ]);
        break;

        case 'saveCertifyProperty':
            
            

            if($_POST['cp_id'] == 0){
                

                
                $field = "cp_id,ct_id,cp_img,cp_name,cp_size,cp_weight,cp_type,cp_y,cp_x,cp_create,cp_update";
                $key = ":cp_id,:ct_id,:cp_img,:cp_name,:cp_size,:cp_weight,:cp_type,:cp_y,:cp_x,:cp_create,:cp_update";
                $value = array(
                    ":cp_id" => 0,
                    ":ct_id" => $_POST['ct_id'],
                    ":cp_img" => '-',
                    ":cp_name" => $_POST['name'],
                    ":cp_size" => $_POST['size'],
                    ":cp_weight" => $_POST['weight'],
                    ":cp_type" => $_POST['cp_type'],
                    ":cp_y" => $_POST['y'],
                    ":cp_x" => $_POST['x'],
                    ":cp_create" => date('Y-m-d H:i:s'),
                    ":cp_update" => date('Y-m-d H:i:s'),
                );
                $res = $dbcon->insertPrepare("certify_property", $field, $key , $value);
                
                echo json_encode($res);
                
                break;
            }else{
                
                $set = "cp_name = :name , cp_size = :size , cp_weight = :weight , cp_type = :cp_type , cp_y = :y , cp_x = :x , cp_update = :cp_update";
                $where = "cp_id = :id";
                $value = array(
                    ":name" => empty($_POST['name'])?'--':$_POST['name'],
                    ":size" => empty($_POST['size'])?'16':$_POST['size'],
                    ":weight" => empty($_POST['weight'])?'0':$_POST['weight'],
                    ":cp_type" => empty($_POST['cp_type'])?'0':$_POST['cp_type'],
                    ":y" => empty($_POST['y'])?'0':$_POST['y'],
                    ":x" => empty($_POST['x'])?'0':$_POST['x'],
                    ":id" => empty($_POST['cp_id'])?'0':$_POST['cp_id'],
                    ":cp_update" => date('Y-m-d H:i:s'),
                );

                
                $res = $dbcon->update_prepare("certify_property", $set, $where, $value);
                echo json_encode($res);
                
            }
        break;


        case 'saveCertifyPropertyImage':


            $set = "cp_y = :y , cp_x = :x , cp_update = :cp_update";
            $where = "cp_id = :id";
            $value = array(
                ":y" => empty($_POST['y'])?'0':$_POST['y'],
                ":x" => empty($_POST['x'])?'0':$_POST['x'],
                ":id" => empty($_POST['cp_id'])?'0':$_POST['cp_id'],
                ":cp_update" => date('Y-m-d H:i:s'),
            );

                
            $res = $dbcon->update_prepare("certify_property", $set, $where, $value);
            echo json_encode($res);
        break;
            

        case 'saveTempPropertyTest':
            // echo json_encode([
            //     'POST' => $_POST
            // ]); exit();

            
            //เช็คว่ามีข้อมูลหรือยัง
            $sql = "SELECT * FROM certify_property WHERE ct_id = :ct_id AND cp_type = :cp_type";
            $value = array(
                ":ct_id" => $_POST['ct_id'],
                ":cp_type" => $_POST['cp_type']
            );
            $res = $dbcon->fetchObject($sql,$value);

            // print_r($res);
            // exit();


            if(!$res){

                $field = "cp_id,ct_id,cp_img,cp_name,cp_size,cp_weight,cp_type,cp_y,cp_x,cp_create,cp_update";
                $key = ":cp_id,:ct_id,:cp_img,:cp_name,:cp_size,:cp_weight,:cp_type,:cp_y,:cp_x,:cp_create,:cp_update";
                $value = array(
                    ":cp_id" => 0,
                    ":ct_id" => $_POST['ct_id'],
                    ":cp_img" => '-',
                    ":cp_name" => $_POST['name'],
                    ":cp_size" => $_POST['size'],
                    ":cp_weight" => $_POST['weight'],
                    ":cp_type" => $_POST['cp_type'],
                    ":cp_y" => $_POST['y'],
                    ":cp_x" => $_POST['x'],
                    ":cp_create" => date('Y-m-d H:i:s'),
                    ":cp_update" => date('Y-m-d H:i:s'),
                );
                // echo "insertPrepare";
                // print_r($value);
                // exit();

                $res = $dbcon->insertPrepare("certify_property", $field, $key , $value);
                echo json_encode($res);
                
                break;
            }else{
                
                $set = "cp_name = :name , cp_size = :size , cp_weight = :weight , cp_type = :cp_type , cp_y = :y , cp_x = :x , cp_update = :cp_update";
                $where = "cp_id = :id";
                $value = array(
                    ":name" => empty($_POST['name'])?'--':$_POST['name'],
                    ":size" => empty($_POST['size'])?'16':$_POST['size'],
                    ":weight" => empty($_POST['weight'])?'0':$_POST['weight'],
                    ":cp_type" => empty($_POST['cp_type'])?'0':$_POST['cp_type'],
                    ":y" => empty($_POST['y'])?'0':$_POST['y'],
                    ":x" => empty($_POST['x'])?'0':$_POST['x'],
                    ":id" => empty($_POST['cp_id'])?'0':$_POST['cp_id'],
                    ":cp_update" => date('Y-m-d H:i:s'),
                );
                // echo "update_prepare";
                // print_r($value);
                // exit();
                
                $res = $dbcon->update_prepare("certify_property", $set, $where, $value);
                echo json_encode($res);
                
            }
        break;

        case 'updatePercentScore':
            if(!is_numeric($_POST['percent_score']) || !is_numeric($_POST['id']) ){
                echo json_encode(['message' => 'Error']);
                exit();
            }

            $set = "percent_score = :percent_score,update_date = :update_date";
            $where = "id = :id";
            $value = array(
                ":percent_score" => $_POST['percent_score'],
                ":id" => $_POST['id'],
                ":update_date" => date('Y-m-d H:i:s')
            );
            // echo "update_prepare";
            // print_r($value);
            // exit();
            
            $res = $dbcon->update_prepare("certify_title", $set, $where, $value);
            echo json_encode($res);
        break;
    }
}
