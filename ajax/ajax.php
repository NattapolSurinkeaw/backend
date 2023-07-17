<?php

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
// require_once dirname(__DIR__) . '/classes/class.upload.php';

$dbcon = new DBconnect();
getData::init();

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {
      //ความยากอยู่ที่ถ้า check คนละภาษาแล้วจะดูยังไง
 
      case 'checkUrl':
          $retune_value = true;
          $slug = filter_var($_POST['slug'], FILTER_SANITIZE_MAGIC_QUOTES);          //slug ใหม่ที่กรอก
          $old_slug = filter_var(isset($_POST['old_slug']) ? $_POST['old_slug'] : "" , FILTER_SANITIZE_MAGIC_QUOTES);  //slug เก่าที่เก็บไว้

          if($slug != $old_slug || empty($old_slug)){
              if(getData::slug_exists($slug)){
                  $retune_value = false;
              }
          }
          // true = slug ถูกใช้งานแล้ว โดยจะส่ง false กลับไปให้ jquery validation จะขึ้นสีแดง , false = ยังไม่ถูกใช้งาน โดยจะส่ง true jquery valition จะขึ้นสีเขียว
          echo json_encode($retune_value);
          break;
    }
}
