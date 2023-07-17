    <?php
   $getpost['amount'] = 20;
   $msg = $mydata->get_laeve_msg($getpost);

  ?>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/iCheck/flat/flat.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/custom.css">

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-inbox"></i> <?php echo  $LANG_LABEL['contactus'];//ติดต่อเรา?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];//Home?></a></li>
        <li class="active"><?php echo  $LANG_LABEL['contactus'];//ติดต่อเรา?> </li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border" style="background-color: #464849;border-radius: 4px 4px 0 0;border-bottom: 3px solid #000000;">
              <h3 class="box-title" style="color:white;">กล่องข้อความ</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" style="color: #fff;"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <ul class="nav nav-pills nav-stacked">

                <li class="mail-box-menu active" id="inbox" data-pagi="<?php echo $getpost['pagi']; ?>" data-amount="<?php echo $getpost['amount']; ?>" data-sortby="<?php echo $getpost['sortby']; ?>" data-search="<?php echo $getpost['search']; ?>"><a><i class="fa fa-inbox"></i> <?php echo $LANG_LABEL['mailbox'];?>
                  <?php
                    if ($new_msg != false) {
                  ?>
                      <span class="label label-warning pull-right"><?php echo count($new_msg); ?></span>
                  <?php
                    }else {
                  ?>
                      <span class="label label-warning pull-right" style="display: none"></span>
                  <?php
                    }
                  ?>
                  </a>
                </li>

                <li class="mail-box-menu" id="mail-box-star"><a><i class="fas fa-star"></i> <?php echo $LANG_LABEL['favorite']; ?></a></li>

                  <?php
                    if ($new_msg != false) {
                  ?>
                      <span class="label label-warning pull-right"><?php echo count($new_msg); ?></span>
                  <?php
                    }else {
                  ?>
                      <span class="label label-warning pull-right" style="display: none"></span>
                  <?php
                    }
                  ?>
                  </a>
                </li>

                <!-- <li class="mail-box-menu" id="mail-box-trash"><a><i class="fa fa-trash-o"></i> ถังขยะ</a></li> -->

              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->

        <div class="col-md-9" id="mail-box">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">ข้อความติดต่อ</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="<?= strtoupper($lang_config[searchtext]) ?>">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <!-- Check all button -->
                <!-- <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button> -->
                <div class="btn-group">
                  <!-- <button type="button" class="btn btn-default btn-sm" ><i class="fa fa-trash-o"></i></button> -->
                  <!-- <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button> -->
                </div>
                <!-- /.btn-group -->
                <!-- <button type="button" id="refresh-data" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button> -->
                <div class="col-sm-12" style="text-align: right;">
                  <span id="page-number"></span>
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm" id="prev-page"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm" id="next-page"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <input type="hidden" id="page" value="1">
                  <input type="hidden" id="msg-count">
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <?php
                  if (!empty($msg)) {
                ?>
                <table class="table table-hover">
                  <tbody id="messages-box">

                    <?php
                      foreach ($msg as $a) {
                        if (date_format(date_create($a["submit_date"]),"Y-m-d") == date('Y-m-d')) {
                          $time = date_format(date_create($a["submit_date"]),"H:i");
                        }else {
                          $time = date_format(date_create($a["submit_date"]),"d/m/Y");
                        }

                        $time_in_read_box = date_format(date_create($a["submit_date"]),"d/m/Y - H:i");

                        if ($a['favorite'] == 'yes') {
                          $star = 'fas fa-star';
                        }else if ($a['favorite'] == 'no') {
                          $star = 'far fa-star';
                        }

                        if ($a['status']=='new') {
                    ?>
                          <tr class="new" data-id="<?php echo $a['id']; ?>" data-name="<?php echo $a['prefix'].$a['name'].' '.$a['lastname']; ?>" data-email="<?php echo $a['email']; ?>" data-phone="<?php echo $a['phone']; ?>" data-topic="<?php echo $a['topic']; ?>" data-message="<?php echo $a['message']; ?>" data-time="<?php echo $time_in_read_box; ?>" data-status="<?php echo $a['status']; ?>">

                            <td class="check-box"><input type="checkbox"></td>
                            <td class="mailbox-star" data-id="<?php echo $a['id']; ?>"><a href="#"><i class=" <?php echo $star; ?> text-yellow"></i></a></td>
                            <td class="mailbox-name"><b><a><?php echo $a['prefix'].$a['name'].' '.$a['lastname']; ?></a></b></td>

                            <td class="mailbox-subject">
                              <div>
                                <b><?php echo $a['topic']; ?></b>
                                <span class="blog-message"> - <?php echo $a['message']; ?></span>
                              </div>
                            </td>

                            <td class="mailbox-date"><b><?php echo $time; ?></b></td>

                          </tr>
                    <?php
                        }else {
                    ?>
                          <tr class="read" data-id="<?php echo $a['id']; ?>" data-name="<?php echo $a['prefix'].$a['name'].' '.$a['lastname']; ?>" data-email="<?php echo $a['email']; ?>" data-phone="<?php echo $a['phone']; ?>" data-topic="<?php echo $a['topic']; ?>" data-message="<?php echo $a['message']; ?>" data-time="<?php echo $time_in_read_box; ?>" data-status="<?php echo $a['status']; ?>">
                            <td class="check-box"><input type="checkbox"></td>
                            <td class="mailbox-star" data-id="<?php echo $a['id']; ?>"><a href="#"><i class=" <?php echo $star; ?> text-yellow"></i></a></td>
                            <td class="mailbox-name"><a><?php echo $a['prefix'].$a['name'].' '.$a['lastname']; ?></a></td>
                            <td class="mailbox-subject">
                              <div>
                                <span><?php echo $a['topic']; ?></span>
                                <span class="blog-message"> - <?php echo $a['message']; ?></span>
                              </div>
                            </td>
                            <td class="mailbox-date"><?php echo $time; ?></td>
                          </tr>
                    <?php
                        }
                      }
                    ?>

                  </tbody>
                </table>
                <!-- /.table -->
                <?php
                  }
                ?>
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer"></div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->


        <div class="col-md-9" id="read-mail">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title mailbox-read-title"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h5></h5>
              </div>
              <!-- /.mailbox-read-info -->

              <div class="mailbox-read-message">
                <p></p>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right"></div>
              <button type="button" class="btn btn-default" id="delete-contact" data-id=""><i class="fa fa-trash-o"></i> <?php echo $LANG_LABEL['delete'];?></button>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->

      </div>
    </section>
  </div>

  <!-- script -->
  <script src="<?php echo SITE_URL; ?>plugins/iCheck/icheck.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/js-contact/contact.js?v=1.1"></script>