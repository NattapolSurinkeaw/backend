<?php
/* @$LANG_LABEL  ตัวแปรภาษาที่ประกาศไว้ที่หน้า index*/
?>
  <header class="main-header">
    <a href="<?php echo SITE_URL; ?>" class="logo">
      <span class="logo-mini"><img src="images/Logo-Wynnsoft-Management.png" style="width: 30px;"></span>
      <span class="logo-img">
        <img src="<?php echo SITE_URL; ?>images/Logo-Wynnsoft-Management.png">
      </span>
      <span class="logo-lg">Wynnsoft Management</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <i class="fas fa-bars"></i>
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <?php
      if ($_SESSION['multi_lingual'] === 'yes') {
      ?>
        <div class="btn-group">
          <div class="btn btn-custom navbar-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?php echo $_SESSION['backend_language']; ?> <b class="caret"></b>
          </div>
          <ul class="dropdown-menu custom-menu dropdown-menu-xs">
          <?php
          $av_lan = getData::get_language_array();
          foreach ($av_lan as $value){
          ?>
            <li class="select-language" data-language="<?php echo $value; ?>"><span><?php echo $value; ?></span></li>
          <?php
          }
          ?>
          </ul>
        </div>
      <?php
      }
      ?>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <?php 
            $author = getData::get_author($_SESSION['user_id']); 
            if ($author['image'] == '') {
              $author_image = SITE_URL.'images/default-user-image.png';
            }else {
              $author_image = ROOT_URL.$author['image'];
            }
          ?>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.$author_image.'&size=25x25'; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $author['display_name'] ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-account" style="">
              <li class="user-header" style="border-radius: 10px 10px 0 0">
                <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.$author_image.'&size=90x90'; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo $author['display_name'].' - '.$LANG_LABEL[$_SESSION['role']] ?>
                  <small><?php echo $author['email'] ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo SITE_URL; ?>?page=profile" style="" class="btn btn-default btn-flat kt:btn-success">
                  <i class="fa fa-user"></i>
                  <?php echo $LANG_LABEL['txtprofile'];//Profile?>
                </a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat kt:btn-danger" style="" href="<?php echo SITE_URL."?logout=yes"; ?>">
                  <i class="fa fa-sign-out"></i>
                  <?php echo $LANG_LABEL['logout'];//Sign out?>
                </a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>