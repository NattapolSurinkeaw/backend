<?php
// use function GuzzleHttp\json_encode; 
session_start();
error_reporting(1);
ini_set('display_errors', 1);
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.uploadimage.php';
require_once dirname(__DIR__) . '/classes/class.predicts.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new predicts();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
        case'get_category':
      
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'numbcate_priority',
                1 => 'numbcate_name',
                2 => 'numbcate_title',
                3 => 'numbcate_pin'
            );

            $sql = "SELECT * FROM berpredict_numbcate"; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE numbcate_name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR numbcate_title LIKE '%" . $requestData['search']['value'] . "%' ";
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
                    $display =  '<div class="col-md-12 btnDisplay"> 
                                    <div class="toggle-switch inTables '.(($value['numbcate_pin'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="switch" data-id="'.$value['numbcate_id'].'"></span>
                                    </div>
                                    <input type="hidden" class="form-control" id="cate_status" value="'.(($value['numbcate_pin'] == 'no')?"no":"yes").'">
                                </div>';

                    $nestedData = array();
                    $nestedData[] = $value['numbcate_priority'];
                    $nestedData[] = $value['numbcate_name'];
                    $nestedData[] = $value['numbcate_title'];  
                    $nestedData[] = $display;
                    $nestedData[] = '<p class="btn-center btn-flex">
                                        <a class="btn kt:btn-primary btn-edit-category" style="color:white;"  data-id="'.$value['numbcate_id'].'" data-name="'.$value['numbcate_name'].'" onclick="showSubcategory(event,' . $value['numbcate_id'] . ')"><i class="fas fa-eye" aria-hidden="true"></i> ดูข้อมูล</a>
                                        <a class="btn kt:btn-warning" style="color:white;" onclick="editCategory(event,' . $value['numbcate_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                        <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['numbcate_id'].'" data-name="'.$value['numbcate_name'].'" onclick="delCategory(event,' . $value['numbcate_id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a>
                                     </p>';
                    
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
        case'get_numb_category':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            if($id == ""){
                echo 'ID is not invalid!';
                exit();
            }
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'priority',
                1 => 'numb_name',
                2 => 'numb_number',
                3 => 'numb_unwanted'
            );

            $sql = "SELECT * FROM berpredict_numb WHERE numb_category_id = ".$id."  "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " AND ( numb_name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR numb_number LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR numb_unwanted LIKE '%" . $requestData['search']['value'] . "%' )";
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
                    $display =  '<div class="col-md-12 btnDisplay"> 
                                    <div class="toggle-switch inTables '.(($value['numb_display'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="switch" data-id="'.$value['numb_id'].'"></span>
                                    </div>
                                    <input type="hidden" class="form-control" id="cate_status" value="'.(($value['numb_display'] == 'no')?"no":"yes").'">
                                </div>';

                    $nestedData = array();
                    $nestedData[] = $value['priority'];
                    $nestedData[] = $value['numb_name'];
                    $nestedData[] = $value['numb_number'];  
                    $nestedData[] = $value['numb_unwanted'];  
                    $nestedData[] = $display;
                    $nestedData[] = ' <p class="btn-center btn-flex"><a class="btn kt:btn-warning" style="color:white;" onclick="prepareEdit_predictnumb(event,' . $value['numb_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                     <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['numb_id'].'" data-name="'.$value['numb_name'].'" onclick="del_predictnumb(event,' . $value['numb_id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a></p>';
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
        case'prepare_edit':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM berpredict_numbcate WHERE numbcate_id = :id";
            $result = $dbcon->fetchAll($sql,[":id"=>$id]);
            $result = $result[0];
            if($result['thumbnail'] == ""){ 
                $camera_i = '<i class="fa fa-camera"></i>';
            }else{
                $thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="'.ROOT_URL.$result['thumbnail'].'"></div>';
            }
            $sql ="SELECT * FROM berpredict_numbcate_background";
            $res = $dbcon->fetchAll($sql,[]);
            if(!empty($res)){
                $options ="";
                $setColor="";
                foreach($res as $key =>$val){
                    $select = ($val['thumbnail'] == $result['background_image'])?"SELECTED":""; 
                    $options .=' <option '.$select.' value="'.$val['thumbnail'].'">'.$val['name'].'</option>';
                    if($val['thumbnail'] == $result['background_image']){
                        $setColor = $val['thumbnail'];
                    }
                }
            }
            $html =  
            '<div class="cate-blog-icon">  
                <div>
                    <label for="">Category icon [SVG,PNG,JPEG]  <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview">
                            <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                            <div class="blog-preview-add">'.$thumbnail.'</div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="'.$result['thumbnail'].'" required>  
                        <input id="swal-input2" class="swal2-input txt_abbrev" style="text-align:center;" placeholder="ชื่อย่อ" value="'.$result['numbcate_title'].'">
                    </div> 
                </div>
            </div>
            <div class="title-numb">ชื่อหมวดหมู่:</div>
            <input  class="swal2-input txt_catename" placeholder="ชื่อหมวดหมู่" value="'.$result['numbcate_name'].'">
            <div class="title-numb">เลขที่ต้องการ: 123,456,999</div>
            <textarea id="editWanted" class="swal2-input" style="height:90px;padding:1px;" >'.$result['numbcate_want'].'</textarea>
            <div class="title-numb"  >เลขที่ไม่ต้องการ: 123,456,999</div>
            <textarea id="editUnwanted"  class="swal2-input" style="height:90px;padding:1px;" >'.$result['numbcate_unwant'].'</textarea> 
            <div class="title-numb">ลำดับการแสดงผล:</div>
            <input  class="swal2-input txt_priority " value="'.$result['numbcate_priority'].'" placeholder="กรุณาใส่ตัวเลข">
            <div class="title-numb">สีพื้นหลังหมวดหมู่: </div>
            <select id="slc_color_numcate"class="swal2-input"> '.$options.' </select>
            <img class="sample_color" style="width: 50px;" src="'.ROOT_URL.$setColor.'">    ';
 
            $result['html'] = $html; 
            echo json_encode($result);

        break;  
        case'uploadImageNumbCategory':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/'; 
            $thumbnail = $myupload->upload_image_thumb($new_folder);   
            echo json_encode($thumbnail);
        break;
        case'update_numb_category':
            $abv = FILTER_VAR($_POST['abv'],FILTER_SANITIZE_MAGIC_QUOTES);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $color = FILTER_VAR($_POST['color'],FILTER_SANITIZE_MAGIC_QUOTES);
            $image = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);

            $want = FILTER_VAR($_POST['wanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $unwant = FILTER_VAR($_POST['unwanted'],FILTER_SANITIZE_MAGIC_QUOTES);

            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;

            $sql = "SELECT numbcate_priority FROM berpredict_numbcate WHERE numbcate_id = :id LIMIT 1";
            $result = $dbcon->fetchAll($sql,[':id' => $id]);
            $priorityOld = $result[0]['numbcate_priority']; 
            if($priorityNew != $priorityOld){ 
                $sql = "SELECT MAX(numbcate_priority) as max FROM berpredict_numbcate ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "numbcate_priority = (CASE WHEN :old < :new THEN numbcate_priority-1 WHEN :old > :new THEN numbcate_priority+1 END)";
                $where = "numbcate_id <> :id AND 
                            (CASE 
                                WHEN :old < :new THEN numbcate_priority > :old AND numbcate_priority <= :new 
                                WHEN :old > :new THEN numbcate_priority >= :new AND numbcate_priority < :old 
                            END)";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $prio['set'] = $dbcon->update_prepare("berpredict_numbcate",$set,$where,$value);

                $set = "numbcate_priority = :new";
                $where = "numbcate_id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $prio['update'] = $dbcon->update_prepare("berpredict_numbcate",$set,$where,$value);
            }
            $table = "berpredict_numbcate";
            $set = "numbcate_title = :title,
                    numbcate_name=:name,
                    numbcate_want=:numbcate_want,
                    numbcate_unwant=:numbcate_unwant,
                    thumbnail =:image,background_image =:color";
            $where = "numbcate_id = :id";
            $value = array(
                ":id" => $id,
                ":name" => $name,
                ":numbcate_want" => $want,
                ":numbcate_unwant" => $unwant,
                ":color" => $color,
                ":title" => $abv,
                ":image" => $image 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;
        case'delete_category_numb':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_numbcate";
            $where  = "numbcate_id = :numbcate_id";
            $val = array(
                ':numbcate_id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'update_pin_numb_category':
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_numbcate";
            $set = "numbcate_pin = :pin";
            $where = "numbcate_id = :id";
            $value = array(
                ":id" => ($id),
                ":pin" => ($pin)
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;

        case'prepare_add_numbcate':
            $sql ="SELECT max(numbcate_priority) as numb FROM berpredict_numbcate";
            $result = $dbcon->fetchObject($sql,[]);
            $camera_i = '<i class="fa fa-camera"></i>';
            $sql ="SELECT * FROM berpredict_numbcate_background";
            $res = $dbcon->fetchAll($sql,[]);
            if(!empty($res)){
                $options ="";
                $setColor="";
                foreach($res as $key =>$val){
                    if($key == 0){
                        $select = "SELECTED"; 
                        $setColor = $val['thumbnail'];
                    }else{
                        $select = ""; 
                    }
                    $options .=' <option '.$select.' value="'.$val['thumbnail'].'">'.$val['name'].'</option>';
                  
                }
            }
            $html = 
            '<div class="cate-blog-icon">  
                <div>
                    <label for="">Category icon [SVG,PNG,JPEG] <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview">
                            <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                            <div class="blog-preview-add"></div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="" required>  
                        <input id="swal-input2" class="swal2-input txt_abbrev" style="text-align:center;" placeholder="ชื่อย่อ" value="">
                    </div> 
                </div>
            </div>
            <div class="title-numb">ชื่อหมวดหมู่:</div>
            <input  class="swal2-input txt_catename" placeholder="ชื่อหมวดหมู่" value="">
            <div class="title-numb">เลขที่ต้องการ: 123,456,999</div>
            <textarea id="editWanted" class="swal2-input" style="height:90px;padding:1px;" >'.$result->numbcate_want.'</textarea>
            <div class="title-numb"  >เลขที่ไม่ต้องการ: 123,456,999</div>
            <textarea id="editUnwanted"  class="swal2-input" style="height:90px;padding:1px;" >'.$result->numbcate_unwant.'</textarea>
            <div class="title-numb">ลำดับการแสดงผล:</div>
            <input  class="swal2-input txt_priority " value="'.($result->numb+1).'" placeholder="กรุณาใส่ตัวเลข">
            <div class="title-numb">สีพื้นหลังหมวดหมู่: </div>
            <select id="slc_color_numcate"class="swal2-input"> '.$options.' </select>
            <img class="sample_color" style="width: 50px;" src="'.ROOT_URL.$setColor.'"> ';

            $result = array();
            $result['html'] = $html; 
            echo json_encode($result);
        break;
        case'insert_numb_category':
            $abv = FILTER_VAR($_POST['abv'],FILTER_SANITIZE_MAGIC_QUOTES);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $color = FILTER_VAR($_POST['color'],FILTER_SANITIZE_MAGIC_QUOTES);
            $image = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $want = FILTER_VAR($_POST['wanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $unwant = FILTER_VAR($_POST['unwanted'],FILTER_SANITIZE_MAGIC_QUOTES);

            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;

            $sql = "SELECT numbcate_priority FROM berpredict_numbcate WHERE numbcate_priority = :prioritynew ";
            $result = $dbcon->fetchObject($sql,[':prioritynew' => $priorityNew]);
            $priorityOld = $result->numbcate_priority; 
            if(!empty($priorityOld)){ 
                $sql = "SELECT MAX(numbcate_priority) as max FROM berpredict_numbcate ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "numbcate_priority = (CASE WHEN :old < :new THEN numbcate_priority-1 WHEN :old > :new THEN numbcate_priority+1 END)";
                $where = "numbcate_id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN numbcate_priority > :old AND numbcate_priority <= :new 
                    WHEN :old > :new THEN numbcate_priority >= :new AND numbcate_priority < :old 
                END)
                ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $prio['set'] = $dbcon->update_prepare("berpredict_numbcate",$set,$where,$value);
            }

            $table = "berpredict_numbcate";
            $field = "numbcate_title,numbcate_name,numbcate_want,numbcate_unwant, thumbnail, background_image,numbcate_priority,date_create";
            $key = ":numbcate_title,:numbcate_name,:numbcate_want,:numbcate_unwant,:thumbnail,:background_image,:numbcate_priority,:date_create";
            $value = array(
                ":numbcate_title" => ($abv),
                ":numbcate_name" => ($name),
                ":numbcate_want"=> $want,
                ":numbcate_unwant"=> $unwant,
                ":thumbnail" => ($image),
                ":background_image" => ($color),
                ":numbcate_priority" => $priorityNew,
                ":date_create"=> date("Y-m-d H:i:s")
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);

            
       

            echo json_encode($result);
        break;

        case'prepare_add_subcate':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT max(priority) as numb FROM berpredict_numb WHERE numb_category_id = :id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            $html = 
            ' <div class="title-numb">ชื่อหมวดย่อย:</div>
                <input  class="swal2-input txt_number" placeholder="ชื่อหมวดย่อย" value="">
                <div class="title-numb">เลขที่ต้องการ: 111,112,121 </div>
                <textarea class="swal2-input txt_wanted"></textarea>
                <div class="title-numb">เลขที่ไม่ต้องการ: 234,334,355 </div>
                <textarea class="swal2-input txt_unwanted"></textarea>
                <div class="title-numb">ลำดับการแสดงผล:</div>
                <input  class="swal2-input txt_priority " value="'.($result->numb+1).'" placeholder="กรุณาใส่ตัวเลข">
                ';
            $result = array();
            $result['html'] = $html; 
            echo json_encode($result);
        break;

        case'insert_numb_subcategory':
            $cate_id = FILTER_VAR($_POST['cate_id'],FILTER_SANITIZE_MAGIC_QUOTES);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $wanted = FILTER_VAR($_POST['wanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $unwanted = FILTER_VAR($_POST['unwanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;
            $sql = "SELECT priority FROM berpredict_numb WHERE priority = :prioritynew LIMIT 1";
            $result = $dbcon->fetchObject($sql,[':prioritynew' => $priorityNew]);
            $priorityOld = $result->numbcate_priority; 
            if(!empty($priorityOld)){ 
                $sql = "SELECT MAX(priority) as max FROM berpredict_numb ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "numb_id <> :id AND numb_category_id = '".$cate_id."' AND 
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
                $prio['set'] = $dbcon->update_prepare("berpredict_numb",$set,$where,$value);
            }

            $table = "berpredict_numb";
            $field = "numb_category_id,numb_name, numb_number, numb_unwanted,priority,date_create";
            $key = ":cate_id,:numb_name,:numb_number,:numb_unwanted,:priority,:date_create";
            $value = array(
                ":cate_id" => ($cate_id),
                ":numb_name" => ($name),
                ":numb_number" => ($wanted),
                ":numb_unwanted" => ($unwanted),
                ":priority" => $priorityNew,
                ":date_create"=> date("Y-m-d H:i:s")
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            $improve = getData::updateImprove_by_pred_id($result['insert_id']); 

            echo json_encode($improve);
        break;
        case'delete_predict_numb':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "berpredict_numb";
            $where  = "numb_id = :numb_id";
            $val = array(
                ':numb_id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;

        case'prepare_edit_subcate':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT * FROM berpredict_numb WHERE numb_id = :id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            if(!empty($result)){
                $html = 
                ' <div class="title-numb">ชื่อหมวดย่อย:</div>
                    <input  class="swal2-input txt_number" placeholder="ชื่อหมวดย่อย" value="'.$result->numb_name.'">
                    <div class="title-numb">เลขที่ต้องการ: 111,112,222 </div>
                    <textarea class="swal2-input txt_wanted">'.$result->numb_number.'</textarea>
                    <div class="title-numb">เลขที่ไม่ต้องการ: 333,334,444 </div>
                    <textarea class="swal2-input txt_unwanted">'.$result->numb_unwanted.'</textarea>
                    <div class="title-numb">ลำดับการแสดงผล:</div>
                    <input  class="swal2-input txt_priority " value="'.($result->priority).'" placeholder="กรุณาใส่ตัวเลข"> ';
                $ret = array();
                $ret['cate_id'] = $result->numb_category_id; 
                $ret['id'] = $id; 
                $ret['html'] = $html;
            }else{
                $ret['id'] = "error"; 
            }
            echo json_encode($ret);
        break;
        case'update_predict_numb':
            $cate_id = FILTER_VAR($_POST['cate_id'],FILTER_SANITIZE_NUMBER_INT);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $wanted = FILTER_VAR($_POST['wanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $unwanted = FILTER_VAR($_POST['unwanted'],FILTER_SANITIZE_MAGIC_QUOTES);
            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;

            $sql = "SELECT priority FROM berpredict_numb WHERE numb_id = :id LIMIT 1";
            $result = $dbcon->fetchAll($sql,[':id' => $id]);
            $priorityOld = $result[0]['priority']; 
            if($priorityNew != $priorityOld){ 
                $sql = "SELECT MAX(priority) as max FROM berpredict_numb WHERE numb_category_id = '".$cate_id."' ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "numb_id <> :id AND numb_category_id = '".$cate_id."' AND 
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
                $prio['set'] = $dbcon->update_prepare("berpredict_numb",$set,$where,$value);

                $set = "priority = :new";
                $where = "numb_id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $prio['update'] = $dbcon->update_prepare("berpredict_numb",$set,$where,$value);
            }
            $table = "berpredict_numb";
            $set = "numb_name=:name, numb_number=:wanted, numb_unwanted=:unwanted";
            $where = "numb_id = :id";
            $value = array(
                ":id" => ($id),
                ":name" => ($name),
                ":wanted" => ($wanted),
                ":unwanted" => ($unwanted)
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	

            $improve = getData::updateImprove_by_pred_id($cate_id); 


            echo json_encode($improve);
        break;
 
        case'testFunction':
            

        break;
      
 


	   
	} 
}

?>