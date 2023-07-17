<?php
 
  $getpost['cateid'] = 4;
  $getpost['status'] = isset($_GET['status']) ? $_GET['status'] : "";
  $getpost['pin'] = isset($_GET['bypin']) ? $_GET['bypin'] : "";
  $getpost['special'] = isset($_GET['spc']) ? $_GET['spc'] : "";
  $search_text = isset($_GET['search']) ? $_GET['search'] : "";
  
  $all_posts = $mydata->get_post($getpost);
  $category =  $mydata->get_category($getpost['cateid']);
?>
<style>
.form-group.has-error .cke_chrome {
    border-color: #dd4b39;
    box-shadow: none;
}
.box-content-cate.error,.box-content-cate-edit.error{
  border: 1px solid #dd4b39;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-newspaper-o"></i> BLOG<?php //echo $LANG_LABEL['content'];?> 
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i><?php echo $LANG_LABEL['home']?></a></li>
      <li class="active"><?php echo $LANG_LABEL['content'];?></li>
    </ol>
  </section>

  <section id="backendContent" class="content">
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-primary">

          <div class="box-header with-border">
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
                <input type="text" class="form-control input-sm" id="search-content" placeholder="<?php echo $LANG_LABEL['searchtext'];?>">
                <input type="hidden" id="search-hidden" value="<?php echo  $search_text; ?>">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>

          
          <div class="categorybox-controls">

            <?php
              if (empty($search_text)) {
                $search_sortby = isset($_GET['sortby']) ? $_GET['sortby'] : "";
                $search_status = isset($_GET['status']) ? $_GET['status'] : "";
                
            ?>
              <label><?php echo $LANG_LABEL['sortby'];?> : </label>
              <select class="form-control" id="sort-by" style="width: 110px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="dc" <?php if($search_sortby=='dc'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['datecreated'];?></option>
                <option value="de" <?php if($search_sortby=='de'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['dateedit'];?></option>
                <option value="dd" <?php if($search_sortby=='dd'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['displaydate'];?></option>
                <option value="df" <?php if($search_sortby=='df'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['priority'];?></option>
              </select>

              <label><?php echo $LANG_LABEL['matchstatus']?> : </label>
              <select class="form-control" id="display-status" style="width: 120px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value=""><?php echo $LANG_LABEL['all'];?></option>
                <option value="yes" <?php if($search_status=='yes'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['show'];?></option>
                <option value="no" <?php if($search_status=='no'){echo 'SELECTED';} ?>><?php echo $LANG_LABEL['hide'];?></option>
              </select>

              <!-- <label><?php echo $LANG_LABEL['categories'];?></label> -->
  
              <!-- <select class="form-control" disabled id="cate-id" style="width: 200px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value="4">เทคโนโลยี</option>  
              </select> -->

            <label class="" style="margin-left:10px;">
              <div id="pinMain" class="kt-checkbox <?=($_GET['bypin'] == "yes")?"checked":""?>">
                
              </div> 
              เลือกเฉพาะที่ปักหมุด(หน้าหลัก)  
            </label>

            <!-- <label class="" style="margin-left:10px;">
              <div id="typeSpecial" class="kt-checkbox <?=($_GET['spc'] == "special")?"checked":""?>">
                
              </div> 
              เลือกเฉพาะ (Member Special)  
            </label> -->

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
              <button type="button" class="btn btn-primary bt-add-content kt:btn-info" data-toggle="modal"><i class="fa fa-plus"></i> <?php echo "เพิ่ม Blog";//$LANG_LABEL['addcontent'];?></button>
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
              foreach($all_posts as $post) {
            ?>
              <div class="attachment-block clearfix" <?php if ($post['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?> >
                <div class="content-img">
                  <a class="fancybox" href="<?php echo ROOT_URL.$post['thumbnail']; ?>" title="<?php echo $post['title']; ?>">
                    <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$post['thumbnail'].'&size=x95'; ?>" alt="">
                  </a>
                </div>
                <div class="content-info">
                  <h1>

                    <?php echo $post['title']; ?> 
                    <?php
                      if ($post['display'] == 'no') {
                        echo '<small class="label label-warning" style="position: absolute; margin: 2px 10px 0;"><i class="fa fa-eye-slash"></i> '.$LANG_LABEL['hide'].'</small>';
                      }
                    ?>
                    <?php 
                      if ($_SESSION['role'] === 'superadmin') {
                    ?>
                    <label class = "showclass">id : <?php echo $post['id']; ?></label>
                    <?php } ?>
                  </h1>

                  <p class="text-category"><i class="fa fa-folder-open-o" aria-hidden="true"></i> 
                    <?php echo $mydata->getcontentcate($post['category']); ?>
                  <p class="text-datetime"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $post['date_created']; ?></p>
                  <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo $post['lang_info']; ?>  <?php if($post['priority'] > 0){ ?>Priority : <?php echo $post['priority']?> <?php } ?>  </p>

                </div>
                <!-- <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small> -->
                <div class="content-button pull-right">
                  <?php
                    if(strpos($post['lang_info'],$_SESSION['backend_language']) > -1){
                  ?>
  
                     <!-- คัดกรองให้ลบได้เฉพาะ SUPERADMIN || ADMIN --> 
                      <?php 
                      if($_SESSION['role'] != "superadmin" && $_SESSION['role'] != "admin"){
                          if($post['section'] != "hide"){ //user and editor zone
                              ?>
                              <button type="button" class="btn kt:btn-danger delete-content" data-id="<?php echo $post['id']; ?>">
                                 <i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo $LANG_LABEL['delete'];?>
                              </button>
                              <?php
                          }
                      }else{ //super admin zone  
                          ?> 
                          <button type="button"  id="deleteHideBtn<?php $post['id']?>"class="btn btn-danger delete-content deleteHideBtn<?php $post['section']?> kt:btn-danger" data-id="<?php echo $post['id']; ?>" style="padding: 8px 40px;"> 
                            <i class="fa fa-trash-o" aria-hidden="true"></i>  <?php echo $LANG_LABEL['delete'];?>
                          </button>
                       
                          <button style="margin-right:1px; display: none; color: white;" id="showSecBtn<?php $post['id']?>"type="button" class="showSecBtn showSecModal<?php $post['section']?> btn btn-blacktest" data-id="<?php echo $post['id']; ?>">
                          <?php echo $LANG_LABEL['show'];?>
                          </button>
                          <button style="border-color: #d0cece; margin-right:1px; display: none;" id="hideSecBtn<?php $post['id']?>"type="button" class="hideSecBtn hideSecModal<?php $post['section']?> btn " data-id="<?php echo $post['id']; ?>">
                            <?php echo $LANG_LABEL['hidebt'];?>
                          </button>
                          <?php
                      }
                      ?>
                      
                      <button type="button" class="btn btn-success margin-r-10 edit-content kt:btn-warning" data-id="<?php echo $post['id']; ?>" data-type="edit"  style="padding: 8px 40px;">
                       <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];?>
                     </button>
                     <?php
                    
                    }else {
                  ?>                    
                    <button type="button" class="btn btn-primary margin-r-10 edit-content" data-id="<?php echo $post['id']; ?>" data-type="add" >
                      <i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];?>
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



<?php
  include 'addblog.php';
  include 'editblog.php';
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
<script src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/blog/blog.js?v=<?php echo date('s');?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/blog/addblog.js?v=<?php echo date('s');?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/blog/editblog.js?v=<?php echo date('s');?>"></script>
<!-- <script src="<?php echo SITE_URL; ?>js/pages/contents/hidebtn.js"> -->
</script>

<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> -->
