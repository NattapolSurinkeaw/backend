<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-cogs"></i> <?php echo $LANG_LABEL['settingsystem'];?>
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['mainpage'];//หน้าหลัก?></a></li>
      <li class="active"><?php echo $LANG_LABEL['settingsystem']; //ตั้งค่าระบบ ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <?php 
        if ($_SESSION['role'] === 'superadmin') {
      ?>
        <div class="col-md-7">
          <?php
            include 'template/setting/feature.php';
            // include 'template/setting/web_info_type.php';
          ?>
        </div>

        <div class="col-md-5">
          <?php
            include 'template/setting/lang_config.php';
            include 'template/setting/ads_type.php';
            include 'template/setting/web_info_type.php';
          ?>
        </div>
      <?php
        }
      ?>
    </div>
  </section>
</div>

<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/custom.css">

<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/setting/setting.js?v=1"></script>