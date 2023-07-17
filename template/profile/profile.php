  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user"></i> <?php echo $LANG_LABEL['txtprofile'];//ข้อมูลส่วนตัว?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['mainpage'];//หน้าหลัก?></a></li>
        <li class="active"><?php echo $LANG_LABEL['txtprofile'];//ข้อมูลส่วนตัว?></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <?php
      $profile = $mydata->get_profile($_SESSION['user_id']);
      $type = getData::valuefromkey('user_type','user_type','id',$profile['member_type']);

      if ($profile['image'] == '') {
        $preview_hide = 'style="display:none;"';
        $profile_image = SITE_URL.'images/default-user-image.png';
      }else {
        $preview_hide = '';
        $profile_image = ROOT_URL.$profile['image'];
      }
    ?>
      <div class="row">
        <div class="col-md-3">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.$profile_image.'&size=100x100'; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?= $profile['display_name'] ?></h3>
              <p class="text-muted text-center"><?= $type['user_type'] ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['name'];//ชื่อ?></b> <a class="pull-right"><?= $profile['username'] ?></a>
                </li>
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['email'];//อีเมล?></b> <a class="pull-right"><?= $profile['email'] ?></a>
                </li>
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['txtphone'];//เบอร์โทรศัพท์?></b> <a class="pull-right"><?= $profile['phone'] ?></a>
                </li>
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['role'];//หน้าที่?></b> <a class="pull-right"><?= $type['user_type'] ?></a>
                </li>
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['language'];//ภาษา?></b> <a class="pull-right"><?= $profile['language'] ?></a>
                </li>
                <li class="list-group-item">
                  <b><?php echo $LANG_LABEL['dateregis'];//วันที่ลงทะเบียน?></b> <a class="pull-right"><?= date_format(date_create($profile["date_regis"]),"d/m/Y - H:i") ?></a>
                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab"><?php echo $LANG_LABEL['txteditprofile'];//แก้ไขข้อมูลส่วนตัว?></a></li>
              <li><a href="#reset-password" data-toggle="tab"><?php echo $LANG_LABEL['txtchangepassword'];//เปลี่ยนรหัสผ่าน?></a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="settings">
                <div class="form-horizontal">
                  
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Images Profile</label>
                    <div class="col-sm-10">
                      <div id="image-preview">
                        <label for="image-upload" class="image-label">
                          <i class="fa fa-camera"></i>
                        </label>
                        <div class="blog-preview-edit">      
                          <div class="col-img-preview" <?php echo $preview_hide; ?>>        
                            <img class="preview-img" src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.$profile_image.'&size=150x150'; ?>">      
                          </div>
                        </div>
                        <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-content" data-preview="blog-preview-edit" data-type="edit" />
                      </div>
                      <span class="help-block add-images-error">Please select images file!</span>
                      <div class="b-row space-15"></div>   
                    </div>                                         
                  </div>

                  <div class="form-group" id="form-display">
                    <label for="inputName" class="col-sm-2 control-label">Display Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="display-name" value="<?php echo $profile['display_name']; ?>">
                      <span class="help-block" id="display-error">กรุณากรอกข้อมูลในช่องนี้</span>
                    </div>
                  </div>

                  <div class="form-group" id="form-username">
                    <label for="inputName" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="username" value="<?php echo $profile['username']; ?>">
                      <span class="help-block" id="username-error">กรุณากรอกข้อมูลในช่องนี้</span>
                    </div>
                  </div>

                  <div class="form-group" id="form-email">
                    <label for="inputEmail" class="col-sm-2 control-label">E-Mail</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="email" value="<?php echo $profile['email'] ?>">
                      <input type="hidden" id="current-email" value="<?php echo $profile['email'] ?>">
                      <span class="help-block" id="email-error">กรุณากรอกข้อมูลในช่องนี้</span>
                    </div>
                  </div>

                  <div class="form-group" id="form-phone">
                    <label for="inputName" class="col-sm-2 control-label">Tel.</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone" value="<?php echo $profile['phone']; ?>">
                      <span class="help-block" id="phone-error">กรุณากรอกข้อมูลในช่องนี้</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Language List</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="" value="<?php echo $profile['language']; ?>" readonly>
                    </div>
                  </div>


                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Language Interface</label>
                    <div class="col-sm-10">
                    <select class="form-control" id="language_templete" name="language_templete" style="width: 110px; display: inline-block; margin: 0 10px 5px 5px;">
                      <?php
                        echo getData::option('language','display_name','','lang-templete','language', $profile['language_templete']);//option($table, $column, $op_name, $op_id, $key, $values)
                      ?>
                    </select>
                     
                    </div>
                  </div>

                  <div class="box-footer">
                    <button class="btn btn-success pull-right" id="save-edit-profile">
                      <i class="fa fa-floppy-o"></i> บันทึก
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="reset-password">
                <div class="form-horizontal">
                  <div class="form-group" id="form-currentPass-error">
                    <label for="inputName" class="col-sm-2 control-label">Current Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="current-password" placeholder="Current Password">
                      <span class="help-block" id="currentPassword-error"></span>
                    </div>
                  </div>
                  <div class="form-group" id="form-password-error">
                    <label for="inputName" class="col-sm-2 control-label">New Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="new-password" placeholder="New Password">
                      <span class="help-block" id="password-error"></span>
                    </div>
                  </div>
                  <div class="form-group" id="form-confirmPass-error">
                    <label for="inputEmail" class="col-sm-2 control-label">Confirm Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password">
                      <span class="help-block" id="confirmPassword-error"></span>
                    </div>
                  </div>
                  <div class="box-footer">
                    <button class="btn btn-success pull-right" id="change-password-save">
                      <i class="fa fa-floppy-o"></i> บันทึก
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <input type="hidden" id="user-id" value="<?php echo $profile['member_id']; ?>">
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/profile/profile.js?v=1"></script>