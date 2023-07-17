<?php
  $getpost['cateid'] = $_REQUEST['bycate'];
  $getpost['status'] = $_REQUEST['status'];
  $rooms = $mydata->get_rooms($getpost);
  $category = $mydata->get_category();
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-bed"></i> ห้องพัก 
      <!-- <small>( <?php echo $language_name['display_name']; ?> )</small> -->
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
      <li class="active">ห้องพัก</li>
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
              <select class="form-control" id="sort-by" style="width: 110px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="dc" <?php if($_REQUEST['sortby']=='dc'){echo 'SELECTED';} ?>>วันที่สร้าง</option>
                <option value="de" <?php if($_REQUEST['sortby']=='de'){echo 'SELECTED';} ?>>วันที่แก้ไข</option>
                <option value="dd" <?php if($_REQUEST['sortby']=='dd'){echo 'SELECTED';} ?>>วันที่แสดง</option>
              </select>

              <label>สถานะ : </label>
              <select class="form-control" id="display-status" style="width: 120px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="">ทั้งหมด</option>
                <option value="yes" <?php if($_REQUEST['status']=='yes'){echo 'SELECTED';} ?>>แสดง</option>
                <option value="no" <?php if($_REQUEST['status']=='no'){echo 'SELECTED';} ?>>ซ่อน</option>
              </select>
            
            <div class="pull-right">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddContent"><i class="fa fa-plus"></i> เพิ่มห้องพัก</button>
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
          if (!empty($rooms)) {
          ?>
          <div class="box-body">
            <?php
              foreach($rooms as $a) {
            ?>
              <div class="attachment-block clearfix" <?php if ($a['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?>
              >
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$a['thumbnail']; ?>" title="<?php echo $a['title']; ?>">
                    <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['thumbnail'].'&size=x95'; ?>" alt="">
                  </a>
                </div>
                <div class="content-info">
                  <h1>
                    <?php echo $a['title']; ?>
                    <?php
                      if ($a['display'] == 'no') {
                        echo '
                        <small class="label label-warning" style="position: absolute; margin: 2px 10px 0;"><i class="fa fa-eye-slash"></i> ซ่อน</small>';
                      }
                    ?>
                  </h1>
                  <p class="text-datetime"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $a['date_created']; ?></p>
                  <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo substr($a['lang_info'],1); ?></p>
                  <!-- <p class="text-user"><i class="fa fa-user-secret" aria-hidden="true"></i> Programmer</p> -->
                </div>
                <!-- <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small> -->
                <div class="content-button pull-right">
                  <?php
                    if(strpos($a['lang_info'],$_SESSION['backend_language'])){
                  ?>
                    <button type="button" class="btn btn-success margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalEditContent">
                      <i class="fa fa-pencil-square-o"></i> แก้ไข
                    </button>

                    <button type="button" class="btn btn-danger delete-content" data-id="<?php echo $a['id']; ?>">
                      <i class="fa fa-trash-o" aria-hidden="true"></i> ลบ
                    </button>
                  <?php
                    }else {
                  ?>
                    <button type="button" class="btn btn-primary margin-r-10 edit-content" data-id="<?php echo $a['id']; ?>" data-type="add" data-toggle="modal" data-target="#modalEditContent">
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
  include 'template/rooms/addrooms.php';
  include 'template/rooms/editrooms.php';
?>
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jstree/style.css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/progress-bar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/jstree/jstree.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-rooms/rooms.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-rooms/add-rooms.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-rooms/edit-rooms.js"></script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>