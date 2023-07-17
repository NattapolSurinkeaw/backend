<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.portfolio.php';
#require_once dirname(__DIR__) . '/classes/class.upload.php';

$dbcon = new DBconnect();
getData::init();

$mydata = new portfolio();

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {

        case 'getcategorycontent':

            $category = $mydata->get_category(5);
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;
        
        /* ดึงข้อมูลจำนวนแบ่งหน้า */
        case 'getpaginationcontent':

            $cate_src = ' AND category = 5';
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

            $table = "post";
            $where = "defaults = 'yes'" . $search . $cate_src . $status;
            $result = getData::pagination($table, $where);
            echo ($result);
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

                $table = "post";
                $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,h1,h2,video,category,tag,content,saleprice,specialprice,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,author,post_view,comment_allow,comment_count,pin,language,defaults,priority";
                $value = "	'" . $imax . "',
		        			'" . $_REQUEST['add-title'] . "',
		        			'" . $_REQUEST['add-keyword'] . "',
		        			'" . $_REQUEST['add-description'] . "',
		        			'" . $_REQUEST['add-slug'] . "',
		        			'" . $_REQUEST['add-topic'] . "',
		        			'" . $addFreetag . "',
		        			'',
		        			'" . $addH1 . "',
		        			'" . $addH2 . "',
		        			'" . $_REQUEST['add-video'] . "',
		        			'" . $_REQUEST['add-category'] . "',
		        			'" . $tagAll . "',
		        			'" . $_REQUEST['add_content'] . "',
		        			'0',
		        			'0',
		        			'" . $_REQUEST['add-link-fb'] . "',
		        			'" . $_REQUEST['add-link-tw'] . "',
		        			'" . $_REQUEST['add-link-ig'] . "',
		        			'" . $_REQUEST['add-display'] . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . $date_display. "',
		        			'" . $_SESSION['user_id'] . "',
		        			'0',
		        			'no',
		        			'0',
		        			'" . $_REQUEST['add-pin'] . "',
		        			'" . $_SESSION['backend_language'] . "',
							'yes',
							'" . $priority . "'";
                $res = $dbcon->insert($table, $field, $value);

                $where = " image_id in ({$_REQUEST['imgmoreId']}) ";
                $set = "status = 'publish', product_id = '{$imax}'";
                $dbcon->update('post_image', $set, $where);

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
							tag = :tag,
							content = :content,
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

                        /* !filter data! */
                        // ":title" => ProtectWeb::string($_REQUEST['title']),
                        // ":keyword" => ProtectWeb::string($_REQUEST['keyword']),
                        // ":description" => ProtectWeb::string($_REQUEST['description']),
                        // ":slug" => ProtectWeb::string($_REQUEST['slug']),
                        // ":topic" => ProtectWeb::string($_REQUEST['topic']),
                        // ":freetag" => ProtectWeb::string($_REQUEST['freetag']),
                        // ":h1" => ProtectWeb::string($_REQUEST['h1']),
                        // ":h2" => ProtectWeb::string($_REQUEST['h2']),
                        // ":video" => ProtectWeb::string($_REQUEST['video']),
                        // ":category" => $_REQUEST['cateid'],
                        // ":tag" => $_REQUEST['tag'],
                        // ":content" => preg_replace('/(<(.*)script>)|(javascript:[\w\d\s();]*)|(\s+on[\w\d\s="\'();]*)/i',"",$_REQUEST['content']),
                        // ":link_fb" => ProtectWeb::string($_REQUEST['linkfb']),
                        // ":link_tw" => ProtectWeb::string($_REQUEST['linktw']),
                        // ":link_ig" => ProtectWeb::string($_REQUEST['linkig']),
                        // ":display" => ProtectWeb::string($_REQUEST['display']),
                        // ":date_edit" => date('Y-m-d H:i:s'),
                        // ":date_display" => $_REQUEST['dateDisplay'],
                        // ":author" => $_SESSION['user_id'],
                        // ":pin" => $_REQUEST['pin'],
                        // ":priority" => ProtectWeb::number_int($_REQUEST['priority']),
                        // ":id" => ProtectWeb::number_int($_REQUEST['id']),
                        // ":backend_language" => $_SESSION['backend_language']
                    );
                    $result = $dbcon->update_prepare($table, $set, $where, $value);

                
                // เพิ่มเนื้อหาภาษาใหม่
                } else if ($_REQUEST['submit-type'] == 'add') { 

                    $table = "post";
                    $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,h1,h2,video,category,tag,content,
								saleprice,specialprice,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,
								author,post_view,comment_allow,comment_count,pin,language,priority";
                    $param = ":id,:title,:keyword,:description,:slug,:topic,:freetag,:thumbnail,:h1,:h2,:video,:category,:tag,
								:content,:saleprice,:specialprice,:link_fb,:link_tw,:link_ig,:display,:date_created,
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
				    			'0',
				    			'0',
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
             echo json_encode(array('data'=>$result,'id'=> @$_REQUEST['edit-content-id']));
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

    }
}
