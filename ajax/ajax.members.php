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

            case 'get_member':

                $requestData = $_REQUEST;
                $columns = array(
                    0 => 'id',
                    1 => 'name',
                    3 => 'credit',
                    4 => 'date_update',
                    5 => 'status',
                );

                $sql = "SELECT * FROM members"; 
                if (!empty($requestData['search']['value'])) {
                    $sql .= " WHERE name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR identification LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR phone LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
                } else {
                    if (!empty($_POST['selectType'])) {
                        $sql .= " WHERE type = '" . $_POST['selectType'] . "' ";
                    }
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
                    foreach ($result as $value) { 
                        $nestedData = array();
                        $balance = ($value['credit_register_fst'] == "no")? '<span style="float: right; color:red;">[ชั่วคราว] '.number_format($value['credit_temp']).' บาท</span>':'<span style="float: right;">'.number_format($value['credit']).' บาท</span>';
                        $nestedData[] = $value['id'];
                        $nestedData[] = $value['name'];
                        $nestedData[] = $value['phone']; 
                        $nestedData[] = $balance;
                        $nestedData[] = date("d-m-Y H:i:s",strtotime($value['date_update']));
                        $nestedData[] = '<div style="display: flex;  justify-content: center;"><span class="'.$value['status'].'-color" style="box-shadow:0 2px 2px 0 rgba(0,0,0,0.2); padding: 3px 8px;color: white;border-radius: 10px;">' . $value['status'] . '</span></div>';
                        $nestedData[] = '
                            <a class="btn kt:btn-primary" style="color:white;" onclick="showMembers(event,' . $value['id'] . ')"><i class="fa fa-eye" aria-hidden="true"></i> ดู</a>
                            <a class="btn kt:btn-warning" style="color:white;" onclick="editMembers(event,' . $value['id'] . ')"><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                            <a class="btn kt:btn-danger" style="color:white;"  onclick="delMembers(event,' . $value['id'] . ')"><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
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
                    ':status' => $_POST['status']  
                ); 
                $result = $dbcon->insertValue($sql, $value); 
                echo json_encode($result); 
                break; 
            case 'getMemberById': 
                if(!is_numeric($_POST['id'])) {  
                    echo json_encode([  
                        'message' => 'error',   
                        'detail'  => 'invalid_number'  
                    ]); 
                    exit(); 
                } 
  
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $sql = "SELECT m.id as id , 
                            m.mem_id as mem_id,
                           m.name as name,
                           m.address as address,
                           m.phone as phone,
                           m.username as username,
                           m.password as pass,
                           m.status as status,
                           p.province_name as province,
                           m.credit as credit,
                           m.credit_temp as credit_temp,
                           m.province_id as province_id, 
                           m.star_red as red_star,
                           m.star_yellow as yellow_star,
                           m.identification as identification,
                           (SELECT log_year FROM members_log WHERE log_status = 'used'  AND log_member_id =  m.mem_id) as years,
                           (SELECT log_activate_date FROM members_log WHERE log_status = 'used'  AND log_member_id =  m.mem_id) as activate_date,
                           (SELECT log_expire_date FROM members_log WHERE log_status = 'used'  AND log_member_id =  m.mem_id) as expire_date  
                    FROM members  as m
                    INNER JOIN province as  p ON p.id = m.province_id  
                    WHERE m.id = '".$id."'     ";
                $result = $dbcon->fetch($sql);    
                $result['activate_date'] = date("d-m-Y H:i:s",strtotime($result['activate_date'])); 
                $result['expire_date'] = date("d-m-Y H:i:s",strtotime($result['expire_date'])); 
              
                echo json_encode([
                    'message' => "OK",
                    'result'  => $result
                ]);
                break;

            case 'editMembers':
                if (!isset($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }
                $id = filter_var($_POST['id'], FILTER_SANITIZE_MAGIC_QUOTES);

                $table = "members";
                $set = "
                    name = :name,
                    province_id = :address,
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
                $sql="SELECT mem_id FROM members WHERE id = ".$id."  ";
                $result = $dbcon->query($sql);

                $mem_id = $result[0]['mem_id'];
           
                $table = "members";
                $where = "mem_id = '" .  $mem_id  . "' ";
                $result = $dbcon->delete($table, $where);

                $table = "members_log";
                $where = "mem_id = '" .  $mem_id  . "' ";
                $result = $dbcon->delete($table, $where);

                $table = "record_paid";
                $where = "mem_id = '" .  $mem_id  . "' ";
                $result = $dbcon->delete($table, $where);

                $table = "product";
                $where = "owner_id = '" .  $mem_id  . "' ";
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
                case'redeem_credit_temporary':
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_MAGIC_QUOTES);
                    $sql="SELECT * FROM members WHERE mem_id =  '".$id."' "; 
                    $res = $dbcon->query($sql);  
                    $res = $res[0];
                    if($res['credit_temp'] == 0){
                        echo json_encode(["message"=>"error"]);
                        exit();
                    }
                    $price = $res['credit_temp'];
                    $table = "members";
                    $set = "credit_temp =:credit_temp , date_update =:update , update_by =:by";
                    $where = "mem_id =:id";
                    $value = array(
                        ':id' => $res['mem_id'],
                        ':credit_temp' => 0,
                        ':update' => date('Y-m-d H:i:s'),
                        'by' => $_SESSION['user_id']
                    );
                    $result_update = $dbcon->update_prepare($table, $set, $where, $value); 
                    
                     $id_transaction = sha1(uniqid(rand(), TRUE));
                     $dec = "ถอนเงินช่ั่วคราวออกจากระบบจำนวน [".$price." บาท]";
                     $sql = "INSERT INTO record_paid(id_transaction,username,mem_id,name,bank_id,date_time,credit,type,status,date_create,date_update,description,update_by)
                     VALUES (:id_transaction,:username,:mem_id,:name,:bank_id,:date_time,:credit,:type,:status,:date_create,:date_update,:desc,:update_by)";
                         $value = [
                           ":id_transaction" => $id_transaction,
                           ":username" => $res['username'],
                           ":mem_id" => $res['mem_id'],
                           ":name" => $res['name'],
                           ":bank_id" => "",
                           ":date_time" => date("Y-m-d H:i:s"),
                           ":credit" => $price,
                           ":type" => 'withdraw',
                           ":status" => 1,
                           ":date_create" => date('Y-m-d H:i:s'),
                           ":date_update" => date('Y-m-d H:i:s'),
                           ":desc" => $dec,
                           ":update_by"=> $_SESSION['user_id'] 
                         ]; 
                     $result_insert = $dbcon->insertValue($sql, $value);  
                    if($result_insert['message'] == "OK"){
                        $res['message'] = "OK";
                        $res['date_time'] = date('d-m-Y H:i:s');
                    } else {
                        $res['message'] = "error"; 
                    }
                    $res['insert_id'] = $result_insert['insert_id'];
                    echo json_encode($res);
                break; 
                case'redeem_credit_temporary_update': 
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_MAGIC_QUOTES);  
                    $name = $_POST['param'][0];
                    $bank_name = $_POST['param'][1];
                    $bank_number = $_POST['param'][2];
                    $date = $_POST['param'][3];

                    $time = strtotime($date); 
                    $datetime = date('Y-m-d H:i:s',$time);
                  
                    $table = "record_paid";
                    $set = "name =:name,bank_name =:bank_name,bank_number =:bank_number , date_time =:date , update_by =:by";
                    $where = "id =:id";
                    $value = array(
                        ':id' => $id,
                        ':name' => $name,
                        ':bank_name' => $bank_name,
                        ':bank_number' => $bank_number,
                        ':date' => $datetime,
                        'by' => $_SESSION['user_id']
                    );
                    $result = $dbcon->update_prepare($table, $set, $where, $value); 
                    echo json_encode($result);
                break;
        }
    }
