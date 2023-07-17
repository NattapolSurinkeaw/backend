<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/custom.css">

<?php
 
  //รายละเอียดเว็บไซต์ดึงจากตาราง category โดยใช้ cate_id =1
  $website_detail = $mydata->get_website_detail(); 
  
  //ดึงข้อมูลประเภทของข้อมูลเว็บไซต์ที่ตั้งค่าเช่น อีเมล , บัญชีธนาคาร
  $webinfotype = $mydata->get_web_info_type();

  //มีการเลือกแทบตั้งค่าเว็บไซต์ เช่น อีเมล , บัญชีธนาคาร
  if ($_REQUEST['type']) {
    //ดึงรายการข้อมูลที่มีการเพิ่มเอาไว้โดยใช้ประเภท
    $content = $mydata->get_web_info($_REQUEST['type']);
    //
    $info_type_title = $mydata->get_web_info_type_by_field('info_type',$_REQUEST['type']); 
  }
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-globe"></i> <?php echo $LANG_LABEL['siteconfig'];//จัดการเว็บไซต์?>
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];//หน้าหลัก?></a></li>
      <li class="active"><?php echo $LANG_LABEL['siteconfig'];//จัดการเว็บไซต์?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-3">

        <div class="box box-solid">
          <div class="box-header with-border" style="background-color: #1e9dea;border-radius: 4px 4px 0 0;border-bottom: 3px solid #187fbd;">
            <h3 class="box-title"></h3>

            <div class="box-tools">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" style="color: #fff;"></i>
              </button>
            </div>
          </div>
          <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
              <li class="siteconfig-menu" id="websiteconfig" data-id="page=siteconfig">
                <a>
                  <i class="fa fa-circle-o"></i> <?php echo $LANG_LABEL['detail'];//รายละเอียดเว็บไซต์?>
                </a>
              </li>
              <?php
               //แสดงเมนูทางซ้าย รายการตั้งค่าเมนูที่สามารถเพิ่มได้
               
                foreach ($webinfotype as $a) {
              ?>
                <li class="siteconfig-menu" id="<?php echo $a['info_type']; ?>" data-id="page=siteconfig&type=<?php echo $a['info_type']; ?>">
                  <a>
                    <i class="fa fa-circle-o"></i> <?php echo $a['info_title']; ?>   
                  </a>
                </li>
              <?php
                }
                 
              ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-9">

        <div class="box box-primary" id="siteconfig-box">
          <div class="box-header with-border">
            <h3 class="box-title cate-title"><i class="fa fa-list-alt"></i> <?php echo $LANG_LABEL['detail'];//รายละเอียดเว็บไซต์?></h3>
          </div>
          
          <div class="box-body no-padding">

            <div class="box-body box-category">
              <div class="form-horizontal">
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label"><?php echo $LANG_LABEL['uploadimage'];//อัพโหลดรูปภาพ?></label>
                  <div class="col-md-10">
                    <div class="form-add-images">
                      <div id="image-preview">
                        <div class="blog-preview-edit">      
                          <div class="col-img-preview">        
                            <img class="preview-img" src="<?php echo ROOT_URL.$website_detail['thumbnail']; ?>">
                          </div>
                        </div>
                        <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-website-images" data-preview="blog-preview-edit" data-type="edit" />
                      </div>
                      <span class="help-block edit-images-error">Please select images file!</span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label"><?php echo $LANG_LABEL['title'];//หัวเรื่อง?></label>

                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="title" placeholder="Title" value="<?php echo $website_detail['title']; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label"><?php echo $LANG_LABEL['keyword'];//คำสำคัญ?></label>

                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="keyword" placeholder="Keyword" value="<?php echo $website_detail['keyword']; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label"><?php echo $LANG_LABEL['description'];//คำอธิบาย?></label>

                  <div class="col-sm-10">
                    <textarea type="text" rows="5" id="description" placeholder="Description" class="form-control"><?php echo $website_detail['description']; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputSkills" class="col-sm-2 control-label"><?php echo $LANG_LABEL['language'];//ภาษา?></label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Language" value="<?php echo $language_fullname['display_name']; ?>" disabled>
                    <input type="hidden" class="form-control" id="language" value="<?php echo $_SESSION['backend_language']; ?>" disabled>
                  </div>
                </div>
              </div>

              <?php
                if(strpos($website_detail['lang_info'],$_SESSION['backend_language']) > -1 ){
              ?>
                  <input type="hidden" id="edit-website-images-hidden">
                  <input type="hidden" id="data-type" value="edit">
              <?php
                }else {
              ?>
                  <input type="hidden" id="edit-website-images-hidden" value="<?php echo $website_detail['thumbnail']; ?>">
                  <input type="hidden" id="data-type" value="add">
              <?php
                }
              ?>
              <input type="hidden" id="data-id" value="<?php echo $website_detail['cate_id']; ?>">
            </div> 

            <div class="box-footer clearfix category-footer">
              <button type="submit" class="btn btn-default pull-left" id="reset-website-detail">
                <i class="fa fa-repeat" aria-hidden="true"></i> <?php echo $LANG_LABEL['clear'];//คืนค่า?>
              </button>
              <button type="submit" class="btn btn-success pull-right" id="save-website-detail">
                <i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?>
              </button>
            </div> 
          </div>
        </div>

<!------------------------------------------------------------------------------------------------>

        <div class="box box-primary" id="webinfo-box">

          <div class="box-header with-border">
            <div class="box-header ui-sortable-handle">
              <h3 class="box-title"><?php echo $info_type_title['info_title']; ?></h3>
              <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="">
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-web-info-add"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];//เพิ่มข้อมูล?></button>
              </div>
            </div>
          </div>
          
          <div class="box-body no-padding">
            <div class="categorybox-controls category-box">

            </div>

            <div class="box-body box-category table-responsive">
              <?php
                if (!empty($content)) {
              ?>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10">#</th>
                    <th><?php echo $LANG_LABEL['title'];//หัวเรื่อง?></th>
                    <th><?php echo $LANG_LABEL['message'];//ข้อความ?></th>
                    <th width="85"><?php echo $LANG_LABEL['display'];//สถานะ?></th>
                    <th width="160">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $j = 0;
                  foreach($content as $a){
                    $j++;
                    if ($a['info_display'] === 'yes') {
                      $status = $LANG_LABEL['show'];//แสดง
                    }else if ($a['info_display'] === 'no') {
                      $status = $LANG_LABEL['hide'];//ซ่อน
                    }
                ?>
                    <tr>
                      <td><?php echo $j; ?></td>
                      <td><?php echo $a['info_title']; ?></td>
                      <td><?php echo $a['text_title']; ?></td>
                      <td><?php echo $status; ?></td>
                      <td>
                      <?php
                        if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
                      ?>
                        <a data-id="<?php echo $a['info_id']; ?>" data-type="edit" data-toggle="modal" data-target="#modal-web-info-edit" id="edit-web-info" class="btn btn-success btn-xs edit" style="margin-right: 7px;">
                          <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไข?>
                        </a> |
                        <a data-id="<?php echo $a['info_id']; ?>" id="web-info-delete" class="btn btn-danger btn-xs delete" style="margin-left: 7px;">
                          <i class="fa fa-trash-o"></i> <?php echo $LANG_LABEL['delete'];//ลบ?>
                        </a>
                      <?php
                        }else {
                      ?>
                        <a data-id="<?php echo $a['info_id']; ?>" data-type="add" data-toggle="modal" data-target="#modal-web-info-edit" class="btn btn-attree btn-xs edit" id="edit-web-info" style="margin-right: 7px;">
                          <i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];//เพิ่ม?>
                        </a> 
                      <?php
                        }
                      ?>
                      </td>
                    </tr>
                <?php
                  }
                ?>
                </tbody>
              </table>
              <?php
                }
              ?>
            </div> 

          </div>
        </div>
<!------------------------------------------------------------------------------------------------>

      </div>
    </div>
  </section>
</div>

<!-- Add -->
<div class="modal fade" id="modal-web-info-add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];//เพื่ม?><?php echo $info_type_title['info_title']; ?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add">
          <div class="box-body">

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['title'];//หัวเรื่อง?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-info-title">
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['description'];//คำอธิบาย?></label>
              <div class="col-sm-10">
                <textarea class="form-control" rows="3" id="add-text-title"></textarea>
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['link'];//ลิงค์?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-info-link">
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['position'];//ตำแหน่ง?></label>
              <div class="col-sm-10">
                <input type="number" class="form-control" id="add-priority" value="0" min="0">
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label">attribute</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-attribute">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['displayonsite'];//แสดงผล?></label>
              <div class="col-sm-10">
                <select class="form-control" id="add-info-display">
                  <option value="yes" id="add-info-display-yes"><?php echo $LANG_LABEL['show'];//แสดง?></option>
                  <option value="no" id="add-info-display-no"><?php echo $LANG_LABEL['hide'];//ซ่อน?></option>
                </select>
              </div>
            </div>

            <input type="hidden" class="form-control" id="add-info-type" value="<?php echo $_REQUEST['type']; ?>">

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default pull-left" id="reset-password"><i class="fa fa-refresh"></i> Reset Password</button> -->
        <button type="button" class="btn btn-success" id="save-add-web-info"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modal-web-info-edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไข?><?php echo $info_type_title['info_title']; ?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-edit">
          <div class="box-body">

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['title'];//หัวเรื่อง?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-info-title">
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['description'];//คำอธิบาย?></label>
              <div class="col-sm-10">
                <textarea class="form-control" rows="3" id="edit-text-title"></textarea>
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['link'];//ลิงค์?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-info-link">
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['position'];//ตำแหน่ง?></label>
              <div class="col-sm-10">
                <input type="number" class="form-control" id="edit-priority" value="0" min="0">
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

            <div class="form-group" id="">
              <label class="col-sm-2 control-label">attribute</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-attribute">
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['displayonsite'];//แสดงผล?></label>
              <div class="col-sm-10">
                <select class="form-control" id="edit-info-display">
                  <option value="yes" id="info-display-yes"><?php echo $LANG_LABEL['show'];//แสดง?></option>
                  <option value="no" id="info-display-no"><?php echo $LANG_LABEL['hide'];//ซ่อน?></option>
                </select>
              </div>
            </div>

            <input type="hidden" class="form-control" id="action-type">
            <input type="hidden" class="form-control" id="edit-info-type">
            <input type="hidden" class="form-control" id="edit-web-info-id">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-edit-web-info"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/siteconfig/siteconfig.js"></script>