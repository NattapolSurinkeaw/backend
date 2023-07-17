<?php 
session_start(); 
require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.product_sel.php';
require_once dirname(__DIR__) . '/classes/class.upload.php';

$dbcon = new DBconnect();
getData::init();

$mydata = new product_sel();

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) { 
        case 'getcategorycontent': 
            $category = $mydata->get_category(3);
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break; 

        /* ดึงข้อมูลจำนวนแบ่งหน้า */
        case 'getpaginationcontent': 
            $cate_src = ' AND category = 3 ';
            // if ($_REQUEST['cate'] != '') {
            //     $cate_src = " AND category LIKE '%," . $_REQUEST['cate'] . ",%'";
            // } 
            $status = '';
            if ($_REQUEST['display'] != '') {
                $status = " AND display = '" . $_REQUEST['display'] . "'";
            } 
            $search = '';
            if ($_REQUEST['search'] != '') {
                $search = " AND title LIKE '%" . $_REQUEST['search'] . "%'";
            } 
            $bypin = '';
            if ($_REQUEST['bypin'] != '') {
                $bypin = " AND pin LIKE '%" . $_REQUEST['bypin'] . "%'";
            } 
            $bycate = '';
            if ($_REQUEST['bycate'] != '') {
                $bycate = " AND product_cate_id LIKE '%" . $_REQUEST['bycate'] . "%'";
            } 
            $table = "post";
            $where = "defaults = 'yes'" . $search . $cate_src . $status . $bypin . $bycate; 
            $result = getData::pagination($table, $where);
            echo ($result);
            break;

        case'pinAddOrRemove': 

            $id = $_POST['id'];
            $status = $_POST['status'];
            $set = " promote =:promote , date_update = :date , update_by = :by ";
            $where = " p_id =:p_id ";
            $value = array(
                ":p_id" =>  $id,
                ":promote" =>  $status,
                ":date" => date('Y-m-d H:i:s'),
                ":by" => $_SESSION['user_id']
            ); 
            $result = $dbcon->update_prepare("product", $set, $where, $value); 
            
            echo json_encode($result);
        break;
        
        /* ดึงข้อมู content แล้วจะแบบ tag,image ของ content มาด้วย */
        case 'getcontent':

            $sql = "SELECT * FROM post WHERE id = :id ORDER BY FIELD(defaults ,'yes')DESC";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute(array(':id' => $_REQUEST['id']));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response = "";
            
            if (count($result) > 0) {
                /* ฟังก์ชั่น */
                $content = current(getData::convertResultPost($result));
                 if (!empty($content['tag']) && !is_null($content['tag'])) {
                    $sql = "SELECT tag_name,tag_id FROM tag WHERE tag_id in (" . $content['tag'] . ")";
                    $content['tag'] = $dbcon->query($sql);
                 }

                $sql = "SELECT * FROM post_image WHERE post_id = '" . $_REQUEST['id'] . "' ORDER BY position ASC";
                $imageList = $dbcon->query($sql);
                if ($imageList == false) {
                    $imageList = 'no_image';
                } else {
                    $content['images'] = $imageList;
                }

                $response = $content;
            } else {
                $response = 'error';
            }
            echo json_encode($response);
            break;

        case 'addcontent':
 
            $priority = empty($_REQUEST['priority'])?0:$_REQUEST['priority'];
            $slug = $_POST['add-slug'];        

            if(!getData::slug_exists($slug)){

                $sql = "SELECT MAX(id) FROM post";
                $imax = $dbcon->fetch_assoc($sql);
                $imax++;
 
                if ($_REQUEST['add-link-fb'] == '#' || $_REQUEST['add-link-fb'] == '') {
                    $_REQUEST['add-link-fb'] = "";
                } else {
                    $tw = str_replace('https://', '', $_REQUEST['add-link-fb']);
                    $tw = explode('/', $tw);
                    $_REQUEST['add-link-fb'] = $tw[3];
                }

                if ($_REQUEST['add-link-tw'] == '#' || $_REQUEST['add-link-tw'] == '') {
                    $_REQUEST['add-link-tw'] = "";
                } else {
                    $tw = str_replace('https://', '', $_REQUEST['add-link-tw']);
                    $tw = explode('/', $tw);
                    $_REQUEST['add-link-tw'] = $tw[3];
                }
                if ($_REQUEST['add-link-ig'] == '#' || $_REQUEST['add-link-ig'] == '') {
                    $_REQUEST['add-link-ig'] = "";
                } else {
                    $ig = str_replace('https://', '', $_REQUEST['add-link-ig']);
                    $ig = explode('/', $ig);
                    $_REQUEST['add-link-ig'] = $ig[2];
                }

                $addFreetag =  isset($_REQUEST['add-freetag']) ? $_REQUEST['add-freetag'] : '';
                $addH1 = isset($_REQUEST['add-h1']) ? $_REQUEST['add-h1'] : '';
                $addH2 = isset($_REQUEST['add-h2']) ? $_REQUEST['add-h2'] : '';
                $tag = isset($_REQUEST['add-tag']) ? $_REQUEST['add-tag'] : "";
                $tagAll = "";
                if(!empty($tag)){
                    $tagAll = implode(',',$tag);
                }


                if(empty($_REQUEST['add-date-display-hidden'])){
                    $_REQUEST['add-date-display-hidden'] = "0000-00-00 ";
                }
                if(empty($_REQUEST['add-time-display'])){
                    $_REQUEST['add-time-display'] = "00:00:00";
                }

                $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';

                //รูปด้านที่ 1
                $images_1 = getData::upload_images_thumb($new_folder,'images-left');
                $images_left = '';
                if(isset($images_1['0'])){
                    $images_left = $images_1['0'];
                }
                
                //รูปด้านที่ 2
                $images_2 = getData::upload_images_thumb($new_folder,'images-right');
                $images_right = '';
                if(isset($images_2['0'])){
                    $images_right = $images_2['0'];
                }

                //รูปขนาด
                $images = getData::upload_images_thumb($new_folder,'images-size');
  
                $size_guide = '';
                if(isset($images['0'])){
                    $size_guide = $images['0'];
                }


                $price = filter_var($_REQUEST['add-price'],FILTER_SANITIZE_MAGIC_QUOTES);
                $specialprice = filter_var($_REQUEST['add-specialprice'],FILTER_SANITIZE_MAGIC_QUOTES);
                $discount = 0;
                if($specialprice == "" || $specialprice == 0){
                    $specialprice  =  $price;
                }else{
                    $discount =  filter_var($_REQUEST['add-discount'],FILTER_SANITIZE_MAGIC_QUOTES);
                }

                $table = "post";
                $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,thumbnail2,size_guide,h1,h2,video,category,tag,content,example,product_cate_id,product_code,brand_id,saleprice,specialprice,discount,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,author,post_view,comment_allow,comment_count,pin,language,defaults,priority";
                $value = "	'" . $imax . "',
		        			'" . filter_var($_REQUEST['add-title'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-keyword'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-description'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-slug'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-topic'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($addFreetag,FILTER_SANITIZE_MAGIC_QUOTES) . "',
                            '" .  $images_left . "',
                            '" .  $images_right . "',
                            '" .  $size_guide . "',
		        			'" . filter_var($addH1,FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($addH2,FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-video'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-category'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($tagAll,FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add_content'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add_example'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-product-cate'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
                            '" . filter_var($_REQUEST['add-product-code'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
                            '" . filter_var($_REQUEST['add-product-brand'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . $price . "',
                            '" . $specialprice . "',
                            '" . $discount . "',
		        			'" . filter_var($_REQUEST['add-link-fb'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-link-tw'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-link-ig'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . filter_var($_REQUEST['add-display'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . $_REQUEST['add-date-display-hidden']." ".$_REQUEST['add-time-display']. "',
		        			'" . $_SESSION['user_id'] . "',
		        			'0',
		        			'no',
		        			'0',
		        			'" . filter_var($_REQUEST['add-pin'],FILTER_SANITIZE_MAGIC_QUOTES) . "',
		        			'" . $_SESSION['backend_language'] . "',
							'yes',
                            '" . $priority . "'
                        ";
                $res = $dbcon->insert($table, $field, $value);

                $where = " image_id in ({$_REQUEST['imgmoreId']}) ";
                $set = "status = 'publish' , '' , product_id = '{$imax}'";
                $dbcon->update(' post_image ', $set, $where);

                 // Add Data Product_sub
                 if(!empty($_POST['NameProductSub']) && !empty($_POST['StockProductSub'])){
                    if(count($_POST['NameProductSub']) === count($_POST['StockProductSub'])){
                        foreach($_POST['NameProductSub'] as $key => $name){
                            $sql = "INSERT INTO product_sub(p_name,p_stock,post_id) VALUES (:name,:p_stock,:post_id)";
                            $value = array(
                                ":name" => $name,
                                ":p_stock" => $_POST['StockProductSub'][$key],
                                ":post_id" => $imax,
                            );
                            $dbcon->insertValue($sql, $value);
                        }
                    }
                }

                $result = array('data' => $res, 'id' => $imax);

            } else {
                $result['data'] = array('message' => 'url_already_exists');
            }
            echo json_encode($result);

        break;

        case 'editcontent':
 
            $slug = filter_var($_POST['edit-slug'], FILTER_SANITIZE_MAGIC_QUOTES);          //slug ใหม่ที่กรอก
            $old_slug = filter_var($_POST['current-url'], FILTER_SANITIZE_MAGIC_QUOTES);  //slug เก่าที่เก็บไว้
            $check_slug = true; //คือ slug สามารถเข้ามาไปใช้งานได้
            if($slug != $old_slug){ //กรณีที่ slug ใหม่ไม่เท่ากับ slug เก่า  หรือ 
                if(getData::slug_exists($slug)){ //ส่งค่าไปเช็คกลับฐานข้อมูล ค่าส่งกลับมา true คือ มีลิงค์แล้ว
                    $check_slug = false;
                }
            }

            $result = "";
            $tag = isset($_REQUEST['edit-tag']) ? $_REQUEST['edit-tag'] : "";
            $tagAll = "";
            if(!empty($tag)){
                $tagAll = implode(',',$tag);
            }
            $editFreetag =  isset($_REQUEST['edit-freetag']) ? $_REQUEST['edit-freetag'] : '';
            $editH1 = isset($_REQUEST['edit-h1']) ? $_REQUEST['edit-h1'] : '';
            $editH2 = isset($_REQUEST['edit-h2']) ? $_REQUEST['edit-h2'] : '';


            
            if(empty($_REQUEST['date-display-hidden'])){
                $_REQUEST['date-display-hidden'] = "0000-00-00 ";
            }
            if(empty($_REQUEST['time-display'])){
                $_REQUEST['time-display'] = "00:00:00";
            }

            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';

                //รูปด้านที่ 1
                $images_1 = getData::upload_images_thumb($new_folder,'images-left');
                $images_left = '';
                if(isset($images_1['0'])){
                    $images_left = $images_1['0'];
                }
                
                //รูปด้านที่ 2
                $images_2 = getData::upload_images_thumb($new_folder,'images-right');
                $images_right = '';
                if(isset($images_2['0'])){
                    $images_right = $images_2['0'];
                }

                //รูปขนาด
                $images = getData::upload_images_thumb($new_folder,'images-size');
  
                $size_guide = '';
                if(isset($images['0'])){
                    $size_guide = $images['0'];
                }
            
            if ($check_slug) {//ค่า true คือ slug สามารถเอาไปใช้งานได้
                
                //แก้ไขข้อมูล content


                
                if ($_REQUEST['submit-type'] == 'edit') {
                    $table = "post";
                    $set = "
							title = :title,
							keyword = :keyword,
							description = :description,
							slug = :slug,
                            topic = :topic,
							freetag = :freetag,
							h1 = :h1,
							h2 = :h2,
							video = :video,
                            category = :category,
                            product_cate_id = :product_cate_id,
                            product_code = :product_code,
                            brand_id = :brand_id,
                            saleprice = :saleprice,
                            specialprice = :specialprice,
                            discount = :discount,
							tag = :tag,
							content = :content,
							example = :example,
							link_fb = :link_fb,
							link_tw = :link_tw,
							link_ig = :link_ig,
							display = :display,
							date_edit = :date_edit,
							date_display = :date_display,
							author = :author,
							pin = :pin,
                            priority = :priority";
                            
                    $where = "id = :id AND language = :backend_language";
                    
                    $value = array(
                        ":title" => $_REQUEST['edit-title'],
                        ":keyword" => $_REQUEST['edit-keyword'],
                        ":description" => $_REQUEST['edit-description'],
                        ":slug" => $_REQUEST['edit-slug'],
                        ":topic" => $_REQUEST['edit-topic'],                      
                        ":freetag" => $editFreetag ,
                        ":h1" =>  $editH1,
                        ":h2" =>  $editH2,
                        ":video" => $_REQUEST['edit-video'],
                        ":category" => $_REQUEST['edit-category'],
                        ":tag" => $tagAll,
                        ":content" => $_REQUEST['edit_content'],
                        ":example" => $_REQUEST['edit_example'],
                        ":product_cate_id" => $_REQUEST['edit-product-cate'],
                        ":product_code" => $_REQUEST['edit-product-code'],
                        ":brand_id" => $_REQUEST['edit-product-brand'],
                        ":saleprice" => $_REQUEST['edit-price'],
                        ":specialprice" => $_REQUEST['edit-specialprice'],
                        ":discount" => $_REQUEST['edit-discount'],
                        ":link_fb" => $_REQUEST['edit-link-fb'],
                        ":link_tw" => $_REQUEST['edit-link-tw'],
                        ":link_ig" => $_REQUEST['edit-link-ig'],
                        ":display" => $_REQUEST['edit-display'],
                        ":date_edit" => date('Y-m-d H:i:s'),
                        ":date_display" => $_REQUEST['date-display-hidden']." ".$_REQUEST['time-display'],
                        ":author" => $_SESSION['user_id'],
                        ":pin" => $_REQUEST['edit-pin'],
                        ":priority" => $_REQUEST['edit-priority'],
                        ":id" => $_REQUEST['edit-content-id'],
                        ":backend_language" => $_SESSION['backend_language'],

                    );

                    if($size_guide != ''){
                        $set .= ',size_guide = :size_guide';
                        $value[':size_guide'] = $size_guide;
                    }
                   

                    if($images_left != ''){
                        $set .= ',thumbnail = :thumbnail';
                        $value[':thumbnail'] =  $images_left;
                    }

                    if($images_right != ''){
                        $set .= ',thumbnail2 = :thumbnail2';
                        $value[':thumbnail2'] = $images_right;
                    }

                    $result = $dbcon->update_prepare($table, $set, $where, $value); 
                    if(!empty($_POST['NameProductSubEdit']) && !empty($_POST['StockProductSubEdit'])){
                        if(count($_POST['NameProductSubEdit']) === count($_POST['StockProductSubEdit'])){
                            

                            if(!empty($_POST['IDProductSubEdit'])){
                                $sql = "SELECT p_id FROM product_sub WHERE post_id =:post_id AND p_id NOT IN (".implode(',',$_POST['IDProductSubEdit']).")";
                                $resP_ID = $dbcon->fetchAll($sql,[":post_id" => $_POST['edit-content-id']]);
                                

                                if($resP_ID){
                                    //ถ้ามีข้อมูลแสดงว่า ID นั้นถูกลบออก
                                    foreach($resP_ID as $r){ $del = $dbcon->deletePrepare("product_sub", "p_id =:id" ,[":id" => $r['p_id']]); }
                                }
                            }

                            //update p_name , p_price
                            foreach($_POST['IDProductSubEdit'] as $key => $id){
                                $set = "p_name =:p_name , p_stock =:p_stock";
                                $where = "p_id =:p_id";
                                $value = array(
                                    ":p_id" => $id,
                                    ":p_name" => $_POST['NameProductSubEdit'][$key],
                                    ":p_stock" => $_POST['StockProductSubEdit'][$key],
                                );
                                $result = $dbcon->update_prepare("product_sub", $set, $where, $value);
                                
                            }
    
                            // exit();
                            //ถ้ามีการเพิ่มสินค้าย่อยเข้ามา เพิ่ม
                            if(count($_POST['IDProductSubEdit']) !== count($_POST['StockProductSubEdit']) ){
                                $max = count($_POST['IDProductSubEdit']) - 1 ;
                                foreach($_POST['NameProductSubEdit'] as $key => $name){
                                    if($key > $max){
    
                                        $sql = "INSERT INTO product_sub(p_name,p_stock,post_id) VALUES (:name,:p_stock,:post_id)";
                                        $value = array(
                                            ":name" => $_POST['NameProductSubEdit'][$key],
                                            ":p_stock" => $_POST['StockProductSubEdit'][$key],
                                            ":post_id" => $_POST['edit-content-id'],
                                        );
                                        $resIn = $dbcon->insertValue($sql, $value);
                                        // print_r($resIn);
                                    }
                                }
                            } 

                        }else{
                            // echo "yyyy";
                        }


                    // if(!empty($_POST['NameProductSubEdit'])){
                    //     if(count($_POST['NameProductSubEdit']) > 0){
                            
                    //         if(!empty($_POST['IDProductSubEdit'])){
                    //             $sql = "SELECT p_id FROM product_sub WHERE post_id =:post_id AND p_id NOT IN (".implode(',',$_POST['IDProductSubEdit']).")";
                    //             $resP_ID = $dbcon->fetchAll($sql,[":post_id" => $_POST['edit-content-id']]);
                                

                    //             if($resP_ID){
                    //                 //ถ้ามีข้อมูลแสดงว่า ID นั้นถูกลบออก
                    //                 foreach($resP_ID as $r){ $del = $dbcon->deletePrepare("product_sub", "p_id =:id" ,[":id" => $r['p_id']]); }
                    //             }
                    //         }

                    //         //update p_name , p_price
                    //         foreach($_POST['IDProductSubEdit'] as $key => $id){
                    //             $set = "p_name =:p_name , p_stock =:p_stock";
                    //             $where = "p_id =:p_id";
                    //             $value = array(
                    //                 ":p_id" => $id,
                    //                 ':p_stock' => $_POST['StockProductSubEdit'][$key] ,
                    //                 ":p_name" => $_POST['NameProductSubEdit'][$key]
                    //             );
                    //             $result = $dbcon->update_prepare("product_sub", $set, $where, $value);
                                
                    //         }
    
                    //         // exit();
                    //         //ถ้ามีการเพิ่มสินค้าย่อยเข้ามา เพิ่ม
                    //         if(count($_POST['IDProductSubEdit']) > 0){
                    //             $max = count($_POST['IDProductSubEdit']) - 1 ;
                    //             foreach($_POST['NameProductSubEdit'] as $key => $name){
                    //                 if($key > $max){
    
                    //                     $sql = "INSERT INTO product_sub(p_name,p_stock,post_id) VALUES (:name,:p_stock,:post_id)";
                    //                     $value = array(
                    //                         ":name" => $_POST['NameProductSubEdit'][$key],
                    //                         ":p_stock" =>  $_POST['StockProductSubEdit'][$key] ,
                    //                         ":post_id" => $_POST['edit-content-id'],
                    //                     );
                    //                     $resIn = $dbcon->insertValue($sql, $value);
                    //                     // print_r($resIn);
                    //                 }
                    //             }
                    //         } 

                    //     }else{
                    //         // echo "yyyy";
                    //     }
                    }else{
                        //ลบทั้งหมด
                        // echo "Delete All"; exit();
                        $deleteDerr = $dbcon->deletePrepare("product_sub", "post_id =:post_id" ,[":post_id" => $_POST['edit-content-id']]);
                        // print_r($deleteDerr); exit();
                    }


                    

                // เพิ่มเนื้อหาภาษาใหม่
                } else if ($_REQUEST['submit-type'] == 'add') { 

                    $table = "post";
                    $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,h1,h2,video,category,tag,content,
                                product_cate_id,product_code,saleprice,specialprice,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,
								author,post_view,comment_allow,comment_count,pin,language,priority";
                    $param = ":id,:title,:keyword,:description,:slug,:topic,:freetag,:thumbnail,:h1,:h2,:video,:category,:tag,
								:content,:product_cate_id,:product_code,:saleprice,:specialprice,:link_fb,:link_tw,:link_ig,:display,:date_created,
								:date_edit,:date_display,:author,:post_view,:comment_allow,:comment_count,:pin,:language";
                    $value = "	'" . $_REQUEST['edit-content-id'] . "',
				    			'" . $_REQUEST['edit-title'] . "',
				    			'" . $_REQUEST['edit-keyword'] . "',
				    			'" . $_REQUEST['edit-description'] . "',
				    			'" . $_REQUEST['edit-slug'] . "',
				    			'" . $_REQUEST['edit-topic'] . "',
				    			'" .  $editFreetag. "',
				    			'" . $_REQUEST['edit-images-content-hidden'] . "',
				    			'" . $editH1 . "',
				    			'" . $editH2 . "',
				    			'" . $_REQUEST['edit-video'] . "',
				    			'" . $_REQUEST['edit-category'] . "',
				    			'" .  $tagAll . "',
				    			'" . $_REQUEST['edit_content'] . "',
				    			'" . $_REQUEST['edit-product-cate'] . "',
				    			'" . $_REQUEST['edit-product-code'] . "',
				    			'" . $_REQUEST['edit-price'] . "',
				    			'" . $_REQUEST['edit-price'] . "',
				    			'" . $_REQUEST['edit-link-fb'] . "',
				    			'" . $_REQUEST['edit-link-tw'] . "',
				    			'" . $_REQUEST['edit-link-ig'] . "',
				    			'" . $_REQUEST['edit-display'] . "',
				    			'" . $_REQUEST['date-created'] . "',
				    			'" . date('Y-m-d H:i:s') . "',
				    			'" . $_REQUEST['date-display-hidden']." ".$_REQUEST['time-display'] . "',
				    			'" . $_SESSION['user_id'] . "',
				    			'0',
				    			'no',
				    			'0',
				    			'" . $_REQUEST['edit-pin'] . "',
								'" . $_SESSION['backend_language'] . "',
								'" . $_REQUEST['edit-priority'] . "'
						  	";
                    $result = $dbcon->insert($table, $field, $value);
                } 
            } else {
                $result =  array('message' => 'url_already_exists');
            }

             echo json_encode(array('data'=>$result,'id'=> $_REQUEST['edit-content-id']));
            break;

        //จบฟังก์ชั่นแก้ไข

        case 'uploadimgcontent':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);

            $table = "post";
            $set = "thumbnail = '" . $images['0'] . "'";
            $where = "id = '" . $_REQUEST['id'] . "'
	        		AND language = '" . $_SESSION['backend_language'] . "'";
            $result = $dbcon->update($table, $set, $where);
            echo json_encode($result);
            break;

        case 'uploadmoreimgcontent':

            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);

            $sql = "SELECT MAX(position) max FROM post_image WHERE post_id = '" . $_REQUEST['id'] . "'";
            $max = $dbcon->fetch_assoc($sql);
            $max++;

            $ret = array();
            foreach ($images as $key => $img_link) {
                $table = "post_image";
                $field = "post_id, image_link, position, language, status";
                $value = "	'" . $_REQUEST['id'] . "',
			    			'" . $img_link . "',
			    			'" . ($key + $max) . "',
			    			'" . $_SESSION['backend_language'] . "',
			    			'draft'";
                $res = $dbcon->insert($table, $field, $value);
                $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
            }
            echo json_encode($ret);
            
            break;

        case 'deleteimagedraft':
            $sql = "SELECT * FROM post_image WHERE status = 'draft' ";
            $ret = $dbcon->query($sql);

            if ($ret != false) {
                foreach ($ret as $key => $value) {
                    unlink('../../' . $value['image_link']);
                }
                $table = "post_image";
                $where = "status = 'draft'";
                $ret = $dbcon->delete($table, $where);
            }
            break;

        case 'deleteimagecontent':
            
            unlink('../../' . $_REQUEST['filename']);
            $table = "post_image";
            $where = "image_id = '" . $_REQUEST['id'] . "'";
            $ret = $dbcon->delete($table, $where);

          /*
          $sql = "SELECT * FROM post_image WHERE post_id = '" . $_REQUEST['postId'] . "' ORDER BY position ASC";
            $res = $dbcon->query($sql);
            if ($res == false) {
                $res = 'no_image';
            }
            $return_arr = array('images' => $res);
            echo json_encode($return_arr);
            */

            break;

        //@searchtag เพิ่ม ค้นหา tag
        case 'searchtag':
            $sql = "SELECT tag_id,tag_name FROM tag WHERE tag_name LIKE '%" . $_REQUEST['key'] . "%'";
            $result = $dbcon->query($sql);
            echo json_encode($result);
            break;

        //@addtag เพิ่ม tag
        case 'addtag':
            $sql = "SELECT tag_name FROM tag WHERE tag_name = '" . $_REQUEST['key'] . "'";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $result = array('data' => 'exist');
            } else {
                $table = "tag";
                $field = "tag_name,post_count,display";
                $value = " '" . $_REQUEST['key'] . "',0,'yes'";
                $res = $dbcon->insert($table, $field, $value);
                $result = array('data' => $res);
            }
            echo json_encode($result);
            break;

        //@deletecontent ลบ content ออก
        case 'deletecontent':
            $table = "post";
            $where = "id = '" . $_REQUEST['id'] . "'";
            $result = $dbcon->delete($table, $where);
            echo json_encode($result);
            break;
        
        case 'getProductSubByID':
            $sql = "SELECT * FROM product_sub WHERE post_id = :id ORDER BY p_id ASC";
            $resProductSub = $dbcon->fetchAll($sql,[":id" => $_POST['id']]);
            if(empty($resProductSub)){
                echo json_encode([
                    'message' => 'OK',
                    'res' => 'empty'
                ]); exit();
            }

            echo json_encode([
                'message' => 'OK',
                'res'  => $resProductSub
            ]);
        break;

    }
}
