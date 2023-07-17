  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-user-secret"></i> <?php echo $LANG_LABEL['admin'];//ผู้ดูแลระบบ?>
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];//หน้าหลัก ?></a></li>
        <li class="active"><?php echo $LANG_LABEL['admin'];//ผู้ดูแลระบบ?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-body">
              <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th> รหัสผู้ดูแล </th>
                      <th><?php echo $LANG_LABEL['name'];//ชื่อ?></th>
                      <th><?php echo $LANG_LABEL['email'];//อีเมล?></th>
                      <th><?php echo $LANG_LABEL['role'];//หน้าที่?></th>
                      <th><?php echo $LANG_LABEL['language'];//ภาษา?></th>
                      <th><?php echo $LANG_LABEL['dateregis'];//วันที่ลงทะเบียน?></th>
                      <th><?php echo $LANG_LABEL['matchstatus'];//สถานะ?></th>
                      <th><?php echo $LANG_LABEL['edit'];//แก้ไข?></th>
                      <th><?php echo $LANG_LABEL['delete'];//ลบ?></th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <div class="modal fade" id="modal-admin">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['editdataadmin'];//แก้ไขข้อมูลของผู้ดูแลระบบ?></h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" id="form-edit-user">
            <div class="box-body">

              <div class="form-group" id="edit-display-group">
                <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['name'];//ชื่อ?></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-display-name">
                  <span class="help-block edit-display-error"></span>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['email'];//อีเมล?></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-email">
                  <span class="help-block edit-email-error"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['role'];//หน้าที่?></label> 
                <div class="col-sm-10">
                <?php  
                if ($_SESSION['role'] != 'superadmin') {
                ?>
                  <style>#type-id-1{display: none;}</style>
                <?php
                }
                ?>

                <?php 
                if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                ?>
                  <style>#type-id-3{display: none;}</style>
                <?php
                }
                ?>
                  <select class="form-control" id="edit-user-type">                        
                    <?php
                      echo getData::option_multilingual('user_type','user_type_th','member_type','type-id-','id');
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['matchstatus'];//สถานะ?></label>
                <div class="col-sm-10">
                  <select class="form-control" id="edit-user-status">
                    <?php
                      echo getData::option_multilingual('user_status','status_user_th','status_user','status-id-','id');
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['language'];//ภาษา?></label>
                <div class="col-sm-6">
                  <table class="table table-bordered table-striped table-lang">
                  <?php
                    $lan_arr = getData::get_language();
                    foreach ($lan_arr as $a) {
                  ?>
                      <tr>
                        <td style="width: 10px">
                          <input type="checkbox" name="language" id="lang-<?php echo $a['language']; ?>" value="<?php echo $a['language']; ?>">
                        </td>
                        <td>
                          <?php echo $a['display_name']; ?>
                        </td>
                        <td style="width: 40px">
                          <span class="badge bg-green">
                            <?php echo $a['language']; ?>
                          </span>
                        </td>
                      </tr>
                    <?php
                      }
                    ?>
                  </table>
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-member-id">
              <input type="hidden" class="form-control" id="current-email">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" id="reset-password"><i class="fa fa-refresh"></i> <?php echo $LANG_LABEL['resetpassword'];//Reset Password?></button>
          <button type="button" class="btn btn-primary kt:btn-success" style="padding: 8px 40px" id="save-edit-user"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//Save Changes?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
 
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/admin.js"></script>