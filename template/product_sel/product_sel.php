<?php
  $getpost = array();
  $getpost['sortby'] = isset($_GET['sortby']) ? $_GET['sortby'] : "";
  $getpost['product_cate'] = isset($_GET['bycate']) ? $_GET['bycate'] : "0";
  $getpost['product_subcate'] = isset($_GET['subcate']) ? $_GET['subcate'] : "";
  $getpost['pin'] = isset($_GET['bypin']) ? $_GET['bypin'] : ""; 
  $getpost['search'] = isset($_GET['search']) ? $_GET['search'] : "";    
  $getpost['status'] = isset($_GET['status']) ? $_GET['status'] : "";  
  $category =  $mydata->get_product_category();    
  $product_subcate = $mydata->get_product_subcategory($getpost['product_cate']);   
  $all_posts = $mydata->get_product_all($getpost);
  $p_status = $mydata->get_product_status();
  
?>
<style>
  .form-group.has-error .cke_chrome { 
      border-color: #dd4b39;
      box-shadow: none;
  } 
  .box-content-cate.error,.box-content-cate-edit.error { 
    border: 1px solid #dd4b39;
  }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-newspaper-o"></i> จัดการสินค้า<?php //echo $LANG_LABEL['content'];?> 
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i><?php echo $LANG_LABEL['home']?></a></li>
      <li class="active"><?php echo $LANG_LABEL['content'];?></li>
    </ol>
  </section>

  <section id="backendContent" class="content">
    <div class="row" id="product_list">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-primary">
          <div id="slc_category_product">
            <div class="row" style="margin-top:10px;">
             <div class="col-md-12"> 
                  <label><?php echo "หมวดหมู่สินค้า";?> : </label> 
                  <select class="form-control" id="product_type">
                    <option value="0">ทั้งหมด</option> 
                    <?php   
                      foreach ($category as $value) { 
                        if($value['id'] == 1){ continue; }
                        $slc = ($value['id'] == $_GET['bycate'])? "selected":""; 
                        echo " <option  ".$slc." value=\"".$value['id']."\">".$value['name']."</option> ";
                      } 
                    ?>
                  </select>
              </div>
             </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-md-12">
                  <label><?php echo "หมวดย่อยสินค้า";?> : </label>
                    <select class="form-control" id="subproduct_type">
                      <option value="0">ทั้งหมด</option>
                      <?php foreach ($product_subcate as $value) {  
                        $slc = ($value['id'] == $_GET['subcate'])? "selected":""; 
                        echo " <option  ".$slc." value=\"".$value['id']."\">".$value['name']."</option> ";
                      } ?>
                    </select>
                </div>
              </div>
           

            <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <label><?php echo "สถานะสินค้า";?> : </label>
                  <select class="form-control" id="product_status">
                    <option value="0">ทั้งหมด</option>
                    <?php foreach ($p_status as $value) {  
                      $slc = ($value['id'] == $_GET['status'])? "selected":""; 
                      echo " <option  ".$slc." value=\"".$value['id']."\">".$value['title']."</option> ";
                    } ?>
                  </select>
              </div>
            </div>
          </div> 

          

          <div class="box-header" style="position: unset;">
            <?php
            if (empty($search_text)) {
              echo '
              <h3 class="box-title"></h3>';
            }else {
              echo '
              <h3 class="box-title"><i class="fa fa-search" aria-hidden="true"></i> '.$LANG_LABEL['resultsfor'].' "'.$_REQUEST['search'].'"</h3>';
            }
            ?>
            <div class="box-tools pull-right">
              <div class="has-feedback">
                <input type="text" class="form-control input-sm" id="search-content" value="<?=$_GET['search']?>"placeholder="<?php echo $LANG_LABEL['searchtext'];?>">
                <input type="hidden" id="search-hidden" value="<?php echo  $search_text; ?>">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div> 
          
          <div class="categorybox-controls"> 
            <?php
              if (empty($search_text)) {
                $search_sortby = isset($_GET['sortby']) ? $_GET['sortby'] : ""; 
              ?>
              <label><?php echo $LANG_LABEL['sortby'];?> : </label>
              <select class="form-control" id="sort-by" style="width: 170px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value=""  SELECTED  > ค่าเริ่มต้น </option>
                <option value="dc" <?php if($search_sortby=='dc'){echo 'SELECTED';} ?>> ราคามากไปน้อย </option>
                <option value="de" <?php if($search_sortby=='de'){echo 'SELECTED';} ?>> ราคาน้อยไปมาก </option>
                <option value="dd" <?php if($search_sortby=='dd'){echo 'SELECTED';} ?>> วันที่ล่าสุด </option>
                <option value="df" <?php if($search_sortby=='df'){echo 'SELECTED';} ?>> คะแนนผู้ขายสูงสุด </option>
              </select> 

              <label><?php echo "";//$LANG_LABEL['categories'];?>  </label>
  
              <select class="form-control" disabled id="cate-id" style="width: 100px; display: none; margin: 0 10px 5px 5px;"> 
                <option value="3">สินค้า</option>  
                <?php
                // $search_bycate = isset($_GET['bycate']) ? $_GET['bycate'] : "";
                ?>
            </select>
            <label class="">
              <div class="kt-checkbox <?=($_GET['bypin'] == "yes")?"checked":""?>">
                
              </div> 
              เลือกเฉพาะที่ปักหมุด(หน้าหลัก) 
            </label> 

            <div style="margin-left:3px;"class="pull-right">
               <button class="btn btn-info " type="submit" id="modalShow" style="display: none;"> <?php echo $LANG_LABEL['matchfinish'];?></button>
                <?php 
                  if($_SESSION['role'] == "admin" || $_SESSION['role'] == "superadmin"){
                    ?>
                    <!-- <button class="btn btn-primary kt:btn-info" type="submit" id="modalHide"> <?php echo $LANG_LABEL['hidebt'].' '.$LANG_LABEL['delete'];?></button> -->
                    <?php 
                  } 
                ?>
            </div>
            <div class="pull-right">
              <!-- <button type="button" class="btn btn-primary bt-add-content kt:btn-info" style="padding: 8px 40px" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่มสินค้า<?php //echo $LANG_LABEL['addcontent'];?></button> -->
            </div>
            <?php
              }else {
            ?>
              <div class="categorybox-controls category-search">
                <span id="count-search">
                  <?php echo $LANG_LABEL['allresults'];?> <?php echo count($all_posts); ?> <?php echo $LANG_LABEL['record'];?>
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
              $curDate = date("Y-m-d H:i:s");  
              foreach($all_posts as $post) { 
                $newDate = date("d-m-Y", strtotime($post['date_update']));    
                $date_process = ($post['status'] != 1 && $post['status'] != 2 )? $newDate: 'เหลือเวลาอีก '.$post['time_bid'] .' วัน';
                $date_result = (($post['status'] != 1 && $post['status'] != 2 )? '<span class="p_label"><i class="fa fa-clock-o" aria-hidden="true"></i> ปิดการขาย '.$date_process .'</span>' : '<small class="label label-danger" style="padding: 5px;"><i class="fa fa-clock-o"></i> '.$date_process.'</small>');
            ?>
              <div class="attachment-block clearfix" <?php if ($post['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?> >
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$post['img']; ?>" title="<?php echo $post['name']; ?>">
                    <img src="<?php echo ROOT_URL.$post['img']; ?>" alt=""> 
                  </a>
                </div>
                <div class="content-info"> 
                  <h1>  
                    <?php echo $post['name']; ?> 
                    <span  style="color:red">[ id: <?=$post['id']?> ]</span> 
                    <?=$date_result?>
                  </h1>
                  <p class="text-category">
                    หมวดหมู่: <?=$post['pc_name']?>  หมวดย่อย: <?=$post['psc_name']?>
                  </p> 
                  <p class="text-datetime"> 
                    <!-- <span>[ผู้ซื้อ: <?=$post['bidder']?>]</span>  -->
                    <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                    <span style="color:#03a9f4;"> สถานะ: <?=$post['status_desc']?> </span>
                    <span style="color:#03a9f4;">[ราคาปัจจุบัน: <?=number_format($post['price_current'])?> บาท]</span> 
                  </p>
                  <p class="text-editor"><i class="fa fa-money" aria-hidden="true"></i> 
                        <span>ราคาเริ่มต้น <?=number_format($post['price'])?> บาท  </span>
                        <!-- <span><?=number_format($post['bid'])?> บาท  </span> -->
                        <span>ราคาซื้อทันที <?=number_format($post['price_special'])?> บาท</span>
                    </p>
                  <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> 
                        <span>ผู้ขาย: <?=$post['m_name']?> (<?=$post['m_phone']?>) </span>
                        <span style="color: #ffc41e;"><i class="fa fa-star"></i> <?=$post['star_yellow']?></span> 
                    </p>
                </div>
                <?php if($post['status'] == 2 ){  ?>
                  <div class="content-button pull-right" style="position: absolute;right:0;">
                    <button type="button" class="btn btn-primary margin-r-10 pin_product <?=(($post['promote'] == 'yes')?'active':"")?> " data-id="<?=$post['p_id']?>">
                        <i class="fa <?=(($post['promote'] == 'yes')?'fa-thumb-tack':"fa-plus")?>"></i> PIN 
                      </button>
                  </div>
                <?php } ?>
              </div> 
            <?php
              }  
            ?>
          </div>
          <div class="box-footer clearfix no-border">
            <div class="box-tools pull-right">
              <ul class="pagination pagination-sm no-margin pull-right">
              </ul>
            </div>
          </div>

          <?php
          }else {
            echo '
            <div class="box-body">
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> '.$LANG_LABEL['nodata'].'
              </div>
            </div>';
          }
          ?>

        </div>
      </section>
    </div>
  </section>
  
  <!-- popup status download  -->
  <div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
  </div>

</div>
<!-- content-wrapper -->

<?php
  include 'template/product_sel/addproduct_sel.php';
  
  include 'template/product_sel/editproduct_sel.php';
?>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jstree/style.css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/progress-bar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">

<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/jstree/jstree.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/product_sel/product_sel.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/product_sel/addproduct_sel.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/product_sel/editproduct_sel.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/contents/hidebtn.js?v=<?=date('ymdhis')?>"></script>

 

<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> -->
