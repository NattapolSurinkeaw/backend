<?php

  $getpost['cate_days'] = $_REQUEST['day'];
  $getpost['cate_bermongkol'] = $_REQUEST['bermongkol'];
  $getpost['cate_power'] = $_REQUEST['pow'];
  $getpost['cate_promotion'] = $_REQUEST['promo'];
  $getpost['cate_network'] = $_REQUEST['network'];
  $getpost['status'] = $_REQUEST['status'];
  $getpost['topic'] = $_REQUEST['topic'];
  $all_posts = $mydata->get_post($getpost);
  /*
  $get_days = $mydata->get_days();
  $get_power = $mydata->get_power();
  $get_promotion = $mydata->get_promotion();
  $get_network = $mydata->get_network();
  $get_bermongkol = $mydata->get_bermongkol(); */
  //var_dump($getpost);
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-shopping-bag"></i> <?php echo $LANG_LABEL['product'];//สินค้า?>
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];//หน้าหลัก?></a></li>
      <li class="active"><?php echo $LANG_LABEL['product'];//สินค้า?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-primary">

          <div class="box-header with-border">
            <?php
            if (empty($_REQUEST['search'])) {
              echo '
              <h3 class="box-title"></h3>';
            }else {
              echo '
              <h3 class="box-title"><i class="fa fa-search" aria-hidden="true"></i> ผลการค้นหาสำหรับ "'.$_REQUEST['search'].'"</h3>';
            }
            ?>
            <div class="box-tools pull-right">
              <div class="has-feedback">
                <input type="text" class="form-control input-sm" id="search-content" placeholder="ค้นหา...">
                <input type="hidden" id="search-hidden" value="<?php echo $_REQUEST['search']; ?>">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>

          
          <div class="categorybox-controls">

            <?php
              if (empty($_REQUEST['search'])) {
            ?>
              <label>เรียงตาม : </label>
              <select class="form-control" id="sort-by" style="width: 140px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="dc" <?php if($_REQUEST['sortby']=='dc'){echo 'SELECTED';} ?>>วันที่เพิ่มสินค้า</option>
                <option value="de" <?php if($_REQUEST['sortby']=='de'){echo 'SELECTED';} ?>>วันที่แก้ไข</option>
                <option value="dd" <?php if($_REQUEST['sortby']=='dd'){echo 'SELECTED';} ?>>วันที่แสดง</option>
              </select>

              <label>สถานะ : </label>
              <select class="form-control" id="display-status" style="width: 120px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="">ทั้งหมด</option>
                <option value="yes" <?php if($_REQUEST['status']=='yes'){echo 'SELECTED';} ?>>แสดง</option>
                <option value="no" <?php if($_REQUEST['status']=='no'){echo 'SELECTED';} ?>>ซ่อน</option>
              </select>

              <label>สถานะเบอร์ : </label>
              <select class="form-control" id="topic" style="width: 140px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="">ทั้งหมด</option>
                <option value="1" <?php if($_REQUEST['topic']=='1'){echo 'SELECTED';} ?>>เบอร์ใหม่</option>
                <option value="2" <?php if($_REQUEST['topic']=='2'){echo 'SELECTED';} ?>>เบอร์ติดจอง</option>
                <option value="3" <?php if($_REQUEST['topic']=='3'){echo 'SELECTED';} ?>>เบอร์ขายแล้ว</option>
              </select>

              <div class="pull-right">
                <button type="button" class="btn btn-attree add-content" data-toggle="modal" data-target="#modalAddContent"><i class="fa fa-plus"></i> เพิ่มเบอร์ / สินค้า</button>
              </div> 
            <?php
              }else {
            ?>
              <div class="categorybox-controls category-search">
                <span id="count-search">
                  ผลการค้นหาทั้งหมด <?php echo count($all_posts); ?> รายการ
                </span>
              </div>
            <?php    
              }
            ?>
          </div>

          <?php
          if (!empty($all_posts)) {
          ?>
          <div class="box-body">
            <?php
              foreach($all_posts as $a) {
            ?>
              <div class="attachment-block clearfix" <?php if ($a['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?>
              >
                <div class="content-img upsize">
                  <a class="fancybox" href="<?php echo ROOT_URL.$a['thumbnail']; ?>" title="<?php echo $a['title']; ?>">
                    <img src="<?php echo ROOT_URL.$a['thumbnail']; ?>" alt="">
                  </a>
                </div>
                <div class="content-info upleftmargin">
                  <h1>
                    <?php echo $a['title']; ?> 
                    <?php
                      if ($a['display'] == 'no') {
                        echo '
                        <small class="label label-warning" style="position: absolute; margin: 2px 10px 0;"><i class="fa fa-eye-slash"></i> ซ่อน</small>';
                      }
                    ?>
                  </h1>

                  <p class="text-category">วัน :  <?php echo substr($mydata->getcontentcate_cate_days($a['cate_days']),0,-2); ?> </p>
                  <p class="text-category">เบอร์มงคล :  <?php echo substr($mydata->getcontentcate_cate_bermongkol($a['cate_bermongkol']),0,-2); ?> </p>
                  <p class="text-category">ผลรวมดี :  <?php echo substr($mydata->getcontentcate_cate_power($a['cate_power']),0,-2); ?> </p>
                  <p class="text-category">โปรโมชั่น :  <?php echo substr($mydata->getcontentcate_cate_promotion($a['cate_promotion']),0,-2); ?> </p>
                  <p class="text-category">เครือข่าย :  <?php echo substr($mydata->getcontentcate_cate_network($a['cate_network']),0,-2); ?> </p>
                 
                  <?php
                  if($_SESSION['price']=='yes') {
                  ?>
                  <!-- <p class="text-datetime">จำนวน : <?php echo $a['amount']; ?>&nbsp;&nbsp; ราคาขาย : <?php echo number_format($a['saleprice'],2); ?>&nbsp;&nbsp; ราคาพิเศษ : <?php echo $a['specialprice']; ?></p> -->
                  <p class="text-datetime">จำนวน : <?php echo $a['amount']; ?>&nbsp;&nbsp; ราคาขาย : <?php echo number_format($a['saleprice'],2); ?></p>
                  <?php
                  }
                  ?>
                  <p class="text-datetime">วันที่หมดอายุ : <?php echo $a['date_expire']; ?> / วันที่หมดการแสดงผล NEW : <?php echo $a['date_shownew']; ?></p>
                  <!--p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo substr($a['lang_info'],1); ?></p--> 
                  <p class="text-editor">จำนวนที่เหลือ : </i> <?php echo $a['amount']; ?> / ราคาขาย : </i> <?php echo number_format($a['saleprice'],2); ?> </p> 
                  <!-- <p class="text-user"><i class="fa fa-user-secret" aria-hidden="true"></i> Programmer</p> -->
                </div>
                
                <div class="content-button">
                  <?php
                    if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
                  ?>
                    <!--button type="button" class="btn btn-success margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalEditContent"-->
                    <button type="button" class="btn btn-success margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="edit" >
                      <i class="fa fa-pencil-square-o"></i> แก้ไข
                    </button>

                    <button type="button" class="btn btn-danger delete-content" data-id="<?php echo $a['id']; ?>">
                      <i class="fa fa-trash-o" aria-hidden="true"></i> ลบ
                    </button>
                  <?php
                    }else {
                  ?>
                    <!--button type="button" class="btn btn-attree margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="add" data-toggle="modal" data-target="#modalEditContent"-->
                    <button type="button" class="btn btn-attree margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="add">
                      <i class="fa fa-plus"></i> เพิ่ม
                    </button>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
              }
            ?>
          </div>

          <?php if (empty($_REQUEST['search'])) { ?>
          <div class="box-footer clearfix no-border">
            <div class="box-tools pull-right">
              <ul class="pagination pagination-sm no-margin pull-right">
              </ul>
            </div>
          </div>
          <?php } ?>
          <?php
          }else {
            echo '
            <div class="box-body">
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> ไม่พบผลลัพธ์การค้นหา
              </div>
            </div>';
          }
          ?>

        </div>
      </section>
    </div>
  </section>
  
</div>
<?php
  // include 'template/product/addproduct.php';
  // include 'template/product/editproduct.php';
?>
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jstree/style.css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/progress-bar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<!--script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script-->

<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/jstree/jstree.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/product/product.js"></script>
<!-- 
<script src="<?php echo SITE_URL; ?>js/pages/product/addproduct.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/product/editproduct.js"></script> -->