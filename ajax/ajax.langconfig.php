<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
// require_once dirname(__DIR__) . '/classes/preheader.php';
// require_once('../classes/class.langconfig.php');

$dbcon = new DBconnect();
//getData::init();
// $mydata = new langconfig();

if (isset($_REQUEST['action'])) {
    $output = $_SESSION['backend_language'];

    //ดึงรายการข้อมูลภาษาทั้งหมดเพื่อไปแสดงในตาราง มีฟังก์ชั่นค้นหา
    switch ($_REQUEST['action']) {
        case 'getlanguage':

            $av_lang_session = explode(",", $_SESSION['available_language']);
            foreach ($av_lang_session as $key) {
                if ($key != '') {
                    $av_lang[$key] = $key;
                }
            }

            $index_array = 2;
            $requestData = $_REQUEST;
            #game comment
            /*$columns = array(
            0 => 'param',
            1 => 'defaults',
            2 => 'TH',
            3 => 'EN',
            4 => 'action'
            );*/

            $columns = array(
                0 => 'param',
                1 => 'defaults',
            );
            # เพิ่มการแอเรย์ ภาษาที่ดึงมาจากการอนุญาติหลังบ้าน
            foreach ($av_lang as $key) {
                array_push($columns, $key);
            }
            array_push($columns, "action"); # เพิ่ม action ไปหลังสุด

            $sql = "SELECT * FROM lang_config";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {
                $sql = "SELECT * ";
                $sql .= " FROM lang_config";
                $sql .= " WHERE param LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR defaults LIKE '%" . $requestData['search']['value'] . "%' ";
                /*$sql.=" OR TH LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR EN LIKE '%".$requestData['search']['value']."%' ";*/
                foreach ($av_lang as $key) { #ทำให้สามารถค้นหาภาษาได้หลายภาษา
                $sql .= " OR " . $key . " LIKE '%" . $requestData['search']['value'] . "%' ";
                }
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);

            } else {
                $sql = "SELECT * ";
                $sql .= " FROM lang_config";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);
            }

            $output = array();
            foreach ($result as $value) {

                $nestedData = array();
                $nestedData[] = $value["param"];
                $nestedData[] = '<input type="text" style="width:100%;" id="defaults-' . $value["id"] . '" class="form-control" value="' . $value["defaults"] . '">';
                /*$nestedData[] = '<input type="text" style="width:100%;" id="th-'.$value["id"].'" class="form-control" value="'.$value["TH"].'">';
                $nestedData[] = '<input type="text" style="width:100%;" id="en-'.$value["id"].'" class="form-control" value="'.$value["EN"].'">';*/
                foreach ($av_lang as $key) { #แสดงข้อมูลภาษาได้หลายภาษา
                $nestedData[] = '<input type="text" class = "av_lang_langconfig form-control" data-type = "' . strtolower($key) . '" style="width:100%;" id="' . strtolower($key) . '-' . $value["id"] . '" value="' . $value[$key] . '">';
                }
                $nestedData[] = '<button type="button" class="btn btn-block btn-success edit-lang" id="lang-' . $value["id"] . '" data-id="' . $value["id"] . '"><i class="fa fa-floppy-o"></i> Save</button>';

                $output[] = $nestedData;
            }

            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
            );
            echo json_encode($json_data);
						break;
						
				case 'editlanguage':
				
            $av_lang_session = explode(",", $_SESSION['available_language']);
            $set = "";
            $table = "lang_config";
            foreach ($av_lang_session as $key) {
                if ($key != '') {
                    $set .= $key . " = '" . $_POST[strtolower($key)] . "',";
                }
            }
            $set .= "defaults = '" . $_POST['defaults'] . "'";
            $where = "id = '" . $_POST['id'] . "'";
            $result = $dbcon->update($table, $set, $where);
            echo json_encode([$result]);

            break;
        case 'addlanguage':

            $value = "";
						$field = "";
            $av_lang_session = explode(",", $_SESSION['available_language']);
						
						foreach ($av_lang_session as $key) {
                if ($key != '') {
										$field .= $key . ",";
										$value .= "'" . $_REQUEST[$key] . "',";
                }
            }
						$field .= "param,defaults";
            $value .= "
              '" . $_REQUEST['parameter'] . "',
              '" . $_REQUEST['defaults'] . "'";

            $result = $dbcon->insert('lang_config', $field, $value);
            echo json_encode($result);
            break;
    }
}
