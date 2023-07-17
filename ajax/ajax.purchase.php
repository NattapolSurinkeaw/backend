<?php	

session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.purchaseOrderData.php';
$dbcon = new DBconnect();
getData::init(); 
purchaseOrderData::init();


if(isset($_REQUEST['action'])) {
	$lang_config = getData::lang_config(); 
    switch($_REQUEST['action']){

        case'getSelectOderDetails':

            $sql =' SELECT *,GROUP_CONCAT(c1.product_phone SEPARATOR "|") as phone, 
                    GROUP_CONCAT(c1.product_ems SEPARATOR "|") as ems,
                    GROUP_CONCAT(c1.product_id SEPARATOR "|") as pid,
                    GROUP_CONCAT(c1.product_price SEPARATOR "|") as price 
                    FROM order_list as a1 
                    INNER JOIN order_contact as  d1 
                    INNER JOIN berproduct_order as  c1 
                    ON a1.contact_id = d1.contact_id 
                    WHERE a1.status = "publish" AND c1.order_id = a1.order_id AND a1.order_id = ? 
                    GROUP BY c1.order_id DESC LIMIT 0,1';  

            $result = $dbcon->select_prepare($sql,[$_REQUEST['id']]);  
            if(!empty($result)){
                $deliverArr = explode('-',$result[0]['date_deliver']);
                $ret['delivery'] = ''.$deliverArr[1].'/'.$deliverArr[2].'/'.$deliverArr[0].'';
 
                    $ret['name'] = trim($result[0]['contact_firstname']);
                    $ret['lastname'] = trim($result[0]['contact_lastname']);
                    $ret['tel'] = trim($result[0]['contact_phone']);
                    $ret['display'] = trim($result[0]['order_ems']);
                    $ret['type'] = trim($result[0]['send_type']);
                    $ret['email'] = trim($result[0]['contact_email']);
                    $ret['order_id'] = trim($result[0]['order_id']);
                    $ret['contact_id'] = trim($result[0]['contact_id']); 
                    $ret['status'] = 'OK';
                    $ret['address'] =  ''.$result[0]['contact_address'].''.$result[0]['contact_district'].''.$result[0]['contact_subdistrict'].''.$result[0]['contact_province'].''.$result[0]['contact_postcode'].''; 
                    $phone = explode('|',$result[0]['phone']); 
                    $ems = explode('|',$result[0]['ems']);
                    $id = explode('|',$result[0]['pid']);
                    $price = explode('|',$result[0]['price']);
                    $totalPrice = 0;
                    foreach($phone as $keyP => $valP){  
                        if($ems[$keyP] == ''){ $ems[$keyP] = ''; }  
                        $ret['set_id'][] = trim($valP);
                        $totalPrice = $totalPrice + $price[$keyP]; 

                        if($ems[$keyP] != '' ){ $check ='active';   }else{ $check ='inactive';  }
                        $ret['order'] .='	
                            <div class="detail_EMS '.$val['p_stock'].'">  
                                <span class="body-details d-number" data-number="'.$valP.'"><input type="tel" maxlength="10" class="form-control phone_number_txt" data-id="'.$valP.'" value="'.$valP.'" placeholder="0123456789"></span>  
                                <span class="number body-details n-price"><input type="number" class="form-control price_phone" data-id="'.$ret['order_id'].'" data-price="'.number_format($price[$keyP]).'" value="'.number_format($price[$keyP]).'" placeholder="0"></span>  
                                <span class="body-details d-ems" >
                                    <input type="text" class="form-control text_ems '.$check.'" data-id="'.$valP.'" value="'.$ems[$keyP].'">
                                    <i class="fa fa-plus " data-id="'.$id[$keyP].'" ></i>
                                  </span>   
                            </div> ';
                 
                    } 
                    $ret['total'] = number_format($totalPrice);
                
            }else{
                $ret['status'] = 'NO';
            }  
            echo json_encode($ret);
        break; 
        case'delOrder':
            /* before delete turn product number back to not yet sold status */  
            $sql =  'SELECT contact_id,product_list FROM order_list WHERE order_id = ? LIMIT 0,1';
            $res = $dbcon->select_prepare($sql,[$_REQUEST['id']]);
            

            $product = substr($res[0]['product_list'],1,-1); 
            $table = "berproduct"; 
            $set = " product_sold =  :val "; 
            $where = "product_id IN (".$product.") ";
            $value = array(
                    ":val" =>  " "
                    );
            $res['setback'] = $dbcon->update_prepare($table,$set,$where,$value);   

            $table = "order_list";
            $where = "order_id = :order_id";
            $value = [
                ':order_id' => $_REQUEST['id']
            ];
            $ret['list'] = $dbcon->deletePrepare($table, $where, $value);
            $con_id = $res[0]['contact_id'];  

            
            /* end of before */  
            
            $table = "order_contact";
            $where = "contact_id = :contact_id";
            $value = [
                ':contact_id' => $con_id
            ];
            $ret['contact'] = $dbcon->deletePrepare($table, $where, $value);  

            $table = "berproduct_order";
            $where = "order_id = :order_id";
            $value = [
                ':order_id' => $_REQUEST['id']
            ];
            $ret['ber_order'] = $dbcon->deletePrepare($table, $where, $value);  

            $result['msg'] = 'OK';
            if($ret['contact']['status'] != 200){
                $result['msg'] = 'NO';
            }
            if($ret['list']['status'] != 200){
                $result['msg'] = 'NO';
            }
            if($ret['ber_order']['status'] != 200){
                $result['msg'] = 'NO';
            }
          
            echo json_encode($result); 
        break;  
        case'updateEMS': 
            $table = "berproduct_order"; 
            $set = " product_ems = :ems"; 
            $where = "product_phone = :id";
            $value = array(
                     ":id" =>  ProtectWeb::number_int($_REQUEST['id']),
			         ":ems" => ProtectWeb::string($_REQUEST['ems'])  
                    );
            $res = $dbcon->update_prepare($table,$set,$where,$value);  
            $ret['status'] = $res['status'];

            echo json_encode($ret); 
        break;
        case'updateStatusOrder':  
            $table = "order_list"; 
            $set = " order_ems = :display"; 
            $where = "order_id = :id";
            $value = array(
                        ":id" =>  ProtectWeb::number_int($_REQUEST['id']),
                        ":display" => ProtectWeb::string($_REQUEST['display']) 
                    );
            $res = $dbcon->update_prepare($table,$set,$where,$value);  

            $table = "berproduct_order"; 
            $set = " send_type = :send_type"; 
            $where = "order_id = :id";
            $value = array(
                        ":id" =>  ProtectWeb::number_int($_REQUEST['id']), 
                        ":send_type" => ProtectWeb::string($_REQUEST['send'])  
                    );
            $res2 = $dbcon->update_prepare($table,$set,$where,$value); 

            $ret['status'] = $res['status'];
 
            echo json_encode($ret); 
                
        break;
        case'updateDateDeliver':
            $dateArr = explode('/',$_REQUEST['date']);
            $date = ''.$dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1].'';
            $table = "order_list"; 
            $set = " date_deliver  = :date"; 
            $where = "order_id = :id";
            $value = array(
                        ":id" =>  ProtectWeb::number_int($_REQUEST['id']),
                        ":date" => ProtectWeb::string($date)  
                    );
            $res = $dbcon->update_prepare($table,$set,$where,$value);   
            $ret['status'] = $res['status']; 
            echo json_encode($ret);

        break;
        case'updateEMSTOken':
              
             $table = "emstokenkey"; 
             $set = " token_key  = :key"; 
             $where = "token_id = :id";
             $value = array(
                         ":id" =>  '0',
                         ":key" => ProtectWeb::string($_REQUEST['key'])  
                     );
             $res = $dbcon->update_prepare($table,$set,$where,$value); 
             $ret['status'] = $res['status'];
             $ret['key'] = $_REQUEST['key'];
             echo json_encode($ret);

        break;
        case'updateApiGetitem':  
            // $test'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJzZWN1cmUtYXBpIiwiYXVkIjoic2VjdXJlLWFwcCIsInN1YiI6IkF1dGhvcml6YXRpb24iLCJleHAiOjE1NzQ2Nzk3MTgsInJvbCI6WyJST0xFX1VTRVIiXSwiZCpzaWciOnsicCI6InpXNzB4IiwicyI6bnVsbCwidSI6ImM5MWFkMTFmMzFhZGI4MWU4ZTdlNjJlY2YxOTg5ODA5IiwiZiI6InhzeiM5In19.GWmbGv_MJYD3kMNe3j9nxNyQHqeja5JEH5AVAqYiv49MrXdvWqkn8BSJ2fzb19V5JexMIOozMwknHAQ1isurWw';
            $table = "emstokenkey"; 
            $set = " sub_token  = :key"; 
            $where = "token_id = :id";
            $value = array(
                        ":id" =>  '0',
                        ":key" => ProtectWeb::string($_REQUEST['token'])  
                    );
            $res = $dbcon->update_prepare($table,$set,$where,$value);  
            $ret['status'] = $res['status']; 
            print_r($res);
             echo json_encode($ret);
        break;
        case'switchSendType':
            $table = "berproduct_order"; 
            $set = " send_type  = :type"; 
            $where = "order_id = :id";
            $value = array(
                        ":type" => ProtectWeb::string($_REQUEST['type']),  
                        ":id" => ProtectWeb::string($_REQUEST['id'])  
                    );
             $res = $dbcon->update_prepare($table,$set,$where,$value);  
             $ret['status'] = $res['status']; 

             echo json_encode($ret);
        break;
        #edit purchase section --------------------------------------------
        case'updateproduct_edit':
            $_REQUEST['id'] = trim($_REQUEST['id']);
            $_REQUEST['val'] = trim($_REQUEST['val']);

            if($_REQUEST['section'] == 'contact'){  
                if($_REQUEST['set'] == 'name'){
                    $set = " contact_firstname = :val "; 
                    
                }else if($_REQUEST['set'] == 'lastname'){
                    $set = " contact_lastname = :val ";

                }else if($_REQUEST['set'] == 'email'){
                    $set = " contact_email = :val ";

                }else if($_REQUEST['set'] == 'tel'){
                    $set = " contact_phone = :val ";

                }else if($_REQUEST['set'] == 'address'){
                    $set = " contact_address = :val ";
                } 
                $table = "order_contact"; 
                $where = "contact_id = :id";  

            }else if($_REQUEST['section'] == 'product'){
                $table = "berproduct_order"; 

                if($_REQUEST['set'] == 'phone'){
                    $set = " product_phone = :val "; 
                    $where = " product_phone = :id "; 
                }

                if($_REQUEST['set'] == 'price'){
                    $set = " product_price = :val "; 
                    $where = " order_id = :id AND product_phone = :phone"; 

                    $value = array(
                        ":id" =>  ProtectWeb::number_int($_REQUEST['id']), 
                        ":val" => ProtectWeb::string($_REQUEST['val']),  
                        ":phone" => ProtectWeb::string($_REQUEST['phone'])  
                       );
                }

            } 
            if(empty($value)){
                $value = array(
                    ":id" =>  ProtectWeb::number_int($_REQUEST['id']),
                    ":val" => ProtectWeb::string($_REQUEST['val'])  
                   );
            }


            $res = $dbcon->update_prepare($table,$set,$where,$value);  
            $ret['status'] = $res;
            $ret['section'] =  $_REQUEST['section'];
            $ret['set'] =  $_REQUEST['set'];
            if($ret['set']){

            }

            echo json_encode($ret); 
        break;
        case'orderListTable':
           
            $dateArr = explode('/',$_REQUEST['date']); 
            $requestData = $_REQUEST;
            #ส่วนของการ order ข้อมูลจากตาราง
            $columns = array(
                0 => 'list.order_id',
                1 => 'con.contact_firstname',  
                3 => 'ord.date_order',  
                4 =>'list.date_order'  
            ); 
            $status = FILTER_VAR($_POST['status'],FILTER_SANITIZE_STRING);
            if($status != "pending"){
                $status = "publish";
            }
            $sql =" SELECT  GROUP_CONCAT(list.product_phone) as list
                            ,ord.order_id as order_id 
                           ,ord.id as code 
                           ,con.contact_firstname as name
                           ,con.contact_tel as tel
                           ,ord.status as status
                           ,ord.date_order as date 
                    FROM berproduct_order_list as ord
                    INNER JOIN berproduct_contact as con ON ord.contact_id = con.contact_id   
                    INNER JOIN berproduct_manage as list ON ord.order_id = list.order_id 
                    WHERE ord.status = '".$status."' ";
            #กรองการค้นหาข้อมูลจากตาราง
            $requestData['search']['value'] = trim($requestData['search']['value']);
            if (!empty($requestData['search']['value'])) { 
                    $sql .= "AND ( con.contact_firstname LIKE '" . $requestData['search']['value'] . "%' ";	
                    $sql .= " OR con.contact_email LIKE '" . $requestData['search']['value'] . "%' ";	
                    $sql .= " OR con.contact_tel LIKE '" . $requestData['search']['value'] . "%' "; 
                    $sql .= " OR ord.id LIKE '" . $requestData['search']['value'] . "%' ";	
                    $sql .= " OR ord.order_received LIKE '" . $requestData['search']['value'] . "%' ";	
                    $sql .= " OR list.product_phone LIKE '" . $requestData['search']['value'] . "%' )";
            }
            #กรองข้อมูลส่วนของ วันที่
            if($_POST['date'] != ''){   
                if(!empty($dateArr[2])){
                    $date = ''.$dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0].'';
                    $sql .=' AND ord.date_order LIKE "%'.$date.'%"  ';
                }  
            } 
            $sql .=' GROUP BY ord.order_id DESC ';
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " ";
            $result = $dbcon->query($sql);  
  
 
            $output = array(); 
            if (!empty($result)) {
                foreach ($result as $value) {  
                     
                    if($value['status'] == 'publish'){
                        $status = 'สำเร็จ';
                        $colors ='green';
                    }else{
                        $status = 'รอดำเนินการ';
                        $colors ='red';   
                    }
                    $date = purchaseOrderData::format_thai_date($value['date']);
                    $nestedData = array();						
                    $nestedData[] = $value['code'];
                    $nestedData[] = '<div class="details" data-id="'.$value['order_id'].'">'.$value['name'].'</div>';
                    $nestedData[] = '<div >'.$value['list'].'</div>';   
                    $nestedData[] = '<div class="text-center">'.$date.'</div>';  
                    $nestedData[] = '<div class="text-center" style="color:'.$colors.';">'.$status.'</div>';
                    $nestedData[] = '<div class="text-center btn-search"> <i class="fa fa-search fa-lg details_seach"  onclick="editPurchaseOrder('.$value['order_id'].')"></i> </div>'; 
                    $action = '<div class="box-tools tdChild  text-center" >
                                    <div class="btn-group"> 
                                    <button type="button"  onclick="delPurchaseOrder('.$value['order_id'].')"  class="btn btn-sm  ">
                                        <i class="fa fa-trash"></i>
                                    </button>';	
                    $nestedData[] = $action . ' </div> </div>';
                    $output[] = $nestedData;
                }
            }   
            $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),  
                    "data" => $output
            );
            echo json_encode($json_data); 
            
        break;
        
        case'get_purchase_order_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql =" SELECT  ord.order_id as order_id 
                            ,ord.id as code  
                            ,ord.status as status
                            ,ord.date_order as date 
                            ,ord.order_carrier as carrier 
                            ,ord.order_received as tracking 
                            ,con.contact_id as c_id
                            ,con.contact_firstname as c_name
                            ,con.contact_email as c_email
                            ,con.contact_tel as c_tel
                            ,con.contact_address as c_address
                            ,con.contact_district as c_district
                            ,con.contact_subdistrict as c_subdistrict
                            ,con.contact_province as c_province
                            ,con.contact_zipcode as c_zipcode
                            ,list.id as p_id 
                            ,list.product_phone as p_phone 
                            ,list.product_discount as p_discount
                            ,list.discount_desc as p_desc
                            ,list.product_network as p_network 
                            ,list.product_price as p_price 
                            ,list.status as p_stock 
                    FROM berproduct_order_list as ord
                    INNER JOIN berproduct_contact as con ON ord.contact_id = con.contact_id   
                    INNER JOIN berproduct_manage as list ON ord.order_id = list.order_id  
                    WHERE ord.order_id = :id";
            $result = $dbcon->fetchAll($sql,[":id" => $id]);
            if(!empty($result)){
                $order = array();
                $order['total'] = 0;
                $resultBer ="";
                foreach($result as $key => $val){
                    #เช็คข้อมูลว่าเบอร์นี้ขายถูกขายแล้วหรือไม่
                    #ถ้าถูกขายแล้วจะถูกแสดงสถานะเป็น empty กรอบสีแดง
                    $val['p_stock'] = purchaseOrderData::check_ber_soldout($val['order_id'],$val['p_phone'],$val['p_stock']);
                    $original_price =  $val['p_price'];
                    $discount = ($val['p_discount'] > 0)? ($val['p_price']*$val['p_discount']/100):0;  
                    $val['p_price'] = round($val['p_price'] - $discount);
                    #เซ็ตข้อมูลนำไปแสดง
                    $order['id'] = $val['order_id'];
                    $order['code'] = $val['code'];
                    $order['c_id'] = $val['c_id'];
                    $order['c_name'] = $val['c_name'];
                    $order['c_address'] = $val['c_address'];
                    $order['c_district'] = $val['c_district'];
                    $order['c_subdistrict'] = $val['c_subdistrict'];
                    $order['c_province'] = $val['c_province'];
                    $order['c_zipcode'] = $val['c_zipcode'];
                    $order['c_tel'] = $val['c_tel'];
                    $order['c_email'] = $val['c_email'];
                    $order['status'] = $val['status'];
                    $order['date'] = $val['date']; 
                    $order['carrier'] = $val['carrier'];
                    $order['tracking'] = $val['tracking'];
                    $order['list'][$val['p_id']]['id'] = $val['p_id'];
                    $order['list'][$val['p_id']]['phone'] = $val['p_phone'];
                    $order['list'][$val['p_id']]['network'] = $val['p_network']; 
                    $order['list'][$val['p_id']]['stock'] = $val['p_stock'];  
                    $order['list'][$val['p_id']]['discount'] = $val['p_discount']; 
                    $order['list'][$val['p_id']]['price'] = $val['p_price'];
                    #ถ้ากรณีที่สินค้ามีส่วนลด 
                    $txt_discount = ($val['p_discount'] > 0)?'<span class="discount" title="ส่วนลด: '.number_format($original_price).' - '.number_format(round($discount)).' = '.number_format($val['p_price']).'฿" > <input type="number" min="0" max="100" data-id="'.$val['p_id'].'" value="'.$val['p_discount'].'" ><i>%</i> </span>':"";
                    #ถ้าสินค้าถูกขายไปแล้วจะไม่นำไปคำนวณราคารวม
                    $order['total'] += ($val['p_stock'] =="empty")?0:$val['p_price']; 
                    #ถ้าสินค้าถูกขายไปแล้วให้แจ้งเตือนว่าไม่มีสินค้านี้แล้ว
                    $delete_icon = ($val['p_stock'] == "empty")?'<i class="fas fa-times fa-lg btn-del-soldout" title="ลบหมายเลขนี้ออกจากรายการ" onclick="deleteProductSold('.$val['p_id'].')"></i>':"";
                    $soldout = ($val['p_stock'] == "empty")?"สินค้าหมด":$val['p_price'];
                    $resultBer.= '<div class="detail_EMS '.$val['p_stock'].'" data-id="'.$val['p_id'].'"> 
                                    '.$delete_icon.'
                                    <span class="number body-details">
                                        <input type="text" class="form-control text-center txt_network" data-name="network" data-id="'.$val['p_id'].'"  value="'.$val['p_network'].'" placeholder="เครือข่าย"></span>  
                                    <span class="body-details d-number" data-number="'.$val['p_phone'].'">
                                        <input type="tel" maxlength="10" class="form-control text-center txt_phone" data-name="phone" data-id="'.$val['p_id'].'"  value="'.$val['p_phone'].'" placeholder="0123456789">
                                    </span>   
                                    <span class="number body-details"> 
                                        '.$txt_discount.'
                                        <input type="text" class="form-control txt_price" data-name="price" data-price="'.$original_price.'" data-id="'.$val['p_id'].'" title="ราคาเดิม '.number_format($val['p_desc']).'฿ หักจากส่วนลดแล้ว (กรณีที่มีส่วนลด)" value="'.$soldout.'" placeholder="0"></span>  
                                    </span>  
                                </div> 	';
                }
                #สถานะการขนส่ง
                if($order['carrier'] == "ems"){ 
                    $ems = "yes"; 
                    $emsCheck = "checked";
                }else if(($order['carrier'] == "kerry")){ 
                    $kerry = "yes"; 
                    $kerryCheck = "checked"; 
                }else { 
                    $kerry = "no"; 
                    $ems = "no"; 
                    $emsCheck = ""; 
                    $kerryCheck = ""; 
                }
                #สถานะของ order
                $status = ($order['status'] == "publish")?"ts-active":""; 
                #ฟอร์มแสดงข้อมูล
                $subdistrict = ($order['c_subdistrict'] != "")? $order['c_subdistrict']:"กรอกรหัสไปรษณีย์";

                $svCharge = getData::getServiceCharge($order['total']);
                $order['total'] = $svCharge+$order['total'];
                $serviceHTML = ($svCharge==0)?"":'<div style="color: #f7708b;font-size: 1.3em;text-align: left;border: 1px solid #d6dae1;font-weight: bold;margin: 3px 0px;padding: 6px;">มีค่าบริการจัดส่ง '.$svCharge.' บาท</div>';
                $html = '<div class="purchase-head"><p>เลขที่รายการ '.$order['id'].'</p></div>
                            <div class="purchase-blog"> 
                                <div class="blog-title"><p>ข้อมูลผู้ซื้อ</p></div>
                                <div class="purchase-blog-contact">
                                    <div class="grid-columns-2fr">  
                                        <p class="title">ชื่อ: </p>
                                        <span class="p-name">
                                            <input type="text" class="form-control txt_name" placeholder="berhoro" value="'.$order['c_name'].'"> 
                                        </span>
                                    </div>  
                                    <div class="grid-columns-2fr">
                                        <p class="title">อีเมล: </p> 
                                        <span class="p-email"><input type="text" class="form-control txt_email" placeholder="berhoro@gmail.com" value="'.$order['c_email'].'"></span>
                                    </div>  
                                    <div class="grid-columns-2fr">
                                        <p class="title">เบอร์โทร: </p> 
                                        <span class="p-tel"><input type="tel" class="form-control txt_tel" maxlength="10" placeholder="09123456789" value="'.$order['c_tel'].'"></span>
                                    </div> 
                                    <div class="grid-columns-2fr">
                                        <p class="title">ที่อยู่: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_address" placeholder="123/456 ซอย. หมู่." value="'.$order['c_address'].'"></span>
                                    </div>
                                    <div class="grid-columns-2fr">       
                                        <p class="title">ไปรษณีย์: </p> 
                                        <span class="p-address"><input type="tel" maxlength="5" class="form-control txt_zipcode" placeholder="10000" value="'.$order['c_zipcode'].'"></span>
                                     </div>
                                    <div class="grid-columns-2fr">       
                                        <p class="title">ตำบล/แขวง: </p> 
                                        <span class="p-address">
                                         <select class="form-control" id="slc_subdistrict"> <option class="default" value="'.$order['c_subdistrict'].'" disabled selected>'.$subdistrict.'</option></select>
                                        </span>
                                    </div>
                                    <div class="grid-columns-2fr">     
                                        <p class="title">อำเภอ/เขต: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_district" placeholder="กรอกรหัสไปรษณีย์"  value="'.$order['c_district'].'"></span>
                                     </div>
                                   
                                    <div class="grid-columns-2fr">     
                                        <p class="title">จังหวัด: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_province" placeholder="กรอกรหัสไปรษณีย์" value="'.$order['c_province'].'"></span>
                                     </div>
                                    
                                </div> 
                                <div class="blog-title"><p>ข้อมูลสินค้า [ '.$order['code'].' ]</p></div>
                                <div class="purchase-blog-order"> 
                                    <div class="head-order"> 
                                        <div class="detail_phone"> 
                                            <div class="body-title">เครือข่าย</div>
                                            <div class="body-title" style="text-align: center;">หมายเลข</div>
                                            <div class="body-title" style="text-align: center;">ราคา</div>
                                        </div>         
                                    </div> 
                                    <div class="body-order"> 
                                        <div class="dataShowORDER"> 
                                           '.$resultBer.'
                                        </div> 
                                    </div>  
                                </div>
                                '.$serviceHTML.'
                   
                                <div class="body-datedeliver"> 
                                    <div class="total-price">
                                        <span class="total-price-box">
                                            <span>รวม</span>  
                                            <span class="total-order-price">'.number_format($order['total']).'</span>
                                            <span> บาท</span> 
                                        </span> 
                                    </div>
                                    <div class="input-group date_deliver"> 
                                        <div class="switch-form add-category">
                                            <div class="col-md-12 switch-btn btnSwitchDelivery">
                                                <span class="switch-title">สถานะจัดส่ง: </span>
                                                <div class="toggle-switch '.$status.'" data-toggle="tooltip"  title="ปรับสถานะเป็น success เมื่อส่งมอบสินค้าแล้ว!" >
                                                <span class="switch"></span>
                                                </div>
                                                <input type="hidden" class="form-control" id="product_delivery" value="'.$order['status'].'">
                                            </div>  
                                        </div>  
                                    </div> 
                                </div>
                                <div class="purchase-blog-contact tracking">
                                    <div class="grid-columns-2fr">   
                                        <p class="title">Tracking: </p>
                                        <span class="p-name">
                                            <input type="text" class="form-control txt_tracking" placeholder="คำอธิบายการส่งสินค้า EMS-KERRY" value="'.$order['tracking'].'"> 
                                        </span>
                                    </div>
                                    <div class="check-box-trans ems">
                                        <label class="container">จัดส่งแบบ EMS  
                                            <input type="checkbox" id="emsService" '.$emsCheck.' class="squared-ems" value="'.$ems.'">
                                            <span class="checkmark ems"></span>
                                        </label>
                                    </div> 
                                    <div class="check-box-trans kerry">
                                        <label class="container">จัดส่งแบบ KERRY  
                                            <input type="checkbox" id="kerryService" '.$kerryCheck.' class="squared-kerry" value="'.$kerry.'">
                                            <span class="checkmark kerry"></span>
                                        </label>
                                    </div> 
                                </div>

                            </div>
                            ';
            } 
            $json_data['order'] = $order;
            $json_data['html'] = $html;
            echo json_encode($json_data);
        break;
        case'del_purchase_order_soldout':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berproduct_manage";
            $where = "id = :id AND (status = 'empty' OR status = 'instock')";
            $value = [ 
                ':id' => $id
            ];
            $result = $dbcon->deletePrepare($table, $where, $value); 
            echo json_encode($result);
        break;
        case'delete_purchase_order_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT order_id,contact_id FROM berproduct_order_list WHERE order_id = :id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            
            $table = "berproduct_contact";
            $where = "contact_id = :id";
            $value = [ 
                ':id' => $result->contact_id  
            ];
            $ret['contact'] = $dbcon->deletePrepare($table, $where, $value);

            $table = "berproduct_manage";
            $where = "order_id = :id";
            $value = [ 
                ':id' => $result->order_id  
            ];
            $ret['manage'] = $dbcon->deletePrepare($table, $where, $value);

            $table = "berproduct_order_list";
            $where = "order_id = :id";
            $value = [ 
                ':id' => $result->order_id  
            ];
            $ret['order'] = $dbcon->deletePrepare($table, $where, $value);

            foreach($ret as $val){
                if($val['message'] != "OK"){
                    $response['message'] = "ERROR";
                    break;
                }else{
                    $response['message'] = "OK";
                }
            }
            $response['total'] = getData::get_notification_purchase(); 
            echo json_encode($response);
        break;
        case'update_purchase_order_by_id':
 
            $order_id = FILTER_VAR($_POST['order_id'],FILTER_SANITIZE_NUMBER_INT);
            $con_id = FILTER_VAR($_POST['con_id'],FILTER_SANITIZE_NUMBER_INT);
            $tracking = FILTER_VAR($_POST['tracking'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $carrier = FILTER_VAR($_POST['carrier'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $status = FILTER_VAR($_POST['status'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $netpay = FILTER_VAR($_POST['price'],FILTER_SANITIZE_NUMBER_FLOAT);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $email = FILTER_VAR($_POST['email'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $address = FILTER_VAR($_POST['address'],FILTER_SANITIZE_MAGIC_QUOTES);
            $district = FILTER_VAR($_POST['district'],FILTER_SANITIZE_MAGIC_QUOTES);
            $subdistrict = FILTER_VAR($_POST['subdistrict'],FILTER_SANITIZE_MAGIC_QUOTES);
            $province = FILTER_VAR($_POST['province'],FILTER_SANITIZE_MAGIC_QUOTES);
            $zipcode = FILTER_VAR($_POST['zipcode'],FILTER_SANITIZE_MAGIC_QUOTES);
            $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
            $list = $_POST['list'];
            $product = $_POST['arr'];

            
            $svCharge = getData::getServiceCharge($netpay); 
         

            if(!empty($product)){
                $new_arr = array();
                foreach($product as $val){
                    $new_arr[$val['id']][$val['name']] = strtoupper($val['value']);
                }
            }
      
            if(!empty($new_arr) && count($new_arr) > 0){
                $loop = count($new_arr);
                $table = "berproduct_manage"; 
                foreach($new_arr as $key => $val){
                    $ber_status = ($status == "publish")?"soldout":"instock";
                    #เช็คข้อมูลว่าเบอร์นี้ขายถูกขายแล้วหรือไม่
                    #ถ้าถูกขายแล้วจะถูกแสดงสถานะเป็น empty กรอบสีแดง
                    $ber_status = purchaseOrderData::check_ber_soldout($order_id,$val['phone'],$ber_status);
                    $set = " product_phone = :product_phone, product_network = :product_network , product_price = :product_price,product_discount = :product_discount, status = :status"; 
                    $where = "order_id = :id AND product_phone = :phone";
                    $value = array(
                        ":id" => $order_id,
                        ":phone" => $val['phone'],
                        ":product_phone" => $val['phone'],
                        ":product_network" => $val['network'],
                        ":product_price" => $val['price'],
                        ":product_discount" => 0,
                        ":status" => $ber_status 
                    );
                    $ret['manage'] = $dbcon->update_prepare($table,$set,$where,$value); 
                }
            }

            if($status == "publish"){
                #ลบเบอร์ที่ไม่มีสินค้าออก
                $table = "berproduct_manage";
                $where = "order_id = :id AND status = :status";
                $value = [ 
                    ':id' => $order_id ,
                    ':status' => "empty" 
                ];
                $ret['del'] = $dbcon->deletePrepare($table, $where, $value); 

                #ปรับสถานะสินค้า 
                $products = purchaseOrderData::update_product_status_by_orderid($order_id);
                $ret['display'] = $products;
            }

            $table = "berproduct_contact"; 
            $set = "contact_firstname=:name 
                    ,contact_tel=:tel 
                    ,contact_email=:email 
                    ,contact_address=:contact_address
                    ,contact_district=:contact_district
                    ,contact_subdistrict=:contact_subdistrict
                    ,contact_province=:contact_province
                    ,contact_zipcode=:contact_zipcode"; 
            $where = "contact_id = :id";
            $value = array(
                ":id" => $con_id
                ,
                ":name" => $name,
                ":tel" => $tel,
                ":email" => $email,
                ":contact_address" => $address,
                ":contact_district" => $district,
                ":contact_subdistrict" => $subdistrict,
                ":contact_province" => $province,
                ":contact_zipcode" => $zipcode 
            );
            $ret['contact'] = $dbcon->update_prepare($table,$set,$where,$value); 

            $tracking = ($status == "publish" && $tracking == "")? "กำลังจัดส่งสินค้า":$tracking;
            $table = "berproduct_order_list"; 
            $set = "date_sent=:date_sent ,status=:status ,order_carrier=:carrier ,order_received=:tracking,order_netpay=:netpay,service_chg=:service_chg,date_update=:date_update,update_by=:by"; 
            $where = "order_id = :id";
            $value = array(
                ":id" => $order_id,
                ":date_sent" => date("Y-m-d H:i:s"),
                ":status" => $status, 
                ":carrier" => $carrier,
                ":tracking" => $tracking,
                ":netpay" => $netpay,
                ":service_chg" => $svCharge,
                ":date_update" => date("Y-m-d H:i:s"),
                ":by" => $_SESSION['user_id']
            );
            $ret['order_list'] = $dbcon->update_prepare($table,$set,$where,$value);  
        
            #วนลูปเช็คว่ามีตัวไหนผิดพลาด
            $result = purchaseOrderData::loop_check_method($ret);
            $result['total'] = getData::get_notification_purchase(); 
            echo json_encode($result);
        break;
        case'get_form_add_order_list':
                $slc_option ="<select class='form-control'><option>1</option></select>";
                $resultBer =  '<div class="detail_EMS emp" data-id="1">  
                                    <span class="number body-details">
                                        <input type="text" class="form-control text-center txt_network" data-id="1" data-name="network"   value="" placeholder="เครือข่าย"></span>  
                                    </span>
                                    <span class="body-details d-number">
                                        <input type="tel" maxlength="10" class="form-control text-center txt_phone add-required empty" data-id="1" data-name="phone"   value="" placeholder="เบอร์โทรศัพท์">
                                    </span>  
                                    <span class="number body-details">
                                        <input type="text" class="form-control txt_price"  data-id="1" data-name="price" title="ราคานี้หักจากส่วนลดแล้ว (กรณีที่มีส่วนลด)" value="0" placeholder="ราคา"></span>  
                                    </span>  
                                </div>';
                $status = "ts-active";
                $publish = "publish"; 
                $html = '<div class="purchase-head"><p>เพิ่มรายการ </p></div>
                            <div class="purchase-blog addOrderList"> 
                                <div class="blog-title"><p>เพิ่มข้อมูลผู้ซื้อ</p></div>
                                <div class="purchase-blog-contact">
                               
                                    <div class="grid-columns-2fr">  
                                        <p class="title">ชื่อ: </p>
                                        <span class="p-name">
                                            <input type="text" class="form-control txt_name" placeholder="berhoro" value="'.$order['c_name'].'"> 
                                        </span>
                                    </div>  
                                    <div class="grid-columns-2fr">
                                        <p class="title">อีเมล: </p> 
                                        <span class="p-email"><input type="text" class="form-control txt_email" placeholder="berhoro@gmail.com" value="'.$order['c_email'].'"></span>
                                    </div>  
                                    <div class="grid-columns-2fr">
                                        <p class="title">เบอร์โทร: </p> 
                                        <span class="p-tel"><input type="tel" class="form-control txt_tel" maxlength="10" placeholder="09123456789" value="'.$order['c_tel'].'"></span>
                                    </div> 
                                    <div class="grid-columns-2fr">
                                        <p class="title">ที่อยู่: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_address" placeholder="123/456 ซอย. หมู่." value="'.$order['c_address'].'"></span>
                                    </div>
                                    <div class="grid-columns-2fr">       
                                        <p class="title">ไปรษณีย์: </p> 
                                        <span class="p-address"><input type="tel" maxlength="5" class="form-control txt_zipcode" placeholder="10000" value="'.$order['c_zipcode'].'"></span>
                                    </div>
                                    <div class="grid-columns-2fr">       
                                        <p class="title">ตำบล/แขวง: </p> 
                                        <span class="p-address">
                                            <select class="form-control" id="slc_subdistrict"> <option value="" disabled selected>กรอกรหัสไปรษณีย์</option></select>
                                        </span>
                                    </div>
                                    <div class="grid-columns-2fr">     
                                        <p class="title">อำเภอ/เขต: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_district" placeholder="กรอกรหัสไปรษณีย์"  value="'.$order['c_district'].'"></span>
                                    </div>
                                
                                    <div class="grid-columns-2fr">     
                                        <p class="title">จังหวัด: </p> 
                                        <span class="p-address"><input type="text" class="form-control txt_province" placeholder="กรอกรหัสไปรษณีย์" value="'.$order['c_province'].'"></span>
                                    </div>
                                  
                                </div> 
                                <div class="blog-title"><p>เพิ่มข้อมูลสินค้า</p></div>
                                <div class="purchase-blog-order"> 
                                    <div class="head-order"> 
                                        <div class="detail_phone"> 
                                            <div class="body-title">เครือข่าย</div>
                                            <div class="body-title" style="text-align: center;">หมายเลข</div>
                                            <div class="body-title" style="text-align: center;">ราคา</div>
                                        </div>         
                                    </div> 
                                    <div class="body-order"> 
                                        <div class="dataShowORDER"> 
                                           '.$resultBer.'
                                        </div> 
                                    </div>  
                                </div>
                                <div class="body-datedeliver"> 
                                    <div class="total-price">
                                        <span class="total-price-box">
                                            <span>รวม</span>  
                                            <span class="total-order-price">0</span>
                                            <span> บาท</span> 
                                        </span> 
                                    </div>
                                    <div class="input-group date_deliver"> 
                                        <div class="switch-form add-category">
                                            <div class="col-md-12 switch-btn btnSwitchDelivery">
                                                <span class="switch-title">สถานะจัดส่ง: </span>
                                                <div class="toggle-switch '.$status.'" data-toggle="tooltip"  title="ปรับสถานะเป็น success เมื่อส่งมอบสินค้าแล้ว!" >
                                                <span class="switch"></span>
                                                </div>
                                                <input type="hidden" class="form-control" id="product_delivery" value="'.$publish.'">
                                            </div>  
                                        </div>  
                                    </div> 
                                </div>
                                <div class="purchase-blog-contact tracking">
                                    <div class="grid-columns-2fr">   
                                        <p class="title">Tracking: </p>
                                        <span class="p-name">
                                            <input type="text" class="form-control txt_tracking" placeholder="คำอธิบายการส่งสินค้า EMS-KERRY" value=""> 
                                        </span>
                                    </div>
                                    <div class="check-box-trans ems">
                                        <label class="container">จัดส่งแบบ EMS  
                                            <input type="checkbox" id="emsService" class="squared-ems" value="no">
                                            <span class="checkmark ems"></span>
                                        </label>
                                    </div> 
                                    <div class="check-box-trans kerry">
                                        <label class="container">จัดส่งแบบ KERRY  
                                            <input type="checkbox" id="kerryService"  class="squared-kerry" value="no">
                                            <span class="checkmark kerry"></span>
                                        </label>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        ';
         
            $response['html'] = $html; 
            echo json_encode($response); 
        break;
        case'insert_purchase_order':
            #guide 
            #insert->contact
            #insert->order_list
            #insert->manage
            $tracking = FILTER_VAR($_POST['tracking'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $carrier = FILTER_VAR($_POST['carrier'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $status = FILTER_VAR($_POST['status'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $email = FILTER_VAR($_POST['email'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
            $address = FILTER_VAR($_POST['address'],FILTER_SANITIZE_MAGIC_QUOTES);
            $district = FILTER_VAR($_POST['district'],FILTER_SANITIZE_MAGIC_QUOTES);
            $subdistrict = FILTER_VAR($_POST['subdistrict'],FILTER_SANITIZE_MAGIC_QUOTES);
            $province = FILTER_VAR($_POST['province'],FILTER_SANITIZE_MAGIC_QUOTES);
            $zipcode = FILTER_VAR($_POST['zipcode'],FILTER_SANITIZE_MAGIC_QUOTES);
            $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
            $product = $_POST['arr'];

            if(!empty($product)){
                $new_arr = array(); 
                $netpay = 0;
                $telIn = "";
                foreach($product as $val){
                 
                    $new_arr[$val['id']][$val['name']] = trim(strtoupper($val['value']));
                    if($val['name'] == "price" ){
                        $netpay += $val['value'];
                    } 
                    if($val['name'] == "phone" ){ 
                        $number = FILTER_VAR($val['value'],FILTER_SANITIZE_NUMBER_INT);
                        if(strlen($number) == 10){
                            $telIn .= ($telIn != "")?",".$number:$number;
                        }
                    }
                }
            } 
           
            if(!empty($new_arr) && count($new_arr) > 0){
                #insert->contact
                $table = "berproduct_contact"; 
                $field = "contact_firstname,contact_tel,contact_email,contact_address,contact_district,contact_subdistrict,contact_province,contact_zipcode"; 
                $key = ":contact_firstname,:contact_tel,:contact_email,:contact_address,:contact_district,:contact_subdistrict,:contact_province,:contact_zipcode"; 
                $value = array( 
                    ":contact_firstname" => $name,
                    ":contact_tel" => $tel,
                    ":contact_email" => $email,
                    ":contact_district" => $district,
                    ":contact_subdistrict" => $subdistrict,
                    ":contact_province" => $province,
                    ":contact_zipcode" => $zipcode,
                    ":contact_address" => $address 
                );
                $ins['contact'] = $dbcon->insertPrepare($table,$field,$key,$value); 

                $charge = ($order_netpay < 1500)? 50 : 0; 
                #insert->order_list
                #สร้างรายการสั่งซื้อ 
                $tracking = ($status == "publish" && $tracking == "")? "กำลังจัดส่งสินค้า":$tracking;
                $order_id = "BERHORO".$ins['contact']['insert_id'];
                $table = "berproduct_order_list"; 
                $field = "id,contact_id,date_order,status,order_carrier,order_received,order_netpay,service_chg,date_update,date_sent,update_by";
                $key = ":id,:contact_id,:date_order,:status,:carrier,:order_received,:order_netpay,:charge,:date_update,:date_sent,:update_by";
                $value = array( 
                    ":id" => $order_id,
                    ":contact_id" => $ins['contact']['insert_id'],
                    ":date_order" => date('Y-m-d H:i:s'),
                    ":status" => $status,
                    ":carrier" => $carrier,
                    ":order_received" =>$tracking,
                    ":order_netpay" => $netpay,
                    ":charge" => $charge,
                    ":date_update" => date('Y-m-d H:i:s'),
                    ":date_sent" => date('Y-m-d H:i:s'),
                    ":update_by" => $_SESSION['user_id']
                );
                $ins['order'] = $dbcon->insertPrepare($table,$field,$key,$value); 
                foreach($new_arr as $key => $value){  
                    $ber_status = ($status == "publish")? "soldout":"instock"; 
                    $ber_status = purchaseOrderData::check_ber_soldout(0,$value['phone'],$ber_status);
                    $listBer[] = array( 
                        'order_id' => $ins['order']['insert_id'],
                        'product_phone' => $value['phone'], 
                        'product_network' => $value['network'], 
                        'product_price' => $value['price'], 
                        'discount_desc' => $value['price'], 
                        'status' => $ber_status
                    ); 
                } 
                $ins['manage'] = $dbcon->multiInsert('berproduct_manage',$listBer);

                if($status == "publish"){
                    #ลบเบอร์ที่ไม่มีสินค้าออก
                    $table = "berproduct_manage";
                    $where = "order_id = :id AND status = :status";
                    $value = [ 
                        ':id' => $ins['order']['insert_id'],
                        ':status' => "empty" 
                    ];
                    $ins['del'] = $dbcon->deletePrepare($table, $where, $value); 

                    #อัพเดทข้อมูล
                    $ins['display'] = purchaseOrderData::update_product_status_by_orderid($ins['order']['insert_id']);
                }
            } 
            
            #วนลูปเช็คว่ามีตัวไหนผิดพลาด 
            $ret = purchaseOrderData::loop_check_method($ins);
            $ret['total'] = getData::get_notification_purchase(); 
            
            echo json_encode($ret);
        break;
        case'notfication_purchase':
            $sql =" SELECT ord.order_id  FROM berproduct_order_list as ord
                    INNER JOIN berproduct_contact as con ON ord.contact_id = con.contact_id   
                    INNER JOIN berproduct_manage as list ON ord.order_id = list.order_id 
                    WHERE ord.status = 'pending' GROUP BY ord.order_id";
            $result = $dbcon->fetchAll($sql,[]);
            if(isset($result[0]['order_id'])){
                $_SESSION['numb']  = count($result);
            } else {
                $_SESSION['numb']  = 0;
            }
            $ret['total'] = $_SESSION['numb'];
            echo json_encode($ret);
        break;

        case'update_service_chage':
            $charge = FILTER_VAR($_POST['charge'],FILTER_SANITIZE_NUMBER_FLOAT);
            $table = "contact_sel"; 
            $set = " service_charge =  :charge "; 
            $where = "id != :id";
            $value = array( ":charge" =>  $charge ,":id"=>0);
            $result = $dbcon->update_prepare($table,$set,$where,$value);   
            $res = ["message" => $result['message'],"description"=>"update service charge success"];
            echo json_encode($res);
        break;
        case'update_below_price':
            $charge = FILTER_VAR($_POST['charge'],FILTER_SANITIZE_NUMBER_FLOAT);
            $table = "contact_sel"; 
            $set = " below_price =  :charge "; 
            $where = "id != :id";
            $value = array( ":charge" =>  $charge ,":id"=>0);
            $result = $dbcon->update_prepare($table,$set,$where,$value);   
            $res = ["message" => $result['message'],"description"=>"update service charge success"];
            echo json_encode($res);
        break;
    }
}
?>
