<?php
session_start();

include '../config/database.php';
require_once '../classes/dbquery.php';
#require_once '../classes/class.upload.php';
require_once '../classes/preheader.php';
require_once '../classes/class.product.php';

$dbcon = new DBconnect();
$data = new getData();
$mydata = new product();

if (isset($_REQUEST['action'])) {
    $lang_config = $data->lang_config();
  
    switch ($_REQUEST['action']) {
        case 'getcategory_days':
            $category = $mydata->get_days();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;
        case 'getcategory_bermongkol':
            $category = $mydata->get_bermongkol();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;
        case 'getcategory_power':
            $category = $mydata->get_power();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;
        case 'getcategory_promotion':
            $category = $mydata->get_promotion();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;

        case 'getcategory_network':
            $category = $mydata->get_network();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
            break;

        case 'getpaginationcontent':

            // $getpost['cate_days'] = $_REQUEST['day'];
            // $getpost['cate_bermongkol'] = $_REQUEST['bermongkol'];
            // $getpost['cate_power'] = $_REQUEST['pow'];
            // $getpost['cate_promotion'] = $_REQUEST['promo'];
            // $getpost['cate_network'] = $_REQUEST['network'];

            $cate_days = '';
            if ($_REQUEST['day'] != '') {
                $cate_days = " AND cate_days LIKE '%," . $_REQUEST['day'] . ",%'";
            }

            $cate_bermongkol = '';
            if ($_REQUEST['bermongkol'] != '') {
                $cate_bermongkol = " AND cate_bermongkol LIKE '%," . $_REQUEST['bermongkol'] . ",%'";
            }

            $cate_power = '';
            if ($_REQUEST['pow'] != '') {
                $cate_power = " AND cate_power LIKE '%," . $_REQUEST['pow'] . ",%'";
            }

            $cate_promotion = '';
            if ($_REQUEST['promo'] != '') {
                $cate_promotion = " AND cate_promotion LIKE '%," . $_REQUEST['promo'] . ",%'";
            }

            $cate_network = '';
            if ($_REQUEST['network'] != '') {
                $cate_network = " AND cate_network LIKE '%," . $_REQUEST['network'] . ",%'";
            }

            $status = '';
            if ($_REQUEST['status'] != '') {
                $status = " AND display = '" . $_REQUEST['status'] . "'";
            }

            $search = '';
            if ($_REQUEST['search'] != '') {
                $search = " AND title LIKE '%" . $_REQUEST['search'] . "%'";
            }

            $topic = '';
            if ($_REQUEST['topic'] != '') {
                $topic = " AND topic = '" . $_REQUEST['topic'] . "'";
            }

            $table = "product";
            $where = "defaults = 'yes'" . $cate_days . $cate_bermongkol . $cate_power . $cate_promotion . $cate_network . $status . $search . $topic;
            // echo $where;
            $result = $data->pagination($table, $where);
            echo ($result);
            break;
        case 'getcontent':
            $lan_arr = $data->get_language_array();
            $sql = "SELECT * FROM product WHERE id = :id ORDER BY FIELD(defaults ,'yes')DESC";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute(array(':id' => $_REQUEST['id']));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content = array();
            $ret = array();
            $tag = array();

            foreach ($result as $a) {
                if ($a['defaults'] == 'yes') {
                    $content['defaults'] = $a;
                }
                $content[$a['language']] = $a;
            }

            foreach ($content as $b => $c) {
                if ($b != 'defaults') {
                    if (in_array($b, $lan_arr)) {
                        $lang_info .= ',' . $c['language'];
                    }
                }

                if ($b == 'defaults') {
                    $ret = $c;
                }

                if ($b == $_SESSION['backend_language']) {
                    $ret = $c;
                }

            }
            $ret['lang_info'] = $lang_info;
            $lang_info = '';

            if ($ret['tag'] != '') {
                $alltag = explode(',', $ret['tag']);
                for ($i = 1; $i < count($alltag) - 1; $i++) {
                    $sql = "SELECT tag_name,tag_id FROM tag WHERE tag_id = '" . $alltag[$i] . "'";
                    $tag[] = $dbcon->query($sql);
                }
            }

            $sql = "SELECT * FROM product_image WHERE product_id = '" . $_REQUEST['id'] . "' ORDER BY position ASC";
            $res = $dbcon->query($sql);
            if ($res == false) {
                $res = 'no_image';
            }

            $return_arr = array("data" => [$ret], 'tag' => $tag, 'images' => $res);

            echo json_encode($return_arr);
            break;
        case 'addcontent':

            /*$sql = " SELECT COUNT(url) AS 'count_url_cate' FROM car_cate WHERE url = '".$_REQUEST['slug']."' ";
            $count_url_cate = $dbcon->fetch_assoc($sql);*/
            $sql = " SELECT COUNT(slug) AS 'count_url_post' FROM product WHERE slug = '" . $_REQUEST['slug'] . "' ";
            $count_url_post = $dbcon->fetch_assoc($sql);

            if ($count_url_cate == 0 && $count_url_post == 0) {

                $productId = date("siHYdm") . (rand(0, 9));

                if ($_REQUEST['linktw'] == '#' || $_REQUEST['linktw'] == '') {
                    $_REQUEST['linktw'] = "";
                } else {
                    $tw = str_replace('https://', '', $_REQUEST['linktw']);
                    $tw = explode('/', $tw);
                    $_REQUEST['linktw'] = $tw[3];
                }
                if ($_REQUEST['linkig'] == '#' || $_REQUEST['linkig'] == '') {
                    $_REQUEST['linkig'] = "";
                } else {
                    $ig = str_replace('https://', '', $_REQUEST['linkig']);
                    $ig = explode('/', $ig);
                    $_REQUEST['linkig'] = $ig[2];
                }

                #gameeiei
                $realslug = explode("_", $_REQUEST['slug']);
                $sql = "SELECT slug FROM product WHERE slug LIKE '%" . $realslug[0] . "%'";
                $rescolor = $dbcon->query($sql);
                $colordefaults = 'yes';
                if ($rescolor || $rescolor != null || $rescolor != false) {
                    $colordefaults = '';
                }

                $thisDate = date_create(date('Y-m-d H:i:s'));
                date_modify($thisDate, "+30 days");
                $date_stop_showingnew = date_format($thisDate, "Y-m-d H:i:s");

                $thisDate = date_create(date('Y-m-d H:i:s'));
                date_modify($thisDate, "+60 days");
                $date_expire = date_format($thisDate, "Y-m-d H:i:s");

                $real_title = str_replace("-", "", $_REQUEST['title']);
                $real_title = str_replace("+", "", $real_title);
                //echo "//".$productId;
                $table = "product";
                $field = "id, title, real_title, keyword, description, slug, freetag, h1, h2, short_url, thumbnail, video, cate_days , cate_bermongkol ,  cate_power , cate_promotion,cate_network,color_dot, categoryfull, tag, topic, content, saleprice, specialprice,amount, size, color_id, link_fb, link_tw, link_ig, display, date_created, date_edit, date_shownew,date_display,date_expire, author, post_view, comment_allow, comment_count, pin, language, defaults,color";
                $value = "
		        			'" . $productId . "',
		        			'" . $_REQUEST['title'] . "',
		        			'" . $real_title . "',
		        			'" . $_REQUEST['keyword'] . "',
		        			'" . $_REQUEST['description'] . "',
		        			'" . $_REQUEST['slug'] . "',
		        			'" . $_REQUEST['freetag'] . "',
		        			'" . $_REQUEST['h1'] . "',
		        			'" . $_REQUEST['h2'] . "',
		        			'',
		        			'',
		        			'" . $_REQUEST['video'] . "',
		        			'" . $_REQUEST['cate_days'] . "',
		        			'" . $_REQUEST['cate_bermongkol'] . "',
		        			'" . $_REQUEST['cate_power'] . "',
		        			'" . $_REQUEST['cate_promotion'] . "',
		        			'" . $_REQUEST['cate_network'] . "',
		        			'" . $_REQUEST['color_dot'] . "',
		        			'',
		        			'" . $_REQUEST['tag'] . "',
		        			'1',
		        			'" . $_REQUEST['content'] . "',
		        			'" . $_REQUEST['saleprice'] . "',
		        			'" . $_REQUEST['specialprice'] . "',
		        			'" . $_REQUEST['amount'] . "',
		        			'',
		        			'0',
		        			'" . $_REQUEST['linkfb'] . "',
		        			'" . $_REQUEST['linktw'] . "',
		        			'" . $_REQUEST['linkig'] . "',
		        			'" . $_REQUEST['display'] . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . date('Y-m-d H:i:s') . "',
		        			'" . $date_stop_showingnew . "',
		        			'" . $_REQUEST['dateDisplay'] . "',
		        			'" . $date_expire . "',
		        			'" . $_SESSION['user_id'] . "',
		        			'0',
		        			'no',
		        			'0',
		        			'" . $_REQUEST['pin'] . "',
		        			'" . $_SESSION['backend_language'] . "',
		        			'yes',
		        			'" . $colordefaults . "'
		        			";
                $res = $dbcon->insert($table, $field, $value);
                // echo "aa ".$value;
                $table = "product_image";
                $set = "status = 'publish',
		        		product_id = '" . $productId . "'";
                $where = "product_id = 0";
                $dbcon->update($table, $set, $where);

                $result = array('data' => $res, 'id' => $productId);

            } else {
                $result['data'] = array('message' => 'url_already_exists');
            }
            echo json_encode($result);
            break;
        case 'editcontent':
            if ($_REQUEST['slug'] == $_REQUEST['currentUrl']) {
                $count_url_cate = 0;
                $count_url_post = 0;
            } else {
                /*$sql = " SELECT COUNT(url) AS 'count_url_cate' FROM car_cate WHERE url = '".$_REQUEST['slug']."' ";
                $count_url_cate = $dbcon->fetch_assoc($sql);*/
                $sql = " SELECT COUNT(slug) AS 'count_url_post' FROM product WHERE slug = '" . $_REQUEST['slug'] . "' ";
                $count_url_post = $dbcon->fetch_assoc($sql);
            }

            if ($count_url_cate == 0 && $count_url_post == 0) {

                if ($_REQUEST['submitType'] == 'edit') {
                    /*
                    $thisDate = date_create( date('Y-m-d H:i:s') );
                    date_modify($thisDate,"+30 days");
                    $date_stop_showingnew = date_format($thisDate,"Y-m-d H:i:s");
                     */
                    $real_title = str_replace("-", "", $_REQUEST['title']);
                    $real_title = str_replace("+", "", $real_title);

                    $table = "product";
                    $set = "title = '" . $_REQUEST['title'] . "',
			        		real_title = '" . $real_title . "',
			        		keyword = '" . $_REQUEST['keyword'] . "',
			        		description = '" . $_REQUEST['description'] . "',
			        		slug = '" . $_REQUEST['slug'] . "',
			        		topic = '" . $_REQUEST['topic'] . "',
			        		freetag = '" . $_REQUEST['freetag'] . "',
			        		h1 = '" . $_REQUEST['h1'] . "',
			        		h2 = '" . $_REQUEST['h2'] . "',
			        		video = '" . $_REQUEST['video'] . "',
			        		cate_days = '" . $_REQUEST['cate_days'] . "',
			        		cate_bermongkol = '" . $_REQUEST['cate_bermongkol'] . "',
			        		cate_power = '" . $_REQUEST['cate_power'] . "',
			        		cate_promotion = '" . $_REQUEST['cate_promotion'] . "',
			        		cate_network = '" . $_REQUEST['cate_network'] . "',
			        		color_dot = '" . $_REQUEST['color_dot'] . "',
			        		tag = '" . $_REQUEST['tag'] . "',
			        		content = '" . $_REQUEST['content'] . "',
			        		saleprice = '" . $_REQUEST['saleprice'] . "',
			        		specialprice = '" . $_REQUEST['specialprice'] . "',
			        		amount = '" . $_REQUEST['amount'] . "',
			        		link_fb = '" . $_REQUEST['linkfb'] . "',
			        		link_tw = '" . $_REQUEST['linktw'] . "',
			        		link_ig = '" . $_REQUEST['linkig'] . "',
			        		display = '" . $_REQUEST['display'] . "',
			        		date_edit = '" . date('Y-m-d H:i:s') . "',
			        		date_display = '" . $_REQUEST['dateDisplay'] . "',
			        		date_expire = '" . $_REQUEST['dateExpire'] . "',
			        		author = '" . $_SESSION['user_id'] . "',
			        		pin = '" . $_REQUEST['pin'] . "'
			        		";
                    $where = "id = '" . $_REQUEST['id'] . "'
			        		AND language = '" . $_SESSION['backend_language'] . "'";
                    $res = $dbcon->update($table, $set, $where);

                } else if ($_REQUEST['submitType'] == 'add') {
                    #เพิ่มภาษา
                    #gameeiei
                    /*$realslug = explode("_", $_REQUEST['slug']);
                    $sql = "SELECT slug FROM product WHERE slug LIKE '%".$realslug[0]."%'";
                    $rescolor = $dbcon->query($sql);
                    $colordefaults = 'yes';
                    if($rescolor || $rescolor != null || $rescolor != false){
                    $colordefaults = '';
                    }*/

                    $thisDate = date_create(date('Y-m-d H:i:s'));
                    date_modify($thisDate, "+30 days");
                    $date_stop_showingnew = date_format($thisDate, "Y-m-d H:i:s");

                    $real_title = str_replace("-", "", $_REQUEST['title']);
                    $real_title = str_replace("+", "", $real_title);

                    $table = "product";
                    $field = "id, title, real_title, keyword, description, slug, freetag, h1, h2, short_url, thumbnail, video, cate_days , cate_bermongkol , cate_power , cate_promotion,cate_network,color_dot, categoryfull, tag, topic, content, saleprice, specialprice,amount, size, color_id, link_fb, link_tw, link_ig, display, date_created, date_edit, date_shownew,date_display,date_expire, author, post_view, comment_allow, comment_count, pin, language,defaults,color";
                    $value = "	'" . $productId . "',
		        				'" . $_REQUEST['title'] . "',
		        				'" . $real_title . "',
		        				'" . $_REQUEST['keyword'] . "',
		        				'" . $_REQUEST['description'] . "',
		        				'" . $_REQUEST['slug'] . "',
		        				'" . $_REQUEST['freetag'] . "',
		        				'" . $_REQUEST['h1'] . "',
		        				'" . $_REQUEST['h2'] . "',
		        				'',
		        				'',
		        				'" . $_REQUEST['video'] . "',
		        				'" . $_REQUEST['cate_days'] . "',
		        				'" . $_REQUEST['cate_bermongkol'] . "',
		        				'" . $_REQUEST['cate_power'] . "',
		        				'" . $_REQUEST['cate_promotion'] . "',
		        				'" . $_REQUEST['cate_network'] . "',
		        				'" . $_REQUEST['color_dot'] . "',
		        				'',
		        				'" . $_REQUEST['tag'] . "',
		        				'" . $_REQUEST['topic'] . "',
		        				'" . $_REQUEST['content'] . "',
		        				'" . $_REQUEST['saleprice'] . "',
		        				'" . $_REQUEST['specialprice'] . "',
		        				'" . $_REQUEST['amount'] . "',
		        				'',
		        				'0',
		        				'" . $_REQUEST['linkfb'] . "',
		        				'" . $_REQUEST['linktw'] . "',
		        				'" . $_REQUEST['linkig'] . "',
		        				'" . $_REQUEST['display'] . "',
		        				'" . date('Y-m-d H:i:s') . "',
		        				'" . date('Y-m-d H:i:s') . "',
		        				'" . $date_stop_showingnew . "',
		        				'" . $_REQUEST['dateDisplay'] . "',
		        				'" . $_REQUEST['dateExpire'] . "',
		        				'" . $_SESSION['user_id'] . "',
		        				'0',
		        				'no',
		        				'0',
		        				'" . $_REQUEST['pin'] . "',
		        				'" . $_SESSION['backend_language'] . "',
		        				'yes',
		        				'" . $colordefaults . "'
		        				";
                    $res = $dbcon->insert($table, $field, $value);
                }

                $table = "product_image";
                $set = "status = 'publish'";
                $where = "product_id = '" . $_REQUEST['id'] . "'";
                $dbcon->update($table, $set, $where);

                $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);

                $result = array('data' => $res);
            } else {
                $result['data'] = array('message' => 'url_already_exists');
            }
            echo json_encode($result);
            break;
        case 'uploadimgcontent':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            // $images = $data->upload_images($new_folder);
            //echo $new_folder;
            $images = $data->upload_images_thumb($new_folder);

            $table = "product";
            $set = "thumbnail = '" . $images['0'] . "'";
            $where = "id = '" . $_REQUEST['id'] . "'
	        		AND language = '" . $_SESSION['backend_language'] . "'";
            $result = $dbcon->update($table, $set, $where);
            echo json_encode($result);
            break;
        case 'uploadmoreimgcontent':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            // $images = $data->upload_images($new_folder);
            $images = $data->upload_images_thumb($new_folder);

            $sql = "SELECT MAX(position) max FROM product_image WHERE product_id = '" . $_REQUEST['id'] . "'";
            $max = $dbcon->fetch_assoc($sql);
            $max++;

            $ret = array();
            foreach ($images as $key => $img_link) {
                $table = "product_image";
                $field = "product_id, image_link, position, language, status";
                $value = "	'" . $_REQUEST['id'] . "',
			    			'" . $img_link . "',
			    			'" . ($key + $max) . "',
			    			'',
			    			'draft'";
                $res = $dbcon->insert($table, $field, $value);
                $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
            }
            echo json_encode($ret);
            break;
        case 'deleteimagedraft':
            $sql = "SELECT * FROM product_image WHERE status = 'draft' ";
            $ret = $dbcon->query($sql);

            if ($ret != false) {
                foreach ($ret as $key => $value) {
                    unlink('../../' . $value['image_link']);
                }
                $table = "product_image";
                $where = "status = 'draft'";
                $ret = $dbcon->delete($table, $where);
            }
            break;
        case 'deleteimagecontent':
            unlink('../../' . $_REQUEST['filename']);
            $table = "product_image";
            $where = "image_id = '" . $_REQUEST['id'] . "'";
            $ret = $dbcon->delete($table, $where);

            $sql = "SELECT * FROM product_image WHERE product_id = '" . $_REQUEST['postId'] . "' ORDER BY position ASC";
            $res = $dbcon->query($sql);
            if ($res == false) {
                $res = 'no_image';
            }
            $return_arr = array('images' => $res);
            echo json_encode($return_arr);
            break;
        case 'searchtag':
            $sql = "SELECT tag_id,tag_name FROM tag WHERE tag_name LIKE '%" . $_REQUEST['key'] . "%'";
            $result = $dbcon->query($sql);
            echo json_encode($result);
            break;
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
        case 'deleteproduct':
            $table = "product";
            $where = "id = '" . $_REQUEST['id'] . "'";
            $result = $dbcon->delete($table, $where);
            echo json_encode($result);
            break;

        case 'inc_shownewdate':
            $sql = "SELECT date_shownew FROM product WHERE id = '" . $_REQUEST['id'] . "' ";
            $ret = $dbcon->query($sql);

            $thisDate = date_create($ret[0]['date_shownew']);
            date_modify($thisDate, "+30 days");
            $date_stop_showingnew = date_format($thisDate, "Y-m-d H:i:s");

            $table = "product";
            $set = "date_shownew = '" . $date_stop_showingnew . "'";
            $where = "id = '" . $_REQUEST['id'] . "'";
            $res = $dbcon->update($table, $set, $where);

            echo json_encode([$res]);
            break;
    }
}
