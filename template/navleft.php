<?php
/*
 * @$LANG_LABEL  ตัวแปรภาษาที่ประกาศไว้ที่หน้า index
 *
 */
?>
<aside class="main-sidebar">
  <section class="sidebar">
    <a href="<?php echo ROOT_URL; ?>" target="_blank">
      <div class="user-panel" style="background-color: #2a4054;text-align:center">
        <img src="<?php echo ROOT_URL . getData::get_website_logo(); ?>" alt="" >
      </div>
    </a>
    <ul class="sidebar-menu" data-widget="tree">
        <li id="dashboard">
          <a href="<?php echo SITE_URL; ?>"><i class="fas fa-tachometer-alt"> </i> <span> <?php echo $LANG_LABEL['dashboard']; ?> </span></a>
        </li>
        <li id="slide"><a href="<?php echo SITE_URL; ?>?page=slide"><i class="fas fa-images"> </i><span> <?php echo $LANG_LABEL['banner']; ?></span></a></li> 
        <li id="category">
          <a href="<?php echo SITE_URL; ?>?page=category"><i class="fa fa-sitemap"></i> <span> <?php echo $LANG_LABEL['categories']; ?> , แถบเมนู</span></a>
        </li> 
        <li id="contents"><a href="<?php echo SITE_URL; ?>?page=contents"><i class="fas fa-newspaper"></i> <span><?php echo $LANG_LABEL['content']; ?></span></a></li>
        <!-- <li id="transmit"><a href="<?php echo SITE_URL; ?>?page=transmit"><i class="fas fa-envelope"> </i><span> แจ้งข่าวสาร</span></a></li> -->

       <hr> 
       <li class="treeview ">
          <a href="#"><i class="fas fa-phone-square" aria-hidden="true"> </i> <span>สินค้า</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu manage_products" style="display: <?=($_GET['page'] =="manage_products" || $_GET['page'] =="product_sel")?"block":"none"?>;">
            <li id="manage_products"><a href="<?php echo SITE_URL; ?>?page=manage_products&subpage=product_cate"><i class="fas fa-circle"></i> <span>หมวดหมู่สินค้า</span></a></li>
          </ul>
        </li>
       <li class="treeview ">
          <a href="#"><i class="fas fa-yin-yang" aria-hidden="true"> </i> <span>ตั้งค่าการทำนาย</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu predicts" style="display: <?=($_GET['page'] =="predicts" || $_GET['page'] =="product_sel")?"block":"none"?>;">
            <li id="numberpredict"><a href="<?php echo SITE_URL; ?>?page=predicts&subpage=numberpredict"><i class="fas fa-circle"></i> <span>ค้นหาเบอร์จากความหมาย</span></a></li>
            <li id="grade"><a href="<?php echo SITE_URL; ?>?page=predicts&subpage=grade"><i class="fas fa-circle"></i> <span>ข้อมูลเกรด</span></a></li>
            <li id="prophecy"><a href="<?php echo SITE_URL; ?>?page=predicts&subpage=prophecy"><i class="fas fa-circle"></i> <span>ทำนายเบอร์</span></a></li>
            <li id="predictsum"><a href="<?php echo SITE_URL; ?>?page=predicts&subpage=predictsum"><i class="fas fa-circle"></i> <span>ผลรวมเบอร์</span></a></li>
          </ul>
        </li>

       <li id="purchaseOrderData" class="<?=$color_tab?>"><a href="<?php echo SITE_URL; ?>?page=purchaseOrderData">
         <span> <i class="fa fa-bell" aria-hidden="true"> </i> บันทึกการสั่งซื้อ </span>
         <span class="notify_number"><?=$_SESSION['nav_payments_notify']?></span> </a>
        </li>

        <li id="order_carrier"><a href="<?php echo SITE_URL; ?>?page=order_carrier"><i class="far fa-paper-plane" aria-hidden="true"></i>  <span> บันทึกการจัดส่ง </span></a></li>
        <li id="reviews"><a href="<?php echo SITE_URL; ?>?page=reviews"><i class="fas fa-comments" aria-hidden="true"></i>  <span> รีวิวสินค้า </span></a></li>
 
        <li id="contentWeb"><a href="<?php echo SITE_URL; ?>?page=contentWeb"><i class="fa fa-info fa-lg" style="padding: 3px;"aria-hidden="true"></i>  <span>เนื้อหาเว็บไซต์</span></a></li>
        <!-- <li class="treeview" >
          <a href="#"><i class="fa fa-globe" aria-hidden="true"></i> <span>ติดต่อเรา</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i> 
            </span>
          </a>
          <ul class="treeview-menu contact_sel" style="display: <?=($_GET['page'] =="contact_sel")?"block":"none"?>;">
          </ul>
        </li>  -->
    
        <li id="contact_sel"><a href="<?php echo SITE_URL; ?>?page=contact_sel"><i class="fa fa-globe"></i> <span>ข้อมูลเว็บไซต์</span></a></li>
        <hr >
        <?php
        if ($_SESSION['leave_a_msg'] === 'yes') {
            ?>
                <li id="contact"><a href="<?php echo SITE_URL; ?>?page=contact"><i class="fa fa-inbox"></i><span> ข้อความติดต่อ </span></a></li>
                <?php
          }
        ?>
        <li class="treeview" >
          <a href="#"><i class="fas fa-landmark"></i> <span>บัญชีธนาคาร</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu bank" style="display: <?=($_GET['page'] =="manage_bank")?"block":"none"?>;">
            <li id="manage_bank"><a href="<?php echo SITE_URL; ?>?page=manage_bank"><i class="fas fa-circle"></i> <span>บัญชีธนาคาร</span></a></li>
          </ul>
        </li>
        <!-- <li id="subscribers"><a href="<?php echo SITE_URL; ?>?page=subscribers"><i class="fa fa-envelope"></i> <span><?php echo $LANG_LABEL['maillist']; ?></span></a></li>  -->

      <?php
        if ($_SESSION['role'] === 'superadmin' || $_SESSION['role'] === 'admin') {
      ?>
              <li id="admin"><a href="<?php echo SITE_URL; ?>?page=admin"><i class="fa fa-user-secret"></i> <span><?php echo $LANG_LABEL['admin']; ?></span></a></li>
      <?php
        }
      ?>
 

        <!-- <hr> -->
        <?php
      if ($_SESSION['role'] === 'superadmin') {
          ?>
                <li id="langconfig"><a href="<?php echo SITE_URL; ?>?page=langconfig"><i class="fa fa-language"></i> <span><?php echo $LANG_LABEL['langconfig']; ?></span></a></li>
              <?php
      }
      ?>

         <!-- <li id="map"><a href="<?php echo SITE_URL; ?>?page=map"><i class="fa fa-map"></i> <span><?php echo $LANG_LABEL['map']; ?></span></a></li> -->
 
      <?php
        if ($_SESSION['role'] === 'superadmin' || $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor') {
            ?>
                <!-- <li id="siteconfig"><a href="<?php echo SITE_URL; ?>?page=siteconfig"><i class="fa fa-globe"></i> <span><?php echo $LANG_LABEL['siteconfig']; ?></span></a></li> -->
              <?php
        }
        ?>

      <?php
if ($_SESSION['role'] === 'superadmin') {
    ?>
        <li id="setting"><a href="<?php echo SITE_URL; ?>?page=setting"><i class="fa fa-cogs"></i> <span><?php echo $LANG_LABEL['settingsystem']; ?></span></a></li>
      <?php
}
?>

      <li id=""><a href="<?=$site_URL?>images/berhoromanual.pdf" target="__blank"><i class="fa fa-book"></i> <span><?php echo $LANG_LABEL['manual']; ?></span></span></a></li></li>
    </ul>
 

  </section>


</aside>