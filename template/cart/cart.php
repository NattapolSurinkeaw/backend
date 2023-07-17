<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-user-secret"></i>สมาชิก (Member)
        <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     ?></a></li>
        <li class="active">สมาชิก (Member)</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="col-md-8">
            <div class="box box-primary">
              <div style="display: block; width: 100%; text-align:right;">
              <a class="btn kt:btn-info" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มสมาชิก</a>
              </div>
              <div class="box-body">
                <table id="sales-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                    <thead>
                      <tr>
                        <th>id</th>
                        <th>ประเภท</th>
                        <th>ชื่อ</th>
                        <th>email</th>
                        <th>date regis</th>
                        <th>date update</th>
                        <th>status</th>
                        <th style="width:200px;">จัดการ</th>
                      </tr>
                    </thead>
                </table>
              </div>
            </div>
        </div>

        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12"><label for="">member ID</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-id"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member Type</label></div>
                <div class="col-md-12">
                  <select class="form-control" name="" id="member-type">
                    <option value="gengeral">ทั่วไป</option>
                    <option value="gengeral">โรงพยาบาล/คลีนิค</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member name</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-name"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member name 2</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-name2"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member address</label></div>
                <div class="col-md-12">
                  <textarea class="form-control" name="" id="" cols="30" rows="2"></textarea>
                </div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member phone</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-phone"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member email</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-email"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member sub email</label></div>
                <div class="col-md-12"><input type="text" class="form-control" id="member-email-sub"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member password</label></div>
                <div class="col-md-12"><input type="password" class="form-control" id="member-password"></div>
                <div class="col-md-12" style="margin-top: 5px;"><label for="">member status</label></div>
                <div class="col-md-12">
                  <div class="toggle-switch">
                      <span class="switch"></span>
                  </div>
                </div>
                <div class="col-md-12 text-center" style="margin-top: 20px;">
                <button class="btn kt:btn-success" style="color: white;padding: 8px 40px;"><i class="fa fa-check" aria-hidden="true"></i> เพิ่มหมวดหมู่</button>
                <button class="btn kt:btn-success" style="color: white;padding: 8px 40px;"><i class="fa fa-check" aria-hidden="true"></i> แก้ไขหมวดหมู่</button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/members/members.js"></script>