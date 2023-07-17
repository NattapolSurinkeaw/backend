<?php
session_start();  
   require_once('../classes/class.protect_web.php');
//   ProtectWeb::admin_only();
//  ProtectWeb::method_post_only();
 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.productber.php');

$site_url = ROOT_URL;
$thumbgenerator =''.$site_url.'backend/classes/thumb-generator/thumb.php?src='.$site_url.'';
 
$dbcon = new DBconnect();
$data = new getData();
$dataClass = new productber();
 
if(isset($_REQUEST['action'])) { 
	switch($_REQUEST['action']){  
			/* 
			* this case sumpage
			*/
			case'addCateProduct': 
				$sqlP = 'SELECT MAX(priority) as maxpri FROM berproduct_category';
				$resultP = $dbcon->query($sqlP);
				$maxPri  = $resultP[0]['maxpri'] + 1;  
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
						  ":bercate_id" => ProtectWeb::string($manual_id),		
						  ":bercate_name" => ProtectWeb::string($_REQUEST['name']),
						  ":status" => ProtectWeb::string($_REQUEST['status']),
						  ":bercate_display" => ProtectWeb::string($_REQUEST['display']),					
          				  ":bercate_needful" => ProtectWeb::string($_REQUEST['needful']),
						  ":bercate_needless" => ProtectWeb::string($_REQUEST['needless']),
						  ":priority" => ProtectWeb::number_int($maxPri),
 						  ":date_created" => date('Y-m-d H:i:s') 	 			 
				);
				$result = $dbcon->insert_prepare($table, $field,$param, $value);
			  
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
			case'getCateProdEdit':

		    	$id = $_REQUEST['id'];
		    	$sql ='SELECT * FROM berproduct_category WHERE bercate_id = ?';
		    	$result = $dbcon->select_prepare($sql,[$id]);        
		    	if(!empty($result)){            
         			$res['prio'] = $result[0]['priority'];
         			$res['name'] = $result[0]['bercate_name'];
         			$res['needful'] = $result[0]['bercate_needful'];
					$res['needless'] = $result[0]['bercate_needless'];
					$res['display'] = $result[0]['bercate_display'];
					$res['type'] = $result[0]['status'];
		    	}else{
		    		$res['status'] = 'error';
		    	}		 
		    	echo json_encode($res); 
			break;  
			case'editCateProduct':

				 	if($_REQUEST['id'] == 3 || $_REQUEST['id'] == 4){

						$table = "berproduct_category";
						$set = "bercate_name = :bercate_name,bercate_display = :bercate_display,bercate_needful = :bercate_needful,bercate_needless = :bercate_needless ";
						$where = "bercate_id = :bercate_id";
						$value = array(	 
							":bercate_id" => ProtectWeb::number_int($_REQUEST['id']),
							":bercate_name" => ProtectWeb::string($_REQUEST['name']),
							":bercate_display" => ProtectWeb::string($_REQUEST['display']),
							":bercate_needful" => ProtectWeb::string($_REQUEST['needful']),				 										 
							":bercate_needless" => ProtectWeb::string($_REQUEST['needless'])	
						); 
						$res['editCateProd'] = $dbcon->update_prepare($table, $set, $where,$value);	

					 }else{

				
				 
						if(!empty($_REQUEST['cate_id']) && isset($_REQUEST['cate_id'])){
							$chkEmp = 'SELECT bercate_id FROM berproduct_category WHERE bercate_id = '.$_REQUEST['cate_id'].' ';
							$resEmp = $dbcon->select_prepare($chkEmp,[]);
						
							if(!empty($resEmp)){
							
								$chkId = 'SELECT max(bercate_id) as numb FROM berproduct_category WHERE status = "no" LIMIT 0,1';
								$reschk = $dbcon->select_prepare($chkId,[]); 
								$setId = $reschk[0]['numb'] +1; 
								$table = "berproduct_category"; 
								$set =  'bercate_id =  '.$setId.' ';
								$where = 'bercate_id = :id ';
								$value = array(			 
									":id" => ProtectWeb::number_int($_REQUEST['cate_id']) 
								);  
								$res['editCate_id'] = $dbcon->update_prepare($table, $set, $where,$value);   	 
							} 

							$bercate_change = $_REQUEST['cate_id'];
						}else{
							$bercate_change = $_REQUEST['id'];
						}
	
	
						$table = "berproduct_category";
						$set = "bercate_id = ".$bercate_change." ,bercate_name = :bercate_name,status = :status,bercate_display = :bercate_display,bercate_needful = :bercate_needful,bercate_needless = :bercate_needless ";
						$where = "bercate_id = :bercate_id";
						$value = array(		
						":status" => ProtectWeb::string($_REQUEST['status']),	 
						":bercate_id" => ProtectWeb::number_int($_REQUEST['id']),
						":bercate_name" => ProtectWeb::string($_REQUEST['name']),
						":bercate_display" => ProtectWeb::string($_REQUEST['display']),
						":bercate_needful" => ProtectWeb::string($_REQUEST['needful']),				 										 
						":bercate_needless" => ProtectWeb::string($_REQUEST['needless'])	
						); 
						$res['editCateProd'] = $dbcon->update_prepare($table, $set, $where,$value);	
						$chkPrio = 'false'; 
						if(isset($_REQUEST['prio']) && $_REQUEST['prio'] != $_REQUEST['old'] && $res['editCateProd']['status'] == 200){ 
							$chkPrio = 'true';				 
							$getpost['new'] = $_REQUEST['prio'];  
							$getpost['id'] = $_REQUEST['id'];
							$getpost['old'] = $_REQUEST['old']; 					
							$res['priority'] =  $dataClass->priorityControl($getpost); 
						}  
						if($_REQUEST['status'] == 'yes'){	   // this section 
							/*  clear หมวดหมู่นั้นๆ ก่อนจะอัพเดท  */			 
							$table = "berproduct";
							$_REQUEST['id'] = trim($_REQUEST['id']);
							$set = "product_category = replace(product_category, ',".$_REQUEST['id'].",' , ',')"; 
							$where = 'product_category LIKE  "%,'.$_REQUEST['id'].',%"  AND default_cate NOT LIKE "%,'.$_REQUEST['id'].',%" ';
							$value = array(			 
							":product_cateId" => ProtectWeb::number_int($_REQUEST['id']) 
							);  
							$res['editResetProductCate'] = $dbcon->update_prepare($table, $set, $where,$value); 
							/*  update จำนวนสินค้าในหมวด */				
							$getpost['order'] = $_REQUEST['id']; 
							$ret['auto'] =  $dataClass->getProductByCategory($getpost);   
						}else{ 
							$ret['manual'] =  $dataClass->getProductByCategoryManual($getpost);   
						}

						$ret['cate'] =  $dataClass->updateCategorySpace(); 
						if($res['getproduct']['result']['status']  != 200){ 
								$ret['status'] = '400';
						}else{  
							$ret['status'] = '200';	 
							if(isset($res['getproduct']['total'])){
								$ret['id'] = $res['getproduct']['id'];
								$ret['total'] = $res['getproduct']['total'];
							}	 					
						} 
					}
					
						if($res['editCateProd']['status'] != 200){
							$ret['status'] = '400';
							$ret['case'] = 'product';
					
						}else if($res['priority']['status'] != 200 && $chkPrio == 'true'){				 
							$ret['status'] = '400';
							$ret['case'] ='priority';
						}else{
							$ret['status'] = '200';
						} 
				

					echo json_encode($ret);
	 		break;


			case'editDataProphecy':

				$_REQUEST['old'] = trim($_REQUEST['old']);
				$_REQUEST['num'] = trim($_REQUEST['num']); 
				$table = "predict_prophecy";	 
				$where = "prophecy_id = :prophecy_id";		
				if($_REQUEST['old'] != $_REQUEST['num']){
 
					$set = "prophecy_id = :prophecy_id, prophecy_numb = :prophecy_numb, prophecy_percent = :prophecy_percent, prophecy_color = :prophecy_color, prophecy_desc = :prophecy_desc";
					$value = array(		
					  ":prophecy_id" => ProtectWeb::number_int($_REQUEST['id']),
					  ":prophecy_numb" => ProtectWeb::number_int($_REQUEST['num']),
				      ":prophecy_desc" => ProtectWeb::string($_REQUEST['desc']),
				      ":prophecy_percent" => ProtectWeb::number_float($_REQUEST['percent']),
				      ":prophecy_color" => ProtectWeb::string($_REQUEST['color'])
					); 
				}else{
				
					$set = "prophecy_id = :prophecy_id, prophecy_percent = :prophecy_percent, prophecy_color = :prophecy_color, prophecy_desc = :prophecy_desc";
					$value = array(		
					  ":prophecy_id" => ProtectWeb::number_int($_REQUEST['id']),
					  ":prophecy_desc" => ProtectWeb::string($_REQUEST['desc']),
					  ":prophecy_percent" => ProtectWeb::number_float($_REQUEST['percent']),
					  ":prophecy_color" => ProtectWeb::string($_REQUEST['color'])
					); 
				}
	 
				$res = $dbcon->update_prepare($table, $set, $where,$value);
	 
				if($res['status'] != 200){ 
					$ret['status'] = 'error'; 
					$ret['message'] .= 'มีหมายเลขเดิมอยู่แล้ว : ';
					$ret['message'] .= $_REQUEST['num'];
				}else{  $ret['status'] = '200'; }
			
				 echo json_encode($ret);

			break;
			case'delDataCateProd':
					$table ='berproduct_category'; 
					$where ='bercate_id = :bercate_id ';
					$value = [
						':bercate_id' =>  ProtectWeb::number_int($_REQUEST['id']),
					];			
					$res = $dbcon->delete_prepare($table, $where, $value);
					if($res['status'] != 200){ 
						$ret['status'] = 'error'; 		 
          			 }else{  $ret['status'] = '200'; }
          				 
					echo json_encode($ret);
			break;
			case 'get_productcategory':
	 
					$requestData = $_REQUEST;
					$columns = array(  
					 	 0 => 'priority', 
					 	 1 => 'bercate_name', 				
					 	 2 => 'bercate_id', 	
					 	 3 => 'bercate_display', 					          	 
					 );
						
					$sql = 'SELECT * FROM berproduct_category WHERE bercate_id != 999999 '; 
					$requestData['search']['value'] = trim($requestData['search']['value']);
					if (!empty($requestData['search']['value'])) { 
						$sql .= " AND bercate_id  LIKE '" . $requestData['search']['value'] . "%' ";
						$sql .= " OR bercate_name  LIKE '" . $requestData['search']['value'] . "%' ";
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
						if($value['status'] == 'no'){ 	$color = '<span style="color:red;">[ '.$value['bercate_id'].' ] manual</span>';	$nodel = 'yes';	}else{ $nodel = 'yes'; $color = '<span>[ '.$value['bercate_id'].' ]</span>'; }
														 
						if($value['bercate_display'] == 'yes'){ 	$disp = '<span>แสดง</span>'; }else{ $disp = '<span style="color:red;">ซ่อน</span>'; }
						// $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
						$nestedData = array();						
						$nestedData[] = $value['priority'];		
						$nestedData[] = '<span class="showProduct namesearch"  data-id="'.$value['bercate_id'].'">'.trim($value['bercate_name']).'</span><span style="color:red;"> ['.$value['bercate_total'].']</span>';
						$nestedData[] =  $color;	
						$nestedData[] =  $disp;
						$nestedData[] = '<span class="showProduct fasearch"  data-id="'.$value['bercate_id'].'" ><i class="fa fa-search"></i></span>';
					 // $nestedData[] = $value['prophecy_percent'].'%';			
						// $nestedData[] = '<div class="agentImages"><center>'.$img.'</center></div>';	

						$action = '<div class="box-tools tdChild btnAgentAction" style="text-align: center;" data-id="'.$value['bercate_id'].'">
									<div class="btn-group">
										<button type="button"  data-id="'.$value['bercate_id'].'"  class="btn btn-sm editCateProductBer">
										<i class="fa fa-pencil  text-aqua"></i></button>
										<button type="button"   data-del="'.$nodel.'" data-id="' . $value['bercate_id'] . '" class="btn btn-sm btnDeleteCateProductBer ">
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
		 

 
		//===========  sales ================

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

			case'addAgent':
				
					//ทำการเก็บข้อมูล user link
						$userLink = $_SERVER[HTTP_HOST];
						$userLink .= '/'.$_REQUEST['username'];

						$table = "salesman";
						$field = "username,name,sale_email,sale_phone,thumbnail,sale_line,sale_link,date_created";
						$param = ":username,:name,:sale_email,:sale_phone,:thumbnail,:sale_line,:sale_link,:date_created";					 
						$value = array(
									":username" => ProtectWeb::string($_REQUEST['username']),
									":name" => ProtectWeb::string($_REQUEST['name']),
									":sale_email" => ProtectWeb::string($_REQUEST['email']),
									":sale_phone" => ProtectWeb::number_int($_REQUEST['phone']),
									":thumbnail" => 'none',	 										 
									":sale_line" => ProtectWeb::string($_REQUEST['line']),							 
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
			$value = [ ':id' => $_REQUEST['id'] ];			
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
			$where = "id = :id AND status = 'publish' ";
			$value = array(
				":id" => ProtectWeb::string($_REQUEST['id']),
				":bank_type" => ProtectWeb::string($_REQUEST['type']),
				":account_name" => ProtectWeb::string($_REQUEST['name']), 
				":bank_id" => ProtectWeb::string($_REQUEST['bankid'])
			); 
			$res['moreBank'] = $dbcon->update_prepare($table, $set, $where,$value);		
		
			echo json_encode($res);
		
		break;	
   
		 // อัพไฟล์ csv .. xlsx .. เพื่อแปลงแล้วเก็บข้อมูลลง db
		 // <form action="upload.php" method="post" enctype="multipart/form-data">
		 // Select image to upload:
		 // <input type="file" name="fileToUpload" id="fileToUpload">
		 // <input type="submit" value="Upload Image" name="submit">
		        // </form>
		case'uploadExcelFile': 
				//   print_r($_FILES['file_upload']); 
			
				$target_dir = PATH_UPLOAD."excel/";
				$target_file = $target_dir . basename($_FILES["file_upload"]['name']);  
				unlink($target_file);  
	
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["file_upload"]["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						echo "ประเภทไฟล์ไม่ถูกต้อง";
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($target_file)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				} 
				// Check file size
				if ($_FILES["file_upload"]["size"] > 20000000) {
					echo "Sorry, your file is too large. please contact wynnsoft";
					$uploadOk = 0;
				} 
				// Allow certain file formats 
				$fileType = explode('.',$imageFileType);	 
				if($fileType[0] != "xlsx" && $fileType[0] != "xls") {
					echo "Sorry,this files is not allowed.";
					$uploadOk = 0;
				} 
				// Check if $uploadOk is set to 0 by an error 
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file 
				} else {
					if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
						echo "The file ". basename( $_FILES["file_upload"]["name"]). " has been uploaded.";
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				} 
				 
				/*
				*delete data before new upload 
				*/
				$table ='berproduct'; 
				$where ='product_id != :product_id ';
				$value = [
					':product_id' => '9999999999999'
				];			
				$ret['res'] = $dbcon->delete_prepare($table, $where, $value);			 
				$extract = $data->readExcel($target_file); 
		 break;
	 
		// case'updatesDataUpload':

		// 	$getpost['order'] = '';  
		//  	$res['manual'] =  $dataClass->getProductByCategoryManual($getpost);   
		// 	$res['auto'] =  $dataClass->getProductByCategory($getpost);  
  
		// 	/* จัดการหมวดหมู่ lover and xxyy */
		// 	$table ='berproduct_alover'; 
		// 	$where ='status = :param ';
		// 	$value = [
		// 		':param' => 'auto'
		// 	];			
		// 	$res['del'] = $dbcon->delete_prepare($table, $where, $value);

		// 	$table = "berproduct";
		// 	$set = "product_category = REPLACE(product_category, ',3,', :param ) ";
		// 	$where = " product_category LIKE  '%,3,%'  ";
		// 	$value = array( 
		// 		":param" => ''	
		// 	); 
		// 	$res['reset3'] = $dbcon->update_prepare($table, $set, $where,$value);

		// 	$table = "berproduct";
		// 	$set2 = "product_category = REPLACE(product_category, ',4,', :param ) "; 
		// 	$where = " product_category LIKE  '%,4,%' ";
		// 	$value = array( 
		// 		":param" => ''	
		// 	); 
		// 	$res['reset4'] = $dbcon->update_prepare($table, $set2, $where,$value);
												
		// 	$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 7) as pp 
		// 			FROM berproduct WHERE  product_category  NOT LIKE "%,0,%" AND product_sold NOT LIKE "%y%" ORDER BY product_id ASC ';
		// 		$resSrc = $dbcon->query($sql); 
				
		// 	/* ********* section 1 แปลงข้อมูลเข้าแต่ละ function ************ */   
		// 	#set array 
		// 	$condition1 = array(); 
		// 	$condition2 = array(); 
		// 	$condition3 = array();  
		// 	$condition4 = array(); 
		// 	$condition5 = array(); 
		// 	$condition6 = array(); 
		// 	$condition7 = array();
		// 	$condition8 = array();
		// 	$condition9 = array(); 
		// 	$product_id = '';
		// 	foreach($resSrc as $keys => $vals){  
		// 		#case1 
		// 		$con1 = substr($vals['pp'],0,-1);  
		// 		if(!empty($condition1[$con1])){  
		// 			$len1 = count($condition1[$con1]); 
		// 		}else{ 
		// 			$len1  = 0;
		// 		}
		
		// 		$condition1[$con1][$len1]['id'] = $vals['product_id'];
		// 		$condition1[$con1][$len1]['price'] = $vals['product_price'];
		// 		$condition1[$con1][$len1]['numb'] = $vals['product_phone'];  
		// 		$condition1[$con1][$len1]['pp'] = $vals['pp'];   
		// 		$condition1[$con1][$len1]['value'] = $con1;  
		// 		#case2    
		// 		$con2 = substr($vals['pp'],1);  
		// 		if(!empty($condition2[$con2])){  
		// 			$len2 = count($condition2[$con2]); 
		// 		}else{
		// 			$len2  = 0;
		// 		}
		// 		$condition2[$con2][$len2]['id'] = $vals['product_id'];
		// 		$condition2[$con2][$len2]['price'] = $vals['product_price'];
		// 		$condition2[$con2][$len2]['numb'] = $vals['product_phone'];  
		// 		$condition2[$con2][$len2]['pp'] = $vals['pp'];   
		// 		$condition2[$con2][$len2]['value'] = $con2; 
				
		// 		#case3 
		// 		$con3 = substr($vals['pp'],2);  
		// 		if(!empty($condition3[$con3])){  
		// 			$len3 = count($condition3[$con3]); 
		// 		}else{
		// 			$len3  = 0;
		// 		}
		// 		$condition3[$con3][$len3]['id'] = $vals['product_id'];
		// 		$condition3[$con3][$len3]['price'] = $vals['product_price'];
		// 		$condition3[$con3][$len3]['numb'] = $vals['product_phone'];  
		// 		$condition3[$con3][$len3]['pp'] = $vals['pp'];   
		// 		$condition3[$con3][$len3]['value'] = $con3; 

		// 		#case4 
		// 		$con4 = substr($vals['pp'],0,-2);
		// 		if(!empty($condition4[$con4])){  
		// 			$len4 = count($condition4[$con4]); 
		// 		}else{
		// 			$len4  = 0;
		// 		}
		// 		$condition4[$con4][$len4]['id'] = $vals['product_id'];
		// 		$condition4[$con4][$len4]['price'] = $vals['product_price'];
		// 		$condition4[$con4][$len4]['numb'] = $vals['product_phone'];  
		// 		$condition4[$con4][$len4]['pp'] = $vals['pp'];  
		// 		$condition4[$con4][$len4]['value'] = $con4; 
				
		// 		#case5 
		// 		$con5 = $vals['pp'];  
		// 		if(!empty($condition5[$con5])){
		// 			$len5 = count($condition5[$con5]); 
		// 		}else{
		// 			$len5  = 0;
		// 		}
		// 		$condition5[$con5][$len5]['id'] = $vals['product_id'];
		// 		$condition5[$con5][$len5]['price'] = $vals['product_price'];
		// 		$condition5[$con5][$len5]['numb'] = $vals['product_phone'];  
		// 		$condition5[$con5][$len5]['pp'] = $vals['pp'];  
		// 		$condition5[$con5][$len5]['value'] = $con5;  
				
		// 		#case6 xyxy
		// 		$numbKey6 = array();
		// 		$numChk6 = array();   
		// 		$limit6 = 6;    
		// 		$position6 = -7;  
		// 		for($i=0; $i < $limit6 ;$i++){ 
		// 				$round =  $position6 + $i; 
		// 				$numb = substr($vals['pp'],$round,2); 
		// 				$numbKey6[$i] = $numb;   
		// 			}  

		// 		if(substr($numbKey6[0],0,1) != substr($numbKey6[0],1,1)   &&  substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)  ){
		// 			if($numbKey6[0] == $numbKey6[2] ){
		// 				$numChk6['value'] =  $numbKey6[0].$numbKey6[2];
		// 				} 
		// 		}

		// 		if(substr($numbKey6[1],0,1) != substr($numbKey6[1],1,1)   &&  substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)  ){
		// 			if($numbKey6[1] == $numbKey6[3]){
		// 				$numChk6['value'] =  $numbKey6[1].$numbKey6[3];
		// 				}
		// 		}
		// 		if(substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)   &&  substr($numbKey6[4],0,1) != substr($numbKey6[4],1,1)  ){
		// 			if($numbKey6[2] == $numbKey6[4]){
		// 				$numChk6['value'] =  $numbKey6[2].$numbKey6[4];
		// 				}
		// 			}

		// 			if(substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)   &&  substr($numbKey6[5],0,1) != substr($numbKey6[5],1,1)  ){
		// 			if($numbKey6[3] == $numbKey6[5]){
		// 				$numChk6['value'] =  $numbKey6[3].$numbKey6[5];
		// 				}
		// 			} 
		// 		if(!empty($numChk6)){  
		// 			$numChk6['numb'] =  $vals['product_phone'];
		// 			$numChk6['pp'] =  $vals['pp'];
		// 			$numChk6['id'] =  $vals['product_id'];  
		// 			$condition6[$vals['product_id']][$vals['pp']]  = $numChk6;  
		// 			$product_id .= $vals['product_id'].',';
		// 		}   

		// 		#case7 xxyy 
		// 		$numbKey7 = array();
		// 		$numChk7 = array();   
		// 		$limit7 = 6;    
		// 		$position7 = -7;  
		// 		for($i=0; $i < $limit7 ;$i++){ 
		// 				$round =  $position7 + $i; 
		// 				$numb = substr($vals['pp'],$round,2); 
		// 				$numbKey7[$i] = $numb;   
		// 			}  
					
		// 		if(substr($numbKey7[0],0,1) == substr($numbKey7[0],1,1)   &&  substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)  ){
		// 			$numChk7['value'] =  $numbKey7[0].$numbKey7[2];
		// 		}

		// 		if(substr($numbKey7[1],0,1) == substr($numbKey7[1],1,1)   &&  substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)  ){
		// 			$numChk7['value'] =  $numbKey7[1].$numbKey7[3];
		// 		}
		// 		if(substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)   &&  substr($numbKey7[4],0,1) == substr($numbKey7[4],1,1)  ){
		// 			$numChk7['value'] =  $numbKey7[2].$numbKey7[4];
		// 			}

		// 			if(substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)   &&  substr($numbKey7[5],0,1) == substr($numbKey7[5],1,1)  ){
		// 			$numChk7['value'] =  $numbKey7[3].$numbKey7[5];
		// 			} 

				
		// 		if(!empty($numChk7)){  
		// 			$numChk7['numb'] =  $vals['product_phone'];
		// 			$numChk7['pp'] =  $vals['pp'];
		// 			$numChk7['id'] =  $vals['product_id'];  
		// 			$condition7[$vals['product_id']][$vals['pp']]  = $numChk7;  
		// 			$product_id .= $vals['product_id'].',';
		// 		} 
			 
		// 		#case8  
		// 		$numbKey8 = array();
		// 		$numChk8 = array();   
		// 		$limit8 = 5;    
		// 		$position8 = -7;  
		// 		for($i=0; $i < $limit8 ;$i++){ 
		// 				$round =  $position8 + $i; 
		// 				$numb = substr($vals['pp'],$round,3); 
		// 				$numbKey8[$i] = $numb;  
		// 		}  
		// 		if( $numbKey8[0] == $numbKey8[3] ){  		$numChk8['value'] =  $numbKey8[0].$numbKey8[0]; 
		// 		}else if( $numbKey8[0] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[4].$numbKey8[4]; 
		// 		}else if($numbKey8[1] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[1].$numbKey8[1]; 
		// 		}else if($numbKey8[3] == $numbKey8[0]){ 	$numChk8['value'] =  $numbKey8[3].$numbKey8[3];
		// 		} 
		// 		if(!empty($numChk8)){  
		// 			$numChk8['numb'] =  $vals['product_phone'];
		// 			$numChk8['pp'] =  $vals['pp'];
		// 			$numChk8['id'] =  $vals['product_id'];  
		// 			$condition8[$vals['product_id']][$vals['pp']]  = $numChk8;  
		// 			$product_id .= $vals['product_id'].',';
		// 		}   
				
		// 		$numbKey9 = array();
		// 		$numChk9 = array();   
		// 		$limit9 = 7;    
		// 		$position9 = -7;  
		// 		for($i=0; $i < $limit9 ;$i++){ 
		// 				$round =  $position9 + $i; 
		// 				$numb = substr($vals['pp'],$round,1); 
		// 				$numbKey9[$i] = $numb;   
		// 			}  
				
		// 		if( $numbKey9[0]  ==  $numbKey9[1] && $numbKey9[1] == $numbKey9[2] ){ 
		// 			$numChk9['value'] =  $numbKey9[0].$numbKey9[1].$numbKey9[2]; 
		// 		}

		// 		if( $numbKey9[1]  ==  $numbKey9[2] && $numbKey9[2] == $numbKey9[3] ){ 
		// 			$numChk9['value'] =  $numbKey9[1].$numbKey9[2].$numbKey9[3]; 
		// 		}

		// 		if( $numbKey9[2]  ==  $numbKey9[3] && $numbKey9[3] == $numbKey9[4] ){ 
		// 			$numChk9['value'] =  $numbKey9[2].$numbKey9[3].$numbKey9[4]; 
		// 		} 

		// 		if( $numbKey9[3]  ==  $numbKey9[4] && $numbKey9[4] == $numbKey9[5] ){ 
		// 			$numChk9['value'] =  $numbKey9[3].$numbKey9[4].$numbKey9[5]; 
		// 		}

		// 		if( $numbKey9[4]  ==  $numbKey9[5] && $numbKey9[5] == $numbKey9[6] ){ 
		// 			$numChk9['value'] =  $numbKey9[4].$numbKey9[5].$numbKey9[6]; 
		// 		} 
		// 		if(!empty($numChk9)){ 
						
		// 			$numChk9['numb'] =  $vals['product_phone'];
		// 			$numChk9['pp'] =  $vals['pp'];
		// 			$numChk9['id'] =  $vals['product_id'];  
		// 			$condition9[$vals['product_id']][$vals['pp']]  = $numChk9;  
		// 			$product_id .= $vals['product_id'].',';
		// 		} 

			
		// 		}  
			
		
		// 	/* ********* section 2 ส่วนของการแปลงข้อมูล ************ */ 
		// 	#function1   =  xxxxxx1 & xxxxxx2  
		// 	$resCondi1 = array();
		// 		#จัดการข้อมูลที่น้อยกว่า 1  
		// 	foreach($condition1 as $index => $valz ){  
		// 		$len = count($valz); 
		// 		if($len  < 2){
		// 			unset($condition1[$index]);
		// 			}else{  
		// 					$price = 0;
		// 					foreach($valz as $key => $value){  
		// 						if($value['price'] >  $price){
		// 							$price =  $value['price'];
		// 						} 
		// 					} 
		// 					foreach($valz as $key => $value){   
		// 						$condition1[$index][$key]['price'] = $price;    
		// 					}   
		// 			} 
		// 	} 
		// 	$resCondi1 = $condition1;  #####

		// 	#function2 =  1xxxxxx &  2xxxxxx   
		// 	$resCondi2 = array();
		// 	#จัดการข้อมูลที่น้อยกว่า 1 
		// 	foreach($condition2 as $index => $valz ){   
		// 		$len = count($valz); 
		// 		if($len  < 2){
		// 			unset($condition2[$index]);
		// 			}else{  
		// 					$price = 0;
		// 					foreach($valz as $key => $value){  
		// 						if($value['price'] >  $price){
		// 							$price =  $value['price'];
		// 						} 
		// 					} 
		// 					foreach($valz as $key => $value){  
		// 						$condition2[$index][$key]['price'] = $price;    
		// 					}   
		// 			} 
		// 	} 
		// 		$resCondi2 = $condition2; #####

				 
		// 	#function3  =  12xxxxx & 21xxxxx   
		// 	$resCondi3 = array();
		// 	#จัดการข้อมูลที่น้อยกว่า 1 
		// 	foreach($condition3 as $index => $valz ){   
		// 		$len = count($valz); 
		// 		if($len  < 2){
		// 			unset($condition3[$index]);
		// 			}else{  
		// 					$price = 0;
		// 					foreach($valz as $key => $value){  
		// 						if($value['price'] >  $price){
		// 							$price =  $value['price'];
		// 						} 
		// 					} 
		// 					foreach($valz as $key => $value){  
		// 						$condition3[$index][$key]['dprice'] = $price;    
		// 					}   
		// 			}  
		// 		}   

				
		// 	#ทำการกรองข้อมูล ด้านหน้า  12xxxxx = 21xxxxx 
		// 	foreach($condition3 as $keys => $valp){   
		// 		foreach($valp as $key => $gg){ 
		// 			$lastKey = $key -1; 
		// 			$value['st'] = substr($gg['pp'],0,1);
		// 			$value['nd'] = substr($gg['pp'],1,1); 
		// 			$resc = $value['nd'].''.$value['st'].''.$gg['value'];  
		// 			foreach($condition3[$gg['value']] as $index => $aa ){   
		// 				if($aa['pp'] == $resc && $gg['id'] != $aa['id'] ){ 
		// 					$resCondi3[$gg['value']][$key]['id'] = $gg['id'];
		// 					$resCondi3[$gg['value']][$key]['numb'] = $gg['numb'];  
		// 						$resCondi3[$gg['value']][$key]['value'] = $gg['value'];
		// 					$resCondi3[$gg['value']][$key]['oldprice'] = $aa['price']; 
		// 					$resCondi3[$gg['value']][$key]['price'] = $gg['dprice']; 
		// 					$resCondi3[$gg['value']][$key]['pp'] = $gg['pp']; 
		// 					$resCondi3[$gg['value']][$key]['flip'] = $resc; 
		// 				} 
		// 			}   
		// 		}   
		// 		if(!empty($resCondi3[$gg['value']])	){
		// 			if(count($resCondi3[$gg['value']]) < 2){  // มีข้อมูลซ้ำ
		// 				unset($resCondi3[$keys]); 
		// 			} 
		// 		} 
		// 		} #######

		// 	#function4  =  xxxxx12 & xxxxx21  
		// 	$resCondi4 = array();
		// 	#จัดการข้อมูลที่น้อยกว่า 1 
		// 	foreach($condition4 as $index => $valz ){   
		// 		$len = count($valz); 
		// 		if($len  < 2){
		// 			unset($condition4[$index]);
		// 			} 
		// 		}  
		// 	#ทำการกรองข้อมูล ด้านหน้า  12 = 21  
		// 	foreach($condition4 as $keys => $valp){    
		// 		foreach($valp as $key => $gg){ 
		// 			$rescArr= array();
		// 			$lastKey = $key -1; 
		// 			$value['st'] = substr($gg['pp'],5,1);
		// 			$value['nd'] = substr($gg['pp'],6,1); 
		// 			$resc =  $gg['value'].$value['nd'].$value['st'];  
		// 			$rescArr[] = substr($gg['pp'],5,1);
		// 			$rescArr[] = substr($gg['pp'],6,1); 
		// 			sort($rescArr);
		// 			$sort =  $rescArr[0].$rescArr[1];   
		// 			foreach($condition4[$gg['value']] as $index => $aa ){   
		// 				if($gg['id'] != $aa['id']){   
		// 					if( $aa['pp'] == $resc ){    
		// 						$oldSort = $sort;  
		// 						$resCondi4[$gg['value']][$sort][$key]['id'] = $gg['id'];
		// 						$resCondi4[$gg['value']][$sort][$key]['numb'] = $gg['numb']; 
		// 						$resCondi4[$gg['value']][$sort][$key]['value'] = $gg['value'];  
		// 						$resCondi4[$gg['value']][$sort][$key]['pp'] = $gg['pp']; 
		// 						$resCondi4[$gg['value']][$sort][$key]['flip'] = $resc; 
		// 						$resCondi4[$gg['value']][$sort][$key]['port'] = $sort;  
		// 						$resCondi4[$gg['value']][$sort][$key]['oldPrice'] = $aa['price']; 
		// 					}   
		// 				} 
		// 				}   
		// 			}    
		// 		if(!empty($resCondi4[$gg['value']][$sort])){
		// 			if(count($resCondi4[$gg['value']][$sort]) < 2){  
		// 				unset($resCondi4[$gg['value']][$sort]);
		// 			}  
		// 		}  
		// 	} #####  
		// 	foreach($resCondi4 as $index => $value){
		// 		foreach($value as $key => $val){  
		// 			$price = 0;  
		// 			foreach($resCondi4[$index][$key] as $keyPrice => $var){ 
		// 				if($var['oldPrice'] >  $price){
		// 					$price =  $var['oldPrice'];
		// 				}   
		// 			}   
		// 			foreach($resCondi4[$index][$key] as $keyId => $var){  
		// 					$resCondi4[$index][$key][$keyId]['price'] =  $price;
		// 			} 
		// 		} 
		// 	}


		// 	#function5  =  xxxxxxx & xxxxxxx   
		// 	$resCondi5 = array();
		// 	#จัดการข้อมูลที่น้อยกว่า 1 
		// 	foreach($condition5 as $index => $valz ){ 
		// 		$len = count($valz);    
		// 		if($len  < 2){    
		// 			unset($condition5[$index]);
		// 			}else{  
		// 					$price = 0;
		// 					foreach($valz as $key => $value){  
		// 						if($value['price'] >  $price){
		// 							$price =  $value['price'];
		// 						} 
		// 					} 
		// 					foreach($valz as $key => $value){  
		// 						$condition5[$index][$key]['price'] = $price;    
		// 					}   
		// 			}
		// 	}  
		// 	#ทำการกรองข้อมูล  x = x 
		// 	foreach($condition5 as $keys => $valp){   
		// 		foreach($valp as $key => $gg){  
		// 			$lastKey = $key -1;  
		// 			$resc =  $gg['value'];  
					
		// 			foreach($condition5[$gg['value']] as $index => $aa ){   
		// 				if($aa['pp'] == $resc && $gg['id'] != $aa['id'] ){ 
		// 					$resCondi5[$gg['value']][$key]['id'] = $gg['id']; 
		// 						$resCondi5[$gg['value']][$key]['numb'] = $gg['numb'];    
		// 						$resCondi5[$gg['value']][$key]['price'] = $gg['price'];  
		// 					$resCondi5[$gg['value']][$key]['val'] = $gg['value'];  
		// 					$resCondi5[$gg['value']][$key]['pp'] = $gg['pp'];   
		// 					$resCondi5[$gg['value']][$key]['value'] = $gg['pp'];  
		// 				} 
		// 			}  
		// 		}  
		// 		if(!empty($resCondi5[$gg['value']])){
		// 			if(count($resCondi5[$gg['value']]) < 2){  
		// 				unset($resCondi5[$keys]); 
		// 			} 
		// 		} 
					
		// 	}  #####

			
		// 	/*******************  insert function section **************************/
		// 	/* category number 3  */ 
		// 	$idArr = array();
		// 	$id='';
		// 	#case1 
		// 	foreach($resCondi1 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr[$kk['id']])){
		// 				$idArr[$kk['id']] = $kk['id']; 
		// 			} 
		// 			$category = 3;
		// 			$func_id = 1;
		// 			$group = $kk['value'];
		// 			$sort_by = 0;
		// 			$priority = $ii; 
		// 				$price =  $kk['price'];
		// 			$number = $kk['numb']; 
		// 			$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 											'love_priority' =>ProtectWeb::string($priority),
		// 											'group_price' =>ProtectWeb::string($price),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}

			
		// 	#case2
		// 	foreach($resCondi2 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr[$kk['id']])){
		// 				$idArr[$kk['id']] = $kk['id']; 
		// 			}
		// 			$category = 3;
		// 			$func_id = 2;
		// 			$group = $kk['value'];
		// 			$sort_by = 0;
		// 					$priority = $ii;
		// 					$price = $kk['price'];
		// 			$number = $kk['numb']; 
		// 			$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 											'love_priority' =>ProtectWeb::string($priority),
		// 											'group_price' =>ProtectWeb::string($price),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}


		// 	#case3
		// 	foreach($resCondi3 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr[$kk['id']])){
		// 				$idArr[$kk['id']] = $kk['id']; 
		// 			} 
		// 			$category = 3;
		// 			$func_id = 3;
		// 			$group = $kk['value']; 
		// 			$sort_by = 0;
		// 					$priority = $ii;
		// 					$price = $kk['price'];
		// 			$number = $kk['numb']; 
		// 			$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 											'love_priority' =>ProtectWeb::string($priority),
		// 											'group_price' =>ProtectWeb::string($price),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}


		// 	#case4
		// 	foreach($resCondi4 as $keys => $vals){  
		// 		foreach( $vals as $cc => $kk){ 
		// 			foreach( $kk as $tt => $mm){
		// 				if(!isset($idArr[$mm['id']])){
		// 					$idArr[$mm['id']] = $mm['id']; 
		// 				}
		// 				$category = 3;
		// 				$func_id = 4;
		// 				$group = $mm['value'];
		// 				$sort_by = $mm['port'];
		// 						$priority = 0;
		// 						$price = $mm['price'];
		// 				$number = $mm['numb']; 
		// 				$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 												'love_priority' =>ProtectWeb::string($priority),
		// 												'group_price' =>ProtectWeb::string($price),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			}
		// 		} 
		// 	}


		// 	#case5
		// 	foreach($resCondi5 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr[$kk['id']])){
		// 				$idArr[$kk['id']] = $kk['id']; 
		// 			}
		// 			$category = 3;
		// 			$func_id = 5;
		// 			$group = $kk['value'];
		// 			$sort_by = 0;
		// 					$priority = $ii;
		// 					$price = $kk['price'];
		// 			$number = $kk['numb']; 
		// 			$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 											'love_priority' =>ProtectWeb::string($priority),
		// 											'group_price' =>ProtectWeb::string($price),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}

		// 	if(!empty($idArr)){ 
		// 		$idIn =''; 
		// 		foreach($idArr as $vals){ 
		// 			$idIn .= $vals.',';
		// 		} 
		// 		$idIn = substr($idIn,0,-1); 
		// 		$table = "berproduct";
		// 		$set = "product_category = CONCAT(product_category,:cate_id )";
		// 		$where = " product_id IN (".$idIn.") ";
		// 		$value = array(
		// 			":cate_id" => ',3,' 
		// 		); 
		// 		$res['cate3'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 		$res['lover3'] = $dbcon->multiInsert('berproduct_alover',$listBer); 
		// 		$idArr = array_unique($idArr);
		// 		$table = "berproduct_category";
		// 		$set = "bercate_total =  :cate_id ";
		// 		$where = "bercate_id = 3 ";
		// 		$value = array(
		// 			":cate_id" => count($idArr)
		// 		); 
		// 		$res['count3'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 	}

		// 	/* category 4  */ 
		// 	$idArr2 = array();  

		// 	#case6 xyxy
		// 	#function6 = xxx1212 
		// 	$resCondi6 = array();
		// 	$resCondi6 = $condition6;
		// 	foreach($resCondi6 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr2[$kk['id']])){
		// 				$idArr2[$kk['id']] = $kk['id']; 
		// 			}  
		// 			$category = 4;
		// 			$func_id = 6;
		// 			$group = $kk['value'];
		// 			$sort_by = $kk['id'];
		// 			$priority = $ii;
		// 			$number = $kk['numb']; 
		// 			$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 								'love_priority' =>ProtectWeb::string($priority),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}	

			
		// 	#case7 xxyy
		// 	#function7 = xxx1122 
		// 	$resCondi7 = array();
		// 	$resCondi7 = $condition7;
		// 	foreach($resCondi7 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr2[$kk['id']])){
		// 				$idArr2[$kk['id']] = $kk['id']; 
		// 			}  
		// 			$category = 4;
		// 			$func_id = 7;
		// 			$group = $kk['value'];
		// 			$sort_by = $kk['id'];
		// 			$priority = $ii;
		// 			$number = $kk['numb']; 
		// 			$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 								'love_priority' =>ProtectWeb::string($priority),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}
			
		// 	#case8
		// 	#function8 = 123x123 
		// 	$resCondi8 = array();
		// 	$resCondi8 = $condition8;
		// 	foreach($resCondi8 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr2[$kk['id']])){
		// 				$idArr2[$kk['id']] = $kk['id']; 
		// 			}  
		// 			$category = 4;
		// 			$func_id = 8;
		// 			$group = $kk['value'];
		// 			$sort_by = $kk['id'];
		// 			$priority = $ii;
		// 			$number = $kk['numb']; 
		// 			$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 								'love_priority' =>ProtectWeb::string($priority),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	} 
		// 	#case9
		// 	#function9 = xxxx111 
		// 	$resCondi9 = array();
		// 	$resCondi9 = $condition9;
		// 	foreach($resCondi9 as $keys => $vals){ 
		// 		$ii= 0;
		// 		foreach( $vals as $cc => $kk){ 
		// 			if(!isset($idArr2[$kk['id']])){
		// 				$idArr2[$kk['id']] = $kk['id']; 
		// 			}  
		// 			$category = 4;
		// 			$func_id = 9;
		// 			$group = $kk['value'];
		// 			$sort_by = $kk['id'];
		// 			$priority = $ii;
		// 			$number = $kk['numb']; 
		// 			$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 								'func_id' => ProtectWeb::string($func_id),
		// 								'lover_group' => ProtectWeb::string($group),
		// 								'sort' => ProtectWeb::string($sort_by),
		// 								'love_priority' =>ProtectWeb::string($priority),
		// 								'product_list' => ProtectWeb::string($number),
		// 								'status' => 'auto'	  
		// 							);   
		// 			$ii++;
		// 		} 
		// 	}

		// 	if(!empty($idArr2)){ 
		// 		$idIn2 = '';
		// 		foreach($idArr2 as $vals){ 
		// 			$idIn2 .= $vals.',';
		// 		}  
		// 		$idIn2 = substr($idIn2,0,-1); 
		// 		$table = "berproduct";
		// 		$set = "product_category = CONCAT(product_category,:cate_id )";
		// 		$where = "product_id IN (".$idIn2.")";
		// 		$value = array(
		// 			":cate_id" => ',4,' 
		// 		); 

		// 		$idArr2 = array_unique($idArr2);

		// 		$res['cate4'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 		$res['lover4'] = $dbcon->multiInsert('berproduct_alover',$listBer2); 
		// 		$table = "berproduct_category";
		// 		$set = "bercate_total =  :cate_id ";
		// 		$where = "bercate_id = 4 ";
		// 		$value = array(
		// 			":cate_id" => count($idArr2)
		// 		); 
		// 		$res['count4'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 	}	

			 
		// 	echo json_encode($res); 


		// 	/*
		// 		$ret['tripRepeat'] =  $dataClass->updateCateTripleRepeat();  //เบอร์ห่าม  285285
		// 		$ret['xyxy'] =  $dataClass->updateCateXYxy();  // xyxy	2828
		// 		$ret['xxyy'] =  $dataClass->updateCateXXyy();  // xxyy    2288
		// 		$ret['quad'] =  $dataClass->updateCateQuadNumb();  // เบอร์ตอง เบอร์โฟร์ 222 2222 
		// 		$ret['triple'] =  $dataClass->updateCateTripleNumb(); 
		// 	*/ 
	 

	 	// break;
		    	  
	/*	--------------------------------------product section------------------------------------------------*/ 
	case 'get_productData':  
		
	    /* แปลงค่าจาก ชื่อเครือข่ายเป็นรูปภาพ */
		$networkSQL = 'SELECT * FROM bernetwork';
		$networkRES = $dbcon->query($networkSQL);
		$network = array();
		foreach($networkRES as $keys => $values){
			$network[$values['network_name']] = $values['thumbnail'];
		}	
 
		$requestData = $_REQUEST;
		$columns = array(  
				0 => 'product_id', 
				1 => 'product_phone', 				
				2 => 'product_sumber', 	
				3 => 'product_network', 		
				4 => 'product_price', 
				5 => 'product_ads', 
				6 => 'product_sold', 				          	 
				7 => 'product_pin',
				8 => 'product_hot'			
			);
		if($_REQUEST['id'] == 0){
			$sql = 'SELECT * FROM berproduct WHERE display = "yes" ';
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
				 
				 /* get network thumbnail */
				 /* ถ้าไม่มีเครือข่ายให้แสดงผลว่าไม่มีเครือข่าย */
				 if($value['product_network'] != ''){
					/* ถ้าไม่มีรูปภาพให้แสดงผลเป็นชื่อเครือข่าย */
					if($network[$value['product_network']] != ''){
						$value['product_network'] = $thumbgenerator.$network[$value['product_network']]."&size=x20";				 
						$imgNetwork = '<img src="'.$value['product_network'].'" alt="ภาพประกอบเครือข่าย'.$value['product_network'].'"> ';
					}else{
						$imgNetwork =  $value['product_network'];
					}					 
				 }else{
					$imgNetwork =  '';
				 }
				// $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
				$nestedData = array();						
				$nestedData[] = $value['product_id'];		
				$nestedData[] = $value['product_phone'];	
				$nestedData[] = $value['product_sumber'];	
				$nestedData[] = $imgNetwork;
				$nestedData[] = $value['product_price'];		
			
				if($value['product_ads'] == 'yes' || $value['product_ads'] == 'y'){
					$nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
				}else{
					$nestedData[] = '';
				}				
	 
			 	if($value['product_sold'] == 'yes' || $value['product_sold'] == 'y'){
					$nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
				}else{
					$nestedData[] = '';
				}				
 
			  	if($value['product_pin'] == 'yes' || $value['product_pin'] == 'y'){
					$nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
				}else{
					$nestedData[] = '';
				}
 
				if($value['product_hot'] == 'yes' || $value['product_hot'] == 'y'){
					$nestedData[] = '<i class="fas fa-check" style="color:green;"></i>';
				}else{
					$nestedData[] = '';
				}

				//  $nestedData[] = $value['prophecy_percent'].'%';			
				// $nestedData[] = '<div class="agentImages"><center>'.$img.'</center></div>';		
				$action = '<div class="box-tools tdChild btnAgentAction" style="text-align: center;">
							<div class="btn-group">
								<button type="button"  data-id="' . $value['product_id'] . '"  class="btn btn-sm editProductBer">
								<i class="fa fa-pencil  text-aqua"></i></button>
								<button type="button"   data-id="' . $value['product_id'] . '" class="btn btn-sm btnDeleteProductBer ">
								<i class="fa fa-trash  text-red"></i></button>';	
				$nestedData[] = $action . '</ul>
												</div>
										</div>';
				// <li><a href="#" class="bt-view-table"   data-id="' . $value['id'] . '"  data-sales="' . $value['title'] . $value["name"] . '" ><i class="fa fa-list text-green"></i> รายการเสนอลูกค้า</a></li>
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
	case'get_network2':  
		
		$sql ='SELECT * FROM bernetwork  ORDER by network_id';
		$result = $dbcon->query($sql);

			foreach($result as $keys => $value){
				$res['option'] .= '<option  value="'.$value['network_id'].'">'.$value['network_name'].' </option>	';
			}
		echo json_encode($res);
	break;		
	case'getProductsEdit':
						
		$id = $_REQUEST['id'];
		$sql ='SELECT * FROM berproduct WHERE product_id = ?';
		$result = $dbcon->select_prepare($sql,[$id]);        
		if(!empty($result)){  
			
			 $res['network'] = $result[0]['product_network'];			
			 $res['phone'] = $result[0]['product_phone'];
			 $res['price'] = $result[0]['product_price'];
			 $res['sum'] = $result[0]['product_sumber'];
		 	if($result[0]['product_ads'] == 'y' || $result[0]['product_ads'] == 'yes'){ 
 		 		$res['ads'] = 'yes'; 
		 	}else{  
		 		$res['ads'] = 'no';	 
			}
			  
		 	if($result[0]['product_pin'] == 'y' || $result[0]['product_pin'] == 'yes'){ 
				$res['pin'] = 'yes'; 
		 	}else{  
		 	   $res['pin'] = 'no';	 
			 }
			 
		   	if($result[0]['product_sold'] == 'y' || $result[0]['product_sold'] == 'yes'){ 
				$res['sold'] = 'yes'; 
		 	}else{  
		 	   $res['sold'] = 'no';	 
			 }

			if($result[0]['product_hot'] == 'y' || $result[0]['product_hot'] == 'yes'){ 
				$res['hot'] = 'yes'; 
		 	}else{  
		 	   $res['hot'] = 'no';	 
		 	}

		}else{
			$res['status'] = 'error';
		}		
		
		echo json_encode($res);

	break;  
	case'addProduct':
 
	 		$table = "berproduct";
	 		$field = "product_phone,product_sumber,product_network,product_price,product_ads,product_pin,product_sold,product_hot";
	 		$param = ":product_phone,:product_sumber,:product_network,:product_price,:product_ads,:product_pin,:product_sold,:product_hot";				 
	 		$value = array(			
						":product_phone" => ProtectWeb::number_int($_REQUEST['number']),
						":product_sumber" => ProtectWeb::number_int($_REQUEST['sum']),
						":product_network" => ProtectWeb::string($_REQUEST['network']),
						":product_price" => ProtectWeb::string($_REQUEST['price']),
						":product_ads" => ProtectWeb::string($_REQUEST['ads']),
						":product_pin" => ProtectWeb::string($_REQUEST['sold']),
						":product_sold" => ProtectWeb::string($_REQUEST['hot']),				 										 
						":product_hot" => ProtectWeb::string($_REQUEST['pin'])			 
	 			);
			 $result['add'] = $dbcon->insert_prepare($table, $field,$param, $value);

			 if($result['add']['status'] == 200){
				$ret['status'] = 200;
				$ret['message'] = 'OK';
			}else{
				$ret['status'] = 'error';
			} 
			echo json_encode($ret);		  
	break;
	case'editProduct':
	
			$table = "berproduct";
			$set = " product_phone = :product_phone, 
					 product_sumber = :product_sumber,
					 product_network = :product_network, 
					 product_price = :product_price,  
					 product_ads = :product_ads,
					 product_pin = :product_pin,
					 product_sold = :product_sold,
					 product_hot = :product_hot
				   ";	 
			$where = "product_id = :product_id";
			$value = array(	
		      ":product_id" => ProtectWeb::number_int($_REQUEST['id']),		 
			  ":product_phone" => ProtectWeb::number_int($_REQUEST['number']),
			  ":product_sumber" => ProtectWeb::number_int($_REQUEST['sum']),
			  ":product_network" => ProtectWeb::string($_REQUEST['network']),
			  ":product_price" => ProtectWeb::string($_REQUEST['price']),
			  ":product_ads" => ProtectWeb::string($_REQUEST['ads']),
			  ":product_pin" => ProtectWeb::string($_REQUEST['pin']),
			  ":product_sold" => ProtectWeb::string($_REQUEST['sold']),				 										 
			  ":product_hot" => ProtectWeb::string($_REQUEST['hot'])	
			); 
			$res['edit'] = $dbcon->update_prepare($table, $set, $where,$value);	
		
			if($res['edit']['status'] == 200){
				$ret['status'] = 200;
				$ret['message'] = 'OK';
			}else{
				$ret['status'] = 'error';
			}
			
			echo json_encode($ret);
	break;

	case'delProductData':
			$table ='berproduct'; 
			$where ='product_id = :product_id ';
			$value = [
				':product_id' =>  ProtectWeb::number_int($_REQUEST['id']),
			];			
			$res = $dbcon->delete_prepare($table, $where, $value);
			if($res['status'] != 200){ 
				$ret['status'] = 'error'; 		 
			 }else{  
				 $ret['status'] = '200';
			} 
			echo json_encode($ret); 
	break;
	case'get_dataNetwork':
	 
		 $sql ='SELECT * FROM bernetwork WHERE network_id = ? LIMIT 0,1';
		 $result = $dbcon->select_prepare($sql,[$_REQUEST['id']]); 
		 if(!empty($result)){			 
			$ret['status'] = 'OK';
			$images = $site_url.$result[0]['thumbnail'];

			if($result[0]['thumbnail'] != ''){
			     $ret['img'] =  ' <div class="col-img-preview" id="col_img_preview_1" data-id="1">                    
									<img class="preview-img" id="preview_img_1" src="'.$images.'">                    
			   						<a href="javascript:;" class="fa fa-trash" id="img_remove_1" data-id="1"></a>                
								</div>';
				 $ret['check'] = $images;	
				 $ret['display'] = $result[0]['display'];	
			}else{
				$ret['img'] = '';
				$ret['check'] = 'false';	
			} 			 		 
		}else{			
			$ret['status'] = 'error';
		} 
		 echo json_encode($ret);  
		 
	break;

	case'add_network':
		 $id = $_REQUEST['id'] + 1;
	     $table = "bernetwork";
	     $field = "network_id,network_name";
	     $param = ":id,:network";							 
	     $value = array(
				":id" => ProtectWeb::number_int($id),
	            ":network" => ProtectWeb::string($_REQUEST['name'])
	     );
		 $res = $dbcon->insert_prepare($table, $field,$param, $value); 
		 if($res['status'] != 200){ 
			$ret['status'] = 'error'; 		 
		 }else{  
			 $ret['status'] = '200';
			 $ret['name'] = $_REQUEST['name'];
			 $ret['dataNetwork'] = $dataClass->getSlcNetwork();  
		 } 
		 echo json_encode($ret); 
	break;
	case'getNetwork':
			$ret['dataNetwork'] = $dataClass->getSlcNetwork();  
			echo json_encode($ret); 
	break;
	case'del_network':

			$table ='bernetwork'; 
			$where ='network_id = :id ';
			$value = [
				':id' =>  ProtectWeb::number_int($_REQUEST['id']),
			];			
			$res = $dbcon->delete_prepare($table, $where, $value);
		 
			if($res['status'] != 200){ 
				$ret['status'] = 'error'; 		 
			 }else{  
				 $ret['status'] = '200';
			 }

			 echo json_encode($ret);

	break;
	case 'uploadimgNetwork':
		 
	 	  #ยังไม่ได้ทดสอบ และ ทำ protect
	 	  $new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
	 	  // $images = $data->upload_images($new_folder);
		  $images = $data->upload_images_thumb($new_folder);
		  $table = "bernetwork";
		  $set = "thumbnail = '".$images['0']."' ";
		  $where = "network_id = :id ";
		  $value = array(
			  ":id" => ProtectWeb::string($_REQUEST['id'])							
		  ); 
		  $result = $dbcon->update_prepare($table, $set, $where,$value);	


		  echo json_encode($result);
	break; 
 
	case'updateNetworkStatus':

			$table = "bernetwork";
			$set = "display = :display";
			$where = "network_id = :network_id";
			$value = array(		
			  ":network_id" => ProtectWeb::number_int($_REQUEST['id']), 	 
			  ":display" => ProtectWeb::string($_REQUEST['display'])
			); 
			$res = $dbcon->update_prepare($table, $set, $where,$value);	 
			$ret['status']  = $res['status'];
		 
			echo json_encode($ret); 
	break; 
	case'getExcelAdsExport':
			global $site_url;
			$sql ='SELECT * ,(SELECT predict_desc FROM predictsum where predict_numb = product_sumber ) as pdesc 
							,(SELECT ad_image FROM banner WHERE ad_id = "6") as logo 
				   FROM berproduct WHERE product_sold != "yes" AND ( product_ads = "yes" OR product_ads = "y") ';
 
			$result = $dbcon->query($sql);   
			if(!empty($result)){ 
				$itemArr = array(); 
				foreach($result as $key =>$value){
					$itemArr[$key]['id'] =  $value['product_id'];
					$itemArr[$key]['number'] =  'เบอร์ '.$value['product_phone'].' '.$value['product_network'].'  ';
					$itemArr[$key]['desc'] =  ' '.$value['pdesc'].' '.$value['product_phone'].' เครือข่าย '.$value['product_network'].' ผลรวม '.$value['product_sumber'].'  ';
					$itemArr[$key]['link'] =  ' '.$site_url.'รายละเอียดสินค้า?id='.$value['product_id'].'	';
					$itemArr[$key]['condition'] =  'ใหม่';
					$itemArr[$key]['price'] =  $value['product_price'];
					$itemArr[$key]['status'] =  'มีสินค้าพร้อมจำหน่าย';
					$itemArr[$key]['imglink'] =  ''.$site_url.''.$value['logo'].'';
					$itemArr[$key]['glin'] =  '';
					$itemArr[$key]['mpn'] =  $value['product_phone'];
					$itemArr[$key]['network'] =  $value['product_network'];
					$itemArr[$key]['googlecateid'] =  '267';
				}
			} 
			if(empty($result)){
				$ret['status'] = 'false';
			}else{ 
				$link = $data->writeExcelADS($itemArr);  
				$ret['status'] = 'true'; 
				$ret['src'] = $site_url.'/backend/classes/'.$link;
			}
  
			echo json_encode($ret); 

	 break;

	 case'addPriceRate':
 
			$table = "berpricerate";
			$field = "price_min,price_max,priority,pricerate_value";
			$param = ":price_min,:price_max,:priority,:pricerate_value";						 
			$value = array(	
					":pricerate_value" => ProtectWeb::string($_REQUEST['display']),		
					":price_min" => ProtectWeb::string($_REQUEST['min']),
					":price_max" => ProtectWeb::string($_REQUEST['max']),		 
					":priority" => ProtectWeb::string('999999999')			 
			);
			$result = $dbcon->insert_prepare($table, $field,$param, $value);
 
			if($_REQUEST['prio'] == '0'){
				$sql = 'SELECT max(priority) as maxnum FROM berpricerate ';
				$result = $dbcon->query($sql);  
				$prio = $result[0]['maxnum'] +1; 
			}else{
				$prio = $_REQUEST['prio'];
			}
			  	 
			$set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
			$where = "pricerate_id <> :id AND (CASE WHEN :old < :new THEN priority > :old AND priority <= :new WHEN :old > :new THEN priority >= :new AND priority < :old END)";
			$value = array(
				":id" => $result['insert_id'], 
				":old" => '999999999',
				":new" => $prio
			);
			$r1 = $dbcon->update_prepare("berpricerate",$set,$where,$value); 
			$set = "priority = :new";
			$where = "pricerate_id  = :id";
			$value = array(
				":id" => $result['insert_id'],
				":new" => $prio 
			);
			$r2 = $dbcon->update_prepare("berpricerate",$set,$where,$value);
			  
			echo json_encode($r2);
	 break;
	 case'editPriceRate':
	 	
			$post_id = $_REQUEST['id'];
			$priority_old = $_REQUEST['old'];
			$post_priority_new = $_REQUEST['prio']; 
	 
			if( !empty($post_id) && !empty($post_priority_new) ){  
				#ดูว่ามันมีค่า priority เท่าเดิมหรือไม่
				$sqlss = 'SELECT * FROM berpricerate WHERE priority = '.$post_priority_new.' AND pricerate_id = "'.$post_id.'" ';
				$ress = $dbcon->query($sqlss);
				$table = "berpricerate";  
				if(empty($ress)){ 
					$set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
					$where = "pricerate_id <> :id AND (CASE WHEN :old < :new THEN priority > :old AND priority <= :new WHEN :old > :new THEN priority >= :new AND priority < :old END)";
					$value = array(
						":id" => $post_id, 
						":old" => $priority_old,
						":new" => $post_priority_new
					);
					$r1 = $dbcon->update_prepare($table,$set,$where,$value); 

					$set = "priority = :new";
					$where = "pricerate_id = :id";
					$value = array(
						":id" => $post_id,
						":new" => $post_priority_new
					);
					$r2 = $dbcon->update_prepare($table,$set,$where,$value);
					 
				} 
					
					$sets = "pricerate_value = :display , price_min = :price_min,price_max = :price_max ";
					$where = "pricerate_id = :id ";
					$value = array(
						":id" => ProtectWeb::string($post_id),
						":display" => ProtectWeb::string($_REQUEST['display']),
						":price_min" => ProtectWeb::string($_REQUEST['min']), 
						":price_max" => ProtectWeb::string($_REQUEST['max']) 
					);
					$ret = $dbcon->update_prepare($table,$sets,$where,$value);
				     
			}else{
				$ret = array([
					'event'  => 'check_request-',
					'status' => '400',
					'message' => 'request_error'
				]);
			
			} 

			echo json_encode($ret);  
	 break;
	 case'getpricerate':

			$sql ='SELECT * FROM berpricerate ORDER BY priority ASC ';
			$result = $dbcon->query($sql);
			 
			if(!empty($result)){
				foreach($result as $key => $val){
				  $ret['html'] .= '
				  	<div class="row-list-body">
						<span>'.$val['priority'].'</span>
						<span>'.$val['pricerate_value'].'</span>
						<span>'.$val['price_min'].'</span>
						<span>'.$val['price_max'].'</span>
						<span class="btnActionRate">
							<span class="editRate" data-toggle="modal" data-target="#priceRate" data-pos="'.$val['priority'].'"  data-id="'.$val['pricerate_id'].'" >แก้ไข</span>
							<span class="delRate" data-id="'.$val['pricerate_id'].'" >ลบ</span>
						</span>
					</div>
				   ';
				}  
			}else{
				$ret['status'] = 'error';
			}

			echo json_encode($ret);

		break;
		case'delPriceRate':
				$table ='berpricerate'; 
				$where ='pricerate_id = :pricerate_id ';
				$value = [
					':pricerate_id' =>  ProtectWeb::number_int($_REQUEST['id']),
				];			
				$res = $dbcon->delete_prepare($table, $where, $value); 
				if($res['status'] != 200){ 
					$ret['status'] = 'error'; 		 
				}else{  
					$ret['status'] = '200'; 
				} 
				echo json_encode($ret);
		break;
		case'getPriceRateById':
			$sql ='SELECT * FROM berpricerate WHERE pricerate_id = ? LIMIT 0,1';
			$result = $dbcon->select_prepare($sql,[$_REQUEST['id']]); 
			if(!empty($result)){
				$ret['id'] = $result[0]['pricerate_id'];
				$ret['display'] = $result[0]['pricerate_value'];
				$ret['min'] = $result[0]['price_min'];
				$ret['max'] = $result[0]['price_max'];
				$ret['priority'] = $result[0]['priority'];
			}else{
				$ret['status'] = 400;
			}  
			echo json_encode($ret); 
		break;
		
 
		//  case'actionUploadLoverMode':
		
				 
		// 		/* จัดการหมวดหมู่ lover and xxyy  */
		// 		$table ='berproduct_alover'; 
		// 		$where ='status = :param ';
		// 		$value = [
		// 			':param' => 'auto'
		// 		];			
		// 		$res['del'] = $dbcon->delete_prepare($table, $where, $value);

		// 		$table = "berproduct";
		// 		$set = "product_category = REPLACE(product_category, ',3,', :param ) ";
		// 		$where = " product_category LIKE  '%,3,%'  ";
		// 		$value = array( 
		// 			":param" => ''	
		// 		); 
		// 		$res['reset3'] = $dbcon->update_prepare($table, $set, $where,$value);

		// 		$table = "berproduct";
		// 		$set2 = "product_category = REPLACE(product_category, ',4,', :param ) "; 
		// 		$where = " product_category LIKE  '%,4,%' ";
		// 		$value = array( 
		// 			":param" => ''	
		// 		); 
		// 		$res['reset4'] = $dbcon->update_prepare($table, $set2, $where,$value);
													
		// 		$sql = 'SELECT product_id,product_category,product_phone,product_sumber,product_network,product_price,product_sold,MID(product_phone,4, 7) as pp 
		// 				FROM berproduct WHERE  product_category  NOT LIKE "%,0,%" AND product_sold NOT LIKE "%y%" ORDER BY product_id ASC ';
		// 			$resSrc = $dbcon->query($sql); 
					
		// 		/* ********* section 1 แปลงข้อมูลเข้าแต่ละ function ************ */   
		// 		#set array 
		// 		$condition1 = array(); 
		// 		$condition2 = array(); 
		// 		$condition3 = array();  
		// 		$condition4 = array(); 
		// 		$condition5 = array(); 
		// 		$condition6 = array(); 
		// 		$condition7 = array();
		// 		$condition8 = array();
		// 		$condition9 = array(); 
		// 		$product_id = '';
		// 		foreach($resSrc as $keys => $vals){  
		// 			#case1 
		// 			$con1 = substr($vals['pp'],0,-1);  
		// 			if(!empty($condition1[$con1])){  
		// 				$len1 = count($condition1[$con1]); 
		// 			}else{ 
		// 				$len1  = 0;
		// 			}
			
		// 			$condition1[$con1][$len1]['id'] = $vals['product_id'];
		// 			$condition1[$con1][$len1]['price'] = $vals['product_price'];
		// 			$condition1[$con1][$len1]['numb'] = $vals['product_phone'];  
		// 			$condition1[$con1][$len1]['pp'] = $vals['pp'];   
		// 			$condition1[$con1][$len1]['value'] = $con1;  
		// 			#case2    
		// 			$con2 = substr($vals['pp'],1);  
		// 			if(!empty($condition2[$con2])){  
		// 				$len2 = count($condition2[$con2]); 
		// 			}else{
		// 				$len2  = 0;
		// 			}
		// 			$condition2[$con2][$len2]['id'] = $vals['product_id'];
		// 			$condition2[$con2][$len2]['price'] = $vals['product_price'];
		// 			$condition2[$con2][$len2]['numb'] = $vals['product_phone'];  
		// 			$condition2[$con2][$len2]['pp'] = $vals['pp'];   
		// 			$condition2[$con2][$len2]['value'] = $con2; 
					
		// 			#case3 
		// 			$con3 = substr($vals['pp'],2);  
		// 			if(!empty($condition3[$con3])){  
		// 				$len3 = count($condition3[$con3]); 
		// 			}else{
		// 				$len3  = 0;
		// 			}
		// 			$condition3[$con3][$len3]['id'] = $vals['product_id'];
		// 			$condition3[$con3][$len3]['price'] = $vals['product_price'];
		// 			$condition3[$con3][$len3]['numb'] = $vals['product_phone'];  
		// 			$condition3[$con3][$len3]['pp'] = $vals['pp'];   
		// 			$condition3[$con3][$len3]['value'] = $con3; 

		// 			#case4 
		// 			$con4 = substr($vals['pp'],0,-2);
		// 			if(!empty($condition4[$con4])){  
		// 				$len4 = count($condition4[$con4]); 
		// 			}else{
		// 				$len4  = 0;
		// 			}
		// 			$condition4[$con4][$len4]['id'] = $vals['product_id'];
		// 			$condition4[$con4][$len4]['price'] = $vals['product_price'];
		// 			$condition4[$con4][$len4]['numb'] = $vals['product_phone'];  
		// 			$condition4[$con4][$len4]['pp'] = $vals['pp'];  
		// 			$condition4[$con4][$len4]['value'] = $con4; 
					
		// 			#case5 
		// 			$con5 = $vals['pp'];  
		// 			if(!empty($condition5[$con5])){
		// 				$len5 = count($condition5[$con5]); 
		// 			}else{
		// 				$len5  = 0;
		// 			}
		// 			$condition5[$con5][$len5]['id'] = $vals['product_id'];
		// 			$condition5[$con5][$len5]['price'] = $vals['product_price'];
		// 			$condition5[$con5][$len5]['numb'] = $vals['product_phone'];  
		// 			$condition5[$con5][$len5]['pp'] = $vals['pp'];  
		// 			$condition5[$con5][$len5]['value'] = $con5;  
					
		// 			#case6 xyxy
		// 			$numbKey6 = array();
		// 			$numChk6 = array();   
		// 			$limit6 = 6;    
		// 			$position6 = -7;  
		// 			for($i=0; $i < $limit6 ;$i++){ 
		// 					$round =  $position6 + $i; 
		// 					$numb = substr($vals['pp'],$round,2); 
		// 					$numbKey6[$i] = $numb;   
		// 				}  

		// 			if(substr($numbKey6[0],0,1) != substr($numbKey6[0],1,1)   &&  substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)  ){
		// 				if($numbKey6[0] == $numbKey6[2] ){
		// 					$numChk6['value'] =  $numbKey6[0].$numbKey6[2];
		// 					} 
		// 			}

		// 			if(substr($numbKey6[1],0,1) != substr($numbKey6[1],1,1)   &&  substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)  ){
		// 				if($numbKey6[1] == $numbKey6[3]){
		// 					$numChk6['value'] =  $numbKey6[1].$numbKey6[3];
		// 					}
		// 			}
		// 			if(substr($numbKey6[2],0,1) != substr($numbKey6[2],1,1)   &&  substr($numbKey6[4],0,1) != substr($numbKey6[4],1,1)  ){
		// 				if($numbKey6[2] == $numbKey6[4]){
		// 					$numChk6['value'] =  $numbKey6[2].$numbKey6[4];
		// 					}
		// 				}

		// 				if(substr($numbKey6[3],0,1) != substr($numbKey6[3],1,1)   &&  substr($numbKey6[5],0,1) != substr($numbKey6[5],1,1)  ){
		// 				if($numbKey6[3] == $numbKey6[5]){
		// 					$numChk6['value'] =  $numbKey6[3].$numbKey6[5];
		// 					}
		// 				} 
		// 			if(!empty($numChk6)){  
		// 				$numChk6['numb'] =  $vals['product_phone'];
		// 				$numChk6['pp'] =  $vals['pp'];
		// 				$numChk6['id'] =  $vals['product_id'];  
		// 				$condition6[$vals['product_id']][$vals['pp']]  = $numChk6;  
		// 				$product_id .= $vals['product_id'].',';
		// 			}   

		// 			#case7 xxyy 
		// 			$numbKey7 = array();
		// 			$numChk7 = array();   
		// 			$limit7 = 6;    
		// 			$position7 = -7;  
		// 			for($i=0; $i < $limit7 ;$i++){ 
		// 					$round =  $position7 + $i; 
		// 					$numb = substr($vals['pp'],$round,2); 
		// 					$numbKey7[$i] = $numb;   
		// 				}  
						
		// 			if(substr($numbKey7[0],0,1) == substr($numbKey7[0],1,1)   &&  substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)  ){
		// 				$numChk7['value'] =  $numbKey7[0].$numbKey7[2];
		// 			}

		// 			if(substr($numbKey7[1],0,1) == substr($numbKey7[1],1,1)   &&  substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)  ){
		// 				$numChk7['value'] =  $numbKey7[1].$numbKey7[3];
		// 			}
		// 			if(substr($numbKey7[2],0,1) == substr($numbKey7[2],1,1)   &&  substr($numbKey7[4],0,1) == substr($numbKey7[4],1,1)  ){
		// 				$numChk7['value'] =  $numbKey7[2].$numbKey7[4];
		// 				}

		// 				if(substr($numbKey7[3],0,1) == substr($numbKey7[3],1,1)   &&  substr($numbKey7[5],0,1) == substr($numbKey7[5],1,1)  ){
		// 				$numChk7['value'] =  $numbKey7[3].$numbKey7[5];
		// 				} 

					
		// 			if(!empty($numChk7)){  
		// 				$numChk7['numb'] =  $vals['product_phone'];
		// 				$numChk7['pp'] =  $vals['pp'];
		// 				$numChk7['id'] =  $vals['product_id'];  
		// 				$condition7[$vals['product_id']][$vals['pp']]  = $numChk7;  
		// 				$product_id .= $vals['product_id'].',';
		// 			} 
				 
		// 			#case8  
		// 			$numbKey8 = array();
		// 			$numChk8 = array();   
		// 			$limit8 = 5;    
		// 			$position8 = -7;  
		// 			for($i=0; $i < $limit8 ;$i++){ 
		// 					$round =  $position8 + $i; 
		// 					$numb = substr($vals['pp'],$round,3); 
		// 					$numbKey8[$i] = $numb;  
		// 			}  
		// 			if( $numbKey8[0] == $numbKey8[3] ){  		$numChk8['value'] =  $numbKey8[0].$numbKey8[0]; 
		// 			}else if( $numbKey8[0] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[4].$numbKey8[4]; 
		// 			}else if($numbKey8[1] == $numbKey8[4]){  	$numChk8['value'] =  $numbKey8[1].$numbKey8[1]; 
		// 			}else if($numbKey8[3] == $numbKey8[0]){ 	$numChk8['value'] =  $numbKey8[3].$numbKey8[3];
		// 			} 
		// 			if(!empty($numChk8)){  
		// 				$numChk8['numb'] =  $vals['product_phone'];
		// 				$numChk8['pp'] =  $vals['pp'];
		// 				$numChk8['id'] =  $vals['product_id'];  
		// 				$condition8[$vals['product_id']][$vals['pp']]  = $numChk8;  
		// 				$product_id .= $vals['product_id'].',';
		// 			}   
					
		// 			$numbKey9 = array();
		// 			$numChk9 = array();   
		// 			$limit9 = 7;    
		// 			$position9 = -7;  
		// 			for($i=0; $i < $limit9 ;$i++){ 
		// 					$round =  $position9 + $i; 
		// 					$numb = substr($vals['pp'],$round,1); 
		// 					$numbKey9[$i] = $numb;   
		// 				}  
					
		// 			if( $numbKey9[0]  ==  $numbKey9[1] && $numbKey9[1] == $numbKey9[2] ){ 
		// 				$numChk9['value'] =  $numbKey9[0].$numbKey9[1].$numbKey9[2]; 
		// 			}

		// 			if( $numbKey9[1]  ==  $numbKey9[2] && $numbKey9[2] == $numbKey9[3] ){ 
		// 				$numChk9['value'] =  $numbKey9[1].$numbKey9[2].$numbKey9[3]; 
		// 			}

		// 			if( $numbKey9[2]  ==  $numbKey9[3] && $numbKey9[3] == $numbKey9[4] ){ 
		// 				$numChk9['value'] =  $numbKey9[2].$numbKey9[3].$numbKey9[4]; 
		// 			} 

		// 			if( $numbKey9[3]  ==  $numbKey9[4] && $numbKey9[4] == $numbKey9[5] ){ 
		// 				$numChk9['value'] =  $numbKey9[3].$numbKey9[4].$numbKey9[5]; 
		// 			}

		// 			if( $numbKey9[4]  ==  $numbKey9[5] && $numbKey9[5] == $numbKey9[6] ){ 
		// 				$numChk9['value'] =  $numbKey9[4].$numbKey9[5].$numbKey9[6]; 
		// 			} 
		// 			if(!empty($numChk9)){ 
							
		// 				$numChk9['numb'] =  $vals['product_phone'];
		// 				$numChk9['pp'] =  $vals['pp'];
		// 				$numChk9['id'] =  $vals['product_id'];  
		// 				$condition9[$vals['product_id']][$vals['pp']]  = $numChk9;  
		// 				$product_id .= $vals['product_id'].',';
		// 			} 

				
		// 			}  
				
			
		// 		/* ********* section 2 ส่วนของการแปลงข้อมูล ************ */ 
		// 		#function1   =  xxxxxx1 & xxxxxx2  
		// 		$resCondi1 = array();
		// 			#จัดการข้อมูลที่น้อยกว่า 1  
		// 		foreach($condition1 as $index => $valz ){  
		// 			$len = count($valz); 
		// 			if($len  < 2){
		// 				unset($condition1[$index]);
		// 				}else{  
		// 						$price = 0;
		// 						foreach($valz as $key => $value){  
		// 							if($value['price'] >  $price){
		// 								$price =  $value['price'];
		// 							} 
		// 						} 
		// 						foreach($valz as $key => $value){   
		// 							$condition1[$index][$key]['price'] = $price;    
		// 						}   
		// 				} 
		// 		} 
		// 		$resCondi1 = $condition1;  #####

		// 		#function2 =  1xxxxxx &  2xxxxxx   
		// 		$resCondi2 = array();
		// 		#จัดการข้อมูลที่น้อยกว่า 1 
		// 		foreach($condition2 as $index => $valz ){   
		// 			$len = count($valz); 
		// 			if($len  < 2){
		// 				unset($condition2[$index]);
		// 				}else{  
		// 						$price = 0;
		// 						foreach($valz as $key => $value){  
		// 							if($value['price'] >  $price){
		// 								$price =  $value['price'];
		// 							} 
		// 						} 
		// 						foreach($valz as $key => $value){  
		// 							$condition2[$index][$key]['price'] = $price;    
		// 						}   
		// 				} 
		// 		} 
		// 			$resCondi2 = $condition2; #####

					 
		// 		#function3  =  12xxxxx & 21xxxxx   
		// 		$resCondi3 = array();
		// 		#จัดการข้อมูลที่น้อยกว่า 1 
		// 		foreach($condition3 as $index => $valz ){   
		// 			$len = count($valz); 
		// 			if($len  < 2){
		// 				unset($condition3[$index]);
		// 				}else{  
		// 						$price = 0;
		// 						foreach($valz as $key => $value){  
		// 							if($value['price'] >  $price){
		// 								$price =  $value['price'];
		// 							} 
		// 						} 
		// 						foreach($valz as $key => $value){  
		// 							$condition3[$index][$key]['dprice'] = $price;    
		// 						}   
		// 				}  
		// 			}   

					
		// 		#ทำการกรองข้อมูล ด้านหน้า  12xxxxx = 21xxxxx 
		// 		foreach($condition3 as $keys => $valp){   
		// 			foreach($valp as $key => $gg){ 
		// 				$lastKey = $key -1; 
		// 				$value['st'] = substr($gg['pp'],0,1);
		// 				$value['nd'] = substr($gg['pp'],1,1); 
		// 				$resc = $value['nd'].''.$value['st'].''.$gg['value'];  
		// 				foreach($condition3[$gg['value']] as $index => $aa ){   
		// 					if($aa['pp'] == $resc && $gg['id'] != $aa['id'] ){ 
		// 						$resCondi3[$gg['value']][$key]['id'] = $gg['id'];
		// 						$resCondi3[$gg['value']][$key]['numb'] = $gg['numb'];  
		// 							$resCondi3[$gg['value']][$key]['value'] = $gg['value'];
		// 						$resCondi3[$gg['value']][$key]['oldprice'] = $aa['price']; 
		// 						$resCondi3[$gg['value']][$key]['price'] = $gg['dprice']; 
		// 						$resCondi3[$gg['value']][$key]['pp'] = $gg['pp']; 
		// 						$resCondi3[$gg['value']][$key]['flip'] = $resc; 
		// 					} 
		// 				}   
		// 			}   
		// 			if(!empty($resCondi3[$gg['value']])	){
		// 				if(count($resCondi3[$gg['value']]) < 2){  // มีข้อมูลซ้ำ
		// 					unset($resCondi3[$keys]); 
		// 				} 
		// 			} 
		// 			} #######

		// 		#function4  =  xxxxx12 & xxxxx21  
		// 		$resCondi4 = array();
		// 		#จัดการข้อมูลที่น้อยกว่า 1 
		// 		foreach($condition4 as $index => $valz ){   
		// 			$len = count($valz); 
		// 			if($len  < 2){
		// 				unset($condition4[$index]);
		// 				} 
		// 			}  
		// 		#ทำการกรองข้อมูล ด้านหน้า  12 = 21  
		// 		foreach($condition4 as $keys => $valp){    
		// 			foreach($valp as $key => $gg){ 
		// 				$rescArr= array();
		// 				$lastKey = $key -1; 
		// 				$value['st'] = substr($gg['pp'],5,1);
		// 				$value['nd'] = substr($gg['pp'],6,1); 
		// 				$resc =  $gg['value'].$value['nd'].$value['st'];  
		// 				$rescArr[] = substr($gg['pp'],5,1);
		// 				$rescArr[] = substr($gg['pp'],6,1); 
		// 				sort($rescArr);
		// 				$sort =  $rescArr[0].$rescArr[1];   
		// 				foreach($condition4[$gg['value']] as $index => $aa ){   
		// 					if($gg['id'] != $aa['id']){   
		// 						if( $aa['pp'] == $resc ){    
		// 							$oldSort = $sort;  
		// 							$resCondi4[$gg['value']][$sort][$key]['id'] = $gg['id'];
		// 							$resCondi4[$gg['value']][$sort][$key]['numb'] = $gg['numb']; 
		// 							$resCondi4[$gg['value']][$sort][$key]['value'] = $gg['value'];  
		// 							$resCondi4[$gg['value']][$sort][$key]['pp'] = $gg['pp']; 
		// 							$resCondi4[$gg['value']][$sort][$key]['flip'] = $resc; 
		// 							$resCondi4[$gg['value']][$sort][$key]['port'] = $sort;  
		// 							$resCondi4[$gg['value']][$sort][$key]['oldPrice'] = $aa['price']; 
		// 						}   
		// 					} 
		// 					}   
		// 				}    
		// 			if(!empty($resCondi4[$gg['value']][$sort])){
		// 				if(count($resCondi4[$gg['value']][$sort]) < 2){  
		// 					unset($resCondi4[$gg['value']][$sort]);
		// 				}  
		// 			}  
		// 		} #####  
		// 		foreach($resCondi4 as $index => $value){
		// 			foreach($value as $key => $val){  
		// 				$price = 0;  
		// 				foreach($resCondi4[$index][$key] as $keyPrice => $var){ 
		// 					if($var['oldPrice'] >  $price){
		// 						$price =  $var['oldPrice'];
		// 					}   
		// 				}   
		// 				foreach($resCondi4[$index][$key] as $keyId => $var){  
		// 						$resCondi4[$index][$key][$keyId]['price'] =  $price;
		// 				} 
		// 			} 
		// 		}


		// 		#function5  =  xxxxxxx & xxxxxxx   
		// 		$resCondi5 = array();
		// 		#จัดการข้อมูลที่น้อยกว่า 1 
		// 		foreach($condition5 as $index => $valz ){ 
		// 			$len = count($valz);    
		// 			if($len  < 2){    
		// 				unset($condition5[$index]);
		// 				}else{  
		// 						$price = 0;
		// 						foreach($valz as $key => $value){  
		// 							if($value['price'] >  $price){
		// 								$price =  $value['price'];
		// 							} 
		// 						} 
		// 						foreach($valz as $key => $value){  
		// 							$condition5[$index][$key]['price'] = $price;    
		// 						}   
		// 				}
		// 		}  
		// 		#ทำการกรองข้อมูล  x = x 
		// 		foreach($condition5 as $keys => $valp){   
		// 			foreach($valp as $key => $gg){  
		// 				$lastKey = $key -1;  
		// 				$resc =  $gg['value'];  
						
		// 				foreach($condition5[$gg['value']] as $index => $aa ){   
		// 					if($aa['pp'] == $resc && $gg['id'] != $aa['id'] ){ 
		// 						$resCondi5[$gg['value']][$key]['id'] = $gg['id']; 
		// 							$resCondi5[$gg['value']][$key]['numb'] = $gg['numb'];    
		// 							$resCondi5[$gg['value']][$key]['price'] = $gg['price'];  
		// 						$resCondi5[$gg['value']][$key]['val'] = $gg['value'];  
		// 						$resCondi5[$gg['value']][$key]['pp'] = $gg['pp'];   
		// 						$resCondi5[$gg['value']][$key]['value'] = $gg['pp'];  
		// 					} 
		// 				}  
		// 			}  
		// 			if(!empty($resCondi5[$gg['value']])){
		// 				if(count($resCondi5[$gg['value']]) < 2){  
		// 					unset($resCondi5[$keys]); 
		// 				} 
		// 			} 
						
		// 		}  #####

				
		// 		/*******************  insert function section **************************/
		// 		/* category number 3  */ 
		// 		$idArr = array();
		// 		$id='';
		// 		#case1 
		// 		foreach($resCondi1 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr[$kk['id']])){
		// 					$idArr[$kk['id']] = $kk['id']; 
		// 				} 
		// 				$category = 3;
		// 				$func_id = 1;
		// 				$group = $kk['value'];
		// 				$sort_by = 0;
		// 				$priority = $ii; 
		// 					$price =  $kk['price'];
		// 				$number = $kk['numb']; 
		// 				$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 												'love_priority' =>ProtectWeb::string($priority),
		// 												'group_price' =>ProtectWeb::string($price),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}

				
		// 		#case2
		// 		foreach($resCondi2 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr[$kk['id']])){
		// 					$idArr[$kk['id']] = $kk['id']; 
		// 				}
		// 				$category = 3;
		// 				$func_id = 2;
		// 				$group = $kk['value'];
		// 				$sort_by = 0;
		// 						$priority = $ii;
		// 						$price = $kk['price'];
		// 				$number = $kk['numb']; 
		// 				$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 												'love_priority' =>ProtectWeb::string($priority),
		// 												'group_price' =>ProtectWeb::string($price),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}


		// 		#case3
		// 		foreach($resCondi3 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr[$kk['id']])){
		// 					$idArr[$kk['id']] = $kk['id']; 
		// 				} 
		// 				$category = 3;
		// 				$func_id = 3;
		// 				$group = $kk['value']; 
		// 				$sort_by = 0;
		// 						$priority = $ii;
		// 						$price = $kk['price'];
		// 				$number = $kk['numb']; 
		// 				$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 												'love_priority' =>ProtectWeb::string($priority),
		// 												'group_price' =>ProtectWeb::string($price),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}


		// 		#case4
		// 		foreach($resCondi4 as $keys => $vals){  
		// 			foreach( $vals as $cc => $kk){ 
		// 				foreach( $kk as $tt => $mm){
		// 					if(!isset($idArr[$mm['id']])){
		// 						$idArr[$mm['id']] = $mm['id']; 
		// 					}
		// 					$category = 3;
		// 					$func_id = 4;
		// 					$group = $mm['value'];
		// 					$sort_by = $mm['port'];
		// 							$priority = 0;
		// 							$price = $mm['price'];
		// 					$number = $mm['numb']; 
		// 					$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 										'func_id' => ProtectWeb::string($func_id),
		// 										'lover_group' => ProtectWeb::string($group),
		// 										'sort' => ProtectWeb::string($sort_by),
		// 													'love_priority' =>ProtectWeb::string($priority),
		// 													'group_price' =>ProtectWeb::string($price),
		// 										'product_list' => ProtectWeb::string($number),
		// 										'status' => 'auto'	  
		// 									);   
		// 					$ii++;
		// 				}
		// 			} 
		// 		}


		// 		#case5
		// 		foreach($resCondi5 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr[$kk['id']])){
		// 					$idArr[$kk['id']] = $kk['id']; 
		// 				}
		// 				$category = 3;
		// 				$func_id = 5;
		// 				$group = $kk['value'];
		// 				$sort_by = 0;
		// 						$priority = $ii;
		// 						$price = $kk['price'];
		// 				$number = $kk['numb']; 
		// 				$listBer[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 												'love_priority' =>ProtectWeb::string($priority),
		// 												'group_price' =>ProtectWeb::string($price),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}  

		// 		if(!empty($idArr)){ 
		// 			$idIn =''; 
		// 			foreach($idArr as $vals){ 
		// 				$idIn .= $vals.',';
		// 			} 
		// 			$idIn = substr($idIn,0,-1); 
		// 			$table = "berproduct";
		// 			$set = "product_category = CONCAT(product_category,:cate_id )";
		// 			$where = " product_id IN (".$idIn.") ";
		// 			$value = array(
		// 				":cate_id" => ',3,' 
		// 			); 
		// 			$res['cate3'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 			$res['lover3'] = $dbcon->multiInsert('berproduct_alover',$listBer); 
		// 			$idArr = array_unique($idArr);
		// 			$table = "berproduct_category";
		// 			$set = "bercate_total =  :cate_id ";
		// 			$where = "bercate_id = 3 ";
		// 			$value = array(
		// 				":cate_id" => count($idArr)
		// 			); 
		// 			$res['count3'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 		}

		// 		/* category 4  */ 
		// 		$idArr2 = array();  

		// 		#case6 xyxy
		// 		#function6 = xxx1212 
		// 		$resCondi6 = array();
		// 		$resCondi6 = $condition6;
		// 		foreach($resCondi6 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr2[$kk['id']])){
		// 					$idArr2[$kk['id']] = $kk['id']; 
		// 				}  
		// 				$category = 4;
		// 				$func_id = 6;
		// 				$group = $kk['value'];
		// 				$sort_by = $kk['id'];
		// 				$priority = $ii;
		// 				$number = $kk['numb']; 
		// 				$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 									'love_priority' =>ProtectWeb::string($priority),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}	

				
		// 		#case7 xxyy
		// 		#function7 = xxx1122 
		// 		$resCondi7 = array();
		// 		$resCondi7 = $condition7;
		// 		foreach($resCondi7 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr2[$kk['id']])){
		// 					$idArr2[$kk['id']] = $kk['id']; 
		// 				}  
		// 				$category = 4;
		// 				$func_id = 7;
		// 				$group = $kk['value'];
		// 				$sort_by = $kk['id'];
		// 				$priority = $ii;
		// 				$number = $kk['numb']; 
		// 				$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 									'love_priority' =>ProtectWeb::string($priority),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}
				
		// 		#case8
		// 		#function8 = 123x123 
		// 		$resCondi8 = array();
		// 		$resCondi8 = $condition8;
		// 		foreach($resCondi8 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr2[$kk['id']])){
		// 					$idArr2[$kk['id']] = $kk['id']; 
		// 				}  
		// 				$category = 4;
		// 				$func_id = 8;
		// 				$group = $kk['value'];
		// 				$sort_by = $kk['id'];
		// 				$priority = $ii;
		// 				$number = $kk['numb']; 
		// 				$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 									'love_priority' =>ProtectWeb::string($priority),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		} 
		// 		#case9
		// 		#function9 = xxxx111 
		// 		$resCondi9 = array();
		// 		$resCondi9 = $condition9;
		// 		foreach($resCondi9 as $keys => $vals){ 
		// 			$ii= 0;
		// 			foreach( $vals as $cc => $kk){ 
		// 				if(!isset($idArr2[$kk['id']])){
		// 					$idArr2[$kk['id']] = $kk['id']; 
		// 				}  
		// 				$category = 4;
		// 				$func_id = 9;
		// 				$group = $kk['value'];
		// 				$sort_by = $kk['id'];
		// 				$priority = $ii;
		// 				$number = $kk['numb']; 
		// 				$listBer2[] = array( 'category' => ProtectWeb::string($category),
		// 									'func_id' => ProtectWeb::string($func_id),
		// 									'lover_group' => ProtectWeb::string($group),
		// 									'sort' => ProtectWeb::string($sort_by),
		// 									'love_priority' =>ProtectWeb::string($priority),
		// 									'product_list' => ProtectWeb::string($number),
		// 									'status' => 'auto'	  
		// 								);   
		// 				$ii++;
		// 			} 
		// 		}

		// 		if(!empty($idArr2)){ 
		// 			$idIn2 = '';
		// 			foreach($idArr2 as $vals){ 
		// 				$idIn2 .= $vals.',';
		// 			}  
		// 			$idIn2 = substr($idIn2,0,-1); 
		// 			$table = "berproduct";
		// 			$set = "product_category = CONCAT(product_category,:cate_id )";
		// 			$where = "product_id IN (".$idIn2.")";
		// 			$value = array(
		// 				":cate_id" => ',4,' 
		// 			); 

		// 			$idArr2 = array_unique($idArr2);

		// 			$res['cate4'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 			$res['lover4'] = $dbcon->multiInsert('berproduct_alover',$listBer2); 
		// 			$table = "berproduct_category";
		// 			$set = "bercate_total =  :cate_id ";
		// 			$where = "bercate_id = 4 ";
		// 			$value = array(
		// 				":cate_id" => count($idArr2)
		// 			); 
		// 			$res['count4'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 		}	

				 
		// 		echo json_encode($res); 
			
		// 	break;






		//  case'insertLoverNumber':
			
		// 		$sql ='SELECT product_id,product_phone,max_relate FROM berproduct WHERE '; 
		// 		foreach($_REQUEST['productArr'] as $key => $vals){ 
		// 			if($key == 1){ }else{ $sql .=' OR ';	}
		// 			$sql .=' product_phone LIKE "%'.$vals.'%" '; 
		// 		}  
		// 		$result = $dbcon->query($sql);
	
		// 		if(!empty($result)){ 
		// 			foreach($result as $keys => $value){  
		// 				$rows = $keys + 1;
		// 				if(in_array($value['product_phone'],$_REQUEST['productArr'])){
		// 					unset($_REQUEST['productArr'][$rows]);  
		// 				}  
		// 			}  	
		// 			// SELECT max(lover_group) as num FROM `berproduct_alover`  
		// 			if(empty($_REQUEST['productArr'])){   
		// 				$numb = $result[0]['max_relate'];
		// 				foreach($result as $keyin => $valin){
		// 					$rows = $keyin + 1;
		// 					$listBer[] = array('category' => '3', 
		// 										'lover_group' => $rows,
		// 										'love_priority' => $numb,
		// 										'product_list' => $valin['product_phone'],
		// 										'status' => 'manual'	  
		// 									 );  
		// 				}
		// 				 $ret['multi'] = $dbcon->multiInsert('berproduct_alover',$listBer);  */
					 
		// 				// foreach($result as $key => $val){ 
		// 				// 	$relate = $val['max_relate'] + 1 ;  
		// 				// 	$table = "berproduct_alover";
		// 				// 	$set = "relate_id = :relate_id ,sub_relate = :sub_relate ";
		// 				// 	$where = "product_phone = :number  ";
		// 				// 	$value = array(			 
		// 				// 		":number" => ProtectWeb::string($val['product_phone']), 
		// 				// 		":product_relate" => ProtectWeb::string($val['product_relate'].''.$relate.','),
		// 				// 		":sub_relate" => ProtectWeb::string($val['sub_relate'].''.$key.',')	
		// 				// 	); 
		// 				// 	$res['product'] = $dbcon->update_prepare($table, $set, $where,$value); 
		// 				// }  
	
		// 				// $numb = $numb + 1;
		// 				// $table = "berproduct";
		// 				// $set = "max_relate = :max_relate ";
		// 				// $where = " product_id != 0 ";
		// 				// $value = array( 
		// 				// 	":max_relate" => ProtectWeb::string($numb)	
		// 				// ); 
		// 				// $res['product'] = $dbcon->update_prepare($table, $set, $where,$value);
						 
		// 		// 	}  
		// 		// }  
	
		// 		$ret['status'] = 200;
		// 		echo json_encode($ret); 
	 
		// 	break;
	
 
	} 
}

?>