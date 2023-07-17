<?php
 
session_start();
require_once 'config/config.php'; 
$site_url = ROOT_URL;
// session_set_cookie_params(0, '/', $site_url);
// setcookie('url', $site_url, time() + 1440); 
//@logout 
if(isset($_GET['logout']) && $_GET['logout'] == "yes"){
    session_destroy();//สั่งเคลียร์ session ทั้งหมด 
    echo "<script>window.location.href = '".SITE_URL."'</script>";
    exit();
} 
//ต่อฐานข้อมูล และ ฟังก์ชั่นที่ต้องใช้งาน
require_once 'classes/dbquery.php';
require_once 'classes/preheader.php';
//สั่งให้คลาส getData ในไฟล์ preheader.php เชื่อมต่อฐานข้อมูล
getData::init(); 

//print_r($_SESSION);

//ตรวจสอบค่าการเปลี่ยนภาษา
$reloadLang = false;
if (!isset($_REQUEST['backend_language'])) {
    if (!isset($_SESSION['backend_language'])) {
        $_SESSION['backend_language'] = 'TH';
        $reloadLang = true;
    }
} else {
    if(isset($_SESSION['available_language'])){
        $av_lan = explode(',', $_SESSION['available_language']);
        if (in_array($_REQUEST['backend_language'], $av_lan)) {
            $_SESSION['backend_language'] = $_REQUEST['backend_language'];
            $reloadLang = true;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<?php  
if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'yes' && $_SESSION['display_page'] == 'backend') {
    //print_r($LANG_LABEL);
    //$LANG_LABEL = '';
    //โหลดภาษาสำหรับแสดง backend ใหม่ เก็บค่าใส่ session
    if(!isset($_SESSION['LANG_LABEL'])  ||  empty($_SESSION['LANG_LABEL'])){
       $LANG_LABEL = getData::lang_interface();
       $_SESSION['LANG_LABEL'] = $LANG_LABEL;
    }else{
        $LANG_LABEL = $_SESSION['LANG_LABEL'];
    }
    $language_fullname = getData::get_language_name();

     //โหลดภาษาใหม่ เก็บค่าใส่ตัวแปรดึงจากฐานข้อมูลทุกครั้งที่เข้าเว็บ
    /* if(empty($LANG_LABEL)){
        $LANG_LABEL = getData::lang_interface();
     }
    */ 
    //ดึงข้อมูลฟีเจอที่จะเปิดใช้งานสำหรับเว็บไซต์
    $feature = getData::get_feature();
    foreach ($feature as $key) {
        $_SESSION[$key['name']] = $key['status'];
    }
    
     $getpost = array(
         'amount'=>10,
         'pagi' => isset($_GET['pagi']) ? $_GET['pagi'] : "",
         'sortby' => isset($_GET['sortby']) ? $_GET['sortby'] : "", 
         'search' => isset($_GET['search']) ? $_GET['search'] : "", 
         );
    //ตรวจสอบ Page
    if (!isset($_REQUEST['page'])) {
        $page = 'dashboard';
    } else {
        $page = $_REQUEST['page'];
    }

    $nav_notify = getData::get_notification_purchase(); 
    $_SESSION['nav_payments_notify'] = $_SESSION['numb'];
    $color_tab = ($_SESSION['numb'] > 0)?"color-tab":"";

    //ดึงเพจที่จะแสดง
    require_once 'classes/class.' . $page . '.php';
    $mydata = new $page(); 
    $section_page = "page_".$page;
    ?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Wynnsoft Management</title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="images/favicon.png">
  <?php include 'template/js-css.php';?>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini <?=$section_page?>">
  <div class="wrapper">
    <?php
    include "template/navtop.php";
    include "template/navleft.php"; 

    if (!isset($_REQUEST['subpage'])) {
        include "template/" . $page . "/" . $page . ".php";
    } else {
        $subpage = $_REQUEST['subpage'];
        include "template/" . $page . "/" . $subpage . ".php";
    } 
    include 'template/footer.php';
    ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script> 
    setInterval(function(){ 
            $.ajax({
                url: 'ajax/ajax.purchase.php',
                type: 'POST',
                dataType: 'json',
                data: { action: 'notfication_purchase' },
                success: function(response){
                   $(".notify_number").html(response['total']);
                   let notify_page = "<?=$_GET['page']?>";
                   let total =  $(".notify_number").html();
                   $("li#purchaseOrderData").css("background","#172c45");  
                   if(response['total'] > 0 && notify_page == "purchaseOrderData" && $("#blog-payments").data('id') != response['total']){
                        if(response['total'] != total){
                            $("li#purchaseOrderData").css("background","#c49f4e");
                            reloadTable();  
                        }
                   }
                   if(response['total'] > 0  && notify_page != "purchaseOrderData"){
                        $("li#purchaseOrderData").css("background","#c49f4e");
                   } 
                }
            });
     }, 100000000); 
  </script> 
</body> 
</html> 
<?php
 } else {
     //ดึงหน้าล็อกอิน
     include 'template/login.php';
 }
?>