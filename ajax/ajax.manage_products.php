<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once 'ajax.manage_product_modal.php';
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
// require_once dirname(__DIR__) . '/classes/class.uploadimage.php';
require_once dirname(__DIR__) . '/classes/class.manage_products.php';

$dbcon = new DBconnect();
getData::init(); 
$mydata = new manage_products();
$myModal = new manage_products_modals();

if (isset($_REQUEST['action'])) {
    $lang_config = getData::lang_config();
    switch ($_REQUEST['action']) {

        //รายการหมวดหมู่สินค้า
        case 'get_categoryProduct': 
                $requestData = $_REQUEST;
                $columns = array(   
                    0 => 'priority', 
                    1 => 'bercate_name', 				
                    2 => 'bercate_id', 	
                    3 => 'status', 		
                    4 => 'bercate_display', 			          	 
                ); 
                    
                $sql = 'SELECT * FROM berproduct_category WHERE bercate_id != 999999 '; 
                $requestData['search']['value'] = trim($requestData['search']['value']);
                if (!empty($requestData['search']['value'])) { 
                    $sql .= " AND (bercate_id  LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR bercate_title  LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR bercate_name  LIKE '%" . $requestData['search']['value'] . "%' )";
                }
                     

                $stmt = $dbcon->runQuery($sql);
                $stmt->execute();
                $totalData = $stmt->rowCount();
                $totalFiltered = $totalData;
                
                if($_REQUEST['order'][0]['column'] == 0){ 							 
                    $sql .= " ORDER BY CAST(" . $columns[$requestData['order'][0]['column']] . " as unsigned ) " . $requestData['order'][0]['dir'] ; 		
                }else{
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];	 
                }	  
                $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " "; 
                $result = $dbcon->query($sql);

               
                $output = array();	 
                if ($result) {
                    foreach ($result as $keys => $value) { 
                        $editCate = ($value['bercate_id'] == 3 ||$value['bercate_id'] == 4)?'editProductCateApproveById('.$value['bercate_id'].')':'editProductCateById('.$value['bercate_id'].')';
                        $mode = ($value['status'] == 'no')? '<span style="color:red;">[ '.$value['bercate_id'].' ] manual</span>' :'<span>[ '.$value['bercate_id'].' ]</span>';	
                        $system = '<div class="col-md-12 btnSystem"> 
                                        <div class="toggle-switch inTables '.(($value['status'] == 'no')?"":"ts-active").'" style="margin: auto">
                                            <span class="'.(($value['allow_edit'] == 'no')?"":"switch").'" data-id="'.$value['bercate_id'].'"> </span>
                                        </div>
                                        <input type="hidden" class="form-control" id="cate_status" value="'.(($value['status'] == 'no')?"no":"yes").'">
                                    </div>';
                        $display = '<div class="col-md-12 btnDisplay"> 
                                        <div class="toggle-switch inTables '.(($value['bercate_display'] == 'no')?"":"ts-active").'" style="margin: auto">
                                            <span class="switch" data-id="'.$value['bercate_id'].'"></span>
                                        </div>
                                         <input type="hidden" class="form-control" id="cate_status" value="'.(($value['bercate_display'] == 'no')?"no":"yes").'">
                                    </div>';
                        $pin = '<div class="col-md-12 btnPin"> 
                                    <div class="toggle-switch inTables '.(($value['bercate_pin'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="'.(($value['allow_edit'] == 'no'  && $value['bercate_id'] == 0)?"":"switch").'" data-id="'.$value['bercate_id'].'"></span>
                                    </div>
                                     <input type="hidden" class="form-control" id="cate_status" value="'.(($value['bercate_pin'] == 'no')?"no":"yes").'">
                                </div>';
                        $button ='<div class="table-blog-btn-action text-center">
                                    <a class="btn kt:btn-primary" style="color:white;"  onclick="viewProductByCateId('.$value['bercate_id'].')" data-id="'.$value['id'].'"><i class="fas fa-eye"></i> ดูสินค้า</a>
                                    <a class="btn kt:btn-warning " style="color:white;" data-toggle="modal" data-target="#exampleModal" onclick="'.$editCate.'" data-id="'.$value['id'].'"><i class="fas fa-edit"></i> แก้ไข</a>  '; 
                        $button .= ($value['allow_edit'] == 'no')?'<a></a></div>':'<a class="btn kt:btn-danger" style="color:white;" onclick="deleteProductCateById('.$value['bercate_id'].')" data-id="'.$value['id'].'"><i class="fas fa-trash-alt"></i> ลบ</a> </div>';
                        $nestedData = array();						
                        $nestedData[] = "<div  class='text-center' >".$value['priority']."</div>";
                        $nestedData[] = '<span class="showProduct namesearch" onclick="viewProductByCateId('.$value['bercate_id'].')"  data-id="'.$value['bercate_id'].'"> <i class="fas fa-eye fa-flip-horizontal"> </i>  '.trim($value['bercate_name']).'</span>  <span style="color:red;"> ['.$value['bercate_total'].']</span>';
                        $nestedData[] = "<div class='text-center'>".$mode."</div>";
                        $nestedData[] = $system;
                        $nestedData[] = $pin;
                        $nestedData[] = $display;
                        $nestedData[] = $button;   
                        $output[] = $nestedData;
                    }
                } 

            $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $output
                    // "check" => $sql,
            );
            echo json_encode($json_data); 

        break;
  
        case 'get_products':  
		
            /* แปลงค่าจาก ชื่อเครือข่ายเป็นรูปภาพ */
            $network = getData::product_prepare_network();
            $requestData = $_REQUEST;
            $columns = array(  
                    0 => 'product_id', 
                    1 => 'product_phone', 				
                    2 => 'product_sumber', 	
                    3 => 'product_network', 		
                    4 => 'product_price', 
                    5 => 'product_ads', 
                    6 => 'product_sold', 				          	 
                    7 => 'product_discount',
                    8 => 'display'			
                );
            if($_REQUEST['id'] == 0){
                $sql = 'SELECT * FROM berproduct WHERE product_id  != "0" ';
            }else{
                $sql = 'SELECT * FROM berproduct WHERE product_id != 0 AND product_category LIKE "%,'.$_REQUEST['id'].',%" ';
            } 
            $requestData['search']['value'] = trim($requestData['search']['value']);
      
            if (!empty($requestData['search']['value'])) {
    
                    $sql .= " AND (product_id  LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR product_phone  LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR product_network  LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR product_sumber  LIKE '%" . $requestData['search']['value'] . "%' )";
                }
         
                $stmt = $dbcon->runQuery($sql);
                $stmt->execute();
                $totalData = $stmt->rowCount();
                $totalFiltered = $totalData;	
                
                if($_REQUEST['order'][0]['column'] == 0){ 							 
                    $sql .= " ORDER BY CAST(" . $columns[$requestData['order'][0]['column']] . " as unsigned ) " . $requestData['order'][0]['dir'] ; 		
                }else{
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];	 
                }	 
             
                $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " "; 
             
                $result = $dbcon->query($sql);
                $output = array();				 
                  
                if ($result) {
                    foreach ($result as $keys => $value) {
                    /* ถ้าไม่มีรูปภาพให้แสดงผลเป็นชื่อเครือข่าย */
                    $imgNetwork = ($network[$value['product_network']] != '')? "<img style='width:70px'; src='".ROOT_URL.$network[$value['product_network']]."'>":$value['product_network'];
                    $nestedData = array();						
                    $nestedData[] = "<div class='text-center'>".$value['product_id']."</div>";		
                    $nestedData[] = "<div class=''>".$value['product_phone']."</div>";		
                    $nestedData[] = "<div class='text-center'>".$value['product_sumber']."</div>";		
                    $nestedData[] = "<div class='text-center'>".$imgNetwork."</div>";		
                    $nestedData[] = "<div class='text-center'>".$value['product_price']."</div>";	  
               			 
                    if($value['product_sold'] == 'yes' || $value['product_sold'] == 'y'){
                        $nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
                    }else{
                        $nestedData[] = '<div class="text-center"></div>';
                    }	 
                    // if($value['product_pin'] == 'yes' || $value['product_pin'] == 'y'){
                    //     $nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
                    // }else{
                    //     $nestedData[] = '';
                    // } 
                    $nestedData[] = "<div class='text-center'>".$value['product_discount']."</div>";	  
                    if($value['display'] == 'yes' || $value['display'] == 'y'){
                        $nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
                    }else{
                        $nestedData[] = '';
                    } 
                    $nestedData[] = '<div class="text-center" >
                                            <a class="btn kt:btn-warning btnEditProduct" style="color:white;" onclick="btnEditProduct('.$value['product_id'].')" data-id="'.$value['product_id'].'"><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                                            <a class="btn kt:btn-danger" style="color:white;" onclick="deleteProductById('.$value['product_id'].')" data-id="'.$value['product_id'].'"><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                                        </div> ';  	
                    $output[] = $nestedData;
                    }
                
                } 
                
                $maxSql = 'SELECT max(product_id)as id FROM berproduct';
                $maxRes =  $dbcon->query($maxSql);
    
                $id = ($maxRes[0]['id']); 
                $json_data = array(
                  "draw" => intval($requestData['draw']),
                  "recordsTotal" => intval($totalData),
                  "recordsFiltered" => intval($totalFiltered),			  
                  "data" => $output,	 
                  "maxId" => intval($id),
                  
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
                $set = "img = '" . $images['0'] . "'";
                $where = "id = '" . $_REQUEST['id'] ."'";
                $result = $dbcon->update($table, $set, $where);
                echo json_encode($result);
            } 
        break; 

        case 'editProductCate': 
            
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT); 
            $sql = "SELECT priority FROM product_cate WHERE id = $id LIMIT 1";
            $result = $dbcon->fetch($sql);
            $priorityOld = $result['priority']; 
            if($priorityNew != $priorityOld){ 
                $sql = "SELECT MAX(priority) as max FROM product_cate ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN priority > :old AND priority <= :new 
                    WHEN :old > :new THEN priority >= :new AND priority < :old 
                END)
                ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $r1 = $dbcon->update_prepare("product_cate",$set,$where,$value);

                $set = "priority = :new";
                $where = "id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $r2 = $dbcon->update_prepare("product_cate",$set,$where,$value);
            }

            $table = "product_cate";
            $set   = "name = :name , date_update = :update , display = :status , priority = :priority  ";
            $where = "id = :id";
            $value = array(
                ':id' => $_POST['id'],
                ':name' => $_POST['name'],
                ':status' => $_POST['status'],
                ':update' => date('Y-m-d H:i:s'),
                ':priority' => $priorityNew 
            );
            
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;
 
        case 'viewProductCate': 
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM berproduct_category WHERE bercate_id = :id ";
            $result = $dbcon->fetchAll($sql,[":id" => $id])[0]; 
            $result['html'] = ($result['status'] == "yes")?
                                $myModal->viewProductCateModalA($result):
                                $myModal->viewProductCateModalB($result);
            echo json_encode($result);
        break;

        case'viewProductCateMode':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT cate.bercate_id,cate.discount_mode,cate.bercate_discount,cate.discount_begin,cate.discount_expire,cate.bercate_name,cate.bercate_title,cate.thumbnail,cate.priority,
                           cate.bercate_h1,cate.bercate_h2,cate.meta_title,cate.meta_description,app.func_id,app.func_name,app.func_display,app.func_desc,app.func_cate_id 
                    FROM berproduct_category as cate 
                    INNER JOIN berproduct_category_approve as app ON cate.bercate_id = app.func_cate_id 
                    WHERE cate.bercate_id = :cate
                    ORDER BY app.func_id";
            $query = $dbcon->fetchAll($sql,[":cate"=>$id]); 
            if(!empty($query)){
                $result = $query[0];
                $ret = "";
                foreach($query as $key =>$val){
                    $case = ($val['func_display'] == "yes")?"ts-active":"";
                    $ret.='<div class="switch-form">
                            <div class="col-md-12 switch-btn cate-lover" >
                                <span style="font-weight:bold;">#case'.$val['func_id'].':</span>
                                <div class="toggle-switch '.$case.'">
                                    <span class="approve switch" data-id="'.$val['func_id'].'"></span>
                                </div>
                                <input type="hidden" class="form-control" value="'.$val['func_display'].'">
                                <div class="txt-description">
                                    <span> '.$val['func_desc'].'</span>
                                    <span>'.$val['func_name'].'</span>
                                </div>
                            </div>
                        </div>'; 
                }
            } 
            $result['html'] = $myModal->viewProductCateModeA($result, $fnHTML);
            echo json_encode($result);
        break;

        case 'getMaxPriorityCategoryProduct':
            $sql = "SELECT MAX(priority)+1 as priority FROM berproduct_category";
            $res = $dbcon->fetchObject($sql,[]);
            echo json_encode([
                'message' => 'OK',
                'priority' => $res->priority 
            ]);
        break;
            //เพิ่มรูปภาพ Brand สินค้า
            case 'uploadimgbrand':

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
                $table = "product_bran";
                $set = "  product_bn_img = '" . $images['0'] . "'";
                $where = "product_bn_id = '" . $_REQUEST['id'] ."'";
                $result = $dbcon->update($table, $set, $where);
                echo json_encode($result);
            }
        break;


         case 'getMaxPriorityBrand':
            $sql = "SELECT MAX(priority)+1 as priority FROM product_bran";
            $res = $dbcon->fetchObject($sql,[]);
            if($res->priority == NULL){  $res->priority = 1; }
            echo json_encode([
                'message' => 'OK',
                'priority' => $res->priority 
            ]);
        break;
        case 'editProducBrand': 
            
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT priority FROM product_bran WHERE product_bn_id = $id LIMIT 1";
            $result = $dbcon->fetch($sql);
            $priorityOld = $result['priority'];
            
            if($priorityNew != $priorityOld){

                $sql = "SELECT MAX(priority) as max FROM product_bran ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }


                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN priority > :old AND priority <= :new 
                    WHEN :old > :new THEN priority >= :new AND priority < :old 
                END)
                ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );

                $r1 = $dbcon->update_prepare("product_bran",$set,$where,$value);

                $set = "priority = :new";
                $where = "product_bn_id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $r2 = $dbcon->update_prepare("product_bran",$set,$where,$value);
            }

            $table = "product_bran";
            $set   = "product_bn_name = :name ,
                      product_bn_update = :update , 
                      product_bn_display = :status , 
                      priority = :priority ";
            $where = "product_bn_id = :id";

            $value = array(
                ':id' => $_POST['id'],
                ':name' => $_POST['name'],
                ':status' => $_POST['status'],
                ':update' => date('Y-m-d H:i:s'),
                ':priority' => $priorityNew,
            );
            
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;

        case 'viewProductBrand':
            $sql = "SELECT * FROM product_bran WHERE product_bn_id = '".$_POST['id']."'";
            $result = $dbcon->fetch($sql);
            echo json_encode($result);
        break;

        

#=============================== SUBCATE สินค้า ================================

        case'viewProductSubCate':
            $sql = "SELECT * FROM product_sub_cate WHERE id = '".$_POST['id']."'";
            $result = $dbcon->fetch($sql); 
            echo json_encode($result);
        break;

        case 'getMaxPrioritySubCategoryProduct':
            $sql = "SELECT MAX(priority)+1 as priority FROM product_sub_cate";
            $res = $dbcon->fetchObject($sql,[]);
            echo json_encode([
                'message' => 'OK',
                'priority' => $res->priority 
            ]);
        break;

        case 'deleteProductSubCate': 

            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $result_cate_pri = $dbcon->fetch("SELECT priority FROM product_sub_cate WHERE id = ".$id." LIMIT 1");


            $set = "priority = priority-1";
            $where = "priority > '".$result_cate_pri['priority']."'";
            $dbcon->update('product_sub_cate',$set,$where);

            $table = "product_sub_cate";
            $where = "id = '".$id."'";
            $result = $dbcon->delete($table, $where);
            echo json_encode($result);
        break;
        //เพิ่มหมวดหมู่ย่อยสินค้า
        case 'add_product_subcate': 
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT); 
            #set priority
            $set = "priority = priority+1";
            $where = "priority >= '".$priorityNew."'";
            $result = $dbcon->update("product_sub_cate", $set, $where);  
            $table = "product_sub_cate";
            $field = "id,product_cate,name,img,date_create,date_update,display,priority";
            $value = "null,'".$_POST['cateid']."','".$_POST['name']."','img', NOW(),NOW(),'".$_POST['status']."','".$_POST['priority']."'";
            $result = $dbcon->insert($table, $field, $value);
            echo json_encode($result);
        break; 
        case 'editProductSubCate': 
            
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT priority FROM product_sub_cate WHERE id = $id LIMIT 1";
            $result = $dbcon->fetch($sql);
            $priorityOld = $result['priority'];
            
            if($priorityNew != $priorityOld){

                $sql = "SELECT MAX(priority) as max FROM product_sub_cate ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }


                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN priority > :old AND priority <= :new 
                    WHEN :old > :new THEN priority >= :new AND priority < :old 
                END)
                ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $r1 = $dbcon->update_prepare("product_sub_cate",$set,$where,$value);

                $set = "priority = :new";
                $where = "id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $r2 = $dbcon->update_prepare("product_sub_cate",$set,$where,$value);
            }
            

            $table = "product_sub_cate";
            $set   = "name = :name , date_update = :update , display = :status , priority = :priority  ";
            $where = "id = :id";
            $value = array(
                ':id' => $_POST['id'],
                ':name' => $_POST['name'],
                ':status' => $_POST['status'],
                ':update' => date('Y-m-d H:i:s'),
                ':priority' => $priorityNew 
            );
            
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;

        case 'uploadimgcontent_subcate': 
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
                $table = "product_sub_cate";
                $set = "img = '" . $images['0'] . "'";
                $where = "id = '" . $_REQUEST['id'] ."'";
                $result = $dbcon->update($table, $set, $where);
                echo json_encode($result);
            } 
        break; 

        case'add_product_category':

            $sql = 'SELECT MAX(priority) as maxpri FROM berproduct_category';
            $result = $dbcon->query($sql);
            $maxPri  = $result[0]['maxpri'] + 1;  

            if($_REQUEST['status'] == 'yes'){
                $sqlId = 'SELECT MAX(bercate_id) as maxid FROM berproduct_category WHERE status = "yes" '; 
            }else{
                $sqlId = 'SELECT MAX(bercate_id) as maxid FROM berproduct_category WHERE status = "no" '; 
            }
            $resId = $dbcon->query($sqlId);
            $manual_id  = $resId[0]['maxid'] + 1; 

 
            $table = "berproduct_category";
            $field = "bercate_id,bercate_name,status,bercate_display,bercate_needful,bercate_needless,priority,date_created";
            $param = ":bercate_id,:bercate_name,:status,:bercate_display,:bercate_needful,:bercate_needless,:priority,:date_created";						 
            $value = array(	
                      ":bercate_id" => filter_var($manual_id,FILTER_SANITIZE_ADD_SLASHES),
                      ":bercate_name" =>filter_var($_POST['name'],FILTER_SANITIZE_ADD_SLASHES),   
                      ":status" =>filter_var($_POST['status'],FILTER_SANITIZE_ADD_SLASHES),  
                      ":bercate_display" =>filter_var($_POST['display'],FILTER_SANITIZE_ADD_SLASHES), 				
                      ":bercate_needful" =>filter_var($_POST['needful'],FILTER_SANITIZE_ADD_SLASHES), 
                      ":bercate_needless" =>filter_var($_POST['needless'],FILTER_SANITIZE_ADD_SLASHES),  
                      ":priority" =>filter_var($maxpri,FILTER_SANITIZE_ADD_SLASHES),  
                       ":date_created" => date('Y-m-d H:i:s') 	 			 
            );

             
            $result = $dbcon->insertPrepare($table, $field,$param, $value);
          
            if(isset($_REQUEST['prio']) && $_REQUEST['prio'] != 0 && $result['status'] == 200){ 
                $getpost['new'] = $_REQUEST['prio'];
                $getpost['id'] = $result['insert_id'];
                $getpost['old'] = $maxPri; 					
                $ret['priority'] =  $dataClass->priorityControl($getpost);			 
            } 	
                 
             /*  update จำนวนสินค้าในหมวด */
             $getpost = array(); 
             if($_REQUEST['status'] == 'yes'){ 
                $getpost['order'] = $manual_id;
                $ret['auto'] =  $dataClass->getProductByCategory($getpost);  
             }else{
                $ret['manual'] =  $dataClass->getProductByCategoryManual($getpost);
             } 
             $ret['cate'] =  $dataClass->updateCategorySpace(); 
            if($result['status'] != 200){ 
                $ret['status'] = 'error'; 			 
             
            }else{  $ret['status'] = '200'; } 

            echo json_encode($ret); 
        break;
        case'updateCategoryPin':
                  
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $pin = filter_var($_POST['pin'],FILTER_SANITIZE_ADD_SLASHES);
  
            $table = "berproduct_category";
            $set   = "bercate_pin = :pin";
            $where = "bercate_id = :bercate_id";
            $value = array(
                ':bercate_id' => $id,
                ':pin' => $pin 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where, $value);
        
            echo json_encode($result);
        break;

        case'updateCategoryDisplay':
                  
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $display = filter_var($_POST['display'],FILTER_SANITIZE_ADD_SLASHES);
  
            $table = "berproduct_category";
            $set   = "bercate_display = :display";
            $where = "bercate_id = :bercate_id";
            $value = array(
                ':bercate_id' => $id,
                ':display' => $display 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where, $value);
        
            echo json_encode($result);
        break;
        case"update_category_system":
          
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $system = filter_var($_POST['system'],FILTER_SANITIZE_ADD_SLASHES);
            $table = "berproduct_category";
            $set   = "status = :status";
            $where = "bercate_id = :bercate_id AND bercate_display = 'yes' ";
            $value = array(
                ':bercate_id' => $id,
                ':status' => $system 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);   
        break;
      
        case'editCategory':
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($_POST['name'],FILTER_SANITIZE_ADD_SLASHES);
            $abv = filter_var($_POST['abv'],FILTER_SANITIZE_ADD_SLASHES);
            $image = filter_var($_POST['image'],FILTER_SANITIZE_ADD_SLASHES);
            $needless = filter_var($_POST['needless'],FILTER_SANITIZE_ADD_SLASHES);
            $needful = filter_var($_POST['needful'],FILTER_SANITIZE_ADD_SLASHES);
            $meta_title = filter_var($_POST['meta_title'],FILTER_SANITIZE_ADD_SLASHES);
            $meta_desc = filter_var($_POST['meta_desc'],FILTER_SANITIZE_ADD_SLASHES);
            $discount = filter_var($_POST['discount'],FILTER_SANITIZE_NUMBER_INT);
            $h1 = filter_var($_POST['h1'],FILTER_SANITIZE_ADD_SLASHES);
            $h2 = filter_var($_POST['h2'],FILTER_SANITIZE_ADD_SLASHES);
            $content = filter_var($_POST['content'],FILTER_SANITIZE_ADD_SLASHES);
            $discountMode = filter_var($_POST['discountMode'],FILTER_SANITIZE_ADD_SLASHES); 

            $beginDate = filter_var($_POST['discountBeginDate'],FILTER_SANITIZE_ADD_SLASHES);
            $beginTime = filter_var($_POST['discountBeginTime'],FILTER_SANITIZE_ADD_SLASHES);
            $expireDate = filter_var($_POST['discountExpireDate'],FILTER_SANITIZE_ADD_SLASHES);
            $expireTime = filter_var($_POST['discountExpireTime'],FILTER_SANITIZE_ADD_SLASHES);

            $discountBegin = ($beginDate != '' || $beginTime != '')?
                date('Y-m-d H:i:s', strtotime("{$beginDate} {$beginTime}")):"0000-00-00 00:00:00";

            $discountExpire = ($expireDate  != '' || $expireTime != '')?
                date('Y-m-d H:i:s', strtotime("{$expireDate} {$expireTime}")):"0000-00-00 00:00:00"; 
 
 
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT); 
            $sql = "SELECT priority,bercate_discount FROM berproduct_category WHERE bercate_id = $id"; 
            $result = $dbcon->fetchObject($sql,[]);
            $priorityOld = $result->priority; 
            $sql = "SELECT bercate_name FROM berproduct_category WHERE bercate_name = :catename AND bercate_id != :id";
            $checking = $dbcon->fetchObject($sql,[":catename"=>$name,":id"=>$id]);

            if(!empty($checking)){
                echo json_encode([
                    "message"=>"error",
                    "status"=> 400
                ]);
                exit();
            } 

            if($priorityNew != $priorityOld && $priorityNew != ""){ 
                $sql = "SELECT MAX(priority) as max FROM berproduct_category "; 
                $PriorityMax = $dbcon->fetchObject($sql,[]); 
                if($priorityNew > $PriorityMax->max){  
                    $priorityNew = $PriorityMax->max+1; 
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "bercate_id <> :id AND (CASE WHEN :old < :new THEN priority > :old AND priority <= :new 
                                              WHEN :old > :new THEN priority >= :new AND priority < :old  END) ";
                $value = array( 
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew );
                $r1 = $dbcon->update_prepare("berproduct_category",$set,$where,$value);
            }else {
                $priorityNew = $priorityOld;
            }  
         
            #ถ้าไม่ได้ใช้ระบบจัดหมวดหมู่อัตโนมัติทำอันนี้
            if($type == "no"){ 
                $set = "bercate_name = :bercate_name,
                        bercate_slug = :bercate_slug,
                        bercate_discount = :bercate_discount,
                        discount_begin = :discount_begin,
                        discount_expire = :discount_expire,
                        priority = :priority ,
                        date_edit = :date_edit,
                        bercate_title=:abv ,
                        bercate_h1 = :bercate_h1 ,
                        bercate_h2 = :bercate_h2 ,
                        bercate_content = :bercate_content ,
                        discount_mode = :discount_mode ,
                        thumbnail = :image";
                $where = "bercate_id = :bercate_id";
                $value = array(
                    ':bercate_id' => $id,
                    ':bercate_name' => $name,
                    ':bercate_discount' => $discount,
                    ':discount_begin' => $discountBegin,
                    ':discount_expire' => $discountExpire, 
                    ':date_edit' => date('Y-m-d H:i:s'),
                    ':priority' => $priorityNew, 
                    ':abv' => $adv, 
                    ':image' => $image,
                    ':bercate_h1' => $h1, 
                    ':bercate_h2' => $h2, 
                    ':bercate_content' => $content,
                    ':discount_mode' =>  $discountMode
                );  
            } else { 
                $set= "bercate_name = :bercate_name,
                       bercate_discount = :bercate_discount, 
                       discount_begin = :discount_begin,
                       discount_expire = :discount_expire,
                       bercate_needful= :needful,
                       bercate_needless = :needless, 
                       priority = :priority,
                       bercate_title = :abv,
                       thumbnail = :image, 
                       bercate_h1 = :bercate_h1,
                       bercate_h2 = :bercate_h2,
                       bercate_content = :bercate_content, 
                       meta_title = :meta_title,
                       meta_description = :meta_description,
                       discount_mode = :discount_mode,
                       date_edit = :date_edit 
                       ";
                $where = "bercate_id = :bercate_id";
                $value = array(
                    ':bercate_id' => $id,
                    ':bercate_name' => $name,
                    ':bercate_discount' => $discount,
                    ':discount_begin' => $discountBegin,
                    ':discount_expire' => $discountExpire, 
                    ':needful' => $needful,
                    ':needless' => $needless,
                    ':date_edit' => date('Y-m-d H:i:s'),
                    ':priority' => $priorityNew ,
                    ':abv' => $abv ,
                    ':image' => $image ,
                    ':bercate_h1' => $h1 ,
                    ':bercate_h2' => $h2 ,
                    ':meta_title' => $meta_title ,
                    ':meta_description' => $meta_desc ,
                    ':bercate_content' => $content ,
                    ':discount_mode' =>  $discountMode
                );
            }  
      
            $table = "berproduct_category";
            $upds = $dbcon->update_prepare($table, $set, $where, $value);

            if($result->bercate_discount != $discount){
                #ถ้ามีการกำหนดวันที่จะทำในส่วนของ conjobs function = Application->conjobs()
                if($discountBegin != "0000-00-00 00:00:00" && $discountExpire != "0000-00-00 00:00:00" && date('Y-m-d H:i:s') < $discountExpire){
                    #todo 
                    $field = "event_name,primary_key,title,title_st,condition_st,title_nd,condition_nd,title_rd,condition_rd,date_begin,date_expire,begin_status,expire_status,status_as,admin_id,created_at,updated_at,note";
                    $key = ":event_name,:primary_key,:title,:title_st,:condition_st,:title_nd,:condition_nd,:title_rd,:condition_rd,:date_begin,:date_expire,:begin_status,:expire_status,:status_as,:admin_id,:created_at,:updated_at,:note";
                    $value = array(
                        ":event_name" => 'category_discount',
                        ":primary_key" => $id,
                        ":title" => "ส่วนลดทั้งหมวดหมู่ ",
                        ":title_st" => "หมวดหมู่ไอดี",
                        ":condition_st" => $id,
                        ":title_nd" => "ส่วนลดที่ต้องการ",
                        ":condition_nd" => $discount,
                        ":title_rd" => "ส่วนลดหลังหมดเวลา",
                        ":condition_rd" => 0,
                        ":date_begin" => $discountBegin,
                        ":date_expire" => $discountExpire,
                        ":begin_status" => "pending", #สถานะว่าทำหรือยัง ถ้าทำแล้วจะเป็น success 
                        ":expire_status" => "pending", #สถานะว่าทำหรือยัง ถ้าทำแล้วจะเป็น success
                        ":status_as" => "active", #เปิดปิดการทำงาน
                        ":admin_id" => $_SESSION['user_id'],
                        ":created_at" => date('Y-m-d H:i:s'),
                        ":updated_at" => date('Y-m-d H:i:s'),
                        ":note" => "ระบบส่วนลดทั้งหมวดหมู่โดยกำหนดวันล่วงหน้า เงื่อนไขวันที่เริ่มต้น - วันที่สิ้นสุด ",
                    );
                    $created = $dbcon->insertPrepare('whatsnew', $field, $key , $value);
                } else {
                    $upds['discount'] = $mydata->update_category_discount($id,$discount);   
                }
            }

            echo json_encode($upds);
        break; 
        case"addCategory":
            $priorityNew = filter_var($_POST['priority'],FILTER_SANITIZE_NUMBER_INT); 
            $name = filter_var($_POST['name'],FILTER_SANITIZE_ADD_SLASHES);
            $needless = filter_var($_POST['needless'],FILTER_SANITIZE_ADD_SLASHES);
            $needful = filter_var($_POST['needful'],FILTER_SANITIZE_ADD_SLASHES);
            $status = filter_var($_POST['status'],FILTER_SANITIZE_ADD_SLASHES);
            $display = filter_var($_POST['display'],FILTER_SANITIZE_ADD_SLASHES); 

            $sql = "SELECT bercate_name FROM berproduct_category WHERE bercate_name = :catename";
            $checking = $dbcon->fetchObject($sql,[":catename"=>$name]);
            if(!empty($checking)){
                echo json_encode([
                    "message"=>"ชื่อหมวดซ้ำ!",
                    "status"=> "error"
                ]);
                exit();
            }

            if($_POST['status'] == 'yes'){
                $sqlId = 'SELECT MAX(bercate_id) as maxid FROM berproduct_category WHERE status = "yes" '; 
            }else{
                $sqlId = 'SELECT MAX(bercate_id) as maxid FROM berproduct_category WHERE status = "no" '; 
            }
            $resId = $dbcon->query($sqlId);
            $bercate_id  = $resId[0]['maxid'] + 1; 
              
            #set priority
            $table = "berproduct_category"; 
            if(isset($priorityNew) && $priorityNew != ""){
                $set = "priority = priority+1";
                $where = "priority >= '".$priorityNew."'";
                $result = $dbcon->update_prepare($table, $set, $where, $value);
                $priority = $priorityNew;
            } else {
                $sql = "SELECT max(priority) as priority FROM berproduct_category";
                $priorityResult = $dbcon->fetchObject($sql,[]);
                $priority = $priorityResult->priority + 1; 
            }
           
            $field = "bercate_id,bercate_name, bercate_needless, bercate_needful,priority,date_created,date_edit,bercate_display,status";
            $key = ":bercate_id,:bercate_name,:bercate_needless,:bercate_needful,:priority,:date_created,:date_edit,:bercate_display,:status";
            $value = array(
                ":bercate_id" => $bercate_id,
                ":bercate_name" => $name,
                ":bercate_needless" => $needless,
                ":bercate_needful" => $needful,
                ":priority" => $priority,
                ":date_created" => date("Y-m-d H:i:s"),
                ":date_edit" => date("Y-m-d H:i:s"),
                ":bercate_display" => $display,
                ":status" => $status
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            echo json_encode($result);
        break;
        case'updateCategoryApprove':
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT); 
            $display = filter_var($_POST['display'],FILTER_SANITIZE_ADD_SLASHES); 
            $table = "berproduct_category_approve";
            $set   = "func_display = :display";
            $where = "func_id = :func_id";
            $value = array(
                ':func_id' => $id,
                ':display' => $display 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where, $value);
        
            echo json_encode($result);
        break;
        case 'deleteProductCate': 
            
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $result_cate_pri = $dbcon->fetchObject("SELECT priority FROM berproduct_category WHERE bercate_id = ".$id." LIMIT 1",[]);

            $table = "berproduct_category";
            $set = "priority = priority-1";
            $where = " priority > :priority ";
            $value = array(":priority" => $result_cate_pri->priority);
            $dbcon->update_prepare($table,$set,$where,$value);
           
            $where = "bercate_id = :id";
            $value = array(":id" => $id);
            $result = $dbcon->deletePrepare($table, $where,$value);

            echo json_encode($result);
        break;
        case'product_config_add':
            $sql ="SELECT * FROM bernetwork WHERE display = :display";
            $resultNetwork = $dbcon->fetchAll($sql,[":display"=>"yes"]);
            if(!empty($resultNetwork)){
                $option = "";
                foreach($resultNetwork as $key =>$val){
                    $option .= '<option value="'.$val['network_name'].'" >'.$val['network_name'].'</option>';
                }
            }

            $html ='<div class="me-swal-title">หมายเลข: </div>
                <input type="tel" maxlength="10" class="swal2-input txt_tel" placeholder="0989999999"  value="">
                <div class="me-swal-title">ราคา:</div>
                <input  class="swal2-input txt_price" placeholder="999"  value="">
                <div class="blog-discount">
                <div class="me-swal-title">ส่วนลด: </div>
                <input type="tel" maxlength="3" class="swal2-input txt_discount" placeholder="0"  value="">
                <div> % </div>
            </div>
                <div class="me-swal-title">เครือข่าย:</div>
                <div class="slc-add-ber">
                    <select class="swal2-input slc_network">'.$option.'</select>
                </div>
                <div class="switch-form add-ber">
                    <div class="col-md-12 switch-btn btnProductDisplay">
                        <span class="title-switch-btn">Display: </span>
                        <div class="toggle-switch ts-active">
                            <span class="switch"></span>
                            <input type="hidden" class="form-control" id="product_display" value="yes">
                        </div>
                    </div>
                    <div class="col-md-12 switch-btn btnProductPin">
                        <span class="title-switch-btn">VIP: </span>
                        <div class="toggle-switch ">
                            <span class="switch"></span>
                            <input type="hidden" class="form-control" id="product_pin" value="no">
                        </div>
                    </div>
                    <div class="col-md-12 switch-btn btnProductSold">
                        <span class="title-switch-btn">Sold: </span>
                        <div class="toggle-switch ">
                            <span class="switch"></span>
                            <input type="hidden" class="form-control" id="product_sold" value="no">
                        </div>
                    </div> 
                </div>';
     
            echo json_encode(["html"=> $html]);
        break;
        case'product_config_edit':

            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT * FROM bernetwork WHERE display = :display";
            $resultNetwork = $dbcon->fetchAll($sql,[":display"=>"yes"]);
            $sql ="SELECT * FROM berproduct WHERE product_id = :id";
            $ber = $dbcon->fetchObject($sql,[":id"=>$id]);
            if(!empty($resultNetwork)){
                $option = "";
                foreach($resultNetwork as $key =>$val){
                    $selected = ($ber->product_network == $val['network_name'])? "SELECTED":"";
                    $option .= '<option '.$selected.' value="'.$val['network_name'].'" >'.$val['network_name'].'</option>';
                }
            }
           
            $html ='<div class="me-swal-title">หมายเลข: </div>
                <input type="tel" maxlength="10" class="swal2-input txt_tel" placeholder="0989999999"  value="'.$ber->product_phone.'">
                <div class="me-swal-title">ราคา:</div>
                <input  class="swal2-input txt_price" placeholder="999"  value="'.$ber->product_price.'">
                <div class="blog-discount">
                    <div class="me-swal-title">ส่วนลด: </div>
                    <input type="tel" maxlength="3" class="swal2-input txt_discount" placeholder="0"  value="'.$ber->product_discount.'">
                    <div> % </div>
                </div>
                <div class="me-swal-title">เครือข่าย:</div>
                <div class="slc-add-ber">
                    <select class="swal2-input slc_network">'.$option.'</select>
                </div>
                <div class="switch-form add-ber">
                    <div class="col-md-12 switch-btn btnProductDisplay">
                        <span class="title-switch-btn">Display: </span>
                        <div class="toggle-switch '.(($ber->display == "yes")?"ts-active":"").'">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_display" value="'.$ber->display.'">

                    </div>
                    <div class="col-md-12 switch-btn btnProductPin">
                        <span class="title-switch-btn">VIP: </span>
                        <div class="toggle-switch '.(($ber->product_pin == "yes")?"ts-active":"").'">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_pin" value="'.$ber->product_pin.'">

                    </div>
                    <div class="col-md-12 switch-btn btnProductSold">
                        <span class="title-switch-btn">Sold: </span>
                        <div class="toggle-switch '.(($ber->product_sold == "yes")?"ts-active":"").'">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_sold" value="'.$ber->product_sold.'">

                    </div> 
                </div>';
     
            echo json_encode(["html"=> $html,"ber"=>$ber]);
        break;
        case'add_product':
            $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
            $price = FILTER_VAR($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $network = strtoupper(FILTER_VAR($_POST['network'],FILTER_SANITIZE_ADD_SLASHES));
            $display = FILTER_VAR($_POST['display'],FILTER_SANITIZE_ADD_SLASHES);
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_ADD_SLASHES);
            $sold = FILTER_VAR($_POST['sold'],FILTER_SANITIZE_ADD_SLASHES);

            if(isset($tel) && strlen($tel) == 10){
                $sum = 0; 
                for($i=0;$i < 9;$i++){ 
                    $sum += (int)substr($tel,$i,1);
                }  
                $table = "berproduct";
                $field = "product_phone,product_sumber,product_network,product_price,product_pin,product_sold,display";
                $key = ":product_phone,:product_sumber,:product_network,:product_price,:product_pin,:product_sold,:display";
                $value = array(
                    ":product_phone" => $tel,
                    ":product_sumber" => $sum,
                    ":product_network" => $network,
                    ":product_price" => $price,
                    ":product_pin" => $pin,
                    ":product_sold" => $sold,
                    ":display" => $display
                );  
                $result = $dbcon->insertPrepare($table, $field, $key , $value);
            }
            echo json_encode($result);
        break;
        case'edit_product':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
            $price = FILTER_VAR($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $discount = FILTER_VAR($_POST['discount'],FILTER_SANITIZE_NUMBER_INT);
            $network = strtoupper(FILTER_VAR($_POST['network'],FILTER_SANITIZE_ADD_SLASHES));
            $display = FILTER_VAR($_POST['display'],FILTER_SANITIZE_ADD_SLASHES);
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_ADD_SLASHES);
            $sold = FILTER_VAR($_POST['sold'],FILTER_SANITIZE_ADD_SLASHES);
            $table = "berproduct";
            $set   = "product_network = :product_network 
                     ,product_price = :product_price 
                     ,product_discount = :product_discount 
                     ,product_pin = :product_pin 
                     ,product_sold = :product_sold 
                     ,display =:display  ";
            $where = "product_id = :id";
            $value = array(
                ":id" => $id, 
                ":product_network" => $network,
                ":product_price" => $price,
                ":product_discount" => $discount,
                ":product_pin" => $pin,
                ":product_sold" => $sold,
                ":display" => $display
            );
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
 
        break;
        case'deleteProductById':
            $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT); 
            print_r($id); 
            $table = "berproduct";  
            $where = "product_id = :id";
            $value = array(":id" => $id);
            $result = $dbcon->deletePrepare($table, $where,$value);
        break;
        case'uploadImageCategory': 
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/'; 
            $thumbnail = $mydata->upload_images_thumb($new_folder);   
            echo json_encode($thumbnail);
        break;

   
 
    }
}
