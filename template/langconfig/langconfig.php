  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-language"></i> <?php echo $LANG_LABEL['langconfig'];//ตั้งค่าภาษา?> 
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['mainpage'];//หน้าหลัก?></a></li>
        <li class="active"><?php echo $LANG_LABEL['langconfig'];//ตั้งค่าภาษา?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <?php
            if ($_SESSION['role'] === 'superadmin') {
            ?>
            <div class="box-header with-border">
              <h3 class="box-title" id="text-title"></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#" data-toggle="modal" data-target="#modal-language" class="export"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['adddata'];//Add Data?></a></li>
                    </ul>
                </div>
              </div>
            </div>
            <?php
            }
            ?>

            <div class="box-body">
              <table id="langconfig-table" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th>PARAMETER</th>
                      <th>DEFAULT</th>
                      <!--th>ภาษาไทย</th>
                      <th>ENGLISH</th-->
                      <?php
                      $sendto_js_av_lang = 0;
                      $av_lang_session = explode(",", $_SESSION['available_language']);
                      foreach ($av_lang_session as $key) {
                        if( $key != '' ) { $sendto_js_av_lang++;
                          ?>
                          <th class= ""><?php echo $key?></th>
                          <?php
                        }
                      }
                      ?>
                      <th style="text-align: center;">Action</th>
                    </tr>
                  </thead>
              </table>
              <input type="hidden" class="sendto_js_av_lang" value = "<?php echo $sendto_js_av_lang?>">
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>


  <div class="modal fade" id="modal-language">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มข้อมูลภาษา</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" id="form-edit-user">
            <div class="box-body">

              <div class="form-group" id="edit-display-group">
                <label class="col-sm-2 control-label">PARAMETER</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="add-parameter">
                  <span class="help-block edit-display-error"></span>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">DEFAULT</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="add-default">
                  <span class="help-block edit-email-error"></span>
                </div>
              </div>

              <?php
              $sendto_js_av_lang = 0;
              $av_lang_session = explode(",", $_SESSION['available_language']);
              foreach ($av_lang_session as $key) {
                if( $key != '' ) { $sendto_js_av_lang++;
                  ?>
                  <div class="form-group" id="edit-email-group">
                    <label class="col-sm-2 control-label"><?php echo $key?></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control add_lang" id="add-<?php echo $key?>" data-type="<?php echo $key?>">
                      <span class="help-block edit-email-error"></span>
                    </div>
                  </div>
                  <?php
                }
              }
              ?> 
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="add-lang"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//Save?></button>
        </div>
      </div>
    </div>
  </div>

  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <!-- script -->
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/langconfig/langconfig.js?v=3.2"></script>