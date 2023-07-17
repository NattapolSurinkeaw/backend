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
            case 'get_notify_payments': 
                  
                $requestData = $_REQUEST;
                $columns = array( 
                    2 => 'price',
                    3 => 'm_name',  
                    7 => 'date_update',  
                    8 => 'm_credit',  
                ); 
                $sql = "SELECT rp.*,m.name as m_name , b.number as b_number ,m.phone as phone, m.credit as m_credit, b.name as b_name ,m.status as member_status,m.credit_register_fst as credit_first_paid ,m.credit_temp as m_credit_temp 
                        FROM record_paid as rp 
                        INNER JOIN members as m ON m.mem_id = rp.mem_id
                        LEFT JOIN bank_info as b ON b.id = rp.bank_id 
                        
                        ";
                $sql .= " WHERE rp.status = '0'   "; 
                if(isset($requestData['method'])){
                    $sql .=' AND  rp.type = "'.$requestData['method'].'" ';
                }
             
                if (!empty($requestData['search']['value'])) {
                    $sql .= " AND(  m.name LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR m.phone LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR m.username LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR m.identification LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR rp.price = '".$requestData['search']['value']."' ";
                    $sql .=     " OR rp.credit  = '".$requestData['search']['value']."' ";
                    $sql .=     " OR rp.bank_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .=     " OR rp.bank_number LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR b.name LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR b.number LIKE '%" . $requestData['search']['value'] . "%'               )";   
 
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
 
                if($result) {  
                    foreach ($result as $value) {   
                  
                        $bank_name_owner = ($value['type'] == "deposit")? $value['b_name']: $value['bank_name']." (".$value['name'].")" ;  
                        $method = (($value['type'] == "deposit") ? "เติมเงิน" : "ถอนเงิน"); 
                        $img = ((preg_match('/\bupload\b/', $value['slip']) )? '<center><figure><a target="_blank" href="'.ROOT_URL.$value['slip'].'"><img class="zoom" src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['slip'] . '&size=30"></figure></center></a>' : '<div style=" text-align: center; color:grey;" ><i class="fa  fa-image fa-2x"></i></div>'); 
                        $price = (isset($value['price']))? $value['price']:$value['credit']; 
                        $alert_price = ($value['m_credit'] < $price && $value['type'] == "withdraw")?"color:red;":"";
                        $balance = ($value['credit_first_paid'] == 'no')? "[ชั่วคราว] ".number_format($value['m_credit_temp'])  :$value['m_credit'];
                        $nestedData = array(); 
                        // $nestedData[] = $value['id'];
                        $nestedData[] = '<div class="blog-member-payments"><span class="member-payments-status" style="background:' . (($value['type'] == "deposit") ? "#3ac47d" : "#ff3860") . ';">' . $method . '</span></div>';  
                        $nestedData[] = $img; 
                        $nestedData[] = '<span style="float: right;">'.number_format($price).' บาท</span>';
                        $nestedData[] = $value['m_name'];
                        $nestedData[] = $value['phone']; 
                        $nestedData[] = $bank_name_owner; #(isset($value['b_name']) ? $value['b_name'] : $value['bank_name']);    
                        $nestedData[] = (isset($value['b_number']) ? $value['b_number'] : $value['bank_number']);  
                        $nestedData[] =  date("d-m-Y H:i:s", strtotime($value['date_update']));                 
                        $nestedData[] = '<span style="float: right;'.$alert_price.'"><label>'.$balance.' </label> บาท</span>'; 
                        $nestedData[] = '<div class="blog-member-payments"><span class="member-payments-status '.$value['member_status'].'-color" >' . $value['member_status'] . '</span></div>';
                        $nestedData[] = ' <span class="btn-action">
                                            <a class="btn kt:btn-warning" style="color:white;" onclick="approvePayments(event,' . $value['id'] . ')"><i class="fa fa-check"></i> อนุมัติ'.$method.'</a>
                                            <a class="btn kt:btn-danger" style="color:white;"  onclick="declinePayments(event,' . $value['id'] . ')"><i class="fa fa-ban" aria-hidden="true"></i> ปฎิเสธ</a>
                                        </span> ';  
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

            case 'approvePaymentsRequest': 
                
                    if (!is_numeric($_POST['id'])) {
                        echo json_encode([
                            'message' => 'error',
                            'detail'  => 'invalid_number'
                        ]);
                        exit();
                    } 
                    #การกำหนดราคา
                    $miniSql = "SELECT * FROM income_config";
                    $fee_config = $dbcon->query($miniSql);
                    $fee_config = $fee_config[0];
                    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT); 
                    $getSql = "SELECT rp.name as name, rp.bank_name as b_name,rp.bank_number as b_number,rp.credit as b_credit, rp.id,rp.mem_id,rp.price as deposit ,rp.credit as draw ,rp.type,m.credit,m.status as m_status,m.credit_register_fst ,m.credit_temp as m_credit_temp 
                                FROM record_paid as rp INNER JOIN members as m ON m.mem_id = rp.mem_id WHERE rp.id = ? LIMIT 0,1"; 
                    $ret = $dbcon->fetchAll($getSql,[$id]);  
                    $result = $ret[0];    
                    $logSql ="SELECT  * FROM members as m INNER JOIN members_log as ml ON m.mem_id = ml.log_member_id WHERE ml.log_member_id = ? ORDER BY ml.log_activate_date ASC";
                    $log_user = $dbcon->fetchAll($logSql,[$result['mem_id']]);
                    $log_user = $log_user[0];
                    
                    $getpost['result'] = $result;   
                    $getpost['log'] = $log_user;  
                    $getpost['fee_config'] = $fee_config;    
                    if(!empty($result['mem_id'])){  
                        if(isset($result['draw']) && $result['draw'] != "" && $result['draw'] > $result['credit']){
                           #การถอนเงินไม่เพียงพอ
                           $res['status'] == 400; #inactive
                           $desc = "เงินคงเหลือ = ".$result['credit'] .' บาท ( ไม่เพียงพอ )';   
                        } else {     
                            #ตั้งค่า default ตัวแปร
                            $used['status'] = 'inactive'; 
                            $used['first_time_paid'] = 'no'; 
                            $used['credit'] = 0;
                            $used['credit_temp'] = $result['m_credit_temp']; #ถ้ายังมีเงินเก่าอยู่ใน m.credit_temp
                            $used['zone'] = "";
                            $deposit = $result['deposit'] + $used['credit_temp'] + $result['credit'];
                            $desc_credit =""; 
                            if($result['type'] == 'deposit'){   #การฝากเงิน   
                                #การฝากเงิน  
                                #ถ้าเป็นการเติมเงินครั้งแรก 
                                #ทำการหักเงินค่าธรรมเนียมสมาชิก
                                #เช็คยอดเงินขั้นต่ำการเติมครั้งแรก   
                                #ถ้าสถานะผู้ใช้งาน = inactive และยังไม่ได้ชำระเงินครั้งแรก ทำส่วนนี้
                                if($result['credit_register_fst'] == "no"){   #ยังไม่เคยเติมเงินขั้นต่ำครั้งแรก    
                                    if($result['m_status'] == 'inactive' && $result['credit_register_fst'] == "no"){ 
                                        #การเติมเงินครั้งแรก ต้องมีจำนวนมากกว่า ค่าธรรมเนียมรายปี + ขั้นต่ำการเติมเงินครั้งแรก 
                                        $required_total = $fee_config['register_paid'] + $fee_config['first_time_paid']; 
                                        if($deposit >= $required_total){   
                                            #ถ้าเงินมากว่าที่ค่าธรรมเนียมรายปี + สมัครครั้งแรกให้ทำการอัพเดทสถานะผู้ใช้งาน แล้วคืนเงินเครดิต หลังหักธรรมเนียมรายปีคืน
                                            $used['zone'] = "A";
                                            $used['credit'] = $deposit - $fee_config['register_paid'];
                                            $used['credit_temp'] = 0;
                                            $used['first_time_paid'] = 'yes'; 
                                            $used['status'] = 'active';  
                                            $desc_credit = number_format($used['credit']);
                                            $renew_date = getData::renew_member_log_date_expire($getpost);
                                   
                                        } else if($deposit >=  $fee_config['register_paid'] && $deposit <  $required_total){  
                                            #ถ้าเงินมากว่าที่ค่าธรรมเนียมรายปี + แต่น้อยกว่าการเติมเงินครั้งแรก หักเงินค่าสมัครสมาชิกแล้วเก็บเงินใส่ credit_temp 
                                            $used['zone'] = "B";
                                            $used['credit_temp'] = $deposit - $fee_config['register_paid']; 
                                            $used['status'] = 'active';  
                                            $renew_date = getData::renew_member_log_date_expire($getpost); 
                                        } else if($deposit < $fee_config['register_paid']){   
                                            #ถ้าการเติมเงินน้อยกว่าค่าธรรมเนียม และ การเติมครั้งแรก เก็บเงินไว้ใน temp
                                            $used['zone'] = "C"; 
                                            $used['credit_temp'] = $deposit; 
                                        } else{  
                                            echo json_encode(["message"=>"error A else section"]);
                                            exit();
                                        }
                                    }           
                                    
                                    #ถ้าสถานะผู้ใช้งาน = active แต่ยังไม่ได้ชำระเงินครั้งแรก ทำส่วนนี้
                                    if($result['m_status'] == 'active' && $result['credit_register_fst'] == "no"){  
                                        if($deposit >= $fee_config['first_time_paid']){ 
                                            $used['zone'] = "D";  
                                            $used['credit'] = $deposit;
                                            $used['credit_temp'] = 0;
                                            $used['first_time_paid'] = 'yes'; 
                                            $used['status'] = 'active';  
                                            $desc_credit = number_format($deposit); 
                                        }else if($deposit < $fee_config['first_time_paid']){ 
                                            $used['zone'] = "E";  
                                            $used['credit_temp'] = $deposit; 
                                            $used['status'] = 'active'; 
                                        }else{
                                            echo json_encode(["message"=>"error D else section"]);
                                            exit();
                                        }
                                    }   
                                    
                                    #ถ้ามีเงินชั่วคราวให้นำมาแสดง
                                    $desc_credit = ($desc_credit == "")?"[เงินชั่วคราว] ".number_format($used['credit_temp']): $desc_credit; 
                                }else{  #เคยเติมเงินขั้นต่ำครั้งแรกแล้ว  
                                    if($result['m_status'] == 'active' && $result['credit_register_fst'] == "yes"){  
                                        #ถ้าสถานะผู้ใช้งาน = active และชำระเงินครั้งแรกแล้วทำการเติมเงิน
                                        $used['zone'] = "F"; 
                                        $deposit = $result['deposit'] + $result['credit'];  
                                        $used['status'] = 'active'; 
                                        $used['first_time_paid'] = 'yes'; 
                                        $used['credit'] = $deposit;   
                                    }else if($result['m_status'] == 'inactive' && $result['credit_register_fst'] == "yes"){  
                                        #ถ้าเติมเงินแล้วแต่ user ไม่พร้อมใช้งานให้เช็ค วันหมดอายุ members_log ก่อน   
                                        if($deposit >= $fee_config['register_paid']){
                                            #จ่ายเงินเพื่อต่ออายุ 
                                            $used['zone'] = "G"; 
                                            $used['credit'] = $deposit - $fee_config['register_paid']; 
                                            $used['first_time_paid'] = 'yes'; 
                                            $used['status'] = 'active'; 
                                            $getpost['used'] = $used; 
                                            $renew_date = getData::renew_member_log_date_expire($getpost);
                                            if($renew_date['message'] != "OK"){
                                                echo json_encode(["message"=>"error C section"]);
                                                exit();
                                            } 
                                        } else {
                                            #เงินไม่พอสำหรับต่ออายุ เอาเงินไปเก็บไว้ที่ credit_temp
                                            $used['credit_temp'] = $deposit;
                                            $used['first_time_paid'] = 'yes'; 
                                            $used['zone'] = "H";  
                                        } 
                                    } 
                                }  #จบการเติมเงิน  
                               
                            }else{ #การถอนเงิน   
                                if($result['m_status'] == 'active' && $result['credit_register_fst'] == "yes"){  
                                    #ถ้าเป็นการเติมเงิน = price  &&  #ถ้าเป็นการถอนเงิน = credit  
                                    $used['zone'] = "I"; 
                                    $withdraw = $result['credit'] - $result['draw'];
                                    $used['credit'] =  $withdraw;
                                    $used['status'] = "active";
                                    $used['first_time_paid'] = 'yes';   
                                }else{
                                    echo json_encode([
                                             'message' => 'error',
                                             'detail'  => 'withdraw_inactive'
                                         ]);
                                    exit();
                                }  
                            }   
                            $getpost['used'] = $used; 
                            #ทำการอัพเดทข้อมูลส่วนของการฝาก / ถอน  
                            $table = "members";
                            $set = "update_by = :by,
                                    date_update = :date, 
                                    credit =  :total,
                                    credit_register_fst = :credit_register_fst,
                                    status = :status,
                                    credit_temp=:credit_temp
                                ";
                            $where = "mem_id = :mem_id ";
                            $value = array(    
                                    ':mem_id' => $result['mem_id'],
                                    ':date' => date('Y-m-d H:i:s'),
                                    ':by' => $_SESSION['login_user_id'],
                                    ':total' => $used['credit'],
                                    ':credit_register_fst'=> $used['first_time_paid'],
                                    ':status' => $used['status'],
                                    "credit_temp"=>$used['credit_temp'] 
                                );    
                            $res = $dbcon->update_prepare($table, $set, $where, $value);   
                            $desc_credit = ($desc_credit == "")?number_format($used['credit']): $desc_credit; 
                            $desc = ( $res['status'] == 200 )? "เงินคงเหลือ = ".$desc_credit." บาท": ""; 
                            if($_SESSION['role'] == 'superadmin'){   $ret['used'] = $used;  } 
                        }
                    } 
                     
                    
                    #อัพเดทสถานะของตาราง record_paid 
                    $status_id = ( $res['status'] == 200 )? "1" : "2"; 
                    $table = "record_paid";
                    $set = "status = :status,
                            update_by = :by,
                            date_update = :date,
                            description = :desc
                            ";
                    $where = " id = :id ";
                    $value = array(            
                            ':status' => $status_id,
                            ':id' => $id,
                            ':date' => date('Y-m-d H:i:s'),
                            ':by' => $_SESSION['login_user_id'],
                            'desc' => ($desc != "")? $desc : ""
                        );  
                    
                    if($result['type'] == "withdraw"){
                        $set .= ",date_time = :cur_date";
                        $value['cur_date'] = date('Y-m-d H:i:s');
                        $ret['id'] = $result['id']; 
                        $ret['name'] = $result['name'];
                        $ret['b_name'] = $result['b_name'];
                        $ret['b_number'] = $result['b_number'];
                        $ret['b_credit'] = $result['b_credit'];
                        $ret['current_date'] = date("d-m-Y H:i:s");
                    }

                    $res = $dbcon->update_prepare($table, $set, $where, $value);
                    $ret['total'] = ($res['status'] == 200)? ($_SESSION['nav_payments_notify']['numb'] - 1) : "0"; 
                    $ret['type'] = $result['type'];

                    #ถ้าเป็นการถอนส่งข้อมูลนี้ไปด้วย
                


                    echo json_encode($ret); 
            break;
            case'update_record_withdraw': 
                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }
                $date = date('Y-m-d H:i:s',strtotime($_POST['date_time']));
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT); 
                $table = "record_paid";
                $set = " date_time =:date_time, update_by =:by";
                $where = " id = :id ";
                $value = array(         
                    ':id' =>$id,   
                    ':date_time' => $date, 
                    ':by' => $_SESSION['login_user_id']
                ); 
                $result = $dbcon->update_prepare($table, $set, $where, $value);
                echo json_encode($result);
            break;

            case 'declinePaymentsRequest':
                if (!is_numeric($_POST['id'])) {
                    echo json_encode([
                        'message' => 'error',
                        'detail'  => 'invalid_number'
                    ]);
                    exit();
                }
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT); 
                $table = "record_paid";
                $set = " status = :status,
                         update_by = :by,
                         date_update = :date
                      ";
                $where = " id = :id ";
                $value = array(            
                    ':status' => 2,
                    ':id' => $id,
                    ':date' => date('Y-m-d H:i:s'),
                    ':by' => $_SESSION['login_user_id']
                ); 
                $result = $dbcon->update_prepare($table, $set, $where, $value);
                $result['total'] = ($result['status'] == 200)? ($_SESSION['nav_payments_notify']['numb'] - 1) : "0";   


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
                case'check_notification_payments':
                        $sql ="SELECT count(*) as numb FROM record_paid WHERE status = 0 ";
                        $result = $dbcon->query($sql);
                        $ret['total'] = $result[0]['numb']; 
                        echo json_encode($ret);
                break;

             
        }
    }
?>
