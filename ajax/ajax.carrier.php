<?php	
session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
// require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.purchaseOrderData.php';
$dbcon = new DBconnect();

getData::init(); 



purchaseOrderData::init();







if(isset($_REQUEST['action'])) {
	$lang_config = getData::lang_config(); 
    switch($_REQUEST['action']){

        case'order_carrier_publsih':
            case'orderListTable':
                $dateArr = explode('/',$_REQUEST['date']);  
                $requestData = $_REQUEST;
                #ส่วนของการ order ข้อมูลจากตาราง
                $columns = array(
                    0 => 'ord.order_id',
                    1 => 'con.contact_firstname',  
                    3 => 'ord.order_netpay',  
                    4 => 'ord.date_sent'  
                ); 
                $sql =" SELECT ord.order_id as order_id 
                               ,ord.id as code 
                               ,ord.order_carrier as carrier 
                               ,ord.order_received as description 
                               ,ord.status as status
                               ,ord.order_netpay as netpay
                               ,ord.service_chg as charge
                               ,ord.date_sent as date  
                               ,con.contact_firstname as name
                               ,con.contact_tel as tel
                               ,list.product_phone as p_phone
                               ,list.product_network as p_network
                               ,list.product_price as p_price
                        FROM berproduct_order_list as ord
                        INNER JOIN berproduct_contact as con ON ord.contact_id = con.contact_id   
                        INNER JOIN berproduct_manage as list ON ord.order_id = list.order_id 
                        WHERE ord.status = 'publish' ";
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
                        $date = purchaseOrderData::format_thai_date($value['date']);
                        $nestedData = array();						
                        $nestedData[] = $value['code'];
                        $nestedData[] = $value['p_phone'];
                        $nestedData[] = $value['name'];
                        $nestedData[] = number_format($value['p_price'])."฿ <span style='color:grey'> [ ".number_format($value['netpay'])."฿ ] </span>";
                        $nestedData[] = $date;
                        $nestedData[] = strtoupper($value['carrier']);
                        $nestedData[] = ($value['description']); 

                              #รอการเปิดใช้งาน
                        // $nestedData[] = "";
                        $nestedData[] = '<button type="button" class="btn kt:btn-danger" onclick="removeOrderByID('.$value["order_id"].')"> <i class="fas fa-trash">  ลบ</button>';
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
  
        case'removeOrderCarrier':
            $id = $_POST['id'];
            $table = "berproduct_order_list";
            $set = "status = :status, update_by = :userId, date_update = :date";
            $where = "order_id = :id";
            $value = array(
                ":id" => $id,
                ":status" => "remove",
                ":userId" => $_SESSION['user_id'],
                ":date" => date('Y-m-d H:i:s')
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;
    
    }


}
?>