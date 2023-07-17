  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-envelope"></i>  <?php echo $LANG_LABEL['maillist'];?>
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];//หน้าหลัก?></a></li>
        <li class="active"> <?php echo $LANG_LABEL['maillist'];//อีเมลรับข่าวสาร?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-body">
              <table id="subscribers-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th><?php echo $LANG_LABEL['email'];//อีเมล?></th>
                      <th><?php echo $LANG_LABEL['dateregis'];//วันที่ลงทะเบียน?></th>
                      <th><?php echo $LANG_LABEL['status'];//สถานะ?></th>
                      <th><?php echo $LANG_LABEL['language'];//ภาษา?></th>
                      <th>action</th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/iCheck/flat/flat.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <!-- script -->
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/iCheck/icheck.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/js-subscribers/subscribers.js?v=1.5"></script>