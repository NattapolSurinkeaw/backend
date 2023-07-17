<?php
// use function GuzzleHttp\json_encode;

session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';
$dbcon = new DBconnect();
getData::init();

if (isset($_REQUEST['action'])) {

    $lang_config = getData::lang_config();

    switch ($_REQUEST['action']) {
        case 'get_pdf':

            $requestData = $_REQUEST;
            $columns = array(
                0 => '',
                1 => 'id',
                2 => 'name',
                3 => 'link',


            );

            $sql = "SELECT * FROM upload_pdf";

            if (!empty($requestData['search']['value'])) {

                $sql .= " WHERE name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            } else {
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
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
                    $nestedData[] = ($key+1);
                    $nestedData[] = $value['category'];
                    $nestedData[] = $value['name'];
                    $nestedData[] = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/".$value['link'];
                    // $nestedData[] = ($value['product_cate_display']=="yes")?"<i class=\"fa fa-check\" aria-hidden=\"true\" style='color:mediumseagreen;display:block;text-align:center;'></i>":"<i class=\"fa fa-times\" aria-hidden=\"true\" style='color:red;display:block;text-align:center;'></i>";
                    $nestedData[] = '
                            <a href="/'.$value['link'].'" target="_blank" class="" style="color:white;"> <img src="/upload/pdf/pdf.png" style="width:40px;"> </a>
                    ';
                    $nestedData[] = '
                            <a href="#" class="btn btn-sm kt:btn-danger" onclick="deletePDF(event,'.$value['id'].')" style="color:white;"><i class="fa fa-trash"></i> ลบ </a>
                            <a href="#" class="btn btn-sm kt:btn-warning" onclick="editPDF(event,'.$value['id'].')" style="color:white;"><i class="fa fa-edit"></i> แก้ไขชื่อ </a>
                    ';

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

        case 'uploadimgcontent':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);

            if(empty($images)){
                echo json_encode([
                    'event' => 'update',
                    'status' => 200,
                    'message' => "OK",
                    'image'   => 'null'
                ]);
            }else{
                $table = "product_cate";
                $set = "product_cate_img = '" . $images['0'] . "'";
                $where = "product_cate_id = '" . $_REQUEST['id'] ."'";
                $result = $dbcon->update($table, $set, $where);
                echo json_encode($result);
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
            if (strtolower($fileType) !== "application/pdf"
                ) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_type',
                    'type'    => $fileType,
                    'test'    => $_FILES['inputFile']
                ]);
                exit();
            }

            /*
                    Check File Extension 
                    xlsx , xlsm , xls
                */
            if (!in_array($fileExt, ["pdf"])) {
                echo json_encode([
                    'message' => 'Error',
                    'detail'  => 'error_extension'
                ]);
                exit();
            }

            ///home/kotapisc/domains/kotapis.com/public_html/sel/backend/ajax
            $filePath = __DIR__ . "/../../upload/pdf/" . $fileNameNew;
            if (move_uploaded_file($fileTMP, $filePath)) {

                
                $sql = "INSERT INTO upload_pdf (category,name,link,create_date) VALUES (:category,:name,:link,NOW())";
                $value = array(
                    ":name" => $_POST['name'],
                    ":category" => $_POST['category'],
                    ":link" => "upload/pdf/".$fileNameNew
                );
                $resInsert = $dbcon->insertValue($sql, $value);

                echo json_encode([
                    'message' => 'OK',
                    'detail'  => 'Upload_Success',
                    'file' => "/upload/pdf/".$fileNameNew
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

        case 'getById':
            
            $sql = "SELECT * FROM upload_pdf WHERE id =:id";
            $value = array(
                ":id" => $_POST['id']
            );
            $res = $dbcon->fetchObject($sql,$value);
            echo json_encode([
                "message" => 'OK',
                'res' => $res
            ]);
        break;

        case 'edit_uploadExcel':

            $table = "upload_pdf";
            $set = "category =:category , name=:name";
            $where = "id =:id";
            $value = array(
                ":category" => $_POST['category'],
                ":name" => $_POST['name'],
                ":id" => $_POST['id']
            );
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;

        case 'deletePdf':
            $table = "upload_pdf";
            $where = "id = :id";
            $val = array(
                ":id" => $_POST['id']
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
    }
}
