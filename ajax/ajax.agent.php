<?php
session_start();  
require_once('../classes/class.protect_web.php');
ProtectWeb::admin_only();
ProtectWeb::method_post_only();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php'); 
$site_url = ROOT_URL;
$thumbgenerator =''.$site_url.'backend/classes/thumb-generator/thumb.php?src='.$site_url.'';  
$dbcon = new DBconnect();
$data = new getData();

if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();
    $output = $_SESSION['backend_language'];
    foreach($lang_config as $a){
      foreach($a as $b => $c){
        if($b == 'param'){
          if($a[$output]!='')
            $$c = $a[$output];
          else
            $$c = $a['defaults']; 
        }
      }
    }
	switch($_REQUEST['action']){
		case'getAgentEdit':

			$id = $_REQUEST['id'];
			$sql ='SELECT * FROM salesman WHERE sale_id = ?';
			$resultData = $dbcon->select_prepare($sql,[$id]);

			$sql2 ='SELECT * FROM sale_bank WHERE saleman_id = ? AND status = "publish" ORDER BY position';
			$resultBank = $dbcon->select_prepare($sql2,[$id]);
	 
			$ret = array();
			$ret['saleid'] = $resultData[0]['sale_id'];
			$ret['saleuser'] = $resultData[0]['username'];
			$ret['salename'] = $resultData[0]['name']; 
			$ret['saleemail'] = $resultData[0]['sale_email'];
			$ret['salephone'] = $resultData[0]['sale_phone'];
			$ret['facebook'] = $resultData[0]['sale_fb'];
			$ret['fbid'] 	= $resultData[0]['facebook_id'];
			$ret['instagram'] = $resultData[0]['sale_ig'];
			$ret['saleline'] = $resultData[0]['sale_line'];
			$ret['thumbnail'] = $resultData[0]['thumbnail']; 
			$ret['saleimage'] = '<div class="col-img-preview" id="col_img_preview_1" data-id="1">
									<img class="preview-img" id="preview_img_1" src="'.$thumbgenerator.$resultData[0]['thumbnail'].'&size=x200 "> 
									<a href="javascript:;" class="fa fa-trash" id="img_remove_1" data-id="1"></a>               
								 </div>  ';

	

			foreach($resultBank as $keys => $values){
 
				$ret['bank'] .= '<li class="ui-state-default"  id="'.$values['id'].'"  title="ลำดับที่ '.$values['position'].' ">
					<span class="ui-icon ui-icon-arrowthick-2-n-s">
						<label>
							 <div class="b_acct">'.$values['account_name'].'</div>
							<div ><span class="b_type">'.$values['bank_type'].':</span><span class="b_id"> '.$values['bank_id'].'</span></div> 
						</label> 
						 <span class="bankAction">
							<i class="fa fa-edit editSaleBank" data-id="'.$values['id'].'" title="แก้ไข"></i>  
							<i class="fa fa-trash delSaleBank" data-id="'.$values['id'].'" title="ลบ"></i>
						</span>		
					 </span>
				</li>
				';

			}

			echo json_encode($ret);
	 
 
		break;
		case 'get_salesList':
	 
			$requestData = $_REQUEST;
			$columns = array(
					0 => 'username',
					1 => 'name',
					2 => 'sale_link',
					3 => 'id_card' 	 		 
			);
		 
			$sql = 'SELECT * FROM salesman '; 

			if (!empty($requestData['search']['value'])) {
					$sql .= " WHERE username LIKE '" . $requestData['search']['value'] . "%' ";
					$sql .= " OR name LIKE '" . $requestData['search']['value'] . "%' ";	  
					$sql .= " OR sale_link LIKE '" . $requestData['search']['value'] . "%' ";	
					$sql .= " OR id_card LIKE '" . $requestData['search']['value'] . "%' ";	
			} 
		 
			$stmt = $dbcon->runQuery($sql);
			$stmt->execute();
			$totalData = $stmt->rowCount();
			$totalFiltered = $totalData;
	 	 	 $sql .= " ORDER BY sale_id ASC," . $columns[$requestData['order'][0]['column']] . "  " . $requestData['order'][0]['dir'];
			 $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
			$result = $dbcon->query($sql);
			$output = array();
			
		 
			if ($result) {
					foreach ($result as $value) {
						if($value['thumbnail'] == "none"){ 
							$img = 'ไม่มี';	
						}else{ 
							$img = '<a target="__blank" class="fancybox" href='.$site_url.$value['thumbnail']. '>
										<img src="'.$thumbgenerator.$value['thumbnail'].'&size=x50">
									 </a>'; 
						}
 
						// $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
						$nestedData = array();						
						$nestedData[] = $value['username'];
						$nestedData[] = $value['name'];
						$nestedData[] = $value["sale_link"];						
						$nestedData[] = '<div class="agentImages"><center>'.$img.'</center></div>';		
						$action = '<div class="box-tools tdChild btnAgentAction" style="text-align: center;">
									<div class="btn-group">
										<button type="button"  data-id="' . $value['sale_id'] . '"  class="btn btn-sm btnEditAgent">
										<i class="fa fa-pencil  text-aqua"></i></button>
										<button type="button"   data-id="' . $value['sale_id'] . '" class="btn btn-sm btnDeleteAgent ">
										<i class="fa fa-trash  text-red"></i></button>';	
						$nestedData[] = $action . '</ul>
														</div>
												</div>';
						// <li><a href="#" class="bt-view-table"   data-id="' . $value['id'] . '"  data-sales="' . $value['title'] . $value["name"] . '" ><i class="fa fa-list text-green"></i> รายการเสนอลูกค้า</a></li>
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

		//=========== จบรายชื่อ sales ================

		case 'get_sales':

			$id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
			$sql = "SELECT * FROM sales WHERE id = '" . $id . "'";
			$result = $dbcon->query($sql);
			echo json_encode(current($result));

		break;
		case 'get_sales_print':

			$id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
			$sql = "SELECT sales.*,car_brand.car_brand,province_name FROM sales
							INNER JOIN car_brand ON sales.brand = car_brand.car_brand_id
							INNER JOIN province ON sales.province = province.id
							WHERE sales.id = '" . $id . "'";

			$result = $dbcon->fetch($sql);
			$table = ' <table class="table table-bordered">
			<tbody>
					<tr>
							<td colspan="2"><center>ข้อมูลฝ่ายขาย</center></td>
					</tr>
					<tr>
							<th style="width: 150px">ชื่อ - นามสกุล</th>
							<td>' . $result['titleName'] . $result['name'] . '</td>
					</tr>
					<tr>
							<th style="">เบอร์โทร</th>
							<td>' . $result['phone'] . '</td>
					</tr>
					<tr>
							<th style="">Line ID</th>
							<td>' . $result['line'] . '</td>
					</tr>
					<tr>
							<th style="">ขายรถยี่ห้อ</th>
							<td>' . $result['car_brand'] . '</td>
					</tr>
					<tr>
							<th style="">ที่ทำงาน</th>
							<td>' . $result['workplace'] . '</td>
					</tr>

					<tr>
							<th style="">สาขา</th>
							<td>' . $result['branch'] . '</td>
					</tr>

					<tr>
							<th style="">จังหวัด</th>
							<td>' . $result['province_name'] . '</td>
					</tr>

					<tr>
							<th style="">สถานะ</th>
							<td>' . $result['status'] . '</td>
					</tr>

					<tr>
							<th style="">รูปโปรไฟล์</th>
							<td><img style="width:50%" src="'.ROOT_URL.$result['profile'] .'"></td>
					</tr>

					<tr>
						<th style="">รูปนามบัตร</th>
						<td><img style="width:50%" src="'.ROOT_URL.$result['businesscards'] .'"></td>
					</tr>

			 </tbody>
			</table>';
			echo $table;

		break;
		case 'update_sales':

			$data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);

			$setUpdate = "phone='{$data_post['phoneSale']}',
								title='{$data_post['titleNameSale']}',
								name='{$data_post['nameSale']}',
								line='{$data_post['lineSale']}',
								brand='{$data_post['saleBrand']}',
								workplace='{$data_post['nameWorkplaceSale']}',
								branch='{$data_post['workplaceBranchSale']}',
								province='{$data_post['workplaceProvinceSale']}',
								status='{$data_post['statusSale']}'";

			$where = " id = {$data_post['sales_id_edit']} ";
			$result = $dbcon->update('sales', $setUpdate, $where);
			echo json_encode($result);

		break;

		case 'delete_sales':
			$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
			$where = "id = '" . $id . "'";
			$result = $dbcon->delete('sales', $where);
			echo json_encode($result);
		break;
		case 'addMoreBank':

					$table = "sale_bank";
					$field = "account_name,bank_id,bank_type,date_created";
					$param = ":account_name,:bank_id,:bank_type,:date_created";							 
					$value = array(
								":account_name" => ProtectWeb::string($_REQUEST['accName']),
								":bank_id" => ProtectWeb::number_int($_REQUEST['bankId']),
								":bank_type" => ProtectWeb::string($_REQUEST['bankType']),
								":date_created" => date('Y-m-d H:i:s') 	 			 
					);
					$res = $dbcon->insert_prepare($table, $field,$param, $value);
					$moreBank['id'] = $res['insert_id'];
					$moreBank['data'] = '
								<li class="ui-state-default"  id="'.$res['insert_id'].'"  title="ลำดับที่ 0">
									<span class="ui-icon ui-icon-arrowthick-2-n-s">
										<label>
											 <div>'.$_REQUEST['accName'].'</div>
											<div>'.$_REQUEST['bankType'].': '.$_REQUEST['bankId'].'</div> 
										</label> 
										 <span class="bankAction">
											<i class="fa fa-edit editSaleBank" data-id="'.$res['insert_id'].'" title="แก้ไข"></i>  
											<i class="fa fa-trash delSaleBank" data-id="'.$res['insert_id'].'" title="ลบ"></i>
										</span>		
									 </span>
								</li>
							 ';

					echo json_encode($moreBank);

		 break;

					/* 
					* 	ต้องทำการ conjob
							
		case'deleteCategory':

				$table ='category'; 
				$where ='cate_id = :id ';
				$value = [
					':id' => $_REQUEST['id']
				];			
				$ret['res'] = $dbcon->delete_prepare($table, $where, $value);
				$ret['id'] = $_REQUEST['id'];
				echo json_encode($ret);

		break;

		
					*/

		 case'resetAgentForm':
				//		$sql = 'DELETE FROM sale_bank WHERE status = "pendding" AND id ';			
		
				$bank_id = $_REQUEST['agentBankId'];
					
				$table ='sale_bank'; 
			
				if(!empty($bank_id)){		
					if(!isset($bank_id[1])){ //ถ้ามี array แค่ 1 ช่องทำส่วนนี้
			 
							$where = 'status = "pendding" AND id = :id ';
							$value = [
								':id' => $bank_id[0]
							];						
							$ret['data'][0] = $bank_id[0];

					 }else{  //ถ้ามี array 2 ช่องขึ้นไปทำส่วนนี้

							$where = 'status = "pendding" AND id IN (';							 
							$value = [];
							foreach($bank_id as $keys => $acc){
								$where .= ':id'.$keys.',';	 
								$value[':id'.$keys] = $acc;		

								//ส่งค่ากลับ
								$ret['data'][$keys] = $acc;		
							}					
							//ปิด syntax	
							$where = rtrim($where,',' );
							$where .=')'; 
					 }

			 		$ret['res'] = $dbcon->delete_prepare($table, $where, $value);
			 
				 }					
				 echo json_encode($ret);
	   break;
	   case'editAgent':
	 		  $userLink = $_SERVER[HTTP_HOST];
			  $userLink .= '/'.$_REQUEST['username']; 
			  if($_REQUEST['id'] == 0){ 
				$userLink = $_SERVER[HTTP_HOST];
				$_REQUEST['username'] = 'OFFICIAL';
			  }
			   
			  $table = "salesman";
			  $set = "username = :username,name = :name,facebook_id = :fbid ,sale_ig = :sale_ig,sale_fb = :sale_fb,sale_email = :sale_email,sale_phone =:sale_phone,sale_line =:sale_line,sale_link= :sale_link";
			  $where = "sale_id = :id";
			  $value = array(
				":id" => ProtectWeb::string($_REQUEST['id']),
				":username" => ProtectWeb::string($_REQUEST['username']),
				":name" => ProtectWeb::string($_REQUEST['name']),
				":sale_email" => ProtectWeb::string($_REQUEST['email']),
				":sale_phone" => ProtectWeb::number_int($_REQUEST['phone']),		
				":sale_ig" => ProtectWeb::string($_REQUEST['instagram']),			 										 
				":sale_fb" => ProtectWeb::string($_REQUEST['facebook']),
				":fbid" => ProtectWeb::string($_REQUEST['fbid']),	
				":sale_line" => ProtectWeb::string($_REQUEST['line']),							 
				":sale_link" => ProtectWeb::string($userLink),
			  ); 
			  $ret['editAgent'] = $dbcon->update_prepare($table, $set, $where,$value);	
			  $ret['id'] = $_REQUEST['id'];
			  if(!empty($_REQUEST['moreBank'])){
					$morebank = $_REQUEST['moreBank'];
					foreach($morebank as $keys => $values){
						$table = "sale_bank";
						$set = "saleman_id = :saleman_id, position = :position , status = :status";
						$where = "id = :id";
						$value = array(
							":id" => ProtectWeb::string($values),
							":saleman_id" => ProtectWeb::string($_REQUEST['id']),
							":position" => ProtectWeb::string($keys),
							":status" => 'publish'
						); 
						$ret['moreBank'] = $dbcon->update_prepare($table, $set, $where,$value);					
					}
				}

			  echo json_encode($ret);
	   break;
	   case'addAgent':
		
		    //ทำการเก็บข้อมูล user link
				$userLink = $_SERVER[HTTP_HOST];
				$userLink .= '/'.$_REQUEST['username']; 
				$table = "salesman";
				$field = "username,name,sale_email,sale_ig,sale_fb,facebook_id,sale_phone,thumbnail,sale_line,sale_link,date_created";
				$param = ":username,:name,:sale_email,:sale_ig,:sale_fb,:fbid,:sale_phone,:thumbnail,:sale_line,:sale_link,:date_created";					 
				$value = array(
							":username" => ProtectWeb::string($_REQUEST['username']),
							":name" => ProtectWeb::string($_REQUEST['name']),
							":sale_email" => ProtectWeb::string($_REQUEST['email']),
							":sale_phone" => ProtectWeb::number_int($_REQUEST['phone']),
							":thumbnail" => 'none',	 										 
							":sale_line" => ProtectWeb::string($_REQUEST['line']),		
							":sale_fb" => ProtectWeb::string($_REQUEST['facebook']),
							":fbid" => ProtectWeb::string($_REQUEST['fbid']),
							":sale_ig" => ProtectWeb::string($_REQUEST['instagram']),					 
							":sale_link" => ProtectWeb::string($userLink),
							":date_created" => date('Y-m-d H:i:s')
				);
		 	 	$res['saleman'] = $dbcon->insert_prepare($table, $field,$param, $value);

				if(!empty($_REQUEST['moreBank'])){
					$morebank = $_REQUEST['moreBank'];
					foreach($morebank as $keys => $values){
						$table = "sale_bank";
						$set = "saleman_id = :saleman_id, position = :position , status = :status";
						$where = "id = :id AND status = 'pendding' ";
						$value = array(
							":id" => ProtectWeb::string($values),
							":saleman_id" => ProtectWeb::string($res['saleman']['insert_id']),
							":position" => ProtectWeb::string($keys),
							":status" => 'publish'
						); 
						$res['moreBank'] = $dbcon->update_prepare($table, $set, $where,$value);					
					}
				}
	 
	  		echo json_encode($res);
			
			break;
			// case 'uploadimgcontent':
			case 'uploadimgAgent':
		 
	  				#ยังไม่ได้ทดสอบ และ ทำ protect
	  				$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
	  				// $images = $data->upload_images($new_folder);
						$images = $data->upload_images_thumb($new_folder);
						$table = "salesman";
						$set = "thumbnail = '".$images['0']."' ";
						$where = "sale_id = :id ";
						$value = array(
							":id" => ProtectWeb::string($_REQUEST['id'])							
						); 
						$result = $dbcon->update_prepare($table, $set, $where,$value);	
    
					  echo json_encode($result);
		  break;
		  case'getMoreBankEdit':
				
				$id = $_REQUEST['id'];
				$sql ='SELECT * FROM sale_bank WHERE id = ?';
				$result = $dbcon->select_prepare($sql,[$id]);
				
				if(!empty($result)){
					$ret['type'] =  $result[0]['bank_type'];
					$ret['name'] = $result[0]['account_name'];	
					$ret['id'] = $result[0]['bank_id'];
				
				}else{
					$ret['message'] = 'false';
				}
	 
				echo json_encode($ret);
						
		  break;
		  case'delAgent':

	  		  $table ='salesman'; 
	  		  $where ='sale_id = :id ';
	  		  $value = [
	  			  ':id' => $_REQUEST['id']
	  		  ];			
	  		  $ret['res'] = $dbcon->delete_prepare($table, $where, $value);
	  		  
	  		  echo json_encode($ret);

		  break;
	 
		  case'delMoreBank':
		 
				$table ='sale_bank'; 
				$where ='id = :id ';
				$value = [
					':id' => $_REQUEST['id']
				];			
				$ret['res'] = $dbcon->delete_prepare($table, $where, $value);
				$ret['id'] = $_REQUEST['id'];
				echo json_encode($ret);

		  break;
		  case'editMorebank':
			 

				  $table = "sale_bank";
				  $set = "bank_type = :bank_type, account_name = :account_name , bank_id = :bank_id";
				  $where = "id = :id ";
				 
				  $value = array(
					   ":id" => ProtectWeb::string($_REQUEST['id']),
					   ":bank_type" => ProtectWeb::string($_REQUEST['type']),
					   ":account_name" => ProtectWeb::string($_REQUEST['name']),
					   ":bank_id" => ProtectWeb::string($_REQUEST['bankid'])
				  ); 
				  $res['moreBank'] = $dbcon->update_prepare($table, $set, $where,$value);	
				  
			


				  echo json_encode($res);

				 
		  break;	
		   
 
 
		
	}
}

?>