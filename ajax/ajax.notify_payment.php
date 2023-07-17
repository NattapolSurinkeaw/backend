<?php
    session_start();
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
                $filePath = __DIR__ . "/../../upload/regis/" . $fileNameNew;
                if (move_uploaded_file($fileTMP, $filePath)) {

                    $sql = "INSERT INTO register_image(id,image,create_date,update_date) VALUES (null,:image,:create,:update)";
                    $value = array(
                        ":image" => "/upload/regis/".$fileNameNew,
                        ":create" => date('Y-m-d H:i:s'),
                        ":update" => date('Y-m-d H:i:s')
                    );
                    $res = $dbcon->insertValue($sql,$value);

                    echo json_encode($res);
                    // echo json_encode([
                    //     'message' => 'OK',
                    //     'detail'  => 'Upload_Success',
                    //     'urlimg'  => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/upload/excel/" . $fileNameNew
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

            case 'get_notify_payments': 
                #filter by action / bank / action status /   
                $requestData = $_REQUEST;
                $columns = array( 
                    1 => 'id',
                    2 => 'm_name',
                    3 => 'type',
                    4 => 'price',
                    5 => 'm_credit',
                    6 => 'b_name',
                    7 => 'b_number',
                    8 => 'date_update', 
                    9 => 'status',
                    10 => 'member_status'   
                ); 
                $sql = "SELECT rp.*,m.name as m_name , b.number as b_number ,m.credit as m_credit, b.name as b_name ,m.status as member_status
                        FROM record_paid as rp 
                        INNER JOIN members as m ON m.mem_id = rp.mem_id
                        LEFT JOIN bank_info as b ON b.id = rp.bank_id ";
                $sql .= " WHERE rp.id != '0'  ";
                if(isset($requestData['method'])){
                    $sql .=' AND  rp.type = "'.$requestData['method'].'" ';
                }
                if(isset($requestData['status_list'])){
                    $sql .=' AND  rp.status = "'.$requestData['status_list'].'" ';
                }

                if (!empty($requestData['search']['value'])) {
                    $sql .= " AND m.name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR m.email LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .= " OR m.identification LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .= " OR m.phone LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .= " OR b.name LIKE '%" . $requestData['search']['value'] . "%' ";
                    if (!empty($_POST['selectType'])) {
                        $sql .= " AND type = '" . $_POST['selectType'] . "' ";
                    }
 
                } else {
                    if (!empty($_POST['selectType'])) {
                        $sql .= " AND type = '" . $_POST['selectType'] . "' ";
                    }  
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
                        $img = ((preg_match('/\bupload\b/', $value['slip']) )? '<center><figure><img src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['slip'] . '&size=30"></figure></center>' : '<div style=" text-align: center; color:grey;" ><i class="fa  fa-image fa-2x"></i></div>'); 
                        $nestedData = array(); 
                        $nestedData[] = $value['id'];
                        $nestedData[] = $img;
                        $nestedData[] = $value['m_name'];
                        $nestedData[] = (($value['type'] == "deposit") ? "เติมเงิน" : "ถอนเงิน");  
                        $nestedData[] = '<span style="float: right;">'.number_format($value['price']).' บาท</span>';
                        $nestedData[] = '<span style="float: right;">'.number_format($value['m_credit']).' บาท</span>';
                        $nestedData[] = (isset($value['b_name']) ? $value['b_name'] : $value['bank_name']);  ; 
                        $nestedData[] = (isset($value['b_number']) ? $value['b_number'] : $value['bank_number']);  
                        $nestedData[] = $value['date_update'];  
                        if($value['status'] == "1"){
                            $orderStatus = 'เสร็จสิ้น'; 
                            $orderStatus_color = '#3ac47d';
                        } else if($value['status'] == "0") {
                            $orderStatus = 'รอตรวจสอบ'; 
                            $orderStatus_color = '#f7b924 ';
                        } else{
                            $orderStatus = 'ผิดพลาด';  
                            $orderStatus_color = '#ff3860'; 
                        }  
                        $nestedData[] = '<div class="blog-member-payments"><span class="member-payments-status" style="background:' . $orderStatus_color . ';">' . $orderStatus . '</span></div>';
                        $nestedData[] = '<div class="blog-member-payments"><span class="member-payments-status" style="background:' . (($value['member_status'] == "active") ? "#3ac47d" : "#ff3860") . ';">' . $value['member_status'] . '</span></div>';
                        // $nestedData[] = '
                        //     <a class="btn kt:btn-primary" style="color:white;" onclick="showMembers(event,' . $value['id'] . ')"><i class="fa fa-eye" aria-hidden="true"></i> ดู</a>
                        //     <a class="btn kt:btn-warning" style="color:white;" onclick="editMembers(event,' . $value['id'] . ')"><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                        //     <a class="btn kt:btn-danger" style="color:white;"  onclick="delMembers(event,' . $value['id'] . ')"><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                        //  '; 

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


            case 'addMembers':

                $sql = "INSERT INTO members
                    (mem_id,type,name,name_2,address,phone,email,email_sub,password,date_create,date_update,status)
                    date_update
                    (null,:type,:name,:name_2,:address,:phone,:email,:email_sub,:password,:create_date,:update_date,:status)
                    ";
                $value = array(
                    ':type' => $_POST['type'],
                    ':name' => $_POST['name'],
                    ':name_2' => $_POST['name_2'],
                    ':address' => $_POST['address'],
                    ':phone' => $_POST['phone'],
                    ':email' => $_POST['email'],
                    ':email_sub' => $_POST['email_sub'],
                    ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                    ':create_date' => date('Y-m-d H:i:s'),
                    ':update_date' => date('Y-m-d H:i:s'),
                    ':status' => $_POST['status'],
                );
                $result = $dbcon->insertValue($sql, $value);
                echo json_encode($result);
                break;

            case 'getMemberById':
                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }

                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $sql = "SELECT  id as id, 
                            name as name,
                            address as address,
                            phone as phone,
                            email as email,
                            password as pass,
                            status as status
                    FROM members 
                    WHERE id = '" . $id . "'";
                $result = $dbcon->fetch($sql);
                echo json_encode([
                    'message' => "OK",
                    'result'  => $result
                ]);
                break;

            case 'editMembers':
                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

                $table = "members";
                $set = "
                    name = :name,
                    address = :address,
                    phone = :phone,
                    email = :email,                    
                    status   = :status
            ";
                $where = " mem_id = :id ";
                $value = array(
                    ':name' => $_POST['name'],
                    ':address' => $_POST['address'],
                    ':phone' => $_POST['phone'],
                    ':email' => $_POST['email'],                    
                    // ':password' => password_hash($_POST['password'],PASSWORD_BCRYPT),
                    ':status' => $_POST['status'],
                    ':id' => $id
                );

                $result = $dbcon->update_prepare($table, $set, $where, $value);
                echo json_encode($result);
                break;

            case 'deleteMembers':
                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $table = "members";
                $where = "mem_id = '" . $id . "' ";
                $result = $dbcon->delete($table, $where);
                echo json_encode($result);
                break;

            case 'editMemberPasswordNew':

                $id = isset($_POST['id']) ? $_POST['id'] : null;
                $password = isset($_POST['password']) ? $_POST['password'] : null;

                if (empty($id) || empty($password)) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'data_empty'
                    ]);
                    exit();
                }

                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }

                $table = "members";
                $set = "password =:password , date_update =:update";
                $where = "mem_id =:id";
                $value = array(
                    ':id' => $id,
                    ':password' => password_hash($password, PASSWORD_BCRYPT),
                    ':update' => date('Y-m-d H:i:s')
                );
                $result = $dbcon->update_prepare($table, $set, $where, $value);
                echo json_encode($result);
                break;

            case 'checkEmail':

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    echo json_encode([
                        'message' => 'email_invalid'
                    ]);
                    exit();
                }
                $sql = "SELECT email FROM members WHERE email =:email LIMIT 1";
                $value = array(
                    ':email' => $_POST['email']
                );
                $result = $dbcon->fetchObject($sql, $value);
                if (!empty($result)) {
                    echo json_encode([
                        'message' => 'email_used'
                    ]);
                    exit();
                }
                echo json_encode([
                    'message' => 'OK',
                ]);
                exit();

                break;
        }
    }
