<?php session_start();
    require_once dirname(__DIR__) . '/classes/class.protected_web.php';
    ProtectedWeb::methodPostOnly();
    ProtectedWeb::login_only();

    require_once dirname(__DIR__) . '/classes/dbquery.php';
    require_once dirname(__DIR__) . '/classes/preheader.php';
    $dbcon = new DBconnect();
    getData::init(); 

    if (isset($_REQUEST['action'])) {
        $lang_config = getData::lang_config();
        switch ($_REQUEST['action']) {
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
                $id = $_POST['inputID'];

                /*
                    create Name
                */

                $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;

                /*
                    Check FILE TYPE
                    application/octet-stream
                    image/svg+xml
                */

                if ((strtolower($fileType) !== "image/jpeg") && 

                    (strtolower($fileType) !== "image/png") && (strtolower($fileType) !== "image/svg+xml") ) {

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
                if (!in_array($fileExt, ["jpg", "jpeg", "png","svg"])) {
                    echo json_encode([
                        'message' => 'Error',
                        'detail'  => 'error_extension',
                        'extension' => $fileExt
                    ]);
                    exit();
                }

                ///home/kotapisc/domains/kotapis.com/public_html/sel/backend/ajax
                $filePath = __DIR__ . "/../../upload/bank/" . $fileNameNew;
                if (move_uploaded_file($fileTMP, $filePath)) {
                    $fileImageName = "/upload/bank/".$fileNameNew;
                    //Update Image TO Table general_facilities
                    $sql = "UPDATE bank_info SET img=:image WHERE id=:id";
                    $res = $dbcon->updateValue($sql,[":image" => $fileImageName,":id" => $id]);
                    echo json_encode($res);
                    // echo json_encode([
                    //     'message' => 'OK',
                    //     'detail'  => 'Upload_Success',
                    // ]);
                    exit();
                } else {
                    echo json_encode([
                        'message' => 'Error',
                        'detail'  => 'Upload_Failed'
                    ]);
                    exit();
                }
            break;



            case 'get_bank':
 
                $requestData = $_REQUEST; 
                $columns = array(
                    0 => 'id',
                    1 => 'name',
                    2 => 'number',
                    3 => 'img',
                    4 => 'date_create',
                    5 => 'date_update',
                    6 => '',
                );

                $sql = "SELECT * FROM bank_info";
                if (!empty($requestData['search']['value'])) {
                    $sql .= " WHERE name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR number LIKE '%" . $requestData['search']['value'] . "%' ";
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
                        // $nestedData[] = '<center><img src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['profile'] . '&size=30"></center>';
                        $nestedData[] = ++$key;
                        $nestedData[] = $value['name'];
                        $nestedData[] = $value['number'];
                        $nestedData[] = '<div style="text-align: center;"><img src="'.ROOT_URL.$value['img'].'" style="width: 70px;height:50px;"></div>';
                        $nestedData[] = $value['date_create'];
                        $nestedData[] = $value['date_update'];
                        $nestedData[] = '<div style="display: flex; justify-content: center;">
                            <a class="btn kt:btn-warning" style="color:white; margin: 0px 2px;" onclick="editBank(event,' . $value['id'] . ')"><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                            <a class="btn kt:btn-danger" style="color:white;  margin: 0px 2px;"  onclick="delBank(event,' . $value['id'] . ')"><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                            </div>
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

            case 'save_bank':

                $dateTime = date('Y-m-d H:i:s');
                $sql = "INSERT INTO bank_info(name,account,number,img,date_create,date_update) VALUES (:name,:account,:number,:img,:date_create,:date_update) ";
                $value = array(
                    ":name" => $_POST['name'],
                    ":account" => $_POST['account'],
                    ":number" => $_POST['number'],
                    ":img" => '-',
                    ":date_create" => $dateTime,
                    ":date_update" => $dateTime
                );

                $result = $dbcon->insertValue($sql, $value);

                echo json_encode($result);

            break;



            case 'getBankById':

                if (!is_numeric($_POST['id'])) {

                    echo json_encode([

                        'message' => 'error',

                        'detail'  => 'invalid_number'

                    ]);

                    exit();

                }

                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

                $sql = "SELECT  *

                    FROM bank_info 

                    WHERE id = '" . $id . "'";

                $result = $dbcon->fetch($sql);

                echo json_encode([

                    'message' => "OK",

                    'result'  => $result

                ]);

            break;



            case 'editBank':

                if (!is_numeric($_POST['id'])) {

                    echo json_encode([

                        'message' => 'error',

                        'detail'  => 'invalid_number'

                    ]);

                    exit();

                }

                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

                $display = ($_POST['status'] == "active")?"yes":"no";

                $table = "bank_info";

                $set = "

                    name = :name,
                    account = :account,
                    number = :number,
                    date_update = :date_update

                ";

                $where = " id = :id ";

                $value = array(

                    ':name' => $_POST['name'],
                    ':account' => $_POST['account'],
                    ':number' => $_POST['number'],

                    ':date_update' => date('Y-m-d H:i:s'),

                    ':id' => $id

                );



                $result = $dbcon->update_prepare($table, $set, $where, $value);

                echo json_encode($result);

            break;



            case 'deleteBank':

                if (!is_numeric($_POST['id'])) {

                    echo json_encode([

                        'message' => 'error',

                        'detail'  => 'invalid_number'

                    ]);

                    exit();

                }

                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

                $table = "bank_info";

                $where = "id = '" . $id . "' ";

                $result = $dbcon->delete($table, $where);

                echo json_encode($result);

            break;

            



        }

    }

