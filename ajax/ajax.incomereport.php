<?php
    session_start();
    require_once dirname(__DIR__) . '/classes/class.protected_web.php';
    ProtectedWeb::methodPostOnly();
    ProtectedWeb::login_only();
  
    require_once dirname(__DIR__) . '/classes/dbquery.php';
    require_once dirname(__DIR__) . '/classes/preheader.php'; 
    $dbcon = new DBconnect(); 
    getData::init();  

    require_once dirname(__DIR__) . '/classes/class.incomereport.php'; 
    $mydata = new incomereport();
 
    if (isset($_REQUEST['action'])) {

        $lang_config = getData::lang_config(); 
        switch ($_REQUEST['action']) { 
            case 'get_incomereport':  
             
                $requestData = $_REQUEST;
                $columns = array( 
                    0 => 'id',
                    1 => 'name',
                    2 => 'price_special',  
                    3 => 'post_paid',  
                    4 => 'buy_paid',  
                    6 => 'date_update'  
                );  

                $setDate = array();
                $setDate = explode("/",$requestData['start_date']); #[0]=>วัน [1]=>เดือน [2]=> ปี
                $startDate = date("Y-m-d", strtotime($setDate[2].$setDate[1].$setDate[0]));  
                if(isset($setDate[2]) && strlen($setDate[2]) > 3){
                    $inputDate['start'] = $startDate;
                }  
                $setDate = array();
                $setDate = explode("/",$requestData['expire_date']); #[0]=>วัน [1]=>เดือน [2]=> ปี
                $expireDate = date("Y-m-d", strtotime($setDate[2].$setDate[1].$setDate[0])); 
                if(isset($setDate[2]) && strlen($setDate[2]) > 3){
                    $inputDate['expire'] = $expireDate;
                    if(!isset($inputDate['start'])){  $inputDate['start'] = $startDate;  }                      
                } else {
                    if(isset($inputDate['start'])){  $inputDate['expire'] = date("Y-m-d");  }
                }  
                #ถ้ามีการค้นหาด้วยวันที่ 
                if(isset($inputDate['start'])){
                    $search_date = "AND (p.date_update  BETWEEN  '".$inputDate["start"]."'  AND  '".$inputDate["expire"]."' ) ";
                } 

                $sql = "SELECT p.* , DATEDIFF(p.auction_time,now()) as time_bid  
                                ,pc.name as pc_name   
                                ,psc.name as psc_name  
                                ,c.url    
                                ,m.star_yellow    
                                ,a.title as status_desc  
                                ,m.name as m_name  
                                ,m.phone as m_phone 
                                ,p.saler_paid as post_paid 
                                ,p.buyer_paid as buy_paid  
                                ,(SELECT bidder_id FROM record_bid WHERE product_id = p.p_id  
                                ORDER BY price_current  LIMIT 0,1 ) as bidder  
                        FROM product as p  
                        INNER JOIN members as m ON m.mem_id = p.owner_id  
                        INNER JOIN product_cate as pc ON pc.id = p.p_cate_id 
                        INNER JOIN product_sub_cate as psc ON psc.id = p.p_sub_cate_id   
                        INNER JOIN category as c ON c.cate_id = 12  
                        INNER JOIN auction_status as a ON  a.id = p.status         
                        WHERE p.id != 0  AND (p.status = 6 OR p.status = 7) ". $search_date ." "; 
 
                if (!empty($requestData['search']['value'])) {
                    $sql .= " AND(  m.name LIKE '%" . $requestData['search']['value'] . "%' "; 
                    $sql .=     " OR m.phone LIKE '%"  . $requestData['search']['value'] . "%' "; 
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
                        // $sql .= " AND type = '" . $_POST['selectType'] . "' ";
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
                        $percent = getData::calc_income($value['price_special']);  
                        $bank_name_owner = ($value['type'] == "deposit")? $value['b_name']: $value['bank_name']." (".$value['name'].")" ;  
                        $method = (($value['type'] == "deposit") ? "เติมเงิน" : "ถอนเงิน"); 
                        $img = ((preg_match('/\bupload\b/', $value['slip']) )? '<center><figure><a target="_blank" href="'.ROOT_URL.$value['slip'].'"><img class="zoom" src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['slip'] . '&size=30"></figure></center></a>' : '<div style=" text-align: center; color:grey;" ><i class="fa  fa-image fa-2x"></i></div>'); 
                        $price = (isset($value['price']))? $value['price']:$value['credit']; 
                        $alert_price = ($value['m_credit'] < $price)?"color:red;":"";
                       
                        $nestedData = array();  
                        $nestedData[] = $value['id'];
                        $nestedData[] = $value['name'];
                        $nestedData[] = "<div style='text-align: center;'>".number_format($value['price_special']).' ('.$percent.')'."</div>";
                        $nestedData[] = number_format($value['post_paid']);
                        $nestedData[] = number_format($value['buy_paid']);
                        $nestedData[] = $value['buy_paid'] +$value['post_paid']; 
                        $nestedData[] =  date("d-m-Y H:i:s", strtotime($value['date_update']));    
                        $nestedData[] = (isset($value['b_number']) ? $value['b_number'] : $value['bank_number']);   
                        $output[] = $nestedData;
                    }  
                }
                

 
                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),  
                    "data" => $output,
                );
                
                if(isset($inputDate['start']) || isset($inputDate['expire'])){
                    #format = "yyyy-mm-dd" 
                    $getpost = array();
                    $getpost['date_start'] = $inputDate["start"];
                    $getpost['date_expire'] = $inputDate["expire"];
                    $reports = $mydata->ajax_calc_income_report($getpost); 
                    $json_data['netpay'] = $reports; 
                }
 
                echo json_encode($json_data);

                break;    
                case'get_incomedeal_report':   
                    $requestData = $_REQUEST; 
                    $setDate = array();
                    $setDate = explode("/",$requestData['start_date']); #[0]=>วัน [1]=>เดือน [2]=> ปี
                    $startDate = date("Y-m-d", strtotime($setDate[2].$setDate[1].$setDate[0]));  
                    if(isset($setDate[2]) && strlen($setDate[2]) > 3){
                        $inputDate['start'] = $startDate;
                    }  
                    $setDate = array();
                    $setDate = explode("/",$requestData['expire_date']); #[0]=>วัน [1]=>เดือน [2]=> ปี
                    $expireDate = date("Y-m-d", strtotime($setDate[2].$setDate[1].$setDate[0])); 
                    if(isset($setDate[2]) && strlen($setDate[2]) > 3){
                        $inputDate['expire'] = $expireDate;
                        if(!isset($inputDate['start'])){  $inputDate['start'] = $startDate;  }                      
                    } else {
                        if(isset($inputDate['start'])){  $inputDate['expire'] = date("Y-m-d");  }
                    }  
                    #ถ้ามีการค้นหาด้วยวันที่ 
                    if(isset($inputDate['start'])){
                        $search_date = "AND (ml.log_activate_date  BETWEEN  '".$inputDate["start"]."'  AND  '".$inputDate["expire"]."' ) ";
                    } 

                    $columns = array( 
                        0 => 'm.id',
                        1 => 'm.name' ,
                        2 => 'ml.log_activate_date'  ,
                        3 => 'ml.log_expire_date'  ,
                        4 => 'ml.log_year'  
                    ); 
              
                    $sql = "   SELECT m.*,ml.*,pv.province_name as address FROM members as m 
                               INNER JOIN members_log as ml ON ml.log_member_id = m.mem_id   
                               INNER JOIN province as pv ON m.province_id = pv.id 
                               WHERE  ml.log_status = 'used' ".$search_date."    
                             ";   
                     if (!empty($requestData['search']['value'])) {
                        $sql .= " AND(  m.name LIKE '%" . $requestData['search']['value'] . "%' "; 
                        $sql .=     " OR m.phone LIKE '%"   . $requestData['search']['value'] . "%' "; 
                        $sql .=     " OR m.username LIKE '%" . $requestData['search']['value'] . "%' "; 
                        $sql .=     " OR pv.province_name LIKE '%" . $requestData['search']['value'] . "%'  )";   
                    }  

                    $sql .= " ORDER BY  " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir']; 
                    $stmt = $dbcon->runQuery($sql);
                    $stmt->execute();
                    $totalData = $stmt->rowCount();
                    $totalFiltered = $totalData; 
                    $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql); 
                    $output = array();  
                    if ($result) {     
                        foreach ($result as $value) {  
                            // $percent = getData::calc_income($value['price_special']);  
                            // $bank_name_owner = ($value['type'] == "deposit")? $value['b_name']: $value['bank_name']." (".$value['name'].")" ;  
                            // $method = (($value['type'] == "deposit") ? "เติมเงิน" : "ถอนเงิน"); 
                            // $img = ((preg_match('/\bupload\b/', $value['slip']) )? '<center><figure><a target="_blank" href="'.ROOT_URL.$value['slip'].'"><img class="zoom" src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['slip'] . '&size=30"></figure></center></a>' : '<div style=" text-align: center; color:grey;" ><i class="fa  fa-image fa-2x"></i></div>'); 
                            // $price = (isset($value['price']))? $value['price']:$value['credit']; 
                            // $alert_price = ($value['m_credit'] < $price)?"color:red;":"";
                            
                            $price = $value['log_activate_paid'] * $value['log_year'];
                            $nestedData = array();  
                            $nestedData[] =  $value['log_member_id'];
                            $nestedData[] =  $value['name']; 
                            $nestedData[] =  "<div style='text-align:center;'>".date("d-m-Y H:i:s", strtotime($value['log_activate_date']))."</div>";
                            $nestedData[] =  "<div style='text-align:center;'>".date("d-m-Y H:i:s", strtotime($value['log_expire_date']))."</div>"; 
                            $nestedData[] =  "<div style='text-align:center;'>ปีที่ ".$value['log_year']."</div>";
                            $nestedData[] =  "<div style='text-align:center;'>". $price."</div>";
                            $nestedData[] =  $value['log_status'];
                            
                            $output[] = $nestedData;
                        }  
                    } 
                    $json_data = array(
                        "draw" => intval($requestData['draw']),
                        "recordsTotal" => intval($totalData),
                        "recordsFiltered" => intval($totalFiltered),  
                        "data" => $output,
                    );

                    if(isset($inputDate['start']) || isset($inputDate['expire'])){
                        #format = "yyyy-mm-dd" 
                        $getpost = array();
                        $getpost['date_start'] = $inputDate["start"];
                        $getpost['date_expire'] = $inputDate["expire"];
                        $reports = $mydata->ajax_calc_income_report($getpost); 
                        $json_data['netpay'] = $reports; 
                    }
                    
                    echo json_encode($json_data);

                break;
                case'get_income_config':    
                    $sql ="SELECT * FROM income_config";
                    $result = $dbcon->query($sql);  
                    $ret = array(
                        "register" => $result[0]['register_paid'],
                        "first_time" => $result[0]['first_time_paid'],
                        "minimum" => $result[0]['minimum_paid'] 
                    );
                    echo json_encode($ret);
                break;
                case'update_config':  
                    $register = $_POST['register'];
                    $first_time = $_POST['first_time'];
                    $minimum = $_POST['minimum'];

                    $table = "income_config";
                    $set = "register_paid = :register , first_time_paid = :first_time, minimum_paid = :minimum, date_update= :date, update_by=:by ";
                    $where = "id != '9999999' ";
                    $value = array(
                        ':register' => $register,
                        ':first_time' => $first_time,
                        ':minimum' => $minimum, 
                        ':date' => date('Y-m-d H:i:s'),
                        ':by' => $_SESSION['user_id']
                    );
                    $result = $dbcon->update_prepare($table, $set, $where, $value); 

                    echo json_encode($result);
                break;


        }  
}
