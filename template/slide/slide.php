<?php
$adsDB = $mydata->get_advertise();
$adsList = array();
//@banner_hide ซ่อน , banner_main โฆษณาหลัก , banner โฆษณาที่ไม่แสดงหน้าหลัก
foreach ($adsDB as $key => $ads) {
  $index = '';
  if($ads['ad_display'] =='no'){
    $index = 'banner_hide';
  }else if($ads['ad_position'] == 'pin'){ //ad_display = yes && ad_position = pin
    $index = 'banner_main';
  }else{ //ad_display = no && ad_position = no
    $index = 'banner';
  }
  $adsList[$index][] = $ads;
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-picture-o"></i> <?php echo $LANG_LABEL['banner'];?>
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];?></a></li>
      <li class="active"><?php echo $LANG_LABEL['banner'];?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-primary">
          <div class="box-header">
            <div class="box-header ui-sortable-handle">
              <i class="fa fa-picture-o"></i>
              <h3 class="box-title"><?php echo $LANG_LABEL['slide']?>(<?php echo $LANG_LABEL['show'];?>)</h3>
              <div class="box-tools pull-right" data-toggle="tooltip" title="">
                <button type="button" class="btn btn-primary pull-right kt:btn-info" style="padding: 10px 40px;" data-toggle="modal" data-target="#modalAddSlide"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['addbannerslide'];?></button>
              </div>
            </div>
          </div>
          <div class="box-body">
            <?php
              /* โฆษณาที่แสดงที่หน้าหลัก */
              if (!empty($adsList['banner_main'])) {
                foreach($adsList['banner_main'] as $a) { 
            ?>
              <div class="attachment-block clearfix">
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$a['ad_image']; ?>" title="<?php echo $a['ad_title']; ?>">
                    <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['ad_image'].'&size=x95'; ?>" alt="">
                  </a>
                </div>
                <div class="content-info">
                  <h1><?php echo $a['ad_title']; ?></h1>

                  <p class="text-category"><i class="fa fa-picture-o"></i> <?php echo $a['ad_position']; ?></p>
                  <p class="text-datetime"><i class="fa fa-clock-o"></i> <?php echo $a['ad_created']; ?></p>
                  <p class="text-editor"><i class="fa fa-globe"></i> <?php echo $a['lang_info']; ?></p>
                </div>
                <div class="content-button pull-right">
                  <?php
                    if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
                  ?>
                    <button type="button" class="btn btn-success edit-slide kt:btn-success" style="padding: 8px 40px;" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="edit">
                      <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];?>
                    </button>

                  <?php
                    }else {
                  ?>
                    <button type="button" class="btn btn-primary edit-slide" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="add">
                      <i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];?>
                    </button>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
                }
              }
            ?>
          </div>   

          <div class="box-header">
            <div class="box-header ui-sortable-handle">
              <i class="fa fa-picture-o"></i>
              <h3 class="box-title"><?php echo $LANG_LABEL['banners'];?>(<?php echo $LANG_LABEL['show'];?>)</h3>
            </div>
          </div>
          <div class="box-body">

            <?php
            /* โฆษณาที่ไม่ได้แสดงที่หน้าหลัก */
            if (!empty($adsList['banner'])) {
              foreach($adsList['banner'] as $a) {
            ?>
              <div class="attachment-block clearfix">
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$a['ad_image']; ?>" title="<?php echo $a['ad_title']; ?>">
                    <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['ad_image'].'&size=x95'; ?>" alt="">
                  </a>
                </div>
                <div class="content-info">
                  <h1><?php echo $a['ad_title']; ?></h1>

                  <p class="text-category"><i class="fa fa-picture-o"></i> <?php echo $a['ad_position']; ?></p>
                  <p class="text-datetime"><i class="fa fa-clock-o"></i> <?php echo $a['ad_created']; ?></p>
                  <p class="text-editor"><i class="fa fa-globe"></i> <?php echo $a['lang_info']; ?></p>
                </div>
                <div class="content-button pull-right">
                  <?php
                      if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
                  ?>
                    <button type="button" class="btn btn-success edit-slide kt:btn-success" style="padding: 8px 40px;" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="edit">
                      <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];?>
                    </button>

                  <?php
                    }else {
                  ?>
                    <button type="button" class="btn btn-primary edit-slide" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="add">
                      <i class="fa fa-plus"></i>  <?php echo $LANG_LABEL['add'];?>
                    </button>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
                }
              }
            ?>
          </div> 
          <div class="box-header">
            <div class="box-header ui-sortable-handle">
              <i class="fa fa-eye-slash"></i>
              <h3 class="box-title"><?php echo $LANG_LABEL['banner']?>(<?php echo $LANG_LABEL['hide'];?>)</h3>
            </div>
          </div>
          <div class="box-body">

            <?php
            //ไม่แสดง
            if (!empty($adsList['banner_hide'])) {
              foreach($adsList['banner_hide'] as $a ) {
            ?>
              <div class="attachment-block clearfix">
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$a['ad_image']; ?>" title="<?php echo $a['ad_title']; ?>">
                    <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['ad_image'].'&size=x95'; ?>" alt="">
                  </a>
                </div>
                <div class="content-info">
                  <h1><?php echo $a['ad_title']; ?></h1>
                  <p class="text-category"><i class="fa fa-picture-o"></i> <?php echo $a['ad_position']; ?></p>
                  <p class="text-datetime"><i class="fa fa-clock-o"></i> <?php echo $a['ad_created']; ?></p>
                  <p class="text-editor"><i class="fa fa-globe"></i> <?php echo $a['lang_info']; ?></p>
                </div>
                <div class="content-button pull-right">
                  <?php
                    if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
                  ?>
                    <button type="button" class="btn btn-success edit-slide kt:btn-success" style="padding: 8px 40px;" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="edit">
                      <i class="fa fa-pencil-square-o"></i>  <?php echo $LANG_LABEL['edit'];?>
                    </button>

                  <?php
                    }else {
                  ?>
                    <button type="button" class="btn btn-primary edit-slide" data-id="<?php echo $a['ad_id']; ?>" data-toggle="modal" data-target="#modalEditSlide" data-type="add">
                      <i class="fa fa-plus"></i>  <?php echo $LANG_LABEL['add'];?>
                    </button>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
                }
              }
            ?>
          </div>       

        </div>
      </section>
    </div>
  </section>
  <?php
     include 'template/slide/addslide.php';
     include 'template/slide/editslide.php';
  ?>
</div>

<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/slide/slide.js?v=<?=time()?>"></script>