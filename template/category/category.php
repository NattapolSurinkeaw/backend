<?php
  $category = $mydata->get_category($getpost);

  $menuId = array(
    'showonmenu' => array(),
    'displayonsite' => array(),
    'hideonsite' => array()
  );
 
  foreach ($category as $key => $value) {
    /* แสดงผลบนแถบเมนู  */
    if($value['display']=='yes' && $value['menu']=='yes' && $value['main_page'] != 'yes'){
      $menuId['showonmenu'][] = $key;
    
     /* แสดงผลบนเว็บไซต์ */
    }else if($value['display']=='yes' && $value['menu']=='no' && $value['main_page']!='yes'){
      $menuId['displayonsite'][] = $key;

     /* ซ่อนจากเว็บไซต์ */
    }else if($value['display']=='no' && $value['main_page'] != 'yes'){
      $menuId['hideonsite'][] = $key;
    }
  }
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-sitemap"></i> <?php echo $LANG_LABEL['categories'];?> 
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i><?php echo $LANG_LABEL['home']?></a></li>
      <li class="active"><?php echo $LANG_LABEL['categories'];?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">

          <div class="box-header with-border">
            <?php
            if (empty($_REQUEST['search'])) {              
              echo '
              <h3 class="box-title cate-title"></h3>';
            }else {
              echo '
              <h3 class="box-title cate-title"><i class="fa fa-search" aria-hidden="true"></i> '.$LANG_LABEL['resultsfor'].' "'.$_REQUEST['search'].'"</h3>';
            }
            ?>
            <div class="box-tools pull-right">
              <div class="has-feedback">
                <input type="text" class="form-control input-sm" id="search-cate" placeholder="<?php echo $LANG_LABEL['searchtext'];?>">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>
          
          <?php
          if (empty($_REQUEST['search'])) {
          ?>
          <div class="box-body no-padding">
            <div class="categorybox-controls category-box">
              <div class="box-header ui-sortable-handle">
                <i class="fa fa-bars"></i>
                <h3 class="box-title"><?php echo $LANG_LABEL['showonmenu'];?></h3>
                <div class="pull-right">
                  <button type="button" class="btn btn-primary kt:btn-info" data-toggle="modal" data-target="#modalAddCategory" style="padding: 10px 40px;"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['addcate'];?></button>
                </div>
              </div>
            </div>
          </div>

          <div class="box-body box-category">
            <?php
            if (!empty($menuId['showonmenu'])) {
              foreach($menuId['showonmenu'] as $id) { 
                  include 'template/category/category-box.php';
              }
            } 
            ?>
            <?php if($_SESSION['role'] === 'superadmin' ){   #category menu admin only  ?>    
               <div class="box-body no-padding">
                 <div class="categorybox-controls category-box">
                   <div class="box-header ui-sortable-handle">
                     <i class="fa fa-eye"></i>
                     <h3 class="box-title"><?php echo $LANG_LABEL['displayonsite'];?></h3>
                   </div>
                 </div>
               </div>
               <div class="box-body box-category">
                 <?php
                 if (!empty($menuId['displayonsite'])) {
                   foreach($menuId['displayonsite'] as $id) {
                       include 'template/category/category-box.php';
                   }
                 }
                 ?>
               </div>

               <div class="box-body no-padding">
                 <div class="categorybox-controls category-box">
                   <div class="box-header ui-sortable-handle">
                     <i class="fa fa-eye-slash"></i>
                     <h3 class="box-title"><?php echo $LANG_LABEL['hiddenfromsite'];?></h3>
                   </div>
                 </div>
               </div>
               <div class="box-body box-category">
                 <?php
                 if (!empty($menuId['hideonsite'])) {
                   foreach($menuId['hideonsite'] as $id) {
                       include 'template/category/category-box.php';
                   }
                 }
                 ?>
               </div>

          <?php    } #category menu admin only
 
          } else {
   
          ?>

          <div class="box-body no-padding">
            <div class="categorybox-controls category-search">
              <span id="count-search">
                <?php echo $LANG_LABEL['allresults']?> <?php echo count($category); ?> <?php echo $LANG_LABEL['record'];?>
              </span>
            </div>
          </div>
          <div class="box-body box-category">
            <?php
            if (!empty($category)) {
              foreach($category as $a) {
                include 'template/category/category-box.php';
              }
            }else {
              echo '
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> '.$LANG_LABEL['nodata'].'
              </div>';
            }
            ?>
          </div> 


          <?php
          }
          ?> 
   <?php    ?>  
        </div>
      </div>
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
     include 'template/category/addcategory.php';
     include 'template/category/editcategory.php';
  ?>
<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/category/category.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/category/addcategory.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/category/editcategory.js"></script>