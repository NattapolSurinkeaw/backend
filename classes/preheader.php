<?php
error_reporting(true);
ini_set('display_errors', 1);

use Dompdf\FrameDecorator\Page;
use PhpOffice\PhpSpreadsheet\IOFactory;

include 'PHPMailer/src/Exception.php';
include 'PHPMailer/src/PHPMailer.php';
include 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class getData
{
    private static $dbcon;
    private $site_url = ROOT_URL;
    private static $language_available;
    public function __construct()
    {
        self::init();
    }

    public static function init()
    {
        self::$dbcon = new DBconnect();
        self::$language_available = self::get_language_array();
    }

    /* @get_feature  ฟังก์ชั่นดึงข้อมูลฟีเจอของระบบเว็บไซต์
    ["topic","tag","SEO","price","multi_user","multi_lingual","member","leave_a_msg","landing","flexible_cate","enable_website","email_letter"]
     */
    public static function get_feature()
    {
        $sql = "SELECT * FROM feature";
        $res = self::$dbcon->query($sql);
        return $res;
    }
    public static function get_notification_payments()
    {
        $sql = "SELECT count(id) as numb FROM record_paid WHERE status = 0 LIMIT 0,1 ";
        $res = self::$dbcon->query($sql);
        return $res[0];
    }

    public static function pagination($table, $where)
    {
        $sql = "SELECT count(*) FROM $table WHERE $where";
        $result = self::$dbcon->runQuery($sql);
        $result->execute();
        $number_of_rows = $result->fetchColumn();
        return $number_of_rows;
    }

    /*
    ดึงรายกาภาษาที่เว็บไซต์รองรับ
     */
    public static function get_language()
    {
        $sql = "SELECT * FROM language ORDER BY id";
        $res = self::$dbcon->query($sql);
        return $res;
    }

    /* @get_language_array ฟังก์ชั่นดึงข้อมูลภาษาเป็นอาร์เรย์
    ค่า return  :  Array ( [0] => TH [1] => EN [2] => CH )
     */
    public static function get_language_array()
    {
        $sql = "SELECT GROUP_CONCAT(language) AS 'language' FROM language";
        $result = self::$dbcon->fetch($sql);
        return explode(',', $result['language']);
    }

    public static function get_name_by_id($table, $field, $key, $id)
    {
        $result = '';
        $sql = "SELECT * FROM " . $table . " WHERE " . $key . " =  '" . $id . "' ORDER BY FIELD(defaults,'yes') DESC";
        // echo $sql;
        $res = self::$dbcon->query($sql);
        if ($res != false) {
            foreach ($res as $b) {
                if ($b['defaults'] == 'yes') {
                    $result = $b[$field];
                }
                if ($b['language'] == $_SESSION['language']) {
                    $result = $b[$field];
                }
            }
            #game แก้ปัญหา slug ต่างภาษา เพราะหา ภาษา ในฟิล language ไม่เจอ
            if ($result == null || !$result || $result == false) {
                $result = $b[$field];
            }
        }
        return $result;
    }

    //ดึงชื่อภาษา
    public static function get_language_name()
    {
        $sql = "SELECT display_name FROM language WHERE language = '" . $_SESSION['backend_language'] . "'";
        $res = self::$dbcon->query($sql);
        return $res['0'];
    }

    public static function get_admin($getpost)
    {
        $sql = "SELECT * FROM user";
        $result = self::$dbcon->query($sql);
        return $result;
    }

    public static function valuefromkey($val, $table, $where, $ind)
    {
        $sql = "SELECT " . $val . " FROM " . $table . " WHERE " . $where . " = '" . $ind . "'";
        $stmt = self::$dbcon->runQuery($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function check_email($email)
    {
        $sql = " SELECT *
                 FROM user
                 WHERE email = '" . $email . "'";
        $result = self::$dbcon->query($sql);
        return $result;
    }

    public function sendemailnew_google($option)
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;
        $mail->Host = $option['SMTP_HOST'];
        $mail->Port = $option['SMTP_PORT'];;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $option['SMTP_USER'];
        $mail->Password = $option['SMTP_PASSWORD'];
        $mail->setFrom($option['mail_system'], $option['sendFromName']);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if (is_array($option['addAddress'])) {
            foreach ($option['addAddress'] as $key => $value) {
                $mail->AddAddress($value['email'], $value['name']);
            }
        }
        if (is_array($option['addBcc'])) {
            foreach ($option['addBcc'] as $key => $value) {
                $mail->addBcc($value['email'], $value['name']);
            }
        }
        $mail->Subject = $option['subject'];
        $mail->msgHTML($option['content']);

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return "false";
        } else {
            return "true";
        }
    }


    public function sendemailSEL($option)
    {

        $email_info = self::get_web_info('system_email');
        $email_config = array();
        foreach ($email_info['system_email']['data'] as $key => $value) {
            $email_config[$value['info_title']] = $value['attribute'];
        }

        // print_r($email_info); exit();

        require_once 'PHPMailer/src/Exception.php';
        require_once 'PHPMailer/src/PHPMailer.php';
        require_once 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer;
        $mail->IsSMTP();

        $mail->CharSet = "utf-8";
        $mail->IsHTML(true);
        $mail->SMTPDebug = 0;
        $mail->Host = $email_config['STMP_HOST'];
        $mail->Port = $email_config['SMTP_PORT'];
        $mail->SMTPSecure = ""; // 'tls';
        $mail->SMTPAuth = false; // true;
        $mail->Username = ""; // $email_config['SMTP_USER'];
        $mail->Password = ""; // $email_config['SMTP_PASS'];



        $mail->setFrom($email_config['SMTP_USER'], $option['sendFromName']);
        if (is_array($option['addAddress'])) {
            foreach ($option['addAddress'] as $key => $value) {
                $mail->AddAddress($value['email'], $value['name']);
            }
        }
        if (is_array($option['addBcc'])) {
            foreach ($option['addBcc'] as $key => $value) {
                $mail->addBcc($value['email'], $value['name']);
            }
        }

        if (is_array($option['addAttachment'])) {
            foreach ($option['addAttachment'] as $key => $value) {
                // echo $value['path'];
                $mail->addAttachment($value['path'], $value['title']);
            }
        }
        $mail->Subject = $option['subject'];
        $mail->msgHTML($option['content']);

        // print_r($mail); exit();
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    /*
    ฟังก์ชั่นดึงภาษาในฐานข้อมูล
     */
    public static function lang_config()
    {
        $listLang = self::$dbcon->query('SELECT * FROM lang_config');
        $langActive = $_SESSION["backend_language"];
        $contentLang = array();
        foreach ($listLang as $word) {
            /*
            [id] => 21
            [param] => add
            [defaults] => add
            [TH] => เพิ่ม
            [EN] => Add
            [CH] =>  加
            ตรวจสอบค่าภาษาถ้าไม่มีข้อมูลให้ใส่ค่า default เข้าไปแทน
             */
            if ($word[$langActive] === "" || $word[$langActive] === null) {
                $contentLang[$word['param']] = $word['defaults'];
            } else {
                $contentLang[$word['param']] = $word[$langActive];
            }
        }
        /*
        @contentLang  ผลลัพที่ได้
        [add] =>  ?
        [addbannerslide] => add banner or slide
        [addbannertype] => add banner or slide
         */
        return $contentLang;
    }

    /* ฟังก์ชั่นดึงภาษาสำหรับแสดง interface ในฐานข้อมูล */
    public static function lang_interface()
    {
        $listLang = self::$dbcon->query('SELECT * FROM lang_backend');
        $langTemplete = $_SESSION["language_templete"];
        $contentLang = array();
        foreach ($listLang as $word) {
            if ($word[$langTemplete] === "" || $word[$langTemplete] === null) {
                $contentLang[$word['param']] = $word['defaults'];
            } else {
                $contentLang[$word['param']] = $word[$langTemplete];
            }
        }
        return $contentLang;
    }

    /* ฟังชั่นดึงข้อมูลข้อความที่ฝากไว้ทางอีเมล */
    public static function new_laeve_msg()
    {
        $sql = "SELECT * FROM leave_msg WHERE status = 'new' ORDER BY submit_date DESC";
        $result = self::$dbcon->query($sql);
        return $result;
    }

    public static function new_orders_msg()
    {
        $sql = "SELECT * FROM orders WHERE status = 'new' ORDER BY OrderDate DESC";
        $result = self::$dbcon->query($sql);
        return $result;
    }

    /*
    ฟังก์ชั่นสร้าง option สำหรับ dropdown
    @table ชือตาราง
    @colum ชื่อคอมลัม
    @op_name
    @op_id
    @key
    @value
     */
    public static function option($table, $column, $op_name, $op_id, $key, $optionActive = '')
    {
        $sql = "SELECT * FROM " . $table;
        $result = self::$dbcon->query($sql);
        $return = '';
        foreach ($result as $value) {
            $statusActive = '';
            if ($optionActive == $value[$key]) {
                $statusActive = " selected ";
            }
            $return .= '<option value=\'' . @$value[$key] . '\' id=\'' . @$value[$op_id] . '\'' . @$statusActive . '>' . @$value[$column] . '</option>';
        }
        return ($return);
    }

    /* สร้าง option สำหรับ dropdown รอบรับหลายภาษา */
    public static function option_multilingual($table, $column, $op_name, $op_id, $key, $optionActive = '')
    {
        //เปิดใช้งานถ้าไม่อยากดึง
        // $lang_config = self::lang_config();
        $sql = "SELECT * FROM " . $table;
        $result = self::$dbcon->query($sql);
        $return = '';
        foreach ($result as $value) {
            $return .= "<option value=\"$value[$key]\" id=\"$op_id$value[$key]\">" . $value[$column] . "</option>";
        }
        return ($return);
    }

    public static function randompassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    public static function get_author($id)
    {
        $sql = "SELECT display_name,email,image FROM user WHERE member_id =  '" . $id . "'";
        $result = self::$dbcon->query($sql);
        $ret = array();
        foreach ($result as $key => $value) {
            $ret = $value;
        }
        return $ret;
    }

    public static function upload_images($new_folder)
    {

        $files = array();
        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }
        umask($oldmask);
        foreach ($_FILES['images'] as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $files)) {
                    $files[$i] = array();
                }

                $files[$i][$k] = $v;
            }
        }

        foreach ($files as $key => $file) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $name = time() . "_";
                $newname = time() . "_" . date('Ymdhis');
                $ext = strchr($file['name'], ".");
                $handle->file_new_name_body = $newname;
                $handle->Process($new_folder);
                $images[$key] = 'upload/' . date('Y') . '/' . date('m') . '/' . $newname . strtolower($ext);
            }
        }
        return $images;
    }
    public function pagination_v2($table, $where, $value)
    {

        // echo json_encode([
        // 	'table' => $table,
        // 	'where' => $where,
        // 	'value' => $value
        // ]); exit();

        $sql = "SELECT count(*) FROM $table WHERE $where";
        $result = self::$dbcon->runQuery($sql);
        $result->execute($value);
        $number_of_rows = $result->fetchColumn();
        return $number_of_rows;
    }


    public function upload_images_thumb($new_folder, $fieldImg = 'images')
    {
        $files = array();
        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }

        umask($oldmask);
        $images = array();
        $totalFile = count($_FILES[$fieldImg]['name']);
        for ($i = 0; $i < $totalFile; $i++) {
            print_r($new_folder);
            move_uploaded_file($_FILES[$fieldImg]['tmp_name'][$i], $new_folder.$_FILES[$fieldImg]['name'][$i]);
            $newname = explode('.', $_FILES[$fieldImg]['name'][$i])[0];
            $ext = strchr($_FILES[$fieldImg]['name'][$i], ".");
            $images[$i] = 'upload/' . date('Y') . '/' . date('m') . '/' . $newname . strtolower($ext);
            // exit();
            // $handle = new Upload(
            //     array(
            //         'name' => $_FILES[$fieldImg]['name'][$i],
            //         'type' => $_FILES[$fieldImg]['type'][$i],
            //         'tmp_name' => $_FILES[$fieldImg]['tmp_name'][$i],
            //         'error' => $_FILES[$fieldImg]['error'][$i],
            //         'size' => $_FILES[$fieldImg]['size'][$i],
            //     ),
            // );
            // if ($handle->uploaded) {
            //     $newname = uniqid() . self::randomString(5); // . microtime(true)
            //     $ext = strchr($_FILES[$fieldImg]['name'][$i], ".");
            //     $handle->file_new_name_body = $newname;
            //     $handle->Process($new_folder);
            //     $images[$i] = 'upload/' . date('Y') . '/' . date('m') . '/' . $newname . strtolower($ext);
            //     $handle->Clean();
            // }
        }
        return $images;
    }

    public static function upload_team_logo($new_folder, $team_name)
    {
        $files = array();
        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }
        umask($oldmask);
        foreach ($_FILES['images'] as $k => $l) {
            foreach ($l as $i => $v) {
                if (!array_key_exists($i, $files)) {
                    $files[$i] = array();
                }

                $files[$i][$k] = $v;
            }
        }
        // var_dump($files);
        foreach ($files as $key => $file) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                $name = time() . "_";
                $newname = $team_name;
                $ext = strchr($file['name'], ".");

                $handle->file_new_name_body = $newname;
                $handle->image_resize = true;
                $handle->image_ratio_x = true;
                $handle->image_y = 60;
                $handle->Process($new_folder);

                $images[$key] = $newname . strtolower($ext);
            }
        }
        return $images;
    }

    public static function video_id_from_url($url)
    {
        if (substr($url, -1) == '/') {
            $url = rtrim($url, '/');
        }
        $pattern =
            '%^# Match any youtube URL
            (?:https?://)?
            (?:www\.)?
            (?:
              youtu\.be/
            | youtube\.com
              (?:
                /embed/
              | /v/
              | /watch\?v=
              )
            )
            ([\w-]{10,12})
            $%x';
        $result = preg_match($pattern, $url, $matches);
        if ($result) {
            return $matches[1];
        }

        $tmp = explode('/', $url);
        if (strtolower($tmp[count($tmp) - 2] == 'videos')) {
            return $tmp[count($tmp) - 1];
        }
        parse_str(parse_url($url)['query'], $query);
        if (!empty($query['v'])) {
            return $query['v'];
        }
    }

    public static function DateName($num)
    {
        $strDate = date('Y-m-d', strtotime($num . " day", strtotime(date('Y-m-d'))));
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("M", strtotime($strDate));
        $strDay = date("d", strtotime($strDate));

        if ($strDay < 10) {
            $strDay = substr($strDay, 1, 2);
        }
        return "$strDay $strMonth $strYear";
    }

    public static function DateData($num)
    {
        $strDate = date('Y-m-d', strtotime($num . " day", strtotime(date('Y-m-d'))));
        return "$strDate";
    }

    public static function DateEng($strDate)
    {
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("d", strtotime($strDate));
        $strMonthCut = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        $strMonthEng = $strMonthCut[$strMonth];
        return "$strDay $strMonthEng $strYear";
    }

    public static function get_website_logo()
    {
        $sql = "SELECT thumbnail FROM contact_sel ";
        $result = self::$dbcon->query($sql);
        return $result[0]["thumbnail"];
    }

    //type: false = short month, true = long month
    public function DateThai($strDate, $type = false)
    {
        if ($_SESSION['language'] == 'TH' || $_SESSION['language'] == "") {
            # code...
            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            if ($type) {
                $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            }
        } elseif ($_SESSION['language'] == 'LA') {
            # code...
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ");
        } else {
            $strYear = date("Y", strtotime($strDate));
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        }
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }

    public static function num_alert_orders()
    {
        $sql = "SELECT COUNT(id) FROM orders WHERE new = 'new' ";
        $res = self::$dbcon->query($sql);
        return $res[0]['COUNT(id)'];
    }

    public static function num_alert_ordersPayed()
    {
        $sql = "SELECT COUNT(id) FROM orders WHERE orders_status = '2' AND slipimg != '' ";
        $res = self::$dbcon->query($sql);
        return $res[0]['COUNT(id)'];
    }

    /**
     * ดึงข้อมูลเว็บไซต์
     */
    public static function get_web_info($type = '')
    {
        $info_type = '';
        if (!empty($type)) {
            $info_type = ' AND info_type="' . $type . '"';
        }

        $sql = "SELECT * FROM web_info_type WHERE (defaults = 'yes ' OR language = '" . $_SESSION['language'] . "') " . $info_type . " ORDER BY FIELD(defaults,'yes') DESC";
        $result_infoType = self::$dbcon->query($sql);
        $output = array();
        if ($result_infoType != false) {
            $dataInfoType = self::convertResultPost($result_infoType, 'id');
            foreach ($dataInfoType as $infoType) {

                $sql = "SELECT * FROM web_info WHERE (defaults = 'yes ' OR language = '" . $_SESSION['language'] . "')  AND info_type ='" . $infoType['info_type'] . "' AND info_display = 'yes' ORDER BY priority ASC, FIELD(defaults,'yes') DESC";
                $result_info = self::$dbcon->query($sql);
                if ($result_info != false) {
                    $output[$infoType['info_type']] = array(
                        'title' => $infoType['info_title'],
                        'data' => self::convertResultPost($result_info, 'info_title'),
                    );
                }
            }
        }

        return $output;
    }

    /* @convertResultPost   ฟังก์ชั่งนี้ใช้เพื่อนำค่าจากตารางโพสต์ แล้วจัดเรียงข้อมูลให้เป็นภาษาปัจุบัน
    @result  ค่าที่ได้การการดึงข้อมุลในฐานข้อมุลด้วยคำสั่ง self::$dbcon->query($sql); ต้องเปลี่ยนชื่อคอมลัมให้เป็น id ในคำนั่ง sql ด้วย
     */
    public static function convertResultPost($result, $defaulColumtId = 'id')
    {
        $post_all = array();
        if (!empty($result)) {
            $postId_follow = "";
            $langActive = $_SESSION['backend_language'];
            $langInfo = array();
            $post_length = count($result);
            foreach ($result as $post) {
                /* จดจำ id ของ post ว่ายังเป็น id เดียวกันอยู่หรือไม่เพราะจะมี id เดียวกันแต่ละคนละภาษา */
                if ($postId_follow == "") {
                    $postId_follow = $post[$defaulColumtId];
                }
                /* เก็บโพสต์ default เอาไว้ */
                if ($post['defaults'] == 'yes') {
                    $post_all[$post[$defaulColumtId]] = $post;
                }
                /* เก็บโพสต์ที่เป็นภาษาปัจจุบัน โดยต้องมีในค่าภาษาที่ระบบได้เพิ่มเอาไว้ */
                if ($post['language'] == $langActive && in_array($post['language'], self::$language_available)) {
                    $post_all[$post[$defaulColumtId]] = $post;
                }
                /* เก็บภาษาที่โพสต์ได้ถูกสร้างขึ้น */
                if ($postId_follow == $post[$defaulColumtId]) {
                    $langInfo[] = $post['language'];
                } else {
                    /* เมื่อขึ้นโพสต์ id ใหม่ให้เก็บค่าภาษาของโพสต์ก่อนหน้าลงไป */
                    $post_all[$postId_follow]['lang_info'] = implode(',', $langInfo);
                    /* กำหนดค่า id ของโพสต์ปัจจุบัน*/
                    $postId_follow = $post[$defaulColumtId];

                    /*เริ่มเก็บภาษาของโพสต์ใหม่ */
                    $langInfo = array();
                    $langInfo[] = $post['language'];
                }
                //ตรวจสอบ id สุดท้าย แล้วใส่ค่าเข้าไป
                if (!next($result)) {
                    $post_all[$postId_follow]['lang_info'] = implode(',', $langInfo);
                }
            }
        }
        return $post_all;
    }

    //ฟังก์ชั่นตรวจสอบว่า slug ถูกใช้ไปหรือยัง โดยจะเช็คกับตาราง post และ
    public function slug_exists($slug)
    {
        //เช็คว่ามี slug หรือเปล่า
        $check_slug = false;
        $langActive = $_SESSION['backend_language'];
        $CHECK_SLUG = array(
            array('table' => 'category', 'colum' => 'url'),
            array('table' => 'post', 'colum' => 'slug')
        );

        foreach ($CHECK_SLUG as $key => $table) {
            $sql = " SELECT COUNT({$table['colum']})  AS 'count_url' FROM {$table['table']} WHERE {$table['colum']} = '{$slug}'";

            $count_url = self::$dbcon->fetch_assoc($sql);
            if ($count_url > 0) {
                $check_slug = true;
                break;
            }
        }

        return $check_slug;
    }

    //$imageId ส่งมาเป้น String สามารถส่งมาหลาย id ได้ต้องคั่นด้วย ,
    //img1.jpg,img2.jpg,img3,jpg
    public function updateImagePost($imageId, $postId, $table = 'post_image')
    {
        if (!empty($imageId)) {
            $set = "status = 'publish', post_id = '{$postId}'";
            $where = " image_id in ({$imageId}) ";
            self::$dbcon->dbcon->update($table, $set, $where);
        }
    }

    public function randomString($length = 5)
    { //กำหนดความยาวข้อความที่ต้องการ
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public static function readExcel($pathFile)
    {

        $inputFileName = '/home/got/html/backend/upload/07/quiz.xlsx';
        require_once 'PhpSpreadsheet/src/Bootstrap.php';

        /**
         * ส่งไฟล์อะไรมาระบบก็จะอ่านเองอัตโนมัติ
         */
        // $spreadsheet = IOFactory::load($inputFileName);
        // $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        // echo '<pre>';
        // print_r($sheetData);
        // echo '</pre>';

    }

    public static function getCertifyPropertyById($id, $type)
    {
        $sql = "SELECT * FROM certify_property WHERE ct_id = :id AND cp_type = :type";

        $res = self::$dbcon->fetchObject($sql, [':id' => $id, ':type' => $type]);
        if (empty($res)) {
            return "false";
        }

        return $res;
    }
    public static function getCertifyTitle($id)
    {
        $sql = "SELECT * FROM certify_title WHERE id = :id";

        $res = self::$dbcon->fetchObject($sql, [':id' => $id]);
        if (empty($res)) {
            return "false";
        }

        return $res;
    }

    public static function savePropertyCertify($id)
    {
        $dateTime = explode(" ", getData::DateThai(date('Y-m-d H:i:s'), true));
        $title = getData::getCertifyTitle($id);
        if ($title === "false") {
            return false;
            exit();
        }
        $arrayPropertyType      = ["name", "title", "score", "day", "month", "year", "image1", "image2"];
        $arrayPropertyName      = ["ตัวอย่างชื่อที่จะแสดง", $title->title, "100", $dateTime[0], $dateTime[1], $dateTime[2], "image1", "image2"];
        $arrayPropertySize      = ["18", "18", "18", "18", "18", "18", "120", "120"];
        $arrayPropertyWeight    = ["400", "400", "400", "400", "400", "400", "400", "400"];
        $arrayPropertyY         = ["428", "562", "642", "723", "719", "724", "836", "836"];
        $arrayPropertyX         = ["375", "371", "484", "262", "420", "589", "193", "554"];
        $arrayPropertyImage    = ["-", "-", "-", "-", "-", "-", "https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg", "https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg"];


        for ($i = 0; $i < count($arrayPropertyType); $i++) {

            $sql = "SELECT * FROM certify_property WHERE ct_id = :id AND cp_type = :type";
            $res = self::$dbcon->fetchObject($sql, [':id' => $id, ':type' => $arrayPropertyType[$i]]);

            if (empty($res)) {
                //ยังไม่มีข้อมูล

                $field = "cp_id,ct_id,cp_img,cp_name,cp_size,cp_weight,cp_type,cp_y,cp_x,cp_create,cp_update";
                $key = ":cp_id,:ct_id,:cp_img,:cp_name,:cp_size,:cp_weight,:cp_type,:cp_y,:cp_x,:cp_create,:cp_update";
                $value = array(
                    ":cp_id" => 0,
                    ":ct_id" => $id,
                    ":cp_img" => $arrayPropertyImage[$i],
                    ":cp_name" => $arrayPropertyName[$i],
                    ":cp_size" => $arrayPropertySize[$i],
                    ":cp_weight" => $arrayPropertyWeight[$i],
                    ":cp_type" => $arrayPropertyType[$i],
                    ":cp_y" => $arrayPropertyY[$i],
                    ":cp_x" => $arrayPropertyX[$i],
                    ":cp_create" => date('Y-m-d H:i:s'),
                    ":cp_update" => date('Y-m-d H:i:s'),
                );

                $res = self::$dbcon->insertPrepare("certify_property", $field, $key, $value);
                // echo json_encode($res);

            } else {
                //มีข้อมูลแล้ว
            }
        }
        return true;
    }

    public static function calcDateAuction($_date)
    {
        $datetime1 = date_create(date('Y-m-d', strtotime($_date)));
        $datetime2 = date_create(date('Y-m-d')); //strtotime(date('Y-m-d H:i:s')."+1 days")
        $dateinterval = date_diff($datetime1, $datetime2, TRUE);
        return $dateinterval->format('%d');
    }

    public static function calc_income($price)
    {
        $c = ($type == "store") ? 1 : 2; //ถ้าเป็น ร้านค่าจะหารด้วย 1 ถ้าเป็น คนที่ซื้อจะหารด้วย2
        if ($price <= 30000) {
            //   $a = (($price / 100) * 1) / $c;
            //   return ($a < 10)?10:$a;
            return "1%";
        } else if ($price <= 100000) {
            return "0.75%";
            //   return (($price / 100) * 0.75) / $c;
        } else if ($price <= 500000) {
            return "0.50%";
            //   return (($price / 100) * 0.50) / $c;
        } else {
            return "0.25%";
            //   return (($price / 100) * 0.25) / $c;
        }
    }

    public static function insert_members_log($getpost)
    {
    }

    public static function renew_member_log_date_expire($getpost)
    {
        $today = date('Y-m-d H:i:s');
        if (!empty($getpost['log'])) {
            if ($getpost['log']['log_expire_date'] < $today) {
                $table = "members_log";
                $set = "log_status = :status";
                $where = "log_member_id = :id";
                $value = array(
                    ":status" => "no",
                    ":id" => $getpost['result']['mem_id']
                );
                $res = self::$dbcon->update_prepare($table, $set, $where, $value);
            } else {
                $ret = array(
                    'message' => 'error',
                    'detail'  => 'date_not_expire'
                );
                return $ret;
            }
        }

        $activate_date = $today;
        $expire_date = date('Y-m-d H:i:s', strtotime('+1 years'));
        $years = (!empty($getpost['log'])) ? (int)$getpost['log']['log_year'] + 1 : 1;
        $status = "used";
        $sql = "INSERT INTO members_log(log_member_id,log_activate_paid,log_activate_date,log_expire_date,log_year,log_status)
                VALUES (:log_member_id,:log_activate_paid,:log_activate_date,:log_expire_date,:log_year,:log_status)";
        $value = [
            ":log_member_id" => $getpost['result']['mem_id'],
            ":log_activate_paid" => $getpost['fee_config']['register_paid'],
            ":log_activate_date" => $activate_date,
            ":log_expire_date" => $expire_date,
            ":log_year" => $years,
            ":log_status" => $status
        ];
        $res = self::$dbcon->insertValue($sql, $value);

        return $res;
    }

    public static function sendemailnew($option)
    {

        $mail = new PHPMailer;

        $mail->CharSet = "utf-8";
        $mail->setFrom($option['mail_system'], $option['sendFromName']);

        if (is_array($option['addAddress'])) {
            foreach ($option['addAddress'] as $key => $value) {
                $mail->AddAddress($value['email'], $value['name']);
            }
        }
        if (is_array($option['addBcc'])) {
            foreach ($option['addBcc'] as $key => $value) {
                $mail->addBcc($value['email'], $value['name']);
            }
        }

        $mail->Subject = $option['subject'];
        $mail->msgHTML($option['content']);
        // $mail->isSMTP();		
        $mail->isHTML(true);

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return "false";
        } else {
            return "true";
        }
    }

    // public  function import!!!_by_excel_productber($path){ 
    //     require_once dirname(__DIR__) .'/classes/PhpSpreadsheet/vendor/autoload.php';
    //     // $setRamBuff = 'set global net_buffer_length=1000000';
    //     // $ramBuff = self::$dbcon->query($setRamBuff);
    //     // $setRamAllow = 'set global max_allowed_packet=1000000000';
    //     // $ramallor = self::$dbcon->query($setRamAllow); 
    //     $spreadsheet = IOFactory::load($path);		 
    //     $sheetData = $spreadsheet->setActiveSheetIndex(0);			 
    //     $highestRow = $sheetData->getHighestRow();		 
    //     $highestColumn = $sheetData->getHighestColumn();	 
    //     $dataExcel = $sheetData->rangeToArray('A1:' . 'K' . $highestRow, null, true, true, false);				 

    //     $sql = 'SELECT * FROM bernetwork';
    //     $res = self::$dbcon->query($sql);  
    //     foreach($res as $keys => $val){  
    //         $networkArr[$val['network_name']] = $val['network_name']; 
    //         if($val['network_name']  == "TRUE"){	
    //             $networkArr[1] = $val['network_name']; 	
    //         }	
    //     } 

    //     $listBer = array();
    //     $dataSql = count($dataExcel);
    //     $chkArr = array();
    //     for ($i=1; $i < $dataSql; $i++) {  
    //         $tel = trim(FILTER_VAR($dataExcel[$i][0],FILTER_SANITIZE_NUMBER_INT));  
    //         if(empty($tel)){ continue; }  
    //         $exNet =  strtoupper(trim($dataExcel[$i][3]));
    //         $networkName = $networkArr[$exNet]; 

    //         if(!isset($chkArr[$tel])){ 
    //             $listBer[] = array('product_phone' => $tel,
    //                     'product_sumber' => $dataExcel[$i][1],
    //                     'product_price' => $dataExcel[$i][2],
    //                     'product_network' => $networkName, 
    //                     'product_category' => ','.$dataExcel[$i][4].',',
    //                     'default_cate' => ','.$dataExcel[$i][4].',',
    //                     'product_pin' => $dataExcel[$i][5],
    //                     'product_sold' => $dataExcel[$i][6],
    //                     'product_new' => $dataExcel[$i][7],
    //                     'product_comment' => $dataExcel[$i][8],
    //                     'product_grade' => strtoupper($dataExcel[$i][9]),
    //                     'product_discount' => $dataExcel[$i][10],
    //                     'product_id' => $i 

    //                 );  
    //             $chkArr[$tel] =  $tel;  
    //         }   
    //     }    

    //     $result = self::$dbcon->multiInsert('berproduct',$listBer); 


    //     return $result;
    // }


    /* 
    *   ดึงข้อมูลเครือข่ายต่างๆ ให้พร้อมใช้งาน
    */
    public static function product_prepare_network()
    {
        $sql = "SELECT * FROM bernetwork";
        $netArr = self::$dbcon->fetchAll($sql, []);
        if (!empty($netArr)) {
            $network = array();
            foreach ($netArr as $key => $val) {
                $network[$val['network_name']] = $val['thumbnail'];
            }
        }
        return $network;
    }

    public static function get_notification_purchase()
    {
        $sql = " SELECT ord.order_id  FROM berproduct_order_list as ord
        INNER JOIN berproduct_contact as con ON ord.contact_id = con.contact_id   
        INNER JOIN berproduct_manage as list ON ord.order_id = list.order_id 
        WHERE ord.status = 'pending' GROUP BY ord.order_id";
        $result = self::$dbcon->fetchAll($sql, []);
        if (isset($result[0]['order_id'])) {
            $_SESSION['numb']  = count($result);
        } else {
            $_SESSION['numb']  = 0;
        }
        $ret = $_SESSION['numb'];
        return $ret;
    }


    public static function updateImprove_by_pred_id($id)
    {
        $berproduct = self::$dbcon->fetchAll("SELECT product_id,product_phone,product_improve FROM berproduct ", []);
        $predCate = self::$dbcon->fetchObject("SELECT numb_id,numb_number,numb_unwanted FROM berpredict_numb WHERE numb_id = :id ", [":id" => $id]);
        $pred_id = $predCate->numb_id;
        #แตกเลขที่ต้องการออกมาเป็นอาเรย์
        $wanted = explode(",", $predCate->numb_number);
        if (!empty($wanted)) {
            $wanted_arr = array();
            foreach ($wanted as $wa) {
                $wanted_arr[$wa] = $wa;
            }
        }
        #แตกเลขที่ไม่ต้องการออกมาเป็นอาเรย์
        $unwanted = explode(",", $predCate->numb_unwanted);
        if (!empty($unwanted)) {
            $unwanted_arr = array();
            foreach ($unwanted as $unw) {
                $unwanted_arr[$unw] = $unw;
            }
        }

        $setCaseIn = ' product_improve = ( CASE product_id ';
        $whereIn = "";
        #เริ่มกรองข้อมูลเลขที่ต้องการและไม่ต้องการ
        foreach ($berproduct as $key => $val) {
            $pp = substr($val['product_phone'], 3, 10);
            foreach ($unwanted_arr as $unw) {
                $found = strpos(trim($pp), trim($unw));
                #ถ้าพบเลขที่ไม่ต้องการให้ข้ามไปเลย
                if ($found != "") {
                    break;
                }
            }
            if ($found != "") continue;

            foreach ($wanted_arr as $wnt) {
                $founds = strpos(trim($pp), trim($wnt));
                #ถ้าพบเลขที่ไม่ต้องการให้ข้ามไปเลย
                if ($founds != "") {
                    $improve_id = $pred_id;
                    $setCaseIn .= ' WHEN "' . $val['product_id'] . '" THEN CONCAT( product_improve , "' . $improve_id . ',") ';
                    if ($whereIn != "") $whereIn .=  ",";
                    $whereIn .=  $val['product_id'];
                    break;
                }
            }
            if ($founds != "") continue;
        }
        $setCaseIn .= " ELSE product_improve END )";
        $where = ' product_id IN( ' . $whereIn . ' )';
        $upds = self::$dbcon->update('berproduct', $setCaseIn, $where);
        return $upds;
    }

    public static function getServiceCharge($netpay)
    {
        $netpay = FILTER_VAR($netpay, FILTER_SANITIZE_NUMBER_FLOAT);
        $charge = self::$dbcon->fetchObject("SELECT service_charge as price ,below_price FROM contact_sel", []);
        if ($netpay > $charge->below_price) $charge->price = 0;
        $svCharge =  $charge->price;
        return $svCharge;
    }
}
