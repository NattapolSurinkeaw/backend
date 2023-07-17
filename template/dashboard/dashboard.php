  <?php
    $getpost['amount'] = 5;
    //$rooms = $mydata->get_rooms($getpost);
    $contents = $mydata->get_post($getpost);
    $advertise = $mydata->get_advertise(10);
    $profile = $mydata->get_profile($_SESSION['user_id']);
    $contact = $mydata->get_laeve_msg();
    $maillist = $mydata->get_maillist($getpost);

  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper dashboard-box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $LANG_LABEL['dashboard'];?>
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['dashboard'];?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">

          <!--div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">ห้องพัก</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
              <?php
                if ($rooms != false) {
              ?>
              <ul class="products-list product-list-in-box">
                <?php
                foreach($rooms as $a) {
                ?>
                  <li class="item">
                    <div class="product-img">
                      <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['thumbnail'].'&size=90x60'; ?>" alt="Content Image">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title"><?= $a['title']; ?></a>
                      <span class="product-description"><?= $a['description']; ?></span>
                    </div>
                  </li>
                <?php
                }
                ?>
              </ul>
              <?php
                }else {
              ?>
                <table class="table no-margin">
                  <thead>
                    <tr>
                      <td colspan='4'><center><b>No data found in the server</b></center></td>
                    </tr>
                  <thead>
                </table>
              <?php
                }
              ?>
            </div>

            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=rooms" class="uppercase"><?= $viewall ?></a>
            </div>

          </div-->

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $LANG_LABEL['recentlycontents'];?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body">
              <?php
                if ($contents != false) {
              ?>
              <ul class="products-list product-list-in-box">
                <?php
                foreach($contents as $a) {
                ?>
                  <li class="item">
                    <div class="product-img">
                      <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['thumbnail'].'&size=90x60'; ?>" alt="Content Image">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title"><?php echo $a['title']; ?></a>
                      <span class="product-description"><?php echo substr($mydata->getcontentcate($a['category']),0,-2); ?></span>
                    </div>
                  </li>
                <?php
                }
                ?>
              </ul>
              <?php
                }else {
              ?>
                <table class="table no-margin">
                  <thead>
                    <tr>
                      <td colspan='4'><center><b>No data found in the server</b></center></td>
                    </tr>
                  <thead>
                </table>
              <?php
                }
              ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=contents" class="uppercase"><?php echo $LANG_LABEL['viewall'];?></a>
            </div>
            <!-- /.box-footer -->
          </div>
 
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $LANG_LABEL['banner'];?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php
                foreach ($advertise as $key => $a) {
                ?>
                  <li class="item">
                    <div class="product-img">
                      <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['ad_image'].'&size=90x60'; ?>" alt="Content Image">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title"><?php echo $a['ad_title']; ?></a>
                      <span class="product-description">position : <?php echo $a['ad_position']; ?></span>
                    </div>
                  </li>
                <?php
                }
                ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=slide" class="uppercase"><?php echo $LANG_LABEL['viewall'];?></a>
            </div>
            <!-- /.box-footer -->
          </div>

        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $LANG_LABEL['txtprofile'];?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body box-profile">
              <?php
                if ($profile['image'] == '') {
                  $profile_image = SITE_URL.'images/default-user-image.png';
                }else {
                  $profile_image = ROOT_URL.$profile['image'];
                }
              ?>
                <img class="profile-user-img img-responsive img-circle" src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.$profile_image.'&size=128x128'; ?>" alt="User profile picture">
              <h3 class="profile-username text-center"><?php echo $profile['display_name'].' - '.$LANG_LABEL['role']; ?></h3>
              <p class="text-muted text-center"><?php echo $profile['email'] ?></p>
              <hr>
              <strong>Name</strong>
              <p class="text-muted">
                <?php echo $profile['username']; ?>
              </p>
              <hr>
              <strong>Display Name</strong>
              <p class="text-muted">
                <?php echo $profile['display_name']; ?>
              </p>
              <hr>
              <strong>E-Mail</strong>
              <p class="text-muted">
                <?php echo $profile['email']; ?>
              </p>
              <hr>
              <strong>Tel.</strong>
              <p class="text-muted">
                <?php echo $profile['phone']; ?>
              </p>
              <hr>
              <strong>Language List</strong>
              <p class="text-muted">
                <?php echo $profile['language']; ?>
              </p>
              <!-- <a href="#" class="btn btn-primary btn-block"><b>แก้ไข</b></a> -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=profile" class="uppercase"><?php echo $LANG_LABEL['edit'];?></a>
            </div>
          </div>


          <?php /*
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">ติดต่อเรา</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              <?php
                  if ($contact != false) {
              ?>
                <table class="table no-margin table-hover main-blog-message">
                  <tbody>
                  <?php
                    foreach ($contact as $key => $value) {
                  ?>
                    <tr>
                      <td><a href="#"><?php echo $value['fullname']; ?></a></td>
                      <td><div class="blog-message"><b><?php echo $value['topic']; ?></b>
                          <span> - <?php echo $value['message']; ?></span></div></td>
                    </tr>
                  <?php
                    }
                  ?>
                  </tbody>
                </table>
              <?php
                }else {
              ?>
                <table class="table no-margin">
                  <thead>
                    <tr>
                      <td colspan='4'><center><b>No data found in the server</b></center></td>
                    </tr>
                  <thead>
                </table>
              <?php
                }
              ?>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=contact" class="uppercase"><?= $viewall ?></a>
            </div>
            <!-- /.box-footer -->
          </div>   
          */?>

          <!-- solid sales graph -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $LANG_LABEL['maillist'];?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <?php
                  if ($maillist != false) {
                ?>
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th><?php echo $LANG_LABEL['txtemail'];?></th>
                    <th>วันที่ลงทะเบียน</th>
                    <th><?php echo $LANG_LABEL['language'];?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach ($maillist as $key => $value) {
                  ?>
                    <tr>
                      <td><?php echo $value['e_mail']; ?></td>
                      <td><?php echo date_format(date_create($value["date_regist"]),"d/m/Y - H:i"); ?></td>
                      <td><?php echo $value['language']; ?></td>
                    </tr>
                  <?php
                    }
                  ?>
                  </tbody>
                </table>
                <?php
                  }else {
                ?>
                  <table class="table no-margin">
                    <thead>
                      <tr>
                        <td colspan='4'><center><b>No data found in the server</b></center></td>
                      </tr>
                    <thead>
                  </table>
                <?php
                  }
                ?>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo SITE_URL; ?>?page=subscribers" class="uppercase"><?php echo $LANG_LABEL['viewall'];?></a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->

        </section>
      </div>

    </section>
  </div>
